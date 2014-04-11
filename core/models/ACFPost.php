<?php

namespace WPMVC\Models;

// Import namespaces
use WPMVC\Model\Post;

/**
 * ACF Post extension
 *
 * @package WPMVC
 * @subpackage Model
 */
class ACFPost extends Post
{

    /**
     * An array of ACF keys and values
     *
     * @access protected
     * @var array
     */
    protected $acfValues = false;

    /**
     * Instantiates a new ACF post instance
     *
     * @access public
     * @constructor
     * @param mixed $post
     */
    public function __construct($post)
    {
        parent::__construct($post);
        // Load ACF custom fields
        if (function_exists('get_fields')) {
            $this->acfValues = get_fields($this->id());
        }
    }

    /**
     * Check if the ACF field exists
     *
     * @access public
     * @param string $key
     * @return bool
     */
    public function hasField($key)
    {
        return isset($this->acfValues[$key]) ? true : false;
    }

    /**
     * If the ACF field exists, return it; otherwise returns null
     *
     * @access public
     * @return mixed
     */
    public function getField($key)
    {
        return $this->hasField($key) ? $this->acfValues[$key] : null;
    }

}

?>