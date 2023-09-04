<?php

if (! function_exists('form_id_string')) {
    function form_id_string(string $value, string $suffix = null): string
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
    function whatsapp_link(string $value, string $text = null): string
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
     */
    function localized_language_names($locale = null): Illuminate\Support\Collection
    {
        return Languages::lookup(null, $locale ?? App::getLocale());
    }
}

if (! function_exists('gender_label')) {
    function gender_label(?string $value): ?string
    {
        return match ($value) {
            'm', 'male' => __('Male'),
            'f', 'female' => __('Female'),
            'other' => __('other'),
            default => $value,
        };
    }
}

if (! function_exists('thumb_path')) {
    function thumb_path(string $orig_path, string $extension = null): string
    {
        $pi = pathinfo($orig_path);

        return $pi['dirname'].DIRECTORY_SEPARATOR.$pi['filename'].'_thumb.'.($extension ?? $pi['extension']);
    }
}
