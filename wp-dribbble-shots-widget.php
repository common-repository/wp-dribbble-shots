<?php
if(!class_exists('WP_Dribbble_Shots_Widget')) {
	class WP_Dribbble_Shots_Widget extends WP_Widget {
		function WP_Dribbble_Shots_Widget()
		{
			$control_ops = array('width' => 400, 'height' => 400);
			$widget_ops = array('class_name' => 'wp_dribbble_shots','description' => 'Displays the most recent Dribbble Shots');
			$this->WP_Widget('wp_dribbble_shots', 'WP Dribbble Shots', $widget_ops, $control_ops);
		}

		function form($instance) {
			$title = !empty($instance['title']) ? $instance['title'] : '';
			$number_of_shots = !empty($instance['number_of_shots']) ? $instance['number_of_shots'] : '';
			$date_format = !empty($instance['date_format']) ? $instance['date_format'] : '';
			$cycle_effect = !empty($instance['cycle_effect']) ? $instance['cycle_effect'] : 'fade';
			$cycle_speed = !empty($instance['cycle_speed']) ? $instance['cycle_speed'] : '';
			$cycle_timeout = !empty($instance['cycle_timeout']) ? $instance['cycle_timeout'] : '';
			
			$all_effects = $this->get_easing_options();
			?>
				<p>
					<label for="<?php echo $this->get_field_id('title'); ?>">Title</label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('number_of_shots'); ?>">Number of Shots</label>
					<input class="widefat" id="<?php echo $this->get_field_id('number_of_shots'); ?>" name="<?php echo $this->get_field_name('number_of_shots'); ?>" type="text" value="<?php echo esc_attr($number_of_shots); ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('date_format'); ?>">Date Format</label>
					<input class="widefat" id="<?php echo $this->get_field_id('date_format'); ?>" name="<?php echo $this->get_field_name('date_format'); ?>" type="text" value="<?php echo esc_attr($date_format); ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('cycle_speed'); ?>">Cycle Speed</label>
					<input class="widefat" id="<?php echo $this->get_field_id('cycle_speed'); ?>" name="<?php echo $this->get_field_name('cycle_speed'); ?>" type="text" value="<?php echo esc_attr($cycle_speed); ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('cycle_timeout'); ?>">Cycle Timeout</label>
					<input class="widefat" id="<?php echo $this->get_field_id('cycle_timeout'); ?>" name="<?php echo $this->get_field_name('cycle_timeout'); ?>" type="text" value="<?php echo esc_attr($cycle_timeout); ?>" />
				</p>
				<p>
					<label for="<?php echo $this->get_field_id('cycle_effect'); ?>">Cycle Effect</label>
					<select class="widefat" id="<?php echo $this->get_field_id('cycle_effect'); ?>" name="<?php echo $this->get_field_name('cycle_effect'); ?>">
						<?php foreach($all_effects as $k => $v): ?>
						<option value="<?php echo esc_attr($v); ?>" id="<?php echo esc_attr($v); ?>"<?php selected($cycle_effect, $v); ?>><?php echo $k; ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['number_of_shots'] = ($new_instance['number_of_shots'] >= 15) ? 15 : strip_tags($new_instance['number_of_shots']);
			$instance['date_format'] = strip_tags($new_instance['date_format']);
			$instance['cycle_effect'] = $new_instance['cycle_effect'];
			$instance['cycle_speed'] = (int) strip_tags($new_instance['cycle_speed']);
			$instance['cycle_timeout'] = (int) strip_tags($new_instance['cycle_timeout']);
			$js_args = array(
				'cycle_effect'	=> $instance['cycle_effect'],
				'cycle_speed'	=> $instance['cycle_speed'], 
				'cycle_timeout'	=> $instance['cycle_timeout']);
			set_transient('wp_dribbble_shots_widget_js_args_'.$this->number, $js_args);
			return $instance;
		}

		function widget($args, $instance) {
			extract($args);
			$title = $instance['title'];
			$date_format = $instance['date_format'];
			$shots = get_the_shots(false, $intance['number_of_shots']);
			echo $before_widget.$before_title.$title.$after_title; ?>
			<div class="wp-dribbble-shot-widget-cycle">
				<?php foreach ($shots as $shot):  ?>
				<?php $date = date( $date_format, strtotime( $shot->created_at ) ); ?>
				<div class="wp-dribbble-shot">
					<h4><a href="<?php echo $shot->url; ?>" target="_blank"><?php echo $shot->title; ?></a></h4>
					<p class="wp-dribbble-date"><?php echo $date; ?></p>
					<a class="wp-dribbble-image-link" href="<?php echo $shot->image_url; ?>" title="<?php echo $shot->title; ?>">
						<img src="<?php echo $shot->image_url; ?>" width="180" alt="<?php echo $shot->title; ?>"/>
					</a>
					<ul class="wp-dribbble-toolbar">
						<li class="views"><span><?php echo $shot->views_count; ?></span></li>
						<li class="comments"><a target="_blank" href="<?php echo esc_url($shot->url.'#comments'); ?>" title="View comments on this screenshot"><span><?php echo $shot->comments_count; ?></span></a></li>
						<li class="likes"><a target="_blank" href="<?php echo esc_url($shot->url.'/fans'); ?>" title="See fans of this screenshot"><span><?php echo $shot->likes_count; ?></span></a></li>
					</ul>
				</div>
				<?php endforeach; ?>
				</div>
				<?php echo $after_widget;
	}
		
		function get_easing_options(){
		return array( 	'Blind X'		=> 'blindX',
				'Blind Y'		=> 'blindY',
				'Blind Z'		=> 'blindZ',
				'Cover'			=> 'cover',
				'Curtain X'		=> 'curtainX',
				'Curtain Y'		=> 'curtainY',
				'Fade'			=> 'fade',
				'Fade Zoom'		=> 'fadeZoom',
				'Grow X'		=> 'growX',
				'Grow Y'		=> 'growY',
				'None'			=> 'None',
				'Scroll Up'		=> 'scrollUp',
				'Scroll Down'		=> 'scrollDown',
				'Scroll Left'		=> 'scrollLeft',
				'Scroll Right'		=> 'scrollRight',
				'Scroll Horizontally'	=> 'scrollHorz',
				'Scroll Vertically'	=> 'scrollVert',
				'Shuffle'		=> 'shuffle',
				'Slide X'		=> 'slideX',
				'Slide Y'		=> 'slideY',
				'Toss'			=> 'toss',
				'Turn Up'		=> 'turnUp',
				'Turn Down'		=> 'turnDown',
				'Turn Left'		=> 'turnLeft',
				'Turn Right'		=> 'turnRight',
				'Uncover'		=> 'uncover',
				'Wipe'			=> 'wipe',
				'Zoom'			=> 'zoom');
		}
	}
	add_action('widgets_init', create_function( '', 'register_widget("WP_Dribbble_Shots_Widget");' ) );
}