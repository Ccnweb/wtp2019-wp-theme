<?php
/*
 * Template Name: Propositions
 *
 * @package wtp2019
 */

get_header();
wp_enqueue_style('wtp2019-programme');
/*wp_enqueue_script('wtp2019-proposition'); */


?>

<div id="content" class="content main bg-arrows">

    <?php while (have_posts()) : the_post(); 

    // on récupère le contenu HTML de la page
    $html = new DOMDocument();
    libxml_use_internal_errors(true);
    $fileContent = mb_convert_encoding(get_the_content(), 'HTML-ENTITIES', 'UTF-8');
    $html->loadHTML($fileContent);
    $xpath = new DOMXpath($html);

    // on récupère l'url de l'image
    $result = $xpath->query('//img/@src');
    $img_src = $result->item(0)->nodeValue;

    // on récupère l'ADJECTIF (h2)
	$result = $xpath->query("//h2");
	$ligne2 = $result->item(0)->nodeValue;

    // on récupère la description/soustitre
    $result = $xpath->query("//p[@class='description']");
    $soustitre = $result->item(0)->nodeValue;
    ?>  


    <div class="slide_intro" style="background:url('<?php echo $img_src; ?>');background-size:cover">
        <div class="info">
            <div class="titre"><?php echo $ligne2; ?><br><span class="txt-white"><?php the_title(); ?></span></div>
            <div class="soustitre"><?php echo $soustitre; ?></div>
            <i class="fas fa-angle-double-down next_arrow arrow_ghost my_arrow_down" onclick="scrollDown('titrepage')"></i>
        </div>
    </div>
    <?php endwhile;?>

    <h1 id="titrepage" class="titre_page">À LA CARTE<br><span class="txt-black"><?php the_title(); ?></span></h1>

    TODO

</div>

<div>
<?php
//get_sidebar();
get_footer();
?>

