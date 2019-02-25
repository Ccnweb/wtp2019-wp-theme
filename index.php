<?php
/**
 * The main template file
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

	<!-- ########################################################## -->
	<!-- ################# TEXTE D'INTRO ########################## -->
	<!-- ########################################################## -->
	<section class="section" data-index="2">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png">
		<div class="section_content bg-arrows flexcc flexcol">
			<div id="quote_lacroix">
				<div><a href="https://www.la-croix.com/Religion/Actualite/Vacances-de-reve-pour-jeunes-cathos-2014-08-08-1189538">
					Vacances de rêve pour jeunes cathos</a></div>
				<img src="<?php echo get_template_directory_uri() ?>/img/logo_lacroix.png" alt="" width="70">
			</div>

			<?php query_posts(array("category_name" => "accueil")); while (have_posts()) : the_post(); ?>
			<div class="subtitle">
				<?php the_title(); ?>
			</div>
			<div class="text_content">
				<?php the_content() ?>
			</div>
			<?php endwhile; ?>

			<!-- <a class="button" href="https://www.youtube.com/watch?v=f_tn338ntWY">VOIR LA VIDÉO</a> -->
			<button data-modal-target="youtube_modal">VOIR LA VIDÉO</button>

		</div>
		<div class="modal" id="youtube_modal">
			<iframe width="560" height="315" 
				src="https://www.youtube.com/embed/f_tn338ntWY" 
				frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
				style="width: 100vw;height: 90vh;margin-top: 5vh;"
				allowfullscreen></iframe>
		</div>
		<i class="fas fa-angle-double-down next_arrow arrow_ghost arrow_black fixed" onclick="$('.main').moveDown();"></i>
	</section>

	<!-- ############################################################ -->
	<!-- ################### CARRES DORES ########################### -->
	<!-- ############################################################ -->
	<section class="section bg-black" data-index="3">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_white.png">
		<div id="carres_propositions" class="carre_container"><!-- contenu généré onload par jQuery dans accueil.js --></div>
	</section>

	<!-- ############################################################ -->
	<!-- ##################### TÉMOIGNAGE ########################### -->
	<!-- ############################################################ -->
	<section class="section" data-index="4">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png">
		<div id="temoignages" class="section_content">
			
			<div class="img_content bg-arrows" style="background-size: cover;"></div>
			<div class="golden_box">
				<span class="quote"><i class="fas fa-spinner fa-spin"></i></span>
			</div>
		</div>
	</section>

	<!-- ########################################################## -->
	<!-- ############# THEME / NOUVELLE FORMULE ################### -->
	<!-- ########################################################## -->
	<section class="section" data-index="5">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png">
		<div class="section_content bg-arrows flexcc flexcol">

			<?php query_posts(array("category_name" => "letheme")); while (have_posts()) : the_post(); ?>
			<div class="subtitle">
				<?php the_title(); ?>
			</div>
			<div class="text_content">
				<?php the_content() ?>
			</div>
			<?php endwhile; ?>

		</div>
		<i class="fas fa-angle-double-down next_arrow arrow_ghost arrow_black fixed" onclick="$('.main').moveDown();"></i>
	</section>


	<!-- ########################################################## -->
	<!-- ############# INSPIRATION ################### -->
	<!-- ########################################################## -->
	<!-- <section class="section bg-black" data-index="6">
		<img class="logo_wtp" src="<?php echo get_template_directory_uri() ?>/img/logo_wtp_gold.png">
		<div class="section_content row">

			<?php query_posts(array("category_name" => "inspiration")); while (have_posts()) : the_post(); ?>
			<div class="text_content col-sm-6" style="background:url('<?php the_post_thumbnail_url() ?>')">
				<?php the_content() ?>
			</div>
			<?php endwhile; ?>

		</div>
		<i class="fas fa-angle-double-down next_arrow arrow_ghost arrow_black fixed" onclick="$('.main').moveDown();"></i>
	</section> -->

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
			<h3 class="txt-gold">SUIVEZ-NOUS</h3>
			<div class="flexcc txt-gold link_social">
				<a href="https://www.facebook.com/festivalwelcometoparadise/" class="fab fa-facebook-square"></a>
				<a href="https://www.instagram.com/festival.welcometoparadise/" class="fab fa-instagram"></a>
				<a href="https://twitter.com/festival_wtp" class="fab fa-twitter"></a>
				<a href="https://www.youtube.com/user/Jeunes1830ans" class="fab fa-youtube"></a>
			</div>
		</div>
	</section>

<?php
//get_sidebar();
get_footer();
