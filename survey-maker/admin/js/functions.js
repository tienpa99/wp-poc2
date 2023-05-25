// (function($){
    
    var $html_name_prefix = 'ays_';
    String.prototype.hexToRgbA = function(a) {
        
        if (typeof a === 'undefined'){
            a = 1;
        }
        var ays_rgb;
        var result1 = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})jQuery/i.exec(this);
        var result2 = /^#?([a-f\d]{1})([a-f\d]{1})([a-f\d]{1})jQuery/i.exec(this);
        if(result1){
            ays_rgb = {
                r: parseInt(result1[1], 16),
                g: parseInt(result1[2], 16),
                b: parseInt(result1[3], 16)
            };
            return 'rgba('+ays_rgb.r+','+ays_rgb.g+','+ays_rgb.b+','+a+')';
        }else if(result2){
            ays_rgb = {
                r: parseInt(result2[1]+''+result2[1], 16),
                g: parseInt(result2[2]+''+result2[2], 16),
                b: parseInt(result2[3]+''+result2[3], 16)
            };
            return 'rgba('+ays_rgb.r+','+ays_rgb.g+','+ays_rgb.b+','+a+')';
        }else{
            return null;
        }
    }
    
    jQuery.fn.aysModal = function(action){
        var jQuerythis = jQuery(this);
        switch(action){
            case 'hide':
                jQuery(this).find('.ays-modal-content').css('animation-name', 'zoomOut');
                setTimeout(function(){
                    jQuery(document.body).removeClass('modal-open');
                    jQuery(document).find('.ays-modal-backdrop').remove();
                    jQuerythis.hide();
                }, 250);
            break;
            case 'show': 
            default:
                jQuerythis.show();
                jQuery(this).find('.ays-modal-content').css('animation-name', 'zoomIn');
                jQuery(document).find('.modal-backdrop').remove();
                jQuery(document.body).append('<div class="ays-modal-backdrop"></div>');
                jQuery(document.body).addClass('modal-open');
            break;
        }
    }

    function checkTrue(flag) {
        return flag === true;
    }

    function openMediaUploaderForImage(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            var answerImgCont = element.parents('.ays-survey-image-container').find('.ays-survey-image-body');
            var img = answerImgCont.find('img.ays-survey-img');
            var hiddenInp = answerImgCont.find('input.ays-survey-img-src');

            element.text( SurveyMakerAdmin.editImage );
            img.attr('src', attachment.url);
            hiddenInp.val(attachment.url);
            answerImgCont.show();
            img.removeClass('fade');
            jQuery(document).find(".ays-survey-logo-url-box").removeClass("display_none_not_important");
            var checkImgUrl = jQuery(document).find("#ays_survey_logo_enable_image_url").prop("checked");
            element.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open').show();
            if(checkImgUrl){
                element.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open-close').show();
            }
        }).open();
        return false;
    }

    function openMediaUploaderForCoverImage(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            var answerImgCont = element.parents('.ays-survey-image-container').find('.ays-survey-image-body');
            var thisParent = element.parents(".ays_survey_cover_image_main");
            thisParent.find('.ays-survey-cover-image-options').css("display" , "flex");
            thisParent.find('.ays-survey-cover-image-options-hr').fadeIn(500);
            var img = answerImgCont.find('img.ays-survey-cover-img');
            var hiddenInp = answerImgCont.find('input.ays-survey-cover-img-src');

            element.text( SurveyMakerAdmin.editImage );
            img.attr('src', attachment.url);
            hiddenInp.val(attachment.url);
            answerImgCont.show();
            img.removeClass('fade');

        }).open();
        return false;
    }


    function openMediaUploaderForGifLoader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            var wrap = element.parents('.ays-survey-image-wrap');
            wrap.find('.ays-survey-image-container img.ays_survey_img_loader_custom_gif').attr('src', attachment.url);
            wrap.find('input.ays-survey-image-path').val(attachment.url);
            wrap.find('.ays-survey-image-container').fadeIn();
            wrap.find('button.add_survey_loader_custom_gif').hide();
        }).open();
        return false;
    }

    // function openMediaUploader(e, element) {
    //     e.preventDefault();
    //     var aysUploader = wp.media({
    //         title: 'Upload',
    //         button: {
    //             text: 'Upload'
    //         },
    //         library: {
    //             type: 'image'
    //         },
    //         multiple: false
    //     }).on('select', function () {
    //         var attachment = aysUploader.state().get('selection').first().toJSON();
    //         element.text('Edit Image');
    //         element.parent().parent().find('.ays-question-image-container').fadeIn();
    //         element.parent().parent().find('img#ays-question-img').attr('src', attachment.url);
    //         element.parent().parent().find('input#ays-question-image').val(attachment.url);
    //     }).open();
    //     return false;
    // }

    
    function openMediaUploader(e, element, type) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            switch(type){
                case 'questionImgButton':
                    var $this = element.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-image-container');
                    var hiddenInp = $this.find('.ays-survey-question-img-src');
                    var img = $this.find('img.ays-survey-question-img');

                    $this.removeAttr('style');
                    img.attr('src', attachment.url);
                    hiddenInp.val(attachment.url);
                break;
                case 'answerImgButton':
                    var answerImgCont = element.parents('.ays-survey-answer-row').find('.ays-survey-answer-image-container');
                    var parent = element.parents('.ays-survey-section-box');

                    var section_id = parent.data('id');
                    var question_id = element.parents('.ays-survey-question-answer-conteiner').data('id');
                    var answer_id = element.parents('.ays-survey-answer-row').data('id');
                    var imgInputName = element.parents('.ays-survey-answer-row').find('input.ays-survey-input').attr('name');
                    var checkInputName = imgInputName.indexOf('ays_section_add');
                    var inputName = false;
                    if (checkInputName >= 0) {
                        inputName = true;
                    }

                    var name = 'section';
                    if (inputName) {
                        name = 'section_add';
                    }
                    // var inputNameAttr = $html_name_prefix+name+'['+section_id+'][question]['+question_id+'][answers]['+answer_id+'][image]';

                    var img = answerImgCont.find('img.ays-survey-answer-img');
                    var hiddenInp = answerImgCont.find('input.ays-survey-answer-img-src');

                    img.attr('src', attachment.url);
                    hiddenInp.val(attachment.url);
                    answerImgCont.show();
                    img.removeClass('Fade');
                break;
            }
            
        }).open();
        return false;
    }


    function openMediaUploaderQuestionBg(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload Question Background',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            element.text('Edit Image');
            element.parent().parent().find('.ays-question-bg-image-container').fadeIn();
            element.parent().parent().find('img#ays-question-bg-img').attr('src', attachment.url);
            element.parent().parent().find('input#ays-question-bg-image').val(attachment.url);
        }).open();
        return false;
    }
    
    function openMusicMediaUploader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload music',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'audio'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            element.next().attr('src', attachment.url);
            element.parent().find('input.ays_quiz_bg_music').val(attachment.url);
        }).open();
        return false;
    }
    
    function openQuizMediaUploader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            if(element.hasClass('add-quiz-bg-image')){
                element.parent().find('img#ays-quiz-bg-img').attr('src', attachment.url);
                element.parent().find('.ays-quiz-bg-image-container').fadeIn();
                element.next().val(attachment.url);
                jQuery(document).find('.ays-quiz-live-container').css({'background-image': 'url("'+attachment.url+'")'});
                element.hide();
            }else if(element.hasClass('ays-edit-quiz-bg-img')){
                element.parent().find('.ays-quiz-bg-image-container').fadeIn();
                element.parent().find('img#ays-quiz-bg-img').attr('src', attachment.url);
                jQuery(document).find('#ays_quiz_bg_image').val(attachment.url);
                jQuery(document).find('.ays-quiz-live-container').css({'background-image': 'url("'+attachment.url+'")'});
            }else{
                element.text('Edit Image');
                element.parent().parent().find('.ays-quiz-image-container').fadeIn();
                element.parent().parent().find('img#ays-quiz-img').attr('src', attachment.url);
                jQuery('input#ays-quiz-image').val(attachment.url);
                var ays_quiz_theme = jQuery(document).find('input[name="ays_quiz_theme"]:checked').val();
                switch (ays_quiz_theme) {
                    case 'elegant_dark':
                    case 'elegant_light':
                    case 'rect_light':
                    case 'rect_dark':
                    case 'classic_dark':
                    case 'classic_light':
                        jQuery(document).find('#ays-quiz-live-image').attr('src', attachment.url);
                        jQuery(document).find('#ays-quiz-live-image').css({'display': 'block'});
                        break;
                    case 'modern_light':
                    case 'modern_dark':
                        jQuery(document).find('.ays-quiz-live-container').css({'background-image':'url('+attachment.url+')'});
                        jQuery(document).find('#ays-quiz-live-image').css({'display': 'none'});
                        jQuery(document).find('#ays-quiz-live-button').css('border','1px solid');
                        break;
                    default:
                        jQuery(document).find('#ays-quiz-live-image').attr('src', attachment.url);
                        jQuery(document).find('#ays-quiz-live-image').css({'display': 'block'});
                        break;

                }
            }
        }).open();

        return false;
    }

    function openAnswerMediaUploader(e, element) {
        e.preventDefault();
        var aysUploader = wp.media({
            title: 'Upload',
            button: {
                text: 'Upload'
            },
            library: {
                type: 'image'
            },
            multiple: false
        }).on('select', function () {
            var attachment = aysUploader.state().get('selection').first().toJSON();
            element.parents().eq(1).find('.add-answer-image').css({'display': 'none'})
            element.parent().parent().find('.ays-answer-image-container').fadeIn();
            element.parent().parent().find('img.ays-answer-img').attr('src', attachment.url);
            element.parents('tr').find('input.ays-answer-image-path').val(attachment.url);
            if(element.hasClass('add-interval-image')){
                element.parent().parent().find('img').attr('src', attachment.url);
                element.parents('tr').find('input.ays-answer-image').val(attachment.url);
            }
        }).open();
        return false;
    }

    function show_hide_rows(page) {

        var rows = jQuery('table.ays-add-questions-table tbody tr');
        rows.each(function (index) {
            jQuery(this).css('display', 'none');
        });
        var counter = page * 5 - 4;
        for (var i = counter; i < (counter + 5); i++) {
            rows.eq(i - 1).css('display', 'table-row');
        }
    }

    function createPagination(pagination, pagesCount, pageShow) {
        (function (baseElement, pages, pageShow) {
            var pageNum = 0, pageOffset = 0;

            function _initNav() {
                var appendAble = '';
                for (var i = 0; i < pagesCount; i++) {
                    var activeClass = (i === 0) ? 'active' : '';
                    appendAble += '<li class="' + activeClass + ' ays-question-page" data-page="' + (i + 1) + '">' + (i + 1) + '</li>';
                }
                jQuery('ul.ays-question-nav-pages').html(appendAble);
                var pagePos = (jQuery('div.ays-question-pagination').width()/2) - (parseInt(jQuery('ul.ays-question-nav-pages>li:first-child').css('width'))/2);
                jQuery('ul.ays-question-nav-pages').css({
                    'margin-left': pagePos,
                });
                //init events
                var toPage;
                baseElement.on('click', '.ays-question-nav-pages li, .ays-question-nav-btn', function (e) {
                    if (jQuery(e.target).is('.ays-question-nav-btn')) {
                        toPage = jQuery(this).hasClass('ays-question-prev') ? pageNum - 1 : pageNum + 1;
                    } else {
                        toPage = jQuery(this).index();
                    }
                    var page = Number(toPage) + 1;
                    show_hide_rows(page);
                    _navPage(toPage);
                });
            }

            function _navPage(toPage) {
                var sel = jQuery('.ays-question-nav-pages li', baseElement), w = sel.first().outerWidth(),
                    diff = toPage - pageNum;

                if (toPage >= 0 && toPage <= pages - 1) {
                    sel.removeClass('active').eq(toPage).addClass('active');
                    pageNum = toPage;
                } else {
                    return false;
                }

                if (toPage <= (pages - (pageShow + (diff > 0 ? 0 : 1))) && toPage >= 0) {
                    pageOffset = pageOffset + -w * diff;
                } else {
                    pageOffset = (toPage > 0) ? -w * (pages - pageShow) : 0;
                }

                sel.parent().css('left', pageOffset + 'px');
            }

            _initNav();

        })(pagination, pagesCount, pageShow);
    }

//    window.onload = function () {
//        if (document.getElementById('import_button')) {
//            document.getElementById('import_button').addEventListener('click', function () {
//                document.getElementById('import_file').click();
//            });
////            document.getElementById('example_button').addEventListener('click', function () {
////                document.getElementById('example_file').click();
////            });
//        }
//    }
    
    function activate_question(element){
        element.find('.ays_question_overlay').addClass('display_none');
        element.find('.ays_fa.ays_fa_times').parent()
            .removeClass('show_remove_answer')
            .addClass('active_remove_answer');
        element.find('.ays_add_answer').parents().eq(1).removeClass('show_add_answer');
        element.addClass('active_question');
        var this_question = element.find('.ays_question').text();
        element.find('.ays_question').remove();
        element.prepend('<input type="text" value="' + this_question + '" class="ays_question_input">');
        var answers_tr = element.find('.ays_answers_table tr');
        for (var i = 0; i < answers_tr.length; i++) {
            var answer_text = (jQuery(answers_tr.eq(i)).find('.ays_answer').text() && jQuery(answers_tr.eq(i)).find('.ays_answer').text() !== "Answer") ? "value='" + jQuery(answers_tr.eq(i)).find('.ays_answer').text() + "'" : "placeholder='Answer text'";
            jQuery(answers_tr.eq(i)).find('.ays_answer_td').empty();
            jQuery(answers_tr.eq(i)).find('.ays_answer_td').append('<input type="text"  ' + answer_text + '  class="ays_answer">');
        }
    }
    
    function deactivate_questions() {
        if (jQuery('.active_question').length !== 0) {
            var question = jQuery('.active_question').eq(0);
            if(!jQuery(question).find('input[name^="ays_answer_radio"]:checked').length){
                jQuery(question).find('input[name^="ays_answer_radio"]').eq(0).attr('checked',true)
            }
            jQuery(question).find('.ays_add_answer').parents().eq(1).addClass('show_add_answer');
            jQuery(question).find('.fa.fa-times').parent().removeClass('active_remove_answer').addClass('show_remove_answer');

            var question_text = jQuery(question).find('.ays_question_input').val();
            jQuery(question).find('.ays_question_input').remove();
            jQuery(question).prepend('<p class="ays_question">' + question_text + '</p>');
            var answers_tr = jQuery(question).find('.ays_answers_table tr');
            for (var i = 0; i < answers_tr.length; i++) {
                var answer_text = (jQuery(answers_tr.eq(i)).find('.ays_answer').val()) ? jQuery(answers_tr.eq(i)).find('.ays_answer').val() : '';
                jQuery(answers_tr.eq(i)).find('.ays_answer_td').empty();
                var answer_html = '<p class="ays_answer">' + answer_text + '</p>'+((answer_text == '')?'<p>Answer</p>':'');
                jQuery(answers_tr.eq(i)).find('.ays_answer_td').append(answer_html)
            }
            jQuery('.active_question').find('.ays_question_overlay').removeClass('display_none');
            jQuery('.active_question').removeClass('active_question');
        }
    }
    
    function searchForPage(params, data) {
        // If there are no search terms, return all of the data
        if (jQuery.trim(params.term) === '') {
          return data;
        }

        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
          return null;
        }
        var searchText = data.text.toLowerCase();
        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        if (searchText.indexOf(params.term) > -1) {
          var modifiedData = jQuery.extend({}, data, true);
          modifiedData.text += ' (matched)';

          // You can return modified objects from here
          // This includes matching the `children` how you want in nested data sets
          return modifiedData;
        }

        // Return `null` if the term should not be displayed
        return null;
    }

    function selectElementContents(el) {
        if (window.getSelection && document.createRange) {
            // var sel = window.getSelection();
            // var range = document.createRange();
            // range.selectNodeContents(el);
            // sel.removeAllRanges();
            // sel.addRange(range);

            // var _this = jQuery(document).find('strong.ays-survey-shortcode-box');
            var _this = jQuery(document).find( el );

            var text      = el.textContent;
            var textField = document.createElement('textarea');

            textField.innerText = text;
            document.body.appendChild(textField);
            textField.select();
            document.execCommand('copy');
            textField.remove();

            var selection = window.getSelection();
            selection.setBaseAndExtent(el,0,el,1);

            _this.attr( "data-original-title", SurveyMakerAdmin.copied );
            _this.attr( "title", SurveyMakerAdmin.copied );

            _this.tooltip("show");

        } else if (document.selection && document.body.createTextRange) {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.select();
        }
    }

    function selectElementContentsCopy(element) {
        var _this = element;
        var text  = _this.attr("data-clipboard-text");
        var newElement = jQuery('<textarea>').appendTo('body').val(text).select();
        document.execCommand('copy');
        newElement.remove();
        _this.attr( "data-original-title", SurveyMakerAdmin.copied );
        _this.attr( "title", SurveyMakerAdmin.copied );
        _this.tooltip("show");
    }

    function nl2br (str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    function getEachSubmission(e){
        
        var innerInput = jQuery(this).parents(".ays_survey_results").find(".ays_survey_result");
        var innerInputVal = innerInput.val();
        var submissionIdStr = jQuery("#questions").find('.ays_submissions_id_str').val();
        var submissionIdArr = submissionIdStr.split(",");
        var newSubmissionArr = [];

        for(var i = 0; i < submissionIdArr.length; i++ ){
            newSubmissionArr[i+1] = submissionIdArr[i];
        }

        var forInput = newSubmissionArr.indexOf(innerInputVal);
        changeTabTo("questions");
        jQuery(document).find('.ays_number_of_result').val(forInput);

        setTimeout(function(){
            jQuery(document).find('.ays_number_of_result').trigger('change');
        }, 500);

        jQuery(document.body).goTo();
        
    }

    function changeTabTo(href){
        var newHref = "#"+href;
        jQuery(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
            if (jQuery(this).hasClass('nav-tab-active')) {
                jQuery(this).removeClass('nav-tab-active');
            }
        });
        jQuery(document).find('a[href='+newHref+']').addClass('nav-tab-active');
        
        jQuery(document).find('.ays-survey-tab-content').each(function () {
            jQuery(this).css('display', 'none');
        });

        jQuery('.ays-survey-tab-content' + newHref).css('display', 'block');
    }
    
    /**
     * @return {string}
     */
    function aysEscapeHtml(text) {
        var map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>\"']/g, function(m) { return map[m]; });
    }

    function makeAllQuestionsRequired(button, inSection){
        var getButtonDataFlag = button.attr("data-flag");
        var setButtonDataFlag = false;
        var thisCheckbox =  button.find(".make-questions-required-checkbox");
        var requiredInputs = jQuery(document).find("#tab1 .ays-survey-input-required-question.ays-switch-checkbox");
        if(inSection){            
            thisCheckbox = button.parent().find(".make-questions-required-checkbox");
            requiredInputs = button.parents('.ays-survey-section-box').find(".ays-survey-input-required-question.ays-switch-checkbox");
        }

        if(getButtonDataFlag == "off"){
            setButtonDataFlag = false;
            button.attr("data-flag" , "on");
            button.find("img.ays-survey-required-section-img").show();
        }
        else{
            setButtonDataFlag = true;
            button.attr("data-flag" , "off");            
            button.find("img.ays-survey-required-section-img").hide();
        }
        thisCheckbox.prop("checked", !setButtonDataFlag);
        
        if(thisCheckbox.prop("checked")){
            requiredInputs.prop("checked" , true);
        }
        else{
            requiredInputs.prop("checked" , false);
        }

    }

    function aysSurveystripHTML( dirtyString ) {
        var container = document.createElement('div');
        var text = document.createTextNode(dirtyString);
        container.appendChild(text);

        return container.innerHTML; // innerHTML will be a xss safe string
    }
//})(jQuery);
