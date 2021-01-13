<?php

namespace kalanis\kw_rules\Rules;


use kalanis\kw_rules\Interfaces\IValidate;
use kalanis\kw_rules\Exceptions\RuleException;


/**
 * Class UrlExists
 * @package kalanis\kw_rules\Rules
 * Check if input is url and exists
 * @codeCoverageIgnore Call external server!!
 */
class UrlExists extends ARule
{
    public function validate(IValidate $entry): void
    {
        $headers = @get_headers($entry->getValue());
        if (!empty($headers) && (false !== strpos($headers[0], '200') )) {
            return;
        }
        throw new RuleException($this->errorText, $entry->getKey());
    }
}
