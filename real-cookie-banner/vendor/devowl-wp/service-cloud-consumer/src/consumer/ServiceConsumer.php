<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\ExistsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\OneOfMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\DisableTechnicalHandlingWhenOneOfMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\GroupMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\ManagerMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\ProviderMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\ServiceAvailableBlockerTemplatesMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\services\TechnicalDefinitionsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\VarMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
/**
 * Predefined service cloud consumer for `ServiceTemplate` with all required middlewares registered.
 */
class ServiceConsumer extends ServiceCloudConsumer
{
    /**
     * C'tor.
     */
    public function __construct()
    {
        parent::__construct(ServiceTemplate::class);
        $this->addMiddleware(new ExistsMiddleware($this));
        $this->addMiddleware(new OneOfMiddleware($this));
        $this->addMiddleware(new DisableTechnicalHandlingWhenOneOfMiddleware($this));
        $this->addMiddleware(new ManagerMiddleware($this));
        $this->addMiddleware(new ServiceAvailableBlockerTemplatesMiddleware($this));
        $this->addMiddleware(new VarMiddleware($this));
        $this->addMiddleware(new ProviderMiddleware($this));
        $this->addMiddleware(new TechnicalDefinitionsMiddleware($this));
        $this->addMiddleware(new GroupMiddleware($this));
    }
}
