<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wdvillage.com/
 * @since      1.0.0
 *
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wdv_Mailchimp_Ajax
 * @subpackage Wdv_Mailchimp_Ajax/public
 * @author     wdvillage <wdvillage100@gmail.com>
 */
class Wdv_Mailchimp_Ajax_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wdv-mailchimp-ajax-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wdv-mailchimp-ajax-public.js', array( 'jquery' ), $this->version, false );
          
                wp_localize_script( $this->plugin_name, 'wdvmailchimpmyajax' , array( 'url' => admin_url( 'admin-ajax.php' ) ) );
	}
        
    public function wdv_mailchimp_ajaxcall() {   

        $email = test_input($_POST['subscribeemail']);
        $widgetid = $_POST['widget_id'];

        $w = get_option('widget_wdv_mailchimp_ajax');

        //If  widget exists, return it
        if (isset($w[$widgetid])) {
            $listID = $w[$widgetid]["listID"];
            $apiKey = $w[$widgetid]["apiKey"];
        }

        $data = array(
            'email_address' => $email,
            'status' => 'subscribed'
        );

        $body = json_encode($data);

        $options = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'apikey ' . $apiKey
            ),
            'body' => $body
        );

        $apiKeyParts = explode('-', $apiKey);
        $shard = $apiKeyParts[1];
        $url = 'https://' . $shard . '.api.mailchimp.com/3.0/lists/' . $listID . '/members/';
        $response = wp_remote_post( $url, $options );

        if ( is_wp_error( $response ) ) {
        echo '<div class="post-error">';
        echo __( 'There was a problem retrieving the response from the server.', 'wdv_mailchimp_widget' );
        echo '</div><!-- /post-error -->';

        }
            elseif ($response["response"]["code"]===200) {
                echo '<div class="post-success">';
                echo __( 'You have been added to our sign-up list.', 'wdv_mailchimp_widget' );
                echo '</div><!-- /post-success -->';
            } elseif ($response["response"]["code"]===400) {
                    echo '<div class="post-success">';
                    echo __( 'You have already subscribed to our sign-up list.', 'wdv_mailchimp_widget' );
                    echo '</div><!-- /post-success -->';
                } else {
                        echo '<div class="post-error">';
                        __( 'There was an error. Please try again.', 'wdv_mailchimp_widget' );
                        echo '</div><!-- /post-error -->';
                    }
        }
}
