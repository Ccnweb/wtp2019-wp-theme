<?php
/*
Plugin Name: wtp-temoignages
Plugin URI: http://my-awesomeness-emporium.com
description: plugin pour le site du festival Welcome To Paradise
Version: 1.2
Author: Communauté du Chemin Neuf
Author URI: http://www.chemin-neuf.fr
License: GPLv3
*/

/*
* On utilise une fonction pour créer notre custom post type Témoignage
*/

function wtpmartyr_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Témoignages', 'Témoignages'),
		// Le nom au singulier
		'singular_name'       => _x( 'Témoignage', 'Témoignage'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Témoignages'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Tous les témoignages'),
		'view_item'           => __( 'Voir les témoignages'),
		'add_new_item'        => __( 'Ajouter un nouveau témoignage'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer le Témoignage'),
		'update_item'         => __( 'Modifier le Témoignage'),
		'search_items'        => __( 'Rechercher un Témoignage'),
		'not_found'           => __( 'Non trouvé'),
		'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Témoignages'),
		'description'         => __( 'Tous sur les témoignages'),
		'labels'              => $labels,
        'menu_icon'           => 'dashicons-format-quote',
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
		/* 
		* Différentes options supplémentaires
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
        'rewrite'			  => array( 'slug' => 'temoignages'),
        'show_in_rest'      => true, // we need to add support to 'custom-fields' in order to see all the metakeys in rest

	);
	
	// On enregistre notre custom post type qu'on nomme ici "serietv" et ses arguments
	register_post_type( 'temoignages', $args );
	
}

add_action( 'init', 'wtpmartyr_custom_post_type', 0 );

?>