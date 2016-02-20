<?php
class WPEditorAdmin {
  
  public static function buildAdminMenu() {
    $page_roles = WPEditorSetting::getValue('admin_page_roles');
    $page_roles = unserialize($page_roles);
    if(WPEditorSetting::getValue('hide_wpeditor_menu')) {
      $settings = add_submenu_page('options-general.php', __('WP Editor Settings', 'wp-editor'), __('WP Editor', 'wp-editor'), $page_roles['settings'], 'wpeditor_admin', array('WPEditorAdmin', 'addSettingsPage'));
    }
    else {
      $icon = WPEDITOR_URL . '/images/wpeditor_logo_16.png';
      $settings = add_menu_page(__('WP Editor Settings', 'wp-editor'), __('WP Editor', 'wp-editor'), $page_roles['settings'], 'wpeditor_admin', array('WPEditorAdmin', 'addSettingsPage'), $icon);
    }
    //add_submenu_page('wpeditor_admin', __('Sub Menu', 'wp-editor'), __('Orders', 'wp-editor'), $page_roles['orders'], 'wpeditor_admin', array('WPEditorAdmin', 'subMenuPage'));
    
    add_action('admin_print_styles-' . $settings, array('WPEditorAdmin', 'defaultStylesheetAndScript'));
  }
  
  public static function addPluginsPage() {
    global $wpeditor_plugin;
    
    $page_title = __('Plugin Editor', 'wp-editor');
    $menu_title = __('Plugin Editor', 'wp-editor');
    $capability = 'edit_plugins';
    $menu_slug = 'wpeditor_plugin';
    $wpeditor_plugin = add_plugins_page($page_title, $menu_title, $capability, $menu_slug, array('WPEditorPlugins', 'addPluginsPage'));
    add_action("load-$wpeditor_plugin", array('WPEditorPlugins', 'pluginsHelpTab'));
    if(isset($_GET['page']) && $_GET['page'] == 'wpeditor_plugin') {
      add_action('admin_print_styles', array('WPEditorAdmin', 'editorStylesheetAndScripts'));
    }
  }
  
  public static function addThemesPage() {
    global $wpeditor_themes;
    
    $page_title = __('Theme Editor', 'wp-editor');
    $menu_title = __('Theme Editor', 'wp-editor');
    $capability = 'edit_themes';
    $menu_slug = 'wpeditor_themes';
    $wpeditor_themes = add_theme_page($page_title, $menu_title, $capability, $menu_slug, array('WPEditorThemes', 'addThemesPage'));
    
    add_action("load-$wpeditor_themes", array('WPEditorThemes', 'themesHelpTab'));
    if(isset($_GET['page']) && $_GET['page'] == 'wpeditor_themes') {
      add_action('admin_print_styles', array('WPEditorAdmin', 'editorStylesheetAndScripts'));
    }
  }
  
  public static function addSettingsPage() {
    $view = WPEditor::getView('views/settings.php');
    echo $view;
  }
  
  public static function editorStylesheetAndScripts() {
    wp_enqueue_style('wpeditor');
    wp_enqueue_script('wpeditor');
    wp_enqueue_style('fancybox');
    wp_enqueue_script('fancybox');
    wp_enqueue_style('codemirror');
    wp_enqueue_style('codemirror_dialog');
    wp_enqueue_style('codemirror_themes');
    wp_enqueue_script('codemirror');
    wp_enqueue_script('codemirror_mustache');
    wp_enqueue_script('codemirror_php');
    wp_enqueue_script('codemirror_javascript');
    wp_enqueue_script('codemirror_css');
    wp_enqueue_script('codemirror_xml');
    wp_enqueue_script('codemirror_clike');
    wp_enqueue_script('codemirror_dialog');
    wp_enqueue_script('codemirror_search');
    wp_enqueue_script('codemirror_searchcursor');
  }
  
  public static function defaultStylesheetAndScript() {
    wp_enqueue_style('wpeditor');
    wp_enqueue_script('wpeditor');
  }
  
  public static function removeDefaultEditorMenus() {
    // Remove default plugin editor
    if(WPEditorSetting::getValue('hide_default_plugin_editor') == 1) {
      global $submenu;
      unset($submenu['plugins.php'][15]);
    }
    if(WPEditorSetting::getValue('hide_default_theme_editor') == 1) {
      // Remove default themes editor
      remove_action('admin_menu', '_add_themes_utility_last', 101);
    }
  }
  
}