<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\AbstractStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ExpireOption;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Helper functionality for common storage operations for a given consumer.
 */
class StorageHelper
{
    use UtilsProvider;
    const TABLE_NAME = 'templates';
    const TRANSIENT_CACHE_KEY = RCB_OPT_PREFIX . '-%s-templates-%s';
    const TRANSIENT_CACHE_EXPIRE = 24 * 60 * 60;
    const TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME = 'StorageHelper.persist.ExpireOption.set.expiration';
    /**
     * Same as `TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME` but it does not get removed on-persist. This allows
     * us to send the retry-signal to the browser.
     */
    const LAST_TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME = 'StorageHelper.persist.ExpireOption.set.expiration.last';
    const TYPE_SERVICE = 'service';
    const TYPE_BLOCKER = 'blocker';
    private $storage;
    /**
     * Cache of templates as they can be time-consuming to generate.
     * So, they are recalculated each x hours.
     *
     * @var ExpireOption
     */
    private $expireOption;
    /**
     * C'tor.
     *
     * @param AbstractStorage $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
    /**
     * Retrieve all templates (`field=null`) or by criteria.
     *
     * @param string $field
     * @param mixed $value
     * @param string $middlewareRoutine Can be `before` or `after`
     * @return AbstractTemplate[]|false
     */
    public function retrieveBy($field = null, $value = null, $middlewareRoutine = 'after')
    {
        global $wpdb;
        $table_name = $this->getTableName(self::TABLE_NAME);
        $type = $this->getType();
        $context = $this->getContext();
        $middlewareRoutine = \in_array($middlewareRoutine, ['before', 'after'], \true) ? $middlewareRoutine : 'after';
        $where = [];
        $where[] = $wpdb->prepare('type = %s', $type);
        $where[] = $wpdb->prepare('context = %s', $context);
        if ($field === 'identifier') {
            $where[] = $wpdb->prepare('identifier = %s', $value);
        }
        // phpcs:disable WordPress.DB
        $result = $wpdb->get_results(\sprintf("SELECT %s AS response FROM {$table_name} WHERE %s ORDER BY headline ASC", $middlewareRoutine . '_middleware', \join(' AND ', $where)), ARRAY_A);
        // phpcs:enable WordPress.DB
        if ($result === null) {
            // Error happened, do not try to refetch from data sources
            return [];
        }
        if ($type === self::TYPE_SERVICE && \count($result) === 0 && $middlewareRoutine === 'after') {
            // No results in database but there must be at least one, refetch from data source
            // Force also the fetch from service cloud via API (e.g. new language in WPML added)
            $this->getExpireOption()->delete();
            return \false;
        }
        foreach ($result as &$row) {
            if ($middlewareRoutine === 'after' && empty($row['response'])) {
                // The `after_middleware` never can be blank, it gets only blank through `wp_rcb_invalidate_presets_cache`.
                // So, in this state we know that we should recalculate middlewares.
                return \false;
            }
            $row = $this->fromArray(\json_decode($row['response'], ARRAY_A));
        }
        return $result;
    }
    /**
     * Convert JSON to `ServiceTemplate` or `BlockerTemplate` depending on current type.
     *
     * @param array $array
     */
    public function fromArray($array)
    {
        $consumer = $this->storage->getConsumer();
        $row = $this->getType() === self::TYPE_BLOCKER ? new BlockerTemplate($consumer) : new ServiceTemplate($consumer);
        $row->fromArray($array);
        return $row;
    }
    /**
     * Persist a set of templates in chunks into the database.
     *
     * @param AbstractTemplate[] $templates
     * @param closre $otherMetaKeysResolver
     */
    public function persist($templates, $otherMetaKeysResolver = null)
    {
        global $wpdb;
        /**
         * Chunk templates to boost performance.
         *
         * But keep a bit lower, as service templates could be huge and feed the memory when doing `join`
         * for preparing the SQL statement.
         *
         * - 50 is too much, first customers are running into issues with a Memory Limit of 128M
         *
         * @var AbstractTemplate[][]
         */
        $chunks = \array_chunk($templates, 30);
        $table_name = $this->getTableName(self::TABLE_NAME);
        $inserted = 0;
        $context = $this->getContext();
        $type = $this->getType();
        $variableResolver = $this->storage->getConsumer()->getVariableResolver();
        foreach ($chunks as $chunk) {
            $values = [];
            foreach ($chunk as $template) {
                $otherMeta = \is_callable($otherMetaKeysResolver) ? $otherMetaKeysResolver($template) : [];
                $values[] = \str_ireplace("'NULL'", 'NULL', $wpdb->prepare('(%s, %s, %s, %d, %s,
                            %s, %s, %s,
                            %d, %d, %d, %d, %d,
                            %s, %s, %s, %s, %s)', $template->identifier, $context, $type, $template->version, \mysql2date('c', \gmdate('Y-m-d H:i:s', $template->createdAt), \false), $template->headline ?? '', $template->subHeadline ?? '', $template->logoUrl ?? '', $template->consumerData['isDisabled'] ? 1 : 0, $template->consumerData['isCreated'] ? 1 : 0, $template->isHidden ? 1 : 0, $template->consumerData['isRecommended'] ? 1 : 0, isset($template->consumerData['isCloud']) && $template->consumerData['isCloud'] ? 1 : 0, $template->tier, \count($template->consumerData['tags']) > 0 ? \json_encode($template->consumerData['tags']) : \json_encode((object) []), \json_encode($template->getBeforeMiddleware()), \json_encode(AbstractTemplate::toArray($template)), \count($otherMeta) > 0 ? \json_encode($otherMeta) : \json_encode((object) [])));
            }
            // phpcs:disable WordPress.DB.PreparedSQL
            $result = $wpdb->query(\sprintf('INSERT INTO %s (
                        `identifier`, `context`, `type`, `version`, `created_at`,
                        `headline`, `sub_headline`, `logo_url`,
                        `is_disabled`, `is_created`, `is_hidden`, `is_recommended`, `is_cloud`,
                        `tier`, `tags`, `before_middleware`, `after_middleware`, `other_meta`
                    )
                    VALUES %s ON DUPLICATE KEY UPDATE
                        `version` = VALUES(`version`),
                        `created_at` = VALUES(`created_at`),
                        `headline` = VALUES(`headline`),
                        `sub_headline` = VALUES(`sub_headline`),
                        `logo_url` = VALUES(`logo_url`),
                        `is_disabled` = VALUES(`is_disabled`),
                        `is_created` = VALUES(`is_created`),
                        `is_hidden` = VALUES(`is_hidden`),
                        `is_recommended` = VALUES(`is_recommended`),
                        `is_cloud` = VALUES(`is_cloud`),
                        `tier` = VALUES(`tier`),
                        `tags` = VALUES(`tags`),
                        `before_middleware` = VALUES(`before_middleware`),
                        `after_middleware` = VALUES(`after_middleware`),
                        `other_meta` = VALUES(`other_meta`)', $table_name, \join(',', $values)));
            // phpcs:enable WordPress.DB.PreparedSQL
            // When $result is zero, the query did not fail but no new row where added, we need to respect `ON DUPLICATE KEY UPDATE`
            $inserted += $result === \false ? 0 : \count($values);
        }
        $this->getExpireOption()->set($this->getCacheInvalidateKey(), $variableResolver->resolveDefault(self::TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME));
        $variableResolver->add(self::TRANSIENT_CACHE_EXPIRE_OVERWRITE_VARIABLE_NAME, null);
        return $inserted;
    }
    // Documented in AbstractStorage
    public function shouldInvalidate()
    {
        $option = $this->getExpireOption();
        $cache = $option->get();
        return $cache !== $this->getCacheInvalidateKey();
    }
    /**
     * Get type as string from template type class.
     */
    public function getType()
    {
        $typeClass = $this->storage->getConsumer()->getTypeClass();
        return $typeClass === ServiceTemplate::class ? self::TYPE_SERVICE : self::TYPE_BLOCKER;
    }
    /**
     * Get the `ExpireOption` instance.
     */
    public function getExpireOption()
    {
        return $this->expireOption === null ? $this->expireOption = new ExpireOption(\sprintf(self::TRANSIENT_CACHE_KEY, $this->getType(), $this->getContext()), \false, self::TRANSIENT_CACHE_EXPIRE) : $this->expireOption;
    }
    /**
     * Automatically invalidate storage when Real Cookie Banner verion changes.
     */
    public function getCacheInvalidateKey()
    {
        return $this->storage->getConsumer()->getVariableResolver()->resolveRequired('cache.invalidate.key');
    }
    /**
     * Get context from variable resolver.
     *
     * @return string
     */
    public function getContext()
    {
        return $this->storage->getConsumer()->getVariableResolver()->resolveRequired('context');
    }
}
