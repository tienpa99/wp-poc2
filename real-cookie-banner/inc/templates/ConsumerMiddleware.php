<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\TemporaryTextDomain;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractConsumerMiddleware;
use NOOP_Translations;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Consumer middleware for both service and blocker templates.
 */
class ConsumerMiddleware extends AbstractConsumerMiddleware
{
    /**
     * Temporary text domain.
     *
     * @var TemporaryTextDomain
     */
    private $td;
    // Documented in AbstractConsumerMiddleware
    public function beforeDownloadAndPersistFromDataSource()
    {
        // Got this already created through another consumer (e.g. blocker consumer within a pool)?
        $temporaryTextDomainExists = !\get_translations_for_domain(Hooks::TD_FORCED) instanceof NOOP_Translations;
        if (!$temporaryTextDomainExists) {
            $this->td = Hooks::getInstance()->createTemporaryTextDomain();
        }
    }
    // Documented in AbstractConsumerMiddleware
    public function afterDownloadAndPersistFromDataSource()
    {
        if ($this->td !== null) {
            $this->td->teardown();
            $this->td = null;
        }
    }
    // Documented in AbstractConsumerMiddleware
    public function beforeUseTemplate()
    {
        // Do literally the same
        $this->beforeDownloadAndPersistFromDataSource();
    }
    // Documented in AbstractConsumerMiddleware
    public function afterUseTemplate()
    {
        // Do literally the same
        $this->afterDownloadAndPersistFromDataSource();
    }
}
