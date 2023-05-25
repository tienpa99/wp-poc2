<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Fonts cookie preset.
 */
class GoogleFontsPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::GOOGLE_FONTS;
    const VERSION = 2;
    /**
     * Web Font Loader compatibility.
     *
     * @see https://app.clickup.com/t/aq01tu
     */
    const WEB_FONT_LOADER_ON_PAGE_LOAD = '<script>
(function () {
  // Web Font Loader compatibility (https://github.com/typekit/webfontloader)
  var modules = {
    typekit: "https://use.typekit.net",
    google: "https://fonts.googleapis.com/"
  };

  var load = function (config) {
    setTimeout(function () {
      var a = window.consentApi;

      // Only when blocker is active
      if (a) {
        // Iterate all modules and handle in a single `WebFont.load`
        Object.keys(modules).forEach(function (module) {
          var newConfigWithoutOtherModules = JSON.parse(
            JSON.stringify(config)
          );
          Object.keys(modules).forEach(function (toRemove) {
            if (toRemove !== module) {
              delete newConfigWithoutOtherModules[toRemove];
            }
          });

          if (newConfigWithoutOtherModules[module]) {
            a.unblock(modules[module]).then(function () {
              var originalLoad = window.WebFont.load;
              if (originalLoad !== load) {
                originalLoad(newConfigWithoutOtherModules);
              }
            });
          }
        });
      }
    }, 0);
  };

  if (!window.WebFont) {
    window.WebFont = {
      load: load
    };
  }
})();
</script>';
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Fonts';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-fonts.png'), 'attributes' => ['name' => $name, 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('Google Fonts is a service that downloads fonts that are not installed on the client device of the user and embeds them into the website. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible.', Hooks::TD_FORCED), 'provider' => 'Google Ireland Limited', 'providerPrivacyPolicyUrl' => 'https://policies.google.com/privacy', 'isEmbeddingOnlyExternalResources' => \true, 'technicalHandlingNotice' => \join('<br /><br />', [\__('When loading Google Fonts, personal data of your visitors is transferred to Google, which is why you need consent. Real Cookie Banner has to check the consent before loading Google Fonts (if there is a consent), which takes a few milliseconds per page view. As a result, you will inevitably notice a small flickering effect (font replacement after a few milliseconds) on your website.', RCB_TD), \sprintf(
            // translators:
            \__('We therefore recommend <a href="%s" target="_blank">replacing Google Fonts with locally hosted fonts</a> when possible, rather than asking for consent. We explained how this works in our blog!', RCB_TD),
            \__('https://devowl.io/2022/google-fonts-wordpress-gdpr/', RCB_TD)
        )]), 'codeOnPageLoad' => self::WEB_FONT_LOADER_ON_PAGE_LOAD, 'ePrivacyUSA' => \true]];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
