<div id="tab8" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab8') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Integrations settings',"survey-maker")?></p>
    <div>
        <span style="font-size: 12px;padding: 2px 10px;font-style: italic;">
            <a href="https://ays-pro.com/instructions-for-survey-maker-plugin-integrations/" style="color:#524e4e;position:relative;" target="_blank" title="PRO feature"><?php echo __("Read the Detailed Guide", "survey-maker"); ?></a>
        </span>
    </div>
    <hr/>
    <?php 
        $args = apply_filters( 'ays_sm_survey_page_integrations_options', array(), $options );
        do_action( 'ays_sm_survey_page_integrations', $args );
    ?>
</div>
