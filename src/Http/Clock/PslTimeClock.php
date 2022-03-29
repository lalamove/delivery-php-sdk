<?php

namespace Lalamove\Http\Clock;

require ("src/Http/Clock/ClockInterface.php");
class PslTimeClock implements ClockInterface
{
    /**
     * @return int
     */
    public function getCurrentTimeInSeconds()
    {
        return time();
    }

    /**
     * @return float|int
     */
    public function getCurrentTimeInMilliseconds()
    {
        return $this->getCurrentTimeInSeconds() * 1000;
    }
}