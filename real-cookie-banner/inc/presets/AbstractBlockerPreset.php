<?php

namespace DevOwl\RealCookieBanner\presets;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
abstract class AbstractBlockerPreset
{
    use UtilsProvider;
    const KNOWN_CDNS = ['cdnjs.cloudflare.com', 'jsdelivr.net', 'unpkg.com'];
    /**
     * Common preset options.
     *
     * @return array
     */
    public abstract function common();
    /**
     * Get a `mailto:` link for the admin email. This can be especially useful for contact forms.
     */
    public function getAdminEmailLink()
    {
        return \sprintf('<a href="mailto:%1$s" target="_blank">%1$s</a>', \get_bloginfo('admin_email'));
    }
    /**
     * Iterate all known CDNs and return an array for blocking rules. You do not need to include
     * the wildcard symbol `*` as it is automatically created.
     *
     * @param string $filename
     */
    public function createHostsForCdn($filename)
    {
        $result = [];
        foreach (self::KNOWN_CDNS as $cdn) {
            $result[] = \sprintf('*%s*%s*', $cdn, $filename);
        }
        return $result;
    }
}
