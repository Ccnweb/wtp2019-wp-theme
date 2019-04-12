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
	<link rel="icon" href="<?php get_site_icon_url(); ?>" />

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<nav id="menu"><div class="burger"><span></span><span></span><span></span></div></nav>

<!-- bouton call to action en bas à droite fixe -->
<button id="inscription" class="open_choix round">
	<!-- <span class="wtp-font wtp-check open_choix">&nbsp;NEXT STEP</span> -->
	<i class="fas fa-ticket-alt"></i>
	<span class="">&nbsp;JE M'INSCRIS !</span>
</button>

<?php include 'part-sidebar.php' ?>
<?php require "components/translation_ui/translation.php"; ?>


	
