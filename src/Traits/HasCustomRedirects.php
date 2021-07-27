<?php

namespace Cryptstick\Performs\Traits;

trait HasCustomRedirects
{
    /**
     * @var string
     */
    private $redirectRoute = '';

    /**
     * @param string $routeName
     */
    protected function shouldRedirectTo(string $routeName)
    {
        $this->redirectRoute = route($routeName);
    }

    /**
     * @return bool
     */
    protected function shouldBeRedirected(): bool
    {
        return $this->redirectRoute !== '';
    }

    /**
     * @return string
     */
    protected function getRedirectRoute(): string
    {
        return $this->redirectRoute;
    }
}
