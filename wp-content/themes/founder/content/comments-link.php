<span class="post-comments">
	<i class="fa fa-comment" title="<?php _e( 'comment icon', 'founder' ); ?>"></i>
	<?php
	if ( ! comments_open() && get_comments_number() < 1 ) :
		comments_number( __( 'Comments closed', 'founder' ), __( 'One Comment', 'founder' ), __( '% Comments', 'founder' ) );
	else :
		echo '<a href="' . esc_url( get_comments_link() ) . '">';
		comments_number( __( 'Leave a Comment', 'founder' ), __( 'One Comment', 'founder' ), __( '% Comments', 'founder' ) );
		echo '</a>';
	endif;
	?>
</span>