<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Abstract implementation of a data source which allows to retrieve `AbstractTemplate` instances
 * from remote. This should actually not implement any caching mechanism as this is done through
 * `AbstractStore`!
 */
abstract class AbstractDataSource
{
    private $consumer;
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }
    /**
     * Retrieve all templates from data source (e.g. request to service cloud).
     *
     * @return AbstractTemplate[]
     */
    public abstract function retrieve();
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
}
