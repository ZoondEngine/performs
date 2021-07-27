<?php

namespace Cryptstick\Performs;

/**
 * Class Performs
 * @package Cryptstick\Performs
 */
class Performs
{
    /**
     * Get the defined configuration anchor
     * @param string $name
     * @return string
     */
    public function anchor(string $name): string
    {
        return config('performs.anchors.' . $name, PerformsFacade::UNDEFINED_ANCHOR);
    }

    /**
     * Get the default defined anchors for primitive
     * @param string $primitive
     * @return array
     */
    public function anchorFor(string $primitive): array
    {
        $anchors = config('performs.anchors.defaults.' . $primitive, []);

        if(count($anchors) > 0) {
            $prepared = [];

            /**
             * Build anchor by keys
             */
            foreach($anchors as $anchor) {
                $prepared[] = $this->anchor($anchor);
            }

            return $prepared;
        }

        return [];
    }

    /**
     * @return array
     */
    public function defaultDelegates(): array
    {
        return config('performs.delegates.default', ['after', 'before']);
    }

    /**
     * @return array
     */
    public function registeredDelegates(): array
    {
        return config('performs.delegates.registered', []);
    }

    /**
     * Indicates use the lang file from configuration
     * @return bool
     */
    public function useLanguage(): bool
    {
        return config('performs.lang.use', false);
    }

    /**
     * @return string
     */
    public function defaultCompletedMessage(): string
    {
        return config(
            'performs.message.completed',
            $this->useLanguage() ? __('Action completed') : 'Action completed'
        );
    }
}
