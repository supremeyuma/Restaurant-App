<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    function setting($key, $default = null) {
        static $settings = null;

        if ($settings === null) {
            $settings = Setting::first(); // assuming there's only one row
        }

        return $settings->{$key} ?? $default;
    }
}
