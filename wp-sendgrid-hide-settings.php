<?php
/**
 * Plugin Name: WP SendGrid Hide Settings
 * Description: Hides the SendGrid account settings so they can't be misconfigured
 * Plugin URI: http://github.com/codeawhile/wp-sendgrid-hide-settings/
 * Author: CodeAwhile.com
 * Author URI: http://codeawhile.com/
 * Version: 0.1
 */

class WP_SendGrid_Hide_Settings {

	static $hidden_fields = array();

	public static function start() {
		add_action( 'admin_init', array( __CLASS__, 'hide_settings' ), 100 );
		add_action( 'wp_sendgrid_settings_before_submit_button', array( __CLASS__, 'add_hidden_fields' ) );
	}

	public static function hide_settings() {
		if ( class_exists( 'WP_SendGrid_Settings' ) ) {
			global $wp_settings_sections, $wp_settings_fields;

			if ( isset($wp_settings_sections)
				&& isset($wp_settings_sections[WP_SendGrid_Settings::SETTINGS_PAGE_SLUG][WP_SendGrid_Settings::SETTINGS_SECTION_ID]) ) {
				unset( $wp_settings_sections[WP_SendGrid_Settings::SETTINGS_PAGE_SLUG][WP_SendGrid_Settings::SETTINGS_SECTION_ID] );
				//print_r( $wp_settings_fields );
				$settings = WP_SendGrid_Settings::get_settings();
				foreach ( $wp_settings_fields[WP_SendGrid_Settings::SETTINGS_PAGE_SLUG][WP_SendGrid_Settings::SETTINGS_SECTION_ID] as $id => $field_args ) {
					self::$hidden_fields[$field_args['args']['id']] = $settings[$field_args['args']['id']];
				}
			}

		}
	}

	public static function add_hidden_fields() {
		foreach ( self::$hidden_fields as $key => $value ) {
			echo '<input type="hidden" name="' . esc_attr( 'wp_sendgrid_options[' . $key . ']' ) . '" value="' . esc_attr( $value ) . '" />';
		}
	}

}

WP_SendGrid_Hide_Settings::start();
