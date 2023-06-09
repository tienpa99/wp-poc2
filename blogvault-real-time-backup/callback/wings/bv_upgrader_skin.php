<?php

if (!defined('ABSPATH')) exit;
if (!class_exists('BVUpgraderSkin')) :
class BVUpgraderSkin extends WP_Upgrader_Skin {
	public $status = array();
	public $action = '';
	public $plugin_info = array();
	public $theme_info = array();
	public $plugins_by_name = array();
	public $language_update = null;

	const UPGRADER_WING_VERSION = 1.1;

	function __construct($type, $plugins_by_name, $package = '') {
		$this->action = $type;
		$this->package = $package;
		$this->plugins_by_name = $plugins_by_name;
		parent::__construct(array());
	}

	function header() {}

	function footer() {}

	function get_keys() {
		$key = "bvgeneral";
		$key_by_file = null;
		switch ($this->action) {
		case "theme_upgrade":
			if (!empty($this->theme_info))
				$key = $this->theme_info['Name'];
			break;
		case "plugin_upgrade":
			if (!empty($this->plugin_info)) {
				$key = $this->plugin_info['Name'];
				$key_by_file = $this->plugins_by_name[$key];
			}
			break;
		case "installer":
			if (!empty($this->package))
				$key = $this->package;
			break;
		case "upgrade_translations":
			if (null != $this->language_update)
				$key = $this->language_update->package;
			break;
		}
		return array($key, $key_by_file);
	}

	function error($errors) {
		list($key, $key_by_file) = $this->get_keys();
		$message = array();
		$message['error'] = true;
		if (is_string($errors)) {
			$message['message'] = $errors;
		} elseif (is_wp_error($errors) && $errors->get_error_code()) {
			$message['data'] = $errors->get_error_data();
			$message['code'] = $errors->get_error_code();
		}
		$this->status[$this->action.':'.$key][] = $message;
		if (!empty($key_by_file)) {
			$this->status[$this->action.':'.$key_by_file][] = $message;
		}
	}

	function feedback($string, ...$args) {
		if ( empty($string) )
			return;

		if ( strpos( $string, '%' ) !== false ) {
			if ( $args ) {
				$args   = array_map( 'strip_tags', $args );
				$args   = array_map( 'esc_html', $args );
				$string = vsprintf( $string, $args );
			}
		}

		list($key, $key_by_file) = $this->get_keys();
		$message = array();
		$message['message'] = $string;
		if (!empty($key_by_file)) {
			$this->status[$this->action.':'.$key_by_file][] = $message;
		}
		$this->status[$this->action.':'.$key][] = $message;
	}
}
endif;