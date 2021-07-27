<?php

namespace Cryptstick\Performs\Primitives;

use Cryptstick\Performs\BasePerform;
use Cryptstick\Performs\Exceptions\BasePerformException;
use Cryptstick\Performs\PerformsFacade;
use Cryptstick\Performs\Traits\HasAnchorBinding;
use Cryptstick\Performs\Traits\HasLifecycleDelegates;
use Cryptstick\Performs\Traits\HasModelBinding;
use Illuminate\Http\RedirectResponse;
use Throwable;

abstract class DeletePerform extends BasePerform
{
    use HasModelBinding,
        HasAnchorBinding,
        HasLifecycleDelegates;

    /**
     * DeletePerform constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultMessage('Resource successfully deleted');
    }

    /**
     * @throws Throwable
     * @throws BasePerformException
     */
    public function handle(array $data): RedirectResponse
    {
        $this->setupData($data);

        if($this->authorized()) {
            if ($this->checkInternal()) {

                /**
                 * Restore the model for delete
                 */
                $model = $this->restore(
                    $this->getDataFromAnchor(
                        PerformsFacade::anchor('transfer')
                    )
                );

                if ($model != null) {
                    /**
                     * Call the after delegate(initialized as default from config)
                     */
                    $this->call('before', $model, $data);

                    if ($model->delete()) {
                        /**
                         * Call the after delegate(initialized as default from config)
                         */
                        $this->call('after', $model, $data);

                        $this->success($this->getMessage());
                    }
                    else {
                        $this->error('Resource dont deleted, maybe core error');
                    }
                }
                else {
                    $this->error('That resource not found for restore');
                }
            }
            else {
                $this->error('Received invalid input for deletion that resource');
            }
        }
        else {
            $this->error('Permissions denied');
        }

        return $this->compute();
    }

    /**
     * @param string $delegate
     * @param ...$params
     * @throws BasePerformException
     */
    private function call(string $delegate, ...$params)
    {
        if($this->delegateCallable($delegate)) {
            $this->on($delegate, $params);
        }
    }

    /**
     * @return bool
     */
    private function checkInternal(): bool
    {
        return $this->check(CreatePerform::class, $this->getRawData());
    }
}
