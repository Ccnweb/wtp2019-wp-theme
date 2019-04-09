<?php
/**
 * [insert-slides category="my_cat_slug"]
 * 
 * This shortcode displays all articles in category "my_cat_slug" as slides
 * 
 * It manages the following post tags :
 * - class-*        adds an arbitrary class to the slide root element
 * - no-title       doesn't display the post title
 * - logo-wtp       adds an <img> as first child of the slide root element of the WTP logo
 * 
 */

include_once(get_template_directory() . '/lib/CcnHtmlObj.php');

function ccnwtp_shortcode_insert_slides() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {
        
        // == 1. == on normalise les attributs
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // == 2. == on gère les valeurs par défaut des attributs
        $atts = shortcode_atts(
            array(
                'category' => 'unknown',
            ), $atts, 'insert-slides' );

        // == 3. == on récupère les articles de la catégorie
        $query_args = array(
            'category_name' => $atts['category'],
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
            'meta_key'      => 'wtp2019_post_order',
            'orderby'       => 'meta_value_num', // nécessaire lorsque la meta_key est numérique
            'order'         => 'ASC',
            'limit'         => 100,
        );
        $query = new WP_Query( $query_args );

        $slides = [];
        
        $compteur = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() && $compteur < 1000) {
                $query->the_post();

                // get the post slug 
                $slug = get_post_field( 'post_name', get_post() );

                // get post tags
                $posttags = get_the_tags();
                $s_posttags = '';
                if (is_array($posttags)) {
                    $arr_posttags = array_map(function($tag) {return $tag->name;}, $posttags);
                    $s_posttags = '##'.implode('##', $arr_posttags).'##';
                }

                // logo WTP in the top left corner ?
                $logo_wtp = (preg_match("/##logo-wtp##/", $s_posttags)) ? '<img class="logo_wtp" src="'.get_template_directory_uri().'/img/logo_wtp_gold.png">' : '';

                // get the classes defined as tags (in the form "class-...")
                preg_match_all('/##class-([^#]+)##/', $s_posttags, $result);
                $tag_classes = ($result) ? ' '.implode(' ', $result[1]): '';

                // get title
                $title = (preg_match("/##no-title##/", $s_posttags)) ? '' : '<h2 class="subtitle">'.get_the_title().'</h2>';

                // generate slide content
                $content = new CcnHtmlObj('div', ['class' => 'text_content flexcc flexcol'], $title.do_shortcode(get_the_content()));

                // add new slide element
                $slides[] = new CcnHtmlObj('section', [
                    'class' => 'section'.$tag_classes.' slide__'.$slug,
                ], $logo_wtp.$content->toString());

                $compteur++;
            }
            // Restore original Post Data
            wp_reset_postdata();

        } else {
            // no posts found ($query->request permet de voir la requête SQL)
            return 'NO POSTS';
        }

        return implode("\n", array_map(function($s) {return $s->toString();}, $slides));
    };

    add_shortcode( 'insert-slides', $shortcode_fun);
};

add_action('init', 'ccnwtp_shortcode_insert_slides');

?>