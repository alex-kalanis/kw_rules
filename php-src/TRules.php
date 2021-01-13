<?php

namespace kalanis\kw_rules;


use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Rules;


/**
 * Trait TRules
 * @package kalanis\kw_rules
 * Main class for processing rules - use it as include for your case
 */
trait TRules
{
    /** @var Interfaces\IRuleFactory */
    protected $rulesFactory = null;
    /** @var Rules\ARule[]|Rules\File\AFileRule[] */
    protected $rules = [];

    /**
     * @param string $ruleName
     * @param string $errorText
     * @param mixed ...$args
     * @return $this
     * @throws RuleException
     */
    public function addRule(string $ruleName, string $errorText, ...$args): self
    {
        $this->setFactory();
        $rule = $this->rulesFactory->getRule($ruleName);
        $rule->setErrorText($errorText);
        $rule->setAgainstValue(empty($args) ? null : reset($args));
        $this->rules[] = $rule;
        return $this;
    }

    public function addRules(iterable $rules = []): self
    {
        foreach ($rules as $rule) {
            if ($rule instanceof Rules\ARule) {
                $this->rules[] = $rule;
            }
            if ($rule instanceof Rules\File\AFileRule) {
                $this->rules[] = $rule;
            }
        }
        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function removeRules(): self
    {
        $this->rules = [];
        return $this;
    }

    protected function setFactory(): void
    {
        if (empty($this->rulesFactory)) {
            $this->rulesFactory = $this->whichFactory();
        }
    }

    /**
     * Set which factory will be used
     * @return Interfaces\IRuleFactory
     */
    abstract protected function whichFactory(): Interfaces\IRuleFactory;
}
