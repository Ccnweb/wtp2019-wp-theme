<?php
/*
Plugin Name: wtp-proposition
Plugin URI: http://my-awesomeness-emporium.com
description: plugin pour le site du festival Welcome To Paradise
Version: 1.2
Author: Communauté du Chemin Neuf
Author URI: http://www.chemin-neuf.fr
License: GPLv3
*/

/*
* On utilise une fonction pour créer notre custom post type 'Séries TV'
*/

function wpm_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Propositions', 'Propositions'),
		// Le nom au singulier
		'singular_name'       => _x( 'Proposition', 'Proposition'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Propositions'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Toutes les propositions'),
		'view_item'           => __( 'Voir les propositions'),
		'add_new_item'        => __( 'Ajouter une nouvelle proposition'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Editer la proposition'),
		'update_item'         => __( 'Modifier la proposition'),
		'search_items'        => __( 'Rechercher une proposition'),
		'not_found'           => __( 'Non trouvée'),
		'not_found_in_trash'  => __( 'Non trouvée dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Propositions'),
		'description'         => __( 'Tous sur les propositions'),
		'labels'              => $labels,
        'menu_icon'           => 'dashicons-tickets',
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'thumbnail', ),
		/* 
		* Différentes options supplémentaires
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
        'rewrite'			  => array( 'slug' => 'props'),
        'show_in_rest'      => true,

	);
	
	// On enregistre notre custom post type qu'on nomme ici "serietv" et ses arguments
	register_post_type( 'propositions', $args );
	
}

add_action( 'init', 'wpm_custom_post_type', 0 );







//On crée taxonomies personnalisées: Thèmes

function wpm_add_taxonomies() {
	
	// Taxonomie TypePropale

	// On déclare ici les différentes dénominations de notre taxonomie qui seront affichées et utilisées dans l'administration de WordPress
	/* $labels_types = array(
		'name'              			=> _x( 'Type de Proposition', 'taxonomy general name'),
		'singular_name'     			=> _x( 'TypePropale', 'taxonomy singular name'),
		'search_items'      			=> __( 'Chercher un type de proposition'),
		'all_items'        				=> __( 'Tous les types de propositions'),
		'edit_item'         			=> __( 'Editer le type de proposition'),
		'update_item'       			=> __( 'Mettre à jour le type de proposition'),
		'add_new_item'     				=> __( 'Ajouter un nouveau type de proposition'),
		'new_item_name'     			=> __( 'Valeur du nouveau type de proposition'),
		'separate_items_with_commas'	=> __( 'Séparer les types de proposition avec une virgule'),
		'menu_name'         => __( 'Type Propale'),
	);

	$args_types = array(
	// Si 'hierarchical' est défini à false, notre taxonomie se comportera comme une étiquette standard
		'hierarchical'      => false,
		'labels'            => $labels_types,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'typepropale' ),
	);

	register_taxonomy( 'typepropale', 'propositions', $args_types ); */

	// Taxonomie Thèmes
	
	$labels_themes = array(
		'name'                       => _x( 'Thèmes', 'taxonomy general name'),
		'singular_name'              => _x( 'Thème', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher un thème'),
		'popular_items'              => __( 'Thèmes populaires'),
		'all_items'                  => __( 'Tous les thèmes'),
		'edit_item'                  => __( 'Editer un thème'),
		'update_item'                => __( 'Mettre à jour un thème'),
		'add_new_item'               => __( 'Ajouter un nouveau thème'),
		'new_item_name'              => __( 'Nom du nouveau thème'),
		'separate_items_with_commas' => __( 'Séparer les thèmes avec une virgule'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer un thème'),
		'choose_from_most_used'      => __( 'Choisir parmi les plus utilisés'),
		'not_found'                  => __( 'Pas de thème trouvé'),
		'menu_name'                  => __( 'Thèmes'),
	);

	$args_themes = array(
		'hierarchical'          => false,
		'labels'                => $labels_themes,
		'show_ui'               => true,
        'show_admin_column'     => true,
        'show_in_rest'      => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'themes' ),
	);

	register_taxonomy( 'themes', 'propositions', $args_themes );
	
	// Catégorie de série

	/*$labels_cat_serie = array(
		'name'                       => _x( 'Catégories de série', 'taxonomy general name'),
		'singular_name'              => _x( 'Catégories de série', 'taxonomy singular name'),
		'search_items'               => __( 'Rechercher une catégorie'),
		'popular_items'              => __( 'Catégories populaires'),
		'all_items'                  => __( 'Toutes les catégories'),
		'edit_item'                  => __( 'Editer une catégorie'),
		'update_item'                => __( 'Mettre à jour une catégorie'),
		'add_new_item'               => __( 'Ajouter une nouvelle catégorie'),
		'new_item_name'              => __( 'Nom de la nouvelle catégorie'),
		'add_or_remove_items'        => __( 'Ajouter ou supprimer une catégorie'),
		'choose_from_most_used'      => __( 'Choisir parmi les catégories les plus utilisées'),
		'not_found'                  => __( 'Pas de catégories trouvées'),
		'menu_name'                  => __( 'Catégories de série'),
	);

	$args_cat_serie = array(
	// Si 'hierarchical' est défini à true, notre taxonomie se comportera comme une catégorie standard
		'hierarchical'          => true,
		'labels'                => $labels_cat_serie,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'categories-series' ),
	);

	register_taxonomy( 'categoriesseries', 'seriestv', $args_cat_serie ); */
}

add_action( 'init', 'wpm_add_taxonomies', 0 );











function wtpprop_add_custom_box() {
    $screens = ['propositions'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wtp_type_proposition',           // Unique ID
            'Informations sur la proposition',  // Box title
            'wtpprop_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wtpprop_add_custom_box');

function wtpprop_custom_box_html($post) {
	$value_adj = get_post_meta($post->ID, '_wtpprop_adj_metakey', true);
	$value_descr = get_post_meta($post->ID, '_wtpprop_descr_metakey', true);
	$value_linkedprop = get_post_meta($post->ID, '_wtpprop_linkedprop_metakey', true);

	// retrieve all propositions for the linked proposition dropdown
	$query_args = array(
		'post_type' => 'propositions',
		'post_status'   => 'publish',
		'lang'          =>  pll_current_language(),
		'posts_per_page'=> 10000,
	);
	$query = new WP_Query( $query_args );
    ?>
	<div class="wtpprop_custom_metabox" style="display:flex;flex-direction:column">

		<!-- Adjectif -->
		<div class="wtpprop_field_container">
            <label for="wtpprop_adjectif_field">Adjectif ou deuxième ligne : </label>
            <input type="text" name="wtpprop_adjectif_field" id="wtpprop_adjectif_field" class="postbox" value="<?php echo $value_adj ?>" />
		</div>
		
		<!-- Description -->
        <div class="wtpprop_field_container" style="display:flex">
            <label for="wtpprop_descr_field">Description : </label>
            <textarea type="text" name="wtpprop_descr_field" id="wtpprop_descr_field" class="postbox" rows="5"><?php echo $value_descr ?></textarea>
		</div>
	
		<!-- The dropdown to select  -->
		<div class="wtpprop_field_container">
			<label for="wtpprop_linkedprop_field">Proposition liée : </label>
			<select name="wtpprop_linkedprop_field" id="wtpprop_linkedprop_field" class="postbox">
				<option value="no_linkedprop"><i>Pas de proposition liée</i></option>
				<?php 
				$propositions = [];
				while ( $query->have_posts() && $compteur < 1000) {
					$query->the_post();
					$propositions[] = ['id' => get_the_ID(), "title" => get_the_title().' '.get_post_meta(get_the_ID(), '_wtpprop_adj_metakey', true)];
				}
				uasort($propositions, function($a, $b) {return (remove_accents($a["title"]) < remove_accents($b["title"])) ? -1: 1;});
				foreach ($propositions as $prop): ?>
					<option value="<?php echo $prop["id"] ?>" <?php selected($value_linkedprop, $prop["id"]); ?>><?php echo $prop["title"] ?></option>
			<?php endforeach; ?>
			</select>
		</div>
	</div>
    <?php
}

function wtpprop_save_postdata($post_id)
{
	$fields = [array('field' => 'wtpprop_adjectif_field', 'metakey' => '_wtpprop_adj_metakey'), 
			array('field' => 'wtpprop_descr_field', 'metakey' => '_wtpprop_descr_metakey'),
			array('field' => 'wtpprop_linkedprop_field', 'metakey' => '_wtpprop_linkedprop_metakey')
    ];

    foreach ($fields as $f) {
        if (array_key_exists($f['field'], $_POST)) {
            update_post_meta(
                $post_id,
                $f['metakey'],
                $_POST[$f['field']]
            );
        }
    }
}
add_action('save_post', 'wtpprop_save_postdata');


function wtpprop_register_fields() {
	$fields = [array('key' => '_wtpprop_adj_metakey', 'descr' => 'Adjectif for post-type proposition'),
				array('key' => '_wtpprop_descr_metakey', 'descr' => 'Description for post-type proposition'),
				array('field' => 'wtpprop_linkedprop_field', 'metakey' => '_wtpprop_linkedprop_metakey')
	];

	foreach ($fields as $f) {
		register_post_meta( 'propositions', $f['key'], array(
			'type' => 'string',
			'description' => $f['descr'],
			'show_in_rest' => true
		) );
	}
}
add_action('init', 'wtpprop_register_fields');
?>