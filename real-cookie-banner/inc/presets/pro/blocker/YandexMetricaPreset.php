<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\YandexMetricaPreset as PresetsYandexMetricaPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Yandex Metrica blocker preset.
 */
class YandexMetricaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsYandexMetricaPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Yandex Metrica';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*mc.yandex.ru*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/yandex.png')];
    }
}
