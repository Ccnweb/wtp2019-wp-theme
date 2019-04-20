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

get_header('accueil');
wp_enqueue_script('wtp2019-accueil');
wp_enqueue_style('wtp2019-accueil-mobile');
wp_enqueue_style('wtp2019-accueil-desktop');
?>

<div id="content" class="content main">

	<!-- ########################################################## -->
	<!-- ################# intro video background ################# -->
	<!-- ########################################################## -->
	<section class="section slide slide1" data-index="1">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_white.png">
		<div class="bg_container">
			<video  id="video_intro" class="video_player" 
					height="100%"
					preload="none" muted autoplay loop playsinline>
				<source id="mp4" src="<?php echo get_site_url() ?>/wp-content/uploads/sites/4/2019/02/Video-WTP-pour-SITE-1.mp4"/>
				<p>Your browser does not support HTML5 Video!</p>
			</video>
			<!-- <img id="intro_logo" src="img/logo_next_step.png"> -->
			<div class="logo_container">
				<img id="next_step" src="<?php echo get_template_directory_uri() ?>/img/logo_next_step_nu.png" width="165">
				<img id="intro_logo" src="<?php echo get_template_directory_uri() ?>/img/arrows_gold_right.svg" width="40">
				<svg viewBox="0 -2 100 104" preserveAspectRatio="none">
					<path d="M 100 50 L 100 0 L 0 0 L 0 100 L 100 100 L 100 90" class="svg_border"/>
				</svg>
			</div>
			<div id="intro_magic_words">
				INOUBLIABLE
			</div>
		</div>
		<!-- <img id="logo_htc" src="<?php echo get_template_directory_uri() ?>/img/logo_htc.jpg" width="60" alt=""> -->
		<div class="slide_footer">
			4 > 11 AOÛT<br>
			ABBAYE D’HAUTECOMBE
		</div>
		<i class="fas fa-angle-double-down next_arrow arrow_ghost fixed" onclick="$('.main').moveDown();"></i>
		
	</section>

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
