<?php

namespace DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\RuleSet;

use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\OutputFormat;
use DevOwl\RealCookieBanner\Vendor\Sabberworm\CSS\Property\AtRule;
/**
 * A RuleSet constructed by an unknown at-rule. `@font-face` rules are rendered into AtRuleSet objects.
 */
class AtRuleSet extends RuleSet implements AtRule
{
    /**
     * @var string
     */
    private $sType;
    /**
     * @var string
     */
    private $sArgs;
    /**
     * @param string $sType
     * @param string $sArgs
     * @param int $iLineNo
     */
    public function __construct($sType, $sArgs = '', $iLineNo = 0)
    {
        parent::__construct($iLineNo);
        $this->sType = $sType;
        $this->sArgs = $sArgs;
    }
    /**
     * @return string
     */
    public function atRuleName()
    {
        return $this->sType;
    }
    /**
     * @return string
     */
    public function atRuleArgs()
    {
        return $this->sArgs;
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render(new OutputFormat());
    }
    /**
     * @return string
     */
    public function render(OutputFormat $oOutputFormat)
    {
        $sResult = $oOutputFormat->comments($this);
        $sArgs = $this->sArgs;
        if ($sArgs) {
            $sArgs = ' ' . $sArgs;
        }
        $sResult .= "@{$this->sType}{$sArgs}{$oOutputFormat->spaceBeforeOpeningBrace()}{";
        $sResult .= $this->renderRules($oOutputFormat);
        $sResult .= '}';
        return $sResult;
    }
}
