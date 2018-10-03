<?php 
global $smof_data;
if( !empty($smof_data['ftc_enable_quickshop']) && ftc_has_woocommerce() && !class_exists('Ftc_Quickshop') && !wp_is_mobile() ){
		
	class Ftc_Quickshop{
	
		public $id;
		
		function __construct(){
			$this->add_hook();
		}
		
		function add_quickshop_button(){
			global $product;
			$href = admin_url('admin-ajax.php', is_ssl()?'https':'http') . '?ajax=true&action=load_quickshop_content&product_id='.$product->get_id();
			echo '<a class="quickview" href="'.esc_url($href).'"><i class="fa fa-eye"></i><span class="ftc-tooltip button-tooltip">'.esc_html__('Quick view', 'giftsshop').'</span></a>';
		}
		
		function add_hook(){
			global $smof_data;
			
			add_action('woocommerce_after_shop_loop_item_title', array($this, 'add_quickshop_button'), 10004 );
			add_action('woocommerce_after_shop_loop_item', array($this, 'add_quickshop_button'), 100);
			/** Product content hook **/
			add_action('ftc_quickshop_single_product_title', array($this, 'product_title'), 10);
			add_action('ftc_quickshop_single_product_summary', 'woocommerce_template_single_rating', 10);
			add_action('ftc_quickshop_single_product_summary', 'ftc_template_single_sku', 20);
			add_action('ftc_quickshop_single_product_summary', 'ftc_template_single_availability', 30);
			add_action('ftc_quickshop_single_product_summary', 'woocommerce_template_single_excerpt', 40);
			add_action('ftc_quickshop_single_product_summary', 'woocommerce_template_single_price', 50);
			add_action('woocommerce_single_product_summary',array($this, 'add_quickshop_button'), 65);
			add_action('woocommerce_after_add_to_cart_button',array($this, 'add_quickshop_button'), 110);
			if( !$smof_data['ftc_enable_catalog_mode'] ){
				add_action('ftc_quickshop_single_product_summary', 'woocommerce_template_single_add_to_cart', 60); 
			}
			
			/* Register ajax */
			add_action('wp_ajax_load_quickshop_content', array( $this, 'load_quickshop_content_callback') );
			add_action('wp_ajax_nopriv_load_quickshop_content', array( $this, 'load_quickshop_content_callback') );		
		}
		
		function product_title(){
			?>
			<h1 itemprop="name" class="product_title entry-title">
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</h1>
			<?php
		}
		
		function filter_add_to_cart_url(){
			$ref_url = wp_get_referer();
			$ref_url = remove_query_arg( array('added-to-cart','add-to-cart'), $ref_url );
			$ref_url = add_query_arg( array( 'add-to-cart' => $this->id ), $ref_url );
			return esc_url( $ref_url );
		}
		
		function filter_review_link( $review_link = '#reviews' ){
			global $product;
			$link = get_permalink( $product->get_id() );
			if( $link ){
				return trailingslashit($link) . $review_link;
			}
			else{
				return $review_link;
			}
		}
		
		function load_quickshop_content_callback(){
			global $post, $product;
			$prod_id = absint($_GET['product_id']);
			$post = get_post( $prod_id );
			$product = wc_get_product( $prod_id );

			if( $prod_id <= 0 ){
				die('Invalid Products');
			}
			if( !isset($post->post_type) || strcmp($post->post_type,'product') != 0 ){
				die('Invalid Products');
			}
			
			$this->id = $prod_id;
			
			add_filter( 'woocommerce_add_to_cart_url', array($this, 'filter_add_to_cart_url'), 10 );
			add_filter( 'ftc_woocommerce_review_link_filter', array($this, 'filter_review_link'), 10 );
			
			$_wrapper_class = "ftc-quickshop-wrapper product type-{$product->get_type()}";
			ob_start();	
			?>		
			<div itemscope itemtype="http://schema.org/Product" id="product-<?php echo get_the_ID();?>" <?php post_class( apply_filters('single_product_wrapper_class',$_wrapper_class  ) ); ?>>
					
				<div class="images-slider-wrapper">
				<?php	
					$image_ids = array();
					/* Main image */
					if ( has_post_thumbnail() ){
						$image_ids[] = get_post_thumbnail_id();				
					}
					/* Thumbnails */
					$attachment_ids = $product->get_gallery_image_ids();
					if( is_array($attachment_ids) ){
						$image_ids = array_merge($image_ids, $attachment_ids);
						if( count($image_ids) > 5 ){
							$image_ids = array_slice($image_ids, 0, 5);
						}
					}
					
					if( count($image_ids) == 0 ){ /* Always show image */
						$image_ids[] = 0;
					}
					
					?>
					<div class="image-items owl-carousel">
						<?php foreach( $image_ids as $image_id ): ?>
						<?php 
							$image_info = wp_get_attachment_image_src($image_id, 'shop_single');
							$image_link = isset($image_info[0])?$image_info[0]:wc_placeholder_img_src();
						?>
						<div class="image-item">
							<img src="<?php echo esc_url($image_link); ?>" alt="" />
						</div>
						<?php endforeach; ?>
					</div>
					
				</div>
				<!-- Product summary -->
				<div class="summary entry-summary">
					<?php do_action('ftc_quickshop_single_product_title'); ?>
					<?php do_action('ftc_quickshop_single_product_summary'); ?>
				</div>
			
			</div>
				
			<?php
			
			remove_filter( 'woocommerce_add_to_cart_url', array($this, 'filter_add_to_cart_url'), 10 );
			remove_filter( 'ftc_woocommerce_review_link_filter', array($this, 'filter_review_link'), 10 );

			$return_html = ob_get_clean();
			wp_reset_postdata();
			die($return_html);
		}
		
	}
	new Ftc_Quickshop();
}
?>