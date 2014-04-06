<?php

namespace WPMVC\Models;

// Import namespaces
use WPMVC\Model\Post;

/**
 * MF2 Post extension
 *
 * @package WPMVC
 * @subpackage Model
 */
class MFPost extends Post
{

    /**
     * An array of MF2 keys and values
     *
     * @access protected
     * @var array
     */
    protected $mfValues = false;

    /**
     * Instantiates a new ACF post instance
     *
     * @access public
     * @constructor
     * @param mixed $post
     */
    public function __construct($post)
    {
        // Load Magic Field 2
        $this->_loadMF2();
        parent::__construct($post);
    }

    /**
     * Include's the Magic Field 2 front-end functions
     *
     * @access private
     * @return void
     */
    private function _loadMF2()
    {
        // Include front-end functions
        $file = WP_PLUGIN_DIR . '/magic-fields-2/mf_front_end.php';
        if (file_exists($file)) { include_once($file); }
    }

}

?>