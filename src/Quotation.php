<?php

namespace Lalamove;

use Carbon\Carbon;
use Lalamove\Resources\AbstractResource;

class Quotation
{
    const SPECIAL_REQUEST_COD = 'COD';
    const SPECIAL_REQUEST_HELP_BUY = 'HELP_BUY';
    const SPECIAL_REQUEST_BAG = 'LALABAG';

    const FLEET_PRIORITY_NONE = 'NONE';
    const FLEET_PRIORITY_FLEET_FIRST = 'FLEET_FIRST';

    /** @var string */
    public $serviceType = 'MOTORCYCLE';
    /** @var array */
    public $specialRequests = [];
    /** @var string */
    public $language;
    /** @var array */
    public $stops = [];

    public static function make($stops, $serviceType = 'MOTORCYCLE', $language = 'en_HK')
    {
        $instance = new static;
        $instance->set($stops, $serviceType, $language);
        return $instance;
    }


    protected function set($stops, $serviceType, $language)
    {
        $this->serviceType = $serviceType;
        $this->stops = $stops;
        $this->language = $language;
    }

    /**
     * @param Carbon $scheduleAt
     * @return string
     */
    public function setScheduleAt(Carbon $scheduleAt)
    {
        $this->scheduleAt = $scheduleAt->format(AbstractResource::LALAMOVE_TIME_FORMAT);
        return $this->scheduleAt;
    }

    /**
     * @param string|array $request
     */
    public function addSpecialRequest($request)
    {
        $this->specialRequests = array_merge($this->specialRequests, (array) $request);
    }

    /**
     * @param Stop|array $stop
     */
    public function addStop($stop)
    {
        $this->stops = array_merge($this->stops, (array) $stop);
    }

    /**
     * @param Delivery|array $delivery
     */
    public function addDelivery($delivery)
    {
        $this->deliveries = array_merge($this->deliveries, (array) $delivery);
    }
}
