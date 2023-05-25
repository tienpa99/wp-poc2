<div class="wrap">
    <h1 class="wp-heading-inline display_none">
        <?php
        echo __(esc_html(get_admin_page_title()),"survey-maker");
        ?>
    </h1>
    <div style="display: flex;justify-content: center; align-items: center;margin-bottom: 15px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/gM6SQdOw3fA" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
    
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
            <div>
                <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL).'/images/screenshots/survey-popup.png'?>" alt="Popup" style="width:100%;" >
            </div>
        </div>
    </div>
</div>
