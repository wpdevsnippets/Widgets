<?php
/**
 * Shortcode Widget
 * This simple widget will allow you to use shortcode in your text widget.
 *
 * Author: WPDevSnippets.com
 * @package WordPress
 */

class Shortcodes_Widget extends WP_Widget {
  /**
	 * Widget constructor 
     *
	 * @desc sets default options and controls for widget
	 */
	function Shortcodes_Widget () {
		/* Widget settings */
		$widget_ops = array (
			'classname' => 'widget_shortcodes',
			'description' => __( 'Show shortcodes' )			
		);

		/* Create the widget */
		$this->WP_Widget( 'shortcodes-widget', __( 'Shortcodes' ), $widget_ops );
	}
	
	/**
	 * Displaying the widget
	 *
	 * Handle the display of the widget
	 * @param array
	 * @param array
	 */
	function widget( $args, $instance ) {
		
		extract ($args);
		
		echo $before_widget;
		
		echo $before_title . $instance['title'] . $after_title;
			
		echo do_shortcode($instance['shortcodes']);
			
		echo $after_widget;

	}
	
	/**
	 * Update and save widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array New widget values
	 */
	function update ( $new_instance, $old_instance ) {	
		$old_instance['title'] = strip_tags( $new_instance['title'] );
		$old_instance['shortcodes'] = strip_tags( $new_instance['shortcodes'] );
	    	
		return $old_instance;
	}
	
	/**
	 * Creates widget controls or settings
	 *
	 * @param array Return widget options form
	 */
	function form ( $instance ) { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title',"ait-theme" ); ?>:</label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"class="widefat" style="width:100%;" />
			<label for="<?php echo $this->get_field_id( 'shortcodes' ); ?>"><?php echo __( 'Shortcodes' ); ?>:</label>
			<textarea id="<?php echo $this->get_field_id( 'shortcodes' ); ?>" name="<?php echo $this->get_field_name( 'shortcodes' ); ?>" style="width:100%;"><?php echo $instance['shortcodes']; ?></textarea>
        </p>
              
        <?php
	}
}
register_widget( 'Shortcodes_Widget' );
