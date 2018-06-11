<?php do_action( 'main_bottom' ); ?>
</section><!-- .main -->

<footer id="site-footer" class="site-footer" role="contentinfo">
	<?php do_action( 'footer_top' ); ?>
	<span>
        <?php
		$footer_text = 'Το περιεχόμενο των κειμένων ανήκει στ@ν αρχικ@ συγγραφέα/μέσο, όπου αναγράφεται.<br>Οι μεταφράσεις μας διατίθονται με άδεια <a href="http://creativecommons.org/licenses/by-sa/4.0/" target="_blank">Creative Commons Attribution-ShareAlike</a>.<br><i class="fa fa-power-off"></i> hosted by <a href="https://libreops.cc/" target="_blank">LibreOps</a>';
        $footer_text = apply_filters( 'ct_founder_footer_text', $footer_text );
        echo wp_kses_post( $footer_text );
        ?>
    </span>
</footer>
</div>
</div><!-- .overflow-container -->

<?php do_action( 'body_bottom' ); ?>

<?php wp_footer(); ?>

</body>
</html>