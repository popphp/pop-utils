<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2027 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils debugger interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2027 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
interface DebuggerInterface
{

    /**
     * Add a handler
     *
     * @param  DebuggerHandlerInterface $handler
     * @return DebuggerInterface
     */
    public function addHandler(DebuggerHandlerInterface $handler): DebuggerInterface;

}
