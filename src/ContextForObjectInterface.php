<?php declare(strict_types=1);

namespace AP\Context;

interface ContextForObjectInterface
{
    public function setContext(Context $context): void;

    public function getContext(): ?Context;
}