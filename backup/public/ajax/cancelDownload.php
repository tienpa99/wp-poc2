<?php

if (backupGuardIsAjax() && isset($_POST['name'])) {
    @unlink(SG_BACKUP_DIRECTORY . sanitize_text_field($_POST['name']));
    die('{"success":1}');
}
