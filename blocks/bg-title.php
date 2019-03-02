<?php
/**
 * Plugin Name: Guty Blocks 2
 * Description: A rebuild of guty blocks build environment
 * Version: 2.0.0
 * Author: Jim Schofield
 * Text Domain: gutyblocks2
 * Domain Path: /languages
 *
 * @package gutyblocks2
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
 
/**
 * Enqueue block JavaScript and CSS for the editor
 */
function gutyblocks2_plugin_editor_scripts() {
 
    // Enqueue block editor JS
    wp_register_script(
        'gutyblocks2/editor-scripts',
        get_template_directory_uri(). '/blocks/bg-title-dist/build.js',
        [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ],
        filemtime( get_template_directory() . '/blocks/bg-title-dist/build.js' ) 
    );
 
    // Enqueue block editor styles
    wp_register_style(
        'gutyblocks2/stylesheets',
        get_template_directory_uri(). '/blocks/bg-title-dist/style.css',
        [ 'wp-edit-blocks' ],
        filemtime( get_template_directory() . '/blocks/bg-title-dist/style.css' ) 
    );

    register_block_type('gutyblocks2/block-library', array(
        'editor_script' => 'gutyblocks2/editor-scripts',
        'style' => 'gutyblocks2/stylesheets'   
    ));
 
}

// Hook the enqueue functions into the editor
add_action( 'init', 'gutyblocks2_plugin_editor_scripts' );

/**
 * Enqueue view scripts
 */
function gutyblocks2_plugin_view_scripts() {
    if ( is_admin() ) {
        return;
    }

    wp_enqueue_script(
		'gutyblocks2/view-scripts',
		get_template_directory_uri(). '/blocks/bg-title-dist/build.view.js',
        array( 'wp-blocks', 'wp-element', 'react', 'react-dom' )
    );
}

add_action( 'enqueue_block_assets', 'gutyblocks2_plugin_view_scripts' );