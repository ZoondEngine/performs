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
    protected function setup(string $delegate, Closure $closure)
    {
        $this->setupIfEmpty();

        if(!$this->hasDelegate($delegate)) {
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
            $this->delegates = array_merge(
                PerformsFacade::defaultDelegates(),
                PerformsFacade::registeredDelegates()
            );
        }
    }

    /**
     * @param string $delegate
     * @return bool
     */
    private function hasDelegate(string $delegate): bool
    {
        return Arr::has($this->delegates, $delegate);
    }

    /**
     * @param string $delegate
     * @return bool
     */
    private function delegateCallable(string $delegate): bool
    {
        if($this->hasDelegate($delegate)) {
            return $this->delegates[$delegate] != null;
        }

        return false;
    }
}
