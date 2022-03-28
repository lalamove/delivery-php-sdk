<?php

namespace src;

require("Client.php");
require("OrderService.php");
require("QuotationService.php");

class Client
{
    /**
     * @var Config
     */
    public $config;

    /**
     * @var OrderService
     */
    public $orders;

    /**
     * @var QuotationService
     */
    public $quotations;

    /**
     * @param $config Config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->quotations = new QuotationService();
    }

}