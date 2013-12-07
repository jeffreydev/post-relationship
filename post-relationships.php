<?php
/**
Plugin Name: Post Relationships
Plugin URI:  http://jeffreydev.com/plugins
Description: Used to create post to post relationships
Version: 1.0.0
Author: Jeffrey Clark
Author URI: http://jeffreydev.com
*/



    if(!defined( 'RELATIONSHIP_BASE_URL' )) {
        define( 'RELATIONSHIP_BASE_URL', plugin_dir_url(__FILE__) );
    }
    
    if(!defined( 'RELATIONSHIP_UPDATE_URL' )) {
        define( 'RELATIONSHIP_UPDATE_URL', 'http://www.jeffreydev.com/api/plugins/post-relationship/update.php' );
    }
    
    
    
    
    /* includes */
    include_once dirname( __FILE__ ) . '/includes/classes/bck_relationships_setup.php';
    include_once dirname( __FILE__ ) . '/includes/classes/bck_relationship.php';
    include_once dirname( __FILE__ ) . '/includes/ajax/ajax_relationship_creation.php';
    include_once dirname( __FILE__ ) . '/includes/classes/rel_filter.php';
    include_once dirname( __FILE__ ) . '/includes/setup/class.Rel_Options_Fields.php';
    include_once dirname( __FILE__ ) . '/includes/setup/class.Rel_Settings.php';

    

    
    /* include js */
    function rel_include_js(){
        if(is_admin()){
            wp_enqueue_script('jquery');
            wp_enqueue_script('custom_val', RELATIONSHIP_BASE_URL . 'includes/ajax/custom_meta_box_validation.js');
            wp_enqueue_style('custom_styles', RELATIONSHIP_BASE_URL . 'includes/styles.css');
            wp_enqueue_style('option_styles', RELATIONSHIP_BASE_URL . 'includes/setup/css/options.css');
        
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-sortable'); 
        }
                  
    }

    add_action('admin_enqueue_scripts', 'rel_include_js');

    
    
    /* Include frontend styles */
    function rel_include_css(){
        if(!is_admin()) {
            wp_enqueue_style('post-relationship-styles', RELATIONSHIP_BASE_URL . 'includes/post-relationship.css');
        }
    }
    
    add_action('wp_enqueue_scripts', 'rel_include_css');



    /* create mysql table */
    function rel_create_table(){
        global $bck_db_version, $wpdb;
        $bck_db_version = "1.0";

        $table_name = $wpdb->prefix . "post_relationship";

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            relationship_type int(2) NOT NULL,
            post_parent_id int(9) NOT NULL,
            child_id int(9) NOT NULL,
            post_type varchar(30) NOT NULL,
            UNIQUE KEY id (id)
            );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        add_option("bck_db_version", $bck_db_version);
    }

    register_activation_hook( __FILE__, 'rel_create_table' );
    
    
    
    
    /* Setup metabox */
    $rel_setup = new bck_relationships_setup();
    
    
    
    
    /**
     * Get Post ID's for any given 
     * post type 
     */

    function rel_get_post_id($rel_post_id, $rel_post_type){
        global $wpdb, $post;
        $rel_post_id = $rel_post_id;
        $rel_post_type = $rel_post_type;
        $rel_tables = $wpdb->prefix . "post_relationship";

        $rel_posts = $wpdb->get_results("SELECT * FROM $rel_tables Where post_parent_id = $rel_post_id AND post_type = '$rel_post_type'");
        $rel_post_ID = array();
        
        foreach($rel_posts as $rel_post){
            $rel_post_ID[] = $rel_post->child_id;
        }
        
        return $rel_post_ID;
        
    }
    
    
    
    
    