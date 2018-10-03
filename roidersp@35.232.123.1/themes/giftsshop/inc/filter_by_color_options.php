<?php 

class FTC_Product_Filter_By_Color{
	public $attr_slug;
	public $term_slug = 'color';
	public function __construct(){
		$this->constant();
	}
	
	function constant(){
		$this->attr_slug = '';
		
		$attribute_name  = wc_attribute_taxonomy_name( $this->term_slug );
		$attribute_name_array = wc_get_attribute_taxonomy_names();
		$taxonomy_exists = in_array($attribute_name,$attribute_name_array);
		
		if( $taxonomy_exists ){
			$this->attr_slug = $attribute_name;
			add_image_size('ftc_prod_color_thumb', 30, 30, true);
			
			$this->init_handle();
			
			add_action( 'admin_enqueue_scripts', array($this, 'register_admin_scripts') );
		}
	}
	
	function init_handle(){
		add_action( $this->attr_slug.'_edit_form_fields', array($this, 'edit_color_attribute'), 100000, 2 );
		add_action( $this->attr_slug.'_add_form_fields', array($this, 'add_color_attribute'), 100000 );
		
		add_action( 'created_term', array( $this, 'save_color_fields'), 10,3 );
		add_action( 'edit_term', array( $this, 'save_color_fields'), 10,3 );
		add_action( 'delete_term', array( $this, 'remove_color_fields'), 10,3 );
	}
	
	function register_admin_scripts(){
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
	}
	
	function edit_color_attribute( $term, $taxonomy ){
		$datas = get_term_meta($term->term_id, 'ftc_product_color_config', true);
		if( strlen($datas) > 0 ){
			$datas = unserialize($datas);	
		}else{
			$datas = array(
						'ftc_color_color' 				=> "#ffffff"
						,'ftc_color_image' 				=> 0
					);
		}
		
		if( absint($datas['ftc_color_image']) > 0 ){
			$image = wp_get_attachment_thumb_url( $datas['ftc_color_image'] );
		}
		else{
			$image = '';
		}
		?>
		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Color', 'giftsshop' ); ?></label></th>
			<td>
				<input name="ftc_color_color" id="hex-color" class="ftc_colorpicker" data-default-color="<?php echo esc_attr($datas['ftc_color_color']);?>" type="text" value="<?php echo esc_attr($datas['ftc_color_color']);?>" size="40" aria-required="true">
				<span class="description"><?php esc_html_e('Use color picker to pick one color.','giftsshop'); ?></span>
			</td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label><?php esc_html_e( 'Thumbnail Image', 'giftsshop' ); ?></label></th>
			<td>
				<input name="ftc_color_image" type="hidden" class="ftc_color_image" value="<?php echo absint($datas['ftc_color_image']);?>" />
				<img style="padding-bottom:5px;" src="<?php echo esc_url( $image ) ;?>" class="ftc_color_preview_image" width="30px" height="30px" /><br />
				<input class="ftc_color_upload_image_button button" type="button"  size="40" value="Choose Image" />
				<input class="ftc_color_remove_image_button button" type="button"  size="40" value="Remove Image" />
			</td>
		</tr>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				"use strict";
				
				jQuery('.ftc_colorpicker').wpColorPicker();
				
				if ( jQuery('input.ftc_color_image').val() == '0' )
					jQuery('.ftc_color_remove_image_button').hide();

				var file_frame;

				jQuery(document).on( 'click', '.ftc_color_upload_image_button', function( event ){

					event.preventDefault();

					if ( file_frame ) {
						file_frame.open();
						return;
					}

					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'giftsshop' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'giftsshop' ); ?>',
						},
						multiple: false
					});

					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('input.ftc_color_image').val( attachment.id );
						jQuery('.ftc_color_preview_image').attr('src', attachment.url );
						jQuery('.ftc_color_remove_image_button').show();
					});

					file_frame.open();
				});

				jQuery(document).on( 'click', '.ftc_color_remove_image_button', function( event ){
					jQuery('.ftc_color_preview_image').attr('src', '');
					jQuery('input.ftc_color_image').val('');
					jQuery('.ftc_color_remove_image_button').hide();
					return false;
				});
			});
		</script>
		<?php
	}
	
	function add_color_attribute(){
		?>
		<div class="form-field">
			<label><?php esc_html_e( 'Color', 'giftsshop' ); ?></label>
			<input name="ftc_color_color" id="hex-color" class="ftc_colorpicker" data-default-color="#ffffff" type="text" value="#ffffff" size="40" aria-required="true">
			<p class="description"><?php esc_html_e('Use color picker to pick one color.','giftsshop'); ?></p>
		</div>

		<div class="form-field">
			<label><?php esc_html_e( 'Thumbnail Image', 'giftsshop' ); ?></label>
			<input name="ftc_color_image" type="hidden" class="ftc_color_image" value="" />
			<img style="padding-bottom:5px;" src="" class="ftc_color_preview_image" width="30px" height="30px" /><br />
			<input class="ftc_color_upload_image_button button" type="button"  size="40" value="Choose Image" />
			<input class="ftc_color_remove_image_button button" type="button"  size="40" value="Remove Image" />
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function(){
				"use strict";
				
				jQuery('.ftc_colorpicker').wpColorPicker(); 
				
				if ( jQuery('input.ftc_color_image').val() == '' )
					jQuery('.ftc_color_remove_image_button').hide();

				var file_frame;

				jQuery(document).on( 'click', '.ftc_color_upload_image_button', function( event ){

					event.preventDefault();

					if ( file_frame ) {
						file_frame.open();
						return;
					}

					file_frame = wp.media.frames.downloadable_file = wp.media({
						title: '<?php esc_html_e( 'Choose an image', 'giftsshop' ); ?>',
						button: {
							text: '<?php esc_html_e( 'Use image', 'giftsshop' ); ?>',
						},
						multiple: false
					});

					file_frame.on( 'select', function() {
						var attachment = file_frame.state().get('selection').first().toJSON();

						jQuery('input.ftc_color_image').val( attachment.id );
						jQuery('.ftc_color_preview_image').attr('src', attachment.url );
						jQuery('.ftc_color_remove_image_button').show();
					});

					file_frame.open();
				});

				jQuery(document).on( 'click', '.ftc_color_remove_image_button', function( event ){
					jQuery('.ftc_color_preview_image').attr('src', '');
					jQuery('input.ftc_color_image').val('');
					jQuery('.ftc_color_remove_image_button').hide();
					return false;
				});
			});
		</script>
		<?php
	}
	
	function save_color_fields( $term_id, $tt_id, $taxonomy ){
		if( isset($_POST['ftc_color_color'], $_POST['ftc_color_image']) ){
			$datas = array();
			$datas['ftc_color_color'] = $_POST['ftc_color_color'];
			$datas['ftc_color_image'] = $_POST['ftc_color_image'];
			update_term_meta( $term_id, 'ftc_product_color_config', serialize($datas) );
		}
	}
	
	function remove_color_fields( $term_id, $tt_id, $taxonomy ){
		delete_term_meta( $term_id, 'ftc_product_color_config' );
	}
}
if( ftc_has_woocommerce() ){
	new FTC_Product_Filter_By_Color();
}
?>