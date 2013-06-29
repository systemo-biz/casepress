<?php
class Cases_Workflow_Labels_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_cases_workflow_labels', 'description' => 'Ярлыки пользователя' );
		parent::__construct( 'cases-workflow-labels', 'Cases. Workflow Labels', $widget_ops );
	}

	function widget( $args, $instance ) {
		global $cases_workflow_labels;

		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? 'Ярлыки' : $instance['title'], $instance, $this->id_base );

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		$cases_workflow_labels->get_label_list( 'style=list' );

		echo $after_widget ;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags( $instance['title'] );
?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
<?php
	}
}
?>