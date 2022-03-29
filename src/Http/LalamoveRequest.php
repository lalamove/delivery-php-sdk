<?php

namespace Lalamove\Http;

use Lalamove\Http\Clock\ClockInterface;
use Lalamove\Http\Clock\PslTimeClock;
use Lalamove\Http\Uuid\PslUniqidGenerator;
use Lalamove\Http\Uuid\UuidGeneratorInterface;
use src\Config;

require ("src/Http/Uuid/PslUniqidGenerator.php");
require ("src/Http/Clock/PslTimeClock.php");


class LalamoveRequest
{
    /** @var Config */
    protected $settings;
    /** @var string */
    protected $method;
    /** @var string */
    protected $uri;
    /** @var array */
    protected $params;
    /** @var UuidGeneratorInterface */
    protected $uuid;
    /** @var ClockInterface */
    protected $clock;

    /**
     * LalamoveRequest constructor.
     * @param $settings
     * @param string $method
     * @param string $uri
     * @param array $params
     * @param UuidGeneratorInterface|null $uuid
     * @param ClockInterface $clock
     */
    public function __construct(
        $settings,
        $method = 'GET',
        $uri = '',
        $params = [],
        UuidGeneratorInterface $uuid = null,
        ClockInterface $clock = null
    ) {
        $this->settings = $settings;
        $this->method   = $method;
        $this->uri      = $uri;
        $this->params   = $this->object2array($params);

        // Dependency injected for easier unit testing:

        if (is_null($uuid)) {
            $uuid = new PslUniqidGenerator();
        }
        $this->uuid = $uuid;

        if (is_null($clock)) {
            $clock = new PslTimeClock();
        }

        $this->clock = $clock;
    }

    /**
     * @param $o
     * @return array
     */
    protected function object2array($o)
    {
        $a = (array)$o;
        foreach ($a as &$v) {
            if (is_object($v) || is_array($v)) {
                $v = $this->object2array($v);
            }
        }
        return $a;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return string
     */
    public function getFullPath()
    {
        $host    = $this->settings->host;
        $version = 3;

        return "{$host}/v{$version}/{$this->uri}";
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $customerId = $this->settings->publicKey;
        $privateKey = $this->settings->privateKey;
        $country    = $this->settings->market;

        $requestTime = $this->clock->getCurrentTimeInMilliseconds();

        $uuid = $this->uuid->getUuid();
        $uri  = str_replace($this->settings->host, '', $this->getFullPath());

        $body    = json_encode(["data" => $this->getParams()]);
        $message = "{$requestTime}\r\n{$this->method}\r\n{$uri}\r\n\r\n";

        if ($this->method != 'GET') {
            $message .= $body;
        }

        echo $message;

        $signature = hash_hmac("sha256", $message, $privateKey);

        return [
            'Authorization' => "hmac {$customerId}:{$requestTime}:{$signature}",
            'Accept' => 'application/json',
            'Content-type' => 'application/json; charset=utf-8',
            'Market' => strtoupper($country),
            'X-Request_ID' => $uuid,
        ];
    }

    public function getSettings()
    {
        return $this->settings;
    }


}