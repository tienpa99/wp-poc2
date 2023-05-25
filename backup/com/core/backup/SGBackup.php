<?php

require_once(SG_RESTORE_PATH . 'SGExternalRestore.php');
require_once(SG_LIB_PATH . 'SGMysqldump.php');
require_once(SG_LIB_PATH . 'SGCharsetHandler.php');
@include_once(SG_LIB_PATH . 'SGMigrate.php');
require_once(SG_BACKUP_PATH . 'SGBackupStorage.php');
@include_once(SG_BACKUP_PATH . 'SGBackupMailNotification.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGArchive.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGLog.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGTask.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGStateFile.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGArchiveHelper.php');
require_once(SG_LIB_PATH . 'BackupGuard/Core/SGBGDirectoryTreeFile.php');

//close session for writing
@session_write_close();

class SGBackup implements ISGArchiveDelegate, SGIMysqldumpDelegate
{
    private $_backupFiles              = null;
    private $_backupDatabase           = null;
    private $_actionId                 = null;
    private $_filesBackupAvailable     = false;
    private $_databaseBackupAvailable  = false;
    private $_isManual                 = true;
    private $_actionStartTs            = 0;
    private $_fileName                 = '';
    private $_filesBackupPath          = '';
    private $_databaseBackupPath       = '';
    private $_backupLogPath            = '';
    private $_restoreLogPath           = '';
    private $_backgroundMode           = false;
    private $_pendingStorageUploads    = array();
    private $_state                    = null;
    private $_token                    = '';
    private $_options                  = array();
    private $_excludeFilePaths         = array();
    private $cacheSize                 = 16 * 1024 * 1024;
    private $_cacheTimeOut             = 10;
    private $_filesCount;
    private $_rowsCount;
    private $_stateFile;
    private $_treeFile;
    private $_logFile;
    private $_archive;
    private $_sgdb;
    private $_logEnabled               = true;
    private $_dontExclude              = array();
    private $_totalRowCount            = 0;
    private $_progressUpdateInterval   = 0;
    private $_currentUploadChunksCount = 0;
    private $_totalUploadChunksCount   = 0;
    private $_nextProgressUpdate       = 0;
    private $_migrationAvailable       = null;
    private $_backedUpTables           = null;
    private $_newTableNames            = null;
    private $_oldDbPrefix              = null;
    private $_migrateObj               = null;
    private $_charsetHandler           = null;
    private $_databaseBackupOldPath    = null;
    private $_restoreMode = SG_RESTORE_MODE_FULL;

    public function __construct()
    {
        $this->_progressUpdateInterval = SGConfig::get('SG_ACTION_PROGRESS_UPDATE_INTERVAL');
        $this->_sgdb                   = SGDatabase::getInstance();
    }

    public function getFilesCount()
    {
        return $this->_filesCount;
    }

    public function setFilesCount($filesCount)
    {
        $this->_filesCount = $filesCount;
    }

    public function getRowsCount()
    {
        return $this->_rowsCount;
    }

    public function setRowsCount($rowsCount)
    {
        $this->_rowsCount = $rowsCount;
    }

    public function getStateFile()
    {
        return $this->_stateFile;
    }

    public function setStateFile($stateFile)
    {
        $this->_stateFile = $stateFile;
    }

    public function getTreeFile()
    {
        return $this->_treeFile;
    }

    public function setTreeFile()
    {
        $this->_treeFile = new SGBGDirectoryTreeFile(SG_BACKUP_DIRECTORY . $this->_fileName . '/tree.txt');
        if (!empty($this->_options['SG_BACKUP_FILE_PATHS'])) {
            $addPaths = $this->_options['SG_BACKUP_FILE_PATHS'];
            $this->_treeFile->setAddedFilePaths(explode(',', $addPaths));
        } else {
            $this->_treeFile->setAddedFilePaths(array());
        }
        if (!empty($this->_options['SG_BACKUP_FILE_PATHS_EXCLUDE'])) {
            $excludePaths       = $this->_options['SG_BACKUP_FILE_PATHS_EXCLUDE'];
            $userCustomExcludes = SGConfig::get('SG_PATHS_TO_EXCLUDE');
            if (!empty($userCustomExcludes)) {
                $excludePaths .= ',' . $userCustomExcludes;
            }
            $this->_treeFile->setExcludedFilePaths(explode(',', $excludePaths));
        } else {
            $this->_treeFile->setExcludedFilePaths(array());
        }
    }

    public function getLogFile()
    {
        return $this->_logFile;
    }

    public function setLogFile($logFilePath)
    {
        $logFile = new SGBGLog($logFilePath);
        $logFile->getCache()->setCacheMode(SGBGCache::CACHE_MODE_TIMEOUT | SGBGCache::CACHE_MODE_SIZE);
        $logFile->getCache()->setCacheTimeout(8);
        $logFile->getCache()->setCacheSize(8 * 1024 * 1024);
        $this->_logFile = $logFile;
    }

    public function log($logData, $forceWrite = false)
    {
        if ($this->getLogEnabled() || $forceWrite) {
            $this->getLogFile()->save($logData);
        }
    }

    public function logException($exception, $forceWrite = false)
    {
        $logData = $exception . ': ' . $exception->getMessage() . ' ';
        $logData .= '[File: ' . $exception->getFile() . ', Line: ' . $exception->getLine() . ']';

        if ($this->getLogEnabled() || $forceWrite) {
            $this->getLogFile()->save($logData);
        }
    }

    public function setLogEnabled($logEnabled)
    {
        $this->_logEnabled = $logEnabled;
    }

    public function getLogEnabled()
    {
        return $this->_logEnabled;
    }

    public function getArchive()
    {
        return $this->_archive;
    }

    public function setArchive($task, $logEnabled)
    {
        $this->_archive = new SGBGArchive(SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.sgbp');
        $this->_archive->setDelegate($this);
        $this->_archive->setTask($task);
        $this->_archive->setLogEnabled($logEnabled);
        $this->_archive->setLogFile($this->getLogFile());
        $this->_archive->getCache()->setCacheMode(SGBGCache::CACHE_MODE_TIMEOUT | SGBGCache::CACHE_MODE_SIZE);
        $this->_archive->getCache()->setCacheTimeout($this->_cacheTimeOut);
        $this->_archive->getCache()->setCacheSize($this->cacheSize);
        if (!empty($this->_options['SG_BACKUP_FILE_PATHS_EXCLUDE']) && strlen($this->_options['SG_BACKUP_FILE_PATHS_EXCLUDE'])) {
            $excludePaths       = $this->_options['SG_BACKUP_FILE_PATHS_EXCLUDE'];
            $userCustomExcludes = SGConfig::get('SG_PATHS_TO_EXCLUDE');
            if (!empty($userCustomExcludes)) {
                $excludePaths .= ',' . $userCustomExcludes;
            }
            $this->_archive->setExcludePaths(explode(',', $excludePaths));
        } else {
            $this->_archive->setExcludePaths(array());
        }
        $this->_archive->setOptions($this->_options);
    }

    public function setFileName($name)
    {
        $this->_fileName = $name;
    }

    public function getFileName()
    {
        return $this->_fileName;
    }

    public function setExcludeFilePaths($paths)
    {
        $this->_excludeFilePaths = $paths;
    }

    public function getExcludeFilePaths()
    {
        return $this->_excludeFilePaths;
    }

    public function setOptions($options)
    {
        $this->_options = $options;

        $this->_filesBackupAvailable    = isset($options['SG_ACTION_BACKUP_FILES_AVAILABLE']) ? $options['SG_ACTION_BACKUP_FILES_AVAILABLE'] : false;
        $this->_databaseBackupAvailable = isset($options['SG_ACTION_BACKUP_DATABASE_AVAILABLE']) ? $options['SG_ACTION_BACKUP_DATABASE_AVAILABLE'] : false;
        $this->_backgroundMode          = isset($options['SG_BACKUP_IN_BACKGROUND_MODE']) ? $options['SG_BACKUP_IN_BACKGROUND_MODE'] : false;
        if (!empty($options['SG_BACKUP_UPLOAD_TO_STORAGES'])) {
            $this->_pendingStorageUploads = explode(',', $options['SG_BACKUP_UPLOAD_TO_STORAGES']);
        }
    }

    public function getOptions()
    {
        return $this->_options;
    }

    public function getScheduleParamsById($id)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT * FROM ' . SG_SCHEDULE_TABLE_NAME . ' WHERE id=%d', array($id));
        if (empty($res)) {
            return '';
        }

        return $res[0];
    }

    public function listStorage($storage)
    {
        if (SGBoot::isFeatureAvailable('DOWNLOAD_FROM_CLOUD')) {
            $listOfFiles = SGBackupStorage::getInstance()->listStorage($storage);

            return $listOfFiles;
        }

        return array();
    }

    public function downloadBackupArchiveFromCloud($archive, $storage, $size, $backupId = null)
    {
        $result = false;
        if (SGBoot::isFeatureAvailable('DOWNLOAD_FROM_CLOUD')) {
            $result = SGBackupStorage::getInstance()->downloadBackupArchiveFromCloud($storage, $archive, $size, $backupId);
        }

        return $result;
    }

    public function getToken()
    {
        return $this->_token;
    }

	private function cleanUpDirectoryState()
	{
		if (file_exists(SG_BACKUP_DIRECTORY.JBWP_DIRECTORY_STATE_FILE_NAME)) {
			unlink(SG_BACKUP_DIRECTORY.JBWP_DIRECTORY_STATE_FILE_NAME);
		}
	}

	private function setCronJobForReloading()
	{
		wp_schedule_event(time() + JBWP_CRON_RELOAD_INTERVAL, 'sixty_seconds', JBWP_RELOAD_SCHEDULE_ACTION);
	}

	private function removeCronJobForReloading()
	{
		wp_clear_scheduled_hook(JBWP_RELOAD_SCHEDULE_ACTION);
	}

    /* Backup implementation */
    public function backup($options, $logEnabled = false, $removeStateFile = false)
    {
        $task = new SGBGTask();

        $this->setLogEnabled($logEnabled);

        try {
            $actions = self::getRunningActions();
            if (count($actions)) {
                $action          = $actions[0];
                $this->_fileName = $action['name'];
                $this->_actionId = $action['id'];
                $options         = json_decode($action['options'], 1);
                $this->setOptions($options);
                $this->setLogFile(SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '_backup.log');
                $this->setBackupPaths();
            } else {
                $this->setOptions($options);
                $this->_fileName = $this->getBackupFileName();
                $this->_token    = backupGuardGenerateToken();
				$this->cleanUpDirectoryState();
                $this->prepareForBackup();
				// in case if previous backup cron was not cleared
				$this->removeCronJobForReloading();
				$this->setCronJobForReloading();
            }

            $actions = self::getRunningActions();
            $action  = $actions[0];

            $task->prepare(SG_BACKUP_DIRECTORY . $this->_fileName . '/state.json');
            $this->setStateFile($task->getStateFile());
            $this->setFilesCount($this->getStateFile()->getCount());

            if ($this->_databaseBackupAvailable) {
                if ($action['status'] == SG_ACTION_STATUS_IN_PROGRESS_DB) {
                    $this->resetBackupProgress();
                    $this->setRowsCount($this->_totalRowCount);
                    $task->start($this->_totalRowCount);
                    $task->getStateFile()->setAction(SG_STATE_ACTION_PREPARING_STATE_FILE);
                    $task->getStateFile()->setOffset(0);
                    $task->getStateFile()->setAction(SG_STATE_ACTION_PREPARING_STATE_FILE);
                    $task->getStateFile()->setType(SG_STATE_TYPE_DB);
                    $task->getStateFile()->setActionId($this->_actionId);
                    $task->getStateFile()->setStartTs($this->_actionStartTs);
                    $task->getStateFile()->setBackupFileName($this->_fileName);
                    $task->getStateFile()->setPendingStorageUploads($this->_pendingStorageUploads);
                    $task->getStateFile()->setBackedUpTables(array());
                    $tablesToBackup = empty($this->_options['SG_BACKUP_TABLES_TO_BACKUP']) ? [] : $this->_options['SG_BACKUP_TABLES_TO_BACKUP'];
                    $task->getStateFile()->setTablesToBackup($tablesToBackup);
                    $this->startBackupDB($task);
					$task->getStateFile()->setCount(0);
					$task->getStateFile()->setOffset(0);
                    $task->end(false);
                    $this->setFilesCount(0);

                    self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_IN_PROGRESS_FILES);
                }
            }

            $this->setTreeFile();

            if ($action['type'] == SG_ACTION_TYPE_BACKUP) {
                $this->getStateFile()->setPendingStorageUploads($this->_pendingStorageUploads);
                if (!$this->getFilesCount()) {
                    $this->getTreeFile()->getCache()->setCacheMode(SGBGCache::CACHE_MODE_TIMEOUT | SGBGCache::CACHE_MODE_SIZE);
                    $this->getTreeFile()->getCache()->setCacheTimeout($this->_cacheTimeOut);
                    $this->getTreeFile()->getCache()->setCacheSize($this->cacheSize);
                    $this->getTreeFile()->setRootPath(rtrim(SGConfig::get('SG_APP_ROOT_DIRECTORY'), '/') . '/');
                    if ($this->_databaseBackupAvailable) {
                        $this->getTreeFile()->addDontExclude($this->_databaseBackupPath);
                    }
                    $this->getTreeFile()->save();
                    $this->setFilesCount($this->getTreeFile()->getFilesCount());

                    $this->log('Root path ' . $this->_filesBackupPath, true);
                    $this->log('Number of files to backup ' . $this->getFilesCount(), true);
                    $this->log('Start backup files', true);
                }
                $this->setArchive($task, $logEnabled);
                $this->getArchive()->open('w');
                $task->start($this->getFilesCount());
                $this->startBackupFiles($task);
                $this->getArchive()->finalize();
                $this->didFinishBackup();
                $task->end(false);
                $this->getTreeFile()->remove();
				$this->getLogFile()->getCache()->flush();
            }

            $this->backupUploadToStorages();
			$this->removeCronJobForReloading();

			$this->getLogFile()->getCache()->flush();
        } catch (Exception $e) {
            if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
                //Writing backup status to report file
                file_put_contents(dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME, 'Backup failed', FILE_APPEND);
                SGBackupMailNotification::sendBackupNotification(
                    SG_ACTION_STATUS_ERROR,
                    array(
                        'flowFilePath' => dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME,
                        'archiveName'  => $this->_fileName
                    )
                );
            }

            self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_ERROR);
            $this->removeCronJobForReloading();
        }
    }

    private function prepareBackupFolder($backupPath)
    {
        if (!is_writable(SG_BACKUP_DIRECTORY)) {
            throw new SGExceptionForbidden('Permission denied. Directory is not writable: ' . $backupPath);
        }

        //create backup folder
        if (!file_exists($backupPath) && !@mkdir($backupPath)) {
            throw new SGExceptionMethodNotAllowed('Cannot create folder: ' . $backupPath);
        }

        if (!is_writable($backupPath)) {
            throw new SGExceptionForbidden('Permission denied. Directory is not writable: ' . $backupPath);
        }

        //create backup log file
        $this->prepareBackupLogFile($backupPath);
    }

    private function prepareForBackup()
    {
        $this->prepareBackupFolder(SG_BACKUP_DIRECTORY . $this->_fileName);
        $this->setLogFile(SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '_backup.log');

        //start logging
        $this->log('Start backup', true);
        $this->getLogFile()->getCache()->flush();

        //save timestamp for future use
        $this->_actionStartTs = time();

        //create action inside db
        $status          = $this->_databaseBackupAvailable ? SG_ACTION_STATUS_IN_PROGRESS_DB : SG_ACTION_STATUS_IN_PROGRESS_FILES;
        $this->_actionId = self::createAction($this->_fileName, SG_ACTION_TYPE_BACKUP, $status, 0, json_encode($this->_options));

        //set paths
        $this->setBackupPaths();

        //additional configuration
        $this->prepareAdditionalConfigurations();

        //check if upload to storages is needed
        $this->prepareUploadToStorages($this->_options);
    }

    private function startBackupFiles($task)
    {
        for ($i = $this->getStateFile()->getOffset(); $i < $this->getFilesCount(); $i++) {
            $file         = $this->getTreeFile()->getFileAtIndex($i);
            $relativePath = $this->pathWithoutRootDirectory($file);
            if (substr($file, -1) != '/') {
                $this->getArchive()->addFileFromPath($relativePath, $file);
            } else {
                if (SGBGArchiveHelper::is_dir_empty($relativePath)) {
                    $this->getArchive()->addEmptyDirectory($relativePath);
                }
            }
            $task->endChunk();
        }
    }

    private function didFinishBackup()
    {
        if (SGConfig::get('SG_REVIEW_POPUP_STATE') != SG_NEVER_SHOW_REVIEW_POPUP) {
            SGConfig::set('SG_REVIEW_POPUP_STATE', SG_SHOW_REVIEW_POPUP);
        }

        $action = $this->didFindWarnings() ? SG_ACTION_STATUS_FINISHED_WARNINGS : SG_ACTION_STATUS_FINISHED;
        self::changeActionStatus($this->_actionId, $action);

        $report = $this->didFindWarnings() ? 'completed with warnings' : 'completed';

        //Writing backup status to report file
        file_put_contents(dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME, 'Backup: ' . $report . "\n", FILE_APPEND);

        if (SGBoot::isFeatureAvailable('NOTIFICATIONS') && !count($this->_pendingStorageUploads)) {
            SGBackupMailNotification::sendBackupNotification(
                $action,
                array(
                    'flowFilePath' => dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME,
                    'archiveName'  => $this->_fileName
                )
            );
        }

        $this->log('End backup files', true);
        $this->log('Backup ' . $report, true);
        $this->log('Total duration ' . backupGuardFormattedDuration($this->getStateFile()->getStartTs(), time()), true);
        $this->log('Memory peak usage ' . (memory_get_peak_usage(true) / 1024 / 1024) . 'MB', true);
        if (function_exists('sys_getloadavg') && sys_getloadavg() !== false) {
            $this->log('CPU usage ' . implode(' / ', sys_getloadavg()), true);
        }

        $archiveSizeInBytes = backupGuardRealFilesize($this->_filesBackupPath);
        $archiveSize        = convertToReadableSize($archiveSizeInBytes);
        $this->log("Archive size " . $archiveSize . " (" . $archiveSizeInBytes . " bytes)", true);

        $this->cleanUp();
        if (SGBoot::isFeatureAvailable('NUMBER_OF_BACKUPS_TO_KEEP') && function_exists('backupGuardOutdatedBackupsCleanup')) {
            backupGuardOutdatedBackupsCleanup(SG_BACKUP_DIRECTORY);
        }
    }

    private function pathWithoutRootDirectory($path)
    {
        return substr($path, strlen(rtrim(SGConfig::get('SG_APP_ROOT_DIRECTORY'), '/') . '/'));
    }

    private function startBackupDB($task)
    {
        $this->log('Start backup database', true);
        $this->log('Total tables to backup ' . count($this->getTables()), true);
        $this->log('Total rows to backup ' . $this->_totalRowCount, true);
        $this->getLogFile()->getCache()->flush();

        $actionStartTs         = $task->getStateFile()->getStartTs();
        $customTablesToExclude = !empty(SGConfig::get('SG_TABLES_TO_EXCLUDE')) ? ',' . str_replace(' ', '', SGConfig::get('SG_TABLES_TO_EXCLUDE')) : '';
        $tablesToExclude       = explode(',', SGConfig::get('SG_BACKUP_DATABASE_EXCLUDE') . $customTablesToExclude);
        $tablesToBackup        = $task->getStateFile()->getTablesToBackup() ? explode(',', $task->getStateFile()->getTablesToBackup()) : array();

        $dump = new SGMysqldump(
            SGDatabase::getInstance(),
            SG_DB_NAME,
            'mysql',
            array(
                'exclude-tables'        => $tablesToExclude,
                'include-tables'        => $tablesToBackup,
                'skip-dump-date'        => true,
                'skip-comments'         => true,
                'skip-tz-utz'           => true,
                'add-drop-table'        => true,
                'no-autocommit'         => false,
                'single-transaction'    => false,
                'lock-tables'           => false,
                'default-character-set' => SG_DB_CHARSET,
                'add-locks'             => false
            )
        );
        $dump->setDelegate($this);
        $this->setLogFile(SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '_backup.log');
        $dump->start($this->_databaseBackupPath, $task);

        $this->log('End backup database', true);
        $this->log('Backup database total duration ' . backupGuardFormattedDuration($actionStartTs, time()), true);
        $this->getLogFile()->getCache()->flush();
    }

    private function prepareBackupReport()
    {
        file_put_contents(dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME, 'Report for: ' . SG_SITE_URL . "\n", FILE_APPEND);
    }

    private function shouldDeleteBackupAfterUpload()
    {
        return SGConfig::get('SG_DELETE_BACKUP_AFTER_UPLOAD') ? true : false;
    }

    private function backupUploadToStorages()
    {
        //check list of storages to upload if any
        $uploadToStorages = count($this->_pendingStorageUploads) ? true : false;

        if (SGBoot::isFeatureAvailable('STORAGE') && $uploadToStorages) {
            while (count($this->_pendingStorageUploads) > 0) {

                $task = new SGBGTask();
                $task->prepare(SG_BACKUP_DIRECTORY . $this->_fileName . '/state_upload.json');

                if (!empty($task->getStateFile()->getPendingStorageUploads())) {
                    $this->_pendingStorageUploads = $task->getStateFile()->getPendingStorageUploads();
                }

                $sgBackupStorage = SGBackupStorage::getInstance();
                $storageId       = $this->_pendingStorageUploads[0];
                $storageInfo     = $sgBackupStorage->getStorageInfoById($storageId);

                if (empty($storageInfo['isConnected'])) {
                    $this->log($storageInfo['storageName'] . ' stopped', true);
                    array_shift($this->_pendingStorageUploads);
                    continue;
                }

                $actions = self::getRunningActions();

                if ($storageId == 0) {
                    return;
                }

                if (!count($actions)) {
                    $this->_actionId = SGBackupStorage::queueBackupForUpload($this->_fileName, $storageId, $this->_options);
                } else {
                    $this->_actionId = $actions[0]['id'];
                }

                $this->startUploadByActionId($task, $this->_actionId);

                array_shift($this->_pendingStorageUploads);

                $task->getStateFile()->setPendingStorageUploads($this->_pendingStorageUploads);
                $task->getStateFile()->save(true);

                $task->end(true);
            }

            $this->didFinishUpload();
            $this->updateUploadProgress();
        }
    }

    private function didFinishUpload()
    {
        //check if option is enabled
        $isDeleteLocalBackupFeatureAvailable = SGBoot::isFeatureAvailable('DELETE_LOCAL_BACKUP_AFTER_UPLOAD');

        if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
            SGBackupMailNotification::sendBackupNotification(
                SG_ACTION_STATUS_FINISHED,
                array(
                    'flowFilePath' => dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME,
                    'archiveName'  => $this->_fileName
                )
            );
        }

        $status = SGBackup::getActionStatus($this->_actionId);

        if ($this->shouldDeleteBackupAfterUpload() && $isDeleteLocalBackupFeatureAvailable && $status == SG_ACTION_STATUS_FINISHED) {
            @unlink(SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.' . SGBP_EXT);
        }

        $this->log('Upload process completed', true);
    }

    private function clear()
    {
        @unlink(dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME);
        SGConfig::set("SG_CUSTOM_BACKUP_NAME", '');
    }

    private function cleanUp()
    {
        //delete sql file
        if ($this->_databaseBackupAvailable && file_exists($this->_databaseBackupPath)) {
            unlink($this->_databaseBackupPath);
        }

		$this->cleanUpDirectoryState();
    }

    private function getBackupFileName()
    {
        if (SGConfig::get("SG_CUSTOM_BACKUP_NAME")) {
            return backupGuardRemoveSlashes(SGConfig::get("SG_CUSTOM_BACKUP_NAME"));
        }

        $sgBackupPrefix = SG_BACKUP_FILE_NAME_DEFAULT_PREFIX;
        if (function_exists('backupGuardGetCustomPrefix')) {
            $sgBackupPrefix = backupGuardGetCustomPrefix();
        }

        $sgBackupPrefix .= backupGuardGetFilenameOptions($this->_options);

        $date = backupGuardConvertDateTimezone(@date('YmdHis'), true, 'YmdHis');

        return $sgBackupPrefix . ($date);
    }

    private function extendLogFileHeader($content)
    {
        $isManual = $this->getIsManual();
        if ($isManual) {
            $content .= 'Backup mode: Manual' . PHP_EOL;
        } else {
            $content .= 'Backup mode: Schedule' . PHP_EOL;
        }

        return $content;
    }

    private function prepareBackupLogFile($backupPath, $exists = false)
    {
        $file                 = $backupPath . '/' . $this->_fileName . '_backup.log';
        $this->_backupLogPath = $file;

        if (!$exists) {
            $isUpload = $this->getIsUploadStorage();

            $content = self::getLogFileHeader(SG_ACTION_TYPE_BACKUP, $this->_fileName, $isUpload);
            $content = $this->extendLogFileHeader($content);

            $types = array();
            if ($this->_filesBackupAvailable) {
                $types[] = 'files';
            }
            if ($this->_databaseBackupAvailable) {
                $types[] = 'database';
            }
            if (function_exists('sys_getloadavg') && sys_getloadavg() !== false) {
                $content .= 'CPU load at backup start: ' . implode(' / ', sys_getloadavg()) . PHP_EOL;
            }

            $content .= 'Backup type: ' . implode(',', $types) . PHP_EOL . PHP_EOL;

            if (!file_put_contents($file, $content)) {
                throw new SGExceptionMethodNotAllowed('Cannot create backup log file: ' . $file);
            }
        }
    }

    private function setBackupPaths()
    {
        $this->_filesBackupPath    = SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.sgbp';
        $this->_databaseBackupPath = SG_BACKUP_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.sql';
    }

    private function prepareUploadToStorages($options)
    {
        $uploadToStorages = $options['SG_BACKUP_UPLOAD_TO_STORAGES'];

        if (SGBoot::isFeatureAvailable('STORAGE') && $uploadToStorages) {
            $this->_pendingStorageUploads = explode(',', $uploadToStorages);
        }
    }

    private function prepareAdditionalConfigurations()
    {
        SGConfig::set('SG_RUNNING_ACTION', 1, true);
    }

    public function cancel()
    {
        $dir = SG_BACKUP_DIRECTORY . $this->_fileName;

        if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
            //Writing backup status to report file
            file_put_contents($dir . '/' . SG_REPORT_FILE_NAME, 'Backup: canceled', FILE_APPEND);
            SGBackupMailNotification::sendBackupNotification(
                SG_ACTION_STATUS_CANCELLED,
                array(
                    'flowFilePath' => dirname($this->_filesBackupPath) . '/' . SG_REPORT_FILE_NAME,
                    'archiveName'  => $this->_fileName
                )
            );
        }

        if ($dir != SG_BACKUP_DIRECTORY) {
            backupGuardDeleteDirectory($dir);
        }

        $this->clear();
        throw new SGExceptionSkip();
    }

    public function handleMigrationErrors($exception)
    {
        SGConfig::set('SG_BACKUP_SHOW_MIGRATION_ERROR', 1);
        SGConfig::set('SG_BACKUP_MIGRATION_ERROR', (string) $exception);
    }

    public function getActionId()
    {
        return $this->_actionId;
    }

    /* Restore implementation */

    public function restore($backupName, $id = null, $restoreMode = SG_RESTORE_MODE_FULL)
    {
        try {
			$backupName = backupGuardRemoveSlashes($backupName);
            $task       = new SGBGTask();
            $task->prepare(SG_BACKUP_DIRECTORY . $backupName . '/restore_state.json');
            $stateFile  = $task->getStateFile();
            $this->setStateFile($stateFile);
            $this->_fileName = $backupName;

            if ($this->getCurrentActionStatus() == SG_ACTION_STATUS_IN_PROGRESS_DB || $stateFile->getType() == SG_STATE_TYPE_DB) {
                die('busy');
            }

            if ($stateFile->getStatus() == SGBGStateFile::STATUS_READY) {
                $backupName = backupGuardRemoveSlashes($backupName);
                $this->prepareForRestore($backupName, $task, $id);
                if (SGExternalRestore::isEnabled()) {
                    $this->log('Start maintenance mode', true);
                }
                $this->log('Start restore', true);

                $stateFile->setBackupFileName($this->_fileName);
                $stateFile->setBackedUpTables(array());
                $stateFile->setAction(SG_STATE_ACTION_RESTORING_FILES);
                $stateFile->setType(SG_STATE_TYPE_FILE);
                $stateFile->setActionId($this->_actionId);
                $stateFile->setStartTs($this->_actionStartTs);
                $stateFile->setOffset(0);
                $stateFile->setRestoreMode($restoreMode);
                $stateFile->save(true);

				$this->_restoreMode = $restoreMode;
            } else if ($stateFile->getStatus() == SGBGStateFile::STATUS_RESUME) {
                $restorePath               = SG_BACKUP_DIRECTORY . $this->_fileName;
                $this->_filesBackupPath    = $restorePath . '/' . $this->_fileName . '.sgbp';
                $this->_databaseBackupPath = $restorePath . '/' . $this->_fileName . '.sql';
				$this->_databaseBackupOldPath = SG_BACKUP_OLD_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.sql';
                $this->prepareRestoreLogFile($restorePath, true);
                $this->_actionId           = $stateFile->getActionId();
                $this->_actionStartTs      = $stateFile->getStartTs();
                $this->_restoreMode        = $stateFile->getRestoreMode();

                $this->log('Resume restore', true);
            } else {
                die('busy');
            }

            $this->startRestore($this->_filesBackupPath, $task);

            $this->getLogFile()->getCache()->flush();

            $this->didFinishFilesRestore($task);

            $this->getLogFile()->getCache()->flush();

            $task->end(true);
        } catch (SGException $exception) {
            if (!$exception instanceof SGExceptionSkip) {
                $this->logException($exception, true);

                if ($exception instanceof SGExceptionMigrationError) {
                    $this->handleMigrationErrors($exception);
                }

                if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
                    SGBackupMailNotification::sendRestoreNotification(false);
                }

                self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_FINISHED_WARNINGS);
            } else {
                self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_CANCELLED);
            }
        }
    }

    private function prepareForRestore($backupName, $task, $id = null)
    {
        //prepare file name
        $this->_fileName = $backupName;

        //set paths
        $restorePath               = SG_BACKUP_DIRECTORY . $this->_fileName;
        $this->_filesBackupPath    = $restorePath . '/' . $this->_fileName . '.sgbp';
        $this->_databaseBackupPath = $restorePath . '/' . $this->_fileName . '.sql';
		$this->_databaseBackupOldPath = SG_BACKUP_OLD_DIRECTORY . $this->_fileName . '/' . $this->_fileName . '.sql';

		//create action inside db
		if (!$id) $this->_actionId = self::createAction($this->_fileName, SG_ACTION_TYPE_RESTORE, SG_ACTION_STATUS_IN_PROGRESS_FILES);


        //save current user credentials
        $this->saveCurrentUser();

        //check if we can run external restore
        $externalRestoreEnabled = SGExternalRestore::getInstance()->prepare($this->_actionId);
        //prepare folder
        $this->prepareRestoreFolder($restorePath);

        SGConfig::set('SG_RUNNING_ACTION', 1, true);

        //save timestamp for future use
        $this->_actionStartTs = time();
    }

    private function prepareRestoreFolder($restorePath)
    {
        if (!is_writable($restorePath)) {
            SGConfig::set('SG_BACKUP_NOT_WRITABLE_DIR_PATH', $restorePath);
            SGConfig::set('SG_BACKUP_SHOW_NOT_WRITABLE_ERROR', 1);
            throw new SGExceptionForbidden('Permission denied. Directory is not writable: ' . $restorePath);
        }

        $this->_filesBackupAvailable = file_exists($this->_filesBackupPath);

        //create restore log file
        $this->prepareRestoreLogFile($restorePath);
    }

    private function prepareRestoreLogFile($backupPath, $exists = false)
    {
        $file = $backupPath . '/' . $this->_fileName . '_restore.log';
        $this->setLogFile($file);
        $this->_restoreLogPath = $file;

        if (!$exists) {
            $content = self::getLogFileHeader(SG_ACTION_TYPE_RESTORE, $this->_fileName);

            $content .= PHP_EOL;

            if (!file_put_contents($file, $content)) {
                throw new SGExceptionMethodNotAllowed('Cannot create restore log file: ' . $file);
            }
        }
    }

    private function didFinishRestore()
    {
        if (SGExternalRestore::isEnabled()) {
            $this->log('Leaving maintenance mode', true);
        }

        if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
            SGBackupMailNotification::sendRestoreNotification(true);
        }

        $this->log('Memory peak usage ' . (memory_get_peak_usage(true) / 1024 / 1024) . 'MB', true);
        if (function_exists('sys_getloadavg') && sys_getloadavg() !== false) {
            $this->log('CPU usage ' . implode(' / ', sys_getloadavg()), true);
        }
        $this->log('Total duration ' . backupGuardFormattedDuration($this->_actionStartTs, time()), true);

        $this->cleanUp();
    }

	private function scanBackupsFolderForSqlFile($backupFolderPath)
	{
		$directory = new \RecursiveDirectoryIterator(
			$backupFolderPath,
			FilesystemIterator::FOLLOW_SYMLINKS|FilesystemIterator::SKIP_DOTS|FilesystemIterator::UNIX_PATHS
		);

		$iterator = new \RecursiveIteratorIterator(
			$directory,
			RecursiveIteratorIterator::SELF_FIRST,
			RecursiveIteratorIterator::CATCH_GET_CHILD
		);

		$iterator = new \LimitIterator($iterator, 0);
		foreach ($iterator as $info) {
			if (strpos($info->getFilename(), '.sql')) {
				return $info->getPathname();
			}
		}

		return null;
	}

    private function didFinishFilesRestore($task)
    {
        $this->_databaseBackupAvailable = file_exists($this->_databaseBackupPath);

		// also check old path to keep backward compatibility after rebranding
		if (!$this->_databaseBackupAvailable) {
			$this->_databaseBackupPath = $this->_databaseBackupOldPath;
			$this->_databaseBackupAvailable = file_exists($this->_databaseBackupPath);
		} 
		
		if (!$this->_databaseBackupAvailable) {
			$this->_databaseBackupPath = $this->scanBackupsFolderForSqlFile(SG_BACKUP_DIRECTORY);
			if (empty($this->_databaseBackupPath)) {
				$this->_databaseBackupPath = $this->scanBackupsFolderForSqlFile(SG_BACKUP_OLD_DIRECTORY);
			}

			$this->_databaseBackupAvailable = file_exists($this->_databaseBackupPath);
		}

        if ($this->_databaseBackupAvailable) {
            self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_IN_PROGRESS_DB);

            $stateFile = $this->getStateFile();

            $stateFile->setOffset(0);
            $stateFile->setCount(1);

            $stateFile->setAction(SG_STATE_ACTION_RESTORING_DATABASE);
            $stateFile->setType(SG_STATE_TYPE_DB);
            $stateFile->setStatus(SGBGStateFile::STATUS_STARTED);
            $stateFile->save(true);

            $this->restoreDB($this->_databaseBackupPath, $task);

            $this->getLogFile()->getCache()->flush();

            SGConfig::set('SG_RESTORE_FINALIZE', 1, true);
        }

        self::changeActionStatus($this->_actionId, SG_ACTION_STATUS_FINISHED);

        $this->didFinishRestore();
    }

    /* General methods */

    public static function getLogFileHeader($actionType, $fileName, $isUpload = false)
    {
        $pluginCapabilities = backupGuardGetCapabilities();
        $timezone           = SGConfig::get('SG_TIMEZONE') ? : SG_DEFAULT_TIMEZONE;

        $confs                            = array();
        $confs['sg_backup_guard_version'] = SG_BACKUP_GUARD_VERSION;
        $confs['sg_archive_version']      = SG_ARCHIVE_VERSION;
        $confs['sg_user_mode']            = ($pluginCapabilities != BACKUP_GUARD_CAPABILITIES_FREE) ? 'pro' : 'free'; // Check if user is pro or free
        $confs['os']                      = PHP_OS;
        $confs['php_version']             = PHP_VERSION;
        $confs['sapi']                    = PHP_SAPI;
        $confs['mysql_version']           = SG_MYSQL_VERSION;
        $confs['int_size']                = PHP_INT_SIZE;
        $confs['method']                  = backupGuardIsReloadEnabled() ? 'ON' : 'OFF';
        $confs['dbprefix']                = SG_ENV_DB_PREFIX;
        $confs['siteurl']                 = SG_SITE_URL;
        $confs['homeurl']                 = SG_HOME_URL;
        $confs['uploadspath']             = SG_UPLOAD_PATH;
        $confs['installation']            = SG_SITE_TYPE;
        $freeSpace                        = backupGuardDiskFreeSize(SG_APP_ROOT_DIRECTORY);
        $confs['free_space']              = $freeSpace == false ? 'unknown' : $freeSpace;
        $isCurlAvailable                  = function_exists('curl_version');
        $confs['curl_available']          = $isCurlAvailable ? 'Yes' : 'No';
        $confs['email_notifications']     = SGConfig::get('SG_NOTIFICATIONS_ENABLED') ? 'ON' : 'OFF';
        $confs['ftp_passive_mode']        = SGConfig::get('SG_FTP_PASSIVE_MODE') ? 'ON' : 'OFF';

        if (extension_loaded('gmp')) {
            $lib = 'gmp';
        } else if (extension_loaded('bcmath')) {
            $lib = 'bcmath';
        } else {
            $lib = 'BigInteger';
        }

        $confs['int_lib']            = $lib;
        $confs['memory_limit']       = SGBoot::$memoryLimit;
        $confs['max_execution_time'] = SGBoot::$executionTimeLimit;
        $confs['env']                = SG_ENV_ADAPTER . ' ' . SG_ENV_VERSION;
        $content                     = '';
        $content                     .= 'Date: ' . backupGuardConvertDateTimezone(@date('Y-m-d H:i'), true) . ' ' . $timezone . PHP_EOL;
        $content                     .= 'Reloads: ' . $confs['method'] . PHP_EOL;

        if ($actionType == SG_ACTION_TYPE_RESTORE) {
            $confs['restore_method'] = SGExternalRestore::isEnabled() ? 'external' : 'standard';
            $content                 .= 'Restore Method: ' . $confs['restore_method'] . PHP_EOL;
        }

        $content .= 'User mode: ' . backupGuardGetProductName() . PHP_EOL;
        $content .= 'JetBackup version: ' . $confs['sg_backup_guard_version'] . PHP_EOL;
        $content .= 'Supported archive version: ' . $confs['sg_archive_version'] . PHP_EOL;
        $content .= 'Database prefix: ' . $confs['dbprefix'] . PHP_EOL;
        $content .= 'Site URL: ' . $confs['siteurl'] . PHP_EOL;
        $content .= 'Home URL: ' . $confs['homeurl'] . PHP_EOL;
        $content .= 'Uploads path: ' . $confs['uploadspath'] . PHP_EOL;
        $content .= 'Site installation: ' . $confs['installation'] . PHP_EOL;
        $content .= 'OS: ' . $confs['os'] . PHP_EOL;
        $content .= 'PHP version: ' . $confs['php_version'] . PHP_EOL;
        $content .= 'MySQL version: ' . $confs['mysql_version'] . PHP_EOL;
        $content .= 'Int size: ' . $confs['int_size'] . PHP_EOL;
        $content .= 'Int lib: ' . $confs['int_lib'] . PHP_EOL;
        $content .= 'Memory limit: ' . $confs['memory_limit'] . PHP_EOL;
        $content .= 'Max execution time: ' . $confs['max_execution_time'] . PHP_EOL;
        $content .= 'Disk free space: ' . $confs['free_space'] . PHP_EOL;
        $content .= 'CURL available: ' . $confs['curl_available'] . PHP_EOL;
        $content .= 'Openssl version: ' . OPENSSL_VERSION_TEXT . PHP_EOL;

        if ($isCurlAvailable) {
            $cv              = curl_version();
            $curlVersionText = $cv['version'] . ' / SSL: ' . $cv['ssl_version'] . ' / libz: ' . $cv['libz_version'];
            $content         .= 'CURL version: ' . $curlVersionText . PHP_EOL;
        }

        $content .= 'Email notifications: ' . $confs['email_notifications'] . PHP_EOL;
        $content .= 'FTP passive mode: ' . $confs['ftp_passive_mode'] . PHP_EOL;
        $content .= 'Exclude paths: ' . SGConfig::get('SG_PATHS_TO_EXCLUDE') . PHP_EOL;
        $content .= 'Tables to exclude: ' . SGConfig::get('SG_TABLES_TO_EXCLUDE') . PHP_EOL;
        $content .= 'Number of rows to backup: ' . (int) SGConfig::get('SG_BACKUP_DATABASE_INSERT_LIMIT') . PHP_EOL;
        $content .= 'AJAX request frequency: ' . SGConfig::get('SG_AJAX_REQUEST_FREQUENCY') . PHP_EOL;

        if ($actionType == SG_ACTION_TYPE_BACKUP && $isUpload) {
            $content .= 'Upload chunk size: ' . SGConfig::get('SG_BACKUP_CLOUD_UPLOAD_CHUNK_SIZE') . 'MB' . PHP_EOL;
        }

        if ($actionType == SG_ACTION_TYPE_RESTORE) {
            $archivePath          = SG_BACKUP_DIRECTORY . $fileName . '/' . $fileName . '.sgbp';
            $archiveSizeInBytes   = backupGuardRealFilesize($archivePath);
            $confs['archiveSize'] = convertToReadableSize($archiveSizeInBytes);
            $content              .= 'Archive Size: ' . $confs['archiveSize'] . ' (' . $archiveSizeInBytes . ' bytes)' . PHP_EOL;
        }

        $content .= 'Environment: ' . $confs['env'] . PHP_EOL;

        return $content;
    }

    private function didFindWarnings()
    {
        $warningsDatabase = false;
        //$warningsDatabase = $this->_databaseBackupAvailable ? $this->_backupDatabase->didFindWarnings() : false;
        $warningsFiles = $this->getArchive()->didFindWarnings();

        return ($warningsFiles || $warningsDatabase);
    }

    public static function createAction($name, $type, $status, $subtype = 0, $options = '')
    {
        $sgdb = SGDatabase::getInstance();

        $date = backupGuardConvertDateTimezone(@date('Y-m-d H:i:s'), true);
        $res  = $sgdb->query('INSERT INTO ' . SG_ACTION_TABLE_NAME . ' (name, type, subtype, status, start_date, options) VALUES (%s, %d, %d, %d, %s, %s)', array(
            $name, $type, $subtype, $status, $date, $options
        ));

        if (!$res) {
            throw new SGExceptionDatabaseError('Could not create action');
        }

        return $sgdb->lastInsertId();
    }

    private function getCurrentActionStatus()
    {
        return self::getActionStatus($this->_actionId);
    }

    private function setCurrentActionStatusCancelled()
    {
        $sgdb = SGDatabase::getInstance();
        $date = backupGuardConvertDateTimezone(@date('Y-m-d H:i:s'), true);
        $sgdb->query('UPDATE ' . SG_ACTION_TABLE_NAME . ' SET status=%d, update_date=%s WHERE name=%s', array(
            SG_ACTION_STATUS_CANCELLED, $date, $this->_fileName
        ));
    }

    public static function changeActionStatus($actionId, $status)
    {
        $sgdb = SGDatabase::getInstance();

        $progress = '';
        if ($status == SG_ACTION_STATUS_FINISHED || $status == SG_ACTION_STATUS_FINISHED_WARNINGS) {
            $progress = 100;
        } else if ($status == SG_ACTION_STATUS_CREATED || $status == SG_ACTION_STATUS_IN_PROGRESS_FILES || $status == SG_ACTION_STATUS_IN_PROGRESS_DB) {
            $progress = 0;
        }

        if ($progress !== '') {
            $progress = ' progress=' . $progress . ',';
        }

        $date = backupGuardConvertDateTimezone(@date('Y-m-d H:i:s'), true);
        $sgdb->query('UPDATE ' . SG_ACTION_TABLE_NAME . ' SET status=%d,' . $progress . ' update_date=%s WHERE id=%d', array(
            $status, $date, $actionId
        ));
    }

    public static function changeActionProgress($actionId, $progress)
    {
        $sgdb = SGDatabase::getInstance();
        $date = backupGuardConvertDateTimezone(@date('Y-m-d H:i:s'), true);
        $sgdb->query('UPDATE ' . SG_ACTION_TABLE_NAME . ' SET progress=%d, update_date=%s WHERE id=%d', array(
            $progress, $date, $actionId
        ));
    }

    /* Methods for frontend use */

    public static function getAction($actionId)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT * FROM ' . SG_ACTION_TABLE_NAME . ' WHERE id=%d', array($actionId));
        if (empty($res)) {
            return false;
        }

        return $res[0];
    }

    public static function getActionByName($name)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT * FROM ' . SG_ACTION_TABLE_NAME . ' WHERE name=%s', array($name));
        if (empty($res)) {
            return false;
        }

        return $res[0];
    }

    public static function getActionProgress($actionId)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT progress FROM ' . SG_ACTION_TABLE_NAME . ' WHERE id=%d', array($actionId));
        if (empty($res)) {
            return false;
        }

        return (int) $res[0]['progress'];
    }

    public static function getActionStatus($actionId)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT status FROM ' . SG_ACTION_TABLE_NAME . ' WHERE id=%d', array($actionId));
        if (empty($res)) {
            return false;
        }

        return (int) $res[0]['status'];
    }

    public static function deleteActionById($actionId)
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('DELETE FROM ' . SG_ACTION_TABLE_NAME . ' WHERE id=%d', array($actionId));

        return $res;
    }

    public static function cleanRunningActions($runningActions)
    {
        if (empty($runningActions)) {
            return false;
        }
        foreach ($runningActions as $action) {
            if (empty($action)) {
                continue;
            }
            if ($action['status'] == SG_ACTION_STATUS_IN_PROGRESS_FILES || $action['status'] == SG_ACTION_STATUS_IN_PROGRESS_DB) {
                $id = $action['id'];
                SGBackup::deleteActionById($id);
            }
        }

        return true;
    }

    public static function getRunningActions()
    {
        $sgdb = SGDatabase::getInstance();
        $res  = $sgdb->query('SELECT * FROM ' . SG_ACTION_TABLE_NAME . ' WHERE status=%d OR status=%d OR status=%d ORDER BY status DESC', array(
            SG_ACTION_STATUS_IN_PROGRESS_FILES, SG_ACTION_STATUS_IN_PROGRESS_DB, SG_ACTION_STATUS_CREATED
        ));

        return $res;
    }

    public static function getBackupFileInfo($file)
    {
        return pathinfo(SG_BACKUP_DIRECTORY . $file);
    }

    public static function autodetectBackups()
    {
        $path              = SG_BACKUP_DIRECTORY;
        $files             = scandir(SG_BACKUP_DIRECTORY);
        $backupLogPostfix  = "_backup.log";
        $restoreLogPostfix = "_restore.log";

        foreach ($files as $file) {
            $fileInfo = self::getBackupFileInfo($file);

            if (!empty($fileInfo['extension']) && $fileInfo['extension'] == SGBP_EXT) {
                @mkdir($path . $fileInfo['filename'], 0777);

                if (file_exists($path . $fileInfo['filename'])) {
                    rename($path . $file, $path . $fileInfo['filename'] . '/' . $file);
                }

                if (file_exists($path . $fileInfo['filename'] . $backupLogPostfix)) {
                    rename($path . $fileInfo['filename'] . $backupLogPostfix, $path . $fileInfo['filename'] . '/' . $fileInfo['filename'] . $backupLogPostfix);
                }

                if (file_exists($path . $fileInfo['filename'] . $restoreLogPostfix)) {
                    rename($path . $fileInfo['filename'] . $restoreLogPostfix, $path . $fileInfo['filename'] . '/' . $fileInfo['filename'] . $restoreLogPostfix);
                }
            }
        }
    }

    public static function getAllBackups()
    {
        $backups = array();

        $path = SG_BACKUP_DIRECTORY;
        self::autodetectBackups();
        clearstatcache();

        $action = self::getRunningActions();
        if (SGBoot::isFeatureAvailable('NUMBER_OF_BACKUPS_TO_KEEP') && !count($action) && function_exists('backupGuardOutdatedBackupsCleanup')) {
            backupGuardOutdatedBackupsCleanup($path);
        }

        //remove external restore file
        SGExternalRestore::getInstance()->cleanup();

        if ($handle = @opendir($path)) {
            $sgdb       = SGDatabase::getInstance();
            $data       = $sgdb->query('SELECT id, name, type, subtype, status, progress, update_date, options FROM ' . SG_ACTION_TABLE_NAME);
            $allBackups = array();
            foreach ($data as $row) {
                $allBackups[$row['name']][] = $row;
            }

            while (($entry = readdir($handle)) !== false) {
                if ($entry === '.' || $entry === '..' || !is_dir($path . $entry)) {
                    continue;
                }

                $backup                = array();
                $backup['name']        = $entry;
                $backup['id']          = '';
                $backup['status']      = '';
                $backup['files']       = file_exists($path . $entry . '/' . $entry . '.sgbp') ? 1 : 0;
                $backup['backup_log']  = file_exists($path . $entry . '/' . $entry . '_backup.log') ? 1 : 0;
                $backup['restore_log'] = file_exists($path . $entry . '/' . $entry . '_restore.log') ? 1 : 0;
                $backup['options']     = '';
                if (!$backup['files'] && !$backup['backup_log'] && !$backup['restore_log']) {
                    continue;
                }
                $backupRow = null;
                if (isset($allBackups[$entry])) {
                    $skip = false;
                    foreach ($allBackups[$entry] as $row) {
                        if ($row['status'] == SG_ACTION_STATUS_IN_PROGRESS_FILES || $row['status'] == SG_ACTION_STATUS_IN_PROGRESS_DB) {
                            $backupRow = $row;
                            break;
                        } else if (($row['status'] == SG_ACTION_STATUS_CANCELLING || $row['status'] == SG_ACTION_STATUS_CANCELLED) && $row['type'] != SG_ACTION_TYPE_UPLOAD) {
                            $skip = true;
                            break;
                        }

                        $backupRow = $row;

                        if ($row['status'] == SG_ACTION_STATUS_FINISHED_WARNINGS || $row['status'] == SG_ACTION_STATUS_ERROR) {
                            if ($row['type'] == SG_ACTION_TYPE_UPLOAD && file_exists(SG_BACKUP_DIRECTORY . $entry . '/' . $entry . '.sgbp')) {
                                $backupRow['status'] = SG_ACTION_STATUS_FINISHED_WARNINGS;
                            }
                        }
                    }

                    if ($skip === true) {
                        continue;
                    }
                }

                if ($backupRow) {
                    $backup['active'] = ($backupRow['status'] == SG_ACTION_STATUS_IN_PROGRESS_FILES ||
                                         $backupRow['status'] == SG_ACTION_STATUS_IN_PROGRESS_DB ||
                                         $backupRow['status'] == SG_ACTION_STATUS_CREATED) ? 1 : 0;

                    $backup['status']   = $backupRow['status'];
                    $backup['type']     = (int) $backupRow['type'];
                    $backup['subtype']  = (int) $backupRow['subtype'];
                    $backup['progress'] = (int) $backupRow['progress'];
                    $backup['id']       = (int) $backupRow['id'];
                    $backup['options']  = $backupRow['options'];
                } else {
                    $backup['active'] = 0;
                }

                $size = '';
                if ($backup['files']) {
                    $size = number_format(backupGuardRealFilesize($path . $entry . '/' . $entry . '.sgbp') / 1000.0 / 1000.0, 2, '.', '') . ' MB';
                }

                $backup['size'] = $size;

                $modifiedTime           = filemtime($path . $entry . '/.');
                $date                   = backupGuardConvertDateTimezone(@date('Y-m-d H:i', $modifiedTime));
                $backup['date']         = $date;
                $backup['modifiedTime'] = $modifiedTime;
                $backups[]              = $backup;
            }
            closedir($handle);
        }

        usort($backups, array('SGBackup', 'sort'));

        return array_values($backups);
    }

    public static function sort($arg1, $arg2)
    {
        return $arg1['modifiedTime'] > $arg2['modifiedTime'] ? -1 : 1;
    }

    public static function deleteBackup($backupName, $deleteAction = true)
    {
        $isDeleteBackupFromCloudEnabled = SGConfig::get('SG_DELETE_BACKUP_FROM_CLOUD');
        if ($isDeleteBackupFromCloudEnabled) {
            $backupRow = self::getActionByName($backupName);
            if ($backupRow) {
                $options = $backupRow['options'];
                if ($options) {
                    $options = json_decode($options, true);

                    if (!empty($options['SG_BACKUP_UPLOAD_TO_STORAGES'])) {
                        $storages = explode(',', $options['SG_BACKUP_UPLOAD_TO_STORAGES']);
                        self::deleteBackupFromCloud($storages, $backupName);
                    }
                }
            }
        }

        backupGuardDeleteDirectory(SG_BACKUP_DIRECTORY . $backupName);

        if ($deleteAction) {
            $sgdb = SGDatabase::getInstance();
            $sgdb->query('DELETE FROM ' . SG_ACTION_TABLE_NAME . ' WHERE name=%s', array($backupName));
        }
    }

    private static function deleteBackupFromCloud($storages, $backupName)
    {
        foreach ($storages as $storage) {
            $storage = (int) $storage;

            $sgBackupStorage = SGBackupStorage::getInstance();
            $sgBackupStorage->deleteBackupFromStorage($storage, $backupName);
        }
    }

    public static function cancelAction($actionId)
    {
        self::changeActionStatus($actionId, SG_ACTION_STATUS_CANCELLING);
    }

    public static function importKeyFile($sgSshKeyFile)
    {
        $filename   = $sgSshKeyFile['name'];
        $uploadPath = SG_BACKUP_DIRECTORY . SG_SSH_KEY_FILE_FOLDER_NAME;
        $filename   = $uploadPath . $filename;

        if (!@file_exists($uploadPath)) {
            if (!@mkdir($uploadPath)) {
                throw new SGExceptionForbidden('SSH key file folder is not accessible');
            }
        }

        if (!empty($sgSshKeyFile) && $sgSshKeyFile['name'] != '') {
            if (!@move_uploaded_file($sgSshKeyFile['tmp_name'], $filename)) {
                throw new SGExceptionForbidden('Error while uploading ssh key file');
            }
        }
    }

    public static function upload($filesUploadSgbp)
    {
        $filename        = str_replace('.sgbp', '', $filesUploadSgbp['name']);
        $backupDirectory = $filename . '/';
        $uploadPath      = SG_BACKUP_DIRECTORY . $backupDirectory;
        $filename        = $uploadPath . $filename;

        if (!@file_exists($uploadPath)) {
            if (!@mkdir($uploadPath)) {
                throw new SGExceptionForbidden('Upload folder is not accessible');
            }
        }

        if (!empty($filesUploadSgbp) && $filesUploadSgbp['name'] != '') {
            if ($filesUploadSgbp['type'] != 'application/octet-stream') {
                throw new SGExceptionBadRequest('Not a valid backup file');
            }
            if (!@move_uploaded_file($filesUploadSgbp['tmp_name'], $filename . '.sgbp')) {
                throw new SGExceptionForbidden('Error while uploading file');
            }
        }
    }

    public static function download($filename, $type)
    {
        $backupDirectory = SG_BACKUP_DIRECTORY . $filename . '/';
        $downloadMode    = SGConfig::get('SG_DOWNLOAD_MODE');

        switch ($type) {
            case SG_BACKUP_DOWNLOAD_TYPE_SGBP:
                $filename .= '.sgbp';
                if ($downloadMode == 1) {
                    backupGuardDownloadFile($backupDirectory . $filename);
                } else {
                    backupGuardDownloadFileViaFunction($backupDirectory, $filename, $downloadMode);
                }
                break;
            case SG_BACKUP_DOWNLOAD_TYPE_BACKUP_LOG:
                $filename .= '_backup.log';
                backupGuardDownloadFile($backupDirectory . $filename, 'text/plain');
                break;
            case SG_BACKUP_DOWNLOAD_TYPE_RESTORE_LOG:
                $filename .= '_restore.log';
                backupGuardDownloadFile($backupDirectory . $filename, 'text/plain');
                break;
        }

        exit;
    }

    public function isCancelled()
    {
        $status = $this->getCurrentActionStatus();

        if ($status == SG_ACTION_STATUS_CANCELLING) {
            $this->cancel();

            return true;
        }

        return false;
    }

    public function didUpdateProgress($progress)
    {
        $progress = max($progress, 0);
        $progress = min($progress, 100);

        self::changeActionProgress($this->_actionId, $progress);
    }

    public function isBackgroundMode()
    {
        return $this->_backgroundMode;
    }

    public function setIsManual($isManual)
    {
        $this->_isManual = $isManual;
    }

    public function getIsManual()
    {
        return $this->_isManual;
    }

    public function getIsUploadStorage()
    {
        if (empty($this->_options['SG_BACKUP_UPLOAD_TO_STORAGES'])) {
            return false;
        }

        $uploadToStoragesString = $this->_options['SG_BACKUP_UPLOAD_TO_STORAGES'];

        $uploadToStorages = explode(',', $uploadToStoragesString);
        if (count($uploadToStorages)) {
            return true;
        }

        return false;
    }

    public function willAddFile($filename)
    {
        return true;
    }

    public function didAddFile($filename)
    {
        return true;
    }

    public function getCorrectCdrFilename($filename)
    {
        return $filename;
    }

    public function didCountFilesInsideArchive($count)
    {
    }

    public function shouldExtractFile($filePath)
    {
		if ($this->_restoreMode == SG_RESTORE_MODE_DB && !strpos($filePath, '/'.SG_BACKUP_DEFAULT_FOLDER_NAME.'/') && !strpos($filePath, '/'.SG_BACKUP_OLD_FOLDER_NAME.'/')) {
			return false;
		} else if ($this->_restoreMode == SG_RESTORE_MODE_FILES && ($filePath == $this->_databaseBackupPath || $filePath == $this->_databaseBackupOldPath)) {
			return false;
		}

        return true;
    }

    public function willExtractFile($filePath)
    {
    }

    public function didExtractFile($filePath)
    {
    }

    public function didFindExtractError($error)
    {
    }

    public function didExtractArchiveHeaders($version, $extra)
    {
        SGConfig::set('SG_OLD_SITE_URL', $extra['siteUrl']);
        SGConfig::set('SG_OLD_DB_PREFIX', $extra['dbPrefix']);

        if (isset($extra['phpVersion'])) {
            SGConfig::set('SG_OLD_PHP_VERSION', $extra['phpVersion']);
        }

        SGConfig::set('SG_BACKUPED_TABLES', json_encode($extra['tables']));
        SGConfig::set('SG_BACKUP_TYPE', $extra['method']);

        SGConfig::set('SG_MULTISITE_OLD_PATH', $extra['multisitePath']);
        SGConfig::set('SG_MULTISITE_OLD_DOMAIN', $extra['multisiteDomain']);
    }

    public function willAddFileChunk($filename)
    {
        return true;
    }

    public function didAddFileChunk($filename, $chunk)
    {
        return true;
    }

    private function resetBackupProgress()
    {
        $this->_totalRowCount   = 0;
        $this->_currentRowCount = 0;
        $tableNames             = $this->getTables();
        foreach ($tableNames as $table) {
            $this->_totalRowCount += $this->getTableRowsCount($table);
        }
    }

    private function getTables()
    {
        $tableNames = array();
        $tables     = $this->_sgdb->query('SHOW TABLES FROM `' . SG_DB_NAME . '`');
        if (!$tables) {
            throw new SGExceptionDatabaseError('Could not get tables of database: ' . SG_DB_NAME);
        }
        foreach ($tables as $table) {
            $tableName       = $table['Tables_in_' . SG_DB_NAME];
            $tablesToExclude = explode(',', SGConfig::get('SG_BACKUP_DATABASE_EXCLUDE'));
            if (in_array($tableName, $tablesToExclude)) {
                continue;
            }
            $tableNames[] = $tableName;
        }

        return $tableNames;
    }

    private function getTableRowsCount($tableName)
    {
        $count        = 0;
        $tableRowsNum = $this->_sgdb->query('SELECT COUNT(*) AS total FROM ' . $tableName);
        $count        = @$tableRowsNum[0]['total'];

        return $count;
    }

    public function addDontExclude($ex)
    {
        $this->_dontExclude[] = $ex;
    }

    public function startUploadByActionId($task, $actionId, $storageName = '')
    {
        $this->setStateFile($task->getStateFile());
        $this->setRowsCount(1);
        $task->start(1);
        $task->getStateFile()->getCache()->flush();

        if (!$task->getStateFile()->getAction()) {
            $task->getStateFile()->setAction(SG_STATE_ACTION_PREPARING_STATE_FILE);
        }

        $res = $this->_sgdb->query('SELECT * FROM ' . SG_ACTION_TABLE_NAME . ' WHERE id=%d LIMIT 1', array($actionId));
        $row = $res[0];

        if (!count($res)) {
            return false;
        }

        if ($row['type'] != SG_ACTION_TYPE_UPLOAD) {
            return false;
        }

        $this->_actionId = $actionId;
        $type            = $row['subtype'];
        $backupName      = $row['name'];

        if ($this->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {

        } else {
            $this->_nextProgressUpdate       = $this->getStateFile()->getProgress() ? $this->getStateFile()->getProgress() : $this->_progressUpdateInterval;
            $this->_actionId                 = $this->getStateFile()->getActionId();
            $this->_currentUploadChunksCount = $this->getStateFile()->getCurrentUploadChunksCount();
            $type                            = $row['subtype'];
            $backupName                      = $row['name'];
        }

        $storage = $this->storageObjectById($type, $storageName);

        $this->startBackupUpload($task, $backupName, $storage, $storageName);
        return true;
    }

    private function storageObjectById($storageId, &$storageName = '')
    {
        $res              = $this->getStorageInfoById($storageId);
        $storageName      = $res['storageName'];
        $storageClassName = $res['storageClassName'];

        if (!$storageClassName) {
            throw new SGExceptionNotFound('Unknown storage');
        }

        return new $storageClassName();
    }

    public function getStorageInfoById($storageId)
    {
        $storageName      = '';
        $storageClassName = '';
        $storageId        = (int) $storageId;
        $isConnected      = true;

        switch ($storageId) {
            case SG_STORAGE_FTP:
                if (SGBoot::isFeatureAvailable('FTP')) {
                    $connectionMethod = SGConfig::get('SG_STORAGE_CONNECTION_METHOD');

                    if ($connectionMethod == 'ftp') {
                        $storageName = 'FTP';
                    } else {
                        $storageName = 'SFTP';
                    }
                    $isFtpConnected = SGConfig::get('SG_STORAGE_FTP_CONNECTED');

                    if (empty($isFtpConnected)) {
                        $isConnected = false;
                    }
                    $storageClassName = "SGFTPManager";
                }
                break;
            case SG_STORAGE_DROPBOX:
                if (SGBoot::isFeatureAvailable('DROPBOX')) {
                    $storageName      = 'Dropbox';
                    $storageClassName = "SGDropboxStorage";
                }
                $isDropboxConnected = SGConfig::get('SG_DROPBOX_ACCESS_TOKEN');

                if (empty($isDropboxConnected)) {
                    $isConnected = false;
                }
                break;
            case SG_STORAGE_GOOGLE_DRIVE:
                if (SGBoot::isFeatureAvailable('GOOGLE_DRIVE')) {
                    $storageName      = 'Google Drive';
                    $storageClassName = "SGGoogleDriveStorage";
                }
                $isGdriveConnected = SGConfig::get('SG_GOOGLE_DRIVE_REFRESH_TOKEN');

                if (empty($isGdriveConnected)) {
                    $isConnected = false;
                }
                break;
            case SG_STORAGE_AMAZON:
                if (SGBoot::isFeatureAvailable('AMAZON')) {
                    $storageName      = 'Amazon S3';
                    $storageClassName = "SGAmazonStorage";
                }
                $isAmazonConnected = SGConfig::get('SG_STORAGE_AMAZON_CONNECTED');

                if (empty($isAmazonConnected)) {
                    $isConnected = false;
                }
                break;
            case SG_STORAGE_ONE_DRIVE:
                if (SGBoot::isFeatureAvailable('ONE_DRIVE')) {
                    $storageName      = 'One Drive';
                    $storageClassName = "SGOneDriveStorage";
                }
                $isOneDriveConnected = SGConfig::get('SG_ONE_DRIVE_REFRESH_TOKEN');

                if (empty($isOneDriveConnected)) {
                    $isConnected = false;
                }
                break;
            case SG_STORAGE_P_CLOUD:
                if (SGBoot::isFeatureAvailable('P_CLOUD')) {
                    $storageName      = 'pCloud';
                    $storageClassName = "SGPCloudStorage";
                }

                $isPCloudConnected = SGConfig::get('SG_P_CLOUD_ACCESS_TOKEN');

                if (empty($isPCloudConnected)) {
                    $isConnected = false;
                }
                break;
            case SG_STORAGE_BOX:
                if (SGBoot::isFeatureAvailable('BOX')) {
                    $storageName      = 'box.com';
                    $storageClassName = "SGBoxStorage";
                }

                $isBoxConnected = SGConfig::get('SG_BOX_REFRESH_TOKEN');

                if (empty($isBoxConnected)) {
                    $isConnected = false;
                }
                break;
        }

        $res = array(
            'storageName'      => $storageName,
            'storageClassName' => $storageClassName,
            'isConnected'      => $isConnected,
        );

        return $res;
    }

    private function startBackupUpload($task, $backupName, SGStorage $storage, $storageName)
    {
        $state         = $task->getStateFile();

        if ($task->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {
            $actionStartTs = time();
        } else {
            $actionStartTs = $task->getStateFile()->getStartTs();
        }

        $backupPath      = SG_BACKUP_DIRECTORY . $backupName;
        $filesBackupPath = $backupPath . '/' . $backupName . '.sgbp';

        if (!is_readable($filesBackupPath)) {
            SGBackup::changeActionStatus($this->_actionId, SG_ACTION_STATUS_ERROR);
            throw new SGExceptionNotFound('Backup not found');
        }

        try {
            @session_write_close();

            if ($task->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {
                SGBackup::changeActionStatus($this->_actionId, SG_ACTION_STATUS_IN_PROGRESS_FILES);

                $this->log('Start upload to ' . $storageName, true);
                $this->log('Authenticating', true);
            }

            $storage->setDelegate($this);
            $storage->connectOffline();

            //get backups container folder
            $backupsFolder = $task->getStateFile()->getActiveDirectory();

            if ($task->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {
                $this->log('Preparing folder', true);

                $folderTree = SG_BACKUP_DEFAULT_FOLDER_NAME;

                if (SGBoot::isFeatureAvailable('SUBDIRECTORIES')) {
                    $folderTree = SGConfig::get('SG_STORAGE_BACKUPS_FOLDER_NAME');
                }

                //create backups container folder, if needed
                $backupsFolder = $storage->createFolder($folderTree);
            }

            $storage->setActiveDirectory($backupsFolder);

            if ($task->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {
                $this->log('Uploading file', true);
            }
            $this->getLogFile()->getCache()->flush();
            $storage->uploadFile($filesBackupPath, $task);


            $this->log('Upload to ' . $storageName . ' end', true);

            //Writing upload status to report file
            file_put_contents($backupPath . '/' . SG_REPORT_FILE_NAME, 'Uploaded to ' . $storageName . ": completed\n", FILE_APPEND);
            $this->log('Total duration: ' . backupGuardFormattedDuration($actionStartTs, time()), true);

            $this->getLogFile()->getCache()->flush();

            SGBackup::changeActionStatus($this->_actionId, SG_ACTION_STATUS_FINISHED);
        } catch (Exception $exception) {
            if ($exception instanceof SGExceptionSkip) {
                SGBackup::changeActionStatus($this->_actionId, SG_ACTION_STATUS_CANCELLED);
                //Writing upload status to report file
                file_put_contents($backupPath . '/' . SG_REPORT_FILE_NAME, 'Uploaded to ' . $storageName . ': canceled', FILE_APPEND);
                SGBackupMailNotification::sendBackupNotification(
                    SG_ACTION_STATUS_CANCELLED,
                    array(
                        'flowFilePath' => $backupPath . '/' . SG_REPORT_FILE_NAME,
                        'archiveName'  => $backupName
                    )
                );
            } else {
                SGBackup::changeActionStatus($this->_actionId, SG_ACTION_STATUS_FINISHED_WARNINGS);

                if (!$exception instanceof SGExceptionExecutionTimeError) {//to prevent log duplication for timeout exception
                    $this->logException($exception, true);
                }

                if (SGBoot::isFeatureAvailable('NOTIFICATIONS')) {
                    //Writing upload status to report file
                    file_put_contents($backupPath . '/' . SG_REPORT_FILE_NAME, 'Uploaded to ' . $storageName . ': failed', FILE_APPEND);
                    SGBackupMailNotification::sendBackupNotification(
                        SG_ACTION_STATUS_ERROR,
                        array(
                            'flowFilePath' => $backupPath . '/' . SG_REPORT_FILE_NAME,
                            'archiveName'  => $backupName
                        )
                    );
                }
            }

            //delete file inside storage
            $storageId = $state->getStorageType();
            $this->deleteBackupFromStorage($storageId, $backupName);

            //delete report file in case of error
            @unlink($backupPath . '/' . SG_REPORT_FILE_NAME);
        }
    }

    public function deleteBackupFromStorage($storageId, $backupName)
    {
        try {
            $uploadFolder = trim(SGConfig::get('SG_STORAGE_BACKUPS_FOLDER_NAME'), '/');

            $storage = $this->storageObjectById($storageId);
            $path    = "/" . $uploadFolder . "/" . $backupName . ".sgbp";

            if ($storage) {
                $storage->deleteFile($path);
            }
        } catch (Exception $e) {
        }
    }

    public function willStartUpload($chunksCount)
    {
        $this->_totalUploadChunksCount = $chunksCount;

        if ($this->getStateFile()->getAction() == SG_STATE_ACTION_PREPARING_STATE_FILE) {
            $this->resetUploadProgress();
        }
    }

    public function shouldUploadNextChunk()
    {
        $this->_currentUploadChunksCount++;

        if ($this->updateUploadProgress()) {
            $this->checkCancellation();
        }

        return true;
    }

    private function updateUploadProgress($progress = null)
    {

        if (!$progress && $this->_totalUploadChunksCount > 0) {
            $progress = (int) ceil($this->_currentUploadChunksCount * 100.0 / $this->_totalUploadChunksCount);
        }

        if ($progress >= $this->_nextProgressUpdate) {
            $this->_nextProgressUpdate += $this->_progressUpdateInterval;

            $progress = max($progress, 0);
            $progress = min($progress, 100);
            SGBackup::changeActionProgress($this->_actionId, $progress);
            return true;
        }

        return false;
    }

    public function updateUploadProgressManually($progress)
    {
        if ($this->updateUploadProgress($progress)) {
            $this->checkCancellation();
        }

        return true;
    }

    private function resetUploadProgress()
    {
        $this->_currentUploadChunksCount = 0;
        $this->_nextProgressUpdate       = $this->_progressUpdateInterval;
    }

    private function checkCancellation()
    {
        $status = SGBackup::getActionStatus($this->_actionId);

        if ($status == SG_ACTION_STATUS_CANCELLING) {
            $this->log('Upload cancelled', true);
            throw new SGExceptionSkip();
        } else if ($status == SG_ACTION_STATUS_ERROR) {
            $this->log('Upload timeout error', true);
            throw new SGExceptionExecutionTimeError();
        }
    }

    public function getPendingStorageUploads()
    {
        return $this->_pendingStorageUploads;
    }

    public function getCurrentUploadChunksCount()
    {
        return $this->_currentUploadChunksCount;
    }

    public function getProgress()
    {
        return $this->getStateFile()->getProgress();
    }

    public function saveCurrentUser()
    {
        if (SG_ENV_ADAPTER != SG_ENV_WORDPRESS) {
            return;
        }

        $user = wp_get_current_user();

        $currentUser = serialize(
            array(
                'login' => $user->user_login,
                'pass'  => $user->user_pass,
                'email' => $user->user_email,
            )
        );

        SGConfig::set('SG_CURRENT_USER', $currentUser, true, false);
    }

    public function startRestore($filePath, $task)
    {
        $state                = $task->getStateFile();
        $this->_warningsFound = false;

        $this->extractArchive($filePath, $task);
    }

    private function extractArchive($filePath, $task)
    {
        $rootDirectory = rtrim(SGConfig::get('SG_APP_ROOT_DIRECTORY'), '/') . '/';
        $restorePath   = $rootDirectory;

        $archive = new SGBGArchive($filePath, 'r');
        $archive->setTask($task);
        $archive->setDelegate($this);
        $archive->setLogEnabled(false);
        $archive->setLogFile($this->getLogFile());
        $archive->getCache()->setCacheMode(SGBGCache::CACHE_MODE_TIMEOUT | SGBGCache::CACHE_MODE_SIZE);
        $archive->getCache()->setCacheTimeout(5);
        $archive->getCache()->setCacheSize(4000000);

        $this->_archive = $archive;

        $archive->open('r');
        $archive->extractTo($restorePath);
    }

    public function restoreDB($filePath, $task)
    {
        $this->_backupFilePath = $filePath;

        $sgDBState = $task->getStateFile();

        if ($sgDBState && $sgDBState->getType() == SG_STATE_TYPE_DB) {
            $this->log('Start restore database', true);
            $this->_actionStartTs = time();
            //prepare for restore (reset variables)
            //$this->resetRestoreProgress();

            //import all db tables
            $this->importDB($task);
        }

        //run migration logic
        if ($this->isMigrationAvailable()) {
            $this->processDBMigration();
        }

        $this->log('End restore database', true);
        $this->log('Restore database total duration ' . backupGuardFormattedDuration($this->_actionStartTs, time()), true);
    }

    private function processDBMigration()
    {
        $this->log('Start migration', true);

        $sgMigrate = new SGMigrate($this->_sgdb);
        $sgMigrate->setDelegate($this);

        $tables = $this->getTables();

        $oldSiteUrl = SGConfig::get('SG_OLD_SITE_URL');

        // Find and replace old urls with new ones
        $sgMigrate->migrate($oldSiteUrl, SG_SITE_URL, $tables);

        // Find and replace old db prefixes with new ones
        $sgMigrate->migrateDBPrefix();

        $isMultisite = backupGuardIsMultisite();
        if ($isMultisite) {
            $tables = explode(',', SG_MULTISITE_TABLES_TO_MIGRATE);

            $oldPath   = SGConfig::get('SG_MULTISITE_OLD_PATH');
            $newPath   = PATH_CURRENT_SITE;
            $newDomain = DOMAIN_CURRENT_SITE;

            $sgMigrate->migrateMultisite($newDomain, $newPath, $oldPath, $tables);
        }

        $this->log('End migration', true);
    }

    private function importDB($task)
    {
        $fileHandle = @fopen($this->_backupFilePath, 'r');
        if (!is_resource($fileHandle)) {
            throw new SGExceptionForbidden('Could not open file: ' . $this->_backupFilePath);
        }

        $importQuery = $this->getDatabaseHeaders();

        while (($row = @fgets($fileHandle)) !== false) {

            $importQuery .= $row;
            $trimmedRow  = trim($row);

            if (strpos($trimmedRow, 'CREATE TABLE') !== false) {
                $strLength   = strlen($trimmedRow);
                $strCtLength = strlen('CREATE TABLE ');
                $length      = $strLength - $strCtLength - 2;
                $tableName   = substr($trimmedRow, $strCtLength, $length);

                $this->log('Importing table ' . $tableName, true);
            }
            if ($trimmedRow && substr($trimmedRow, -9) == "/*SGEnd*/") {
                $queries = explode("/*SGEnd*/" . PHP_EOL, $importQuery);
                foreach ($queries as $query) {
                    if (!$query) {
                        continue;
                    }

                    $importQuery = $this->prepareQueryToExec($query);
                    $res         = $this->_sgdb->execRaw($importQuery);

                    if ($res === false) {
                        //continue restoring database if any query fails
                        //we will just show a warning inside the log

                        if (isset($tableName)) {
                            $this->warn('Could not import table: ' . $tableName);
                        }

                        $this->warn('Query: ' . $importQuery);
                        $this->warn($this->_sgdb->getLastError());
                    }
                }
                $importQuery = '';
            }
        }

        @fclose($fileHandle);
    }

    public function isMigrationAvailable()
    {
        if ($this->_migrationAvailable === null) {
            $this->_migrationAvailable = SGBoot::isFeatureAvailable('BACKUP_WITH_MIGRATION');
        }

        return $this->_migrationAvailable;
    }

    private function getDatabaseHeaders()
    {
        return "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;/*SGEnd*/" . PHP_EOL .
               "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;/*SGEnd*/" . PHP_EOL .
               "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;/*SGEnd*/" . PHP_EOL .
               "/*!40101 SET NAMES " . SG_DB_CHARSET . " */;/*SGEnd*/" . PHP_EOL .
               "/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;/*SGEnd*/" . PHP_EOL .
               "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;/*SGEnd*/" . PHP_EOL .
               "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;/*SGEnd*/" . PHP_EOL .
               "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;/*SGEnd*/" . PHP_EOL;
    }

    public function warn($message)
    {
        $this->_warningsFound = true;
        $this->log('Warning։ ' . $message, true);
    }

    public function finalizeDBRestore()
    {
		return;
        //recreate current user (to be able to login with it)
        $this->restoreCurrentUser();

        //setting the following options will tell WordPress that the db is already updated
        global $wp_db_version;
        update_option('db_version', $wp_db_version);
        update_option('db_upgraded', true);

        //fix invalid upload path inserted in db
        update_option("upload_path", "");
    }

    private function restoreCurrentUser()
    {

		return;

        $currentUser = SGConfig::get('SG_CURRENT_USER');
        $user        = unserialize($currentUser);

        //erase user data from the config table
        SGConfig::set('SG_CURRENT_USER', '');

        //if a user is found, it means it's cache, because we have dropped wp_users already
        $cachedUser = get_user_by('login', $user['login']);
        if ($cachedUser) {
            clean_user_cache($cachedUser); //delete user from cache
        }

        //create a user (it will be a subscriber)
        $id = wp_create_user($user['login'], $user['pass'], $user['email']);
        if (is_wp_error($id)) {
            //$this->log('User not recreated: ' . $id->get_error_message(), true);

            return false; //user was not created for some reason
        }

        //get the newly created user
        $newUser = get_user_by('id', $id);

        //remove its role of subscriber
        $newUser->remove_role('subscriber');
        $isMultisite = backupGuardIsMultisite();

        if ($isMultisite) {
            // add super adminn role
            grant_super_admin($id);
        } else {
            //add admin role
            $newUser->add_role('administrator');
        }

        //update password to set the correct (old) password
        $this->_sgdb->query(
            'UPDATE ' . SG_ENV_DB_PREFIX . 'users SET user_pass=%s WHERE ID=%d',
            array(
                $user['pass'],
                $id
            )
        );

        //clean cache, so new password can take effect
        clean_user_cache($newUser);

        //$this->log('User recreated: ' . $user['login'], true);
    }

    private function prepareQueryToExec($query)
    {
        $query = $this->replaceInvalidCharacters($query);
        $query = $this->replaceInvalidEngineTypeInQuery($query);

        if ($this->isMigrationAvailable()) {
            $tableNames    = $this->getBackedUpTables();
            $newTableNames = $this->getNewTableNames();
            $query         = $this->getMigrateObj()->replaceValuesInQuery($tableNames, $newTableNames, $query);
        }

        $query = $this->getCharsetHandler()->replaceInvalidCharsets($query);

        $query = rtrim(trim($query), "/*SGEnd*/");

        return $query;
    }

    private function replaceInvalidEngineTypeInQuery($query)
    {
        if (version_compare(SG_MYSQL_VERSION, '5.1', '>=')) {
            return str_replace("TYPE=InnoDB", "ENGINE=InnoDB", $query);
        } else {
            return str_replace("ENGINE=InnoDB", "TYPE=InnoDB", $query);
        }
    }

    private function replaceInvalidCharacters($str)
    {
        return $str;//preg_replace('/\x00/', '', $str);;
    }

    private function updateDBProgress()
    {
        $progress = round($this->_currentRowCount * 100.0 / $this->_totalRowCount);

        if ($progress >= $this->_nextProgressUpdate) {
            $this->_nextProgressUpdate += $this->_progressUpdateInterval;

            $this->didUpdateProgress($progress);

            return true;
        }

        return false;
    }

    private function resetRestoreProgress()
    {
        $this->_totalRowCount          = $this->getFileLinesCount($this->_backupFilePath);
        $this->_currentRowCount        = 0;
        $this->_progressUpdateInterval = SGConfig::get('SG_ACTION_PROGRESS_UPDATE_INTERVAL');
        $this->_nextProgressUpdate     = $this->_progressUpdateInterval;
    }

    private function getFileLinesCount($filePath)
    {
        $fileHandle = @fopen($filePath, 'rb');
        if (!is_resource($fileHandle)) {
            throw new SGExceptionForbidden('Could not open file: ' . $filePath);
        }

        $linecount = 0;
        while (!feof($fileHandle)) {
            $linecount += substr_count(fread($fileHandle, 8192), "\n");
        }

        @fclose($fileHandle);

        return $linecount;
    }

    public function getBackedUpTables()
    {
        if ($this->_backedUpTables === null) {
            $tableNames = backupGuardRemoveSlashes(SGConfig::get('SG_BACKUPED_TABLES'));
            if ($tableNames) {
                $tableNames = json_decode($tableNames, true);
                if (is_string($tableNames)) {
                    $tableNames = json_decode($tableNames, true);
                }
            } else {
                $tableNames = array();
            }
            $this->_backedUpTables = $tableNames;
        }

        return $this->_backedUpTables;
    }

    public function getNewTableNames()
    {
        if ($this->_newTableNames === null) {
            $oldDbPrefix = $this->getOldDbPrefix();
            $tableNames  = $this->getBackedUpTables();

            $newTableNames = array();
            foreach ($tableNames as $tableName) {
                $newTableNames[] = str_replace($oldDbPrefix, SG_ENV_DB_PREFIX, $tableName);
            }
            $this->_newTableNames = $newTableNames;
        }

        return $this->_newTableNames;
    }

    public function getOldDbPrefix()
    {
        if ($this->_oldDbPrefix === null) {
            $this->_oldDbPrefix = SGConfig::get('SG_OLD_DB_PREFIX');
        }

        return $this->_oldDbPrefix;
    }

    public function getMigrateObj()
    {
        if ($this->_migrateObj === null) {
            $this->_migrateObj = new SGMigrate();
        }

        return $this->_migrateObj;
    }

    public function getCharsetHandler()
    {
        if ($this->_charsetHandler === null) {
            $this->_charsetHandler = new SGCharsetHandler();
        }

        return $this->_charsetHandler;
    }

    public function getArchiveExtraData()
    {
        $tables = SGConfig::get('SG_BACKUPED_TABLES');

        if ($tables) {
            $tables = json_encode($tables);
        } else {
            $tables = "";
        }

        $multisitePath   = "";
        $multisiteDomain = "";

        if (SG_ENV_ADAPTER == SG_ENV_WORDPRESS) {
            // in case of multisite save old path and domain for later usage
            if (is_multisite()) {
                $multisitePath   = PATH_CURRENT_SITE;
                $multisiteDomain = DOMAIN_CURRENT_SITE;
            }
        }

        //save db prefix, site and home url for later use
        $extra = json_encode(
            array(
                'siteUrl'             => get_site_url(),
                'home'                => get_home_url(),
                'dbPrefix'            => SG_ENV_DB_PREFIX,
                'tables'              => $tables,
                'method'              => SGConfig::get('SG_BACKUP_TYPE'),
                'multisitePath'       => $multisitePath,
                'multisiteDomain'     => $multisiteDomain,
                'selectivRestoreable' => true,
                'phpVersion'          => phpversion()
            )
        );

        return $extra;
    }

	public function cleanUpRestoreState($backupName)
	{
		if (file_exists(SG_BACKUP_DIRECTORY . $backupName . '/restore_state.json')) {
			unlink(SG_BACKUP_DIRECTORY . $backupName . '/restore_state.json');
		}
	}

}
