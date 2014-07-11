<?php

namespace WPMVC\Helpers;

class Archive
{

    /**
     * Retrieve an array of year archives
     *
     * @access public
     * @static
     * @param array $args
     * @param string $args['post_type']
     * @param string $args['post_status']
     * @return array
     */
    public static function getYears($args)
    {
        global $wpdb;
        // Set default parameters
        $defaults = array(
            'post_type' => 'post',
            'post_status' => 'publish'
        );
        // Merge the arguments
        $args = array_merge($defaults, $args);
        // Create the years array
        $years = array();
        $sql = <<<EOD
SELECT
    YEAR(p.post_date) AS year
FROM %s p
WHERE
    p.post_type = '%s' AND
    p.post_status = '%s' AND
    p.post_date <= CURDATE()
GROUP BY
    YEAR(p.post_date),
    MONTH(p.post_date)
 ORDER BY p.post_date DESC
EOD;
        $esql = sprintf($sql,
            $wpdb->posts,
            $args['post_type'],
            $args['post_status']);
        $res = $wpdb->get_results($esql);
        if ($res && is_array($res)) {
            foreach ($res as $row) {
                array_push($years, $row->year);
            }
        }
        // Sort the years desc
        arsort($years);
        return $years;
    }

}