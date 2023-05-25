<?php
ob_start();
class Surveys_List_Table extends WP_List_Table {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The table name in database of the survey categories.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $table_name    The table name in database of the survey categories.
     */
    private $table_name;

    /**
     * The settings object of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      object    $settings_obj    The settings object of this plugin.
     */
    private $settings_obj;


    private $title_length;


    /** Class constructor */
    public function __construct( $plugin_name ) {
        global $wpdb;

        $this->plugin_name = $plugin_name;

        $this->table_name = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);

        $this->title_length = Survey_Maker_Data::get_listtables_title_length('surveys');

        parent::__construct( array(
            'singular' => __( 'Survey', "survey-maker" ), //singular name of the listed records
            'plural'   => __( 'Surveys', "survey-maker" ), //plural name of the listed records
            'ajax'     => false //does this table support ajax?
        ) );

        add_action( 'admin_notices', array( $this, 'survey_notices' ) );
        add_filter( 'default_hidden_columns', array( $this, 'get_hidden_columns'), 10, 2 );

    }

    /**
     * Override of table nav to avoid breaking with bulk actions & according nonce field
     */
    public function display_tablenav( $which ) {
        ?>
        <div class="tablenav <?php echo esc_attr( $which ); ?>">
            
            <div class="alignleft actions">
                <?php  $this->bulk_actions( $which ); ?>
            </div>
            <?php
            $this->extra_tablenav( $which );
            $this->pagination( $which );
            ?>
            <br class="clear" />
        </div>
        <?php
    }

    /**
     * Disables the views for 'side' context as there's not enough free space in the UI
     * Only displays them on screen/browser refresh. Else we'd have to do this via an AJAX DB update.
     *
     * @see WP_List_Table::extra_tablenav()
     */
    public function extra_tablenav( $which ) {
        global $wpdb;
        $titles_sql = "SELECT s.title, s.id
                       FROM " .$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories AS s
                       WHERE s.status = 'published'";
        $cat_titles = $wpdb->get_results($titles_sql);

        $users_sql = "SELECT `author_id`
                FROM " .$wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys
                GROUP BY author_id";
        $users = $wpdb->get_results($users_sql,"ARRAY_A");
        $cat_id = null;
        
        if( isset( $_GET['filterby'] )){
            $cat_id = absint( sanitize_text_field( $_GET['filterby'] ) );
        }

        if( isset( $_GET['filterbyuser'] )){            
            $author_id = absint( sanitize_text_field( $_GET['filterbyuser'] ) );
        }

        $categories_select = array();
        foreach($cat_titles as $key => $cat_title){
            $selected = "";
            if($cat_id === intval($cat_title->id)){
                $selected = "selected";
            }
            $categories_select[$cat_title->id]['title'] = $cat_title->title;
            $categories_select[$cat_title->id]['selected'] = $selected;
            $categories_select[$cat_title->id]['id'] = $cat_title->id;
        }
        sort($categories_select);
        ?>
        <div id="category-filter-div-surveylist" class="alignleft actions bulkactions">
            <select name="filterby-<?php echo esc_attr( $which ); ?>" id="survey-category-filter-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Select Category',"survey-maker")?></option>
                <?php
                    foreach($categories_select as $key => $cat_title){
                        echo "<option ".esc_attr($cat_title['selected'])." value='".esc_attr($cat_title['id'])."'>".esc_attr($cat_title['title'])."</option>";
                    }
                ?>
            </select>
        </div>
        <div id="user-filter-div-surveylist" class="alignleft actions bulkactions">
            <select name="filterbyuser-<?php echo esc_attr( $which ); ?>" id="survey-category-filter-<?php echo esc_attr( $which ); ?>">
                <option value=""><?php echo __('Select Author',"survey-maker")?></option>
                <?php
                    foreach($users as $user_key => $user){
                        $user_selected = ( isset($author_id) && $user['author_id'] == $author_id ) ? "selected" : "";
                        $user_data = get_userdata($user['author_id']);
                        $user_name = ($user_data !== false) ? $user_data->data->display_name : "";
                        
                        echo "<option ".esc_attr($user_selected)." value='".esc_attr($user['author_id'])."'>".esc_attr($user_name)."</option>";
                    }
                ?>
            </select>
            <input type="button" id="doaction-<?php echo esc_attr( $which ); ?>" class="user-filter-apply-<?php echo esc_attr( $which ); ?> button ays-survey-question-tab-all-filter-button-<?php echo esc_attr( $which ); ?>" value="Filter">
        </div>
        
        <a style="margin: 0px 8px 0 0;" href="?page=<?php echo esc_attr( sanitize_text_field( $_REQUEST['page'] ) ); ?>" class="button"><?php echo __( "Clear filters", "survey-maker" ); ?></a>
        <?php
    }
    
    protected function get_views() {
        $published_count = $this->get_statused_record_count( 'published' );
        $draft_count = $this->get_statused_record_count( 'draft' );
        $trashed_count = $this->get_statused_record_count( 'trashed' );
        $all_count = $this->all_record_count();
        $selected_all = "";
        $selected_published = "";
        $selected_draft = "";
        $selected_trashed = "";
        if( isset( $_GET['fstatus'] ) ){
            switch( sanitize_text_field( $_GET['fstatus'] ) ){
                case "published":
                    $selected_published = " style='font-weight:bold;' ";
                    break;
                case "draft":
                    $selected_draft = " style='font-weight:bold;' ";
                    break;
                case "trashed":
                    $selected_trashed = " style='font-weight:bold;' ";
                    break;
                default:
                    $selected_all = " style='font-weight:bold;' ";
                    break;
            }
        }else{
            $selected_all = " style='font-weight:bold;' ";
        }
        $status_links = array(
            "all" => "<a ".$selected_all." href='?page=".sanitize_text_field( $_REQUEST['page'] )."'>" . __( "All", "survey-maker" ) . " (".$all_count.")</a>",
        );
        if( intval( $published_count ) > 0 ){
            $status_links["published"] = "<a ".$selected_published." href='?page=".sanitize_text_field( $_REQUEST['page'] )."&fstatus=published'>" . __( "Published", "survey-maker" ) . " (".$published_count.")</a>";
        }
        if( intval( $draft_count ) > 0 ){
            $status_links["draft"] = "<a ".$selected_draft." href='?page=".sanitize_text_field( $_REQUEST['page'] )."&fstatus=draft'>" . __( "Draft", "survey-maker" ) . " (".$draft_count.")</a>";
        }
        if( intval( $trashed_count ) > 0 ){
            $status_links["trashed"] = "<a ".$selected_trashed." href='?page=".sanitize_text_field( $_REQUEST['page'] )."&fstatus=trashed'>" . __( "Trash", "survey-maker" ) . " (".$trashed_count.")</a>";
        }
        return $status_links;
    }

    /**
     * Retrieve customers data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_items( $per_page = 20, $page_number = 1, $search = '' ) {

        global $wpdb;
        
        $sql = "SELECT * FROM ". $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        $where = array();

        if ( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != ''){
            $where[] = ' status = "' . esc_sql( sanitize_text_field( $_GET['fstatus'] ) ) . '" ';
        }else{
            $where[] = ' status != "trashed" ';
        }

        if( $search != '' ){
            $where[] = $search;
        }

        if(! empty( $_REQUEST['filterby'] ) && intval( $_REQUEST['filterby'] ) > 0){
            $cat_id = intval( sanitize_text_field( $_REQUEST['filterby'] ) );
            $where[] = ' FIND_IN_SET( "'.$cat_id.'", `category_ids` ) ';
        }

        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $where[] = ' author_id ='.$user_id;
        }

        if ( ! empty( $where ) ){
            $sql .= ' WHERE ' . implode( ' AND ', $where );
        }

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $order_by  = ( isset( $_REQUEST['orderby'] ) && sanitize_text_field( $_REQUEST['orderby'] ) != '' ) ? sanitize_text_field( $_REQUEST['orderby'] ) : 'id';
            $order_by .= ( ! empty( $_REQUEST['order'] ) && strtolower( $_REQUEST['order'] ) == 'asc' ) ? ' ASC' : ' DESC';

            $sql_orderby = sanitize_sql_orderby( $order_by );

            if ( $sql_orderby ) {
                $sql .= ' ORDER BY ' . $sql_orderby;
            } else {
                $sql .= ' ORDER BY ordering DESC';
            }
        }else{
            $sql .= ' ORDER BY ordering DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public function get_categories(){
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE status='published' ORDER BY title ASC";

        $result = $wpdb->get_results($sql, 'ARRAY_A');

        return $result;
    }

    public static function get_item_by_id( $id ){
        global $wpdb;

        $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE id=" . absint( $id );

        $result = $wpdb->get_row($sql, 'ARRAY_A');

        return $result;
    }

    public function add_or_edit_item(){
        global $wpdb;
        $table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );
        $sections_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections" );
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions" );
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers" );

        if( isset( $_POST["survey_action"] ) && wp_verify_nonce( sanitize_text_field( $_POST["survey_action"] ), 'survey_action' ) ){

            $name_prefix = 'ays_';
            
            // Save type
            $save_type = (isset($_POST['save_type'])) ? sanitize_text_field( $_POST['save_type'] ) : '';

            // Id of item
            $id = isset( $_POST['id'] ) ? absint( sanitize_text_field( $_POST['id'] ) ) : 0;

            // Ordering
            $max_id = $this->get_max_id();
            $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
            
            // Author ID
            $user_id = get_current_user_id();
            $author_id = isset( $_POST[ $name_prefix . 'author_id' ] ) && $_POST[ $name_prefix . 'author_id' ] != '' ? intval( sanitize_text_field( $_POST[ $name_prefix . 'author_id' ] ) ) : $user_id;

            // Title
            $title = isset( $_POST[ $name_prefix . 'title' ] ) && $_POST[ $name_prefix . 'title' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'title' ] ) ) : '';

            if($title == ''){
                $url = esc_url_raw( remove_query_arg( false ) );
                $url = esc_url_raw( add_query_arg( array(
                    'status' => 'empty-title'
                ), $url ) );
                wp_redirect( $url );
            }

            // Description
            $description = ''; //isset( $_POST[ $name_prefix . 'description' ] ) && $_POST[ $name_prefix . 'description' ] != '' ? stripslashes( wp_kses_post( $_POST[ $name_prefix . 'description' ] ) ) : '';

            // Status
            $status = isset( $_POST[ $name_prefix . 'status' ] ) && $_POST[ $name_prefix . 'status' ] != '' ? sanitize_text_field( $_POST[ $name_prefix . 'status' ] ) : 'published';

            // Trash status
            $trash_status = '';
            
            // Date created
            $date_created = isset( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) && Survey_Maker_Admin::validateDate( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) ? sanitize_text_field( $_POST[ $name_prefix . 'survey_change_creation_date' ] ) : current_time( 'mysql' );
            
            // Date modified
            $date_modified = isset( $_POST[ $name_prefix . 'date_modified' ] ) && Survey_Maker_Admin::validateDate( $_POST[ $name_prefix . 'date_modified' ] ) ? sanitize_text_field( $_POST[ $name_prefix . 'date_modified' ] ) : current_time( 'mysql' );

            // Survey categories IDs
            $category_ids = isset( $_POST[ $name_prefix . 'category_ids' ] ) && $_POST[ $name_prefix . 'category_ids' ] != '' ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'category_ids' ] ) : array();
            $category_ids = empty( $category_ids ) ? '' : implode( ',', $category_ids );

            // Survey questions IDs
            $question_ids = isset( $_POST[ $name_prefix . 'question_ids' ] ) && $_POST[ $name_prefix . 'question_ids' ] != '' ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'question_ids' ] ) : array();
            // $question_ids = empty( $question_ids ) ? '' : implode( ',', $question_ids );

            // Survey image
            $image = isset( $_POST[ $name_prefix . 'image' ] ) && $_POST[ $name_prefix . 'image' ] != '' ? sanitize_text_field( $_POST[ $name_prefix . 'image' ] ) : '';

            // Post ID
            $post_id = isset( $_POST[ $name_prefix . 'post_id' ] ) && ! empty( $_POST[ $name_prefix . 'post_id' ] ) ? intval( sanitize_text_field( $_POST[ $name_prefix . 'post_id' ] ) ) : 0;

            // Section ids
            $section_ids = (isset( $_POST[ $name_prefix . 'sections_ids' ] ) && $_POST[ $name_prefix . 'sections_ids' ] != '') ? array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'sections_ids' ] ) : array();


            // =======================  //  ======================= // ======================= // ======================= // ======================= //

            // =============================================================
            // ======================    Styles Tab    =====================
            // ========================    START    ========================


            // Survey Theme
            $survey_theme = (isset( $_POST[ $name_prefix . 'survey_theme' ] ) && $_POST[ $name_prefix . 'survey_theme' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_theme' ] ) ) : 'classic_light';

            // Survey Color
            $survey_color = (isset( $_POST[ $name_prefix . 'survey_color' ] ) && $_POST[ $name_prefix . 'survey_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_color' ] ) ) : '#ff5722';

            // Background color
            $survey_background_color = (isset( $_POST[ $name_prefix . 'survey_background_color' ] ) && $_POST[ $name_prefix . 'survey_background_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_background_color' ] ) ) : '#fff';

            // Text Color
            $survey_text_color = (isset( $_POST[ $name_prefix . 'survey_text_color' ] ) && $_POST[ $name_prefix . 'survey_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_text_color' ] ) ) : '#333';

            // Buttons text Color
            $survey_buttons_text_color = (isset( $_POST[ $name_prefix . 'survey_buttons_text_color' ] ) && $_POST[ $name_prefix . 'survey_buttons_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_text_color' ] ) ) : '#333';

            // Width
            $survey_width = (isset( $_POST[ $name_prefix . 'survey_width' ] ) && $_POST[ $name_prefix . 'survey_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'survey_width' ] ) ) : '';

            // Survey Width by percentage or pixels
            $survey_width_by_percentage_px = (isset( $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] ) && $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_width_by_percentage_px' ] ) ) : 'pixels';

            // Mobile width
            $survey_mobile_width = (isset( $_POST[ $name_prefix . 'survey_mobile_width' ] ) && $_POST[ $name_prefix . 'survey_mobile_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'survey_mobile_width' ] ) ) : '';

            // Survey mobile width by percentage or pixels
            $survey_mobile_width_by_percentage_px = (isset( $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] ) && $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_mobile_width_by_percentage_px' ] ) ) : 'pixels';

            // Survey container Max-Width
            $survey_mobile_max_width = (isset( $_POST[ $name_prefix . 'mobile_max_width' ] ) && $_POST[ $name_prefix . 'mobile_max_width' ] != '') ? absint( intval( $_POST[ $name_prefix . 'mobile_max_width' ] ) ) : '';

            // Custom class for survey container
            $survey_custom_class = (isset( $_POST[ $name_prefix . 'survey_custom_class' ] ) && $_POST[ $name_prefix . 'survey_custom_class' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_custom_class' ] ) ) : ''; 

            // Custom CSS
            $survey_custom_css = (isset( $_POST[ $name_prefix . 'survey_custom_css' ] ) && $_POST[ $name_prefix . 'survey_custom_css' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_custom_css' ] ) ) : '';

            // Survey logo
            $survey_logo = (isset( $_POST[ $name_prefix . 'survey_logo' ]) && $_POST[ $name_prefix . 'survey_logo' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_logo' ] ) ) : '';

            // Survey Logo url
            $survey_logo_image_url = (isset( $_POST[ $name_prefix . 'survey_logo_image_url' ] ) && $_POST[ $name_prefix . 'survey_logo_image_url' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_image_url' ] ) : '';
            $survey_logo_image_url_check = (isset( $_POST[ $name_prefix . 'survey_logo_enable_image_url' ] ) && $_POST[ $name_prefix . 'survey_logo_enable_image_url' ] == 'on') ? 'on' : 'off';

            // Survey Logo position
            $survey_logo_image_position = (isset( $_POST[ $name_prefix . 'survey_logo_pos' ] ) && $_POST[ $name_prefix . 'survey_logo_pos' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_pos' ] ) : 'right';

            // Survey Logo title
            $survey_logo_title = (isset( $_POST[ $name_prefix . 'survey_logo_title' ] ) && $_POST[ $name_prefix . 'survey_logo_title' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_logo_title' ] ) : '';

            // Survey cover photo
            $survey_cover_photo = (isset( $_POST[ $name_prefix . 'survey_cover_photo' ]) && $_POST[ $name_prefix . 'survey_cover_photo' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_photo' ] ) ) : '';

            // Survey cover photo height
            $survey_cover_photo_height = (isset( $_POST[ $name_prefix . 'survey_cover_image_height' ]) && $_POST[ $name_prefix . 'survey_cover_image_height' ] != '') ? absint(intval(( sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_image_height' ])))) : 150;

            // Survey cover photo mobile height
            $survey_cover_photo_mobile_height = (isset( $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ]) && $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ] != '') ? absint(intval(( sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_photo_mobile_height' ])))) : 150;

            // Survey cover photo position
            $survey_cover_photo_position = (isset( $_POST[ $name_prefix . 'survey_cover_image_pos' ]) && $_POST[ $name_prefix . 'survey_cover_image_pos' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_image_pos' ]) : "center_center";

            // Survey cover photo object fit
            $survey_cover_photo_object_fit = (isset( $_POST[ $name_prefix . 'survey_cover_image_object_fit' ]) && $_POST[ $name_prefix . 'survey_cover_image_object_fit' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_cover_image_object_fit' ]) : "cover";

            // Survey title alignment
            $survey_title_alignment = (isset( $_POST[ $name_prefix . 'survey_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_title_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_title_alignment' ] ) : 'left';

            // Survey title font size
            $survey_title_font_size = (isset( $_POST[ $name_prefix . 'survey_title_font_size' ] ) && $_POST[ $name_prefix . 'survey_title_font_size' ] != '' && $_POST[ $name_prefix . 'survey_title_font_size' ] != '0' ) ? absint(intval(sanitize_text_field( $_POST[ $name_prefix . 'survey_title_font_size' ] ))) : '30';

            // Survey title font size mobile
            $survey_title_font_size_for_mobile = (isset( $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] ) && $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] != '' && $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] != '0' ) ? absint(intval(sanitize_text_field( $_POST[ $name_prefix . 'survey_title_font_size_for_mobile' ] ))) : '30';

            // Survey title box shadow
            $survey_title_box_shadow_enable = (isset( $_POST[ $name_prefix . 'survey_title_box_shadow_enable' ] ) && $_POST[ $name_prefix . 'survey_title_box_shadow_enable' ] == 'on' ) ? 'on' : 'off';

            // Survey title box shadow color
            $survey_title_box_shadow_color = (isset( $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] ) && $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] != '' ) ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_title_box_shadow_color' ] ) ) : '#333';

            // === Survey title box shadow offsets start ===
                // Survey title box shadow offset x
                $survey_title_text_shadow_x_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_x_offset' ] )  : 0;
                // Survey title box shadow offset y
                $survey_title_text_shadow_y_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_y_offset' ] )  : 0;
                // Survey title box shadow offset z
                $survey_title_text_shadow_z_offset = (isset( $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] ) && $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] != '' ) ? intval( $_POST[ $name_prefix . 'title_text_shadow_z_offset' ] )  : 10;
            // === Survey title box shadow offsets end ===

            // Survey section title font size on PC
            $survey_section_title_font_size = (isset( $_POST[ $name_prefix . 'survey_section_title_font_size' ] ) && $_POST[ $name_prefix . 'survey_section_title_font_size' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_title_font_size' ] )) : 32;
            // Survey section title font size Mobile
            $survey_section_title_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_title_font_size_mobile' ] )) : 32;

            // Survey section title alignment
            $survey_section_title_alignment = (isset( $_POST[ $name_prefix . 'survey_section_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_section_title_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_section_title_alignment' ] ) : 'left';

            // Survey section description alignment
            $survey_section_description_alignment = (isset( $_POST[ $name_prefix . 'survey_section_description_alignment' ] ) && $_POST[ $name_prefix . 'survey_section_description_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_section_description_alignment' ] ) : 'left';

            // Survey section description font size
            $survey_section_description_font_size = (isset( $_POST[ $name_prefix . 'survey_section_description_font_size' ] ) && $_POST[ $name_prefix . 'survey_section_description_font_size' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_description_font_size' ] )) : '14';

            // Survey section description font size mobile
            $survey_section_description_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] != '') ? absint(intval( $_POST[ $name_prefix . 'survey_section_description_font_size_mobile' ] )) : '14';

            // =========== Questions Styles Start ===========

            // Question font size
            $survey_question_font_size = (isset( $_POST[ $name_prefix . 'survey_question_font_size' ] ) && $_POST[ $name_prefix . 'survey_question_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_font_size' ] ) ) : 16;

            // Question font size mobile
            $survey_question_font_size_mobile = (isset( $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] ) && $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_font_size_mobile' ] ) ) : 16;

            // Question title alignment 
            $survey_question_title_alignment = (isset( $_POST[ $name_prefix . 'survey_question_title_alignment' ] ) && $_POST[ $name_prefix . 'survey_question_title_alignment' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_title_alignment' ] ) ) : 'left';

            // Question Image Width
            $survey_question_image_width = (isset( $_POST[ $name_prefix . 'survey_question_image_width' ] ) && $_POST[ $name_prefix . 'survey_question_image_width' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_width' ] ) ) : '';

            // Question Image Height
            $survey_question_image_height = (isset( $_POST[ $name_prefix . 'survey_question_image_height' ] ) && $_POST[ $name_prefix . 'survey_question_image_height' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_height' ] ) ) : '';

            // Question Image sizing
            $survey_question_image_sizing = (isset( $_POST[ $name_prefix . 'survey_question_image_sizing' ] ) && $_POST[ $name_prefix . 'survey_question_image_sizing' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_image_sizing' ] ) ) : 'cover';
            
            // Question padding
            $survey_question_padding = (isset( $_POST[ $name_prefix . 'survey_question_padding' ] ) && $_POST[ $name_prefix . 'survey_question_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_padding' ] ) ) : 24;
            
            // Question caption text color
            $survey_question_caption_text_color = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_color' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_color' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_color' ] ) ) : $survey_text_color;
            
            // Question caption text alignment
            $survey_question_caption_text_alignment = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_alignment' ] ) : 'center';
                        
            // Question font size
            $survey_question_caption_font_size = (isset( $_POST[ $name_prefix . 'survey_question_caption_font_size' ] ) && $_POST[ $name_prefix . 'survey_question_caption_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_font_size' ] ) ) : 16;
                        
            // Question font size on mobile
            $survey_question_caption_font_size_on_mobile = (isset( $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] ) && $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_font_size_on_mobile' ] ) ) : 16;
                        
            // Question caption text transform
            $survey_question_caption_text_transform = (isset( $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] ) && $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_question_caption_text_transform' ] ) : 'none';

            // =========== Questions Styles End   ===========



            // =========== Answers Styles Start ===========

            // Answer font size
            $survey_answer_font_size = (isset( $_POST[ $name_prefix . 'survey_answer_font_size' ] ) && $_POST[ $name_prefix . 'survey_answer_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answer_font_size' ] ) ) : 15;

            // Answer font size mobile
            $survey_answer_font_size_on_mobile = (isset( $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] ) && $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answer_font_size_on_mobile' ] ) ) : 15;

            // Answer view
            $survey_answers_view = (isset( $_POST[ $name_prefix . 'survey_answers_view' ] ) && $_POST[ $name_prefix . 'survey_answers_view' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_view' ] ) ) : 'list';

            // Answer view alignment
            $survey_answers_view_alignment = (isset( $_POST[ $name_prefix . 'survey_answers_view_alignment' ] ) && $_POST[ $name_prefix . 'survey_answers_view_alignment' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_view_alignment' ] ) ) : 'space-around';

            // Answer object-fit
            $survey_answers_object_fit = (isset( $_POST[ $name_prefix . 'survey_answers_object_fit' ] ) && $_POST[ $name_prefix . 'survey_answers_object_fit' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_object_fit' ] ) ) : 'cover';

            // Answer padding
            $survey_answers_padding = (isset( $_POST[ $name_prefix . 'survey_answers_padding' ] ) && $_POST[ $name_prefix . 'survey_answers_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_padding' ] ) ) : 8;

            // Answer Gap
            $survey_answers_gap = (isset( $_POST[ $name_prefix . 'survey_answers_gap' ] ) && $_POST[ $name_prefix . 'survey_answers_gap' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_gap' ] ) ) : 0;

            // Answer image size
            $survey_answers_image_size = (isset( $_POST[ $name_prefix . 'survey_answers_image_size' ] ) && $_POST[ $name_prefix . 'survey_answers_image_size' ] != '' && $_POST[ $name_prefix . 'survey_answers_image_size' ] != 0) ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_answers_image_size' ] ) ) : 195;

            // =========== Answers Styles End   ===========



            // =========== Buttons Styles Start ===========

            // Buttons background color
            $survey_buttons_bg_color = (isset( $_POST[ $name_prefix . 'survey_button_bg_color' ] ) && $_POST[ $name_prefix . 'survey_button_bg_color' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_button_bg_color' ] ) : '#fff';

            // Buttons size
            $survey_buttons_size = (isset( $_POST[ $name_prefix . 'survey_buttons_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_size' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_size' ] ) ) : 'medium';

            // Buttons font size
            $survey_buttons_font_size = (isset( $_POST[ $name_prefix . 'survey_buttons_font_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_font_size' ] ) ) : 14;

            // Buttons mobile font size
            $survey_buttons_mobile_font_size = (isset( $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] ) && $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_mobile_font_size' ] ) ) : 14;

            // Buttons Left / Right padding
            $survey_buttons_left_right_padding = (isset( $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] ) && $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_left_right_padding' ] ) ) : 24;

            // Buttons Top / Bottom padding
            $survey_buttons_top_bottom_padding = (isset( $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] ) && $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_top_bottom_padding' ] ) ) : 0;

            // Buttons border radius
            $survey_buttons_border_radius = (isset( $_POST[ $name_prefix . 'survey_buttons_border_radius' ] ) && $_POST[ $name_prefix . 'survey_buttons_border_radius' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_border_radius' ] ) ) : 4;

            // Buttons alignment
            $survey_buttons_alignment = (isset( $_POST[ $name_prefix . 'survey_buttons_alignment' ] ) && $_POST[ $name_prefix . 'survey_buttons_alignment' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_alignment' ] ) : 'left';

            // Buttons top distance
            $survey_buttons_top_distance = (isset( $_POST[ $name_prefix . 'survey_buttons_top_distance' ] ) && $_POST[ $name_prefix . 'survey_buttons_top_distance' ] != '') ? absint( sanitize_text_field( $_POST[ $name_prefix . 'survey_buttons_top_distance' ] ) ) : 10;
            // ===========  Buttons Styles End  ===========


            // =============================================================
            // ======================    Styles Tab    =====================
            // ========================     END     ========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // ======================  Settings Tab  =======================
            // ========================    START   =========================

            // Show survey title
            $survey_show_title = (isset( $_POST[ $name_prefix . 'survey_show_title' ] ) && $_POST[ $name_prefix . 'survey_show_title' ] == 'on') ? 'on' : 'off';

            // Show survey section header
            $survey_show_section_header = (isset( $_POST[ $name_prefix . 'survey_show_section_header' ] ) && $_POST[ $name_prefix . 'survey_show_section_header' ] == 'on') ? 'on' : 'off';

            // Enable randomize answers
            $survey_enable_randomize_answers = (isset( $_POST[ $name_prefix . 'survey_enable_randomize_answers' ] ) && $_POST[ $name_prefix . 'survey_enable_randomize_answers' ] == 'on') ? 'on' : 'off';

            // Enable randomize questions
            $survey_enable_randomize_questions = (isset( $_POST[ $name_prefix . 'survey_enable_randomize_questions' ] ) && $_POST[ $name_prefix . 'survey_enable_randomize_questions' ] == 'on') ? 'on' : 'off';

            // Enable randomize questions
            $survey_enable_rtl_direction = (isset( $_POST[ $name_prefix . 'survey_enable_rtl_direction' ] ) && $_POST[ $name_prefix . 'survey_enable_rtl_direction' ] == 'on') ? 'on' : 'off';

            // Enable clear answer button
            $survey_enable_clear_answer = (isset( $_POST[ $name_prefix . 'survey_enable_clear_answer' ] ) && $_POST[ $name_prefix . 'survey_enable_clear_answer' ] == 'on') ? 'on' : 'off';

            // Enable previous button
            $survey_enable_previous_button = (isset( $_POST[ $name_prefix . 'survey_enable_previous_button' ] ) && $_POST[ $name_prefix . 'survey_enable_previous_button' ] == 'on') ? 'on' : 'off';

            // Allow HTML in answers
            $survey_allow_html_in_answers = (isset( $_POST[ $name_prefix . 'survey_allow_html_in_answers' ] ) && $_POST[ $name_prefix . 'survey_allow_html_in_answers' ] == 'on') ? 'on' : 'off';

            // Allow HTML in section description
            $survey_allow_html_in_section_description = (isset( $_POST[ $name_prefix . 'survey_allow_html_in_section_description' ] ) && $_POST[ $name_prefix . 'survey_allow_html_in_section_description' ] == 'on') ? 'on' : 'off';

            // Enable confirmation box for leaving the page
            $survey_enable_leave_page = (isset( $_POST[ $name_prefix . 'survey_enable_leave_page' ] ) && $_POST[ $name_prefix . 'survey_enable_leave_page' ] == 'on') ? 'on' : 'off';

            // Autofill information
            $survey_info_autofill = (isset( $_POST[ $name_prefix . 'survey_enable_info_autofill' ] ) && $_POST[ $name_prefix . 'survey_enable_info_autofill' ] == 'on') ? 'on' : 'off';

            //---- Schedule Start  ---- //

            // Schedule the Survey
            $survey_enable_schedule = (isset( $_POST[ $name_prefix . 'survey_enable_schedule' ] ) && $_POST[ $name_prefix . 'survey_enable_schedule' ] == 'on') ? 'on' : 'off';

            // Start date
            $survey_schedule_active = (isset( $_POST[ $name_prefix . 'survey_schedule_active' ] ) && $_POST[ $name_prefix . 'survey_schedule_active' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_schedule_active' ] ) ) : '';

            // End date
            $survey_schedule_deactive = (isset( $_POST[ $name_prefix . 'survey_schedule_deactive' ] ) && $_POST[ $name_prefix . 'survey_schedule_deactive' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_schedule_deactive' ] ) ) : '';

            // Show timer
            $survey_schedule_show_timer = (isset( $_POST[ $name_prefix . 'survey_schedule_show_timer' ] ) && $_POST[ $name_prefix . 'survey_schedule_show_timer' ] == 'on') ? 'on' : 'off';

            // Show countdown / start date
            $survey_show_timer_type = (isset( $_POST[ $name_prefix . 'survey_show_timer_type' ] ) && $_POST[ $name_prefix . 'survey_show_timer_type' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_show_timer_type' ] ) ) : 'countdown';

            // Pre start message
            $survey_schedule_pre_start_message = (isset( $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] ) && $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_schedule_pre_start_message' ] ) : __("The survey will be available soon!", "survey-maker");

            // Expiration message
            $survey_schedule_expiration_message = (isset( $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] ) && $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_schedule_expiration_message' ] ) : __("This survey has expired!", "survey-maker");

            //---- Schedule End  ---- //

            // Survey full screen mode
            $survey_full_screen = isset( $_POST[ $name_prefix . 'survey_enable_full_screen_mode' ] ) && $_POST[ $name_prefix . 'survey_enable_full_screen_mode' ] == 'on' ? 'on' : 'off';
            $survey_full_screen_button_color = isset( $_POST[ $name_prefix . 'survey_full_screen_button_color' ] ) && $_POST[ $name_prefix . 'survey_full_screen_button_color' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_full_screen_button_color' ] ) ) : '#333';

            // Survey progress bar
            $survey_enable_progress_bar = isset( $_POST[ $name_prefix . 'survey_enable_progres_bar' ] ) && $_POST[ $name_prefix . 'survey_enable_progres_bar' ] == 'on' ? 'on' : 'off';
            $survey_hide_section_pagination_text = isset( $_POST[ $name_prefix . 'survey_hide_section_pagination_text' ] ) && $_POST[ $name_prefix . 'survey_hide_section_pagination_text' ] == 'on' ? 'on' : 'off';
            $survey_pagination_positioning = isset( $_POST[ $name_prefix . 'survey_pagination_positioning' ] ) && $_POST[ $name_prefix . 'survey_pagination_positioning' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_pagination_positioning' ]) : 'none';
            $survey_hide_section_bar = isset( $_POST[ $name_prefix . 'survey_hide_section_bar' ] ) && $_POST[ $name_prefix . 'survey_hide_section_bar' ] == 'on' ? 'on' : 'off';
            $survey_progress_bar_text = isset( $_POST[ $name_prefix . 'survey_progress_bar_text' ] ) && $_POST[ $name_prefix . 'survey_progress_bar_text' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_progress_bar_text' ]) : 'Page';
            $survey_pagination_text_color = isset( $_POST[ $name_prefix . 'survey_pagination_text_color' ] ) && $_POST[ $name_prefix . 'survey_pagination_text_color' ] != '' ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_pagination_text_color' ] ) ) : '#333';
            // Survey show sections questions count
            $survey_show_sections_questions_count = isset( $_POST[ $name_prefix . 'survey_show_questions_count' ] ) && $_POST[ $name_prefix . 'survey_show_questions_count' ] == 'on' ? 'on' : 'off';

            // Survey required questions message
            $survey_required_questions_message = isset( $_POST[ $name_prefix . 'survey_required_questions_message' ] ) && $_POST[ $name_prefix . 'survey_required_questions_message' ] != '' ? sanitize_text_field($_POST[ $name_prefix . 'survey_required_questions_message' ]) : '';



            // =============================================================
            // ======================  Settings Tab  =======================
            // ========================     END    =========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // =================== Results Settings Tab  ===================
            // ========================    START   =========================


            // Redirect after submit
            $survey_redirect_after_submit = (isset( $_POST[ $name_prefix . 'survey_redirect_after_submit' ] ) && $_POST[ $name_prefix . 'survey_redirect_after_submit' ] == 'on') ? 'on' : 'off';

            // Redirect URL
            $survey_submit_redirect_url = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_url' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_submit_redirect_url' ] ) ) : '';

            // Redirect delay (sec)
            $survey_submit_redirect_delay = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_submit_redirect_delay' ] ) ) : '';

            // Redirect in new tab
            $survey_submit_redirect_new_tab = (isset( $_POST[ $name_prefix . 'survey_submit_redirect_new_tab' ] ) && $_POST[ $name_prefix . 'survey_submit_redirect_new_tab' ] == 'on') ? 'on' : 'off';

            // Enable EXIT button
            $survey_enable_exit_button = (isset( $_POST[ $name_prefix . 'survey_enable_exit_button' ] ) && $_POST[ $name_prefix . 'survey_enable_exit_button' ] == 'on') ? 'on' : 'off';

            // Redirect URL
            $survey_exit_redirect_url = (isset( $_POST[ $name_prefix . 'survey_exit_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_exit_redirect_url' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_exit_redirect_url' ] ) ) : '';

            // Enable restart button
            $survey_enable_restart_button = (isset( $_POST[ $name_prefix . 'survey_enable_restart_button' ] ) && $_POST[ $name_prefix . 'survey_enable_restart_button' ] == 'on') ? 'on' : 'off';

            // Thank you message
            $survey_final_result_text = (isset( $_POST[ $name_prefix . 'survey_final_result_text' ] ) && $_POST[ $name_prefix . 'survey_final_result_text' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_final_result_text' ] ) : '';

            // Thank you message
            $survey_main_url = (isset( $_POST[ $name_prefix . 'survey_main_url' ] ) && $_POST[ $name_prefix . 'survey_main_url' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_main_url' ] ) : '';

            // Show questions as html
            $survey_show_questions_as_html = (isset( $_POST[ $name_prefix . 'survey_show_questions_as_html' ] ) && $_POST[ $name_prefix . 'survey_show_questions_as_html' ] == 'on') ? 'on' : 'off';

            // Select survey loader
            $survey_loader = (isset( $_POST[ $name_prefix . 'survey_loader' ] ) && $_POST[ $name_prefix . 'survey_loader' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_loader' ] ) ) : 'default';

            // Social share buttons
            $survey_social_buttons   = ( isset( $_POST[ $name_prefix . 'survey_social_buttons' ] ) && $_POST[ $name_prefix . 'survey_social_buttons' ] == 'on' ) ? 'on' : 'off';
            $survey_social_buttons_heading = ( isset( $_POST[ $name_prefix . 'survey_social_buttons_heading' ] ) && $_POST[ $name_prefix . 'survey_social_buttons_heading' ] != '' ) ? wp_kses_post($_POST[ $name_prefix . 'survey_social_buttons_heading' ]) : '';
            $survey_social_button_ln = ( isset( $_POST[ $name_prefix . 'survey_enable_linkedin_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_linkedin_share_button' ] == 'on' ) ? 'on' : 'off';
            $survey_social_button_fb = ( isset( $_POST[ $name_prefix . 'survey_enable_facebook_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_facebook_share_button' ] == 'on' ) ? 'on' : 'off';
            $survey_social_button_tr = ( isset( $_POST[ $name_prefix . 'survey_enable_twitter_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_twitter_share_button' ] == 'on' ) ? 'on' : 'off';
            $survey_social_button_vk = ( isset( $_POST[ $name_prefix . 'survey_enable_vkontakte_share_button' ] ) && $_POST[ $name_prefix . 'survey_enable_vkontakte_share_button' ] == 'on' ) ? 'on' : 'off';

            // Auto numbering questions
            $survey_auto_numbering_questions = (isset( $_POST[ $name_prefix . 'survey_show_question_numbering' ] ) && $_POST[ $name_prefix . 'survey_show_question_numbering' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_show_question_numbering' ] ) : 'none';
            
            // =============================================================
            // =================== Results Settings Tab  ===================
            // ========================    END    ==========================


            // =======================  //  ======================= // ======================= // ======================= // ======================= //


            // =============================================================
            // ===================    Limitation Tab     ===================
            // ========================    START   =========================

            // Maximum number of attempts per user
            $survey_limit_users = (isset( $_POST[ $name_prefix . 'survey_limit_users' ] ) && $_POST[ $name_prefix . 'survey_limit_users' ] == 'on') ? 'on' : 'off';

            // Detects users by IP / ID
            $survey_limit_users_by = (isset( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) && $_POST[ $name_prefix . 'survey_limit_users_by' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) ) : 'ip';
            $survey_limit_users_by = (isset( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) && $_POST[ $name_prefix . 'survey_limit_users_by' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_limit_users_by' ] ) ) : 'ip';

            // Attempts count
            $survey_max_pass_count = (isset( $_POST[ $name_prefix . 'survey_max_pass_count' ] ) && $_POST[ $name_prefix . 'survey_max_pass_count' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_max_pass_count' ] ) ) : 1;

            // Limitation Message
            $survey_limitation_message = (isset( $_POST[ $name_prefix . 'survey_limitation_message' ] ) && $_POST[ $name_prefix . 'survey_limitation_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_limitation_message' ] ) : '';

            // Redirect Url
            $survey_redirect_url = (isset( $_POST[ $name_prefix . 'survey_redirect_url' ] ) && $_POST[ $name_prefix . 'survey_redirect_url' ] != '') ? stripslashes( sanitize_text_field( $_POST[ $name_prefix . 'survey_redirect_url' ] ) ) : '';

            // Redirect delay
            $survey_redirect_delay = (isset( $_POST[ $name_prefix . 'survey_redirection_delay' ] ) && $_POST[ $name_prefix . 'survey_redirection_delay' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_redirection_delay' ] ) ) : 0;

            // Only for logged in users
            $survey_enable_logged_users = (isset( $_POST[ $name_prefix . 'survey_enable_logged_users' ] ) && $_POST[ $name_prefix . 'survey_enable_logged_users' ] == 'on') ? 'on' : 'off';

            // Message - Only for logged in users
            $survey_logged_in_message = (isset( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) && $_POST[ $name_prefix . 'survey_logged_in_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_logged_in_message' ] ) : '';
            
            // Show login form
            $survey_show_login_form = (isset( $_POST[ $name_prefix . 'survey_show_login_form' ] ) && $_POST[ $name_prefix . 'survey_show_login_form' ] == 'on') ? 'on' : 'off';

            //limitation takers count
            $survey_enable_takers_count = (isset( $_POST[ $name_prefix . 'survey_enable_tackers_count' ] ) && $_POST[ $name_prefix . 'survey_enable_tackers_count' ] == 'on') ? 'on' : 'off';

            // Takers count
            $survey_takers_count = (isset( $_POST[ $name_prefix . 'survey_tackers_count' ] ) && $_POST[ $name_prefix . 'survey_tackers_count' ] != '') ? absint ( sanitize_text_field( $_POST[ $name_prefix . 'survey_tackers_count' ] ) ) : 1;


            // =============================================================
            // ===================    Limitation Tab     ===================
            // ========================    END    ==========================

            // =======================  //  ======================= // ======================= // ======================= // ======================= //

            // =============================================================
            // =====================    E-Mail Tab     =====================
            // ========================    START   =========================


            // Send Mail To User
            $survey_enable_mail_user = (isset( $_POST[ $name_prefix . 'survey_enable_mail_user' ] ) && $_POST[ $name_prefix . 'survey_enable_mail_user' ] == 'on') ? 'on' : 'off';

            // Email message
            $survey_mail_message = (isset( $_POST[ $name_prefix . 'survey_mail_message' ] ) && $_POST[ $name_prefix . 'survey_mail_message' ] != '') ? wp_kses_post( $_POST[ $name_prefix . 'survey_mail_message' ] ) : '';

            // Send email to admin
            $survey_enable_mail_admin = (isset( $_POST[ $name_prefix . 'survey_enable_mail_admin' ] ) && $_POST[ $name_prefix . 'survey_enable_mail_admin' ] == 'on') ? 'on' : 'off';

            // Send email to site admin ( SuperAdmin )
            $survey_send_mail_to_site_admin = (isset( $_POST[ $name_prefix . 'survey_send_mail_to_site_admin' ] ) && $_POST[ $name_prefix . 'survey_send_mail_to_site_admin' ] == 'on') ? 'on' : 'off';


            // Additional emails
            $survey_additional_emails = "";
            if( isset( $_POST[ $name_prefix . 'survey_additional_emails' ] ) ) {
                if(!empty( $_POST[ $name_prefix . 'survey_additional_emails' ] )) {
                    $additional_emails_arr = explode(",", $_POST[ $name_prefix . 'survey_additional_emails' ] );
                    foreach($additional_emails_arr as $email) {
                        $email = stripslashes(trim($email));
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                          $survey_additional_emails .= $email.", ";
                        }
                    }
                    $survey_additional_emails = substr($survey_additional_emails, 0, -2);
                }
            }

            // Email message
            $survey_mail_message_admin = (isset( $_POST[ $name_prefix . 'survey_mail_message_admin' ] ) && $_POST[ $name_prefix . 'survey_mail_message_admin' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_mail_message_admin' ] ) : '';


            //---- Email configuration Start  ---- //

            // From email
            $survey_email_configuration_from_email = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] != '') ? stripslashes ( sanitize_email( $_POST[ $name_prefix . 'survey_email_configuration_from_email' ] ) ) : '';

            // From name
            $survey_email_configuration_from_name = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_from_name' ] ) ) : '';

            // Subject
            $survey_email_configuration_from_subject = (isset( $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_from_subject' ] ) ) : '';

            // Reply to email
            $survey_email_configuration_replyto_email = (isset( $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] != '') ? stripslashes ( sanitize_email( $_POST[ $name_prefix . 'survey_email_configuration_replyto_email' ] ) ) : '';

            // Reply to name
            $survey_email_configuration_replyto_name = (isset( $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] ) && $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] != '') ? stripslashes ( sanitize_text_field( $_POST[ $name_prefix . 'survey_email_configuration_replyto_name' ] ) ) : '';

            //---- Email configuration End ---- //

            // Reply to name
            $survey_logo_url_new_tab = (isset( $_POST[ $name_prefix . 'survey_logo_enable_image_url_new_tab' ] ) && $_POST[ $name_prefix . 'survey_logo_enable_image_url_new_tab' ] == 'on') ? "on" : 'off';

            // =============================================================
            // =====================    E-Mail Tab     =====================
            // ========================    END    ==========================

            // Auto numbering
            $survey_auto_numbering = (isset( $_POST[ $name_prefix . 'survey_show_answers_numbering' ] ) && $_POST[ $name_prefix . 'survey_show_answers_numbering' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_show_answers_numbering' ] ) : 'none';

            // Loader text
            $survey_loader_text = (isset( $_POST[ $name_prefix . 'survey_loader_text_value' ] ) && $_POST[ $name_prefix . 'survey_loader_text_value' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_text_value' ] ) : '';
            // Loader Gif
            $survey_loader_gif       = (isset( $_POST[ $name_prefix . 'survey_loader_custom_gif' ] ) && $_POST[ $name_prefix . 'survey_loader_custom_gif' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_custom_gif' ] ) : '';
            if ($survey_loader_gif != '' && exif_imagetype( $survey_loader_gif ) != IMAGETYPE_GIF) {
                $survey_loader_gif = '';
            }
            $survey_loader_gif_width = (isset( $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] ) && $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] != '') ? sanitize_text_field( $_POST[ $name_prefix . 'survey_loader_custom_gif_width' ] ) : '100';
            if($survey_loader_gif_width <= "0"){
                $survey_loader_gif_width = "100";
            }
            
            // Options
            $options = array(
                'survey_version'                    => SURVEY_MAKER_VERSION,
                // Styles Tab
                'survey_theme'                      => $survey_theme,
                'survey_color'                      => $survey_color,
                'survey_background_color'           => $survey_background_color,
                'survey_text_color'                 => $survey_text_color,
                'survey_buttons_text_color'         => $survey_buttons_text_color,
                'survey_width'                      => $survey_width,
                'survey_width_by_percentage_px'     => $survey_width_by_percentage_px,
                'survey_mobile_width'               => $survey_mobile_width,
                'survey_mobile_width_by_percent_px' => $survey_mobile_width_by_percentage_px,
                'survey_mobile_max_width'           => $survey_mobile_max_width,
                'survey_custom_class'               => $survey_custom_class,
                'survey_custom_css'                 => $survey_custom_css,
                'survey_logo'                       => $survey_logo,
                'survey_logo_url'                   => $survey_logo_image_url,
                'survey_enable_logo_url'            => $survey_logo_image_url_check,
                'survey_logo_image_position'        => $survey_logo_image_position,
                'survey_logo_title'                 => $survey_logo_title,
                'survey_cover_photo'                => $survey_cover_photo,
                'survey_cover_photo_height'         => $survey_cover_photo_height,
                'survey_cover_photo_mobile_height'  => $survey_cover_photo_mobile_height,
                'survey_cover_photo_position'       => $survey_cover_photo_position,
                'survey_cover_photo_object_fit'     => $survey_cover_photo_object_fit,

                'survey_question_font_size'         => $survey_question_font_size,
                'survey_question_font_size_mobile'  => $survey_question_font_size_mobile,
                'survey_question_title_alignment'   => $survey_question_title_alignment,
                'survey_question_image_width'       => $survey_question_image_width,
                'survey_question_image_height'      => $survey_question_image_height,
                'survey_question_image_sizing'      => $survey_question_image_sizing,
                'survey_question_padding'           => $survey_question_padding,
                'survey_question_caption_text_color' => $survey_question_caption_text_color,
                'survey_question_caption_text_alignment' => $survey_question_caption_text_alignment,
                'survey_question_caption_font_size' => $survey_question_caption_font_size,
                'survey_question_caption_font_size_on_mobile' => $survey_question_caption_font_size_on_mobile,
                'survey_question_caption_text_transform' => $survey_question_caption_text_transform,

                'survey_answer_font_size'           => $survey_answer_font_size,
                'survey_answer_font_size_on_mobile' => $survey_answer_font_size_on_mobile,
                'survey_answers_view'               => $survey_answers_view,
                'survey_answers_view_alignment'     => $survey_answers_view_alignment,
                'survey_answers_object_fit'         => $survey_answers_object_fit,
                'survey_answers_padding'            => $survey_answers_padding,
                'survey_answers_gap'                => $survey_answers_gap,
                'survey_answers_image_size'         => $survey_answers_image_size,

                'survey_buttons_bg_color'              => $survey_buttons_bg_color,
                'survey_buttons_size'                  => $survey_buttons_size,
                'survey_buttons_font_size'             => $survey_buttons_font_size,
                'survey_buttons_mobile_font_size'      => $survey_buttons_mobile_font_size,
                'survey_buttons_left_right_padding'    => $survey_buttons_left_right_padding,
                'survey_buttons_top_bottom_padding'    => $survey_buttons_top_bottom_padding,
                'survey_buttons_border_radius'         => $survey_buttons_border_radius,
                'survey_buttons_alignment'             => $survey_buttons_alignment,
                'survey_buttons_top_distance'          => $survey_buttons_top_distance,
                'survey_title_alignment'               => $survey_title_alignment,
                'survey_title_font_size'               => $survey_title_font_size,
                'survey_title_font_size_for_mobile'    => $survey_title_font_size_for_mobile,
                'survey_title_box_shadow_enable'       => $survey_title_box_shadow_enable,
                'survey_title_box_shadow_color'        => $survey_title_box_shadow_color,
                'survey_title_text_shadow_x_offset'    => $survey_title_text_shadow_x_offset,
                'survey_title_text_shadow_y_offset'    => $survey_title_text_shadow_y_offset,
                'survey_title_text_shadow_z_offset'    => $survey_title_text_shadow_z_offset,
                'survey_section_title_font_size'       => $survey_section_title_font_size,
                'survey_section_title_font_size_mobile' => $survey_section_title_font_size_mobile,
                'survey_section_title_alignment'       => $survey_section_title_alignment,
                'survey_section_description_alignment' => $survey_section_description_alignment,
                'survey_section_description_font_size' => $survey_section_description_font_size,
                'survey_section_description_font_size_mobile' => $survey_section_description_font_size_mobile,

                // Settings Tab
                'survey_show_title'                         => $survey_show_title,
                'survey_show_section_header'                => $survey_show_section_header,
                'survey_enable_randomize_answers'           => $survey_enable_randomize_answers,
                'survey_enable_randomize_questions'         => $survey_enable_randomize_questions,
                'survey_enable_rtl_direction'               => $survey_enable_rtl_direction,
                'survey_enable_clear_answer'                => $survey_enable_clear_answer,
                'survey_enable_previous_button'             => $survey_enable_previous_button,
                'survey_allow_html_in_answers'              => $survey_allow_html_in_answers,
                'survey_allow_html_in_section_description'  => $survey_allow_html_in_section_description,
                'survey_enable_leave_page'                  => $survey_enable_leave_page,
                'survey_auto_numbering'                     => $survey_auto_numbering,
                'survey_enable_info_autofill'               => $survey_info_autofill,
                'survey_auto_numbering_questions'           => $survey_auto_numbering_questions,

                'survey_enable_schedule'            => $survey_enable_schedule,
                'survey_schedule_active'            => $survey_schedule_active,
                'survey_schedule_deactive'          => $survey_schedule_deactive,
                'survey_schedule_show_timer'        => $survey_schedule_show_timer,
                'survey_show_timer_type'            => $survey_show_timer_type,
                'survey_schedule_pre_start_message' => $survey_schedule_pre_start_message,
                'survey_schedule_expiration_message'=> $survey_schedule_expiration_message,
                'survey_logo_url_new_tab'           => $survey_logo_url_new_tab,
                'survey_full_screen_mode'           => $survey_full_screen,
                'survey_full_screen_button_color'   => $survey_full_screen_button_color,
                'survey_enable_progress_bar'        => $survey_enable_progress_bar,
                'survey_hide_section_pagination_text' => $survey_hide_section_pagination_text,
                'survey_pagination_positioning'       => $survey_pagination_positioning,
                'survey_hide_section_bar'             => $survey_hide_section_bar,
                'survey_progress_bar_text'            => $survey_progress_bar_text,
                'survey_pagination_text_color'        => $survey_pagination_text_color,
                'survey_show_sections_questions_count' => $survey_show_sections_questions_count,
                'survey_required_questions_message' => $survey_required_questions_message,

                // Result Settings Tab
                'survey_redirect_after_submit'      => $survey_redirect_after_submit,
                'survey_submit_redirect_url'        => $survey_submit_redirect_url,
                'survey_submit_redirect_delay'      => $survey_submit_redirect_delay,
                'survey_submit_redirect_new_tab'    => $survey_submit_redirect_new_tab,
                'survey_enable_exit_button'         => $survey_enable_exit_button,
                'survey_exit_redirect_url'          => $survey_exit_redirect_url,
                'survey_enable_restart_button'      => $survey_enable_restart_button,
                'survey_final_result_text'          => $survey_final_result_text,
                'survey_show_questions_as_html'     => $survey_show_questions_as_html,
                'survey_main_url'                   => $survey_main_url,
                'survey_loader'                     => $survey_loader,
                'survey_loader_text'                => $survey_loader_text,
                'survey_loader_gif'                 => $survey_loader_gif,
                'survey_loader_gif_width'           => $survey_loader_gif_width,
                'survey_social_buttons'             => $survey_social_buttons,
                'survey_social_buttons_heading'     => $survey_social_buttons_heading,
                'survey_social_button_ln'           => $survey_social_button_ln,
                'survey_social_button_fb'           => $survey_social_button_fb,
                'survey_social_button_tr'           => $survey_social_button_tr,
                'survey_social_button_vk'           => $survey_social_button_vk,

                // Limitation Tab
                'survey_limit_users'                => $survey_limit_users,
                'survey_limit_users_by'             => $survey_limit_users_by,
                'survey_max_pass_count'             => $survey_max_pass_count,
                'survey_limitation_message'         => $survey_limitation_message,
                'survey_redirect_url'               => $survey_redirect_url,
                'survey_redirect_delay'             => $survey_redirect_delay,
                'survey_enable_logged_users'        => $survey_enable_logged_users,
                'survey_logged_in_message'          => $survey_logged_in_message,
                'survey_show_login_form'            => $survey_show_login_form,
                'survey_enable_takers_count'        => $survey_enable_takers_count,
                'survey_takers_count'               => $survey_takers_count,

                // E-mail Tab
                'survey_enable_mail_user'           => $survey_enable_mail_user,
                'survey_mail_message'               => $survey_mail_message,
                'survey_enable_mail_admin'          => $survey_enable_mail_admin,
                'survey_send_mail_to_site_admin'    => $survey_send_mail_to_site_admin,
                'survey_additional_emails'          => $survey_additional_emails,
                'survey_mail_message_admin'         => $survey_mail_message_admin,

                'survey_email_configuration_from_email'    => $survey_email_configuration_from_email,
                'survey_email_configuration_from_name'     => $survey_email_configuration_from_name,
                'survey_email_configuration_from_subject'  => $survey_email_configuration_from_subject,
                'survey_email_configuration_replyto_email' => $survey_email_configuration_replyto_email,
                'survey_email_configuration_replyto_name'  => $survey_email_configuration_replyto_name,

            );

            if (isset($_POST['save_type_default_options']) && $_POST['save_type_default_options'] == 'save_type_default_options') {

                $survey_default_options = $options;
                $survey_default_options['survey_enable_schedule'] = 'off';
                unset($survey_default_options['survey_schedule_active']);
                unset($survey_default_options['survey_schedule_deactive']);

                $this->settings_obj->ays_update_setting( 'survey_default_options', json_encode( $survey_default_options ) );
            }
            
            $options = apply_filters("ays_sm_survey_page_integrations_saves", $options, array());


            if (isset($_POST[ $name_prefix . 'sections_delete' ]) && ! empty( $_POST[ $name_prefix . 'sections_delete' ] )) {
                $sections_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'sections_delete' ] );
                foreach( $sections_delete as $key => $del_id ) {
                    if( in_array( $del_id, $section_ids ) ){
                        $del_index = array_search( $del_id, $section_ids );
                        unset($section_ids[$del_index]);
                    }
                    $wpdb->delete(
                        $sections_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            if (isset($_POST[ $name_prefix . 'questions_delete' ]) && ! empty( $_POST[ $name_prefix . 'questions_delete' ] )) {
                $questions_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'questions_delete' ] );
                foreach ( $questions_delete as $key => $del_id ) {
                    if( in_array( $del_id, $question_ids ) ){
                        $del_index = array_search( $del_id, $question_ids );
                        unset($question_ids[$del_index]);
                    }
                    $wpdb->delete(
                        $questions_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            if (isset($_POST[ $name_prefix . 'answers_delete' ]) && ! empty( $_POST[ $name_prefix . 'answers_delete' ] )) {
                $answers_delete = array_map( 'sanitize_text_field', $_POST[ $name_prefix . 'answers_delete' ] );
                foreach ( $answers_delete as $key => $del_id ) {
                    $wpdb->delete(
                        $answers_table,
                        array( 'id' => intval( $del_id ) ),
                        array( '%d' )
                    );
                }
            }

            $question_ids_new = array();
            $section_ids_array = array();

            if (isset($_POST[ $name_prefix . 'section_add' ]) && ! empty( $_POST[ $name_prefix . 'section_add' ] )) {
                // --------------------------- Sections Table ------------Start--------------- //
                // $section_ordering = 1;
                $textareas = array(
                    'description',
                    'title'
                );

                //$section_add = Survey_Maker_Data::recursive_sanitize_text_field( $_POST[ $name_prefix . 'section_add' ], $textareas );
                array_walk_recursive($_POST[ $name_prefix . 'section_add' ] , [$this, 'survey_recursive_sanitize_data']);
                foreach ( $_POST[ $name_prefix . 'section_add' ] as $key => $section) {

                    //Section Title
                    $section_title = ( isset($section['title']) && $section['title'] != '' ) ? stripslashes( $section['title'] ) : '';

                    //Section Description
                    $section_description = ( isset($section['description']) && $section['description'] != '' ) ? stripslashes( $section['description'] ) : '';

                    //Section Ordering
                    $section_ordering = ( isset($section['ordering']) && $section['ordering'] != '' ) ? $section['ordering'] : '';

                    // Section collapsed
                    $section_collapsed = ( isset($section['options']['collapsed']) && $section['options']['collapsed'] != '' ) ? $section['options']['collapsed'] : 'expanded';

                    // Options
                    $section_options = array(
                        'collapsed' => $section_collapsed
                    );

                    $result = $wpdb->insert(
                        $sections_table,
                        array(
                            'title'         => $section_title,
                            'description'   => $section_description,
                            'ordering'      => $section_ordering,
                            'options'       => json_encode( $section_options ),
                        ),
                        array(
                            '%s', // title
                            '%s', // description
                            '%d', // ordering
                            '%s', // options
                        )
                    );

                    $section_insert_id = $wpdb->insert_id;
                    $section_ids_array[] = $section_insert_id;

                    // --------------------------- Question Table ------------Start--------------- //
                    // $question_ordering = 1;
                    if(isset($section['questions']) && !empty($section['questions'])){
                        foreach ($section['questions'] as $question_id => $question) {
                            $question_id = absint(intval($question_id));

                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', "survey-maker" );

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';
                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                            
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';
                            

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,
                                'survey_input_type_placeholder' => $survey_input_type_placeholder,
                                
                                'image_caption'     => $question_image_caption,
                                'image_caption_enable'     => $question_image_caption_enable,

                                'with_editor' => $with_editor,
                            );

                            $question_result = $wpdb->update(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_insert_id,
                                    'category_ids'      => $category_ids,
                                    'question'          => $ays_question,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options),
                                ),
                                array( 'id' => $question_id ),
                                array(
                                    '%d', // author_id
                                    '%d', // section_id
                                    '%s', // category_ids
                                    '%s', // question
                                    '%s', // type
                                    '%s', // status
                                    '%s', // trash_status
                                    '%s', // date_created
                                    '%s', // date_modified
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                    '%s', // image
                                    '%d', // ordering
                                    '%s', // options
                                ),
                                array( '%d' )
                            );
                            
                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                foreach ($question['answers'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->update(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array( 'id' => $answer_id ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        ),
                                        array( '%d' )
                                    );

                                    // $answer_ordering++;
                                }
                            }

                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        )
                                    );

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }

                    // $question_ordering = 1;
                    $question_id_array = array();
                    if ( isset( $section['questions_add'] ) && ! empty( $section['questions_add'] ) ) {
                        foreach ($section['questions_add'] as $question_id => $question) {
                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', "survey-maker" );

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';
                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                                                        
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';
                            
                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,
                                'survey_input_type_placeholder' => $survey_input_type_placeholder,                                
                                'image_caption'               => $question_image_caption,
                                'image_caption_enable'        => $question_image_caption_enable,

                                'with_editor' => $with_editor,
                            );

                            $question_result = $wpdb->insert(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_insert_id,
                                    'category_ids'      => $category_ids,
                                    'question'          => $ays_question,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options),
                                ),
                                array(
                                    '%d', // author_id
                                    '%d', // section_id
                                    '%s', // category_ids
                                    '%s', // question
                                    '%s', // type
                                    '%s', // status
                                    '%s', // trash_status
                                    '%s', // date_created
                                    '%s', // date_modified
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                    '%s', // image
                                    '%d', // ordering
                                    '%s', // options
                                )
                            );

                            $question_insert_id = $wpdb->insert_id;
                            $question_ids_new[] = $question_insert_id;

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers_add'] ) && ! empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_insert_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        )
                                    );
                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //
                            // $question_ordering++;
                        }
                    }
                    // --------------------------- Question Table ------------End--------------- //
                    // $section_ordering++;
                }
                // --------------------------- Sections Table ------------End--------------- //
            }

            if (isset($_POST[ $name_prefix . 'sections' ]) && !empty( $_POST[ $name_prefix . 'sections' ] )) {
                // --------------------------- Sections Table ------------Start--------------- //

                // $section_ordering = 1;
                $textareas = array(
                    'description',
                    'title'
                );

                //$section_update = Survey_Maker_Data::recursive_sanitize_text_field( $_POST[ $name_prefix . 'sections' ], $textareas );
                $section_update = $_POST[ $name_prefix . 'sections' ];

                foreach ( $section_update as $section_id => $section) {
                    $section_id = absint(intval($section_id));
                    $section_title = $section['title'];
                    $section_description = $section['description'];
                    $section_ordering = $section['ordering'];

                    // Section collapsed
                    $section_collapsed = ( isset($section['options']['collapsed']) && $section['options']['collapsed'] != '' ) ? $section['options']['collapsed'] : 'expanded';

                    // Options
                    $section_options = array(
                        'collapsed' => $section_collapsed
                    );

                    $result = $wpdb->update(
                        $sections_table,
                        array(
                            'title'             => $section_title,
                            'description'       => $section_description,
                            'ordering'          => $section_ordering,
                            'options'           => json_encode( $section_options ),
                        ),
                        array( 'id' => $section_id ),
                        array(
                            '%s', // title
                            '%s', // description
                            '%d', // ordering
                            '%s', // options
                        ),
                        array( '%d' )
                    );

                    // --------------------------- Question Table ------------Start--------------- //
                    // $question_ordering = 1;
                    if( isset( $section['questions'] ) && ! empty( $section['questions'] ) ){
                        foreach ($section['questions'] as $question_id => $question) {
                            $question_id = absint(intval($question_id));

                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', "survey-maker" );

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';
                            
                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';
                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';
                            
                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';
                                                                                    
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,  
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,                             
                                'survey_input_type_placeholder' => $survey_input_type_placeholder,
                                
                                'image_caption'     => $question_image_caption,
                                'image_caption_enable'     => $question_image_caption_enable,                             

                                'with_editor' => $with_editor,
                            );

                            $question_result = $wpdb->update(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_id,
                                    'category_ids'      => $category_ids,
                                    'question'          => $ays_question,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options),
                                ),
                                array( 'id' => $question_id ),
                                array(
                                    '%d', // author_id
                                    '%d', // section_id
                                    '%s', // category_ids
                                    '%s', // question
                                    '%s', // type
                                    '%s', // status
                                    '%s', // trash_status
                                    '%s', // date_created
                                    '%s', // date_modified
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                    '%s', // image
                                    '%d', // ordering
                                    '%s', // options
                                ),
                                array( '%d' )
                            );

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                                foreach ($question['answers'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;

                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->update(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array( 'id' => $answer_id ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        ),
                                        array( '%d' )
                                    );

                                    // $answer_ordering++;
                                }
                            }

                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        )
                                    );

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }

                    if( isset( $section['questions_add'] ) && !empty( $section['questions_add'] ) ){
                        foreach ($section['questions_add'] as $question_id => $question) {
                            $ays_question = ( isset($question['title']) && trim( $question['title'] ) != '' ) ? stripslashes( $question['title'] ) : __( 'Question', "survey-maker" );

                            $type = ( isset($question['type']) && $question['type'] != '' ) ? $question['type'] : 'radio';

                            $user_variant = ( isset($question['user_variant']) && $question['user_variant'] != '' ) ? $question['user_variant'] : 'off';

                            $user_explanation = '';

                            $question_image = ( isset($question['image']) && $question['image'] != '' ) ? $question['image'] : '';

                            $required = isset( $question['options']['required'] ) ? $question['options']['required'] : 'off';

                            $question_ordering = ( isset($question['ordering']) && $question['ordering'] != '' ) ? $question['ordering'] : '';

                            // Question collapsed
                            $question_collapsed = ( isset($question['options']['collapsed']) && $question['options']['collapsed'] != '' ) ? $question['options']['collapsed'] : 'expanded';

                            // Enable selection count
                            $enable_max_selection_count = isset($question['options']['enable_max_selection_count']) ? $question['options']['enable_max_selection_count'] : 'off';
                            // Maximum selection count
                            $max_selection_count = ( isset($question['options']['max_selection_count']) && $question['options']['max_selection_count'] != '' ) ? $question['options']['max_selection_count'] : '';
                            // Minimum selection count
                            $min_selection_count = ( isset($question['options']['min_selection_count']) && $question['options']['min_selection_count'] != '' ) ? $question['options']['min_selection_count'] : '';

                            // Text Limitations
                            // Enable selection count
                            $enable_word_limitation = (isset($question['options']['enable_word_limitation']) && $question['options']['enable_word_limitation'] == "on") ? "on" : 'off';
                            // Limitation type
                            $limitation_limit_by = ( isset($question['options']['limit_by']) && $question['options']['limit_by'] != '' ) ? $question['options']['limit_by'] : "";
                            // Limitation char/word length
                            $limitation_limit_length = ( isset($question['options']['limit_length']) && $question['options']['limit_length'] != '' ) ? $question['options']['limit_length'] : '';
                            // Limitation char/word length
                            $limitation_limit_counter = ( isset($question['options']['limit_counter']) && $question['options']['limit_counter'] == 'on' ) ? "on" : 'off';

                            // Number Limitations
                            // Enable Limitation
                            $enable_number_limitation = (isset($question['options']['enable_number_limitation']) && $question['options']['enable_number_limitation'] == "on") ? "on" : 'off';
                            // Min number
                            $number_min_selection = ( isset($question['options']['number_min_selection']) && $question['options']['number_min_selection'] != '' ) ? $question['options']['number_min_selection'] : "";
                            // Max number
                            $number_max_selection = ( isset($question['options']['number_max_selection']) && $question['options']['number_max_selection'] != '' ) ? $question['options']['number_max_selection'] : '';
                            // Error message
                            $number_error_message = ( isset($question['options']['number_error_message']) && $question['options']['number_error_message'] != '' ) ? $question['options']['number_error_message'] : '';
                            // Show error message
                            $enable_number_error_message = (isset($question['options']['enable_number_error_message']) && $question['options']['enable_number_error_message'] == "on") ? "on" : 'off';
                            // Char length
                            $number_limit_length = (isset($question['options']['number_limit_length']) && $question['options']['number_limit_length'] != "") ? $question['options']['number_limit_length'] : '';
                            // Show Char length
                            $enable_number_limit_counter = (isset($question['options']['enable_number_limit_counter']) && $question['options']['enable_number_limit_counter'] == "on") ? "on" : 'off';                            
                                                                                                                
                            // Input types placeholders
                            $survey_input_type_placeholder = (isset($question['options']['placeholder']) && $question['options']['placeholder'] != "") ? $question['options']['placeholder'] : '';

                            $question_image_caption = ( isset($question['options']['image_caption']) && $question['options']['image_caption'] != '' ) ? sanitize_text_field($question['options']['image_caption']) : '';
                            $question_image_caption_enable = ( isset($question['options']['image_caption_enable']) && $question['options']['image_caption_enable'] == 'on' ) ? 'on' : 'off';

                            // With editor
                            $with_editor = ( isset($question['options']['with_editor']) && $question['options']['with_editor'] == 'on' ) ? 'on' : 'off';

                            $question_options = array(
                                'required' => $required,
                                'collapsed' => $question_collapsed,
                                'enable_max_selection_count' => $enable_max_selection_count,
                                'max_selection_count' => $max_selection_count,
                                'min_selection_count' => $min_selection_count,
                                // Text Limitations
                                'enable_word_limitation' => $enable_word_limitation,
                                'limit_by' => $limitation_limit_by,
                                'limit_length' => $limitation_limit_length,
                                'limit_counter' => $limitation_limit_counter,
                                // Number Limitations
                                'enable_number_limitation'    => $enable_number_limitation,
                                'number_min_selection'        => $number_min_selection,
                                'number_max_selection'        => $number_max_selection,
                                'number_error_message'        => $number_error_message,
                                'enable_number_error_message' => $enable_number_error_message,
                                'number_limit_length'         => $number_limit_length,
                                'enable_number_limit_counter' => $enable_number_limit_counter,
                                'survey_input_type_placeholder' => $survey_input_type_placeholder,
                                
                                'image_caption'     => $question_image_caption,
                                'image_caption_enable'     => $question_image_caption_enable,
                                
                                'with_editor' => $with_editor,
                            );

                            $question_result = $wpdb->insert(
                                $questions_table,
                                array(
                                    'author_id'         => $author_id,
                                    'section_id'        => $section_id,
                                    'category_ids'      => $category_ids,
                                    'question'          => $ays_question,
                                    'type'              => $type,
                                    'status'            => $status,
                                    'trash_status'      => $trash_status,
                                    'date_created'      => $date_created,
                                    'date_modified'     => $date_modified,
                                    'user_variant'      => $user_variant,
                                    'user_explanation'  => $user_explanation,
                                    'image'             => $question_image,
                                    'ordering'          => $question_ordering,
                                    'options'           => json_encode($question_options),
                                ),
                                array(
                                    '%d', // author_id
                                    '%d', // section_id
                                    '%s', // category_ids
                                    '%s', // question
                                    '%s', // type
                                    '%s', // status
                                    '%s', // trash_status
                                    '%s', // date_created
                                    '%s', // date_modified
                                    '%s', // user_variant
                                    '%s', // user_explanation
                                    '%s', // image
                                    '%d', // ordering
                                    '%s', // options
                                )
                            );

                            $question_new_id = $wpdb->insert_id;
                            $question_ids_new[] = $question_new_id;

                            // --------------------------- Answers Table ------------Start--------------- //
                            // $answer_ordering = 1;
                            if( isset( $question['answers_add'] ) && !empty( $question['answers_add'] ) ){
                                foreach ($question['answers_add'] as $answer_id => $answer) {
                                    $answer_id = absint(intval($answer_id));
                                    $answer_ordering = ( isset($answer['ordering']) && $answer['ordering'] != '' ) ? $answer['ordering'] : '';
                                    $answer_title = ( isset($answer['title']) && trim( $answer['title'] ) != '' ) ? stripslashes( $answer['title'] ) : __( 'Option', "survey-maker" ) . ' ' . $answer_ordering;
                                    $answer_image = '';
                                    if ( isset( $answer['image'] ) && $answer['image'] != '' ) {
                                        $answer_image = $answer['image'];
                                    }
                                    $placeholder = '';
                                    $answer_result = $wpdb->insert(
                                        $answers_table,
                                        array(
                                            'question_id'       => $question_new_id,
                                            'answer'            => $answer_title,
                                            'image'             => $answer_image,
                                            'ordering'          => $answer_ordering,
                                            'placeholder'       => $placeholder,
                                        ),
                                        array(
                                            '%d', // question_id
                                            '%s', // answer
                                            '%s', // image
                                            '%d', // ordering
                                            '%s', // placeholder
                                        )
                                    );

                                    // $answer_ordering++;
                                }
                            }
                            // --------------------------- Answers Table ------------End--------------- //

                            // $question_ordering++;
                        }
                    }
                    // --------------------------- Question Table ------------End--------------- //

                    // $section_ordering++;
                }
                // --------------------------- Sections Table ------------End--------------- //
            }

            $message = '';
            if( $id == 0 ){
                $sections_count = count( $section_ids_array );
                $questions_count = count( $question_ids_new );
                $section_ids = empty( $section_ids_array ) ? '' : implode( ',', $section_ids_array );
                $question_ids = empty( $question_ids_new ) ? '' : implode( ',', $question_ids_new );
                $result = $wpdb->insert(
                    $table,
                    array(
                        'author_id'         => $author_id,
                        'title'             => $title,
                        'description'       => $description,
                        'category_ids'      => $category_ids,
                        'question_ids'      => $question_ids,
                        'sections_count'    => $sections_count,
                        'questions_count'   => $questions_count,
                        'image'             => $image,
                        'status'            => $status,
                        'trash_status'      => $trash_status,
                        'date_created'      => $date_created,
                        'date_modified'     => $date_modified,
                        'ordering'          => $ordering,
                        'post_id'           => $post_id,
                        'section_ids'       => $section_ids,
                        'options'           => json_encode( $options ),
                    ),
                    array(
                        '%d', // author_id
                        '%s', // title
                        '%s', // description
                        '%s', // category_ids
                        '%s', // question_ids
                        '%d', // sections_count
                        '%d', // questions_count
                        '%s', // image
                        '%s', // status
                        '%s', // trash_status
                        '%s', // date_created
                        '%s', // date_modified
                        '%d', // ordering
                        '%d', // post_id
                        '%s', // section_ids
                        '%s', // options
                    )
                );

                $inserted_id = $wpdb->insert_id;
                $message = 'created';
            }else{
                if( ! empty( $section_ids ) ){
                    if( ! empty( $section_ids_array ) ){
                        $section_ids = array_merge( $section_ids, $section_ids_array );
                    }
                }else{
                    if( ! empty( $section_ids_array ) ){
                        $section_ids = array_merge( $section_ids, $section_ids_array );
                    }
                }
                $sections_count = count( $section_ids );
                $section_ids = !empty( $section_ids ) ? implode(',', $section_ids) : '';
                // $question_ids = empty( $question_ids_new ) ? '' : implode( ',', $question_ids_new );
                if( ! empty( $question_ids ) ){
                    if( ! empty( $question_ids_new ) ){
                        $question_ids = array_merge( $question_ids, $question_ids_new );
                    }
                }else{
                    if( ! empty( $question_ids_new ) ){
                        $question_ids = array_merge( $question_ids, $question_ids_new );
                    }
                }
                $questions_count = count( $question_ids );
                $question_ids = empty( $question_ids ) ? '' : implode( ',', $question_ids );


                $result = $wpdb->update(
                    $table,
                    array(
                        'author_id'         => $author_id,
                        'title'             => $title,
                        'description'       => $description,
                        'category_ids'      => $category_ids,
                        'question_ids'      => $question_ids,
                        'sections_count'    => $sections_count,
                        'questions_count'   => $questions_count,
                        'image'             => $image,
                        'status'            => $status,
                        'date_created'      => $date_created,
                        'date_modified'     => $date_modified,
                        'post_id'           => $post_id,
                        'section_ids'       => $section_ids,
                        'options'           => json_encode( $options ),
                    ),
                    array( 'id' => $id ),
                    array(
                        '%d', // author_id
                        '%s', // title
                        '%s', // description
                        '%s', // category_ids
                        '%s', // question_ids
                        '%d', // sections_count
                        '%d', // questions_count
                        '%s', // image
                        '%s', // status
                        '%s', // date_created
                        '%s', // date_modified
                        '%d', // post_id
                        '%s', // section_ids
                        '%s', // options
                    ),
                    array( '%d' )
                );

                $inserted_id = $id;
                $message = 'updated';
            }

            $ays_survey_tab = isset($_POST['ays_survey_tab']) ? sanitize_text_field( $_POST['ays_survey_tab'] ) : 'tab1';
            if( $result >= 0  ) {
                if($save_type == 'apply'){
                    if($id == 0){
                        $url = esc_url_raw( add_query_arg( array(
                            "action"    => "edit",
                            "id"        => $inserted_id,
                            "tab"       => $ays_survey_tab,
                            "status"    => $message
                        ) ) );
                    }else{
                        $url = esc_url_raw( add_query_arg( array(
                            "tab"    => $ays_survey_tab,
                            "status" => $message
                        ) ) );
                    }
                    wp_redirect( $url );
                }elseif($save_type == 'save_new'){
                    $url = remove_query_arg( array('id') );
                    $url = esc_url_raw( add_query_arg( array(
                        "action" => "add",
                        "status" => $message
                    ), $url ) );
                    wp_redirect( $url );
                }else{
                    $url = remove_query_arg( array('action', 'id') );
                    $url = esc_url_raw( add_query_arg( array(
                        "tab"    => $ays_survey_tab,
                        "status" => $message
                    ), $url ) );
                    wp_redirect( $url );
                }
            }

        }
    }

    private function get_max_id() {
        global $wpdb;
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql = "SELECT MAX(id) FROM {$table}";

        $result = $wpdb->get_var($sql);

        return $result;
    }

    /**
     * Delete a customer record.
     *
     * @param int $id customer ID
     */
    public static function delete_items( $id ) {
        global $wpdb;

        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'survey_id' => absint( $id ) ),
            array( '%d' )
        );

        $wpdb->delete(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 'id' => absint( $id ) ),
            array( '%d' )
        );

    }

    /**
     * Move to trash a customer record.
     *
     * @param int $id customer ID
     */
    public static function trash_items( $id ) {
        global $wpdb;
        $db_item = self::get_item_by_id( $id );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'status' => 'trashed' ),
            array( 'survey_id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 
                'status' => 'trashed',
                'trash_status' => $db_item['status'],
            ),
            array( 'id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

    }

    /**
     * Restore a customer record.
     *
     * @param int $id customer ID
     */
    public static function restore_items( $id ) {
        global $wpdb;
        $db_item = self::get_item_by_id( $id );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions",
            array( 'status' => 'published' ),
            array( 'survey_id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );

        $wpdb->update(
            $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys",
            array( 
                'status' => $db_item['trash_status'],
                'trash_status' => '',
            ),
            array( 'id' => absint( $id ) ),
            array( '%s', '%s' ),
            array( '%d' )
        );
    }

    /**
     * Duplicate a customer record.
     *
     * @param int $id customer ID
     */
    public function duplicate_items( $id ){
        global $wpdb;
        $survey_table    = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        $sections_table  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $answers_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $object = $this->get_item_by_id( $id );
        $question_id_arr = isset($object['question_ids']) && $object['question_ids'] != "" ? explode("," , $object['question_ids']) : array(); 
        $old_questions = array();
        $survey_id   = isset($object['id']) && $object['id'] != "" ? $object['id'] : "";
        $section_ids = isset($object['section_ids']) && $object['section_ids'] != "" ? $object['section_ids'] : array();
        // Get sections
        $old_sections = Survey_Maker_Data::get_sections_by_survey_id($section_ids);
        $section_new_ids = array();
        // Get questions
        $old_questions = Survey_Maker_Data::get_question_by_ids($question_id_arr);
        $question_new_ids = array();

        foreach($old_sections as $section_key => $section_value){
            $section_id            = isset($section_value['id']) && $section_value['id'] != "" ? sanitize_text_field($section_value['id']) : "";
            $new_section_title     = isset($section_value['title']) && $section_value['title'] != "" ? sanitize_text_field($section_value['title']) : "";
            $new_section_desc      = isset($section_value['description']) && $section_value['description'] != "" ? sanitize_text_field($section_value['description']) : "";
            $new_section_ordering  = isset($section_value['ordering']) && $section_value['ordering'] != "" ? intval(sanitize_text_field($section_value['ordering'])) : "";
            $new_section_options   = isset($section_value['options']) && $section_value['options'] != "" ? sanitize_text_field($section_value['options']) : "";

            
            $sresult = $wpdb->insert(
                $sections_table,
                array(
                    'title'       => $new_section_title,
                    'description' => $new_section_desc,
                    'ordering'    => $new_section_ordering,
                    'options'     => $new_section_options,
                ),
                array(
                    '%s', // title
                    '%s', // description
                    '%d', // ordering
                    '%s', // options
                    )
                );
            $section_new_ids[] = $wpdb->insert_id;
            $section_new_ids_for_question[$section_id] = $wpdb->insert_id;
        }
        
        foreach($old_questions as $question_key => $question_value){
            $question_id  = isset($question_value->id) && $question_value->id != "" ? intval(sanitize_text_field($question_value->id)) : "";
            $question_answers        = isset($question_value->answers) && $question_value->answers != "" ? $question_value->answers : array();
            $new_question_author_id  = isset($question_value->author_id) && $question_value->author_id != "" ? intval(sanitize_text_field($question_value->author_id)) : "";
            $new_question_section_id = isset($section_new_ids_for_question[$question_value->section_id]) && $section_new_ids_for_question[$question_value->section_id] != "" ? intval(sanitize_text_field($section_new_ids_for_question[$question_value->section_id])) : "";
            $new_question_cat_ids    = isset($question_value->category_ids) && $question_value->category_ids != "" ? sanitize_text_field($question_value->category_ids) : "";
            $new_question_title      = isset($question_value->question) && $question_value->question != "" ? sanitize_text_field($question_value->question) : "";
            $new_question_type       = isset($question_value->type) && $question_value->type != "" ? sanitize_text_field($question_value->type) : "";
            $new_question_status     = isset($question_value->status) && $question_value->status != "" ? sanitize_text_field($question_value->status) : "";
            $new_question_create_date      = current_time( 'mysql' );
            $new_question_modified_date    = current_time( 'mysql' );
            $new_question_user_variant     = isset($question_value->user_variant) && $question_value->user_variant != "" ? sanitize_text_field($question_value->user_variant) : "";
            $new_question_user_explanation = isset($question_value->user_explanation) && $question_value->user_explanation != "" ? sanitize_text_field($question_value->user_explanation) : "";
            $new_question_image        = isset($question_value->image) && $question_value->image != "" ? sanitize_text_field($question_value->image) : "";
            $new_question_ordering     = isset($question_value->ordering) && $question_value->ordering != "" ? intval(sanitize_text_field($question_value->ordering)) : "";
            $new_question_options      = isset($question_value->options) && $question_value->options != "" ? sanitize_text_field($question_value->options) : "";
            
            $question_result = $wpdb->insert(
                $questions_table,
                array(
                    'author_id'        => $new_question_author_id,
                    'section_id'       => $new_question_section_id,
                    'category_ids'     => $new_question_cat_ids,
                    'question'         => $new_question_title,
                    'type'             => $new_question_type,
                    'status'           => $new_question_status,
                    'date_created'     => $new_question_create_date,
                    'date_modified'    => $new_question_modified_date,
                    'user_variant'     => $new_question_user_variant,
                    'user_explanation' => $new_question_user_explanation,
                    'image'            => $new_question_image,
                    'ordering'         => $new_question_ordering,
                    'options'          => $new_question_options,
                ),
                array(
                    '%d', // author id
                    '%d', // section id
                    '%s', // category ids
                    '%s', // title
                    '%s', // type
                    '%s', // status
                    '%s', // date created
                    '%s', // date modified
                    '%s', // user variant
                    '%s', // user xplanation
                    '%s', // image
                    '%d', // ordering
                    '%s', // options
                )
            );
            $question_new_ids[] = $wpdb->insert_id;
            $question_new_ids_for_answers[$question_id] = $wpdb->insert_id;
            if(!empty($question_answers)){
                foreach($question_answers as $answer_key => $answer_value){
                    $new_answer_question_id = isset($question_new_ids_for_answers[$answer_value->question_id]) && $question_new_ids_for_answers[$answer_value->question_id] != "" ? intval(sanitize_text_field($question_new_ids_for_answers[$answer_value->question_id])) : "";
                    $new_answer_title = isset($answer_value->answer) && $answer_value->answer != "" ? sanitize_text_field($answer_value->answer) : "";
                    $new_answer_image = isset($answer_value->image) && $answer_value->image != "" ? sanitize_text_field($answer_value->image) : "";
                    $new_answer_ordering = isset($answer_value->ordering) && $answer_value->ordering != "" ? sanitize_text_field($answer_value->ordering) : "";
                    $new_answer_placeholder = isset($answer_value->placeholder) && $answer_value->placeholder != "" ? sanitize_text_field($answer_value->placeholder) : "";
                    $answer_result = $wpdb->insert(
                        $answers_table,
                        array(
                            'question_id' => $new_answer_question_id,
                            'answer'      => $new_answer_title,
                            'image'       => $new_answer_image,
                            'ordering'    => $new_answer_ordering,
                            'placeholder' => $new_answer_placeholder,
                        ),
                        array(
                            '%d', // question id
                            '%s', // title
                            '%s', // image
                            '%s', // ordering
                            '%d', // placeholder
                        )
                    );
                }
            }
        }
        
        $author_id = get_current_user_id();
        
        $max_id = $this->get_max_id();
        $ordering = ( $max_id != NULL ) ? ( $max_id + 1 ) : 1;
        
        $options = json_decode($object['options'], true);
        
        $result = $wpdb->insert(
            $survey_table,
            array(
                'author_id'         => $author_id,
                'title'             => "Copy - " . $object['title'],
                'description'       => $object['description'],
                'category_ids'      => $object['category_ids'],
                'question_ids'      => implode("," , $question_new_ids),
                'section_ids'       => implode("," , $section_new_ids),
                'sections_count'    => $object['sections_count'],
                'questions_count'   => $object['questions_count'],
                'date_created'      => current_time( 'mysql' ),
                'date_modified'     => current_time( 'mysql' ),
                'image'             => $object['image'],
                'status'            => $object['status'],
                'trash_status'      => $object['trash_status'],
                'ordering'          => $ordering,
                'post_id'           => 0,
                'options'           => json_encode( $options, JSON_UNESCAPED_SLASHES ),
            ),
            array(
                '%d', // author_id
                '%s', // title
                '%s', // description
                '%s', // category_ids
                '%s', // question_ids
                '%s', // section ids
                '%d', // sections count
                '%d', // questions count
                '%s', // date_created
                '%s', // date_modified
                '%s', // image
                '%s', // status
                '%s', // trash_status
                '%d', // ordering
                '%d', // post_id
                '%s', // options
            )
        );
    }



    /**
     * Returns the count of records in the database.
     *
     * @return null|string
     */
    public static function record_count() {
        global $wpdb;
        $filter = array();
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";
        
        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = intval( sanitize_text_field( $_GET['filterby'] ) );
            $filter[] = ' FIND_IN_SET('.$cat_id.',category_ids) ';
        }

        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $filter[] = ' author_id ='.$user_id;
        }

        if( isset( $_REQUEST['fstatus'] ) ){
            $fstatus = sanitize_text_field( $_REQUEST['fstatus'] );
            if($fstatus !== null){
                $filter[] = " status = '". esc_sql( $fstatus ) ."' ";
            }
        }else{
            $filter[] = " status != 'trashed' ";
        }
        
        if(count($filter) !== 0){
            $sql .= " WHERE ".implode(" AND ", $filter);
        }

        return $wpdb->get_var( $sql );
    }
    
    public static function all_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE status != 'trashed'";

        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = intval( sanitize_text_field( $_GET['filterby'] ) );
            $sql .= ' AND '.$cat_id.' IN (category_ids) ';
        }

        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $sql .= ' AND author_id ='.$user_id;
        }

        return $wpdb->get_var( $sql );
    }

    public static function published_questions_record_count() {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions WHERE status = 'published'";

        return $wpdb->get_var( $sql );
    }

    public static function get_statused_record_count( $status ) {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE status='" . esc_sql( $status ) . "'";

        if( isset( $_GET['filterby'] ) && intval($_GET['filterby']) > 0){
            $cat_id = intval( sanitize_text_field( $_GET['filterby'] ) );
            $sql .= ' AND '.$cat_id.' IN (category_ids) ';
        }

        if(! empty( $_REQUEST['filterbyuser'] ) && intval( $_REQUEST['filterbyuser'] ) > 0){
            $user_id = intval( sanitize_text_field( $_REQUEST['filterbyuser'] ) );
            $sql .= ' AND author_id ='.$user_id;
        }

        return $wpdb->get_var( $sql );
    }

    public static function get_passed_users_count( $id ) {
        global $wpdb;
        $id = absint($id);
        $sql = "SELECT COUNT(*) FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions WHERE survey_id=".$id;

        return $wpdb->get_var( $sql );
    }


    /** Text displayed when no customer data is available */
    public function no_items() {
        Survey_Maker_Data::survey_no_items_list_tables();
    }


    /**
     * Render a column when no column specific method exist.
     *
     * @param array $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'title':
            case 'category_ids':
            case 'shortcode':
            case 'code_include':
            case 'items_count':
            case 'sections_count':
            case 'author_id':
            case 'submissions_count':
            case 'status':
            case 'id':
                return $item[ $column_name ];
                break;
            default:
                return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
     * Render the bulk edit checkbox
     *
     * @param array $item
     *
     * @return string
     */
    function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
        );
    }


    /**
     * Method for name column
     *
     * @param array $item an array of DB data
     *
     * @return string
     */
    function column_title( $item ) {
        if($item['status'] == 'trashed'){
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-delete-survey' );
        }else{
            $delete_nonce = wp_create_nonce( $this->plugin_name . '-trash-survey' );
        }

        $survey_title = stripcslashes( $item['title'] );

        $q = esc_attr( $survey_title );

        $restitle = Survey_Maker_Admin::ays_restriction_string( "word", $survey_title, $this->title_length );
        
        $fstatus = '';
        if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
            $fstatus = '&fstatus=' . sanitize_text_field( $_GET['fstatus'] );
        }

        $title = sprintf( '<a href="?page=%s&action=%s&id=%d" title="%s">%s</a>', sanitize_text_field( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $q, stripcslashes($item['title']));

        $actions = array();
        if($item['status'] == 'trashed'){
            $title = sprintf( '<a><strong>%s</strong></a>', $restitle );
            $actions['restore'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Restore', "survey-maker") .'</a>', sanitize_text_field( $_REQUEST['page'] ), 'restore', absint( $item['id'] ), $delete_nonce );
            $actions['delete'] = sprintf( '<a class="ays_confirm_del" data-message="%s" href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Delete Permanently', "survey-maker") .'</a>', $restitle, sanitize_text_field( $_REQUEST['page'] ), 'delete', absint( $item['id'] ), $delete_nonce );
        }else{
            $draft_text = '';
            if( $item['status'] == 'draft' && !( isset( $_GET['fstatus'] ) && $_GET['fstatus'] == 'draft' )){
                $draft_text = ' — ' . '<span class="post-state">' . __( "Draft", "survey-maker" ) . '</span>';
            }
            $title = sprintf( '<strong><a href="?page=%s&action=%s&id=%d" title="%s">%s</a>%s</strong>', sanitize_text_field( $_REQUEST['page'] ), 'edit', absint( $item['id'] ), $q, $restitle, $draft_text );

            $actions['edit'] = sprintf( '<a href="?page=%s&action=%s&id=%d">'. __('Edit', "survey-maker") .'</a>', sanitize_text_field( $_REQUEST['page'] ), 'edit', absint( $item['id'] ) );

            $actions['submissions'] = sprintf( '<a href="?page=%s&survey=%d">'. __('View submissions', "survey-maker") .'</a>', sanitize_text_field( $_REQUEST['page'] ) . '-each-submission', absint( $item['id'] ) );
            // $actions['duplicate'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Duplicate', "survey-maker") .'</a>', esc_attr( $_REQUEST['page'] ), 'duplicate', absint( $item['id'] ), $delete_nonce );
            $actions['duplicate'] = sprintf( '<a href="?page=%s&action=%s&id=%d&_wpnonce=%s'.$fstatus.'">'. __('Duplicate', "survey-maker") .'</a>', sanitize_text_field( $_REQUEST['page'] ), 'duplicate', absint( $item['id'] ), $delete_nonce );
            $actions['trash'] = sprintf( '<a href="?page=%s&action=%s&id=%s&_wpnonce=%s'.$fstatus.'">'. __('Move to trash', "survey-maker") .'</a>', sanitize_text_field( $_REQUEST['page'] ), 'trash', absint( $item['id'] ), $delete_nonce );
        }

        return $title . $this->row_actions( $actions );
    }

    function column_category_ids( $item ) {
        global $wpdb;

        // Survey categories IDs
        $category_ids = isset( $item['category_ids'] ) && $item['category_ids'] != '' ? $item['category_ids'] : '';
        // $category_ids = $category_ids == '' ? array() : explode( ',', $category_ids );
        
        if( ! empty( $category_ids ) ){
            $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "survey_categories WHERE id IN (" . esc_sql( $category_ids ) . ")";

            $results = $wpdb->get_results($sql, 'ARRAY_A');
            $category_location = array();
            foreach ($results as $key => $value) {
                $category_location[] = sprintf( '<a href="?page=%s&action=%s&id=%d" target="_blank">%s</a>', 'survey-maker-survey-categories', 'edit', $value['id'], $value['title']);
            }
            
            $titles = implode( ', ', $category_location );

            return $titles;
        }

        return '-';
    }

    function column_code_include( $item ) {
        $shortcode = htmlentities('[\'[ays_survey id="'.$item["id"].'"]\']');
        return '<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="<?php echo do_shortcode('.$shortcode.'); ?>" style="max-width:100%;" />';
    }

    function column_shortcode( $item ) {
        $shortcode = htmlentities('[ays_survey id="'.$item["id"].'"]');
        return '<input type="text" onClick="this.setSelectionRange(0, this.value.length)" readonly value="'.$shortcode.'" />';
    }

    function column_status( $item ) {
        global $wpdb;
        $status = ucfirst( $item['status'] );
        $date = date( 'Y/m/d', strtotime( $item['date_modified'] ) );
        $title_date = date( 'l jS \of F Y h:i:s A', strtotime( $item['date_modified'] ) );
        $html = "<p style='font-size:14px;margin:0;'>" . $status . "</p>";
        $html .= "<p style=';font-size:14px;margin:0;text-decoration: dotted underline;' title='" . $title_date . "'>" . $date . "</p>";
        return $html;
    }

    function column_author_id( $item ) {
        $user = get_user_by( 'id', $item['author_id'] );
        $author_name = '';
        if($user->data->display_name == ''){
            if($user->data->user_nicename == ''){
                $author_name = $user->data->user_login;
            }else{
                $author_name = $user->data->user_nicename;
            }
        }else{
            $author_name = $user->data->display_name;
        }
        return $author_name;
    }

    function column_submissions_count( $item ) {
        $id = $item['id'];
        $passed_count = $this->get_passed_users_count( $id );
        $passed_count = sprintf( '<a href="?page=%s&survey=%d">'. $passed_count .'</a>', sanitize_text_field( $_REQUEST['page'] ) . '-each-submission', absint( $item['id'] ) );
        $text = "<p style='font-size:14px;'>".$passed_count."</p>";
        return $text;
    }

    function column_items_count( $item ) {
        global $wpdb;
        if(empty($item['questions_count'])){
            $count = 0;
        }else{
            $count = intval($item['questions_count']);
        }
        return "<p style='font-size:14px;'>" . $count . "</p>";
    }

    function column_sections_count( $item ) {
        global $wpdb;
        if(empty($item['sections_count'])){
            $count = 0;
        }else{
            $count = intval($item['sections_count']);
        }
        return "<p style='font-size:14px;'>" . $count . "</p>";
    }

    /**
     *  Associative array of columns
     *
     * @return array
     */
    function get_columns() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Title', "survey-maker" ),
        );

        if( is_super_admin() ){
            $columns['author_id'] = __( 'Author', "survey-maker" );
        }

        $columns['category_ids'] = __( 'Categories', "survey-maker" );
        $columns['shortcode'] = __( 'Shortcode', "survey-maker" );
        $columns['code_include'] = __( 'Code include', "survey-maker" );
        $columns['sections_count'] = __( 'Sections', "survey-maker" );
        $columns['items_count'] = __( 'Questions', "survey-maker" );
        $columns['submissions_count'] = __( 'Submissions', "survey-maker" );
        $columns['status'] = __( 'Status', "survey-maker" );
        $columns['id'] = __( 'ID', "survey-maker" );

        if( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ){
            return array();
        }
        
        return $columns;
    }


    /**
     * Columns to make sortable.
     *
     * @return array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'title'         => array( 'title', true ),
            'id'            => array( 'id', true ),
        );

        return $sortable_columns;
    }

    /**
     * Columns to make hidden.
     *
     * @return array
     */
    public function get_hidden_columns() {
        $sortable_columns = array(
            'category_ids',
            'code_include',
        );

        return $sortable_columns;
    }

    /**
     * Returns an associative array containing the bulk action
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            // 'bulk-duplicate' => __( 'Duplicate', "survey-maker" ),
            'bulk-trash' => __( 'Move to trash', "survey-maker" ),
        );

        if(isset($_GET['fstatus']) && sanitize_text_field( $_GET['fstatus'] ) == 'trashed'){
            $actions = array(
                'bulk-restore' => __( 'Restore', "survey-maker" ),
                'bulk-delete' => __( 'Delete Permanently', "survey-maker" ),
            );
        }

        return $actions;
    }



    /**
     * Handles data query and filter, sorting, and pagination.
     */
    public function prepare_items() {
        global $wpdb;
        $this->_column_headers = $this->get_column_info();

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page     = $this->get_items_per_page( 'surveys_per_page', 20 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page'    => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items / $per_page )
        ) );

        $search = ( isset( $_REQUEST['s'] ) ) ? sanitize_text_field( $_REQUEST['s'] ) : false;

        $do_search = ( $search ) ? sprintf( " title LIKE '%%%s%%' ", esc_sql( $wpdb->esc_like( $search ) ) ) : '';

        $this->items = self::get_items( $per_page, $current_page, $do_search );
    }

    public function process_bulk_action() {
       
        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-survey' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::delete_items( absint( sanitize_text_field($_GET['id']) ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'deleted'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'trash' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-trash-survey' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::trash_items( absint( sanitize_text_field($_GET['id']) ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'trashed'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'restore' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-delete-survey' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                self::restore_items( absint( sanitize_text_field($_GET['id']) ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'restored'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        //Detect when a bulk action is being triggered...
        if ( 'duplicate' === $this->current_action() ) {

            // In our file that handles the request, verify the nonce.
            $nonce = sanitize_text_field( $_REQUEST['_wpnonce'] );

            if ( ! wp_verify_nonce( $nonce, $this->plugin_name . '-trash-survey' ) ) {
                die( 'Go get a life script kiddies' );
            }
            else {
                $this->duplicate_items( absint( sanitize_text_field($_GET['id']) ) );

                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                // add_query_arg() return the current url

                $add_query_args = array(
                    "status" => 'duplicated'
                );
                if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                    $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
                }
                $url = remove_query_arg( array('action', 'id', '_wpnonce') );
                $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
                wp_redirect( $url );
            }

        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) ) {

            $delete_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-deleted'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-trash' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-trash' ) ) {

            $trash_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $trash_ids as $id ) {
                self::trash_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-trashed'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-restore' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-restore' ) ) {

            $restore_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $restore_ids as $id ) {
                self::restore_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-restored'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-duplicate' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-duplicate' ) ) {

            $restore_ids = ( isset( $_POST['bulk-delete'] ) && ! empty( $_POST['bulk-delete'] ) ) ? esc_sql( $_POST['bulk-delete'] ) : array();

            // loop over the array of record IDs and delete them
            foreach ( $restore_ids as $id ) {
                $this->duplicate_items( $id );
            }

            // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            $add_query_args = array(
                "status" => 'all-duplicated'
            );
            if( isset( $_GET['fstatus'] ) && $_GET['fstatus'] != '' ){
                $add_query_args['fstatus'] = sanitize_text_field( $_GET['fstatus'] );
            }
            $url = remove_query_arg( array('action', 'id', '_wpnonce') );
            $url = esc_url_raw( add_query_arg( $add_query_args, $url ) );
            wp_redirect( $url );
        }
    }

    public function survey_notices(){
        $status = (isset($_REQUEST['status'])) ? sanitize_text_field( $_REQUEST['status'] ) : '';

        if ( empty( $status ) )
            return;

        $error = false;
        switch ( $status ) {
            case 'created':
                $updated_message = __( 'Survey created.', "survey-maker" );
                break;
            case 'updated':
                $updated_message = __( 'Survey saved.', "survey-maker" );
                break;
            case 'duplicated':
                $updated_message = __( 'Survey duplicated.', "survey-maker" );
                break;
            case 'deleted':
                $updated_message = __( 'Survey deleted.', "survey-maker" );
                break;
            case 'trashed':
                $updated_message = __( 'Survey moved to trash.', "survey-maker" );
                break;
            case 'restored':
                $updated_message = __( 'Survey restored.', "survey-maker" );
                break;
            case 'all-duplicated':
                $updated_message = __( 'Surveys are duplicated.', "survey-maker" );
                break;
            case 'all-deleted':
                $updated_message = __( 'Surveys are deleted.', "survey-maker" );
                break;
            case 'all-trashed':
                $updated_message = __( 'Surveys are moved to trash.', "survey-maker" );
                break;
            case 'all-restored':
                $updated_message = __( 'Surveys are restored.', "survey-maker" );
                break;
            case 'empty-title':
                $error = true;
                $updated_message = __( 'Error: Survey title can not be empty.', "survey-maker" );
                break;
            default:
                break;
        }

        if ( empty( $updated_message ) )
            return;

        $notice_class = 'success';
        if( $error ){
            $notice_class = 'error';
        }
        ?>
        <div class="notice notice-<?php echo esc_attr( $notice_class ); ?> is-dismissible">
            <p> <?php echo esc_html($updated_message); ?> </p>
        </div>
        <?php
    }

    public function survey_recursive_sanitize_data(&$item, $key) {
        $item = sanitize_text_field($item);
    }
}
