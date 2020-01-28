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
            $this->callableType = (!empty($this->parameters)) ? self::CLOSURE_PARAMS : self::CLOSURE;
        } else if (is_string($this->callable)) {
            if (strpos($this->callable, '::') !== false) {
                $this->callableType = (!empty($this->parameters)) ? self::STATIC_CALL_PARAMS : self::STATIC_CALL;
                [$class, $method]   = explode('::', $this->callable);
            } else if (strpos($this->callable, '->') !== false) {
                $this->callableType = (!empty($this->parameters)) ? self::INSTANCE_CALL_PARAMS : self::INSTANCE_CALL;
                [$class, $method]   = explode('->', $this->callable);
            } else if (class_exists($this->callable)) {
                $this->callableType = (!empty($this->parameters)) ? self::CONSTRUCTOR_CALL_PARAMS : self::CONSTRUCTOR_CALL;
            } else if (function_exists($this->callable)) {
                $this->callableType = (!empty($this->parameters)) ? self::FUNCTION_PARAMS : self::FUNCTION;
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

        if (!$this->isCallable()) {
            throw new Exception('Error: Unable to prepare the callable object for execution.');
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
     * Execute the call
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

        $this->prepareParameters();

        $result = null;

        switch ($this->callableType) {
            case self::FUNCTION:
            case self::CLOSURE:
                $result = ${$this->callable}();
                break;
            case self::FUNCTION_PARAMS:
            case self::CLOSURE_PARAMS:
            case self::STATIC_CALL_PARAMS:
                $result = call_user_func_array($this->callable, $this->parameters);
                break;
            case self::STATIC_CALL:
                $result = call_user_func($this->callable);
                break;
            case self::INSTANCE_CALL:
                $object = (!empty($this->constructorParams)) ?
                    (new \ReflectionClass($this->class))->newInstanceArgs($this->constructorParams) :
                    new ${$this->class}();
                $result = call_user_func([$object, ${$this->method}]);
                break;
            case self::INSTANCE_CALL_PARAMS:
                $object = (!empty($this->constructorParams)) ?
                    (new \ReflectionClass($this->class))->newInstanceArgs($this->constructorParams) :
                    new ${$this->class}();
                $result = call_user_func_array([$object, ${$this->method}], $this->parameters);
                break;
            case self::CONSTRUCTOR_CALL:
                $result = new ${$this->callable}();
                break;
            case self::CONSTRUCTOR_CALL_PARAMS:
                $result = (new \ReflectionClass($this->callable))->newInstanceArgs($this->parameters);
                break;
        }

        $this->wasCalled = true;

        return $result;
    }

}