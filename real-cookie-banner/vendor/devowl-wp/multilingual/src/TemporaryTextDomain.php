<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\PluginReceiver;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Constants;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Localization;
use MO;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Allows to set a given text domain to be translated from a .mo file.
 */
class TemporaryTextDomain
{
    /**
     * A collection of all instances with the fallback text domain as key.
     * This is needed to get translations in `AbstractSyncPlugin` also for
     * default-POT file (in most cases English)
     *
     * @var TemporaryTextDomain[]
     */
    private static $instances = [];
    private $domain;
    private $fallbackDomain;
    private $mofile;
    private $locale;
    /**
     * MO instance. Can be null if the given mo file is not found.
     *
     * @var MO
     */
    private $mo;
    private $skipFallbackTranslation;
    /**
     * C'tor.
     *
     * @param string $domain
     * @param string $fallbackDomain
     * @param string $mofile
     * @param string $locale
     * @param boolean $skipFallbackTranslation
     * @codeCoverageIgnore
     */
    public function __construct($domain, $fallbackDomain, $mofile, $locale, $skipFallbackTranslation = \false)
    {
        $this->domain = $domain;
        $this->fallbackDomain = $fallbackDomain;
        $this->mofile = $mofile;
        $this->locale = $locale;
        $this->skipFallbackTranslation = $skipFallbackTranslation;
        $this->createMo();
        $this->hooks();
    }
    /**
     * Create a MO instance.
     *
     * @see https://stackoverflow.com/a/28604283/5506547
     */
    protected function createMo()
    {
        if (!\file_exists($this->mofile)) {
            return;
        }
        $this->mo = new MO();
        $this->mo->import_from_file($this->mofile);
        self::$instances[$this->fallbackDomain] = $this;
    }
    /**
     * Create `gettext` hooks.
     */
    protected function hooks()
    {
        \add_filter('gettext', [$this, 'gettext'], 1, 3);
        \add_filter('gettext_with_context', [$this, 'gettext_with_context'], 1, 4);
    }
    /**
     * Teardown the `gettext` filter.
     */
    public function teardown()
    {
        \remove_filter('gettext', [$this, 'gettext'], 1, 3);
        \remove_filter('gettext_with_context', [$this, 'gettext_with_context'], 1, 4);
    }
    /**
     * Gettext filter.
     *
     * @param string $translation Translated text.
     * @param string $text Text to translate.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    public function gettext($translation, $text, $domain)
    {
        if ($this->domain === $domain) {
            if ($this->mo === null) {
                if ($this->skipFallbackTranslation) {
                    return $text;
                }
                return \call_user_func('translate', $text, $this->fallbackDomain);
            }
            return $this->mo->translate($text);
        }
        return $translation;
    }
    /**
     * Gettext with context filter.
     *
     * @param string $translation Translated text.
     * @param string $text Text to translate.
     * @param string $context Text context.
     * @param string $domain Text domain. Unique identifier for retrieving translated strings.
     */
    public function gettext_with_context($translation, $text, $context, $domain)
    {
        if ($this->domain === $domain) {
            if ($this->mo === null) {
                if ($this->skipFallbackTranslation) {
                    return $text;
                }
                return \call_user_func('translate_with_gettext_context', $text, $context, $this->fallbackDomain);
            }
            return $this->mo->translate($text, $context);
        }
        return $translation;
    }
    /**
     * Get all translation entries of the given MO file.
     */
    public function getEntries()
    {
        return isset($this->mo) ? $this->mo->entries : [];
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMoFile()
    {
        return $this->mofile;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLocale()
    {
        return $this->locale;
    }
    /**
     * Create a temporary text domain from a given WP React Starter plugin receiver.
     *
     * @param string $domain
     * @param string $fallbackDomain
     * @param PluginReceiver $receiver
     * @param AbstractSyncPlugin $compLanguage
     * @param string $overrideClass A class with a `getPotLanguages` method
     */
    public static function fromPluginReceiver($domain, $fallbackDomain, $receiver, $compLanguage, $overrideClass = null)
    {
        $skipFallbackTranslation = \false;
        // Never use the language of the compatible plugin while deactivation
        if (isset($_GET['action'], $_GET['plugin']) && $_GET['action'] === 'deactivate') {
            $useLocale = '';
        } else {
            $useLocale = $compLanguage->getWordPressCompatibleLanguageCode($compLanguage->getCurrentLanguageFallback());
        }
        // Fallback to blog language
        if (empty($useLocale)) {
            // Do not use `get_bloginfo('language')` or `determine_locale` as they also respect
            // the configured user language. But at this point we definitely need the blog language.
            $useLocale = \str_replace('-', '_', \get_locale());
        }
        if ($overrideClass !== null) {
            /**
             * Localization instance.
             *
             * @var Localization
             */
            $overrideClassInstance = new $overrideClass();
            // Check if fallback should be skipped if the POT language is currently in use
            $skipFallbackTranslation = \in_array($useLocale, $overrideClassInstance->getPotLanguages(), \true);
        }
        $path = \untrailingslashit(\plugin_dir_path($receiver->getPluginConstant(Constants::PLUGIN_CONST_FILE))) . $receiver->getPluginData('DomainPath');
        $mo = \trailingslashit($path) . $receiver->getPluginConstant(Constants::PLUGIN_CONST_TEXT_DOMAIN) . '-' . $useLocale . '.mo';
        return new TemporaryTextDomain($domain, $fallbackDomain, $mo, $useLocale, $skipFallbackTranslation);
    }
    /**
     * Get an instance from a given fallback domain.
     *
     * @param string $fallbackDomain
     */
    public static function fromFallbackDomain($fallbackDomain)
    {
        return isset(self::$instances[$fallbackDomain]) ? self::$instances[$fallbackDomain] : null;
    }
    /**
     * Get translations for the given domain. It also searches for temporary text domains
     * if we are e. g. in the default POT file language (in most cases English).
     *
     * @param string $domain
     * @param AbstractLanguagePlugin $compLanguage
     */
    public static function getTranslations($domain, $compLanguage)
    {
        // From current domain if exists
        $mo = \get_translations_for_domain($domain);
        $entries = $mo->entries;
        if (\count($entries) > 0) {
            return ['locale' => $compLanguage->getCurrentLanguage(), 'items' => $entries];
        }
        // From temporary text domain
        $temporary = TemporaryTextDomain::fromFallbackDomain($domain);
        if ($temporary !== null) {
            return ['locale' => $temporary->getLocale(), 'items' => $temporary->getEntries()];
        }
        return ['locale' => null, 'items' => []];
    }
}
