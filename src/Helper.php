<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class Helper
{


    /**
     * Check if helper functions are loaded
     *
     * @return bool
     */
    public static function isLoaded(): bool
    {
        return function_exists('app_date');
    }

    /**
     * Load helper functions
     *
     * @return void
     */
    public static function loadFunctions(): void
    {
        include __DIR__ . '/functions.php';
    }

}
