<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */
class Cyrtrans_Plugin_Admin {
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
    protected $options_updated;


    /**
     * Plugin path
     *
     * @var string
     */
    protected $plugin_path;

    /**
     * Link to settings page
     *
     * @var string
     */
    protected $settings_link;

    /**
     * Initialize the class and set its properties.
     *
     * @since    0.9.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     * @param      string    $api_url    The api url
     * @param      string    $plugin_path    Plugin path
     */
    public function __construct( $plugin_name, $version, $plugin_path ) {
        $this->plugin_name      = $plugin_name;
        $this->version          = $version;
        $this->plugin_path      = $plugin_path;
        $this->settings_link    = admin_url( 'options-general.php?page=cyrtrans' );

        $this->options = get_option($this->option_name);
        $this->options_updated = get_option('cyrtrans_old_updated');

        /**
         * Admin menu and settings
         */
        add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_cyrtrans_settings' ) );

        /**
         * Add css and js files
         */
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


        // plugin settings link
        add_filter( "plugin_action_links_$this->plugin_path", array( $this, 'plugin_add_settings_link' ) );

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    0.9.5
     */
    public function enqueue_styles($hook) {
        if($hook != 'settings_page_cyrtrans') {
            return;
        }
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/cyrtrans-admin.css', array(), $this->version, 'all');
    }
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    0.9.5
     */
    public function enqueue_scripts($hook) {
        if($hook != 'settings_page_cyrtrans') {
            return;
        }
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/cyrtrans-admin.js', array('jquery'), $this->version, false);
        wp_localize_script( $this->plugin_name, 'posttransliterate', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));

    }


    /**
     * Add settings link in plugins list
     *
     * @param $links
     * @return mixed
     */
    public function plugin_add_settings_link( $links ) {
        $settings_link = '<a href="' . $this->settings_link . '">' . __( 'Settings' ) . '</a>';
        array_unshift( $links, $settings_link );
        return $links;
    }


    /**
     * Add plugin settings menu link
     */
    public function create_admin_menu() {

        add_submenu_page( 'options-general.php', 'Greeklish Permalink','Greeklish Permalink', 'manage_options', 'cyrtrans', array( $this, 'admin_page_display' ));

    }


    /**
     * Register settings
     */
    public function register_cyrtrans_settings() {
        register_setting( 'cyrtrans_settings', $this->option_name, array( $this, 'sanitize_sytrans_options' ) );

    }

    public function sanitize_cyrtrans_options( $options ) {
        return $options;
    }


    /**
     * Display admin plugin page
     */
    public function admin_page_display() {


?>

<div class="wrap wptrans cyrtrans js-cyrtrans">
    <h1>Greek to Latin Slug Transliteration Pro</h1>  
    <div class="wptrans-cols">
        <form method="post" action="options.php" class="js-cyrtrans-form">

            <?php settings_fields( 'cyrtrans_settings' ); ?>

            <h2 class="wptrans-tab-wrapper js-wptrans-tab-wrapper">
                <a class="wptrans-tab wptrans-tab-active" id="tab-cyrtrans_general" href="#cyrtrans_general">Πληροφορίες</a>
                <a class="wptrans-tab" id="tab-cyrtrans_settings" href="#cyrtrans_settings">
                    Ρυθμίσεις
                </a>
            </h2>

            <div id="cyrtrans_general" class="wptrans-tab-in js-wptrans-tab-item active">
                <div class="option-field-header">Μετατροπή Ελληνικών γραμμάτων σε Λατινικά σε Wordpress blogs</div>
                <p>
                    <strong>GRTrans Pro</strong> Η δωρεάν έκδοση μετατρέπει το Ελληνικό αλφάβητο σε Λατινικό. Πχ. ο τίτλος ενός άρθρου στα Ελληνικά "https://blog.gr/κατασκευή και προώθηση ιστοσελίδων" θα γίνει " https://blog.gr/kataskevi-proothisi-istoselidon".
                    Στην έκδοση Pro προσθέσαμε της εξής λειτουργίες που θα βοηθήσουν το σαιτ σας να εμφανίζετε καλύτερα στις μηχανές αναζήτησης και στα κοινωνικά δίκτυα.
                    <ol>
                        <li>Μετατροπή τον παλιών άρθρων (τα άρθρα που είχατε δημιουργήσει πριν από την εγκατάσταση του plugin).</li>
                        <li>Προσθήκη αυτόματου canonical και 301 ανακατεύθυνση παλαιών άρθρων σε νέες οι οποίες έχουν μετατραπεί.</li>
                        <li>Αφαίρεση των stop λέξεων από τη διεύθυνση url (Θεωρείτε ότι δεν συνεισφέρουν στης μηχανές αναζήτησης). </li>
                        <li>Περιορισμός του αριθμό των λέξεων στη διεύθυνση url.</li>

                    </ol>
                  

                </p>
                <p>Για περισσότερες πληροφορίες, ερωτήσεις και επικοινωνία επισκεφθείτε την ιστοσελίδα μας: <a href="https://www.problogger.gr">problogger.gr</a>.</p>

            </div>

            <div id="cyrtrans_settings" class="wptrans-tab-in js-wptrans-tab-item">
                <div class="option-field-header">Μετατροπή παλαιών συνδέσμων</div>
                <div class="option-field">
                    <label class="option-field-label" for="trans_old">
                        Κάνε αυτόματη μετατροπή των παλαιών συνδέσμων
                    </label>
                    <div class="option-field-body">
                        <?php if($this->options_updated['updated']): ?>
                        <p class="description">
                            Επιτυχή μετατροπή τον παλιών άρθρων!                             
                            <input type="button" name="trans_old" id="trans_old" class="button button-primary" value="Κάνε σάρωση ξανά" />
                            <?php if($this->options_updated['posts']>0): ?>
                            <br/>
                            <br/>
                            Μετατράπηκαν <strong><?php echo $this->options_updated['posts']; ?></strong> άρθρα και 
                            <strong>
                                <?php echo $this->options_updated['terms']; ?>
                            </strong> όροι.
                            <?php else: ?>
                            <br/>Κατά την σάρωση δεν βρέθηκαν άρθρα στα Ελληνικά.
                            <?php endif; ?>
                        </p>
                        <?php else: ?>                      
                        <input type="button" name="trans_old" id="trans_old" class="button button-primary" value="Transliterate" />
                        <p class="description">
                            Το GRTrans Pro κατά την ενεργοποίηση του αυτόματα μετατρέπει σε Λατινικούς χαρακτήρες όλες τις νέες αναρτήσεις και σελίδες. Για να προχωρήσετε στην μετατροπή τον παλιών κάντε κλικ στο 
                            <strong>Transliterate</strong> πιο πάνω. Οι αυτόματες ανακατευθύνσεις 301 και οι κανονικοί σύνδεσμοι στης παλιές διευθύνσεις θα προστεθούν αυτόματα για να διατηρήσουν το βάρος SEO που κουβαλούν χωρίς να γίνουν λάθη. <?php // Това ще го добавм по-нататък ако има покупки -> Пренасочванията ще можете да видите в нов таб. ?>
                        </p>
                        <p class="description">
                            <strong>GRTrans Pro:</strong> Σας παρέχει την δυνατότητα να μετατρέψετε της παλιές συνδέσεις από Ελληνικά σε Λατινικά.
                        </p>
                        <?php endif; ?>
                        
                    </div>
                </div><!--.option-field-->

                <div class="option-field-header">Φίλτρα</div>
                <p class="descrh">
                    Οι σύνδεσμοι (links) στα άρθρα και της σελίδες σας δεν χρειάζεται να είναι ακριβές αντίγραφο του τίτλου. Οι σύντομοι σύνδεσμοι προτιμούνται από SEO  άποψη και και είναι πιο πρακτικοί όταν μοιράζονται (share) στα κοινωνικά δίκτυα. Είναι σημαντικό να περιέχουν μόνο τις βασικές λέξεις-κλειδιά για τον προσδιορισμό του άρθρου. Επιλέξτε να αφαιρέσετε τα stop words και να περιορίσετε τον αριθμό τον λέξεων στα άρθρα σας.
                </p>
                <div class="option-field">
                    <label class="option-field-label" for="remove-stopwords">
                        Απενεργοποιήστε τα stοp words
                    </label>
                    <div class="option-field-body">
                        <?php $this->display_checkbox('remove-stopwords') ?>
                        <p class="description">
                           Τα <a href="https://en.wikipedia.org/wiki/Stop_words">stop words</a> είναι συνηθισμένες λέξεις που φιλτράρονται από τους αλγόριθμους επεξεργασίας κειμένου των μηχανών αναζήτησης (Google, Bing, κλπ.). Η ένταξή τους στους συνδέσμους δεν έχει αξία και απλά τους κάνει μακρύτερους χωρίς λόγο. Συνιστάται η μη χρήση αυτών των λέξεων.
                        </p>
                        <p class="description">
                            <strong>Σημείωση:</strong> Αφού αφαιρέσετε τα stop words, ο σύνδεσμος πρέπει να περιέχει τουλάχιστον μία λέξη. Διαφορετικά θα διατηρηθούν.                           
                        </p>
                    </div>
                </div><!--.option-field-->

                <div class="option-field">
                    <label class="option-field-label" for="limit-words">
                        Περιορισμός τον αριθμό των λέξεων στον σύνδεσμο
                    </label>
                    <div class="option-field-body">
                        <div>
                            <?php $this->display_checkbox('limit-words') ?>
                        </div>
                        <?php $this->display_input_number('url_words_limit', 1, 3, 12) ?>
                        <p class="description">
                            Πολλές μελέτες δείχνουν ότι οι μικρότεροι σύνδεσμοι έχουν υψηλότερο βαθμό κλικ δίνοντας έτσι ένα θετικότερο μήνυμα από SEO άποψη προς της μηχανές αναζήτησης. Οι περισσότερες συστάσεις είναι οι σύνδεσμοι να μην υπερβαίνουν τους 100 χαρακτήρες. Η Google λέει ότι ο βέλτιστος σύνδεσμος πρέπει να περιέχει μέχρι 5 λέξεις. Η δική μας σύσταση είναι να περιορίσετε τους συνδέσμους στης 8-10 λέξεις το πολύ.
                        </p>
                        <p class="description">
                            <strong>GRTrans Pro:</strong> Σας επιτρέπει να περιορίσετε τον αριθμό των λέξεων στο σύνδεσμο.
                        </p>
                    </div>
                </div><!--.option-field-->



            </div>

            <?php submit_button(); ?>

        </form>
        <h2>
            Αν σας αρέσει το plugin μας κέρασε μας μια μπίρα :-)
        </h2>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="RLFEB4EZ8KGPN" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </form>

    </div><!--.wptrans-col-left-->
</div>

<?php
    }


    /**
     * Display option checkbox
     *
     * @param string $name
     */
    public function display_checkbox( $name ) {
        $checked = '';
        if (isset($this->options[$name]) && $this->options[$name] == 'on') $checked = ' checked';
        $string = '<span class="pseudo-checkbox'. $checked .'"></span> <input class="pseudo-checkbox-hidden" name="' . $this->option_name . '[' . $name . ']" type="checkbox" id="' . $name . '" value="on"'. $checked .'>';
        echo $string;
    }

    /**
     * Display input text field
     *
     * @param string $name
     */
    public function display_input_text( $name ) {
        $value = '';
        if (isset($this->options[$name]) && ! empty($this->options[$name])) $value = $this->options[$name];
        $string = '<input name="' . $this->option_name . '[' . $name . ']" type="text" id="' . $name . '" value="'. $value .'"" class="regular-text">';
        echo $string;
    }

    /**
     * Display textarea field
     *
     * @param string $name
     */
    public function display_textarea_robots( $name ) {
        $value = '';
        if (isset($this->options[$name]) && ! empty($this->options[$name])) $value = $this->options[$name];
        if ( empty( $value ) ) {
            $plugin = new cyrtrans_Plugin();
            $value = $plugin->right_robots_txt( '' );
            //$value = cyrtrans_Plugin::right_robots_txt( '' );
        }
        $string = '<textarea name="' . $this->option_name . '[' . $name . ']" id="' . $name . '" class="regular-text">'. $value .'</textarea>';
        echo $string;
    }

	public function display_textarea_last_modified( $name ) {
        $value = '';
        if (isset($this->options[$name]) && ! empty($this->options[$name])) $value = $this->options[$name];
        $string = '<textarea name="' . $this->option_name . '[' . $name . ']" id="' . $name . '" class="regular-text" rows="4">'. $value .'</textarea>';
        echo $string;
    }

    /**
     * Display input number field
     *
     * @param $name
     * @param $step
     * @param $min
     * @param $max
     */
    public function display_input_number( $name , $step = '', $min = '', $max = '' ) {
        $value = '';
        if (isset($this->options[$name]) && ! empty($this->options[$name])) {
            $value = $this->options[$name];
        }
        else{
            $value = 8;
        }
        $string  = '<input name="' . $this->option_name . '[' . $name . ']" type="number" ';
        if (!empty($step)) $string .= 'step="'. $step .'" ';
        if (!empty($min) || $min === 0)  $string .= 'min="'. $min .'"  ';
        if (!empty($max))  $string .= 'max="'. $max .'" ';
        $string .= 'id="' . $name . '" value="'. $value .'"" class="small-text">';
        echo $string;
    }
}

