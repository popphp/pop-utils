<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2023 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils date-time helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2023 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.3.0
 */
class DateTime extends \DateTime
{

    /**
     * Default date format
     * @var string
     */
    protected $defaultDateFormat = null;

    /**
     * Default time format
     * @var string
     */
    protected $defaultTimeFormat = null;

    /**
     * Create a new DateTime object
     *
     * @param  string        $dateTime
     * @param  \DateTimeZone $timeZone
     * @param  string        $defaultDateFormat
     * @param  string        $defaultTimeFormat
     * @return static
     */
    public static function create($dateTime = 'now', \DateTimeZone $timeZone = null, $defaultDateFormat = null, $defaultTimeFormat = null)
    {
        $dt = new static($dateTime, $timeZone);
        if (null !== $defaultDateFormat) {
            $dt->setDefaultDateFormat($defaultDateFormat);
        }
        if (null !== $defaultTimeFormat) {
            $dt->setDefaultTimeFormat($defaultTimeFormat);
        }

        return $dt;
    }

    /**
     * Method to set the default date format
     *
     * @param  string $defaultDateFormat
     * @return static
     */
    public function setDefaultDateFormat($defaultDateFormat)
    {
        $this->defaultDateFormat = $defaultDateFormat;
        return $this;
    }

    /**
     * Method to get the default date format
     *
     * @return string
     */
    public function getDefaultDateFormat()
    {
        return $this->defaultDateFormat;
    }

    /**
     * Method to see if the object as a default date format
     *
     * @return boolean
     */
    public function hasDefaultDateFormat()
    {
        return !empty($this->defaultDateFormat);
    }

    /**
     * Method to set the default time format
     *
     * @param  string $defaultTimeFormat
     * @return static
     */
    public function setDefaultTimeFormat($defaultTimeFormat)
    {
        $this->defaultTimeFormat = $defaultTimeFormat;
        return $this;
    }

    /**
     * Method to get the default time format
     *
     * @return string
     */
    public function getDefaultTimeFormat()
    {
        return $this->defaultTimeFormat;
    }

    /**
     * Method to see if the object as a default time format
     *
     * @return boolean
     */
    public function hasDefaultTimeFormat()
    {
        return !empty($this->defaultTimeFormat);
    }

    /**
     * Method to get total time from array of multiple time values in HH:MM:SS format
     *
     * Standard hh:mm:ss format string is '%H:%I:%S'
     *
     * @param  array   $times
     * @param  string  $format
     * @param  boolean $secondsOnly
     * @return \DateInterval|string
     */
    public static function getTotal(array $times, $format = null, $secondsOnly = false)
    {
        $totalHours   = 0;
        $totalMinutes = 0;
        $totalSeconds = 0;

        foreach ($times as $i => $time) {
            if ($time instanceof \DateInterval) {
                $hours   = $time->format('%h');
                $minutes = $time->format('%i');
                $seconds = $time->format('%s');
            } else {
                if (substr_count($time, ':') == 2) {
                    [$hours, $minutes, $seconds] = explode(':', $time);
                } else {
                    $hours = 0;
                    [$minutes, $seconds] = explode(':', $time);
                }
            }
            $totalHours   += (int)$hours;
            $totalMinutes += (int)$minutes;
            $totalSeconds += (int)$seconds;
        }

        if ($secondsOnly) {
            $totalSeconds  += (int)$totalHours * 3600;
            $totalSeconds  += (int)$totalMinutes * 60;
            $intervalFormat = 'PT' . $totalSeconds . 'S';
        } else {
            if ($totalSeconds > 60) {
                $totalMinutes += floor($totalSeconds / 60);
                $totalSeconds  = $totalSeconds % 60;
            }
            if ($totalMinutes > 60) {
                $totalHours  += floor($totalMinutes / 60);
                $totalMinutes = $totalMinutes % 60;
            }
            $intervalFormat = 'PT' . (int)$totalHours . 'H' . (int)$totalMinutes . 'M' . (int)$totalSeconds . 'S';
        }

        $dateInterval = new \DateInterval($intervalFormat);

        return (null !== $format) ? $dateInterval->format($format) : $dateInterval;
    }

    /**
     * Method to get average time from array of multiple time values in HH:MM:SS format
     *
     * Standard hh:mm:ss format string is '%H:%I:%S'
     *
     * @param  array   $times
     * @param  string  $format
     * @param  boolean $secondsOnly
     * @return \DateInterval|string
     */
    public static function getAverage(array $times, $format = null, $secondsOnly = false)
    {
        $total       = static::getTotal($times, null, true);
        $totalTime   = $total->s;
        $averageTime = round(($totalTime / count($times)), 2);
        $hh          = 0;
        $mm          = 0;
        $ss          = 0;

        if ($averageTime >= 3600) {
            $hh   = floor($averageTime / 3600);
            $mins = $averageTime - ($hh * 3600);
            $mm   = floor($mins / 60);
            $ss   = (int)($mins - ($mm * 60)) % 60;
        } else if (($averageTime < 3600) && ($averageTime >= 60)) {
            $mm = floor($averageTime / 60);
            $ss = ((int)$averageTime % 60);
        } else {
            $ss = $averageTime;
        }

        if ($secondsOnly) {
            $totalSeconds   = 0;
            $totalSeconds  += (int)$hh * 3600;
            $totalSeconds  += (int)$mm * 60;
            $intervalFormat = 'PT' . $totalSeconds . 'S';
        } else {
            $intervalFormat = 'PT';
            if ($hh != 0) {
                $intervalFormat .= (int)$hh . 'H';
            }
            if ($mm != 0) {
                $intervalFormat .= (int)$mm . 'M';
            }
            if ($ss != 0) {
                $intervalFormat .= (int)$ss . 'S';
            }
        }

        $dateInterval = new \DateInterval($intervalFormat);

        return (null !== $format) ? $dateInterval->format($format) : $dateInterval;
    }

    /**
     * Method to get dates of a week
     *
     * @param  int    $week
     * @param  int    $year
     * @param  string $format
     * @return array
     */
    public static function getWeekDates($week = null, $year = null, $format = null)
    {
        if (null === $week) {
            $week = date('W');
        }
        if (null === $year) {
            $year = date('Y');
        }

        $today     = new static('today');
        $sunday    = clone $today->setISODate($year, $week, 0);
        $monday    = clone $today->setISODate($year, $week, 1);
        $tuesday   = clone $today->setISODate($year, $week, 2);
        $wednesday = clone $today->setISODate($year, $week, 3);
        $thursday  = clone $today->setISODate($year, $week, 4);
        $friday    = clone $today->setISODate($year, $week, 5);
        $saturday  = clone $today->setISODate($year, $week, 6);

        if (null !== $format) {
            $weekDates = [
                0 => $sunday->format($format),
                1 => $monday->format($format),
                2 => $tuesday->format($format),
                3 => $wednesday->format($format),
                4 => $thursday->format($format),
                5 => $friday->format($format),
                6 => $saturday->format($format),
            ];
        } else {
            $weekDates = [
                0 => $sunday,
                1 => $monday,
                2 => $tuesday,
                3 => $wednesday,
                4 => $thursday,
                5 => $friday,
                6 => $saturday,
            ];
        }

        return $weekDates;
    }

    /**
     * __toString method
     *
     * @return string
     */
    public function __toString()
    {
        $string = '';

        if (!empty($this->defaultDateFormat)) {
            $format = $this->defaultDateFormat;

            if (!empty($this->defaultTimeFormat)) {
                $format .= ' ' . $this->defaultTimeFormat;
            }

            $string = $this->format($format);
        }

        return $string;
    }

}

