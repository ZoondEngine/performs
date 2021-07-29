<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\Exceptions\BasePerformException;
use Illuminate\Support\Arr;

trait HasLocalStore
{
    /**
     * @var array
     */
    private $storage = [];

    /**
     * @var array
     */
    private $hidden = [];

    /**
     * @param string $key
     * @param bool $replace
     * @param ...$params
     * @return bool
     */
    protected function store(string $key, bool $replace = false, ...$params): bool
    {
        if(!$this->has($key) || ($this->has($key) && !$replace)) {
            $this->storage[] = $params;

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return Arr::where($this->storage, function($item, $key) {
            return !Arr::has($this->hidden, $key);
        });
    }

    /**
     * @param string $key
     * @return mixed
     * @throws BasePerformException
     */
    protected function extract(string $key)
    {
        if(!$this->has($key)) {
            throw new BasePerformException("Trying to get item from local store, but does not exists: [$key]");
        }

        return $this->storage[$key];
    }

    /**
     * @param string $key
     * @return bool
     */
    protected function has(string $key): bool
    {
        return Arr::has($this->storage, $key);
    }
}
