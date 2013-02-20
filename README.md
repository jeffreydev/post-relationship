The 1010 Collective Post Relationship Plugin Documentation

Version: 1.0 

FEATURES INCLUDE::

    - Ability to create relationships between post types of any kind.
    
    - Full Ajax UI.


EXAMPLE USAGE::
    
    add_filter('post_relationship', 'jdev_filter_relationship');
        
    function jdev_filter_relationship($html) {

        $html = '<div>';
        $html .= get_the_title();
        $html .= '</div>';

        return $html;
    }