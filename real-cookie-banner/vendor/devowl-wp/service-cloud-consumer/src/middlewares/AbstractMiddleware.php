<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
/**
 * Abstract implementation of a middleware for templates.
 */
abstract class AbstractMiddleware
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
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVariableResolver()
    {
        return $this->getConsumer()->getVariableResolver();
    }
}
