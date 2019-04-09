<?php
/**
 * This endpoints returns a JSON with all the carres
 * 
 * source : https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/
 */

function get_carres( WP_REST_Request $request ) {

    $lang = $request['lang'];

    $query_args = array(
        'post_type'     => 'carre',
        'post_status'   => 'publish',
        'lang'          =>  $lang,
        /* 'meta_key' => '_wtpip_type',
        'orderby' => 'meta_value_num',
        'order' => 'ASC', */
    );
    $query = new WP_Query( $query_args );
    //return $query->request;

    $compteur = 0;
    $carres = [];
    while ( $query->have_posts() && $compteur < 100) {
        $query->the_post();
        $carre = [
            'slug' => get_post_field( 'post_name', get_post() ),
            //'tags' => get_the_tags(),
            'title' => get_the_title(),
            'adjectif' => get_post_meta(get_the_ID(), '_wtpcarre_adj_metakey', true),
            'descr' => get_post_meta(get_the_ID(), '_wtpcarre_descr_metakey', true),
            'link' => get_post_meta(get_the_ID(), '_wtpcarre_pagelink_metakey', true),
            'img' => get_the_post_thumbnail_url(),
        ];
        $carre['titres'] = [$carre['adjectif'], $carre['title']];
        $carres[] = $carre;
    }
    return $carres;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'wtp/v1', '/carres/(?P<lang>[A-z\-_]+)', array(
      'methods' => 'GET',
      'callback' => 'get_carres',
    ) );
} );

?>