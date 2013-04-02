<?php
/**
Plugin Name: Post Relationships
Plugin URI: http://the1010collective.com/plugins
Description: Used to create post to post relationships
Version: 1.0.0
Author: 1010 Collective
Author URI: http://the1010collective.com
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
    
    
    
    
    // UPDATE
    
    // Update Ability
    add_filter('pre_set_site_transient_update_plugins', 'jdev_altapi_check' );
    
    function jdev_altapi_check( $transient ) {
        
        // check if the transient has checked info        
        if( empty( $transient->checked ) )
            return $transient;
        
        $plugin_slug = plugin_basename(__file__);
        $current_version =  $transient->checked[$plugin_slug];
               
        // Post data to send to our API
        $args = array(
            'action' => 'update-check',
            'plugin_name' => $plugin_slug,
            'version' => $transient->checked[$plugin_slug],
        );

        // Send a request checking for an update
        $response = jdev_altapi_request($args, $current_version);
        
        // If resosne is false, dont alter the transient
        if( false !== $response && $current_version < $response->new_version ) {
            $transient->response[$plugin_slug] = $response;
        }

        return $transient;
        
    }
    
    
    
    
    function jdev_altapi_request( $args){
        
        // Send Request      
        $request = wp_remote_post(RELATIONSHIP_UPDATE_URL, array( 'body' => $args) );
        
        // Make sure the request was successful
        if( is_wp_error( $request ) || wp_remote_retrieve_response_code($request) != 200 ) {
            return false;      
        }

   
        // Read server response, which should be an object
        $response = unserialize( wp_remote_retrieve_body( $request ) );

        if( is_object( $response ) ) {
            return $response;
        } else {
            return false;
        }
        
    }
    
    
    add_filter( 'plugins_api', 'jdev_altapi_info' );
    
    function jdev_altapi_info( $args ){
        
        $plugin_slug = plugin_basename(__file__);
        // Post data to send to our API
        $args = array(
            'action' => 'plugin_information',
            'plugin_name' => $plugin_slug,
        );
        
        // Send request for detailed information
        $response = jdev_altapi_request( $args );
        
        // Send request for information
        $request = wp_remote_post( RELATIONSHIP_UPDATE_URL, array( 'body' => $args ) );
        
        return $response;
        
    }