<?php 
/*** Metaboxes class ***/
class Ftc_Metaboxes{
	function __construct(){
		if( is_admin() ){
			add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
			add_action('save_post', array($this, 'save_meta_boxes'));
		}
	}
	
	function add_meta_boxes(){
		$datas = array(
					array(
						'id' 			=> 'page_options'
						,'label' 		=> esc_html__('Page Options', 'giftsshop')
						,'post_type'	=> 'page'
					)
					,array(
						'id'			=> 'testimonial_options'
						,'label'		=> esc_html__('Testimonial Options', 'giftsshop')
						,'post_type'	=> 'ftc_testimonial'
					)
					,array(
						'id'			=> 'team_options'
						,'label'		=> esc_html__('Member Information', 'giftsshop')
						,'post_type'	=> 'ftc_team'
					)
					,array(
						'id'			=> 'portfolio_options'
						,'label'		=> esc_html__('Portfolio Options', 'giftsshop')
						,'post_type'	=> 'ftc_portfolio'
					)
					,array(
						'id'			=> 'portfolio_gallery'
						,'label'		=> esc_html__('Portfolio Gallery', 'giftsshop')
						,'post_type'	=> 'ftc_portfolio'
						,'context'		=> 'side'
						,'priority'		=> 'low'
					)
					,array(
						'id'			=> 'logo_options'
						,'label'		=> esc_html__('Logo Options', 'giftsshop')
						,'post_type'	=> 'ftc_logo'
					)
					,array(
						'id'			=> 'product_options'
						,'label'		=> esc_html__('Product Options', 'giftsshop')
						,'post_type'	=> 'product'
					)
					,array(
						'id'			=> 'post_options'
						,'label'		=> esc_html__('Post Options', 'giftsshop')
						,'post_type'	=> 'post'
					)
					,array(
						'id'			=> 'post_gallery'
						,'label'		=> esc_html__('Post Gallery', 'giftsshop')
						,'post_type'	=> 'post'
						,'context'		=> 'side'
						,'priority'		=> 'low'
					)
				);
		$this->add_meta_box($datas);
	}
	
	function add_meta_box( $datas ){
		foreach( $datas as $data ){
			$context = 'normal';
			$priority = 'high';
			if( isset($data['context']) ){
				$context = $data['context'];
			}
			if( isset($data['priority']) ){
				$priority = $data['priority'];
			}
			add_meta_box($data['id'], $data['label'], array($this, 'meta_box_callback'), $data['post_type'], $context, $priority, array('file_name'=>$data['id']));
		}
	}
	
	function save_meta_boxes( $post_id ){
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return;
		}
		
		if( wp_is_post_revision($post_id) ){
			return;
		}
		
		if( isset($_POST['post_type']) ){
			if ( 'page' == $_POST['post_type'] ) {
				if ( !current_user_can('edit_page', $post_id) ) {
					return $post_id;
				}
			} else {
				if ( !current_user_can('edit_post', $post_id) ) {
					return $post_id;
				}
			}
		}

		foreach( $_POST as $key => $value ){
			if( strpos($key, 'ftc_') !== false ){
				update_post_meta($post_id, $key, $value);
			}
		}
	}
	
	function meta_box_callback( $post, $para ){
		$file_name = isset($para['args']['file_name'])?$para['args']['file_name']:'';
		$file = get_template_directory().'/inc/advanceoptions/'.$file_name.'.php';
		$options = array();
		if( file_exists($file) ){
			include $file;
			$options = apply_filters('ftc_metabox_options_'.$file_name, $options);
			$this->generate_field_html($options);
		}
	}

	function generate_field_html( $options ){
		global $post;
		$defaults = array(
							'id'			=> ''
							,'label' 		=> ''
							,'desc'			=> ''
							,'type'			=> 'text'
							,'options'		=> array() /* Use for select box */
							,'default'		=> ''
							);
		foreach( $options as $option ){
			$option = wp_parse_args($option, $defaults);
			
			if( $option['id'] == '' )
				continue;
			
			$post_meta_value = get_post_meta($post->ID, 'ftc_'.$option['id'], true);
			if( $post_meta_value == '' )
				$post_meta_value = $option['default'];
			$html = '';
			
			switch( $option['type'] ){
				case 'text':
					$html .= '<div class="ftc-meta-box-field">';
						$html .= '<label for="ftc_'.$option['id'].'">'.$option['label'].'</label>';
						$html .= '<div class="field">';
							$html .= '<input type="text" name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'" value="'.$post_meta_value.'" />';
							if( strlen($option['desc']) > 0 ){
								$html .= '<p class="description">'.$option['desc'].'</p>';
							}
						$html .= '</div>';
					$html .= '</div>';
				break;
				
				case 'select':
					$html .= '<div class="ftc-meta-box-field">';
						$html .= '<label for="ftc_'.$option['id'].'">'.$option['label'].'</label>';
						$html .= '<div class="field">';
							$html .= '<select name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'">';
							foreach( $option['options'] as $key => $value ){
								$html .= '<option value="'.$key.'" '.selected($key, $post_meta_value, false).'>'.$value.'</option>';
							}
							$html .= '</select>';
							if( strlen($option['desc']) > 0 ){
								$html .= '<p class="description">'.$option['desc'].'</p>';
							}
						$html .= '</div>';
					$html .= '</div>';
				break;
				
				case 'textarea':
					$html .= '<div class="ftc-meta-box-field">';
						$html .= '<label for="ftc_'.$option['id'].'">'.$option['label'].'</label>';
						$html .= '<div class="field">';
							$html .= '<textarea name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'">'.$post_meta_value.'</textarea>';
						if( strlen($option['desc']) > 0 ){
							$html .= '<p class="description">'.$option['desc'].'</p>';
						}
						$html .= '</div>';
					$html .= '</div>';
				break;
				
				case 'upload':
					$post_meta_value = trim($post_meta_value);
					$html .= '<div class="ftc-meta-box-field">';
						$html .= '<label for="ftc_'.$option['id'].'">'.$option['label'].'</label>';
						$html .= '<div class="field">';
							$html .= '<input type="text" class="upload_field" name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'" value="'.$post_meta_value.'" />';
							$html .= '<input type="button" class="ftc_meta_box_upload_button" value="'.esc_html__('Select Image', 'giftsshop').'" />';
							$html .= '<input type="button" class="ftc_meta_box_clear_image_button" value="'.esc_html__('Clear Image', 'giftsshop').'" '.($post_meta_value?'':'disabled').' />';
						if( strlen($option['desc']) > 0 ){
							$html .= '<p class="description">'.$option['desc'].'</p>';
						}
						if( $post_meta_value ){
							$html .= '<img class="preview-image" src="'.$post_meta_value.'" />';
						}
						$html .= '</div>';
					$html .= '</div>';
				break;
				
				case 'heading':
					$html .= '<div class="ftc-meta-box-field ftc-heading-box">';
						$html .= '<h2 class="ftc-meta-box-heading">'.$option['label'].'</h2>';
						if( strlen($option['desc']) > 0 ){
							$html .= '<p class="description">'.$option['desc'].'</p>';
						}
					$html .= '</div>';
				break;
				
				case 'gallery':
					$attachment_ids = array();
					if( $post_meta_value != '' ){
						$attachment_ids = explode(',', $post_meta_value);
					}
					
					$html .= '<div class="ftc-meta-box-field ftc-gallery-box">';
						$html .= '<ul class="images">';
							foreach( $attachment_ids as $attachment_id ){
							$html .= '<li class="image">';
								$html .= '<span class="del-image"></span>';
								$html .= wp_get_attachment_image( $attachment_id, 'thumbnail', false, array('data-id'=> $attachment_id) );
							$html .= '</li>';
							}
						$html .= '</ul>';
						$html .= '<input type="hidden" class="meta-value" name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'" value="'.$post_meta_value.'" />';
						$html .= '<a href="#" class="add-image">'.esc_html__('Add Images', 'giftsshop').'</a>';
					$html .= '</div>';
				break;
				
				case 'colorpicker':
					$html .= '<div class="ftc-meta-box-field">';
						$html .= '<label for="ftc_'.$option['id'].'">'.$option['label'].'</label>';
						$html .= '<div class="field">';
							$html .= '<input type="text" class="colorpicker" name="ftc_'.$option['id'].'" id="ftc_'.$option['id'].'" value="'.$post_meta_value.'" />';
							if( strlen($option['desc']) > 0 ){
								$html .= '<p class="description">'.$option['desc'].'</p>';
							}
						$html .= '</div>';
					$html .= '</div>';
				break;
				
				default:
				break;
			}
			
			echo trim($html);
		}
	}	
}

new Ftc_Metaboxes();
?>