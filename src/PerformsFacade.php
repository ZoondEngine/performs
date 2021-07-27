<?php

namespace Cryptstick\Performs;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string defaultCompletedMessage()
 * @method static bool useLanguage()
 * @method static string anchor(string $string)
 * @method static array anchorFor(string $string)
 * @method static array defaultDelegates()
 * @method static array registeredDelegates()
 */
class PerformsFacade extends Facade
{
    /**
     * Indicates the undefined anchor
     */
    const UNDEFINED_ANCHOR = 'undefined';

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'performs';
    }
}
