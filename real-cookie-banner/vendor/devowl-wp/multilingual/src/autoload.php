<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

// Simply check for defined constants, we do not need to `die` here
if (\defined('ABSPATH')) {
    Utils::setupConstants();
    Localization::instanceThis()->hooks();
    // Add an universal string for REST API requests to pass the current requested language and correctly switch to it.
    \add_action('init', function () {
        $compLanguage = AbstractLanguagePlugin::determineImplementation();
        \add_filter('DevOwl/Utils/RestQuery', function ($restQuery) use($compLanguage) {
            // Add language to each REST query string (only non-defaults, because WPML automatically redirects for default `lang` parameter)
            if ($compLanguage->isActive()) {
                $currentLanguage = $compLanguage->getCurrentLanguage();
                $restQuery['_dataLocale'] = $currentLanguage;
            }
            return $restQuery;
        });
        \add_action('rest_api_init', function () use($compLanguage) {
            if (isset($_GET['_dataLocale'])) {
                $compLanguage->switch($_GET['_dataLocale']);
            }
        }, 0);
    });
}
