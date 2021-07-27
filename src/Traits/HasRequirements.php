<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\PerformsFacade;
use Illuminate\Support\Arr;

trait HasRequirements
{
    /**
     * @var array
     */
    protected $checkers = [];

    /**
     * @param array $data
     * @return bool
     */
    protected function requires(array $data): bool
    {
        return Arr::has($data, $this->checkers);
    }

    /**
     * @param string $primitive
     * @param array $data
     * @return bool
     */
    protected function check(string $primitive, array $data): bool
    {
        /**
         * Populate default checkers
         * for that perform primitive
         */
        $this->populateChecksIfEmpty($primitive);

        /**
         * Call simply
         */
        return $this->requires($data);
    }

    /**
     * Populate checkers from config
     */
    private function populateChecksIfEmpty(string $primitive)
    {
        if(count($this->checkers) <= 0) {
            $this->checkers = PerformsFacade::anchorFor($primitive);
        }
    }
}
