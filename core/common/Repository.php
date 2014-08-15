<?php

namespace WPMVC\Common;

// Import namespaces
use \wpdb;

/**
 * Abstract repository class
 *
 * @package Repository
 * @version 1.0.0
 */
abstract class Repository
{

    /**
     * The wpdb instance
     *
     * @access public
     * @var string
     */
    protected $wpdb = false;

    /**
     * The associated post type
     *
     * @access protected
     * @var string
     */
    protected $postType = 'post';

    /**
     * The associated post type
     *
     * @access protected
     * @var string
     */
    protected $className = '\WPMVC\Models\Post';

    /**
     * Creates a new repository and inject the wpdb instance
     *
     * @access public
     * @param wpdb $wpdb
     * @return void
     */
    public function __construct(wpdb $wpdb)
    {
        $this->wpdb = $wpdb;
    }

    /**
     * Creates a new post
     *
     * @access public
     * @param array $postData           An array of post data that complies with wp_insert_post()
     * @param string $fileKey           (default: false) The ID of the $_FILES key to set as the featured image
     * @return int                      The ID of the post if the post is successfully added to the database. On failure, it returns 0 if $wp_error is set to false, or a WP_Error object if $wp_error is set to true.
     */
    public function add($postData, $fileKey = false)
    {
        // Set the post type
        $postData['post_type'] = $this->postType;
        // Insert the post and return the response
        $newPostId = wp_insert_post($postData);
        // If the new post was added successfully
        if ($newPostId && !is_wp_error($newPostId)) {
            // If the file is uploaded
            if (is_uploaded_file($_FILES[$fileKey]['tmp_name'])) {
                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                // Upload the file
                $attachmentId = media_handle_upload($fileKey, $newPostId);
                // If the attachment is valid
                if ($attachmentId && !is_wp_error($attachmentId)) {
                    // Set the post thumbnail
                    set_post_thumbnail($newPostId, $attachmentId);
                }
            }
        }
        return $newPostId;
    }

    /**
     * Updates an existing post
     *
     * @access public
     * @return int                      The ID of the post if the post is successfully updated in the database. Otherwise returns 0.
     */
    public function update($postData, $postId)
    {
        // Set the post ID
        $postData['ID'] = $postID;
        // Update the post
        return wp_update_post($postData);
    }

    /**
     * Deletes an existing post
     *
     * @access public
     * @return bool|WP_Post
     */
    public function delete($postId)
    {
        // Force delete the post
        return wp_delete_post($postId, true);
    }

    /**
     * Saves the meta value for the given post
     *
     * @access public
     * @return bool
     */
    public function updateMeta($postId, $key, $value)
    {
        if (function_exists('update_field')) {
            return update_field($key, $value, $postId);
        } else {
            return update_post_meta($postId, $key, $value);
        }
    }

    /**
     * Retrieve a single instance by a given post ID
     *
     * @access public
     * @return \WPMVC\Models\Post
     */
    public function findById($postId)
    {
        // Retrieve the post
        $thePost = get_post($postId);
        // Return the wrapped instance
        return new $this->className($thePost);
    }

}