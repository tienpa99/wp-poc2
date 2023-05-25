<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\selectorSyntaxFunction;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\SelectorSyntaxMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\SelectorSyntaxAttributeFunction;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\SelectorSyntaxMatcher;
/**
 * This plugin registers the selector syntax `matchesUrl()`. When called for an
 * attribute it will take its value and iterate it through all non-selector-syntax
 * content blocker rules.
 *
 * Example: Rules of content blocker:
 *
 * ```
 * *youtube.com*
 * *youtu.be*
 * div[data-url:matchesUrl()]
 * ```
 *
 * When a `div` with `data-url` got found, it takes its value of `data-url` and tries
 * to block by `*youtube.com*` or `*youtu.be*`.
 */
class MatchesUrl extends AbstractPlugin
{
    // Documented in AbstractPlugin
    public function init()
    {
        $this->getHeadlessContentBlocker()->addSelectorSyntaxFunction('matchesUrl', [$this, 'fn']);
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
        $matcher = $this->getHeadlessContentBlocker()->getFinderToMatcher()[$fn->getAttribute()->getFinder()] ?? null;
        if ($matcher !== null && $matcher instanceof SelectorSyntaxMatcher) {
            $blockedResult = new BlockedResult('', [], '');
            $matcher->iterateBlockablesInString($blockedResult, $value, \false, \false, null, [$matcher->getBlockable()]);
            return $blockedResult->isBlocked();
        }
        return \true;
    }
}
