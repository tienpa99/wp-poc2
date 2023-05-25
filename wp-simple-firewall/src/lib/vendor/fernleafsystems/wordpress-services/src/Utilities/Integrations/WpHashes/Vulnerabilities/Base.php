<?php

namespace FernleafSystems\Wordpress\Services\Utilities\Integrations\WpHashes\Vulnerabilities;

use FernleafSystems\Wordpress\Services\Utilities\Integrations\WpHashes;

abstract class Base extends WpHashes\ApiBase {

	public const API_ENDPOINT = 'vulnerabilities';
	public const API_VERSION = 2;
	public const RESPONSE_DATA_KEY = 'data';
	public const ASSET_TYPE = '';
	public const TOKEN_REQUIRED = true;

	/**
	 * @return array[]|null
	 */
	public function query() {
		return parent::query();
	}

	protected function getApiUrl() :string {
		return sprintf( '%s/%s', parent::getApiUrl(), $this->getRequestVO()->type );
	}

	/**
	 * @return RequestVO
	 */
	protected function getRequestVO() {
		/** @var RequestVO $req */
		$req = parent::getRequestVO();
		$req->type = static::ASSET_TYPE;
		return $req;
	}

	/**
	 * @return RequestVO
	 */
	protected function newReqVO() {
		return new RequestVO();
	}
}