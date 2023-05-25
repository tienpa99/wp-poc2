<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

/**
 * Abstract implementation of a middleware for consumer life cycles.
 */
abstract class AbstractConsumerMiddleware extends AbstractMiddleware
{
    /**
     * Before downloading from specified datasources. This allows you to e.g. change the
     * language of the context to make translations work as expected.
     *
     * @return void
     */
    public abstract function beforeDownloadAndPersistFromDataSource();
    /**
     * Teardown of `beforeDownloadAndPersistFromDataSource`.
     *
     * @return void
     */
    public abstract function afterDownloadAndPersistFromDataSource();
    /**
     * Before using a template and running all the middlewares of `AbstractTemplateMiddleware`.
     *
     * @return void
     */
    public abstract function beforeUseTemplate();
    /**
     * Teardown of `beforeDownloadAndPersistFromDataSource`.
     *
     * @return void
     */
    public abstract function afterUseTemplate();
}
