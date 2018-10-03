<?php
add_action('widgets_init', 'ftc_blogs_tabs_load_widgets');

function ftc_blogs_tabs_load_widgets()
{
	register_widget('Ftc_Blogs_Tabs_Widget');
}

if( !class_exists('Ftc_Blogs_Tabs_Widget') ){
	class Ftc_Blogs_Tabs_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ftc-blogs-tabs-widget', 'description' => esc_html__('Display popular and recent blogs in tabs', 'giftsshop'));
			parent::__construct('ftc_blogs_tabs', esc_html__('FTC - Blogs Tabs', 'giftsshop'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			if( !shortcode_exists('vc_tta_tabs') ){
				return;
			}
			
			extract($args);
			$popular_title 				= esc_attr($instance['popular_title']);
			$recent_title 				= esc_attr($instance['recent_title']);
			$limit 						= ($instance['limit'] != 0)?absint($instance['limit']):4;
			
			echo $before_widget;
			
			global $post;
			
			$popular_tab_content = '';
			$recent_tab_content = '';
			
			/* Get popular tab content */
			$args = array(
					'post_type'				=> 'post'
					,'ignore_sticky_posts'	=> 1
					,'post_status'			=> 'publish'
					,'posts_per_page'		=> $limit
					,'meta_key'				=> '_ftc_post_views_count'
					,'orderby'				=> 'meta_value_num'
					,'order'				=> 'desc'
			);
			
			$posts = new WP_Query($args);
			if( $posts->have_posts() ):
				ob_start();
				$this->get_tab_content($instance, $posts);
				$popular_tab_content = ob_get_clean();
			endif;
			
			/* Get recent tab content */
			$args = array(
					'post_type'				=> 'post'
					,'ignore_sticky_posts'	=> 1
					,'post_status'			=> 'publish'
					,'posts_per_page'		=> $limit
					,'orderby'				=> 'date'
					,'order'				=> 'desc'
			);
			
			$posts = new WP_Query($args);
			if( $posts->have_posts() ):
				ob_start();
				$this->get_tab_content($instance, $posts);
				$recent_tab_content = ob_get_clean();
			endif;
			
			$shortcode_html = '';
			$shortcode_html .= '[vc_tta_tabs ftc_style="top_border"]';
			$shortcode_html .= '[vc_tta_section title="'.$popular_title.'" tab_id="ftc-popular-tab-'.rand().'"]'.$popular_tab_content.'[/vc_tta_section]';
			$shortcode_html .= '[vc_tta_section title="'.$recent_title.'" tab_id="ftc-recent-tab-'.rand().'"]'.$recent_tab_content.'[/vc_tta_section]';
			$shortcode_html .= '[/vc_tta_tabs]';
			
			echo do_shortcode($shortcode_html);
			
			echo $after_widget;
			wp_reset_postdata();
		}
		
		function get_tab_content($instance, $posts){
			global $post;
			
			$show_thumbnail 	= empty($instance['show_thumbnail'])?0:$instance['show_thumbnail'];
			$show_title 		= empty($instance['show_title'])?0:$instance['show_title'];
			$show_date 			= empty($instance['show_date'])?0:$instance['show_date'];
			$show_author 		= empty($instance['show_author'])?0:$instance['show_author'];
			$show_comment 		= empty($instance['show_comment'])?0:$instance['show_comment'];
			$show_excerpt 		= empty($instance['show_excerpt'])?0:$instance['show_excerpt'];
			$excerpt_words 		= absint($instance['excerpt_words']);
		
			$extra_class = '';
			$extra_class .= ($show_thumbnail)?' has-image':' no-image';
			?>
			<ul class="post_list_widget <?php echo esc_attr($extra_class); ?>">
			<?php 
			while( $posts->have_posts() ): $posts->the_post(); 
				?>
					
						<li>
							<?php if( $show_thumbnail ): ?>
							<a class="thumbnail" href="<?php the_permalink(); ?>">
								<figure>
									<?php 
									if( has_post_thumbnail() ){
										the_post_thumbnail('ftc_blog_shortcode_thumb'); 
									}
									else{
										?>
										<img title="noimage" src="<?php echo get_template_directory_uri()?>/assets/images/no-image-blog.jpg" alt="<?php echo esc_attr(get_the_title()); ?>" />
										<?php 
									}
									?>
								</figure>
								<div class="smooth-thumbnail"></div>
							</a>
							<?php endif; ?>
							
							<div class="blog-info">
								<?php if( $show_title ): ?>
								<a href="<?php the_permalink() ?>" class="post-title">
									<?php the_title(); ?>
								</a>
								<?php endif; ?>
								
								<?php if( $show_excerpt ): ?>
									<div class="excerpt">
										<?php ftc_the_excerpt_max_words($excerpt_words, $post); ?>
									</div>
								<?php endif; ?>
								
								<?php if( $show_date || $show_author || $show_comment ): ?>
								<div class="post-meta">
								
									<?php if( $show_date ): ?>
									<span class="entry-date">
										<i class="fa fa-calendar"></i>
										<?php the_time( get_option('date_format') ); ?>
									</span>
									<?php endif; ?>
									
									<?php if( $show_author ): ?>
									<span class="author">
										<i class="fa fa-user"></i>
										<?php the_author_posts_link(); ?>
									</span>
									<?php endif; ?>
									
									<?php if( $show_comment ): ?>
									<span class="comment">
										<i class="fa fa-comments-o"></i>
										<span class="comment-number"><?php echo get_comments_number(); ?></span>
									</span>
									<?php endif; ?>
								</div>
								<?php endif; ?>
							</div>
						</li>
				<?php 
			endwhile;
			?>
			</ul>
			<?php 
		}

		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;		
			$instance['popular_title'] 		= strip_tags($new_instance['popular_title']);		
			$instance['recent_title'] 		= strip_tags($new_instance['recent_title']);		
			$instance['limit'] 				= absint($new_instance['limit']);
			$instance['show_thumbnail'] 	= $new_instance['show_thumbnail'];	
			$instance['show_title'] 		= $new_instance['show_title'];		
			$instance['show_date'] 			= $new_instance['show_date'];		
			$instance['show_author'] 		= $new_instance['show_author'];		
			$instance['show_comment'] 		= $new_instance['show_comment'];		
			$instance['show_excerpt'] 		= $new_instance['show_excerpt'];		
			$instance['excerpt_words'] 		= absint($new_instance['excerpt_words']);		
			
			return $instance;
		}

		function form( $instance ) {
			
			$defaults = array(
				'popular_title' 		=> 'Popular'
				,'recent_title' 		=> 'Recent'
				,'limit'				=> 4
				,'show_thumbnail' 		=> 1
				,'show_title' 			=> 1
				,'show_date' 			=> 1
				,'show_author' 			=> 0
				,'show_comment'			=> 1
				,'show_excerpt'			=> 0
				,'excerpt_words'		=> 8
			);
		
			$instance = wp_parse_args( (array) $instance, $defaults );	
			
		?>
			<p>
				<label for="<?php echo $this->get_field_id('popular_title'); ?>"><?php esc_html_e('Popular tab title', 'giftsshop'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('popular_title'); ?>" name="<?php echo $this->get_field_name('popular_title'); ?>" type="text" value="<?php echo esc_attr($instance['popular_title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('recent_title'); ?>"><?php esc_html_e('Recent tab title', 'giftsshop'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('recent_title'); ?>" name="<?php echo $this->get_field_name('recent_title'); ?>" type="text" value="<?php echo esc_attr($instance['recent_title']); ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of posts to show', 'giftsshop'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_thumbnail'); ?>" name="<?php echo $this->get_field_name('show_thumbnail'); ?>" value="1" <?php echo ($instance['show_thumbnail'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_thumbnail'); ?>"><?php esc_html_e('Show post thumbnail', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_title'); ?>" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php echo ($instance['show_title'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_title'); ?>"><?php esc_html_e('Show post title', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" value="1" <?php echo ($instance['show_date'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php esc_html_e('Show post date', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" value="1" <?php echo ($instance['show_author'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_author'); ?>"><?php esc_html_e('Show post author', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_comment'); ?>" name="<?php echo $this->get_field_name('show_comment'); ?>" value="1" <?php echo ($instance['show_comment'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_comment'); ?>"><?php esc_html_e('Show post comment', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<input type="checkbox" id="<?php echo $this->get_field_id('show_excerpt'); ?>" name="<?php echo $this->get_field_name('show_excerpt'); ?>" value="1" <?php echo ($instance['show_excerpt'])?'checked':''; ?> />
				<label for="<?php echo $this->get_field_id('show_excerpt'); ?>"><?php esc_html_e('Show post excerpt', 'giftsshop'); ?></label>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id('excerpt_words'); ?>"><?php esc_html_e('Number of words in excerpt', 'giftsshop'); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('excerpt_words'); ?>" name="<?php echo $this->get_field_name('excerpt_words'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['excerpt_words']); ?>" />
			</p>
			<?php 
		}
		
	}
}

