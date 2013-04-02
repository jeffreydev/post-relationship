<h2> Post Relationships WordPress Plugin </h2>
Version 1.0.0

<h2> Post Relationships Documentation </h2>
The post relationships plugin allows you to create relationships between post types.  It offers a few canned outputs for quick and simple implementation.  Bellow you can see what outputs are available and how to use them along with a how to use the custom filter for complete control over your output.

<h2>Why would I need to use the Post Relationship Plugin?</h2>
Lets go over a quick senario that would be in need of this plugin.  You have video site with two custom post types one being videos and one being mentors.  Now your mentors have a profile page with all kinds of cool content about that person, but on your video page you want to be able to link to that persons profile, and maybe they are in more then one video.  The Post Relationship Plugin allows for you to easily make this happen by creating a one way relationship from your video to your mentor and running a simple shortcode or function to display what videos your mentor is in.  If you hav more then one mentor is that video just create another relationship and let the plugin do the rest!!!

<h2> Documentation </h2>
Lets take a look at how to use the Post Relationship Plugin

<h4> Options </h4>
In Settings->Post Relationships select what post types you would like to be available for relationships.

<img src="http://www.jeffreydev.com/wp-content/uploads/2013/01/post-relationship-options1.jpg" alt="WordPress Post Relationship" width="600" class="alignnone size-full wp-image-261" />

<h4>Filter</h4>
<pre lang="php" line="1">
    add_filter("jdev_new_relationship_output", 'jdev_get_it');
    
    function jdev_get_it( $html ) {
        $html .= 'I can do anything I want!!!';
        return $html;
    }
</pre>

<h4>Canned Outputs</h4>
Post Relationships Plugins offers 4 canned outputs that you can take advantage of.  Below is a look at how to access those and what they will look like for you.

<h5>Parameters</h5>

In the function below you will be using the jdev_get_related_posts function to access your relationships.  This is a static function from class rel_filter.  It takes 3 parameters.

1.  $post_type ( The post type of your relationship )
2.  $ouput -- <em>optional</em> ( The output you would like to show, default, 1, 2, 3 )
3.  $canned -- <em>default is true</em> ( Set this to false if you want to run your own filter using jdev_new_relationship_output )

<h5>$output</h5>
default -- list of linked title

1 -- Post thumbnail.

2 -- Post thumbnail and title.

3 -- Post thumbnail, title and excerpt.


<pre lang="php" line="1">
// standard usage
echo rel_filter::jdev_get_related_posts($post_id, 'post');
</pre>


<pre lang="php" line="1">
// go back to parent leven and get all associated posts
$parentId = bck_relationship::get_parent_id($post->ID, 'post');
echo rel_filter::jdev_get_related_posts($parentID, 'post');
</pre>