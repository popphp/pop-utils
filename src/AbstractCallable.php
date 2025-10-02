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
 * Pop utils callable object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
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
    const IS_CALLABLE             = 'IS_CALLABLE';             // Is a basic callable entity
    const IS_CALLABLE_PARAMS      = 'IS_CALLABLE_PARAMS';      // Is a basic callable entity w/ parameters
    const NEW_OBJECT              = 'NEW_OBJECT';              // 'new Class'
    const OBJECT                  = 'OBJECT';                  // Already an instantiated object

    /**
     * Callable
     * @var mixed
     */
    protected mixed $callable = null;

    /**
     * Parameters
     * @var array
     */
    protected array $parameters = [];

    /**
     * Callable type
     * @var ?string
     */
    protected ?string $callableType = null;

    /**
     * Was called flag
     * @var bool
     */
    protected bool $wasCalled = false;

    /**
     * Callable class
     * @var ?string
     */
    protected ?string $class = null;

    /**
     * Callable method
     * @var ?string
     */
    protected ?string $method = null;

    /**
     * Constructor parameters for instance calls
     * @var array
     */
    protected array $constructorParams = [];

    /**
     * Set callable
     *
     * @param  mixed $callable
     * @return AbstractCallable
     */
    public function setCallable(mixed $callable): AbstractCallable
    {
        $this->callable = $callable;
        return $this;
    }

    /**
     * Get callable
     *
     * @return mixed
     */
    public function getCallable(): mixed
    {
        return $this->callable;
    }

    /**
     * Get callable type
     *
     * @return ?string
     */
    public function getCallableType(): ?string
    {
        return $this->callableType;
    }

    /**
     * Set parameters
     *
     * @param  array $parameters
     * @return AbstractCallable
     */
    public function setParameters(array $parameters): AbstractCallable
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
    public function addParameters(array $parameters): AbstractCallable
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
    public function addParameter(mixed $parameter): AbstractCallable
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
    public function addNamedParameter(string $name, mixed $parameter): AbstractCallable
    {
        $this->parameters[$name] = $parameter;
        return $this;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Get a parameter
     *
     * @param  string $key
     * @return mixed
     */
    public function getParameter(string $key): mixed
    {
        return (isset($this->parameters[$key])) ? $this->parameters[$key] : null;
    }

    /**
     * Has parameters
     *
     * @return bool
     */
    public function hasParameters(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * Has a parameter
     *
     * @param  string $key
     * @return bool
     */
    public function hasParameter(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Remove a parameter
     *
     * @param  string $key
     * @return AbstractCallable
     */
    public function removeParameter(string $key): AbstractCallable
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
    public function removeParameters(): AbstractCallable
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
    public function setConstructorParams(array $constructorParams): AbstractCallable
    {
        $this->constructorParams = $constructorParams;
        return $this;
    }

    /**
     * Get constructor parameters for instance call
     *
     * @return array
     */
    public function getConstructorParams(): array
    {
        return $this->constructorParams;
    }

    /**
     * Get a constructor parameter for instance call
     *
     * @param  string $key
     * @return mixed
     */
    public function getConstructorParam(string $key): mixed
    {
        return (isset($this->constructorParams[$key])) ? $this->constructorParams[$key] : null;
    }

    /**
     * Has constructor parameters for instance call
     *
     * @return bool
     */
    public function hasConstructorParams(): bool
    {
        return !empty($this->constructorParams);
    }

    /**
     * Has a constructor parameter for instance call
     *
     * @param  string $key
     * @return bool
     */
    public function hasConstructorParam(string $key): bool
    {
        return isset($this->constructorParams[$key]);
    }

    /**
     * Remove a constructor parameter for instance call
     *
     * @param  string $key
     * @return AbstractCallable
     */
    public function removeConstructorParam(string $key): AbstractCallable
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
    public function removeConstructorParams(): AbstractCallable
    {
        $this->constructorParams = [];
        return $this;
    }

    /**
     * Check if object is callable
     *
     * @return bool
     */
    public function isCallable(): bool
    {
        if ($this->callableType === null) {
            $this->prepare();
        }

        return ($this->callableType !== null);
    }

    /**
     * Check if object was called
     *
     * @return bool
     */
    public function wasCalled(): bool
    {
        return $this->wasCalled;
    }

    /**
     * Prepare object for call
     *
     * @return AbstractCallable
     */
    abstract public function prepare(): AbstractCallable;

    /**
     * Prepare parameters
     *
     * @return AbstractCallable
     */
    abstract public function prepareParameters(): AbstractCallable;

    /**
     * Execute the call
     *
     * @param  mixed $parameters
     * @return mixed
     */
    abstract public function call(mixed $parameters = null): mixed;

}
