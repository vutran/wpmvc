<?php

// Define default constants
define('WP_HOME_URL', get_home_url());
define('WP_SITENAME', get_bloginfo('name'));
define('WP_AJAX_URL', admin_url('admin-ajax.php'));
define('TEMPLATEDIR', str_replace(WP_CONTENT_DIR, '', WP_CONTENT_URL . TEMPLATEPATH));
define('STYLESHEET_URL', get_stylesheet_directory_uri());
define('TEMPLATE_URL', get_template_directory_uri());
define('RSS2_URL', get_bloginfo('rss2_url'));

// Composer autoload
if (file_exists(STYLESHEETPATH . '/vendor/autoload.php')) {
    require_once(STYLESHEETPATH . '/vendor/autoload.php');
}

// Auto-load hook files
$hooks = glob(TEMPLATEPATH . '/app/hooks/*');
if ($hooks && count($hooks)) {
    foreach ($hooks as $hook) {
        if (file_exists($hook) && is_file($hook)) { require_once($hook); }
    }
}