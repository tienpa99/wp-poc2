<?php

require_once(dirname(__FILE__) . '/../boot.php');
require_once(SG_BACKUP_PATH . 'SGBackup.php');

if (backupGuardIsAjax() && count($_POST)) {
    $error = array();
    try {
        define('BG_EXTERNAL_RESTORE_RUNNING', true);

        $backupName  = sanitize_textarea_field($_POST['bname']);
		$restoreMode = isset($_POST['type'])? sanitize_textarea_field($_POST['type']) : SG_RESTORE_MODE_FULL;

        $backup      = new SGBackup();
		$backup->cleanUpRestoreState($backupName);
        $backup->restore($backupName, null, $restoreMode);
    } catch (SGException $exception) {
        array_push($error, $exception->getMessage());
        die(json_encode($error));
    }
}
