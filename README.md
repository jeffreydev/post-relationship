The 1010 Collective Post Relationship Plugin Documentation

Version: 1.0 ( BETA )

FEATURES INCLUDE::
    - Ability to create relationships between post types of any kind.
    
    - Full Ajax UI allowing.


EXAMPLE USAGE::

    global $post;
    $rel_post_type = 'post'; // post type for query
    
    //gets back post ides associated with the post type selected
    $rel = rel_get_post_id($post->ID, $rel_post_type); 
                            
    $args = array(
        'post_type' => $rel_post_type,
        'post__in' => $rel
      );
                            
    $loop = new WP_Query($args);
                            
    while($loop->have_posts()) : $loop->the_post(); ?>
        <?php // normal WordPress loop here ?>
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <?php endwhile; ?>