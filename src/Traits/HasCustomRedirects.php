<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\Exceptions\BasePerformException;
use Illuminate\Support\Facades\Route;

trait HasCustomRedirects
{
    /**
     * @var string
     */
    private $redirectRoute = '';

    /**
     * @param string $routeName
     * @throws BasePerformException
     */
    protected function shouldRedirectTo(string $routeName)
    {
        if(Route::has($routeName)) {
            $this->redirectRoute = route($routeName);
        }
        else {
            throw new BasePerformException(
                "Trying to redirect not exists route: {$this->getRedirectRoute()}"
            );
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
