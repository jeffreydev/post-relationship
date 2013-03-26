<?php
/**
 * Description of options
 *
 * @author Jeff Clark
 */

require_once( 'class.Rel_Options_Fields.php' );


class Rel_Settings extends Rel_Options_Fields {

    public function __construct(){
        
        // create the options panel
        add_action('admin_menu', array( $this, 'bully_create_menu' ));
        
        // adding needed javascripts 
        add_action( 'admin_menu', array($this, 'bully_include_scripts' ));
  
    }
    
    
    
    /**
     * 
     * Include our scripts and styles
     */
    public function bully_include_scripts() {
        
        if(isset($_GET['page'])):
            if($_GET['page'] == 'wp-stripe-bully'):
                wp_enqueue_style( 'wp-stripe-bully', '/' . PLUGINDIR . '/wp-stripe-bully/css/styles.css', false, '1.0' );
            endif;
        endif;
    }


    
    /**
     * 
     * Create the Theme Options
     * Menu Item
     */
    public function bully_create_menu() {

        add_options_page(
                'Post Relationships', 
                'Post Relationships', 
                'administrator', 
                'post-relationshop', 
                array( $this, 'post_relationship_content')
        );

        //call register settings function
        add_action( 'admin_init', array($this, 'rel_register_mysettings' ));
       
    }    
    
    
    
    /**
     * 
     * Register the settings for the 
     * options page
     */
    public function rel_register_mysettings() {

        // register general settings
        $post_types = get_post_types('', 'names'); 
        $setting = array();
        foreach ($post_types as $post_type) {
            $setting[] = $post_type;
        }
        
        foreach($setting as $new_setting) {
            register_setting('post-relationship', $new_setting);
        }
    }
    
    
    
    
    
    /**
     *
     * The content to be displayed on our 
     * options page 
     */
    public function post_relationship_content() {
    ?>
        
    <div class="rel">
        <h2 class="relationship-title">Post Relationship Plugin</h2>
        <div class="rel-options">             
            <form method="post" action="options.php" >
            <?php settings_fields('post-relationship'); ?>
            <?php do_settings_sections('post-relationship'); ?>
                
                <h3>Select Post Types For Relationships</h3>
                <?php
                    $args = array(
                        'public'   => true,
                    );
                    $post_types = get_post_types($args, 'names');
                    foreach ($post_types as $post_type) {
                        parent::jdev_checkbox($post_type, $post_type);
                    }
                 ?>
                 <div><?php submit_button(); ?></div>
             </form>
            <div><a href="">Learn how to use post relationships</a> </div>
        </div> <!-- end wrapp -->
    </div>
    <?php    
    }
    
}// end our options 

new Rel_Settings();