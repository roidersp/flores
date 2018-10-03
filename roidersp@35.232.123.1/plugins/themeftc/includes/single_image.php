<?php
add_action('widgets_init', 'ftc_single_image_load_widgets');

function ftc_single_image_load_widgets()
{
	register_widget('Ftc_Single_Image_Widget');
}

if( !class_exists('Ftc_Single_Image_Widget') ){
	class Ftc_Single_Image_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ftc-single-image', 'description' => esc_html__('Display a single image', 'themeftc'));
			parent::__construct('ftc_single_image', esc_html__('FTC - Single Image', 'themeftc'), $widgetOps);
		}

		function widget( $args, $instance ) {
			extract($args);
			
			if( ! shortcode_exists('ftc_single_image') ){
				return;
			}
			
			$shortcode_content = '[ftc_single_image ';
			$shortcode_content .= ' img_url="'.$instance['img_url'].'"';
			$shortcode_content .= ' style_smooth="'.$instance['style_smooth'].'"';
			$shortcode_content .= ' link="'.$instance['link'].'"';
			$shortcode_content .= ' link_title="'.$instance['link_title'].'"';
			$shortcode_content .= ' target="'.$instance['target'].'"';
			$shortcode_content .= ']';
			
			$before_title = '<h3 class="widget-title title_sub hidden">';
			$after_title = '</h3>';
			
			echo $before_widget;
			
			echo $before_title . esc_html($instance['link_title']) . $after_title;
			
			echo do_shortcode($shortcode_content);
			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['img_url'] 				= $new_instance['img_url'];		
			$instance['style_smooth'] 			= $new_instance['style_smooth'];	
			$instance['link'] 					= $new_instance['link'];	
			$instance['link_title'] 			= $new_instance['link_title'];	
			$instance['target'] 				= $new_instance['target'];	
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'img_url'			=> ''
				,'style_smooth'		=> ''
				,'link' 			=> '#'
				,'link_title' 		=> ''						
				,'target' 			=> '_blank'
			);
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
		?>
			<p>
				<label for="<?php echo $this->get_field_id('link'); ?>"><?php esc_html_e('Link','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" value="<?php echo esc_attr($instance['link']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('link_title'); ?>"><?php esc_html_e('Link title','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('link_title'); ?>" name="<?php echo $this->get_field_name('link_title'); ?>" value="<?php echo esc_attr($instance['link_title']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('target'); ?>"><?php esc_html_e('Target','themeftc'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('target'); ?>" id="<?php echo $this->get_field_id('target'); ?>">
					<option value="_blank" <?php selected('_blank', $instance['target']) ?>><?php esc_html_e('New Window Tab', 'themeftc') ?></option>
					<option value="_self" <?php selected('_self', $instance['target']) ?>><?php esc_html_e('Self', 'themeftc') ?></option>
				</select>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('img_url'); ?>"><?php esc_html_e('Image URL','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('img_url'); ?>" name="<?php echo $this->get_field_name('img_url'); ?>" value="<?php echo esc_attr($instance['img_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('style_smooth'); ?>"><?php esc_html_e('Style Effect','themeftc'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('style_smooth'); ?>" id="<?php echo $this->get_field_id('style_smooth'); ?>">
					<option value="smooth-image" <?php selected('smooth-image', $instance['style_smooth']) ?>><?php esc_html_e('Widespread Corner', 'themeftc') ?></option>
					<option value="smooth-border-image" <?php selected('smooth-border-image', $instance['style_smooth']) ?>><?php esc_html_e('Border Scale', 'themeftc') ?></option>
					<option value="smooth-top-image" <?php selected('smooth-top-image', $instance['style_smooth']) ?>><?php esc_html_e('Fade', 'themeftc') ?></option>
				</select>
			</p>
			
			<?php 
		}
	}
}

