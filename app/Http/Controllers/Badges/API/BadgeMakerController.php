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
            'elements.*.picture_url' => [
                'string',
            ],
            'alt_logo' => [
                'file',
                'image',
            ],
        ]);

        $persons = collect($request->elements, [])
            ->map(fn ($e, $idx) => [
                'name' => $e['name'],
                'position' => $e['position'] ?? null,
                'picture' => $this->getResizedPictureFromRequest($request, $idx),
            ]);

        $badgeCreator = new BadgeCreator($persons);
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

    private function getResizedPictureFromRequest(Request $request, int $i)
    {
        if ($request->hasFile("elements.$i.picture")) {
            $image = new \Gumlet\ImageResize($request->file("elements.$i.picture"), IMAGETYPE_JPEG);
            $image->resizeToBestFit(800, 800, true);
            return 'data:image/jpeg;base64,' . base64_encode((string) $image);
        } else if ($request->filled("elements.$i.picture_url")) {
            return $request->input("elements.$i.picture_url");
        }
        return null;
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
                'picture_url' => $cmtyvol->portrait_picture != null ? Storage::url($cmtyvol->portrait_picture) : null,
            ]);

        return response()->json($elements);
    }
}
