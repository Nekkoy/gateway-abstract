<?php

namespace Nekkoy\GatewayAbstract\Services;

use Illuminate\Support\Facades\Log;
use Nekkoy\GatewayAbstract\DTO\AbstractConfigDTO;
use Nekkoy\GatewayAbstract\DTO\MessageDTO;
use Nekkoy\GatewayAbstract\DTO\ResponseDTO;

/**
 * Send message
 */
abstract class AbstractSendMessageService
{
    /**
     * @var AbstractConfigDTO
     */
    protected $config;

    /**
     * @var MessageDTO
     */
    protected $message;

    /** @var string */
    protected $api_url;

    /** @var mixed */
    protected $response;

    /** @var string */
    protected $useragent = 'Nekkoy/Gateway_0.0.1';

    /** @var array */
    protected $header = [];

    /** @var int */
    protected $response_code = -1;

    /** @var mixed */
    protected $response_message = "Success";

    /** @var mixed */
    protected $message_id = 0;

    /** @var int  */
    protected $connect_timeout = 5;

    /** @var int */
    protected $max_attempts = 2;

    /** @var bool  */
    protected $enabled = true;

    /**
     * @param AbstractConfigDTO $config
     * @param MessageDTO $message
     * @param int $max_attempts
     */
    public function __construct($config, $message, $max_attempts = 1)
    {
        $this->config = $config;
        $this->message = $message;
        $this->max_attempts = $max_attempts;

        $this->init();
    }

    protected function init() {

    }

    abstract protected function data();
    abstract protected function development();
    abstract protected function response();

    /** @return string */
    protected function url() {
        return $this->api_url;
    }

    /**
     * @param \CurlHandle $curl
     * @return \CurlHandle
     */
    protected function curl_options($curl) {
        return $curl;
    }

    /**
     * @return ResponseDTO
     */
    public function send() {
        // режим разработчика
        if( $this->config->devmode ) {
            $this->response = $this->development(); // имитируем ответ
        } elseif ( !$this->enabled ) {
            return new ResponseDTO('Not enabled', -1);
        } elseif ( isset($this->users) && empty($this->users) ) {
            if( isset($this->config->skip) && $this->config->skip == true ) {
                // not error if skip intended
                return new ResponseDTO('Skipped', 0);
            }

            return new ResponseDTO('User not found', -2);
        } else {
            $ch = curl_init($this->url());
            if( $ch === false ) {
                return new ResponseDTO('Curl init error', -3);
            }

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connect_timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, $this->useragent);
            curl_setopt($ch, CURLOPT_HEADER, true); // Получаем заголовки ответа
            $ch = $this->curl_options($ch);

            curl_setopt($ch, CURLOPT_HEADER, false);
            if( !empty($this->header) ) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            }

            $this->execute($ch);

            if( $this->response === false ) {
                return new ResponseDTO('No response from gateway', -1000);
            }
        }

        $this->response();

        return new ResponseDTO($this->response_message, $this->response_code, $this->message_id);
    }

    protected function execute($ch) {
        $postData = $this->data();
        if( !empty($postData) ) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        Log::debug($this->url());
        Log::debug($postData);

        $attempts = 1;
        do {
            try {
                $this->response = curl_exec($ch);
                $this->response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                Log::debug($this->response);
            } catch (\Exception $e) {
                $this->response_code = 408; // Request Timeout
                $this->response_message = $e->getMessage();
            }

            $attempts++;
        } while($attempts <= $this->max_attempts);
        curl_close($ch);
    }
}
