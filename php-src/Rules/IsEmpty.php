<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Interfaces\IValidate;
use kalanis\kw_rules\Exceptions\RuleException;


/**
 * Class IsEmpty
 * @package kalanis\kw_rules\Rules
 * Check if input is empty
 */
class IsEmpty extends ARule
{
    public function validate(IValidate $entry): void
    {
        if (!empty($entry->getValue())) {
            throw new RuleException($this->errorText);
        }
    }
}
