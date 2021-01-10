<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Exceptions\RuleException;


/**
 * trait TCheckArrayString
 * @package kalanis\kw_rules\Rules
 * Check original values as set of strings
 */
trait TCheckArrayString
{
    use TRule;

    protected function checkValue($againstValue)
    {
        if (!is_array($againstValue)) {
            throw new RuleException('No array found. Need set input as array!');
        }
        return array_map([$this, 'checkRule'], $againstValue);
    }

    /**
     * @param mixed $singleRule
     * @return string
     * @throws RuleException
     */
    protected function checkRule($singleRule): string
    {
        if (!is_string($singleRule)) {
            throw new RuleException('Input for check is not a string.');
        }
        return $singleRule;
    }
}