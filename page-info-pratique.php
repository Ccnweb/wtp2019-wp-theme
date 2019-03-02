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

?>

<div id="content" class="content main bg-black">

    <!-- <div class="content_header"> -->
        <h1 class="titre_page">INFOS<br>PRATIQUES</h1>

        <div class="types_infos_container bg-black">
            <ul class="types_infos">
                <li>TRANSPORT</li>
                <li>LOGEMENT</li>
                <li>PRIX</li>
                <li>VOLONTAIRES</li>
            </ul>
            <div class="underline_bar">
                <div class="mobile_bar"></div>
            </div>
        </div>
    <!-- </div> -->

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <?php echo apply_filters( 'the_content', $post->post_content ); ?>
    <?php endwhile;  endif;?>

    

</div>

<div>
<?php
//get_sidebar();
get_footer();
?>

