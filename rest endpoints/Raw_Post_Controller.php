<?php

class Raw_Post_Controller extends WP_REST_Controller {

    public function register_routes() {
        $namespace = 'wtp/v1';
        $path = 'post/(?P<slug>\d+)';
    
        register_rest_route( $namespace, '/' . $path, [
            [
                'methods'             => 'GET',
                'callback'            => array( $this, 'get_item' ),
                'permission_callback' => array( $this, 'get_item_permissions_check' )
            ],
        ]);     
    }

    function get_item_permissions_check( $request ) {
        return true;
    }

    function get_item( $request ) {

        global $wpdb;
    
        $slug = $request['slug'];
    
        // get the post id
        $id = $this->slug_to_id($slug);
        if (isset($id['success']) && !$id['success']) return $id;
    
        // execute SQL query
        $p = $wpdb->prefix;
        $sql_query = "SELECT ID, post_title, post_content FROM ".$p."posts WHERE ID = '$id'";
        $results = $wpdb->get_results($sql_query, ARRAY_A);
        
        if (empty($results)) return new WP_REST_Response(['error' => 'POST_NOT_FOUND', 'details' => 'Could not find post with id '+$id+' (SQL query = '+ $sql_query + ')'], 400);
        $results = $results[0];
    
        return new WP_REST_Response(['success' => true, 'data' => $results]);
    }

    function create_item( $request ) { // $request has class WP_REST_Request
        return $request;
    }

    private function slug_to_id($slug) {
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => 1,
            'post_name__in'  => [$slug],
            'fields'         => 'ids' 
        ];
        $id = get_posts( $args );
        if (empty($id)) return ['success' => false, 'error' => 'SLUG_NOT_FOUND', 'details' => 'Could not find post with slug '+$slug];
        return $id[0];
    }

}

// we initialize the REST routes
/* add_action('rest_api_init', function () {           
    $posts_controller = new Raw_Post_Controller();
    $posts_controller->register_routes();
}); */

?>