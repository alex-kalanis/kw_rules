<?php

namespace kalanis\kw_rules\Exceptions;


use Exception;
use Throwable;


class RuleException extends Exception
{
    protected $key = '';
    protected $prev = null;

    public function __construct($message = '', $key = '', Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->prev = $previous;
        $this->key = $key;
    }

    public function setPrev(?Throwable $prev): void
    {
        $this->prev = $prev;
    }

    public function getPrev(): ?Throwable
    {
        return $this->prev;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
