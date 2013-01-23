<?php
/**
 * bck_post_relationship will create a relationship
 * between two posts.  This can be one ore 
 * two way relationship.
 *
 * @author Jeff Clark 1010 Collective
 */

class bck_relationship {
   
    
    public function __construct() {
        //add_action('publish_nirec_videos', array($this, 'rel_create_relationship'));
    }
    
    
    
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

        $rel_relationship = $wpdb->get_results("SELECT * FROM $rel_post_title_table Where post_parent_id = $parent_id");

        if (!$rel_relationship) {

            try {
                $wpdb->insert($rel_table, array(
                    'relationship_type' => $rel_type,
                    'post_parent_id' => $rel_parent_id,
                    'child_id' => $rel_child_id,
                    'post_type' => $rel_post_type
                ));

                $this->rel_get_relationship_id($rel_parent_id);

            } catch (Exception $e) {
                echo 'relationship creation failed';
            }
        } 
    }
    
    

    public function rel_get_relationship($rel_post_type, $rel_post_id) {
        //delete the relationship
        global $wpdb;
        $rel_post_title_table = $wpdb->prefix . 'posts';
        $rel_table = $wpdb->prefix . 'post_relationship';
        
        $myrows = $wpdb->get_results( "SELECT child_id FROM $rel_table WHERE post_parent_id = $rel_id" );
        
    }
    
    
    
    
    public function rel_get_relationship_id($rel_id) {
        global $wpdb;
        
        $rel_table = $wpdb->prefix . 'post_relationship';
        $rel_post_table = $wpdb->prefix . 'posts';
        
        $relationship_img = RELATIONSHIP_BASE_URL . 'includes/images/one_way_icon.png';
        $delete_img = RELATIONSHIP_BASE_URL . 'includes/images/delete-icon.png';
        
        $rel_id = $rel_id;
        
        $myrows = $wpdb->get_results( "SELECT * FROM $rel_table WHERE post_parent_id = $rel_id order by post_type" );
        foreach($myrows as $rows){
            
            $rel = $wpdb->get_results("SELECT * FROM $rel_post_table Where ID = $rows->child_id");
            
            $last_type;
            foreach ($rel as $rel) {

                echo '<div class="relation">';
                if ($last_type != $rel->post_type) {
                    echo '<div class="rel-title">' . $rel->post_type . '</div>';
                    $last_type = $rel->post_type;
                }
                echo '<img src="' . $relationship_img . '" />';
                echo $rel->post_title;
                echo '<span id="delete-rel" value="' . $rel->ID . '">';
                echo '<img src="' . $delete_img . '">';
                echo '</span>';
                echo '</div>';
            }
        }
       
        
    }

}