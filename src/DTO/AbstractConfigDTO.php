<?php

namespace Nekkoy\GatewayAbstract\DTO;

/**
 * Gateway Config DTO
 * Used in:
 *  private Nekkoy\MessageRouter package
 *  public Nekkoy\GatewayGoip package
 */
abstract class AbstractConfigDTO
{
    /**
     * Name of the gateway
     * @var string
     */
    public $module;

    /**
     * Indicates whether the gateway is enabled
     * @var bool
     */
    public $enabled = false;

    /**
     * Position of the gateway in the list
     * @var int
     */
    public $priority = 1;

    /**
     * Allowed phone prefix for messages processed by the gateway
     * @var string
     */
    public $prefix = "any";

    /**
     * If a message contains a gateway tag, it will be sent only through this gateway
     * @var string
     */
    public $tags = "";

    /**
     * Indicates whether this gateway should be used as the default
     * @var bool
     */
    public $default = false;

    /**
     * Developer mode flag; messages will not be sent
     * @var bool
     */
    public $devmode = false;

    /**
     * Message handling class
     * @var \Nekkoy\GatewayAbstract\Services\AbstractSendMessageService
     */
    public $handler = \Nekkoy\GatewayAbstract\Services\AbstractSendMessageService::class;

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        foreach($config as $key => $value) {
            $this->{$key} = $value;
        }
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
