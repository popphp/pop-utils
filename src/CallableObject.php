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

use ReflectionClass;
use ReflectionException;

/**
 * Pop utils callable object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.1.0
 */
class CallableObject extends AbstractCallable
{

    /**
     * Constructor
     *
     * Instantiate the callable object
     *
     * @param  mixed $callable
     * @param  mixed $parameters
     */
    public function __construct(mixed $callable, mixed $parameters = null)
    {
        $this->setCallable($callable);
        if ($parameters !== null) {
            if (is_array($parameters)) {
                $this->setParameters($parameters);
            } else {
                $this->addParameter($parameters);
            }
        }
    }

    /**
     * Prepare object for call
     *
     * @throws Exception
     * @return CallableObject
     */
    public function prepare(): CallableObject
    {
        $class  = null;
        $method = null;

        if ($this->callable instanceof \Closure) {
            $this->callableType = self::CLOSURE;
        } else if (is_object($this->callable)) {
            $this->callableType = self::OBJECT;
        } else if (is_string($this->callable)) {
            if (str_contains($this->callable, '::')) {
                $this->callableType = self::STATIC_CALL;
                [$class, $method]   = explode('::', $this->callable);
            } else if (str_contains($this->callable, '->')) {
                $this->callableType = self::INSTANCE_CALL;
                [$class, $method]   = explode('->', $this->callable);
            } else if (str_starts_with($this->callable, 'new ')) {
                $this->callableType = self::NEW_OBJECT;
                $class = substr($this->callable, 4);
            } else if (class_exists($this->callable)) {
                $this->callableType = self::CONSTRUCTOR_CALL;
            } else if (function_exists($this->callable)) {
                $this->callableType = self::FUNCTION;
            }
        } else if (is_callable($this->callable)) {
            $this->callableType = self::IS_CALLABLE;
        }

        if ($class !== null) {
            if (!class_exists($class)) {
                throw new Exception("Error: The class '" . $class . "' does not exist.");
            }
            if ($method !== null) {
                if (!method_exists($class, $method)) {
                    throw new Exception("Error: The method '" . $method  . "' does not exist in the class '" . $class . "'.");
                }
            }
        }

        if ($this->callableType === null) {
            throw new Exception('Error: Unable to prepare the callable object for execution.');
        } else if (!empty($this->parameters)) {
            $this->callableType .= '_PARAMS';
        }

        $this->class  = $class;
        $this->method = $method;

        return $this;
    }

    /**
     * Prepare parameters
     *
     * @throws Exception|ReflectionException
     * @return CallableObject
     */
    public function prepareParameters(): CallableObject
    {
        foreach ($this->parameters as $key => $value) {
            if ($value instanceof self) {
                $this->parameters[$key] = $value->call();
            } else if (is_callable($value)) {
                $this->parameters[$key] = call_user_func($value);
            } else if (is_array($value) && isset($value[0]) && is_callable($value[0])) {
                $callable = $value[0];
                unset($value[0]);
                $this->parameters[$key] = call_user_func_array($callable, array_values($value));
            }
        }

        return $this;
    }

    /**
     * Execute the callable
     *
     * @param  mixed $parameters
     * @return mixed
     * @throws Exception|ReflectionException
     */
    public function call(mixed $parameters = null): mixed
    {
        if ($parameters !== null) {
            if (!is_array($parameters)) {
                $this->addParameter($parameters);
            } else {
                $this->addParameters($parameters);
            }
        }

        if ($this->callableType === null) {
            $this->prepare();
        }

        $this->prepareParameters();

        $result = null;

        switch ($this->callableType) {
            case self::FUNCTION:
            case self::CLOSURE:
            case self::STATIC_CALL:
            case self::IS_CALLABLE:
                $result = call_user_func($this->callable);
                break;
            case self::FUNCTION_PARAMS:
            case self::CLOSURE_PARAMS:
            case self::STATIC_CALL_PARAMS:
            case self::IS_CALLABLE_PARAMS:
                $result = call_user_func_array($this->callable, array_values($this->parameters));
                break;
            case self::INSTANCE_CALL:
                $class  = $this->class;
                $object = (!empty($this->constructorParams)) ?
                    (new ReflectionClass($class))->newInstanceArgs($this->constructorParams) :
                    new $class();
                $result = call_user_func([$object, $this->method]);
                break;
            case self::INSTANCE_CALL_PARAMS:
                $class  = $this->class;
                $object = (!empty($this->constructorParams)) ?
                    (new ReflectionClass($class))->newInstanceArgs($this->constructorParams) :
                    new $class();
                $result = call_user_func_array([$object, $this->method], array_values($this->parameters));
                break;
            case self::CONSTRUCTOR_CALL:
                $class  = $this->callable;
                $result = new $class();
                break;
            case self::CONSTRUCTOR_CALL_PARAMS:
                $result = (new ReflectionClass($this->callable))->newInstanceArgs($this->parameters);
                break;
            case self::NEW_OBJECT:
                $class  = $this->class;
                $result = new $class();
                break;
            case self::OBJECT:
                $result = $this->callable;
                break;
        }

        $this->wasCalled = true;

        return $result;
    }

}
