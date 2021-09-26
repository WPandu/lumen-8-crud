<?php

use Carbon\Carbon;
use Illuminate\Http\Request;

if (!function_exists('get_file_url')) {
    function get_file_url($folderName, $fileName)
    {
        return sprintf(
            '%s/%s/%s',
            config('media.url'),
            $folderName,
            $fileName
        );
    }
}

if (!function_exists('get_file_source')) {
    function get_file_source($folderName, $fileName)
    {
        return sprintf(
            '%s/%s/%s',
            config('media.path'),
            $folderName,
            $fileName
        );
    }
}

if (!function_exists('get_x_platform')) {
    function get_x_platform()
    {
        return isset($_SERVER['HTTP_X_PLATFORM']) ? strtolower($_SERVER['HTTP_X_PLATFORM']) : null;
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('obj_to_arr')) {
    function obj_to_arr($obj)
    {
        return json_decode(json_encode($obj), true);
    }
}

if (!function_exists('now')) {
    function now()
    {
        return Carbon::now();
    }
}

if (!function_exists('public_path')) {
   /**
    * Get the path to the public folder.
    *
    * @param string $path
    * @return string
    */
    function public_path($path = '')
    {
        return env('PUBLIC_PATH', base_path('public')) . ($path ? '/' . $path : $path);
    }
}

if (!function_exists('get_arr_one_dimen')) {
    /** @return string */
    function get_arr_one_dimen(array $data)
    {
        return call_user_func_array('array_merge', array_values($data));
    }
}

if (!function_exists('check_include')) {
    /** @return string */
    function check_include(Request $request, $value)
    {
        $includes = explode(',', $request->query('include'));

        return in_array($value, $includes, true);
    }
}
