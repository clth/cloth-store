<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package cloth-store
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function clothe_store_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'clothe_store_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function clothe_store_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'clothe_store_pingback_header' );

/***********************************************************
 * 
 * Generate Random String
 * 
 ***********************************************************/

function cloth_generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Custom Widget to Display Products
 */
class Woocommerce_product_custom_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'woocommerce_product_custom_widget', // Base ID
			esc_html__( 'Woocommerce Product Listing', 'cloth-store' ), // Name
			array( 'description' => esc_html__( 'Display Products', 'cloth-store' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wpdb;
		global $woocommerce ;
		
		
		$number_of_product_display = '-1';
		$product_category = '';
		$list_product_type = '';
		$adv_image = '';
		$adv_link = '';
		if(isset($instance['product_display'])){
			$number_of_product_display = $instance['product_display'];
		}
		if(isset($instance['product_category'])){
			$product_category = $instance['product_category'];
		}
		if(isset($instance['list_product_type'])){
			$list_product_type = $instance['list_product_type'];
		}
		if(isset($instance['adv_image'])){
			$adv_image = $instance['adv_image'];
		}
		if(isset($instance['adv_link'])){
			$adv_link = $instance['adv_link'];
		}
		if(isset($instance['adv_side'])){
			$adv_side = $instance['adv_side'];
		}
		
		$adv = array(
			'image' => $adv_image,
			'adv_link' => $adv_link,
			'adv_side' => $adv_side
		);
		if($list_product_type == 2){
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => $number_of_product_display,
				'meta_key' => 'total_sales',
				'orderby' => 'meta_value_num',
				'product_cat' => $product_category,
				);
		}else{
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => $number_of_product_display,
				'orderby' => 'date',
				'order' => 'DESC',
				'product_cat' => $product_category,
				);
		}
		
		$product_loop = new WP_Query( $args );	
		?>
	  
<?php if ( $product_loop->have_posts() ) { ?>
		<?php 
		while ( $product_loop->have_posts() ) : $product_loop->the_post();
			?>
			<?php $url = wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); 
			$price = get_post_meta( get_the_ID(), '_regular_price');
			$sale = get_post_meta( get_the_ID(), '_sale_price');
			
			$product_detail[] = array(
				'pid'				=> get_the_ID(),
                'image'           	=> $url,
                'link'              => get_permalink(),
                'name'   			=> get_the_title(),
				'price'  			=> $price[0],
				'sale'  			=> $sale[0]
				);
			
			endwhile;  
			wp_reset_query();
			// Random number for theme colors
			$random_id = cloth_generateRandomString();
			do_action('cloth_store_product_single_block',$instance['title'], $random_id , $product_detail, $adv);
			?>
		
<?php 

	}
		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
		$product_display = $instance['product_display'] ;
		$selected_category = $instance['product_category'];
		$list_product_type = $instance['list_product_type'];
		$adv_link = $instance['adv_link'];
		$adv_side = $instance['adv_side'];
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'cloth-store' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		
		<p>
			 <label for="<?php echo esc_attr( $this->get_field_id( 'product_display' ) ); ?>"><?php esc_attr_e( 'No. of Products:', 'cloth-store' ); ?></label> 
			 <input class="widefat" type ="number" id ="<?php echo esc_attr( $this->get_field_id( 'product_display' ) ); ?>" name ="<?php echo esc_attr( $this->get_field_name( 'product_display' ) ); ?>" value="<?php echo esc_attr( $product_display ); ?>"/>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'product_category' ) ); ?>"><?php esc_attr_e( 'Category:', 'cloth-store' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'product_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_category' ) ); ?>">
				<option value=""><?php esc_attr_e( 'All Category', 'cloth-store' ); ?></option>
				
				<?php
					$product_categories = get_terms( 'product_cat' );
					$count = count($product_categories);
					if ( $count > 0 ){
						foreach ( $product_categories as $product_category ) {
							if($product_category->slug === $selected_category){
								echo '<option value="'.$product_category->slug.'" selected>' . $product_category->name . '</option>';
							}else{
								echo '<option value="'.$product_category->slug.'">' . $product_category->name . '</option>';
							}
						}
					}
				?>
			</select>
        </p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'list_product_type' ) ); ?>"><?php esc_attr_e( 'Show:', 'cloth-store' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'list_product_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'list_product_type' ) ); ?>">
				<option value="1" <?php echo ($list_product_type==='1')?'selected':''; ?>><?php esc_attr_e( 'New Products', 'cloth-store' ); ?></option>
				<option value="2" <?php echo ($list_product_type==='2')?'selected':''; ?>><?php esc_attr_e( 'Best Selling', 'cloth-store' ); ?></option>
			</select>
        </p>
		
		<p>	
			<label for="<?php echo esc_attr( $this->get_field_id( 'adv_image' ) ); ?>"><?php esc_attr_e( 'Advertisement Image:', 'cloth-store' ); ?></label> 
		<?php
			pco_image_field( $this, $instance, array( 'field' => 'adv_image') );
		?>
		</p>
		
		<p>	
			<label for="<?php echo esc_attr( $this->get_field_id( 'adv_link' ) ); ?>"><?php esc_attr_e( 'Advertisement Link:', 'cloth-store' ); ?></label> 
			<input class="widefat" type ="url" id ="<?php echo esc_attr( $this->get_field_id( 'adv_link' ) ); ?>" name ="<?php echo esc_attr( $this->get_field_name( 'adv_link' ) ); ?>" value="<?php echo esc_attr( $adv_link ); ?>"/>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'adv_side' ) ); ?>"><?php esc_attr_e( 'Show on:', 'cloth-store' ); ?></label> 
			<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'adv_side' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'adv_side' ) ); ?>">
				<option value="1" <?php echo ($adv_side==='1')?'selected':''; ?>><?php esc_attr_e( 'Right', 'cloth-store' ); ?></option>
				<option value="2" <?php echo ($adv_side==='2')?'selected':''; ?>><?php esc_attr_e( 'Left', 'cloth-store' ); ?></option>
			</select>
       </p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['product_display'] = $new_instance['product_display'] ;
		$instance['product_category'] = $new_instance['product_category'];
		$instance['list_product_type'] = $new_instance['list_product_type'] ;
		$instance['adv_image'] = $new_instance['adv_image'] ;
		$instance['adv_link'] = $new_instance['adv_link'] ;
		$instance['adv_side'] = $new_instance['adv_side'] ;
		return $instance;
	}

} 

// Register register_product_selling_Widget widget
function register_product_selling_Widget() {
    register_widget( 'woocommerce_product_custom_widget' );
}
add_action( 'widgets_init', 'register_product_selling_Widget' );


add_action('cloth_store_product_single_block', 'product_single_block', 10, 4);
function product_single_block($title, $random_id, $product_detail = array(), $adv = array()){
	$advimage = wp_get_attachment_image_src($adv['image'], 'full');
	$image_per_slider = '4';
	if($advimage){
		$rowclass = 'row';
		$sliderclass = 'col-md-9 col-sm-8 padding-10';
		$image_per_slider = '3';
		$advdiv = '<div class="col-sm-4 col-md-3 padding-10">
						<a href="'.$adv['adv_link'].'" >
						<div class="adv-image">
							<div class="corner-img"></div>
							<img src="'.$advimage[0].'" class="img-responsive">
						</div>
						</a>
					</div>';
		
	}
	?>
	<section id="new-products-section">
		<div class="container">
			<?php if($title){ ?>
				<h2 class="section-title"> 
				<span class="color-box green"></span>
				<span class="color-box blue"></span>
				<span class="color-box purple"></span>
			<?php echo $title; ?> </h2>
			<?php } ?>
			<div class="<?php echo $rowclass; ?>">
			<?php if($adv['adv_side'] == 2){
				echo $advdiv;
			} ?>
			<div class="product-slider <?php echo $sliderclass; ?>">
				<div class="products-container product-slider owl-carousel owl-theme <?php echo $random_id; ?>">
					<?php foreach ($product_detail as $product_value){

						if($product_value['sale'] !=""){
							$product_currency_symbol = get_woocommerce_currency_symbol();
							$product_price = $product_value['sale'];
							$price_div = '<div class="product-price">'.$product_currency_symbol.''.$product_value['sale'].' <span class="sale-price">'.$product_currency_symbol.''.$product_value['price'].'</span></div>';
						}else{
							$product_currency_symbol = get_woocommerce_currency_symbol();
							$product_price = $product_value['price'] ;
							$price_div = '<div class="product-price">'.$product_currency_symbol.''.$product_value['price'].'</div>';
						}
					?>
					
					<div class="single-product">
						<div class="product-wrapper">
							<a href="<?php echo $product_value['link']; ?>" title="<?php echo $product_value['name']; ?>">
								<div class="product-img">
									<div class="img-wrap">
										<img src="<?php echo $product_value['image']; ?>" class="img-responsive">
									</div>
								</div>
								<div class="product-detail text-center">
								
								
									<div class="add-to-cart">
									<button type="submit"
										data-quantity="1" data-product_id="<?php echo $product_value['pid']; ?>"
										class="button alt ajax_add_to_cart add_to_cart_button product_type_simple">
										<i class="fa fa-shopping-cart"></i>
									</button>
									</div>
									<div class="product-name"> <?php echo $product_value['name']; ?>  </div>
									<?php echo $price_div; ?>
								</div>
							</a>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php if($adv['adv_side'] == 1){
				echo $advdiv;
			} ?>
			</div>
		</div>
	</section>
	<script>
		/*Slider*/
		jQuery(window).load(function () {
			jQuery('.product-slider.<?php echo $random_id; ?>').owlCarousel({
				loop: true,
				margin: 20,
				dots: true,
				nav: false,
				responsiveClass: true,
				responsive: {
				  320: {
					items: 1,
				  },
				  480: {
					items: 2,
				  },
				  600: {
					items: 2,
				  },
				  1000: {
					items: <?php echo $image_per_slider; ?>,
					loop: false,
					margin: 20
				  }
				}
			});});
	</script>
	<?php
}

/*Register widgets area*/
if ( function_exists('register_sidebar') ){
  register_sidebar(array(
		'name' => 'Footer Menu 1',
		'id' => 'footer1',
		'before_widget' => '<div class = "footer1-menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>',
	  )
	);
	
	register_sidebar(array(
		'name' => 'Footer Menu 2',
		'id' => 'footer2',
		'before_widget' => '<div class = "footer2-menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>',
	  )
	);
	
	register_sidebar(array(
		'name' => 'Footer Menu 3',
		'id' => 'footer3',
		'before_widget' => '<div class = "footer3-menu">',
		'after_widget' => '</div>',
		'before_title' => '<div class="title">',
		'after_title' => '</div>',
	  )
	);
	
	register_sidebar(array(
		'name' => 'Home Product Listing',
		'id' => 'homeproducts',
		'before_widget' => '<div class = "productslisting">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	  )
	);
	
	register_sidebar(array(
		'name' => 'Product Search',
		'id' => 'header-product-search',
		'before_widget' => '<div class = "productsearch">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	  )
	);

}

/*Home Page advertisement section*/
add_action('home_page_adv_section', 'home_adv_section');
function home_adv_section($postid){
	
		// check if the repeater field has rows of data
		if( have_rows('advertisement_section', $postid) ):
			?>
			<section id="adv-section">
			<?php
			// loop through the rows of data
			while ( have_rows('advertisement_section', $postid) ) : the_row();

				// display a sub field value
				$img = get_sub_field('image_adv', $postid);
				$link_to_redirect = get_sub_field('link_to_redirect', $postid);
				?>
					<div class="col-sm-4 adv-image">
						<a href="<?php echo $link_to_redirect; ?>">
							<div class="corner-img"></div>
							<img src="<?php echo $img; ?>" class="img-responsive">
						</a>
					</div>
				
				<?php
			endwhile;
			?>
				</section>
			<?php
		endif;
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function social_media_options( $wp_customize ) {
	$wp_customize->add_section( 'social_media_options_settings' , array(
        'title'    => __( 'Social Media & Store Info.', 'cloth-store' ),
        'priority' => 30,
		'description' => __('Add Social Media URL', 'cloth-store')
    ) );   

    $wp_customize->add_setting( 'facebook', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'facebook', array(
	  'type' => 'text',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Facebook'
	) );
	
	$wp_customize->add_setting( 'twitter', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'twitter', array(
	  'type' => 'text',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Twitter'
	) );
	
	$wp_customize->add_setting( 'googleplus', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'googleplus', array(
	  'type' => 'text',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Google Plus'
	) );
	
	$wp_customize->add_setting( 'linkedin', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'linkedin', array(
	  'type' => 'text',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Linkedin'
	) );
	
	$wp_customize->add_setting( 'pinterest', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'pinterest', array(
	  'type' => 'text',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Pinterest'
	) );
	
	$wp_customize->add_setting( 'footertext', array(
	  'capability' => 'edit_theme_options',
	  'sanitize_callback' => 'sanitize_text_field',
	) );
	
	$wp_customize->add_control( 'footertext', array(
	  'type' => 'textarea',
	  'section' => 'social_media_options_settings', // Add a default or your own section
	  'label' => 'Store Information'
	) );
}
add_action( 'customize_register', 'social_media_options' );

/*Footer social media*/
add_action('cloth_footer_social_media', 'footer_social_media');
function footer_social_media(){
	$facebook = get_theme_mod( 'facebook' ); 
	$twitter = get_theme_mod( 'twitter' ); 
	$googleplus = get_theme_mod( 'googleplus' ); 
	$linkedin = get_theme_mod( 'linkedin' ); 
	$pinterest = get_theme_mod( 'pinterest' );
	if($facebook || $twitter || $googleplus || $linkedin || $pinterest){
	?>
		<div class="title"> Follow us </div>
		<ul>
			<?php if($facebook){ ?>
			<li> <a href="<?php echo esc_url($facebook); ?>" target="blank"> <i class="fa fa-facebook"></i></a> </li>
			<?php } ?>
			<?php if($twitter){ ?>
			<li> <a href="<?php echo esc_url($twitter); ?>" target="blank"> <i class="fa fa-twitter"></i></a> </li>
			<?php } ?>
			<?php if($googleplus){ ?>
			<li> <a href="<?php echo esc_url($googleplus); ?>" target="blank"> <i class="fa fa-google-plus"></i></a> </li>
			<?php } ?>
			<?php if($linkedin){ ?>
			<li> <a href="<?php echo esc_url($linkedin); ?>" target="blank"> <i class="fa fa-linkedin"></i></a> </li>
			<?php } ?>
			<?php if($pinterest){ ?>
			<li> <a href="<?php echo esc_url($pinterest); ?>" target="blank"> <i class="fa fa-pinterest"></i></a> </li>
			<?php } ?>
		</ul>
	<?php
	}
}
?>
