<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\YoutubePreset as FreeYoutubePreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Youtube blocker preset.
 */
class YoutubePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeYoutubePreset::IDENTIFIER;
    const HOSTS_GROUP_PLATFORM_JS_NAME = 'platform-js';
    const HOSTS_GROUP_SUBSCRIBE_EMBED_PLATFORM_JS_NAME = 'subscribe-embed-platform-js';
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'YouTube';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/youtube.png'), 'attributes' => ['name' => $name, 'rules' => [
            '*youtube.com*',
            '*youtu.be*',
            '*youtube-nocookie.com*',
            '*ytimg.com*',
            // TODO: platform.js needs to be implemented together with `div[class*="g-ytsubscribe"]`...
            // This needs to be done with the service cloud and `ruleGroups`
            '*apis.google.com/js/platform.js*',
            'div[class*="g-ytsubscribe"]',
            '*youtube.com/subscribe_embed*',
            // [Plugin Comp] Elementor
            'div[data-settings:matchesUrl()]',
            // [Plugin Comp] Ultimate Addons for Elementor
            'script[id="uael-video-subscribe-js"]',
            // [Plugin Comp] Premium Addons for Elementor
            'div[class*="elementor-widget-premium-addon-video-box"][data-settings*="youtube"]',
            // [Plugin Comp] tagDiv Composer
            'div[class*="td_wrapper_playlist_player_youtube"]',
            // [Plugin Comp] https://wordpress.org/plugins/wp-youtube-lyte/
            '*wp-content/plugins/wp-youtube-lyte/lyte/lyte-min.js*',
            // [Plugin Comp] https://wordpress.org/plugins/youtube-embed-plus/
            '*wp-content/plugins/youtube-embed-plus/scripts/*',
            '*wp-content/plugins/youtube-embed-plus-pro/scripts/*',
            'div[id^="epyt_gallery"]',
            // [Plugin Comp] Thrive Visual Editor
            'div[class*="tcb-yt-bg"]',
            // [Plugin Comp] https://wordpress.org/plugins/wp-video-lightbox/
            'a[href*="youtube.com"][rel="wp-video-lightbox"]',
            // [Plugin Comp] https://github.com/paulirish/lite-youtube-embed
            'lite-youtube[videoid]',
            // [Plugin Comp] https://avada.theme-fusion.com/design-elements/lightbox-element/
            'a[href*="youtube.com"][class*="awb-lightbox"]',
            // [Plugin Comp] https://elementor.com/help/lightbox/
            'div[data-elementor-lightbox*="youtube.com"]',
            // [Plugin Comp] Impreza + WP Bakery
            'div[class*="w-video"][onclick*="youtube.com"]',
            // [Plugin Comp] Oxygen
            'new OUVideo({*type:*yt',
            // [Plugin Comp] Streamtube
            '*/wp-content/plugins/streamtube-core/public/assets/vendor/video.js/youtube*',
            'video-js[data-setup:matchesUrl()][data-player-id]',
            // [Plugin Comp] https://bandtheme.com/
            'a[data-youtube:delegateClick()]',
            // [Plugin Comp] https://wordpress.org/plugins/video-gallery-playlist/
            'spidochetube',
            'div[class*="spidochetube"]',
            // [Plugin Comp] Essentials Addons for Elementor
            'div[id^="eaelsv-player"][data-plyr-provider="youtube":visualParent(value=.elementor-widget-container)]',
        ], 'serviceTemplates' => [FreeYoutubePreset::IDENTIFIER], 'isVisual' => \true, 'visualType' => 'hero', 'visualContentType' => 'video-player']];
    }
}
