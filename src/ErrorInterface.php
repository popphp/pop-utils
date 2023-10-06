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
 * Pop utils error interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
interface ErrorInterface
{

    /**
     * Get error codes
     *
     * @return array
     */
    public function getErrorCodes(): array;

    /**
     * Set error code (clear all previous errors)
     *
     * @param  mixed $errorCode
     * @return ErrorInterface
     */
    public function setErrorCode(mixed $errorCode): ErrorInterface;

    /**
     * Set error codes (clear all previous errors)
     *
     * @param  array $errorCodes
     * @return ErrorInterface
     */
    public function setErrorCodes(array $errorCodes): ErrorInterface;

    /**
     * Add error code
     *
     * @param  mixed $errorCode
     * @return ErrorInterface
     */
    public function addErrorCode(mixed $errorCode): ErrorInterface;

    /**
     * Add error codes
     *
     * @param  array $errorCodes
     * @return ErrorInterface
     */
    public function addErrorCodes(array $errorCode): ErrorInterface;

    /**
     * Has error codes
     *
     * @return bool
     */
    public function hasErrorCodes(): bool;

    /**
     * Has error (alias)
     *
     * @return bool
     */
    public function hasError(): bool;

    /**
     * Is error (alias)
     *
     * @return bool
     */
    public function isError(): bool;

    /**
     * Get error messages
     *
     * @return array
     */
    public function getErrorMessages(): array;

    /**
     * Get all error messages
     *
     * @return array
     */
    public function getAllErrorMessages(): array;

    /**
     * Get all error codes
     *
     * @return array
     */
    public function getAllErrorCodes(): array;

    /**
     * Get error message from a provided error code
     *
     * @param  mixed $errorCode
     * @return string
     */
    public function getErrorMessage(mixed $errorCode): string;

}