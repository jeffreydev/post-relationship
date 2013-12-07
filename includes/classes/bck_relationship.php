<?php
/**
 * bck_post_relationship will create a relationship
 * between two posts.  This can be one ore 
 * two way relationship.
 *
 * @author Jeff Clark 1010 Collective
 * 
 */

class bck_relationship {
   
    
    public function __construct() {
        add_shortcode( 'get_relationship', array( $this, 'jdev_get_related_posts' ) );
    }
    
    
    /**
     * Create the relationship between
     * to post types.
     * 
     * @global type $wpdb
     * @global type $post
     * @param type $rel
     * @param type $rel_parent_id
     * @param type $rel_child_id
     * @param type $rel_post_type 
     */
    public function rel_create_relationship( $rel, $rel_parent_id, $rel_child_id, $rel_post_type) {
        // create the post relationship
        global $wpdb, $post;
        
        $rel_post_title_table = $wpdb->prefix . 'posts';
        $rel_table = $wpdb->prefix . 'post_relationship';
        
        $rel_type = $rel;
        $rel_child_id = $rel_child_id; // will be post request
        $rel_parent_id = $rel_parent_id;
        $rel_post_type = $rel_post_type;

        $relationship_img = RELATIONSHIP_BASE_URL . 'includes/images/one_way_icon.png';
        $delete_img = RELATIONSHIP_BASE_URL . 'includes/images/delete-icon.png';
        
        $rel_check = $wpdb->get_row("SELECT * FROM $rel_table WHERE post_parent_id = $rel_parent_id && child_id = $rel_child_id");

        if(!$rel_check) {
            $wpdb->insert($rel_table, array(
                'relationship_type' => $rel_type,
                'post_parent_id' => $rel_parent_id,
                'child_id' => $rel_child_id,
                'post_type' => $rel_post_type
            ));
        }

        $this->rel_get_relationship_id($rel_parent_id);


        
    }
    
    
    /**
     * Get any relationship for a given post type
     * and post id
     * 
     * @global type $wpdb
     * @param type $rel_post_type
     * @param type $rel_post_id 
     */
    public function rel_get_relationship($rel_post_type, $rel_post_id) {
        //delete the relationship
        global $wpdb;
        $rel_post_title_table = $wpdb->prefix . 'posts';
        $rel_table = $wpdb->prefix . 'post_relationship';
        
        $myrows = $wpdb->get_results( "SELECT child_id FROM $rel_table WHERE post_parent_id = $rel_id" );
        
    }
    
    
    
    /**
     * Get the relationshop ID
     * 
     * @global type $wpdb
     * @param type $rel_id 
     */
    public function rel_get_relationship_id($rel_id) {
        global $wpdb;
        
        $rel_table = $wpdb->prefix . 'post_relationship';
        $rel_post_table = $wpdb->prefix . 'posts';

        $delete_img = RELATIONSHIP_BASE_URL . 'includes/images/delete-icon.png';
        
        $rel_id = $rel_id;
        $last_type = null;
        
        $myrows = $wpdb->get_results( "SELECT * FROM $rel_table WHERE post_parent_id = $rel_id order by post_type" );
        $count = 2;
        echo '<div class="rel-title">Authors</div>';
        foreach($myrows as $rows){
            
            //$rel = $wpdb->get_results("SELECT * FROM $rel_post_table Where ID = $rows->child_id");
            
//            foreach ($rel as $rel) {
//                echo '<div class="relation">';
//                if ($last_type != $rel->post_type) {
//                    echo '<div class="rel-title">' . $rel->post_type . '</div>';                 
//                }
//
//                if($new_post_type) : echo '<div class="rel-title">' . $rel->post_type . '</div>'; endif; 
//                echo '<img src="' . $relationship_img . '" />';
//                echo $rel->post_title;
//                echo '<span id="delete-rel" value="' . $rel->ID . '">';
//                echo '<img src="' . $delete_img . '">';
//                echo '</span>';
//                echo '</div>';
//                
//       
//                $last_type = $rel->post_type;
//            }
            
            // If we have authors
            $auth = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE ID = $rows->child_id");
            
            foreach ($auth as $auth) {
                echo '<div class="relation">';
                
                echo '&rarr; &nbsp; ';
                echo $auth->user_nicename;
                echo '<span id="delete-rel" value="' . $auth->ID . '">';
                echo '<img src="' . $delete_img . '">';
                echo '</span>';
                echo '</div>';
            }
        }
         
    }
    
    
    
    
    
    static function get_parent_id($post_id, $post_type) {
        global $wpdb;
        $child_id = $post_id;
        $post_type = $post_type;
        $rel_post_title_table = $wpdb->prefix . 'posts';
        $rel_table = $wpdb->prefix . 'post_relationship';
        
        $parent_id = $wpdb->get_row( "SELECT post_parent_id FROM $rel_table WHERE child_id = $child_id && post_type = '$post_type'" );
        
        if($parent_id) {
            foreach($parent_id as $parent_id) {
                return $parent_id[0];
            }
        }
    }
    
    
    
    /*
     * Get post related to current
     * post by.
     * 
     * @filter type bck_relationship
     * @param type $post_type
     */
    public function jdev_get_related_posts($atts, $content = null) {

        global $post;
        
        ob_start();
        
        extract(shortcode_atts(array(
                    'post_type' => 'post_type',
                        ), $atts));
        
        // post type for query
        $rel_post_type = $post_type;
 
        //gets back post ides associated with the post type selected
        $rel = rel_get_post_id($post->ID, $rel_post_type);

        // If nothing is returned, return
        if (!$rel)
            return;
        
        // set html = nothing
        $html = '';
        
        // Our loop args
        $args = array(
            'post_type' => $rel_post_type,
            'post__in' => $rel,
            'showposts' => -1
        );

        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post();
            
            // If there is a filter fun the filter
            // If not filter then default is link and title
            if (has_filter('post_relationship')) {
                $html .= apply_filters('post_relationship', $html);
            } else {              
                $html .= '<div>';
                $html .= '<a href="'.get_permalink().'">' . get_the_title() . '</a>';
                $html .= '</div>';
            }
            
        endwhile;
        
        // Reset the query
        wp_reset_query();
        
        // Clean our object
        $output = ob_get_clean();
        
        return $html . $output;
        
        
    }

}

// initialize for shortcode
new bck_relationship();