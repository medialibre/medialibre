<?php

// Start session for captcha validation
if (!isset ($_SESSION)) session_start(); 
$_SESSION['vscf-rand'] = isset($_SESSION['vscf-rand']) ? $_SESSION['vscf-rand'] : rand(100, 999);

// The shortcode
function vscf_shortcode($vscf_atts) {
	$vscf_atts = shortcode_atts( array( 
		"email_to" => get_bloginfo('admin_email'),
		"label_name" => __('Name', 'very-simple-contact-form'),
		"label_email" => __('Email', 'very-simple-contact-form'),
		"label_subject" => __('Subject', 'very-simple-contact-form'),
		"label_message" => __('Message', 'very-simple-contact-form'),
		"label_captcha" => __('Enter number %s', 'very-simple-contact-form'),
		"label_submit" => __('Submit', 'very-simple-contact-form'),
		"error_name" => __('Please enter at least 2 characters', 'very-simple-contact-form'),
		"error_subject" => __('Please enter at least 2 characters', 'very-simple-contact-form'),
		"error_message" => __('Please enter at least 10 characters', 'very-simple-contact-form'),
		"error_captcha" => __('Please enter the correct number', 'very-simple-contact-form'),
		"error_email" => __('Please enter a valid email', 'very-simple-contact-form'),
		"message_error" => __('Please fill in all the required fields', 'very-simple-contact-form'),
		"message_success" => __('Thank you for your message! You will receive a response as soon as possible.', 'very-simple-contact-form')
	), $vscf_atts);

	// Set some variables 
	$form_data = array(
		'form_name' => '',
		'email' => '',
		'form_subject' => '',
		'form_captcha' => '',
		'form_firstname' => '',
		'form_lastname' => '',
		'form_message' => ''
	);
	$error = false;
	$sent = false;
	$info = '';

	if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['form_send']) ) {
	
		// Get posted data and sanitize it
		$post_data = array(
			'form_name' => sanitize_text_field($_POST['form_name']),
			'email' => sanitize_email($_POST['email']),
			'form_subject' => sanitize_text_field($_POST['form_subject']),
			'form_message' => strip_tags($_POST['form_message']),
			'form_captcha' => sanitize_text_field($_POST['form_captcha']),
			'form_firstname' => sanitize_text_field($_POST['form_firstname']),
			'form_lastname' => sanitize_text_field($_POST['form_lastname'])
		);

		// Validate name
		$value = $post_data['form_name'];
		if ( strlen($value)<2 ) {
			$error_class['form_name'] = true;
			$error = true;
			$result = $vscf_atts['message_error'];
		}
		$form_data['form_name'] = $value;

		// Validate email
		$value = $post_data['email'];
		if ( empty($value) ) {
			$error_class['email'] = true;
			$error = true;
			$result = $vscf_atts['message_error'];
		}
		$form_data['email'] = $value;

		// Validate subject
		$value = $post_data['form_subject'];
		if ( strlen($value)<2 ) {
			$error_class['form_subject'] = true;
			$error = true;
			$result = $vscf_atts['message_error'];
		}
		$form_data['form_subject'] = $value;

		// Validate message
		$value = $post_data['form_message'];
		if ( strlen($value)<10 ) {
			$error_class['form_message'] = true;
			$error = true;
			$result = $vscf_atts['message_error'];
		}
		$form_data['form_message'] = $value;

		// Validate captcha
		$value = $post_data['form_captcha'];
		if ( $value != $_SESSION['vscf-rand'] ) { 
			$error_class['form_captcha'] = true;
			$error = true;
			$result = $vscf_atts['message_error'];
		}
		$form_data['form_captcha'] = $value;

		// Validate first honeypot field
		$value = $post_data['form_firstname'];
		if ( strlen($value)>0 ) {
			$error = true;
		}
		$form_data['form_firstname'] = $value;

		// Validate second honeypot field
		$value = $post_data['form_lastname'];
		if ( strlen($value)>0 ) {
			$error = true;
		}
		$form_data['form_lastname'] = $value;

		// Sending message to admin
		if ($error == false) {
			$to = $vscf_atts['email_to'];
			$subject = "(".get_bloginfo('name').") " . $form_data['form_subject'];
			$message = $form_data['form_name'] . "\r\n\r\n" . $form_data['email'] . "\r\n\r\n" . $form_data['form_message'] . "\r\n\r\n" . sprintf( esc_attr__( 'IP: %s', 'very-simple-contact-form' ), vscf_get_the_ip() ); 
			$headers = "Content-Type: text/plain; charset=UTF-8" . "\r\n";
			$headers .= "Content-Transfer-Encoding: 8bit" . "\r\n";
			$headers .= "From: ".$form_data['form_name']." <".$form_data['email'].">" . "\r\n";
			$headers .= "Reply-To: <".$form_data['email'].">" . "\r\n";
			wp_mail($to, $subject, $message, $headers);
			$result = $vscf_atts['message_success'];
			$sent = true;
		}
	}

	// Display message above form in case of success or error 
	if(!empty($result)) {
		$info = '<p class="vscf_info">'.esc_attr($result).'</p>';
	}

	// The Contact form
	$email_form = '<form class="vscf" id="vscf" method="post" action="">
		
		<p><label for="vscf_name">'.esc_attr($vscf_atts['label_name']).': <span class="'.((isset($error_class['form_name'])) ? "error" : "hide").'" >'.esc_attr($vscf_atts['error_name']).'</span></label></p>
		<p><input type="text" name="form_name" id="vscf_name" class="'.((isset($error_class['form_name'])) ? "error" : "").'" maxlength="50" value="'.esc_attr($form_data['form_name']).'" /></p>
		
		<p><label for="vscf_email">'.esc_attr($vscf_atts['label_email']).': <span class="'.((isset($error_class['email'])) ? "error" : "hide").'" >'.esc_attr($vscf_atts['error_email']).'</span></label></p>
		<p><input type="text" name="email" id="vscf_email" class="'.((isset($error_class['email'])) ? "error" : "").'" maxlength="50" value="'.esc_attr($form_data['email']).'" /></p>
		
		<p><label for="vscf_subject">'.esc_attr($vscf_atts['label_subject']).': <span class="'.((isset($error_class['form_subject'])) ? "error" : "hide").'" >'.esc_attr($vscf_atts['error_subject']).'</span></label></p>
		<p><input type="text" name="form_subject" id="vscf_subject" class="'.((isset($error_class['form_subject'])) ? "error" : "").'" maxlength="50" value="'.esc_attr($form_data['form_subject']).'" /></p>
		
		<p><label for="vscf_captcha">'.sprintf(esc_attr($vscf_atts['label_captcha']), $_SESSION['vscf-rand']).': <span class="'.((isset($error_class['form_captcha'])) ? "error" : "hide").'" >'.esc_attr($vscf_atts['error_captcha']).'</span></label></p>
		<p><input type="text" name="form_captcha" id="vscf_captcha" class="'.((isset($error_class['form_captcha'])) ? "error" : "").'" maxlength="50" value="'.esc_attr($form_data['form_captcha']).'" /></p>
		
		<p><input type="text" name="form_firstname" id="vscf_firstname" maxlength="50" value="'.esc_attr($form_data['form_firstname']).'" /></p>
		
		<p><input type="text" name="form_lastname" id="vscf_lastname" maxlength="50" value="'.esc_attr($form_data['form_lastname']).'" /></p>
		
		<p><label for="vscf_message">'.esc_attr($vscf_atts['label_message']).': <span class="'.((isset($error_class['form_message'])) ? "error" : "hide").'" >'.esc_attr($vscf_atts['error_message']).'</span></label></p>
		<p><textarea name="form_message" id="vscf_message" rows="10" class="'.((isset($error_class['form_message'])) ? "error" : "").'" >'.esc_textarea($form_data['form_message']).'</textarea></p>
		
		<p><input type="submit" value="'.esc_attr($vscf_atts['label_submit']).'" name="form_send" class="vscf_send" id="vscf_send" /></p>
		
	</form>';
	
	// Send message and unset captcha variabele or display form with error message
	if(isset($sent) && $sent == true) {
		unset($_SESSION['vscf-rand']);
		return $info;
	} else {
		return $info . $email_form;
	}
} 
add_shortcode('contact', 'vscf_shortcode');

?>