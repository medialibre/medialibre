<?php

class vscf_widget extends WP_Widget {

	// Constructor 
	function vscf_widget() {
		$widget_ops = array( 'classname' => 'vscf_sidebar', 'description' => __('Very Simple Contact Form sidebar widget', 'very-simple-contact-form') );
		parent::__construct( 'vscf-widget', __('Very Simple Contact Form', 'very-simple-contact-form'), $widget_ops );
	}

	// Set widget and title in dashboard
	function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'title' => '',
			'attributes' => ''
		));
		$title = !empty( $instance['title'] ) ? $instance['title'] : __('Very Simple Contact Form', 'very-simple-contact-form'); 
		$attributes = $instance['attributes'];
		?> 
		<p> 
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Widget Title', 'very-simple-contact-form'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" maxlength='50' value="<?php echo esc_attr( $title ); ?>">
 		</p> 
		<p> 
		<label for="<?php echo $this->get_field_id( 'attributes' ); ?>"><?php _e('Attributes', 'very-simple-contact-form'); ?>:</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'attributes' ); ?>" name="<?php echo $this->get_field_name( 'attributes' ); ?>" type="text" maxlength='200' value="<?php echo esc_attr( $attributes ); ?>">
 		</p> 
		<?php 
	}

	// Update widget 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Strip tags from title to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['attributes'] = strip_tags( $new_instance['attributes'] );

		return $instance;
	}

	// Display widget with form in frontend 
	function widget( $args, $instance ) {
		echo $args['before_widget']; 

		if ( !empty( $instance['title'] ) ) { 
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title']; 
		} 

		$content = '[contact-widget ';
		if ( !empty( $instance['attributes'] ) ) { 
			$content .= $instance['attributes'];
		}
		$content .= ']';
		echo do_shortcode( $content );

		echo $args['after_widget']; 
	}
}

?>