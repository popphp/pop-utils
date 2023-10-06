<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
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
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
abstract class AbstractError implements ErrorInterface
{

    /**
     * Current error codes
     * @var array
     */
    protected array $errorCodes = [];

    /**
     * Error messages
     * @var array
     */
    protected array $errorMessages = [];

    /**
     * Get current error codes
     *
     * @return array
     */
    public function getErrorCodes(): array
    {
        return $this->errorCodes;
    }

    /**
     * Set error code (clear all previous errors)
     *
     * @param  mixed $errorCode
     * @throws Exception
     * @return AbstractError
     */
    public function setErrorCode(mixed $errorCode): AbstractError
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
     * @param  array $errorCodes
     * @return AbstractError
     */
    public function setErrorCodes(array $errorCodes): AbstractError
    {
        $this->errorCodes = [];
        $this->addErrorCodes($errorCodes);
        return $this;
    }

    /**
     * Add error code
     *
     * @param  mixed $errorCode
     * @throws Exception
     * @return AbstractError
     */
    public function addErrorCode(mixed $errorCode): AbstractError
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
     * @param  array $errorCodes
     * @return AbstractError
     */
    public function addErrorCodes(array $errorCodes): AbstractError
    {
        foreach ($errorCodes as $errorCode) {
            $this->addErrorCode($errorCode);
        }

        return $this;
    }

    /**
     * Has error codes
     *
     * @return bool
     */
    public function hasErrorCodes(): bool
    {
        return !empty($this->errorCodes);
    }

    /**
     * Has error (alias)
     *
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->hasErrorCodes();
    }

    /**
     * Is error (alias)
     *
     * @return bool
     */
    public function isError(): bool
    {
        return $this->hasErrorCodes();
    }

    /**
     * Get current error messages
     *
     * @return array
     */
    public function getErrorMessages(): array
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
    public function getAllErrorMessages(): array
    {
        return $this->errorMessages;
    }

    /**
     * Get all error codes
     *
     * @return array
     */
    public function getAllErrorCodes(): array
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
    public function getErrorMessage(mixed $errorCode): string
    {
        if (!array_key_exists($errorCode, $this->errorMessages)) {
            throw new Exception('Error: That error code is not allowed.');
        }
        return $this->errorMessages[$errorCode];
    }

}