<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Interfaces\IValidate;
use kalanis\kw_rules\Exceptions\RuleException;


/**
 * Class GreaterThan
 * @package kalanis\kw_rules\Rules
 * Check if input is greater than expected value
 */
class GreaterThan extends ARule
{
    use TCheckInt;

    public function validate(IValidate $entry): void
    {
        if (intval($entry->getValue()) <= $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}