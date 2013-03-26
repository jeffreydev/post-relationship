<?php
/**
 *  
 */
class rel_filter {
    
    static $hook_to_run;
    
    public function __construct() {
        add_filter("jdev_relationship", array($this, 'jdev_get_the_relationship') );
    }
    
    
    /**
     * Get any posts that have a 
     * current relationship with given post
     * and post type
     * 
     * 
     * @global type $post
     * @param type $post_type
     * @param type $hooked
     * @return type 
     */
    static function jdev_get_related_posts($post_type, $output = null, $canned = true ) {
        global $post;
        
        $rel_post_type = $post_type; // post type for query        
        self::$hook_to_run = $output;

        
        //gets back post ides associated with the post type selected
        $rel = rel_get_post_id($post->ID, $rel_post_type);

        // If nothing is returned, return
        if (!$rel)
            return;

        $args = array(
            'post_type' => $rel_post_type,
            'post__in' => $rel,
            'showposts' => -1
        );

        $loop = new WP_Query($args);

        $html = null;

        while ($loop->have_posts()) : $loop->the_post();

            if (has_filter('jdev_relationship') && $canned === true) {
                $html = apply_filters('jdev_relationship', $html);
            }
            
            if (has_filter('jdev_new_relationship_output') && $canned === false) {
                $html = apply_filters('jdev_new_relationship_output', $html);
            }
            
        endwhile;

        return $html;

        wp_reset_query();
    }
    
    
    
    
    /**
     * Based on the hook selected, different
     * outputs are availiable.
     * 
     * @global type $post
     * @param type $html
     * @return string 
     */
    static function jdev_get_the_relationship($html) {

        global $post;
        switch (self::$hook_to_run) {

            case "1" :
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
                $thumbnail = $thumbnail['0'];
                $html .= '<div class="rel-wrap">';
                $html .= '<a href="' . get_permalink() . '" >';
                $html .= '<div id="rel-image"><img src="' . $thumbnail . '" alt="post_thumbnail" /></div>';
                $html .= '</a>';
                $html .= '</div>';

                return $html;
                break;

            case "2" :
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
                $thumbnail = $thumbnail['0'];
                $html .= '<div class="rel-wrap">';
                $html .= '<a href="' . get_permalink() . '" >';
                $html .= '<div id="rel-image"><img src="' . $thumbnail . '" alt="post_thumbnail" /></div>';
                $html .= '</a>';
                $html .= '<a href="' . get_permalink() . '" >';
                $html .= '<span id="title">' . get_the_title() . '</span></a>';
                $html .= '</div>';

                return $html;
                break;
            
            case "3" :
                $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
                $thumbnail = $thumbnail['0'];
                $html .= '<div class="rel-wrap">';
                $html .= '<a href="' . get_permalink() . '" >';
                $html .= '<div id="rel-image"><img src="' . $thumbnail . '" alt="post_thumbnail" /></div>';
                $html .= '</a>';
                $html .= '<a href="' . get_permalink() . '" >';
                $html .= '<span id="title">' . get_the_title() . '</span></a>';
                $html .= '<div class="rel-excerpt">' . get_the_excerpt() . '</div>';
                $html .= '</div>';

                return $html;
                break;
            
            default :
                $html .= '<li>';
                $html .= '<a href="' . get_permalink() . '">';
                $html .= get_the_title();
                $html .= '</a>';
                $html .= '</li>';
                
                return $html;
                break;
        }
    }
        
    
}


// Init rel_filter()
new rel_filter();



