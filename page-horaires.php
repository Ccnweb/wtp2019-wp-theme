<?php
/*
 * Template Name: Propositions-horaires
 *
 * @package wtp2019
 */

get_header();
wp_enqueue_style('wtp2019-horaires');
wp_enqueue_script('wtp2019-menu-scroll');
wp_enqueue_script('wtp2019-horaires-script');

?>

<div id="content" class="content main">

    <?php while (have_posts()) : the_post(); 

    // on récupère le contenu HTML de la page
    // TODO : trop moche, faire autrement
    $html = new DOMDocument();
    libxml_use_internal_errors(true);
    $fileContent = mb_convert_encoding(get_the_content(), 'HTML-ENTITIES', 'UTF-8');
    $html->loadHTML($fileContent);
    $xpath = new DOMXpath($html);

    // on récupère l'url de l'image
    //$result = $xpath->query('//img/@src');
    //$img_src = $result->item(0)->nodeValue;
    $img_src = kdmfi_get_featured_image_src( 'featured-image-mobile', 'full' );
    $img_src_desktop = kdmfi_get_featured_image_src( 'featured-image-desktop', 'full' );

    // on récupère l'ADJECTIF (h2)
	$result = $xpath->query("//h2");
	$ligne2 = $result->item(0)->nodeValue;

    // on récupère la description/soustitre
    $result = $xpath->query("//p[@class='description']");
    $soustitre = $result->item(0)->nodeValue;
    ?>  


    <div class="slide slide_intro" data-img-desktop="<?php echo $img_src_desktop; ?>" style="background:url('<?php echo $img_src; ?>');background-size:cover;background-position:center">
        <div class="info">
            <div class="titre"><?php echo $ligne2; ?><br><span class="txt-white"><?php the_title(); ?></span></div>
            <div class="soustitre"><?php echo $soustitre; ?></div>
            <!-- <i class="fas fa-angle-double-down next_arrow arrow_ghost my_arrow_down" onclick="scrollDown('titrepage')"></i> -->
        </div>
    </div>
    <?php endwhile;?>

    <h1 id="titrepage" class="titre_page"><?php echo $ligne2; ?><br><span class="txt-black"><?php the_title(); ?></span></h1>

    <div class="slide slide-journee-type bg-arrows">
        <div class="text-content">
            <h4 class="subtitle">journée type</h4>
            <ul class="horaires">
                <li><span class="heure txt-gold">9<sup>00</sup></span>heure spi</li>
                <li><span class="heure">10<sup>15</sup></span>workshop/sports</li>
                <li><span class="heure">12<sup>15</sup></span>eucharistie</li>
                <li><span class="heure">13<sup>30</sup></span>déjeuner</li>
                <li><span class="heure">16<sup>15</sup></span>workshop/sports</li>
                <li><span class="heure">18<sup>45</sup></span>office/adoration</li>
                <li><span class="heure">21<sup>00</sup></span>soirée/after</li>
            </ul>
            <!-- <button style="margin-top:3rem;">TÉLÉCHARGER LE PROGRAMME</button> -->
        </div>
    </div>

    <div class="slide">
        <div class="semaine_container"></div>

        <?php
        $query_args = array(
            'category_name' => 'horaires',
            'post_status'   => 'publish',
            'lang'          =>  pll_current_language(),
        );
        $query = new WP_Query( $query_args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                // lien pour éditer l'article
                echo (current_user_can('edit_posts')) ? '<a class="edit_post_link" target="_blank" href="'.get_edit_post_link(get_the_ID()).'">'.__("Éditer cette page", 'ccnbtc').'&nbsp;&nbsp;<i class="fas fa-external-link-alt"></i></a>' : '';

                echo do_shortcode(get_the_content());
            }
        }
        ?>
    </div>
</div>

<div>
<?php
//get_sidebar();
get_footer();
?>

