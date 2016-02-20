<?php
class WPEditorAjax {
  
  public static function saveSettings() {
    $error = '';
    
    foreach($_REQUEST as $key => $value) {
      if($key[0] != '_' && $key != 'action' && $key != 'submit') {
        if(is_array($value)) {
          $value = implode('~', $value);
        }
        if($key == 'wpeditor_logging' && $value == '1') {
          try {
            WPEditorLog::createLogFile();
          }
          catch(WPEditorException $e) {
            $error = $e->getMessage();
            WPEditorLog::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Caught WPEditor exception: " . $e->getMessage());
          }
        }
        WPEditorSetting::setValue($key, trim(stripslashes($value)));
      }
    }
    
    if(isset($_REQUEST['_tab'])) {
      WPEditorSetting::setValue('settings_tab', $_REQUEST['_tab']);
    }
    
    if($error) {
      $result[0] = 'WPEditorAjaxError';
      $result[1] = '<h3>' . __('Warning','wpeditor') . "</h3><p>$error</p>";
    }
    else {
      $result[0] = 'WPEditorAjaxSuccess';
      $result[1] = '<h3>' . __('Success', 'wp-editor') . '</h3><p>' . $_REQUEST['_success'] . '</p>'; 
    }
    
    $out = json_encode($result);
    echo $out;
    die();
  }
  
  public static function uploadFile() {
    $upload = '';
    if(isset($_POST['current_theme_root'])){
      $upload = WPEditorBrowser::uploadThemeFiles();
    }
    elseif(isset($_POST['current_plugin_root'])) {
      $upload = WPEditorBrowser::uploadPluginFiles();
    }
    echo json_encode($upload);
    die();
  }
  
  public static function saveFile() {
    $error = '';
    try {
      if(isset($_POST['new_content']) && isset($_POST['real_file'])) {
        $real_file = $_POST['real_file'];
        if(file_exists($real_file)) {
          if(is_writable($real_file)) {
            $new_content = stripslashes($_POST['new_content']);
            if(file_get_contents($real_file) === $new_content) {
              WPEditorLog::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Contents are the same");
            }
            else {
              $f = fopen($real_file, 'w+');
              fwrite($f, $new_content);
              fclose($f);
              WPEditorLog::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] just wrote to $real_file");
            }
          }
          else {
            $error = __('This file is not writable', 'wp-editor');
          }
        }
        else {
          $error = __('This file does not exist', 'wp-editor');
        }
      }
      else {
        $error = __('Invalid Content', 'wp-editor');
      }
    }
    catch(WPEditorException $e) {
      $error = $e->getMessage();
      WPEditorLog::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Caught WPEditor exception: " . $e->getMessage());
    }
    
    if($error) {
      $result[0] = 'WPEditorAjaxError';
      $result[1] = '<h3>' . __('Warning','wpeditor') . "</h3><p>$error</p>";
    }
    else {
      $result[0] = 'WPEditorAjaxSuccess';
      $result[1] = '<h3>' . __('Success', 'wp-editor') . '</h3><p>' . $_REQUEST['_success'] . '</p>'; 
    }
    
    if(isset($_POST['extension'])) {
      $result[2] = $_POST['extension'];
    }
    
    $out = json_encode($result);
    echo $out;
    die();
  }  
  
  public static function ajaxFolders() {
    
    $dir = urldecode($_REQUEST['dir']);
    
    if(isset($_REQUEST['contents'])) {
      $contents = $_REQUEST['contents'];
    }
    else {
      $contents = 0;
    }
    $type = null;
    if(isset($_REQUEST['type'])) {
      $type = $_REQUEST['type'];
    }
    $out = json_encode(WPEditorBrowser::getFilesAndFolders($dir, $contents, $type));
    echo $out;
    die();
  }
  
}