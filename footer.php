<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cloth-store
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="row">
				<div class="col-md-3 left-footercontent">
					<?php
					if(get_custom_logo()){
						the_custom_logo();
					}else{
					 ?>	<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php }
					$footertext = get_theme_mod( 'footertext' ); 
					echo "<p>".$footertext."</p>";
					?>
					
					<div class="follow-us">
						<?php do_action('cloth_footer_social_media'); ?>
					</div>
				</div>
				<div class="col-md-9">
					<div class="footer-rightcontent">
						<div class="title"> Newsletter Signup </div>
						<form class="newsletter">
							<input type="email" class="input-email" placeholder="email@example.com" required>
							<input type="submit" class="newsletter-submit" value="SUBSCRIBE">
						</form>
					
					<div class="row footer-menu-content">
						<div class="col-sm-4">
							<?php dynamic_sidebar('footer1'); ?>
						</div>
						<div class="col-sm-4">
							<?php dynamic_sidebar('footer2'); ?>
						</div>
						<div class="col-sm-4">
							<?php dynamic_sidebar('footer3'); ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		
		
		</div>
		<div class="site-info">
			<div class="container">
			<?php
				/* translators: %s: CMS name, i.e. WordPress. */
				printf( esc_html__( 'Copyright &copy; 2017 Cloth-store. All Right Reserved', 'cloth-store' ), 'WordPress' );
			?>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
