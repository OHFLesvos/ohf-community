<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    /**
     * Gender distribution
     */
    public function genderDistribution(): array
    {
        return CommunityVolunteer::query()
            ->select('gender')
            ->selectRaw('COUNT(*) AS total')
            ->workStatus('active')
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->orderBy('total', 'DESC')
            ->get()
            ->mapWithKeys(fn ($i) => [gender_label($i['gender']) => $i['total']])
            ->toArray();
    }

    /**
     * Nationality distribution
     */
    public function nationalityDistribution(Request $request): array
    {
        $request->validate([
            'limit' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $nationalities = CommunityVolunteer::query()
            ->select('nationality')
            ->selectRaw('COUNT(*) AS total')
            ->workStatus('active')
            ->whereNotNull('nationality')
            ->groupBy('nationality')
            ->orderBy('total', 'DESC')
            ->get()
            ->mapWithKeys(fn ($i) => [$i['nationality'] => $i['total']])
            ->toArray();

        $limit = $request->input('limit', 3);

        return self::sliceDataOthers($nationalities, $limit);
    }

    private static function sliceDataOthers(array $source, int $limit): array
    {
        $source_collection = collect($source);
        $data = $source_collection->slice(0, $limit)
            ->toArray();
        $other = (float) ($source_collection->slice($limit)
            ->reduce(fn ($carry, $item) => $carry + $item));
        if ($other > 0) {
            $data[__('Others')] = $other;
        }

        return $data;
    }

    /**
     * Age distribution
     */
    public function ageDistribution(): JsonResponse
    {
        return (new ChartResponseBuilder())
            ->dataset(__('persons'), collect(self::getAges()))
            ->build();
    }

    private static function getAges(): array
    {
        $ages = [];
        $minAge = optional(self::getYoungestPerson())->age;
        $maxAge = optional(self::getOldestPerson())->age;
        if ($minAge !== null && $maxAge !== null) {
            foreach (range($minAge, $maxAge) as $r) {
                $ages[$r.' '] = null;
            }

            return collect($ages)
                ->merge(
                    self::getPersonAges()
                        ->mapWithKeys(fn ($i) => [$i['age'].' ' => $i['total']])
                )
                ->mapWithKeys(fn ($v, $k) => [intval($k) => $v])
                ->toArray();
        }

        return [];
    }

    private static function getYoungestPerson(): ?CommunityVolunteer
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->workStatus('active')
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'desc')
            ->limit(1)
            ->first();
    }

    private static function getOldestPerson(): ?CommunityVolunteer
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->workStatus('active')
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'asc')
            ->limit(1)
            ->first();
    }

    private static function getPersonAges(): Collection
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            ->selectRaw('COUNT(*) AS total')
            ->workStatus('active')
            ->whereNotNull('date_of_birth')
            ->groupBy('age')
            ->orderBy('age', 'asc')
            ->get();
    }
}
