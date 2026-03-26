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
 * Pop utils UUID helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.3.0
 */
class Uuid
{

    /**
     * Generate a v4 UUID (random)
     *
     * @return string
     */
    public static function v4(): string
    {
        $bytes    = random_bytes(16);
        $bytes[6] = chr(ord($bytes[6]) & 0x0f | 0x40);
        $bytes[8] = chr(ord($bytes[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    /**
     * Check if the Linux random/uuid file is available
     *
     * @param  string $file
     * @return bool
     */
    public static function v4LinuxAvailable(string $file = '/proc/sys/kernel/random/uuid'): bool
    {
        return file_exists($file);
    }

    /**
     * Generate a v4 UUID (random) using the Linux random/uuid file
     *
     * @param  string $file
     * @return string
     */
    public static function v4Linux(string $file = '/proc/sys/kernel/random/uuid'): string
    {
        if (!static::v4LinuxAvailable($file)) {
            throw new Exception('Error: The Linux random UUID file is not available.');
        }

        return trim(file_get_contents($file));
    }

    /**
     * Generate a v7 UUID (time-based)
     *
     * @return string
     */
    public static function v7(): string
    {
        $ms      = (int)(microtime(true) * 1000);
        $time    = str_pad(dechex($ms), 12, '0', STR_PAD_LEFT);
        $random  = bin2hex(random_bytes(9));
        $uuid    = substr($time, 0, 8) . '-' . substr($time, 8, 4) . '-7' . substr($random, 0, 3);
        $variant = dechex(hexdec($random[3]) & 0x3 | 0x8);
        $uuid   .= '-' . $variant . substr($random, 4, 3) . '-' . substr($random, 7);

        return $uuid;
    }

}
