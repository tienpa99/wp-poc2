<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Survey_Maker_Integrations
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $capability;

    /**
     * The settings object of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $settings_obj    The settings object of this plugin.
     */
    private $settings_obj;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    // ===== INTEGRATIONS HOOKS =====

    // Integrations survey page action hook
    public function ays_survey_page_integrations_content( $args ){

        $integrations_contents = apply_filters( 'ays_sm_survey_page_integrations_contents', array(), $args );
        
        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. $integrations_content['icon'] .'" alt="">';
                }
                $content .= '<h5>'. $integrations_content['title'] .'</h5></legend>';
            }
            $content .= $integrations_content['content'];

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        $integrations = implode('<hr/>', $integrations);        
        echo html_entity_decode(esc_html( $integrations ));
    }

    // Integrations settings page action hook
    public function ays_settings_page_integrations_content( $args ){

        $integrations_contents = apply_filters( 'ays_sm_settings_page_integrations_contents', array(), $args );
        
        $integrations = array();

        foreach ($integrations_contents as $key => $integrations_content) {
            $content = '<fieldset>';
            if(isset($integrations_content['title'])){
                $content .= '<legend>';
                if(isset($integrations_content['icon'])){
                    $content .= '<img class="ays_integration_logo" src="'. $integrations_content['icon'] .'" alt="">';
                }
                $content .= '<h5>'. $integrations_content['title'] .'</h5></legend>';
            }
            if(isset($integrations_content['content'])){
                $content .= $integrations_content['content'];
            }

            $content .= '</fieldset>';

            $integrations[] = $content;
        }

        $integrations = implode('<hr/>', $integrations);        
        echo html_entity_decode(esc_html( $integrations ));
    }

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== MailChimp integration start =====

        // MailChimp integration / survey page

        // MailChimp integration in survey page content
        public function ays_survey_page_mailchimp_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mailchimp_logo.png';
            $title = __('MailChimp Settings',"survey-maker");

            $content = '';

            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<hr>';
            $content .= '<div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_enable_mailchimp">'. __('Enable MailChimp',"survey-maker") .'</label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_mailchimp" value="on" >';
            $content .= '
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_mailchimp_list">'. __('MailChimp list',"survey-maker") .'</label>
                </div>
                <div class="col-sm-8">';
            $content .= '<select id="ays_mailchimp_list">';
            $content .= '<option value="" disabled selected>'. __( "Select list", "survey-maker" ) .'</option>';
            $content .= '</select>';
            $content .= '</div>
            </div>
            </div>
            </div>';

            $integrations['mailchimp'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // MailChimp integration / settings page

        // MailChimp integration in General settings page content
        public function ays_settings_page_mailchimp_content( $integrations, $args ){

            $actions = $this->settings_obj;

            $mailchimp_res = ($actions->ays_get_setting('mailchimp') === false) ? json_encode(array()) : $actions->ays_get_setting('mailchimp');
            $mailchimp = json_decode($mailchimp_res, true);
            $mailchimp_username = isset($mailchimp['username']) ? $mailchimp['username'] : '' ;
            $mailchimp_api_key = isset($mailchimp['apiKey']) ? $mailchimp['apiKey'] : '' ;

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mailchimp_logo.png';
            $title = __( 'MailChimp', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<div class="form-group row">
                <div class="col-sm-12">
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_mailchimp_username">'. __( 'MailChimp Username', "survey-maker" ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text"
                                class="ays-text-input"
                                id="ays_mailchimp_username"
                                name="ays_mailchimp_username"
                                value="'. $mailchimp_username .'"
                            />
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_mailchimp_api_key">'. __( 'MailChimp API Key', "survey-maker" ) .'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text"
                                class="ays-text-input"
                                id="ays_mailchimp_api_key"
                                name="ays_mailchimp_api_key"
                                value="'. $mailchimp_api_key .'"
                            />
                        </div>
                    </div>
                    <blockquote>';
            $content .= sprintf( __( "You can get your API key from your ", "survey-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://us20.admin.mailchimp.com/account/api/", "Account Extras menu" );
            $content .= '</blockquote>
                </div>
            </div>
            </div>
            </div>';

            $integrations['mailchimp'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

    // ===== MailChimp integration end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Campaign Monitor start =====    
        // Campaign Monitor integration / survey page

        // Campaign Monitor integration in survey page content
        public function ays_survey_page_camp_monitor_content($integrations, $args){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/campaignmonitor_logo.png';
            $title = __('Campaign Monitor Settings',"survey-maker");
            $content = '';

            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<hr/>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_enable_monitor">'.__('Enable Campaign Monitor', "survey-maker").'</label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1" id="ays_enable_monitor" value="on" />
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_monitor_list">'.__('Campaign Monitor list', "survey-maker").'</label>
                    </div>
                    <div class="col-sm-8">';
                $content .= '<select id="ays_monitor_list">
                    <option disabled selected>'.__("Select List", "survey-maker").'</option>';
                $content .= '</select>';
            $content .= '
                    </div>
                </div>
            </div>';

            $integrations['monitor'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Campaign Monitor integration / settings page

        // Campaign Monitor integration in General settings page
        public function ays_settings_page_campaign_monitor_content( $integrations, $args ){
        $actions = $this->settings_obj;
        
        $monitor_res     = ($actions->ays_get_setting('monitor') === false) ? json_encode(array()) : $actions->ays_get_setting('monitor');
        $monitor         = json_decode($monitor_res, true);
        $monitor_client  = isset($monitor['client']) ? $monitor['client'] : '';
        $monitor_api_key = isset($monitor['apiKey']) ? $monitor['apiKey'] : '';
        
        $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/campaignmonitor_logo.png';
        $title = __( 'Campaign Monitor', "survey-maker" );

        $content = '';
        $content .= '<div class="form-group row" style="margin:0px;">';
        $content .= '<div class="col-sm-12 only_pro">';
            $content .= '<div class="pro_features">';
                $content .= '<div>';
                    $content .= '<p>';
                        $content .= __("This feature is available only in ", "survey-maker");
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                    $content .= '</p>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '<div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group row" aria-describedby="aaa">
                    <div class="col-sm-3">
                        <label for="ays_monitor_client">'. __( 'Campaign Monitor Client ID', "survey-maker" ) .'</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" 
                            class="ays-text-input" 
                            id="ays_monitor_client" 
                            name="ays_monitor_client"
                            value="'. $monitor_client .'"
                        />
                    </div>
                </div>
                <hr/>
                <div class="form-group row" aria-describedby="aaa">
                    <div class="col-sm-3">
                        <label for="ays_monitor_api_key">'. __( 'Campaign Monitor API Key', "survey-maker" ) .'</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" 
                            class="ays-text-input" 
                            id="ays_monitor_api_key" 
                            name="ays_monitor_api_key"
                            value="'. $monitor_api_key .'"
                        />
                    </div>
                </div>
                <blockquote>';
        $content .= __( "You can get your API key and Client ID from your Account Settings page.");
        $content .= '</blockquote>
            </div>
        </div>
        </div>
        </div>';

        $integrations['monitor'] = array(
            'content' => $content,
            'icon' => $icon,
            'title' => $title
        );

        return $integrations;
    }


    // ===== Campaign Monitor end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Zapier start =====

        // Zapier integration / survey page

        // Zapier integration in survey page content
        public function ays_survey_page_zapier_content($integrations, $args){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/zapier_logo.png';
            $title = __('Zapier Settings',"survey-maker");

            $content = '';

            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_enable_zapier">'.__('Enable Zapier Integration', "survey-maker").'</label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_zapier" value="on" >
                </div>
                <div class="col-sm-3">
                    <button type="button" id="testZapier" class="btn btn-outline-secondary">'.__("Send test data", "survey-maker").'</button>
                    <a class="ays_help" data-toggle="tooltip" style="font-size: 16px;"
                       title="'.__("We will send you a test data, and you can catch it in your ZAP for configure it.", "survey-maker").'">
                        <i class="ays_fa ays_fa_info_circle"></i>
                    </a>
                </div>
            </div>
            </div>
            </div>';

            $integrations['zapier'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );
            return $integrations;
        }

        // Zapier integration / settings page

        // Zapier integration in General settings page content
        public function ays_settings_page_zapier_content( $integrations, $args ){
        $actions = $this->settings_obj;
        
        $zapier_res  = ($actions->ays_get_setting('zapier') === false) ? json_encode(array()) : $actions->ays_get_setting('zapier');
        $zapier      = json_decode($zapier_res, true);
        $zapier_hook = isset($zapier['hook']) ? $zapier['hook'] : '';
        
        $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/zapier_logo.png';
        $title = __( 'Zapier', "survey-maker" );

        $content = '';
        $content .= '<div class="form-group row" style="margin:0px;">';
        $content .= '<div class="col-sm-12 only_pro">';
            $content .= '<div class="pro_features">';
                $content .= '<div>';
                    $content .= '<p>';
                        $content .= __("This feature is available only in ", "survey-maker");
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                    $content .= '</p>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '<div class="form-group row">
            <div class="col-sm-12">
                <div class="form-group row" aria-describedby="aaa">
                    <div class="col-sm-3">
                        <label for="ays_zapier_hook">'. __( 'Zapier Webhook URL', "survey-maker" ) .'</label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" 
                            class="ays-text-input"
                            id="ays_zapier_hook" 
                            name="ays_zapier_hook"
                            value="'. $zapier_hook .'"
                        />
                    </div>
                </div>
                <blockquote>';
        $content .= sprintf( __( "If you don't have any ZAP created, go", "survey-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://zapier.com/app/editor/", "here..." );
        $content .= '</blockquote>
                    <blockquote>
                    '.__("We will send you all data from survey information form with the “AysSurvey” key by POST method.").'
                    </blockquote>
            </div>
        </div>
        </div>
        </div>';

        $integrations['zapier'] = array(
            'content' => $content,
            'icon' => $icon,
            'title' => $title
        );

        return $integrations;
    }

    // ===== Zapier end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Active Campaign start =====

        // Active Campaign integration / survey page

        // Active Campaign integration in survey page content
        public function ays_survey_page_active_camp_content($integrations, $args){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/activecampaign_logo.png';
            $title = __('ActiveCampaign Settings', "survey-maker");

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
                    $content .= '<hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_enable_active_camp">'. __('Enable ActiveCampaign', "survey-maker") .'</label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timer1" id="ays_enable_active_camp" value="on">
                        </div>
                    </div>
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label for="ays_active_camp_list">'.__('ActiveCampaign list', "survey-maker").'</label>
                        </div>
                        <div class="col-sm-8">';
                $content .= '<select id="ays_active_camp_list">
                    <option value="" disabled selected>'. __("Select List", "survey-maker") .'</option>
                    <option value="">'.__("Just create contact", "survey-maker").'</option>';
                $content .= '</select></div>';
            $content .= '</div><hr>';
            $content .= '
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_active_camp_automation">'.__("ActiveCampaign automation", "survey-maker").'</label>
                </div>
                <div class="col-sm-8">';

            $content .= '<select id="ays_active_camp_automation">
                <option value="" disabled selected>'.__("Select List", "survey-maker").'</option>
                <option value="">'.__("Just create contact", "survey-maker").'</option>';
            $content .= '</select></div>';
            $content .= '</div></div>';

            $integrations['active_camp'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }


        // Active Campaign integration / settings page

        // Active Campaign integration in Gengeral settings page content
        public function ays_settings_page_active_camp_content( $integrations, $args ){
        $actions = $this->settings_obj;
        
        $active_camp_res     = ($actions->ays_get_setting('active_camp') === false) ? json_encode(array()) : $actions->ays_get_setting('active_camp');
        $active_camp         = json_decode($active_camp_res, true);
        $active_camp_url     = isset($active_camp['url']) ? $active_camp['url'] : '';
        $active_camp_api_key = isset($active_camp['apiKey']) ? $active_camp['apiKey'] : '';
        
        $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/activecampaign_logo.png';
        $title = __( 'ActiveCampaign', "survey-maker" );

        $content = '';
        $content .= '<div class="form-group row" style="margin:0px;">';
        $content .= '<div class="col-sm-12 only_pro">';
            $content .= '<div class="pro_features">';
                $content .= '<div>';
                    $content .= '<p>';
                        $content .= __("This feature is available only in ", "survey-maker");
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                    $content .= '</p>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '<div class="form-group row">
                        <div class="col-sm-12">
                        <div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_active_camp_url">'. __( 'API Access URL', "survey-maker" ) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" 
                                    class="ays-text-input" 
                                    id="ays_active_camp_url" 
                                    name="ays_active_camp_url"
                                    value="'. $active_camp_url .'"
                                />
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_active_camp_api_key">'. __( 'API Access Key', "survey-maker" ) .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" 
                                    class="ays-text-input" 
                                    id="ays_active_camp_api_key" 
                                    name="ays_active_camp_api_key"
                                    value="'. $active_camp_api_key .'"
                                />
                            </div>
                        </div>
                <blockquote>';
        $content .= __( "Your API URL and Key can be found in your account on the My Settings page under the “Developer” tab.");
        $content .= '</blockquote>
            </div>
        </div>
        </div>
        </div>';

        $integrations['active_camp'] = array(
            'content' => $content,
            'icon' => $icon,
            'title' => $title
        );

        return $integrations;
    }

    // ===== Active Campaign end =====
    
    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Slack start =====
    
        // Slack integration / survey page

        // Slack integration in survey page content
        public function ays_survey_page_slack_content($integrations, $args){

            $content = '';
            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/slack_logo.png';
            $title = __('Slack Settings',"survey-maker");
            $content = '';

            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';

            $content .= '
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_enable_slack">'.__("Enable Slack integration", "survey-maker").'</label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_enable_slack" value="on">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_slack_conversation">'.__("Slack conversation", "survey-maker").'</label>
                </div>
                <div class="col-sm-8">';
            $content .= '<select name="ays_slack_conversation" id="ays_slack_conversation">
                    <option value="" disabled selected>'.__("Select Channel", "survey-maker").'</option>';
            $content .= '</select>';
            $content .= '</div></div>';
            $content .= '</div></div>';

            $integrations['slack'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Slack integration / settings page

        // Slack integration in General settings page content
        public function ays_settings_page_slack_content( $integrations, $args ){
        $actions = $this->settings_obj;
        
        $slack_res    = ($actions->ays_get_setting('slack') === false) ? json_encode(array()) : $actions->ays_get_setting('slack');
        $slack        = json_decode($slack_res, true);
        $slack_client = isset($slack['client']) ? $slack['client'] : '';
        $slack_secret = isset($slack['secret']) ? $slack['secret'] : '';
        $slack_token  = isset($slack['token']) ? $slack['token'] : '';
        $slack_oauth  = '';
        
        $data_code = '';
        $code_content = sprintf(__("1. You will need to " . "<a href='%s' target='_blank'>%s</a>" . " new Slack App.", "survey-maker"), "https://api.slack.com/apps?new_app=1", "create");
        $server_http = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://")) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&oauth=slack";
        $slack_readonly = $slack_oauth ? '' : 'readonly';
        if ($slack_oauth) {
            $slack_temp_code = "";
            $slack_client    = "";
            $data_code       = !empty($slack_temp_code) ? $slack_temp_code : "";
            $ays_survey_tab  = 'tab2';
        }
        
        $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/slack_logo.png';
        $title = __( 'Slack', "survey-maker" );

        $content = '';
        $content .= '<div class="form-group row" style="margin:0px;">';
        $content .= '<div class="col-sm-12 only_pro">';
            $content .= '<div class="pro_features">';
                $content .= '<div>';
                    $content .= '<p>';
                        $content .= __("This feature is available only in ", "survey-maker");
                        $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                    $content .= '</p>';
                $content .= '</div>';
            $content .= '</div>';
        $content .= '<div class="form-group row">
                        <div class="col-sm-12">';
        if(!$slack_oauth){
            $content .= '<div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <button id="slackInstructionsPopOver" type="button" class="btn btn-info" title="'.__("Slack Integration Setup Instructions", "survey-maker").'">'.__("Instructions", "survey-maker").'</button>
                                <div class="d-none" id="slackInstructions">
                                    <p>'.$code_content.'</p>
                                    <p>'.__("2. Complete Project creation for get App credentials.", "survey-maker").'</p>
                                    <p>'.__("3. Next, go to the Features > OAuth & Permissions > Redirect URLs section.", "survey-maker").'</p>
                                    <p>'.__("4. Click Add a new Redirect URL.", "survey-maker").'</p>
                                    <p>'.__("5. In the shown input field, put this value below", "survey-maker").'</p>
                                    <p>
                                        <code>'.$server_http.'</code>
                                    </p>
                                    <p>'.__("6. Then click the Add button.", "survey-maker").'</p>
                                    <p>'.__("7. Then click the Save URLs button.", "survey-maker").'</p>
                                </div>
                            </div>
                        </div>';
        }
        $content .= '<div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_slack_client">
                                '.__("App Client ID", "survey-maker").'
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_slack_client" name="ays_slack_client" value='.$slack_client.'>
                        </div>
                    </div>
                    <hr/>';
        $content .= '<div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_slack_oauth">'.__("Slack Authorization", "survey-maker").'</label>
                        </div>
                        <div class="col-sm-9">';
                        if($slack_oauth){
                            $content .= '<span class="btn btn-success pointer-events-none">'.__("Authorized", "survey-maker").'</span>';
                        }
                        else{
                            $content .= '<button type="button" id="slackOAuth2" class="btn btn-outline-secondary disabled">'.__("Authorize", "survey-maker").'</button>';
                        }

        $content .= '</div>
                    </div>
                    <hr/>';
        $content .= '<div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_slack_secret">'.__('App Client Secret', "survey-maker").'</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_slack_secret" name="ays_slack_secret" value="'.$slack_secret.'" '.$slack_readonly.'>
                        </div>
                    </div>
                    <hr/>';                    
        $content .= '<div class="form-group row" aria-describedby="aaa">
                        <div class="col-sm-3">
                            <label for="ays_slack_oauth">'.__('App Access Token', "survey-maker").'</label>
                        </div>
                        <div class="col-sm-9">';
                        if($slack_oauth){
                            $content .= '<button type="button" data-code='.$data_code.' id="slackOAuthGetToken" data-success='.__("Access granted", "survey-maker").' class="btn btn-outline-secondary disabled">'.__("Get it", "survey-maker").'</button>';
                        }
                        else{
                            $content .= '<button type="button" class="btn btn-outline-secondary disabled">'.__("Need Authorization", "survey-maker").'</button>';
                            $content .= '<input type="hidden" id="ays_slack_token" name="ays_slack_token" value="'.$slack_token.'">';
                        }
        $content .= '</div></div>';

        $content .= '<blockquote>
                        '.__( "You can get your App Client ID and Client Secret from your App's Basic Information page.").'
                    </blockquote>
            </div>
        </div>
        </div>
        </div>';

        $integrations['slack'] = array(
            'content' => $content,
            'icon' => $icon,
            'title' => $title
        );

        return $integrations;
    }

    // ===== Slack end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Google start =====

        // Google integration / survey page

        // Google integration in survey page content
        public function ays_survey_page_google_sheet_content($integrations, $args){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/sheets_logo.png';
            $title = __('Google Settings',"survey-maker");
            $content = "";
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_enable_google">
                                    '. __('Enable Google integration', "survey-maker") .'
                                </label>
                            </div>
                            <div class="col-sm-1">
                                <input type="checkbox" class="ays-enable-timer1" value="on">
                            </div>
                        </div>
                        <hr></div></div>';
            $integrations['google'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

        // Google integration / settings page

        // Google integration in General settings page content
        public function ays_settings_page_google_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/sheets_logo.png';
            $title = __( 'Google', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
            $content .= '<div class="form-group row">
                            <div class="col-sm-12">
                                <div class="form-group row" aria-describedby="aaa">
                                    <div class="col-sm-3">
                                        <button id="googleInstructionsPopOver" type="button" class="btn btn-info" data-original-title="Google Integration Setup Instructions" >'. __("Instructions", "survey-maker"). '</button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_google_client">
                                            '. __("Google Client ID", "survey-maker") .'
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input">
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3">
                                        <label for="ays_google_secret">
                                            '. __("Google Client Secret", "survey-maker") .'
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input">
                                        <input type="hidden">
                                    </div>
                                </div>
                                <hr/>
                                <div class="form-group row">
                                    <div class="col-sm-3"></div>
                                    <div class="col-sm-9">
                                        <button type="submit" class="btn btn-outline-info">
                                            '. __("Connect", "survey-maker") .'
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['google'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

    // ===== Google end =====


    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== GamiPress start =====

        // GamiPress integration / survey page | GamiPress Nooo :D | Aro | Start

        // GamiPress integration / settings page

        // GamiPress integration in Gengeral settings page content
        public function ays_settings_page_gamipress_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/gamipress_logo.png';
            $title = __( 'GamiPress', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
                $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <blockquote>' .
                            __( "Install the GamiPress plugin to use the integration. Configure the settings from the Automatic Points Awards section from the GamiPres plugin.", "survey-maker" ) . '
                            <br>' .
                            __( "After enabling the integration, the Survey Maker will automatically be added to the event list.", "survey-maker" ) . '
                        </blockquote>';
                $content .= '
                    </div>
                </div>';
            $content .= '
                </div>
            </div>';

            $integrations['gamipress'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

        // GamiPress | Aro | End

    // ===== GamiPress end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== SendGrid start =====

        // SendGrid integration / settings page

        // SendGrid integration in Gengeral settings page content
        public function ays_settings_page_sendgrid_content( $integrations, $args ){
            $actions = $this->settings_obj;

            $sendgrid_res     = ($actions->ays_get_setting('sendgrid') === false) ? json_encode(array()) : $actions->ays_get_setting('sendgrid');
            $sendgrid         = json_decode($sendgrid_res, true);
            $sendgrid_api_key = isset($sendgrid['apiKey']) ? $sendgrid['apiKey'] : '';

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/sendgrid_logo.png';
            $title = __( 'SendGrid', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
                $content .= '
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="form-group row" aria-describedby="aaa">
                            <div class="col-sm-3">
                                <label for="ays_sendgrid_username">'. __('SendGrid API Key',"survey-maker") .'</label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_sendgrid_api_key"/>
                            </div>
                        </div>
                        <hr/>
                        <blockquote>';
                $content .= sprintf( __( "You can get your API key from", "survey-maker" ) . " <a href='%s' target='_blank'> %s.</a>", "https://app.sendgrid.com/settings/api_keys", "sendgrid.com" );
                $content .= '
                        </blockquote>
                    </div>
                </div>';
            $content .= '
                </div>
            </div>';

            $integrations['sendgrid'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title
            );

            return $integrations;
        }

    // ===== SendGrid end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Madmimi start =====

        // Madmimi integration / settings page

        // Mad mimi integration in General settings page content
        public function ays_settings_page_mad_mimi_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mad-mimi-logo.png';
            $title = __( 'Mad Mimi', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label>'. __('Username', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_mad_mimi_api_key">'. __('API Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>';
                            $content .= '<blockquote>';
                            $content .= sprintf( __( "You can get your API key from your ", "survey-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://madmimi.com/user/edit?account_info_tabs=account_info_personal", "Account" );
                            $content .= '</blockquote>';
                                $content .= '
                                    </div>
                                </div>';
                        $content .= '
                            </div>
                        </div>';

            $integrations['mad_mimi'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // Mad mimi integration in survey page content
        public function ays_survey_page_mad_mimi_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mad-mimi-logo.png';
            $title = __('Mad Mimi Settings',"survey-maker");

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_mad_mimi">'. __('Enable Mad Mimi', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label >'. __('Select List', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select>
                                            <option disabled selected>Select list</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>';

            $integrations['mad_mimi'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

    // ===== Madmimi end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== GetResponse start =====

        // GetResponse integration / settings page

        // GetResponse integration in General settings page content
        public function ays_settings_page_get_response_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/get_response.png';
            $title = __( 'GetResponse', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_getresponse_api_key">'. __('GetResponse API Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>';
                            $content .= '<blockquote>';
                            $content .= sprintf( __( "You can get your API key from your ", "survey-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.getresponse.com/api", "account" );
                            $content .= '</blockquote>';
                            $content .= '<blockquote>';
                            $content .= __( "For security reasons, unused API keys expire after 90 days. When that happens, you'll need to generate a new key.", "survey-maker" );
                            $content .= '</blockquote>';
                            $content .= '
                                    </div>
                                </div>';
                        $content .= '
                            </div>
                        </div>';

            $integrations['get_response'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // GetResponse integration in survey page content
        public function ays_survey_page_get_response_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/get_response.png';
            $title = __('GetResponse Settings',"survey-maker");
            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_getResponse">'. __('Enable GetResponse', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>'. __('GetResponse List', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select >
                                            <option selected disabled>Select list</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['get_response'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

    // ===== GetResponse end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== ConvertKit start =====

        // ConvertKit integration / settings page

        // ConvertKit Settings integration in General settings page content
        public function ays_settings_page_convert_kit_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/convertkit_logo.png';
            $title = __( 'ConvertKit', "survey-maker" );

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_convert_kit">'. __('API Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>';
                            $content .= '<blockquote>';
                            $content .= sprintf( __( "You can get your API key from your ", "survey-maker" ) . "<a href='%s' target='_blank'> %s.</a>", "https://app.convertkit.com/account/edit", "Account" );
                            $content .= '</blockquote>';
                            $content .= '
                                    </div>
                                </div>';
                    $content .= '
                            </div>
                        </div>';

            $integrations['convertKit'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // ConvertKit Settings integration in survey page content
        public function ays_survey_page_convert_kit_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/convertkit_logo.png';
            $title = __('ConvertKit Settings',"survey-maker");

            $content = '';
            $content .= '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("PRO version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_convertkit">'. __('Enable ConvertKit', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_convertKit_list">'. __('ConvertKit List', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select>
                                            <option selected disabled>Select list</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['convertKit'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

    // ===== ConvertKit end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    
    // ===== Paypal Settings start =====

        // PayPal Settings integration

        // PayPal Settings integration in survey page content
        public function ays_survey_page_PayPal_content( $integrations, $args ){
            $editor_id = 'ays_survey_paypal_message';
            $settings = array(
                'editor_height' => 150,
                'textarea_name' => 'ays_survey_paypal_message',
                'editor_class' => 'ays-textarea',
                'media_elements' => false,
                
            );

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/paypal_logo.png';
            $title = __('PayPal Settings',"survey-maker");
            ob_start();
            $content = '';
            wp_editor("", $editor_id, $settings);
            $content .= '<div class="form-group row" style="padding: 10px;">
                            <div class="col-sm-12 only_pro">                                
                                <div class="pro_features">
                                    <div>
                                        <p style="position: absolute;bottom: 20px; right: 20px;">
                                            '.__("This feature is available only in ", "survey-maker"). '
                                            <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature">'. __("DEVELOPER version!!!", "survey-maker") .'</a>
                                        </p>
                                        <p style="position: absolute;top: 5px; right: 20px;height: 40px; background-color: #8080802e;">
                                            '.__("This feature is available only in ", "survey-maker"). '
                                            <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature">'. __("DEVELOPER version!!!", "survey-maker") .'</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_paypal">
                                            '.__('Enable PayPal',"survey-maker").'
                                        </label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" value="on" checked>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_paypal_amount">
                                            '.__('Amount',"survey-maker").'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <input type="text" class="ays-text-input ays-text-input-short">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_paypal_currency">
                                            '.__('Currency',"survey-maker").'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input ays-text-input-short">';                                        
                                            $content .= '<option selected >-Currency-</option>';
                            $content .= '</select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_paypal_currency">
                                            '.__('Payment details',"survey-maker").'
                                        </label>
                                    </div>
                                    <div class="col-sm-8">';
                                    $editor_contents = ob_get_clean();
                                    $content .= $editor_contents;
                                    $content .= '</div>
                                </div>
                            </div>
                        </div>';
            
            
            $integrations['PayPal'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // PayPal Settings integration in General settings page content
        public function ays_settings_page_PayPal_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/paypal_logo.png';
            $title = __( 'PayPal', "survey-maker" );
            $blockquote_content = sprintf( __( "You can get your Client ID from %s", "survey-maker" ), "<a href='https://developer.paypal.com/developer/applications' target='_blank'> Developer Paypal.</a>");
            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("DEVELOPER version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-12">';
                            $content .= '<div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_paypal_client_id">'.__('Paypal Client ID',"survey-maker").'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" >
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label>'.__('Payment terms',"survey-maker").'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <label class="ays_survey_loader" style="display:inline-block;">
                                                    <input type="radio" value="lifetime" checked/>
                                                    <span>'.__('Lifetime payment',"survey-maker").'</span>
                                                </label>
                                                <label class="ays_survey_loader" style="display:inline-block;">
                                                    <input type="radio" value="onetime" />
                                                    <span>'.__('Onetime payment',"survey-maker").'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <blockquote>
                                            '.$blockquote_content.'
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['PayPal'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

    // ===== Paypal Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
    //====================================================================================//
    ////////////////////////////////////////////////////////////////////////////////////////

    // ===== Stripe Settings start =====

        // Stripe Settings integration

        // Stripe Settings integration in survey page content
        public function ays_survey_page_Stripe_content( $integrations, $args ){
            // WP editor settings
            $editor_id = 'ays_survey_stripe_message';
            $settings = array(
                'editor_height' => 150,
                'textarea_name' => 'ays_survey_stripe_message',
                'editor_class' => 'ays-textarea',
                'media_elements' => false,
                
            );

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/stripe_logo.png';
            $title = __('Stripe Settings',"survey-maker");
            ob_start();
            $content = '';
                wp_editor("", $editor_id, $settings);
                $content .= '<div class="form-group row" style="padding: 10px;">
                                <div class="col-sm-12 only_pro">                                    
                                    <div class="pro_features">
                                        <div>
                                            <p style="position: absolute;bottom: 20px; right: 20px;">
                                                '.__("This feature is available only in ", "survey-maker"). '
                                                <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature">'. __("DEVELOPER version!!!", "survey-maker") .'</a>
                                            </p>
                                            <p style="position: absolute;top: 5px; right: 20px;height: 40px; background-color: #8080802e;">
                                                '.__("This feature is available only in ", "survey-maker"). '
                                                <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature">'. __("DEVELOPER version!!!", "survey-maker") .'</a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_enable_stripe">
                                                '.__('Enable Stripe',"survey-maker").'
                                            </label>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_stripe"
                                                value="off" checked>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_stripe_amount">
                                                '.__('Amount',"survey-maker").'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text"
                                                class="ays-text-input ays-text-input-short">
                                                <span class="ays_option_description">'. __( "Specify the amount of the payment.", "survey-maker" ) .'</span>
                                                <span class="ays_option_description">'. __( "This field doesn't accept an empty value or a value less than 1.", "survey-maker" ).'</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_stripe_currency">
                                                '.__('Currency',"survey-maker").'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="ays-text-input ays-text-input-short">';
                                                    $content .= '<option seleted value="" >-Currency-</option>';
                                $content .= '</select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_stripe_currency">
                                                '.__('Payment details',"survey-maker").'
                                            </label>
                                        </div>
                                        <div class="col-sm-8">';
                                            $editor_contents = ob_get_clean();
                                            $content .= $editor_contents;
                                            $content .= '</div>
                                        </div>
                                    </div>
                            </div>';
            
            
            $integrations['stripe'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }
        // Stripe Settings integration / settings page

        // Stripe Settings integration in General settings page content
        public function ays_settings_page_Stripe_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/stripe_logo.png';
            $title = __( 'Stripe', "survey-maker" );
            $blockquote_content = __( "You can get your Publishable and Secret keys on API Keys page on your Stripe dashboard.", "survey-maker" );
            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("DEVELOPER version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-12">';
                            $content .=  '<div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_stripe_api_key">'.__('Stripe Publishable Key',"survey-maker").'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" 
                                                    class="ays-text-input">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_stripe_secret_key">'.__('Stripe Secret Key',"survey-maker").'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" 
                                                    class="ays-text-input"
                                                    value="">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label>'.__('Payment terms',"survey-maker").'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <label class="ays_survey_loader" style="display:inline-block;">
                                                    <input type="radio" value="lifetime" checked/>
                                                    <span>'.__('Lifetime payment',"survey-maker").'</span>
                                                </label>
                                                <label class="ays_survey_loader" style="display:inline-block;">
                                                    <input type="radio" value="onetime" />
                                                    <span>'.__('Onetime payment',"survey-maker").'</span>
                                                </label>
                                            </div>
                                        </div>
                                        <blockquote>
                                            '.$blockquote_content.'
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['stripe'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title
            );

            return $integrations;
        }

    // ===== Stripe Settings end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== reCAPTCHA start =====

        // reCAPTCHA integration

        // reCAPTCHA integration in survey page content
        public function ays_survey_page_recaptcha_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/recaptcha_logo.png';
            $title = __('reCAPTCHA Settings',"survey-maker");

            $content = '';
            $disabled = "disabled";
            $checked =  '';
            $content = '<div class="form-group row" style="margin:0px;">';
            $content .= '<div class="col-sm-12 only_pro">';
                $content .= '<div class="pro_features">';
                    $content .= '<div>';
                        $content .= '<p>';
                            $content .= __("This feature is available only in ", "survey-maker");
                            $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("DEVELOPER version!!!", "survey-maker") .'</a>';
                        $content .= '</p>';
                    $content .= '</div>';
                $content .= '</div>';
                $content .= '<hr/>';
            $content .= '<div class="form-group row">
                    <div class="col-sm-4">
                        <label for="ays_survey_enable_recaptcha">'. __('Enable reCAPTCHA', "survey-maker") .'</label>
                    </div>
                    <div class="col-sm-1">
                        <input type="checkbox" class="ays-enable-timer1"/>
                    </div>
                    </div>
                    </div>
                </div>';
            
            

            $integrations['recaptcha'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

        // reCAPTCHA integration / settings page

        // reCAPTCHA integration in General settings page content
        public function ays_settings_page_recaptcha_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/recaptcha_logo.png';
            $title = __( 'reCAPTCHA', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("DEVELOPER version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_recaptcha_site_key">'. __('reCAPTCHA v2 Site Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_recaptcha_secret_key">'. __('reCAPTCHA v2 Secret Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" >
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_recaptcha_language">'. __('reCAPTCHA Language', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input" >
                                                <span class="ays_survey_small_hint_text">
                                                    <span>' . sprintf(
                                                        __( "e.g. en, de - Language used by reCAPTCHA. To get the code for your language click %s here %s", "survey-maker" ),
                                                        '<a href="https://developers.google.com/recaptcha/docs/language" target="_blank">',
                                                        "</a>"
                                                    ) . '</span>
                                                </span>
                                            </div>
                                        </div>
                                        <hr/>
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_recaptcha_theme">'. __('reCAPTCHA Theme', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="ays-text-input" id="ays_survey_recaptcha_theme" name="ays_survey_recaptcha_theme" >
                                                    <option value="light" selected>'. __('Light', "survey-maker") .'</option>
                                                </select>
                                            </div>
                                        </div>
                                        ';
                                        $content .= '<blockquote>';
                                        $content .= sprintf( __( "You need to set up reCAPTCHA in your Google account to generate the required keys and get them by %s Google's reCAPTCHA admin console %s.", "survey-maker" ), "<a href='https://www.google.com/recaptcha/admin/create' target='_blank'>", "</a>");
                                        $content .= '</blockquote>';
                                        $content .= '
                                    </div>
                                </div>
                            </div>
                        </div>';

            $integrations['recaptcha'] = array(
                'content' => $content,
                'icon' => $icon,
                'title' => $title,
            );

            return $integrations;
        }

    // ===== reCAPTCHA end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== Aweber start =====

        // Aweber integration

        // Aweber integration in survey page content
        public function ays_survey_page_aweber_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/aweber-logo.png';
            $title = __('Aweber Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-4">';
                            $content .= '<label for="ays_survey_enable_aweber">'. __('Enable Aweber', "survey-maker") .'</label>';
                        $content .= '</div>';
                        $content .='<div class="col-sm-1">';
                            $content .= '<input type="checkbox" class="ays-enable-timer1" />';
                        $content .= '</div>';
                    $content .= '</div>';	
                    $content .= '<hr>';
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-4">';
                            $content .= '<label for="ays_survey_aweber_list_id">'. __('Aweber Lists', "survey-maker") .'</label>';
                        $content .= '</div>';
                        $content .= '<div class="col-sm-8">';
                            $content .= '<select  class="ays-text-input">';
                                $content .= '<option value="" >Select list</option>';
                            $content .= '</select>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['aweber'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Aweber integration / settings page

        // Aweber integration in General settings page content
        public function ays_aweber_settings_page_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/aweber-logo.png';
            $title = __( 'Aweber', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">';
                        $content .= '<div class="col-sm-12">';
                            $content .= '<div class="form-group row">
                                            <div class="col-sm-3">
                                                <button id="aweberInstructionsPopOver" type="button" class="btn btn-info" data-original-title="Aweber Integration Setup Instructions" >'.__('Instructions', "survey-maker").'</button>                                                
                                            </div>
                                        </div>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3">';
                                    $content .= '<label for="ays_survey_aweber_client_id">'. __('Client ID', "survey-maker") .'</label>';
                                $content .= '</div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<input type="text" class="ays-text-input">';
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<hr>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3">';
                                    $content .= '<label for="ays_survey_aweber_client_secret">'. __('Client Secret', "survey-maker") .'</label>';
                                $content .= '</div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<input type="text" class="ays-text-input">';							
                                $content .= '</div>';
                            $content .= '</div>';
                            $content .= '<hr>';
                            $content .= '<div class="form-group row">';
                                $content .= '<div class="col-sm-3"></div>';
                                $content .= '<div class="col-sm-9">';
                                    $content .= '<button type="submit" class="btn btn-outline-info">'.__("Connect", "survey-maker").'</button>';
                                $content .= '</div>';
                            $content .= '</div>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['aweber'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== Aweber end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== MailPoet start =====

        // MailPoet integration

        // MailPoet integration in survey page content
        public function ays_survey_page_mailpoet_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL .'/images/integrations/mail_poet.png';
            $title = __('MailPoet Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_enable_mailpoet">'. __('Enable MailPoet',"survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1">';
                                $content .= '
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_mailpoet_list">'. __('MailPoet list',"survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">';                                
                            $content .= '<select class="ays-text-input">';
                                $content .= '<option value="" >'. __( "Select list", "survey-maker" ) .'</option>';                                    
                            $content .= '</select>';
                        $content .= '</div>
                                </div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mailpoet'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // MailPoet integration / settings page

        // MailPoet integration in General settings page content
        public function ays_settings_page_mailpoet_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mail_poet.png';
            $title = __( 'MailPoet', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro" style="padding: 10px 0 10px 10px;">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here start
                    $content .= '<blockquote style="width: 50%; margin: initial;">'.__('To choose a list, please go to the <strong>Integration</strong> tab of the given survey.' , "survey-maker").'</blockquote>'; 
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mailpoet'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== MailPoet end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////

    // ===== MyCred start =====

        // MyCred integration / settings page

        // MyCred integration in General settings page content
        public function ays_mycred_settings_page_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/mycred_icon.png';
            $title = __( 'MyCred', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro" style="padding: 10px 0 10px 10px;">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here start
                    $content .= '<blockquote style="width: 50%; margin: initial;">';
                        $content .= __( "Setup your first point type and go to the Hook page. Choose Survey Maker from the Available Hooks list.", "survey-maker" );
                    $content .= '<br>';
                        $content .= __( " Configure the settings and save the hook.", "survey-maker"  );
                    $content .= '</blockquote>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['mycred'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== MyCred end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////
    
    // ===== Klaviyo start =====

        // Klaviyo integration

        // Klaviyo integration in survey page content
        public function ays_survey_page_klaviyo_content( $integrations, $args ){

            $icon = SURVEY_MAKER_ADMIN_URL .'/images/integrations/klaviyo-logo.png';
            $title = __('Klaviyo Settings',"survey-maker");
            
            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    $content .= '<hr/>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_enable_klaviyo">'. __('Enable Klaviyo', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-1">
                                        <input type="checkbox" class="ays-enable-timer1" />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label for="ays_survey_klaviyo_list">'. __('Select List', "survey-maker") .'</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <select class="ays-text-input">
                                            <option>Select list</option>
                                        </select>
                                    </div>';
                    $content .= '</div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['klaviyo'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

        // Klaviyo integration / settings page

        // Klaviyo integration in General settings page content
        public function ays_settings_page_klaviyo_content( $integrations, $args ){

            $icon  = SURVEY_MAKER_ADMIN_URL . '/images/integrations/klaviyo-logo.png';
            $title = __( 'Klaviyo', "survey-maker" );

            $content = '<div class="form-group row" style="margin:0px;">';
                $content .= '<div class="col-sm-12 only_pro">';
                    $content .= '<div class="pro_features">';
                        $content .= '<div>';
                            $content .= '<p>';
                                $content .= __("This feature is available only in ", "survey-maker");
                                $content .= '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"> ' .__("AGENCY version!!!", "survey-maker") .'</a>';
                            $content .= '</p>';
                        $content .= '</div>';
                    $content .= '</div>';
                    // Content part reaplce here start
                    $content .= '<div class="form-group row">
                                    <div class="col-sm-12">                        
                                        <div class="form-group row">
                                            <div class="col-sm-3">
                                                <label for="ays_survey_Klaviyo_api_key">'. __('API Key', "survey-maker") .'</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" class="ays-text-input">
                                            </div>
                                        </div>';
                            $content .= '<blockquote>';
                                $content .= __( "You can get your API key from your Account.", "survey-maker" );
                            $content .= '</blockquote>';
                            $content .= '
                                    </div>
                                </div>';
                    // Content part reaplce here end
                $content .= '</div>';
            $content .= '</div>';

            $integrations['klaviyo'] = array(
                'content' => $content,
                'icon'    => $icon,
                'title'   => $title,
            );

            return $integrations;
        }

    // ===== Klaviyo end =====

    ////////////////////////////////////////////////////////////////////////////////////////
	//====================================================================================//
	////////////////////////////////////////////////////////////////////////////////////////
}
