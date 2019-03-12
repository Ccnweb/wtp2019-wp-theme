<?php    
include_once(get_template_directory() . '/lib/CcnHtmlObj.php');

function ccnwtp_shortcode_carousel() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {
        
        // == 1. == on normalise les attributs
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // == 2. == on gère les valeurs par défaut des attributs
        $atts = shortcode_atts(
            array(
                'post_type' => 'post',
                'theme' => '',
            ), $atts, 'carousel' );

        // == 3. == on récupère les articles de la catégorie
        $query_args = array(
            'post_type' => $atts['post_type'],
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
        );
        // si un theme est défini, on l'inclut dans la requête
        if ($atts['theme'] != '') {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'themes',
                    'field'    => 'slug',
                    'terms'    => $atts['theme'],
                ),
            );
        }
        $query = new WP_Query( $query_args );

        $html = new CcnHtmlObj('div', ['class' => 'carousel']);
        
        $compteur = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() && $compteur < 1000) {
                $query->the_post();
                
                $title = '<h3 class="soustitre">'.get_the_title().'</h3>';
                $carre = new CcnHtmlObj('div', [
                    'class' => 'element',
                    'style' => 'background: center / cover no-repeat url('.get_the_post_thumbnail_url().')',
                ], $title.'<p>'.get_the_content().'</p>');

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

    add_shortcode( 'carousel', $shortcode_fun);
};

add_action('init', 'ccnwtp_shortcode_carousel');

?>