<?php
/**
 * [insert-menu name="menu_slug"]
 * 
 * This shortcode inserts the corresponding menu
 * 
 * 
 */

require_once(CCN_LIBRARY_PLUGIN_DIR . '/lib.php'); use \ccn\lib as lib;


include_once(get_template_directory() . '/lib/CcnHtmlObj.php');

function ccnwtp_shortcode_insert_menu() {

    $shortcode_fun = function($atts = [], $content = null, $tag = '') {
        
        // == 1. == on normalise les attributs
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        // == 2. == on gère les valeurs par défaut des attributs
        $atts = shortcode_atts(
            array(
                'name' => '',
                'slug' => '',
                'id' => '',
                'depth' => 1,
                'wrapper' => 'ul',
                'class_wrapper' => '',
            ), $atts, 'insert-menu' );

        $menu_name = lib\first_not_falsy([$atts["name"], $atts["slug"], $atts["id"]]); 

        // == 3. == get menu HTML
        $items = wp_get_nav_menu_items($menu_name);
        return '<'.$atts['wrapper'].' class="menu '.$atts['class_wrapper'].'">'.
            walk_nav_menu_tree($items, $atts["depth"], ['theme_location' => 'menu-social', 'container_class' => 'txt-gold link_social'])
        ."</".$atts['wrapper'].">";
    };

    add_shortcode( 'insert-menu', $shortcode_fun);
};

add_action('init', 'ccnwtp_shortcode_insert_menu');

?>