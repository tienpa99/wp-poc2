<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use WP_Hook;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Util functionalities.
 */
class Utils
{
    use UtilsProvider;
    /**
     * Run $callback with the $handler disabled for the $hook action/filter
     *
     * @param string $hook filter name
     * @param callable $callback function execited while filter disabled
     * @return mixed value returned by $callback
     * @see https://gist.github.com/westonruter/6647252#gistcomment-2668616
     */
    public static function withoutFilters($hook, $callback)
    {
        global $wp_filter;
        $wp_hook = null;
        // Remove and cache the filter
        if (isset($wp_filter[$hook]) && $wp_filter[$hook] instanceof WP_Hook) {
            $wp_hook = $wp_filter[$hook];
            unset($wp_filter[$hook]);
        }
        $retval = \call_user_func($callback);
        // Add back the filter
        if ($wp_hook instanceof WP_Hook) {
            $wp_filter[$hook] = $wp_hook;
        }
        return $retval;
    }
    /**
     * Expand keys to dot notation so `skipKeys` works as expected and can skip
     * multidimensional arrays. This functionality also keeps the reference!
     *
     * @param array $arr
     * @param string $skipKeys
     * @see https://stackoverflow.com/a/40217420/5506547
     */
    public static function expandKeys(&$arr, $skipKeys = [])
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($arr), RecursiveIteratorIterator::SELF_FIRST);
        $pathMapping = [];
        $flatArray = [];
        foreach ($iterator as $key => $value) {
            $pathMapping[$iterator->getDepth()] = $key;
            if (!\is_array($value)) {
                $pathMapping = \array_slice($pathMapping, 0, $iterator->getDepth() + 1);
                // Get the value by reference
                $refValue =& $arr;
                foreach ($pathMapping as $dot) {
                    if (\in_array($dot, $skipKeys, \true)) {
                        continue 2;
                    }
                    $refValue =& $refValue[$dot];
                }
                $flatArray[\implode('.', $pathMapping)] =& $refValue;
            }
        }
        return $flatArray;
    }
    /**
     * Check if passed string is JSON.
     *
     * @param string $string
     * @see https://stackoverflow.com/a/6041773/5506547
     * @return array|false
     */
    public static function isJson($string)
    {
        $result = \json_decode($string, ARRAY_A);
        return \json_last_error() === \JSON_ERROR_NONE ? $result : \false;
    }
}
