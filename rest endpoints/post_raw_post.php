<?php
/**
 * This endpoints sets the post data corrisponding of a post
 * 
 * exemple : https://www.welcometoparadise.fr/wp-json/wtp/v1/post_raw_post/le-festival
 * 
 * source : https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 */

require_once(CCN_LIBRARY_PLUGIN_DIR . '/log.php'); use \ccn\lib\log as log;

function post_raw_post( WP_REST_Request $request ) {

  global $wpdb;

  $post_id = $request['ID'];
  $web_data = $request->get_body_params();

  $authorized_fields = [
    'post_title' => 'string',
    'post_content' => 'string',
  ];
  $authorized_meta_prefix = "/^(_wtp|wtp2019)/";// 'wtp2019';

  // prepare the SQL query
  $fields = [];
  foreach ($authorized_fields as $field_name => $type) {
    if (isset($web_data[$field_name]) && gettype($web_data[$field_name]) == $type) {
      $fields[$field_name] = $web_data[$field_name];
    }
  }
  $p = $wpdb->prefix;
  /*$sql_query = "UPDATE ".$p."posts SET ".implode(', ', $fields)." WHERE ID = '$post_id'"; */

  // execute Update query
  if (!empty($fields)) $res = $wpdb->update( $p."posts", $fields, ['ID' => $post_id]);

  // execute updates for custom fields
  if (!empty($web_data['custom_fields'])) {
    log\info("COCO", "ok custom fields  not empty ".json_encode($web_data['custom_fields']));
    foreach ($web_data['custom_fields'] as $meta_key => $meta_value) {
      if ($authorized_meta_prefix == '' || preg_match($authorized_meta_prefix, $meta_key)) {
        log\info("COCO", "updating custom field ".$meta_key." => ".$meta_value);
        $res = $wpdb->update( $p."postmeta", ['meta_value' => $meta_value], ['post_id' => $post_id, 'meta_key' => $meta_key]);
      }
    }
  }

  // retrieve the post
  $sql_query = "SELECT ID, post_title, post_content FROM ".$p."posts WHERE ID = '$post_id'";
  $post_object = $wpdb->get_results($sql_query, ARRAY_A);
  if (count($post_object) < 1) return new WP_REST_Response('Post '.$post_id.' could not be retrieved after update query', 404);
  $post_object = $post_object[0];

  // get all custom fields that start with the theme prefix
  $sql_query = "SELECT meta_key, meta_value FROM ".$p."postmeta WHERE post_id = '$post_id'";
  $post_meta = $wpdb->get_results($sql_query, ARRAY_A);
  $post_object['custom_fields'] = [];
  foreach ($post_meta as $meta_o) {
      if ($authorized_meta_prefix == '' || preg_match($authorized_meta_prefix, $meta_o['meta_key'])) $post_object['custom_fields'][$meta_o['meta_key']] = $meta_o['meta_value'];
  }

  return $post_object;
    
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wtp/v1', '/post_raw_post/(?P<ID>[0-9]+)', array(
      'methods' => 'POST',
      'callback' => 'post_raw_post',
      'permission_callback' => function($request){
        return current_user_can('edit_posts');
      },
    ));
} );

?>