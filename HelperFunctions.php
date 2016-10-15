<?php
if (! function_exists('array_get')) {
    function array_get($array, $key, $default = null){
        return isset($array[$key]) ? $array[$key] : $default;
    }
}