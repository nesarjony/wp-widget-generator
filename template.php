<?php


class %widget_class% extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */

	function __construct() {

		parent::__construct(
			'%widget_id%', // Base ID
			__( '%widget_title%', '%textdomain%' ), // Name
			array( 'description' => __( '%description%', '%textdomain%' ) ) // Args
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		%widget_contents%
?>	
	<!-- Put your html here -->
<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */

	public function form( $instance ) {

		%form_contents%
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

	public function update( $new_instance, $old_instance ) {
		
		%update_contents%

		return $instance;
	}

}


add_action("widgets_init",function(){
	register_widget('%widget_class%');
});