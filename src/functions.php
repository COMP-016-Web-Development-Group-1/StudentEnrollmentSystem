<?php

function dd($value)
{
    echo '<pre>';
    var_dump($value);
    echo '</pre>';

    exit();
}

function base_path($path = '')
{
    return __DIR__ . '/../' . $path;
}

function uri_is($value)
{
    return $_SERVER['REQUEST_URI'] === $value;
}

function config($key = null, $default = null)
{
    static $config;

    if (is_null($config)) {
        $configPath = base_path('config.php');

        if (!file_exists($configPath)) {
            throw new Exception('Config file not found: ' . $configPath);
        }

        $config = require $configPath;
    }

    if (is_null($key)) {
        return $config;
    }

    return $config[$key] ?? $default;
}


// Supports nested if needed
// function config($key = null, $default = null)
// {
//     static $config;

//     if (!$config) {
//         $configFile = base_path('config.php');
//         if (!file_exists($configFile)) {
//             throw new Exception('Config file not found: ' . $configFile);
//         }

//         $config = require $configFile;
//     }

//     if (is_null($key)) {
//         return $config;
//     }

//     $keys = explode('.', $key);
//     $value = $config;

//     foreach ($keys as $segment) {
//         if (is_array($value) && array_key_exists($segment, $value)) {
//             $value = $value[$segment];
//         } else {
//             return $default;
//         }
//     }

//     return $value;
// }
