<?php
/*
Plugin Name: WTP2019 Carrés Accueil
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

function wtpcarre_custom_post_type() {

	// On rentre les différentes dénominations de notre custom post type qui seront affichées dans l'administration
	$labels = array(
		// Le nom au pluriel
		'name'                => _x( 'Carrés', 'Carrés'),
		// Le nom au singulier
		'singular_name'       => _x( 'Carré', 'Carré'),
		// Le libellé affiché dans le menu
		'menu_name'           => __( 'Carrés'),
		// Les différents libellés de l'administration
		'all_items'           => __( 'Tout sur les carrés'),
		'view_item'           => __( 'Voir les carrés'),
		'add_new_item'        => __( 'Ajouter un nouveau carré'),
		'add_new'             => __( 'Ajouter'),
		'edit_item'           => __( 'Éditer le carré'),
		'update_item'         => __( 'Modifier le carré'),
		'search_items'        => __( 'Rechercher un carré'),
		'not_found'           => __( 'Non trouvé'),
		'not_found_in_trash'  => __( 'Non trouvé dans la corbeille'),
	);
	
	// On peut définir ici d'autres options pour notre custom post type
	
	$args = array(
		'label'               => __( 'Carrés'),
		'description'         => __( 'Tous sur les carrés'),
		'labels'              => $labels,
        'menu_icon'           => 'dashicons-grid-view',
		// On définit les options disponibles dans l'éditeur de notre custom post type ( un titre, un auteur...)
		'supports'            => array( 'title', 'thumbnail', 'custom-fields'),
		/* 
		* Différentes options supplémentaires
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'has_archive'         => true,
        'rewrite'			  => array( 'slug' => 'carre'),
        'show_in_rest'      => true,

	);
	
	// On enregistre notre custom post type qu'on nomme ici "serietv" et ses arguments
	register_post_type( 'carre', $args );
	
}

add_action( 'init', 'wtpcarre_custom_post_type', 0 );




/* function add_metabox(id, type, post_types, opt) {
    // TODO
} */


function wtpcarre_custom_metabox() {
    $screens = ['carre'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wtp_carre_data',           // Unique ID
            'Données du carré',  // Box title
            'wtpcarre_data_cbk',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wtpcarre_custom_metabox');

function wtpcarre_data_cbk($post) {
    $value_adj = get_post_meta($post->ID, '_wtpcarre_adj_metakey', true);
    $value_descr = get_post_meta($post->ID, '_wtpcarre_descr_metakey', true);
    $value_pagelink = get_post_meta($post->ID, '_wtpcarre_pagelink_metakey', true);
    ?>
    <div class="wtpcarre_custom_metabox" style="display:flex;flex-direction:column">
        <div class="wtpcarre_field_container">
            <label for="wtpcarre_adjectif_field">Adjectif du carré : </label>
            <input type="text" name="wtpcarre_adjectif_field" id="wtpcarre_adjectif_field" class="postbox" value="<?php echo $value_adj ?>" />
        </div>
        <div class="wtpcarre_field_container" style="display:flex">
            <label for="wtpcarre_descr_field">Description : </label>
            <textarea type="text" name="wtpcarre_descr_field" id="wtpcarre_descr_field" class="postbox" rows="5"><?php echo $value_descr ?></textarea>
        </div>
        <div class="wtpprop_field_container">
			<label for="wtpcarre_pagelink_field">Lien vers une page : </label>
			<select name="wtpcarre_pagelink_field" id="wtpcarre_pagelink_field" class="postbox">
				<option value="none" <?php selected($value_pagelink, 'none'); ?>>Aucun lien</option>
				<option value="programmation" <?php selected($value_pagelink, 'programmation'); ?>>Programmation</option>
			</select>
		</div>
    </div>
    <?php
}

function wtpcarre_data_save($post_id)
{
    $fields = [array('field' => 'wtpcarre_adjectif_field', 'metakey' => '_wtpcarre_adj_metakey'), 
            array('field' => 'wtpcarre_descr_field', 'metakey' => '_wtpcarre_descr_metakey'),
            array('field' => 'wtpcarre_pagelink_field', 'metakey' => '_wtpcarre_pagelink_metakey')
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
add_action('save_post', 'wtpcarre_data_save');

function wtpcarre_register_fields() {
    $fields = [array('key' => '_wtpcarre_adj_metakey', 'descr' => 'Adjectif for post-type carre'),
                array('key' => '_wtpcarre_descr_metakey', 'descr' => 'Description for post-type carre'),
                array('key' => '_wtpcarre_pagelink_metakey', 'descr' => 'Link to page for post-type carre')
	];

	foreach ($fields as $f) {
		register_post_meta( 'carre', $f['key'], array(
			'type' => 'string',
			'description' => $f['descr'],
			'show_in_rest' => true
		) );
	}
}
add_action('init', 'wtpcarre_register_fields');

?>