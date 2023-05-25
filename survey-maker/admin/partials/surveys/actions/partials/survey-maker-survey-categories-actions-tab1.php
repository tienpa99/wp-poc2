<div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
    <div class="form-group row">
        <div class="col-sm-2">
            <label for='ays-title'>
                <?php echo __('Title', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the survey category title.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-10">
            <input type="text" class="ays-text-input" id='ays-title' name='<?php echo esc_attr($html_name_prefix); ?>title'
                   value="<?php echo esc_attr($title); ?>" />
        </div>
    </div> <!-- Title -->
    <hr/>
    <div class='ays-field-dashboard'>
        <label for='ays-description'>
            <?php echo __('Description', "survey-maker"); ?>
            <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Provide more information about the survey category. Attach images or any other media to its description if you wish.',"survey-maker")?>">
                <i class="ays_fa ays_fa_info_circle"></i>
            </a>
        </label>
        <?php
            $content = $description;
            $editor_id = 'ays-description';
            $settings = array( 
                'editor_height' => $survey_wp_editor_height,
                'textarea_name' => $html_name_prefix . 'description',
                'editor_class' => 'ays-textarea'
            );
            wp_editor( $content, $editor_id, $settings );
        ?>
    </div> <!-- Description -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-2">
            <label for="ays-status">
                <?php echo __('Category status', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo htmlspecialchars( __("Decide whether the survey category is active or not. If the category is a draft, it won't be shown anywhere on your website (you don't need to remove shortcodes).","survey-maker") ); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-10">
            <select id="ays-status" name="<?php echo esc_attr($html_name_prefix); ?>status">
                <option></option>
                <option <?php selected( $status, 'published' ); ?> value="published"><?php echo __( "Published", "survey-maker" ); ?></option>
                <option <?php selected( $status, 'draft' ); ?> value="draft"><?php echo __( "Draft", "survey-maker" ); ?></option>
            </select>
        </div>
    </div> <!-- Status -->
</div>
