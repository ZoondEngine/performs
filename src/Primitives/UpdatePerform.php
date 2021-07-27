<?php

namespace Cryptstick\Performs\Primitives;

use Closure;
use Cryptstick\Performs\BasePerform;
use Cryptstick\Performs\Exceptions\BasePerformException;
use Cryptstick\Performs\PerformsFacade;
use Cryptstick\Performs\Traits\HasAnchorBinding;
use Cryptstick\Performs\Traits\HasLifecycleDelegates;
use Cryptstick\Performs\Traits\HasModelBinding;
use Illuminate\Http\RedirectResponse;
use Throwable;

abstract class UpdatePerform extends BasePerform
{
    use HasModelBinding,
        HasAnchorBinding,
        HasLifecycleDelegates;

    /**
     * Anchor for should update delegate
     */
    private const SHOULD_UPDATE_DELEGATE = 'shouldUpdate';

    /**
     * DeletePerform constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setDefaultMessage('Resource successfully updated');
    }

    /**
     * @param Closure $closure
     * @throws BasePerformException
     */
    public function shouldUpdate(Closure $closure)
    {
        if(!$this->delegateCallable(self::SHOULD_UPDATE_DELEGATE)) {
            $this->setup(self::SHOULD_UPDATE_DELEGATE, $closure);
        }
    }

    /**
     * @throws Throwable
     * @throws BasePerformException
     */
    public function handle(array $data): RedirectResponse
    {
        $this->setupData($data);

        if($this->authorized()) {
            if($this->checkInternal()) {
                $model = $this->restore($this->getDataFromAnchor(
                    PerformsFacade::anchor('id')
                ));

                if($model != null) {
                    if($this->updatePrevented()) {
                        /**
                         * Use custom update if dont need use our primitive logic :(
                         */
                        $this->on(self::SHOULD_UPDATE_DELEGATE, $model, $data);
                        $this->success($this->getDefaultMessage());
                    }
                    else {
                        if($model->update($this->getDataFromAnchor(
                            PerformsFacade::anchor('data')
                        ))) {
                            $this->success($this->getDefaultMessage());
                        }
                        else {
                            $this->error('Resource dont updated, maybe core error');
                        }
                    }
                }
                else {
                    $this->error('That resource not found');
                }
            }
            else {
                $this->error('Received invalid data for that resource');
            }
        }
        else {
            $this->error('Permissions denied');
        }

        return $this->compute();
    }

    /**
     * @return bool
     */
    private function updatePrevented(): bool
    {
        return $this->delegateCallable(self::SHOULD_UPDATE_DELEGATE);
    }

    /**
     * @return bool
     */
    private function checkInternal(): bool
    {
        return $this->check(UpdatePerform::class, $this->getRawData());
    }
}
