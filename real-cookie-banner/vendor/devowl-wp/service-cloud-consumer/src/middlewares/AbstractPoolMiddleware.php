<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Abstract implementation of a middleware for templates with a pool.
 */
abstract class AbstractPoolMiddleware extends AbstractMiddleware
{
    /**
     * Before persisting the template instance when all consumers within a pool got downloaded.
     *
     * Example: Calculate `contentBlockerTemplates` for `ServiceTemplate`.
     *
     * @param AbstractTemplate $template
     * @param AbstractTemplate[] $typeClassToAllTemplates Key = type class, value = array of templates
     * @return void
     */
    public abstract function beforePersistTemplateWithinPool($template, &$typeClassToAllTemplates);
}
