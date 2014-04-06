<?php

namespace WPMVC\Models;

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
     * @access protected
     * @var WP_Post
     */
    protected $post = false;

    /**
     * An array of post meta key and values
     *
     * @access protected
     * @var √
     */
    protected $metaData = false;

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
     * Creates the post model
     *
     * Also loads the custom post meta data
     *
     * @constructor
     * @access public
     * @param int|object|array|WP_Post    A post ID or a post object or WP_Post instance
     */
    public function __construct($post)
    {
        // If a number is given
        if (is_numeric($post)) {
            // Retrieve the transient
            $transientKey = sprintf('WPMVC_Post_%d', $post);
            $storedData = get_transient($transientKey);
            // If the transient doesn't yet exist, query for it!
            if ($storedData === false) {
                $post = get_post(intval($post));
                set_transient($transientKey, $post, $this->transientTimeout);
            } else { $post = $storedData; }
        }
        // If an array is given
        if (is_array($post)) {
            $this->post = (object) $post;
        }
        // If an object is given
        if (is_object($post)) {
            $this->post = $post;
            // Load meta data
            $this->loadMetaData();
            // Set the adjacent post    
            $this->nextPost = $this->getAdjacentPost('next', $this->get('post_type'));
            $this->prevPost = $this->getAdjacentPost('prev', $this->get('post_type'));
        }
    }

    /**
     * Loads the post's meta data
     *
     * @access public
     * @return void
     */
    public function loadMetaData()
    {
        // Retrieve the transient
        $transientKey = sprintf('WPMVC_Post_%d_loadMetaData', $this->id());
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
     * @global object $post
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
        $content = get_the_content();
        // Apply filter
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        $post = $_p;
        setup_postdata($post);
        return $content;
    }

    /**
     * Retrieve the post's excerpt
     *
     * Note: Temporarily sets the global $post object to the event post and resets it
     *
     * @access public
     * @link http://codex.wordpress.org/Function_Reference/get_the_excerpt
     * @global object $post
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
     * @global object $post
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
     * Retrieve the post's categories
     *
     * @access public
     * @return string
     */
    public function theCategories()
    {
        return get_the_category_list();
    }
    
    /**
     * Retrieve the post's categories
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
    
    /**
     * Retrieves the taxonomy terms
     *
     * @access public
     * @return object
     */
    public static function getTerms($taxonomy)
    {
        $args = array(
            'orderby'       => 'name', 
            'order'         => 'ASC'
        );
        $terms = get_terms($taxonomy, $args);
        return $terms;
    }
    
    /**
     * Retrieves the taxonomy terms associated with the current post
     *
     * @access public
     * @return string
     */
    public static function getAssociatedTerms($postId, $taxonomy)
    {
        $args = array(
            'orderby'       => 'name', 
            'order'         => 'ASC'
        );
        $termsArray = array();
        $terms = wp_get_post_terms($postId, $taxonomy, $args);
        if ($terms && is_array($terms) && count($terms)) {
            foreach ($terms as $term){
                array_push($termsArray, $term->slug);
            }
        }
        return implode(' ', $termsArray);
    }

}

?>