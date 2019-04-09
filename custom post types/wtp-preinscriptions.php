<?php
/**
* Plugin Name: WTP Subscription Form
* Description: Formulaire de préinscription
* Version: 1.0.0
* Author: Communauté du Chemin Neuf
*/

// we need to have the CCN Library plugin activated
if (!defined('CCN_LIBRARY_PLUGIN_DIR')) {
    //die('global var CCN_LIBRARY_PLUGIN_DIR is not defined');
    return;
}

require_once(CCN_LIBRARY_PLUGIN_DIR . 'log.php'); use \ccn\lib\log as log;
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
            'type' => "postal_code",
        )
    );

    // == 3. == on crée tous les : metakeys, metabox/champs html, save callbacks, ...
    $metabox_options = array(
        array('title' => 'Données de pré-inscription', 'fields' => 'ALL')
    );
    create_custom_post_fields($cp_name, $cp_slug, $metabox_options, $prefix, $fields);

    // == 4. == on crée le backend REST pour POSTer des nouvelles inscriptions
	$backend_options = array(
		'on_before_save_post' => 'send_preinscription_to_samuel',
		'computed_fields' => array(
            'post_title' => function($post_values) use ($prefix) {
				return $post_values[$prefix.'_key_firstname'] . ' ' . $post_values[$prefix.'_key_name'];
			},
        ),
	);
    create_POST_backend($cp_name, $prefix, 'inscrire', $accepted_users = 'all', $fields, $backend_options); // the final action_name of the backend will be $prefix.'inscrire'
    $html_form_options = array(
        'title' => '',
        'text_btn_submit' => 'Je me pré-inscris !',
        'required' => array('@ALL'),
    );
    // ... et le formulaire HTML que l'on enregistre comme un shortcode
    create_HTML_form_shortcode($cp_name, $prefix.'_inscrire', $html_form_options, $fields); // shortcode will be $action_name.'-show-form' = "wtpsubs_inscrire-show-form"
}

function send_preinscription_to_samuel($post_values, $old_posts) {
	
	$prefix = "wtpsubs";
	
	// on remet la birthdate dans le bon sens pour Samuel (2019-01-26)
	$birthdate = $post_values[$prefix.'_key_birthdate'];
	$birthdate = implode('-', array_reverse(explode('-', $birthdate)));
	
	$data = array(
		'Dossier' => 'TLIL', 
		'firstname' => $post_values[$prefix.'_key_firstname'],
		'name' => $post_values[$prefix.'_key_name'],
		'email' => $post_values[$prefix.'_key_email'],
		'birthdate' => $birthdate,
		'postalcode' => $post_values[$prefix.'_key_postalcode'],
	);
	ksort($data); // we sort $data by key
	$data['HMAC'] = hash_hmac('sha256', json_encode($data), 'Wtp2019@!');
	$post_fields = http_build_query($data);
	
	$ch = curl_init();
    
    $url = build_samuel_endpoint_url_preinscr();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Receive server response ...

	$server_output = curl_exec($ch);

	curl_close($ch);

	$res = json_decode($server_output, true);
	return ($res !== null) ? $res: $server_output; // should return something like array('success' => false, 'errno' => 'INVALID_HMAC', 'descr' => 'Un joli message')
}

add_action( 'init', 'wtpsubs_custom_post_type', 0 );


function build_samuel_endpoint_url_preinscr($v = '') {
    if ($v == '') $v = get_option('samuel_endpoint_preinscr');
    if ($v == '') return "https://inscriptions.chemin-neuf.net/JMJ/PreinscriptionWP.php";
    return "https://inscriptions.chemin-neuf.net/".$v."/PreinscriptionWP.php";
}


// add an options page
// source : https://codex.wordpress.org/Settings_API

add_action('admin_menu', 'wtp_register_my_custom_submenu_page');
function wtp_register_my_custom_submenu_page() {
    add_submenu_page( 'edit.php?post_type=inscription', 'Config Samuel', 'Config Samuel', 'inscription_options', 'config-samuel-preinscription', 'wtp_custom_submenu_page_callback' ); 
    
    add_settings_section( 'section_samuel', 'Options Samuel', function() {
        //echo "<h3>(Options Samuel) Section Lode a te Gesù !</h3>";
    }, 'inscription_options' );
    
    add_settings_field( 'samuel_endpoint_preinscr', 'Endpoint Samuel Préinscriptions', function() {
        $options = ['JMJ', 'JMJBeta', 'JMJ_eaujames', 'JMJ_cbauge'];
        $curr_endpoint = get_option('samuel_endpoint_preinscr');

        $html = '<select id="samuel_endpoint_preinscr" name="samuel_endpoint_preinscr">';
        foreach ($options as $opt) {
            $ifselected = ($opt == $curr_endpoint) ? 'selected' : '';
            $html .= '<option value="'.$opt.'" '.$ifselected.'>'.$opt.'</option>';
        }
        $html .= '</select>';

        $full_url = build_samuel_endpoint_url_preinscr();
        $html .= '<a id="samuel_full_url"
            href="'.$full_url.'">'.$full_url.'
        </a>';
        $html .= '<script>jQuery("#samuel_endpoint_preinscr").change(function() {
            let url = "https://inscriptions.chemin-neuf.net/"+jQuery(this).val()+"/PreinscriptionWP.php";
            jQuery("#samuel_full_url").attr("href", url);
            jQuery("#samuel_full_url").text(url);
        })</script>';

        echo $html;

    }, 'inscription_options', 'section_samuel');
    
    register_setting( 'inscription_options', 'samuel_endpoint_preinscr', array() );
}
function wtp_custom_submenu_page_callback() {
	// check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?= esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg_options"
            settings_fields('inscription_options');
            // output setting sections and their fields
            do_settings_sections('inscription_options');
            // output save settings button
            submit_button('Enregistrer');
            ?>
        </form>
    </div>
    <?php
}

?>