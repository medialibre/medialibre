<?php
$date   = date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) );
$author = get_the_author();
?>

<div class="post-meta">
	<p>
		<?php printf( __( '%1$s', 'founder' ), $date, $author ); ?>
	</p>
</div>