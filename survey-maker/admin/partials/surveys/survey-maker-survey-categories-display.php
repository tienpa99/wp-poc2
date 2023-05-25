<?php

?>
<div class="wrap">
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
        echo __( esc_html(get_admin_page_title()), "survey-maker" );
        echo sprintf( '<a href="?page=%s&action=%s" class="page-title-action">' . __( 'Add New', "survey-maker" ) . '</a>', esc_attr( $_REQUEST['page'] ), 'add');
        ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <?php
                        $this->surveys_categories_obj->views();
                    ?>
                    <form method="post">
                        <?php
                            $this->surveys_categories_obj->prepare_items();
                            $search = __( "Search", "survey-maker" );
                            $this->surveys_categories_obj->search_box($search, $this->plugin_name);
                            $this->surveys_categories_obj->display();
                        ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
