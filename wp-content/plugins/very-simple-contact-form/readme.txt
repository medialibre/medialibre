=== Very Simple Contact Form ===
Contributors: Guido07111975
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=donation%40guidovanderleest%2enl
Version: 4.8
License: GNU General Public License v3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Requires at least: 3.7
Tested up to: 4.4
Stable tag: trunk
Tags: simple, responsive, contact, form, email, honeypot, captcha, widget, sidebar


== Changelog ==
= Version 4.8 =
* files vscf_main and vscf_widget_form: simplyfied field validation
* relocated file vscf_style to folder css
* updated file vscf_style
* updated readme file

= Version 4.7 =
* added a PayPal donate link
* updated readme file

= Version 4.6 =
* updated file readme
* updated the FAQ
* updated file vscf_settings
* file vscf_widget: changed label Shortcode Attributes into Attributes, to avoid confusion

= Version 4.5 =
* updated file vscf_widget: you can use all shortcode attributes for the widget now. More info about this at the Installation section.  
* updated file readme

= Version 4.4 =
* removed translations: plugin now support WordPress language packs
* added file uninstall.php so settings in database are removed when uninstalling plugin
* updated files vscf_main, vscf_widget_form and readme
* file vscf_setting: removed function to set mail header 'Sender' because many servers ignore this

= Version 4.3 =
* bugfix

= Version 4.2 =
* updated files vscf_main and vscf_widget_form
* renamed sum_fields: captcha_fields
* renamed security_fields: honeypot_fields

= Version 4.1 =
* updated Data Validation and Escaping again
* text area validation: replaced my own 'vscf_sanitize_text_area' with 'strip_tags'
* updated FAQ

= Version 4.0 =
* updated files vscf_main and vscf_widget_form 
* updated Data Validation and Escaping

= Version 3.9 =
* changed text domain for the wordpress.org translation system
* updated the FAQ

= Version 3.8 =
* marjor update
* as mentioned before I have removed the custom style editor
* fixed bug with REPLY-TO header and Cyrilic language
* updated most php files
* added Bulgarian translation (thanks Nikolay Nikolov)
* added Portuguese translation (thanks Marta Ferreira)
* updated language files
* updated FAQ

= Version 3.7 =
* NOTE: in next version I will remove the custom style editor. More info: WordPress plugin page and readme file
* file vscf_widget: updated php constructor and frontend code

= Version 3.6 =
* updated language files

= Version 3.5 =
* added Finnish translation (thanks Sami Skogberg)
* several small adjustments
* updated language files

= Version 3.4 =
* adjusted the email headers to avoid messages go directly in junk/spam folder: added Reply-To and Return-Path
* renamed vscf_sanitize_text_field into vscf_sanitize_text_area
* updated language files

= Version 3.3 =
* removed 'extract' from files vscf_main and vscf_widget_form
* adjusted code in files vscf_main and vscf_widget_form
* added Swedish translation (thanks Bo Ahlqvist)

= Version 3.2 =
* request: changed required number of characters from 3 to 2 (name and subject field)
* fixed bug with captcha not working properly in widget (in version 3.1)
* added Italian translation (thanks Antonio Melcore)

= Version 3.1 =
* cleaned up code in files vscf_main and vscf_widget_form
* added Turkish translation (thanks WordCommerce)

= Version 3.0 =
* major update
* added Custom Style editor: you can change the layout (CSS) of your form using the custom style page in WP dashboard
* linebreaks in textarea field are allowed now
* updated language files with help from nice users listed below and Google Translate

= Version 2.9 =
* fixed bug in locale of Catalan, Croatian and Estonian language  
* added Slovenian translation (thanks Maja Blejec)

= Version 2.8 =
* form will now use theme styling for input fields and submit button. If not supported in your theme you can activate plugin styling in file vscf_style
* added Estonian translation (thanks Villem Kuusk)

= Version 2.7 =
* added Polish translation (thanks Milosz Raczkowski)
* replaced all divs with paragraph tags for better form display

= Version 2.6 =
* added file vscf_widget_form
* fixed bug with widget: now you can use form and widget on same website
* updated language files

= Version 2.5 =
* major update
* added file vscf_widget
* added Very Simple Contact Form widget: now you can display form in sidebar too
* updated language files

= Version 2.4 =
* major update
* added anti-spam: honeypot fields and a simple captcha sum
* adjusted stylesheet
* updated language files

= Version 2.3 =
* fixed small coding error in file vscf_main

= Version 2.2 =
* added Danish language (thanks Borge Kolding)
* updated FAQ
* adjusted stylesheet

= Version 2.1 =
* adjusted stylesheet
* updated language files

= Version 2.0 =
* major update
* removed function vscf_clean_input and replaced it with default WP function sanitize_text_field: now all UTF-8 characters are supported!
* added Catalan translation (thanks Miquel Serrat)
* updated FAQ

= Version 1.9 =
* added Croatian translation (thanks Dario Abram)
* added FAQ

= Version 1.8 =
* adjusted function vscf_clean_input. Only allowed: letters (a-z), digits (0-10), space, point, hyphen and comma
* added Brazilian Portuguese translation (thanks Gustavo Lucas)

= Version 1.7 =
* changed shortcode 'email' into 'email_to' (to avoid possible conflict with the email input field)
* added name and email in text of message to admin

= Version 1.6 =
* updated several translation files
* added Spanish translation (thanks Alvaro Reig Gonzalez)

= Version 1.5 =
* several small frontend adjustments
 
= Version 1.4 =
* several small adjustments

= Version 1.3 =
* removed code that wasn't necessary
* added Hungarian translation (thanks Roman Kekesi)

= Version 1.2 =
* IMPORTANT SECURITY UPDATE > please do not use older version of plugin
* removed jquery validation (and folder .js)
* several other small adjustments

= Version 1.1 =
* removed font-family from stylesheet
* added French and German translation (thanks Curlybracket)

= Version 1.0 =
* first stable release


== Description ==
This is a very simple responsive translation-ready contact form. 

It only contains Name, Email, Subject and Message. And a simple captcha sum. 

Use shortcode `[contact]` to display form on page or use the widget to display form in sidebar.

It supports several attributes to personalize your form. You can find more info about this at the Installation section.

= Question? = 
Please take a look at the FAQ section.

= Translation =
Not included but plugin supports WordPress language packs.

More [translations](https://translate.wordpress.org/projects/wp-plugins/very-simple-contact-form) are very welcome!

= Credits =
Without the WordPress codex and help from the WordPress community I was not able to develop this plugin, so: thank you!

And I would like to thank the users of 'PHP hulp' for helping me creating bugfree code.

Enjoy!


== Installation == 
After installation add shortcode `[contact]` on your page to display form. 

Or go to Appearance > Widgets and add the widget to your sidebar.

By default messages will be send to email from admin (set in Settings > General).

While adding the shortcode or the widget you can add several attributes to personalize your form.

= Shortcode attributes = 
Example changing email from admin: `[contact email_to="your-email-here"]`

When using multiple email: `[contact email_to="first-email-here, second-email-here"]`

You can also change message text or label text using an attribute.

* Label attributes: label_name, label_email, label_subject, label_captcha, label_message, label_submit
* Label error attributes: error_name, error_email, error_subject, error_captcha, error_message
* Message attributes: message_error, message_success

Example changing labels for Name and Submit: `[contact label_name="Your Name" label_submit="Send"]`

= Widget attributes =
The sidebar widget supports the same attributes.

Example changing email from admin: `email_to="your-email-here"`

When using multiple email: `email_to="first-email-here, second-email-here"`

Example changing labels for Name and Submit: `label_name="Your Name" label_submit="Send"`


== Frequently Asked Questions ==
= How do I set plugin language? =
Very Simple Contact Form uses the WP Dashboard language, set in Settings > General. If plugin language pack is not available, form will display in English.

= Where do I add the widget attributes? =
While adding the widget you will notice a field called Attributes. You can find more info about this at the Installation section.

= Can I change form layout? =
Form will use theme styling for input fields and submit button (if available). 

You can change the layout (CSS) of your form using for example the [Very Simple Custom Style](https://wordpress.org/plugins/very-simple-custom-style) plugin.

= Why am I not receiving messages? =
* Look also in your junk/spam folder.
* Check the Installation section and check shortcode for mistakes.
* Install another contactform plugin (such as Contact Form 7) to determine if it's caused by Very Simple Contact Form or something else.
* Messages are send using the wp_mail function (similar to php mail function). Maybe your hostingprovider disabled the php mail function, ask them to enable it.

= Why am I having trouble with the captcha number? =
The build in captcha (random number) uses a php session to temporary store the number and some hostingproviders have disabled the use of sessions. Ask them for more info about this.

= Can I use multiple forms on the same website? =
Don't use multiple forms on the same website. This may cause a conflict. 

But you can use the shortcode on your page and the widget on the same website.

= Can user add html in form? =
No, html tags are removed and the content of the message send to admin is text/plain.

= Is this plugin protected against spammers, bots, etc? =
Of course, the default WordPress sanitization and escaping functions are included.

It also contains honeypot fields and a simple captcha sum.

= Why does this form has 2 invisible fields (firstname and lastname)? =
This is part of anti-spam: these are the 2 honeypot fields.

= How can I make a donation? =
You like my plugin and you're willing to make a donation? Nice! There's a PayPal donate link on the WordPress plugin page and my website.

= Other question or comment? =
Please open a topic in plugin forum.


== Screenshots == 
1. Very Simple Contact Form in frontend (using Twenty Sixteen theme).
2. Very Simple Contact Form in frontend (using Twenty Sixteen theme).
3. Very Simple Contact Form in backend (sidebar widget).