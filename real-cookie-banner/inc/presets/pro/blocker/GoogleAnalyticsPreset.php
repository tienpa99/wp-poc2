<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset as PresetsGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Analytics (Universal Analytics) blocker preset.
 */
class GoogleAnalyticsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleAnalyticsPreset::IDENTIFIER;
    const VERSION = 1;
    const HOSTS_GROUP_PROPERTY_ID_NAME = 'property-id';
    const HOSTS_GROUP_SCRIPT_NAME = 'script';
    const HOSTS_GROUP_SCRIPT = [
        [BlockerHostsOptionsMiddleware::EXPRESSION => '*google-analytics.com/analytics.js*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_SCRIPT_NAME],
        [BlockerHostsOptionsMiddleware::EXPRESSION => '*google-analytics.com/ga.js*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_SCRIPT_NAME],
        // Comp: RankMath
        [BlockerHostsOptionsMiddleware::EXPRESSION => 'script[id="google_gtagjs"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_SCRIPT_NAME],
    ];
    const HOSTS_GROUP_SCRIPT_PROPERTY = [[BlockerHostsOptionsMiddleware::EXPRESSION => '"UA-*"', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_PROPERTY_ID_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => "'UA-*'", BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_PROPERTY_ID_NAME]];
    /**
     * The `/collect` route of GA is usually only used with JavaScript, but it could be in HTML, too,
     * due to the fact it can be used with `<noscript`. It resolves both logical must groups as it can
     * be standalone (e.g. PixelYourSite integration).
     */
    const HOSTS_GROUP_COLLECTOR = [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google-analytics.com/collect*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => [self::HOSTS_GROUP_SCRIPT_NAME, self::HOSTS_GROUP_PROPERTY_ID_NAME], BlockerHostsOptionsMiddleware::QUERY_ARGS => [['queryArg' => 'tid', 'regexp' => '/^UA-/']]]];
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Universal Analytics', 'attributes' => ['rules' => \array_merge(self::HOSTS_GROUP_SCRIPT_PROPERTY, [[BlockerHostsOptionsMiddleware::EXPRESSION => 'ga(', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_PROPERTY_ID_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => 'gtag(', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_PROPERTY_ID_NAME, BlockerHostsOptionsMiddleware::SKIP_IN_GROUP_VALIDATION => \true]], self::HOSTS_GROUP_SCRIPT, self::HOSTS_GROUP_COLLECTOR, [[BlockerHostsOptionsMiddleware::EXPRESSION => '*googletagmanager.com/gtag/js?*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::HOSTS_GROUP_SCRIPT_NAME, BlockerHostsOptionsMiddleware::QUERY_ARGS => [['queryArg' => 'id', 'isOptional' => \true, 'regexp' => '/^UA-/']]]], [[BlockerHostsOptionsMiddleware::EXPRESSION => '*googletagmanager.com/gtag/js?*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => [self::HOSTS_GROUP_SCRIPT_NAME, self::HOSTS_GROUP_PROPERTY_ID_NAME], BlockerHostsOptionsMiddleware::QUERY_ARGS => [['queryArg' => 'id', 'isOptional' => \false, 'regexp' => '/^UA-/']]]])], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-analytics.png')];
    }
}
