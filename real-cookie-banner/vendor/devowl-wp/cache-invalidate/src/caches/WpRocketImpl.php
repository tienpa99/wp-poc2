<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\caches;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\AbstractCache;
use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
/**
 * WP Rocket.
 *
 * @see https://wp-rocket.me/
 * @see https://docs.wp-rocket.me/article/92-rocketcleandomain
 * @codeCoverageIgnore
 */
class WpRocketImpl extends AbstractCache
{
    const IDENTIFIER = 'wp-rocket';
    // Documented in AbstractCache
    public function isActive()
    {
        return \function_exists('rocket_clean_domain');
    }
    // Documented in AbstractCache
    public function invalidate()
    {
        return \rocket_clean_domain();
    }
    /**
     * Exclude JavaScript and CSS assets.
     *
     * @param ExcludeAssets $excludeAssets
     */
    public function excludeAssetsHook($excludeAssets)
    {
        foreach (['rocket_exclude_%s', 'rocket_exclude_defer_%s', 'rocket_delay_%s_exclusions'] as $filter) {
            foreach (['js', 'css'] as $type) {
                \add_filter(\sprintf($filter, $type), function ($excluded) use($excludeAssets, $type) {
                    $path = $excludeAssets->getAllUrlPath($type);
                    $handles = $excludeAssets->getHandles()[$type];
                    return \array_merge($excluded, $path, $handles);
                });
            }
        }
    }
    // Documented in AbstractCache
    public function label()
    {
        return 'WP Rocket';
    }
}
