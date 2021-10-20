<?php

class WP_Widget_Text_Highlighted extends WP_Widget {

	function __construct() {
		$widget_ops  = array(
			'classname'   => 'widget_text widget_text_highlighted',
			'description' => __( 'Arbitrary text or HTML.' )
		);
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'text_highlighted', __( 'Text highlighted' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text  = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		?>
        <div class="textwidget"><?php echo ! empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['text'] = $new_instance['text'];
		} else {
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) );
		} // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset( $new_instance['filter'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title    = strip_tags( $instance['title'] );
		$text     = esc_textarea( $instance['text'] );
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
                   value="<?php echo esc_attr( $title ); ?>"/></p>

        <textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo $text; ?></textarea>

        <p><input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"
                  name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>"
                  type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;<label
                    for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"><?php esc_html_e( 'Automatically add paragraphs' ); ?></label>
        </p>
		<?php
	}
}