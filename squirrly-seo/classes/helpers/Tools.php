<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * Handles the parameters and url
 *
 * @author Squirrly
 */
class SQ_Classes_Helpers_Tools
{

    /**
     * 
     *
     * @var array Options, User Metas, Package and Plugin details 
     */
    public static $options, $usermeta, $allplugins = array();

    public function __construct()
    {
        self::$options = $this->getOptions();

	    //Load the languages pack
	    add_action("init", array($this, 'loadMultilanguage'));

	    SQ_Classes_ObjController::getClass('SQ_Classes_HookController')->setHooks($this);
    }

    /**
     * This hook will save the current version in database
     *
     * @return void
     */
    function hookInit()
    {

        //If the token si not yet set, run the instalation process
        //check the User Roles, DB and DevKit
        if(!self::getOption('sq_api')){
            self::install();
        }

        //add extra links to the plugin in the Plugins list
        add_filter("plugin_row_meta", array($this, 'hookExtraLinks'), 10, 4);
        //add setting link in plugin
        add_filter('plugin_action_links', array($this, 'hookActionlink'), 5, 2);
    }

    /**
     * Install the required data
     *
     * @return void
     */
    public static function install(){

        //Add the Squirrly User Role
        SQ_Classes_ObjController::getClass('SQ_Models_RoleManager')->addSQRoles();

        //Create Qss table if not exists
        SQ_Classes_ObjController::getClass('SQ_Models_Qss')->checkTableExists();

        //This option is use for custom Package installs
        //update text in case of devkit
        SQ_Classes_ObjController::getClass('SQ_Classes_Helpers_DevKit')->checkPluginData();

    }

    /**
     * Add a link to settings in the plugin list
     *
     * @param  array  $links
     * @param  string $file
     * @return array
     */
    public function hookActionlink($links, $file)
    {
        if ($file == _SQ_PLUGIN_NAME_ . '/squirrly.php') {
            $link = '<a href="' . self::getAdminUrl('sq_dashboard') . '">' . esc_html__("Getting started", 'squirrly-seo') . '</a>';
            array_unshift($links, $link);
        }

        return $links;
    }

    /**
     * Adds extra links to plugin  page
     *
     * @param  $meta
     * @param  $file
     * @param  $data
     * @param  $status
     * @return array
     */
    public function hookExtraLinks($meta, $file, $data = null, $status = null)
    {
        if ($file == _SQ_PLUGIN_NAME_ . '/squirrly.php') {
            echo '<style>
                .ml-stars{display:inline-block;color:#ffb900;position:relative;top:3px}
                .ml-stars svg{fill:#ffb900}
                .ml-stars svg:hover{fill:#ffb900}
                .ml-stars svg:hover ~ svg{fill:none}
            </style>';

            $meta[] = "<a href='https://howto12.squirrly.co/kb/install-squirrly-seo-plugin/' target='_blank'>" . esc_html__("Documentation", 'squirrly-seo') . "</a>";
            $meta[] = "<a href='https://wordpress.org/support/plugin/squirrly-seo/reviews/#new-post' target='_blank' title='" . esc_html__("Leave a review", 'squirrly-seo') . "'><i class='ml-stars'><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg><svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg></i></a>";
        }
        return $meta;
    }

    /**
     * Load the Options from user option table in DB
     *
     * @param string $action
     * @return array|mixed|object
     */
    public static function getOptions($action = '')
    {
        $default = array(
            //Global settings
            'sq_api' => '',
            'sq_installed' => date('Y-m-d H:i:s'),
            //
            'sq_cloud_connect' => 0,
            'sq_cloud_token' => false,
            'sq_notification' => true,
            'sq_alert_overview' => true,
            'sq_alert_journey' => true,
            //Advanced settings
            'sq_seoexpert' => 0,
            //later buffer load
            'sq_laterload' => 0,
            'sq_audit_email' => '',

            //SEO Journey
            'sq_seojourney' => 0,
            'sq_seojourney_congrats' => 1,
            'sq_menu_visited' => array(),

            //minify Squirrly Metas
            'sq_load_css' => 1,
            'sq_minify' => 0,
            'sq_non_utf8_support' => 1,

            //Settings Assistant
            'sq_assistant' => 1,
            'sq_complete_uninstall' => 0,

            //Onboarding
            'sq_mode' => 0,
            'sq_onboarding' => 0,
            'sq_onboarding_data' => array(),

            //Live Assistant
            'sq_sla' => 1,
            'sq_sla_frontend' => 0,
            'sq_sla_type' => 'auto',
            'sq_sla_exclude_post_types' => array(),
            'sq_keyword_help' => 1,
            'sq_local_images' => 0,
            'sq_img_licence' => 1,
            'sq_sla_social_fetch' => 0,

            //JsonLD
            'sq_auto_jsonld' => 0,
            'sq_auto_jsonld_local' => 0,
            'sq_jsonld_type' => 'Organization',
            'sq_jsonld_personal' => 0,
            'sq_jsonld_global_person' => 0,
            'sq_jsonld_breadcrumbs' => 1,
            'sq_jsonld_woocommerce' => 1,
            'sq_jsonld_clearcode' => 0,
            'sq_jsonld_product_rating' => 0,
            'sq_jsonld_product_custom' => 1,
            'sq_jsonld_product_defaults' => 0,
            'sq_jsonld_local' => array(
                'priceRange' => '',
                'servesCuisine' => '',
                'menu' => '',
                'acceptsReservations' => '',
                'openingHoursSpecification' => array(
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Monday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Tuesday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Wednesday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Thursday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Friday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Saturday',
                        'opens' => '',
                        'closes' => '',
                    ),
                    array(
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => 'Sunday',
                        'opens' => '',
                        'closes' => '',
                    ),

                ),
            ),
            'sq_jsonld' => array(
                'Organization' => array(
                    'name' => '',
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => '',
                    ),
                    'contactPoint' => array(
                        '@type' => 'ContactPoint',
                        'telephone' => '',
                        'contactType' => '',
                    ),
                    'address' => array(
                        '@type' => 'PostalAddress',
                        'streetAddress' => '',
                        'addressLocality' => '',
                        'addressRegion' => '',
                        'postalCode' => '',
                        'addressCountry' => '',
                    ),
                    'place' => array(
                        '@type' => 'Place',
                        'geo' => array(
                            '@type' => 'GeoCoordinates',
                            'latitude' => '',
                            'longitude' => '',
                        ),

                    ),

                    'description' => ''
                ),
                'Person' => array(
                    'name' => '',
                    'image' => array(
                        '@type' => 'ImageObject',
                        'url' => '',
                    ),
                    'telephone' => '',
                    'jobTitle' => '',
                    'description' => ''
                )),

            //Indexnow api
            'sq_auto_indexnow' => true,
            'indexnow_key' => false,
            'indexnow_post_type' => array('post'),

            //Sitemap
            'sq_auto_sitemap' => 0,
            'sq_sitemap_ping' => 0,
            'sq_sitemap_exclude_noindex' => 0,
            'sq_sitemap_show' => array(
                'images' => 1,
                'videos' => 1,
            ),
            'sq_sitemap_perpage' => 500,
            'sq_sitemap_frequency' => 'weekly',
            'sq_sitemap_combinelangs' => 0,
            'sq_sitemap' => array(
                'sitemap' => array('sitemap.xml', 1),
                'sitemap-home' => array('sitemap-home.xml', 1),
                'sitemap-news' => array('sitemap-news.xml', 0),
                'sitemap-product' => array('sitemap-product.xml', 1),
                'sitemap-post' => array('sitemap-posts.xml', 1),
                'sitemap-page' => array('sitemap-pages.xml', 1),
                'sitemap-category' => array('sitemap-categories.xml', 1),
                'sitemap-post_tag' => array('sitemap-tags.xml', 1),
                'sitemap-archive' => array('sitemap-archives.xml', 1),
                'sitemap-author' => array('sitemap-authors.xml', 0),
                'sitemap-custom-tax' => array('sitemap-custom-taxonomies.xml', 0),
                'sitemap-custom-post' => array('sitemap-custom-posts.xml', 0),
                'sitemap-attachment' => array('sitemap-attachment.xml', 0),
            ),

            //Robots
            'sq_auto_robots' => 0,
            'sq_robots_permission' => array(
                'User-agent: *',
                'Disallow: */trackback/',
                'Disallow: */xmlrpc.php',
                'Disallow: /wp-*.php',
                'Disallow: /cgi-bin/',
                'Disallow: /wp-admin/',
                'Allow: */wp-content/uploads/',),

            //Metas
            'sq_use' => 1,
            'sq_auto_metas' => 0,
            'sq_auto_links' => 0,
            'sq_auto_redirects' => 1,
            'sq_auto_title' => 1,
            'sq_auto_description' => 1,
            'sq_auto_keywords' => 1,
            'sq_keywordtag' => 0,
            'sq_auto_canonical' => 1,
            'sq_auto_dublincore' => 0,
            'sq_auto_feed' => 0,
            'sq_auto_noindex' => 1,
            'sq_use_frontend' => 0,
            'sq_attachment_redirect' => 0,
            'sq_term_noindex_empty' => 1,
            '404_url_redirect' => home_url(),
            'sq_external_nofollow' => 1,
            'sq_external_exception' => array(),
            'sq_external_blank' => 0,
            'sq_metas' => array(
                'title_maxlength' => 75,
                'description_maxlength' => 320,
                'og_title_maxlength' => 75,
                'og_description_maxlength' => 200,
                'tw_title_maxlength' => 75,
                'tw_description_maxlength' => 280,
                'jsonld_title_maxlength' => 75,
                'jsonld_description_maxlength' => 320,
            ),

            //favicon
            'sq_auto_favicon' => 0,
            'sq_favicon_apple' => 1,
            'favicon' => '',

            //Ranking Option
            'sq_google_country' => 'com',
            'sq_google_language' => 'en',
            'sq_google_device' => 'desktop',
            'sq_google_serpsperhour' => 500,
            'connect' => array(
                'google_analytics' => 0,
                'google_search_console' => 0,
            ),

            // dev kit
            'sq_auto_devkit' => 1,
            'sq_devkit_logo' => false,
            'sq_devkit_name' => false,
            'sq_devkit_menu_name' => false,
            'sq_devkit_audit_success' => false,
            'sq_devkit_audit_fail' => false,
            //menu restrictions
            'menu' => array(
                'show_account_info' => 1,
                'show_seogoals' => 1,
                'show_features' => 1,
                'show_onpagesetup' => 1,
                'show_journey' => 1,
                'show_research' => 1,
                'show_assistant' => 1,
                'show_automation' => 1,
                'show_bulkseo' => 1,
                'show_focuspages' => 1,
                'show_audit' => 1,
                'show_rankings' => 1,
                'show_tutorial' => 1,
                'show_seo' => 1,
                'show_ads' => 1,
            ),

            //socials
            'sq_auto_facebook' => 0,
            'sq_auto_twitter' => 0,
            'sq_og_locale' => 'en_US',
            'sq_og_image' => '',
            'sq_tc_image' => '',

            'socials' => array(
                'fb_admins' => array(),
                'fbconnectkey' => "",
                'fbadminapp' => "",

                'facebook_site' => "",
                'twitter_site' => "https://twitter.com/twitter",
                'twitter' => "",
                'instagram_url' => "",
                'linkedin_url' => "",
                'myspace_url' => "",
                'pinterest_url' => "",
                'youtube_url' => "",
                'twitter_card_type' => "summary_large_image",
                'plus_publisher' => ""
            ),

            //Webmasters and Tracking
            'sq_auto_amp' => 0,
            'sq_auto_tracking' => 0,
            'sq_auto_pixels' => 0,
            'sq_tracking_logged_users' => 0,
            'sq_tracking_ip_users' => 1,
            'sq_auto_webmasters' => 0,
            'sq_analytics_google_js' => 'analytics',
            'codes' => array(
                'google_wt' => "",
                'google_analytics' => "",
                'facebook_pixel' => "",

                'bing_wt' => "",
                'baidu_wt' => "",
                'yandex_wt' => "",
                'pinterest_verify' => "",
                'norton_verify' => "",
            ),

            //Patterns
            'sq_auto_pattern' => 0,
            'patterns' => array(
                'home' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{sitename}} {{page}} {{sep}} {{sitedesc}}',
                    'description' => '{{excerpt}} {{page}} {{sep}} {{sitename}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'post' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'article',
                    'jsonld_types' => array('newsarticle'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 1,
                    'google_news' => 1,
                ),
                'page' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 1,
                    'google_news' => 0,
                ),
                'product' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'product',
                    'jsonld_types' => array('product'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 1,
                    'google_news' => 0,
                ),
                'category' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{category}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{category_description}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 0,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tag' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{tag}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 0,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-product_cat' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Category", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-product_tag' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Tag", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-post_format' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Format", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-category' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Category", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-post_tag' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Tag", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'tax-product_shipping_class' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{term_title}} ' . esc_html__("Shipping Option", 'squirrly-seo') . ' {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'shop' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'profile' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{name}}, ' . esc_html__("Author at", 'squirrly-seo') . ' {{sitename}} {{page}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'profile',
                    'jsonld_types' => array('profile'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'archive' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{plural}} {{date}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'search' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => esc_html__("Are you looking for", 'squirrly-seo') . ' {{searchphrase}}? {{page}} {{sep}} {{sitename}}',
                    'description' => esc_html__("These are the results for", 'squirrly-seo') . ' {{searchphrase}} ' . esc_html__("that you can find on our website.", 'squirrly-seo') . ' {{excerpt}}',
                    'noindex' => 1,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 0,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                ),
                'attachment' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 1,
                ),
                '404' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => esc_html__("Page not found", 'squirrly-seo') . ' {{sep}} {{sitename}}',
                    'description' => esc_html__("This page could not be found on our website.", 'squirrly-seo') . ' {{excerpt}}',
                    'noindex' => 1,
                    'nofollow' => 1,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 0,
                    'do_jsonld' => 0,
                    'do_pattern' => 1,
                    'do_og' => 0,
                    'do_twc' => 0,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                ),
                'custom' => array(
                    'protected' => 1,
                    'sep' => '|',
                    'title' => '{{title}} {{page}} {{sep}} {{sitename}}',
                    'description' => '{{excerpt}}',
                    'noindex' => 0,
                    'nofollow' => 0,
                    'og_type' => 'website',
                    'jsonld_types' => array('website'),
                    'do_metas' => 1,
                    'do_sitemap' => 1,
                    'do_jsonld' => 1,
                    'do_pattern' => 1,
                    'do_og' => 1,
                    'do_twc' => 1,
                    'do_analytics' => 1,
                    'do_fpixel' => 1,
                    'do_redirects' => 0,
                    'google_news' => 0,
                ),
            )

        );
        $options = json_decode(get_option(SQ_OPTION), true);

        //Check the updates from old versions of the plugin
        $options = self::checkUpdates($options);

        //Replace the default options with the database data
        if (is_array($options)) {
            $options = array_replace_recursive((array)$default, (array)$options);

            //Add recommended settings
            if ($action == 'reset') {
                $options['sq_auto_jsonld'] = 0;
                $options['sq_auto_sitemap'] = 0;
                $options['sq_sitemap_exclude_noindex'] = 0;
                $options['sq_sitemap_ping'] = 0;
                $options['sq_attachment_redirect'] = 0;
                $options['sq_auto_metas'] = 0;
                $options['sq_auto_amp'] = 0;
                $options['sq_auto_robots'] = 0;
                $options['sq_auto_facebook'] = 0;
                $options['sq_sla_social_fetch'] = 0;
                $options['sq_auto_twitter'] = 0;
                $options['sq_auto_tracking'] = 0;
                $options['sq_auto_pixels'] = 0;
                $options['sq_auto_webmasters'] = 0;
                $options['sq_auto_pattern'] = 0;
                $options['sq_use_frontend'] = 0;
                $options['sq_auto_favicon'] = 0;
            }

            //Add recommended settings
            if ($action == 'recommended') {
                $options['sq_auto_jsonld'] = 1;
                $options['sq_auto_sitemap'] = 1;
                $options['sq_sitemap_exclude_noindex'] = 1;
                $options['sq_sitemap_ping'] = 1;
                $options['sq_attachment_redirect'] = 1;
                $options['sq_auto_metas'] = 1;
                $options['sq_auto_amp'] = 1;
                $options['sq_auto_robots'] = 1;
                $options['sq_auto_facebook'] = 1;
                $options['sq_sla_social_fetch'] = 1;
                $options['sq_auto_twitter'] = 1;
                $options['sq_auto_tracking'] = 1;
                $options['sq_auto_pixels'] = 1;
                $options['sq_auto_webmasters'] = 1;
                $options['sq_auto_pattern'] = 1;
                $options['sq_use_frontend'] = 1;
            }

            return $options;
        }

        return $default;
    }

    /**
     * Check the updates
     * @return array
     */
    public static function checkUpdates($options){
        //Update the Json-LD for Organization Logo
        if (isset($options['sq_jsonld']['Organization']['logo']) && !is_array($options['sq_jsonld']['Organization']['logo'])) {
            $options['sq_jsonld']['Organization']['logo'] = array(
                '@type' => 'ImageObject',
                'url' => $options['sq_jsonld']['Organization']['logo'],
            );
        }

        if (isset($options['sq_jsonld']['Organization']['telephone']) && isset($options['sq_jsonld']['Organization']['contactType'])) {
            $options['sq_jsonld']['Organization']['contactPoint'] = array(
                '@type' => 'ContactPoint',
                'telephone' => $options['sq_jsonld']['Organization']['telephone'],
                'contactType' => $options['sq_jsonld']['Organization']['contactType']
            );

            unset($options['sq_jsonld']['Organization']['telephone']);
            unset($options['sq_jsonld']['Organization']['contactType']);
        }

        //Update the Json-LD for Person Image
        if (isset($options['sq_jsonld']['Person']['logo'])) {
            $options['sq_jsonld']['Person']['image'] = array(
                '@type' => 'ImageObject',
                'url' => $options['sq_jsonld']['Person']['logo'],
            );

            unset($options['sq_jsonld']['Person']['logo']);
        }


        return $options;
    }

    /**
     * Get the option from database
     *
     * @param  $key
     * @return mixed
     */
    public static function getOption($key)
    {
        if (!isset(self::$options[$key])) {
            self::$options = self::getOptions();

            if (!isset(self::$options[$key])) {
                self::$options[$key] = false;
            }
        }

        return apply_filters('sq_option_' . $key, self::$options[$key]);
    }

    /**
     * Save the Options in user option table in DB
     *
     * @param null   $key
     * @param string $value
     */
    public static function saveOptions($key = null, $value = '')
    {
        if (isset($key)) {
            self::$options[$key] = $value;
        }

        update_option(SQ_OPTION, wp_json_encode(self::$options));
    }

    /**
     * Get user metas
     *
     * @param  null $user_id
     * @return array|mixed
     */
    public static function getUserMetas($user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }

        $default = array('sq_auto_sticky' => 0,);

        $usermeta = get_user_meta($user_id);
        $usermetatmp = array();
        if (is_array($usermeta)) foreach ($usermeta as $key => $values) {
            $usermetatmp[$key] = $values[0];
        }
        $usermeta = $usermetatmp;

        if (is_array($usermeta)) {
            $usermeta = array_merge((array)$default, (array)$usermeta);
        } else {
            $usermeta = $default;
        }
        self::$usermeta = $usermeta;
        return $usermeta;
    }

    /**
     * Get use meta
     *
     * @param  $value
     * @return bool
     */
    public static function getUserMeta($value)
    {
        if (!isset(self::$usermeta[$value])) {
            self::getUserMetas();
        }

        if (isset(self::$usermeta[$value])) {
            return apply_filters('sq_usermeta_' . $value, self::$usermeta[$value]);
        }

        return false;
    }

    /**
     * Save user meta
     *
     * @param $key
     * @param $value
     * @param null $user_id
     */
    public static function saveUserMeta($key, $value, $user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }
        self::$usermeta[$key] = $value;
        update_user_meta($user_id, $key, $value);
    }

    /**
     * Delete User meta
     *
     * @param $key
     * @param null $user_id
     */
    public static function deleteUserMeta($key, $user_id = null)
    {
        if (!isset($user_id)) {
            $user_id = get_current_user_id();
        }
        unset(self::$usermeta[$key]);
        delete_user_meta($user_id, $key);
    }

    /**
     * Get the option from database
     *
     * @param  $key
     * @return mixed
     */
    public static function getMenuVisible($key)
    {
        if(self::$options['sq_auto_devkit']) {
            if (!isset(self::$options['menu'][$key])) {
                self::$options = self::getOptions();

                if (!isset(self::$options['menu'][$key])) {
                    self::$options['menu'][$key] = false;
                }
            }

            return apply_filters('sq_menu_visible', self::$options['menu'][$key], $key);
        }

        return true;
    }

    /**
     * Set the header type
     *
     * @param string $type
     */
    public static function setHeader($type)
    {
        if (self::getValue('sq_debug') == 'on') {
            // header("Content-type: text/html");
            return;
        }

        switch ($type) {
        case 'json':
            header('Content-Type: application/json');
            break;
        case 'ico':
            header('Content-Type: image/x-icon');
            break;
        case 'png':
            header('Content-Type: image/png');
            break;
        case'text':
            header("Content-type: text/plain");
            break;
        case'html':
            header("Content-type: text/html");
            break;
        }
    }

    /**
     * Set the Nonce action
     *
     * @param  $action
     * @param  string $name
     * @param  bool   $referer
     * @param  bool   $echo
     * @return string
     */
    public static function setNonce($action, $name = '_wpnonce', $referer = true, $echo = true)
    {
        $nonce_field = '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr(wp_create_nonce($action)) . '" />';

        if ($referer) {
            $nonce_field .= wp_referer_field(false);
        }

        if ($echo) {
            echo $nonce_field;
        }

        return $nonce_field;
    }

    /**
     * Get a value from $_POST / $_GET
     * if unavailable, take a default value
     *
     * @param  string $key           Value key
     * @param  mixed  $defaultValue  (optional)
     * @param  bool   $htmlcode
     * @param  bool   $keep_newlines
     * @return mixed Value
     */
    public static function getValue($key, $defaultValue = false, $keep_newlines = false)
    {
        if (!isset($key) || (isset($key) && $key == '')) {
            return $defaultValue;
        }

        //Get the params from forms
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : ''));
        } else {
            $ret = (isset($_GET[$key]) ? $_GET[$key] : '');
        }

        //Start sanitization of each param
        //based on the type
        if (is_array($ret)) { //if array, sanitize each value from the array
            if (!empty($ret)) {
                foreach ($ret as &$row) {
                    if (!is_array($row)) {
                        $row = sanitize_text_field($row); //sanitize
                    }
                }
            }
        } elseif (is_string($ret) && $ret <> '' && $keep_newlines && function_exists('sanitize_textarea_field')) {
            $ret = sanitize_textarea_field($ret);
        }  else{
            $ret = sanitize_text_field($ret);
        }

        if (!$ret) {
            return $defaultValue;
        } else {
            return wp_unslash($ret);
        }

    }

    /**
     * Check if the parameter is set
     *
     * @param  string $key
     * @return boolean
     */
    public static function getIsset($key)
    {
        return (isset($_GET[$key]) || isset($_POST[$key]));
    }


    /**
     * Find the string in the content
     *
     * @param  string $content
     * @param  string $string
     * @param  bool   $normalize
     * @return bool|false|int
     */
    public static function findStr($content, $string, $normalize = false)
    {
        if ($normalize) {
            //Check if the search requires char normalization
            $content = SQ_Classes_Helpers_Sanitize::normalizeChars($content);
            $string = SQ_Classes_Helpers_Sanitize::normalizeChars($string);
        } else {
            //decode the content to match quotes and special chars
            $content = html_entity_decode($content, ENT_QUOTES);
            $string = html_entity_decode($string, ENT_QUOTES);
        }

        if (function_exists('mb_stripos')) {
            return mb_stripos($content, $string);
        } else {
            SQ_Classes_Error::setMessage(esc_html__("For better text comparison you need to install PHP mbstring extension.", 'squirrly-seo'));

            return stripos($content, $string);
        }
    }

    /**
     * Get the first key of the given array without affecting
     * the internal array pointer
     */
    public static function arrayKeyFirst( array $arr ) {
        foreach ( $arr as $key => $value ) {
            return $key;
        }
    }

    /**
     * Load the multilanguage support from .mo
     */
	public function loadMultilanguage()
    {
	    if (!defined('WP_PLUGIN_DIR') ) {
		    load_plugin_textdomain(_SQ_PLUGIN_NAME_, _SQ_PLUGIN_NAME_ . '/languages/');
	    } else {
		    load_plugin_textdomain(_SQ_PLUGIN_NAME_, null, _SQ_PLUGIN_NAME_ . '/languages/');
	    }

    }

    /**
     * Hook the activate process
     */
    public function sq_activate()
    {
        set_transient('sq_activate', true);
        set_transient('sq_import', true);

        //Install & Initialize
        self::install();

    }

    /**
     * Hook the deactivate process
     */
    public function sq_deactivate()
    {
        SQ_Classes_ObjController::getClass('SQ_Models_RoleManager')->removeSQCaps();
        SQ_Classes_ObjController::getClass('SQ_Models_RoleManager')->removeSQRoles();
    }

    /**
     * Empty the cache from other plugins
     */
    public static function emptyCache()
    {
        try {
            //////////////////////////////////////////////////////////////////////////////
            if (function_exists('w3tc_pgcache_flush')) {
                w3tc_pgcache_flush();
            }

            if (function_exists('w3tc_minify_flush')) {
                w3tc_minify_flush();
            }
            if (function_exists('w3tc_dbcache_flush')) {
                w3tc_dbcache_flush();
            }
            if (function_exists('w3tc_objectcache_flush')) {
                w3tc_objectcache_flush();
            }
            //////////////////////////////////////////////////////////////////////////////

            if (function_exists('wp_cache_clear_cache')) {
                wp_cache_clear_cache();
            }

            if (function_exists('rocket_clean_domain') && function_exists('rocket_clean_minify') && function_exists('rocket_clean_cache_busting')) {
                // Remove all cache files
                rocket_clean_domain();
                rocket_clean_minify();
                rocket_clean_cache_busting();
            }
            //////////////////////////////////////////////////////////////////////////////

            if (function_exists('apc_clear_cache')) {
                // Remove all apc if enabled
                apc_clear_cache();
            }
            //////////////////////////////////////////////////////////////////////////////

            if (class_exists('Cache_Enabler_Disk') && method_exists('Cache_Enabler_Disk', 'clear_cache')) {
                // clear disk cache
                Cache_Enabler_Disk::clear_cache();
            }
            //////////////////////////////////////////////////////////////////////////////

            if (class_exists('LiteSpeed_Cache')) {
                LiteSpeed_Cache::get_instance()->purge_all();
            }
            //////////////////////////////////////////////////////////////////////////////

            if (self::isPluginInstalled('hummingbird-performance/wp-hummingbird.php')) {
                do_action('wphb_clear_page_cache');
            }
            //////////////////////////////////////////////////////////////////////////////

            if (class_exists('WpeCommon')) {
                if (method_exists('WpeCommon', 'purge_memcached')) {
                    WpeCommon::purge_memcached();
                }
                if (method_exists('WpeCommon', 'clear_maxcdn_cache')) {
                    WpeCommon::clear_maxcdn_cache();
                }
                if (method_exists('WpeCommon', 'purge_varnish_cache')) {
                    WpeCommon::purge_varnish_cache();
                }
            }
            //////////////////////////////////////////////////////////////////////////////

            if (self::isPluginInstalled('sg-cachepress/sg-cachepress.php') && class_exists('Supercacher')) {
                if (method_exists('Supercacher', 'purge_cache') && method_exists('Supercacher', 'delete_assets')) {
                    Supercacher::purge_cache();
                    Supercacher::delete_assets();
                }
            }

            //Clear the fastest cache
            global $wp_fastest_cache;
            if (isset($wp_fastest_cache) && method_exists($wp_fastest_cache, 'deleteCache')) {
                $wp_fastest_cache->deleteCache();
            }
            //////////////////////////////////////////////////////////////////////////////
        } catch (Exception $e) {

        }
    }

    /**
     * Check if it's Ajax Call
     * @return bool
     */
    public static function isAjax()
    {
        return (defined('DOING_AJAX') && DOING_AJAX);
    }

    /**
     * Check if a plugin is installed
     *
     * @param  $name
     * @return bool
     */
    public static function isPluginInstalled($name)
    {
        if (empty(self::$allplugins)) {
            self::$allplugins = (array)get_option('active_plugins', array());

            if (is_multisite()) {
                self::$allplugins = array_merge(array_values(self::$allplugins), array_keys(get_site_option('active_sitewide_plugins')));
            }
        }

        if (!empty(self::$allplugins)) {
            return in_array($name, self::$allplugins, true);
        }

        return false;
    }

    /**
     * Check whether the theme is active.
     *
     * @param string $theme Theme folder/main file.
     *
     * @return boolean
     */
    public static function isThemeActive( $theme )
    {
        if (function_exists('wp_get_theme') ) {
            $themes = wp_get_theme();

            if (isset($themes->name) && stripos($themes->name, $theme) !== false) {
                return true;
            }

            if (isset($themes->template) && stripos($themes->template, $theme) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if frontend and user is logged in
     *
     * @return bool
     */
    public static function isFrontAdmin()
    {
        return (!is_admin() && (function_exists('is_user_logged_in') && is_user_logged_in()));
    }

    /**
     * Check if user is in dashboard
     *
     * @return bool
     */
    public static function isBackedAdmin()
    {
        return (is_admin() || is_network_admin());
    }

    /**
     * Check if the current website is an E-commerce website
     *
     * @return bool
     */
    public static function isEcommerce()
    {

        if(self::isPluginInstalled('woocommerce/woocommerce.php')) {
            return true;
        }

        $products = array('product', 'wpsc-product');
        $post_types = get_post_types(array('public' => true));

        foreach ($products as $type) {
            if (in_array($type, array_keys($post_types))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if it's an AMP Endpoint
     *
     * @return bool|void
     */
    public static function isAMPEndpoint()
    {
        if (defined('AMPFORWP_AMP_QUERY_VAR')) {
            $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
            $explode_path = explode('/', $url_path);
            if (AMPFORWP_AMP_QUERY_VAR === end($explode_path)) {
                return true;
            }
        }

        if (function_exists('is_amp_endpoint')) {
            return is_amp_endpoint();
        }

        if (function_exists('is_amp') && is_amp()) {
            return is_amp();
        }

        if (function_exists('ampforwp_is_amp_endpoint')) {
            return ampforwp_is_amp_endpoint();
        }

        return false;
    }

    public static function isBlockEditor()
    {
        // Check WordPress version.
        if ( version_compare( get_bloginfo( 'version' ), '5.0.0', '<' ) ) {
            return false;
        }

        $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : false;

        if ( ! $screen instanceof WP_Screen ) {
            return false;
        }

        if ( method_exists( $screen, 'is_block_editor' ) ) {
            return $screen->is_block_editor();
        }

        if ( 'post' === $screen->base ) {
            if ( ! post_type_exists( $screen->post_type  ) ) {
                return false;
            }

            if ( ! post_type_supports( $screen->post_type , 'editor' ) ) {
                return false;
            }

            $post_type_object = get_post_type_object( $screen->post_type  );
            if ( $post_type_object && ! $post_type_object->show_in_rest ) {
                return false;
            }

            return apply_filters( 'use_block_editor_for_post_type', true, $screen->post_type  );
        }

        return false;
    }

    /**
     * Check the user capability for the roles attached
     *
     * @param  $cap
     * @param  mixed ...$args
     * @return bool
     */
    public static function userCan($cap, ...$args )
    {

        if (current_user_can($cap, ...$args)) {
            return true;
        }

        $user = wp_get_current_user();
        if (count((array)$user->roles) > 1) {
            foreach ($user->roles as $role) {
                $role_object = get_role($role);
                if ($role_object->has_cap($cap)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get the admin url for the specific age
     *
     * @param  string $page
     * @param  string $tab
     * @param  array  $args
     * @return string
     */
    public static function getAdminUrl($page, $tab = null, $args = array())
    {
        if (strpos($page, '.php')) {
            $url = admin_url($page);
        } else {
            $url = admin_url('admin.php?page=' . $page);
        }

        if (isset($tab) && $tab <> '') {
            $url .= '&tab=' . $tab;
        }

        if (!empty($args)) {
            if (strpos($url, '?') !== false) {
                $url .= '&';
            } else {
                $url .= '?';
            }
            $url .= join('&', $args);
        }

        return apply_filters('sq_menu_url', $url, $page, $tab, $args);
    }

    /**
     * Instantiates the WordPress filesystem.
     *
     * @static
     * @access public
     * @return object
     */
    public static function initFilesystem()
    {
        // The WordPress filesystem.
        global $wp_filesystem;

        if (! function_exists('WP_Filesystem') ) {
            include_once ABSPATH . 'wp-admin/includes/file.php';
        }

        WP_Filesystem();

        if (!$wp_filesystem->connect()) {
            add_filter( 'filesystem_method', function ($method) {
                return 'direct';
            }, 1
            );
            WP_Filesystem();
        }

        return $wp_filesystem;
    }
}
