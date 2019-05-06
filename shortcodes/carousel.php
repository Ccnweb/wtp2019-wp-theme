<?php    
include_once(get_template_directory() . '/lib/CcnHtmlObj.php');
require_once(CCN_LIBRARY_PLUGIN_DIR . 'lib.php'); use \ccn\lib as lib;

function ccnwtp_shortcode_carousel() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {

        // == 0. == Predefined options TODO rendre ça modifiable via l'interface
        // post types in this array will not display the post title in the carousel
        $no_title_post_types = ['temoignages'];
        
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
            'post_type'     => $atts['post_type'],
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
            'posts_per_page'=>  -1,
        );
        // si un theme est défini, on l'inclut dans la requête
        if ($atts['theme'] != '') {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'themes',
                    'field'    => 'slug',
                    'terms'    => $atts['theme'],
                    'posts_per_page'=>  -1,
                ),
            );
        }
        $query = new WP_Query( $query_args );

        $html = new CcnHtmlObj('div', ['class' => 'carousel post_type__'.$atts['post_type']]);
        
        $compteur = 0;
        if ( $query->have_posts() ) {
            while ( $query->have_posts() && $compteur < 1000) {
                $query->the_post();

                // get the post slug 
                $slug = get_post_field( 'post_name', get_post() );

                // lien pour éditer l'article
                $ifeditlink = (current_user_can('edit_posts')) ? '<a class="edit_post_link" target="_blank" href="'.get_edit_post_link(get_the_ID()).'">'.__('Éditer', 'ccnbtc').'&nbsp;&nbsp;<i class="fas fa-external-link-alt"></i></a>' : '';

                // get post tags
                $posttags = get_the_tags();
                $s_posttags = '';
                if (is_array($posttags)) {
                    $arr_posttags = array_map(function($tag) {return $tag->name;}, $posttags);
                    $s_posttags = '##'.implode('##', $arr_posttags).'##';
                }
                
                $title = (in_array($atts['post_type'], $no_title_post_types)) ? '': '<h3 class="soustitre">'.get_the_title().'</h3>';
                $img_url = get_the_post_thumbnail_url();

                $carre = new CcnHtmlObj('div', [
                    'data-post-id' => 'post__'.$atts['post_type'].'@'.$slug,
                    'class' => 'element',
                    // add a "?" at the end of data-img-mobile for ex, to tell that this attribute should only be added if the value is not falsy
                    'data-img-mobile' => lib\one_of(kdmfi_get_featured_image_src( 'featured-image-mobile', 'full' ), get_the_post_thumbnail_url()),
                    'data-img-desktop' => lib\one_of(kdmfi_get_featured_image_src( 'featured-image-desktop', 'full' ), kdmfi_get_featured_image_src( 'featured-image-mobile', 'full' )),
                    'style' => 'background: center / cover no-repeat url('.$img_url.')',
                ], $title.'<p>'.get_the_content().'</p>'.$ifeditlink);

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