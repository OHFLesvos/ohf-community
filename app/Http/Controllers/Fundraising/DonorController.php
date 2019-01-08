<?php

namespace App\Http\Controllers\Fundraising;

use App\Donor;
use App\Donation;
use App\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Fundraising\StoreDonor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use JeroenDesloovere\VCard\VCard;
use Illuminate\Support\Facades\DB;
use Validator;

class DonorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('list', Donor::class);

        // Validate request
        Validator::make($request->all(), [
            'tag' => [
                'nullable', 
                'alpha_dash',
            ],
        ])->validate();

        // Handle tag session persistence
        if ($request->has('reset_tag')) {
            $request->session()->forget('donors_tag');
        }
        if (isset($request->tag)) {
            $request->session()->put('donors_tag', $request->tag);
        }

        // Handle filter session persistence
        if ($request->has('reset_filter') || ($request->has('filter') && $request->filter == null)) {
            $request->session()->forget('donors_filter');
        }
        if (isset($request->filter)) {
            $request->session()->put('donors_filter', $request->filter);
        }

        // Init query
        if ($request->session()->has('donors_tag')) {
            $tag = Tag::where('slug', $request->session()->get('donors_tag'))->firstOrFail();
            $query = $tag->donors();
        } else {
            $tag = null;
            $query = Donor::query();
        }

        // Filter
        if ($request->session()->has('donors_filter')) {
            $filter = $request->session()->get('donors_filter');
            $query->where(function($wq) use($filter) {
                return $wq->where(DB::raw('CONCAT(first_name, \' \', last_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere(DB::raw('CONCAT(last_name, \' \', first_name)'), 'LIKE', '%' . $filter . '%')
                    ->orWhere('company', 'LIKE', '%' . $filter . '%')
                    ->orWhere('first_name', 'LIKE', '%' . $filter . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $filter . '%');
            });
        } else {
            $filter = null;
        }

        return view('fundraising.donors.index', [
            'donors' => $query
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->orderBy('company')
                ->paginate(100),
            'filter' => $filter,
            'tag' => $tag,
            'tags' => Tag::has('donors')->orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Donor::class);

        return view('fundraising.donors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDonor $request)
    {
        $this->authorize('create', Donor::class);

        $donor = new Donor();
        $donor->salutation = $request->salutation;
        $donor->first_name = $request->first_name;
        $donor->last_name = $request->last_name;
        $donor->company = $request->company;
        $donor->street = $request->street;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country_name = $request->country_name;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->language = $request->language;
        $donor->remarks = $request->remarks;
        $donor->save();

        // Tags
        $tags = self::splitTags($request->tags);
        foreach ($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag != null) {
                $donor->tags()->attach($tag);
            } else {
                $tag = new Tag();
                $tag->name = $tag_str;
                $donor->tags()->save($tag);
            }
        }

        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donor_added'));
    }

    private static function splitTags($value) {
        return array_unique(preg_split('/\s*,\s*/', $value, -1, PREG_SPLIT_NO_EMPTY));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function show(Donor $donor)
    {
        $this->authorize('view', $donor);

        return view('fundraising.donors.show', [
            'donor' => $donor,
            'donations' => $donor->donations()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(),
            'currencies' => Config::get('fundraising.currencies'),
            'channels' => Donation::select('channel')->distinct()->get()->pluck('channel')->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function edit(Donor $donor)
    {
        $this->authorize('update', $donor);

        return view('fundraising.donors.edit', [
            'donor' => $donor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Fundraising\StoreDonor  $request
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function update(StoreDonor $request, Donor $donor)
    {
        $this->authorize('update', $donor);

        $donor->salutation = $request->salutation;
        $donor->first_name = $request->first_name;
        $donor->last_name = $request->last_name;
        $donor->company = $request->company;
        $donor->street = $request->street;
        $donor->zip = $request->zip;
        $donor->city = $request->city;
        $donor->country_name = $request->country_name;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->language = $request->language;
        $donor->remarks = $request->remarks;
        $donor->save();

        // Tags
        $tags = self::splitTags($request->tags);
        $tag_ids = [];
        foreach($tags as $tag_str) {
            $tag = Tag::where('name', $tag_str)->first();
            if ($tag != null) {
                $tag_ids[] = $tag->id;
            } else {
                $tag = new Tag();
                $tag->name = $tag_str;
                $tag->save();
                $tag_ids[] = $tag->id;
            }
        }
        $donor->tags()->sync($tag_ids);

        return redirect()->route('fundraising.donors.show', $donor)
            ->with('success', __('fundraising.donor_updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Donor $donor)
    {
        $this->authorize('delete', $donor);

        $donor->tags()->detach();

        $donor->delete();
        return redirect()->route('fundraising.donors.index')
            ->with('success', __('fundraising.donor_deleted'));
    }

    /**
     * Exports a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        $this->authorize('list', Donor::class);

        \Excel::create(Config::get('app.name') . ' ' . __('fundraising.donors') . ' (' . Carbon::now()->toDateString() . ')', function($excel) {
            $excel->sheet(__('fundraising.donors'), function($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->freezeFirstRow();
                $sheet->loadView('fundraising.donors.export',[
                    'donors' => Donor
                        ::orderBy('first_name')
                        ->orderBy('last_name')
                        ->orderBy('company')
                        ->get(),
                ]);
                $sheet->getStyle('I')->getNumberFormat()->setFormatCode(Config::get('fundraising.base_currency_excel_format'));
                $sheet->getStyle('J')->getNumberFormat()->setFormatCode(Config::get('fundraising.base_currency_excel_format'));
            });
        })->export('xlsx');
    }

    /**
     * Download vcard
     * 
     * @param  \App\Donor  $donor
     * @return \Illuminate\Http\Response
     */
    function vcard(Donor $donor)
    {
        $this->authorize('view', $donor);

        // define vcard
        $vcard = new VCard();
        if ($donor->company != null) {
            $vcard->addCompany($donor->company);
        }
        if ($donor->last_name != null || $donor->first_name != null) {
            $vcard->addName($donor->last_name, $donor->first_name, '', '', '');
        }
        if ($donor->email != null) {
            $vcard->addEmail($donor->email);
        }
        if ($donor->phone != null) {
            $vcard->addPhoneNumber($donor->phone, $donor->company != null ? 'WORK' : 'HOME');
        }
        $vcard->addAddress(null, null, $donor->street, $donor->city, null, $donor->zip, $donor->country_name, ($donor->company != null ? 'WORK' : 'HOME') . ';POSTAL');

        // return vcard as a download
        return $vcard->download();
    }

}
