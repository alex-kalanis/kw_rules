<?php

namespace kalanis\kw_rules;


use kalanis\kw_rules\Interfaces;
use kalanis\kw_rules\Exceptions\RuleException;
use kalanis\kw_rules\Rules;


/**
 * Trait TValidate
 * @package kalanis\kw_rules
 * Main class for validation - use it as include for your case
 */
trait TValidate
{
    /** @var Interfaces\IRuleFactory */
    protected $rulesFactory = null;
    /** @var Rules\ARule[] */
    protected $rules = [];
    /** @var RuleException[] */
    protected $errors = [];

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
        $this->errors = [];
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
        $this->errors = [];
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

    public function validate(Interfaces\IValidate $entry): bool
    {
        $this->errors = [];
        foreach ($this->rules as $rule) {
            try {
                $rule->validate($entry);
            } catch (RuleException $ex) {
                $this->errors[] = $ex;
                while ($ex = $ex->getPrev()) {
                    $this->errors[] = $ex;
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * @return RuleException[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
