<?php

add_action( 'wp_enqueue_scripts', 'app_add_webpack_bundle' );

/**
 * Adds the webpack bundle file
 *
 * @access public
 * @return void
 */
function app_add_webpack_bundle()
{
    printf(
        '<script type="text/javascript" src="%s/dist/bundle.js"></script>',
        get_stylesheet_directory_uri()
    );
}
