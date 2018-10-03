<?php
add_action('widgets_init', 'ftc_blogs_load_widgets');

function ftc_blogs_load_widgets()
{
	register_widget('Ftc_Blogs_Widget');
}

if( !class_exists('Ftc_Blogs_Widget') ){
	class Ftc_Blogs_Widget extends WP_Widget {

		function __construct() {
			$widgetOps = array('classname' => 'ftc-blogs-widget', 'description' => esc_html__('Display blogs on site', 'giftsshop'));
			parent::__construct('ftc_blogs', esc_html__('FTC - Blogs', 'giftsshop'), $widgetOps);
		}

		function widget( $args, $instance ) {
			
			extract($args);
			$title 				= apply_filters('widget_title', $instance['title']);
			$limit 				= ($instance['limit'] != 0)?absint($instance['limit']):4;
			$order 				= $instance['order'];
			$orderby 			= $instance['orderby'];
			$categories 		= isset($instance['categories'])?(array)$instance['categories']:array();
			$show_thumbnail 	= empty($instance['show_thumbnail'])?0:$instance['show_thumbnail'];
			$show_title 		= empty($instance['show_title'])?0:$instance['show_title'];
			$show_date 			= empty($instance['show_date'])?0:$instance['show_date'];
			$show_author 		= empty($instance['show_author'])?0:$instance['show_author'];
			$show_comment 		= empty($instance['show_comment'])?0:$instance['show_comment'];
			$show_excerpt 		= empty($instance['show_excerpt'])?0:$instance['show_excerpt'];
			$excerpt_words 		= absint($instance['excerpt_words']);
			$is_slider 			= empty($instance['is_slider'])?0:$instance['is_slider'];
			$row 				= ($instance['row'] != 0)?absint($instance['row']):2;
			$show_nav 			= empty($instance['show_nav'])?0:$instance['show_nav'];
			$auto_play 			= empty($instance['auto_play'])?0:$instance['auto_play'];
			
			echo $before_widget;
			
			if( $title ){
				echo $before_title . $title . $after_title;
			}
			
			$args = array(
				'post_type'				=> 'post'
				,'ignore_sticky_posts'	=> 1
				,'post_status'			=> 'publish'
				,'posts_per_page'		=> $limit
				,'order'				=> $order
				,'orderby'				=> $orderby
				);
			
			if( is_array($categories) && count($categories) > 0 ){
				$args['category__in'] = $categories;
			}
			
			global $post;
			$posts = new WP_Query($args);
			if( $posts->have_posts() ):
				$count = 0;
			$num_posts = $posts->post_count;
			if( $num_posts <= $row ){
				$is_slider = false;
			}
			if( !$is_slider ){
				$row = $num_posts;
			}

			$extra_class = '';
			$extra_class .= ($is_slider)?'ftc-slider owl-carousel loading':'';


			?>
			<div class="ftc-blogs-widget-wrapper  <?php echo esc_attr($extra_class); ?>" data-show_nav="<?php echo esc_attr($show_nav) ?>" data-auto_play="<?php echo esc_attr($auto_play) ?>">
				<ul>
					<?php while( $posts->have_posts() ): 
					$posts->the_post(); 
					$post_format = get_post_format(); /* Video, Audio, Gallery, Quote */
					if( $is_slider && $post_format == 'gallery' ){ /* Remove Slider in Slider */
						$post_format = false;
					}
					?>
					<?php if( $count % $row == 0 ): ?>
						<li class="per-slide">
							<div class="post_list_widget">
							<?php endif; ?>
							<div class="post-in-list">
								<?php if( $show_thumbnail ): ?>
									<?php if( $post_format == 'gallery' || $post_format === false || $post_format == 'standard' ){ ?>
									<a class="thumbnail <?php echo esc_attr($post_format); echo ($post_format == 'gallery')?' loading':''; ?>" href="<?php the_permalink(); ?>">
										<span>
											<?php 
											if( $post_format == 'gallery' ){
												$gallery = get_post_meta($post->ID, 'ftc_gallery', true);
												$gallery_ids = explode(',', $gallery);
												if( is_array($gallery_ids) && has_post_thumbnail() ){
													array_unshift($gallery_ids, get_post_thumbnail_id());
												}
												foreach( $gallery_ids as $gallery_id ){
													echo wp_get_attachment_image( $gallery_id, 'ftc_blog_shortcode_thumb' );
												}
											}

											if( $post_format === false || $post_format == 'standard' ){
												if( has_post_thumbnail() ){
													the_post_thumbnail('ftc_blog_shortcode_thumb'); 
												}
												else{
													?>
													<img title="noimage" src="<?php echo get_template_directory_uri()?>/assets/images/no-image-blog.jpg" alt="<?php echo esc_attr(get_the_title()); ?>" />
													<?php 
												}
											}
											?>
										</span>
										<div class="smooth-thumbnail"></div>
									</a>
									<?php 
								}

								if( $post_format == 'video' ){
									$video_url = get_post_meta($post->ID, 'ftc_video_url', true);
									echo do_shortcode('[ftc_video src="'.$video_url.'"]');
								}

								if( $post_format == 'audio' ){
									$audio_url = get_post_meta($post->ID, 'ftc_audio_url', true);
									if( strlen($audio_url) > 4 ){
										$file_format = substr($audio_url, -3, 3);
										if( in_array($file_format, array('mp3', 'ogg', 'wav')) ){
											echo do_shortcode('[audio '.$file_format.'="'.$audio_url.'"]');
										}
										else{
											echo do_shortcode('[ftc_soundcloud url="'.$audio_url.'" width="100%" height="122"]');
										}
									}
								}
								?>
							<?php endif; /* End thumbnail */ ?>

							<?php if( $post_format != 'quote' ): ?>
								
								<?php if( $show_date && !$show_thumbnail ): ?>
									<div class="post-date">
										<span class="day">
											<?php echo get_the_time('d'); ?>
										</span>
										<span class="month">
											<?php echo get_the_time('M'); ?>
										</span>
									</div>
								<?php endif; ?>

								<?php if( $show_title ): ?>
									<a href="<?php the_permalink() ?>" class="post-title">
										<h4><?php the_title(); ?></h4>
									</a>
								<?php endif; ?>

								<?php if( $show_excerpt ): ?>
									<p class="excerpt">
										<?php ftc_the_excerpt_max_words($excerpt_words, $post); ?>
									</p>
								<?php endif; ?>

								<?php if( ( $show_date && $show_thumbnail ) || $show_author || $show_comment ): ?>
									<div class="post-meta">

										<?php if( $show_date && $show_thumbnail ): ?>
											<!-- Blog Date Time -->
										<div class="entry-date entry-date-meta">
										<i class="fa fa-calendar"></i> <?php echo get_the_time(get_option('date_format')); ?>
									</div>
								<?php endif; ?>
										
										<?php if( $show_comment ): ?>
											<span class="comment">
												<i class="fa fa-comments-o"></i>
												<span class="comment-number"><?php echo get_comments_number(); ?></span>
											</span>
										<?php endif; ?>
										
										<?php if( $show_author ): ?>
											<span class="author">
												<i class="fa fa-user"></i>
												<?php the_author_posts_link(); ?>
											</span>
										<?php endif; ?>
									</div>
								<?php endif; ?>

							<?php else: /* Post format is quote */ ?>
								
								<blockquote class="blockquote-bg">
									<?php 
									$quote_content = get_the_excerpt();
									if( !$quote_content ){
										$quote_content = get_the_content();
									}
									echo do_shortcode($quote_content);
									?>
								</blockquote>
								<div class="blockquote-meta">
									<?php if( $show_date && $show_thumbnail ): ?>
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
								</div>

							<?php endif; /* End quote */ ?>
						</div>
						<?php if( $count % $row == $row - 1 || $count == $num_posts - 1 ): ?>	
						</div>
					</li>
				<?php endif; ?>
				<?php $count++; endwhile; ?>
			</ul>
		</div>

		<?php
		endif;

		echo $after_widget;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;		
		$instance['title'] 				= strip_tags($new_instance['title']);		
		$instance['limit'] 				= absint($new_instance['limit']);
		$instance['order'] 				= $new_instance['order'];
		$instance['orderby'] 			= $new_instance['orderby'];
		$instance['categories'] 		= $new_instance['categories'];
		$instance['show_thumbnail'] 	= $new_instance['show_thumbnail'];	
		$instance['show_title'] 		= $new_instance['show_title'];		
		$instance['show_date'] 			= $new_instance['show_date'];		
		$instance['show_author'] 		= $new_instance['show_author'];		
		$instance['show_comment'] 		= $new_instance['show_comment'];		
		$instance['show_excerpt'] 		= $new_instance['show_excerpt'];		
		$instance['excerpt_words'] 		= absint($new_instance['excerpt_words']);		
		$instance['is_slider'] 			= $new_instance['is_slider'];	
		$instance['row'] 				= absint($new_instance['row']);			
		$instance['show_nav'] 			= $new_instance['show_nav'];		
		$instance['auto_play'] 			= $new_instance['auto_play'];	

		if( $instance['row'] > $instance['limit'] ){
			$instance['row'] = $instance['limit'];
		}
		return $instance;
	}

	function form( $instance ) {

		$defaults = array(
			'title' 				=> 'Recent Posts'
			,'limit'				=> 4
			,'order'				=> 'desc'
			,'orderby'				=> 'date'
			,'categories'			=> array()
			,'show_thumbnail' 		=> 1
			,'show_title' 			=> 1
			,'show_date' 			=> 1
			,'show_author' 			=> 0
			,'show_comment'			=> 1
			,'show_excerpt'			=> 1
			,'excerpt_words'		=> 8
			,'is_slider' 			=> 1
			,'row'					=> 2
			,'show_nav' 			=> 1
			,'auto_play' 			=> 1
			);
		
		$instance = wp_parse_args( (array) $instance, $defaults );	

		$categories = $this->get_list_categories(0);
		if( !is_array($instance['categories']) ){
			$instance['categories'] = array();
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Enter your title', 'giftsshop'); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Number of posts to show', 'giftsshop'); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['limit']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php esc_html_e('Order by', 'giftsshop'); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
				<option value="none" <?php selected('none', $instance['orderby']) ?>><?php esc_html_e('None', 'giftsshop') ?></option>
				<option value="ID" <?php selected('ID', $instance['orderby']) ?>><?php esc_html_e('ID', 'giftsshop') ?></option>
				<option value="title" <?php selected('title', $instance['orderby']) ?>><?php esc_html_e('Title', 'giftsshop') ?></option>
				<option value="date" <?php selected('date', $instance['orderby']) ?>><?php esc_html_e('Date', 'giftsshop') ?></option>
				<option value="comment_count" <?php selected('comment_count', $instance['orderby']) ?>><?php esc_html_e('Comment count', 'giftsshop') ?></option>
				<option value="rand" <?php selected('rand', $instance['orderby']) ?>><?php esc_html_e('Random', 'giftsshop') ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php esc_html_e('Order', 'giftsshop'); ?> </label>
			<select class="widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="asc" <?php selected('asc', $instance['order']) ?>><?php esc_html_e('Ascending', 'giftsshop') ?></option>
				<option value="desc" <?php selected('desc', $instance['order']) ?>><?php esc_html_e('Descending', 'giftsshop') ?></option>
			</select>
		</p>

		<p>
			<label><?php esc_html_e('Select categories', 'giftsshop'); ?></label>
			<div class="categorydiv">
				<div class="tabs-panel">
					<ul class="categorychecklist">
						<?php foreach($categories as $cat){ ?>
						<li>
							<label>
								<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($cat->term_id); ?>]" value="<?php echo esc_attr($cat->term_id); ?>" <?php echo (in_array($cat->term_id,$instance['categories']))?'checked':''; ?> />
								<?php echo esc_html($cat->name); ?>
							</label>
							<?php $this->get_list_sub_categories($cat->term_id, $instance); ?>
						</li>
						<?php } ?>
					</ul>
				</div>
			</div>
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

		<hr/>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('is_slider'); ?>" name="<?php echo $this->get_field_name('is_slider'); ?>" value="1" <?php echo ($instance['is_slider'])?'checked':''; ?> />
			<label for="<?php echo $this->get_field_id('is_slider'); ?>"><?php esc_html_e('Show in a carousel slider', 'giftsshop'); ?></label>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('row'); ?>"><?php esc_html_e('Number of rows - in carousel slider', 'giftsshop'); ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('row'); ?>" name="<?php echo $this->get_field_name('row'); ?>" type="number" min="0" value="<?php echo esc_attr($instance['row']); ?>" />
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('show_nav'); ?>" name="<?php echo $this->get_field_name('show_nav'); ?>" value="1" <?php echo ($instance['show_nav'])?'checked':''; ?> />
			<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php esc_html_e('Show navigation button', 'giftsshop'); ?></label>
		</p>

		<p>
			<input type="checkbox" id="<?php echo $this->get_field_id('auto_play'); ?>" name="<?php echo $this->get_field_name('auto_play'); ?>" value="1" <?php echo ($instance['auto_play'])?'checked':''; ?> />
			<label for="<?php echo $this->get_field_id('auto_play'); ?>"><?php esc_html_e('Auto play', 'giftsshop'); ?></label>
		</p>

		<?php 
	}

	function get_list_categories( $cat_parent_id ){
		$args = array(
			'hierarchical'		=> 1
			,'parent'			=> $cat_parent_id
			,'title_li'			=> ''
			,'child_of'			=> 0
			);
		$cats = get_categories($args);
		return $cats;
	}

	function get_list_sub_categories( $cat_parent_id, $instance ){
		$sub_categories = $this->get_list_categories($cat_parent_id); 
		if( count($sub_categories) > 0){
			?>
			<ul class="children">
				<?php foreach( $sub_categories as $sub_cat ){ ?>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->get_field_name('categories'); ?>[<?php esc_attr($sub_cat->term_id); ?>]" value="<?php echo esc_attr($sub_cat->term_id); ?>" <?php echo (in_array($sub_cat->term_id,$instance['categories']))?'checked':''; ?> />
						<?php echo esc_html($sub_cat->name); ?>
					</label>
					<?php $this->get_list_sub_categories($sub_cat->term_id, $instance); ?>
				</li>
				<?php } ?>
			</ul>
			<?php }
		}
		
	}
}

