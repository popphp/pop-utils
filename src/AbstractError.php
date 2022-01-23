<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2021 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils abstract error class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2021 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.3.0
 */
abstract class AbstractError implements ErrorInterface
{

    /**
     * Current error codes
     * @var mixed
     */
    protected $errorCodes = [];

    /**
     * Error messages
     * @var array
     */
    protected $errorMessages = [];

    /**
     * Get current error codes
     *
     * @return array
     */
    public function getErrorCodes()
    {
        return $this->errorCodes;
    }

    /**
     * Set error code (clear all previous errors)
     *
     * @param mixed $errorCode
     * @return AbstractError
     * @throws Exception
     */
    public function setErrorCode($errorCode)
    {
        if (!array_key_exists($errorCode, $this->errorMessages)) {
            throw new Exception('Error: That error code is not allowed.');
        }
        $this->errorCodes = [$errorCode];
        return $this;
    }

    /**
     * Set error codes (clear all previous errors)
     *
     * @param array $errorCodes
     * @return AbstractError
     */
    public function setErrorCodes(array $errorCodes)
    {
        $this->errorCodes = [];
        $this->addErrorCodes($errorCodes);
        return $this;
    }

    /**
     * Add error code
     *
     * @param mixed $errorCode
     * @return AbstractError
     * @throws Exception
     */
    public function addErrorCode($errorCode)
    {
        if (!array_key_exists($errorCode, $this->errorMessages)) {
            throw new Exception('Error: That error code is not allowed.');
        }
        $this->errorCodes[] = $errorCode;
        return $this;
    }

    /**
     * Add error codes
     *
     * @param array $errorCodes
     * @return AbstractError
     */
    public function addErrorCodes(array $errorCodes)
    {
        foreach ($errorCodes as $errorCode) {
            $this->addErrorCode($errorCode);
        }

        return $this;
    }

    /**
     * Has error codes
     *
     * @return boolean
     */
    public function hasErrorCodes()
    {
        return !empty($this->errorCodes);
    }

    /**
     * Has error (alias)
     *
     * @return boolean
     */
    public function hasError()
    {
        return $this->hasErrorCodes();
    }

    /**
     * Is error (alias)
     *
     * @return boolean
     */
    public function isError()
    {
        return $this->hasErrorCodes();
    }

    /**
     * Get current error messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        $errorCodes = $this->errorCodes;

        return array_filter($this->errorMessages, function ($k) use ($errorCodes) {
            return in_array($k, $errorCodes);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get all error messages
     *
     * @return array
     */
    public function getAllErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Get all error codes
     *
     * @return array
     */
    public function getAllErrorCodes()
    {
        return array_keys($this->errorMessages);
    }

    /**
     * Get error message from a provided error code
     *
     * @param  mixed $errorCode
     * @throws Exception
     * @return string
     */
    public function getErrorMessage($errorCode)
    {
        if (!array_key_exists($errorCode, $this->errorMessages)) {
            throw new Exception('Error: That error code is not allowed.');
        }
        return $this->errorMessages[$errorCode];
    }

}