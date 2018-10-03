<?php 
/*** FTC Testimonial ***/
if( !class_exists('Ftc_Testimonials') ){
	class Ftc_Testimonials{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'ftc_testimonial';
			$this->thumb_size_name = 'ftc_testimonial_thumb';
			$this->thumb_size_array = array(150, 150);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_filter('enter_title_here', array($this, 'enter_title_here'));
				add_filter('manage_'.$this->post_type.'_posts_columns', array($this, 'custom_column_headers'), 10);
				add_action('manage_'.$this->post_type.'_posts_custom_column', array($this, 'custom_columns'), 10, 2);
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Testimonials', 'post type general name', 'themeftc' ),
				'singular_name' 	=> esc_html_x( 'Testimonial', 'post type singular name', 'themeftc' ),
				'add_new' 			=> esc_html_x( 'Add New', 'testimonial', 'themeftc' ),
				'add_new_item' 		=> esc_html__( 'Add New Testimonial', 'themeftc' ),
				'edit_item' 		=> esc_html__( 'Edit Testimonial', 'themeftc' ),
				'new_item' 			=> esc_html__( 'New Testimonial', 'themeftc' ),
				'all_items' 		=> esc_html__( 'All Testimonials', 'themeftc' ),
				'view_item' 		=> esc_html__( 'View Testimonial', 'themeftc' ),
				'search_items' 		=> esc_html__( 'Search Testimonials', 'themeftc' ),
				'not_found' 		=> esc_html__( 'No Testimonials Found', 'themeftc' ),
				'not_found_in_trash'=> esc_html__( 'No Testimonials Found In Trash', 'themeftc' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Testimonials', 'themeftc' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> 'ftc_testimonials',
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> '',
                                'menu_icon'             => 'dashicons-format-quote',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'themeftc' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'themeftc' ),
								'search_items'        => esc_html__( 'Search Categories', 'themeftc' ),
								'all_items'           => esc_html__( 'All Categories', 'themeftc' ),
								'parent_item'         => esc_html__( 'Parent Category', 'themeftc' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'themeftc' ),
								'edit_item'           => esc_html__( 'Edit Category', 'themeftc' ),
								'update_item'         => esc_html__( 'Update Category', 'themeftc' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'themeftc' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'themeftc' ),
								'menu_name'           => esc_html__( 'Categories', 'themeftc' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('ftc_testimonial_cat', $this->post_type, $args);
		}
		
		function enter_title_here( $title ) {
			if( get_post_type() == $this->post_type ) {
				$title = esc_html__('Enter the customer\'s name here', 'themeftc');
			}
			return $title;
		}
		
		function get_image( $id, $size = '' ){
			$response = '';
			if( $size == '' ){
				$size = $this->thumb_size_array[0];
			}
			if ( has_post_thumbnail( $id ) ) {
				if ( ( is_int( $size ) || ( 0 < intval( $size ) ) ) && ! is_array( $size ) ) {
					$size = array( intval( $size ), intval( $size ) );
				} elseif ( ! is_string( $size ) && ! is_array( $size ) ) {
					$size = $this->thumb_size_array;
				}
				$response = get_the_post_thumbnail( intval( $id ), $size );
			} else {
				$gravatar_email = get_post_meta( $id, 'ftc_gravatar_email', true );
				if ( '' != $gravatar_email && is_email( $gravatar_email ) ) {
					$response = get_avatar( $gravatar_email, $size );
				}
			}

			return $response;
		}
		
		function custom_columns( $column_name, $id ){
			global $wpdb, $post;

			$meta = get_post_custom( $id );
			switch ( $column_name ) {
				case 'image':
					$value = '';
					$value = $this->get_image( $id, 40 );
					echo $value;
				break;
				default:
				break;

			}
		}
		
		function custom_column_headers( $defaults ){
			$new_columns = array( 'image' => esc_html__( 'Image', 'themeftc' ) );
			$last_item = '';
			
			if( isset($defaults['date']) ) { unset($defaults['date']); }
			if( count($defaults) > 2 ) {
				$last_item = array_slice($defaults, -1);
				array_pop($defaults);
			}
			
			$defaults = array_merge($defaults, $new_columns);
			if( $last_item != '' ) {
				foreach ( $last_item as $k => $v ) {
					$defaults[$k] = $v;
					break;
				}
			}

			return $defaults;
		}
	}
}
global $ftc_testimonials;
$ftc_testimonials = new Ftc_Testimonials();

/*** FTC Brands ***/
if( !class_exists('Ftc_Brands') ){
	class Ftc_Brands{
		public $post_type;
		public $thumb_size_name;
		public $thumb_size_array;
		
		function __construct(){
			$this->post_type = 'ftc_brand';
			$this->thumb_size_name = 'ftc_brand_thumb';
			$size_options = get_option('ftc_brand_setting', array());
			$logo_width = isset($size_options['size']['width'])?$size_options['size']['width']:240;
			$logo_height = isset($size_options['size']['height'])?$size_options['size']['height']:130;
			$this->thumb_size_array = array($logo_width, $logo_height);
			$this->add_image_size();
			
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'register_taxonomy'));
			
			if( is_admin() ){
				add_action('admin_menu', array( $this, 'register_setting_page' ));
			}
		}
		
		function add_image_size(){
			global $_wp_additional_image_sizes;
			if( !isset($_wp_additional_image_sizes[$this->thumb_size_name]) ){
				add_image_size($this->thumb_size_name, $this->thumb_size_array[0], $this->thumb_size_array[1], true);
			}
		}
		
		function register_post_type(){
			$labels = array(
				'name' 			=> esc_html_x( 'Brands', 'post type general name', 'themeftc' ),
				'singular_name' 	=> esc_html_x( 'Logo', 'post type singular name', 'themeftc' ),
				'add_new' 		=> esc_html_x( 'Add New', 'logo', 'themeftc' ),
				'add_new_item' 		=> esc_html__( 'Add New Logo', 'themeftc' ),
				'edit_item' 		=> esc_html__( 'Edit Logo', 'themeftc' ),
				'new_item' 		=> esc_html__( 'New Logo', 'themeftc' ),
				'all_items' 		=> esc_html__( 'All Brands', 'themeftc' ),
				'view_item' 		=> esc_html__( 'View Brand', 'themeftc' ),
				'search_items' 		=> esc_html__( 'Search Brands', 'themeftc' ),
				'not_found' 		=>  esc_html__( 'No Brands Found', 'themeftc' ),
				'not_found_in_trash'=> esc_html__( 'No Brands Found In Trash', 'themeftc' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Brands', 'themeftc' )
			);
			$args = array(
				'labels' 		=> $labels,
				'public' 		=> true,
				'publicly_queryable'=> true,
				'show_ui' 		=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 		=> array( 'slug' => str_replace('ftc_', '', $this->post_type) ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 		=> array( 'title', 'thumbnail' ),
				'menu_position' 	=> 5,
				'menu_icon' 		=> '',
                                'menu_icon'             => 'dashicons-format-gallery',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function register_taxonomy(){
			$args = array(
					'labels' => array(
								'name'                => esc_html_x( 'Categories', 'taxonomy general name', 'themeftc' ),
								'singular_name'       => esc_html_x( 'Category', 'taxonomy singular name', 'themeftc' ),
								'search_items'        => esc_html__( 'Search Categories', 'themeftc' ),
								'all_items'           => esc_html__( 'All Categories', 'themeftc' ),
								'parent_item'         => esc_html__( 'Parent Category', 'themeftc' ),
								'parent_item_colon'   => esc_html__( 'Parent Category:', 'themeftc' ),
								'edit_item'           => esc_html__( 'Edit Category', 'themeftc' ),
								'update_item'         => esc_html__( 'Update Category', 'themeftc' ),
								'add_new_item'        => esc_html__( 'Add New Category', 'themeftc' ),
								'new_item_name'       => esc_html__( 'New Category Name', 'themeftc' ),
								'menu_name'           => esc_html__( 'Categories', 'themeftc' )
								)
					,'public' 				=> true
					,'hierarchical' 		=> true
					,'show_ui' 				=> true
					,'show_admin_column' 	=> true
					,'query_var' 			=> true
					,'show_in_nav_menus' 	=> false
					,'show_tagcloud' 		=> false
					);
			register_taxonomy('ftc_brand_cat', $this->post_type, $args);
		}
		
		function register_setting_page(){
			add_submenu_page('edit.php?post_type='.$this->post_type, esc_html__('Logo Settings','themeftc'), 
						esc_html__('Settings','themeftc'), 'manage_options', 'ftc-logo-settings', array($this, 'setting_page_content'));
		}
		
		function setting_page_content(){
			$options_default = array(
							'size' => array(
								'width' => 240
								,'height' => 130
								,'crop' => 1
							)
							,'responsive' => array(
								'break_point'	=> array(0, 220, 320, 500, 900, 1050, 1180)
								,'item'			=> array(1, 2, 3, 4, 5, 6, 6)
							)
						);
						
			$options = get_option('ftc_brand_setting', $options_default);
			if(isset($_POST['ftc_brand_save_setting'])){
				$options['size']['width'] = $_POST['width'];
				$options['size']['height'] = $_POST['height'];
				$options['size']['crop'] = $_POST['crop'];
				$options['responsive']['break_point'] = $_POST['responsive']['break_point'];
				$options['responsive']['item'] = $_POST['responsive']['item'];
				update_option('ftc_brand_setting', $options);
			}
			if( isset($_POST['ftc_brand_reset_setting']) ){
				update_option('ftc_brand_setting', $options_default);
				$options = $options_default;
			}
			?>
			<h2 class="ftc-logo-settings-page-title"><?php esc_html_e('Logo Settings','themeftc'); ?></h2>
			<div id="ftc-logo-setting-page-wrapper">
				<form method="post">
					<h3><?php esc_html_e('Image Size', 'themeftc'); ?></h3>
					<p class="description"><?php esc_html_e('You must regenerate thumbnails after changing','themeftc'); ?></p>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image width','themeftc'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="width" value="<?php echo esc_attr($options['size']['width']); ?>" />
									<p class="description"><?php esc_html_e('Input image width (In pixels)','themeftc'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Image height','themeftc'); ?></label></th>
								<td>
									<input type="number" min="1" step="1" name="height" value="<?php echo esc_attr($options['size']['height']); ?>" />
									<p class="description"><?php esc_html_e('Input image height (In pixels)','themeftc'); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e('Crop','themeftc'); ?></label></th>
								<td>
									<select name="crop">
										<option value="1" <?php echo ($options['size']['crop']==1)?'selected':''; ?>>Yes</option>
										<option value="0" <?php echo ($options['size']['crop']==0)?'selected':''; ?>>No</option>
									</select>
									<p class="description"><?php esc_html_e('Crop image after uploading','themeftc'); ?></p>
								</td>
							</tr>
						</tbody>
					</table>
					<h3><?php esc_html_e('Slider Responsive Options', 'themeftc'); ?></h3>
					<div class="responsive-options-wrapper">
						<ul>
							<?php foreach( $options['responsive']['break_point'] as $k => $break){ ?>
							<li>
								<label><?php esc_html_e('Breakpoint from','themeftc'); ?></label>
								<input name="responsive[break_point][]" type="number" min="0" step="1" value="<?php echo (int)$break; ?>" class="small-text" />
								<span>px</span>
								<input name="responsive[item][]" type="number" min="0" step="1" value="<?php echo (int)$options['responsive']['item'][$k]; ?>" class="small-text" />
								<label><?php esc_html_e('Items','themeftc'); ?></label>
							</li>
							<?php } ?>
						</ul>
					</div>
					
					<input type="submit" name="ftc_brand_save_setting" value="<?php esc_html_e('Save changes','themeftc'); ?>" class="button button-primary" />
					<input type="submit" name="ftc_brand_reset_setting" value="<?php esc_html_e('Reset','themeftc'); ?>" class="button" />
				</form>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					"use strict";
					jQuery('input[name="ftc_brand_reset_setting"]').bind('click', function(e){
						var ok = confirm('Do you want to reset all settings?');
						if( !ok ){
							e.preventDefault();
						}
					});
				});
			</script>
			<?php
		}
	}
}
new Ftc_Brands();

/*** FTC Footer ***/
if( !class_exists('Ftc_Footer') ){
	class Ftc_Footer{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'ftc_footer';
			add_action('init', array($this, 'register_post_type'));
			add_action('wp_head', array($this, 'add_custom_css'));
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'FTC Footer', 'post type general name', 'themeftc' ),
				'singular_name' 	=> esc_html_x( 'FTC Footer', 'post type singular name', 'themeftc' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'themeftc' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'themeftc' ),
				'edit_item' 		=> esc_html__( 'Edit Footer Block', 'themeftc' ),
				'new_item' 			=> esc_html__( 'New Footer', 'themeftc' ),
				'all_items' 		=> esc_html__( 'All FTC Footer', 'themeftc' ),
				'view_item' 		=> esc_html__( 'View FTC Footer', 'themeftc' ),
				'search_items' 		=> esc_html__( 'Search FTC Footer', 'themeftc' ),
				'not_found' 		=> esc_html__( 'No FTC Footer Found', 'themeftc' ),
				'not_found_in_trash'=> esc_html__( 'No FTC Footer Found In Trash', 'themeftc' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'FTC Footer', 'themeftc' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> false,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
                                'menu_icon'             => 'dashicons-admin-customizer',
			);
			register_post_type( $this->post_type, $args );
		}
		
		function add_custom_css(){
			global $post;
			$args = array(
				'post_type' 		=> $this->post_type
				,'posts_per_page' 	=> -1
				,'post_status'		=> 'publish'
			);
			$posts = new WP_Query($args);
			if( $posts->have_posts() ){
				$custom_css = '';
				while( $posts->have_posts() ){
					$posts->the_post();
					$custom_css .= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
				}
				if( !empty($custom_css) ){
					echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
					echo $custom_css;
					echo '</style>';
				}
			}
			wp_reset_postdata();
		}
	}
}
new Ftc_Footer();

?>