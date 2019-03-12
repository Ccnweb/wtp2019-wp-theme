<?php
/*
Plugin Name: wtp-info-pratique
Plugin URI: http://my-awesomeness-emporium.com
description: plugin pour le site du festival Welcome To Paradise
Version: 1.2
Author: Communauté du Chemin Neuf
Author URI: http://www.chemin-neuf.fr
License: GPLv3
*/

function wtpip_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Info Pratique', 'Info Pratique'),
		// Le nom au singulier
		'singular_name'       => _x( 'Info Pratique', 'Info Pratique'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Info Pratique'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Toutes les infos pratiques'),
		'view_item'           => __( 'Voir les infos pratiques'),
		'add_new_item'        => __( 'Ajouter une nouvelle info pratique'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer l\'info pratique'),
		'update_item'         => __( 'Modifier l\'info pratique'),
		'search_items'        => __( 'Rechercher une info pratique'),
		'not_found'           => __( 'Non trouvée'),
		'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Infos Pratiques'),
		'description'         => __( 'Tous sur les infos pratiques'),
		'labels'              => $labels,
        'menu_icon'           => 'dashicons-info',
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
		/* 
		* Différentes options supplémentaires
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
        'rewrite'			  => array( 'slug' => 'infos-pratiques'),
        'show_in_rest'      => false,

	);
	
	// On enregistre notre custom post type qu'on nomme ici "serietv" et ses arguments
	register_post_type( 'infospratiques', $args );

}

add_action( 'init', 'wtpip_custom_post_type', 0 );



// ===============================================================
//          META BOX TYPE D'INFO PRATIQUE (logement, transport, ...)
// ===============================================================
function wtpip_add_type_box() {
    $screens = ['infospratiques'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wtp_type_infopratique',           // Unique ID
            'Type de l\'info pratique',  // Box title
            'wtp_infopratique_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wtpip_add_type_box');

function wtp_infopratique_html($post) {
    $value = get_post_meta($post->ID, '_wtpip_type', true);
    ?>
    <label for="wtpip_type_field">Choisis un type d'info pratique : </label>
    <select name="wtpip_type_field" id="wtpip_type_field" class="postbox">
        <option value="transport">Transport</option>
        <option value="logement" <?php selected($value, 'logement'); ?>>Logement</option>
        <option value="prix" <?php selected($value, 'prix'); ?>>Prix</option>
        <option value="volontaires" <?php selected($value, 'volontaires'); ?>>Volontaires</option>
    </select>
    <?php
}

function wtpip_save_type($post_id)
{
    if (array_key_exists('wtpip_type_field', $_POST)) {
        update_post_meta(
            $post_id,
            '_wtpip_type',
            $_POST['wtpip_type_field']
        );
    }
}
add_action('save_post', 'wtpip_save_type');


?>