<?php

namespace Nekkoy\GatewayAbstract\DTO;

/**
 * Gateway message DTO
 */
class MessageDTO
{
    /**
     * Message text
     * @var string
     */
    public $text;

    /**
     * Message recipient
     * @var string
     */
    public $destination;

    /**
     * Telegram User ID
     * @var int
     */
    public $user_id;

    /**
     * @param string $text
     * @param string $destination
     * @param int $telegram_id
     */
    public function __construct($text, $destination, $telegram_id = 0) {
        $this->text = trim($text);
        $this->destination = $destination;
        $this->user_id = $telegram_id;
    }

    /**
     * Magic setter method
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) {
        $this->{$name} = $value;
    }

    /**
     * Magic isset method
     * @param $name
     * @return bool
     */
    public function __isset($name) {
        return isset($this->{$name});
    }

    /**
     * Magic getter method
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->{$name};
    }
}
