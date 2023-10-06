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
 * Pop utils abstract array object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
abstract class AbstractArray implements ArrayableInterface
{

    /**
     * Array data
     * @var mixed
     */
    protected mixed $data = null;

    /**
     * Get the values as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        if (!is_array($this->data)) {
            if (is_object($this->data) && method_exists($this->data, 'toArray')) {
                $data = $this->data->toArray();
            } else if ($this->data instanceof \ArrayObject) {
                $data = (array)$this->data;
            } else if ($this->data instanceof \Traversable) {
                $data = iterator_to_array($this->data);
            }
        } else {
            $data = $this->data;
        }

        foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $data[$key] = $value->toArray();
            }
        }

        return $data;
    }

}