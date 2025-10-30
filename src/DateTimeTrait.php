<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils date-time format helper trait
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.3.0
 */
trait DateTimeTrait
{

    /**
     * Date-time format
     * @var ?string
     */
    protected ?string $dateTimeFormat = null;

    /**
     * Date-time formats
     * @var array
     */
    protected array $dateTimeFormats = [
        '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\.\d{3}$/' => 'Y-m-d H:i:s.v',
        '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/'        => 'Y-m-d H:i:s',
        '/^\d{2}\/\d{2}\/\d{4} \d{1,2}:\d{2}\s(A|P)M$/'  => 'm/d/Y g:i A',
        '/^\d{2}\/\d{2}\/\d{2} \d{1,2}:\d{2}\s(A|P)M$/'  => 'm/d/y g:i A',
        '/^\d{4}-\d{2}-\d{2}$/'                          => 'Y-m-d',
        '/^\d{2}\/\d{2}\/\d{4}$/'                        => 'm/d/Y',
        '/^\d{2}\.\d{2}\.\d{4}$/'                        => 'd.m.Y',
        '/^\d{2}\/\d{2}\/\d{2}$/'                        => 'm/d/y',
        '/^\d{2}\.\d{2}\.\d{2}$/'                        => 'd.m.y',
        '/^\d{2}:\d{2}:\d{2}$/'                          => 'H:i:s',
        '/^\d{1,2}:\d{2}\s(A|P)M$/'                      => 'g:i A',
    ];

    /**
     * Detect date-time format
     *
     * @param  string $dateTime
     * @return ?string
     */
    public static function detectDateTimeFormat(string $dateTime): ?string
    {
        return (new self())->detectFormat($dateTime);
    }

    /**
     * Detect date-time format
     *
     * @param  string $dateTime
     * @return ?string
     */
    public function detectFormat(string $dateTime): ?string
    {
        foreach ($this->dateTimeFormats as $regex => $format) {
            if (preg_match($regex, $dateTime)) {
                $this->dateTimeFormat = $format;
                break;
            }
        }

        return $this->dateTimeFormat;
    }
}
