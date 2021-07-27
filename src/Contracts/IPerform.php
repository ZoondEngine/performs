<?php

namespace Cryptstick\Performs\Contracts;

use Illuminate\Http\RedirectResponse;

/**
 * Interface IPerform
 * @package Cryptstick\Performs\Contracts
 */
interface IPerform
{
    /**
     * @param array $data
     * @return mixed
     */
    public function handle(array $data): RedirectResponse;

    /**
     * @return bool
     */
    public function authorized(): bool;
}
