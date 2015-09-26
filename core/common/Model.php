<?php

namespace WPMVC\Common;

/**
 * Base model object
 *
 * @package Models
 * @version 1.0.0
 */
abstract class Model
{
    /**
     * @access protected
     * @var array
     */
    protected $data = array();

    /**
     * Instantiates a new model
     *
     * @access public
     * @param array $data        (default: array()) A key-value paired data property array
     * @return void
     */
    public function __construct($data = array())
    {
        $this->data = $data;
    }

    /**
     * Magic method to map all parameter calls to the data property
     *
     * @access public
     * @param string $key
     * @return mixed|null        The value if it exists, or null if it doesn't
     */
    public function __get($key)
    {
        return (isset($this->data[$key])) ? $this->data[$key] : null;
    }

    /**
     * Magic method to set a specific value into the data property
     *
     * @access public
     * @param string $key        The key of the property to set
     * @param mixed $value       The value to set for the property
     * @return void
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Retrieves the data array
     *
     * @access public
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
