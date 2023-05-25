<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils;
/**
 * Find HTML tags by tag and attribute name.
 */
class TagAttributeFinder extends AbstractRegexFinder
{
    private $regexp;
    /**
     * C'tor.
     *
     * @param string[] $tags
     * @param string[] $attributes If you rely on the regular expression match of the link attribute it is highly recommend
     *                             to pass only one attribute and create multiple instances of `TagAttributeFinder`.
     * @codeCoverageIgnore
     */
    public function __construct($tags, $attributes)
    {
        $this->regexp = self::createRegexp($tags, $attributes);
    }
    // See `AbstractRegexFinder`.
    public function getRegularExpression()
    {
        return $this->regexp;
    }
    /**
     * See `AbstractRegexFinder`.
     *
     * @param array $m
     */
    public function createMatch($m)
    {
        list($beforeLinkAttribute, $tag, $linkAttribute, $link, $afterLink, $attributes) = self::prepareMatch($m);
        if ($this->isLinkEscaped($link) && Utils::isJson($link) === \false) {
            return \false;
        }
        return new TagAttributeMatch($this, $m[0], $tag, $attributes, $beforeLinkAttribute, $afterLink, $linkAttribute);
    }
    /**
     * Do not modify escaped data as they appear mostly in JSON CDATA - we
     * do not modify behavior of other plugins and themes ;-)
     *
     * @param string $link
     */
    protected function isLinkEscaped($link)
    {
        return \strpos($link, '\\') !== \false || empty(\trim($link));
    }
    /**
     * Prepare the result match of a `createRegexp` regexp.
     *
     * @param array $m
     */
    public static function prepareMatch($m)
    {
        // Prepare data
        $beforeLinkAttribute = $m[1];
        $tag = $m[2];
        $linkAttribute = $m[3];
        $link = \html_entity_decode($m[5]);
        $afterLink = $m[6];
        // Prepare all attributes as array (unfortunately not available from regexp due to back-reference usage...)
        $beforeLinkAttribute = \preg_split('/\\s/', $m[1] . ' ', 2);
        $withoutClosingTagChars = \rtrim($afterLink, '/>');
        $afterLink = \substr($afterLink, (\strlen($afterLink) - \strlen($withoutClosingTagChars)) * -1);
        $attributes = Utils::parseHtmlAttributes($beforeLinkAttribute[1] . ' ' . \ltrim($withoutClosingTagChars, '"\''));
        $beforeLinkAttribute = $beforeLinkAttribute[0];
        // Append our original link attribute to the attributes
        $attributes[$linkAttribute] = $link;
        return [$beforeLinkAttribute, $tag, $linkAttribute, $link, $afterLink, $attributes];
    }
    /**
     * Create regular expression to catch multiple tags and attributes.
     *
     * Available matches:
     *      $match[0] => Full string
     *      $match[1] => All content before the link attribute
     *      $match[2] => Used tag
     *      $match[3] => Used link attribute
     *      $match[4] => Used quote for link attribute
     *      $match[5] => Link
     *      $match[6] => All content after the link
     *
     * @param string[] $searchTags
     * @param string[] $searchAttributes
     * @see https://regex101.com/r/SPbQzu/6
     */
    public static function createRegexp($searchTags, $searchAttributes)
    {
        return \sprintf('/(<(%s)(?:\\s[^>]*[\\s"\']|\\s))(?:(%s)(?!\\s*=\\s*[\\\\]?(?:&quot|&#039)\\b))=([\\"\']??)([^\\4]*)(\\4[^>]*>)/siU', \count($searchTags) > 0 ? \join('|', $searchTags) : '[A-Za-z_-]+', \join('|', $searchAttributes));
    }
}
