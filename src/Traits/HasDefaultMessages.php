<?php

namespace Cryptstick\Performs\Traits;

trait HasDefaultMessages
{
    /**
     * @var string
     */
    private $defaultMessage = '';

    /**
     * @param string $message
     */
    protected function setDefaultMessage(string $message)
    {
        $this->defaultMessage = $message;
    }

    /**
     * @return string
     */
    protected function getDefaultMessage(): string
    {
        return $this->defaultMessage;
    }

    /**
     * @return bool
     */
    protected function shouldUseDefaultMessage(): bool
    {
        return $this->getDefaultMessage() !== '';
    }
}
