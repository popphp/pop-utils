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
 * Pop utils callable object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.0.0
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
    public function __construct($callable, $parameters = null)
    {
        $this->setCallable($callable);
        if (null !== $parameters) {
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
     * @return CallableObject
     */
    public function prepare()
    {
        $class  = null;
        $method = null;

        if ($this->callable instanceof \Closure) {
            $this->callableType = self::CLOSURE;
        } else if (is_string($this->callable)) {
            if (strpos($this->callable, '::') !== false) {
                $this->callableType = self::STATIC_CALL;
                [$class, $method]   = explode('::', $this->callable);
            } else if (strpos($this->callable, '->') !== false) {
                $this->callableType = self::INSTANCE_CALL;
                [$class, $method]   = explode('->', $this->callable);
            } else if (class_exists($this->callable)) {
                $this->callableType = self::CONSTRUCTOR_CALL;
            } else if (function_exists($this->callable)) {
                $this->callableType = self::FUNCTION;
            }
        }

        if (null !== $class) {
            if (!class_exists($class)) {
                throw new Exception("Error: The class '" . $class . "' does not exist.");
            }
            if (null !== $method) {
                if (!method_exists($class, $method)) {
                    throw new Exception("Error: The method '" . $method  . "' does not exist in the class '" . $class . "'.");
                }
            }
        }

        if (null === $this->callableType) {
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
     * @return CallableObject
     */
    public function prepareParameters()
    {
        foreach ($this->parameters as $key => $value) {
            if ($value instanceof self) {
                $this->parameters[$key] = $value->call();
            } else if (is_callable($value)) {
                $this->parameters[$key] = call_user_func($value);
            } else if (is_array($value) && isset($value[0]) && is_callable($value[0])) {
                $callable = $value[0];
                unset($value[0]);
                $this->parameters[$key] = call_user_func_array($callable, $value);
            }
        }

        return $this;
    }

    /**
     * Execute the callable
     *
     * @param  mixed $parameters
     * @return mixed
     */
    public function call($parameters = null)
    {
        if (null !== $parameters) {
            if (!is_array($parameters)) {
                $this->addParameter($parameters);
            } else {
                $this->addParameters($parameters);
            }
        }

        if (null === $this->callableType) {
            $this->prepare();
        }

        $this->prepareParameters();

        $result = null;

        switch ($this->callableType) {
            case self::FUNCTION:
            case self::CLOSURE:
            case self::STATIC_CALL:
                $result = call_user_func($this->callable);
                break;
            case self::FUNCTION_PARAMS:
            case self::CLOSURE_PARAMS:
            case self::STATIC_CALL_PARAMS:
                $result = call_user_func_array($this->callable, $this->parameters);
                break;
            case self::INSTANCE_CALL:
                $class  = $this->class;
                $object = (!empty($this->constructorParams)) ?
                    (new \ReflectionClass($class))->newInstanceArgs($this->constructorParams) :
                    new $class();
                $result = call_user_func([$object, $this->method]);
                break;
            case self::INSTANCE_CALL_PARAMS:
                $class  = $this->class;
                $object = (!empty($this->constructorParams)) ?
                    (new \ReflectionClass($class))->newInstanceArgs($this->constructorParams) :
                    new $class();
                $result = call_user_func_array([$object, $this->method], $this->parameters);
                break;
            case self::CONSTRUCTOR_CALL:
                $class  = $this->callable;
                $result = new $class();
                break;
            case self::CONSTRUCTOR_CALL_PARAMS:
                $result = (new \ReflectionClass($this->callable))->newInstanceArgs($this->parameters);
                break;
        }

        $this->wasCalled = true;

        return $result;
    }

}