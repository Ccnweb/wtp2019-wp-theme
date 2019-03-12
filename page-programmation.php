<?php
/*
 * Template Name: Programmation
 *
 * @package wtp2019
 */

get_header();
wp_enqueue_style('wtp2019-programmation');
wp_enqueue_style('wtp2019-programmation-desktop');
wp_enqueue_script('wtp2019-programmation');

?>

<div id="content" class="content main">

	<div class="category_container">

		<!-- get page content -->
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php echo apply_filters( 'the_content', $post->post_content ); ?>
		<?php endwhile;  endif;?>

	</div>
</div>

<div id="card_details"></div>

<div>
<?php
//get_sidebar();
get_footer();
?>

