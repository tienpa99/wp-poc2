
<?php
#SG_DYNAMIC_DEFINES#

$action = isset($_REQUEST['action']) ? filter_var($_REQUEST['action'], FILTER_SANITIZE_STRING) : null;
$key = isset($_REQUEST['k']) ? filter_var($_REQUEST['k'], FILTER_SANITIZE_STRING) : null;
//validate key
if ($key != BG_RESTORE_KEY) die('Invalid key');

define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
define('SG_APP_ROOT_DIRECTORY', dirname(WP_CONTENT_DIR) . "/");
define('SG_ENV_WORDPRESS', 'Wordpress');
define('SG_ENV_ADAPTER', SG_ENV_WORDPRESS);
define('SG_DB_ADAPTER', SG_ENV_ADAPTER);
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define( 'WPINC', 'wp-includes' );

if (!defined('BG_EXTERNAL_RESTORE_RUNNING')) define('BG_EXTERNAL_RESTORE_RUNNING', true);

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

//check if JetBackup plugin exists
$pluginPath = WP_PLUGIN_DIR . '/' . SG_PLUGIN_NAME . '/com/config/';
if (!file_exists($pluginPath . 'config.php')) die('Plugin not found');


function maintenanceMode($active = false) {

	if ($active) {
		file_put_contents(ABSPATH . '.maintenance', '<?php $upgrading = ' . time() . '; ?>');
	} else {
		unlink(ABSPATH . '.maintenance');
	}

}


//require everything we need for only wpdb to run
include_once ABSPATH . 'wp-includes/version.php';
include_once ABSPATH . 'wp-includes/formatting.php';
include_once ABSPATH . 'wp-includes/plugin.php';
include_once ABSPATH . 'wp-includes/class-wp-error.php';
include_once ABSPATH . 'wp-includes/user.php';
include_once ABSPATH . 'wp-includes/class-wp-user.php';
include_once ABSPATH . 'wp-includes/link-template.php';
include_once ABSPATH . 'wp-includes/option.php';
include_once ABSPATH . 'wp-includes/load.php';
include_once ABSPATH . 'wp-includes/cache.php';
include_once ABSPATH . 'wp-includes/pluggable.php';
include_once ABSPATH . 'wp-includes/meta.php';
include_once ABSPATH . 'wp-includes/compat.php';


//starting from WordPress 4.7.1 is_wp_error() has been moved to another location
//wpdb needs it, so we create it here
if (!function_exists('is_wp_error')) {

	function is_wp_error($thing) {
		return ($thing instanceof WP_Error);
	}

}

if (!function_exists('absint')) {
// Issue #39, in some environments this returns fatal error for some reason

	function absint( $maybeint ) {
		return abs( (int) $maybeint );
	}

}

function readLines($fp, $num) {

	$line_count = 0; $line = ''; $pos = -1; $lines = array(); $c = '';

	while($line_count < $num) {
		$line = $c . $line;
		fseek($fp, $pos--, SEEK_END);
		$c = fgetc($fp);
		if($c == "\n") { $line_count++; $lines[] = $line; $line = ''; $c = ''; }
	}
	return $lines;
}

function array_in_string($str, array $arr) {
	foreach($arr as $arr_value) { //start looping the array
		if (stripos($str,$arr_value) !== false) return true; //if $arr_value is found in $str return true
	}
	return false; //else return false
}

include_once ABSPATH . 'wp-includes/wp-db.php';
global $wpdb;
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
$wpdb->db_connect();

maintenanceMode(true);

//the mysql version is needed for the charset handler
if (!defined('SG_MYSQL_VERSION')) define('SG_MYSQL_VERSION', $wpdb->db_version());

$dbCharset = 'utf8';
if (@constant("DB_CHARSET")) $dbCharset = DB_CHARSET;
if (!defined('SG_DB_CHARSET')) define('SG_DB_CHARSET', $dbCharset);

//require JetBackup plugin
$sgPluginFile = '';
if (file_exists($pluginPath . 'config.wordpress.pro.php')) {
	$sgPluginFile = 'backup-guard-pro';
	include_once $pluginPath . 'config.wordpress.pro.php';
} else if (file_exists($pluginPath . 'config.wordpress.free.php')) {
	$sgPluginFile = 'backup';
	include_once $pluginPath . 'config.wordpress.free.php';
}
require_once $pluginPath . 'config.php';
require_once SG_CORE_PATH . 'SGBoot.php';
include_once SG_BACKUP_PATH . 'SGBackup.php';

switch ($action) {

	case 'awake':

		include_once SG_BACKUP_PATH . 'SGBackup.php';
		$currentAction = SGBackup::getAction(SG_ACTION_ID);

		$backup_dir = SG_BACKUP_DIRECTORY;
		if (!is_dir($backup_dir)) $backup_dir = SG_BACKUP_OLD_DIRECTORY;

		$restore_log = $backup_dir.$currentAction['name']."/".$currentAction['name']."_restore.log";

		if (file_exists($restore_log)) {

			$fp = @fopen($restore_log, "r");
			$lines = readLines($fp, 2);
			if ($lines) array_shift($lines);
			$line = isset($lines[0]) ? $lines[0] : null;

			if (strpos($line, '###_Extract_OffSet_###') === false) {
				die('Busy');
			}

			fclose($fp);

		}


		$currentAction = SGBackup::getAction(SG_ACTION_ID);
		if ($currentAction) {
			$sgBackup = new SGBackup();
			$sgBackup->restore($currentAction['name'], SG_ACTION_ID);
		}

		break;

	case 'quit':

		maintenanceMode(false);
		// Todo - clear db & local state file

		break;

	case 'finalize':

		maintenanceMode(false);

		$row = $wpdb->get_row(
			$wpdb->prepare('SELECT option_value FROM '.SG_ENV_DB_PREFIX.'options WHERE option_name = %s', 'active_plugins')
		);

		$activePLugins = unserialize($row->option_value);
		$activePLugins[] = SG_PLUGIN_NAME . '/' . $sgPluginFile . '.php';
		$activePLuginsRow = serialize($activePLugins);

		$wpdb->query(
			$wpdb->prepare(
				"UPDATE `" . SG_ENV_DB_PREFIX . "options` SET option_value = %s WHERE option_name = %s",
				$activePLuginsRow,
				'active_plugins'
			)
		);

		//include_once SG_BACKUP_PATH . 'SGBackup.php';
		SGBackup::changeActionStatus(SG_ACTION_ID, SG_ACTION_STATUS_FINISHED);

		$runningActions = SGBackup::getRunningActions();
		if ($runningActions) SGBackup::cleanRunningActions($runningActions);

		$currentUser = SGConfig::get('SG_CURRENT_USER');
		$user        = unserialize($currentUser);

		$prefixFromBackup = SGConfig::get('SG_OLD_DB_PREFIX');


		if ($prefixFromBackup != SG_ENV_DB_PREFIX) {

			$WPconfig = ABSPATH.'wp-config.php';
			$WPconfigBackup = ABSPATH.'.jetbackup_'.rand().'_wp-config.php';

			if (file_exists($WPconfig)) {

				@chmod($WPconfig, 0777);

				$WPcontent=file_get_contents($WPconfig);
				$split = explode (SG_ENV_DB_PREFIX, $WPcontent );
				$NewWPcontent = implode( $prefixFromBackup, $split );
				@copy($WPconfig, $WPconfigBackup);
				@file_put_contents($WPconfig, $NewWPcontent);
				@chmod($WPconfig, 0400);
				@chmod($WPconfigBackup, 0400);

			}

			$dbuser = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$prefixFromBackup.'users WHERE `user_email` = %s', $user['email']));

			// User from SG_CURRENT_USER is the same as user from DB
			if (isset($dbuser->ID) && is_numeric($dbuser->ID)) die(1); // same user

			// Not sure user, we need to inject current active admin so user can login after the switch
			$name = $user['login'];
			$email = $user['email'];
			$pass = $user['pass'];
			$now = date("Y-m-d H:i:s");

			$sql = "INSERT INTO `" . $prefixFromBackup . "users`
          (`user_login`,`user_pass`,`user_nicename`,`user_email`,`user_url`,`user_registered`,`user_activation_key`,`user_status`,`display_name`) 
   values ('".$name."', '".$pass."', '".$name."', '".$email."', 'url', '".$now."', 'key', 0, '".$name."')";

			$res = $wpdb->query($sql);
			$lastid = $wpdb->insert_id;

			if ($lastid && is_numeric($lastid)) {

				$user_id = $lastid;
				$meta_key = $prefixFromBackup.'capabilities';
				$meta_value = 'a:1:{s:13:"administrator";s:1:"1";}';

				$sql = "INSERT INTO `" . $prefixFromBackup . "usermeta`
          (`user_id`,`meta_key`,`meta_value`) 
   values ('".$user_id."', '".$meta_key."', '".$meta_value."')";
				$res = $wpdb->query($sql);

			}

			die(1);

		} // if ($prefixFromBackup != SG_ENV_DB_PREFIX)


		die(1);

	case 'getAction':

		include_once SG_BACKUP_PATH . 'SGBackup.php';
		$currentAction = SGBackup::getAction(SG_ACTION_ID);

		$backup_dir = SG_BACKUP_DIRECTORY;
		if (!is_dir($backup_dir)) $backup_dir = SG_BACKUP_OLD_DIRECTORY;

		$restore_log = $backup_dir.$currentAction['name']."/".$currentAction['name']."_restore.log";

		if (file_exists($restore_log)) {

			$fp = @fopen($restore_log, "r");
			$lines = readLines($fp, 2);
			if ($lines) array_shift($lines);
			$line = isset($lines[0]) ? $lines[0] : null;
			$currentAction['lastAction'] = $line;
			fclose($fp);

		}


		$status = isset($currentAction['status']) ? $currentAction['status'] : null;

		switch ($status) {

			case SG_ACTION_STATUS_CREATED:
			case SG_ACTION_STATUS_IN_PROGRESS_FILES:
			case SG_ACTION_STATUS_IN_PROGRESS_DB:

				die (json_encode($currentAction));
				break;

			case SG_ACTION_STATUS_FINISHED:
			case SG_ACTION_STATUS_FINISHED_WARNINGS:

				die ('1');

			default: die('0');

		}

		break;

	default: break;


}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="<?php echo SG_PUBLIC_URL; ?>css/spinner.css">
    <link rel="stylesheet" type="text/css" href="<?php echo SG_PUBLIC_URL; ?>css/bgstyle.less.css">
    <link rel="stylesheet" type="text/css" href="<?php echo SG_PUBLIC_URL; ?>css/main.css">
    <style>
        body {
            background-color: #fff;
            padding: 0;
            margin: 0;
        }

        .sg-box-center {
            width: 400px;
            position: absolute;
            left: 50%;
            margin-left: -200px;
            margin-top: 100px;
            border: 1px solid #5c5c5c;
        }

        .sg-logo {
            text-align: center;
            padding: 20px 0;
            background-color: #0021C8;
        }

        .sg-wrapper-less .sg-progress {
            height: 4px;
            margin: 1px 0 0;
        }

        .sg-progress-box p {
            margin-top: 10px;
            text-align: center;
        }

        .restore-warning {
            color: #C20000;
        }

        .restore-progress-p {
            font-size: 21px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="sg-wrapper-less">
    <div class="sg-wrapper">
        <div class="sg-box-center">
            <div class="sg-logo">
                <img width="172px" src="<?php echo SG_PUBLIC_URL; ?>img/jetbackup.svg">
            </div>
            <div class="sg-progress-box">
                <p class="restore-progress-p">Restoring <span id="progressItem">files</span>: <span
                            id="progressTxt">0%</span></p>
                <p class="restore-progress-file" style="font-style: italic; font-size: 12px;"><span id="progressFile">...</span></p>
                <p class="restore-warning"><small>NOTE: Please don't close your browser until finished.</small></p>
                <div class="sg-progress progress">
                    <div id="progressBar" class="progress-bar" style="width: 0%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>


    function bgRunAwake(url) {

        var req;

        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }

        req.open("GET", url, true);
        req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        req.send();

    }

    function bgRunAjax(url, responseHandler, params) {
        var req;
        if (window.XMLHttpRequest) {
            req = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            req = new ActiveXObject("Microsoft.XMLHTTP");
        }
        req.onreadystatechange = function () {
            if (req.readyState == 4) {
                if (req.status < 400) {
                    responseHandler(req, params);
                }
            }
        };
        req.open("POST", url, true);
        req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        req.send(params);
    }

    function bgUpdateProgress(progress) {
        var progressInPercents = progress + '%';
        var progressBar = document.getElementById('progressBar');
        progressBar.style.width = progressInPercents;
        var progressTxt = document.getElementById('progressTxt');
        progressTxt.innerHTML = progressInPercents;
    }

    var getActionRunning = false;

    function getAction() {

        if (getActionRunning) return;
        getActionRunning = true;

        bgRunAjax("<?php echo BG_RESTORE_URL; ?>&action=getAction", function (response) {
            try {
                var response = eval('(' + response.responseText + ')');

                if (response === 1) {

                    clearInterval(getActionTimer);
                    clearInterval(getAwakeTimer);

                    bgRunAjax("<?php echo BG_RESTORE_URL; ?>&action=finalize", function (response) {

                        bgUpdateProgress(100);
                        location.href = '<?php echo BG_PLUGIN_URL; ?>';
                    }, "");

                    return;
                } else if (response === 0) {
                    clearInterval(getActionTimer);
                    clearInterval(getAwakeTimer);

                    bgUpdateProgress(100);
                    location.href = '<?php echo BG_PLUGIN_URL; ?>';
                    return;
                } else if (typeof response === 'object') {
                    bgUpdateProgress(response.progress);
                    if (response.status ==<?php echo SG_ACTION_STATUS_IN_PROGRESS_FILES; ?>) {
                        progressItem.innerHTML = 'files';
                    } else {
                        progressItem.innerHTML = 'database';
                    }
                    progressFile.innerHTML = response.lastAction;
                }
            } catch (e) {
            }

            getActionRunning = false;

        }, "");
    }

    //get action  (for progress)
    var getActionTimer = setInterval(function () {
        getAction();
    }, 5000);

    //get action  (for progress)
    var getAwakeTimer = setInterval(function () {
        bgRunAwake("<?php echo BG_RESTORE_URL; ?>&action=awake");
    }, 20000);

    getAction();

</script>
</body>
</html>