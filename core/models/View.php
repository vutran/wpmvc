<?php

namespace WPMVC\Models;

use WPMVC\Common\Model;

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
     * Output's the view
     *
     * @access public
     * @param string $name                The name of the view to load (relative to the views directory)
     * @param array $vars                 An array of variables to pass to the view
     * @return self
     */
    public function display($name, $vars = array())
    {
        echo $this->get($name, $vars);
        return $this;
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
     * Sets the view variable
     *
     * @access public
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set($key, $value)
    {
        $this->_vars[$key] = $value;
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
     * @param array $vars                 (default: array()) An array of variables to pass to the view
     * @return string
     */
    public function output($vars = array())
    {
        // If the view file exists
        if ($this->hasFile()) {
            // Merge registry variables
            extract($vars);
            ob_start();
            include($this->getPath() . '/' . $this->getFile(true));
            $html = ob_get_contents();
            ob_end_clean();
            return $html;
        } else {
            die('Fatal Error: ' . $this->getPath() . '/' . $this->getFile(true) . ' does not exist.');
        }
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
     * Sets the view file
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

}

?>