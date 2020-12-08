<?php

namespace App\Http\Controllers\Badges;

use App\Http\Controllers\Controller;
use App\Imports\Badges\BadgeImport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Util\Badges\BadgeCreator;
use Exception;
use Gumlet\ImageResize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Setting;
use Validator;

class BadgeMakerController extends Controller
{
    private const BADGE_ITEMS_SESSION_KEY = 'badge_items';

    private static function getSources() {
        $sources = [
            [
                'key' => 'cmtyvol',
                'label' => __('cmtyvol.community_volunteers'),
                'allowed' => request()->user()->can('viewAny', CommunityVolunteer::class),
            ],
            [
                'key' => 'file',
                'label' => __('app.file'),
                'allowed' => true,
            ],
            [
                'key' => 'list',
                'label' => __('app.list'),
                'allowed' => true,
            ],
        ];
        return collect($sources)->filter()->where('allowed', true)->pluck('label', 'key');
    }

    public function index(Request $request)
    {
        $request->session()->forget(self::BADGE_ITEMS_SESSION_KEY);

        $sources = self::getSources();
        $source = $sources->keys()->first();

        return view('badges.index', [
            'source' => $source,
            'sources' => $sources,
        ]);
    }

    public function selection(Request $request) {
        Validator::make($request->all(), [
            'source' => [
                'required',
                Rule::in(self::getSources()->keys()),
            ],
            'file' => [
                'file',
                'required_if:source,file',
            ],
            'name' => [
                'array',
                'required_if:source,list',
            ],
        ])->validate();

        $persons = [];

        // Source: Community Volunteers
        if ($request->source == 'cmtyvol') {
            $persons = CommunityVolunteer::workStatus('active')
                ->get()
                ->map(fn ($cmtyvol) => self::communityVolunteerToBadgePerson($cmtyvol));
        }
        // Source: File
        elseif ($request->source == 'file') {
            $sheets = (new BadgeImport())->toArray($request->file('file'));
            foreach ($sheets as $rows) {
                foreach ($rows as $row) {
                    if (isset($row['name']) && isset($row['position'])) {
                        $persons[] = [
                            'name' => $row['name'],
                            'position' => $row['position'],
                            'code' => $row->code ?? null,
                        ];
                    }
                }
            }
        }
        // Source: List
        elseif ($request->source == 'list') {
            $num_names = count($request->name);
            for ($i = 0; $i < $num_names; $i++) {
                if (! empty($request->name[$i])) {
                    if ($request->hasFile('picture.'.$i)) {
                        $image = new ImageResize($request->file('picture.'.$i), IMAGETYPE_JPEG);
                        $image->resizeToBestFit(800, 800, true);
                        $picture = 'data:image/jpeg;base64,' . base64_encode((string) $image);
                    } else {
                        $picture = null;
                    }
                    $persons[] = [
                        'name' => $request->name[$i],
                        'position' => $request->position[$i] ?? null,
                        'picture' => $picture,
                        'code' => null,
                    ];
                }
            }
        }

        // Ensure there are records
        Validator::make([], [])
            ->after(function ($validator) use ($persons) {
                if (count($persons) == 0) {
                    $validator->errors()->add('source', __('app.empty_data_source'));
                }
            })
            ->validate();

        $data = collect($persons)
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->mapWithKeys(fn ($e) => [ Str::random(16) => $e ]);

        $request->session()->put(self::BADGE_ITEMS_SESSION_KEY, $data->toArray());

        return view('badges.selection', [
            'persons' => $data->map(fn ($e) => $e['name'] . ($e['position'] != null ? ' (' . $e['position'] . ')' : ''))
                ->toArray(),
        ]);
    }

    public function make(Request $request) {
        $validator = Validator::make($request->all(), [
            'persons' => [
                'required',
                'array',
            ],
            'persons.*' => [
                'string',
            ],
            'alt_logo' => [
                'file',
                'image',
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->route('badges.index')
                ->with('error', implode(', ', $validator->errors()->all()));
        }

        // Retrieve data
        $data = $request->session()->get(self::BADGE_ITEMS_SESSION_KEY, []);
        $persons = collect($request->persons)
            ->map(fn ($idx) => self::addCommunityVolunteerData($data[$idx]));

        // Ensure there are records
        if (count($persons) == 0) {
            return redirect()
                ->route('badges.index')
                ->with('error', __('app.empty_data_source'));
        }

        $title = __('badges.badges');

        $badgeCreator = new BadgeCreator($persons);
        if ($request->hasFile('alt_logo')) {
            $badgeCreator->logo = $request->file('alt_logo');
        } elseif (Setting::has('badges.logo_file')) {
            $badgeCreator->logo = Storage::path(Setting::get('badges.logo_file'));
        }
        try {
            $badgeCreator->createPdf($title);
        } catch (Exception $e) {
            return redirect()->route('badges.index')
                ->with('error', $e->getMessage());
        }
    }

    private static function addCommunityVolunteerData($person)
    {
        if (isset($person['type']) && $person['type'] == 'cmtyvol' && isset($person['id'])) {
            $cmtyvol = CommunityVolunteer::find($person['id']);
            if ($cmtyvol != null) {
                $person['picture'] = $cmtyvol->portrait_picture != null ? Storage::path($cmtyvol->portrait_picture) : null;
            }
        }
        return $person;
    }

    private static function communityVolunteerToBadgePerson($cmtyvol) {
        return [
            'type' => 'cmtyvol',
            'id' => $cmtyvol->id,
            'name' => $cmtyvol->nickname ?? $cmtyvol->first_name,
            'position' => $cmtyvol->responsibilities->unique('name')->implode('name', ', '),
        ];
    }
}
