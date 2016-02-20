<?php
class WPEditor {
  
  public function install() {
    
    global $wpdb;
    $prefix = $this->getTablePrefix();
    $sqlFile = WPEDITOR_PATH . 'sql/database.sql';
    $sql = str_replace('[prefix]', $prefix, file_get_contents($sqlFile));
    $queries = explode(";\n", $sql);
    $wpdb->hide_errors();
    foreach($queries as $sql) {
      if(strlen($sql) > 5) {
        $wpdb->query($sql);
      }
    }
    
    // Set the version number for this version of WPEditor
    require_once(WPEDITOR_PATH . 'classes/WPEditorSetting.php');
    WPEditorSetting::setValue('version', WPEDITOR_VERSION_NUMBER);

    if(!WPEditorSetting::getValue('upgrade')) {
      $this->firstInstall();
    }

  }
  
  public function firstInstall() {
    
    // Set the database to upgrade instead of first time install
    WPEditorSetting::setValue('upgrade', 1);
    
    // Check if the post editor has been enabled and enable if not
    if(!WPEditorSetting::getValue('enable_post_editor')) {
      WPEditorSetting::setValue('enable_post_editor', 1);
    }
    
    // Check if the plugin and theme editors have been hidden before and hide them if not
    if(!WPEditorSetting::getValue('hide_default_plugin_editor')) {
      WPEditorSetting::setValue('hide_default_plugin_editor', 1);
    }
    if(!WPEditorSetting::getValue('hide_default_theme_editor')) {
      WPEditorSetting::setValue('hide_default_theme_editor', 1);
    }
    
    // Check if the edit link for plugins has been hidden before and hide if not
    if(!WPEditorSetting::getValue('replace_plugin_edit_links')) {
      WPEditorSetting::setValue('replace_plugin_edit_links', 1);
    }
    
    // Check if the plugin line numbers have been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_plugin_line_numbers')) {
      WPEditorSetting::setValue('enable_plugin_line_numbers', 1);
    }
    
    // Check if the theme line numbers have been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_theme_line_numbers')) {
      WPEditorSetting::setValue('enable_theme_line_numbers', 1);
    }
    
    // Check if the post line numbers have been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_post_line_numbers')) {
      WPEditorSetting::setValue('enable_post_line_numbers', 1);
    }
    
    // Check if plugin line wrapping has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_plugin_line_wrapping')) {
      WPEditorSetting::setValue('enable_plugin_line_wrapping', 1);
    }
    
    // Check if theme line wrapping has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_theme_line_wrapping')) {
      WPEditorSetting::setValue('enable_theme_line_wrapping', 1);
    }
    
    // Check if post line wrapping has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_post_line_wrapping')) {
      WPEditorSetting::setValue('enable_post_line_wrapping', 1);
    }
    
    // Check if plugin active line highlighting has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_plugin_active_line')) {
      WPEditorSetting::setValue('enable_plugin_active_line', 1);
    }
    
    // Check if theme active line highlighting has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_theme_active_line')) {
      WPEditorSetting::setValue('enable_theme_active_line', 1);
    }
    
    // Check if post active line highlighting has been disabled and enable if not
    if(!WPEditorSetting::getValue('enable_post_active_line')) {
      WPEditorSetting::setValue('enable_post_active_line', 1);
    }
    
    // Check if the default allowed extensions for the plugin editor have been set and set if not
    if(!WPEditorSetting::getValue('plugin_editor_allowed_extensions')) {
      WPEditorSetting::setValue('plugin_editor_allowed_extensions', 'php~js~css~txt~htm~html~jpg~jpeg~png~gif~sql~po~less~xml');
    }
    
    // Check if the default allowed extensions for the theme editor have been set and set if not
    if(!WPEditorSetting::getValue('theme_editor_allowed_extensions')) {
      WPEditorSetting::setValue('theme_editor_allowed_extensions', 'php~js~css~txt~htm~html~jpg~jpeg~png~gif~sql~po~less~xml');
    }
    
    // Check if the upload plugin file option has been set and set if not
    if(!WPEditorSetting::getValue('plugin_file_upload')) {
      WPEditorSetting::setValue('plugin_file_upload', 1);
    }
    
    // Check if the upload theme file option has been set and set if not
    if(!WPEditorSetting::getValue('theme_file_upload')) {
      WPEditorSetting::setValue('theme_file_upload', 1);
    }
    
    // Check if the plugin indent unit option has been set and set if not
    if(!WPEditorSetting::getValue('plugin_indent_unit')) {
      WPEditorSetting::setValue('plugin_indent_unit', 2);
    }
    
    // Check if the theme indent unit option has been set and set if not
    if(!WPEditorSetting::getValue('theme_indent_unit')) {
      WPEditorSetting::setValue('theme_indent_unit', 2);
    }
    
    // Check if the post indent unit option has been set and set if not
    if(!WPEditorSetting::getValue('post_indent_unit')) {
      WPEditorSetting::setValue('post_indent_unit', 2);
    }
    
  }
  
  public function init() {
    // Load all additional required classes
    $this->loadCoreModels();
    
    // Verify that upgrade has been run
    if(IS_ADMIN) {
      if(version_compare(WPEDITOR_VERSION_NUMBER, WPEditorSetting::getValue('version'))) {
        $this->install();
      }
    }
    
    // Define debugging and testing info
    $wpeditor_logging = WPEditorSetting::getValue('wpeditor_logging') ? true : false;
    define('WPEDITOR_DEBUG', $wpeditor_logging);
    
    $default_wpeditor_roles = array(
      'settings' => 'manage_options',
      'theme-editor' => 'edit_themes',
      'plugin-editor' => 'edit_plugins'
    );
    // Set default admin page roles if there isn't any
    $wpeditor_roles = WPEditorSetting::getValue('admin_page_roles');
    if(empty($wpeditor_roles)){
      WPEditorSetting::setValue('admin_page_roles', serialize($default_wpeditor_roles));
    }
    // Ensure that all admin page roles have been set.
    else {
      $update_roles = false;
      $wpeditor_roles = unserialize($wpeditor_roles);
      foreach($default_wpeditor_roles as $key => $value) {
        if(!array_key_exists($key, $wpeditor_roles)) {
          $wpeditor_roles[$key] = $value;
          $update_roles = true;
        }
      }
      if($update_roles) {
        WPEditorSetting::setValue('admin_page_roles', serialize($wpeditor_roles));
      }
      $wpeditor_roles = serialize($wpeditor_roles);
    }
    
    if(IS_ADMIN) {
      // Load default stylesheet
      add_action('admin_init', array($this, 'registerDefaultStylesheet'));
      // Load default script
      add_action('admin_init', array($this, 'registerDefaultScript'));
      
      // Remove default editor submenus
      add_action('admin_menu', array('WPEditorAdmin', 'removeDefaultEditorMenus'));
      // Add WP Editor Settings Page
      add_action('admin_menu', array('WPEditorAdmin', 'buildAdminMenu'));

      // Add Plugin Editor Page
      add_action('admin_menu', array('WPEditorAdmin', 'addPluginsPage'));
      // Add Theme Editor Page
      add_action('admin_menu', array('WPEditorAdmin', 'addThemesPage'));
      
      // Ajax request to save settings
      add_action('wp_ajax_save_wpeditor_settings', array('WPEditorAjax', 'saveSettings'));
      
      // Ajax request to save files
      add_action('wp_ajax_save_files', array('WPEditorAjax', 'saveFile'));
      
      // Ajax request to upload files
      add_action('wp_ajax_upload_files', array('WPEditorAjax', 'uploadFile'));
      
      // Ajax request to retrieve files and folders
      add_action('wp_ajax_ajax_folders', array('WPEditorAjax', 'ajaxFolders'));
      
      // Replace default plugin edit links
      add_filter('plugin_action_links', array($this, 'replacePluginEditLinks'),9,1);
      
      add_filter('the_editor', array('WPEditorPosts', 'addPostsJquery'));
      
      if(!current_user_can('editor') && !current_user_can('administrator')) {
        global $pagenow;
        if($pagenow == 'index.php') {
          add_filter('admin_footer', array('WPEditorPosts', 'addPostsJquery'));
        }
      }
      
    }
  }
  
  public function loadCoreModels() {
    require_once(WPEDITOR_PATH . 'classes/WPEditorAdmin.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorAjax.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorBrowser.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorException.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorLog.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorPlugins.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorPosts.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorSetting.php');
    require_once(WPEDITOR_PATH . 'classes/WPEditorThemes.php');
  }
  
  public function registerDefaultStylesheet() {
    wp_register_style('wpeditor', WPEDITOR_URL . '/wpeditor.css', false, WPEDITOR_VERSION_NUMBER);
    wp_register_style('fancybox', WPEDITOR_URL . '/extensions/fancybox/jquery.fancybox-1.3.4.css', false, WPEDITOR_VERSION_NUMBER);
    wp_register_style('codemirror', WPEDITOR_URL . '/extensions/codemirror/codemirror.css', false, WPEDITOR_VERSION_NUMBER);
    wp_register_style('codemirror_dialog', WPEDITOR_URL . '/extensions/codemirror/dialog.css', false, WPEDITOR_VERSION_NUMBER);
    wp_register_style('codemirror_themes', WPEDITOR_URL . '/extensions/codemirror/themes/themes.css', false, WPEDITOR_VERSION_NUMBER);
  }
  
  public function registerDefaultScript() {
    wp_register_script('wpeditor', WPEDITOR_URL . '/js/wpeditor.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('wp-editor-posts-jquery', WPEDITOR_URL . '/js/posts-jquery.js', false, WPEDITOR_VERSION_NUMBER, true);
    wp_register_script('fancybox', WPEDITOR_URL . '/extensions/fancybox/js/jquery.fancybox-1.3.4.pack.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror', WPEDITOR_URL . '/extensions/codemirror/js/codemirror.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_php', WPEDITOR_URL . '/extensions/codemirror/js/php.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_javascript', WPEDITOR_URL . '/extensions/codemirror/js/javascript.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_css', WPEDITOR_URL . '/extensions/codemirror/js/css.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_xml', WPEDITOR_URL . '/extensions/codemirror/js/xml.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_clike', WPEDITOR_URL . '/extensions/codemirror/js/clike.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_dialog', WPEDITOR_URL . '/extensions/codemirror/js/dialog.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_search', WPEDITOR_URL . '/extensions/codemirror/js/search.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_searchcursor', WPEDITOR_URL . '/extensions/codemirror/js/searchcursor.js', false, WPEDITOR_VERSION_NUMBER);
    wp_register_script('codemirror_mustache', WPEDITOR_URL . '/extensions/codemirror/js/mustache.js', false, WPEDITOR_VERSION_NUMBER);
    //wp_register_script('codemirror_foldcode', WPEDITOR_URL . '/extensions/codemirror/js/foldcode.js');
  }
  
  public static function getView($filename, $data=null) {
    $filename = WPEDITOR_PATH . "/$filename";
    ob_start();
    include $filename;
    $contents = ob_get_contents();
    ob_end_clean();
    return $contents;
  }
  
  public static function getTableName($name){
    return WPEditor::getTablePrefix() . $name;
  }
  
  public static function getTablePrefix(){
    global $wpdb;
    return $wpdb->prefix . 'wpeditor_';
  }
  
  public static function replacePluginEditLinks($links) {
    $data = '';
    if(isset($_REQUEST['plugin_status']) && in_array($_REQUEST['plugin_status'], array('mustuse', 'dropins'))) {
      $data = $links;
    }
    elseif(WPEditorSetting::getValue('replace_plugin_edit_links') == 1) {
      foreach($links as $key => $value) {
        if($key === 'edit') {
          $value = str_replace('plugin-editor.php?', 'plugins.php?page=wpeditor_plugin&', $value);
        }
        $data[$key] = $value;
      }
    }
    else {
      $data = $links;
    }
    return $data;
  }
  
}