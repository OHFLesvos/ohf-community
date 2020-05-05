<?php

namespace App\Http\Controllers\Helpers\API;

use App\Http\Controllers\Controller;
use App\Models\People\Person;
use App\Support\ChartResponseBuilder;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    /**
     * Gender distribution
     */
    public function genders()
    {
        return collect(self::getPersonGenders())
            ->mapWithKeys(fn ($i) => [ self::getGenderLabel($i['gender']) => $i['total'] ])
            ->toArray();
    }

    private static function getPersonGenders()
    {
        return Person::query()
            ->whereHas('helper', fn ($query) => $query->active())
            ->select('gender')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('gender')
            ->whereNotNull('gender')
            ->get();
    }

    private static function getGenderLabel(string $value): string
    {
        if ($value == 'm') {
            return __('app.male');
        }
        if ($value == 'f') {
            return __('app.female');
        }
        return $value;
    }

    /**
     * Nationality distribution
     */
    public function nationalities(Request $request)
    {
        $request->validate([
            'limit' => [
                'nullable',
                'integer',
                'min:1',
            ]
        ]);
        $limit = $request->input('limit', 10);

        $nationalities = collect(self::getPersonNationalities())
            ->mapWithKeys(fn ($i) => [ $i['nationality'] => $i['total'] ]);

        return self::sliceData($nationalities, $limit);
    }

    private static function getPersonNationalities()
    {
        return Person::query()
            ->whereHas('helper', fn ($query) => $query->active())
            ->select('nationality')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('nationality')
            ->whereNotNull('nationality')
            ->orderBy('total', 'DESC')
            ->get();
    }

    private static function sliceData($source, $limit)
    {
        $data = $source->slice(0, $limit)
            ->toArray();
        $other = $source->slice($limit)
            ->reduce(fn ($carry, $item) => $carry + $item);
        if ($other > 0) {
            $data[__('app.others')] = $other;
        }
        return $data;
    }

    /**
     * Age distribution
     */
    public function ages()
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
        return Person::query()
            ->whereHas('helper', fn ($query) => $query->active())
            ->select('date_of_birth')
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'desc')
            ->limit(1)
            ->first();
    }

    private static function getOldestPerson()
    {
        return Person::query()
            ->whereHas('helper', fn ($query) => $query->active())
            ->select('date_of_birth')
            ->whereNotNull('date_of_birth')
            ->orderBy('date_of_birth', 'asc')
            ->limit(1)
            ->first();
    }

    private static function getPersonAges()
    {
        return Person::query()
            ->whereHas('helper', fn ($query) => $query->active())
            ->select('date_of_birth')
            ->selectRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('age')
            ->whereNotNull('date_of_birth')
            ->orderBy('age', 'asc')
            ->get();
    }
}
