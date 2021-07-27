<?php

namespace Cryptstick\Performs\Primitives;

use Cryptstick\Performs\BasePerform;
use Cryptstick\Performs\PerformsFacade;
use Cryptstick\Performs\Traits\HasAnchorBinding;
use Cryptstick\Performs\Traits\HasModelBinding;
use Illuminate\Http\RedirectResponse;
use Throwable;

abstract class CreatePerform extends BasePerform
{
    use HasModelBinding,
        HasAnchorBinding;

    /**
     * CreatePerform constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultMessage('Resource successfully created');
    }

    /**
     * @param array $data
     * @return RedirectResponse
     * @throws Throwable
     */
    public function handle(array $data): RedirectResponse
    {
        /**
         * Setup received data
         */
        $this->setupData($data);

        /**
         * Run creation perform
         */
        if($this->authorized()) {
            if($this->checkInternal()) {
                $model = new $this->model($this->getDataFromAnchor(
                    PerformsFacade::anchor('data')
                ));

                if($model != null) {
                    if($model->save()) {
                        $this->success($this->getDefaultMessage());

                        /**
                         * Save the created model
                         */
                        $this->setCreatedModel($model);
                    }
                    else {
                        $this->error('Can\'t save that resource, maybe core error');
                    }
                }
                else {
                    $this->error('Can\'t create the model with received data');
                }
            }
            else {
                $this->error('Received invalid data for creating resource');
            }
        } else {
            $this->error('Permissions denied');
        }

        /**
         * Compute the response after creation chain
         */
        return $this->compute();
    }

    /**
     * @return bool
     */
    private function checkInternal(): bool
    {
        return $this->check(CreatePerform::class, $this->getRawData());
    }
}
