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
 * Pop utils callable interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
interface CallableInterface
{

    /**
     * Set callable
     *
     * @param  mixed $callable
     * @return CallableInterface
     */
    public function setCallable(mixed $callable): CallableInterface;

    /**
     * Get callable
     *
     * @return mixed
     */
    public function getCallable(): mixed;

    /**
     * Get callable type
     *
     * @return ?string
     */
    public function getCallableType(): ?string;

    /**
     * Set parameters
     *
     * @param  array $parameters
     * @return CallableInterface
     */
    public function setParameters(array $parameters): CallableInterface;

    /**
     * Add parameters
     *
     * @param  array $parameters
     * @return CallableInterface
     */
    public function addParameters(array $parameters): CallableInterface;

    /**
     * Add a parameter
     *
     * @param  mixed $parameter
     * @return CallableInterface
     */
    public function addParameter(mixed $parameter): CallableInterface;

    /**
     * Add a parameter
     *
     * @param  string $name
     * @param  mixed  $parameter
     * @return CallableInterface
     */
    public function addNamedParameter(string $name, mixed $parameter): CallableInterface;

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters(): array;

    /**
     * Get a parameter
     *
     * @param  string $key
     * @return mixed
     */
    public function getParameter(string $key): mixed;

    /**
     * Has parameters
     *
     * @return bool
     */
    public function hasParameters(): bool;

    /**
     * Has a parameter
     *
     * @param  string $key
     * @return bool
     */
    public function hasParameter(string $key): bool;

    /**
     * Remove a parameter
     *
     * @param  string $key
     * @return CallableInterface
     */
    public function removeParameter(string $key): CallableInterface;

    /**
     * Remove all parameters
     *
     * @return CallableInterface
     */
    public function removeParameters(): CallableInterface;

    /**
     * Set constructor parameters for instance call
     *
     * @param  array $constructorParams
     * @return CallableInterface
     */
    public function setConstructorParams(array $constructorParams): CallableInterface;

    /**
     * Get constructor parameters for instance call
     *
     * @return array
     */
    public function getConstructorParams(): array;

    /**
     * Get a constructor parameter for instance call
     *
     * @param  string $key
     * @return mixed
     */
    public function getConstructorParam(string $key): mixed;

    /**
     * Has constructor parameters for instance call
     *
     * @return bool
     */
    public function hasConstructorParams(): bool;

    /**
     * Has a constructor parameter for instance call
     *
     * @param  string $key
     * @return bool
     */
    public function hasConstructorParam(string $key): bool;

    /**
     * Remove a constructor parameter for instance call
     *
     * @param  string $key
     * @return CallableInterface
     */
    public function removeConstructorParam(string $key): CallableInterface;

    /**
     * Remove all constructor parameters for instance call
     *
     * @return CallableInterface
     */
    public function removeConstructorParams(): CallableInterface;

    /**
     * Check if object is callable
     *
     * @return bool
     */
    public function isCallable(): bool;

    /**
     * Check if object was called
     *
     * @return bool
     */
    public function wasCalled(): bool;

    /**
     * Prepare object for call
     *
     * @return CallableInterface
     */
    public function prepare(): CallableInterface;

    /**
     * Prepare parameters
     *
     * @return CallableInterface
     */
    public function prepareParameters(): CallableInterface;

    /**
     * Execute the call
     *
     * @param  mixed $parameters
     * @return mixed
     */
    public function call(mixed $parameters = null): mixed;

}
