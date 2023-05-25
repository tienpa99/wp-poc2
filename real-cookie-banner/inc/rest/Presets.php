<?php

namespace DevOwl\RealCookieBanner\rest;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\BannerPresets;
use DevOwl\RealCookieBanner\presets\BlockerPresets;
use DevOwl\RealCookieBanner\presets\CookiePresets;
use DevOwl\RealCookieBanner\presets\Presets as PresetsPresets;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\templates\StorageHelper;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Create REST API for all available presets and service / blocker templates.
 */
class Presets
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
        $namespace = Service::getNamespace($this);
        \register_rest_route($namespace, '/presets/banner', ['methods' => 'GET', 'callback' => [$this, 'routeBannerPresets'], 'permission_callback' => [$this, 'permission_callback_customize']]);
        \register_rest_route($namespace, '/templates/(?P<type>blocker|services)', ['methods' => 'GET', 'callback' => [$this, 'routeTemplatesGet'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['storage' => ['type' => 'enum', 'enum' => ['use', 'invalidate', 'redownload'], 'default' => 'use']]]);
        \register_rest_route($namespace, '/templates/(?P<type>blocker|services)/(?P<identifier>[a-zA-Z0-9_-]+)/', ['methods' => 'GET', 'callback' => [$this, 'routeTemplatesGetSingle'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/presets/blocker', ['methods' => 'GET', 'callback' => [$this, 'routeBlockerPresets'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['force' => ['type' => 'boolean', 'default' => \false]]]);
        \register_rest_route($namespace, '/presets/blocker/(?P<identifier>[a-zA-Z0-9_-]+)', ['methods' => 'GET', 'callback' => [$this, 'routeBlockerPresetsSingle'], 'permission_callback' => [$this, 'permission_callback']]);
        \register_rest_route($namespace, '/presets/cookies', ['methods' => 'GET', 'callback' => [$this, 'routeCookiesPresets'], 'permission_callback' => [$this, 'permission_callback'], 'args' => ['force' => ['type' => 'boolean', 'default' => \false]]]);
        \register_rest_route($namespace, '/presets/cookies/(?P<identifier>[a-zA-Z0-9_-]+)', ['methods' => 'GET', 'callback' => [$this, 'routeCookiesPresetsSingle'], 'permission_callback' => [$this, 'permission_callback']]);
    }
    /**
     * Check if user is allowed to call this service requests.
     */
    public function permission_callback()
    {
        return \current_user_can(Core::MANAGE_MIN_CAPABILITY);
    }
    /**
     * Check if user is allowed to call this service requests with `customize` cap.
     */
    public function permission_callback_customize()
    {
        return \current_user_can('customize');
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/presets/banner Get all available customize banner presets
     * @apiHeader {string} X-WP-Nonce
     * @apiName Banner
     * @apiGroup Presets
     * @apiPermission customize
     * @apiVersion 1.0.0
     */
    public function routeBannerPresets()
    {
        $presets = new BannerPresets();
        return new WP_REST_Response(['items' => $presets->get(), 'defaults' => $presets->defaults(), 'constants' => $presets->constants()]);
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/templates/:type Get all available templates by type (can be blocker or services)
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {string} [storage=use] Can be `'use' | 'invalidate' | 'redownload'`
     * @apiName Templates
     * @apiGroup Presets
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     *
     * @param WP_REST_Request $request
     */
    public function routeTemplatesGet($request)
    {
        $type = $request->get_param('type');
        $storage = $request->get_param('storage');
        if ($storage === 'redownload') {
            TemplateConsumers::getInstance()->currentForceRedownload();
        }
        $consumer = $type === 'blocker' ? TemplateConsumers::getCurrentBlockerConsumer() : TemplateConsumers::getCurrentServiceConsumer();
        $items = AbstractTemplate::toArrays($consumer->retrieve($storage === 'invalidate'));
        $retryIn = $consumer->getVariableResolver()->resolveDefault(StorageHelper::LAST_TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME);
        if ($storage === 'redownload' && $retryIn !== null && $retryIn > 0) {
            \header('Retry-After: ' . $retryIn);
            return new WP_Error('rcb_template_cache_pending', 'We requested the service cloud to download templates and allowed async cache calculation, which is still pending.');
        }
        return new WP_REST_Response(['items' => $items, 'retryIn' => $retryIn]);
    }
    /**
     * See API docs.
     *
     * @api {get} /real-cookie-banner/v1/templates/:type/:identifier Get template by identifier and apply use-middlewares
     * @apiHeader {string} X-WP-Nonce
     * @apiName UseTemplate
     * @apiGroup Presets
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     *
     * @param WP_REST_Request $request
     */
    public function routeTemplatesGetSingle($request)
    {
        $type = $request->get_param('type');
        $identifier = $request->get_param('identifier');
        $consumer = $type === 'blocker' ? TemplateConsumers::getCurrentBlockerConsumer() : TemplateConsumers::getCurrentServiceConsumer();
        $template = $consumer->retrieveBy('identifier', $identifier);
        if (\count($template) === 0) {
            return new WP_Error('rest_not_found', '', ['status' => 404]);
        }
        return new WP_REST_Response(AbstractTemplate::toArray($template[0]->use()));
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/presets/blocker Get all available content blocker presets
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} force If `true` the cache will be invalidated
     * @apiName Blocker
     * @apiGroup Presets
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeBlockerPresets($request)
    {
        $presets = new BlockerPresets();
        return new WP_REST_Response(['items' => $presets->getAllFromCache($request->get_param('force'))]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/presets/blocker/:identifier Get attributes of available blocker preset by identifier
     * @apiHeader {string} X-WP-Nonce
     * @apiName Blocker
     * @apiGroup PresetAttributes
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeBlockerPresetsSingle($request)
    {
        $presets = new BlockerPresets();
        $preset = $presets->getWithAttributes($request->get_param('identifier'));
        if ($preset === \false) {
            return new WP_Error('rest_not_found', '', ['status' => 404]);
        }
        $presets->resolveAvailableCookies($preset);
        return new WP_REST_Response($preset['attributes']);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/presets/cookies Get all available cookie presets
     * @apiHeader {string} X-WP-Nonce
     * @apiParam {boolean} force If `true` the cache will be invalidated
     * @apiName Cookies
     * @apiGroup Presets
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeCookiesPresets($request)
    {
        $presets = new CookiePresets();
        return new WP_REST_Response(['items' => $presets->getAllFromCache($request->get_param('force'))]);
    }
    /**
     * See API docs.
     *
     * @param WP_REST_Request $request
     *
     * @api {get} /real-cookie-banner/v1/presets/cookies/:identifier Get attributes of available cookie preset by identifier
     * @apiHeader {string} X-WP-Nonce
     * @apiName Cookies
     * @apiGroup PresetAttributes
     * @apiPermission manage_options
     * @apiVersion 1.0.0
     */
    public function routeCookiesPresetsSingle($request)
    {
        $presets = new CookiePresets();
        $preset = $presets->getWithAttributes($request->get_param('identifier'));
        return $preset === \false ? new WP_Error('rest_not_found', '', ['status' => 404]) : new WP_REST_Response($preset['attributes']);
    }
    /**
     * Modify the response of the cookie / blocker Custom Post Type.
     *
     * @param WP_REST_Response $response The response object.
     * @param WP_Post $post Post object.
     */
    public function rest_prepare_presets($response, $post)
    {
        $preset = \false;
        $presetId = \get_post_meta($post->ID, Blocker::META_NAME_PRESET_ID, \true);
        if (!empty($presetId)) {
            /**
             * Presets metadata cache.
             *
             * @var PresetsPresets
             */
            $presets = null;
            switch ($response->data['type']) {
                case Cookie::CPT_NAME:
                    $presets = new CookiePresets();
                    break;
                case Blocker::CPT_NAME:
                    $presets = new BlockerPresets();
                    break;
                default:
                    return $response;
            }
            $preset = $presets->getFromCache($presetId);
        }
        $response->data['preset'] = $preset;
        return $response;
    }
    /**
     * New instance.
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\rest\Presets();
    }
}
