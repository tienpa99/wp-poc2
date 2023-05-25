<?php

namespace DevOwl\RealCookieBanner\presets;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\ExpireOption;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Persistence for cookie and blocker presets metadata by using a class list.
 */
abstract class Presets
{
    use UtilsProvider;
    const TABLE_NAME = 'presets';
    const TRANSIENT_CACHE_KEY = RCB_OPT_PREFIX . '-%s-presets-%s';
    const TRANSIENT_CACHE_EXPIRE = 12 * 60 * 60;
    /**
     * Cache of presets as they can be time-consuming to generate.
     * So, they are recalculated each x hours.
     *
     * @var ExpireOption
     */
    private $expireOption;
    private $type;
    /**
     * C'tor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }
    /**
     * Get all available presets and persist the metadata to cache.
     *
     * @param boolean $force If `true`, the cache gets invalidated
     */
    public abstract function getClassList($force = \false);
    /**
     * Create presets from `AbstractBlockerPreset` classes.
     *
     * @param string[] $clazzes
     */
    public abstract function fromClassList($clazzes);
    /**
     * Get a list of other meta keys which should be persist to metadata cache.
     */
    public function getOtherMetaKeys()
    {
        return [];
    }
    /**
     * Expand the read rows with additional, recalculated tags (not cached!).
     *
     * @param array $rows
     */
    public function expandResult(&$rows)
    {
        // Silence is golden.
    }
    /**
     * Get presets from the cache.
     *
     * @param boolean $force If `true`, the cache gets invalidated
     */
    public function getAllFromCache($force = \false)
    {
        global $wpdb;
        // Probably create new metadata cache
        $this->getClassList($force);
        $table_name = $this->getTableName(self::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} WHERE context = %s AND `type` = %s ORDER BY `name`", $this->getContextKey(), $this->getType()), ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        $result = [];
        foreach ($rows as &$row) {
            $this->castReadRow($row);
            $result[$row['identifier']] = $row;
        }
        $this->expandResult($result);
        return $result;
    }
    /**
     * Get a preset from the cache. Returns `false` if preset not in cache.
     *
     * @param string $identifier
     */
    public function getFromCache($identifier)
    {
        global $wpdb;
        $list = $this->getClassList();
        if (isset($list[$identifier])) {
            $table_name = $this->getTableName(self::TABLE_NAME);
            // phpcs:disable WordPress.DB.PreparedSQL
            $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name} WHERE identifier = %s AND context = %s AND `type` = %s", $identifier, $this->getContextKey(), $this->getType()), ARRAY_A);
            // phpcs:enable WordPress.DB.PreparedSQL
            if (\is_array($row)) {
                $this->castReadRow($row);
                $result = [$row];
                $this->expandResult($result);
                return $result[0];
            }
        }
        return \false;
    }
    /**
     * Cast SQL types.
     *
     * @param array $row
     */
    public function castReadRow(&$row)
    {
        $row['id'] = \intval($row['id']);
        $row['version'] = \intval($row['version']);
        if (empty($row['description'])) {
            unset($row['description']);
        }
        if (empty($row['attributes_name'])) {
            unset($row['attributes_name']);
        }
        if (empty($row['tags'])) {
            unset($row['tags']);
        } else {
            $row['tags'] = \json_decode($row['tags'], ARRAY_A);
        }
        $row['disabled'] = \boolval($row['disabled']);
        $row['recommended'] = \boolval($row['recommended']);
        $row['created'] = \boolval($row['created']);
        if ($row['disabled'] && empty($row['tags'][\__('Disabled', RCB_TD)])) {
            $row['tags'][\__('Disabled', RCB_TD)] = \sprintf(
                // translators:
                \__('This template is currently disabled because the respective WordPress plugin is not installed or the desired function is not active. <a href="%s" target="_blank">Learn more</a>', RCB_TD),
                \__('https://devowl.io/knowledge-base/real-cookie-banner-disabled-cookie-templates/', RCB_TD)
            );
        }
        $row['hidden'] = \boolval($row['hidden']);
        if (!empty($row['other_meta'])) {
            // Merge by reference
            $other_meta = \json_decode($row['other_meta'], ARRAY_A);
            if (\is_array($other_meta)) {
                foreach ($other_meta as $key => $value) {
                    $row[$key] = $value;
                }
            }
        }
        unset($row['other_meta']);
    }
    /**
     * Get a preset with attributes resolved. Returns `false` if
     * preset not found or no attributes are available. This function does currently
     * not use the metadata cache!
     *
     * @param string $identifier
     */
    public function getWithAttributes($identifier)
    {
        $list = $this->getClassList();
        if (isset($list[$identifier])) {
            $resolved = $this->fromClassList([$list[$identifier]]);
            if (\count($resolved) > 0 && !empty($resolved[0]['attributes'])) {
                return $resolved[0];
            }
        }
        return \false;
    }
    /**
     * Check if the current preset type needs to be recalculated.
     */
    public function needsRecalculation()
    {
        $option = $this->getExpireOption();
        $cache = $option->get();
        // Migration <= v1.3 currently does save all presets in one autoloaded option
        // instead of the presets table. Let's delete the old behavior as the option
        // should only hold the amount of available presets.
        if (\is_array($cache)) {
            $option->delete();
            $cache = \false;
        }
        return $cache === \false;
    }
    /**
     * Force regeneration of presets.
     */
    public function forceRegeneration()
    {
        $this->getExpireOption()->delete();
    }
    /**
     * Persist a set of presets.
     *
     * @param array $items
     */
    public function persist($items)
    {
        if (\count($items) === 0) {
            return \false;
        }
        global $wpdb;
        $values = [];
        $context = self::getContextKey();
        $otherMetaKeys = $this->getOtherMetaKeys();
        foreach ($items as $identifier => $value) {
            $other_meta = [];
            foreach ($otherMetaKeys as $key) {
                if (isset($value[$key])) {
                    $other_meta[$key] = $value[$key];
                }
            }
            $values[] = \str_ireplace("'NULL'", 'NULL', $wpdb->prepare('(%s, %s, %s, %d, %s, %s, %s, %s, %s, %d, %d, %d, %d, %s, %s)', $this->getType(), $identifier, $context, $value['version'], isset($value['description']) ? $value['description'] : '', $value['logoFile'], $value['name'], isset($value['tags']) && \count($value['tags']) > 0 ? \json_encode($value['tags']) : 'NULL', isset($value['attributes'], $value['attributes']['name']) ? $value['attributes']['name'] : 'NULL', isset($value['disabled']) ? $value['disabled'] ? 1 : 0 : 0, isset($value['hidden']) ? $value['hidden'] ? 1 : 0 : 0, isset($value['recommended']) ? $value['recommended'] ? 1 : 0 : 0, isset($value['created']) ? $value['created'] ? 1 : 0 : 0, empty($other_meta) ? 'NULL' : \json_encode($other_meta), isset($value['tier']) ? $value['tier'] : 'NULL'));
        }
        // Persist it
        $table_name = $this->getTableName(self::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $result = $wpdb->query(\sprintf('INSERT INTO %s (`type`, `identifier`, `context`, `version`, `description`, `logoFile`, `name`, `tags`, `attributes_name`, `disabled`, `hidden`, `recommended`, `created`, `other_meta`, `tier`)
                VALUES %s ON DUPLICATE KEY UPDATE
                `version` = VALUES(`version`),
                `description` = VALUES(`description`),
                logoFile = VALUES(`logoFile`),
                `name` = VALUES(`name`),
                tags = VALUES(`tags`),
                attributes_name = VALUES(`attributes_name`),
                `disabled` = VALUES(`disabled`),
                `hidden` = VALUES(`hidden`),
                `recommended` = VALUES(`recommended`),
                `created` = VALUES(`created`),
                `other_meta` = VALUES(`other_meta`),
                `tier` = VALUES(`tier`)', $table_name, \join(',', $values)));
        // phpcs:enable WordPress.DB.PreparedSQL
        // When $result is zero, the query did not fail but no new row where added, we need to respect `ON DUPLICATE KEY UPDATE`
        $result = $result === 0 ? 1 : $result;
        $this->getExpireOption()->set($result);
        return $result > 0;
    }
    /**
     * Get the `ExpireOption` instance.
     */
    public function getExpireOption()
    {
        return $this->expireOption === null ? $this->expireOption = new ExpireOption(\sprintf(self::TRANSIENT_CACHE_KEY, $this->getType(), self::getContextKey()), \false, self::TRANSIENT_CACHE_EXPIRE) : $this->expireOption;
    }
    // Self-explaining
    public function getType()
    {
        return $this->type;
    }
    /**
     * Get the context key for cache. Presets should be saved per-language.
     */
    public static function getContextKey()
    {
        $language = isset($_GET['_dataLocale']) ? \sanitize_text_field($_GET['_dataLocale']) : Core::getInstance()->getCompLanguage()->getCurrentLanguage();
        // Fallback to blog language
        if (empty($language)) {
            $language = \str_replace('-', '_', \get_locale());
        }
        return $language;
    }
}
