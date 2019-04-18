<?php
/**
 * wtp2019 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wtp2019
 */

if ( ! function_exists( 'wtp2019_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wtp2019_setup() {
		add_theme_support('post-thumbnails', array(
			'post',
			'page',
			'propositions',
			'temoignages',
			'infospratiques',
		));
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on wtp2019, use a find and replace
		 * to change 'wtp2019' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'wtp2019', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails', array(
			'post',
			'page',
		));

		// Register menu locations
		register_nav_menus( array(
			'menu-principal' 	=> esc_html__( 'Primary', 'wtp2019' ),
			'menu-langues' 		=> esc_html__( 'Langues', 'wtp2019' ),
			'menu-social' 		=> esc_html__( 'Social', 'wtp2019' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'wtp2019_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

	}
endif;
add_action( 'after_setup_theme', 'wtp2019_setup' );


/** ================================================
 * SETUP TRANSLATIONS
 * ================================================= 
*/

include_once(get_template_directory() . '/translation.php');


/** ================================================
 * Manage menus
 * =================================================
 */
// source: https://wabeo.fr/hook-nav-menus/

add_filter( 'nav_menu_link_attributes', 'wtp2019_open_external_nav_link_new_window' );
function wtp2019_open_external_nav_link_new_window( $atts ) {
	
	$regex_prefix = "/^https?\:\/\/([^\.]+\.)?";
	$regex_suffix = "\./";
	$social_medias = ['facebook', 'instagram', 'twitter', 'youtube'];
	$icons = ['facebook' => 'fa-facebook-square', 'instagram' => 'fa-instagram', 'twitter' => 'fa-twitter', 'youtube' => 'fa-youtube'];

	if ( preg_match( $regex_prefix."(".implode('|', $social_medias).")".$regex_suffix, $atts['href'], $match ) ) {
		$atts['target'] = '_blank';
		$social = $match[2];
		$atts['class']  = "fab ".$icons[$social];
	}
	return $atts;
}

add_action( 'walker_nav_menu_start_el', 'wtp2019_empty_nav_links_to_span', 10, 4 );
function wtp2019_empty_nav_links_to_span( $item_output, $item, $depth, $args ) {

	$regex_prefix = "/^https?\:\/\/([^\.]+\.)?";
	$regex_suffix = "\./";
	$social_medias = ['facebook', 'instagram', 'twitter', 'youtube'];
	if (preg_match($regex_prefix."(".implode('|', $social_medias).")".$regex_suffix, $item->url)) {
		$item_output = preg_replace( '/(<a.*?>).*<\/a>/', '$1</a>', $item_output );
	}
	
	return $item_output;
}






/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wtp2019_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'wtp2019_content_width', 640 );
}
add_action( 'after_setup_theme', 'wtp2019_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wtp2019_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'wtp2019' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'wtp2019' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wtp2019_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function wtp2019_scripts() {
	wp_enqueue_style( 'wtp2019-style', get_stylesheet_uri() );
	wp_enqueue_style( 'wtp2019-style-fa', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css');
	/* wp_enqueue_style( 'wtp2019-style-onepage', get_template_directory_uri() . '/styles/onepage-scroll.css'); */
	wp_enqueue_style( 'wtp2019-style-helpers', get_template_directory_uri() . '/styles/helpers.css');
	wp_enqueue_style( 'wtp2019-style-tiles', get_template_directory_uri() . '/styles/tiles.css');
	wp_enqueue_style( 'wtp2019-style-header', get_template_directory_uri() . '/styles/header.css');
	wp_enqueue_style( 'wtp2019-style-menu', get_template_directory_uri() . '/styles/menu.css');

	/* wp_enqueue_script( 'wtp2019-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true ); */

	/* wp_enqueue_script( 'wtp2019-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true ); */

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_script('jquery');
	
	// TESTS
	/* wp_enqueue_script('wtp2019-gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.4/TweenMax.min.js', [], '2.1.1', true); */
	
	// NAVIGATION POINTS AND SCROLL
	wp_enqueue_script('alloyfinger', 'https://cdn.jsdelivr.net/npm/alloyfinger@0.1.16/alloy_finger.min.js', [], '0.1.16', true);
	wp_enqueue_script('wtp2019-ariane-points-script', get_template_directory_uri() . '/js/ariane-points.js', ['jquery', 'alloyfinger'], '002', true);
	/* wp_enqueue_script( 'wtp2019-onepage-scroll', get_template_directory_uri() . '/js/jquery.onepage-scroll.min.js', array('jquery'), '20181213', true); */
	
	// CAROUSEL
	wp_enqueue_style('owlcarousel-style', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css', ['wtp2019-main-script'], '001', 'all');
	//wp_enqueue_style('owlcarousel-theme-style', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css', [], '001', 'all');
	wp_enqueue_script('owlcarousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', ['jquery'], '2.3.4', true);
	wp_enqueue_script('wtp2019-carousel', get_template_directory_uri() . '/js/carousel.js', array('owlcarousel'), '002', true);

	// MAIN WTP SCRIPT
	global $wp_post_types;
	wp_enqueue_script( 'wtp2019-main-script', get_template_directory_uri() . '/js/main.js', array('jquery'), '003', true);
	wp_localize_script('wtp2019-main-script', 'translationAjaxData', array(
		'post_type_slugs' => array_keys($wp_post_types),
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest') //secret value created every time you log in and can be used for authentication to alter content 
	));
	
	// burger menu
	wp_enqueue_script( 'wtp2019-script-menu', get_template_directory_uri() . '/js/menu.js', array(), '20151215', true );
	// menu scrolling for infos-pratiques and horaires menus
	wp_enqueue_script( 'wtp2019-menu-scroll', get_template_directory_uri() . '/js/menu-scroll.js', array(), '001', true);

	// specific scripts and styles
	// --- accueil
	wp_register_script( 'wtp2019-accueil', get_template_directory_uri() . '/js/accueil.js', array('jquery'), '009', true);
	wp_register_style( 'wtp2019-accueil-mobile', get_template_directory_uri() . '/styles/accueil.css', array(), '001', 'all');
	wp_register_style( 'wtp2019-accueil-desktop', get_template_directory_uri() . '/styles/accueil-desktop.css', array(), '002', 'all and (min-width: 600px)');
	// --- programmation
	wp_register_script( 'wtp2019-programmation', get_template_directory_uri() . '/js/programmation.js', array(), '20181213', true);
	wp_register_style( 'wtp2019-programmation', get_template_directory_uri() . '/styles/programmation.css');
	wp_register_style( 'wtp2019-programmation-desktop', get_template_directory_uri() . '/styles/programmation-desktop.css', array(), '20181228', 'all and (min-width: 600px)');
	// --- horaires
	wp_register_script( 'wtp2019-horaires-script', get_template_directory_uri() . '/js/horaires.js', array('jquery'), '001', true);
	wp_register_style( 'wtp2019-horaires', get_template_directory_uri() . '/styles/horaires.css', [], '002', 'all');
	// --- infos-pratiques
	wp_register_style( 'wtp2019-infos-pratiques', get_template_directory_uri() . '/styles/infos-pratiques.css', [], '002', 'all');
	wp_register_style( 'wtp2019-infos-pratiques-desktop', get_template_directory_uri() . '/styles/infos-pratiques-desktop.css', array(), '20181228', 'all and (min-width: 600px)');
	wp_register_script( 'wtp2019-infos-pratiques-script', get_template_directory_uri() . '/js/infos-pratiques.js', array('jquery'));

	// ## 3 ## For connected users
	// Translation tools
	wp_register_style( 'wtp2019-translation-style', get_template_directory_uri() . '/components/translation_ui/translation.css', array(), '001', 'all');
	wp_register_script('wtp2019-translation-script', get_template_directory_uri() . '/components/translation_ui/translation.js', array(), '003');
	wp_localize_script('wtp2019-translation-script', 'edit_mode', array(
		'available' => true,
	));
	
}
add_action( 'wp_enqueue_scripts', 'wtp2019_scripts' );


// admin scripts
/* add_action( 'admin_enqueue_scripts', function() {
	wp_register_script( 'wtp2019-upload-image-metabox',  get_template_directory_uri() . '/custom post types/wtp-metabox-media/js/wtp_mediaupload_admin.js');
}); */

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Customize the TinyMCE Color Palette
/**
 * Add support for custom color palettes in Gutenberg.
 */
function tabor_gutenberg_color_palette() {
	add_theme_support(
		'editor-color-palette', array(
			array(
				'name'  => esc_html__( 'Gold Dark', '@@textdomain' ),
				'slug' => 'gold-dark',
				'color' => '#b57a26',
			),
			array(
				'name'  => esc_html__( 'Gold Light', '@@textdomain' ),
				'slug' => 'gold-light',
				'color' => '#dcbf7f',
			),
			array(
				'name'  => esc_html__( 'Black', '@@textdomain' ),
				'slug' => 'black',
				'color' => '#000',
			)
		)
	);
}
add_action( 'after_setup_theme', 'tabor_gutenberg_color_palette' );



// set upload max size

/* @ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' ); */


/* ========================================================= */
/*               ADD CUSTOM FIELDS TO POSTS                  */
/* ========================================================= */

function wtp2019_add_custom_fields_to_posts() {
	if (!defined('CCN_LIBRARY_PLUGIN_DIR')) {
			//die('global var CCN_LIBRARY_PLUGIN_DIR is not defined. You should first install the plugin "CCN Library"');
			return;
	}

	// we load here some high-level functions to create custom post types
	require_once(CCN_LIBRARY_PLUGIN_DIR . 'create-custom-post-type.php');

	$prefix = 'wtp2019';

	// on ajoute un field "ordre" pour indiquer un ordre aux articles/posts
	$field_order = [
			'id' => 'wtp2019_post_order',
			'type' => 'number',
			'description' => "Ordre de l'article",
			'html_label' => 'Ordre',
			'show_as_column' => "Ordre",
			'html_attributes' => ['min' => 0],
	];
	// on crée tous les : metakeys, metabox/champs html, save callbacks, ...
	$metabox_options = array(
			array('title' => "Ordre d'affichage de l'article", 'fields' => 'ALL')
	);
	create_custom_post_fields('post', 'post', $metabox_options, $prefix, array($field_order));
}
wtp2019_add_custom_fields_to_posts();


// =====================================================================
// import custom post types, shortcodes and gutenberg blocks
// =====================================================================

if (!function_exists('require_once_all_regex')):
function require_once_all_regex($dir_path, $regex = "") {
	/**
	 * Require once all files in $dir_path that have a filename matching $regex
	 * 
	 * @param string $dir_path
	 * @param string $regex
	 */

	if ($regex == "") $regex = "//";

	foreach (scandir($dir_path) as $filename) {
		$path = $dir_path . '/' . $filename;
		if ($filename[0] != '.' && is_file($path) && preg_match("/\.php$/i", $path) == 1 && preg_match($regex, $filename) == 1) {
			require_once $path;
		} else if ($filename[0] != '.' && is_dir($path)) {
			require_once_all_regex($path, $regex);
		}
	}
}
endif;
require_once_all_regex(get_template_directory() . '/custom post types/', "/^wtp/");

// load shortcodes
require_once_all_regex(get_template_directory() . '/shortcodes/', "");

// load gutenberg blocks
require_once_all_regex(get_template_directory() . '/blocks/', "");

// load REST endpoints
require_once_all_regex(get_template_directory() . '/rest endpoints/', "");



/* ========================================================= */
/*                 MULTIPLE FEATURED IMAGES                  */
/* ========================================================= */
/**
 * uses this plugin : https://wordpress.org/plugins/multiple-featured-images/
 */

// adds a field to add a custom featured image for mobiles
add_filter( 'kdmfi_featured_images', function( $featured_images ) {
    $args_mobile = array(
      'id' => 'featured-image-mobile',
      'desc' => 'Add a different image for mobiles',
      'label_name' => 'Mobile featured image',
      'label_set' => 'Set mobile featured image',
      'label_remove' => 'Remove mobile featured image',
      'label_use' => 'Set mobile featured image',
      'post_type' => array( 'post', 'page', 'temoignages' ),
	);
	
	$args_desktop = array(
		'id' => 'featured-image-desktop',
		'desc' => 'Add a different image for desktops',
		'label_name' => 'Desktop featured image',
		'label_set' => 'Set desktop featured image',
		'label_remove' => 'Remove desktop featured image',
		'label_use' => 'Set desktop featured image',
		'post_type' => array( 'post', 'page', 'temoignages' ),
	  );
  
	$featured_images[] = $args_mobile;
	$featured_images[] = $args_desktop;
  
    return $featured_images;
});
// to get mobile image url : kdmfi_get_featured_image_src( 'featured-image-mobile', 'full' );
