<?php
class WPEditorSetting {
  
  public static function setValue($key, $value) {
    global $wpdb;
    $settingsTable = WPEditor::getTableName('settings');
    
    if(!empty($key)) {
      $dbKey = $wpdb->get_var("SELECT `key` from $settingsTable where `key`='$key'");
      if($dbKey) {
        if(!empty($value) || $value !== 0) {
          $wpdb->update($settingsTable, 
            array('key'=>$key, 'value'=>$value),
            array('key'=>$key),
            array('%s', '%s'),
            array('%s')
          );
        }
        else {
          $wpdb->query("DELETE from $settingsTable where `key`='$key'");
        }
      }
      else {
        if(!empty($value) || $value !== 0) {
          $wpdb->insert($settingsTable, 
            array('key'=>$key, 'value'=>$value),
            array('%s', '%s')
          );
        }
      }
    }
    
  }
  
  public static function getValue($key, $entities=false) {
    $value = false;
    global $wpdb;
    $settingsTable = WPEditor::getTableName('settings');
    $value = $wpdb->get_var("SELECT `value` from $settingsTable where `key`='$key'");
    
    if(!empty($value) && $entities) {
      $value = htmlentities($value);
    }
    
    return $value;
  }
  
}