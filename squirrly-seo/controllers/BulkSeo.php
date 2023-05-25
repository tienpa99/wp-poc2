<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class SQ_Controllers_BulkSeo extends SQ_Classes_FrontController
{
    /** @var Post List (used in the view) */
    public $post;
    /** @var Task labels */
    public $labels;
    /** @var array All pages that are sent to the view */
    public $pages = array();

    function init()
    {

        $tab = preg_replace("/[^a-zA-Z0-9]/", "", SQ_Classes_Helpers_Tools::getValue('tab', 'bulkseo'));

        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('bootstrap-reboot');
        if(is_rtl()) {
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('popper');
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('bootstrap.rtl');
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('rtl');
        }else{
            SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('bootstrap');
        }
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('switchery');
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('fontawesome');
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('global');

        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('assistant');
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('navbar');
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('seosettings');

        if (method_exists($this, $tab)) {
            call_user_func(array($this, $tab));
        }

        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        }

        //@ob_flush();
        $this->show_view('BulkSeo/' . esc_attr(ucfirst($tab)));

        //get the modal window for the assistant popup
        echo SQ_Classes_ObjController::getClass('SQ_Models_Assistant')->getModal();
    }

    public function bulkseo()
    {
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('bulkseo');
        SQ_Classes_ObjController::getClass('SQ_Classes_DisplayController')->loadMedia('labels');

        $search = (string)SQ_Classes_Helpers_Tools::getValue('skeyword', '');
        $this->pages = SQ_Classes_ObjController::getClass('SQ_Models_Snippet')->getPages($search);

        if (!empty($labels) || count((array)$this->pages) > 1) {
            //Get the labels for view use
            $this->labels = SQ_Classes_ObjController::getClass('SQ_Models_BulkSeo')->getLabels();
        }
    }

    /**
     * Called when action is triggered
     *
     * @return void
     */
    public function action()
    {
        parent::action();

        switch (SQ_Classes_Helpers_Tools::getValue('action')) {

            case 'sq_ajax_assistant_bulkseo':

            SQ_Classes_Helpers_Tools::setHeader('json');

            $response = array();
            if (!SQ_Classes_Helpers_Tools::userCan('sq_manage_snippet')) {
                $response['error'] = SQ_Classes_Error::showNotices(esc_html__("You do not have permission to perform this action", 'squirrly-seo'), 'error');
                echo wp_json_encode($response);
                exit();
            }

            $post_id = (int)SQ_Classes_Helpers_Tools::getValue('post_id', 0);
            $term_id = (int)SQ_Classes_Helpers_Tools::getValue('term_id', 0);
            $taxonomy = SQ_Classes_Helpers_Tools::getValue('taxonomy', '');
            $post_type = SQ_Classes_Helpers_Tools::getValue('post_type', '');

            //Set the Labels and Categories
            SQ_Classes_ObjController::getClass('SQ_Models_BulkSeo')->init();
            if ($post = SQ_Classes_ObjController::getClass('SQ_Models_Snippet')->getCurrentSnippet($post_id, $term_id, $taxonomy, $post_type)) {
                $this->post = SQ_Classes_ObjController::getClass('SQ_Models_BulkSeo')->parsePage($post)->getPage();
            }

            $json = array();
            $json['html'] = $this->get_view('BulkSeo/BulkseoRow');
            $json['html_dest'] = "#sq_row_" . $this->post->hash;

            $json['assistant'] = '';
            $categories = apply_filters('sq_assistant_categories_page', $this->post->hash);
            if (!empty($categories)) {
                foreach ($categories as $index => $category) {
                    if (isset($category->assistant)) {
                        $json['assistant'] .= $category->assistant;
                    }
                }
            }
            $json['assistant_dest'] = "#sq_assistant_" . $this->post->hash;

            echo wp_json_encode($json);
            exit();

        }

    }

}
