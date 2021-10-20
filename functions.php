<?php
/**
 * @package WordPress
 * @subpackage Yoko Child One Sidebar Only
 */

function yoko_child_one_sidebar_only_unregister_sidebar() {
	unregister_sidebar( 'sidebar-2' );
}
add_action( 'init', 'yoko_child_one_sidebar_only_unregister_sidebar' );

function yoko_child_one_sidebar_only_enqueue_style() {
	wp_enqueue_style(
		'yoko-style',
		get_template_directory_uri() . '/style.css',
        null,
		gmdate( 'YmdHi', filemtime( get_template_directory() . '/style.css' ) )

	);
	wp_enqueue_style(
		'yoko-child-one-sidebar-only-styles',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'yoko-style' ),
		gmdate( 'YmdHi', filemtime( get_stylesheet_directory() . '/style.css' ) )
	);
	wp_enqueue_script(
		'yoko-navigation',
		get_stylesheet_directory_uri() . '/js/navigation.js',
		array(),
		gmdate( 'YmdHi', filemtime( get_stylesheet_directory() . '/style.css' ) ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'yoko_child_one_sidebar_only_enqueue_style' );

function yoko_child_one_sidebar_only_footer_menu_setup() {
    register_nav_menus( array(
        'footer' => __( 'Footer Menu', 'yoko_child_one_sidebar_only' ),
    ) );
}
add_action( 'after_setup_theme', 'yoko_child_one_sidebar_only_footer_menu_setup' );


class WP_Widget_Text_Highlighted extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_text widget_text_highlighted', 'description' => __( 'Arbitrary text or HTML.' ) );
		$control_ops = array( 'width' => 400, 'height' => 350 );
		parent::__construct( 'text_highlighted', __( 'Text highlighted' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } ?>
			<div class="textwidget"><?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?></div>
		<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		if ( current_user_can('unfiltered_html') )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset($new_instance['filter']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title = strip_tags( $instance['title'] );
		$text  = esc_textarea( $instance['text'] );
?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'filter' ) ); ?>" type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo esc_attr( $this->get_field_id( 'filter' ) ); ?>"><?php esc_html_e( 'Automatically add paragraphs' ); ?></label></p>
<?php
	}
}
register_widget( 'WP_Widget_Text_Highlighted' );

function yoko_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
		case 'comment' :
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>">
				<div class="comment-gravatar"><?php echo get_avatar( $comment, 65 ); ?></div>

				<div class="comment-body">
					<div class="comment-meta commentmetadata">
						<?php printf( esc_html__( '%s', 'yoko' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?><br/>
						<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<?php
							/* translators: 1: date, 2: time */
							printf( esc_html__( '%1$s at %2$s', 'yoko' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( esc_html__( 'Edit &rarr;', 'yoko' ), ' ' );
						?>
					</div><!-- .comment-meta .commentmetadata -->

					<?php comment_text(); ?>

					<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'yoko' ); ?></p>
					<?php endif; ?>

					<div class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div><!-- .reply -->

				</div>
				<!--comment Body-->

			</div><!-- #comment-##  -->

			<?php
			break;
		case 'pingback'  :
		case 'trackback' :
			?>
			<li class="post pingback">
			<p><?php esc_html_e( 'Pingback:', 'yoko' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'yoko'), ' ' ); ?></p>
			<?php
			break;
	endswitch;
}