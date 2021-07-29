<?php

namespace Cryptstick\Performs;

use Cryptstick\Performs\Contracts\IPerform;
use Cryptstick\Performs\Traits\HasCustomRedirects;
use Cryptstick\Performs\Traits\HasDefaultMessages;
use Cryptstick\Performs\Traits\HasLocalStore;
use Cryptstick\Performs\Traits\HasNotifications;
use Cryptstick\Performs\Traits\HasRequirements;
use Illuminate\Http\RedirectResponse;

/**
 * Class BasePerform
 * @package Cryptstick\Performs
 */
abstract class BasePerform implements IPerform
{
    use HasCustomRedirects,
        HasDefaultMessages,
        HasRequirements,
        HasNotifications,
        HasLocalStore;

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
     */
    protected function compute(): RedirectResponse
    {
        if($this->shouldBeRedirected()) {
            $response = redirect($this->getRedirectRoute());
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
