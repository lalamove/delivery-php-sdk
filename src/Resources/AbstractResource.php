<?php

namespace Lalamove\Resources;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Lalamove\Exceptions\ConflictException;
use Lalamove\Exceptions\ForbiddenException;
use Lalamove\Exceptions\InvalidRequestException;
use Lalamove\Exceptions\LalamoveException;
use Lalamove\Exceptions\NotFoundException;
use Lalamove\Exceptions\PaymentRequiredException;
use Lalamove\Exceptions\ServerException;
use Lalamove\Exceptions\TooManyRequestsException;
use Lalamove\Exceptions\UnauthorizedException;

use Lalamove\Http\GuzzleTransport;
use Lalamove\Http\LalamoveRequest;

require ("src/Http/GuzzleTransport.php");
require ("src/Http/LalamoveRequest.php");


abstract class AbstractResource
{
    const LALAMOVE_TIME_FORMAT = 'Y-m-d\TH:i:00.000\Z';
    const LALAMOVE_API_VERSION = '2';

    protected $transport;
    protected $settings;

    public function __construct($settings, $transport = null)
    {
        $this->settings = $settings;
        $this->transport = $transport ? $transport : new GuzzleTransport();
    }

    /**
     * @param $method
     * @param $uri
     * @param array $params
     * @return mixed
     */
    protected function send($method, $uri, $params = [])
    {
        $request = new LalamoveRequest($this->settings, $method, $uri, $params);

        try {
            return $this->transport->send($request);

        } catch (\Exception $ex) {
           throw $ex;
        }
    }

    /**
     * @param RequestException $baseException
     * @return LalamoveException|null
     */
    protected function mapClientException(RequestException $baseException)
    {
        $typedExceptions = [
            // Client exceptions
            ConflictException::class,
            ForbiddenException::class,
            InvalidRequestException::class,
            NotFoundException::class,
            PaymentRequiredException::class,
            TooManyRequestsException::class,
            UnauthorizedException::class,
            // Server exceptions
            ServerException::class,
        ];

        $message = json_decode("{$baseException->getResponse()->getBody()}");
        $message = isset($message->detail) ? $message->detail : null;

        foreach ($typedExceptions as $cExName) {
            /** @var \Lalamove\Exceptions\LalamoveException $cEx */
            $cEx = new $cExName($message ? $message : $baseException->getMessage(), $baseException->getCode(), $baseException);

            $response = $baseException->getResponse();

            if ($response && $response->getStatusCode() == $cEx->getStatusCode()) {
                return $cEx;
            }
        }

        return null;
    }
}
