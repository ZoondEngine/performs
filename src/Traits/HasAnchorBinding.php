<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\Exceptions\BasePerformException;
use Cryptstick\Performs\PerformsFacade;
use Illuminate\Support\Arr;

trait HasAnchorBinding
{
    /**
     * @var array
     */
    private $dataWithAnchors;

    /**
     * @param array $data
     */
    protected function setupData(array $data)
    {
        $this->dataWithAnchors = $data;
    }

    /**
     * @return array
     */
    protected function getRawData(): array
    {
        return $this->dataWithAnchors;
    }

    /**
     * @throws BasePerformException
     */
    protected function getDataFromAnchor(string $anchor)
    {
        if($this->dataWithAnchors == null) {
            throw new BasePerformException("Trying to get value from empty data array!");
        }

        if($anchor === PerformsFacade::UNDEFINED_ANCHOR) {
            throw new BasePerformException("Trying to get undefined anchor: $anchor");
        }

        if(!Arr::has($this->dataWithAnchors, $anchor)) {
            throw new BasePerformException("Received data array dont have a requested anchor offset: $anchor");
        }

        return $this->dataWithAnchors[$anchor];
    }
}
