<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\TagAttributeFinder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
/**
 * Match defining a `TagAttributeFinder` match.
 */
class TagAttributeMatch extends AbstractMatch
{
    private $beforeLinkAttribute;
    private $afterLink;
    private $linkAttribute;
    /**
     * C'tor.
     *
     * @param TagAttributeFinder $finder
     * @param string $originalMatch
     * @param string $tag
     * @param array $attributes
     * @param string $beforeLinkAttribute
     * @param string $afterLink
     * @param string $linkAttribute
     */
    public function __construct($finder, $originalMatch, $tag, $attributes, $beforeLinkAttribute, $afterLink, $linkAttribute)
    {
        parent::__construct($finder, $originalMatch, $tag, $attributes);
        $this->beforeLinkAttribute = $beforeLinkAttribute;
        $this->afterLink = $afterLink;
        $this->linkAttribute = $linkAttribute;
    }
    // See `AbstractRegexFinder`.
    public function render()
    {
        return $this->encloseRendered($this->hasChanged() ? \sprintf('<%1$s%2$s%3$s', $this->getTag(), $this->renderAttributes(), $this->getAfterLink()) : $this->getOriginalMatch());
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBeforeLinkAttribute()
    {
        return $this->beforeLinkAttribute;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAfterLink()
    {
        return $this->afterLink;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLinkAttribute()
    {
        return $this->linkAttribute;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLink()
    {
        return $this->getAttribute($this->getLinkAttribute(), null, \true);
    }
    /**
     * Getter.
     *
     * @return TagAttributeFinder
     * @codeCoverageIgnore
     */
    public function getFinder()
    {
        return parent::getFinder();
    }
}
