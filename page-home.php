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

	<!-- ########################################################## -->
	<!-- ###################### FOOTER ############################ -->
	<!-- ########################################################## -->
	<section class="section slide-footer" data-index="7">
		<div class="head">
			<img src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png"/>
			<a href="<?php echo get_home_url() ?>"><img src="<?php echo get_template_directory_uri() ?>/img/logo_next_step.png"/></a>
			<div style="width: 70px;"></div>
		</div>
		<div class="link_container">
			<a href="/programmation/" class="button white">PROGRAMMATION</a>
			<a href="https://www.youtube.com/watch?v=f_tn338ntWY" class="button white">VOIR LA VIDÉO</a>
			<a href="mailto:jeunes.france@gmail.com" class="button white">CONTACT</a>
		</div>
		<div>
            <h3 class="txt-gold"><?php echo pll__('Suivez-nous'); ?></h3>
            <!-- THE LANGUAGES MENU IS LOADED HERE -->
            <?php wp_nav_menu( array( 'theme_location' => 'menu-social', 'container_class' => 'txt-gold link_social' ) ); ?>
		</div>
	</section>

<?php
//get_sidebar();
get_footer();
