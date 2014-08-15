<?php

namespace WPMVC\Models;

/**
 * A basic WordPress comment model
 *
 * @package WPMVC
 * @subpackage Model
 */
class Comment
{

    /**
     * The transient timeout
     *
     * @access protected
     * @var int
     */
    protected $transientTimeout = 1;

    /**
     * A comment standard object instance
     *
     * @access public
     * @var WP_Comment
     */
    public $comment = false;

    /**
     * Creates a new instance by the comment ID
     *
     * @access public
     * @param array|int $comment
     * @return static
     */
    public static function create($comment)
    {
        // If numeric, create the comment
        if (is_numeric($comment)) {
            // Retrieve the transient
            $transientKey = sprintf('WPMVC\Models\Comment(%d)', $comment);
            $storedData = get_transient($transientKey);
            // If the transient doesn't yet exist, query for it!
            if ($storedData === false) {
                // Retrieve the comment object
                $comment = get_comment(intval($comment));
                // Store the transient
                set_transient($transientKey, $comment, $this->transientTimeout);
            } else {
                $comment = $storedData;
            }
        } elseif (is_array($comment)) {
            // Convert array into object
            $comment = (object) $comment;
        }
        return new static($comment);
    }

    /**
     * Creates the comment model
     *
     * @constructor
     * @access public
     * @param WP_Post               A comment standard object instance
     */
    public function __construct($comment)
    {
        // If an object is given
        if (is_object($comment)) {
            $this->comment = $comment;
        }
    }

    /**
     * Retrieve the comment ID
     *
     * @access public
     * @return int
     */
    public function id()
    {
        return $this->comment->comment_ID;
    }

    /**
     * Retrieve the comment's post ID
     *
     * @access public
     * @return int
     */
    public function postId()
    {
        return $this->comment->comment_post_ID;
    }

    /**
     * Retrieve the author's username
     *
     * @access public
     * @return string
     */
    public function author()
    {
        return $this->comment->comment_author;
    }

    /**
     * Retrieve the author's display name
     *
     * @access public
     * @return string
     */
    public function authorName()
    {
        $user = get_userdata( $this->comment->user_id );
        return $user->display_name;
    }


    /**
     * Retrieve the author's email
     *
     * @access public
     * @return string
     */
    public function authorEmail()
    {
        return $this->comment->comment_author_email;
    }

    /**
     * Retrieve the comment's date of creation
     *
     * @access public
     * @return string
     */
    public function date()
    {
        return $this->comment->comment_date;
    }

    /**
     * Retrieve the comment's date of creation (GMT)
     *
     * @access public
     * @return string
     */
    public function dateGmt()
    {
        return $this->comment->comment_date_gmt;
    }

    /**
     * Retrieve the comment's date of creation (unix timestamp)
     *
     * @access public
     * @return int
     */
    public function timestamp()
    {
        return strtotime($this->comment->comment_date);
    }

    /**
     * Retrieve the comment's date of creation (unix timestamp; GMT)
     *
     * @access public
     * @return int
     */
    public function timestampGmt()
    {
        return strtotime($this->comment->comment_date_gmt);
    }

    /**
     * Retrieve the comment ID
     *
     * @access public
     * @return string
     */
    public function content()
    {
        return $this->comment->comment_content;
    }

    /**
     * Retrieve the commenter's user ID
     *
     * @access public
     * @return int
     */
    public function userId()
    {
        return $this->comment->user_id;
    }

    /**
     * Retrieve the commenter's user avatar
     *
     * @access public
     * @return int
     */
    public function userAvatar()
    {
        $author = $this->comment->user_id;
        return get_avatar($author, 90);
    }

    /**
     * Retrieve the commenter's profile URL (Requires BuddyPress)
     *
     * @access public
     * @return string
     */
    public function userProfileURL()
    {
        if (function_exists('bp_core_get_userlink')) {
            return bp_core_get_userlink($this->comment->user_id, false, true);
        }
        return '';
    }

    /**
     * Retrieves the comment class
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/comment_class
     * @return string
     */
    public function cssClasses()
    {
        return comment_class('', $this->id(), $this->postId(), false);
    }

    /**
     * Retrieve the comment editing link
     *
     * @access public
     * @link https://core.trac.wordpress.org/browser/tags/3.9.1/src/wp-includes/link-template.php#L1296
     * @param string $link Optional. Anchor text.
     * @param string $before Optional. Display before edit link.
     * @param string $after Optional. Display after edit link.
     * @return string
     */
    public function editComment($link = null, $before = '', $after = '')
    {
        // If the user cannot comment on this comment
        if (!current_user_can('edit_comment', $this->id())) {
            return;
        }
        // If the link is empty
        if (null === $link) {
            $link = __('Edit This');
        }
        // Retrieve the comment link
        $link = '<a class="comment-edit-link" href="' . get_edit_comment_link( $this->id() ) . '">' . $link . '</a>';
        return $before . apply_filters('edit_comment_link', $link, $this->id()) . $after;
    }

    /**
     * Retrive the comment reply link
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/comment_reply_link
     * @param string $depth (default: 0)
     * @return string
     */
    public function replyComment($depth = 0)
    {
        $args = array(
            'depth' => $depth,
            'max_depth' => 3
        );
        return get_comment_reply_link($args, $this->id(), $this->postId());
    }

    /**
     * Checks if the comment is approved
     *
     * @access public
     * @return bool
     */
    public function isApproved()
    {
        return wp_get_comment_status($this->id());
    }

}