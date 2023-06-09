<?php

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\Plugin\Lib\ImportExport;

use FernleafSystems\Wordpress\Plugin\Shield;
use FernleafSystems\Wordpress\Plugin\Shield\ActionRouter\Actions;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\Plugin;
use FernleafSystems\Wordpress\Plugin\Shield\Modules\Plugin\ModCon;
use FernleafSystems\Wordpress\Services\Services;

class ImportExportController extends Shield\Modules\Base\Common\ExecOnceModConsumer {

	use Shield\Crons\PluginCronsConsumer;

	public const MOD = ModCon::SLUG;

	protected function canRun() :bool {
		return $this->getOptions()->isOpt( 'importexport_enable', 'Y' ) && $this->getCon()->isPremiumActive();
	}

	protected function run() {
		$this->setupHooks();
		$this->setupCronHooks();
	}

	private function setupHooks() {
		$con = $this->getCon();
		/** @var Plugin\Options $opts */
		$opts = $this->getOptions();

		$this->getImportExportSecretKey();

		( new NotifyWhitelist() )->execute();

		add_action( 'shield/plugin_activated', function () {
			$this->importFromFlag();
		} );

		if ( $opts->hasImportExportMasterImportUrl() ) {
			// For auto update whitelist notifications:
			add_action( $con->prefix( Actions\PluginImportExport_UpdateNotified::SLUG ), function () {
				( new Import() )
					->setMod( $this->getMod() )
					->autoImportFromMaster();
			} );
		}
	}

	public function addUrlToImportExportWhitelistUrls( string $url ) {
		/** @var Plugin\Options $opts */
		$opts = $this->getOptions();
		$url = Services::Data()->validateSimpleHttpUrl( $url );
		if ( $url !== false ) {
			$urls = $opts->getImportExportWhitelist();
			$urls[] = $url;
			$opts->setOpt( 'importexport_whitelist', $urls );
			$this->getMod()->saveModOptions();
		}
	}

	public function removeUrlFromImportExportWhitelistUrls( string $url ) {
		/** @var Plugin\Options $opts */
		$opts = $this->getOptions();
		$url = Services::Data()->validateSimpleHttpUrl( $url );
		if ( $url !== false ) {
			$urls = $opts->getImportExportWhitelist();
			$key = array_search( $url, $urls );
			if ( $key !== false ) {
				unset( $urls[ $key ] );
			}
			$opts->setOpt( 'importexport_whitelist', $urls );
			$this->getMod()->saveModOptions();
		}
	}

	protected function getImportExportSecretKey() :string {
		/** @var Plugin\Options $opts */
		$opts = $this->getOptions();
		$ID = $opts->getOpt( 'importexport_secretkey', '' );
		if ( empty( $ID ) || $this->isImportExportSecretKeyExpired() ) {
			$ID = sha1( $this->getCon()->getInstallationID()[ 'id' ].wp_rand( 0, PHP_INT_MAX ) );
			$opts->setOpt( 'importexport_secretkey', $ID )
				 ->setOpt( 'importexport_secretkey_expires_at', Services::Request()->ts() + \DAY_IN_SECONDS );
		}
		return $ID;
	}

	public function verifySecretKey( string $secret ) :bool {
		return !empty( $secret ) && $this->getImportExportSecretKey() == $secret;
	}

	protected function isImportExportSecretKeyExpired() :bool {
		return Services::Request()->ts() > $this->getOptions()->getOpt( 'importexport_secretkey_expires_at' );
	}

	private function importFromFlag() {
		try {
			( new Import() )
				->setMod( $this->getMod() )
				->fromFile( $this->getCon()->paths->forFlag( 'import.json' ) );
		}
		catch ( \Exception $e ) {
		}
	}

	/**
	 * We've been notified that there's an update to pull in from the master site, so we set a cron to do this.
	 */
	public function runOptionsUpdateNotified() {
		/** @var Plugin\Options $opts */
		$opts = $this->getOptions();

		$cronHook = $this->getCon()->prefix( Actions\PluginImportExport_UpdateNotified::SLUG );
		if ( !wp_next_scheduled( $cronHook ) ) {
			wp_schedule_single_event( Services::Request()->ts() + rand( 30, 180 ), $cronHook );
			$this->getCon()->fireEvent(
				'import_notify_received',
				[ 'audit_params' => [ 'master_site' => $opts->getImportExportMasterImportUrl() ] ]
			);
		}
	}

	public function runDailyCron() {
		( new Import() )
			->setMod( $this->getMod() )
			->autoImportFromMaster();
	}
}