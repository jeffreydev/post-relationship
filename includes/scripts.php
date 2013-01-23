<?php

function rel_include_js(){
    wp_enqueue_scripts('metabox_validation', RELATIONSHIP_BASE_URL . 'includes/scripts/custom_meta_box_validation.js');
}

add_action('wp_enqueue_scripts', 'rel_include_js');