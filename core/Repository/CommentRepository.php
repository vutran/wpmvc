<?php

namespace WPMVC\Repository;

// Import namespaces
use \WPMVC\Common\Model,
    \WPMVC\Models\Comment;

/**
 * Comment repository
 */
class CommentRepository extends Model
{

    /**
     * The associated post type
     *
     * @access protected
     * @var string
     */
    protected $className = '\WPMVC\Models\Comment';

    /**
     * Converts an array of standard object instances to Comment model instances
     *
     * @access protected
     * @param array $comments
     * @return array
     */
    protected function convertAndReturn($comments)
    {
        // Create the return value
        $retval = false;
        if ($comments && is_array($comments) && count($comments)) {
            // Create the array
            $retval = array();
            foreach ($comments as $theComment) {
                // Create a new instance
                $commentInstance = new $this->className($theComment);
                // Append the instance to the return value
                array_push($retval, $commentInstance);
            }
        }
        return $retval;
    }

    /**
     * Find all comments by a given post ID
     *
     * @access public
     * @param int $postId
     * @link http://codex.wordpress.org/Function_Reference/get_comments
     * @return array
     */
    public function findByPostId($postId)
    {
        $args = array(
            'post_id' => $postId,
            'parent' => 0
        );
        $comments = get_comments($args);
        // Convert and return the comments
        return $this->convertAndReturn($comments);
    }

    /**
     * Find all comments by a given post ID
     *
     * @access public
     * @param int $commentId
     * @link http://codex.wordpress.org/Function_Reference/get_comments
     * @return array
     */
    public function findByParentId($commentId)
    {
        $args = array(
            'parent' => $commentId
        );
        $comments = get_comments($args);
        // Convert and return the comments
        return $this->convertAndReturn($comments);
    }

}