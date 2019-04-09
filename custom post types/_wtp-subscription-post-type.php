<?php

/* // we load here some high-level functions to create custom post types
require_once('lib/create-custom-post-type.php');
// on charge la librairie pour créer des REST POST backend
require_once('lib/create-cp-rest-backend.php');
// on charge la libraire pour créer des formulaires HTML
require_once('lib/create-cp-html-forms.php'); */


// we need to have the CCN Library plugin activated
if (!defined('CCN_LIBRARY_PLUGIN_DIR')) {
    //die('global var CCN_LIBRARY_PLUGIN_DIR is not defined');
    return;
}

require_once(CCN_LIBRARY_PLUGIN_DIR . '/log.php'); use \ccn\lib\log as log;

// we load here some high-level functions to create custom post types
require_once(CCN_LIBRARY_PLUGIN_DIR . 'create-custom-post-type.php');
// on charge la librairie pour créer des REST POST backend
require_once(CCN_LIBRARY_PLUGIN_DIR . 'create-cp-rest-backend.php');
// on charge la libraire pour créer des formulaires HTML
require_once(CCN_LIBRARY_PLUGIN_DIR . 'create-cp-html-forms.php');



function wtpsubs_custom_post_type() {

    $prefix = "wtpsubs";
    $cp_name = 'inscription';

    // == 1. == on crée le custom post type 'inscription'
	$args = create_custom_post_info(
        $cp_name, 
        $genre = "f", 
        $post_icon = 'dashicons-info', 
        $supports = array( 'title', 'custom-fields') // 'custom-fields' is required if we want to retrieve the meta_keys from the rest api
    );
    register_post_type( $cp_name, $args );
    $cp_slug = $args['rewrite']['slug'];
    
    // == 2. == on définit les fields
    $fields = array(
        array( // Prénom
            'id' => $prefix.'_key_firstname', // le nom de la meta key
            'description'  => "Person first name for custom post inscription",
            'html_label' => 'Prénom',
            'type' => "text"
        ),
        array( // Nom
            'id' => $prefix.'_key_name', // le nom de la meta key
            'description'  => "Person name for custom post inscription",
            'html_label' => 'Nom',
            'type' => "text"
        ),
        array( // EMAIL
            'id' => $prefix.'_key_email', // le nom de la meta key
            'description'  => "Email address for custom post inscription",
            'unique' => true,           // tells if this field must have unique values
            'show_as_column' => "Email", // shows this field as a column in the "list" view in admin panel
            'html_label' => 'Email',
            'type' => "email"
        ),
        array( // Date de naissance
            'id' => $prefix.'_key_birthdate', // le nom de la meta key
            'description'  => "Person birth date for custom post inscription",
            'html_label' => 'Date de naissance',
            'type' => "date"
        ),
        array( // Code postal
            'id' => $prefix.'_key_postalcode', // le nom de la meta key
            'description'  => "Postal code for custom post inscription",
            'html_label' => 'Code postal',
            'type' => "postal_code"
        )
    );

    // == 3. == on crée tous les : metakeys, metabox/champs html, save callbacks, ...
    $metabox_options = array(
        ['title' => "Données d'inscription", "fields" => 'ALL'],
    );
    create_custom_post_fields($cp_name, $cp_slug, $metabox_options, $prefix, $fields);

    // == 4. == on crée le backend REST pour POSTer des nouvelles inscriptions
    $backend_options = [
        'post_status' => 'private', // 'private' because inscriptions should be private and therefore not available through the rest api without authentication !
        'computed_fields' => array(
            'post_title' => function($pv) use ($prefix) {
                return $pv[$prefix.'_key_firstname'] . ' ' . $pv[$prefix.'_key_name'];
            },
        ),
    ];
    create_POST_backend($cp_name, $prefix, 'inscrire', $accepted_users = 'all', $fields, $backend_options); // the final action_name of the backend will be $prefix.'inscrire'
    
    $html_form_options = array(
        'title' => '',
        'text_btn_submit' => 'Je me pré-inscris !',
        'required' => array('@ALL'),
        'computed_fields' => array(
            'post_title' => function($pv) use ($prefix) {
                //log\info('POST TITLE', $pv[$prefix.'_key_firstname'] . ' ' . $pv[$prefix.'_key_name']);
                return $pv[$prefix.'_key_firstname'] . ' ' . $pv[$prefix.'_key_name'];
            },
        ),
    );
    create_HTML_form_shortcode($cp_name, $prefix.'_inscrire', $html_form_options, $fields); // shortcode will be $action_name.'-show-form' = "wtpsubs_inscrire-show-form"
}

add_action( 'init', 'wtpsubs_custom_post_type', 0 );








?>