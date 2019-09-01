<?php

/*
Plugin Name: WDV MailChimp Ajax
Description: Contact form for MailChimp using ajax
Version: 1.0.4
Author: wdvillage
Author URI: http://wdvillage.com/
Plugin URI: http://wdvillage.com/wdv-mailchimp-ajax/
*/
/*  Copyright 2016  wdvillage  (email : wdvillage100 {at} gmail.com)

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
//********************************************************************************

//The current directory
$currentDir = dirname(__FILE__);
define('WIDGET_WDV_MAILCHIMP_DIR', $currentDir);
 
//Plugin version
define('WIDGET_WDV_MAILCHIMP_VERSION', '1.0');
 
//The name of the plugin
$pluginName = plugin_basename(WIDGET_WDV_MAILCHIMP_DIR);
 
//URI path to the directory with the plugin
$pluginUrl = trailingslashit(WP_PLUGIN_URL . '/' . $pluginName);
 
//Path to CSS, JS scripts and images
$assetsUrl = $pluginUrl . '/assets';

add_action('admin_init', 'widget_attachment_admin_init');
add_action('widgets_init', 'widget_attachment_widgets_init');

function widget_attachment_admin_init()
{
 load_plugin_textdomain( 'wdv_mailchimp_ajax_widget');
}
 
// The function which loads the class with the widget and initialize it
function widget_attachment_widgets_init()
{
 global $assetsUrl;
 wp_enqueue_script('jquery');   
 wp_enqueue_script('underscore');
 //Register a javascript for widget
 wp_register_script('wdv_mailchimp_ajax_js', $assetsUrl . '/js/wdv_mailchimp_ajaxcall.js', array('jquery', 'underscore'), WIDGET_WDV_MAILCHIMP_VERSION, true);
 
 wp_enqueue_script('wdv_mailchimp_ajax_js');
 
 include_once WIDGET_WDV_MAILCHIMP_DIR . '/WDV_MailChimp_Ajax.php';
 register_widget( 'WDV_MailChimp_Ajax' );
}

//******************************************************************************
// Add admin-ajax.php
add_action( 'wp_enqueue_scripts', 'myajax_data', 99 );
function myajax_data(){
	wp_localize_script('wdv_mailchimp_ajax_js', 'myajax', 
		array(
		'url' => admin_url('admin-ajax.php')
		)
	);  
}

// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_wdv_mailchimp_ajaxcall', 'wdv_mailchimp_ajaxcall' );
add_action( 'wp_ajax_wdv_mailchimp_ajaxcall', 'wdv_mailchimp_ajaxcall' );

function wdv_mailchimp_ajaxcall() {   

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
function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
