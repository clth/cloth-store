<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package cloth-store
 */

?>

	
	<!-- Slider section -->
	<section id="home-slider" class="slider-section">
		<?php echo do_shortcode("[avartanslider alias='home']"); ?>
	</section>
	<!-- End Slider section -->

	<!-- Advertisement section -->
	<?php do_action('home_page_adv_section', get_the_ID()); ?>
	

	<!-- Whats new section -->
	<?php dynamic_sidebar('homeproducts'); ?>
	<!-- End Best seller section -->
