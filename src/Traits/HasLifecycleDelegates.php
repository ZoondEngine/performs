<?php

namespace Cryptstick\Performs\Traits;

use Closure;
use Cryptstick\Performs\Exceptions\BasePerformException;
use Cryptstick\Performs\PerformsFacade;
use Illuminate\Support\Arr;

trait HasLifecycleDelegates
{
    /**
     * @var array
     */
    private $delegates = [];

    /**
     * @throws BasePerformException
     */
    protected function on(string $delegate, array $params)
    {
        if($this->delegateCallable($delegate)) {
            $this->delegates[$delegate]->call($this, $params);
        }
        else {
            throw new BasePerformException(
                "Try to call not initialized delegate: $delegate"
            );
        }
    }

    /**
     * @throws BasePerformException
     */
    protected function registerDelegate(string $delegate, Closure $closure)
    {
        $this->setupIfEmpty();

        if(!$this->delegateCallable($delegate)) {
            $this->delegates[$delegate] = $closure;
        }
        else {
            throw new BasePerformException(
                "Try to apply closure to lifecycle delegate, but already exists: $delegate"
            );
        }
    }

    /**
     * Setup delegates array with default if empty
     */
    private function setupIfEmpty()
    {
        if(count($this->delegates) <= 0) {

            /**
             * Merge delegates
             */
            $forRegister = array_merge(
                PerformsFacade::defaultDelegates(),
                PerformsFacade::registeredDelegates()
            );

            /**
             * Adding delegates to array
             */
            foreach ($forRegister as $delegate) {
                $this->delegates[$delegate] = null;
            }
        }
    }

    /**
     * @param string $delegate
     * @return bool
     */
    protected function hasDelegate(string $delegate): bool
    {
        return Arr::has($this->delegates, $delegate);
    }

    /**
     * @param string $delegate
     * @return bool
     */
    protected function delegateCallable(string $delegate): bool
    {
        if($this->hasDelegate($delegate)) {
            return $this->delegates[$delegate] != null;
        }

        return false;
    }
}
