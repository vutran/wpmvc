<?php

namespace WPMVC\Common;

// Import namespaces
use WPMVC\Models\View;

/**
 * Core WPMVC Bootstrap
 */
class Bootstrap
{

    /**
     * Create a new View instance
     *
     * @access public
     * @static
     * @param string $file (default : null)
     * @return \WPMVC\Models\View
     */
    public static function createView($file = null)
    {
        $view = new View(TEMPLATEPATH . '/app/views/');
        if ($file) {
            $view->setFile($file);
        }
        return $view;
    }

    /**
     * Initializes the WordPress theme
     *
     * @access public
     * @param string $rootDir (default: "")
     * @return void
     */
    public function __construct($rootDir = "")
    {
        // Define default constants
        define('WP_HOME_URL', get_home_url());
        define('WP_SITENAME', get_bloginfo('name'));
        define('WP_AJAX_URL', admin_url('admin-ajax.php'));
        define('TEMPLATEDIR', str_replace(WP_CONTENT_DIR, '', WP_CONTENT_URL . TEMPLATEPATH));
        define('STYLESHEET_URL', get_stylesheet_directory_uri());
        define('TEMPLATE_URL', get_template_directory_uri());
        define('ASSETSDIR', get_template_directory_uri().'/app/assets');
        define('RSS2_URL', get_bloginfo('rss2_url'));

        // Auto-load hook files
        $hooks = glob(TEMPLATEPATH . '/app/hooks/*');
        if ($hooks && count($hooks)) {
            foreach ($hooks as $hook) {
                if (file_exists($hook) && is_file($hook)) { require_once($hook); }
            }
        }

        // Auto-load included files
        $incs = glob(TEMPLATEPATH . '/app/inc/*');
        if ($incs && count($incs)) {
            foreach ($incs as $inc) {
                if (file_exists($inc) && is_file($inc)) { require_once($inc); }
            }
        }
    }

    /** 
     * Begin the routing
     *
     * @access public
     * @return void
     */
    public function init()
    {
        // Create a new view and set the default path as the current path
        $theHeader = new View(TEMPLATEPATH . '/core/Views/');
        $theBody = new View(TEMPLATEPATH . '/app/views/');
        $theFooter = new View(TEMPLATEPATH . '/core/Views/');

        // Set the header view
        $theHeader->setFile('header');
        // Set the footer view
        $theFooter->setFile('footer');

        // If the front page is requested
        if (is_front_page() || is_home()) {
            $theBody->setFile('home');
        } else {
            // Retrieve the requested post type
            $postType = get_query_var('post_type');
            if (is_404()) {
                // 404 view
                $theBody->setFile('404');
            } elseif (is_search()) {
                // Search index
                $theBody->setFile('search/index');
            } elseif (is_tax()) {
                // Taxonomy archive
                $taxonomy = get_query_var('taxonomy');
                $theBody->setFile(sprintf('taxonomy/%s/index', $taxonomy));
            } elseif (is_tag()) {
                // Tag archive
                $theBody->setFile('tag/index');
            } elseif (is_page()) {
                global $pagename;
                // Page view
                $theBody->setFile($pagename);
                // If view file doesn't exist, fallback to the page.php view
                if (!$theBody->hasFile()) {
                    $theBody->setFile('page');
                }
            } elseif (is_post_type_archive()) {
                // Post type archive
                $theBody->setFile(sprintf('%s/index', $postType));
            } elseif (is_single()) {
                // Retrieve the current requested post type (applies to pages, and post single and archive views)
                $postType = get_post_type();
                // Post permalink
                $theBody->setFile(sprintf('%s/single', $postType));
            }
        }

        // Apply the body file filter
        $theBody->setFile(apply_filters('wpmvc_body_file', $theBody->getFile()));

        echo $theHeader->output();
        echo $theBody->output();
        echo $theFooter->output();
    }

}