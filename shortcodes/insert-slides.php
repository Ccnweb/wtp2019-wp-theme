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
require_once(CCN_LIBRARY_PLUGIN_DIR . '/lib.php'); use \ccn\lib as lib;

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
        $cat_in = [];
        $cat_original = get_category_by_slug( $atts['category'] );
        if ($cat_original) $cat_in[] = $cat_original->term_id;
        $local_cat_slug = $atts['category']."-".pll_current_language();
        $cat_local = get_category_by_slug( $local_cat_slug );
        if ($cat_local) $cat_in[] = $cat_local->term_id;

        $query_args = array(
            //'category_name' => $atts['category'],
            'category__in'  =>  $cat_in,
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
            'meta_key'      => 'wtp2019_post_order',
            'orderby'       => 'meta_value_num', // nécessaire lorsque la meta_key est numérique
            'order'         => 'ASC',
            'posts_per_page'=> 10000,
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
                //lib\php_console_log("PHP : ".$s_posttags);

                // logo WTP in the top left corner ?
                $logo_wtp = (preg_match("/##logo-wtp##/", $s_posttags)) ? '<img class="logo_wtp" src="'.get_template_directory_uri().'/img/logo_wtp_gold.png">' : '';

                // get the classes defined as tags (in the form "class-...")
                $tag_classes = lib\tags_to_css_classes();

                // get title
                $title = (preg_match("/##no-title##/", $s_posttags)) ? '' : '<h2 class="subtitle">'.get_the_title().'</h2>';

                // get bg video
                $bg_video_url = get_post_meta(get_the_ID(), 'background_video', true);
                $bg_video_html = '';
                if (!empty($bg_video_url)) {
                    if (!preg_match("/^http/", $bg_video_url) && !preg_match("/^\//", $bg_video_url)) $bg_video_url = '/'.$bg_video_url;
                    if (!empty($bg_video_url) && preg_match("/^\//", $bg_video_url)) $bg_video_url = get_site_url().$bg_video_url;
                    
                    $bg_video_html = '<video class="video_player" 
                            height="100%"
                            preload="none" muted autoplay loop playsinline>
                        <source src="'.$bg_video_url.'"/>
                        <p>Your browser does not support HTML5 Video!</p>
                    </video>';
                }

                // lien pour éditer l'article
                $ifeditlink = (current_user_can('edit_posts')) ? '<a class="edit_post_link" target="_blank" href="'.get_edit_post_link(get_the_ID()).'">'.__('Éditer', 'ccnbtc').'&nbsp;&nbsp;<i class="fas fa-external-link-alt"></i></a>' : '';

                // generate slide content
                $content = new CcnHtmlObj('div', 
                    ['class' => 'text_content flexcc flexcol'], 
                    $ifeditlink . $bg_video_html . $title . do_shortcode(get_the_content())
                );

                // add new slide element
                $class_categories = array_map(function($el) {$c = get_category($el); return 'cat_'.$c->slug;}, wp_get_post_categories(get_the_ID()));
                //var_dump($class_categories);
                
                $slides[] = new CcnHtmlObj('section', [
                    'data-post-id' => 'post__post@'.$slug,
                    'class' => array_merge([
                        'section'.$tag_classes, 
                        'slide__'.$slug, 
                    ], $class_categories),
                ], $logo_wtp . $content->toString());

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