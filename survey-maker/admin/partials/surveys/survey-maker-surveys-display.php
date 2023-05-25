<?php
    $action = ( isset($_GET['action']) ) ? sanitize_text_field( $_GET['action'] ) : '';
    $id     = ( isset($_GET['survey']) ) ? sanitize_text_field( $_GET['survey'] ) : null;
    
    $max_id = 10;
    
    $survey_max_id = Survey_Maker_Admin::get_max_id('surveys');
?>

<div class="wrap ays_surveys_list_table">
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
            echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action page-title-action-surveys-page">' . __('Add New', "survey-maker") . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
        ?>
    </h1>

    <?php if($max_id <= 6): ?>
    <div class="notice notice-success is-dismissible">
        <p style="font-size:14px;">
            <strong>
                <?php echo __( "If you haven't created questions yet, you need to create the questions at first.", "survey-maker" ); ?> 
            </strong>
            <br>
            <strong>
                <em>
                    <?php echo __( "For creating a question go", "survey-maker" ); ?> 
                    <a href="<?php echo admin_url('admin.php') . "?page=".$this->plugin_name . "-questions"; ?>" target="_blank">
                        <?php echo __( "here", "survey-maker" ); ?>.
                    </a>
                </em>
            </strong>
        </p>
    </div>
    <?php endif; ?>
    <div id="poststuff" style="margin-top:20px;">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->surveys_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->surveys_obj->prepare_items();
                            $search = __( "Search", "survey-maker" );
                            $this->surveys_obj->search_box($search, $this->plugin_name);
                            $this->surveys_obj->display();
                               
                        ?>
                        
                    </form>
                </div>
            </div>
        </div>
        
        <br class="clear">
        <?php if($survey_max_id <= 3): ?>
            <div class="ays-survey-create-survey-video-box" style="margin: auto;">
                <div class="ays-survey-create-survey-title">
                    <h4><?php echo __( "Create Your First Survey in Under One Minute", "survey-maker" ); ?></h4>
                </div>
                <div class="ays-survey-create-survey-youtube-video-button-box-top">
                    <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-survey-add-new-button-video">' . __('Add New', "survey-maker") . '</a>', esc_attr( $_REQUEST['page'] ), 'add');?>
                </div>
                <div class="ays-survey-create-survey-youtube-video">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/vZ2-VEd8Bq4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="max-width: 100%;"></iframe>
                </div>
                <div class="ays-survey-create-survey-youtube-video-button-box-bottom">
                    <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-survey-add-new-button-video">' . __('Add New', "survey-maker") . '</a>', esc_attr( $_REQUEST['page'] ), 'add');?>
                </div>
            </div>
        <?php else: ?>
            <div class="ays-survey-create-survey-video-box" style="margin: auto;height: 83px;">                
                <div class="ays-survey-create-survey-youtube-video">
                    <?php echo sprintf( '<a href="?page=%s&action=%s" class="ays-survey-add-new-button-video">' . __('Add New', "survey-maker") . '</a>', esc_attr( $_REQUEST['page'] ), 'add');?>
                </div>
                <div class="ays-survey-create-survey-youtube-video">
                    <a href="https://www.youtube.com/watch?v=vZ2-VEd8Bq4" target="_blank" title="YouTube video player" >How to create Survey in Under One Minute</a>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
