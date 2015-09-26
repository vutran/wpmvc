<?php

namespace WPMVC\Models;

use \WPMVC\Common\Model;
use \Minify_HTML;

/**
 * The View Model
 *
 * @package Model
 * @subpackage Views
 * @version 1.0.0
 */
class View extends Model
{
    /**
     * The root path for the view (excludes the path to the file)
     *
     * @access private
     * @var string
     */
    private $_path = '';

    /**
     * The path to the file relative to the view's path
     *
     * @access private
     * @var string
     */
    private $_file = '';

    /**
     * @access private
     * @var array
     */
    private $_vars = array();

    /**
     * Creates a new view
     *
     * @access public
     * @param string $path          The path of the view
     * @return void
     */
    public function __construct($path = '')
    {
        // Set the view's path
        $this->setPath($path);
    }

    /**
     * Sets the view path
     *
     * @access public
     * @param string $path      The view's path
     * @return self
     */
    public function setPath($path)
    {
        $this->_path = $path;
        return $this;
    }

    /**
     * Sets the view path
     *
     * @access public
     * @return string
     */
    public function getPath()
    {
        return rtrim($this->_path, '/');
    }

    /**
     * Checks if the view file exists
     *
     * @access public
     * @return bool
     */
    public function hasFile()
    {
        return file_exists($this->getPath() . '/' . $this->getFile(true));
    }

    /**
     * Sets the view file (make sure to exclude the PHP extension)
     *
     * @access public
     * @param string $file      The file path relative to the view's path
     * @return self
     */
    public function setFile($file)
    {
        $this->_file = $file;
        return $this;
    }

    /**
     * Retrieves the view file
     *
     * @access public
     * @param bool $includeExtension    (default: false) Set to true to include the .php extension
     * @return string
     */
    public function getFile($includeExtension = false)
    {
        $ext = $includeExtension ? '.php' : '';
        return sprintf('%s%s', $this->_file, $ext);
    }

    /**
     * Sets a variable for the view
     *
     * @access public
     * @param array $vars
     * @return self
     */
    public function setVars($vars)
    {
        // If vars is an array
        if ( is_array($vars) && count($vars)) {
            // Iterate and set the var
            foreach ($vars as $key => $value) {
                $this->setVar($key, $value);
            }
        }
        return $this;
    }

    /**
     * Retrieves all stored variable
     *
     * @access public
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Sets a variable for the view
     *
     * @access public
     * @param string $key                 The name of the variable
     * @param mixed $value                A value to store
     * @return self
     */
    public function setVar($key, $value)
    {
        // Store into the vars array
        $this->_vars[$key] = $value;
        return $this;
    }

    /**
     * Retrieves a stored variable
     *
     * @access public
     * @param string $key                 The name of the variable
     * @return mixed
     */
    public function getVar($key)
    {
        return (array_key_exists($key, $this->_vars)) ? $this->_vars[$key] : null;
    }

    /**
     * Sets the view variable (will accept an array of key/values)
     *
     * @access public
     * @param string|array $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value = '')
    {
        // If array
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->_vars[$key] = $value;
        }
        return $this;
    }

    /**
     * Retrieve a view variable
     *
     * @access public
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return array_key_exists($key, $this->_vars) ? $this->_vars[$key] : null;
    }

    /**
     * Retrieve's the view output
     *
     * @access public
     * @param int $minify                 (default: false) Set to true to minify
     * @return string
     */
    public function output($minify = false)
    {
        // If the view file exists
        if ($this->hasFile()) {
            // Extract all view variables
            extract($this->getVars());
            ob_start();
            include($this->getPath() . '/' . $this->getFile(true));
            $html = ob_get_contents();
            if ($minify) { $html = Minify_HTML::minify($html); }
            ob_end_clean();
            return $html;
        } else {
            die('Fatal Error: ' . $this->getPath() . '/' . $this->getFile(true) . ' does not exist.');
        }
    }
}
