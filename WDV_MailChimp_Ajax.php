<?php

/**
 * Class of WDV_MailChimp_Ajax
 *
 * @author wdvillage
 */
class WDV_MailChimp_Ajax extends WP_Widget {
    /**
    * Register widget with WordPress.
    */    
    var $textdomain;
    
    public function __construct() {

        $widget_ops = array(
            'classname'   => 'widget_wdv_mailchimp_ajax', 
            'description' => __( 'Subscribe to our news', 'wdv-mailchimp-ajax-widget' ));
       
         parent::__construct( 'wdv_mailchimp_ajax', __( 'WDV: Subscribe mailchimp', 'wdv-mailchimp-ajax-widget' ), $widget_ops );
    
         add_action( 'init', array( $this , 'load_text_domain' ) );
         add_action( 'admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ) );
    }
    
    function load_text_domain() {
	load_plugin_textdomain( 'wdv-mailchimp-ajax-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );                        
    }

    function admin_enqueue_scripts ( $hook_suffix )
        {
            if ( $hook_suffix != 'widgets.php' ) { return;}

            wp_enqueue_style( 'wp-color-picker' );          
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script('underscore');
        }
    
    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance )
    {

        $titletextcolor = isset( $instance['titletextcolor'] ) ? esc_attr( $instance['titletextcolor'] ) : '';
        $descriptiontextcolor      = isset( $instance['descriptiontextcolor'] ) ? esc_attr( $instance['descriptiontextcolor'] ) : '';
        $buttonbgcolor      = isset( $instance['buttonbgcolor'] ) ? esc_attr( $instance['buttonbgcolor'] ) : '';
        $buttontextcolor      = isset( $instance['buttontextcolor'] ) ? esc_attr( $instance['buttontextcolor'] ) : '';
        $bgcolor      = isset( $instance['bgcolor'] ) ? esc_attr( $instance['bgcolor'] ) : '';
        $padding      = isset( $instance['padding'] ) ? esc_attr( $instance['padding'] ) : '';
        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Subscribe', 'wdv-mailchimp-ajax-widget' );
	$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
	$description   = isset( $instance['description'] ) ? esc_attr( $instance['description'] ) : '';
        $placeholder = isset( $instance['placeholder'] ) ? esc_attr( $instance['placeholder'] ) : 'name@mail.com';
        //$buttontext   = isset( $instance['buttontext'] ) ? esc_attr( $instance['buttontext'] ) : 'Subscribe';
        $buttontext = ( ! empty( $instance['buttontext'] ) ) ? $instance['buttontext'] : __( 'Subscribe', 'wdv-mailchimp-ajax-widget' );
        $listID   = isset( $instance['listID'] ) ? esc_attr( $instance['listID'] ) : '';
	$apiKey   = isset( $instance['apiKey'] ) ? esc_attr( $instance['apiKey'] ) : '';

        echo $args['before_widget'];
        ?>
        <style>
            .widget_wdv_mailchimp_ajax .subscribe-description { margin-bottom: 1rem;}
            .widget_wdv_mailchimp_ajax input[type="button"] {margin-top: 1rem; margin-bottom: 1rem; width: 100%;}
            .widget_wdv_mailchimp_ajax .email {width: 100%;}
            .widget_wdv_mailchimp_ajax .post-success {color: green;}
            .widget_wdv_mailchimp_ajax .post-error {color: red;}
        </style>
    <?php
        if ( $title ) { ?>
            <?php    echo $args['before_title'] . $title . $args['after_title'];             
        }         
        if( ($description) ) { ?>
        <?php echo '<div class="subscribe-description">'. $description .'</div>';
	}
        
        echo '<form method="post" class="subscribe_mc">';
        echo       '<input type="email" value="" name="email" class="email" id="email" placeholder="' .$placeholder . '" required>';
        ?>
        
        <input type="button" value='<?php if ($buttontext){echo esc_attr($buttontext);} ?>' name="subscribe" id="mc-embedded-subscribe" class="button" >   
        <?php
        echo  '</form>
                <div class="result"></div>';                         
	echo $args['after_widget'];
                
        if( ($titletextcolor) ) { ?>
            <style>
                .widget_wdv_mailchimp_ajax .widget-title {color:<?php echo $titletextcolor. '!important';?>;  border-color: <?php echo $titletextcolor. '!important';?>;}
            </style>
        <?php 
	}
        if( ($descriptiontextcolor) ) { ?>
            <style>
                .widget_wdv_mailchimp_ajax .subscribe-description {color:<?php echo $descriptiontextcolor. '!important';?>;}
            </style>
        <?php 
	}
        if( ($buttonbgcolor) ) { ?>
            <style>  
            .widget_wdv_mailchimp_ajax input[type="button"], .widget_wdv_mailchimp_ajax input[type="button"]:focus
            {background: <?php echo $buttonbgcolor. '!important';?>;border-color:<?php echo $buttonbgcolor. '!important';?>;}
            .widget_wdv_mailchimp_ajax input[type="button"]:hover
            {background: <?php echo $buttonbgcolor. '!important';?>; border-color:<?php echo $buttonbgcolor. '!important';?>; opacity: 0.8 !important;}
            </style>
        <?php 
	}
        if( ($buttontextcolor) ) { ?>
            <style>  
            .widget_wdv_mailchimp_ajax input[type="button"], .widget_wdv_mailchimp_ajax input[type="button"]:focus
            {color:<?php echo $buttontextcolor . '!important';?>;}
            .widget_wdv_mailchimp_ajax input[type="button"]:hover
            {color:<?php echo $buttontextcolor . '!important';?>;}
            </style>
        <?php 
	}
        if( ($bgcolor) ) { ?>
        <style>
            .widget_wdv_mailchimp_ajax {background: <?php echo $bgcolor . '!important';?>;padding: <?php echo $padding.'px !important';?>;}
        </style>
        <?php 
	}
    }
    /**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
    public function form( $instance )
    {

        $titletextcolor = isset( $instance['titletextcolor'] ) ? esc_attr( $instance['titletextcolor'] ) : '';
        $descriptiontextcolor = isset( $instance['descriptiontextcolor'] ) ? esc_attr( $instance['descriptiontextcolor'] ) : '';
        $buttonbgcolor = isset( $instance['buttonbgcolor'] ) ? esc_attr( $instance['buttonbgcolor'] ) : '';
        $buttontextcolor = isset( $instance['buttontextcolor'] ) ? esc_attr( $instance['buttontextcolor'] ) : '';
        $bgcolor = isset( $instance['bgcolor'] ) ? esc_attr( $instance['bgcolor'] ) : '';
        $padding = ! empty( $instance['padding'] ) ? $instance['padding'] : '0';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $placeholder = ! empty( $instance['placeholder'] ) ? $instance['placeholder'] : 'name@mail.com';
        $buttontext = ! empty( $instance['buttontext'] ) ? $instance['buttontext'] : 'Subscribe';
        $listID = ! empty( $instance['listID'] ) ? $instance['listID'] : '';
        $apiKey = ! empty( $instance['apiKey'] ) ? $instance['apiKey'] : '';

        ?>
        <script type='text/javascript'>

    ( function($) {

	function initColorPicker( widget ) {
                widget.find( '#title-color-field' ).wpColorPicker( {
				change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
				}, 3000 )
		});
                widget.find( '#description-color-field' ).wpColorPicker( {
				change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
				}, 3000 )
		});
		widget.find( '#color-field' ).wpColorPicker( {
				change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
				}, 3000 )
		});
		widget.find( '#btn-txt-color' ).wpColorPicker( {
				change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
				}, 3000 )
		});
                widget.find( '#color-bg' ).wpColorPicker( {
				change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
				}, 3000 )
		});
	}

	function onFormUpdate( event, widget ) {
		initColorPicker( widget );
	}

	$( document ).on( 'widget-added widget-updated', onFormUpdate );

	$( document ).ready( function() {
                $( '.widget_wdv_mailchimp_ajax #title-color-field' ).each( function () {
				initColorPicker( $( this ) );
		} );
                $( '.widget_wdv_mailchimp_ajax #description-color-field' ).each( function () {
				initColorPicker( $( this ) );
		} );
		$( '.widget_wdv_mailchimp_ajax #color-field' ).each( function () {
				initColorPicker( $( this ) );
		} );
                $( '.widget_wdv_mailchimp_ajax #btn-txt-color' ).each( function () {
				initColorPicker( $( this ) );
		} );
                $( '.widget_wdv_mailchimp_ajax #color-bg' ).each( function () {
				initColorPicker( $( this ) );
		} );
	} );
        

} )( jQuery );

        </script>

        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php _e( 'Description:', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>" type="text" value="<?php echo esc_attr( $description ); ?>" />
        </p>
        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>"><?php _e( 'Placeholder for e-mail:', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'placeholder' )); ?>" type="text" value="<?php echo esc_attr( $placeholder ); ?>" />
        </p>    
        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'buttontext' )); ?>"><?php _e( 'Text on button:', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'buttontext' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'buttontext' )); ?>" type="text" value="<?php echo esc_attr( $buttontext ); ?>" />
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'apiKey' )); ?>"><?php _e( 'API Key (from MailChimp):', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'apiKey' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'apiKey' )); ?>" type="text" value="<?php echo esc_attr( $apiKey ); ?>" />
        </p>
        <p>                                                                  
        <label for="<?php echo esc_attr($this->get_field_id( 'listID' )); ?>"><?php _e( 'List ID (from MailChimp):', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'listID' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'listID' )); ?>" type="text" value="<?php echo esc_attr( $listID ); ?>" />
        </p> 
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('titletextcolor')); ?>"><?php _e('Change the color of the title:', 'wdv-mailchimp-ajax-widget'); ?></label><br> 
        <input id="title-color-field" id="<?php echo esc_attr($this->get_field_id('titletextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('titletextcolor')); ?>" type="text" value="<?php if($titletextcolor) { echo esc_attr($titletextcolor); }  ?>" />    
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('descriptiontextcolor')); ?>"><?php _e('Change the color of the description:', 'wdv-mailchimp-ajax-widget'); ?></label><br> 
        <input id="description-color-field" id="<?php echo esc_attr($this->get_field_id('descriptiontextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('descriptiontextcolor')); ?>" type="text" value="<?php if($descriptiontextcolor) { echo esc_attr($descriptiontextcolor); } ?>" />    
        </p>        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('buttonbgcolor')); ?>"><?php _e('Change the color for the button background:', 'wdv-mailchimp-ajax-widget'); ?></label><br> 
        <input id="color-field" id="<?php echo esc_attr($this->get_field_id('buttonbgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('buttonbgcolor')); ?>" type="text" value="<?php if($buttonbgcolor) { echo esc_attr($buttonbgcolor); } ?>" />    
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('buttontextcolor')); ?>"><?php _e("Change the color for the text on the button:", 'wdv-mailchimp-ajax-widget'); ?></label><br> 
        <input id="btn-txt-color" id="<?php echo esc_attr($this->get_field_id('buttontextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('buttontextcolor')); ?>" type="text" value="<?php if($buttontextcolor) { echo esc_attr($buttontextcolor); }?>" />    
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('bgcolor')); ?>"><?php _e('Change the color of the widget background:', 'wdv-mailchimp-ajax-widget'); ?></label><br> 
        <input id="color-bg" id="<?php echo esc_attr($this->get_field_id('bgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('bgcolor')); ?>" type="text" value="<?php if($bgcolor) { echo esc_attr($bgcolor); } ?>" />    
        </p> 
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'padding' )); ?>"><?php _e( 'Create padding around the widget (in pixels):', 'wdv-mailchimp-ajax-widget' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'padding' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'padding' )); ?>" type="text" value="<?php echo esc_attr( $padding ); ?>" />
        </p>

        <?php
    } 
    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */    
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['titletextcolor']     = strip_tags($new_instance['titletextcolor']);
        $instance['buttontext']     = strip_tags($new_instance['buttontext']);
        $instance['padding'] = ( ! empty( $new_instance['padding'] ) ) ? strip_tags( $new_instance['padding'] ) : '';            
        $instance['buttonbgcolor']     = strip_tags($new_instance['buttonbgcolor']);
        $instance['buttontextcolor']     = strip_tags($new_instance['buttontextcolor']);
        $instance['descriptiontextcolor']     = strip_tags($new_instance['descriptiontextcolor']);
        $instance['bgcolor']      = strip_tags( $new_instance['bgcolor'] );
        $instance['padding'] = ( ! empty( $new_instance['padding'] ) ) ? strip_tags( $new_instance['padding'] ) : '';            
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';        
        $instance['placeholder'] = ( ! empty( $new_instance['placeholder'] ) ) ? strip_tags( $new_instance['placeholder'] ) : '';        
        $instance['listID'] = ( ! empty( $new_instance['listID'] ) ) ? strip_tags( $new_instance['listID'] ) : '';
        $instance['apiKey'] = ( ! empty( $new_instance['apiKey'] ) ) ? strip_tags( $new_instance['apiKey'] ) : '';            
        return $instance;
    }
}
