<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Modal hints are only shown once so the user understands a specific tab, e. g. "What is a cookie?".
 */
class ModalHints
{
    use UtilsProvider;
    const SETTING_MODAL_HINTS = RCB_OPT_PREFIX . '-modal-hints';
    const DEFAULT_MODAL_HINTS = '[]';
    /**
     * Singleton instance.
     *
     * @var ModalHints
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_MODAL_HINTS, self::DEFAULT_MODAL_HINTS);
    }
    /**
     * Get a list of already seen identifiers.
     *
     * @return string[]
     */
    public function getAlreadySeen()
    {
        return \json_decode(\get_option(self::SETTING_MODAL_HINTS, self::DEFAULT_MODAL_HINTS), ARRAY_A);
    }
    /**
     * Set an identifier as seen.
     *
     * @param string $identifier
     */
    public function setSeen($identifier)
    {
        $list = $this->getAlreadySeen();
        $list[] = $identifier;
        $list = \array_unique($list);
        return \update_option(self::SETTING_MODAL_HINTS, \json_encode($list));
    }
    /**
     * Get singleton instance.
     *
     * @return ModalHints
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\ModalHints() : self::$me;
    }
}
