<?php
add_action('widgets_init', 'ftc_banner_load_widgets');

function ftc_banner_load_widgets()
{
	register_widget('Ftc_Banner_Widget');
}

if( !class_exists('Ftc_Banner_Widget') ){
	class Ftc_Banner_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ftc-banner', 'description' => esc_html__('Display your banner', 'themeftc'));
			parent::__construct('ftc_banner', esc_html__('FTC - Banner', 'themeftc'), $widgetOps);
		}

		function widget( $args, $instance ) {
			extract($args);
			
			if( ! shortcode_exists('ftc_banner') ){
				return;
			}
			
			$shortcode_content = '[ftc_banner ';
			$shortcode_content .= ' bg_url="'.$instance['bg_url'].'"';
			$shortcode_content .= ' bg_color="'.$instance['bg_color'].'"';
			$shortcode_content .= ' position_content="'.$instance['position_content'].'"';
			$shortcode_content .= ' opacity_bg_device="'.$instance['opacity_bg_device'].'"';
			$shortcode_content .= ' link="'.$instance['link'].'"';
			$shortcode_content .= ' style_smooth="'.$instance['style_smooth'].'"';
			$shortcode_content .= ' link_title="'.$instance['link_title'].'"';
			$shortcode_content .= ' target="'.$instance['target'].'"';
			$shortcode_content .= ' extra_class="'.$instance['extra_class'].'"';
			$shortcode_content .= ']'.$instance['content'].'[/ftc_banner]';
			
			echo $before_widget;
			
			echo do_shortcode($shortcode_content);
			
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['bg_url'] 					= $new_instance['bg_url'];
			$instance['bg_color'] 					= $new_instance['bg_color'];
			$instance['content'] 					= trim($new_instance['content']);
			$instance['position_content'] 			= $new_instance['position_content'];
			$instance['opacity_bg_device'] 			= $new_instance['opacity_bg_device'];
			$instance['link'] 						= $new_instance['link'];
			$instance['style_smooth'] 				= $new_instance['style_smooth'];
			$instance['link_title'] 				= $new_instance['link_title'];
			$instance['target'] 					= $new_instance['target'];
			$instance['extra_class'] 				= $new_instance['extra_class'];
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'bg_url'			=> ''
				,'bg_color'			=> '#ffffff'
				,'content'			=> ''
				,'position_content'	=> ''
				,'opacity_bg_device'=> 0
				,'link' 			=> '#'
				,'style_smooth'		=> 'ftc-background-scale'
				,'link_title' 		=> ''						
				,'target' 			=> '_blank'
				,'extra_class'		=> ''
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
				<label for="<?php echo $this->get_field_id('bg_url'); ?>"><?php esc_html_e('Background image URL','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('bg_url'); ?>" name="<?php echo $this->get_field_name('bg_url'); ?>" value="<?php echo esc_url($instance['bg_url']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('bg_color'); ?>"><?php esc_html_e('Background color','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('bg_color'); ?>" name="<?php echo $this->get_field_name('bg_color'); ?>" value="<?php echo esc_attr($instance['bg_color']); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('content'); ?>"><?php esc_html_e('Banner content','themeftc'); ?> </label>
				<textarea class="widefat" name="<?php echo $this->get_field_name('content'); ?>" id="<?php echo $this->get_field_id('content'); ?>">
					<?php echo esc_html($instance['content']); ?>
				</textarea>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('position_content'); ?>"><?php esc_html_e('Banner content position','themeftc'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('position_content'); ?>" id="<?php echo $this->get_field_id('position_content'); ?>">
					<option value="left-top" <?php selected('left-top', $instance['position_content']) ?>><?php esc_html_e('Left Top', 'themeftc') ?></option>
					<option value="left-bottom" <?php selected('left-bottom', $instance['position_content']) ?>><?php esc_html_e('Left Bottom', 'themeftc') ?></option>
					<option value="left-center" <?php selected('left-center', $instance['position_content']) ?>><?php esc_html_e('Left Center', 'themeftc') ?></option>
					<option value="right-top" <?php selected('right-top', $instance['position_content']) ?>><?php esc_html_e('Right Top', 'themeftc') ?></option>
					<option value="right-bottom" <?php selected('right-bottom', $instance['position_content']) ?>><?php esc_html_e('Right Bottom', 'themeftc') ?></option>
					<option value="right-center" <?php selected('right-center', $instance['position_content']) ?>><?php esc_html_e('Right Center', 'themeftc') ?></option>
					<option value="center-top" <?php selected('center-top', $instance['position_content']) ?>><?php esc_html_e('Center Top', 'themeftc') ?></option>
					<option value="center-bottom" <?php selected('center-bottom', $instance['position_content']) ?>><?php esc_html_e('Center Bottom', 'themeftc') ?></option>
					<option value="center-center" <?php selected('center-center', $instance['position_content']) ?>><?php esc_html_e('Center Center', 'themeftc') ?></option>
				</select>
			</p>
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('opacity_bg_device'); ?>" name="<?php echo $this->get_field_name('opacity_bg_device'); ?>" value="1" <?php checked('1', $instance['opacity_bg_device']) ?> />
				<label for="<?php echo $this->get_field_id('opacity_bg_device'); ?>"><?php esc_html_e('Background opacity on device','themeftc'); ?> </label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('style_smooth'); ?>"><?php esc_html_e('Style smooth','themeftc'); ?> </label>
				<select class="widefat" name="<?php echo $this->get_field_name('style_smooth'); ?>" id="<?php echo $this->get_field_id('style_smooth'); ?>">
					<option value="ftc-background-scale" <?php selected('ftc-background-scale', $instance['style_smooth']) ?>><?php esc_html_e('Background Scale', 'themeftc') ?></option>
					<option value="ftc-background-scale-opacity" <?php selected('ftc-background-scale-opacity', $instance['style_smooth']) ?>><?php esc_html_e('Background Scale Opacity', 'themeftc') ?></option>
					<option value="ftc-background-scale-and-line" <?php selected('ftc-background-scale-and-line', $instance['style_smooth']) ?>><?php esc_html_e('Background Scale And Line', 'themeftc') ?></option>
					<option value="ftc-background-scale-opacity-line" <?php selected('ftc-background-scale-opacity-line', $instance['style_smooth']) ?>><?php esc_html_e('Background Scale Opacity Line', 'themeftc') ?></option>
					<option value="ftc-background-opacity-and-line" <?php selected('ftc-background-opacity-and-line', $instance['style_smooth']) ?>><?php esc_html_e('Background Opacity And Line', 'themeftc') ?></option>
					<option value="ftc-background-opacity" <?php selected('ftc-background-opacity', $instance['style_smooth']) ?>><?php esc_html_e('Background Opacity', 'themeftc') ?></option>
					<option value="ftc-eff-line" <?php selected('ftc-eff-line', $instance['style_smooth']) ?>><?php esc_html_e('Line', 'themeftc') ?></option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('extra_class'); ?>"><?php esc_html_e('Extra class','themeftc'); ?> </label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('extra_class'); ?>" name="<?php echo $this->get_field_name('extra_class'); ?>" value="<?php echo esc_attr($instance['extra_class']); ?>" />
			</p>
			<?php 
		}
	}
}

