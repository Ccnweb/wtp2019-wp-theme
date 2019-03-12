<?php
/**
 * wtp2019 Theme Customizer
 *
 * @package wtp2019
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 * https://developer.wordpress.org/themes/customize-api/customizer-objects/#sections
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function wtp2019_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'wtp2019_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'wtp2019_customize_partial_blogdescription',
		) );
	}

	// ========================================
	// Animations
	// ========================================
	$wp_customize->add_section( 'animations' , array(
		'title' => __( 'Animations' ),
		'priority' => 105, // Before Widgets.
	) );

	
	// == speed in ms of the "magic words" in the welcome page
	$wp_customize->add_setting('flashing_words_speed', array(
        'default'        => 200,
        'capability'     => 'edit_theme_options',
		'type'           => 'option',
		'sanitize_callback' => function($val, $setting) {
			$val = absint($val);
			// If the input is an absolute integer, return it; otherwise, return the default
  			return ( $val ? $val : $setting->default );
		}, 
    ));
 
    $wp_customize->add_control('wtp2019_flash_words', array(
		'label'      => __('Mots flash', 'wtp2019'),
		'description'=> __('Durée d\'affichage d\'un mot-flash en page d\'accueil (ms)', 'wtp2019'),
        'section'    => 'animations',
        'settings'   => 'flashing_words_speed',
	));

	// == display duration in ms for a témoignage ==
	$wp_customize->add_setting('temoignages_duration', array(
        'default'        => 6000,
        'capability'     => 'edit_theme_options',
		'type'           => 'option',
		'sanitize_callback' => function($val, $setting) {
			$val = absint($val);
			// If the input is an absolute integer, return it; otherwise, return the default
  			return ( $val ? $val : $setting->default );
		}, 
    ));
 
    $wp_customize->add_control('wtp2019_temoignages_duration', array(
		'label'      => __('Durée témoignage', 'wtp2019'),
		'description'=> __('Durée d\'affichage des témoignages en page d\'accueil (ms)', 'wtp2019'),
        'section'    => 'animations',
        'settings'   => 'temoignages_duration',
	));


}
add_action( 'customize_register', 'wtp2019_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function wtp2019_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function wtp2019_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function wtp2019_customize_preview_js() {
	wp_enqueue_script( 'wtp2019-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'wtp2019_customize_preview_js' );





/**
 * Theme Customizer for animations
 */

/* function wtp2019_customize_register_animations( $wp_customize ) {
	// Animations
	$wp_customize->add_section( 'Animations' , array(
		'title' => __( 'Animation' ),
		'priority' => 105, // Before Widgets.
	) );
}

add_action( 'customize_register', 'wtp2019_customize_register_animations' ); */