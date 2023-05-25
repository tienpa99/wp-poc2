<?php
    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/survey-maker-survey-categories-actions-options.php" );
?>
<div class="wrap">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;">View Documentation</span>
                </a>
            </div>
        </div>
        <form class="ays-survey-category-form" id="ays-survey-category-form" method="post">
            <h1><?php echo esc_html($heading); ?></h1>
            <hr/>
            
            <?php
                for($tab_ind = 1; $tab_ind <= 1; $tab_ind++){
                    require_once( SURVEY_MAKER_ADMIN_PATH . "/partials/surveys/actions/partials/survey-maker-survey-categories-actions-tab".$tab_ind.".php" );
                }
            ?>
            
            <input type="hidden" name="<?php echo esc_attr($html_name_prefix); ?>date_created" value="<?php echo esc_attr($date_created); ?>">
            <input type="hidden" name="<?php echo esc_attr($html_name_prefix); ?>date_modified" value="<?php echo esc_attr($date_modified); ?>">
            <hr/>
            <?php
                wp_nonce_field('survey_category_action', 'survey_category_action');
                $other_attributes = array('id' => 'ays-button-save');
                submit_button(__('Save and close', "survey-maker"), 'primary ays-button ays-survey-loader-banner', 'ays_submit', false, $other_attributes);
                $other_attributes = array('id' => 'ays-button-save-new');
                submit_button(__('Save and new', "survey-maker"), 'primary ays-button ays-survey-loader-banner', 'ays_save_new', false, $other_attributes);
                
                $other_attributes = array(
                    'id' => 'ays-button-apply',
                    'title' => 'Ctrl + s',
                    'data-toggle' => 'tooltip',
                    'data-delay'=> '{"show":"1000"}'
                );

                submit_button(__('Save', "survey-maker"), 'ays-button ays-survey-loader-banner', 'ays_apply', false, $other_attributes);

                echo wp_kses_post($loader_iamge);
            ?>
        </form>
    </div>
</div>
