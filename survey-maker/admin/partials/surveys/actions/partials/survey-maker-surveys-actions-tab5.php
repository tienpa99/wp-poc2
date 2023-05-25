<div id="tab5" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab5') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Limitation of Users',"survey-maker"); ?></p>
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_limit_users">
                <?php echo __('Maximum number of attempts per user',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, you can manage the attempts count per user for taking the survey.',"survey-maker")?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_limit_users" name="ays_survey_limit_users"
                   value="on" <?php echo ($survey_limit_users) ? 'checked' : ''; ?>/>
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_limit_users) ? "" : "display_none_not_important"; ?>">
            <div class="ays-limitation-options">
                <!-- Limitation by -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_limit_users_by_ip">
                            <?php echo __('Detect users by',"survey-maker")?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                                echo htmlspecialchars( sprintf(__('Choose the method of user detection:',"survey-maker") . '
                                    <ul class="ays_help_ul">
                                        <li>' .__('%s By IP %s - Detect the users by their IP addresses and limit them. This will work both for guests and registered users. Note: in general, IP is not a static variable, it is constantly changing when the users change their location/ WIFI/ Internet provider.',"survey-maker") . '</li>
                                        <li>' .__('%s By User ID %s - Detect the users by their WP User IDs and limit them. This will work only for registered users. It\'s recommended to use this method to get more reliable results.',"survey-maker") . '</li>
                                        <li>' .__('%s By Cookie %s - Detect the users by their browser cookies and limit them.  It will work both for guests and registered users.',"survey-maker") . '</li>
                                        <li>' .__('%s By Cookie and IP %s - Detect the users both by their browser cookies and IP addresses and limit them. It will work both for guests and registered users.',"survey-maker") .'</li>
                                    </ul>',
                                    '<em>',
                                    '</em>',
                                    '<em>',
                                    '</em>',
                                    '<em>',
                                    '</em>',
                                    '<em>',
                                    '</em>'
                                ) );
                            ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_ip" class="form-check-input" name="ays_survey_limit_users_by" value="ip" <?php echo ($survey_limit_users_by == 'ip') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_ip"><?php echo __('IP',"survey-maker")?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_user_id" class="form-check-input" name="ays_survey_limit_users_by" value="user_id" <?php echo ($survey_limit_users_by == 'user_id') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_user_id"><?php echo __('User ID',"survey-maker")?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_cookie" class="form-check-input" name="ays_survey_limit_users_by" value="cookie" <?php echo ($survey_limit_users_by == 'cookie') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_cookie"><?php echo __('Cookie',"survey-maker")?></label>
                        </div>
                        <div class="form-check form-check-inline checkbox_ays">
                            <input type="radio" id="ays_limit_users_by_ip_cookie" class="form-check-input" name="ays_survey_limit_users_by" value="ip_cookie" <?php echo ($survey_limit_users_by == 'ip_cookie') ? 'checked' : ''; ?>/>
                            <label class="form-check-label" for="ays_limit_users_by_ip_cookie"><?php echo __('IP and Cookie',"survey-maker")?></label>
                        </div>
                    </div>
                </div>
                <hr/>
                <!-- Limitation count -->
                <div class="form-group row" style="margin:0px;">
                    <div class="col-sm-12 only_pro">
                        <div class="pro_features">
                            <div>
                                <p>
                                    <?php echo __("This feature is available only in ", "survey-maker"); ?>
                                    <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                                </p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_max_pass_count">
                                    <?php echo __('Attempts count',"survey-maker")?>
                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('.',"survey-maker")?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="number" class="ays-text-input" id="ays_survey_max_pass_count" value="1"/>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <!-- Limitation message -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_limitation_message">
                            <?php echo __('Message',"survey-maker")?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write the message for those survey takers who have already passed the survey under the given conditions.',"survey-maker")?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <?php
                        $content = $survey_limitation_message;
                        $editor_id = 'ays_survey_limitation_message';
                        $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_limitation_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                        wp_editor($content, $editor_id, $settings);
                        ?>
                    </div>
                </div>
                <hr/>
                <!-- Limitation redirect url -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_redirect_url">
                            <?php echo __('Redirect URL',"survey-maker")?>
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Redirect your visitors to a different URL.',"survey-maker")?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="text" name="ays_survey_redirect_url" id="ays_survey_redirect_url" class="ays-text-input" value="<?php echo esc_attr($survey_redirect_url); ?>"/>
                    </div>
                </div>
                <hr/>
                <!-- Limitation redirect delay -->
                <div class="form-group row">
                    <div class="col-sm-3">
                        <label for="ays_survey_redirection_delay">
                            <?php echo __('Redirect delay',"survey-maker")?>(s)
                            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose the delay on the redirect in seconds. If you set it 0, the redirection will be disabled.',"survey-maker")?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input type="number" name="ays_survey_redirection_delay" id="ays_survey_redirection_delay" class="ays-text-input" value="<?php echo esc_attr($survey_redirect_delay); ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Maximum number of attempts per user -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_logged_users">
                <?php echo __('Only for logged-in users',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, only logged-in users will be able to participate in the survey.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_logged_users" name="ays_survey_enable_logged_users" value="on" <?php echo ($survey_enable_logged_users) ? 'checked' : ''; ?> />
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo ($survey_enable_logged_users) ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_logged_in_message">
                        <?php echo __('Message',"survey-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Write a message for unauthorized users.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <?php
                    $content = $survey_logged_in_message;
                    $editor_id = 'ays_survey_logged_in_message';
                    $settings = array('editor_height' => $survey_wp_editor_height, 'textarea_name' => 'ays_survey_logged_in_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div>
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_show_login_form">
                        <?php echo __('Show Login form',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the login form to not logged-in users.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_show_login_form" name="ays_survey_show_login_form" value="on" <?php echo $survey_show_login_form ? 'checked' : ''; ?>/>
                </div>
            </div>
        </div>
    </div> <!-- Only for logged in users -->
    <hr/>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-3">
            <label for="ays_survey_enable_tackers_count">
                <?php echo __('Max count of takers', "survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Choose how many users can participate in the survey.',"survey-maker")?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" value="on" <?php echo $enable_takers_count ? 'checked' : ''; ?> name="ays_survey_enable_tackers_count" id="ays_survey_enable_tackers_count">
        </div>
        <div class="col-sm-8 ays_toggle_target ays_divider_left <?php echo  $enable_takers_count ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-2">
                    <label for="ays_survey_tackers_count">
                        <?php echo __('Count',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Indicate the number of users who can participate in the survey.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-10">
                    <input type="number" class="ays-enable-timerl ays-text-input" value="<?php echo esc_attr($survey_takers_count); ?>" name="ays_survey_tackers_count" id="ays_survey_tackers_count">
                </div>
            </div>
        </div>
    </div> <!-- Limitation count of takers -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
                <div class="pro_features_inner_2">
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_restriction_pass">
                        <?php echo __('Access only to selected user role(s)',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Make the survey available only for the user roles mentioned in the list. By enabling this option, the Only for logged-in users option will be enabled automatically.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" value="on" checked>
                </div>
                <div class="col-sm-8 ays_toggle_target ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_users_roles">
                                <?php echo __('User role(s)',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Select the role(s) of the user. The option accepts multiple values.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <select id="ays_survey_users_roles" multiple>                                
                                 <option value="" selected>User Role</option>                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_restriction_pass_message">
                                <?php echo __('Message',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Message for the users who aren’t included in the above-mentioned list.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php
                            $content = "";
                            $editor_id = 'ays_survey_restriction_pass_message';
                            $settings = array('editor_height' => '100', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                </div>
            </div> <!-- Only for selected user role -->
        </div>
    </div><!-- Only for selected user role -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
                <div class="pro_features_inner_2">
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_restriction_pass">
                        <?php echo __('Access only to selected user(s)',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Make the survey available only for the user roles mentioned in the list. By enabling this option, the Only for logged-in users option will be enabled automatically.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" value="on" checked>
                </div>
                <div class="col-sm-8 ays_toggle_target ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_users_roles">
                                <?php echo __('User(s)',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Select the role(s) of the user. The option accepts multiple values.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <select id="ays_survey_users_pro" multiple>                                
                                 <option value="" selected>User</option>                                
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_restriction_pass_message">
                                <?php echo __('Message',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Message for the users who aren’t included in the above-mentioned list.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php
                            $content = "";
                            $editor_id = 'ays_survey_restriction_pass_message_user';
                            $settings = array('editor_height' => '100', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Only selected users -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_password">
                        <?php echo __('Password for passing survey', "survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('You can choose a password for users to pass the survey.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <div>
                        <span style="font-size: 12px;padding: 2px 10px;font-style: italic;">
                            <a href="https://ays-demo.com/job-satisfaction-survey/" class="ays-survey-zindex-for-pro" style="color:#524e4e;position:relative;" target="_blank" title="PRO feature"><?php echo __("View Demo ", "survey-maker"); ?></a>
                        </span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_password" checked value="on"/>
                </div>
                <div class="col-sm-8 ays_toggle_target ays_divider_left">
                    <div class="form-group">
                        <label class="checkbox_ays form-check form-check-inline" >
                            <input type="radio" value='general' checked>
                            <?php echo __('General', "survey-maker") ?>
                        </label>
                        <label class="checkbox_ays form-check form-check-inline" >
                            <input type="radio" value="generated_password" >
                            <?php echo __('Generated Passwords', "survey-maker") ?>
                        </label>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_password_survey">
                                <?php echo __('Password',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Specify the password for the users who can take the survey.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="ays_survey_password_survey" class="ays-enable-timer ays-text-input">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-2">
                            <label for="ays_survey_password_message">
                                <?php echo __('Message',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Write the message for users who must fill in the password for taking this survey.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-10">
                            <?php
                            $content = "";
                            $editor_id = 'ays_survey_password_message';
                            $settings = array('editor_height' => '100', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- Survey Password -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_limit_by_country">
                        <?php echo __('Limit by country', "survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, the given survey will not be available in the selected country.' , "survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timer1 ays_toggle_checkbox" id="ays_survey_enable_limit_by_country" value="on" checked/>
                </div>
                <div class="col-sm-8 ays_divider_left">
                    <select class="ays-text-input ays-text-input-short" id="ays_survey_limit_country">                        
                        <option value="" selected>Andora</option>
                    </select>
                </div>
            </div> <!-- Limit by country -->
        </div>
    </div>
</div>
