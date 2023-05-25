<?php
/**
 * ArubaHiSpeedCacheLoader.php contains an abstraction of the wordpress hook system.
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     run_aruba_hispeed_cache
 * @since    1.0.0
 */

namespace ArubaHiSpeedCache\includes;

use Exception;
use Throwable;
use add_action;
use add_filter;

if (!class_exists(__NAMESPACE__ . '\ArubaHiSpeedCacheLoader')) {
    /**
     * ArubaHiSpeedCacheLoader
     *
     * Contains an abstraction of the wordpress hook system
     *
     * @category Wordpress-plugin
     * @package  Aruba-HiSpeed-Cache
     * @author   Aruba Developer <hispeedcache.developer@aruba.it>
     * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
     * @link     run_aruba_hispeed_cache
     * @since    1.0.0
     */
    class ArubaHiSpeedCacheLoader
    {
        /**
         * Actions
         *
         * @var array
         */
        protected $actions = array();

        /**
         * Filters
         *
         * @var array
         */
        protected $filters = array();

        /**
         * Add_action - Wrap for the wp method add_action
         *
         * @param string  $hook          Hook name
         * @param object  $component     Calss targhet
         * @param string  $callback      Function to fire on action
         * @param integer $priority      The priority of action
         * @param integer $accepted_args Number of var passed to function.
         *
         * @see https://developer.wordpress.org/reference/functions/add_action/
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function add_action(
            $hook,
            $component,
            $callback,
            $priority = 10,
            $accepted_args = 1
        ) {
            $this->actions = $this->_add(
                $this->actions,
                $hook,
                $component,
                $callback,
                $priority,
                $accepted_args
            );
        }

        /**
         * Remove_action
         * Removes the action element of the indicated list if it is present.
         *
         * @param string $hook Hook name
         *
         * @since 1.0.3
         *
         * @return void
         */
        public function remove_action($hook)
        {
            foreach ($this->actions as $action) {
                if ($hook === $action['hook']) {
                    $key = array_keys($this->actions, $action);
                    unset($this->actions[$key[0]]);
                }
            }
        }

        /**
         * Add_filter
         * Wrap for the wp method add_filter
         *
         * @param string  $hook          Hook name
         * @param object  $component     Calss targhet
         * @param string  $callback      Function to fire on action
         * @param integer $priority      The priority of action
         * @param integer $accepted_args Number of var passed to function.
         *
         * @see https://developer.wordpress.org/reference/functions/add_filter/
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function add_filter(
            $hook,
            $component,
            $callback,
            $priority = 10,
            $accepted_args = 1
        ) {
            $this->filters = $this->_add(
                $this->filters,
                $hook,
                $component,
                $callback,
                $priority,
                $accepted_args
            );
        }

        /**
         * Remove_filter
         * Removes the filter element of the indicated list if it is present.
         *
         * @param string $hook Hook name
         *
         * @since 1.0.3
         *
         * @return void
         */
        public function remove_filter($hook)
        {
            foreach ($this->filters as $action) {
                if ($hook === $action['hook']) {
                    $key = array_keys($this->filters, $action);
                    unset($this->filters[$key[0]]);
                }
            }
        }

        /**
         * Add
         *
         * Helper method for populating filters and actions arrays
         *
         * @param string  $hooks         Array targhet
         * @param string  $hook          Hook name
         * @param object  $component     Calss
         * @param string  $callback      Class::methos
         * @param integer $priority      The priority
         * @param integer $accepted_args Number of var passed by wp hook
         *
         * @return array
         */
        private function _add(
            $hooks,
            $hook,
            $component,
            $callback,
            $priority,
            $accepted_args
        ) {
            $hooks[] = array(
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
            );

            return $hooks;
        }

        /**
         * Run
         *
         * Runner method for queuing actions and filters
         *
         * @return void
         */
        public function run()
        {
            if (!empty($this->filters)) {
                foreach ($this->filters as $hook) {
                    \add_filter(
                        $hook['hook'],
                        array( $hook['component'], $hook['callback'] ),
                        $hook['priority'],
                        $hook['accepted_args']
                    );
                }
            }

            if (!empty($this->actions)) {
                foreach ($this->actions as $hook) {
                    \add_action(
                        $hook['hook'],
                        array( $hook['component'], $hook['callback'] ),
                        $hook['priority'],
                        $hook['accepted_args']
                    );
                }
            }
        }
    }
}
