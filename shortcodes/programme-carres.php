<?php

include_once(get_template_directory() . '/lib/CcnHtmlObj.php');

function ccnwtp_shortcode_programme_carres() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {
        
        // == 1. == on normalise les attributs
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // == 2. == on gère les valeurs par défaut des attributs
        $atts = shortcode_atts(
            array(
                'theme' => 'unknown',
                'class' => '',
            ), $atts, 'programme-carres' );

        // == 3. == on récupère les articles de la catégorie
        $query_args = array(
            'post_type' => 'propositions',
            'tax_query' => array(
                array(
                    'taxonomy' => 'themes',
                    'field'    => 'slug',
                    'terms'    => $atts['theme'],
                ),
            ),
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
            'limit'         => 10000,
            /* 'meta_key'      => 'ccnbtc_post_order',
            'orderby'       => 'meta_value_num', // nécessaire lorsque la meta_key est numérique
            'order'         => 'ASC',
            'limit'         => 100, */
        );
        $query = new WP_Query( $query_args );

        $html = new CcnHtmlObj('div', ['class' => 'carres_container '.$atts['class']]);
        $carres_list = new CcnHtmlObj('div', ['class' => 'carres_list']);
        
        $compteur = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() && $compteur < 1000) {
                $query->the_post();

                // get the post slug 
                $slug = get_post_field( 'post_name', get_post() );

                // lien pour éditer l'article
                $ifeditlink = (current_user_can('edit_posts')) ? '<br><a class="edit_post_link" target="_blank" href="'.get_edit_post_link(get_the_ID()).'">'.__('Éditer', 'ccnbtc').'&nbsp;&nbsp;<i class="fas fa-external-link-alt"></i></a>' : '';

                $background = "background:url('".get_the_post_thumbnail_url()."');background-position:center;background-size:cover";
                $carre_title = new CcnHtmlObj('div', ['class' => 'card_title'], get_the_title().'<br>'.
                    '<span class="txt-white">'.get_post_meta(get_the_ID(), '_wtpprop_adj_metakey', true).'</span>'.
                    '<div class="card_descr">'.get_post_meta(get_the_ID(), '_wtpprop_descr_metakey', true).'</div>'.$ifeditlink);
                $carre = new CcnHtmlObj('div', [
                    'class' => 'carre card',
                    'data-post-id' => 'post__propositions@'.$slug, 
                    'style' => $background
                ], $carre_title->toString());
                $carres_list->append($carre);
                $compteur++;
            }
            // Restore original Post Data
            wp_reset_postdata();

        } else {
            // no posts found ($query->request permet de voir la requête SQL)
            $html->append('NO POSTS');
        }

        $arrow_left = new CcnHtmlObj('div', ['class' => 'fas fa-angle-left arrow arrow_left']);
        $arrow_right = new CcnHtmlObj('div', ['class' => 'fas fa-angle-right arrow arrow_right']);
        $arrows = new CcnHtmlObj('div', ['class' => 'arrows_container'], [$arrow_left, $arrow_right]);

        $html->append($carres_list);
        $html->append($arrows);
        return $html->toString();
    };

    add_shortcode( 'programme-carres', $shortcode_fun);
};

add_action('init', 'ccnwtp_shortcode_programme_carres');

?>