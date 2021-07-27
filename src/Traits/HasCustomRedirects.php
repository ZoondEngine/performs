<?php

namespace Cryptstick\Performs\Traits;

use Illuminate\Support\Facades\Route;

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
        if(Route::has($routeName)) {
            $this->redirectRoute = route($routeName);
        }
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
