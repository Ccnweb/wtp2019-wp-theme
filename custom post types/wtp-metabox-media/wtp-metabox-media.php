<?php
/*
Plugin Name: WTP2019 Add Media Metabox
Plugin URI: http://my-awesomeness-emporium.com
description: Ajoute une metabox pour uploader des images à certains post-types
Version: 1.2
Author: Communauté du Chemin Neuf
Author URI: http://www.chemin-neuf.fr
License: GPLv3

help came from : https://wordpress.stackexchange.com/questions/295388/upload-images-from-custom-plugin-using-the-media-modal
*/



$prefix = 'wtp_mediaupload_'; // plugin prefix


$custom_meta_fields = array(array(
    'label'=> 'Additional Image',
    'desc'  => 'Additional Image',
    'id'    => $prefix.'image',
    'type'  => 'media'
));

// Add the Meta Box
/* function wtp_mediaupload_add_custom_meta_box() {
    $screens = ['temoignages']; // to what post types do you want to add the metabox
    foreach ($screens as $screen) {
        add_meta_box(
            'wtp_mediaupload_metabox', // $id
            'Image pour écrans larges', // $title
            'wtp_mediaupload_show_custom_meta_box', // $callback
            $screen, // post type
            'normal', // $context
            'low'); // $priority
    }
}
add_action('add_meta_boxes', 'wtp_mediaupload_add_custom_meta_box'); */


// The Callback
function wtp_mediaupload_show_custom_meta_box($post) {
    $value_img = get_post_meta($post->ID, '_wtpmediaupload_img_metakey', true);
    ?>
    <p>
        <label for="wtp_mediaupload_img_field">Image:</label>
        <br>
        <input class="widefat" id="wtp_mediaupload_img_field" name="wtp_mediaupload_img_field" value="<?php echo $value_img;?>" />
        <br>
        <button class="upload_image_button">Upload Image</button>
    </p>
    <?php
}

function wtp_mediaupload_save_postdata($post_id)
{
	$fields = [array('field' => 'wtp_mediaupload_img_field', 'metakey' => '_wtpmediaupload_img_metakey')
    ];

    foreach ($fields as $f) {
        if (array_key_exists($f['field'], $_POST)) {
            update_post_meta(
                $post_id,
                $f['metakey'],
                $_POST[$f['field']]
            );
        }
    }
}
add_action('save_post', 'wtp_mediaupload_save_postdata');

function wtp_mediaupload_register_fields() {
    $screens = ['temoignages']; // to what post types do you want to add the metabox
	$fields = [array('key' => '_wtpmediaupload_img_metakey', 'descr' => 'Additional image for custom posts')
	];

	foreach ($fields as $f) {
        foreach ($screens as $screen) {
            register_post_meta( $screen, $f['key'], array(
                'type' => 'string',
                'description' => $f['descr'],
                'show_in_rest' => true
            ) );
        }
	}
}
add_action('init', 'wtp_mediaupload_register_fields');


// Register admin scripts for custom fields
function wtp_mediaupload_load_wp_admin_style() {
    wp_enqueue_media();
    wp_enqueue_script('media-upload');
    //wp_enqueue_style( 'wtp_mediaupload_admin_css', plugins_url( '/css/admin.css', __FILE__ ) );
    wp_enqueue_script( 'wtp_mediaupload_admin_script', get_template_directory_uri().'/custom post types/wtp-metabox-media/js/wtp_mediaupload_admin.js', array(), '20181228', 'all');
}
add_action( 'admin_enqueue_scripts', 'wtp_mediaupload_load_wp_admin_style' );
?>