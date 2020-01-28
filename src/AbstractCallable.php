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
abstract class AbstractCallable implements CallableInterface
{

    /**
     * Call type constants
     */
    const FUNCTION                = 'FUNCTION';                // Function
    const FUNCTION_PARAMS         = 'FUNCTION_PARAMS';         // Function w/ parameters
    const CLOSURE                 = 'CLOSURE';                 // Closure
    const CLOSURE_PARAMS          = 'CLOSURE_PARAMS';          // Closure w/ parameters
    const STATIC_CALL             = 'STATIC_CALL';             // 'Class::method'
    const STATIC_CALL_PARAMS      = 'STATIC_CALL_PARAMS';      // 'Class::method' w/ parameters
    const INSTANCE_CALL           = 'INSTANCE_CALL';           // 'Class->method'
    const INSTANCE_CALL_PARAMS    = 'INSTANCE_CALL_PARAMS';    // 'Class->method' w/ parameters
    const CONSTRUCTOR_CALL        = 'CONSTRUCTOR_CALL';        // 'Class'
    const CONSTRUCTOR_CALL_PARAMS = 'CONSTRUCTOR_CALL_PARAMS'; // 'Class' w/ parameters
    const NEW_OBJECT              = 'NEW_OBJECT';              // 'new Class'
    const OBJECT                  = 'OBJECT';                  // Already an instantiated object

    /**
     * Callable
     * @var mixed
     */
    protected $callable = null;

    /**
     * Parameters
     * @var array
     */
    protected $parameters = [];

    /**
     * Callable type
     * @var string
     */
    protected $callableType = null;

    /**
     * Was called flag
     * @var boolean
     */
    protected $wasCalled = false;

    /**
     * Callable class
     * @var string
     */
    protected $class = null;

    /**
     * Callable method
     * @var string
     */
    protected $method = null;

    /**
     * Constructor parameters for instance calls
     * @var array
     */
    protected $constructorParams = [];

    /**
     * Set callable
     *
     * @param  mixed $callable
     * @return AbstractCallable
     */
    public function setCallable($callable)
    {
        $this->callable = $callable;
        return $this;
    }

    /**
     * Get callable
     *
     * @return mixed
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * Get callable type
     *
     * @return string
     */
    public function getCallableType()
    {
        return $this->callableType;
    }

    /**
     * Set parameters
     *
     * @param  array $parameters
     * @return AbstractCallable
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Add parameters
     *
     * @param  array $parameters
     * @return AbstractCallable
     */
    public function addParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if (is_numeric($key)) {
                $this->addParameter($value);
            } else {
                $this->addNamedParameter($key, $value);
            }
        }
        return $this;
    }

    /**
     * Add a parameter
     *
     * @param  mixed $parameter
     * @return AbstractCallable
     */
    public function addParameter($parameter)
    {
        $this->parameters[] = $parameter;
        return $this;
    }

    /**
     * Add a parameter
     *
     * @param  string $name
     * @param  mixed  $parameter
     * @return AbstractCallable
     */
    public function addNamedParameter($name, $parameter)
    {
        $this->parameters[$name] = $parameter;
        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Get a parameter
     *
     * @param  string $key
     * @return mixed
     */
    public function getParameter($key)
    {
        return (isset($this->parameters[$key])) ? $this->parameters[$key] : null;
    }

    /**
     * Has parameters
     *
     * @return boolean
     */
    public function hasParameters()
    {
        return !empty($this->parameters);
    }

    /**
     * Has a parameter
     *
     * @param  string $key
     * @return boolean
     */
    public function hasParameter($key)
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Remove a parameter
     *
     * @param  string $key
     * @return AbstractCallable
     */
    public function removeParameter($key)
    {
        if (isset($this->parameters[$key])) {
            unset($this->parameters[$key]);
        }
        return $this;
    }

    /**
     * Remove all parameters
     *
     * @return AbstractCallable
     */
    public function removeParameters()
    {
        $this->parameters = [];
        return $this;
    }

    /**
     * Set constructor parameters
     *
     * @param  array $constructorParams
     * @return AbstractCallable
     */
    public function setConstructorParams(array $constructorParams)
    {
        $this->constructorParams = $constructorParams;
        return $this;
    }

    /**
     * Get constructor parameters for instance call
     *
     * @return array
     */
    public function getConstructorParams()
    {
        return $this->constructorParams;
    }

    /**
     * Get a constructor parameter for instance call
     *
     * @param  string $key
     * @return mixed
     */
    public function getConstructorParam($key)
    {
        return (isset($this->constructorParams[$key])) ? $this->constructorParams[$key] : null;
    }

    /**
     * Has constructor parameters for instance call
     *
     * @return boolean
     */
    public function hasConstructorParams()
    {
        return !empty($this->constructorParams);
    }

    /**
     * Has a constructor parameter for instance call
     *
     * @param  string $key
     * @return boolean
     */
    public function hasConstructorParam($key)
    {
        return isset($this->constructorParams[$key]);
    }

    /**
     * Remove a constructor parameter for instance call
     *
     * @param  string $key
     * @return AbstractCallable
     */
    public function removeConstructorParam($key)
    {
        if (isset($this->constructorParams[$key])) {
            unset($this->constructorParams[$key]);
        }
        return $this;
    }

    /**
     * Remove all constructor parameters for instance call
     *
     * @return AbstractCallable
     */
    public function removeConstructorParams()
    {
        $this->constructorParams = [];
        return $this;
    }

    /**
     * Check if object is callable
     *
     * @return boolean
     */
    public function isCallable()
    {
        if (null === $this->callableType) {
            $this->prepare();
        }

        return (null !== $this->callableType);
    }

    /**
     * Check if object was called
     *
     * @return boolean
     */
    public function wasCalled()
    {
        return $this->wasCalled;
    }

    /**
     * Prepare object for call
     *
     * @return AbstractCallable
     */
    abstract public function prepare();

    /**
     * Prepare parameters
     *
     * @return AbstractCallable
     */
    abstract public function prepareParameters();

    /**
     * Execute the call
     *
     * @param  mixed $parameters
     * @return mixed
     */
    abstract public function call($parameters = null);

}