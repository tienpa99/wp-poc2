<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractSyncPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\Sync;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Localization as UtilsLocalization;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\view\customize\banner\Legal;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * i18n management for backend and frontend.
 */
class Localization
{
    use UtilsProvider;
    use UtilsLocalization;
    /**
     * Keys of array which should be not translated with `translateArray`.
     */
    const COMMON_SKIP_KEYS = ['slug'];
    /**
     * Get the directory where the languages folder exists.
     *
     * @param string $type
     * @return string[]
     */
    protected function getPackageInfo($type)
    {
        if ($type === Constants::LOCALIZATION_BACKEND) {
            return [RCB_PATH . '/languages', RCB_TD];
        } else {
            return [RCB_PATH . '/' . Constants::LOCALIZATION_PUBLIC_JSON_I18N, RCB_TD];
        }
    }
    /**
     * Make our plugin multilingual with the help of `AbstractSyncPlugin` and `Sync`!
     * Also have a look at `BannerCustomize`, there are `LanguageDependingOption`'s.
     */
    public static function multilingual()
    {
        $compLanguage = \DevOwl\RealCookieBanner\Core::getInstance()->getCompLanguage();
        $sync = new Sync(\array_merge([Cookie::CPT_NAME => Cookie::SYNC_OPTIONS, Blocker::CPT_NAME => Blocker::SYNC_OPTIONS], \DevOwl\RealCookieBanner\Core::getInstance()->isPro() ? [TcfVendorConfiguration::CPT_NAME => TcfVendorConfiguration::SYNC_OPTIONS] : []), [CookieGroup::TAXONOMY_NAME => CookieGroup::SYNC_OPTIONS], $compLanguage);
        $compLanguage->setSync($sync);
        $idsToCurrent = [Legal::SETTING_PRIVACY_POLICY, Legal::SETTING_IMPRINT];
        foreach ($idsToCurrent as $id) {
            \add_filter('DevOwl/Customize/LocalizedValue/' . $id, function ($value) use($compLanguage) {
                return $compLanguage->getCurrentPostId($value, Cookie::CPT_NAME);
            });
        }
        // Translate some meta fields when they get copied to the other language
        if ($compLanguage instanceof AbstractSyncPlugin) {
            foreach (Cookie::SYNC_META_COPY_ONCE as $translateMetaKey) {
                \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . $translateMetaKey, [$compLanguage, 'translateInputAndReturnValue']);
            }
        }
    }
}
