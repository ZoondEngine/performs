<?php

namespace Cryptstick\Performs;

use Cryptstick\Performs\Contracts\IPerform;
use Cryptstick\Performs\Exceptions\BasePerformException;
use Cryptstick\Performs\Traits\HasCustomRedirects;
use Cryptstick\Performs\Traits\HasDefaultMessages;
use Cryptstick\Performs\Traits\HasNotifications;
use Cryptstick\Performs\Traits\HasRequirements;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;

/**
 * Class BasePerform
 * @package Cryptstick\Performs
 */
abstract class BasePerform implements IPerform
{
    use HasCustomRedirects,
        HasDefaultMessages,
        HasRequirements,
        HasNotifications;

    /**
     * BasePerform constructor.
     */
    public function __construct()
    {
        /**
         * Registering the default message
         */
        $this->setDefaultMessage(PerformsFacade::defaultCompletedMessage());
    }

    /**
     * @return RedirectResponse
     * @throws BasePerformException
     */
    protected function compute(): RedirectResponse
    {
        if($this->shouldBeRedirected()) {
            if(Route::has($this->getRedirectRoute())) {
                $response = redirect($this->getRedirectRoute());
            }
            else {
                throw new BasePerformException(
                    "Trying to redirect not exists route: {$this->getRedirectRoute()}"
                );
            }
        }
        else {
            $response = back();
        }

        /**
         * Get notification data if used
         */
        return $response->with($this->getNotifyData());
    }

    /**
     * @param string $trait
     * @return bool
     */
    protected function hasTrait(string $trait): bool
    {
        return trait_exists($trait);
    }

    /**
     * @param array $data
     * @return RedirectResponse
     */
    public abstract function handle(array $data): RedirectResponse;

    /**
     * @return bool
     */
    public abstract function authorized(): bool;
}
