<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/includes
 * @author     wdvillage <wdvillage100@gmail.com>
 */
class Wdv_Mailchimp_Ajax_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wdv-mailchimp-ajax',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
