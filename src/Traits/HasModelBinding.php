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

        if($this->model === '') {
            throw new BasePerformException("Cannot restore the empty model!");
        }

        $model = $this->model::find($identifier);

        if($model == null) {
            throw new BasePerformException("Model: $this->model not found!");
        }

        return $model;
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
