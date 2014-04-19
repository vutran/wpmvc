<?php

// Import namespaces
use WPMVC\Common\Bootstrap;

// Composer autoload
if (file_exists(STYLESHEETPATH . '/vendor/autoload.php')) {
    require_once(STYLESHEETPATH . '/vendor/autoload.php');
}

// Instantiate the WPMVC
$app = new Bootstrap(TEMPLATEPATH);

// Start!
$app->init();