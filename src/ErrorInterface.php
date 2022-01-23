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
 * Pop utils error interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2021 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.0
 */
interface ErrorInterface
{

    /**
     * Get error codes
     *
     * @return array
     */
    public function getErrorCodes();

    /**
     * Set error code (clear all previous errors)
     *
     * @param  mixed $errorCode
     * @return ErrorInterface
     */
    public function setErrorCode($errorCode);

    /**
     * Set error codes (clear all previous errors)
     *
     * @param  array $errorCodes
     * @return ErrorInterface
     */
    public function setErrorCodes(array $errorCodes);

    /**
     * Add error code
     *
     * @param  mixed $errorCode
     * @return ErrorInterface
     */
    public function addErrorCode($errorCode);

    /**
     * Add error codes
     *
     * @param  array $errorCodes
     * @return ErrorInterface
     */
    public function addErrorCodes(array $errorCode);

    /**
     * Has error codes
     *
     * @return boolean
     */
    public function hasErrorCodes();

    /**
     * Has error (alias)
     *
     * @return boolean
     */
    public function hasError();

    /**
     * Is error (alias)
     *
     * @return boolean
     */
    public function isError();

    /**
     * Get error messages
     *
     * @return array
     */
    public function getErrorMessages();

    /**
     * Get all error messages
     *
     * @return array
     */
    public function getAllErrorMessages();

    /**
     * Get all error codes
     *
     * @return array
     */
    public function getAllErrorCodes();

    /**
     * Get error message from a provided error code
     *
     * @param  mixed $errorCode
     * @return string
     */
    public function getErrorMessage($errorCode);

}