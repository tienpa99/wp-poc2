<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Set provider-specific values when the current website owner is the provider.
 */
class ProviderMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof ServiceTemplate && $template->isProviderCurrentWebsite) {
            $variableResolver = $this->getVariableResolver();
            $template->providerPrivacyPolicyUrl = $variableResolver->resolveRequired('consumer.privacyPolicyUrl');
            $template->providerLegalNoticeUrl = $variableResolver->resolveRequired('consumer.legalNoticeUrl');
            $template->provider = $variableResolver->resolveRequired('consumer.provider');
        }
    }
}
