<?php

require_once(SG_BACKUP_PATH . 'SGBackupSchedule.php');
$allSchedules = SGBackupSchedule::getAllSchedules();
$contentClassName = esc_attr(getBackupPageContentClassName('schedule'));
?>
<div id="sg-backup-page-content-schedule" class="sg-backup-page-content <?php echo $contentClassName; ?>">
    <div class="sg-schedule-container">
        <fieldset>
            <div><h1 class="sg-backup-page-title"><?php _backupGuardT('Schedules') ?></h1></div>
            <button class="pull-left btn btn-success sg-backup-action-buttons" data-toggle="modal"
                    data-modal-name="create-schedule" data-remote="modalCreateSchedule">
                <span class="sg-backup-cross sg-backup-buttons-content"></span>
                <span class="sg-backup-buttons-text sg-backup-buttons-content"><?php _backupGuardT('Create schedule') ?></span>
            </button>
            <div class="clearfix"></div>
            <br/>
            <table class="table table-striped sg-schedule paginated sg-backup-table">
                <thead>
                <tr>
                    <th><?php _backupGuardT('Label') ?></th>
                    <th><?php _backupGuardT('Recurrence') ?></th>
                    <th><?php _backupGuardT('Execution date') ?></th>
                    <th><?php _backupGuardT('Backup options') ?></th>
                    <th><?php _backupGuardT('Upload to') ?></th>
                    <th><?php _backupGuardT('Status') ?></th>
                    <th><?php _backupGuardT('Actions') ?></th>
                </tr>
                </thead>
                <tbody>
				<?php if (empty($allSchedules)) : ?>
                    <tr>
                        <td colspan="7"><?php _backupGuardT('No schedules found.') ?></td>
                    </tr>
				<?php endif; ?>
				<?php foreach ($allSchedules as $schedule) :
					$backupOptions = backupGuardParseBackupOptions($schedule);
					?>
                    <tr>
                        <td><?php echo esc_html($schedule['label']) ?></td>
                        <td><?php echo esc_html($schedule['recurrence']) ?></td>
                        <td><?php echo backupGuardConvertDateTimezone(@date('Y-m-d H:i:s', (int)$schedule['executionDate']), true) ?></td>
                        <td>
							<?php
							$showOptions = array();
							if (!$backupOptions['isCustomBackup']) {
								$showOptions[] = 'Full';
							} else {
								if ($backupOptions['isDatabaseSelected']) {
									$showOptions[] = 'DB';
								}
								if ($backupOptions['isFilesSelected']) {
									$selectedDirectories = str_replace('wp-content/', '', $backupOptions['selectedDirectories']);
									if (in_array('wp-content', $selectedDirectories)) {
										$showOptions[] = 'wp-content';
									} else {
										$showOptions = array_merge($showOptions, $selectedDirectories);
									}
								}
							}
							echo implode(', ', $showOptions);
							?>
                        </td>
                        <td>
							<?php
							foreach ($backupOptions['selectedClouds'] as $cloud) {
								echo '<span class="btn-xs sg-status-icon sg-status-3' . esc_attr($cloud) . '">&nbsp;</span> ';
							}
							?>
                        </td>
                        <td><?php echo (int)$schedule['status'] == SG_SHCEDULE_STATUS_PENDING ? '<span class="sg-schedule-pending">' . _backupGuardT('Pending', true) . '</span>' : '<span class="sg-schedule-inactive">' . _backupGuardT('Inactive', true) . '</span>' ?></td>
                        <td>
                            <a data-toggle="modal" data-modal-name="create-schedule" data-remote="modalCreateSchedule"
                               data-sgbp-params="<?php echo esc_attr($schedule['id']) ?>"
                               class="btn-xs sg-schedule-icon sg-schedule-edit" title="<?php _backupGuardT('Edit') ?>">&nbsp</a>
                            <a onclick="sgBackup.removeSchedule(<?php echo esc_attr($schedule['id']) ?>)"
                               class="btn-xs sg-schedule-icon sg-schedule-delete"
                               title="<?php _backupGuardT('Delete') ?>">&nbsp&nbsp;</a>
                        </td>
                    </tr>
				<?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-right sg-schedule">
                <ul class="pagination"></ul>
            </div>
        </fieldset>
    </div>
</div>
