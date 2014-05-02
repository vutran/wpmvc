<?php

namespace WPMVC\Models;

// Import namespaces
use \WPMVC\Models\Post;

/**
 * ACF Post extension
 *
 * @package WPMVC
 * @subpackage Model
 */
class ACFPost extends Post
{

    /**
     * Sets the transient timeout for the field values
     *
     * @access protected
     * @var int
     */
    protected $transientTimeout = 1;

    /**
     * An array of ACF keys and values
     *
     * @access protected
     * @var array
     */
    protected $acfValues = false;

    /**
     * Loads the ACF values
     *
     * @access public
     * @return void
     */
    public function loadAcfValues()
    {
        // Retrieve from transient
        $transientKey = sprintf('WPMVC\Models\ACFPost(%d)::acfValues', $this->id());
        $this->acfValues = get_transient($transientKey);
        // If the values are not available
        if (!$this->acfValues) {
            // Load ACF custom fields
            if (function_exists('get_fields')) {
                $this->acfValues = get_fields($this->id());
                set_transient($transientKey, $this->acfValues, $this->transientTimeout);
            }
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
        // Load the values if necessary
        if (!$this->acfValues) { $this->loadAcfValues(); }
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