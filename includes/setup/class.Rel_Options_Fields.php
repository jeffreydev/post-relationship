<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author jeffclark
 */
class Rel_Options_Fields {
    
    
    //text
    var $text_field_name;
    var $text_label;
    
    //textarea
    var $field_name;
    var $label;
    
    //checkbox 
    var $checkbox_field_name;
    var $checkbox_label;
    
    //image uploader
    var $image_upload_field_name;
    var $bckLabel;
    
    //Wysiwig
    var $editor_field_name;
    var $editor_label;
    
    //select
    var $select_field_name;
    var $select_label;
    var $select_option = array();
    
    public function __construct() {
        
        
       
    }
    
    
    
    
    /**
     * Create the text fields.  It takes two parameters
     * 
     * @param type $text_field_name
     * @param type $text_label 
     */
    public function jdev_input_text( $text_field_name, $text_label ) {
        
        $this->text_field_name = $text_field_name;
        $this->text_label = $text_label;
        
        $field = get_option( $this->text_field_name );
        

        $html = '<div class="field-wrap">';
        $html .= '<label for="'.$this->text_field_name.'">'.esc_html($this->text_label, "bckspace").'</label>';
        $html .= '<input type="text" name="'.$this->text_field_name.'" value="'.wp_kses( $field, array() ).'">';
        $html .= '</div><!-- end field wrap -->';
    
        return $html;
 
    }
    
    
    
    
    /**
     * Create the textarea field. It takes two parameters
     * 
     * @param type $field_name
     * @param type $label 
     */
    public function jdev_input_textarea( $field_name, $label ) {
        
        $this->field_name = $field_name;
        $this->label = $label;
        
        $field = get_option( $this->field_name );
        
        ?>
        
        <div class="field-wrap">
            <label for="<?php echo $this->field_name; ?>"><?php esc_html_e( $this->label, 'bckspace'); ?></label>
            <textarea name="<?php echo $this->field_name; ?>"/><?php echo wp_kses_post( $field ); ?></textarea>
        </div><!-- end field wrap -->   
        
    <?php    
    }
    
    
    
    
    public function jdev_wysiwig_editor( $editor_field_name, $editor_label) {
        $this->editor_field_name = $editor_field_name;
        $this->editor_label = $editor_label;
        
        $editor_content = get_option( $this->editor_field_name );
        echo '<div class="field-wrap">';
        echo '<label for="'. $this->editor_field_name .'">'. $this->editor_label . '</label>'; 
        wp_editor($editor_content, $this->editor_field_name);
        echo '</div>';
    }
    
    
    
    /**
     * Create the check box field.  It takes two parameters
     * 
     * @param type $checkbox_field_name 
     * @param type $checkbox_label 
     */
    public function jdev_checkbox( $checkbox_field_name, $checkbox_label ) {
        
        $this->checkbox_field_name = $checkbox_field_name;
        $this->checkbox_label = $checkbox_label;

        
        $html = '<div class="field-wrap checkbox">';
        $html .= '<label for="'. $this->checkbox_field_name .'">'. $this->checkbox_label . '</label>'; 
        $html .= '<input type="checkbox" id="'. $this->checkbox_field_name .'" name="'. $this->checkbox_field_name .'" value="1" ' . checked(1, get_option( $this->checkbox_field_name ), false) . '/>'; 
        $html .= '</div>';
        echo $html;  
    } 
    
    
    
    
    /**
     * Create an image uploader for our options.  It takes one 
     * parameter.
     * 
     * @param type $image_upload_field_name 
     */
    public function jdev_image_upload( $bckLabel, $image_upload_field_name ) {
        
        $this->image_upload_field_name = $image_upload_field_name;
        $this->bckLabel = $bckLabel;
        
        ?>
        
        <div class="field-wrap">      
        <label for="<?php echo $this->bckLabel; ?>"><?php _e( $this->bckLabel, 'bckspace'); ?></label>
        
        <input type="text" class="jdev-image-uploader" id="<?php echo $this->image_upload_field_name; ?>" name="<?php echo $this->image_upload_field_name; ?>" value="<?php echo get_option( $this->image_upload_field_name ); ?>"/>
        <input id="_btn" class="upload_image_button" type="button" value="Upload Image" />
        
        <img src="<?php echo get_option( $this->image_upload_field_name ); ?>" class="logo-image" />
        </div> <!-- end field wrap -->    
        
        <?php
    }
    
    
    
    
    /**
     * Create a drop down selection for our options
     *  
     */
    
    public function bck_select_option( $select_label, $select_field_name, $select_option = array() ) {
        
        $this->select_field_name = $select_field_name;
        $this->select_label = $select_label;
        $this->select_option = $select_option;

               /**
        * get the option string and 
        * explode it to form an array 
        */
       $bck_option = explode(",", $this->select_option);
       
       ?>

       <div class="field-wrap">  
       <label for="<?php echo $this->select_label; ?>"><?php _e( $this->select_label, 'bckspace'); ?></label>
       <select name="<?php echo $this->select_field_name; ?>">
       <option value="">Select</option>
       <?php 
        foreach( $bck_option as $newOption ) {
           if(get_option($this->select_field_name) == $newOption ) {
               echo '<option selected value="'.$newOption.'">' . $newOption . '</option>';
           } else {
                echo '<option value="'.$newOption.'">' . $newOption . '</option>';
           }
        } 
       ?>
       
       </select>
       </div>
       <?php        
        
    }
    

}// end options class 