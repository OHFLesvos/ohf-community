<?php

namespace App\Http\Controllers\People;

use App\Exports\People\PeopleExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\People\StorePerson;
use App\Http\Requests\UploadSpreadsheet;
use App\Imports\People\PeopleImport;
use App\Models\Bank\CouponHandout;
use App\Models\People\Person;
use Carbon\Carbon;
use Countries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Validator;

class PeopleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Person::class);
    }

    public function index()
    {
        return view('people.index');
    }

    public function create()
    {
        $countries = Countries::getList('en');
        return view('people.create', [
            'countries' => $countries,
        ]);
    }

    public function store(StorePerson $request)
    {
        $person = new Person();
        $person->fill($request->all());
        $person->save();

        return redirect()
            ->route('people.index')
            ->with('success', __('people.person_added'));
    }

    public function show(Person $person)
    {
        return view('people.show', [
            'person' => $person,
        ]);
    }

    public function edit(Person $person)
    {
        return view('people.edit', [
            'person' => $person,
            'countries' => Countries::getList('en'),
        ]);
    }

    public function update(StorePerson $request, Person $person)
    {
        $person->fill($request->all());
        $person->save();
        return redirect()->route('people.show', $person)
            ->with('success', __('people.person_updated'));
    }

    public function destroy(Person $person)
    {
        $person->delete();

        return redirect()->route('people.index')
            ->with('success', __('people.person_deleted'));
    }

    public function duplicates()
    {
        $names = [];
        Person::orderBy('family_name')
            ->orderBy('name')
            ->get()
            ->each(function ($e) use (&$names) {
                $names[$e->name . ' ' . $e->family_name][$e->id] = $e;
            });
        $duplicates = collect($names)
            ->filter(fn ($e) => count($e) > 1);

        return view('people.duplicates', [
            'duplicates' => $duplicates,
            'total' => Person::count(),
            'actions' => [
                'nothing' => 'Do nothing',
                'merge' => 'Merge',
            ],
        ]);
    }

    public function applyDuplicates(Request $request)
    {
        Validator::make($request->all(), [
            'action' => 'required|array',
        ])->validate();
        $merged = 0;
        foreach ($request->action as $idsString => $action) {
            if ($action == 'merge') {
                $ids = explode(',', $idsString);
                self::mergePersons($ids);
                $merged++;
            }
        }

        return redirect()->route('people.index')
            ->with('success', 'Done (merged ' . $merged . ' persons).');
    }

    private static function mergePersons($ids)
    {
        // Get master and related persons
        $persons = Person::whereIn('public_id', $ids)
            ->orderBy('created_at', 'desc')
            ->get();
        $master = $persons->shift();

        // Merge basic attributes
        $attributes = [
            'gender',
            'date_of_birth',
            'nationality',
            'languages',
            'police_no',
            'card_no',
            'card_issued',
        ];
        foreach ($attributes as $attr) {
            if ($master->$attr == null) {
                $master->$attr = self::getFirstNonEmptyAttributeFromCollection($persons, $attr);
            }
        }

        // Merge coupon handouts
        CouponHandout::whereIn('person_id', $persons->pluck('id')->toArray())
            ->get()
            ->each(function ($e) use ($master) {
                $e->person_id = $master->id;
                try {
                    $e->save();
                } catch(\Illuminate\Database\QueryException $ex) {
                    // Ignore
                    Log::notice('Skip adapting coupon handout during merge: ' . $ex->getMessage());
                }
            });

        // Merge remarks
        $remarks = $persons->pluck('remarks')
            ->push($master->remarks)
            ->filter(fn ($e) => $e != null)
            ->unique()
            ->implode("\n");
        if (! empty($remarks)) {
            $master->remarks = $remarks;
        }

        // Save master, remove duplicates
        $master->save();
        $persons->each(function ($e) {
            $e->forceDelete();
        });

        return count($ids);
    }

    private static function getFirstNonEmptyAttributeFromCollection($collection, $attributeName)
    {
        return $collection->pluck($attributeName)
            ->filter(fn ($e) => $e != null)
            ->first();
    }

    public function export()
    {
        $file_name = __('people.people') . ' ' . Carbon::now()->toDateString();
        $extension = 'xlsx';
        return (new PeopleExport())->download($file_name . '.' . $extension);
    }

    public function import()
    {
        return view('people.import');
    }

    public function doImport(UploadSpreadsheet $request)
    {
        $import = new PeopleImport();
        $import->import($request->file('file'));

        return redirect()->route('people.index')
            ->with('success', __('app.imported_num_records', ['num' => $import->count()]));
    }

    public function bulkSearch()
    {
        $orders = [
            'name' => __('app.name'),
            'age' => __('people.age'),
            'nationality' => __('people.nationality'),
        ];
        return view('people.bulkSearch', [
            'orders' => $orders,
            'order' => collect($orders)->keys()->first(),
        ]);
    }

    public function doBulkSearch(Request $request)
    {
        $request->validate([
            'data' => [
                'filled',
            ],
            'order' => [
                'required',
                Rule::in([
                    'name',
                    'age',
                    'nationality',
                ]),
            ],
        ]);

        $lines = preg_split("/[\s]*(\r\n|\n|\r)[\s]*/", $request->input('data'));
        $qry = Person::orderBy('name')
            ->orderBy('family_name');
        foreach ($lines as $line) {
            $terms = split_by_whitespace($line);
            $qry->orWhere(function ($qp) use ($terms) {
                foreach ($terms as $term) {
                    // Remove dash "-" from term
                    $term = preg_replace('/^([0-9]+)-([0-9]+)/', '$1$2', $term);
                    $qp->where(function ($wq) use ($term) {
                        $wq->where('search', 'LIKE', '%' . $term  . '%');
                        $wq->orWhere('police_no', $term);
                    });
                }
            });
        }
        $persons = $qry->get()
            ->sortBy($request->input('order'));
        return view('people.bulkSearchResults', [
            'persons' => $persons,
        ]);
    }

}
