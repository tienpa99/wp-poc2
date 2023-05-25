<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `jQueryHijackEach()`. When called for an
 * attribute it will mark the individual node to defer execution of `jQuery().each` on the
 * given node.
 *
 * Example: Rules of content blocker:
 *
 * ```
 * div[class="my-class":keepAttributes(value=class),jQueryHijackEach()]
 * ```
 */
class JQueryHijackEach extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('jQueryHijackEach', [$this, 'fn']);
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
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match) {
            if ($result->isBlocked()) {
                $match->setAttribute(Constants::HTML_ATTRIBUTE_JQUERY_HIJACK_EACH, \true);
            }
        });
        return \true;
    }
}
