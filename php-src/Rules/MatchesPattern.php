<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Interfaces\IValidate;
use kalanis\kw_rules\Exceptions\RuleException;


/**
 * Class MatchesPattern
 * @package kalanis\kw_rules\Rules
 * Check if input matches pattern
 */
class MatchesPattern extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!boolval(preg_match($this->againstValue, $entry->getValue()))) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}