<?php
/***
* Plugin Name: DB Backup
* Description: Plugin for Database Backup.
* Version: 6.0
* Author: Syed Amir Hussain
***/
if ( ! defined('ABSPATH') ) {
	die('Please do not load this file directly.');
}
define('DBBKP_SLASH_N', "--\n");
if(!class_exists('DB_Backup')) {	
	class DB_Backup {
		private $input;
		function __construct() {
			if( is_admin() ) {
				// hook for adding admin menus
				add_action('admin_menu', array( $this, 'dbbkp_add_pages' ));
				add_action( 'admin_init', array( $this, 'dbbkp_init_css_js' ) );
				// action hook to handle ajax request
				add_action( 'wp_ajax_myAjax', array( $this, 'dbbkp_handleRequest' ) );
				// action hook to add option
				register_activation_hook( __FILE__, array( $this, 'dbbkp_update_option' ) );
			}
		}
		function dbbkp_update_option(){
				$array = wp_upload_dir();
				update_option('dbbkp_upload_path', str_replace('\\', '/', $array['basedir']));
		}
		function dbbkp_add_pages() {
			// add a new top-level menu
			add_menu_page('DB Backup', 'DB Backup', 'manage_options', 'db-backup', array( &$this, 'dbbkp_get_option' ) );
		}
		// action function to include css and js
		function dbbkp_init_css_js() {
			wp_register_style( 'style', plugins_url('/css/style.css', __FILE__));
			wp_enqueue_style( 'style' );
			wp_register_script( 'js_', plugins_url('/js/js.js', __FILE__));
			wp_enqueue_script( 'js_' );
		}
		// action function displays the page content for the Make CSV
		function dbbkp_get_option() {
			global $wpdb;
			//must check that the user has the required capability 
			if (!current_user_can('manage_options'))
			{
			  wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			$this->dbbkp_echo_option();
		}
		private function dbbkp_get_tables( $exclude_prefix = false ){
			global $wpdb;
			$sql = 'SHOW TABLES LIKE "%"';
			$results = $wpdb->get_results($sql);
			$tables = array();
			foreach($results as $index => $value) {
				foreach($value as $tableName) {
					if( $exclude_prefix ){
						$tableName = str_replace($wpdb->prefix, '', $tableName);
					}
					$tables[] = $tableName;
				}
			}
			if(count( array_filter($tables) )){
				return $tables;
			}
			die('Error! there is no tables in the selected database.');
		}
		// action function to create dropdown of the tables
		private function dbbkp_echo_option() {
			echo $this->dbbkp_get_template('index', $this->dbbkp_get_template('donate'));
		}
		function dbbkp_handleRequest() {
			$output = "";
			parse_str($_POST['data'], $_POST);
			$this->input = $this->dbbkp_clean_input($_POST);
			if( 'comp_bkp' == $this->input['csv_comp_bkp'] ){
				$tables = $this->dbbkp_get_tables( $exclude_prefix = false );
				$this->input['dbbkp_csv_tbl'] = array_merge( array(), $tables );
			}
			$func = 'dbbkp_'.$this->input['dbbkp_option'];
			foreach( $this->input['dbbkp_csv_tbl'] as $tab ):
				$output .= $this->$func( $tab )."\n\n";
			endforeach;
			if( isset($this->input['dbbkp_saveAs_option']) && "save_as" == $this->input['dbbkp_saveAs_option'] ):
				$jsResponse = $this->dbbkp_make_download( $output, $ext = $this->input['dbbkp_option'] );
			else:
				$jsResponse = '<textarea class="dbbkp_csv_output_area">'.$output.'</textarea>';
			endif;
			echo $jsResponse;
			die;	
		}
		// action function to make sql query
		private function dbbkp_export( $tbl ) {
			global $wpdb;
			$result_col = $wpdb->get_results('SHOW COLUMNS FROM '.$tbl);
			$struct = "";	$data = "";
			if( 'only_structure' == $this->input['ex_struct'] ) {
				$struct .= DBBKP_SLASH_N.'-- Table structure for table `'.$tbl."`\n".DBBKP_SLASH_N.'CREATE TABLE `'.$tbl."` (\n";
				foreach ($result_col as $row) {
					$null = ($row->Null == 'NO') ? ' NOT NULL' : '';
					$pri = ($row->Key == 'PRI') ? ' PRIMARY KEY' : '';
					$default = ($row->Default != '') ? ' DEFAULT "'.$row->Default.'"' : '';
					$extra = ($row->Extra != '') ? ' '.$row->Extra.' ' : '';
					$struct .= '`'.$row->Field.'` '.$row->Type.$null.$default.$extra.$pri.",\n";
				}
				$struct = rtrim($struct, ",\n");
				$struct .= "\n) ENGINE = MYISAM;\n\n";
			}
			if( 'only_data' == $this->input['ex_data'] ) {
				$rs_data = $wpdb->get_results('SELECT * FROM '.$tbl, ARRAY_A);
				if( $rs_data ){
					$fields = "";
					foreach ($result_col as $row) {
						$fields .= '`'.$row->Field.'`, ';
					}
					$fields = rtrim($fields, ', ');
					
					$values = "";
					foreach( $rs_data as $val ){
						$values .= "(";
						foreach( $val as $v ):
							$v = htmlentities($wpdb->_escape($v));
							$values .= '"'.$v.'", ';
						endforeach;
						$values = rtrim($values, ', ');
						$values .= "),\n";
					}
					$values = rtrim($values, ",\n");
					$data .= DBBKP_SLASH_N.'-- Dumping data for table `'.$tbl."`\n".DBBKP_SLASH_N.'INSERT INTO `'.$tbl.'`( '.$fields." ) VALUES\n".$values.';';
				}
			}
			$query = $struct.$data;
			return $query;
		}
		// action function to make the csv
		private function dbbkp_make_csv( $tbl ) {
			global $wpdb;
			$data = "";
			if( 'include_column' == $this->input['csv_inc_col'] ){
				$result_col = $wpdb->get_results('SHOW COLUMNS FROM '.$tbl);
				foreach( $result_col as $col ){
					$data .= '"'.$col->Field.'",';
				}
				$data = rtrim($data, ',');
				$data .= "\n";
			}
			$rs_data = $wpdb->get_results('SELECT * FROM '.$tbl, ARRAY_A);
			if( $rs_data ){
				$values = "";
				foreach( $rs_data as $val ){
					foreach( $val as $v ):
						$v = $wpdb->_escape(htmlentities($v));
						if( empty($v) ){
							$v = 'NULL';
						}
						$values .= '"'.$v.'",';
					endforeach;
					$values = rtrim($values, ',');
					$values .= "\n";
				}
				$data .= $values;
			}
			return $data;
		}
		// action to download export file
		private function dbbkp_make_download( $content = "", $ext ){
			$fileName = 'dbbkp_'.time();
			if( "" != $this->input['dbbkp_saveAs_fileName'] ) {
				$fileName = $this->input['dbbkp_saveAs_fileName'];
			}
			$ext = ( $ext == 'make_csv' )?'.csv':'.sql';
			$fileName = $this->dbbkp_make_file($fileName, $ext, $content);
			$url = content_url().'/uploads/'.$fileName;
			echo<<<EOM
				<script>
					jQuery('<form action="$url" method="post"></form>').appendTo('body').submit().remove();
				</script>
EOM;
		}
		// action to make file
		private function dbbkp_make_file( $fileName, $ext, $content ){
			$path = get_option('dbbkp_upload_path').'/'.$fileName.$ext;
			$fp = fopen($path, 'w');
			fwrite( $fp, $content);
			fclose($fp);
			# make zip
			return $this->dbbkp_make_zip( $fileName, $ext );
		}
		private function dbbkp_make_zip( $fileName, $ext ){
			$zip = new ZipArchive();
			$path = get_option('dbbkp_upload_path').'/'.$fileName.$ext;
			$zip_name = $fileName.'.zip';
			$zip_path = get_option('dbbkp_upload_path').'/'.$zip_name;
			$zip->open($zip_path, ZipArchive::CREATE);
			$zip->addFromString(basename($path),  file_get_contents($path));
			$zip->close();
			unlink( $path );
			return $zip_name;
		}
		private function dbbkp_get_template( $file, $donate="", $echo = false ){
			global $wpdb;
			ob_start();
			include('templates/'.$file.'.php');
			$content = ob_get_clean();
			if( $echo ) echo $content; else return $content;
		}
		private function dbbkp_clean_input($request){
			$data = array();
			$tables = $request['dbbkp_csv_tbl'];
			unset($request['dbbkp_csv_tbl']);
			foreach ($request as $key => $value) {
				if('dbbkp_saveAs_fileName' == $key){
					$data[$key] = sanitize_file_name($value);
				} else {
					$data[$key] = sanitize_key($value);
				}
			}
			foreach ($tables as $key => $value) {
				$data['dbbkp_csv_tbl'][$key] = sanitize_key($value);
			}
			return $data;
		}
	}
	new DB_Backup();
}