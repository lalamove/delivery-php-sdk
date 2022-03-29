<?php

namespace src;

use Lalamove\Resources\QuotationsResource;

require("OrderService.php");
require("src/Resources/QuotationsResource.php");

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
     * @var QuotationsResource
     */
    public $quotations;

    /**
     * @param $config Config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->quotations = new QuotationsResource($config);
    }

}