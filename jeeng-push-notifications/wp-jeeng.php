<?php
  /* 9OY3zWyYAG -DEMO-ID
  *Plugin Name: wp-jeeng-push-notifications
  * Plugin URI:        https://www.jeeng.com/
  * Description:       Jeeng push notification solution for WordPress blogs and websites. After setup, your visitors can opt-in to receive desktop push notifications when you publish a new post, and visitors receive these notifications even after they’ve left your website.We make it easy to configure delivering notifications at preset intervals, targeting notifications to specific users, and customizing the opt-in process for your visitors.
  * Version:           2.0.18
  * Author:            Jeeng
  * Author URI:
  * License:           GPL-3.0+
  * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
  * Text Domain:       jeeng
  *
  * This program is free software: you can redistribute it and/or modify
  * it under the terms of the GNU General Public License as published by
  * the Free Software Foundation, either version 3 of the License, or
  * (at your option) any later version.
  *
  * This program is distributed in the hope that it will be useful,
  * but WITHOUT ANY WARRANTY; without even the implied warranty of
  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  * GNU General Public License for more details.
  *
  * You should have received a copy of the GNU General Public License
  * along with this program.  If not, see <http://www.gnu.org/licenses/>.
  */
  if (!defined('ABSPATH')) {
    exit;
  }

  add_action( 'init', 'jeeng_add_script_service_worker');
  add_action('admin_menu','jeeng_admin_menu');
  add_action('wp_head','jeeng_header_scripts');
  
  
  function jeeng_add_script_service_worker() {
      $is_jeeng_service_active = get_option('jeeng_scripts_toggle');
      $request = $is_jeeng_service_active && isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : false;

      if ($is_jeeng_service_active && '/firebase-messaging-sw.js' === $request) {
          header( 'Content-Type: text/javascript' );
          echo file_get_contents(plugins_url( 'javascript/firebase-messaging-sw.js', __FILE__ ));
          die();
      }
  }
  
  
  function jeeng_admin_menu() {
    if (current_user_can('administrator') || current_user_can('editor') || current_user_can('author')) {
      add_menu_page('Jeeng Push Notifications','Jeeng','manage_options','jeeng-admin-menu','jeeng_page','dashicons-megaphone',200);
    }
  }

  function jeeng_header_scripts() {
	  $jeeng_client_id = get_option('jeeng_client_id','none');
    if (get_option('jeeng_scripts_toggle') && $jeeng_client_id !== '' && $jeeng_client_id !== 'none') {
      wp_enqueue_script('jeeng_sdk',esc_url("https://users.api.jeeng.com/users/domains/".$jeeng_client_id."/sdk"), array(), null);
    };
  }

  function jeeng_page() {
    
    $has_user_nonce = isset( $_POST['jeeng_edit_nonce'] ) && wp_verify_nonce( $_POST['jeeng_edit_nonce'], 'jeeng_edit_nonce' );

    if ($has_user_nonce && isset($_POST['update_client_id']))
    {
        update_option('jeeng_client_id', sanitize_text_field($_POST['client_id']));
        update_option('jeeng_scripts_toggle',sanitize_text_field($_POST['toggle']));
        ?>
        <div id="setting-error-settings-updated" class="updated settings-error notice is-dismissible">
        <strong>Settings have been saved.</strong></div>
        <?php
    }

    $client_id = sanitize_text_field(get_option('jeeng_client_id','none'));
	  $jeeng_scripts_toggle = sanitize_text_field(get_option('jeeng_scripts_toggle'));
    ?>
    <div class = "warp">
      <h1> Jeeng General Settings </h1>
      <form method="post" action="">
        <label>Enable Jeeng on Webs</label>
        <label class="switch">
		    <input name="toggle"  id = "toggle"  type="checkbox" value="checked" autocomplete="off" <?php print esc_attr($jeeng_scripts_toggle) ?>/>
        <span class="slider round"></span>
        </label><br>
        <label for="client_id">Account ID:    </label> 
		    <input  type="text" name="client_id"  value=<?php print esc_attr($client_id) ?>><br><br>
        <input type="submit" name="update_client_id" class = "button button-primary" value = "UPDATE"><br><br>
        <?php wp_nonce_field( 'jeeng_edit_nonce', 'jeeng_edit_nonce' ); ?>
      </form> 
      <div>
        To see reports and more,
        <a href="https://dashboard.jeeng.com" target="_blank">click here</a>
        to go to dashboard
      </div>
    </div>
   
    <?php
  }