<?php
/**
 * WPDS Image Widget
 * Frequently, we need to place a third party advertisement banner, a third party product through an affiliate program or just an image to draw attention of visitors to new features you just offered. This one widget has the capability to fulfil all these requirements from simple image only banner to detailed product advertisement.  
 *
 * Author: WPDevSnippets.com
 * @package WordPress
 */

add_action('widgets_init', create_function('', 'return register_widget("WPDS_Image");'));
class WPDS_Image extends WP_Widget 
{
   var $defaults = array(
        'title' => 'Image Title',
        'description' => 'Add image/product description here.',
        'image' => '',
        'link' => '',
        'more_label' => 'View Details &raquo;',
        'image_position' => 'before_title',
        'image_align' => 'aligncenter',
    );
    
    function __construct() 
    {
        $widget_options = array('description' => __('Add an image, advertisement or product with its description and link.', 'wpds') );
        $this->WP_Widget('wpdev_image', '&raquo; WPDS Image', $widget_options, array( 'width' => 500));
    }

    function widget($args, $instance)
    {
        global $theme;
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        echo $before_widget;

        if($instance['image']) {
            $image_align = $instance['image_align'];
            if($instance['image_position'] == 'bottom') {
                $image_align .= ' inbottom';
            }
            if($instance['link']) {
                $output_image = '<a href="' . $instance['link'] .'"><img src="' . $instance['image'] .'" class="' . $image_align . '" /></a>';
            } else {
                $output_image = '<img src="' . $instance['image'] .'" class="' . $image_align . '" />';
            }
        } else {
            $output_image = false;
        }
         
        
        
    ?>
        <ul class="widget-container"><li class="wpds_image-widget">
            <?php
                if($output_image && $instance['image_position'] == 'before_title')
                    echo $output_image;
                
  	if ( $title )
		
                if ($title) {
                    echo $before_title;
                    echo ($instance['link'])? '<a href="<?php echo '.$instance['link'].'">'.$title .'</a>':$title;
                    echo $after_title;
                }
             ?>
            <ul>
        	   <li class="wpds_image-widget-description">
            <?php
                if($output_image && $instance['image_position'] == 'before_desc')
                    echo $output_image;

                if($instance['description'])
                    echo $instance['description'];

                if($instance['link'] && $instance['more_label'])
                    echo ' <a href="' . $instance['link'] .'" class="wpds_image-widget-more">' . $instance['more_label'] .'<a/>';

                if($output_image && $instance['image_position'] == 'bottom')
                    echo $output_image;
            ?>
               </li>
            </ul>
        </li></ul>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) 
    {

    	$instance = $old_instance;
    	$instance['title'] = strip_tags($new_instance['title']);
        $instance['description'] = $new_instance['description'];
        $instance['link'] = strip_tags($new_instance['link']);
        $instance['more_label'] = $new_instance['more_label'];
        $instance['image'] = strip_tags($new_instance['image']);
        $instance['image_position'] = strip_tags($new_instance['image_position']);
        $instance['image_align'] = strip_tags($new_instance['image_align']);
        return $instance;
    }
    
    function form($instance) 
    {	
	$instance = wp_parse_args( (array) $instance, $this->defaults );
        
        ?>
        
        <div class="wpds-widget">
            <table width="100%">
                <tr>
                    <td class="wpds-widget-label" width="25%"><label for="<?php echo $this->get_field_id('title'); ?>">Title:</label></td>
                    <td class="wpds-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="wpds-widget-label" width="25%"><label for="<?php echo $this->get_field_id('image'); ?>">Image URL:</label></td>
                    <td class="wpds-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_attr($instance['image']); ?>" /></td>
                </tr>
                <tr>
                    <td class="wpds-widget-label" width="25%"><label for="<?php echo $this->get_field_id('link'); ?>">Link URL:</label></td>
                    <td class="wpds-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($instance['link']); ?>" /></td>
                </tr>
                <tr>
                    <td class="wpds-widget-label" valign="top"><label for="<?php echo $this->get_field_id('description'); ?>">Image Description:</label></td>
                    <td class="wpds-widget-content"><textarea class="widefat" id="<?php echo $this->get_field_id('description'); ?>" name="<?php echo $this->get_field_name('description'); ?>" style="height: 160px;"><?php echo esc_attr($instance['description']); ?></textarea></td>
                </tr>
                <tr>
                    <td class="wpds-widget-label" width="25%"><label for="<?php echo $this->get_field_id('more_label'); ?>">"Read More" Label:</label></td>
                    <td class="wpds-widget-content" width="75%"><input class="widefat" id="<?php echo $this->get_field_id('more_label'); ?>" name="<?php echo $this->get_field_name('more_label'); ?>" type="text" value="<?php echo esc_attr($instance['more_label']); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="wpds-widget-label">Image Placement:</td>
                    <td class="wpds-widget-content">
                        <select name="<?php echo $this->get_field_name('image_position'); ?>">
                            <option value="before_title" <?php selected('before_title', $instance['image_position']); ?> >Before Title</option>
                            <option value="before_desc"  <?php selected('before_desc', $instance['image_position']); ?>>Before Description</option>
                            <option value="bottom" <?php selected('bottom', $instance['image_position']); ?>>Bottom</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="wpds-widget-label">Image Alignment:</td>
                    <td class="wpds-widget-content">
                        <select name="<?php echo $this->get_field_name('image_align'); ?>">
                            <option value="alignleft" <?php selected('alignleft', $instance['image_align']); ?> >Left</option>
                            <option value="alignright"  <?php selected('alignright', $instance['image_align']); ?>>Right</option>
                            <option value="aligncenter" <?php selected('aligncenter', $instance['image_align']); ?>>Center</option>
                        </select>
                    </td>
                </tr>
            </table>
          </div>
        <?php 
    }
} 
