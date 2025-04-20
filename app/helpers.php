<?php

use App\Helpers\TranslationHelper;

if (!function_exists('t')) {
    /**
     * Get a translation by key with support for parameters
     *
     * @param string $key The translation key
     * @param mixed $params_or_default Either parameters array or default text if translation not found
     * @param string|null $default Default text if translation not found (when params is provided)
     * @return string
     */
    function t($key, $params_or_default = null, $default = null)
    {
        // Check if second parameter is an array (parameters) or string (default)
        if (is_array($params_or_default)) {
            return TranslationHelper::getWithParams($key, $params_or_default, $default);
        } else {
            return TranslationHelper::get($key, $params_or_default);
        }
    }
}