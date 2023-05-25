<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\presets\BlockerPresets;
use DevOwl\RealCookieBanner\presets\CookiePresets;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle try.devowl.io specific settings, e. g. hide preset attributes.
 */
class DemoEnvironment
{
    use UtilsProvider;
    /**
     * Singleton instance.
     *
     * @var DemoEnvironment
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Remove all PRO cookie presets from demo environment.
     *
     * @param string[] $presets
     */
    public function cookiePresets($presets)
    {
        if ($this->isDemoEnv()) {
            foreach ($presets as $key => $preset) {
                if (!\in_array($key, \array_keys(CookiePresets::CLASSES), \true)) {
                    $presets[$key] = \str_replace('DevOwl\\RealCookieBanner\\Vendor\\lite\\presets', 'DevOwl\\RealCookieBanner\\Vendor\\presets\\pro', $preset);
                }
            }
        }
        return $presets;
    }
    /**
     * Remove all PRO blocker presets from demo environment.
     *
     * @param array $presets
     */
    public function blockerPresets($presets)
    {
        if ($this->isDemoEnv()) {
            foreach ($presets as $key => &$preset) {
                if (!\in_array($key, \array_keys(BlockerPresets::CLASSES), \true)) {
                    $presets[$key] = \str_replace('DevOwl\\RealCookieBanner\\Vendor\\lite\\presets\\blocker', 'DevOwl\\RealCookieBanner\\Vendor\\presets\\pro\\blocker', $preset);
                }
            }
        }
        return $presets;
    }
    /**
     * Checks if the current running WordPress instance is configured as sandbox
     * because then some configurations are not allowed to change.
     */
    public function isDemoEnv()
    {
        return \defined('MATTHIASWEB_DEMO') && \constant('MATTHIASWEB_DEMO');
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\DemoEnvironment() : self::$me;
    }
}
