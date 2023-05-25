<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/public/partials
 */

class Survey_Maker_Submissions_Summary
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    private $html_class_prefix = 'ays-survey-';
    private $html_name_prefix = 'ays-survey-';
    private $name_prefix = 'survey_';
    private $unique_id;
    private $unique_id_in_class;
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;


        add_shortcode('ays_survey_submissions_summary', array($this, 'ays_generate_submissions_summary_method'));
    }

    public function ays_survey_get_data_by_survey_id( $attr ){
        global $wpdb;

        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys" );

        $survey_id = (isset($attr['id']) && sanitize_text_field( $attr['id'] ) != '') ? absint( sanitize_text_field( $attr['id'] ) ) : null;

        if ( is_null( $survey_id ) ) {
            return null;
        }

        $results = array();

        $sql = "SELECT * FROM " . $surveys_table . " WHERE id =" . absint( $survey_id );
        $survey_name = $wpdb->get_row( $sql, 'ARRAY_A' );

        if ( empty( $survey_name ) || is_null( $survey_name ) ) {
            return null;
        }

        // // For charts in summary
        $survey_question_results = Survey_Maker_Data::ays_survey_question_results_for_summary( $survey_id );
        $question_results = $survey_question_results['questions'];

        $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id_for_summary( $survey_id );

        $ays_survey_individual_questions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission_for_summary( $last_submission, $survey_name );

        if( empty( $ays_survey_individual_questions['sections'] ) ){
            $question_results = array();
        }

        $submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids_for_summary( $survey_id );
        wp_localize_script( $this->plugin_name . 'public-charts', 'aysSurveyPublicChartLangObj', array(
            'answers'        => __( 'Answers' , "survey-maker" ),
            'percent'        => __( 'Percent' , "survey-maker" ),
            'count'          => __( 'Count' , "survey-maker" ),
        ) );

        $options = array(
            'perAnswerData' => $question_results,
            'surveyColor'    => $this->options[ $this->name_prefix . 'color' ],
            'sectionDescHtml'    => $this->options[ $this->name_prefix . 'allow_html_in_section_description' ],
        );
        
            
        $script = '<script type="text/javascript">';
        $script .= "
                if(typeof aysSurveyPublicChartData === 'undefined'){
                    var aysSurveyPublicChartData = [];
                }
                aysSurveyPublicChartData['" . $this->unique_id . "']  = '" . base64_encode( json_encode( $options ) ) . "';";
        $script .= '</script>';

        $results = array(
            'ays_survey_individual_questions' => $ays_survey_individual_questions,
            'submission_count_and_ids' => $submission_count_and_ids,
            'question_results' => $question_results,
            'script' => $script,
            'options' => $options,
        );

        return $results;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name . "-public-submissions", SURVEY_MAKER_PUBLIC_URL . '/css/partials/survey-maker-public-submissions.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(){

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        wp_enqueue_script( $this->plugin_name . 'public-charts-google', SURVEY_MAKER_ADMIN_URL . '/js/google-chart.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . 'public-charts', SURVEY_MAKER_PUBLIC_URL . '/js/partials/survey-maker-public-submissions-charts.js', array('jquery'), $this->version, true);
    }

    public function ays_submissions_summary_html( $attr ){

        $content = array();

        $results = $this->ays_survey_get_data_by_survey_id( $attr );

        if( $results === null ){
            $content = "<p style='text-align: center;font-style:italic;'>" . __( "There are no questions atteched yet.", "survey-maker" ) . "</p>";
            return $content;
        }

        $ays_survey_individual_questions = $results['ays_survey_individual_questions'];
        $submission_count_and_ids = $results['submission_count_and_ids'];
        $question_results = $results['question_results'];
        $script = $results['script'];
        
        // Allow HTML in description
        $survey_allow_html_in_section_description = (isset($results['options'][ 'sectionDescHtml' ]) && $results['options'][ 'sectionDescHtml' ] == 'on') ? true : false;

        $text_types = array(
            'text',
            'short_text',
            'number',
            'name',
            'email',
            'date',
        );

        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-container" id="' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . '" data-id="' . $this->unique_id . '">';

            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container" style="padding: 20px;">';
                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container-header">';
                    $content[] = sprintf( __( 'In total %s submission', "survey-maker" ), intval( $submission_count_and_ids['submission_count'] ) );
                $content[] = '</div>';
            $content[] = '</div>';
            
            if( is_array( $ays_survey_individual_questions['sections'] ) ):
                foreach ($ays_survey_individual_questions['sections'] as $section_key => $section):
                    $content[] = '<div class="' . $this->html_class_prefix . 'submission-section ' . $this->html_class_prefix . 'submission-summary-section">';

                        $content[] = '<div class="ays_survey_name ' . $this->html_class_prefix . 'submission-summary-section-header">';
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-section-header-title">' . $section['title'] . '</div>';
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-section-header-description">' . ($survey_allow_html_in_section_description) ? strip_tags(htmlspecialchars_decode($section['description'] )) : nl2br( $section['description'] ) . '</div>';
                        $content[] = '</div>';

                        foreach ( $section['questions'] as $q_key => $question ):
                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-container">';

                                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header">';
                                    $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content">';
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content-title">' . nl2br( $question_results[ $question['id'] ]['question'] ) . '</div>';
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-header-content-submission">' . $question_results[ $question['id'] ]['sum_of_answers_count'] . ' ' . __( 'submissions', "survey-maker" ) . '</div>';
                                    $content[] = '</div>';
                                $content[] = '</div>';

                                $content[] = '<div class="' . $this->html_class_prefix . 'submission-summary-question-content">';
                                    if( in_array( $question_results[ $question['id'] ]['question_type'], $text_types ) && $question_results[ $question['id'] ]['question_type'] != 'date' ):
                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answers-div">';
                                            if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ):
                                                if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ):
                                                    $filtered_text_answers = array_values(array_unique($question_results[ $question['id'] ]['answers'][ $question['id'] ]));
                                                    foreach( $filtered_text_answers  as $aid => $answer ):
                                                        $text_answer_count = isset($question_results[ $question['id'] ]['sum_of_same_answers'][$answer]) && $question_results[ $question['id'] ]['sum_of_same_answers'][$answer] != "" ? $question_results[ $question['id'] ]['sum_of_same_answers'][$answer] : "";
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answer">
                                                                        <div>'. nl2br( htmlentities($answer) ) .'</div>
                                                                        <div>'. nl2br( $text_answer_count ) .'</div>
                                                                    </div>';

                                                    endforeach;
                                                endif;
                                            endif;
                                        $content[] = '</div>';
                                    elseif( $question_results[ $question['id'] ]['question_type'] == 'date' ):
                                        $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrapper">';
                                            $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-wrap">';
                                                if( isset( $question_results[ $question['id'] ]['answers'] ) && !empty( $question_results[ $question['id'] ]['answers'] ) ){
                                                    if( isset( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) && !empty( $question_results[ $question['id'] ]['answers'][ $question['id'] ] ) ){
                                                        $dates_array = array();
                                                        foreach( $question_results[ $question['id'] ]['answers'][ $question['id'] ] as $aid => $answer ){
                                                            $year_month = explode( '-', $answer );
                                                            $day = $year_month[2];
                                                            if( isset( $dates_array[ $year_month[0] ] ) ){
                                                                if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ] ) ){
                                                                    if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] ) ){
                                                                        $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] += 1;
                                                                    }else{
                                                                        $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                                    }
                                                                }else{
                                                                    $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                                }
                                                            }else{
                                                                $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                                            }
                                                        }

                                                        ksort( $dates_array, SORT_NATURAL );
                                                        foreach( $dates_array as $year => $months ){
                                                            ksort( $months, SORT_NATURAL );
                                                            foreach( $months as $month => $days ){
                                                                ksort( $days, SORT_NATURAL );
                                                            }
                                                        }

                                                        foreach( $dates_array as $year => $months ){
                                                            foreach( $months as $month => $days ){
                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-row">';
                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-year-month">' . date_i18n( 'F Y', strtotime( $year ."-". $month ) ) . '</div>';
                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days">';
                                                                        $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row">';
                                                                            foreach( $days as $day => $count ){
                                                                                if( $count == 1 ){
                                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day">';
                                                                                    $content[] = '<span>' . esc_html( $day ). '</span>';
                                                                                $content[] = '</div>';
                                                                                }else{
                                                                                $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day ' . $this->html_class_prefix . 'question-date-summary-days-row-day-with-count">';
                                                                                    $content[] = '<span>' . esc_html( $day ) . '</span>';
                                                                                    $content[] = '<div class="' . $this->html_class_prefix . 'question-date-summary-days-row-day-count">' . esc_html( $count ) . '</div>';
                                                                                $content[] = '</div>';
                                                                                }
                                                                            }
                                                                        $content[] = '</div>';
                                                                    $content[] = '</div>';
                                                                $content[] = '</div>';
                                                            }
                                                        }
                                                    }
                                                }
                                            $content[] = '</div>';
                                        $content[] = '</div>';
                                    else:
                                        $content[] = '<div id="survey_answer_chart_' . $question_results[ $question['id'] ]['question_id'] . '" style="width: 100%;" class="chart_div"></div>';
                                        if( !empty( $question_results[ $question['id'] ]['otherAnswers'] ) ):
                                            $content[] = '<div class="' . $this->html_class_prefix . 'other-answer-row">' . __( '"Other" answers', "survey-maker" ) . '</div>';
                                            $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answers-div">';
                                                if( isset( $question_results[ $question['id'] ]['otherAnswers'] ) && !empty( $question_results[ $question['id'] ]['otherAnswers'] ) ):
                                                    $filtered_other_answers = array_values(array_unique($question_results[ $question['id'] ]['otherAnswers']));
                                                    foreach( $filtered_other_answers as $aid => $answer ):
                                                        $other_answer_count = isset($question_results[ $question['id'] ]['same_other_count'][$answer]) && $question_results[ $question['id'] ]['same_other_count'][$answer] != "" ? $question_results[ $question['id'] ]['same_other_count'][$answer] : "";
                                                        $content[] = '<div class="' . $this->html_class_prefix . 'submission-text-answer">
                                                                        <div>' . stripslashes(htmlentities($answer)) . '</div>
                                                                        <div>' . stripslashes($other_answer_count) . '</div>
                                                                      </div>';
                                                    endforeach;
                                                endif;
                                            $content[] = '</div>';
                                        endif;
                                    endif;
                                $content[] = '</div>';

                            $content[] = '</div>';
                        endforeach;

                    $content[] = '</div>';

                endforeach;
            endif;

        $content[] = $this->get_styles();
        $content[] = $script;
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }

    public function get_styles(){
        
        $content = array();
        $content[] = '<style type="text/css">';

        $mobile_max_width = $this->options[ $this->name_prefix . 'mobile_max_width' ];
        
        if( absint( $mobile_max_width ) > 0 ){
            $mobile_max_width .= '%';
        }else{
            $mobile_max_width = '90%';
        }

        $content[] = '
            #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-summary-section-header {
                border-top-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }
            
            #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' .' . $this->html_class_prefix . 'submission-summary-question-container {
                border-left-color: ' . $this->options[ $this->name_prefix . 'color' ] . ';
            }

            @media screen and (max-width: 640px){
                #' . $this->html_class_prefix . 'submission-summary-container-' . $this->unique_id_in_class . ' {
                    max-width: '. $mobile_max_width .';
                }
            }
            
            ';
        
        $content[] = '</style>';

        $content = implode( '', $content );

        return $content;
    }

    public function ays_generate_submissions_summary_method( $attr ) {

        $id = (isset($attr['id']) && $attr['id'] != '') ? absint(intval($attr['id'])) : null;

        if (is_null($id)) {
            $content = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', "survey-maker") . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), '', $content);
        }

        $this->enqueue_styles();
        $this->enqueue_scripts();

        $unique_id = uniqid();
        $this->unique_id = $unique_id;
        $this->unique_id_in_class = $id . "-" . $unique_id;

        $survey = Survey_Maker_Data::get_survey_by_id( $id );

        $this->options = Survey_Maker_Data::get_survey_validated_data_from_array( $survey, $attr );

        $content = $this->ays_submissions_summary_html( $attr );

        return str_replace(array("\r\n", "\n", "\r"), "\n", $content);
    }
}
