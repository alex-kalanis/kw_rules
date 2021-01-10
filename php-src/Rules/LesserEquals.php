<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Interfaces\IValidate;
use kalanis\kw_rules\Exceptions\RuleException;


/**
 * Class LesserEquals
 * @package kalanis\kw_rules\Rules
 * Check if input is lower or equals to expected value
 */
class LesserEquals extends ARule
{
    use TCheckInt;

    public function validate(IValidate $entry): void
    {
        if (intval($entry->getValue()) > $this->againstValue) {
            throw new RuleException($this->errorText, $entry->getKey());
        }
    }
}