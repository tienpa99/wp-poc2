<?php
/*
@ class Task
@ version 1.0.1
@ updated 26/01/2021
*/

require_once(__DIR__.'/SGBGStateFile.php');
require_once(__DIR__.'/SGBGReloader.php');

class SGBGTask
{
	private $stateFile = null;

	public function getStateFile()
	{
		return $this->stateFile;
	}

	public function setStateFile($stateFile)
	{
		$this->stateFile = $stateFile;
	}

	private function prepareStateFile($stateFilePath)
	{
		$file = new SGBGStateFile($stateFilePath);
		$file->getCache()->setCacheMode(SGBGCache::CACHE_MODE_TIMEOUT);
		$file->getCache()->setCacheTimeout(10);
		$file->load();

		$this->setStateFile($file);
	}

	private function prepareReloader()
	{
		$reloader = SGBGReloader::getInstance();
		$reloader->setInterval(23);
		$reloader->setLastReloadTs((int)$this->getStateFile()->getData('last_reload_ts'));
	}

	private function prepareStateFileStatus($count)
	{
		$stateFile = $this->getStateFile();

		if ($stateFile->getStatus() == SGBGStateFile::STATUS_STARTED || $stateFile->getStatus() == SGBGStateFile::STATUS_BUSY) {
			die; //the task is already in progress
		}
		else if ($stateFile->getStatus() == SGBGStateFile::STATUS_DONE) {
			$stateFile->setStatus(SGBGStateFile::STATUS_READY);
		}

		if ($stateFile->getStatus() == SGBGStateFile::STATUS_READY) {
			$stateFile->setStatus(SGBGStateFile::STATUS_STARTED);
			$stateFile->setCount((int)$count);
			$stateFile->setData('last_reload_ts', time());
			$stateFile->save();
		}
		else if ($stateFile->getStatus() == SGBGStateFile::STATUS_RESUME) {
			$stateFile->setStatus(SGBGStateFile::STATUS_BUSY);
			$stateFile->save();
		}
	}

	public function prepare($stateFilePath)
	{
		$this->prepareStateFile($stateFilePath);
	}

	public function start($count)
	{
		$this->prepareStateFileStatus($count);
		$this->prepareReloader();
	}

	public function setParam($key, $value)
	{
		$this->getStateFile()->setData($key, $value);
	}

	public function getParam($key)
	{
		return $this->getStateFile()->getData($key);
	}

	public function continueTask($exitCallable)
	{
		$reloader = SGBGReloader::getInstance();

		if ($reloader->shouldReload()) {
			$stateFile = $this->getStateFile();
			$stateFile->setStatus(SGBGStateFile::STATUS_RESUME);
			$stateFile->setData('last_reload_ts', time());

			$exitCallable();

			//we call this after the callable because we may change status params inside the callable
			$stateFile->save(true);
			$reloader->reload();
            die();
		}
	}

	public function endChunk()
	{
		$stateFile = $this->getStateFile();
		$stateFile->setOffset($stateFile->getOffset() + 1);
		$stateFile->save();
	}

	public function end($removeStateFile = true)
	{
		$stateFile = $this->getStateFile();

		if ($removeStateFile) {
			$stateFile->remove();
			return;
		}

		$stateFile->setStatus(SGBGStateFile::STATUS_DONE);
		$stateFile->save(true);
	}
}
