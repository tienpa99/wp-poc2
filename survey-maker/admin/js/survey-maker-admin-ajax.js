(function( $ ) {
    'use strict';
    $.fn.serializeFormJSON = function () {
        let o = {},
            a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
    
    $.fn.aysModal = function(action){
        let $this = $(this);
        switch(action){
            case 'hide':
                $(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                setTimeout(function(){
                    $(document.body).removeClass('modal-open');
                    $(document).find('.ays-modal-backdrop').remove();
                    $this.hide();
                }, 250);
            break;
            case 'show': 
            default:
                $this.show();
                $(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                $(document).find('.modal-backdrop').remove();
                $(document.body).append('<div class="ays-modal-backdrop"></div>');
                $(document.body).addClass('modal-open');
            break;
        }
    };

    $(document).on('click', '.ays-survey-apply-question-changes', function(e){
        var sectionCont = $(document).find('.ays-survey-sections-conteiner');
        var editorPopup = $(document).find('#ays-edit-question-content');
        var questionId = $(this).attr('data-question-id');
        var questionName = $(this).attr('data-question-name');
        var sectionId = $(this).attr('data-section-id');
        var sectionName = $(this).attr('data-section-name');
        var question = sectionCont.find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionName+'"] .ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionName+'"]');

        var editor = window.tinyMCE.get('ays_survey_question_editor');
        var questionContent = '';

        question.find('.ays-survey-open-question-editor-flag').val('on');

        editorPopup.find('.ays-survey-preloader').css('display', 'flex');

        if ( editorPopup.find("#wp-ays_survey_question_editor-wrap").hasClass("tmce-active")){
            questionContent = editor.getContent();
        }else{
            questionContent = editorPopup.find('#ays_survey_question_editor').val();
        }
        var action = 'ays_live_preivew_content';
        var data = {};
        data.action = action;
        data.content = questionContent;
        $.ajax({
            url: ajaxurl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.status) {
                    editorPopup.find('.ays-survey-preloader').css('display', 'none');
                    question.find('textarea.ays-survey-question-input-textarea').val( questionContent );
                    question.find('.ays-survey-question-preview-box').html( response.content );

                    question.find('.ays-survey-question-input-box').addClass('display_none');
                    question.find('.ays-survey-question-preview-box').removeClass('display_none');

                    editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', '' );
                    editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', '' );
                    editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', '' );
                    editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', '' );
                    var SurveyTinyMCE = window.tinyMCE.get('ays_survey_question_editor');
                    if(SurveyTinyMCE != null){
                        SurveyTinyMCE.setContent( '' );
                    }
                    else{
                        $(document).find('#ays_survey_question_editor').val(" ");
                    }
                    
                    editorPopup.aysModal('hide');
                }
            }
        });
    });
})( jQuery );
