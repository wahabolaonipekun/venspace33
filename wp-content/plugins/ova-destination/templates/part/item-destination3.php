<?php if ( !defined( 'ABSPATH' ) ) exit();

	$id = get_the_id();

	$template3_style = isset( $args['template3_style'] ) ? $args['template3_style'] : 'default' ;

	$show_link_to    = isset( $args['show_link_to_detail'] ) ? $args['show_link_to_detail'] : 'yes' ;
    
    // get size image
	$thumbnail_square     = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), 'ova_destination_square' );  
	if( $thumbnail_square == '') {
		$thumbnail_square = \Elementor\Utils::get_placeholder_image_src();
	}
    
    if ( isset($args['flag']) ) {
    	$flag = $args['flag'];
    } else {
    	$flag = 'slider';
    }

    $args_query = array(
	    'post_type' 		=> 'product',
	    'post_status' 		=> 'publish',
	    'posts_per_page' 	=> -1,
	    'meta_key' 			=> 'ovabrw_destination',
	    'meta_value' 		=> $id,
	    'meta_compare' 		=> 'LIKE',
	    'fields' 			=> 'ids',
	);

	$the_query  = new WP_Query( $args_query );
    $count      = $the_query->post_count;

    // get product from destination id
    $product_ids = ovadestination_get_product_ids_by_id($id);

    // get destination rating from product rating
    $total_rating = $average_rating = $r_count = 0;

    foreach ($product_ids as $product_id) {
    	$product = wc_get_product( $product_id );
		$rating  = $product->get_average_rating();
    	if( $rating != 0) {
    		$r_count += 1;
    	}
    	$total_rating += $rating;
	} 
    
    if ( $r_count > 0) {
    	$average_rating = $total_rating/$r_count;
    }			


?>

<?php if( $show_link_to == 'yes' ): ?>
    <a href="<?php the_permalink(); ?>">
<?php endif; ?>	

		<div class="item-destination item-destination-template3 template3-<?php echo esc_attr($template3_style); ?> item-destination-<?php echo esc_attr($flag); ?>">

			<div class="item-wrapper">
			    <!-- Image -->
				<div class="img">
			    	<img src="<?php echo esc_url( $thumbnail_square ) ?>" class="destination-img" alt="<?php the_title() ?>">
					<div class="mask"></div>
					<div class="count-tour">
					    <span class="number">
					    	<?php echo esc_html($count); ?>		    	
					    </span> 
					    <?php if ( $count != 1 ): ?>
					    	<?php esc_html_e(' Tours','ova-destination') ;?>
					    <?php else: ?>
					    	<?php esc_html_e(' Tour','ova-destination') ;?>
					    <?php endif; ?>
					</div>	
				</div>

				<!-- Info -->
				<div class="info">	

					<h3 class="name">
						<?php the_title(); ?>
					</h3>
					<div class="rating">
						<i aria-hidden="true" class="fas fa-star"></i>
						<?php if( $average_rating && $average_rating != 0 ) { ?>
						    <span class="average_rating">
						    	<?php echo sprintf('%.1f', $average_rating) ;?>		    	
						    </span> 
						<?php } else { ?>
							<?php echo esc_html($average_rating) . ' ' . esc_html__('Reviews','ova-destination') ;?> 
						<?php } ?>
					</div>

				</div>
			</div>
		
		</div>

<?php if( $show_link_to == 'yes' ): ?>
    </a>
<?php endif; ?>	