<?php

namespace WPMVC\Models;

// Import namespaces
use WP_Query;

/**
 * A basic WordPress post model
 *
 * @package WPMVC
 * @subpackage Model
 */
class Post
{

    /**
     * The transient timeout
     *
     * @access protected
     * @var int
     */
    protected $transientTimeout = 1;

    /**
     * A WP_Post object instance
     *
     * @access public
     * @var WP_Post
     */
    public $post = false;

    /**
     * An array of post meta key and values
     *
     * @access protected
     * @var array
     */
    protected $metaData = false;

    /**
     * An array of post terms
     *
     * @access protected
     * @var array
     */
    protected $terms = array();

    /**
     * A WP_Post object instance representing the next post object
     *
     * @access protected
     * @var WP_Post
     */
    protected $nextPost = false;

    /**
     * A WP_Post object instance representing the previous post object
     *
     * @access protected
     * @var WP_Post
     */
    protected $prevPost = false;

    /**
     * Query for posts and returns an array of the model
     *
     * @access public
     * @static
     * @return array|bool
     */
    public static function queryPosts($args)
    {
        $query = new WP_Query($args);
        // Set default return to false
        $posts = false;
        // Iterate and build the array of instances instances
        if ($query->have_posts()) {
            global $post;
            // Create the array
            $posts = array();
            while ($query->have_posts()) {
                $query->the_post();
                $thePost = static::create($post);
                array_push($posts, $thePost);
            }
        }
        return $posts;
    }

    /**
     * Creatrs a new instance by the post ID
     *
     * @access public
     * @param int $postId
     * @return static
     */
    public static function createById($postId)
    {
        $post = get_post(intval($post));
        return new static($post);
    }

    /**
     * Creatrs a new instance by the post ID
     *
     * @access public
     * @param array|int|WP_Post $postId
     * @return static
     */
    public static function create($post)
    {
        // If numeric, create the post
        if (is_numeric($post)) {
            // Retrieve the transient
            $transientKey = sprintf('WPMVC\Models\Post(%d)', $post);
            $storedData = get_transient($transientKey);
            // If the transient doesn't yet exist, query for it!
            if ($storedData === false) {
                // Retrieve the post object
                $post = get_post(intval($post));
                // Store the transient
                set_transient($transientKey, $post, $this->transientTimeout);
            } else { $post = $storedData; }
        } elseif (is_array($post)) {
            // Convert array into object
            $post = (object) $post;
        }
        return new static($post);
    }

    /**
     * Creates the post model
     *
     * Also loads the custom post meta data
     *
     * @constructor
     * @access public
     * @param WP_Post               A WP_Post instance
     */
    public function __construct($post)
    {
        // If an object is given
        if (is_object($post)) {
            $this->post = $post;
            // Load meta data
            $this->loadMetaData();
        }
    }

    /**
     * Loads the post's meta data
     *
     * @access protected
     * @return void
     */
    protected function loadMetaData()
    {
        // Retrieve the transient
        $transientKey = sprintf('WPMVC\Models\Post(%d)::loadMetaData', $this->id());
        $metaData = get_transient($transientKey);
        // If it doesn't exist
        if (!$metaData) {
            $keys = get_post_custom($this->id());
            $metaData = array();
            if (count($keys)) {
                foreach ($keys as $key=>$value) {
                    // If only 1 value, it's a string
                    if (is_array($value) && count($value) == 1) { $metaData[$key] = $value[0]; }
                    else { $metaData[$key] = $value; }
                }
            }
            set_transient($transientKey, $metaData, $this->transientTimeout);
        }
        $this->metaData = $metaData;
    }

    /**
     * Checks if the post has the given key
     *
     * @access public
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return (isset($this->post->$key)) ? true : false;
    }

    /**
     * Retrieves a post value
     *
     * @access public
     * @return mixed|bool
     */
    public function get($key)
    {
        return $this->has($key) ? $this->post->$key : false;
    }

    /**
     * Checks if the post has a meta key
     *
     * @access public
     * @return bool 
     */
    public function hasMeta($option)
    {
        return (isset($this->metaData[$option])) ? $this->metaData[$option] : false;
    }

    /**
     * Retrieves a meta data value
     */
    public function getMeta($option)
    {
        return $this->hasMeta($option) ? $this->metaData[$option] : false;
    }

    /**
     * Retrieve the post's ID
     *
     * @access public
     * @return int
     */
    public function id()
    {
        return $this->has('ID') ? $this->post->ID : 0;
    }

    /**
     * Retrieve the post's type
     *
     * @access public
     * @return string
     */
    public function type()
    {
        return $this->has('post_type') ? $this->post->post_type : '';
    }

    /**
     * Retrieve the post's title
     *
     * @access public
     * @return string
     */
    public function title()
    {
        return $this->has('ID') ? get_the_title($this->id()) : '';
    }

    /**
     * Retrieve the post's permalink
     *
     * @access public
     * @return string
     */
    public function permalink()
    {
        return $this->has('ID') ? get_permalink($this->post) : '';
    }

    /**
     * Retrieve the post's slug
     *
     * @access public
     * @return string
     */
    public function slug()
    {
        return $this->has('post_name') ? $this->post->post_name : '';
    }

    /**
     * Retrieve the post's content
     *
     * Note: Temporarily sets the global $post object to the event post and resets it
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/get_the_title
     * @global WP_Post $post
     * @param string $moreLinkText (default: null)
     * @param string $stripTeaser boolean (default: false)
     * @return string
     */
    public function content($moreLinkText = null, $stripTeaser = false)
    {
        global $post;
        $_p = $post;
        $post = $this->post;
        setup_postdata($post);
        $content = get_the_content($moreLinkText, $stripTeaser);
        // Apply filter
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        $post = $_p;
        if ($_p) {
            setup_postdata($post);
        }
        return $content;
    }

    /**
     * Retrieve the post's excerpt
     *
     * Note: Temporarily sets the global $post object to the event post and resets it
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/get_the_excerpt
     * @global WP_Post $post
     * @param int $wordCount (default: 20)
     * @param string $delimiter (default: '...')
     * @return string
     */
    public function excerpt($wordCount = 20, $delimiter = '...')
    {
        global $post;
        // Store current post into temp variable
        $_p = $post;
        $post = $this->post;

        // Do the magic!
        $limit = $wordCount + 1;
        $full_excerpt = get_the_excerpt();
        $full_excerpt_count = count(explode(' ', $full_excerpt)); /* Correct Word Count */
        $new_excerpt = explode(' ', $full_excerpt, $limit);

        if ($full_excerpt_count <= $wordCount) { $delimiter = ''; }
        else { array_pop($new_excerpt); }
        $new_excerpt = implode(" ",$new_excerpt) . $delimiter;

        // Restore post
        $post = $_p;

        return $new_excerpt;
    }

    /**
     * Retrieve the post's excerpt (improved version with tags)
     *
     * Note: Temporarily sets the global $post object to the event post and resets it
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/get_the_excerpt
     * @global WP_Post $post
     * @param int $wordCount (default: 20)
     * @param bool $moreLinkText (default: true)
     * @return string
     */
    public function improvedExcerpt($wordCount = 20, $moreLinkText = true)
    {
        $text = '';
        if ($this->id()) {
            $text = $this->post->post_content;
            $text = apply_filters('the_content', $text);
            $text = str_replace('\]\]\>', ']]&gt;', $text);

            // Allow <a> and <p> as well as formatting and list items
            $text = strip_tags($text, '<a><p><i><em><strong><b><ul><ol><li>');
            $words_array = explode(' ', $text, $wordCount + 1);
            if (count($words_array) > $wordCount) {
                array_pop($words_array);
                if ($moreLinkText) array_push($words_array, '<br /><a href="'. $this->permalink() . '">read more</a>');
                $words_array[0] = str_replace('<p>', '', $words_array[0]); // remove <p> tag at beginning
                $text = implode(' ', $words_array);
            }
        }
        return $text;
    }
    
    /**
     * Retrieves the taxonomy terms for the post
     *
     * @access public
     * @return object
     */
    public function getTerms($taxonomy)
    {
        // If terms isn't set
        if (!$this->terms) {
            // Retrieve the transient value
            $transientKey = sprintf('WPMVC\Models\Post::getTerms(%s)', $this->id(), $taxonomy);
            $terms = get_transient($transientKey);
            // If the terms isn't found in the cache
            if (!$terms) {
                // Query for the terms
                $args = array(
                    'orderby' => 'name', 
                    'order' => 'ASC'
                );
                // Retrieve and set the terms
                $terms = wp_get_post_terms($this->id(), $taxonomy, $args);
                // Store into the cache
                set_transient($transientKey, $terms, $this->transientTimeout);
            }
            // Set the terms
            $this->terms[$taxonomy] = $terms;
        }
        return $this->terms[$taxonomy];
    }

    /**
     * Retrieve the post's categories in a list format
     *
     * @access public
     * @return string
     */
    public function theCategories()
    {
        return get_the_category_list();
    }
    
    /**
     * Retrieve the post's categories in a list format
     *
     * @access public
     * @return string
     */
    public function theTags($before = '', $sep = '', $after = '')
    {
        return get_the_tag_list($before, $sep, $after);
    }
    
    /**
     * Checks if the post has a thumbnail
     *
     * @access public
     * @return bool
     */
    public function hasThumbnail()
    {
        return $this->has('ID') ? has_post_thumbnail($this->id()) : false;
    }

    /**
     * Retrieve the post's thumbnail
     *
     * @access public
     * @global object $post
     * @param string $size (default: "full")
     * @return string
     */
    public function thumbnail($size = 'full')
    {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($this->id()), $size);
        return isset($thumb[0]) ? $thumb[0] : '';
    }

    /**
     * Retrieve the post's time
     *
     * @access public
     * @param string $format (default: 'm.d.y')
     * @return string
     */
    public function postTime($format = 'm.d.y')
    {
        return $this->has('ID') ? get_the_time($format, $this->id()) : '';
    }

    /**
     * Loads the adjacent posts
     *
     * @access public
     * @param string $direction (default: null)
     * @return void
     */
    protected function loadAdjacentPost($direction = null)
    {
        $loadPrev = $loadNext = false;
        switch ($direction) {
            case 'prev':
                $loadPrev = true;
                break;
            case 'next':
                $loadNext = true;
                break;
            default:
                $loadPrev = $loadNext = true;
                break;
        }
        if ($loadPrev && !$this->prevPost) {
            $this->prevPost = $this->getAdjacentPost('prev', $this->get('post_type'));
        }
        if ($loadNext && !$this->nextPost) {
            $this->nextPost = $this->getAdjacentPost('next', $this->get('post_type'));
        }
    }

    /*
     * Replacement for get_adjacent_post()
     *
     * This supports only the custom post types you identify and does not
     * look at categories anymore. This allows you to go from one custom post type
     * to another which was not possible with the default get_adjacent_post().
     * Orig: wp-includes/link-template.php 
     *
     * @access public
     * @param string $direction: Can be either 'prev' or 'next'
     * @param multi $post_types: Can be a string or an array of strings
     * @return object
     */
    public function getAdjacentPost($direction = 'prev', $post_types = 'post')
    {
        global $wpdb;
        if ($this->has('ID')) {
            if (!$post_types) return NULL;
            if (is_array($post_types)) {
                $txt = '';
                for ($i = 0; $i <= count($post_types) - 1; $i++){
                    $txt .= "'".$post_types[$i]."'";
                    if ($i != count($post_types) - 1) $txt .= ', ';
                }
                $post_types = $txt;
            } else {
                $post_types = "'" . $post_types . "'";
            }
            $current_post_date = $this->get('post_date');
            $join = '';
            $in_same_cat = FALSE;
            $excluded_categories = '';
            $adjacent = $direction == 'prev' ? 'previous' : 'next';
            $op = $direction == 'prev' ? '<' : '>';
            $order = $direction == 'prev' ? 'DESC' : 'ASC';
            $join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
            $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type IN({$post_types}) AND p.post_status = 'publish'", $current_post_date), $in_same_cat, $excluded_categories );
            $sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );
            $query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
            $query_key = 'adjacent_post_' . md5($query);
            $result = wp_cache_get($query_key, 'counts');
            if ( false !== $result ) { return $result; }
            $esql = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
            $result = $wpdb->get_row($esql);
            if ( null === $result )
                $result = false;
            return $result;
        }
        return false;
    }

    /**
     * Checks if the next post is available
     *
     * @access public
     * @return bool
     */
    public function hasNextPost()
    {
        return ($this->nextPost && $this->nextPost->ID !== $this->get('ID')) ? true : false;
    }
    /**
     * Checks if the next post is available
     *
     * @access public
     * @return bool
     */
    public function hasPrevPost()
    {
        return ($this->prevPost && $this->prevPost->ID !== $this->get('ID')) ? true : false;
    }

    /**
     * Retrieves the next post link
     *
     * @access public
     * @return object
     */
    public function getNextPost()
    {
        return $this->hasNextPost() ? get_permalink($this->nextPost) : '';
    }

    /**
     * Retrieves the previous post link
     *
     * @access public
     * @return object
     */
    public function getPrevPost()
    {
        return $this->hasPrevPost() ? get_permalink($this->prevPost) : '';
    }

    /**
     * Retrieves the previous post slug
     *
     * @access public
     * @return object
     */
    public function getPrevSlug()
    {
        return $this->hasPrevPost() ? $this->prevPost->post_name : '';
    }

    /**
     * Retrieves the next post slug
     *
     * @access public
     * @return object
     */
    public function getNextSlug()
    {
        return $this->hasNextPost() ? $this->nextPost->post_name : '';
    }

    /**
     * Retrieves the previous post title
     *
     * @access public
     * @return object
     */
    public function getPrevTitle()
    {
        return $this->hasPrevPost() ? $this->prevPost->post_title : '';
    }

    /**
     * Retrieves the next post title
     *
     * @access public
     * @return object
     */
    public function getNextTitle()
    {
        return $this->hasPrevPost() ? $this->nextPost->post_title : '';
    }

}