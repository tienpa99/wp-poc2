<?php

namespace DevOwl\RealCookieBanner\lite\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service as UtilsService;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Handle general lite REST services.
 */
class Service
{
    use UtilsProvider;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Register endpoints.
     */
    public function rest_api_init()
    {
        $namespace = UtilsService::getNamespace($this);
        \register_rest_route($namespace, '/dismiss-config-page-pro-notice', ['methods' => 'DELETE', 'callback' => [$this, 'routeDeleteConfigProNotice'], 'permission_callback' => [$this, 'permission_callback']]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback()
    {
        return \current_user_can(Core::MANAGE_MIN_CAPABILITY);
    }
    /**
     * See API docs.
     *
     * @api {delete} /real-cookie-banner/v1/lite/dismiss-config-page-pro-notice Dismiss config page pro notice for now
     * @apiHeader {string} X-WP-Nonce
     * @apiName DismissConfigProNotice
     * @apiGroup Lite
     * @apiPermission Lite only
     * @apiVersion 1.0.0
     */
    public function routeDeleteConfigProNotice()
    {
        Core::getInstance()->getConfigPage()->isProNoticeVisible(\true);
        return new WP_REST_Response(['success' => \true]);
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\lite\rest\Service();
    }
}
