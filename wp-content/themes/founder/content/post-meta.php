<?php
$date   = date_i18n( get_option( 'date_format' ), strtotime( get_the_date( 'r' ) ) );
?>

<div class="post-meta">
	<?php printf( __( '<i class="fa fa-calendar"></i> %1$s', 'founder' ), $date ); ?>
</div>