<?php
declare(strict_types=1);
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

use Psr\Log\LoggerInterface;

/**
 * Pop utils debugger handler interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
interface DebuggerHandlerInterface
{

    /**
     * Set name
     *
     * @param  string $name
     * @return DebuggerHandlerInterface
     */
    public function setName(string $name): DebuggerHandlerInterface;

    /**
     * Get name
     *
     * @return ?string
     */
    public function getName(): ?string;

    /**
     * Has name
     *
     * @return bool
     */
    public function hasName(): bool;

    /**
     * Set data
     *
     * @param  array $data
     * @return DebuggerHandlerInterface
     */
    public function setData(array $data = []): DebuggerHandlerInterface;

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Has data
     *
     * @return bool
     */
    public function hasData(): bool;

    /**
     * Start
     *
     * @return DebuggerHandlerInterface
     */
    public function start(): DebuggerHandlerInterface;

    /**
     * Stop
     *
     * @return DebuggerHandlerInterface
     */
    public function stop(): DebuggerHandlerInterface;

    /**
     * Set start
     *
     * @param  ?float $start
     * @return DebuggerHandlerInterface
     */
    public function setStart(?float $start = null): DebuggerHandlerInterface;

    /**
     * Get start
     *
     * @return ?float
     */
    public function getStart(): ?float;

    /**
     * Has start
     *
     * @return bool
     */
    public function hasStart(): bool;

    /**
     * Set end
     *
     * @param  ?float $end
     * @return DebuggerHandlerInterface
     */
    public function setEnd(?float $end = null): DebuggerHandlerInterface;

    /**
     * Get end
     *
     * @return ?float
     */
    public function getEnd(): ?float;

    /**
     * Has end
     *
     * @return bool
     */
    public function hasEnd(): bool;

    /**
     * Set elapsed
     *
     * @param  float $elapsed
     * @return DebuggerHandlerInterface
     */
    public function setElapsed(float $elapsed): DebuggerHandlerInterface;

    /**
     * Get elapsed
     *
     * @return ?float
     */
    public function getElapsed(): ?float;

    /**
     * Has elapsed
     *
     * @return bool
     */
    public function hasElapsed(): bool;

    /**
     * Set logger
     *
     * @param  LoggerInterface $logger
     * @return DebuggerHandlerInterface
     */
    public function setLogger(LoggerInterface $logger): DebuggerHandlerInterface;

    /**
     * Get logger
     *
     * @return ?LoggerInterface
     */
    public function getLogger(): ?LoggerInterface;

    /**
     * Has logger
     *
     * @return bool
     */
    public function hasLogger(): bool;

    /**
     * Set logger
     *
     * @param  array $loggingParams
     * @return DebuggerHandlerInterface
     */
    public function setLoggingParams(array $loggingParams): DebuggerHandlerInterface;

    /**
     * Get logging params
     *
     * @return array
     */
    public function getLoggingParams(): array;

    /**
     * Has logging parameters
     *
     * @return bool
     */
    public function hasLoggingParams(): bool;

    /**
     * Prepare handler data for storage
     *
     * @return array
     */
    public function prepare(): array;

    /**
     * Prepare handler message
     *
     * @param  ?array $context
     * @return string
     */
    public function prepareMessage(?array $context = null): string;

    /**
     * Trigger handle logging
     *
     * @return void
     */
    public function log(): void;

}
