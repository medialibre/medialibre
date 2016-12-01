<?php get_header();

get_template_part( 'content/archive-header' ); ?>

<!--
<div style="border: 1px solid #800000;border-radius: 10px;padding: 10px;margin-top:1.5em;">
    <h3 style="color:#800000">19/11 Προβολή: WikiRebels</h3>
    
    <p style="font-size:0.8em;display:inline-block;line-height:1;margin-bottom:0;margin-top:10px;">
        To medialibre σχεδιάζει μια σειρά προβολών με ταινίες και ντοκυμαντέρ περιεχομένου που πιστεύουμε
        πως πρέπει να αφορούν και το ελληνόγλωσσο κοινό. Στις προβολές περιλαμβάνονται θέματα ελευθερίας, δικαιωμάτων,
        ισότητας, τεχνολογίας, επιστήμης, πολιτικής κ.α.
    </p>
    
    <p style="margin:0;display:inline-block">
        <strong>Σάββατο 19/11, 20:00 WikiRebels</strong><br>
        Ένα ντοκυμαντέρ που διηγείται την ιστορία του Wikileaks, ακολουθώντας για έξι μήνες τον Julian Assange.<br>
        Μέρος: <a href="https://www.hackerspace.gr/wiki/Medialibre_Screenings_Series_1d" target="_blank">Hackerspace.gr</a><br>
		ελεύθερη είσοδος / ελληνικοί υπότιτλοι
    </p>
</div>
-->
<div id="loop-container" class="loop-container">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			ct_founder_get_content_template();
		endwhile;
	endif;
	?>
</div>

<?php the_posts_pagination();

get_footer();