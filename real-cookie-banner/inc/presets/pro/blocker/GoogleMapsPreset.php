<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleMapsPreset as PresetsGoogleMapsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Maps blocker preset.
 */
class GoogleMapsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleMapsPreset::IDENTIFIER;
    const VERSION = 4;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Maps';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*maps.google.com*',
            '*google.*/maps*',
            '*maps.googleapis.com*',
            '*maps.gstatic.com*',
            '*google.maps.Map(*',
            // [Plugin Comp] https://wordpress.org/plugins/wp-google-maps/
            'div[class*="wpgmza_map":keepAttributes(value=class),jQueryHijackEach()][data-maps-engine="google-maps"]',
            // [Plugin Comp] https://wordpress.org/plugins/google-maps-easy/
            'div[class="gmp_map_opts"]',
            // [Plugin Comp] https://www.elegantthemes.com/gallery/divi/
            'div[class="et_pb_map"]',
            // [Plugin Comp] https://undsgn.com/uncode/
            'div[class*="uncode-gmaps-widget"]',
            '*uncode.gmaps*.js*',
            // [Plugin Comp] https://www.dynamic.ooo/widget/dynamic-google-maps/
            '*dynamic-google-maps.js*',
            '*@googlemaps/markerclustererplus/*',
            'div[data-widget_type*="dyncontel-acf-google-maps"]',
            // [Plugin Comp] https://wordpress.org/plugins/wp-google-map-plugin/
            '*/wp-content/plugins/wp-google-map-gold/assets/js/*',
            '*/wp-content/plugins/wp-google-map-plugin/assets/js/*',
            // [Plugin Comp] https://www.wpgmaps.com/gold-add-on/
            '*/wp-content/plugins/wp-google-maps-gold/js/*',
            '.data("wpgmp_maps")',
            'div[class*="wpgmp_map_container"]',
            // [Plugin Comp] https://themify.me/addons/maps-pro
            'div[data-map-provider="google"]',
            'div[class*="module-maps-pro"]',
            // [Plugin Comp] https://wordpress.org/plugins/wp-store-locator/
            'div[id="wpsl-wrap"]',
            '*/wp-content/plugins/wp-store-locator/js/*',
            // [Plugin Comp] https://themeforest.net/item/avada-responsive-multipurpose-theme/2833226
            'script[id="google-maps-infobox-js"]',
            '*google.maps.event*',
            'div[class*="fusion-google-map"]',
            // [Plugin Comp] https://wordpress.org/plugins/extensions-for-elementor/
            '*/wp-content/plugins/extensions-for-elementor/assets/lib/gmap3/gmap3*',
            'div[class*="elementor-widget-ee-mb-google-map"]',
            // [Plugin Comp] https://wordpress.org/plugins/modern-events-calendar-lite/
            'div[class*="mec-events-meta-group-gmap"]',
            '*/wp-content/plugins/modern-events-calendar/assets/packages/richmarker/richmarker*',
            '*/wp-content/plugins/modern-events-calendar/assets/js/googlemap*',
            ".mecGoogleMaps('",
            // [Plugin Comp] https://wordpress.org/plugins/elementor/
            'div[class*="google_map_shortcode_wrapper"]',
            // [Plugin Comp] https://themeforest.net/item/wp-residence-real-estate-wordpress-theme/7896392
            '*/wp-content/themes/wpresidence/js/google_js/google_map_code_listing*',
            '*/wp-content/themes/wpresidence/js/google_js/google_map_code.js*',
            '*/wp-content/themes/wpresidence/js/infobox*',
            // [Plugin Comp] https://premiumaddons.com/
            'div[class*="elementor-widget-premium-addon-maps"]',
            'div[class*="premium-maps-container"]',
            '*/wp-content/plugins/premium-addons-for-elementor/assets/frontend/min-js/premium-maps.min.js*',
            // [Plugin Comp] http://arktheme.com/
            'section[class*="ff-map"]',
            '*/wp-content/themes/ark/assets/plugins/google-map*',
            '*/wp-content/themes/ark/assets/scripts/components/google-map*',
            // [Plugin Comp] https://ultimateelementor.com/
            '*/wp-content/plugins/ultimate-elementor/assets/min-js/uael-google-map*',
            'div[class*="elementor-widget-uael-google-map"]',
            // [Plugin Comp] https://wpforms.com/how-to-display-a-map-with-your-wordpress-contact-form/
            'div[class*="wpforms-geolocation-map"]',
            '*/wp-content/plugins/wpforms-geolocation/assets/js/wpforms-geolocation-google-api*',
            // [Plugin Comp] https://www.wp-immomakler.de/
            'iframe[src*="callback=map-iframe"]',
            '*/wp-content/plugins/immomakler/vendors/gmaps/*',
            // [Plugin Comp] https://codecanyon.net/item/progress-map-wordpress-plugin/5581719
            '*/wp-content/plugins/codespacing-progress-map/assets/js/*',
            'div[class*="codespacing_progress_map_area"]',
            // [Plugin Comp] https://qodeinteractive.com/wordpress-theme/bridge-creative-multi-purpose-wordpress-theme/
            'div[class*="qode_google_map"]',
            // [Plugin Comp] WP Grid Builder
            '*wp-content/plugins/wp-grid-builder-map-facet/assets/js*',
            'div[class*="wpgb-map-facet"]',
            // [Plugin Comp] https://themeforest.net/item/mylisting-directory-listing-wordpress-theme/20593226
            '*wp-content/themes/my-listing/assets/dist/maps/google-maps/*',
            '*wp-content/themes/my-listing/assets/dist/explore*',
            'div[class*="c27-map"]',
            'div[class*="elementor-widget-case27-explore-widget"]',
            // [Plugin Comp] https://themeforest.net/item/salient-responsive-multipurpose-theme/4363266
            '*wp-content/themes/salient/js/build/map*',
            'div[class*="nectar-google-map"]',
            // [Plugin Comp] https://heroplugins.com/product/maps/
            'div[class="hmapsprem_map_container"]',
            '*/wp-content/plugins/hmapsprem/assets/js/frontend_script.js*',
            // [Plugin Comp] Advanced Maps (Jet Elements)
            'div[class*="jet-map"]',
            // [Plugin Comp] https://gravitygeolocation.com/
            'div[class*="ginput_container_gfgeo_google_map"]',
            // [Theme Comp] https://themeforest.net/item/rodich-a-restaurant-wordpress-theme/19949280
            'div[class*="rodich-google-map"]',
            '.googleMap({',
            // [Plugin Comp] https://crocoblock.com/knowledge-base/articles/jetengine-maps-listing-overview/
            'div[class*="elementor-widget-jet-engine-maps-listing"]',
            '*/wp-content/plugins/jet-engine/assets/js/frontend-maps*',
            '*wp-content/plugins/jet-engine/includes/modules/maps-listings/*',
            // [Plugin Comp] https://facetwp.com/help-center/facets/facet-types/map/
            '*/wp-content/plugins/facetwp-map-facet/assets/js/*',
            'div[class*="facetwp-type-map"]',
            // [Plugin Comp] https://themeforest.net/item/borderland-a-daring-multiconcept-theme/10939025
            'div[class*="eltd_google_map"]',
            // [Plugin Comp] https://themeforest.net/item/attornix-lawyer-wordpress-theme/24032543
            'div[class*="cmsmasters_google_map"]',
            // [Plugin Comp] https://github.com/themovation/th-widget-pack
            '*wp-content/plugins/th-widget-pack/js/themo-google-maps.js*',
            'div[class*="th-gmap-wrap"]',
            'div[class*="th-map"]',
            // [Plugin Comp] https://ultimate.brainstormforce.com/docs/google-maps/
            'div[class*="ultimate_google_map"]',
            // [Plugin Comp] https://agilestorelocator.com/
            'div[id="asl-storelocator"]',
            '*wp-content/plugins/agile-store-locator*',
            // [Plugin Comp] https://wordpress.org/plugins/wp-map-block/
            '*wp-content/plugins/wp-map-block*',
            'div[class*="wpmapblockrender"]',
            // [Plugin Comp] https://rockythemes.com/creativo/
            '*wp-content/themes/creativo/assets/js/min/general/google-maps.min.js*',
            'div[data-id^="google_map_element_"]',
            // [Plugin Comp] OnePress Plus
            'div[class*="onepress-map"]',
            // [Plugin Comp] Enfold / Avia
            '*/wp-content/themes/enfold/framework/js/conditional_load/avia_google_maps*',
            'avf_exclude_assets:avia-google-maps-api',
            'avf_exclude_assets:avia_google_maps_front_script',
            'avf_exclude_assets:avia_google_maps_api_script',
            'div[class*="avia-google-maps"]',
            // Legacy
            'div[class*="avia-google-map-container"]',
            // [Plugin Comp] https://woodmart.xtemos.com/google-maps/demo/fashion-colored/
            '*wp-content/themes/woodmart/js/scripts/elements/googleMap*',
            '*wp-content/themes/woodmart/js/libs/maplace*',
            'div[class*="elementor-widget-wd_google_map"]',
            // [Plugin Comp] https://docs.neuronthemes.com/article/290-advanced-google-maps
            'div[class*="elementor-widget-neuron-advanced-google-maps"]',
            // [Plugin Comp] https://codecanyon.net/item/directories-pro-for-wordpress/21800540
            '*wp-content/plugins/directories/assets/js/map-googlemaps.min.js*',
            '*wp-content/plugins/directories/assets/js/map-googlemaps-*.min.js*',
            '*DRTS.Map.api.getMap(*',
            'div[class*="drts-map-container"]',
            // [Plugin Comp] https://siteorigin.com/widgets-bundle/google-maps-widget/
            '*/wp-content/plugins/so-widgets-bundle/js/sow.google-map*',
            'div[class*="so-widget-sow-google-map"]',
            // [Plugin Comp] The Events Calendar
            'div[id^="tribe-events-gmap"]',
            '*/wp-content/plugins/the-events-calendar/src/resources/js/embedded-map*',
            // [Plugin Comp] https://wordpress.org/plugins/mappress-google-maps-for-wordpress/
            'div[id^="mapp"][class*="mapp-layout"]',
            '*/wp-content/plugins/mappress-google-maps-for-wordpress/build/index_mappress*',
            // [Plugin Comp] https://themeforest.net/item/sober-woocommerce-wordpress-theme/18332889
            'div[class*="sober-map"]',
            // [Plugin Comp] https://vehica.com/
            'div[class*="elementor-widget-vehica_location_single_car_widget"]',
            'div[class*="elementor-widget-vehica_map_general_widget"]',
            // [Plugin Comp] Essentials Addons for Elementor
            'div[class*="elementor-widget-eael-google-map"]',
            // [Plugin Comp] https://wordpress.org/plugins/events-manager/
            'div[class*="em-location-map"]',
            'div[class*="em-locations-map"]',
            'div[class*="em-search-geo"]',
            'div[id="em-map"]',
            // [Plugin Comp] https://wordpress.org/plugins/geodirectory/
            // TODO: this also allows OSM but we cannot distinguish between them
            '*wp_widget_gd_map*',
            '*wp-content/plugins/geodirectory/assets/aui/js/map.min.js*',
            '*wp-content/plugins/geodirectory/assets/js/goMap.min.js*',
            '*wp-content/plugins/geodirectory/assets/js/geodir-map*',
            'div[class*="geodir_map_container"]',
            'build_map_ajax_search_param',
            'geodir_show_sub_cat_collapse_button',
            // [Plugin Comp] https://themeforest.net/user/mikado-themes
            'div[class*="mkdf-mwl-map"]',
            // [Plugin Comp] Impreza WP Bakery
            'div[class*="w-map provider_google"]',
            // [Plugin Comp] https://wordpress.org/plugins/bold-page-builder/
            'div[class*="bt_bb_google_maps_map"]',
            // [Plugin Comp] https://bricksbuilder.io/
            'div[class*="brxe-map"]',
            '*/wp-content/themes/bricks/assets/js/libs/infobox*',
            // [Plugin Comp] https://bold-themes.com/wordpress-themes-plugins/
            'div[class*="btGoogleMapsWrapper"]',
            '*/wp-content/plugins/industrial/bt_gmap*',
            'bt_gmap_init',
            // [Plugin Comp] Breakdance Page Builder
            'div[class*="bde-google-map":visualParent(value=children:iframe),keepAttributes(value=class),style(this=height:auto!important;)]',
            // [Plugin Comp] https://wordpress.org/plugins/estatik/ (and estaik-pro)
            '*/wp-content/plugins/estatik*/assets/js/custom/map*',
            '*/wp-content/plugins/estatik*/assets/js/custom/shortcode-property-map*',
            'div[class*="es-widget-map-wrap"]',
            // [Plugin Comp] https://www.elegantthemes.com/marketplace/supreme-maps-pro/
            '*/wp-content/plugins/supreme-maps-pro/assets/google/*',
            'div[class*="supreme_google_map_container"]',
            // [Plugin Comp] https://themedraft.net/
            '*wp-content/plugins/themedraft-core/elementor-widgets/assets/js/gmap.js*',
            'div[class*="elementor-widget-themedraft-map"]',
            'div[class*="td-map-wrapper"]',
            // [Plugin Comp] https://wordpress.org/plugins/wd-google-maps/
            '*/wp-content/plugins/wd-google-maps/js/*',
            'gmwdInitMainMap',
            'div[class*="gmwd_container_wrapper"]',
            // [Plugin Comp] https://themeforest.net/item/dejure-responsive-wp-theme-for-law-firm-business/11228701
            'div[class*="advanced-gmaps":keepAttributes(value=class),jQueryHijackEach()]',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-maps.png')];
    }
}
