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

function get_posts_of_theme($theme) {
	query_posts(array(
		'post_type' => 'propositions', 
		'tax_query' => array(
			array(
				'taxonomy' => 'themes', 
				'field' => 'slug', 
				'terms' => $theme
			)
		)
	));
}

function get_posts_of_type($mytype) {
	$args = array(
		'post_type' => 'propositions',
		'meta_key' => '_wtp_meta_key_prop_type',
		'orderby' => 'meta_value_num',
		'order' => 'ASC',
		'meta_query' => array(
			array(
				'key' => '_wtp_meta_key_prop_type',
				'value' => array($mytype),
				'compare' => 'IN',
			)
		)
	);
	query_posts( $args );
}

$propositions = array(
	array('key' => 'intervenant', 'title' => 'INTERVENANTS'),
	array('key' => 'sport', 'title' => 'SPORTS'),
	array('key' => 'soiree', 'title' => 'SOIRÉES'),
	array('key' => 'bar', 'title' => 'BAR KAWACO'),
	array('key' => 'priere', 'title' => 'ÉCOLE DE PRIÈRE'),
);
?>

<?php while (have_posts()) : the_post(); 

	// on récupère le contenu HTML de la page
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

	<!-- add class "fullpage" to have bg img occupy all the page -->
	<div class="slide_intro fullpage" data-img-desktop="<?php echo $img_src_desktop; ?>" data-img-mobile="'<?php echo $img_src; ?>">
		<div class="info">
			<div class="titre"><?php echo $ligne2; ?><br><span class="txt-white"><?php the_title(); ?></span></div>
			<div class="soustitre"><?php echo $soustitre; ?></div>
			<i class="fas fa-angle-double-down next_arrow arrow_ghost my_arrow_down" onclick="scrollDownPorgrammation('content')"></i>
		</div>
	</div>
	<?php endwhile;?>

<div id="content" class="content main">

	<h1 id="titrepage" class="titre_page">À LA CARTE</h1>

	<div class="category_container">
		<!--  <p><?php /*echo do_shortcode("[wtpsubs_inscrire-show-form]");*/ ?></p> -->

		<h3>THE BEST OF WTP</h3>
		<div class="card_container bestof">

			<?php get_posts_of_theme('bestof'); while (have_posts()) : the_post(); ?>
			<div class="card bestof" style="background:url('<?php echo get_the_post_thumbnail_url() ?>');background-size:cover;background-position:center;">
				<div class="card_title">
					<?php the_title(); ?><br>
					<span class="txt-white"><?php echo get_post_meta(get_the_ID(), '_wtpprop_adj_metakey', true) ?></span>
					<div class="card_descr"><?php echo get_post_meta(get_the_ID(), '_wtpprop_descr_metakey', true); ?></div>
				</div>
            </div>
			<?php endwhile;?>

		</div>

		<?php foreach($propositions as $prop) : ?>
		<h3><?php echo $prop['title']; ?></h3>
		<div class="list_next" onclick="scrollH('container_<?php echo $prop['key'] ?>', 300)">»</div>
		<div id="container_<?php echo $prop['key'] ?>" class="card_container">
			<div class="card_next"><span class="wtp-font wtp-next-arrow"></span></div>
			<?php get_posts_of_theme($prop['key']); while (have_posts()) : the_post(); ?>
			<div class="card" style="background:url('<?php echo get_the_post_thumbnail_url() ?>');background-size:cover;background-position:center;">
				<div class="card_title">
					<?php the_title(); ?><br>
					<span class="txt-white"><?php echo get_post_meta(get_the_ID(), '_wtpprop_adj_metakey', true); ?></span>
					<div class="card_descr"><?php echo get_post_meta(get_the_ID(), '_wtpprop_descr_metakey', true); ?></div>
				</div>
            </div>
			<?php endwhile;?>

		</div>
		<?php endforeach; ?>

	</div>
</div>

<div id="card_details"></div>

<div>
<?php
//get_sidebar();
get_footer();
?>

