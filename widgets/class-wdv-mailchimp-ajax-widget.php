<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wdvillage.com
 * @since      1.0.0
 *
 * @package    Wdv_MailChimp_Ajax
 * @subpackage Wdv_MailChimp_Ajax/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wdv_MailChimp_Ajax
 * @subpackage Wdv_MailChimp_Ajax/includes
 * @author     wdvillage <wdvillage100@gmail.com>
 */
class WDV_MailChimp_Ajax_Widget extends WP_Widget {

    public function __construct() {
        $this->defaults = array(
            'titletextcolor' => '',
            'descriptiontextcolor' => '',
            'buttonbgcolor' => '',
            'buttontextcolor' => '',
            'bgcolor' => '',
            'padding' => 20,
            'title' => __( 'Subscribe', 'wdv-mailchimp-ajax' ),
            'description' => '',
            'placeholder' => 'name@mail.com',
            'buttontext' => __( 'Subscribe', 'wdv-mailchimp-ajax' ),
            'listID' => '',
            'apiKey' => '',         
            'image'=> '',  
            'img-width' => 200,
            'img-height' => 150,
            'img-radius' => 10,
            'img-show' => 'background-img',
            'btnwidth'=> 'on'
        );

        $widget_ops = array('classname' => 'widget_wdv_mailchimp_ajax', 'description' => __('Subscribe to MailChimp', 'wdv-mailchimp-ajax'));
        parent::__construct('wdv_mailchimp_ajax', __('WDV: MailChimp Ajax', 'wdv-mailchimp-ajax'), $widget_ops);
    }

    //frontend output content
    public function widget($args, $instance) {
        $instance = wp_parse_args((array) $instance, $this->defaults);

        echo $args['before_widget'];?>

    <?php if ($instance['img-show']==='background-img'&&!empty($instance['image']) )  {      
            echo (!empty($instance['image']) ) ? '<div class="wdv-bgimage" style="background-image:url(' . esc_url($instance['image']) . '); padding:' . $instance['padding'].'px !important;">' : '';  
    } else {
                echo '<div class="wdv-bgimage" style="background-color:' . $instance['bgcolor'] . '!important; padding:' . $instance['padding'].'px !important;">';
    }  ?> 

    <?php
        if ( $instance['title'] ) { ?>
            <?php    echo $args['before_title'] .'<div class="" style="color:'. $instance['titletextcolor']. '!important;">'. $instance['title'] .'</div>'. $args['after_title'];    
        }    

        if ($instance['img-show']==='single-img')  {      
                echo (!empty($instance['image']) ) ? '<div class="wdv-image" style="background-image:url(' . esc_url($instance['image']) . ');'
                        . 'width:' . $instance['img-width'] . 'px;'
                        . 'height: ' . $instance['img-height'] . 'px;'
                        . 'border-radius: ' . $instance['img-radius'] . 'px;margin:1em auto;'
                        . '"></div>' : '';  
        } 
        
        if( ($instance['description']) ) { ?>
        <?php echo '<div class="subscribe-description" style="color:'. $instance['descriptiontextcolor']. '!important;">'. $instance['description'] .'</div>';
	}
        
        echo '<form method="post" class="subscribe_mc">';

        echo '<input type="email" value="" name="email" class="email" id="wdv-email" placeholder="' .$instance['placeholder'] . '" required>';
        ?>

        <input type="button" value='<?php if ($instance['buttontext']){echo esc_attr($instance['buttontext']);} ?>' name="subscribe" id="" class="button mc-embedded-subscribe" style="<?php if ($instance['btnwidth']==='on'){echo 'width:100%;';} ?>background: <?php echo $instance['buttonbgcolor']. '!important';?>; border-color:<?php echo $instance['buttonbgcolor']. '!important';?>;color:<?php echo $instance['buttontextcolor'] . '!important';?>;" >    
        <?php
        echo  '</form>
                <div class="wdv-result"></div>';         
               echo '</div><!--wdv-bgimage-->'; 
               echo $args['after_widget'];          
           }

           
    //backend form
    public function form($instance) {
        $instance = wp_parse_args((array) $instance, $this->defaults);
        ?>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <!---------------------------->
        <!--Add Image -->  
        <!---------------------------->
        <div class="wdv-image-container">        

            <label for="<?php echo esc_attr($this->get_field_id('image')); ?>"><?php esc_html_e('Image', 'wdv-mailchimp-ajax'); ?>:</label><br>
            <div class="wdv-media-container" >

                <div class="wdv-media-inner">
                    <?php $img_style = ( $instance['image'] != '' ) ? '' : 'style="display:none; float: left"'; 
                    if ($instance['image']) {?>
                    <style>
                        .wdv-media-inner .no-image {display: none;}
                    </style>                        
                    <?php } ?>
                    
                    <img id="<?php echo esc_attr($this->get_field_id('image')); ?>-preview" src="<?php echo esc_url($instance['image']); ?>" <?php echo esc_attr($img_style); ?> <?php $no_img_style = esc_attr(( $instance['image'] != '' ) ? 'style="display:none"' : ''); ?> /><br>        
                    <span class="no-image" id="<?php echo esc_attr($this->get_field_id('image')); ?>-noimg" <?php echo esc_attr($no_img_style); ?>><?php esc_html_e('No image selected', 'wdv-mailchimp-ajax'); ?></span><br><br>
                    <div class="wdv-empty"></div>
                </div>

                <input type="text" id="<?php echo esc_attr($this->get_field_id('image')); ?>" name="<?php echo esc_attr($this->get_field_name('image')); ?>" value="<?php echo esc_attr($instance['image']); ?>" class="wdv-media-url" />
                <p>
                    <input type="button" value="<?php echo esc_html_e('Remove Image', 'wdv-mailchimp-ajax'); ?>" class="button wdv-media-remove" id="<?php echo esc_attr($this->get_field_id('image')); ?>-remove" <?php echo esc_attr($img_style); ?> />
                    <input type="button" value="<?php echo esc_html_e('Select Image', 'wdv-mailchimp-ajax'); ?>" class="button wdv-media-upload" id="<?php echo esc_attr($this->get_field_id('image')); ?>-button" />
                </p>
            </div>

        </div>     
        
            <!---------------------------->
            <!--Show image how: (background-img or single-img)--> 
            <!---------------------------->
            <label for="<?php echo $this->get_field_id('text_area'); ?>">
                <?php echo('Show image how:'); ?>
            </label><br>
            <?php
            $rate = isset($instance['img-show']) ? $instance['img-show'] : 'background-img';

            echo '<p>';
            /* Default value */
            $value = 'background-img';

            echo '<input value="' . $value . '" class="widefat" id="' . $this->get_field_id($value) . '" name="' . $this->get_field_name('img-show') . '" type="radio"' . ($rate == $value ? ' checked="checked"' : '') . ' />';
            echo '<label for="' . $this->get_field_id($value) . '">' . __('Background image', 'wdv-mailchimp-ajax') . '</label>';
            echo '<br/>';

            $value = 'single-img';

            echo '<input value="' . $value . '" class="widefat" id="' . $this->get_field_id($value) . '" name="' . $this->get_field_name('img-show') . '" type="radio"' . ($rate == $value ? ' checked="checked"' : '') . ' />';
            echo '<label for="' . $this->get_field_id($value) . '">' . __('Single image', 'wdv-mailchimp-ajax') . '</label>';
            
            echo '</p>';
            ?>    

        </p>        
        <!---------------------------->
        <!--Image size and border radius--> 
        <!---------------------------->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('img-width')); ?>"><?php esc_html_e('Width (for single image)', 'wdv-mailchimp-ajax'); ?>:</label>
            <input class="widefat" type="number" min="20" max="500" step="5" id="<?php echo esc_attr($this->get_field_id('img-width')); ?>" name="<?php echo esc_attr($this->get_field_name('img-width')); ?>" value="<?php echo esc_attr($instance['img-width']); ?>" />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('img-height')); ?>"><?php esc_html_e('Height (for single image)', 'wdv-mailchimp-ajax'); ?>:</label>
            <input class="widefat" type="number" min="20" max="500" step="5" id="<?php echo esc_attr($this->get_field_id('img-height')); ?>" name="<?php echo esc_attr($this->get_field_name('img-height')); ?>" value="<?php echo esc_attr($instance['img-height']); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('img-radius')); ?>"><?php esc_html_e('Border radius (for single image)', 'wdv-mailchimp-ajax'); ?>:</label>
            <input class="widefat" type="number" min="0" max="500" step="1" id="<?php echo esc_attr($this->get_field_id('img-radius')); ?>" name="<?php echo esc_attr($this->get_field_name('img-radius')); ?>" value="<?php echo esc_attr($instance['img-radius']); ?>" />
        </p>

        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'description' )); ?>"><?php _e( 'Description:', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'description' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'description' )); ?>" type="text" value="<?php echo esc_attr( $instance['description'] ); ?>" />
        </p>
        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>"><?php _e( 'Placeholder for e-mail:', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'placeholder' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'placeholder' )); ?>" type="text" value="<?php echo esc_attr( $instance['placeholder'] ); ?>" />
        </p>    
        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'buttontext' )); ?>"><?php _e( 'Text on button:', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'buttontext' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'buttontext' )); ?>" type="text" value="<?php echo esc_attr( $instance['buttontext'] ); ?>" />
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id( 'apiKey' )); ?>"><?php _e( 'API Key (from MailChimp):', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'apiKey' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'apiKey' )); ?>" type="text" value="<?php echo esc_attr( $instance['apiKey'] ); ?>" />
        </p>
        <p>                                                                  
        <label for="<?php echo esc_attr($this->get_field_id( 'listID' )); ?>"><?php _e( 'Audience ID (from MailChimp):', 'wdv-mailchimp-ajax' ); ?></label>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'listID' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'listID' )); ?>" type="text" value="<?php echo esc_attr( $instance['listID'] ); ?>" />
        </p> 
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('padding')); ?>"><?php esc_html_e(__('Create padding around the widget (in pixels):', 'wdv-mailchimp-ajax')); ?></label>
            <input class="widefat" type="number" min="0" max="70" step="1" id="<?php echo esc_attr($this->get_field_id('padding')); ?>" name="<?php echo esc_attr($this->get_field_name('padding')); ?>" value="<?php echo esc_attr($instance['padding']); ?>" />
        </p>          
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('titletextcolor')); ?>"><?php _e('Change the color of the title:', 'wdv-mailchimp-ajax'); ?></label><br> 
        <input id="title-color-field" id="<?php echo esc_attr($this->get_field_id('titletextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('titletextcolor')); ?>" type="text" value="<?php if($instance['titletextcolor']) { echo esc_attr($instance['titletextcolor']); }  ?>" />    
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('descriptiontextcolor')); ?>"><?php _e('Change the color of the description:', 'wdv-mailchimp-ajax'); ?></label><br> 
        <input id="description-color-field" id="<?php echo esc_attr($this->get_field_id('descriptiontextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('descriptiontextcolor')); ?>" type="text" value="<?php if($instance['descriptiontextcolor']) { echo esc_attr($instance['descriptiontextcolor']); } ?>" />    
        </p>        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('buttonbgcolor')); ?>"><?php _e('Change the color for the button background:', 'wdv-mailchimp-ajax'); ?></label><br> 
        <input id="color-field" id="<?php echo esc_attr($this->get_field_id('buttonbgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('buttonbgcolor')); ?>" type="text" value="<?php if($instance['buttonbgcolor']) { echo esc_attr($instance['buttonbgcolor']); } ?>" />    
        </p>
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('buttontextcolor')); ?>"><?php _e("Change the color for the text on the button:", 'wdv-mailchimp-ajax'); ?></label><br> 
        <input id="btn-txt-color" id="<?php echo esc_attr($this->get_field_id('buttontextcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('buttontextcolor')); ?>" type="text" value="<?php if($instance['buttontextcolor']) { echo esc_attr($instance['buttontextcolor']); }?>" />    
        </p>        
        <p>
        <label for="<?php echo esc_attr($this->get_field_id('bgcolor')); ?>"><?php _e('Change the color of the widget background:', 'wdv-mailchimp-ajax'); ?></label><br> 
        <input id="color-bg" id="<?php echo esc_attr($this->get_field_id('bgcolor')); ?>" name="<?php echo esc_attr($this->get_field_name('bgcolor')); ?>" type="text" value="<?php if($instance['bgcolor']) { echo esc_attr($instance['bgcolor']); } ?>" />    
        </p> 

        <p>
            <input class="checkbox" type="checkbox" <?php checked($instance['btnwidth'], 'on'); ?> id="<?php echo $this->get_field_id('btnwidth'); ?>" name="<?php echo $this->get_field_name('btnwidth'); ?>"/>
            <label for="<?php echo $this->get_field_id('btnwidth'); ?>"><?php _e('Subscribe button have width 100%', 'wdv-mailchimp-ajax'); ?></label>
        </p>                 
        <?php
    }

    //backend update
    public function update($new_instance, $old_instance) {
        $new_instance['title'] = strip_tags($new_instance['title']);
        $new_instance['image'] = esc_url_raw($new_instance['image']);
        $new_instance['img-width'] = (int)($new_instance['img-width']);
        $new_instance['img-height'] = (int)($new_instance['img-height']);
        $new_instance['img-radius'] = (int)($new_instance['img-radius']);
        $new_instance['img-show'] = strip_tags($new_instance['img-show']);
        $new_instance['titletextcolor'] = strip_tags($new_instance['titletextcolor']);
        $new_instance['descriptiontextcolor'] = strip_tags($new_instance['descriptiontextcolor']);
        $new_instance['buttonbgcolor'] = strip_tags($new_instance['buttonbgcolor']);
        $new_instance['buttontextcolor'] = esc_textarea($new_instance['buttontextcolor']);
        $new_instance['bgcolor'] = strip_tags($new_instance['bgcolor']);
        $new_instance['padding'] = (int)($new_instance['padding']);
        $new_instance['description'] = strip_tags($new_instance['description']);
        
        $new_instance['placeholder'] = strip_tags($new_instance['placeholder']); 
        $new_instance['buttontext'] = strip_tags($new_instance['buttontext']);   
        $new_instance['btnwidth'] = strip_tags($new_instance['btnwidth']); 
        $new_instance['listID'] = strip_tags($new_instance['listID']);
        $new_instance['apiKey'] = strip_tags($new_instance['apiKey']);
 
        return $new_instance;
    }

}
