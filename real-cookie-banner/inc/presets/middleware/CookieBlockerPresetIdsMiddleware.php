<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\BlockerPresets;
use DevOwl\RealCookieBanner\settings\Blocker;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to add `blockerPresetIds` to the cookies so they are associated together.
 * This is useful to show a dropdown of available content blockers for a cookie preset.
 */
class CookieBlockerPresetIdsMiddleware
{
    use UtilsProvider;
    /**
     * See class description.
     *
     * @param array $preset
     * @param AbstractCookiePreset $instance Preset instance
     * @param WP_Post[] $existingCookies
     * @param WP_Post[] $existingBlockers
     */
    public function middleware(&$preset, $instance, $existingCookies, $existingBlockers)
    {
        // Check if preset is already created
        $foundExisting = $preset['created'] ?? \false;
        $usedPresetIds = [];
        foreach ($existingBlockers as $existingBlocker) {
            $usedPresetIds[] = $existingBlocker->metas[Blocker::META_NAME_PRESET_ID];
        }
        // If not existing, check if a content blocker with same ID exists
        $preset['contentBlockerTemplates'] = isset($preset['contentBlockerTemplates']) ? $preset['contentBlockerTemplates'] : [];
        if (!$foundExisting) {
            $checkBlockerPresets = \array_unique(\array_merge(isset($preset['blockerPresetIds']) ? $preset['blockerPresetIds'] : [], [$preset['id']]));
            $blockerPresets = new BlockerPresets();
            foreach ($checkBlockerPresets as $presetId) {
                // Check if blocker already created
                if (\in_array($presetId, $usedPresetIds, \true)) {
                    continue;
                }
                // Check if blocker preset exists with attributes
                $blockerPreset = $blockerPresets->getWithAttributes($presetId);
                if ($blockerPreset === \false || isset($blockerPreset['disabled']) && $blockerPreset['disabled'] || isset($blockerPreset['hidden']) && $blockerPreset['hidden'] || isset($blockerPreset['tier']) && $blockerPreset['tier'] === \DevOwl\RealCookieBanner\presets\middleware\AdoptTierFromClassNamespaceMiddleware::TIER_PRO && !$this->isPro()) {
                    continue;
                }
                $preset['tags']['Content Blocker'] = \__('A suitable content blocker for this service can be created automatically.', RCB_TD);
                $preset['contentBlockerTemplates'][] = ['identifier' => $blockerPreset['id'], 'name' => $blockerPreset['name'], 'subHeadline' => $blockerPreset['description'] ?? null];
            }
        }
        unset($preset['blockerPresetIds']);
        return $preset;
    }
}
