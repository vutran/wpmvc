<?php

// Import namespaces
use WPMVC\Common\Bootstrap;

// Composer autoload
if (file_exists(STYLESHEETPATH . '/vendor/autoload.php')) {
    require_once(STYLESHEETPATH . '/vendor/autoload.php');
}

global $app;

// Instantiate the WPMVC
$app = new Bootstrap([
    'templatePath' => TEMPLATEPATH,
    'templateDir' => str_replace(WP_CONTENT_DIR, '', WP_CONTENT_URL . TEMPLATEPATH),
    'templateUrl' => get_template_directory_uri()
]);
