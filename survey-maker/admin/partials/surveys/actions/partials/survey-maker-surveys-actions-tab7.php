<div id="tab7" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab7') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('E-mail settings', "survey-maker"); ?></p>
    <hr/>
    <div style="display: flex;justify-content: center; align-items: center;margin-bottom: 15px;">
    <iframe width="560" height="315" src="https://www.youtube.com/embed/-NNIV6bNSGA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>    </div>
        <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                    <p style="position: absolute;top: 0;">
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_mail_user">
                        <?php echo __('Send email to user',"survey-maker")?>
                        <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Activate the option of sending emails to your users after taking the survey.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timerl" id="ays_survey_enable_mail_user" value="on" />
                </div>
                <div class="col-sm-8 ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_mail_message">
                                <?php echo __('Email message',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php 
                                        echo htmlspecialchars( sprintf(
                                            __('Write the message to send it out to your survey takers via email. You can use %sMessage Variables%s as well.',"survey-maker"),
                                            '<strong>',
                                            '</strong>'
                                        ) );
                                    ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                            <p class="ays_survey_small_hint_text_for_message_variables">
                                <span><?php echo __( "To see all Message Variables " , "survey-maker" ); ?></span>
                                <a href="?page=survey-maker-settings" target="_blank"><?php echo __( "click here" , "survey-maker" ); ?></a>
                            </p>
                        </div>
                        <div class="col-sm-9">
                            <?php
                            $content = '';
                            $editor_id = 'ays_survey_mail_message';
                            $settings = array('editor_height' => '100', 'textarea_name' => 'ays_survey_mail_message', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                    <hr/>
                    <div class='row'>
                        <div class="col-sm-3">
                            <label for="ays_survey_summary_single_email_to_users">
                                <?php echo __('Send user summary', "survey-maker"); ?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('After enabling this option the user will receive its own survey summary.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                            
                        </div>
                        <div class="col-sm-9">
                            <input type="checkbox" >
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_test_email">
                                <?php echo __('Send email for testing',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php 
                                        echo htmlspecialchars( sprintf(
                                            __('Provide an email and click on the %sSend%s button to see what the message looks like. Note that you need to write a message on the %sEmail message%s field beforehand. Take into account that the message variables will not work while testing.',"survey-maker"),
                                            '<strong>',
                                            '</strong>',
                                            '<strong>',
                                            '</strong>'
                                        ) );
                                    ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <div class="ays_send_test">
                                <input type="text" id="ays_survey_test_email" class="ays-text-input" value="">
                                <input type="hidden" value="<?php echo esc_attr($id); ?>">
                                <a href="javascript:void(0)" class="ays_survey_test_mail_btn button button-primary"><?php echo __( "Send", "survey-maker" ); ?></a>
                                <span id="ays_survey_test_delivered_message" data-src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . "/images/loaders/loading.gif" ?>" style="display: none;"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- Send Mail To User -->
            <hr/>
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_mail_admin">
                        <?php echo __('Send email to admin',"survey-maker")?>
                        <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Activate this option so that the survey data can be sent to the super admin of your WordPress site.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays-enable-timerl ays_toggle_checkbox" id="ays_survey_enable_mail_admin" value="on" />
                </div>
                <div class="col-sm-8 ays_toggle_target ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_send_mail_to_site_admin">
                                <?php echo __('Admin', "survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Send the survey results to the super admin.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-1">
                            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_send_mail_to_site_admin" value="on" />
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="ays-text-input ays-enable-timerl" placeholder="admin@example.com" disabled />
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_additional_emails">
                                <?php echo __('Additional emails',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Provide additional email addresses that will receive the survey results. List the emails by separating them with commas.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_additional_emails" placeholder="example1@gmail.com, example2@gmail.com, ..."/>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_mail_message_admin">
                                <?php echo __('Email message',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php 
                                        echo htmlspecialchars( sprintf(
                                            __('Provide a text message to be sent to the super admin and/or the provided additional emails. %sMessage Variables%s will be of help.',"survey-maker"),
                                            '<strong>',
                                            '</strong>'
                                        ) );
                                    ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                            <p class="ays_survey_small_hint_text_for_message_variables">
                                <span><?php echo __( "To see all Message Variables " , "survey-maker" ); ?></span>
                                <a href="?page=survey-maker-settings" target="_blank"><?php echo __( "click here" , "survey-maker" ); ?></a>
                            </p>
                        </div>
                        <div class="col-sm-9">
                            <?php
                            $content = '';
                            $editor_id = 'ays_survey_mail_message_admin';
                            $settings = array('editor_height' => '100', 'textarea_name' => 'ays_survey_mail_message_admin', 'editor_class' => 'ays-textarea', 'media_elements' => false);
                            wp_editor($content, $editor_id, $settings);
                            ?>
                        </div>
                    </div>
                </div>
            </div> <!-- Send mail to admin -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label>
                        <?php echo __('Email configuration',"survey-maker")?>
                        <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Set up the attributes of the sending email.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ays_divider_left">
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_email_configuration_from_email">
                                <?php echo __('From email',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php 
                                    echo htmlspecialchars( sprintf(
                                        __('Specify the email address from which the results will be sent. If you leave the field blank, the sending email address will take the default value — %ssurvey_maker@{your_site_url}%s.',"survey-maker"),
                                        '<em>',
                                        '</em>'
                                    ) );
                                    ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_email" value=""/>
                        </div>
                    </div> <!-- From email -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_email_configuration_from_name">
                                <?php echo __('From name',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php 
                            echo htmlspecialchars( sprintf(
                                __("Specify the name that will be displayed as the sender of the results. If you don't enter any name, it will be %sSurvey Maker%s.","survey-maker"),
                                '<em>',
                                '</em>'
                            ) );
                        ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_names" value=""/>
                        </div>
                    </div><!-- From name -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_email_configuration_from_subject">
                                <?php echo __('Subject',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __("Fill in the subject field of the message. If you don't, it will take the survey title.","survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_email_configuration_from_subject" value=""/>
                        </div>
                    </div> <!-- Subject -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_email_configuration_replyto_email">
                                <?php echo __('Reply to email',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __("Specify to which email the survey taker can reply. If you leave the field blank, the email address won't be specified.","survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_email_configuration_replyto_email" value=""/>
                        </div>
                    </div> <!-- Reply to email -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-3">
                            <label for="ays_survey_email_configuration_replyto_name">
                                <?php echo __('Reply to name',"survey-maker")?>
                                <a  class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __("Specify the name of the email address to which the survey taker can reply. If you leave the field blank, the name won't be specified.","survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="ays-text-input" id="ays_survey_email_configuration_replyto_name" value=""/>
                        </div>
                    </div> <!-- Reply to name -->
                </div>
            </div> <!-- Email Configuration -->
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="">
                        <?php echo __('Send summary', "survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the selected people. Click on the Send Now button and the summary will be sent at that given moment combined with data collected before it.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ays_divider_left">
                    <div class='form-grpoup'>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_admin">
                                    <?php echo __('To admin', "survey-maker"); ?>
                                    <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the registered email of the super admin of your WordPress website.',"survey-maker"); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="checkbox" id='ays_survey_summary_emails_to_admin' name="ays_survey_summary_emails_to_admin" value="on">
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_users">
                                    <?php echo __('To users', "survey-maker"); ?>
                                    <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Send a detailed summary of the survey to the survey participants.',"survey-maker"); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>

                            </div>
                            <div class="col-sm-9">
                                <input type="checkbox" id='ays_survey_summary_emails_to_users' name="ays_survey_summary_emails_to_users" value="on">
                            </div>
                        </div>
                        <hr>
                        <div class='row'>
                            <div class="col-sm-3">
                                <label for="ays_survey_summary_emails_to_admins">
                                    <?php echo __('To additional emails', "survey-maker"); ?>
                                    <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Provide additional email addresses. These email accounts will receive a detailed summary of the survey. List the emails by separating them with commas.', "survey-maker"); ?>">
                                        <i class="ays_fa ays_fa_info_circle"></i>
                                    </a>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" class="ays-text-input" id="ays_survey_summary_emails_to_admins" name="ays_survey_summary_emails_to_admins" value="">
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class='form-group' style="display:flex;align-items: center;">
                        <input type="hidden" name="ays_survey_id_summary_mail" id="ays_survey_id_summary_mail" value="<?php echo esc_attr($id); ?>">
                        <a href="javascript:void(0)" class="ays_survey_summary_mail_btn button button-primary"><?php echo __( "Send now", "survey-maker" ); ?></a>
                        <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL) . "/images/loaders/loading.gif"; ?>" alt="" style="display: none;margin-left: 15px;width:20px;height:20px" class="ays_survey_summary_delivered_message_loader" >
                        <span id="ays_survey_summary_delivered_message" style="display: none;margin-left: 15px;"></span>
                    </div>
                </div>
            </div><!-- Send Summary -->
        </div>
    </div>
</div>
