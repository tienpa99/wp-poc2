<?php
/**
 * ArubaHiSpeedCacheWpPurger
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     \ArubaHiSpeedCache\Run_Aruba_Hispeed_cache()
 * @since    1.0.0
 */

namespace ArubaHiSpeedCache\includes;

use ArubaHiSpeedCache\ArubaHiSpeedCachePurger;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheAdmin;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs;
use \WP_Comment;
use \WP_Post;
use \WP_Term;

use \get_permalink;
use \wp_get_attachment_url;
use \home_url;
use \get_taxonomy;
use \get_ancestors;
use \get_term;
use \user_trailingslashit;
use \trailingslashit;
use \get_term_link;
use \current_filter;
use \current_user_can;
use \wp_die;
use \__;
use \add_action;
use \check_admin_referer;
use \add_query_arg;
use \is_admin;
use \wp_parse_url;
use \wp_redirect;
use \esc_url_raw;
use \icl_get_home_url;
use \is_multisite;
use \get_current_blog_id;
use \get_site_url;
use \wp_is_post_autosave;
use \str_replace;
use \in_array;
use \array_reverse;
use \implode;
use \filter_input;
use \filter_var;
use \sprintf;
use \_get_list_table;

if (!class_exists(__NAMESPACE__ . '\ArubaHiSpeedCacheWpPurger')) {
    /**
     * ArubaHiSpeedCacheWpPurger
     *
     * @category ArubaHiSpeedCache
     * @package  ArubaHiSpeedCache
     * @author   Aruba Developer <hispeedcache.developer@aruba.it>
     * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
     * @link     null
     * @since    1.0.0
     */
    class ArubaHiSpeedCacheWpPurger extends ArubaHiSpeedCachePurger
    {
        /**
         * Settings The plugin settings.
         *
         * @since 1.0.0
         * @var   array
         */
        // phpcs:ignore
        private $settings;

        /**
         * Adimin utils
         *
         * @since 1.0.0
         *
         * @var \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheAdmin
         */
        // phpcs:ignore
        private $admin;

        /**
         * Control variable. Check if the cache has already
         * been cleaned by the same process.
         *
         * @var bool
         */
        // phpcs:ignore
        private $is_purged = false;

        /**
         * ArubaHiSpeedCacheWpPurger
         *
         * @param \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheAdmin $admin admin utils
         */
        public function __construct($admin)
        {
            $this->setPurger(
                array(
                'time_out'     => ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PURGE_TIME_OUT'), // phpcs:ignore
                'server_host'  => ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PURGE_HOST'), // phpcs:ignore
                'server_port'  => ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PURGE_PORT') // phpcs:ignore
                )
            );

            //@todo We should think about how to remove this dispensation.
            $this->admin = $admin;
            $this->settings = $admin->options;
        }

        /**
         * PurgeUrl
         * Purge the cache of url passed.
         *
         * @param string $url page to purge.
         *
         * @return void
         */
        public function purgeUrl($url)
        {
            // $site_url = $this->getParseSiteUrl();
            // $host = $site_url['host'];

            $_url = \filter_var($url, FILTER_SANITIZE_URL);

            //Logger
            logger('targhet ' . $_url, 'purgeUrl()', 'info');
            ///Logger

            $this->doRemoteGet($_url);

            $this->flush_wp_object_cache();

            return true;
        }

        /**
         * PurgeUrls
         * Purge the cache of urls passed
         *
         * @param array $urls urls to purge
         *
         * @return void
         */
        public function purgeUrls($urls)
        {
            // $site_url = $this->getParseSiteUrl();
            // $host = $site_url['host'];

            foreach ($urls as $url) {
                $this->doRemoteGet($url);

                //Logger
                logger('targhet ' . $url, 'purgeUrls()', 'info');
                ///Logger
            }

            $this->flush_wp_object_cache();

            return true;
        }

        /**
         * PurgeAll
         * Purge all site
         *
         * @return void
         */
        public function purgeAll()
        {
            // $site_url = $this->getParseSiteUrl();
            // $host = $site_url['host'];

            //Logger
            logger('targhet /', 'purgeAll()', 'info');
            ///Logger

            $this->doRemoteGet('/');

            $this->flush_wp_object_cache();

            return true;
        }

        /**
         * DoRemoteGet
         * Make request to purger
         *
         * @param string $target The subdomine of page to purge
         *
         * @return void
         */
        public function doRemoteGet($target = '/')
        {
            $purgeUrl = $this->preparePurgeRequestUri($target);

            $blog_id = null;

            if (is_multisite()) {
                $blog_id = \get_current_blog_id();
            }

            $host = \wp_parse_url(\get_site_url($blog_id));
            $host = $host['host'];

            $response = \wp_remote_get(
                $purgeUrl,
                array(
                    'timeout'   => $this->timeOut,
                    'headers' => array(
                        'Host' => $host
                        )
                )
            );

            return $response;
        }

        /**
         * Undocumented function
         *
         * @return void
         */
        public function flush_wp_object_cache()
        {
            $wp_object_cache = \wp_cache_flush();
            if ($wp_object_cache) {
                //Logger
                logger('flush wp_object_cache with success....', 'info');
                ///Logger
            }
            return $wp_object_cache;
        }

        /**
         * GetParseSiteUrl
         *
         * @return void
         */
        public function getParseSiteUrl()
        {
            $blog_id = null;

            if (!is_multisite()) {
                $blog_id = \get_current_blog_id();
            }

            return \wp_parse_url(\get_site_url($blog_id));
        }

        /**
         * ParseUrl
         *
         * @param string $url Url to parse
         *
         * @return string
         */
        public function parseUrl($url)
        {
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return ($url[0] != '/') ? '/' . $url : $url;
            }

            $parse = \wp_parse_url($url); // for php 5.3

            return \trailingslashit($parse['path']);
        }

        /**
         * _purge_homepage
         *
         * @return boolean true
         */
        public function getHomepage()
        {
            return \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getSiteHome(); // phpcs:ignore
        }

        /**
         * Is_enable_setting
         *
         * @param string $setting Setting to check
         *
         * @return boolean
         */
        public function is_enable_setting($setting)
        {
            return (bool) $this->settings[$setting];
        }

        //--------------
        // HOOKs SECTION
        //--------------

        /**
         * Ahsc_admin_bar_init
         * Purge the cache
         *
         * @return void
         */
        public function ahsc_admin_bar_init()
        {
            global $wp;

            // if (!$this->is_enable_setting('ahsc_enable_purge')) {
            //     return;
            // }

            // $method = \filter_input(
            //     INPUT_SERVER,
            //     'REQUEST_METHOD',
            //     FILTER_SANITIZE_URL
            // );

            // if ('POST' === $method) {
            //     $action = \filter_input(
            //         INPUT_POST,
            //         'aruba_hispeed_cache_action',
            //         FILTER_SANITIZE_URL
            //     );
            // } else {
            //     $action = \filter_input(
            //         INPUT_GET,
            //         'aruba_hispeed_cache_action',
            //         FILTER_SANITIZE_URL
            //     );
            // }

            $action = \filter_input(
                INPUT_GET,
                'aruba_hispeed_cache_action',
                FILTER_SANITIZE_URL
            );

            if (empty($action)) {
                return;
            }

            //@see https://developer.wordpress.org/reference/functions/wp_die/
            if (! \current_user_can('manage_options')) {
                \wp_die(
                    \sprintf(
                        '<h3>%s</h3><p>%s</p>',
                        \_e('An error has occurred.', 'aruba-hispeed-cache'),
                        \_e('Sorry, you do not have the necessary privileges to edit these options.', 'aruba-hispeed-cache') // phpcs:ignore
                    ),
                    '',
                    array(
                        'link_url'  => \esc_url(\ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_assistance')), // phpcs:ignore
                        'link_text' => \_e('Contact customer service', 'aruba-hispeed-cache') // phpcs:ignore
                    )
                );
            }

            if ('done' === $action) {
                \add_action('admin_notices', array( $this->admin, 'display_notices_purge_initied' )); // phpcs:ignore
                \add_action('network_admin_notices', array( $this->admin, 'display_notices_purge_initied' )); // phpcs:ignore
                return;
            }

            //@see https://developer.wordpress.org/reference/functions/check_admin_referer/
            \check_admin_referer('aruba_hispeed_cache-purge_all');

            // current url if permalink is set to simple
            $current_url = \add_query_arg($wp->query_vars, \home_url());

            // if permalink is custom.
            if (!empty($wp->request)) {
                $current_url = \user_trailingslashit(\home_url($wp->request));
            }

            //add done
            $redirect_url = \add_query_arg(
                array( 'aruba_hispeed_cache_action' => 'done' )
            );

            if (! \is_admin()) {
                $action       = 'purge_current_page';
                $redirect_url = $current_url;
            }

            switch ($action) {
                case 'purge':
                    $this->purgeAll();
                    break;
                case 'purge_current_page':

                    if (\is_front_page() || \is_home()) {
                        $this->purgeAll();
                    } else {
                        $parse_url = \wp_parse_url($current_url);
                        $url_to_purge = (!isset($parse_url['query'])) ? $parse_url['path'] : '/?' . $parse_url['query']; // phpcs:ignore
                        $this->purgeUrl($url_to_purge);
                    }

                    break;
            }

            if (\wp_redirect(\esc_url_raw($redirect_url))) {
                exit;
            }
        }

        /**
         * Ahsc_check_ajax_referer
         * Purge the cache on 'save-sidebar-widgets' ajax request
         *
         * @param int|string $action Action name
         *
         * @return void
         */
        public function ahsc_check_ajax_referer($action)
        {
            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            switch ($action) {
                case 'save-sidebar-widgets':
                    $this->purgeUrl($this->getHomepage());
                    //Logger
                    logger(
                        'targhet ' . $this->getHomepage(),
                        'hook:check_ajax_referer',
                        'info'
                    );
                    ///Logger
                    break;
                default:
                    break;
            }
        }

        /**
         * Ahsc_edit_term
         * Purge the cache of term item or home on term edit.
         *
         * @param int    $term_id The term id
         * @param int    $tt_id   The taxnomi id
         * @param string $taxon   Taxon
         *
         * @return void
         */
        public function ahsc_edit_term($term_id, $tt_id, $taxon)
        {
            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            if (!$this->is_enable_setting('ahsc_purge_archive_on_edit')) {
                return;
            }

            if ($this->is_enable_setting('ahsc_purge_homepage_on_edit')) {
                $this->purgeUrl($this->parseUrl($this->getHomepage()));
                //Logger
                logger(
                    'targhet ' . $this->parseUrl($this->getHomepage()),
                    'hook:edit_term::home',
                    'info'
                );
                ///Logger
                return;
            }

            $term_link      = \get_term_link($term_id, $taxon);

            $this->purgeUrl($this->parseUrl($term_link));
            //Logger
            logger(
                'targhet ' . $this->parseUrl($term_link),
                'hook:edit_term',
                'info'
            );
            ///Logger

            return true;
        }

        /**
         * Ahsc_delete_term
         *
         * @param integer  $term         Term
         * @param integer  $tt_id        Taxon id
         * @param string   $taxonomy     Taxon
         * @param \WP_Term $deleted_term Delete Taxon
         *
         * @return void
         */
        public function ahsc_delete_term($term, $tt_id, $taxonomy, $deleted_term)
        {
            global $wp_rewrite;

            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            if (!$this->is_enable_setting('ahsc_purge_archive_on_edit')) {
                return;
            }

            $taxonomy = $deleted_term->taxonomy;
            $termlink = $wp_rewrite->get_extra_permastruct($taxonomy);
            $slug     = $deleted_term->slug;
            $t        = \get_taxonomy($taxonomy);

            $termlink = \str_replace("%$taxonomy%", $slug, $termlink);

            if (! empty($t->rewrite['hierarchical'])) {
                $hierarchical_slugs = array();
                $ancestors          = \get_ancestors($deleted_term->term_id, $taxonomy, 'taxonomy'); // phpcs:ignore
                foreach ((array) $ancestors as $ancestor) {
                    $ancestor_term        = \get_term($ancestor, $taxonomy);
                    $hierarchical_slugs[] = $ancestor_term->slug;
                }
                $hierarchical_slugs   = array_reverse($hierarchical_slugs);
                $hierarchical_slugs[] = $slug;
                $termlink             = \str_replace("%$taxonomy%", \implode('/', $hierarchical_slugs), $termlink); // phpcs:ignore
            }

            $termlink = \user_trailingslashit($termlink, 'category');

            // purge the term cache.
            $this->purgeUrl($termlink);
            //Logger
            logger('targhet ' . $termlink, 'hook:delete_term', 'info');
            ///Logger

            if ($this->is_enable_setting('ahsc_purge_homepage_on_del')) {
                $this->purgeUrl($this->parseUrl($this->getHomepage()));
                //Logger
                logger(
                    'targhet ' . $this->parseUrl($this->getHomepage()),
                    'hook:delete_term::home',
                    'info'
                );
                ///Logger
            }

            return true;
        }

        /**
         * Ahsc_transition_post_status
         * Purge the cache of item or site on transition post status
         *
         * @param int|string $new_status new status
         * @param int|string $old_status old status
         * @param \WP_Post   $post       Post object
         *
         * @return void
         */
        public function ahsc_transition_post_status($new_status, $old_status, $post)
        {
            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            /**
             * Prevent multiple calls
             */
            if ($this->is_purged) {
                return;
            }

            $this->is_purged = true;
            // Prevent multiple calls

            /**
             * Disable on autosave
             */
            if (\wp_is_post_autosave($post)) {
                return;
            }

            /**
             * For json call
             */
            if (\wp_is_json_request()) {
                $is_multisite = \is_multisite();
                $do_purge = ($is_multisite) ? \get_site_transient('ahsc_do_purge') : \get_transient('ahsc_do_purge'); // phpcs:ignore

                if (!$do_purge) {
                    logger('transint non presente presente lo imposto', 'deffer');
                    if ($is_multisite) {
                       \set_site_transient('ahsc_do_purge', true);
                    } else {
                       \set_transient('ahsc_do_purge', true);
                    }
                    return;
                }

                logger('transint presente', 'deffer');
                return;
            }

            /**
             * Edit item
             */
            if ($this->is_enable_setting('ahsc_purge_page_on_mod') || $this->is_enable_setting('ahsc_purge_archive_on_edit')) { // phpcs:ignore
                if ($this->is_enable_setting('ahsc_purge_homepage_on_edit')) {
                    $this->purgeUrl($this->parseUrl($this->getHomepage()));
                    //Logger
                    logger(
                        'targhet ' . $this->parseUrl($this->getHomepage()),
                        'hook:transition_post_status:edit::home',
                        'info'
                    );
                    ///Logger
                    return;
                }


                $status = array( 'publish', 'future' );

                if (in_array($new_status, $status, true)) {
                    $post_url = \get_permalink($post->ID);
                    $this->purgeUrl($this->parseUrl($post_url));
                    //Logger
                    logger(
                        'targhet ' . $this->parseUrl($post_url),
                        'hook:Iif:transition_post_status',
                        'info'
                    );
                    ///Logger
                }
            }

            /**
             * Move item to trash
             */
            if ($this->is_enable_setting('ahsc_purge_archive_on_del') && 'trash' === $new_status) { // phpcs:ignore
                if ($this->is_enable_setting('ahsc_purge_homepage_on_del')) {
                    $this->purgeUrl($this->parseUrl($this->getHomepage()));
                    //Logger
                    logger(
                        'targhet ' . $this->parseUrl($this->getHomepage()),
                        'hook:transition_post_status:del::home',
                        'info'
                    );
                    ///Logger
                    return;
                }

                $slug = \str_replace('__trashed', '', $post->post_name);
                $post_url = \home_url($slug);
                $this->purgeUrl($this->parseUrl($post_url));
                //Logger
                logger(
                    'targhet ' . $this->parseUrl($post_url),
                    'hook:transition_post_status::trashPost',
                    'info'
                );
                ///Logger
            }
        }

         //ref tags/6.0/src/wp-includes/post.php
         public function ahsc_post_updated($post_ID, $post_after, $post_before)
         {
             if (!$this->is_enable_setting('ahsc_enable_purge')) {
                 return;
             }

             /**
              * Edit item
              */
             if ($this->is_enable_setting('ahsc_purge_page_on_mod') || $this->is_enable_setting('ahsc_purge_archive_on_edit')) { // phpcs:ignore
                 if ($this->is_enable_setting('ahsc_purge_homepage_on_edit')) {
                     $this->purgeUrl($this->parseUrl($this->getHomepage()));
                     //Logger
                     logger(
                         'targhet ' . $this->parseUrl($this->getHomepage()),
                         'hook:post_updated:edit::home',
                         'info'
                     );
                     ///Logger
                     return;
                 }
             }
         }

        /**
         * Ahsc_wp_update_nav_menu
         *
         * @param int $menu_id Menu id
         *
         * @since 1.03
         *
         * @return void
         */
        public function ahsc_wp_update_nav_menu($menu_id)
        {
            //call from wp-admin/nav-menus.php
            $stack = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $admin_call = end($stack);

            if ('wp_nav_menu_update_menu_items' != $admin_call['function']) {
                return;
            }

            $this->purgeUrl($this->parseUrl($this->getHomepage()));
            //Logger
            logger(
                'targhet ' . $this->parseUrl($this->getHomepage()),
                'hook:wp_update_nav_menu:home',
                'info'
            );
            ///Logger
        }

        /**
         * Ahsc_transition_comment_status
         * Purge the cache of item or site on canghe status of the comment
         *
         * @param int|string  $new_status new status
         * @param int|string  $old_status old status
         * @param \WP_Comment $comment    comment object
         *
         * @return void
         */
        public function ahsc_transition_comment_status($new_status, $old_status, $comment) // phpcs:ignore
        {
            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            if ($this->is_enable_setting('ahsc_purge_page_on_new_comment') || $this->is_enable_setting('ahsc_purge_page_on_deleted_comment')) { // phpcs:ignore
                $_post_id    = $comment->comment_post_ID;

                $post_url = \get_permalink($_post_id);

                switch ($new_status) {
                    case 'approved':
                        if ($this->is_enable_setting('ahsc_purge_page_on_new_comment')) {
                            $this->purgeUrl($this->parseUrl($post_url));
                            //Logger
                            logger(
                                'targhet ' . $this->parseUrl($post_url),
                                'hook:approved:transition_comment_status',
                                'info'
                            );
                            ///Logger
                        }
                        break;

                    case 'spam':
                    case 'unapproved':
                    case 'trash':
                        if ('approved' === $old_status && $this->is_enable_setting('ahsc_purge_page_on_deleted_comment')) { // phpcs:ignore
                            $this->purgeUrl($this->parseUrl($post_url));
                            //Logger
                            logger(
                                'targhet ' . $this->parseUrl($post_url),
                                'hook:trash:transition_comment_status',
                                'info'
                            );
                            ///Logger
                        }
                        break;
                }
            }

            return true;
        }

        /**
         * Ahsc_wp_insert_comment
         * Purge the cache of item on insert the comment
         *
         * @param int         $id      the comment id
         * @param \WP_Comment $comment comment object
         *
         * @return void
         */
        public function ahsc_wp_insert_comment($id, $comment)
        {
            if (!$this->is_enable_setting('ahsc_enable_purge')) {
                return;
            }

            if (!$this->is_enable_setting('ahsc_purge_page_on_new_comment')) {
                return;
            }

            /**
             * Prevent multiple calls
             */
            if ($this->is_purged) {
                return;
            }

            $this->is_purged = true;
            // Prevent multiple calls

            if ($this->is_enable_setting('ahsc_purge_homepage_on_edit')) {
                $this->purgeUrl($this->parseUrl($this->getHomepage()));
                //Logger
                logger(
                    'targhet ' . $this->parseUrl($this->getHomepage()),
                    'hook:wp_insert_comment::home',
                    'info'
                );
                ///Logger
                return;
            }

            $_post_id = $comment->comment_post_ID;
            $post_url = \get_permalink($_post_id);

            $this->purgeUrl($this->parseUrl($post_url));
            //Logger
            logger(
                'targhet ' . $this->parseUrl($post_url),
                'hook:wp_insert_comment',
                'info'
            );
            ///Logger
        }

        /**
         * Ahsc_deferred_purge
         * Deferred cache cleanup, which is triggered when the last
         * item imported from the bulk operation is iterated
         *
         * @since 1.0.3
         *
         * @return void
         */
        public function ahsc_deferred_purge()
        {
            if ($this->i === $this->iterations) {
                $this->purgeUrl($this->parseUrl($this->getHomepage()));
                //Logger
                logger(
                    'targhet ' . $this->parseUrl($this->getHomepage()),
                    'ahsc_deferred_purge',
                    'info'
                );
                ///Logger
            }

            $this->i++;
            return;
        }

        /**
         * Ahsc_purge_on_plugin_actions
         * Purge cache on plugin activation, deativation
         *
         * @param string $plugin The plugin name/file
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function ahsc_purge_on_plugin_actions($plugin)
        {
            $this->purgeUrl($this->parseUrl($this->getHomepage()));
            //Logger
            logger(
                'targhet ' . $this->parseUrl($this->getHomepage()),
                'hook:ahsc_purge_on_plugin_actions:home',
                'info'
            );
            ///Logger
        }

        /**
         * Ahsc_purge_on_theme_actions
         * Purge cache on theme switch
         *
         * @param string $new_name  New Name
         * @param string $new_theme New theme
         * @param string $old_theme Old theme
         *
         * @since 1.2.0
         *
         * @return void
         */
        public function ahsc_purge_on_theme_actions($new_name, $new_theme, $old_theme) // phpcs:ignore
        {
            $this->purgeUrl($this->parseUrl($this->getHomepage()));
            //Logger
            logger(
                'targhet ' . $this->parseUrl($this->getHomepage()),
                'hook:ahsc_purge_on_theme_actions:home',
                'info'
            );
            ///Logger
        }

        /**
         * Ahsc_deferred_purge_by_transient
         * Deferred cache cleanup, which is triggered when the last
         * item imported from the bulk operation is iterated
         *
         * @since 1.0.3
         *
         * @return void
         */
        public function ahsc_deferred_purge_by_transient()
        {
            if (wp_is_json_request()) {
                return;
            }

            $is_multisite = \is_multisite();
            $do_purge = ($is_multisite) ? \get_site_transient('ahsc_do_purge') : \get_transient('ahsc_do_purge'); // phpcs:ignore

            if ($do_purge) {
                logger('trovato il transients', 'deffer by ahsc_deferred_purge_by_transient'); // phpcs:ignore

                $this->purgeUrl($this->parseUrl($this->getHomepage()));
                //Logger
                logger('targhet ' . $this->parseUrl($this->getHomepage()), 'ahsc_deferred_purge_by_transient', 'info'); // phpcs:ignore
                ///Logger

                //remove the transient to prevent moltiple calls.
                if ($is_multisite) {
                    \delete_site_transient('ahsc_do_purge');
                } else {
                    \delete_transient('ahsc_do_purge');
                }
            }

            return;
        }

        /**
         * Ahsc_bulk_manager
         * Hooked to check_admin_referer to check if this is a bulk operation. If so.
         * I remove the "single" hook and add a deferred action that fires in the
         * last iteration of the loop.
         *
         * @param string  $action the anction name passed from check.
         * @param integer $result result of the action
         *
         * @since 1.0.3
         *
         * @return void
         */
        public function ahsc_bulk_manager($action, $result)
        {
            // bulk-tags
            global $wp_filter;

            $this->i = 1;

            switch ($action) {
                case 'bulk-tags':

                    $current_action = (\_get_list_table('WP_Terms_List_Table')) ? (\_get_list_table('WP_Terms_List_Table'))->current_action() : false; // phpcs:ignore

                    if ('bulk-delete' === $current_action) {
                        logger('Tolgo il delete_term hook', 'Rimozione');
                        $this->iterations = count((array) $_REQUEST['delete_tags']);
                        $wp_filter['delete_term']->remove_filter('delete_term', array($this, 'ahsc_delete_term'), 20); // phpcs:ignore
                        $wp_filter['delete_term']->add_filter('delete_term', array($this, 'ahsc_deferred_purge'), 20, 0); // phpcs:ignore
                    }

                    break;
                case 'bulk-posts':

                    //@see https://github.com/WordPress/WordPress/blob/master/wp-admin/edit.php
                    logger('Tolgo il transition_post_status hook', 'Rimozione');
                    $this->iterations = count((array) $_REQUEST['post']);
                    $wp_filter['transition_post_status']->remove_filter('transition_post_status', array($this, 'ahsc_transition_post_status'), 20); // phpcs:ignore
                    $wp_filter['transition_post_status']->add_filter('transition_post_status', array($this, 'ahsc_deferred_purge'), 20, 0); // phpcs:ignore

                    break;
                case 'bulk-comments':

                    //@see https://github.com/WordPress/WordPress/blob/b8e44627c834677be9608422cd97cdaa93ef39df/wp-admin/edit-comments.php#L38
                    logger('Tolgo il transition_comment_status hook', 'Rimozione');
                    $this->iterations = count((array) $_REQUEST['delete_comments']);
                    $wp_filter['transition_comment_status']->remove_filter('transition_comment_status', array($this, 'ahsc_transition_comment_status'), 200); // phpcs:ignore
                    $wp_filter['transition_comment_status']->add_filter('transition_comment_status', array($this, 'ahsc_deferred_purge'), 200, 0); // phpcs:ignore

                    break;
                case 'bulk-plugins':
                    //@see https://github.com/WordPress/WordPress/blob/223cda987f3f4406182e326eaa555958610e1c17/wp-admin/plugins.php
                    logger('Tolgo il plugin actions hook', 'Rimozione');
                    $this->iterations = count(isset($_POST['checked']) ? (array) wp_unslash($_POST['checked']) : array()); // phpcs:ignore


                    $wp_filter['activated_plugin']->remove_filter('activated_plugin', array($this, 'ahsc_purge_on_plugin_actions'), 200); // phpcs:ignore
                    $wp_filter['deactivate_plugin']->remove_filter('deactivate_plugin', array($this, 'ahsc_purge_on_plugin_actions'), 200); // phpcs:ignore
                    $wp_filter['delete_plugin']->remove_filter('delete_plugin', array($this, 'ahsc_purge_on_plugin_actions'), 200); // phpcs:ignore

                    $wp_filter['activated_plugin']->add_filter('activated_plugin', array($this, 'ahsc_deferred_purge'), 200, 0); // phpcs:ignore
                    $wp_filter['deactivate_plugin']->add_filter('deactivate_plugin', array($this, 'ahsc_deferred_purge'), 200, 0); // phpcs:ignore
                    $wp_filter['delete_plugin']->add_filter('delete_plugin', array($this, 'ahsc_deferred_purge'), 200, 0); // phpcs:ignore
                    break;

                default:
                    logger('Bulk action detected action:' .$action, 'error');
                    break;
            }

            return;
        }
    }
}
