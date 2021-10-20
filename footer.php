<?php
/**
 * @package WordPress
 * @subpackage Yoko
 */
?>

</div><!-- end wrap -->

	<footer id="colophon" class="clearfix">
		<p>Proudly powered by <a href="http://wordpress.org/">WordPress</a><span class="sep"> | </span><?php printf( esc_html__( 'Theme: %1$s by %2$s', 'yoko' ), 'Yoko', '<a href="http://www.elmastudio.de/en/themes/">Elmastudio</a>' ); ?></p>
		<?php
        // Footer navigation menu.
        wp_nav_menu( array(
            'menu_class'     => 'nav-menu-footer',
            'theme_location' => 'footer',
        ) );
        ?>
		<a href="#page" class="top"><?php esc_html_e( 'Top', 'yoko' ); ?></a>
	</footer><!-- end colophon -->
	
</div><!-- end page -->
<?php wp_footer(); ?>

</body>
</html>