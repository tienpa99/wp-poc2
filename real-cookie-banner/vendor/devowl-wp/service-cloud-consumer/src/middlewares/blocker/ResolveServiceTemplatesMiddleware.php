<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
/**
 * Middleware that transforms `serviceTemplateIdentifiers` expressions into an array of
 * `ServiceTemplate`. This allows us to create a service directly within the blocker template.
 */
class ResolveServiceTemplatesMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        if ($template instanceof BlockerTemplate) {
            $data = [];
            /**
             * Service consumer.
             *
             * @var ServiceCloudConsumer
             */
            $consumer = $this->getVariableResolver()->resolveRequired('service.consumer');
            foreach ($template->serviceTemplateIdentifiers as $serviceIdentifier) {
                $serviceTemplate = $consumer->retrieveBy('identifier', $serviceIdentifier);
                if (\count($serviceTemplate) > 0) {
                    $data[] = $serviceTemplate[0];
                }
            }
            $template->consumerData['serviceTemplates'] = $data;
        }
    }
}
