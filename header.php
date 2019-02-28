<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package wtp2019
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" href="<?php echo get_template_directory_uri() ?>/img/favicon/favicon.ico" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>
	<img src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png"/>
	<a href="<?php echo get_home_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/img/logo_next_step.png" style="margin-right: calc(50vw - 31px);"/></a>
</header>
<nav id="menu"><div class="burger black"><span></span><span></span><span></span></div></nav>

<!-- bouton call to action en bas à droite fixe -->
<button id="inscription" class="round">
	<span class="wtp-font wtp-check open_drapeaux">&nbsp;NEXT STEP</span>
</button>

<?php include 'part-sidebar.php' ?>


	
