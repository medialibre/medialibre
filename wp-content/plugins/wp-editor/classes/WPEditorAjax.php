<?php
class WPEditorAjax {

	public static function save_settings() {

		if ( ! check_ajax_referer( 'wp_editor_ajax_nonce_settings_main', 'wp_editor_ajax_nonce_settings_main', false ) && ! check_ajax_referer( 'wp_editor_ajax_nonce_settings_themes', 'wp_editor_ajax_nonce_settings_themes', false ) && ! check_ajax_referer( 'wp_editor_ajax_nonce_settings_plugins', 'wp_editor_ajax_nonce_settings_plugins', false ) && ! check_ajax_referer( 'wp_editor_ajax_nonce_settings_posts', 'wp_editor_ajax_nonce_settings_posts', false ) ) {
			die;
		}

		$error = '';

		foreach ( $_REQUEST as $key => $value ) {
			if ( $key[0] != '_' && $key != 'action' && $key != 'submit' ) {
				if ( is_array( $value ) ) {
					$value = implode( '~', $value );
				}
				if ( $key == 'wpeditor_logging' && $value == '1' ) {
					try {
						WPEditorLog::create_log_file();
					}
					catch( WPEditorException $e ) {
						$error = $e->getMessage();
						WPEditorLog::log( '[' . basename( __FILE__ ) . ' - line ' . __LINE__ . "] Caught WPEditor exception: " . $e->getMessage() );
					}
				}
				WPEditorSetting::set_value( $key, trim( stripslashes( esc_html( $value ) ) ) );
			}
		}

		if (isset( $_REQUEST['_tab'] ) ) {
			WPEditorSetting::set_value( 'settings_tab', sanitize_title( wp_unslash( $_REQUEST['_tab'] ) ) );
		}

		if ( $error ) {
			$result[0] = 'WPEditorAjaxError';
			$result[1] = '<h3>' . __( 'Warning','wpeditor' ) . "</h3><p>$error</p>";
		}
		else {
			$result[0] = 'WPEditorAjaxSuccess';
			$result[1] = '<h3>' . __( 'Success', 'wp-editor' ) . '</h3>';
			if ( isset( $_REQUEST['_success'] ) ) {
				$result[1] .= '<p>' . sanitize_text_field( wp_unslash( $_REQUEST['_success'] ) ) . '</p>';
			}
			//
		}

		echo wp_json_encode( $result );
		die();

	}

	public static function upload_file() {

		$upload = '';
		if ( isset( $_POST['current_theme_root'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_upload_file_theme', 'wp_editor_ajax_nonce_upload_file_theme' );

			if ( current_user_can( 'edit_themes' ) ) {
				$upload = WPEditorBrowser::upload_theme_files();
			}

		}
		elseif ( isset( $_POST['current_plugin_root'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_upload_file_plugin', 'wp_editor_ajax_nonce_upload_file_plugin' );

			if ( current_user_can( 'edit_plugins' ) ) {
				$upload = WPEditorBrowser::upload_plugin_files();
			}

		}

		echo wp_json_encode( $upload );
		die();

	}

	public static function save_file() {

		if ( isset( $_POST['wp_editor_ajax_nonce_save_files_themes'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_save_files_themes', 'wp_editor_ajax_nonce_save_files_themes' );

			if ( ! current_user_can( 'edit_themes' ) ) {
				die;
			}

		}
		elseif ( isset( $_POST['wp_editor_ajax_nonce_save_files_plugins'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_save_files_plugins', 'wp_editor_ajax_nonce_save_files_plugins' );

			if ( ! current_user_can( 'edit_plugins' ) ) {
				die;
			}

		}
		else {
			die;
		}

		$error = '';

		try {

			if ( isset( $_POST['new_content'] ) && isset( $_POST['real_file'] ) ) {

				$real_file = wp_normalize_path( sanitize_text_field( wp_unslash( $_POST['real_file'] ) ) );

				//detect and handle unc paths
				if ( substr( $real_file, 0, 4) === '\\\\\\\\' ) {
					$real_file = str_replace( '\\\\', '\\', $real_file );
				}

				if ( file_exists( $real_file ) ) {

					if ( is_writable( $real_file ) ) {

						// phpcs:ignore HM.Security.ValidatedSanitizedInput.InputNotSanitized, (per WP Core editor)
						$new_content = wp_unslash( $_POST['new_content'] );
						if ( file_get_contents( $real_file ) === $new_content ) {
							WPEditorLog::log( '[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Contents are the same" );
						}
						else {
							$f = fopen( $real_file, 'w+' );
							fwrite( $f, $new_content );
							fclose( $f );
							WPEditorLog::log( '[' . basename(__FILE__) . ' - line ' . __LINE__ . "] just wrote to $real_file" );
						}

					}
					else {
						$error = __( 'This file is not writable', 'wp-editor' );
					}

				}
				else {
					$error = __( 'This file does not exist', 'wp-editor' );
				}

			}
			else {
				$error = __( 'Invalid Content', 'wp-editor' );
			}

		}
		catch( WPEditorException $e ) {
			$error = $e->getMessage();
			WPEditorLog::log( '[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Caught WPEditor exception: " . $e->getMessage() );
		}

		if ( $error ) {
			$result[0] = 'WPEditorAjaxError';
			$result[1] = '<h3>' . __( 'Warning','wpeditor' ) . "</h3><p>' . esc_html( $error ) . '</p>";
		}
		else {
			$result[0] = 'WPEditorAjaxSuccess';
			$result[1] = '<h3>' . __( 'Success', 'wp-editor' ) . '</h3>';
			if ( isset(  $_REQUEST['_success'] ) ) {
				$result[1] .= '<p>' . sanitize_text_field( wp_unslash( $_REQUEST['_success'] ) ) . '</p>';
			}
		}

		if (isset( $_POST['extension'] ) ) {
			$result[2] = sanitize_file_name( wp_unslash( $_POST['extension'] ) );
		}

		echo wp_json_encode( $result );
		die();

	}

	public static function ajax_folders() {

		if ( isset( $_POST['wp_editor_ajax_nonce_ajax_folders_themes'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_ajax_folders_themes', 'wp_editor_ajax_nonce_ajax_folders_themes' );

			if ( ! current_user_can( 'edit_themes' ) ) {
				die;
			}

		}
		elseif ( isset( $_POST['wp_editor_ajax_nonce_ajax_folders_plugins'] ) ) {

			check_ajax_referer( 'wp_editor_ajax_nonce_ajax_folders_plugins', 'wp_editor_ajax_nonce_ajax_folders_plugins' );

			if ( ! current_user_can( 'edit_plugins' ) ) {
				die;
			}

		}
		else {
			die;
		}

		if ( isset( $_REQUEST['dir'] ) ) {
			// phpcs:ignore HM.Security.ValidatedSanitizedInput.InputNotSanitized, (data is incorrectly sent)
			$dir = wp_normalize_path( sanitize_text_field( urldecode( wp_unslash( $_REQUEST['dir'] ) ) ) );
		} else {
			$dir = 0;
		}

		if ( isset( $_REQUEST['contents'] ) ) {
			$contents = wp_unslash( $_REQUEST['contents'] );
		}
		else {
			$contents = 0;
		}

		$type = null;
		if ( isset( $_REQUEST['type'] ) ) {
			$type = sanitize_text_field( wp_unslash( $_REQUEST['type'] ) );
		}

		echo wp_json_encode( WPEditorBrowser::get_files_and_folders( $dir, $contents, $type ) );
		die();

	}

}
