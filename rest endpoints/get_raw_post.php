<?php
/**
 * This endpoints returns the data corrisponding to the article slug
 * 
 * exemple : https://www.welcometoparadise.fr/wp-json/wtp/v1/get_raw_post/le-festival
 * 
 * source : https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 */

function get_raw_post( WP_REST_Request $request ) {

    global $wpdb;

    $authorized_meta_prefix = "/^(_wtp|wtp2019)/";// 'wtp2019';

    $post_type = $request['type'];
    $slug = $request['slug'];

    // get the post id
    $args = [
        'post_type'      => $post_type,
        'posts_per_page' => 1,
        'post_name__in'  => [$slug],
        'fields'         => 'ids' 
    ];
    $id = get_posts( $args );
    if (empty($id)) return new WP_REST_Response(['success' => false, 'error' => 'SLUG_NOT_FOUND', 'details' => 'Could not find post with slug '.$slug], 404);
    $id = $id[0];

    // execute SQL query
    $p = $wpdb->prefix;
    $sql_query = "SELECT ID, post_type, post_title, post_content FROM ".$p."posts WHERE ID = '$id'";
    $post_object = $wpdb->get_results($sql_query, ARRAY_A);
    if (empty($post_object)) return new WP_REST_Response(['success' => false, 'error' => 'POST_NOT_FOUND', 'details' => 'Could not find post with id '.$id.' (SQL query = '.$sql_query.')'], 400);
    $post_object = $post_object[0];

    // get all custom fields that start with the theme prefix
    $sql_query = "SELECT meta_key, meta_value FROM ".$p."postmeta WHERE post_id = '$id'";
    $post_meta = $wpdb->get_results($sql_query, ARRAY_A);
    $post_object['custom_fields'] = [];
    foreach ($post_meta as $meta_o) {
        if ($authorized_meta_prefix == '' || preg_match($authorized_meta_prefix, $meta_o['meta_key'])) $post_object['custom_fields'][$meta_o['meta_key']] = $meta_o['meta_value'];
    }
    
    return ['success' => true, 'data' => $post_object];
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wtp/v1', '/post/(?P<type>[A-z\-_0-9]+)/(?P<slug>[A-z\-_0-9]+)', array(
        'methods' => 'GET',
        'callback' => 'get_raw_post',
        'permission_callback' => function($request){
            return is_user_logged_in();
        },
    ));
} );

?>