<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources\AbstractDataSource;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middleware\AbstractMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractConsumerMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractPoolMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\AbstractStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\InMemoryStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Service cloud consumer implementation which can be used for service or
 * blocker templates.
 */
class ServiceCloudConsumer
{
    private $typeClass;
    /**
     * In-memory cache of collected templates of all data sources.
     *
     * @var AbstractTemplate[]
     */
    private $templates;
    /**
     * Data sources.
     *
     * @var AbstractDataSource[]
     */
    private $dataSources = [];
    /**
     * Middlewares.
     *
     * @var AbstractMiddleware[]
     */
    private $middlewares = [];
    /**
     * Storage.
     *
     * @var AbstractStorage
     */
    private $storage;
    /**
     * VariableResolver.
     *
     * @var VariableResolver
     */
    private $variableResolver;
    /**
     * Consumer pools.
     *
     * @var ConsumerPool[]
     */
    private $pools = [];
    /**
     * C'tor.
     *
     * @param string $typeClass Should be the class which extends from `AbstractTemplate`
     */
    public function __construct($typeClass)
    {
        $this->typeClass = $typeClass;
        $this->storage = new InMemoryStorage($this);
        $this->variableResolver = new VariableResolver($this);
    }
    /**
     * Get all template instances.
     *
     * @param boolean $forceInvalidate
     * @return AbstractTemplate[]
     */
    public function retrieve($forceInvalidate = \false)
    {
        if ($forceInvalidate || $this->storage->shouldInvalidate()) {
            // Force usage from data source
            $this->downloadAndPersistFromDataSource();
        } elseif (!\is_array($this->templates)) {
            // No results known yet, get from storage
            $storageResult = $this->storage->retrieve();
            if ($storageResult === \false) {
                // The storage does not know anything about templates yet, so usage from data source
                $this->downloadAndPersistFromDataSource();
            } else {
                $this->templates = $this->filterTemplates($storageResult);
            }
        }
        return $this->templates;
    }
    /**
     * Get a single template instance by criteria.
     *
     * @param string $field Can be `identifier` or your custom implementation which is supported by your storage
     * @param mixed $value
     * @param boolean $forceInvalidate
     * @return AbstractTemplate[]
     */
    public function retrieveBy($field, $value, $forceInvalidate = \false)
    {
        if ($forceInvalidate || $this->storage->shouldInvalidate()) {
            // Force usage from data source
            $this->downloadAndPersistFromDataSource();
            $storageResult = $this->storage->retrieveBy($field, $value);
            return $storageResult === \false ? [] : $this->filterTemplates($storageResult);
        } else {
            $storageResult = $this->storage->retrieveBy($field, $value);
            if ($storageResult === \false) {
                // The storage does not know anything about templates yet, so usage from data source
                $this->downloadAndPersistFromDataSource();
                $storageResult = $this->storage->retrieveBy($field, $value);
                return $storageResult === \false ? [] : $this->filterTemplates($storageResult);
            } else {
                return $this->filterTemplates($storageResult);
            }
        }
    }
    /**
     * Add data source to our consumer.
     *
     * @param AbstractDataSource $dataSource
     */
    public function addDataSource($dataSource)
    {
        $this->dataSources[] = $dataSource;
    }
    /**
     * Add middleware to our consumer.
     *
     * @param AbstractMiddleware $middleware
     */
    public function addMiddleware($middleware)
    {
        $this->middlewares[] = $middleware;
    }
    /**
     * Add new consumer pool.
     *
     * @param ConsumerPool $pool
     * @codeCoverageIgnore
     */
    public function addPool($pool)
    {
        $this->pools[] = $pool;
    }
    /**
     * Remove unwanted classes from the templates array.
     *
     * @param AbstractTemplate[] $arr
     * @return AbstractTemplate[]
     */
    protected function filterTemplates($arr)
    {
        if (!\is_array($arr)) {
            return [];
        }
        foreach ($arr as $key => $template) {
            if (\get_class($template) !== $this->typeClass) {
                unset($arr[$key]);
            }
        }
        return \array_values($arr);
    }
    /**
     * Download templates from data sources and persist to storage. This also
     * respected the `ConsumerPool` if one given.
     *
     * @return AbstractTemplate[]
     */
    protected function downloadAndPersistFromDataSource()
    {
        $this->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) {
            $middleware->beforeDownloadAndPersistFromDataSource();
        });
        if (\count($this->pools) === 0) {
            $this->storage->persist($this->downloadFromDataSource());
        } else {
            /**
             * Get unique consumers from multiple pools.
             *
             * @var ServiceCloudConsumer[]
             */
            $consumers = [];
            foreach ($this->pools as $pool) {
                foreach ($pool->getConsumers() as $c) {
                    if (!\in_array($c, $consumers, \true)) {
                        $consumers[] = $c;
                    }
                }
            }
            $typeClassToAllTemplates = [];
            foreach ($consumers as $consumer) {
                $typeClassToAllTemplates[$consumer->getTypeClass()] = $consumer->downloadFromDataSource();
            }
            // Apply middlewares
            $this->runMiddleware(AbstractPoolMiddleware::class, function ($middleware) use(&$typeClassToAllTemplates) {
                foreach ($typeClassToAllTemplates as $dataSourceResult) {
                    foreach ($dataSourceResult as $dsTemplate) {
                        $middleware->beforePersistTemplateWithinPool($dsTemplate, $typeClassToAllTemplates);
                    }
                }
            });
            // Finally, store it
            foreach ($consumers as $consumer) {
                $consumer->getStorage()->persist($typeClassToAllTemplates[$consumer->getTypeClass()]);
            }
        }
        $this->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) {
            $middleware->afterDownloadAndPersistFromDataSource();
        });
    }
    /**
     * Download templates from data sources. This does not persist anything to the storage,
     * for this use `downloadAndPersistFromDataSource` or `retrieve` instead!
     *
     * @return AbstractTemplate[]
     */
    protected function downloadFromDataSource()
    {
        /**
         * Data source results, merged.
         *
         * @var AbstractTemplate[]
         */
        $dataSourceResult = [];
        foreach ($this->dataSources as $dataSource) {
            foreach ($dataSource->retrieve() as $dsTemplate) {
                if (isset($dataSourceResult[$dsTemplate->identifier])) {
                    // Already added, skip this one
                    continue;
                }
                $dataSourceResult[$dsTemplate->identifier] = $dsTemplate;
            }
        }
        $dataSourceResult = $this->filterTemplates(\array_values($dataSourceResult));
        // Sort by headline
        \usort($dataSourceResult, function ($a, $b) {
            return \strcmp($a->headline, $b->headline);
        });
        foreach ($dataSourceResult as $dsTemplate) {
            $dsTemplate->memoizeBeforeMiddleware();
        }
        // Apply middlewares
        $this->runMiddleware(AbstractTemplateMiddleware::class, function ($middleware) use($dataSourceResult) {
            foreach ($dataSourceResult as $dsTemplate) {
                $middleware->beforePersistTemplate($dsTemplate, $dataSourceResult);
            }
        });
        $this->templates = $dataSourceResult;
        return $dataSourceResult;
    }
    /**
     * Run a closure for each middleware type. This also respects `AbstractConsumerMiddleware`.
     *
     * @param string $middlewareTypeClass
     * @param callable $closure
     */
    public function runMiddleware($middlewareTypeClass, $closure)
    {
        foreach ($this->middlewares as $middleware) {
            if (\is_a($middleware, $middlewareTypeClass)) {
                $closure($middleware);
            }
        }
    }
    /**
     * Setter.
     *
     * @param AbstractStorage $storage
     * @codeCoverageIgnore
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }
    /**
     * Setter.
     *
     * @param VariableResolver $resolver
     * @codeCoverageIgnore
     */
    public function setVariableResolver($resolver)
    {
        $this->variableResolver = $resolver;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTypeClass()
    {
        return $this->typeClass;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getStorage()
    {
        return $this->storage;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPools()
    {
        return $this->pools;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDataSources()
    {
        return $this->dataSources;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMiddlewares()
    {
        return $this->middlewares;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getVariableResolver()
    {
        return $this->variableResolver;
    }
}
