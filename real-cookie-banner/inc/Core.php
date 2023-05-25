<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CacheInvalidate\ExcludeAssets;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\AbstractCustomizePanel;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Customize\Core as CustomizeCore;
use DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset\AnonymousAssetBuilder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractLanguagePlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core as RealQueue;
use DevOwl\RealCookieBanner\base\Core as BaseCore;
use DevOwl\RealCookieBanner\comp\ComingSoonPlugins;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileMigrationMajor2;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileMigrationMajor3;
use DevOwl\RealCookieBanner\comp\migration\DashboardTileTcfV2IllegalUsage;
use DevOwl\RealCookieBanner\comp\PresetsPluginIntegrations;
use DevOwl\RealCookieBanner\lite\Core as LiteCore;
use DevOwl\RealCookieBanner\overrides\interfce\IOverrideCore;
use DevOwl\RealCookieBanner\presets\free\RealCookieBannerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerExistsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\BlockerContentTypeButtonTextMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\BlockerDisableProFeaturesInFreeMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookieBlockerPresetIdsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookieExistsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookieManagerMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\DisableTechnicalHandlingThroughPluginMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\ExtendsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\AdoptTierFromClassNamespaceMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookiesDeactivateAutomaticContentBlockerCreationByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookieGroupNamesBackwardsCompatibleMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\CookiesSetUniqueName;
use DevOwl\RealCookieBanner\rest\Presets;
use DevOwl\RealCookieBanner\rest\Config;
use DevOwl\RealCookieBanner\rest\Import;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\view\Banner;
use DevOwl\RealCookieBanner\view\ConfigPage;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\settings\Multisite;
use DevOwl\RealCookieBanner\presets\UpdateNotice;
use DevOwl\RealCookieBanner\rest\Consent as RestConsent;
use DevOwl\RealCookieBanner\rest\Stats as RestStats;
use DevOwl\RealCookieBanner\rest\Scanner as RestScanner;
use DevOwl\RealCookieBanner\scanner\AutomaticScanStarter;
use DevOwl\RealCookieBanner\scanner\Persist;
use DevOwl\RealCookieBanner\scanner\Scanner;
use DevOwl\RealCookieBanner\scanner\ScannerNotices;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\ModalHints;
use DevOwl\RealCookieBanner\settings\Reset;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\view\Blocker as ViewBlocker;
use DevOwl\RealCookieBanner\view\checklist\ActivateBanner;
use DevOwl\RealCookieBanner\view\checklist\AddCookie;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicyMentionUsage;
use DevOwl\RealCookieBanner\view\checklist\SaveSettings;
use DevOwl\RealCookieBanner\view\customize\banner\BasicLayout;
use DevOwl\RealCookieBanner\view\customize\banner\BodyDesign;
use DevOwl\RealCookieBanner\view\customize\banner\Decision;
use DevOwl\RealCookieBanner\view\customize\banner\Texts;
use DevOwl\RealCookieBanner\view\customize\banner\Mobile;
use DevOwl\RealCookieBanner\view\customize\banner\individual\SaveButton;
use DevOwl\RealCookieBanner\view\shortcode\LinkShortcode;
use DevOwl\RealCookieBanner\view\shortcode\HistoryUuidsShortcode;
use DevOwl\RealCookieBanner\view\Scanner as ViewScanner;
use DevOwl\RealCookieBanner\view\navmenu\NavMenuLinks;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealUtils\Core as RealUtilsCore;
use DevOwl\RealCookieBanner\Vendor\DevOwl\TcfVendorListNormalize\TcfVendorListNormalizer;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\FixInvalidJsonInDb;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ServiceNoStore;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Singleton core class which handles the main system for plugin. It includes
 * registering of the autoload, all hooks (actions & filters) (see BaseCore class).
 */
class Core extends BaseCore implements IOverrideCore
{
    use LiteCore;
    use CustomizeCore;
    /**
     * The minimal required capability so a user can manage cookies.
     */
    const MANAGE_MIN_CAPABILITY = 'manage_options';
    /**
     * Singleton instance.
     *
     * @var Core
     */
    private static $me = null;
    /**
     * The config page.
     *
     * @var ConfigPage
     */
    private $configPage;
    /**
     * The banner.
     *
     * @var Banner
     */
    private $banner;
    /**
     * The blocker.
     *
     * @var ViewBlocker
     */
    private $blocker;
    /**
     * An unique id for this page request. This is e. g. needed for unique overlay id.
     *
     * @var string
     */
    private $pageRequestUuid4;
    /**
     * See AbstractLanguagePlugin.
     *
     * @var AbstractLanguagePlugin
     */
    private $compLanguage;
    /**
     * See AdInitiator.
     *
     * @var AdInitiator
     */
    private $adInitiator;
    /**
     * See RpmInitiator.
     *
     * @var RpmInitiator
     */
    private $rpmInitiator;
    /**
     * See AnonymousAssetBuilder.
     *
     * @var AnonymousAssetBuilder
     */
    private $anonymousAssetBuilder;
    /**
     * See TcfVendorListNormalizer.
     *
     * @var TcfVendorListNormalizer
     */
    private $tcfVendorListNormalizer;
    /**
     * See ExcludeAssets.
     *
     * @var ExcludeAssets
     */
    private $excludeAssets;
    /**
     * See Scanner.
     *
     * @var Scanner
     */
    private $scanner;
    /**
     * See ScannerNotices.
     *
     * @var ScannerNotices
     */
    private $scannerNotices;
    /**
     * See RealQueue.
     *
     * @var RealQueue
     */
    private $realQueue;
    /**
     * Application core constructor.
     */
    protected function __construct()
    {
        parent::__construct();
        // Load no-namespace API functions
        foreach (['services', 'consent'] as $apiInclude) {
            require_once RCB_PATH . '/inc/api/' . $apiInclude . '.php';
        }
        // The Uuid4 must start with a non-number character to work with CSS selectors
        $this->pageRequestUuid4 = 'a' . \wp_generate_uuid4();
        $this->blocker = ViewBlocker::instance();
        $this->scanner = Scanner::instance();
        $this->scannerNotices = ScannerNotices::getInstance();
        $this->realQueue = new RealQueue($this);
        $this->configPage = ConfigPage::instance();
        $this->banner = Banner::instance();
        $automaticScanStarter = AutomaticScanStarter::instance();
        $presetsPluginIntegrations = PresetsPluginIntegrations::getInstance();
        // Create multilingual instance
        $path = \untrailingslashit(\plugin_dir_path(RCB_FILE)) . $this->getPluginData('DomainPath');
        $mo = \trailingslashit($path) . RCB_TD . '-%s.mo';
        $this->compLanguage = AbstractLanguagePlugin::determineImplementation(RCB_TD, $mo, \DevOwl\RealCookieBanner\Localization::class);
        // Enable `no-store` for our relevant WP REST API endpoints
        ServiceNoStore::hook('/' . Service::getNamespace($this));
        ServiceNoStore::hook('/wp/v2/rcb-');
        // Custom Post Types
        // Fix technicalDefinitions hosts modified by migration tools
        (new FixInvalidJsonInDb())->fixMetadataBySingleMetaKey(Cookie::META_NAME_TECHNICAL_DEFINITIONS);
        // Register preset middleware
        $extendsMiddleware = new ExtendsMiddleware();
        $adoptTierFromClassNamespaceMiddleware = new AdoptTierFromClassNamespaceMiddleware();
        $disablePresetByNeedsMiddleware = new DisablePresetByNeedsMiddleware();
        \add_filter('RCB/Presets/Cookies/MiddlewareCallbacks', function ($callbacks) use($extendsMiddleware, $adoptTierFromClassNamespaceMiddleware, $disablePresetByNeedsMiddleware, $presetsPluginIntegrations) {
            $callbacks[] = [$extendsMiddleware, 'middleware'];
            $callbacks[] = [new DisableTechnicalHandlingThroughPluginMiddleware(), 'middleware'];
            $callbacks[] = [new CookieExistsMiddleware(), 'middleware'];
            $callbacks[] = [CookieGroupNamesBackwardsCompatibleMiddleware::createMiddlewareStatisticStatics(), 'middleware'];
            $callbacks[] = [new CookieManagerMiddleware(), 'middleware'];
            $callbacks[] = [new CookieBlockerPresetIdsMiddleware(), 'middleware'];
            $callbacks[] = [$adoptTierFromClassNamespaceMiddleware, 'middleware'];
            $callbacks[] = [$disablePresetByNeedsMiddleware, 'middleware'];
            $callbacks[] = [$presetsPluginIntegrations, 'middleware_cookies_recommended'];
            $callbacks[] = [new CookiesDeactivateAutomaticContentBlockerCreationByNeedsMiddleware(), 'middleware'];
            $callbacks[] = [new CookiesSetUniqueName(), 'middleware'];
            return $callbacks;
        });
        \add_filter('RCB/Presets/Blocker/MiddlewareCallbacks', function ($callbacks) use($extendsMiddleware, $adoptTierFromClassNamespaceMiddleware, $disablePresetByNeedsMiddleware, $presetsPluginIntegrations) {
            $callbacks[] = [$extendsMiddleware, 'middleware'];
            $callbacks[] = [new BlockerHostsOptionsMiddleware(), 'middleware'];
            $callbacks[] = [new BlockerExistsMiddleware(), 'middleware'];
            $callbacks[] = [$adoptTierFromClassNamespaceMiddleware, 'middleware'];
            $callbacks[] = [$disablePresetByNeedsMiddleware, 'middleware'];
            $callbacks[] = [$presetsPluginIntegrations, 'middleware_blocker_recommended'];
            $callbacks[] = [new BlockerContentTypeButtonTextMiddleware(), 'middleware'];
            $callbacks[] = [new BlockerDisableProFeaturesInFreeMiddleware(), 'middleware'];
            return $callbacks;
        });
        // Official Consent API
        \add_filter('Consent/Block/HTML', [$this->getBlocker(), 'replace']);
        \add_action('init', [$presetsPluginIntegrations, 'init'], 0);
        \add_action('init', [ComingSoonPlugins::getInstance(), 'init'], 11);
        \add_action('init', [$this, 'registerPostTypes'], 0);
        // E.g. WooCommerce does not know at 10 priority about the custom post types
        \add_action('RCB/Templates/FalsePositivePlugin', [$presetsPluginIntegrations, 'presets_plugin_false_positives'], 10, 4);
        \add_action('activated_plugin', [$this->getActivator(), 'anyPluginToggledState']);
        \add_action('deactivated_plugin', [$this->getActivator(), 'anyPluginToggledState']);
        \add_action('activated_plugin', [$this->scannerNotices, 'togglePluginStateNotice'], 10, 2);
        \add_action('deactivated_plugin', [$this->scannerNotices, 'togglePluginStateNotice'], 10, 2);
        \add_action('admin_notices', [$this->scannerNotices, 'admin_notices_any_plugin_change_state']);
        \add_action('admin_init', [$automaticScanStarter, 'probablyAddClientJob']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [$automaticScanStarter, 'probablyAddClientJob']);
        \add_action('admin_init', [Cookie::getInstance(), 'register_cap']);
        \add_action('admin_init', [Blocker::getInstance(), 'register_cap']);
        \add_action('admin_init', [$this, 'registerSettings'], 1);
        \add_action('rest_api_init', [$this, 'registerSettings'], 1);
        \add_action('plugins_loaded', [\DevOwl\RealCookieBanner\Localization::class, 'multilingual'], 1);
        \add_action('wp', [$this->getAssets(), 'createHashedAssets']);
        \add_action('login_init', [$this->getAssets(), 'createHashedAssets']);
        \add_action('plugins_loaded', [$this->getBlocker(), 'registerOutputBuffer'], ViewBlocker::OB_START_PLUGINS_LOADED_PRIORITY);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [TCF::getInstance(), 'new_version_installation']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Texts::class, 'new_version_installation_after_2_6_5']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [RealCookieBannerPreset::class, 'new_version_installation_after_2_11_0']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [BasicLayout::class, 'new_version_installation_after_2_15_0']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Persist::class, 'new_version_installation_after_2_15_0']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Decision::class, 'new_version_installation_after_2_17_3']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [BodyDesign::class, 'new_version_installation_after_2_17_3']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [SaveButton::class, 'new_version_installation_after_2_17_3']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Consent::class, 'new_version_installation_after_2_17_3']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Consent::class, 'new_version_installation_after_3_0_1']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Cookie::getInstance(), 'new_version_installation_after_3_0_2']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Blocker::getInstance(), 'new_version_installation_after_3_0_2']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [\DevOwl\RealCookieBanner\Stats::getInstance(), 'new_version_installation_after_3_4_13']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [Cookie::getInstance(), 'new_version_installation_after_3_4_13']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [\DevOwl\RealCookieBanner\UserConsent::getInstance(), 'new_version_installation_after_3_4_13']);
        \add_action('DevOwl/Utils/NewVersionInstallation/' . RCB_SLUG, [PrivacyPolicyMentionUsage::class, 'new_version_installation_after_3_4_13']);
        \add_filter('RCB/Blocker/Enabled', [$this->getScanner(), 'force_blocker_enabled']);
        \add_filter('customize_save_response', [$this, 'customize_save_response'], 10, 1);
        \add_filter('option_' . Consent::SETTING_COOKIE_DURATION, [Consent::getInstance(), 'option_cookie_duration']);
        \add_filter('option_' . Consent::SETTING_CONSENT_DURATION, [Consent::getInstance(), 'option_consent_duration']);
        \add_action('update_option_' . Consent::SETTING_CONSENT_DURATION, [Consent::getInstance(), 'update_option_consent_transient_deletion'], 10, 2);
        // Cache relevant hooks
        \add_filter('RCB/Customize/Updated', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'customize_updated']);
        \add_filter('RCB/Settings/Updated', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'settings_updated']);
        \add_filter('RCB/Settings/Updated', [SaveSettings::class, 'settings_updated']);
        \add_filter('RCB/Settings/Updated', [ActivateBanner::class, 'settings_updated'], 10, 2);
        \add_filter('RCB/Settings/Updated', [\DevOwl\RealCookieBanner\UserConsent::getInstance(), 'settings_updated']);
        \add_filter('RCB/Revision/Hash', [\DevOwl\RealCookieBanner\Cache::getInstance(), 'revision_hash']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [\DevOwl\RealCookieBanner\Cache::getInstance(), 'invalidate']);
        \add_action('DevOwl/RealProductManager/LicenseActivation/StatusChanged/' . RCB_SLUG, [TemplateConsumers::getInstance(), 'licenseStatusChanged']);
        // Compatibility hooks (Blocker)
        \add_filter('ez_buffered_final_content', [$this->getBlocker(), 'replace'], 1);
        // Ezoic CDN compatibility
        \add_filter('rocket_buffer', [$this->getBlocker(), 'replace'], 1);
        // WP Rocket Lazy loading compatibility
        \add_filter('ionos_performancemodify_output', [$this->getBlocker(), 'replace']);
        // IONOS Performance plugin
        \add_filter('ionos_performance_modify_output', [$this->getBlocker(), 'replace']);
        // IONOS Performance plugin (v2)
        \add_filter('facetwp_facet_html', [$this->getBlocker(), 'replace']);
        // Facet WP facets
        \add_filter('wp_grid_builder/async/render_response', [$this->getBlocker(), 'replace']);
        // WP Grid builder refresh ajax action
        \add_filter('autoptimize_filter_html_before_minify', [$this->getBlocker(), 'replace']);
        // Autoptimize
        \add_filter('autoptimize_filter_css_exclude', [$this->getBlocker(), 'autoptimize_filter_css_exclude']);
        \add_filter('avf_exclude_assets', [$this->getBlocker(), 'avf_exclude_assets']);
        \add_filter('litespeed_buffer_before', [$this->getBlocker(), 'replace'], 1);
        // LiteSpeed Cache
        \add_filter('litespeed_ccss_url', [$this->getBlocker(), 'modifyUrlToSkipContentBlocker']);
        // LiteSpeed Cache Critical CSS
        \add_filter('litespeed_ucss_url', [$this->getBlocker(), 'modifyUrlToSkipContentBlocker']);
        // LiteSpeed Cache Unique CSS
        \add_filter('us_grid_listing_post', [$this->getBlocker(), 'replace']);
        // Impreza Lazy Loading posts
        \add_action('shutdown', function () {
            // [Theme Comp] Themify
            if (\has_action('shutdown', ['TFCache', 'tf_cache_end']) !== \false) {
                echo $this->getBlocker()->replace(\ob_get_clean());
            }
        }, 0);
        \add_action('init', [$this->getBlocker(), 'registerOutputBuffer'], 11);
        // SiteGround Optimizer (https://wordpress.org/support/topic/allow-to-modify-html-in-output-buffer/)
        \add_action('shutdown', [$this->getBlocker(), 'closeOutputBuffer'], 11);
        // "
        \add_shortcode(LinkShortcode::TAG, [LinkShortcode::class, 'render']);
        \add_shortcode(HistoryUuidsShortcode::TAG, [HistoryUuidsShortcode::class, 'render']);
        \add_shortcode(HistoryUuidsShortcode::DEPRECATED_TAG, [HistoryUuidsShortcode::class, 'render']);
        $this->adInitiator = new \DevOwl\RealCookieBanner\AdInitiator();
        $this->adInitiator->start();
        $this->rpmInitiator = new \DevOwl\RealCookieBanner\RpmInitiator();
        $this->rpmInitiator->start();
        $this->anonymousAssetBuilder = new AnonymousAssetBuilder($this->getTableName(AnonymousAssetBuilder::TABLE_NAME), RCB_OPT_PREFIX, \true);
        $this->tcfVendorListNormalizer = new TcfVendorListNormalizer(RCB_DB_PREFIX, \defined('DEVOWL_WP_DEV') && \constant('DEVOWL_WP_DEV') ? 'http://real_cookie_banner_backend:8000/1.0.0/tcf/gvl/' : 'https://rcb.devowl.io/1.0.0/tcf/gvl/', $this->getCompLanguage());
        $this->getScanner()->probablyForceSitemaps();
        $this->overrideConstructFreemium();
        $this->overrideConstruct();
    }
    /**
     * Define constants which relies on i18n localization loaded.
     */
    public function i18n()
    {
        parent::i18n();
        // Internal hook; see CU-jbayae
        $args = \apply_filters('RCB/Misc/ProUrlArgs', ['partner' => null, 'aid' => null]);
        // Check if affiliate Link is allowed (only when privacy policy got accepted)
        $isLicensed = !empty($this->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getCode());
        if (!$isLicensed) {
            $args['aid'] = null;
        }
        $translatedUrl = \__('https://devowl.io/go/real-cookie-banner?source=rcb-lite', RCB_TD);
        $translatedUrl = \add_query_arg($args, $translatedUrl);
        \define('RCB_PRO_VERSION', $translatedUrl);
    }
    /**
     * Register settings.
     */
    public function registerSettings()
    {
        General::getInstance()->register();
        Consent::getInstance()->register();
        Multisite::getInstance()->register();
        CountryBypass::getInstance()->register();
        TCF::getInstance()->register();
        $this->overrideRegisterSettings();
    }
    /**
     * Register post types and custom taxonomies.
     */
    public function registerPostTypes()
    {
        Cookie::getInstance()->register();
        Blocker::getInstance()->register();
        CookieGroup::getInstance()->register();
        $this->overrideRegisterPostTypes();
    }
    /**
     * The init function is fired even the init hook of WordPress. If possible
     * it should register all hooks to have them in one place.
     */
    public function init()
    {
        $this->excludeAssets = new ExcludeAssets($this);
        $presetsService = Presets::instance();
        $configService = Config::instance();
        $viewScanner = ViewScanner::instance();
        $restScanner = RestScanner::instance();
        $navMenuLinks = NavMenuLinks::instance();
        $scanner = $this->getScanner();
        $scannerQuery = $scanner->getQuery();
        $scannerOnChangeDetection = $scanner->getOnChangeDetection();
        // Register all your hooks here
        \add_action('rest_api_init', [$presetsService, 'rest_api_init']);
        \add_action('rest_api_init', [$configService, 'rest_api_init']);
        \add_action('rest_api_init', [RestConsent::instance(), 'rest_api_init']);
        \add_action('rest_api_init', [RestStats::instance(), 'rest_api_init']);
        \add_action('rest_api_init', [$restScanner, 'rest_api_init']);
        \add_action('rest_api_init', [Import::instance(), 'rest_api_init']);
        \add_action('admin_enqueue_scripts', [$this->getAssets(), 'admin_enqueue_scripts']);
        \add_action('wp_enqueue_scripts', [$this->getAssets(), 'wp_enqueue_scripts'], 0);
        \add_action('login_enqueue_scripts', [$this->getAssets(), 'login_enqueue_scripts'], 0);
        \add_action('DevOwl/RealQueue/Job/Label', [$scanner, 'real_queue_job_label'], 10, 3);
        \add_action('DevOwl/RealQueue/Job/Actions', [$scanner, 'real_queue_job_actions'], 10, 2);
        \add_action('DevOwl/RealQueue/Error/Description', [$scanner, 'real_queue_error_description'], 10, 3);
        \add_action('DevOwl/RealQueue/EnqueueScripts', [$this->getAssets(), 'real_queue_enqueue_scripts']);
        \add_action('DevOwl/RealQueue/Rest/Status/AdditionalData/rcb-scan-list', [$restScanner, 'real_queue_additional_data_list']);
        \add_action('DevOwl/RealQueue/Rest/Status/AdditionalData/rcb-scan-notice', [$restScanner, 'real_queue_additional_data_notice']);
        \add_action('admin_menu', [$this->getConfigPage(), 'admin_menu']);
        \add_filter('plugin_action_links_' . \plugin_basename(RCB_FILE), [$this->getConfigPage(), 'plugin_action_links'], 10, 2);
        \add_action('customize_register', [$this->getBanner()->getCustomize(), 'customize_register']);
        \add_action('wp_footer', [$this->getBanner(), 'wp_footer']);
        \add_action('login_footer', [$this->getBanner(), 'wp_footer']);
        \add_action('admin_bar_menu', [$this->getBanner(), 'admin_bar_menu'], 100);
        \add_action('admin_bar_menu', [$viewScanner, 'admin_bar_menu'], 100);
        \add_action('save_post_' . Cookie::CPT_NAME, [AddCookie::class, 'save_post'], 10, 3);
        \add_action('save_post_' . Blocker::CPT_NAME, [Blocker::getInstance(), 'save_post'], 10, 3);
        \add_action('save_post_' . Blocker::CPT_NAME, [\DevOwl\RealCookieBanner\Cache::getInstance(), 'invalidate']);
        \add_action('save_post_page', [General::getInstance(), 'save_post_page']);
        \add_action('delete_' . CookieGroup::TAXONOMY_NAME, [CookieGroup::getInstance(), 'deleted'], 10, 4);
        \add_action('edited_' . CookieGroup::TAXONOMY_NAME, [\DevOwl\RealCookieBanner\Stats::getInstance(), 'edited_group']);
        \add_action('deleted_post', [Blocker::getInstance(), 'deleted_post']);
        \add_action('admin_notices', [new UpdateNotice(), 'admin_notices']);
        \add_action('admin_notices', [$this->getConfigPage(), 'admin_notices_preinstalled_environment']);
        \add_action('admin_notices', [$this->getConfigPage(), 'admin_notices_ad_blocker']);
        \add_action('admin_notices', [$this->getConfigPage(), 'admin_notices_services_with_empty_privacy_policy']);
        \add_action('posts_where', [\DevOwl\RealCookieBanner\Utils::class, 'posts_where_find_in_set'], 10, 2);
        \add_action('post_updated', [$scannerOnChangeDetection, 'post_updated'], 10, 3);
        \add_action('save_post', [$scannerOnChangeDetection, 'save_post'], 10, 2);
        \add_action('delete_post', [$scannerOnChangeDetection, 'delete_post'], 10);
        \add_action('wp_trash_post', [$scannerOnChangeDetection, 'wp_trash_post']);
        \add_action('untrash_post', [$scannerOnChangeDetection, 'wp_trash_post']);
        \add_action('wp_nav_menu_item_custom_fields', [$navMenuLinks, 'wp_nav_menu_item_custom_fields'], 10, 2);
        \add_action('wp_update_nav_menu_item', [$navMenuLinks, 'wp_update_nav_menu_item'], 10, 3);
        \add_action('admin_head-nav-menus.php', [$navMenuLinks, 'admin_head']);
        \add_action('customize_controls_head', [$navMenuLinks, 'customize_controls_head']);
        \add_action('delete_post', [General::getInstance(), 'delete_post_imprint_privacy_policy']);
        \add_action('wp_trash_post', [General::getInstance(), 'delete_post_imprint_privacy_policy']);
        \add_action('update_option_wp_page_for_privacy_policy', [General::getInstance(), 'update_option_wp_page_for_privacy_policy'], 10, 2);
        \add_action('update_option_' . General::SETTING_PRIVACY_POLICY_ID, [General::getInstance(), 'update_option_privacy_policy'], 10, 2);
        \add_action('update_option_' . General::SETTING_IMPRINT_ID, [General::getInstance(), 'update_option_imprint'], 10, 2);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileMigrationMajor2(), 'actions']);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileMigrationMajor3(), 'actions']);
        \add_action('RCB/Migration/RegisterActions', [new DashboardTileTcfV2IllegalUsage(), 'actions']);
        \add_filter('nav_menu_link_attributes', [$navMenuLinks, 'nav_menu_link_attributes'], 10, 2);
        \add_filter('wp_setup_nav_menu_item', [$navMenuLinks, 'wp_setup_nav_menu_item']);
        \add_filter('customize_nav_menu_available_item_types', [$navMenuLinks, 'register_customize_nav_menu_item_types']);
        \add_filter('customize_nav_menu_available_items', [$navMenuLinks, 'register_customize_nav_menu_items'], 10, 4);
        \add_filter('RCB/Revision/Current', [$navMenuLinks, 'revisionCurrent']);
        \add_filter('RCB/Revision/Current', [TemplateConsumers::getInstance(), 'revisionCurrent']);
        \add_filter('oembed_result', [$this->getBlocker(), 'modifyOEmbedHtmlToKeepOriginalUrl'], 10, 2);
        \add_filter('embed_oembed_html', [$this->getBlocker(), 'modifyOEmbedHtmlToKeepOriginalUrl'], 10, 2);
        \add_filter('RCB/Hints', [$this->getAssets(), 'hints_dashboard_tile_predefined_links'], 100);
        \add_filter('rest_post_dispatch', [$configService, 'rest_post_dispatch'], 10, 3);
        \add_filter('rest_prepare_' . Cookie::CPT_NAME, [$presetsService, 'rest_prepare_presets'], 10, 2);
        \add_filter('rest_' . Cookie::CPT_NAME . '_item_schema', [Cookie::getInstance(), 'rest_item_schema']);
        \add_filter('rest_prepare_' . Blocker::CPT_NAME, [$presetsService, 'rest_prepare_presets'], 10, 2);
        \add_filter('RCB/Revision/Current', [$scannerQuery, 'revisionCurrent']);
        \add_filter('RCB/Revision/Array', [Blocker::getInstance(), 'revisionArray']);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Texts::class, 'applyBlockerTextsBackwardsCompatibility'], 10, 2);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Mobile::class, 'applyBackwardsCompatibility'], 10, 2);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Decision::class, 'applyBackwardsCompatibility'], 10, 2);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Cookie::getInstance(), 'applyMetaRenameBackwardsCompatibility'], 10, 2);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Cookie::getInstance(), 'applyMetaLegalNoticeUrlBackwardsCompatibility'], 10, 2);
        \add_filter('RCB/Revision/BackwardsCompatibility', [Blocker::getInstance(), 'applyMetaRenameBackwardsCompatibility'], 10, 2);
        // Multilingual
        \add_filter('rest_' . Cookie::CPT_NAME . '_query', [Hooks::getInstance(), 'rest_query']);
        \add_filter('rest_' . Blocker::CPT_NAME . '_query', [Hooks::getInstance(), 'rest_query']);
        \add_action('rest_api_init', [Hooks::getInstance(), 'rest_api_init'], 1);
        \add_filter('RCB/Hints', [Hooks::getInstance(), 'hints']);
        \add_filter('RCB/Query/Arguments', [Hooks::getInstance(), 'queryArguments']);
        \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . Blocker::META_NAME_SERVICES, [Hooks::getInstance(), 'copy_blocker_cookies_meta'], 10, 4);
        \add_filter('DevOwl/Multilingual/Copy/Meta/post/' . Blocker::META_NAME_TCF_VENDORS, [Hooks::getInstance(), 'copy_blocker_cookies_meta'], 10, 4);
        \add_filter('RCB/Revision/Option/' . General::SETTING_IMPRINT_ID, [Hooks::getInstance(), 'revisionOptionValue_pageId']);
        \add_filter('RCB/Revision/Option/' . General::SETTING_PRIVACY_POLICY_ID, [Hooks::getInstance(), 'revisionOptionValue_pageId']);
        \add_filter('RCB/Revision/Context', [Hooks::getInstance(), 'context']);
        \add_filter('RCB/Revision/Context/Translate', [Hooks::getInstance(), 'contextTranslate']);
        \add_filter('RCB/Revision/Hash', [Hooks::getInstance(), 'revisionHash']);
        \add_filter('RCB/Revision/NeedsRetrigger', [Hooks::getInstance(), 'revisionNeedsRetrigger']);
        \add_filter('RCB/Presets/Cookies', [\DevOwl\RealCookieBanner\DemoEnvironment::getInstance(), 'cookiePresets'], \PHP_INT_MAX);
        \add_filter('RCB/Presets/Blocker', [\DevOwl\RealCookieBanner\DemoEnvironment::getInstance(), 'blockerPresets'], \PHP_INT_MAX);
        if ($scanner->isActive()) {
            $scanner->reduceCurrentUserPermissions();
            ComingSoonPlugins::getInstance()->bypass();
            \add_action('shutdown', [$scanner, 'teardown']);
            \add_filter('show_admin_bar', '__return_false');
            \add_filter('RCB/Blocker/ResolveBlockables', [$scanner, 'resolve_blockables'], 50, 2);
        }
        // Allow to reset all available data and recreated
        if (isset($_GET['rcb-reset-all']) && \current_user_can('activate_plugins')) {
            \check_admin_referer('rcb-reset-all');
            Reset::getInstance()->all(isset($_GET['reset-consents']));
            \wp_safe_redirect($this->getConfigPage()->getUrl());
            exit;
        }
        // Create default content
        if ($this->getConfigPage()->isVisible() && (\get_site_option(\DevOwl\RealCookieBanner\Activator::OPTION_NAME_NEEDS_DEFAULT_CONTENT) || CookieGroup::getInstance()->getEssentialGroup() === null || \count(Hooks::getInstance()->getLanguagesWithoutEssentialGroup()) > 0)) {
            $this->getActivator()->addInitialContent();
        }
        $this->getBanner()->getCustomize()->enableOptionsAutoload();
        General::getInstance()->enableOptionsAutoload();
        Consent::getInstance()->enableOptionsAutoload();
        Multisite::getInstance()->enableOptionsAutoload();
        TCF::getInstance()->enableOptionsAutoload();
        CountryBypass::getInstance()->enableOptionsAutoload();
        ModalHints::getInstance()->enableOptionsAutoload();
        ScannerNotices::getInstance()->enableOptionsAutoload();
        // If we reached next thursday, update the GVL automatically
        TCF::getInstance()->probablyUpdateGvl();
        // If we reached next sunday, update the country database automatically
        CountryBypass::getInstance()->probablyUpdateDatabase();
        // If country bypass is active, add the filter so the frontend fetches the WP REST API and modify revision
        if (CountryBypass::getInstance()->isActive()) {
            \add_action('RCB/Consent/Created', [CountryBypass::getInstance(), 'consentCreated']);
            \add_filter('RCB/Consent/DynamicPreDecision', [CountryBypass::getInstance(), 'dynamicPredecision'], 10, 2);
            \add_filter('RCB/Revision/Option/' . CountryBypass::SETTING_COUNTRY_BYPASS_COUNTRIES, [CountryBypass::getInstance(), 'revisionOptionCountriesExpandPredefinedLists']);
            \add_filter('RCB/Revision/Array/Independent', [CountryBypass::getInstance(), 'revisionArrayIndependent']);
        }
        $this->overrideInitCustomize();
        $this->overrideInit();
    }
    /**
     * Check if any plugin specific setting got changed in customize.
     *
     * @param array $response
     */
    public function customize_save_response($response)
    {
        if (AbstractCustomizePanel::gotUpdated($response, RCB_OPT_PREFIX)) {
            /**
             * Real Cookie Banner (Content Blocker, banner) got updated in the Customize.
             *
             * @hook RCB/Customize/Updated
             * @param {array} $response
             * @return {array}
             */
            $response = \apply_filters('RCB/Customize/Updated', $response);
        }
        return $response;
    }
    /**
     * See filter RCB/Query/Arguments.
     *
     * @param array $arguments
     * @param string $context
     */
    public function queryArguments($arguments, $context)
    {
        /**
         * Modify arguments to `get_posts` and `get_terms`. This can be especially useful for WPML / PolyLang.
         *
         * @hook RCB/Query/Arguments
         * @param {array} $arguments
         * @param {string} $context
         * @return {array}
         */
        return \apply_filters('RCB/Query/Arguments', \array_merge(['suppress_filters' => \false], $arguments), $context);
    }
    /**
     * Return the base URL to assets specially for Real Cookie Banner.
     *
     * @param string $path
     */
    public function getBaseAssetsUrl($path)
    {
        return RealUtilsCore::getInstance()->getBaseAssetsUrl('wp-real-cookie-banner/' . $path);
    }
    /**
     * Get config page.
     *
     * @codeCoverageIgnore
     */
    public function getConfigPage()
    {
        return $this->configPage;
    }
    /**
     * Get banner.
     *
     * @codeCoverageIgnore
     */
    public function getBanner()
    {
        return $this->banner;
    }
    /**
     * Get blocker.
     *
     * @codeCoverageIgnore
     */
    public function getBlocker()
    {
        return $this->blocker;
    }
    /**
     * Get request uuid 4.
     *
     * @codeCoverageIgnore
     */
    public function getPageRequestUuid4()
    {
        return $this->pageRequestUuid4;
    }
    /**
     * Get compatibility language class.
     *
     * @codeCoverageIgnore
     */
    public function getCompLanguage()
    {
        return $this->compLanguage;
    }
    /**
     * Get ad initiator from `real-utils`.
     *
     * @codeCoverageIgnore
     */
    public function getAdInitiator()
    {
        return $this->adInitiator;
    }
    /**
     * Get ad initiator from `real-product-manager-wp-client`.
     *
     * @codeCoverageIgnore
     */
    public function getRpmInitiator()
    {
        return $this->rpmInitiator;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAnonymousAssetBuilder()
    {
        return $this->anonymousAssetBuilder;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTcfVendorListNormalizer()
    {
        return $this->tcfVendorListNormalizer;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExcludeAssets()
    {
        return $this->excludeAssets;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getScanner()
    {
        return $this->scanner;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRealQueue()
    {
        return $this->realQueue;
    }
    /**
     * Check if a license is active.
     */
    public function isLicenseActive()
    {
        return !empty(\DevOwl\RealCookieBanner\Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getCode());
    }
    /**
     * Get singleton core class.
     *
     * @return Core
     */
    public static function getInstance()
    {
        return !isset(self::$me) ? self::$me = new \DevOwl\RealCookieBanner\Core() : self::$me;
    }
}
/**
 * See API docs.
 *
 * @api {get} /real-cookie-banner/v1/plugin Get plugin information
 * @apiHeader {string} X-WP-Nonce
 * @apiName GetPlugin
 * @apiGroup Plugin
 *
 * @apiSuccessExample {json} Success-Response:
 * {
 *     Name: "My plugin",
 *     PluginURI: "https://example.com/my-plugin",
 *     Version: "0.1.0",
 *     Description: "This plugin is doing something.",
 *     Author: "<a href="https://example.com">John Smith</a>",
 *     AuthorURI: "https://example.com",
 *     TextDomain: "my-plugin",
 *     DomainPath: "/languages",
 *     Network: false,
 *     Title: "<a href="https://example.com">My plugin</a>",
 *     AuthorName: "John Smith"
 * }
 * @apiVersion 1.0.0
 */
