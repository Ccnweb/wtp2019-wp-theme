<?php
/*
 * Template Name: Infos Pratiques
 *
 * @package wtp2019
 */

get_header();
wp_enqueue_style('wtp2019-infos-pratiques');
wp_enqueue_style('wtp2019-infos-pratiques-desktop');
wp_enqueue_script('wtp2019-infos-pratiques-script');

function get_posts_of_type($mytype) {
	$args = array(
		'post_type' => 'infospratiques',
		'meta_key' => '_wtpip_type',
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => '_wtpip_type',
				'value' => array($mytype),
				'compare' => 'IN',
			)
		)
	);
	query_posts( $args );
}
?>

<div id="content" class="content main">

    <!-- <div class="content_header"> -->
        <h1 class="titre_page">INFOS<br>PRATIQUES</h1>

        <ul class="types_infos">
            <li><a href="#part_transport">TRANSPORT</a></li>
            <li><a href="#part_logement">LOGEMENT</a></li>
            <li><a href="#part_prix">PRIX</a></li>
            <li><a href="#part_volontaires">VOLONTAIRES</a></li>
        </ul>
    <!-- </div> -->

    <div id="part_transport" class="info_part">
        <div class="info_part_img" style="background:url('<?php echo get_template_directory_uri(); ?>/img/info_transport.jpg');background-size:cover;background-position:center;background-attachment:fixed;">
            TRANSPORT
        </div>

        <div class="info_part_content">
            <?php get_posts_of_type('transport'); while (have_posts()) : the_post(); ?>
            <div class="element">
                <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                <h3 class="soustitre"><?php the_title(); ?></h3>
                <?php the_content(); ?>
            </div>
            <?php endwhile;?>
        </div>
        
    </div>

    <div id="part_logement" class="info_part">
        <div class="info_part_img" style="background:url('<?php echo get_template_directory_uri(); ?>/img/info_logement.jpg');background-size:cover;background-position:center;background-attachment:fixed;">
            LOGEMENT
        </div>

        <div class="info_part_content">
            <?php get_posts_of_type('logement'); while (have_posts()) : the_post(); ?>
            <div class="element">
                <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                <h3 class="soustitre"><?php the_title(); ?></h3>
                <?php the_content(); ?>
            </div>
            <?php endwhile;?>
        </div>
        
    </div>
    
    <div id="part_prix" class="info_part">
        <div class="info_part_img" style="background:url('<?php echo get_template_directory_uri(); ?>/img/info_transport.jpg');background-size:cover;background-position:center;background-attachment:fixed;">
            PRIX
        </div>

        <div class="info_part_content">
            <?php get_posts_of_type('prix'); while (have_posts()) : the_post(); ?>
            <div class="element">
                <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                <h3 class="soustitre"><?php the_title(); ?></h3>
                <?php the_content(); ?>
            </div>
            <?php endwhile;?>
        </div>
        
    </div>

    <div id="part_volontaires" class="info_part">
        <div class="info_part_img" style="background:url('<?php echo get_template_directory_uri(); ?>/img/info_volontaires.jpg');background-size:cover;background-position:center;background-attachment:fixed;">
            VOLONTAIRES
        </div>

        <div class="info_part_content">
            <?php get_posts_of_type('volontaires'); while (have_posts()) : the_post(); ?>
            <div class="element">
                <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="">
                <h3 class="soustitre"><?php the_title(); ?></h3>
                <?php the_content(); ?>
            </div>
            <?php endwhile;?>
        </div>
        
    </div>

</div>

<div>
<?php
//get_sidebar();
get_footer();
?>

