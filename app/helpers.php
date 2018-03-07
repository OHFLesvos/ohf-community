<?php

if (! function_exists('form_id_string')) {
    function form_id_string($value, $suffix = null) {
        return trim(preg_replace('/[^A-Za-z0-9-_]+/', '-', $value . ($suffix != null ? '_' . $suffix : '')));
    }
}
