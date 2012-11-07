<?php
/*
Plugin Name: Simple Login Window
Plugin URI: http://plugins.findingsimple.com
Description: Insert a modal login window to a theme template.
Version: 1.0
Author: Finding Simple
Author URI: http://findingsimple.com
License: GPL2
*/
/*
Copyright 2012  Finding Simple  (email : plugins@findingsimple.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'Simple_Login_Window' ) ) :

/**
 * So that themes and other plugins can customise the text domain, the Simple_Login_Window
 * should not be initialized until after the plugins_loaded and after_setup_theme hooks.
 * However, it also needs to run early on the init hook.
 *
 * @author Jason Conroy <jason@findingsimple.com>
 * @package Simple Sharebar
 * @since 1.0
 */
function initialize_sharebar(){
	Simple_Login_Window::init();
}
add_action( 'init', 'initialize_sharebar', -1 );

/**
 * Plugin Main Class.
 *
 * @package Simple Sharebar
 * @since 1.0
 */
class Simple_Login_Window {

	public static function init() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles_and_scripts'), 100 );
		
		add_action( 'wp_footer', array( __CLASS__, 'window_content') );

	}

	/**
	 * Add sharebar scripts
	 *
	 * @since 1.0
	 */
	public static function enqueue_styles_and_scripts(){
		
		if ( !is_admin() ) {
		
			wp_enqueue_script( 'jquery-colorbox', self::get_url( '/js/jquery.colorbox-min.js' ) ,'jquery','1.0',true );
			
			wp_enqueue_style( 'simple-login-window-css', self::get_url( '/css/simple-login-window.css' ) );
								
		}
		
	}
	

	/**
	 * Display the link for the modal window
	 *
	 * @since 1.0
	 */
	public static function window_link() {
				
		if(is_user_logged_in()) {
			echo '<a href="'. get_site_url() .'/wp-admin/profile.php">view profile</a> <a href="'. wp_logout_url( home_url() ) .'">log out</a>'; 
		}
		else {
			echo '<a href="#simple-login-window" class="simple-login-window">log in</a>';
		}
		
	}
	
	/**
	 * Modal window div
	 *
	 * @since 1.0
	 */
	public static function window_content() {
		
		if(!is_user_logged_in()) {		
			$content = '<div style="display:none">';
			$content .= '<div id="simple-login-window">';
			$content .= '<h2>Login</h2>';
			$content .= wp_login_form(array( 'echo' => false ));
			$content .= '<a title="Close Popup" onclick="jQuery.colorbox.close()" id="cboxClose"></a>';
			$content .= '</div></div>';
			echo $content;
		}
		
	}
	
	/**
	 * Helper function to get the URL of a given file. 
	 * 
	 * As this plugin may be used as both a stand-alone plugin and as a submodule of 
	 * a theme, the standard WP API functions, like plugins_url() can not be used. 
	 *
	 * @since 1.0
	 * @return array $post_name => $post_content
	 */
	public static function get_url( $file ) {

		// Get the path of this file after the WP content directory
		$post_content_path = substr( dirname( str_replace('\\','/',__FILE__) ), strpos( __FILE__, basename( WP_CONTENT_DIR ) ) + strlen( basename( WP_CONTENT_DIR ) ) );

		// Return a content URL for this path & the specified file
		return content_url( $post_content_path . $file );
	}	
	
}

endif;