<?php

namespace DevOwl\RealCookieBanner\lite;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Freemium\CoreLite;
use DevOwl\RealCookieBanner\lite\rest\Service;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\presets\pro\ActiveCampaignSiteTrackingPreset;
use DevOwl\RealCookieBanner\presets\pro\AddThisPreset;
use DevOwl\RealCookieBanner\presets\pro\AddToAnyPreset;
use DevOwl\RealCookieBanner\presets\pro\AdInserterPreset;
use DevOwl\RealCookieBanner\presets\pro\AdobeTypekitPreset;
use DevOwl\RealCookieBanner\presets\pro\AmazonAssociatesWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\Analytify4Preset;
use DevOwl\RealCookieBanner\presets\pro\AnalytifyPreset;
use DevOwl\RealCookieBanner\presets\pro\AnchorFmPreset;
use DevOwl\RealCookieBanner\presets\pro\AppleMusicPreset;
use DevOwl\RealCookieBanner\presets\pro\AwinLinkImageAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\AwinPublisherMasterTagPreset;
use DevOwl\RealCookieBanner\presets\pro\BingAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\BingMapsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ActiveCampaignFormPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ActiveCampaignSiteTrackingPreset as BlockerActiveCampaignSiteTrackingPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AddThisPreset as BlockerAddThisPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AddToAnyPreset as BlockerAddToAnyPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AdInserterPreset as BlockerAdInserterPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AdobeTypekitPreset as BlockerAdobeTypekitPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\Analytify4Preset as BlockerAnalytify4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AnalytifyPreset as BlockerAnalytifyPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AnchorFmPreset as BlockerAnchorFmPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AppleMusicPreset as BlockerAppleMusicPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AwinLinkImageAdsPreset as BlockerAwinLinkImageAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\BingMapsPreset as BlockerBingMapsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\BloomPreset as BlockerBloomPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\CalderaFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\CalendlyPreset as BlockerCalendlyPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\CleverReachRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ContactForm7RecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ConvertKitPreset as BlockerConvertKitPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\CustomFacebookFeedPreset as BlockerCustomFacebookFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\CustomTwitterFeedPreset as BlockerCustomTwitterFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\DailyMotionPreset as BlockerDailyMotionPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\DiscordWidgetPreset as BlockerDiscordWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\DiviContactFormPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ElementorFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\EtrackerPreset as BlockerEtrackerPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\EtrackerWithConsentPreset as BlockerEtrackerWithConsentPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ExactMetrics4Preset as BlockerExactMetrics4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ExactMetricsPreset as BlockerExactMetricsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookForWooCommercePreset as BlockerFacebookForWooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookGraphPreset as BlockerFacebookGraphPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookLikePreset as BlockerFacebookLikePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPagePluginPreset as BlockerFacebookPagePluginPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset as BlockerFacebookPixelPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPostPreset as BlockerFacebookPostPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FacebookSharePreset as BlockerFacebookSharePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FeedsForYoutubePreset as BlockerFeedsForYoutubePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FiveStarRestaurantReservationsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FlickrPreset as BlockerFlickrPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FormidablePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FormMakerRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GAGoogleAnalytics4Preset as BlockerGAGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GAGoogleAnalyticsPreset as BlockerGAGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GetYourGuidePreset as BlockerGetYourGuidePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GiphyPreset as BlockerGiphyPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset as BlockerGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset as BlockerGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleMapsPreset as BlockerGoogleMapsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleRecaptchaPreset as BlockerGoogleRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleTranslatePreset as BlockerGoogleTranslatePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleTrendsPreset as BlockerGoogleTrendsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleUserContentPreset as BlockerGoogleUserContentPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\HappyFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\HotjarPreset as BlockerHotjarPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ImgurPreset as BlockerImgurPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\InstagramPostPreset as BlockerInstagramPostPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\IntercomChatPreset as BlockerIntercomChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\IssuuPreset as BlockerIssuuPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\KlaviyoPreset as BlockerKlaviyoPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\KlikenPreset as BlockerKlikenPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\KomootPreset as BlockerKomootPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\LinkedInAdsPreset as BlockerLinkedInAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\LoomPreset as BlockerLoomPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MailchimpForWooCommercePreset as BlockerMailchimpForWooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MailerLitePreset as BlockerMailerLitePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MailPoetPreset as BlockerMailPoetPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MatomoIntegrationPluginPreset as BlockerMatomoIntegrationPluginPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MatomoPluginPreset as BlockerMatomoPluginPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MetricoolPreset as BlockerMetricoolPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MicrosoftClarityPreset as BlockerMicrosoftClarityPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MonsterInsights4Preset as BlockerMonsterInsights4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MonsterInsightsPreset as BlockerMonsterInsightsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MouseflowPreset as BlockerMouseflowPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MyCruiseExcursionPreset as BlockerMyCruiseExcursionPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MyFontsPreset as BlockerMyFontsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\NinjaFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\OpenStreetMapPreset as BlockerOpenStreetMapPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PerfmattersGA4Preset as BlockerPerfmattersGA4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PerfmattersGAPreset as BlockerPerfmattersGAPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PinterestPreset as BlockerPinterestPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PinterestTagPreset as BlockerPinterestTagPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PiwikProPreset as BlockerPiwikProPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PodigeePreset as BlockerPodigeePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PopupMakerPreset as BlockerPopupMakerPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ProvenExpertWidgetPreset as BlockerProvenExpertWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\QuformRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\RankMathGAPreset as BlockerRankMathGAPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\RankMathGA4Preset as BlockerRankMathGA4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\RedditPreset as BlockerRedditPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\SendinbluePreset as BlockerSendinbluePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\SmashBalloonSocialPhotoFeedPreset as BlockerSmashBalloonSocialPhotoFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\SoundCloudPreset as BlockerSoundCloudPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\SpotifyPreset as BlockerSpotifyPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TaboolaPreset as BlockerTaboolaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TawkToChatPreset as BlockerTawkToChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ThriveLeadsPreset as BlockerThriveLeadsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TidioChatPreset as BlockerTidioChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TikTokPreset as BlockerTikTokPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TiWooCommerceWishlistPreset as BlockerTiWooCommerceWishlistPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TrustindexIoPreset as BlockerTrustindexIoPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TwitterTweetPreset as BlockerTwitterTweetPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TypeformPreset as BlockerTypeformPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\UserlikePreset as BlockerUserlikePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\VGWortPreset as BlockerVGWortPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\VimeoPreset as BlockerVimeoPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\WooCommerceGoogleAnalytics4Preset as BlockerWooCommerceGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\blocker\WooCommerceGoogleAnalyticsPreset as BlockerWooCommerceGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\WooCommerceGoogleAnalyticsProPreset as BlockerWooCommerceGoogleAnalyticsProPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\WPFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\XingEventsPreset as BlockerXingEventsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\YandexMetricaPreset as BlockerYandexMetricaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ZendeskChatPreset as BlockerZendeskChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ZohoBookingsPreset as BlockerZohoBookingsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ZohoFormsPreset as BlockerZohoFormsPreset;
use DevOwl\RealCookieBanner\presets\pro\BloomPreset;
use DevOwl\RealCookieBanner\presets\pro\CalendlyPreset;
use DevOwl\RealCookieBanner\presets\pro\CleanTalkSpamProtectPreset;
use DevOwl\RealCookieBanner\presets\pro\CloudflarePreset;
use DevOwl\RealCookieBanner\presets\pro\ConvertKitPreset;
use DevOwl\RealCookieBanner\presets\pro\CustomFacebookFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\CustomTwitterFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\DailyMotionPreset;
use DevOwl\RealCookieBanner\presets\pro\DiscordWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\EtrackerPreset;
use DevOwl\RealCookieBanner\presets\pro\EtrackerWithConsentPreset;
use DevOwl\RealCookieBanner\presets\pro\ExactMetrics4Preset;
use DevOwl\RealCookieBanner\presets\pro\ExactMetricsPreset;
use DevOwl\RealCookieBanner\presets\pro\EzoicEssentialPreset;
use DevOwl\RealCookieBanner\presets\pro\EzoicMarketingPreset;
use DevOwl\RealCookieBanner\presets\pro\EzoicPreferencesPreset;
use DevOwl\RealCookieBanner\presets\pro\EzoicStatisticPreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookForWooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookGraphPreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookLikePreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookPagePluginPreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookPixelPreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookPostPreset;
use DevOwl\RealCookieBanner\presets\pro\FacebookSharePreset;
use DevOwl\RealCookieBanner\presets\pro\FeedsForYoutubePreset;
use DevOwl\RealCookieBanner\presets\pro\FlickrPreset;
use DevOwl\RealCookieBanner\presets\pro\FoundEePreset;
use DevOwl\RealCookieBanner\presets\pro\FreshchatPreset;
use DevOwl\RealCookieBanner\presets\pro\GAGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\GAGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\GetYourGuidePreset;
use DevOwl\RealCookieBanner\presets\pro\GiphyPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleAds;
use DevOwl\RealCookieBanner\presets\pro\GoogleAdSensePreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleMapsPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleTranslatePreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleTrendsPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleUserContentPreset;
use DevOwl\RealCookieBanner\presets\pro\GtmPreset;
use DevOwl\RealCookieBanner\presets\pro\HCaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\HelpCrunchChatPreset;
use DevOwl\RealCookieBanner\presets\pro\HelpScoutChatPreset;
use DevOwl\RealCookieBanner\presets\pro\HotjarPreset;
use DevOwl\RealCookieBanner\presets\pro\ImgurPreset;
use DevOwl\RealCookieBanner\presets\pro\InstagramPostPreset;
use DevOwl\RealCookieBanner\presets\pro\IntercomChatPreset;
use DevOwl\RealCookieBanner\presets\pro\IssuuPreset;
use DevOwl\RealCookieBanner\presets\pro\KlarnaCheckoutWooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\KlaviyoPreset;
use DevOwl\RealCookieBanner\presets\pro\KlikenPreset;
use DevOwl\RealCookieBanner\presets\pro\KomootPreset;
use DevOwl\RealCookieBanner\presets\pro\LinkedInAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\LoomPreset;
use DevOwl\RealCookieBanner\presets\pro\LuckyOrangePreset;
use DevOwl\RealCookieBanner\presets\pro\MailchimpForWooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\MailerLitePreset;
use DevOwl\RealCookieBanner\presets\pro\MailPoetPreset;
use DevOwl\RealCookieBanner\presets\pro\MatomoIntegrationPluginPreset;
use DevOwl\RealCookieBanner\presets\pro\MatomoPluginPreset;
use DevOwl\RealCookieBanner\presets\pro\MatomoPreset;
use DevOwl\RealCookieBanner\presets\pro\MetricoolPreset;
use DevOwl\RealCookieBanner\presets\pro\MicrosoftClarityPreset;
use DevOwl\RealCookieBanner\presets\pro\MonsterInsights4Preset;
use DevOwl\RealCookieBanner\presets\pro\MonsterInsightsPreset;
use DevOwl\RealCookieBanner\presets\pro\MouseflowPreset;
use DevOwl\RealCookieBanner\presets\pro\MtmPreset;
use DevOwl\RealCookieBanner\presets\pro\MyCruiseExcursionPreset;
use DevOwl\RealCookieBanner\presets\pro\MyFontsPreset;
use DevOwl\RealCookieBanner\presets\pro\OpenStreetMapPreset;
use DevOwl\RealCookieBanner\presets\pro\PaddleComPreset;
use DevOwl\RealCookieBanner\presets\pro\PerfmattersGA4Preset;
use DevOwl\RealCookieBanner\presets\pro\PerfmattersGAPreset;
use DevOwl\RealCookieBanner\presets\pro\PinterestPreset;
use DevOwl\RealCookieBanner\presets\pro\PinterestTagPreset;
use DevOwl\RealCookieBanner\presets\pro\PiwikProPreset;
use DevOwl\RealCookieBanner\presets\pro\PodigeePreset;
use DevOwl\RealCookieBanner\presets\pro\PolyLangPreset;
use DevOwl\RealCookieBanner\presets\pro\PopupMakerPreset;
use DevOwl\RealCookieBanner\presets\pro\ProvenExpertWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\QuformPreset;
use DevOwl\RealCookieBanner\presets\pro\RankMathGAPreset;
use DevOwl\RealCookieBanner\presets\pro\RankMathGA4Preset;
use DevOwl\RealCookieBanner\presets\pro\ReamazeChatPreset;
use DevOwl\RealCookieBanner\presets\pro\RedditPreset;
use DevOwl\RealCookieBanner\presets\pro\SendinbluePreset;
use DevOwl\RealCookieBanner\presets\pro\SmashBalloonSocialPhotoFeedPreset;
use DevOwl\RealCookieBanner\presets\pro\SoundCloudPreset;
use DevOwl\RealCookieBanner\presets\pro\SpotifyPreset;
use DevOwl\RealCookieBanner\presets\pro\StripePreset;
use DevOwl\RealCookieBanner\presets\pro\TaboolaPreset;
use DevOwl\RealCookieBanner\presets\pro\TawkToChatPreset;
use DevOwl\RealCookieBanner\presets\pro\ThriveLeadsPreset;
use DevOwl\RealCookieBanner\presets\pro\TidioChatPreset;
use DevOwl\RealCookieBanner\presets\pro\TikTokPixelPreset;
use DevOwl\RealCookieBanner\presets\pro\TikTokPreset;
use DevOwl\RealCookieBanner\presets\pro\TiWooCommerceWishlistPreset;
use DevOwl\RealCookieBanner\presets\pro\TranslatePressPreset;
use DevOwl\RealCookieBanner\presets\pro\TrustindexIoPreset;
use DevOwl\RealCookieBanner\presets\pro\TwitterTweetPreset;
use DevOwl\RealCookieBanner\presets\pro\TypeformPreset;
use DevOwl\RealCookieBanner\presets\pro\UltimateMemberPreset;
use DevOwl\RealCookieBanner\presets\pro\UserlikePreset;
use DevOwl\RealCookieBanner\presets\pro\VGWortPreset;
use DevOwl\RealCookieBanner\presets\pro\VimeoPreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGatewayStripePreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGeolocationPreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalyticsProPreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommercePreset;
use DevOwl\RealCookieBanner\presets\pro\WordfencePreset;
use DevOwl\RealCookieBanner\presets\pro\WPCerberSecurityPreset;
use DevOwl\RealCookieBanner\presets\pro\WPMLPreset;
use DevOwl\RealCookieBanner\presets\pro\XingEventsPreset;
use DevOwl\RealCookieBanner\presets\pro\YandexMetricaPreset;
use DevOwl\RealCookieBanner\presets\pro\ZendeskChatPreset;
use DevOwl\RealCookieBanner\presets\pro\ZohoBookingsPreset;
use DevOwl\RealCookieBanner\presets\pro\ZohoFormsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
trait Core
{
    use CoreLite;
    // Documented in IOverrideCore
    public function overrideConstruct()
    {
        \add_filter('RCB/Presets/Cookies', [$this, 'createProCookiePresets']);
        \add_filter('RCB/Presets/Blocker', [$this, 'createProBlockerPresets']);
    }
    // Documented in IOverrideCore
    public function overrideRegisterSettings()
    {
        // Silence is golden.
    }
    // Documented in IOverrideCore
    public function overrideRegisterPostTypes()
    {
        // Silence is golden.
    }
    // Documented in IOverrideCore
    public function overrideInit()
    {
        \add_action('rest_api_init', [Service::instance(), 'rest_api_init']);
        \add_filter('RCB/Revision/Current', [new \DevOwl\RealCookieBanner\lite\FomoCoupon(), 'revisionCurrent']);
    }
    /**
     * Create PRO-specific cookie presets.
     *
     * @param array $result
     */
    public function createProCookiePresets($result)
    {
        return \array_merge($result, [PresetIdentifierMap::CLOUDFLARE => CloudflarePreset::class, PresetIdentifierMap::POLYLANG => PolyLangPreset::class, PresetIdentifierMap::WPML => WPMLPreset::class, PresetIdentifierMap::WOOCOMMERCE => WooCommercePreset::class, PresetIdentifierMap::ULTIMATE_MEMBER => UltimateMemberPreset::class, PresetIdentifierMap::GTM => GtmPreset::class, PresetIdentifierMap::MTM => MtmPreset::class, PresetIdentifierMap::GOOGLE_MAPS => GoogleMapsPreset::class, PresetIdentifierMap::FACEBOOK_POST => FacebookPostPreset::class, PresetIdentifierMap::INSTAGRAM_POST => InstagramPostPreset::class, PresetIdentifierMap::TWITTER_TWEET => TwitterTweetPreset::class, PresetIdentifierMap::GOOGLE_RECAPTCHA => GoogleRecaptchaPreset::class, PresetIdentifierMap::GOOGLE_ANALYTICS => GoogleAnalyticsPreset::class, PresetIdentifierMap::MATOMO => MatomoPreset::class, PresetIdentifierMap::GOOGLE_AD_SENSE => GoogleAdSensePreset::class, PresetIdentifierMap::GOOGLE_ADS => GoogleAds::class, PresetIdentifierMap::FACEBOOK_PIXEL => FacebookPixelPreset::class, PresetIdentifierMap::FACEBOOK_LIKE => FacebookLikePreset::class, PresetIdentifierMap::FACEBOOK_SHARE => FacebookSharePreset::class, PresetIdentifierMap::HOTJAR => HotjarPreset::class, PresetIdentifierMap::AMAZON_ASSOCIATES_WIDGET => AmazonAssociatesWidgetPreset::class, PresetIdentifierMap::INTERCOM_CHAT => IntercomChatPreset::class, PresetIdentifierMap::ZENDESK_CHAT => ZendeskChatPreset::class, PresetIdentifierMap::FRESHCHAT => FreshchatPreset::class, PresetIdentifierMap::HELP_CRUNCH_CHAT => HelpCrunchChatPreset::class, PresetIdentifierMap::HELP_SCOUT_CHAT => HelpScoutChatPreset::class, PresetIdentifierMap::TIDIO_CHAT => TidioChatPreset::class, PresetIdentifierMap::TAWK_TO_CHAT => TawkToChatPreset::class, PresetIdentifierMap::REAMAZE_CHAT => ReamazeChatPreset::class, PresetIdentifierMap::PINTEREST => PinterestPreset::class, PresetIdentifierMap::IMGUR => ImgurPreset::class, PresetIdentifierMap::GOOGLE_TRANSLATE => GoogleTranslatePreset::class, PresetIdentifierMap::ADOBE_TYPEKIT => AdobeTypekitPreset::class, PresetIdentifierMap::FACEBOOK_PAGE_PLUGIN => FacebookPagePluginPreset::class, PresetIdentifierMap::FLICKR => FlickrPreset::class, PresetIdentifierMap::VG_WORT => VGWortPreset::class, PresetIdentifierMap::PADDLE_COM => PaddleComPreset::class, PresetIdentifierMap::GOOGLE_ANALYTICS_4 => GoogleAnalytics4Preset::class, PresetIdentifierMap::MICROSOFT_CLARITY => MicrosoftClarityPreset::class, PresetIdentifierMap::GOOGLE_TRENDS => GoogleTrendsPreset::class, PresetIdentifierMap::ZOHO_BOOKINGS => ZohoBookingsPreset::class, PresetIdentifierMap::ZOHO_FORMS => ZohoFormsPreset::class, PresetIdentifierMap::ADD_TO_ANY => AddToAnyPreset::class, PresetIdentifierMap::APPLE_MUSIC => AppleMusicPreset::class, PresetIdentifierMap::ANCHOR_FM => AnchorFmPreset::class, PresetIdentifierMap::SPOTIFY => SpotifyPreset::class, PresetIdentifierMap::REDDIT => RedditPreset::class, PresetIdentifierMap::TIKTOK => TikTokPreset::class, PresetIdentifierMap::BING_MAPS => BingMapsPreset::class, PresetIdentifierMap::ADD_THIS => AddThisPreset::class, PresetIdentifierMap::ACTIVE_CAMPAIGN_SITE_TRACKING => ActiveCampaignSiteTrackingPreset::class, PresetIdentifierMap::DISCORD_WIDGET => DiscordWidgetPreset::class, PresetIdentifierMap::MY_FONTS => MyFontsPreset::class, PresetIdentifierMap::PROVEN_EXPERT_WIDGET => ProvenExpertWidgetPreset::class, PresetIdentifierMap::USERLIKE => UserlikePreset::class, PresetIdentifierMap::MOUSEFLOW => MouseflowPreset::class, PresetIdentifierMap::MONSTERINSIGHTS => MonsterInsightsPreset::class, PresetIdentifierMap::GA_GOOGLE_ANALYTICS => GAGoogleAnalyticsPreset::class, PresetIdentifierMap::GA_GOOGLE_ANALYTICS_4 => GAGoogleAnalytics4Preset::class, PresetIdentifierMap::EXACT_METRICS => ExactMetricsPreset::class, PresetIdentifierMap::ANALYTIFY => AnalytifyPreset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS => WooCommerceGoogleAnalyticsPreset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS_4 => WooCommerceGoogleAnalytics4Preset::class, PresetIdentifierMap::FACEBOOK_FOR_WOOCOMMERCE => FacebookForWooCommercePreset::class, PresetIdentifierMap::MATOMO_PLUGIN => MatomoPluginPreset::class, PresetIdentifierMap::STRIPE => StripePreset::class, PresetIdentifierMap::WOOCOMMERCE_GATEWAY_STRIPE => WooCommerceGatewayStripePreset::class, PresetIdentifierMap::MAILCHIMP_FOR_WOOCOMMERCE => MailchimpForWooCommercePreset::class, PresetIdentifierMap::LUCKY_ORANGE => LuckyOrangePreset::class, PresetIdentifierMap::CUSTOM_FACEBOOK_FEED => CustomFacebookFeedPreset::class, PresetIdentifierMap::CUSTOM_TWITTER_FEED => CustomTwitterFeedPreset::class, PresetIdentifierMap::FEEDS_FOR_YOUTUBE => FeedsForYoutubePreset::class, PresetIdentifierMap::MAILERLITE => MailerLitePreset::class, PresetIdentifierMap::CLEANTALK_SPAM_PROTECT => CleanTalkSpamProtectPreset::class, PresetIdentifierMap::WORDFENCE => WordfencePreset::class, PresetIdentifierMap::TRANSLATEPRESS => TranslatePressPreset::class, PresetIdentifierMap::ISSUU => IssuuPreset::class, PresetIdentifierMap::KLARNA_CHECKOUT_WOOCOMMERCE => KlarnaCheckoutWooCommercePreset::class, PresetIdentifierMap::QUFORM => QuformPreset::class, PresetIdentifierMap::PINTEREST_TAG => PinterestTagPreset::class, PresetIdentifierMap::HCAPTCHA => HCaptchaPreset::class, PresetIdentifierMap::BING_ADS => BingAdsPreset::class, PresetIdentifierMap::YANDEX_METRICA => YandexMetricaPreset::class, PresetIdentifierMap::FOUND_EE => FoundEePreset::class, PresetIdentifierMap::BLOOM => BloomPreset::class, PresetIdentifierMap::TYPEFORM => TypeformPreset::class, PresetIdentifierMap::RANKMATH_GA => RankMathGAPreset::class, PresetIdentifierMap::THRIVE_LEADS => ThriveLeadsPreset::class, PresetIdentifierMap::POPUP_MAKER => PopupMakerPreset::class, PresetIdentifierMap::METRICOOL => MetricoolPreset::class, PresetIdentifierMap::EZOIC_ESSENTIAL => EzoicEssentialPreset::class, PresetIdentifierMap::EZOIC_PREFERENCES => EzoicPreferencesPreset::class, PresetIdentifierMap::EZOIC_STATISTIC => EzoicStatisticPreset::class, PresetIdentifierMap::EZOIC_MARKETING => EzoicMarketingPreset::class, PresetIdentifierMap::SOUNDCLOUD => SoundCloudPreset::class, PresetIdentifierMap::VIMEO => VimeoPreset::class, PresetIdentifierMap::XING_EVENTS => XingEventsPreset::class, PresetIdentifierMap::SENDINBLUE => SendinbluePreset::class, PresetIdentifierMap::AWIN_LINK_AND_IMAGE_ADS => AwinLinkImageAdsPreset::class, PresetIdentifierMap::AWIN_PUBLISHER_MASTERTAG => AwinPublisherMasterTagPreset::class, PresetIdentifierMap::CONVERTKIT => ConvertKitPreset::class, PresetIdentifierMap::MATOMO_INTEGRATION_PLUGIN => MatomoIntegrationPluginPreset::class, PresetIdentifierMap::GETYOURGUIDE => GetYourGuidePreset::class, PresetIdentifierMap::CALENDLY => CalendlyPreset::class, PresetIdentifierMap::MY_CRUISE_EXCURSION => MyCruiseExcursionPreset::class, PresetIdentifierMap::MAILPOET => MailPoetPreset::class, PresetIdentifierMap::SMASH_BALLOON_SOCIAL_PHOTO_FEED => SmashBalloonSocialPhotoFeedPreset::class, PresetIdentifierMap::PODIGEE => PodigeePreset::class, PresetIdentifierMap::AD_INSERTER => AdInserterPreset::class, PresetIdentifierMap::DAILYMOTION => DailyMotionPreset::class, PresetIdentifierMap::GIPHY => GiphyPreset::class, PresetIdentifierMap::LINKEDIN_ADS => LinkedInAdsPreset::class, PresetIdentifierMap::LOOM => LoomPreset::class, PresetIdentifierMap::OPEN_STREET_MAP => OpenStreetMapPreset::class, PresetIdentifierMap::TIKTOK_PIXEL => TikTokPixelPreset::class, PresetIdentifierMap::TABOOLA => TaboolaPreset::class, PresetIdentifierMap::PERFMATTERS_GA => PerfmattersGAPreset::class, PresetIdentifierMap::PERFMATTERS_GA4 => PerfmattersGA4Preset::class, PresetIdentifierMap::WP_CERBER_SECURITY => WPCerberSecurityPreset::class, PresetIdentifierMap::KOMOOT => KomootPreset::class, PresetIdentifierMap::WOOCOMMERCE_GEOLOCATION => WooCommerceGeolocationPreset::class, PresetIdentifierMap::KLIKEN => KlikenPreset::class, PresetIdentifierMap::KLAVIYO => KlaviyoPreset::class, PresetIdentifierMap::TI_WOOCOMMERCE_WISHLIST => TiWooCommerceWishlistPreset::class, PresetIdentifierMap::ANALYTIFY_4 => Analytify4Preset::class, PresetIdentifierMap::MONSTERINSIGHTS_4 => MonsterInsights4Preset::class, PresetIdentifierMap::EXACT_METRICS_4 => ExactMetrics4Preset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS_PRO => WooCommerceGoogleAnalyticsProPreset::class, PresetIdentifierMap::PIWIK_PRO => PiwikProPreset::class, PresetIdentifierMap::FACEBOOK_GRAPH => FacebookGraphPreset::class, PresetIdentifierMap::GOOGLE_USER_CONTENT => GoogleUserContentPreset::class, PresetIdentifierMap::TRUSTINDEX_IO => TrustindexIoPreset::class, PresetIdentifierMap::ETRACKER => EtrackerPreset::class, PresetIdentifierMap::ETRACKER_WITH_CONSENT => EtrackerWithConsentPreset::class, PresetIdentifierMap::RANKMATH_GA_4 => RankMathGA4Preset::class]);
    }
    /**
     * Create PRO-specific blocker presets.
     *
     * @param array $result
     */
    public function createProBlockerPresets($result)
    {
        return \array_merge($result, [PresetIdentifierMap::PINTEREST => BlockerPinterestPreset::class, PresetIdentifierMap::IMGUR => BlockerImgurPreset::class, PresetIdentifierMap::GOOGLE_TRANSLATE => BlockerGoogleTranslatePreset::class, PresetIdentifierMap::GOOGLE_RECAPTCHA => BlockerGoogleRecaptchaPreset::class, PresetIdentifierMap::ADOBE_TYPEKIT => BlockerAdobeTypekitPreset::class, PresetIdentifierMap::GOOGLE_MAPS => BlockerGoogleMapsPreset::class, PresetIdentifierMap::TWITTER_TWEET => BlockerTwitterTweetPreset::class, PresetIdentifierMap::FLICKR => BlockerFlickrPreset::class, PresetIdentifierMap::INSTAGRAM_POST => BlockerInstagramPostPreset::class, PresetIdentifierMap::FACEBOOK_PAGE_PLUGIN => BlockerFacebookPagePluginPreset::class, PresetIdentifierMap::FACEBOOK_SHARE => BlockerFacebookSharePreset::class, PresetIdentifierMap::FACEBOOK_LIKE => BlockerFacebookLikePreset::class, PresetIdentifierMap::FACEBOOK_POST => BlockerFacebookPostPreset::class, PresetIdentifierMap::CONTACT_FORM_7_RECAPTCHA => ContactForm7RecaptchaPreset::class, PresetIdentifierMap::FORM_MAKER_RECAPTCHA => FormMakerRecaptchaPreset::class, PresetIdentifierMap::CALDERA_FORMS_RECAPTCHA => CalderaFormsPreset::class, PresetIdentifierMap::NINJA_FORMS_RECAPTCHA => NinjaFormsPreset::class, PresetIdentifierMap::WPFORMS_RECAPTCHA => WPFormsPreset::class, PresetIdentifierMap::FORMIDABLE_RECAPTCHA => FormidablePreset::class, PresetIdentifierMap::VG_WORT => BlockerVGWortPreset::class, PresetIdentifierMap::GOOGLE_TRENDS => BlockerGoogleTrendsPreset::class, PresetIdentifierMap::ZOHO_BOOKINGS => BlockerZohoBookingsPreset::class, PresetIdentifierMap::ZOHO_FORMS => BlockerZohoFormsPreset::class, PresetIdentifierMap::ADD_TO_ANY => BlockerAddToAnyPreset::class, PresetIdentifierMap::APPLE_MUSIC => BlockerAppleMusicPreset::class, PresetIdentifierMap::ANCHOR_FM => BlockerAnchorFmPreset::class, PresetIdentifierMap::SPOTIFY => BlockerSpotifyPreset::class, PresetIdentifierMap::REDDIT => BlockerRedditPreset::class, PresetIdentifierMap::TIKTOK => BlockerTikTokPreset::class, PresetIdentifierMap::BING_MAPS => BlockerBingMapsPreset::class, PresetIdentifierMap::ADD_THIS => BlockerAddThisPreset::class, PresetIdentifierMap::ACTIVE_CAMPAIGN_RECAPTCHA => ActiveCampaignFormPreset::class, PresetIdentifierMap::DISCORD_WIDGET => BlockerDiscordWidgetPreset::class, PresetIdentifierMap::FACEBOOK_PIXEL => BlockerFacebookPixelPreset::class, PresetIdentifierMap::MY_FONTS => BlockerMyFontsPreset::class, PresetIdentifierMap::PROVEN_EXPERT_WIDGET => BlockerProvenExpertWidgetPreset::class, PresetIdentifierMap::GOOGLE_ANALYTICS => BlockerGoogleAnalyticsPreset::class, PresetIdentifierMap::MONSTERINSIGHTS => BlockerMonsterInsightsPreset::class, PresetIdentifierMap::GA_GOOGLE_ANALYTICS => BlockerGAGoogleAnalyticsPreset::class, PresetIdentifierMap::EXACT_METRICS => BlockerExactMetricsPreset::class, PresetIdentifierMap::ANALYTIFY => BlockerAnalytifyPreset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS => BlockerWooCommerceGoogleAnalyticsPreset::class, PresetIdentifierMap::FACEBOOK_FOR_WOOCOMMERCE => BlockerFacebookForWooCommercePreset::class, PresetIdentifierMap::MATOMO_PLUGIN => BlockerMatomoPluginPreset::class, PresetIdentifierMap::MAILCHIMP_FOR_WOOCOMMERCE => BlockerMailchimpForWooCommercePreset::class, PresetIdentifierMap::CLEVERREACH_RECAPTCHA => CleverReachRecaptchaPreset::class, PresetIdentifierMap::CUSTOM_FACEBOOK_FEED => BlockerCustomFacebookFeedPreset::class, PresetIdentifierMap::CUSTOM_TWITTER_FEED => BlockerCustomTwitterFeedPreset::class, PresetIdentifierMap::FEEDS_FOR_YOUTUBE => BlockerFeedsForYoutubePreset::class, PresetIdentifierMap::MAILERLITE => BlockerMailerLitePreset::class, PresetIdentifierMap::QUFORM => QuformRecaptchaPreset::class, PresetIdentifierMap::ISSUU => BlockerIssuuPreset::class, PresetIdentifierMap::PINTEREST_TAG => BlockerPinterestTagPreset::class, PresetIdentifierMap::YANDEX_METRICA => BlockerYandexMetricaPreset::class, PresetIdentifierMap::BLOOM => BlockerBloomPreset::class, PresetIdentifierMap::TYPEFORM => BlockerTypeformPreset::class, PresetIdentifierMap::RANKMATH_GA => BlockerRankMathGAPreset::class, PresetIdentifierMap::THRIVE_LEADS => BlockerThriveLeadsPreset::class, PresetIdentifierMap::POPUP_MAKER => BlockerPopupMakerPreset::class, PresetIdentifierMap::METRICOOL => BlockerMetricoolPreset::class, PresetIdentifierMap::SOUNDCLOUD => BlockerSoundCloudPreset::class, PresetIdentifierMap::VIMEO => BlockerVimeoPreset::class, PresetIdentifierMap::XING_EVENTS => BlockerXingEventsPreset::class, PresetIdentifierMap::SENDINBLUE => BlockerSendinbluePreset::class, PresetIdentifierMap::AWIN_LINK_AND_IMAGE_ADS => BlockerAwinLinkImageAdsPreset::class, PresetIdentifierMap::CONVERTKIT => BlockerConvertKitPreset::class, PresetIdentifierMap::MATOMO_INTEGRATION_PLUGIN => BlockerMatomoIntegrationPluginPreset::class, PresetIdentifierMap::GETYOURGUIDE => BlockerGetYourGuidePreset::class, PresetIdentifierMap::CALENDLY => BlockerCalendlyPreset::class, PresetIdentifierMap::MY_CRUISE_EXCURSION => BlockerMyCruiseExcursionPreset::class, PresetIdentifierMap::MAILPOET => BlockerMailPoetPreset::class, PresetIdentifierMap::SMASH_BALLOON_SOCIAL_PHOTO_FEED => BlockerSmashBalloonSocialPhotoFeedPreset::class, PresetIdentifierMap::ACTIVE_CAMPAIGN_SITE_TRACKING => BlockerActiveCampaignSiteTrackingPreset::class, PresetIdentifierMap::HOTJAR => BlockerHotjarPreset::class, PresetIdentifierMap::INTERCOM_CHAT => BlockerIntercomChatPreset::class, PresetIdentifierMap::MICROSOFT_CLARITY => BlockerMicrosoftClarityPreset::class, PresetIdentifierMap::MOUSEFLOW => BlockerMouseflowPreset::class, PresetIdentifierMap::TAWK_TO_CHAT => BlockerTawkToChatPreset::class, PresetIdentifierMap::TIDIO_CHAT => BlockerTidioChatPreset::class, PresetIdentifierMap::USERLIKE => BlockerUserlikePreset::class, PresetIdentifierMap::ZENDESK_CHAT => BlockerZendeskChatPreset::class, PresetIdentifierMap::GOOGLE_ANALYTICS_4 => BlockerGoogleAnalytics4Preset::class, PresetIdentifierMap::GA_GOOGLE_ANALYTICS_4 => BlockerGAGoogleAnalytics4Preset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS_4 => BlockerWooCommerceGoogleAnalytics4Preset::class, PresetIdentifierMap::PODIGEE => BlockerPodigeePreset::class, PresetIdentifierMap::AD_INSERTER => BlockerAdInserterPreset::class, PresetIdentifierMap::DAILYMOTION => BlockerDailyMotionPreset::class, PresetIdentifierMap::GIPHY => BlockerGiphyPreset::class, PresetIdentifierMap::LINKEDIN_ADS => BlockerLinkedInAdsPreset::class, PresetIdentifierMap::LOOM => BlockerLoomPreset::class, PresetIdentifierMap::OPEN_STREET_MAP => BlockerOpenStreetMapPreset::class, PresetIdentifierMap::TABOOLA => BlockerTaboolaPreset::class, PresetIdentifierMap::ELEMENTOR_FORMS_RECAPTCHA => ElementorFormsPreset::class, PresetIdentifierMap::PERFMATTERS_GA => BlockerPerfmattersGAPreset::class, PresetIdentifierMap::PERFMATTERS_GA4 => BlockerPerfmattersGA4Preset::class, PresetIdentifierMap::KOMOOT => BlockerKomootPreset::class, PresetIdentifierMap::KLIKEN => BlockerKlikenPreset::class, PresetIdentifierMap::KLAVIYO => BlockerKlaviyoPreset::class, PresetIdentifierMap::TI_WOOCOMMERCE_WISHLIST => BlockerTiWooCommerceWishlistPreset::class, PresetIdentifierMap::HAPPYFORMS_RECAPTCHA => HappyFormsPreset::class, PresetIdentifierMap::ANALYTIFY_4 => BlockerAnalytify4Preset::class, PresetIdentifierMap::MONSTERINSIGHTS_4 => BlockerMonsterInsights4Preset::class, PresetIdentifierMap::EXACT_METRICS_4 => BlockerExactMetrics4Preset::class, PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS_PRO => BlockerWooCommerceGoogleAnalyticsProPreset::class, PresetIdentifierMap::FIVE_STAR_RESTAURANT_RESERVATION => FiveStarRestaurantReservationsPreset::class, PresetIdentifierMap::DIVI_CONTACT_FORM_RECAPTCHA => DiviContactFormPreset::class, PresetIdentifierMap::PIWIK_PRO => BlockerPiwikProPreset::class, PresetIdentifierMap::FACEBOOK_GRAPH => BlockerFacebookGraphPreset::class, PresetIdentifierMap::GOOGLE_USER_CONTENT => BlockerGoogleUserContentPreset::class, PresetIdentifierMap::TRUSTINDEX_IO => BlockerTrustindexIoPreset::class, PresetIdentifierMap::ETRACKER => BlockerEtrackerPreset::class, PresetIdentifierMap::ETRACKER_WITH_CONSENT => BlockerEtrackerWithConsentPreset::class, PresetIdentifierMap::RANKMATH_GA_4 => BlockerRankMathGA4Preset::class]);
    }
}
