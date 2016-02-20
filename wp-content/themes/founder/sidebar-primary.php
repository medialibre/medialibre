<?php if ( is_active_sidebar( 'primary' ) ) :

	$widgets      = get_option( 'sidebars_widgets' );
	$widget_count = count( $widgets['primary'] );
	?>
	<aside class="sidebar sidebar-primary" id="sidebar-primary" role="complementary">
		<button id="toggle-sidebar" class="toggle-sidebar" name="toggle-sidebar" aria-expanded="false">
			<span class="screen-reader-text"><?php _e( 'open sidebar', 'founder' ); ?></span>
			<i class="fa fa-angle-down" title="<?php _e( 'sidebar icon', 'founder' ); ?>" aria-hidden="true"></i>
		</button>
		<div id="sidebar-primary-content" class="sidebar-primary-content">
			<div id="sidebar-primary-widgets" class="sidebar-primary-widgets active-<?php echo $widget_count; ?>">
				<?php dynamic_sidebar( 'primary' ); ?>
			</div>
		</div>
	</aside>

<?php endif;