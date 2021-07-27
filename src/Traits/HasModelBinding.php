<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\Exceptions\BasePerformException;
use Illuminate\Database\Eloquent\Model;
use Throwable;

trait HasModelBinding
{
    /**
     * @var string
     */
    protected $model = '';

    /**
     * @var Model
     */
    private $created;

    /**
     * @var bool
     */
    private $restoreCancelled = false;

    /**
     * @throws Throwable
     */
    protected function restore(int $identifier): Model
    {
        /**
         * Prevent restoring hack
         */
        if($this->restoreCancelled) {
            return new $this->model;
        }

        throw_if(
            $this->model == '',
            new BasePerformException("Cannot restore the empty model!")
        );

        return $this->model::find($identifier);
    }

    /**
     * Determine model restoring
     */
    protected function dontRestoreModel()
    {
        $this->restoreCancelled = true;
    }

    /**
     * @param Model $model
     */
    protected function setCreatedModel(Model $model)
    {
        $this->created = $model;
    }

    /**
     * @return Model
     */
    protected function getCreatedModel(): Model
    {
        return $this->created;
    }
}
