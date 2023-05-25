<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Abstract checklist item implementation.
 */
abstract class AbstractChecklistItem
{
    use UtilsProvider;
    const OPT_PREFIX = RCB_OPT_PREFIX . '-checklist-';
    /**
     * Set state of checklist item.
     *
     * @param boolean $state
     * @return boolean
     */
    public function toggle($state)
    {
        return \false;
    }
    /**
     * Is this checklist item checked?
     *
     * @return boolean
     */
    public abstract function isChecked();
    /**
     * Get checklist title.
     *
     * @return string
     */
    public abstract function getTitle();
    /**
     * Get checklist description. Can be empty.
     *
     * @return string
     */
    public function getDescription()
    {
        return '';
    }
    /**
     * Get link so the checklist item can be resolved there. Can be empty.
     *
     * @return string
     */
    public function getLink()
    {
        return '';
    }
    /**
     * Get link text. Can be empty.
     *
     * @return string
     */
    public function getLinkText()
    {
        return '';
    }
    /**
     * Get link target. Can be empty or "_blank", ...
     *
     * @return string
     */
    public function getLinkTarget()
    {
        return '';
    }
    /**
     * Does this checklist item need PRO version of RCB?
     *
     * @return boolean
     */
    public function needsPro()
    {
        return \false;
    }
    /**
     * Should this be visible as checklist item?
     *
     * @return boolean
     */
    public function isVisible()
    {
        return \true;
    }
    /**
     * Persist the state from `wp_options`.
     *
     * @param string $id
     * @param boolean $state
     */
    protected function persistStateToOption($id, $state)
    {
        if ($state === $this->getFromOption($id, \true)) {
            return \true;
        }
        return \update_option(self::OPT_PREFIX . $id, $state, \true);
    }
    /**
     * Get the state from `wp_options`.
     *
     * @param string $id
     * @param boolean $allowNotAutoloaded
     * @return boolean
     */
    protected function getFromOption($id, $allowNotAutoloaded = \false)
    {
        $optionName = self::OPT_PREFIX . $id;
        if ($allowNotAutoloaded) {
            $value = \get_option($optionName, \false);
        } else {
            // Usually all checked items are available in autoloaded options
            $options = \wp_load_alloptions();
            $value = isset($options[$optionName]) ? \intval($options[$optionName]) : \false;
        }
        return \boolval($value);
    }
}
