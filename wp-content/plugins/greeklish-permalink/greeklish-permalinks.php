<?php

/*
Plugin Name: greeklish-permalinks
Plugin URI: https://github.com/elpak/Greeklish-permalink-wordpress
Description: Convert greek characters to latin (greeklish). Greek text turns into a long series of non-readable symbols when appeared in URLs, which makes it look bad. This plugin solves the problem and it just works!
Tags: greek, greeklish permalink, latin, greeklish, slugs, permalinks, gr, autoconvert
Author: Panagiotis Kontogiannis
Version: 3.1
Author URI: https://www.problogger.gr
Requires at least: 3.0
Tested up to: 5.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


if( ! class_exists( 'Trans_Plugin' ) ):

    /**
     * The core plugin class.
     *
     * This is used to define internationalization, admin-specific hooks, and
     * public-facing site hooks.
     *
     * Also maintains the unique identifier of this plugin as well as the current
     * version of the plugin.
     *
     */
    class Trans_Plugin
    {
        /**
         * Cache of update checker
         * Need to delete when license key updated
         */


        /**
         * The unique identifier of this plugin.
         *
         * @since    0.9.0
         * @access   protected
         * @var      string    $plugin_name    The string used to uniquely identify this plugin.
         */
        protected $plugin_name;
        /**
         * The current version of the plugin.
         *
         * @since    0.9.0
         * @access   protected
         * @var      string    $version    The current version of the plugin.
         */
        protected $version;

        /**
         * Option name
         *
         * @var string
         */
        protected $option_name = 'cyrtrans_options';

        /**
         * All options
         *
         * @var mixed|void
         */
        protected $options;



        /**
         * Plugin path
         *
         * @var string
         */
        protected $plugin_path;


        /**
         * Define the core functionality of the plugin.
         *
         * Set the plugin name and the plugin version that can be used throughout the plugin.
         * Load the dependencies, define the locale, and set the hooks for the admin area and
         * the public-facing side of the site.
         *
         */
        public function __construct() {

            // set variables
            $this->plugin_name      = 'greeklish-permalink';
            $this->version          = '3.0';
            $this->plugin_path      = plugin_basename( __FILE__ );

            // set options
            $this->options = get_option($this->option_name);
            add_action( 'init', array($this, 'auto_transliterate_links') );

            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            if ( is_admin() ) {
                require_once dirname(__FILE__) . '/admin/cyrtrans-admin.php';
                new Cyrtrans_Plugin_Admin($this->plugin_name, $this->version, $this->plugin_path);
                add_action( 'wp_ajax_cyrtrans_ajax_old', array( $this, 'cyrtrans_ajax_old') );
                add_action( 'wp_ajax_nopriv_cyrtrans_ajax_old', array( $this,'cyrtrans_ajax_old') );

            }
        }


        /**
         * Retrieve the version number of the plugin.
         *
         * @since     0.9.0
         * @return    string    The version number of the plugin.
         */
        public function get_version() {
            return $this->version;
        }


        /**
         * Check option exist and active
         *
         * @param $name string
         * @return bool
         */
        public function check_option($name) {
            if (isset($this->options[$name]) && $this->options[$name] == 'on')
                return true;

            return false;
        }

        public function auto_transliterate_links() {

            add_filter( 'sanitize_title', array( $this, 'cyr_sanitize_title'), 8 ) ;

        }

        /**
         * Sanitizes a title, replacing whitespace and a few other characters with dashes.
         * Replace greek symbols to latin.
         * Limits the output to alphanumeric characters, underscore (_) and dash (-).
         * Whitespace becomes a dash.
         *
         * @since 1.0.0
         *
         * @param string $title     The title to be sanitized.
         * @return string The sanitized title.
         */
        public function cyr_sanitize_title($title) {
            if ( ! is_admin() ) {
                return $title;
            }
            if (seems_utf8($title)) {
                if (function_exists('mb_strtolower')) {
                    $title = mb_strtolower($title, 'UTF-8');
                }

            }

            $title = strtolower($title);
            $title = str_replace('.', '-', $title);
            $title = preg_replace('/\s+/', '-', $title);
            $title = preg_replace('|-+|', '-', $title);
            $title = trim($title, '-');

            $title = htmlspecialchars(urldecode($title));


            if( $this->check_option('remove-stopwords') ){
                $title = $this->remove_stop_words($title);
            }

            if(  $this->check_option('limit-words') ){
                if(isset($this->options['url_words_limit']) && is_numeric($this->options['url_words_limit'])){
                    $title = $this->limit_words($title, $this->options['url_words_limit']);
                }
            }

            $expressions = array(

                '/[αάΑΆ]/u'   => 'a',
		        '/[βΒ]/u'     => 'v',
		        '/[γΓ]/u'     => 'g',
		        '/[δΔ]/u'     => 'd',
		        '/[εέΕΈ]/u'   => 'e',
		        '/[ζΖ]/u'     => 'z',
		        '/[ηήΗΉ]/u'   => 'i',
		        '/[θΘ]/u'     => 'th',
		        '/[ιίϊΙΊΪ]/u' => 'i',
		        '/[κΚ]/u'     => 'k',
		        '/[λΛ]/u'     => 'l',
		        '/[μΜ]/u'     => 'm',
		        '/[νΝ]/u'     => 'n',
		        '/[ξΞ]/u'     => 'x',
		        '/[οόΟΌ]/u'   => 'o',
		        '/[πΠ]/u'     => 'p',
		        '/[ρΡ]/u'     => 'r',
		        '/[σςΣ]/u'    => 's',
		        '/[τΤ]/u'     => 't',
		        '/[υύϋΥΎΫ]/u' => 'y',
		        '/[φΦ]/iu'    => 'f',
		        '/[χΧ]/u'     => 'ch',
		        '/[ψΨ]/u'     => 'ps',
		        '/[ωώ]/iu'    => 'o',
		        '/[αΑ][ιίΙΊ]/u'                             => 'e',
		        '/[οΟΕε][ιίΙΊ]/u'                           => 'i',
		        '/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
		        '/[αΑ][υύΥΎ]/u'                             => 'av',
		        '/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
		        '/[εΕ][υύΥΎ]/u'                             => 'ev',
		        '/[οΟ][υύΥΎ]/u'                             => 'ou',
		        '/(^|\s)[μΜ][πΠ]/u'                         => '$1b',
		        '/[μΜ][πΠ](\s|$)/u'                         => 'b$1',
		        '/[μΜ][πΠ]/u'                               => 'b',
		        '/[νΝ][τΤ]/u'                               => 'nt',
		        '/[τΤ][σΣ]/u'                               => 'ts',
		        '/[τΤ][ζΖ]/u'                               => 'tz',
		        '/[γΓ][γΓ]/u'                               => 'ng',
		        '/[γΓ][κΚ]/u'                               => 'gk',
		        '/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u'   => 'if$1',
		        '/[ηΗ][υΥ]/u'                               => 'iu',

            );
            $title = preg_replace( array_keys($expressions), array_values($expressions), $title );

            //$title = strtr($title,  $cyr_table);
            $title = preg_replace("/[^A-Za-z0-9'_\-\.]/", '-', $title);
            $title = preg_replace('/\-+/', '-', $title);
            $title = preg_replace('/^-+/', '', $title);
            $title = preg_replace('/-+$/', '', $title);
            return $title;
        }

        private function remove_stop_words($title){
            $stopwords = array(
            'αλλά',
            'αν',
            'αντι',
            'από',
            'αυτα',
            'αυτές',
            'αυτη',
            'αυτο',
            'αυτοι',
            'αυτων',
            'εγώ',
            'εσύ',
            'αυτό',
            'εμείς',
            'εσείς',
            'είμαι',
            'είναι',
            'ήταν',
            'έχω',
            'έχει',
            'ένα',
            'και',
            'αν',
            'η',
            'επειδή',
            'μέχρι',
            'του',
            'στο',
            'για',
            'με',
            'δεν',
            'κατά',
            'πριν',
            'μετά',
            'εδώ',
            'εκεί',
            'πότε',
            'είμαι',
            'είμαστε',
            'εισαι',
            'γιατί',
            'όλα',
            'κάθε',
            'λίγοι',
            'περισσότερο',
            'όχι',
            'ίδιο',
            'έτσι',
            'εκεινων',
            'ενώ',
                );
            $titlearr = explode("-",$title);
            $nostops = array_diff($titlearr, $stopwords);
            if( count($nostops)<1) return $title;
            $title = implode("-",$nostops);
            return $title;
        }

        private function limit_words($title, $num){
            $titlearr = explode("-",$title);
            $titlearr = array_slice($titlearr, 0, $num);
            $title = implode("-", $titlearr );
            return $title;
        }

        public function cyrtrans_ajax_old() {
            global $wpdb;

            $posts = $wpdb->get_results("SELECT ID, post_name, post_title FROM {$wpdb->posts} WHERE post_name REGEXP('[^A-Za-z0-9\-]+') AND post_status IN ('publish', 'future', 'private')");
            foreach ( (array) $posts as $post ) {
                $sanitized_name = sanitize_title($post->post_title);
                if ( $post->post_name != $sanitized_name ) {
                   // add_post_meta($post->ID, '_wp_old_slug', $post->post_name);
                   // $wpdb->update($wpdb->posts, array( 'post_name' => $sanitized_name ), array( 'ID' => $post->ID ));
                   // clean_post_cache($post->ID );
                    wp_update_post( array(
                        'ID' => $post->ID,
                        'post_name' => $sanitized_name
                    ));

                }
            }
            $terms = $wpdb->get_results("SELECT term_id, slug FROM {$wpdb->terms} WHERE slug REGEXP('[^A-Za-z0-9\-]+') ");
            foreach ( (array) $terms as $term ) {
                $sanitized_slug = sanitize_title(urldecode($term->slug));
                if ( $term->slug != $sanitized_slug ) {
                    $wpdb->update($wpdb->terms, array( 'slug' => $sanitized_slug ), array( 'term_id' => $term->term_id ));
                }
            }
            $countPosts = count($posts);
            $countTerms = count($terms);
            $response= array(
                    'terms'=> $countTerms,
                    'posts'=> $countPosts,
                    'updated'=>true
                 );
            if($countPosts >0){
                update_option('cyrtrans_old_updated', $response);
            }

            // Whatever the outcome, send the Response back
            wp_send_json( $response);

            // Always exit when doing Ajax
            exit();
        }




    }

    new Trans_Plugin();

endif;
/********************************************************************
 *
 * Clear options when deactivated
 *
 ********************************************************************/
register_deactivation_hook(__FILE__, 'cyrTrans_deactivate_plugin');
function cyrTrans_deactivate_plugin(){
    delete_option('cyrtrans_old_updated');
}