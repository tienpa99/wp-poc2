<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

use WeglotWP\Services\Language_Service_Weglot;
use WeglotWP\Services\Translate_Service_Weglot;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Weglot Output Buffering compatibility.
 *
 * @see https://weglot.com/
 */
class Weglot extends AbstractOutputBufferPlugin
{
    // Documented in AbstractOutputBufferPlugin
    public function maybePersistTranslation($sourceContent, $content, $sourceLocale, $targetLocale)
    {
        // TODO how can we persist known translations to Weglot?
    }
    // Documented in AbstractSyncPlugin
    public function switch($locale)
    {
        $languageEntry = $this->getLanguageService()->get_language_from_internal($locale);
        $this->getTranslateService()->set_current_language($languageEntry);
    }
    // Documented in AbstractLanguagePlugin
    public function getActiveLanguages()
    {
        $result = [$this->getDefaultLanguage()];
        foreach (\weglot_get_destination_languages() as $lang) {
            $result[] = $lang['language_to'];
        }
        return $result;
    }
    // Documented in AbstractLanguagePlugin
    public function getTranslatedName($locale)
    {
        $lang = $this->getLanguageService()->get_language_from_internal($locale);
        return isset($lang) ? $lang->getEnglishName() : $lang;
    }
    // Documented in AbstractLanguagePlugin
    public function getCountryFlag($locale)
    {
        // Weglot uses CSS classes with spread image
        return \false;
    }
    // Documented in AbstractLanguagePlugin
    public function getPermalink($url, $locale)
    {
        $object = \weglot_create_url_object($url);
        foreach ($object->getAllUrls() as $foundUrl) {
            if ($foundUrl['language']->getInternalCode() === $locale) {
                return $foundUrl['url'];
            }
        }
        return $url;
    }
    // Documented in AbstractLanguagePlugin
    public function getWordPressCompatibleLanguageCode($locale)
    {
        return IsoCodeMapper::twoToWordPressCompatible($locale);
    }
    // Documented in AbstractLanguagePlugin
    public function getDefaultLanguage()
    {
        return \weglot_get_original_language();
    }
    // Documented in AbstractLanguagePlugin
    public function getCurrentLanguage()
    {
        return \weglot_get_current_language();
    }
    // Documented in AbstractOutputBufferPlugin
    public function getSkipHTMLForTag($force = \false)
    {
        // See https://developers.weglot.com/wordpress/getting-started#exclude-blocks
        return $this->isCurrentlyInEditorPreview() && !$force ? '' : 'data-wg-notranslate';
    }
    // Documented in AbstractOutputBufferPlugin
    public function isCurrentlyInEditorPreview()
    {
        return isset($_GET['weglot-ve']);
    }
    // Documented in AbstractOutputBufferPlugin
    public function translateStrings(&$content, $locale, $context = null)
    {
        if (!$this->isCurrentlyInEditorPreview()) {
            $currentLanguage = $this->getCurrentLanguage();
            if ($locale !== null) {
                $this->switch($locale);
            }
            $result = $this->wrapHtmlToArray($this->getTranslateService()->weglot_treat_page($this->wrapArrayToHtml($content)));
            $this->remapResultToReference($content, $result, $locale, $context);
            if ($locale !== null) {
                $this->switch($currentLanguage);
            }
        }
    }
    /**
     * Get the `Language_Service_Weglot` instance.
     *
     * @return Language_Service_Weglot
     */
    protected function getLanguageService()
    {
        return \weglot_get_service('Language_Service_Weglot');
    }
    /**
     * Get the `Translate_Service_Weglot` instance.
     *
     * @return Translate_Service_Weglot
     */
    protected function getTranslateService()
    {
        return \weglot_get_service('Translate_Service_Weglot');
    }
    /**
     * Check if Weglot is active.
     */
    public static function isPresent()
    {
        return \is_plugin_active('weglot/weglot.php');
    }
}
