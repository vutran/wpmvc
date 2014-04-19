<?php

// Import namespaces
use WPMVC\Models\View;

// Create a new view and set the default path as the current path
$theHeader = new View(dirname(__FILE__) . '/core/views/');
$theBody = new View(dirname(__FILE__) . '/app/views/');
$theFooter = new View(dirname(__FILE__) . '/core/views/');

$isAjax = (isset($_GET['ajax']) && $_GET['ajax']);

$theHeader->setFile('header');

// If request for homepage
if (is_home()) {
    $theBody->setFile('home');
} else {
    // Retrieve the current requested post type (applies to pages, and post single and archive views)
    $postType = get_post_type();
    if (is_404()) {
        // 404 view
        $theBody->setFile('404');
    } elseif (is_search()) {
        $theBody->setFile('search/index');
    } elseif (is_tax()) {
        $taxonomy = get_query_var('taxonomy');
        $theBody->setFile(sprintf('taxonomy/%s/index', $taxonomy));
    } elseif (is_tag()) {
        $theBody->setFile('views/tag/index');
    } elseif (is_post_type_archive()) {
        // Archive view
        $theBody->setFile(sprintf('%s/index', $postType));
    } elseif (is_single()) {
        // Permalink view
        $theBody->setFile(sprintf('%s/single', $postType));
    } elseif (is_page()) {
        global $pagename;
        // Page view
        $theBody->setFile($pagename);
    }
}

$theFooter->setFile('footer');

echo $theHeader->output();
echo $theBody->output();
echo $theFooter->output();