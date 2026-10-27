<?php

// app/Helpers/helpers.php

if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        static $cache = [];
        if (!isset($cache[$key])) {
            $cache[$key] = \App\Models\Setting::where('key', $key)->value('value');
        }
        return $cache[$key] ?? $default;
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo(\Carbon\Carbon $date): string
    {
        return $date->diffForHumans();
    }
}
