<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class SQ_Models_LiveAssistant
{


    /**
     * Load the JS for API
     *
     * @param $args
     * @return void
     */
    public static function loadMedia($args = array())
    {
        global $post;
        $referer = '';

        $metas = json_decode(wp_json_encode(SQ_Classes_Helpers_Tools::getOption('sq_metas')));
        $sq_postID = ((isset($post->ID) && $post->ID > 0) ? $post->ID : 0);

        //Load Squirrly Live Assistant for Elementor builder
        if (SQ_Classes_Helpers_Tools::getOption('sq_sla_frontend')) {
            $referer = get_post_meta($sq_postID, '_sq_sla', true);
        }

	    $sq_config = array(
			'debug' => (bool)SQ_DEBUG,
		    'sq_version' => SQ_VERSION,
		    'token' => SQ_Classes_Helpers_Tools::getOption('sq_api'),
		    'url_token' => (SQ_Classes_Helpers_Tools::getOption('sq_cloud_connect') ? SQ_Classes_Helpers_Tools::getOption('sq_cloud_token') : false),
		    'sq_apiurl' => _SQ_APIV2_URL_,
		    'ajaxurl' => esc_url(admin_url('admin-ajax.php')),
		    'sq_nonce' =>  wp_create_nonce(_SQ_NONCE_ID_),
			'user_url' => esc_url(apply_filters('sq_homeurl', get_bloginfo('url'))),
		    'language' => apply_filters('sq_language', get_bloginfo('language')),
		    'referer' => esc_attr($referer),
		    'sq_keywordtag' => (int)SQ_Classes_Helpers_Tools::getOption('sq_keywordtag'),
		    'sq_keyword_help' => (int)SQ_Classes_Helpers_Tools::getOption('sq_keyword_help'),
		    'frontend_css' => esc_url(_SQ_ASSETS_URL_ . 'css/frontend' . (SQ_DEBUG ? '' : '.min') . '.css'),
		    'postID' => $sq_postID,
		    '__date' => esc_html__('date', 'squirrly-seo'),
		    '__noconnection' => esc_html__("To load the Live Assistant and optimize this page, click to connect to Squirrly Cloud.", 'squirrly-seo'),
		    '__saved' => esc_html__('Saved!', 'squirrly-seo'),
		    '__readit' => esc_html__('Read it!', 'squirrly-seo'),
		    '__insertit' => esc_html__('Insert it!', 'squirrly-seo'),
		    '__reference' => esc_html__('Reference', 'squirrly-seo'),
		    '__insertasbox' => esc_html__('Insert as box', 'squirrly-seo'),
		    '__addlink' => esc_html__('Insert Link', 'squirrly-seo'),
		    '__notrelevant' => esc_html__('Not relevant?', 'squirrly-seo'),
		    '__ajaxerror' => esc_html__(':( An error occurred while processing your request. Please try again', 'squirrly-seo'),
		    '__searching' =>  esc_html__('Searching ... ', 'squirrly-seo'),
		    '__nofound' =>  esc_html__('No results found!', 'squirrly-seo'),
		    '__sq_photo_copyright' =>  esc_html__('[ ATTRIBUTE: Please check: %s to find out how to attribute this image ]', 'squirrly-seo'),
		    '__has_attributes' =>  esc_html__('Has creative commons attributes', 'squirrly-seo'),
		    '__no_attributes' =>  esc_html__('No known copyright restrictions', 'squirrly-seo'),
		    '__subscription_expired' =>  esc_html__('Your Subscription has Expired', 'squirrly-seo'),
		    '__no_briefcase' =>  esc_html__('There are no keywords saved in briefcase yet', 'squirrly-seo'),
		    '__fulloptimized' =>  esc_html__('Congratulations! Your article is 100% optimized!', 'squirrly-seo'),
		    '__toomanytimes' =>  esc_html__('appears too many times. Try to remove %s of them', 'squirrly-seo'),
		    '__writemorewords' =>  esc_html__('write %s more words', 'squirrly-seo') ,
		    '__morewordsafter' =>  esc_html__('Write more words after the %s keyword', 'squirrly-seo'),
		    '__orusesynonyms' => esc_html__('or use synonyms', 'squirrly-seo') ,
		    '__addmorewords' =>  esc_html__('add %s more word(s)', 'squirrly-seo'),
		    '__removewords' =>  esc_html__('or remove %s word(s)', 'squirrly-seo'),
		    '__addmorekeywords' =>  esc_html__('add the selected keyword %s more time(s) ', 'squirrly-seo'),
		    '__addminimumwords' =>  esc_html__('write %s more words to start calculating', 'squirrly-seo'),
		    '__frontend_optimized' =>  esc_html__('Live Assistant was used to optimize this page with the Page Builder. Please go back and resume your optimization work there.', 'squirrly-seo'),

	    );
		$sq_params = array(
			'max_length_title' => (int)$metas->title_maxlength,
			'max_length_description' => (int)$metas->description_maxlength,
		);

        if (is_rtl()) {
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('sqbootstrap.rtl', $args);
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('rtl', $args);
        } else {
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('sqbootstrap', $args);
        }
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('logo', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('post', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('fontawesome', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/utils/mark'.(SQ_DEBUG ? '' : '.min').'.js', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/utils/xregexp'.(SQ_DEBUG ? '' : '.min').'.js', $args);

        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/sq_briefcase'.(SQ_DEBUG ? '' : '.min').'.js', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/sq_blockseo'.(SQ_DEBUG ? '' : '.min').'.js', $args);
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/sq_blocksearch'.(SQ_DEBUG ? '' : '.min').'.js', $args);
        if(SQ_Classes_Helpers_Tools::getOption('sq_sla_type') <> 'integrated') {
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/sq_floating' . (SQ_DEBUG ? '' : '.min') . '.js', $args);
        }
	    $handle = SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/squirrly'.(SQ_DEBUG ? '' : '.min').'.js', $args);

	    wp_localize_script( $handle, '$sq_config', $sq_config );
	    wp_localize_script( $handle, '$sq_params', $sq_params );

    }

	/**
	 * Load Squirrly Assistant in frontend
	 */
	public function loadBackend()
	{
		$args = array(
			'dependencies' => array('jquery', 'jquery-ui-core', 'jquery-ui-draggable')
		);

		SQ_Classes_ObjController::getClass('SQ_Models_LiveAssistant')->loadMedia($args);

	}

    /**
     * Load Squirrly Assistant in frontend
     */
    public function loadFrontent()
    {
        //If squirrly should load on frontend
        if (apply_filters('sq_load_frontend_sla', false)) {

            global $post;

            if (isset($post->ID) && isset($post->post_type)) {

                if(SQ_Classes_ObjController::getClass('SQ_Models_Post')->isSLAEnable($post->post_type)) {

                    //Load media in frontend
                    $args = array(
						'dependencies' => array('jquery', 'jquery-ui-core', 'jquery-ui-draggable')
                    );

                    SQ_Classes_ObjController::getClass('SQ_Models_LiveAssistant')->loadMedia($args);
                    SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant/sq_frontend'.(SQ_DEBUG ? '' : '.min').'.js', $args);

                    //Load the Frontend Assistant
                    SQ_Classes_ObjController::getClass('SQ_Controllers_Post')->show_view('Blocks/FrontendAssistant');

                }

            }
        }

    }


}
