<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer;

/**
 * Utility helpers.
 */
class Utils
{
    /**
     * Convert a semver version-string to an integer. This does not support alpha, beta, next
     * or other release channels - but prereleases.
     *
     * Examle: `2.18.0-10597` -> `0002 0018 0000 0010597`
     *
     * @param string $semver
     * @see https://regex101.com/r/GcX7VV/2
     */
    public static function semverToInt($semver)
    {
        $result = 0;
        if (\preg_match('/^(\\d+)\\.(\\d+)\\.(\\d)+(?:-(\\d+))?/m', $semver, $matches)) {
            list(, $major, $minor, $patch) = $matches;
            $prerelease = $matches[4] ?? '0';
            $result = \sprintf('%s%s%s%s', \str_pad($major, 4, '0', \STR_PAD_LEFT), \str_pad($minor, 4, '0', \STR_PAD_LEFT), \str_pad($patch, 4, '0', \STR_PAD_LEFT), \str_pad($prerelease, 7, '0', \STR_PAD_LEFT));
        }
        return $result;
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
    /**
     * Check if an array of objects or arrays has a property with a given value.
     *
     * @param mixed[] $arr
     * @param string $key
     * @param string $value
     * @param boolean $returnObj
     * @return boolean|mixed
     */
    public static function in_array_column($arr, $key, $value, $returnObj = \false)
    {
        foreach ($arr as $item) {
            if (\is_array($item) && isset($item[$key]) && $item[$key] === $value || \is_object($item) && \property_exists($item, $key) && $item->{$key} === $value) {
                return $returnObj ? $item : \true;
            }
        }
        return \false;
    }
}
