<?php
defined('ABSPATH') || die('Cheatin\' uh?');

/**
 * The class handles the theme part in WP
 */
class SQ_Classes_DisplayController
{

    /**
     * echo the css link from theme css directory
     *
     * @param string $filename    The name of the css file or the entire uri path of the css file
     * @param string $params : trigger, media
     *
     * @return void
     */
    public static function loadMedia($filename = '', $params = array())
    {
        global $sq_setting_page;

        if (!did_action( 'init' ) || SQ_Classes_Helpers_Tools::isAjax()) {
            return;
        }

	    if (!isset($params['trigger'])) {
		    $params['trigger'] = false;
	    }

	    if (!isset($params['dependencies'])) {
		    $params['dependencies'] = array();
	    }

	    $css_uri = '';
        $js_uri = '';

	    $handle = substr(md5($filename), 0, 10);

	    /* if is a custom css file */
        if (strpos($filename, '//') === false) {

            if (strpos($filename, '.css') !== false && file_exists(_SQ_ASSETS_DIR_ . 'css/' . strtolower($filename))) {
                $css_uri = _SQ_ASSETS_URL_ . 'css/' . strtolower($filename);
            }
            if (file_exists(_SQ_ASSETS_DIR_ . 'css/' . strtolower($filename) . (SQ_DEBUG ? '' : '.min') . '.css')) {
                $css_uri = _SQ_ASSETS_URL_ . 'css/' . strtolower($filename) . (SQ_DEBUG ? '' : '.min') . '.css';
            }

            if (strpos($filename, '.js') !== false && file_exists(_SQ_ASSETS_DIR_ . 'js/' . strtolower($filename))) {
                $js_uri = _SQ_ASSETS_URL_ . 'js/' . strtolower($filename);
            }
            if (file_exists(_SQ_ASSETS_DIR_ . 'js/' . strtolower($filename) . (SQ_DEBUG ? '' : '.min') . '.js')) {
                $js_uri = _SQ_ASSETS_URL_ . 'js/' . strtolower($filename) . (SQ_DEBUG ? '' : '.min') . '.js';
            }

        }


        if ($css_uri <> '') {

	        if (!wp_style_is($handle)) {
                wp_enqueue_style($handle, $css_uri, null, SQ_VERSION, 'all');

                if ((isset($sq_setting_page) && $sq_setting_page) ||  $params['trigger']) { //load CSS for admin or on triggered
                    wp_print_styles(array($handle));
                }
            }

        }

        if ($js_uri <> '') {

	        if (!wp_script_is($handle)) {

                if (!wp_script_is('jquery')) {
                    wp_enqueue_script('jquery');
                    wp_print_scripts(array('jquery'));
                }

                wp_enqueue_script($handle, $js_uri, $params['dependencies'], SQ_VERSION);

                if ((isset($sq_setting_page) && $sq_setting_page) || $params['trigger']) { //load CSS for admin or on triggered
                    wp_print_scripts(array($handle));
                }
            }

        }

		return $handle;
    }

    /**
     * return the block content from theme directory
     *
     * @param  $block
     * @param  $view
     * @return bool|string
     */
    public function get_view($block, $view)
    {

        try {
            $file = apply_filters('sq_view', _SQ_THEME_DIR_ . $block . '.php', $block);

            if (file_exists($file)) {
                ob_start();
                include $file;
                return ob_get_clean();
            }

        } catch (Exception $e) {
        }

        return false;
    }



}
