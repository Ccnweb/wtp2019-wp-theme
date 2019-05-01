<?php
/**
 * Template Name: Home
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package wtp2019
 */

get_header(); // get_header('accueil');
wp_enqueue_script('wtp2019-accueil');
wp_enqueue_style('wtp2019-accueil-mobile');
wp_enqueue_style('wtp2019-accueil-desktop');
?>

<div id="content" class="content main">

	<!-- get page content -->
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<?php echo apply_filters( 'the_content', $post->post_content ); ?>
	<?php endwhile;  endif;?>

<?php
//get_sidebar();
get_footer();
