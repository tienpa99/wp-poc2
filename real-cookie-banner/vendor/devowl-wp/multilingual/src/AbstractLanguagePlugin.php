<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Localization;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Commonly used functions for language plugins.
 */
abstract class AbstractLanguagePlugin
{
    const TEMPORARY_TEXT_DOMAIN_PREFIX = 'multilingual-temporary-text-domain';
    /**
     * The sync instance for this plugin.
     *
     * @var Sync
     */
    private $sync;
    /**
     * Use this as text domain for translations. E. g. `post_title` is automatically
     * passed while duplicating a post to another language.
     *
     * @var string
     */
    private $domain;
    private $moFile;
    private $overrideClass;
    /**
     * Temporary text domain, if given.
     *
     * @var TemporaryTextDomain
     */
    private $temporaryTextDomain = null;
    /**
     * Current translations hold as an instance.
     */
    protected $currentTranslationEntries = null;
    protected $lockCurrentTranslations = \false;
    /**
     * C'tor.
     *
     * @param string $domain Original text domain where `post_title` and so on are translated
     * @param string $moFile Needed for `TemporaryTextDomain`. E. g. `/var/www/html/wp-content/plugins/real-cookie-banner/languages/real-cookie-banner-%s.mo`
     * @param mixed $overrideClass A class with a `getPotLanguages` method
     * @codeCoverageIgnore
     */
    public function __construct($domain, $moFile = null, $overrideClass = null)
    {
        $this->domain = $domain;
        $this->moFile = $moFile;
        $this->overrideClass = $overrideClass;
    }
    /**
     * Switch to a given language code. Please do not use this function directly, use
     * `switchToLanguage` instead!
     *
     * @param string $locale
     */
    public abstract function switch($locale);
    /**
     * Get all active languages.
     *
     * @return string[]
     */
    public abstract function getActiveLanguages();
    /**
     * Get translated name for a given locale.
     *
     * @param string $locale
     * @return string
     */
    public abstract function getTranslatedName($locale);
    /**
     * Get a `src` compatible link to the country flag.
     *
     * @param string $locale
     * @return string|false
     */
    public abstract function getCountryFlag($locale);
    /**
     * Get the URL of a given URL for another locale.
     *
     * @param string $url
     * @param string $locale
     * @return string
     */
    public abstract function getPermalink($url, $locale);
    /**
     * Get the WordPress compatible language code of a given locale.
     *
     * @param string $locale
     * @return string
     */
    public abstract function getWordPressCompatibleLanguageCode($locale);
    /**
     * Get default language.
     *
     * @return string
     */
    public abstract function getDefaultLanguage();
    /**
     * Get current language.
     *
     * @return string
     */
    public abstract function getCurrentLanguage();
    /**
     * Get original id of passed post id.
     *
     * @param int $id
     * @param string $post_type
     * @return int
     */
    public abstract function getOriginalPostId($id, $post_type);
    /**
     * Get original id of passed term id.
     *
     * @param int $id
     * @param string $taxonomy
     * @return int
     */
    public abstract function getOriginalTermId($id, $taxonomy);
    /**
     * Get current id of passed post id and fallback to passed id,
     * when no translation found.
     *
     * @param int $id
     * @param string $post_type
     * @param string $locale Get item of this language
     * @return int
     */
    public abstract function getCurrentPostId($id, $post_type, $locale = null);
    /**
     * Get current id of passed term id and fallback to `0` when not translation found.
     *
     * @param int $id
     * @param string $taxonomy
     * @param string $locale Get item of this language
     * @return int
     */
    public abstract function getCurrentTermId($id, $taxonomy, $locale = null);
    /**
     * Get the HTML attribute so the "dynamic" replacement gets disabled
     * on frontend side. This can be useful for texts which are directly
     * translated in PHP already and gets translated via JavaScript again.
     *
     * @param boolean $force Pass `true` to get the attribute and do not respect `isCurrentlyInEditorPreview`
     * @return string
     */
    public abstract function getSkipHTMLForTag($force = \false);
    /**
     * Disable sync mechanism of our language plugin as it is handled by `Sync.php`.
     *
     * @param Sync $sync
     */
    public abstract function disableCopyAndSync($sync);
    /**
     * Check if the translate plugin is currently in edit mode (preview).
     *
     * @return boolean
     */
    public abstract function isCurrentlyInEditorPreview();
    /**
     * Maybe persist a translation in the database of available translations.
     *
     * @param string $sourceContent
     * @param string $content
     * @param string $sourceLocale
     * @param string $targetLocale
     */
    public abstract function maybePersistTranslation($sourceContent, $content, $sourceLocale, $targetLocale);
    /**
     * Translate strings to a given locale. Do not use this function directly, use `translateArray` instead!
     *
     * @param string[] $content This parameter needs to be passed as reference map, see also `translateArray`. The implementation needs to update the references correctly
     * @param string $locale
     * @param string[] $context
     */
    public abstract function translateStrings(&$content, $locale, $context = null);
    /**
     * Translate a complete array to a given locale (recursively).
     *
     * @param array $content
     * @param string[] $skipKeys
     * @param string $locale
     * @param string[] $context
     * @return array
     */
    public function translateArray($content, $skipKeys = [], $locale = null, $context = null)
    {
        // Snapshot current translations
        $useLocale = empty($locale) ? $this->getCurrentLanguageFallback() : $locale;
        if ($useLocale === $this->getDefaultLanguage()) {
            return $content;
        }
        $this->createTemporaryTextDomain($useLocale);
        $this->snapshotCurrentTranslations();
        // Check if translations exists for that language and fallback to default language
        if (\count($this->currentTranslationEntries['items']) === 0) {
            // Snapshot default language
            $this->createTemporaryTextDomain($this->getDefaultLanguage(), \true);
            $this->snapshotCurrentTranslations(\true);
            $this->createTemporaryTextDomain($useLocale, \true);
        }
        $expandedContent = Utils::expandKeys($content, $skipKeys);
        $referenceMap = [];
        \array_walk_recursive($expandedContent, function (&$value) use(&$referenceMap) {
            if (\is_string($value) && !empty($value) && !\is_numeric($value)) {
                $referenceMap[] =& $value;
            }
        });
        $this->translateStrings($referenceMap, $useLocale, $context);
        // Teardown
        $this->teardownTemporaryTextDomain();
        $this->unsetCurrentTranslations();
        return $content;
    }
    /**
     * Translate string from `.mo` file. Please consider to create a temporary text domain
     * before!
     *
     * @param string $content
     * @param string $targetLocale
     * @param string[] $context
     * @return array `[$found: boolean, $content: string]`
     */
    protected function translateStringFromMo($content, $targetLocale, $context = null)
    {
        $found = \false;
        // This only works with a override class
        $overrideClassInstance = $this->getOverrideClassInstance();
        if ($overrideClassInstance === null) {
            return [$found, $content];
        }
        // Translate content
        $sourceContent = $content;
        list(, $content) = $this->translateInput($content, $context);
        $sourceLocale = $this->getDefaultLanguage();
        // if ($sourceContent === 'Nur essenzielle Cookies akzeptieren') {
        //      json_encode([$sourceContent, $content, $sourceLocale, $targetLocale]);
        //      Default de_DE: => ["Accept all cookies","Allen Cookies zustimmen","en_US","de_DE"]
        //      Default en_US: => ["Nur essenzielle Cookies akzeptieren","Accept only essential cookies","de_DE","en_US"]
        // }
        if ($sourceContent !== $content && $targetLocale !== null && \strtolower($sourceLocale) !== \strtolower($targetLocale) && !empty($content)) {
            $found = \true;
            $this->maybePersistTranslation($sourceContent, $content, $sourceLocale, $targetLocale);
        }
        return [$found, $content];
    }
    /**
     * Is a multilingual plugin active?
     */
    public function isActive()
    {
        return !empty($this->getCurrentLanguage());
    }
    /**
     * Get current language or fallback to default language when the multilingual is in a state
     * like "Show all languages" (option known in the admin toolbar).
     */
    public function getCurrentLanguageFallback()
    {
        $current = $this->getCurrentLanguage();
        return empty($current) ? $this->getDefaultLanguage() : $current;
    }
    /**
     * Iterate all other languages than current one and get their context.
     * Context = switch to the language.
     *
     * Attention: If you are using switchToLanguage in a REST API call, please consider
     * to pass the `_wp_http_referer` parameter. E.g. TranslatePress checks if the
     * referer is an admin page and behaves differently.
     *
     * @param callback $callback Arguments: $locale, $currentLanguage
     */
    public function iterateOtherLanguagesContext($callback)
    {
        $this->iterateAllLanguagesContext($callback, [$this->getCurrentLanguageFallback()]);
    }
    /**
     * Iterate all language contexts.
     *
     * @param callback $callback Arguments: $locale, $currentLanguage
     * @param string[] $skip Skip locales
     */
    public function iterateAllLanguagesContext($callback, $skip = [])
    {
        $languages = $this->getActiveLanguages();
        // Keep current language for translation purposes
        $this->snapshotCurrentTranslations();
        foreach ($languages as $locale) {
            if (\in_array($locale, $skip, \true)) {
                continue;
            }
            /**
             * Allows to skip a language with a language iteration.
             *
             * @hook DevOwl/Multilingual/IterateLanguageContexts/Skip/$locale
             * @param {boolean} $skip
             * @param {string} $locale
             * @return {boolean}
             */
            if (\apply_filters('DevOwl/Multilingual/IterateLanguageContexts/Skip/' . $locale, \false, $locale)) {
                continue;
            }
            $this->switchToLanguage($locale, $callback);
        }
        $this->unsetCurrentTranslations();
    }
    /**
     * Open given language and get their context. Context = switch to the language.
     *
     * Attention: If you are using switchToLanguage in a REST API call, please consider
     * to pass the `_wp_http_referer` parameter. E.g. TranslatePress checks if the
     * referer is an admin page and behaves differently.
     *
     * @param string $locale
     * @param callback $callback Arguments: $locale, $currentLanguage
     */
    public function switchToLanguage($locale, $callback)
    {
        // Switch to other language
        $currentLanguage = $this->getCurrentLanguageFallback();
        $this->createTemporaryTextDomain($locale);
        $this->switch($locale);
        $result = \call_user_func($callback, $locale, $currentLanguage);
        // Restore to previous
        $this->teardownTemporaryTextDomain();
        $this->switch($currentLanguage);
        return $result;
    }
    /**
     * Create a temporary text domain.
     *
     * @param string $locale
     * @param boolean $force
     */
    public function createTemporaryTextDomain($locale, $force = \false)
    {
        if ($this->temporaryTextDomain !== null && !$force) {
            return;
        }
        if ($this->moFile !== null) {
            $skipFallbackTranslation = \false;
            $useLocale = $this->getWordPressCompatibleLanguageCode($locale);
            $overrideClassInstance = $this->getOverrideClassInstance();
            if ($overrideClassInstance !== null) {
                // Check if fallback should be skipped if the POT language is currently in use
                $skipFallbackTranslation = \in_array($useLocale, $overrideClassInstance->getPotLanguages(), \true);
            }
            $this->temporaryTextDomain = new TemporaryTextDomain($this->getTemporaryTextDomainName(), $this->domain, \sprintf($this->moFile, $useLocale), $useLocale, $skipFallbackTranslation);
        }
    }
    /**
     * Get the current temporary text domain name which can be used for `__` when e.g. inside `switchToLanguage`.
     */
    public function getTemporaryTextDomainName()
    {
        return self::TEMPORARY_TEXT_DOMAIN_PREFIX . '-' . $this->domain;
    }
    /**
     * Teardown the known temporary text domain.
     */
    public function teardownTemporaryTextDomain()
    {
        if ($this->temporaryTextDomain !== null) {
            $this->temporaryTextDomain->teardown();
            $this->temporaryTextDomain = null;
        }
    }
    /**
     * Do not allow to take another snapshot of current translations.
     *
     * @param boolean $state
     */
    public function lockCurrentTranslations($state = \false)
    {
        $this->lockCurrentTranslations = $state;
    }
    /**
     * Snapshot the current translations.
     *
     * @param boolean $force
     */
    public function snapshotCurrentTranslations($force = \false)
    {
        if (!isset($this->currentTranslationEntries) && !$this->lockCurrentTranslations || $force) {
            $this->currentTranslationEntries = TemporaryTextDomain::getTranslations($this->domain, $this);
        }
    }
    /**
     * Unset current translations.
     */
    public function unsetCurrentTranslations()
    {
        if (!$this->lockCurrentTranslations) {
            unset($this->currentTranslationEntries);
        }
    }
    /**
     * Translate a given input from known translations (.po, .pot) and only return the value.
     * This should be only used for `add_filter`!
     *
     * @param string $input
     */
    public function translateInputAndReturnValue($input)
    {
        return $this->translateInput($input)[1];
    }
    /**
     * Translate a given input from known translations (.po, .pot).
     *
     * @param string $input
     * @param string[] $context
     */
    public function translateInput($input, $context = null)
    {
        $key = $this->findI18nKeyOfTranslation($input);
        $td = self::TEMPORARY_TEXT_DOMAIN_PREFIX . '-' . $this->domain;
        if (\is_array($context)) {
            foreach ($context as $ctx) {
                $value = \call_user_func('_x', $key, $ctx, $td);
                if ($value !== $key) {
                    return [$key, $value];
                }
            }
        }
        $value = \call_user_func('__', $key, $td);
        return [$key, $value];
    }
    /**
     * Find an i18n key for `__()` from a given translated string.
     *
     * @param string $input
     */
    public function findI18nKeyOfTranslation($input)
    {
        if (isset($this->currentTranslationEntries)) {
            // Find source key of translation
            foreach ($this->currentTranslationEntries['items'] as $translation) {
                $index = \array_search($input, $translation->translations, \true);
                if ($index !== \false) {
                    switch ($index) {
                        case 0:
                            return $translation->singular;
                        case 1:
                            return $translation->plural;
                        default:
                            return $input;
                    }
                }
            }
        }
        return $input;
    }
    /**
     * Get an instance of the `overrideClass`.
     */
    public function getOverrideClassInstance()
    {
        if ($this->overrideClass !== null) {
            $overrideClass = $this->overrideClass;
            /**
             * Localization instance.
             *
             * @var Localization
             */
            $overrideClassInstance = new $overrideClass();
            return $overrideClassInstance;
        }
        return null;
    }
    /**
     * Set `Sync` instance for this plugin. Can be `null` if not given.
     *
     * @param Sync $sync
     */
    public function setSync($sync)
    {
        $this->sync = $sync;
    }
    /**
     * Get `Sync` instance for this plugin. Can be `null` if not given.
     */
    public function getSync()
    {
        return $this->sync;
    }
    /**
     * Determine implementation class.
     *
     * @param string $domain
     * @param string $moFile Needed for `TemporaryTextDomain`. E. g. `/var/www/html/wp-content/plugins/real-cookie-banner/languages/real-cookie-banner-%s.mo`
     * @param mixed $overrideClass A class with a `override` method (arguments: `locale`)
     * @return AbstractLanguagePlugin
     */
    public static function determineImplementation($domain = '', $moFile = null, $overrideClass = null)
    {
        if (WPML::isPresent()) {
            return new WPML($domain, $moFile, $overrideClass);
        } elseif (PolyLang::isPresent()) {
            return new PolyLang($domain, $moFile, $overrideClass);
        } elseif (TranslatePress::isPresent()) {
            return new TranslatePress($domain, $moFile, $overrideClass);
        } elseif (Weglot::isPresent()) {
            return new Weglot($domain, $moFile, $overrideClass);
        }
        return new None($domain, $moFile, $overrideClass);
    }
}
