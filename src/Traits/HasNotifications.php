<?php

namespace Cryptstick\Performs\Traits;

use Cryptstick\Performs\PerformsFacade;
use Illuminate\Support\MessageBag;

trait HasNotifications
{
    /**
     * @var MessageBag
     */
    private $messages;

    /**
     * @var string
     */
    private $messageType;

    /**
     * @var bool
     */
    private $useNotifications = true;

    /**
     * @return bool
     */
    private function initialized(): bool
    {
        return $this->messages != null;
    }

    private function initializeIfNot()
    {
        if(!$this->initialized()) {
            $this->messages = new MessageBag();
        }
    }

    /**
     * @param string $message
     */
    protected function error(string $message) {
        $this->mark('error', 'error', 'Error', $message);
    }

    /**
     * @param string $message
     */
    protected function warning(string $message) {
        $this->mark('warning', 'warning', 'Warning', $message);
    }

    /**
     * @param string $message
     */
    protected function info(string $message) {
        $this->mark('info', 'info', 'Information', $message);
    }

    /**
     * @param string $message
     */
    protected function success(string $message) {
        $this->mark('success', 'success', 'Success', $message);
    }

    /**
     * @param string $icon
     * @param string $type
     * @param string $title
     * @param string $message
     */
    private function mark(string $icon, string $type, string $title, string $message) {
        $this->initializeIfNot();

        /**
         * Bind type
         */
        $this->messageType = [
            'icon' => $icon,
            'type' => $type,
            'title' => PerformsFacade::useLanguage() ? __($title) : $title
        ];

        /**
         * Push notification
         */
        $this->messages->add(
            $this->childClass(),
            PerformsFacade::useLanguage() ? __($message) : $message
        );
    }

    /**
     * Determines the notifications mechanism
     */
    protected function dontUseNotifications()
    {
        $this->useNotifications = false;
    }

    /**
     * @return array
     */
    protected function getNotifyData(): array
    {
        if($this->useNotifications) {
            return [
                'messagesType' => $this->messageType,
                'messages' => $this->messages
            ];
        }
        else {
            return [];
        }
    }

    /**
     * @return string
     */
    protected function childClass(): string
    {
        return last(explode('\\', get_class($this)));
    }
}
