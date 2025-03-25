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
    public string $module;

    /**
     * Indicates whether the gateway is enabled
     * @var bool
     */
    public bool $enabled = false;

    /**
     * Position of the gateway in the list
     * @var int
     */
    public int $priority = 1;

    /**
     * Allowed phone prefix for messages processed by the gateway
     * @var string
     */
    public string $prefix = "any";

    /**
     * If a message contains a gateway tag, it will be sent only through this gateway
     * @var string
     */
    public string $tags = "";

    /**
     * Indicates whether this gateway should be used as the default
     * @var bool
     */
    public bool $default = false;

    /**
     * Developer mode flag; messages will not be sent
     * @var bool
     */
    public bool $devmode = false;

    /**
     * Message handling class
     * @var \Nekkoy\GatewayAbstract\Services\AbstractSendMessageService
     */
    public string $handler = \Nekkoy\GatewayAbstract\Services\AbstractSendMessageService::class;

    public function __construct(array $config)
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
     * Magic getter method
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        return $this->{$name};
    }
}
