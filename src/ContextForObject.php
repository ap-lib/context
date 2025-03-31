<?php declare(strict_types=1);

namespace AP\Context;

class ContextForObject
{
    private ?Context $_context = null;

    public function setContext(Context $context): void
    {
        $this->_context = $context;
    }

    public function getContext(): ?Context
    {
        return $this->_context;
    }
}