<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealProductManagerWpClient\Utils as RealProductManagerWpClientUtils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\BlockerConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ConsumerPool;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\VariableResolver;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Common service cloud consumer manager to consume local and external templates (e.g. service cloud).
 *
 * It makes use of `@devowl-wp/service-cloud-consumer`.
 */
class TemplateConsumers
{
    use UtilsProvider;
    const ONE_OF_PLUGIN_THEME_ACTIVE = 'is-wordpress-plugin-or-theme-active:';
    /**
     * All available consumers with key as context and value the `ConsumerPool`.
     *
     * @var ConsumerPool[]
     */
    private $pools = [];
    /**
     * Singleton instance.
     *
     * @var TemplateConsumers
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Get the service cloud consumer pool for the current context (e.g. WPML language).
     */
    public function getCurrentPool()
    {
        $context = self::getContext();
        if (!isset($this->pools[$context])) {
            $serviceConsumer = new ServiceConsumer();
            $blockerConsumer = new BlockerConsumer();
            $this->fillVariableResolver($context, $serviceConsumer->getVariableResolver(), $serviceConsumer, $blockerConsumer);
            $this->fillVariableResolver($context, $blockerConsumer->getVariableResolver(), $serviceConsumer, $blockerConsumer);
            // Storage
            $serviceConsumer->setStorage(new \DevOwl\RealCookieBanner\templates\ServiceStorage($serviceConsumer));
            $blockerConsumer->setStorage(new \DevOwl\RealCookieBanner\templates\BlockerStorage($blockerConsumer));
            // Middlewares
            $serviceConsumer->addMiddleware(new \DevOwl\RealCookieBanner\templates\ConsumerMiddleware($serviceConsumer));
            $serviceConsumer->addMiddleware(new \DevOwl\RealCookieBanner\templates\RecommendedHooksMiddleware($serviceConsumer));
            $blockerConsumer->addMiddleware(new \DevOwl\RealCookieBanner\templates\ConsumerMiddleware($blockerConsumer));
            $blockerConsumer->addMiddleware(new \DevOwl\RealCookieBanner\templates\RecommendedHooksMiddleware($blockerConsumer));
            // Data sources
            $serviceConsumer->addDataSource(new \DevOwl\RealCookieBanner\templates\ServiceLocalDataSource($serviceConsumer));
            $serviceConsumer->addDataSource(new \DevOwl\RealCookieBanner\templates\CloudDataSource($serviceConsumer));
            $blockerConsumer->addDataSource(new \DevOwl\RealCookieBanner\templates\CloudDataSource($blockerConsumer));
            $pool = new ConsumerPool([$serviceConsumer, $blockerConsumer]);
            $this->pools[$context] = $pool;
        }
        return $this->pools[$context];
    }
    /**
     * Fill the variable resolver with our values.
     *
     * @param string $context
     * @param VariableResolver $resolver
     * @param ServiceConsumer $serviceConsumer
     * @param BlockerConsumer $blockerConsumer
     */
    protected function fillVariableResolver($context, $resolver, $serviceConsumer, $blockerConsumer)
    {
        $variables = [
            // Custom properties
            'context' => $context,
            'cache.invalidate.key' => function () use($context) {
                $licenseActivationReceived = Core::getInstance()->getRpmInitiator()->getPluginUpdater()->getCurrentBlogLicense()->getActivation()->getReceived();
                return \json_encode([
                    // Download from cloud API for each language
                    $context,
                    // Download from cloud API when Real Cookie Banner got updated
                    RCB_VERSION,
                    // Download from cloud API when Real Cookie Banner license got changed
                    \is_array($licenseActivationReceived) ? $licenseActivationReceived['id'] : \false,
                ]);
            },
            // Consumer
            'consumer.contactEmail' => function () {
                return \get_bloginfo('admin_email');
            },
            'consumer.privacyPolicyUrl' => function () {
                return General::getInstance()->getPrivacyPolicyUrl('');
            },
            'consumer.legalNoticeUrl' => function () {
                return General::getInstance()->getImprintPageUrl('');
            },
            'consumer.provider' => function () {
                return \html_entity_decode(\get_bloginfo('name'));
            },
            'consumer.host.main' => function () {
                return Utils::host(Utils::HOST_TYPE_MAIN);
            },
            'consumer.host.main+subdomains' => function () {
                return Utils::host(Utils::HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS);
            },
            'consumer.host.current' => function () {
                return Utils::host(Utils::HOST_TYPE_CURRENT);
            },
            'consumer.host.current+protocol' => function () {
                return Utils::host(Utils::HOST_TYPE_CURRENT_PROTOCOL);
            },
            'consumer.host.current+subdomains' => function () {
                return Utils::host(Utils::HOST_TYPE_CURRENT_WITH_ALL_SUBDOMAINS);
            },
            'template.fbPixel.scriptLocale' => function () {
                $default = 'en_US';
                $websiteLocale = \get_locale();
                if (Utils::startsWith($websiteLocale, 'de_DE')) {
                    return 'de_DE';
                } elseif (Utils::startsWith($websiteLocale, 'en_')) {
                    return 'en_US';
                }
                return $default;
            },
            'adminUrl' => function () {
                return \admin_url('/');
            },
            'blocker.consumer' => $blockerConsumer,
            'service.consumer' => $serviceConsumer,
            'tier' => $this->isPro() ? 'pro' : 'free',
            'manager' => General::getInstance()->getSetCookiesViaManager(),
            'oneOf' => [$this, 'oneOf'],
            'blocker.created' => [$this, 'blockerCreated'],
            'services.created' => [$this, 'servicesCreated'],
            // I18n
            'i18n.ContentTypeButtonTextMiddleware.loadContent' => function () {
                return \__('Load content', Hooks::TD_FORCED);
            },
            'i18n.ContentTypeButtonTextMiddleware.loadMap' => function () {
                return \__('Load map', Hooks::TD_FORCED);
            },
            'i18n.ContentTypeButtonTextMiddleware.loadForm' => function () {
                return \__('Load form', Hooks::TD_FORCED);
            },
            'i18n.disabled' => \__('Disabled', RCB_TD),
            'i18n.ExistsMiddleware.alreadyCreated' => \__('Already created', RCB_TD),
            'i18n.ExistsMiddleware.blockerAlreadyCreatedTooltip' => \__('You have already created a Content Blocker with this template.', RCB_TD),
            'i18n.ExistsMiddleware.serviceAlreadyCreatedTooltip' => \__('You have already created a Service (Cookie) with this template.', RCB_TD),
            // translators:
            'i18n.ManagerMiddleware.tooltip' => \__('This cookie template is optimized to work with %s.', RCB_TD),
            // translators:
            'i18n.ManagerMiddleware.disabledTooltip' => \__('Please activate %s in settings to use this template.', RCB_TD),
            // translators:
            'i18n.DisableTechnicalHandlingWhenOneOfMiddleware.technicalHandlingNotice' => \__('You don\'t have to define a technical handling here, because this is done by the plugin <strong>%s</strong>.', RCB_TD),
            'i18n.ServiceAvailableBlockerTemplatesMiddleware.tooltip' => \__('A suitable content blocker for this service can be created automatically.', RCB_TD),
            'i18n.GroupMiddleware.group.essential' => function () {
                return \__('Essential', Hooks::TD_FORCED);
            },
            'i18n.GroupMiddleware.group.functional' => function () {
                return \__('Functional', Hooks::TD_FORCED);
            },
            'i18n.GroupMiddleware.group.statistics' => function () {
                return \__('Statistics', Hooks::TD_FORCED);
            },
            'i18n.GroupMiddleware.group.marketing' => function () {
                return \__('Marketing', Hooks::TD_FORCED);
            },
            'i18n.OneOfMiddleware.disabledTooltip' => function () {
                return \sprintf(
                    // translators:
                    \__('This template is currently disabled because the respective WordPress plugin is not installed or the desired function is not active. <a href="%s" target="_blank">Learn more</a>', RCB_TD),
                    \__('https://devowl.io/knowledge-base/real-cookie-banner-disabled-cookie-templates/', RCB_TD)
                );
            },
        ];
        foreach ($variables as $key => $value) {
            $resolver->add($key, $value);
        }
    }
    /**
     * Implementation of `blockerCreated`.
     *
     * @param VariableResolver $resolver
     */
    public function blockerCreated($resolver)
    {
        $consumer = $resolver->getConsumer();
        $result = [];
        $existing = Blocker::getInstance()->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => Blocker::CPT_NAME, 'numberposts' => -1, 'nopaging' => \true, 'meta_query' => [['key' => Blocker::META_NAME_PRESET_ID, 'compare' => 'EXISTS']], 'post_status' => ['publish', 'private', 'draft']], 'blockerWithPreset')));
        foreach ($existing as $post) {
            $tmp = new BlockerTemplate($consumer);
            $tmp->identifier = $post->metas[Blocker::META_NAME_PRESET_ID];
            $tmp->consumerData['id'] = $post->ID;
            $result[] = $tmp;
        }
        return $result;
    }
    /**
     * Implementation of `servicesCreated`.
     *
     * @param VariableResolver $resolver
     */
    public function servicesCreated($resolver)
    {
        $consumer = $resolver->getConsumer();
        $result = [];
        $existing = Cookie::getInstance()->getOrdered(null, \false, \get_posts(Core::getInstance()->queryArguments(['post_type' => Cookie::CPT_NAME, 'numberposts' => -1, 'nopaging' => \true, 'meta_query' => [['key' => Blocker::META_NAME_PRESET_ID, 'compare' => 'EXISTS']], 'post_status' => ['publish', 'private', 'draft']], 'cookiesWithPreset')));
        foreach ($existing as $post) {
            $tmp = new ServiceTemplate($consumer);
            $tmp->identifier = $post->metas[Blocker::META_NAME_PRESET_ID];
            $tmp->consumerData['id'] = $post->ID;
            $result[] = $tmp;
        }
        return $result;
    }
    /**
     * Force re-download from all datasources. This also includes download from the cloud API instead
     * of "only" recalculating middlewares.
     */
    public function currentForceRedownload()
    {
        foreach ($this->getCurrentPool()->getConsumers() as $consumer) {
            /**
             * Storage.
             *
             * @var ServiceStorage|BlockerStorage
             */
            $storage = $consumer->getStorage();
            $storage->getHelper()->getExpireOption()->delete();
        }
    }
    /**
     * Delete all templates from storage except "Real Cookie Banner" template when license got deactivated.
     *
     * Otherwise, download from cloud API again.
     *
     * @param boolean $status
     */
    public function licenseStatusChanged($status)
    {
        global $wpdb;
        if ($status) {
            $this->currentForceRedownload();
            foreach ($this->getCurrentPool()->getConsumers() as $consumer) {
                $consumer->retrieve();
            }
        } else {
            $table_name = $this->getTableName(\DevOwl\RealCookieBanner\templates\StorageHelper::TABLE_NAME);
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query("DELETE FROM {$table_name} WHERE is_cloud = 1");
            // phpcs:enable WordPress.DB.PreparedSQL
        }
    }
    /**
     * Force recalculation for middlewares (this does not necessarily download from cloud API!) for all
     * blockers and services.
     */
    public function forceRecalculation()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\templates\StorageHelper::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query("UPDATE {$table_name} SET after_middleware = ''");
        // phpcs:enable WordPress.DB.PreparedSQL
    }
    /**
     * Add the release info of the latest cloud API template download.
     *
     * @param array $arr
     */
    public function revisionCurrent($arr)
    {
        $arr['cloud_release_info'] = [\DevOwl\RealCookieBanner\templates\StorageHelper::TYPE_BLOCKER => \get_option(\DevOwl\RealCookieBanner\templates\CloudDataSource::OPTION_NAME_LATEST_RESPONSE_RELEASE_INFO_PREFIX . \DevOwl\RealCookieBanner\templates\StorageHelper::TYPE_BLOCKER, null), \DevOwl\RealCookieBanner\templates\StorageHelper::TYPE_SERVICE => \get_option(\DevOwl\RealCookieBanner\templates\CloudDataSource::OPTION_NAME_LATEST_RESPONSE_RELEASE_INFO_PREFIX . \DevOwl\RealCookieBanner\templates\StorageHelper::TYPE_SERVICE, null)];
        return $arr;
    }
    /**
     * Implementation of `oneOf`.
     *
     * @param string $statement
     * @param AbstractTemplate $template
     */
    public function oneOf($statement, $template)
    {
        if ($template->identifier !== 'real-cookie-banner') {
            return \false;
        }
        if (Utils::startsWith($statement, self::ONE_OF_PLUGIN_THEME_ACTIVE)) {
            $slug = \substr($statement, \strlen(self::ONE_OF_PLUGIN_THEME_ACTIVE));
            if (Utils::isPluginActive($slug)) {
                /**
                 * Allows you to deactivate a false-positive plugin preset.
                 *
                 * Example: Someone has RankMath SEO active, but deactivated the GA function.
                 *
                 * Attention: This filter is only applied for active plugins!
                 *
                 * @hook RCB/Templates/FalsePositive
                 * @param {boolean} $isActive
                 * @param {string} $plugin The active plugin (can be slug or file)
                 * @param {string} $identifier The preset identifier
                 * @param {string} $type Can be `service` or `blocker`
                 * @return {boolean}
                 * @since 3.16.0
                 */
                if (\apply_filters('RCB/Templates/FalsePositivePlugin', \true, $slug, $template->identifier, $template instanceof ServiceTemplate ? 'service' : 'blocker')) {
                    $pluginName = RealProductManagerWpClientUtils::getActivePluginsMap()[$slug] ?? null;
                    return $pluginName !== null ? $pluginName : \true;
                }
            } elseif (Utils::isThemeActive($slug)) {
                return \true;
            }
        }
        return \false;
    }
    /**
     * Shortcut to directly get the current context `ServiceConsumer`.
     */
    public static function getCurrentServiceConsumer()
    {
        return self::getInstance()->getCurrentPool()->getConsumer(ServiceTemplate::class);
    }
    /**
     * Shortcut to directly get the current context `ServiceConsumer`.
     */
    public static function getCurrentBlockerConsumer()
    {
        return self::getInstance()->getCurrentPool()->getConsumer(BlockerTemplate::class);
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\templates\TemplateConsumers() : self::$me;
    }
    /**
     * Get the context key for consumer pool. Its the language for which we request templates.
     */
    public static function getContext()
    {
        $language = isset($_GET['_dataLocale']) ? \sanitize_text_field($_GET['_dataLocale']) : Core::getInstance()->getCompLanguage()->getCurrentLanguage();
        // Fallback to blog language
        if (empty($language)) {
            $language = \str_replace('-', '_', \get_locale());
        }
        return $language;
    }
}
