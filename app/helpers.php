<?php

if (! function_exists('form_id_string')) {
    function form_id_string(string $value, ?string $suffix = null): string
    {
        return trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', $value.($suffix !== null ? '_'.$suffix : '')));
    }
}

if (! function_exists('split_by_whitespace')) {
    function split_by_whitespace(string $value): array
    {
        return preg_split('/\s+/', $value);
    }
}

if (! function_exists('whatsapp_link')) {
    function whatsapp_link(string $value, ?string $text = null): string
    {
        // See https://medium.com/@jeanlivino/how-to-fix-whatsapp-api-in-desktop-browsers-fc661b513dc
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $iphone = strpos($user_agent, 'iPhone');
        $android = strpos($user_agent, 'Android');
        $palmpre = strpos($user_agent, 'webOS');
        $berry = strpos($user_agent, 'BlackBerry');
        $ipod = strpos($user_agent, 'iPod');
        $chrome = strpos($user_agent, 'Chrome');
        if ($android || $iphone) {
            $prefix = '<a href="whatsapp://send?phone=';
        } elseif ($palmpre || $ipod || $berry || $chrome) {
            $prefix = '<a href="https://api.whatsapp.com/send?phone=';
        } else {
            $prefix = '<a target="_blank" href="https://web.whatsapp.com/send?phone=';
        }
        $suffix = $text !== null ? '&text='.urlencode($text) : '';

        return $prefix.preg_replace('/[^0-9]/', '', $value).$suffix.'">'.$value.'</a>';
    }
}

if (! function_exists('bytes_to_human')) {
    function bytes_to_human($bytes)
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}

if (! function_exists('previous_route')) {
    function previous_route(): string
    {
        return optional(
            app('router')
                ->getRoutes()
                ->match(
                    app('request')
                        ->create(URL::previous())
                )
        )->getName();
    }
}

if (! function_exists('array_insert')) {
    /**
     * @param  array  $array
     * @param  int|string  $position
     * @param  mixed  $insert
     */
    function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
}

if (! function_exists('array_elements_not_blank')) {
    /**
     * Checks if the array contains non-blank values specified by the given keys.
     *
     * @param  array  $array the array to be checked
     * @param  array  $keys the keys to be considered
     * @return bool if all values indicated by the specified keys are not blank
     */
    function array_elements_not_blank(array $array, array $keys): bool
    {
        foreach ($keys as $key) {
            if (! filled($array[$key])) {
                return false;
            }
        }

        return true;
    }
}

if (! function_exists('randomPercentages')) {
    function randomPercentages(int $num): array
    {
        $temp = [];
        $temp[] = 0;
        for ($i = 1; $i < $num; $i++) {
            $new = mt_rand(1, 99);
            if ($i < 98) {
                while (in_array($new, $temp)) {
                    $new = mt_rand(1, 99);
                }
            }
            $temp[] = $new;
        }
        $temp[] = 100;
        sort($temp);
        $percentages = [];
        $count = count($temp);
        for ($i = 1; $i < $count; $i++) {
            $percentages[] = $temp[$i] - $temp[$i - 1];
        }

        return $percentages;
    }
}

if (! function_exists('weightedCountries')) {
    function weightedCountries(int $num): array
    {
        $countries = Countries::getList('en');
        $rand_keys = array_rand($countries, $num);
        $percentages = randomPercentages($num);
        $data = [];
        while (count($percentages) > 0) {
            $percentage = array_pop($percentages);
            $country_id = array_pop($rand_keys);
            for ($i = 0; $i < $percentage; $i++) {
                $data[] = Countries::getOne($country_id);
            }
        }

        return $data;
    }
}

if (! function_exists('weightedLanguages')) {
    function weightedLanguages(int $num): array
    {
        $languages = Languages::lookup()->keys()->toArray();
        $selected_languages = Arr::random($languages, $num);
        $percentages = randomPercentages($num);
        $data = [];
        while (count($percentages) > 0) {
            $percentage = array_pop($percentages);
            $lang = array_pop($selected_languages);
            for ($i = 0; $i < $percentage; $i++) {
                $data[] = $lang;
            }
        }

        return $data;
    }
}

if (! function_exists('getCategorizedPermissions')) {
    function getCategorizedPermissions(): array
    {
        $map = collect(config('permissions.keys'))
            ->map(fn ($p) => __($p['label']))
            ->toArray();
        $permissions = [];
        foreach ($map as $k => $v) {
            if (preg_match('/^(.+): (.+)$/', $v, $m)) {
                $permissions[$m[1]][$k] = $m[2];
            } else {
                $permissions[null][$k] = $v;
            }
        }
        ksort($permissions);

        return $permissions;
    }
}

if (! function_exists('localized_country_names')) {
    /**
     * Returns a list of localized country names
     *
     * @param  string|null  $locale
     * @return \Illuminate\Support\Collection
     */
    function localized_country_names($locale = null): Illuminate\Support\Collection
    {
        return collect(Countries::getList($locale ?? App::getLocale()));
    }
}

if (! function_exists('localized_language_names')) {
    /**
     * Returns a list of localized language names
     *
     * @param  string|null  $locale
     * @return \Illuminate\Support\Collection
     */
    function localized_language_names($locale = null): Illuminate\Support\Collection
    {
        return Languages::lookup(null, $locale ?? App::getLocale());
    }
}

if (! function_exists('slice_data_others')) {
    function slice_data_others(array $source, int $limit): array
    {
        $source_collection = collect($source);
        $data = $source_collection->slice(0, $limit)
            ->toArray();
        $other = $source_collection->slice($limit)
            ->reduce(fn ($carry, $item) => $carry + $item);
        if ($other > 0) {
            $data[__('Others')] = $other;
        }

        return $data;
    }
}

if (! function_exists('gender_label')) {
    function gender_label(string $value): string
    {
        if ($value == 'm') {
            return __('Male');
        }
        if ($value == 'f') {
            return __('Female');
        }

        return $value;
    }
}

if (! function_exists('thumb_path')) {
    function thumb_path(string $orig_path, ?string $extension = null): string
    {
        $pi = pathinfo($orig_path);

        return $pi['dirname'].DIRECTORY_SEPARATOR.$pi['filename'].'_thumb.'.($extension ?? $pi['extension']);
    }
}
