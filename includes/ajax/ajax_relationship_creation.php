<?php

add_action( 'wp_ajax_ajax_get_post_types', 'rel_get_post_types' );

function rel_get_post_types() {;
        echo '<label>Select Option For Relationship</label>';
        echo '<select name="cpt_option" id="cpt-option">';
        echo '<option>Select Post Type</option>';
        
//        $post_types = get_post_types('', 'names'); 
//        foreach ($post_types as $post_type) {
//            if(get_option($post_type, false ) ) {
//                echo '<option value="' . $post_type . '">' . $post_type . '</option>';
//            }
//        }
        echo '<option value="author">Author</option>';
        echo '</select>';
        die();  
        
}




add_action( 'wp_ajax_ajax_get_posts', 'rel_get_posts' );

function rel_get_posts() {
    
        global $post, $wpdb;

        $rel_selected_post_type = esc_attr($_POST['selected_post_type']);
        
        if($rel_selected_post_type == 'author') {
            $authors = $wpdb->get_results("SELECT ID, user_nicename from $wpdb->users ORDER BY display_name");
            echo $authors;
            echo '<label>Select Relationship Item</label><br>';
            echo '<select name="rel_post" id="rel_post">';
            echo '<option>Select Author </option>';
            foreach ($authors as $me) {
                echo '<option value="'.$me->ID.'">'. $me->user_nicename .'</option>';
            }
            echo '</select>';
            
            
        } else {
        
            $args = array( 'post_type' => $rel_selected_post_type, 'posts_per_page' => -1, 'order' => 'ASC' );
            $loop = new WP_Query( $args );

            if(!$loop->have_posts()){
                echo 'No Posts Found For ' . $rel_selected_post_type;
            } else {
                echo '<label>Select Relationship Item</label><br>';
                echo '<select name="rel_post" id="rel_post">';
                echo '<option>Select Post </option>';
                    while ( $loop->have_posts() ) : $loop->the_post();
                        echo '<option value="' . get_the_ID() . '">' . get_the_title() . '</option>';
                    endwhile;
                echo '</select>';
            }
        }
        
        die(); 
        
}




/* Create Post Relationship */
add_action( 'wp_ajax_ajax_create_relationships', 'rel_ajax_create_relationship' );

function rel_ajax_create_relationship(){
    global $wpdb, $post;
        
    $rel_type = 2;
    $rel_selected_post = esc_attr($_POST['selected_post']);
    $rel_post_parent_id = esc_attr($_POST['post_parent_id']);
    $rel_post_type = esc_attr($_POST['rel_post_type']);
    
    $rel_create = new bck_relationship();
    $rel_create->rel_create_relationship($rel_type, $rel_post_parent_id, $rel_selected_post, $rel_post_type);
    
    die();
}




/* Delete Post Relationship */
add_action( 'wp_ajax_ajax_delete_relationship', 'rel_ajax_delete_relationship' );

function rel_ajax_delete_relationship(){
    global $wpdb, $post;
    $rel_table = $wpdb->prefix . 'post_relationship';
    
    $rel_post_parent = esc_attr($_POST['post_parent_id']);
    $rel_child_id = esc_attr($_POST['delete_selected']);
    
    try {    
        $wpdb->query("DELETE FROM $rel_table WHERE child_id = $rel_child_id AND post_parent_id = $rel_post_parent");
        $query_relationships = new bck_relationship();
        $query_relationships->rel_get_relationship_id($rel_post_parent);
    } catch(Exception $e) {
        echo 'Failed to delete record';
    }
    
    die();
}

