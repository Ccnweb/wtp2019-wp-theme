<?php    
include_once(get_template_directory() . '/lib/CcnHtmlObj.php');

function ccnwtp_shortcode_infos_pratiques() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {
        
        // == 1. == on normalise les attributs
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // == 2. == on gère les valeurs par défaut des attributs
        $atts = shortcode_atts(
            array(
                'type' => 'unknown',
            ), $atts, 'infos-pratiques' );

        // == 3. == on récupère les articles de la catégorie
        $query_args = array(
            'post_type' => 'infospratiques',
            'meta_key' => '_wtpip_type',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_wtpip_type',
                    'value' => array($atts['type']),
                    'compare' => 'IN',
                )
            )
        );
        $query = new WP_Query( $query_args );

        $html = new CcnHtmlObj('div', ['class' => 'info_part_content']);
        
        $compteur = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() && $compteur < 1000) {
                $query->the_post();
                $img = '<img src="'.get_the_post_thumbnail_url().'" alt="">';
                $title = '<h3 class="soustitre">'.get_the_title().'</h3>';
                $carre = new CcnHtmlObj('div', ['class' => 'element'], $img.$title.'<p>'.get_the_content().'</p>');
                $html->append($carre);
                $compteur++;
            }
            // Restore original Post Data
            wp_reset_postdata();

        } else {
            // no posts found ($query->request permet de voir la requête SQL)
            $html->append('NO POSTS');
        }

        return $html->toString();
    };

    add_shortcode( 'infos-pratiques', $shortcode_fun);
};

add_action('init', 'ccnwtp_shortcode_infos_pratiques');

?>