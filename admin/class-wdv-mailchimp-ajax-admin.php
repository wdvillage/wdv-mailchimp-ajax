<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/admin
 * @author     wdvillage <wdvillage100@gmail.com>
 */
class Wdv_Mailchimp_Ajax_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdv_Mailchimp_Ajax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdv_Mailchimp_Ajax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wdv-mailchimp-ajax-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'wp-color-picker' ); 
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wdv_Mailchimp_Ajax_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wdv_Mailchimp_Ajax_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
                if ( ! did_action( 'wp_enqueue_media' ) ) {
                        wp_enqueue_media();
                }
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_script('undescore', includes_url('js') . '/underscore.min.js' );
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wdv-mailchimp-ajax-admin.js', array( 'jquery' ), $this->version, false );
	}
        /*
        *Register widgets
        */

	public function register_widgets() {
		 register_widget( 'WDV_MailChimp_Ajax_Widget' );
	} 
        
}
