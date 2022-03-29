<?php

namespace Lalamove\Http\Uuid;

require ("src/Http/Uuid/UuidGeneratorInterface.php");
/**
 * Class PslUniqidGenerator
 * Generates a universally unique identifier using the PHP standard library uniqid() function.
 * @package Lalamove\Http
 */
class PslUniqidGenerator implements UuidGeneratorInterface
{
    /**
     * @return string
     */
    public function getUuid()
    {
        return uniqid();
    }
}