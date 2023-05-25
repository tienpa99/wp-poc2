<?php

namespace DevOwl\RealCookieBanner\presets;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CookieGroup;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Let the user know when a preset got updated through a plugin update.
 */
class UpdateNotice
{
    use UtilsProvider;
    const DISMISS_PARAM = 'rcb-dismiss-upgrade-notice';
    /**
     * Creates an admin notice when there is an update for preset'able entry.
     */
    public function admin_notices()
    {
        $needsUpdate = $this->needsUpdate();
        if (isset($_GET[self::DISMISS_PARAM])) {
            $this->dismiss($needsUpdate);
            return;
        }
        if (\current_user_can(Core::MANAGE_MIN_CAPABILITY) && \count($needsUpdate) > 0) {
            $this->outputNotice($needsUpdate);
        }
    }
    /**
     * Dismiss the notice by updating the preset version in database.
     *
     * @param array $needsUpdate
     */
    protected function dismiss($needsUpdate)
    {
        foreach ($needsUpdate as $update) {
            \update_post_meta($update->post_id, Blocker::META_NAME_PRESET_VERSION, $update->should);
        }
    }
    /**
     * Output the notice.
     *
     * @param array $needsUpdate
     */
    protected function outputNotice($needsUpdate)
    {
        $configPage = Core::getInstance()->getConfigPage();
        echo '<div class="notice notice-warning"><p>' . \__('Changes have been made to the templates you use in Real Cookie Banner. You should review the proposed changes and adjust your services if necessary to be able to remain legally compliant. The following services are affected:', RCB_TD) . '</p><ul>';
        foreach ($needsUpdate as $update) {
            $configPageUrl = $configPage->getUrl();
            switch ($update->post_type) {
                case Blocker::CPT_NAME:
                    $typeLabel = \__('Content Blocker', RCB_TD);
                    $editLink = $configPageUrl . '#/blocker/edit/' . $update->post_id;
                    break;
                case Cookie::CPT_NAME:
                    $groupIds = \wp_get_post_terms($update->post_id, CookieGroup::TAXONOMY_NAME, ['fields' => 'ids']);
                    $typeLabel = \__('Service (Cookie)', RCB_TD);
                    $editLink = $configPageUrl . '#/cookies/' . $groupIds[0] . '/edit/' . $update->post_id;
                    break;
                default:
                    break;
            }
            echo \sprintf('<li><strong>%s</strong> (%s) - <a target="_blank" href="%s">%s</a></li>', $update->post_title, $typeLabel, $editLink, \__('Review changes', RCB_TD));
        }
        $dismissLink = \add_query_arg(self::DISMISS_PARAM, '1');
        echo '</ul><p><a href="' . \esc_url($dismissLink) . '">' . \__('Dismiss this notice', RCB_TD) . '</a></p></div>';
    }
    /**
     * Read all updates from database.
     * It uses a very cheap SQL on each page request.
     */
    protected function needsUpdate()
    {
        global $wpdb;
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\presets\Presets::TABLE_NAME);
        // Probably refresh preset metadata cache
        $tempTd = Hooks::getInstance()->createTemporaryTextDomain();
        (new \DevOwl\RealCookieBanner\presets\BlockerPresets())->getClassList();
        (new \DevOwl\RealCookieBanner\presets\CookiePresets())->getClassList();
        $tempTd->teardown();
        $needsUpdate = $wpdb->get_results(
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->prepare("SELECT\n                    pm.meta_id AS post_version_meta_id,\n                    pm.post_id,\n                    pm.meta_value AS post_preset_version,\n                    prid.meta_value AS post_preset_identifier,\n                    p.post_title, p.post_type,\n                    presets.version as should\n                FROM {$wpdb->postmeta} pm\n                INNER JOIN {$wpdb->postmeta} prid\n                    ON prid.post_id = pm.post_id\n                INNER JOIN {$wpdb->posts} p\n                    ON p.ID = pm.post_id\n                INNER JOIN {$table_name} presets\n                    ON BINARY presets.identifier = BINARY prid.meta_value\n                    AND presets.context = %s\n                    AND presets.type = (\n                        CASE\n                            WHEN p.post_type = %s THEN %s\n                            ELSE %s\n                        END\n                    )\n                WHERE pm.meta_key = %s\n                    AND pm.meta_value > 0\n                    AND prid.meta_key = %s\n                    AND p.post_type IN (%s, %s)\n                    AND presets.version <> pm.meta_value", \DevOwl\RealCookieBanner\presets\Presets::getContextKey(), Cookie::CPT_NAME, \DevOwl\RealCookieBanner\presets\CookiePresets::PRESETS_TYPE, \DevOwl\RealCookieBanner\presets\BlockerPresets::PRESETS_TYPE, Blocker::META_NAME_PRESET_VERSION, Blocker::META_NAME_PRESET_ID, Blocker::CPT_NAME, Cookie::CPT_NAME)
        );
        // Remove rows of languages other than current and cast to correct types
        foreach ($needsUpdate as $key => &$row) {
            $row->post_version_meta_id = \intval($row->post_version_meta_id);
            $row->post_id = \intval($row->post_id);
            $row->post_preset_version = \intval($row->post_preset_version);
            $row->should = \intval($row->should);
            if (\intval(Core::getInstance()->getCompLanguage()->getCurrentPostId($row->post_id, $row->post_type)) !== \intval($row->post_id)) {
                unset($needsUpdate[$key]);
            }
        }
        return $needsUpdate;
    }
}
