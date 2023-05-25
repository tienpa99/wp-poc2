<?php

namespace DevOwl\RealCookieBanner\view\customize\banner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\view\BannerCustomize;
use WP_Customize_Code_Editor_Control;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Custom CSS.
 */
class CustomCss
{
    use UtilsProvider;
    const SECTION = BannerCustomize::PANEL_MAIN . '-custom-css';
    const SETTING = RCB_OPT_PREFIX . '-banner-custom-css';
    const SETTING_ANTI_AD_BLOCKER = self::SETTING . '-anti-ad-blocker';
    const SETTING_CSS = self::SETTING . '-css';
    const DEFAULT_ANTI_AD_BLOCKER = 'y';
    const DEFAULT_CSS = '';
    /**
     * Return arguments for this section.
     */
    public function args()
    {
        return ['name' => 'customCss', 'title' => \__('Custom CSS', RCB_TD), 'controls' => [self::SETTING_ANTI_AD_BLOCKER => ['name' => 'antiAdBlocker', 'label' => \__('Anti-Adblocker optimization', RCB_TD), 'description' => \__('Some adblockers are able to block cookie banners. This means that these website visitors will never see the cookie banner and you cannot get consent from them to use more than the essential services. We have implemented several mechanisms that try to remove any technical indicators that adblockers can use to block the cookie banner. This includes CSS selections for styles. As a result, it is not possible to add custom CSS code for the cookie banner. If you disable the Adblocker optimizations, you can add custom CSS code.', RCB_TD), 'type' => 'radio', 'choices' => ['y' => \__('Enable', RCB_TD), 'n' => \__('Disable', RCB_TD)], 'setting' => ['default' => self::DEFAULT_ANTI_AD_BLOCKER]], self::SETTING_CSS => ['class' => WP_Customize_Code_Editor_Control::class, 'name' => 'css', 'label' => \__('Custom CSS', RCB_TD), 'description' => \__('The style will only be applied if you have disabled the anti-blocker optimization.', RCB_TD), 'code_type' => 'text/css', 'setting' => ['default' => self::DEFAULT_CSS, 'allowEmpty' => \true]]]];
    }
}
