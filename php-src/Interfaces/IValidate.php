<?php

namespace kalanis\kw_rules\Interfaces;


/**
 * Interface IValidate
 * @package kalanis\kw_rules\Interfaces
 * Interface for validating values
 */
interface IValidate
{
    /**
     * Key which will be validated
     * @return string
     */
    public function getKey(): string;

    /**
     * Value to validate
     * @return string|string[]
     */
    public function getValue();
}
