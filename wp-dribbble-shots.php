<?php
/*
Plugin Name: WP Dribbble Shots
Plugin URI: https://github.com/klangley/wp-dribbble-shots
Description: Adds a template function, get_the_shots(), which returns the latest shots of the Dribbble user and adds a widget to cycle through the results..
Author: Kevin Langley
Version: 0.5
Author URI: http://ubergeni.us
*******************************************************************
Copyright 2011-2012 Kevin Langley (email : me@ubergeni.us)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*******************************************************************
*/
if(!class_exists('WP_Dribbble_Shots')) {
	class WP_Dribbble_Shots {
	
		public static function initialize() {
			add_action( 'admin_init', array( __CLASS__, 'add_settings' ) );
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
			add_action( 'wp_enqueue_styles', array( __CLASS__, 'eneuque_styles' ) );
		}
		
		public static function is_active_widget($id_base) {
			global $wp_registered_widgets;
			$sidebars_widgets = wp_get_sidebars_widgets();
			if ( is_array($sidebars_widgets) ) {
				foreach ( $sidebars_widgets as $sidebar => $widgets ) {
					if ( 'wp_inactive_widgets' == $sidebar )
						continue;
					if ( is_array($widgets) ) {
						foreach ( $widgets as $widget ) {
							if ( ( $id_base && _get_widget_id_base($widget) == $id_base ) ) {
								return $widget;
							}
						}
					}
				}
			}
			return false;
		}
		
		public static function enqueue_scripts(){
			if( $active_widget = WP_Dribbble_Shots::is_active_widget( 'wp_dribbble_shots' ) ){
				$widget_number = explode('-', $active_widget);
				$widget_number = $widget_number[1];
				$js_args = get_transient('wp_dribbble_shots_widget_js_args_'.$widget_number);
				
				wp_enqueue_style( 'wp-dribbble-shots-widget', plugins_url(basename(dirname(__FILE__)).'/css/wp-dribbble-shots-widget.css'), __FILE__ );
				
				wp_enqueue_script( 'jquery-cycle', plugins_url('/js/jquery.cycle.all.js', __FILE__), array('jquery') );
				wp_enqueue_script( 'jquery-easing', plugins_url('/js/jquery.easing-1.4.pack.js', __FILE__) );
				wp_enqueue_script( 'wp-dribbble-shots-widget', plugins_url('/js/wp-dribbble-shots-widget.js', __FILE__) );
				
				wp_localize_script( 'wp-dribbble-shots-widget', 'dribbble_args', $js_args );
			}
		}
	
		public static function get_the_shots($player_id = false, $number = 15){
			$player_id = ($player_id) ? get_option('dribbble_player_id') : $player_id;
			$cache = get_transient('dribbble-shots-'.$player_id);
			if( $cache ) {
				return $cache;
			}
			$url = 'http://api.dribbble.com/'.rawurlencode($player_id).'/shots';
			if (function_exists('curl_init')) {
				$c = curl_init();
				curl_setopt($c, CURLOPT_URL, $url);
				curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($c, CURLOPT_HEADER, false);
				$shots = curl_exec($c);
				curl_close($c);
			}  else {
				$shots = file_get_contents($url);
			}
			$json = json_decode($shots);
			$shots = $json->{"shots"};
			set_transient('dribbble-shots-'.$player_id, $shots, 300);
			return $shots;
		}
		
		public static function add_settings(){
			add_settings_section('dribbble_settings', 'Dribbble Settings', create_function('', ''), 'general');
			add_settings_field('dribbble_player_id', 'Dribbble Player ID', array( __CLASS__, 'dribbble_player_id_cb' ), 'general', 'dribbble_settings');
			register_setting('general','dribbble_player_id', array(__CLASS__, 'update_dribbble_player_id'));
		}
		
		public static function dribbble_player_id_cb(){
			$player_id = get_option('dribbble_player_id');
			echo '<input type="text" id="dribble_player_id" name="dribbble_player_id" value="'.esc_attr($player_id).'" class="regular-text"/>';
		}
		
		public static function update_dribbble_player_id( $data ) {
			delete_transient('dribbble-shots');
		}
	}
	
	add_action( 'init', array( 'WP_Dribbble_Shots', 'initialize' ) );
	
	function get_the_shots($player = '', $number = ''){
		return WP_Dribbble_Shots::get_the_shots($player, $number);
	}
	
	require_once( dirname(__FILE__) . '/wp-dribbble-shots-widget.php' );
}
