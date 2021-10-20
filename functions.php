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


function yoko_child_one_sidebar_only_register_widgets() {
    require 'inc/class.wp-widget-text-highlighted.php';
	register_widget( 'WP_Widget_Text_Highlighted' );
}

add_action( 'widgets_init', 'yoko_child_one_sidebar_only_register_widgets' );
