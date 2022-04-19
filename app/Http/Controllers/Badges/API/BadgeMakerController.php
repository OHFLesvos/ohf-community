<?php

namespace App\Http\Controllers\Badges\API;

use App\Http\Controllers\Controller;
use App\Imports\Badges\BadgeImport;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Util\Badges\BadgeCreator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Setting;

class BadgeMakerController extends Controller
{
    public function make(Request $request)
    {
        $request->validate([
            'elements' => [
                'required',
                'array',
                'min:1',
            ],
            'elements.*.name' => [
                'string',
                'filled',
            ],
            'elements.*.picture' => [
                'file',
                'image',
            ],
            'alt_logo' => [
                'file',
                'image',
            ],
        ]);

        // if ($request->hasFile('picture.'.$i)) {
        //     $image = new \Gumlet\ImageResize($request->file('picture.'.$i), IMAGETYPE_JPEG);
        //     $image->resizeToBestFit(800, 800, true);
        //     $picture = 'data:image/jpeg;base64,' . base64_encode((string) $image);
        // } else {
        //     $picture = null;
        // }
        // $persons[] = [
        //     'name' => $request->name[$i],
        //     'position' => $request->position[$i] ?? null,
        //     'picture' => $picture,
        // ];

        $badgeCreator = new BadgeCreator($request->elements);
        if ($request->hasFile('alt_logo')) {
            $badgeCreator->logo = $request->file('alt_logo');
        } elseif (Setting::has('badges.logo_file')) {
            $badgeCreator->logo = Storage::path(Setting::get('badges.logo_file'));
        }
        try {
            $badgeCreator->createPdf(__('Badges'));
        } catch (Exception $e) {
            return redirect()->route('badges.index')
                ->with('error', $e->getMessage());
        }
    }

    public function parseSpreadsheet(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
        ]);

        $elements = [];
        $sheets = (new BadgeImport())->toArray($request->file('file'));
        foreach ($sheets as $rows) {
            foreach ($rows as $row) {
                if (isset($row['name']) && isset($row['position'])) {
                    $elements[] = [
                        'name' => $row['name'],
                        'position' => $row['position'],
                    ];
                }
            }
        }

        return response()->json($elements);
    }

    public function fetchCommunityVolunteers()
    {
        $this->authorize('viewAny', CommunityVolunteer::class);

        $elements = CommunityVolunteer::workStatus('active')
            ->get()
            ->map(fn ($cmtyvol) => [
                'name' => $cmtyvol->nickname ?? $cmtyvol->first_name,
                'position' => $cmtyvol->responsibilities->unique('name')->implode('name', ', '),
                // TODO picture
            ]);

        return response()->json($elements);
    }
}
