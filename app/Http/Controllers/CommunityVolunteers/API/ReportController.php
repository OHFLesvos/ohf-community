<?php

namespace App\Http\Controllers\CommunityVolunteers\API;

use App\Http\Controllers\Controller;
use App\Models\CommunityVolunteers\CommunityVolunteer;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Gender distribution
     */
    public function genderDistribution()
    {
        return CommunityVolunteer::query()
            ->select('gender')
            ->selectRaw('COUNT(*) AS total')
            ->active()
            ->whereNotNull('gender')
            ->groupBy('gender')
            ->orderBy('total', 'DESC')
            ->get()
            ->mapWithKeys(fn ($i) => [ gender_label($i['gender']) => $i['total'] ])
            ->toArray();
    }

    /**
     * Nationality distribution
     */
    public function nationalityDistribution(Request $request)
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
            ->active()
            ->whereNotNull('nationality')
            ->groupBy('nationality')
            ->orderBy('total', 'DESC')
            ->get()
            ->mapWithKeys(fn ($i) => [ $i['nationality'] => $i['total'] ])
            ->toArray();

        $limit = $request->input('limit', 10);
        return slice_data_others($nationalities, $limit);
    }

    /**
     * Age distribution
     */
    public function ageDistribution()
    {
        return (new ChartResponseBuilder())
            ->dataset(__('people.persons'), collect(self::getAges()))
            ->build();
    }

    private static function getAges()
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
                        ->mapWithKeys(fn ($i) => [ $i['age'] . ' ' => $i['total'] ])
                )
                ->mapWithKeys(fn ($v, $k) => [intval($k) => $v])
                ->toArray();
        }
        return [];
    }

    private static function getYoungestPerson()
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->active()
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'desc')
            ->limit(1)
            ->first();
    }

    private static function getOldestPerson()
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->active()
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'asc')
            ->limit(1)
            ->first();
    }

    private static function getPersonAges()
    {
        return CommunityVolunteer::query()
            ->select('date_of_birth')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            ->selectRaw('COUNT(*) AS total')
            ->active()
            ->whereNotNull('date_of_birth')
            ->groupBy('age')
            ->orderBy('age', 'asc')
            ->get();
    }
}
