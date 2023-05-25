<?php
$example_export_path = SURVEY_MAKER_ADMIN_URL . '/partials/export-import/survey-export-example.json';
?>
<div class="wrap ays_results_table">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;">View Documentation</span>
                </a>
            </div>
        </div>
        <h1 class="wp-heading-inline">
            <?php
            echo __(esc_html(get_admin_page_title()),"survey-maker");
            ?>
        </h1>

        <div style="display: flex;justify-content: center; align-items: center;"><iframe width="560" height="315" src="https://www.youtube.com/embed/xLSv8h87fX4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>

        <div class="nav-tab-wrapper">
            <a href="#tab1" class="nav-tab nav-tab-active"><?php echo __('Export',"survey-maker"); ?></a>
            <a href="#tab2" class="nav-tab"><?php echo __('Import',"survey-maker"); ?></a>
            <a href="<?php echo esc_url($example_export_path); ?>" class="export-survey-example" download="survey-export-example.json"><?php echo __('Download example for import',"survey-maker"); ?></a>
        </div>

        <div id="tab1" class="ays-survey-tab-content ays-survey-tab-content-active">
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
                    <form method="post" id="ays-export-form">
                        <p class="ays-subtitle"><?php echo __('Export surveys',"survey-maker")?></p>
                        <hr/>
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="ays_select_surveys">
                                    <span><?php echo __("Select Surveys", "survey-maker"); ?></span>
                                    <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Specify the surveys which must be exported. If you want to export all surveys just leave blank.',"survey-maker")?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                                </label>
                            </div>
                            <div class="col-sm-8">                        
                                <select id="ays_select_surveys" multiple>
                                    <option value=""><?php echo __( "Survey title", "survey-maker" ); ?></option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="button ays_export_surveys" id="export-reports" disabled="disabled" ><?php echo __( "Export to JSON", "survey-maker" ); ?>
                                </button>
                                <a download="" id="downloadFile" hidden href=""></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="tab2" class="ays-survey-tab-content">
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
                    <div class="upload-import-file-wrap show-upload-view">
                        <div class="upload-import-file">
                            <p class="install-help"><?php echo __( "After completing the exporting process, move to the website where you are planning to import those surveys. Click on the Choose file button and pick the JSON file which you exported recently. Click on the Import Now button at the end.", "survey-maker" ); ?></p>
                            <form method="post" enctype="multipart/form-data" class="ays-dn">
                                <input type="file" accept=".json" id="import_file"/>
                                <label class="screen-reader-text" for="import_file"><?php echo __( "Import file", "survey-maker" ); ?></label>
                                <input type="submit" class="button" value="<?php echo __( "Import now", "survey-maker" ); ?>" disabled="">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

