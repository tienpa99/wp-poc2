<?php

class SQ_Models_Settings
{

    public function saveSettings(){
        //Save the settings
        if (!empty($_POST)) {
            SQ_Classes_ObjController::getClass('SQ_Models_Settings')->saveValues($_POST);
        }

        //Save custom links
        if (SQ_Classes_Helpers_Tools::getIsset('links_permission')) {
            $links = SQ_Classes_Helpers_Tools::getValue('links_permission', '', true);
            $links = explode(PHP_EOL, $links);
            $links = str_replace("\r", "", $links);

            if (!empty($links)) {
                SQ_Classes_Helpers_Tools::$options['sq_external_exception'] = array_unique($links);
            }
        }

        //Make sure we get the Sitemap data from the form
        if ($sitemap = SQ_Classes_Helpers_Tools::getValue('sitemap')) {
            foreach (SQ_Classes_Helpers_Tools::$options['sq_sitemap'] as $key => $value) {
                if (isset($sitemap[$key])) {
                    SQ_Classes_Helpers_Tools::$options['sq_sitemap'][$key][1] = (int)$sitemap[$key];
                } elseif ($key <> 'sitemap') {
                    SQ_Classes_Helpers_Tools::$options['sq_sitemap'][$key][1] = 0;
                }
            }
        }

        //delete other sitemap xml files from root
        if (SQ_Classes_Helpers_Tools::getOption('sq_auto_sitemap') && file_exists(ABSPATH . "/" . 'sitemap.xml')) {
            @rename(ABSPATH . "/" . 'sitemap.xml', ABSPATH . "/" . 'sitemap_ren' . time() . '.xml');
        }

        //Save custom robots
        if (SQ_Classes_Helpers_Tools::getIsset('robots_permission')) {
            $robots = SQ_Classes_Helpers_Tools::getValue('robots_permission', '', true);
            $robots = explode(PHP_EOL, $robots);
            $robots = str_replace("\r", "", $robots);

            if (!empty($robots)) {
                SQ_Classes_Helpers_Tools::$options['sq_robots_permission'] = $robots;
            }
        }

        /* if there is an icon to upload */
        if(SQ_Classes_Helpers_Tools::getOption('sq_auto_favicon')) {
            if (!empty($_FILES['favicon'])) {
                //If the favicon is turned off delete the favicon image created
                if (SQ_Classes_Helpers_Tools::getOption('favicon') <> '' && file_exists(ABSPATH . "/" . 'favicon.ico')) {
                    @rename(ABSPATH . "/" . 'favicon.ico', ABSPATH . "/" . 'favicon_ren' . time() . '.ico');
                }

                if ($return = SQ_Classes_ObjController::getClass('SQ_Models_Ico')->addFavicon($_FILES['favicon'])) {
                    if ($return['favicon'] <> '') {
                        SQ_Classes_Helpers_Tools::saveOptions('favicon', strtolower(basename($return['favicon'])));
                    }
                }
            }
        }

        SQ_Classes_Helpers_Tools::$options['sq_jsonld']['Person']['telephone'] = SQ_Classes_Helpers_Sanitize::checkTelephone(SQ_Classes_Helpers_Tools::$options['sq_jsonld']['Person']['telephone']);
        SQ_Classes_Helpers_Tools::$options['sq_jsonld']['Organization']['contactPoint']['telephone'] = SQ_Classes_Helpers_Sanitize::checkTelephone(SQ_Classes_Helpers_Tools::$options['sq_jsonld']['Organization']['contactPoint']['telephone']);


        //save the options in database
        SQ_Classes_Helpers_Tools::saveOptions();

        //reset the report time
        SQ_Classes_Helpers_Tools::saveOptions('seoreport_time', false);

    }
    /**
     * Save the Values in database
     *
     * @param $params
     */
    public function saveValues($params)
    {
        //Save the option values
        foreach ($params as $key => $value) {
            if (in_array($key, array_keys(SQ_Classes_Helpers_Tools::$options))) {

                //Initialize the array for some options
                if (in_array($key, array('sq_sla_exclude_post_types'))) {
                    SQ_Classes_Helpers_Tools::$options[$key] = array();
                }

                if (is_array(SQ_Classes_Helpers_Tools::$options[$key])) {

                    //Sanitize each value from subarray
                    $array = SQ_Classes_Helpers_Tools::getValue($key);

                    //Save the array values
                    if (is_array($array)) {
                        if (!empty($array)) {
                            foreach ($array as $subkey => $subvalue) {
                                switch ($subkey) {
                                case 'google_wt':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkGoogleWTCode($value[$subkey]);
                                    break;
                                case 'bing_wt':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkBingWTCode($value[$subkey]);
                                    break;
                                case 'baidu_wt':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkBaiduWTCode($value[$subkey]);
                                    break;
                                case 'yandex_wt':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkYandexWTCode($value[$subkey]);
                                    break;
                                case 'pinterest_verify':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkPinterestCode($value[$subkey]);
                                    break;
                                case 'google_analytics':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkGoogleAnalyticsCode($value[$subkey]);
                                    break;
                                case 'facebook_pixel':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkFacebookPixel($value[$subkey]);
                                    break;
                                case 'twitter_site':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkTwitterAccount($value[$subkey]);
                                    SQ_Classes_Helpers_Tools::$options[$key]['twitter'] = SQ_Classes_Helpers_Sanitize::checkTwitterAccountName($value[$subkey]);
                                    break;
                                case 'facebook_site':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkFacebookAccount($value[$subkey]);
                                    break;
                                case 'pinterest_url':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkPinterestAccount($value[$subkey]);
                                    break;
                                case 'instagram_url':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkInstagramAccount($value[$subkey]);
                                    break;
                                case 'linkedin_url':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkLinkeinAccount($value[$subkey]);
                                    break;
                                case 'youtube_url':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkYoutubeAccount($value[$subkey]);
                                    break;
                                case 'fb_admins':
                                    if (is_array($value[$subkey]) && !empty($value[$subkey])) {
                                        foreach ($value[$subkey] as $index => $admin) {
                                            $value[$subkey][$index] = SQ_Classes_Helpers_Sanitize::checkFacebookAdminCode($admin);
                                        }

                                        $subvalue = $value[$subkey];
                                    }
                                    break;
                                case 'fbadminapp':
                                    $subvalue = SQ_Classes_Helpers_Sanitize::checkFacebookApp($value[$subkey]);
                                    break;
                                }
                                SQ_Classes_Helpers_Tools::$options[$key][$subkey] = $subvalue;
                            }
                        }
                    }

                    //sanitize the value and save it
                    SQ_Classes_Helpers_Tools::saveOptions();
                } else {

                    //sanitize the value and save it
                    SQ_Classes_Helpers_Tools::saveOptions($key, SQ_Classes_Helpers_Tools::getValue($key));
                }
            }
        }
    }


}
