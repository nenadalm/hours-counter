<?php

namespace NenadalM\HoursCounter;

class Time
{
    const HOUR_TO_MINUTES = 60;
    const HOUR_TIME_SEPARATOR = ':';

    /** @var int */
    private $hours;
    /** @var int */
    private $minutes;

    /**
     * @param int $hours
     * @param int $minutes
     */
    public function __construct($hours, $minutes)
    {
        $this->hours = (int) $hours;
        $this->minutes = (int) $minutes;
    }

    /**
     * @param string $timeIntervalString
     *
     * @return Time
     */
    public static function createFromIntervalString($timeIntervalString)
    {
        $matches = [];
        preg_match('/([0-9]{1,2}:[0-9]{1,2}) (-) ([0-9]{1,2}:[0-9]{1,2})/', $timeIntervalString, $matches);
        list(, $lOperand, , $rOperand) = $matches;

        return static::createFromTimeString($rOperand)->sub(static::createFromTimeString($lOperand));
    }

    /**
     * @param string $timeString
     *
     * @return Time
     */
    public static function createFromTimeString($timeString)
    {
        $matches = [];
        preg_match('/([0-9]{1,2}):([0-9]{1,2})/', $timeString, $matches);
        list(, $hours, $minutes) = $matches;

        return new self($hours, $minutes);
    }

    /**
     * @param Time $time
     *
     * @return Time
     */
    public function add(Time $time)
    {
        $hours = $this->getHours() + $time->getHours();
        $minutes = $this->getMinutes() + $time->getMinutes();
        $restOfMinutes = $minutes % static::HOUR_TO_MINUTES;

        return new self($hours + ($minutes - $restOfMinutes) / static::HOUR_TO_MINUTES, $restOfMinutes);
    }

    /**
     * @param Time $time
     *
     * @return Time
     */
    public function sub(Time $time)
    {
        $hours = $this->getHours() - $time->getHours();
        $minutes = $this->getMinutes() - $time->getMinutes();
        if ($minutes < 0) {
            $minutes = static::HOUR_TO_MINUTES + $minutes;
            --$hours;
        }

        return new self($hours, $minutes);
    }

    public function isZero()
    {
        return $this->getHours() === 0 && $this->getMinutes() === 0;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @return int
     */
    public function getMinutes()
    {
        return $this->minutes;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%d%s%s (%f)',
            $this->hours,
            static::HOUR_TIME_SEPARATOR,
            str_pad($this->minutes, 2, 0, STR_PAD_LEFT),
            $this->hours + $this->minutes / static::HOUR_TO_MINUTES
        );
    }
}
