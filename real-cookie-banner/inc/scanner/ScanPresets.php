<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\BlockerPresets;
use DevOwl\RealCookieBanner\presets\CookiePresets;
use DevOwl\RealCookieBanner\presets\Presets;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Predefined presets for blocker which got scanned through our scanner.
 */
class ScanPresets
{
    use UtilsProvider;
    private $blockerPresets;
    private $cookiePresets;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->blockerPresets = new BlockerPresets();
        $this->cookiePresets = new CookiePresets();
    }
    // Documented in Presets
    public function getAllFromCache()
    {
        global $wpdb;
        $table_name = $this->getTableName(Presets::TABLE_NAME);
        $table_name_scan = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table_name} p \n                    WHERE context = %s\n                    AND disabled = 0\n                    AND\n                        ((`type` = %s AND EXISTS(SELECT 1 FROM {$table_name_scan} WHERE preset = p.identifier))\n                        OR recommended = 1)\n                    ORDER BY `name`", $this->blockerPresets->getContextKey(), BlockerPresets::PRESETS_TYPE), ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        $result = [];
        foreach ($rows as &$row) {
            $this->castReadRow($row);
            $result[$row['identifier']] = $row;
        }
        $this->expandResult($result);
        return $result;
    }
    // Documented in Presets
    public function getFromCache($identifier)
    {
        global $wpdb;
        $table_name = $this->getTableName(Presets::TABLE_NAME);
        $table_name_scan = $this->getTableName(\DevOwl\RealCookieBanner\scanner\Persist::TABLE_NAME);
        // phpcs:disable WordPress.DB.PreparedSQL
        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$table_name}\n                    WHERE identifier = %s\n                    AND context = %s\n                    AND disabled = 0\n                    AND ((`type` = %s AND EXISTS(SELECT 1 FROM {$table_name_scan} WHERE preset = p.identifier))\n                        OR recommended = 1)", $identifier, $this->blockerPresets->getContextKey(), BlockerPresets::PRESETS_TYPE), ARRAY_A);
        // phpcs:enable WordPress.DB.PreparedSQL
        if (\is_array($row)) {
            $this->castReadRow($row);
            $result = [$row];
            $this->expandResult($result);
            return $result[0];
        }
        return \false;
    }
    // Documented in Presets
    public function castReadRow(&$row)
    {
        if ($row['type'] === BlockerPresets::PRESETS_TYPE) {
            $this->blockerPresets->castReadRow($row);
        } else {
            $this->cookiePresets->castReadRow($row);
        }
    }
    // Documented in Presets
    public function expandResult(&$rows)
    {
        $scannedResults = Core::getInstance()->getScanner()->getQuery()->getScannedPresets();
        foreach ($rows as $key => &$row) {
            $row['scanned'] = $scannedResults[$key] ?? \false;
        }
    }
}
