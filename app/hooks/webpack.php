<?php

add_action( 'wp_footer', 'app_add_webpack_bundle' );

/**
 * Adds the webpack bundle file
 *
 * @access public
 * @return void
 */
function app_add_webpack_bundle()
{
    printf(
        '<script type="text/javascript" src="%s/bundle.js"></script>',
        'http://dockerhost:4000'
        // get_stylesheet_directory_uri() // production
    );
}
