<?php 
if( !class_exists('WooCommerce') ){
	return;
}

class Ftc_Custom_Product_Category{

	function __construct(){
		if( is_admin() ){
			add_action( 'product_cat_add_form_fields', array($this, 'add_category_fields'), 20 );
			add_action( 'product_cat_edit_form_fields', array($this, 'edit_category_fields'), 20, 2 );
			add_action( 'created_term', array($this, 'save_category_fields'), 10, 3 );
			add_action( 'edit_term', array($this, 'save_category_fields'), 10, 3 );
		}
		
		/* Allow HTML in Category Descriptions */
		remove_filter('pre_term_description', 'wp_filter_kses');
		remove_filter('pre_link_description', 'wp_filter_kses');
		remove_filter('pre_link_notes', 'wp_filter_kses');
		remove_filter('term_description', 'wp_kses_data');
	}
	
	function add_category_fields(){
		global $ftc_default_sidebars;
		$sidebar_options = array();
		foreach( $ftc_default_sidebars as $key => $_sidebar ){
			$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
		}
		?>
		<div class="form-field ftc-product-cat-upload-field">
			<label><?php esc_html_e( 'Breadcrumbs Background Image', 'giftsshop' ); ?></label>
			<div class="preview-image">
				<?php echo wc_placeholder_img( 'thumbnail' ); ?>
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_cat_bg_breadcrumbs_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
			</div>
		</div>
		
		<div class="form-field">
			<label for="layout"><?php esc_html_e( 'Layout', 'giftsshop' ); ?></label>
			<select name="layout" id="layout">
				<option value=""><?php esc_html_e('Default', 'giftsshop') ?></option>
				<option value="0-1-0"><?php esc_html_e('Fullwidth', 'giftsshop') ?></option>
				<option value="1-1-0"><?php esc_html_e('Left Sidebar', 'giftsshop') ?></option>
				<option value="0-1-1"><?php esc_html_e('Right Sidebar', 'giftsshop') ?></option>
				<option value="1-1-1"><?php esc_html_e('Left & Right Sidebar', 'giftsshop') ?></option>
			</select>
		</div>
		
		<div class="form-field">
			<label for="left_sidebar"><?php esc_html_e( 'Left Sidebar', 'giftsshop' ); ?></label>
			<select name="left_sidebar" id="left_sidebar">
				<option value=""><?php esc_html_e('Default', 'giftsshop') ?></option>
				<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
					<option value="<?php echo esc_attr($sidebar_id); ?>"><?php echo esc_html($sidebar_name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<div class="form-field">
			<label for="right_sidebar"><?php esc_html_e( 'Right Sidebar', 'giftsshop' ); ?></label>
			<select name="right_sidebar" id="right_sidebar">
				<option value=""><?php esc_html_e('Default', 'giftsshop') ?></option>
				<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
					<option value="<?php echo esc_attr($sidebar_id); ?>"><?php echo esc_html($sidebar_name); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		
		<h4>Product In Category Tabs 2 Shortcode Options</h4>
		
		<div class="form-field ftc-product-cat-upload-field">
			<label><?php esc_html_e( 'Banner', 'giftsshop' ); ?></label>
			<div class="preview-image">
				<?php echo wc_placeholder_img( 'thumbnail' ); ?>
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_cat_shortcode_banner_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
			</div>
		</div>
		
		<div class="form-field ftc-product-cat-upload-field">
			<label><?php esc_html_e( 'Icon', 'giftsshop' ); ?></label>
			<div class="preview-image">
				<?php echo wc_placeholder_img( 'thumbnail' ); ?>
			</div>
			<div class="button-wrapper">
				<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
				<input type="hidden" name="product_cat_shortcode_icon_id" class="value-field" value="" />
				<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
				<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
			</div>
		</div>
		
		<div class="form-field">
			<label><?php esc_html_e( 'Top border color', 'giftsshop' ); ?></label>
			<input type="text" name="product_cat_shortcode_border_top_color" class="ftc-color-picker" value="" data-default-color=""/>
		</div>
		<?php
	}
	
	function edit_category_fields( $term, $taxonomy ){
		global $ftc_default_sidebars;
		$sidebar_options = array();
		foreach( $ftc_default_sidebars as $key => $_sidebar ){
			$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
		}
	
		$bg_breadcrumbs_id = get_term_meta($term->term_id, 'bg_breadcrumbs_id', true);
		$layout = get_term_meta($term->term_id, 'layout', true);
		$left_sidebar = get_term_meta($term->term_id, 'left_sidebar', true);
		$right_sidebar = get_term_meta($term->term_id, 'right_sidebar', true);
		
		$shortcode_banner_id = get_term_meta($term->term_id, 'shortcode_banner_id', true);
		$shortcode_icon_id = get_term_meta($term->term_id, 'shortcode_icon_id', true);
		$shortcode_border_top_color = get_term_meta($term->term_id, 'shortcode_border_top_color', true);
		?>
		<tr class="form-field ftc-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Breadcrumbs Background Image', 'giftsshop' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php 
					if( empty($bg_breadcrumbs_id) ){
						echo wc_placeholder_img( 'thumbnail' ); 
					}
					else{
						echo wp_get_attachment_image( $bg_breadcrumbs_id, 'thumbnail' );
					}
					?>
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_cat_bg_breadcrumbs_id" class="value-field" value="<?php echo esc_attr($bg_breadcrumbs_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Layout', 'giftsshop' ); ?></label></th>
			<td>
				<select name="layout" id="layout">
					<option value="" <?php selected($layout, ''); ?>><?php esc_html_e('Default', 'giftsshop') ?></option>
					<option value="0-1-0" <?php selected($layout, '0-1-0'); ?>><?php esc_html_e('Fullwidth', 'giftsshop') ?></option>
					<option value="1-1-0" <?php selected($layout, '1-1-0'); ?>><?php esc_html_e('Left Sidebar', 'giftsshop') ?></option>
					<option value="0-1-1" <?php selected($layout, '0-1-1'); ?>><?php esc_html_e('Right Sidebar', 'giftsshop') ?></option>
					<option value="1-1-1" <?php selected($layout, '1-1-1'); ?>><?php esc_html_e('Left & Right Sidebar', 'giftsshop') ?></option>
				</select>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Left Sidebar', 'giftsshop' ); ?></label></th>
			<td>
				<select name="left_sidebar" id="left_sidebar">
					<option value="" <?php selected($left_sidebar, ''); ?>><?php esc_html_e('Default', 'giftsshop') ?></option>
					<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
						<option value="<?php echo esc_attr($sidebar_id); ?>" <?php selected($left_sidebar, $sidebar_id); ?>><?php echo esc_html($sidebar_name); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Right Sidebar', 'giftsshop' ); ?></label></th>
			<td>
				<select name="right_sidebar" id="right_sidebar">
					<option value="" <?php selected($right_sidebar, ''); ?>><?php esc_html_e('Default', 'giftsshop') ?></option>
					<?php foreach( $sidebar_options as $sidebar_id => $sidebar_name ): ?>
						<option value="<?php echo esc_attr($sidebar_id); ?>" <?php selected($right_sidebar, $sidebar_id); ?>><?php echo esc_html($sidebar_name); ?></option>
					<?php endforeach; ?>
				</select>
			</td>
		</tr>
		
		<tr><th colspan="2"><h4>Product In Category Tabs 2 Shortcode Options</h4></th></tr>
		
		<tr class="form-field ftc-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Banner', 'giftsshop' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php
					if( empty($shortcode_banner_id) ){
						echo wc_placeholder_img( 'thumbnail' ); 
					}
					else{
						echo wp_get_attachment_image( $shortcode_banner_id, 'thumbnail' );
					}
					?>
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_cat_shortcode_banner_id" class="value-field" value="<?php echo esc_attr($shortcode_banner_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field ftc-product-cat-upload-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Icon', 'giftsshop' ); ?></label></th>
			<td>
				<div class="preview-image">
					<?php
					if( empty($shortcode_icon_id) ){
						echo wc_placeholder_img( 'thumbnail' ); 
					}
					else{
						echo wp_get_attachment_image( $shortcode_icon_id, 'thumbnail' );
					}
					?>
				</div>
				<div class="button-wrapper">
					<input type="hidden" class="placeholder-image-url" value="<?php echo esc_url( wc_placeholder_img_src() ); ?>" />
					<input type="hidden" name="product_cat_shortcode_icon_id" class="value-field" value="<?php echo esc_attr($shortcode_icon_id) ?>" />
					<button type="button" class="button upload-button"><?php esc_html_e('Upload/Add image', 'giftsshop') ?></button>
					<button type="button" class="button remove-button"><?php esc_html_e('Remove image', 'giftsshop') ?></button>
				</div>
			</td>
		</tr>
		
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Top border color', 'giftsshop' ); ?></label></th>
			<td><input type="text" name="product_cat_shortcode_border_top_color" class="ftc-color-picker" value="<?php echo esc_attr($shortcode_border_top_color) ?>" data-default-color="<?php echo esc_attr($shortcode_border_top_color) ?>"/></td>
		</tr>
		<?php
	}
	
	function save_category_fields( $term_id, $tt_id, $taxonomy ){
		if( isset($_POST['product_cat_bg_breadcrumbs_id']) ){
			update_term_meta( $term_id, 'bg_breadcrumbs_id', esc_attr( $_POST['product_cat_bg_breadcrumbs_id'] ) );
		}
	
		if( isset($_POST['layout']) ){
			update_term_meta( $term_id, 'layout', esc_attr( $_POST['layout'] ) );
		}
		
		if( isset($_POST['left_sidebar']) ){
			update_term_meta( $term_id, 'left_sidebar', esc_attr( $_POST['left_sidebar'] ) );
		}
		
		if( isset($_POST['right_sidebar']) ){
			update_term_meta( $term_id, 'right_sidebar', esc_attr( $_POST['right_sidebar'] ) );
		}
		
		if( !empty($_POST['product_cat_shortcode_banner_id']) ){
			update_term_meta( $term_id, 'shortcode_banner_id', esc_attr( $_POST['product_cat_shortcode_banner_id'] ) );
		}
		else{
			delete_term_meta( $term_id, 'shortcode_banner_id' );
		}
		
		if( !empty($_POST['product_cat_shortcode_icon_id']) ){
			update_term_meta( $term_id, 'shortcode_icon_id', esc_attr( $_POST['product_cat_shortcode_icon_id'] ) );
		}
		else{
			delete_term_meta( $term_id, 'shortcode_icon_id' );
		}
		
		if( !empty($_POST['product_cat_shortcode_border_top_color']) ){
			update_term_meta( $term_id, 'shortcode_border_top_color', esc_attr( $_POST['product_cat_shortcode_border_top_color'] ) );
		}
		else{
			delete_term_meta( $term_id, 'shortcode_border_top_color' );
		}
	}
}
new Ftc_Custom_Product_Category();
?>