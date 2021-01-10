<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Exceptions\RuleException;


/**
 * trait TCheckRange
 * @package kalanis\kw_rules\Rules
 * Check original values as range
 */
trait TCheckRange
{
    use TRule;

    protected function checkValue($againstValue)
    {
        if (!is_array($againstValue)) {
            throw new RuleException('No array found. Need set both values to compare!');
        }
        $values = array_map([$this, 'checkRule'], $againstValue);
        $lower = min($values);
        $higher = max($values);
        return [$lower, $higher];
    }

    /**
     * @param mixed $againstValue
     * @return int
     * @throws RuleException
     */
    protected function checkRule($againstValue): int
    {
        if (is_array($againstValue)) {
            throw new RuleException('Sub-array found. Need set only values to compare!');
        }
        if (is_object($againstValue)) {
            throw new RuleException('Object found. Need set only values to compare!');
        }
        return intval(strval($againstValue));
    }
}