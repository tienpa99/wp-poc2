<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `visualParent(value=string|number)`. When called for an
 * attribute it will mark the individual node with the given visual parent.
 *
 * This can be useful for elements blocked inside forms and use the form as visual parent.
 *
 * Example: Rules of content blocker:
 *
 * ```
 * input[name="_wpcf7_recaptcha_response":visualParent(value=form)]
 * ```
 */
class VisualParent extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('visualParent', [$this, 'fn']);
    }
    /**
     * Function implementation.
     *
     * @param SelectorSyntaxAttributeFunction $fn
     * @param SelectorSyntaxMatch $match
     * @param mixed $value
     */
    public function fn($fn, $match, $value)
    {
        $visualParent = $fn->getArgument('value');
        if (!empty($visualParent)) {
            MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match, $visualParent) {
                if ($result->isBlocked() && !$match->hasAttribute(Constants::HTML_ATTRIBUTE_VISUAL_PARENT)) {
                    $match->setAttribute(Constants::HTML_ATTRIBUTE_VISUAL_PARENT, $visualParent);
                    $match->setAttribute(Constants::HTML_ATTRIBUTE_VISUAL_PARENT_HIDE, \true);
                    // Avoid flickering by automatically hiding the visual parents' next item
                    // We can safely use `<style` as this tag is allowed everywhere, head and body
                    if (!Utils::startsWith($visualParent, 'children:') && !\is_numeric($visualParent)) {
                        $match->setBeforeTag($match->getBeforeTag() . \sprintf('<style>%s:has([%s="%s"][%s]){visibility:hidden;}</style>', $visualParent, Constants::HTML_ATTRIBUTE_VISUAL_PARENT, $visualParent, Constants::HTML_ATTRIBUTE_COOKIE_IDS));
                    }
                }
            });
        }
        return \true;
    }
}
