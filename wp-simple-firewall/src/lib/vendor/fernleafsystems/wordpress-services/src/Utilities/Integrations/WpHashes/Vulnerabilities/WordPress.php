<?php

namespace FernleafSystems\Wordpress\Services\Utilities\Integrations\WpHashes\Vulnerabilities;

use FernleafSystems\Wordpress\Services\Services;

class WordPress extends Base {

	public const ASSET_TYPE = 'wordpress';

	/**
	 * @param string $version
	 * @return array[]|null
	 */
	public function getVulnerabilities( $version ) {
		if ( empty( $version ) ) {
			$version = Services::WpGeneral()->getVersion( true );
		}
		$req = $this->getRequestVO();
		$req->version = $version;
		return $this->query();
	}

	protected function getApiUrl() :string {
		return sprintf( '%s/%s', parent::getApiUrl(), $this->getRequestVO()->version );
	}

	/**
	 * @return array[]|null
	 */
	public function getCurrent() {
		return $this->getVulnerabilities( Services::WpGeneral()->getVersion( true ) );
	}
}