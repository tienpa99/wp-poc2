<?php
/**
 * ArubaHiSpeedCacheBootstrap - Control center for everything.
 * php version 5.6
 *
 * @category Component
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     null
 * @since    1.0.1
 */

namespace ArubaHiSpeedCache\includes;

use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs;

use \is_array;

use \is_wp_error;
use \wp_remote_get;

if (!class_exists(__NAMESPACE__ . '\HiSpeedCacheServiceChecker')) {

    /**
     * ArubaHiSpeedCacheConfigs
     *
     * @category ArubaHiSpeedCache
     * @package  ArubaHiSpeedCache
     * @author   Aruba Developer <hispeedcache.developer@aruba.it>
     * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
     * @link     null
     * @since    1.0.1
     */
    class HiSpeedCacheServiceChecker
    {
        /**
         * Target The address you want to test default is null
         *
         * @var string
         */
        // phpcs:ignore
        private $target = null;

        /**
         * Header Request header parameters.
         *
         * @var array
         */
        // phpcs:ignore
        private $headers = array();

        /**
         * $status_code The code of the request.
         * It is used to perform some integrity checks
         *
         * @var int
         */
        // phpcs:ignore
        private $status_code = null;

        /**
         * ServiceIsActivabile
         * Valued as true if the site is hosted on OW Aruba servers. Default False
         *
         * @var boolean
         */
        public $serviceIsActivabile = false;

        /**
         * ServiceIsActive
         * Valued as true if the site is hosted on OW Aruba servers
         * and the service is active.
         * Default False
         *
         * @var boolean
         */
        public $serviceIsActive = false;

        /**
         * ServiceIsActive
         * Valued on the basis of the x-aruba-cache value.
         *
         * @var string
         */
        public $serviceStatus = null;

        /**
         * IsArubaServer Control variables that is used to determine
         * if you are on Aruba server or not.
         *
         * @var boolean
         */
        public $isArubaServer = false;

        /**
         * Check_error Control variable to determine
         * if an error was encountered during the request.
         *
         * @var boolean
         */
        // phpcs:ignore
        private $check_error = false;

        //
        // Error_code Control variable to determine
        // if an error was encountered during the request.
        //
        // @var int
        //
        // phpcs:ignore
        //private $error_code = null;

        //
        // Error_code Control variable to determine
        // if an error was encountered during the request.
        //
        // @var string
        //
        // phpcs:ignore
        //private $error_message = null;

        /**
         * HiSpeedCacheServiceChecker
         *
         * @param string $target Target The address you want to test default is null
         *
         * @since  1.0.1
         * @return void
         */
        public function __construct($target = null)
        {
            $this->target = (null === $target) ?
                \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getSiteHome() :
                $target;

            $this->_getHeaders();
        }

        /**
         * _getHeaders - Getter of the headers for the request to perform the check.
         *
         * @since  1.0.1
         * @return bool
         */
        private function _getHeaders()
        {
            $response = \wp_remote_get(
                $this->target,
                array(
                'sslverify'     => false,
                'user-agent'    => 'aruba-ua',
                'httpversion'   => '1.1',
                'timeout'       => \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('CHECK_TIMEOUT')
                )
            );

            if (\is_array($response) && ! \is_wp_error($response)) {
                $this->headers = $response['headers']->getAll();
                $this->status_code = $response['response']['code'];

                return true;
            }

            if (\is_wp_error($response)) {
                $this->check_error = true;
                // $this->error_code = $response->get_error_code();
                // $this->error_message = $response->get_error_message();
            }

            return false;
        }

        /**
         * _headersAnalizer - Analyze the request headers and value the variables.
         *
         * @since  1.0.1
         * @return void
         */
        private function _headersAnalizer()
        {

            /**
             * If the request headers are empty or the request
             * produced a wp_error then I set everything to true.
             */
            if (empty($this->headers) or $this->check_error) {
                $this->isArubaServer = true;
                $this->serviceIsActive = true;
                return;
            }

            /**
             * If the headers contain 'x-aruba-cache' we are on an aruba server.
             * If it has value NA we are on servers without cache.
             */
            if (array_key_exists('x-aruba-cache', $this->headers)) {
                $this->isArubaServer = true;
                $this->serviceStatus = $this->headers['x-aruba-cache'];

                switch ($this->headers['x-aruba-cache']) {
                case 'NA':
                    $this->serviceIsActivabile = false;
                    $this->serviceIsActive = false;
                    break;
                default:
                    $this->serviceIsActive = true;
                    break;
                }

                return;
            }


            /**
             * If the headers do not contain 'x-aruba-cache'
             * we are not on the aruba server.
             *
             * If the 'server' header contains 'aruba-proxy'
             * the service can be activated.
             *
             * If it is different from 'aruba-proxy' we are
             * not on aruba server or behind cdn.
             */
            if (array_key_exists('server', $this->headers)) {
                switch ($this->headers['server']) {
                case 'aruba-proxy':
                    $this->serviceIsActivabile = true;
                    break;
                default:
                    $this->serviceIsActivabile = false;

                    if (array_key_exists('x-servername', $this->headers)
                        && str_contains($this->headers['x-servername'], 'aruba.it')
                    ) {
                        $this->serviceIsActivabile = true;
                    }
                    break;
                }
                return;
            }
        }

        /**
         * Check - Check the populated variables and issue a control message.
         *
         * @since  1.0.1
         * @return string
         */
        public function check()
        {
            $this->_headersAnalizer();

            if ($this->isArubaServer && !$this->serviceIsActive) {
                return ($this->serviceIsActivabile) ? 'available' : 'unavailable';
            }

            if (!$this->isArubaServer && !$this->serviceIsActive) {
                return ($this->serviceIsActivabile) ? 'available' : 'no-aruba-server';
            }
        }

        /**
         * Debugger - It exposes some elements of the control to
         * try to resolve any errors. To activate it, just go to the
         * dominio.tld/wp-admin/options-general.php?page=aruba-hispeed-cache&debug=1
         *
         * @return string
         */
        public function debugger()
        {
            $data =  array(
                'date'          => date('D, d M Y H:i:s', time()),
                'target'        => $this->target,
                'headers'       => $this->headers,
                'status_code'   => $this->status_code
            );

            return var_export($data, true);
        }
    }
}
