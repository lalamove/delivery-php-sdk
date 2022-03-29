<?php

namespace src;

class Config
{
    public $host;
    public $publicKey;
    public $privateKey;
    public $market;

    public function __construct(
        $host,
        $publicKey = '',
        $privateKey = '',
        $market = ''
    ) {
        $this->host       = $host;
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $this->market    = $market;
    }
}