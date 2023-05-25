<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\finder\match\MatchPluginCallbacks;
/**
 * This plugin registers the selector syntax `delegateClick([selector=self|string][&hide=boolean])`.
 * When called for an attribute it will mark the individual node to be clicked after unblocked via content
 * blocker.
 *
 * Example: Rules of content blocker:
 *
 * ```
 * a[data-tcb-events*="thrive_leads":delegateClick(selector=self&hide=false)]
 * ```
 */
class DelegateClick extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('delegateClick', [$this, 'fn']);
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
        $selector = $fn->getArgument('selector', 'self');
        $hide = $fn->getArgument('hide', \false);
        MatchPluginCallbacks::getFromMatch($match)->addBlockedMatchCallback(function ($result) use($match, $selector, $hide) {
            if ($result->isBlocked()) {
                $match->setAttribute(Constants::HTML_ATTRIBUTE_DELEGATE_CLICK, \json_encode(['selector' => $selector, 'hide' => $hide]));
            }
        });
        return \true;
    }
}
