<?php

namespace Lalamove\Resources;

require("AbstractResource.php");


class QuotationsResource extends AbstractResource
{
    public function create($quotation)
    {
        var_dump($quotation);
        $response = $this->send('POST', 'quotations', $quotation);
        return $response;
    }
}