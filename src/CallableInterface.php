<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils callable interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.0.0
 */
interface CallableInterface
{

    /**
     * Set callable
     *
     * @param  mixed $callable
     * @return CallableInterface
     */
    public function setCallable($callable);

    /**
     * Get callable
     *
     * @return mixed
     */
    public function getCallable();

    /**
     * Get callable type
     *
     * @return string
     */
    public function getCallableType();

    /**
     * Set parameters
     *
     * @param  array $parameters
     * @return CallableInterface
     */
    public function setParameters(array $parameters);

    /**
     * Add parameters
     *
     * @param  array $parameters
     * @return CallableInterface
     */
    public function addParameters(array $parameters);

    /**
     * Add a parameter
     *
     * @param  mixed $parameter
     * @return CallableInterface
     */
    public function addParameter($parameter);

    /**
     * Add a parameter
     *
     * @param  string $name
     * @param  mixed  $parameter
     * @return CallableInterface
     */
    public function addNamedParameter($name, $parameter);

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters();

    /**
     * Get a parameter
     *
     * @param  string $key
     * @return mixed
     */
    public function getParameter($key);

    /**
     * Has parameters
     *
     * @return boolean
     */
    public function hasParameters();

    /**
     * Has a parameter
     *
     * @param  string $key
     * @return boolean
     */
    public function hasParameter($key);

    /**
     * Remove a parameter
     *
     * @param  string $key
     * @return CallableInterface
     */
    public function removeParameter($key);

    /**
     * Remove all parameters
     *
     * @return CallableInterface
     */
    public function removeParameters();

    /**
     * Set constructor parameters
     *
     * @param  array $constructorParams
     * @return CallableInterface
     */
    public function setConstructorParams(array $constructorParams);

    /**
     * Get parameters
     *
     * @return array
     */
    public function getConstructorParams();

    /**
     * Get a parameter
     *
     * @param  string $key
     * @return mixed
     */
    public function getConstructorParam($key);

    /**
     * Has parameters
     *
     * @return boolean
     */
    public function hasConstructorParams();

    /**
     * Has a parameter
     *
     * @param  string $key
     * @return boolean
     */
    public function hasConstructorParam($key);

    /**
     * Remove a parameter
     *
     * @param  string $key
     * @return CallableInterface
     */
    public function removeConstructorParam($key);

    /**
     * Remove all parameters
     *
     * @return CallableInterface
     */
    public function removeConstructorParams();

    /**
     * Check if object is callable
     *
     * @return boolean
     */
    public function isCallable();

    /**
     * Check if object was called
     *
     * @return boolean
     */
    public function wasCalled();

    /**
     * Prepare object for call
     *
     * @return CallableInterface
     */
    public function prepare();

    /**
     * Prepare parameters
     *
     * @return CallableInterface
     */
    public function prepareParameters();

    /**
     * Execute the call
     *
     * @param  mixed $parameters
     * @return mixed
     */
    public function call($parameters = null);

}
