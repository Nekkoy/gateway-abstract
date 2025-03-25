<?php

namespace Nekkoy\GatewayAbstract\DTO;

/**
 * Gateway response DTO
 */
class ResponseDTO
{
    /**
     * Response message
     * @var string
     */
    public string $message;

    /**
     * Response code
     *  < 0 - gateway errors
     *  = 0 - success
     *  > 0 - http code errors
     * @var int
     */
    public int $code;

    /**
     * Response ID
     * @var mixed
     */
    public string $id;

    /**
     * @param string $message
     * @param int $code
     * @param mixed $id
     */
    public function __construct(string $message, int $code = 0, mixed $id = 0) {
        $this->message = $message;
        $this->code = $code;
        $this->id = $id;

        if( empty($id) ) {
            list($time1, $time2) = explode(' ', microtime());
            $this->id = $time2 . substr($time1, 2, 2);
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
