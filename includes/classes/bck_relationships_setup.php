<?php

/**
 * Description of bck_relationships_setup
 *
 * @author Jeff Clark
 */
include 'bck_relationship.php';
class bck_relationships_setup extends bck_relationship {

        public function __construct(){
            add_action( 'add_meta_boxes', array($this, 'rel_add_custom_box' ));
            //add_action( 'save_post', array($this, 'rel_save_post_relationship' ));
        }




        /**
        * Callback for adding the custom
        * meta box to our cpt block; 
        */
        public function rel_add_custom_box() {
            foreach( array('nirec_videos', 'nirec_mentors', 'nirec_topics') as $page ) {
                add_meta_box( 
                        'Post Relationships',
                        __( 'Create A Post Relationship', 'bckspace' ),
                        array($this, 'rel_relationship_content'),
                        $page,
                        'side',
                        'default'
                    );
            }
        }




        /*
        * The meta box will hold
        * the content from this function
        */
        public function rel_relationship_content() {
            global $post;
            $rel_parent = $post->ID;
        ?>
            <div class="post-relationship">
                <p id="messages"></p>
                <div id="relationships">
                    <?php parent::rel_get_relationship_id($rel_parent); ?>
                </div>
                <p id="create-yes"> Create New Relationship + </p>
                <div class="relationship-form">
                    <p id="select_cpt"></p>
                    <p id="cpt_options"></p>
                    <p id="create-relationship">create new relationship</p>
                    <input type="text" id="rel_post_parent_id" name="rel_post_parent_id" value="<?php the_ID(); ?>" />
                </div>
            </div>
        <?php
        }


    }