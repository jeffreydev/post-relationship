/*
 * Step one of creating a 
 * post relationship.  This ajax
 * call will fetch all post types
 * available for selection
 */
jQuery(document).ready(function($) {
    $('#create-yes').click(function() {        
        $.ajax({
            type: 'post',
            url: 'admin-ajax.php',                
            data: {
                action: 'ajax_get_post_types'
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success:function(data){
                $('#loading').hide();
                $('#select_cpt').html(data);
            }
        });
       
    });
});





jQuery(document).ready(function($) {
    $('#select_cpt').change(function() {
        var selected_post_type = $('#cpt-option').val();
         $.ajax({
            type: 'post',
            url: 'admin-ajax.php',                
            data: {
                action: 'ajax_get_posts',
                selected_post_type: selected_post_type
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success:function(data){
                $('#loading').hide();
                $('#cpt_options').html(data);
                $('#create-relationship').hide();
            }
        });
    });
});




jQuery(document).ready(function($) {
    $('#cpt_options').change(function() {
        $('#create-relationship').show();
    });
});


//jQuery(document).ready(function ($) {
//	$("#sortable2").sortable({
//		update: function (e, ui) {
//			var selected_post = $('#rel_post').val();
//                        var post_parent_id = $('#rel_post_parent_id').val();
//                        var post_type = $('#cpt-option').val();
//                        $.ajax({
//                            type: 'post',
//                            url: 'admin-ajax.php',                
//                            data: {
//                                action: 'ajax_create_relationships',
//                                selected_post: selected_post,
//                                post_parent_id: post_parent_id,
//                                rel_post_type: post_type
//                            },
//                            beforeSend: function() {
//                                $('#loading').show();
//                            },
//                            success:function(data){
//                                //$('#loading').hide();
//                                //$('#select_cpt').empty();
//                                //$('#cpt_options').empty();
//                                //$('#create-relationship').hide();
//                                //$('#relationships').empty();
//                                //$('#relationships').append(data);
//                                alert('finished');
//                            }
//                        });
//		}
//	});
//});




jQuery(document).ready(function($) {
    $('#create-relationship').click(function() {
        var selected_post = $('#rel_post').val();
        var post_parent_id = $('#rel_post_parent_id').val();
        var post_type = $('#cpt-option').val();
        $.ajax({
            type: 'post',
            url: 'admin-ajax.php',                
            data: {
                action: 'ajax_create_relationships',
                selected_post: selected_post,
                post_parent_id: post_parent_id,
                rel_post_type: post_type
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success:function(data){
                $('#loading').hide();
                $('#select_cpt').empty();
                $('#cpt_options').empty();
                $('#create-relationship').hide();
                $('#relationships').empty();
                $('#relationships').append(data);
            }
        });
    });
});


jQuery(document).ready(function($) {
    $('#delete-rel').live('click', function() {
       var delete_id = $(this).attr('value');
       var post_parent_id = $('#rel_post_parent_id').val();
       $.ajax({
            type: 'post',
            url: 'admin-ajax.php',                
            data: {
                action: 'ajax_delete_relationship',
                delete_selected: delete_id,
                post_parent_id: post_parent_id 
            },
            beforeSend: function() {
                $('#loading').show();
            },
            success:function(data){
                $('#loading').hide();
                $('#relationships').empty();
                $('#relationships').append(data);
                $('#messages').text('Relationship Deleted');
                $('#messages').fadeIn("fast", function() {
                    $(this).delay(2000).fadeOut("slow");
                });
            }
        });
    });
});

