<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cloth-store
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="hidden">
<?php
	the_post_thumbnail();
	add_editor_style();
?>
</div>
<div id="page" class="site">
	<header class="header">
		<!-- Site Logo -->
		<div class="header-logo-wrapper">
			<div class="container">
				<div class="site-branding text-center">
					<?php
					if(get_custom_logo()){
						the_custom_logo();
					}else{
					 ?>	<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php }
					?>
					
				</div><!-- .site-branding -->
			</div>
		</div>
		<!-- Navigation -->
		<nav class="navbar navbar-default main-navigation">
			<div class="container">
				<div class="navbar-header">
					 <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
						<span class="sr-only">Toggle navigation</span> 
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span> 
					 </button> 
				</div> 
				
				<div class="header-search pull-left">
					<i class="fa fa-search"></i>
					<?php dynamic_sidebar('header-product-search');	?>					
				</div>
				<div class="collapse navbar-collapse js-navbar-collapse pull-left">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
						'menu_class'	=> 'top-nav nav navbar-nav'
					) );
					?>
				</div>
				<div class="header-cart pull-right">
					<?php global $woocommerce; ?>
						<a class="header-cart-icon" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"
						title="<?php __('Cart View', 'cloth-store'); ?>">
						<?php echo $woocommerce->cart->cart_contents_count;?>
						</a>
					<i class="fa fa-shopping-cart"></i>
				</div>
			</div>
		</nav>
	</header>
	<div class="clearfix"></div>
	<div id="content" class="site-content">
