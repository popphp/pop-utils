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
 * Pop utils jsonable interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.2.0
 */
interface JsonableInterface extends \JsonSerializable
{

    /**
     * JSON serialize the array object
     *
     * @param  int $options
     * @param  int $depth
     * @return string
     */
    public function jsonSerialize(int $options = 0, int $depth = 512): string;

    /**
     * Unserialize a JSON string
     *
     * @param  string $jsonString
     * @param  int    $depth
     * @param  int    $options
     * @return ArrayObject
     */
    public function jsonUnserialize(string $jsonString, int $depth = 512, int $options = 0): ArrayObject;

}
