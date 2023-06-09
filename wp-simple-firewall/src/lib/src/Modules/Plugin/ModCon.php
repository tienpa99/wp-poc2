<?php

namespace FernleafSystems\Wordpress\Plugin\Shield\Modules\Plugin;

use FernleafSystems\Wordpress\Plugin\Shield;
use FernleafSystems\Wordpress\Plugin\Shield\ActionRouter\{
	Actions,
	Actions\Render\Components\BannerGoPro,
	Actions\Render\Components\ToastPlaceholder
};
use FernleafSystems\Wordpress\Plugin\Shield\Modules\BaseShield;
use FernleafSystems\Wordpress\Services\Services;
use FernleafSystems\Wordpress\Services\Utilities\Net\RequestIpDetect;
use FernleafSystems\Wordpress\Services\Utilities\Net\VisitorIpDetection;

class ModCon extends BaseShield\ModCon {

	public const SLUG = 'plugin';

	/**
	 * @var Lib\ImportExport\ImportExportController
	 */
	private $importExportCon;

	/**
	 * @var Components\PluginBadge
	 */
	private $pluginBadgeCon;

	/**
	 * @var Shield\Utilities\ReCaptcha\Enqueue
	 */
	private $oCaptchaEnqueue;

	/**
	 * @var Lib\Reporting\ReportingController
	 */
	private $reportsCon;

	/**
	 * @var Shield\ShieldNetApi\ShieldNetApiController
	 */
	private $shieldNetCon;

	/**
	 * @var Lib\Sessions\SessionController
	 */
	private $sessionCon;

	public function getImpExpController() :Lib\ImportExport\ImportExportController {
		return $this->importExportCon ?? $this->importExportCon = new Lib\ImportExport\ImportExportController();
	}

	public function getPluginBadgeCon() :Components\PluginBadge {
		return $this->pluginBadgeCon ?? $this->pluginBadgeCon = ( new Components\PluginBadge() )->setMod( $this );
	}

	public function getReportingController() :Lib\Reporting\ReportingController {
		return $this->reportsCon ?? $this->reportsCon = new Lib\Reporting\ReportingController();
	}

	public function getSessionCon() :Lib\Sessions\SessionController {
		return $this->sessionCon ?? $this->sessionCon = new Lib\Sessions\SessionController();
	}

	public function getShieldNetApiController() :Shield\ShieldNetApi\ShieldNetApiController {
		return $this->shieldNetCon ?? $this->shieldNetCon = ( new Shield\ShieldNetApi\ShieldNetApiController() )->setMod( $this );
	}

	public function getDbH_ReportLogs() :DB\Report\Ops\Handler {
		return $this->getDbHandler()->loadDbH( 'report' );
	}

	protected function doPostConstruction() {
		$this->setVisitorIpSource();
		$this->setupCacheDir();
	}

	protected function setupCacheDir() {
		$opts = $this->getOptions();
		$url = Services::WpGeneral()->getWpUrl();
		$lastKnownDirs = $opts->getOpt( 'last_known_cache_basedirs' );
		if ( empty( $lastKnownDirs ) || !is_array( $lastKnownDirs ) ) {
			$lastKnownDirs = [
				$url => ''
			];
		}

		$cacheDirFinder = new Shield\Utilities\CacheDirHandler( $lastKnownDirs[ $url ] ?? '' );
		$workableDir = $cacheDirFinder->dir();
		$lastKnownDirs[ $url ] = empty( $workableDir ) ? '' : dirname( $workableDir );

		$opts->setOpt( 'last_known_cache_basedirs', $lastKnownDirs );
		$this->getCon()->cache_dir_handler = $cacheDirFinder;
	}

	protected function enumRuleBuilders() :array {
		return [
			Rules\Build\RequestStatusIsAdmin::class,
			Rules\Build\RequestStatusIsAjax::class,
			Rules\Build\RequestStatusIsXmlRpc::class,
			Rules\Build\RequestStatusIsWpCli::class,
			Rules\Build\IsServerLoopback::class,
			Rules\Build\IsTrustedBot::class,
			Rules\Build\IsPublicWebRequest::class,
			Rules\Build\RequestBypassesAllRestrictions::class,
		];
	}

	protected function preProcessOptions() {
		/** @var Options $opts */
		$opts = $this->getOptions();
		if ( $opts->getIpSource() === 'AUTO_DETECT_IP' ) {
			$opts->setOpt( 'ipdetect_at', 0 );
		}

		( new Lib\Captcha\CheckCaptchaSettings() )
			->setMod( $this )
			->checkAll();
	}

	public function deleteAllPluginCrons() {
		$con = $this->getCon();
		$wpCrons = Services::WpCron();

		foreach ( $wpCrons->getCrons() as $key => $cronArgs ) {
			foreach ( $cronArgs as $hook => $cron ) {
				if ( strpos( (string)$hook, $con->prefix() ) === 0 || strpos( (string)$hook, $con->prefixOption() ) === 0 ) {
					$wpCrons->deleteCronJob( $hook );
				}
			}
		}
	}

	/**
	 * Forcefully sets preferred Visitor IP source in the Data component for use throughout the plugin
	 */
	private function setVisitorIpSource() {
		$con = $this->getCon();
		/** @var Options $opts */
		$opts = $this->getOptions();
		if ( $opts->getIpSource() !== 'AUTO_DETECT_IP' ) {
			Services::Request()->setIpDetector(
				( new RequestIpDetect() )->setPreferredSource( $opts->getIpSource() )
			);
			Services::IP()->setIpDetector(
				( new VisitorIpDetection() )->setPreferredSource( $opts->getIpSource() )
			);
		}
		$con->this_req->ip = Services::Request()->ip();
		$con->this_req->ip_is_public = !empty( $con->this_req->ip )
									   && Services::IP()->isValidIp_PublicRemote( $con->this_req->ip );
	}

	/**
	 * @throws \Exception
	 */
	public function canSiteLoopback() :bool {
		$can = false;
		if ( class_exists( '\WP_Site_Health' ) && method_exists( '\WP_Site_Health', 'get_instance' ) ) {
			$can = \WP_Site_Health::get_instance()->get_test_loopback_requests()[ 'status' ] === 'good';
		}
		if ( !$can ) {
			$can = Services::HttpRequest()->post( site_url( 'wp-cron.php' ), [
				'timeout' => 10
			] );
		}
		return $can;
	}

	public function getLinkToTrackingDataDump() :string {
		return $this->getCon()->plugin_urls->noncedPluginAction( Actions\PluginDumpTelemetry::class );
	}

	public function getPluginReportEmail() :string {
		$con = $this->getCon();
		$e = (string)$this->getOptions()->getOpt( 'block_send_email_address' );
		if ( $con->isPremiumActive() ) {
			$e = apply_filters( $con->prefix( 'report_email' ), $e );
		}
		$e = trim( $e );
		return Services::Data()->validEmail( $e ) ? $e : Services::WpGeneral()->getSiteAdminEmail();
	}

	/**
	 * This is the point where you would want to do any options verification
	 */
	protected function doPrePluginOptionsSave() {
		/** @var Options $opts */
		$opts = $this->getOptions();

		$this->storeRealInstallDate();

		if ( $opts->isTrackingEnabled() && !$opts->isTrackingPermissionSet() ) {
			$opts->setOpt( 'tracking_permission_set_at', Services::Request()->ts() );
		}

		$this->cleanRecaptchaKey( 'google_recaptcha_site_key' );
		$this->cleanRecaptchaKey( 'google_recaptcha_secret_key' );

		$this->cleanImportExportWhitelistUrls();
		$this->cleanImportExportMasterImportUrl();
	}

	public function getFirstInstallDate() :int {
		return (int)Services::WpGeneral()->getOption( $this->getCon()->prefixOption( 'install_date' ) );
	}

	public function getInstallDate() :int {
		return (int)$this->getOptions()->getOpt( 'installation_time', 0 );
	}

	public function isShowAdvanced() :bool {
		return $this->getOptions()->isOpt( 'show_advanced', 'Y' );
	}

	/**
	 * @return int - the real install timestamp
	 */
	public function storeRealInstallDate() {
		$key = $this->getCon()->prefixOption( 'install_date' );
		$wpDate = Services::WpGeneral()->getOption( $key );
		if ( empty( $wpDate ) ) {
			$wpDate = Services::Request()->ts();
		}

		$date = $this->getInstallDate();
		if ( $date == 0 ) {
			$date = Services::Request()->ts();
		}

		$finalDate = min( $date, $wpDate );
		Services::WpGeneral()->updateOption( $key, $finalDate );
		$this->getOptions()->setOpt( 'installation_time', $date );

		return $finalDate;
	}

	/**
	 * @param string $optionKey
	 */
	protected function cleanRecaptchaKey( $optionKey ) {
		$opts = $this->getOptions();
		$sCaptchaKey = trim( (string)$opts->getOpt( $optionKey, '' ) );
		$nSpacePos = strpos( $sCaptchaKey, ' ' );
		if ( $nSpacePos !== false ) {
			$sCaptchaKey = substr( $sCaptchaKey, 0, $nSpacePos + 1 ); // cut off the string if there's spaces
		}
		$sCaptchaKey = preg_replace( '#[^\da-zA-Z_-]#', '', $sCaptchaKey ); // restrict character set
//			if ( strlen( $sCaptchaKey ) != 40 ) {
//				$sCaptchaKey = ''; // need to verify length is 40.
//			}
		$opts->setOpt( $optionKey, $sCaptchaKey );
	}

	public function getActivateLength() :int {
		return Services::Request()->ts() - (int)$this->getOptions()->getOpt( 'activated_at', 0 );
	}

	public function setActivatedAt() {
		$this->getOptions()->setOpt( 'activated_at', Services::Request()->ts() );
	}

	private function cleanImportExportWhitelistUrls() {
		/** @var Options $opts */
		$opts = $this->getOptions();
		$cleaned = [];
		$whitelist = $opts->getImportExportWhitelist();
		foreach ( $whitelist as $url ) {

			$url = Services::Data()->validateSimpleHttpUrl( $url );
			if ( $url !== false ) {
				$cleaned[] = $url;
			}
		}
		$opts->setOpt( 'importexport_whitelist', array_unique( $cleaned ) );
	}

	private function cleanImportExportMasterImportUrl() {
		/** @var Options $opts */
		$opts = $this->getOptions();
		$url = Services::Data()->validateSimpleHttpUrl( $opts->getImportExportMasterImportUrl() );
		$opts->setOpt( 'importexport_masterurl', $url === false ? '' : $url );
	}

	public function isXmlrpcBypass() :bool {
		return (bool)apply_filters( 'shield/allow_xmlrpc_login_bypass', false );
	}

	public function getDbHandler_Notes() :Shield\Databases\AdminNotes\Handler {
		return $this->getDbH( 'notes' );
	}

	public function getCaptchaEnqueue() :Shield\Utilities\ReCaptcha\Enqueue {
		return $this->oCaptchaEnqueue ?? $this->oCaptchaEnqueue = ( new Shield\Utilities\ReCaptcha\Enqueue() )->setMod( $this );
	}

	protected function setupCustomHooks() {
		add_action( 'admin_footer', function () {
			$con = $this->getCon();
			$AR = $con->action_router;
			if ( !empty( $AR ) && $con->isModulePage() ) {
				echo $AR->render( BannerGoPro::SLUG );
				echo $AR->render( ToastPlaceholder::SLUG );
			}
		}, 100, 0 );
	}

	public function isModOptEnabled() :bool {
		/** @var Options $opts */
		$opts = $this->getOptions();
		return !$opts->isPluginGloballyDisabled();
	}
}