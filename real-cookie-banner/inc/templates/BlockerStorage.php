<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages\AbstractStorage;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * This storage persists and reads content blocker templates from `wp_rcb_templates` database table.
 *
 * The database table itself does not reflect all available properties in own columsn, instead only the
 * important one. The complete template JSON will be saved in a serialized column so it can be restored.
 */
class BlockerStorage extends AbstractStorage
{
    private $helper;
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        parent::__construct($consumer);
        $this->helper = new \DevOwl\RealCookieBanner\templates\StorageHelper($this);
    }
    // Documented in AbstractStorage
    public function retrieve()
    {
        return $this->helper->retrieveBy();
    }
    // Documented in AbstractStorage
    public function retrieveBy($field, $value)
    {
        return $this->helper->retrieveBy($field, $value);
    }
    // Documented in AbstractStorage
    public function persist($templates)
    {
        $this->helper->persist($templates, function ($template) {
            /**
             * Service template.
             *
             * @var BlockerTemplate
             */
            $template = $template;
            return ['extendsIdentifier' => $template->extendsIdentifier, 'ruleGroups' => $template->ruleGroups, 'rules' => $template->rules];
        });
    }
    // Documented in AbstractStorage
    public function shouldInvalidate()
    {
        return $this->helper->shouldInvalidate();
    }
    /**
     * Getter.
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
