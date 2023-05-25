(function($) {
    'use strict';

    var defaults = {
        mode: 'lg-slide',
        cssEasing: 'ease',
        easing: 'linear',
        speed: 600,
        height: '100%',
        width: '100%',
        addClass: '',
        galleryId: 1
    };

    function AysSurveyPlugin(element, options){
        this.el = element;
        this.$el = $(element);
        this.htmlClassPrefix = 'ays-survey-';
        this.dbOptionsPrefix = 'survey_';
        this.ajaxAction = 'ays_survey_ajax';
        this.dbOptions = undefined;
        this.QuizQuestionsOptions = undefined;
        this.uniqueId;
        this.surveyId;
        this.sectionsContainer;
        this.sections;
        this.current_fs;
        this.next_fs;
        this.previous_fs; //fieldsets
        this.left;
        this.opacity;
        this.scale; //fieldset properties which we will animate
        this.animating;
        this.percentAnimate; //flag to prevent quick multi-click glitches
        this.explanationTimeout;
        this.confirmBeforeUnload = false;
        // new added email pattern
        this.emailValivatePattern = /^[a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.\w{2,}$/;

        // old email pattern(works slower)
        // this.emailValivatePattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,})+$/;

        this.init();

        return this;
    }

    AysSurveyPlugin.prototype.init = function() {
        var _this = this;
        _this.uniqueId = _this.$el.data('id');

        if( typeof window.aysSurveyOptions != 'undefined' ){
            _this.dbOptions = JSON.parse( atob( window.aysSurveyOptions[ _this.uniqueId ] ) );
        }
        
        if( _this.$el.hasClass( _this.htmlClassPrefix + 'blocked-content' ) ){
            _this.blockedContent( true );
            return false;
        }

        _this.setup();
        _this.blockedContent( false );
        _this.setEvents();
        // _this.share();
        _this.keydown();
    };

    
    AysSurveyPlugin.prototype.setup = function(e) {
        var _this = this;
        _this.sectionsContainer = _this.$el.find('.' + _this.htmlClassPrefix + 'sections');
        _this.sections = _this.sectionsContainer.find('.' + _this.htmlClassPrefix + 'section');

        _this.sections.first().addClass('active-section');

		var questionTypeText = $(document).find('textarea.' + _this.htmlClassPrefix + 'question-input-textarea');
		autosize(questionTypeText);

		var questionTypeSelect = $(document).find('.' + _this.htmlClassPrefix + 'question-select');
		
        questionTypeSelect.each(function(){
			$(this).aysDropdown({
				duration: 150,
				transition: 'scale'
			});
		});

        if( _this.dbOptions.options ){
            if( _this.dbOptions.options.survey_enable_info_autofill ){
                var checkAutoFill = _this.dbOptions.options.survey_enable_info_autofill.length > 0 && _this.dbOptions.options.survey_enable_info_autofill == "on" ? true : false; 
                if(checkAutoFill){
                    var form = _this.$el.find('form');
                    var data = form.serializeFormJSON();
                    data.action    = _this.ajaxAction;
                    data.function  = 'ays_survey_get_user_information';
                    data.end_date  = _this.GetFullDateTime();
                    data.unique_id = _this.uniqueId;
                    _this.aysAutofillData(data , _this.$el);
                }
            }
        }
    }

    AysSurveyPlugin.prototype.setEvents = function(e) {
        var _this = this;
        
        _this.aysNext();
        _this.aysFinish();

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'answer-label-other input', function(){
            $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-other-input').focus();
            _this.confirmBeforeUnload = true;
        });
        
        _this.$el.on('input', '.' + _this.htmlClassPrefix + 'answer-other-input', function(){
            if( $(this).parents('.' + _this.htmlClassPrefix + 'question').data('type') == 'checkbox' ){
                var checkedCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
                var questionId   = $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-id').val();

                questionId = parseInt( questionId );
                var options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
                if(typeof options != "undefined"){
                    if( options.enable_max_selection_count === true ){
                        if( !(options.max_selection_count != null && (options.max_selection_count < checkedCount + 1)) ){                        
                            $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
                        }
                    }
                    else{
                        $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
                    }
                }
            }else{
                $(this).parents('.' + _this.htmlClassPrefix + 'answer').find('.' + _this.htmlClassPrefix + 'answer-label-other input').prop('checked', true);
            }
            _this.confirmBeforeUnload = true;
        });

        _this.sectionsContainer.on('change', 'input[type="radio"][name^="' + _this.htmlClassPrefix + 'answers"]', function(){
            var check_required = $(this).parents('.' + _this.htmlClassPrefix + 'question').data('required');
            
            if(!check_required){
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').addClass('in');
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').removeClass('out');
                $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer-clear-selection-container').removeClass(_this.htmlClassPrefix + 'visibility-none');
            }
            _this.confirmBeforeUnload = true;
        });

        _this.sectionsContainer.on('click', 'input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]', function(){
            var questionBox = $(this).parents('.' + _this.htmlClassPrefix + 'question');
            var checkedCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
            var allVotesCount = $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]').length;
            var questionId = questionBox.find('.' + _this.htmlClassPrefix + 'question-id').val();
            questionId = parseInt( questionId );
            
            
            var options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
            var minVotes = options.min_selection_count;

            var maxVotes = options.max_selection_count != null ? options.max_selection_count : 0;
            if( options.enable_max_selection_count === true ){
                var checkVotesCount = (maxVotes != null || minVotes != null) ? true : false;
                if(checkVotesCount){
                    if(maxVotes <= 0 ){
                        maxVotes = allVotesCount;
                    }
                    if( maxVotes < checkedCount ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.maximumVotes + ' ' + maxVotes + '</span>';
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                        
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                        setTimeout(function() {
                            questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
                        }, 3000);
                        return false;
                    }
                    if(minVotes > maxVotes){
                        minVotes = maxVotes;
                    }
                    if(minVotes > allVotesCount){
                        minVotes = allVotesCount;
                    }
                    if(minVotes <= checkedCount){
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
                    }
                    else{
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.minimumVotes + ' ' + minVotes + '</span>';
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                        questionBox.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                    }
                }
            }
        });

        _this.sectionsContainer.on('click', '.' + _this.htmlClassPrefix + 'answer-clear-selection-container .' + _this.htmlClassPrefix + 'button', function(){
            var clearContainer = $(this).parents('.' + _this.htmlClassPrefix + 'answer-clear-selection-container');
            $(this).parents('.' + _this.htmlClassPrefix + 'question-answers').find('input[type="radio"][name^="' + _this.htmlClassPrefix + 'answers"]').prop('checked', false);
            clearContainer.removeClass('in');
            clearContainer.addClass('out');
            setTimeout(function(){
                clearContainer.addClass(_this.htmlClassPrefix + 'visibility-none');
            }, 200);
        });

        if( _this.dbOptions[ _this.dbOptionsPrefix + 'enable_leave_page' ] ){
            window.onbeforeunload =  function (e) {
                if( _this.confirmBeforeUnload === true ){
                    return true;
                }else{
                    return null;
                }
            }
        }

        _this.$el.on('input', '.' + _this.htmlClassPrefix + 'question-email-input', function(){
            $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
            if($(this).val() != ''){
                if (!(_this.emailValivatePattern.test($(this).val()))) {
                    var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                    errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').addClass('ays-has-error');
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                }else{
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').hide();
                    $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
                }
            }else{
                $(this).parents('.' + _this.htmlClassPrefix + 'question').removeClass('ays-has-error');
                $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').hide();
                $(this).parents('.' + _this.htmlClassPrefix + 'question').find('.' + _this.htmlClassPrefix + 'question-validation-error').html('');
            }
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'restart-button', function(){
            window.location.reload();
        });

        // Social buttons
        _this.$el.find(".ays-survey-share-btn").on("click" , function(e){
            e.preventDefault();
            var wWidth = 650,
                wHeight = 450;
            var windowOptions = 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,width='+wWidth+',height='+wHeight+',top='+(screen.height/2-wHeight/2)+',left='+(screen.width/2-wWidth/2);
            window.open(this.href, "_blank", windowOptions);
        });

        // Survey full screen
        _this.$el.find('.ays-survey-close-full-screen, .ays-survey-open-full-screen').on('click', function() {
            _this.toggleFullscreen(_this.el);
        });
        _this.aysSurveyFullScreenDeactivateAll();

        // Text limit character/word
        _this.$el.find('textarea.ays-survey-check-word-limit, input.ays-survey-check-word-limit').on('keyup keypress', function(e) {
            var currentQuestion = $(this).parents(".ays-survey-question");
            var currentquestionId = currentQuestion.find('.' + _this.htmlClassPrefix + 'question-id').val();
            var questionTextLimitOptions = _this.dbOptions[ _this.dbOptionsPrefix + 'text_limit_options' ][currentquestionId];
            _this.aysSurveyCheckTextLimit(currentQuestion,questionTextLimitOptions,e,$(this));
        });

        // Number limit character/word
        _this.$el.find('input.ays-survey-check-number-limit').on('keyup keypress', function(e) {
            var currentQuestion = $(this).parents(".ays-survey-question");
            var currentquestionId = currentQuestion.find('.' + _this.htmlClassPrefix + 'question-id').val();
            var questionTextLimitOptions = _this.dbOptions[ _this.dbOptionsPrefix + 'number_limit_options' ][currentquestionId];
            _this.aysSurveyCheckNumberLimit(currentQuestion,questionTextLimitOptions,e,$(this));
        });

        _this.aysSurveyonTabPress();
        
    }

    AysSurveyPlugin.prototype.start = function(e) {
        var _this = this;
        var $this = _this.$el.find('.ays_next.start_button');

        _this.quizId = _this.$el.find('input[name="ays_quiz_id"]').val();
        _this.dbOptions = JSON.parse(atob(window.aysQuizOptions[_this.quizId]));
        if(typeof _this.dbOptions.answers_rw_texts == 'undefined'){
            _this.dbOptions.answers_rw_texts = 'on_passing';
        }
        if(typeof _this.dbOptions.make_questions_required == 'undefined'){
            _this.dbOptions.make_questions_required = 'off';
        }
        var quizOptionsName = 'quizOptions_' + _this.quizId;
        _this.QuizQuestionsOptions = [];
        if(typeof window[quizOptionsName] !== 'undefined'){
            for(var i in window[quizOptionsName]){
                _this.QuizQuestionsOptions[i] = JSON.parse(window.atob(window[quizOptionsName][i]));
            }
        }

        _this.aysResetQuiz( _this.$el );

        _this.$el.css('padding-bottom', '0px');

        var isRequiredQuestion = (_this.dbOptions.make_questions_required && _this.dbOptions.make_questions_required == "on") ? true : false;
        if(isRequiredQuestion === true){
            _this.$el.find('div[data-question-id]').each(function(){
                var thisStep = $(this);
                if(!thisStep.find('input.ays_next').hasClass('ays_display_none')){
                    thisStep.find('input.ays_next').attr('disabled', 'disabled');
                }else if(!thisStep.find('i.ays_next_arrow').hasClass('ays_display_none')){
                    thisStep.find('i.ays_next_arrow').attr('disabled', 'disabled');
                }

                if(!thisStep.find('input.ays_early_finish').hasClass('ays_display_none')){
                    thisStep.find('input.ays_early_finish').attr('disabled', 'disabled');
                }else if(!thisStep.find('i.ays_early_finish').hasClass('ays_display_none')){
                    thisStep.find('i.ays_early_finish').attr('disabled', 'disabled');
                }
            });
        }

        setTimeout(function(){
            _this.$el.css('border-radius', _this.dbOptions.quiz_border_radius + 'px');
            _this.$el.find('.step').css('border-radius', _this.dbOptions.quiz_border_radius + 'px');
        }, 400);

        $(e.target).parents('div.step').removeClass('active-step');
        $(e.target).parents('div.step').next().addClass('active-step');
        _this.current_fs = $this.parents('.step');
        _this.next_fs = $this.parents('.step').next();
        _this.startLiveProgressBar($this);

        if(_this.dbOptions.enable_pass_count == 'on'){
            _this.aysAnimateStep(_this.$el.data('questEffect'), _this.$el.find('.ays_quizn_ancnoxneri_qanak'));
        }
        if(_this.dbOptions.enable_rate_avg == 'on'){
            _this.aysAnimateStep(_this.$el.data('questEffect'), _this.$el.find('.ays_quiz_rete_avg'));
        }
        _this.selects();
        _this.answersField();
    };

    AysSurveyPlugin.prototype.startTime = function (e) {
        var _this = this;
        var $this = $(e.target);
        var thisStep = $this.parents('.step');
        _this.$el.find('.ays-live-bar-wrap').css({'display': 'block'});
        _this.$el.find('.ays-live-bar-percent').css({'display': 'inline-block'});
        _this.$el.find('input.ays-start-date').val(_this.GetFullDateTime());
        if (_this.dbOptions.enable_timer == 'on') {
            _this.$el.find('div.ays-survey-timer').hide();
            var timer = parseInt(_this.$el.find('div.ays-survey-timer').attr('data-timer'));
            if (!isNaN(timer) && _this.dbOptions.timer !== undefined) {
                if (_this.dbOptions.timer === timer && timer !== 0) {
                    timer += 2;
                    if (timer !== undefined) {
                        _this.timer(timer, {
                            isTimer: true,
                            blockedContent: false,
                            blockedElement: null,
                        });
                    }
                } else {
                    alert('Wanna cheat??');
                    window.location.reload();
                }
            }
        }
    }

    AysSurveyPlugin.prototype.timer = function(timer, args) {
        if(typeof args == "undefined"){
            args = {};
        }
        var _this = this;
        var addTime = 0;
        if(timer >= 0){
            addTime = (timer * 1000);
        }
        var countDownDate = new Date().getTime() + addTime;
        var timeForShow;
        if(addTime > 0){
            var x = setInterval(function (){
                var now = new Date().getTime();
                var distance = countDownDate - Math.ceil(now/1000)*1000;
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                if(hours <= 0){
                    hours = null;
                }else if (hours < 10) {
                    hours = '0' + hours;
                }
                if (minutes < 10) {
                    minutes = '0' + minutes;
                }
                if (seconds < 10) {
                    seconds = '0' + seconds;
                }
                timeForShow = ((hours == null)? "" : (hours + ":")) + minutes + ":" + seconds;
                if(distance <= 1000){
                    timeForShow = ((hours == null) ? "" : "00:") + "00:00";
                }else{
                    timeForShow = timeForShow;
                }
                _this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').html(timeForShow);
                // _this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').show(500);
                if(_this.$el.find('.' + _this.htmlClassPrefix + 'countdown-time').length === 0){
                    clearInterval(x);
                }

                if (distance <= 1000) {
                    clearInterval(x);
                    if(args.blockedContent){
                        if(args.redirectNewTab){
                            window.open( args.redirectUrl , "_blank");
                        }
                        else{
                            window.location.assign( args.redirectUrl );
                        }
                    }
                }
            }, 1000);
        }
        else{
            if(args.blockedContent){
                if(args.redirectNewTab){
                    window.open( args.redirectUrl , "_blank");
                }
                else{
                    window.location.assign( args.redirectUrl );
                }
            }
        }
    };

    AysSurveyPlugin.prototype.ratingAvg = function() {
        var _this = this;
        _this.$el.find('.for_quiz_rate_avg.ui.rating').rating('disable');
    };

    AysSurveyPlugin.prototype.aysNext = function() {
        var _this = this;
        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'next-button', function (e){
            var currentPage = _this.$el.find("div.ays-survey-section.active-section").data("pageNumber") + 1;
            _this.confirmBeforeUnload = true;
            e.preventDefault();
            if (_this.animating) return false;
            _this.animating = true;
            if( _this.checkForm( $( e.target ) ) ){
                // _this.actionLiveProgressBar($(e.target), 'next');
                _this.activeStep($(e.target), 'next');

                // _this.validateButtonsVisibility();

                // _this.next_fs.find('.ays-text-input').trigger( "focus" );

                // _this.aysAnimateStep(_this.$el.data('questEffect'), _this.current_fs, _this.next_fs);
                _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);

                _this.goTo();
                // _this.goToTop();
                var pageCount   = _this.$el.find("div.ays-survey-section:not(:last-child)").length;
                var fillWidth   = (currentPage*100)/pageCount;
                _this.$el.find("div.ays-survey-live-bar-fill").animate({'width': fillWidth+"%"}, 1000);
                _this.$el.find("span.ays-survey-live-bar-changeable-text").text(currentPage);

            }else{
            //    alert('tutuz');
            }
        });

        _this.$el.on('click', '.' + _this.htmlClassPrefix + 'prev-button', function (e){
            _this.confirmBeforeUnload = true;
            e.preventDefault();
            var currentPage = _this.$el.find("div.ays-survey-section.active-section").data("pageNumber") - 1;
            var pageCount   = _this.$el.find("div.ays-survey-section:not(:last-child)").length;
            var fillWidth   = (currentPage*100)/pageCount;
            _this.$el.find("div.ays-survey-live-bar-fill").animate({'width': fillWidth+"%"}, 500);
            _this.$el.find("span.ays-survey-live-bar-changeable-text").text(currentPage);

            if (_this.animating) return false;
            _this.animating = true;
            //if( _this.checkForm( $( e.target ) ) ){
                // _this.actionLiveProgressBar($(e.target), 'next');
                _this.activeStep($(e.target), 'prev');

                // _this.validateButtonsVisibility();

                // _this.next_fs.find('.ays-text-input').trigger( "focus" );

                // _this.aysAnimateStep(_this.$el.data('questEffect'), _this.current_fs, _this.next_fs);
                _this.aysAnimateStep('fade', _this.current_fs, _this.next_fs);

                _this.goTo();
                // _this.goToTop();
            //}else{
            //    alert('tutuz');
            //}
        });
    };

    AysSurveyPlugin.prototype.aysFinish = function() {
        var _this = this;
        var fButtonSelector = 'input.' + _this.htmlClassPrefix + 'finish-button';

        _this.$el.on('click', fButtonSelector, function (e) {
            e.preventDefault();
            _this.confirmBeforeUnload = false;

            // if(_this.$el.find('.ays_music_sound').length !== 0){
            //     _this.$el.find('.ays_music_sound').fadeOut();
            //     setTimeout(function() {
            //         audioVolumeOut(_this.$el.find('.ays_quiz_music').get(0));
            //     },4000);
            //     setTimeout(function() {
            //         _this.$el.find('.ays_quiz_music').get(0).pause();
            //     },6000);
            // }
            // if(_this.$el.find('audio').length > 0){
            //     _this.$el.find('audio').each(function(e, el){
            //         el.pause();
            //     });
            // }
            // if(_this.$el.find('video').length > 0){
            //     _this.$el.find('video').each(function(e, el){
            //         el.pause();
            //     });
            // }
            // _this.$el.find('.ays-live-bar-wrap').addClass('bounceOut');
            // setTimeout(function () {
            //     _this.$el.find('.ays-live-bar-wrap').css('display','none');
            // },300);

            if( _this.checkForm( $( e.target ) ) ){


                // var next_sibilings_count = $(this).parents('form').find('.ays_question_count_per_page').val();
                // _this.$el.find('input[name^="ays_questions"]').attr('disabled', false);
                // _this.$el.find('div.ays-quiz-timer').slideUp(500);
                // if(_this.$el.find('div.ays-quiz-after-timer').hasClass('empty_after_timer_text')){
                //     _this.$el.find('div.ays-quiz-timer').parent().slideUp(500);
                // }

                // _this.activeStep($(this), 'next');

                // _this.next_fs = $(this).parents('.step').next();
                // _this.current_fs = $(this).parents('.step');
                // _this.next_fs.addClass('active-step');
                // _this.current_fs.removeClass('active-step');

                var form = _this.$el.find('form');

                var data = form.serializeFormJSON();
                // var questionsIds = data.ays_quiz_questions.split(',');

                // for(var i = 0; i < questionsIds.length; i++){
                //     if(! data['ays_questions[ays-question-'+questionsIds[i]+']']){
                //         data['ays_questions[ays-question-'+questionsIds[i]+']'] = "";
                //     }
                // }
                
                var surveyCurrentPageLink = form.find('input[name="ays_survey_curent_page_link"]').val();
                data.action = _this.ajaxAction;
                data.function = 'ays_finish_survey';
                data.end_date = _this.GetFullDateTime();
                data.unique_id = _this.uniqueId;
                data.survey_current_page_link = surveyCurrentPageLink;


                var sections = _this.$el.find('.' + _this.htmlClassPrefix + 'sections > .' + _this.htmlClassPrefix + 'section:not(:last-child)');
                // sections.hide(100);
                setTimeout( function(){
                    sections.remove();
                }, 100 );

                _this.$el.find('.' + _this.htmlClassPrefix + 'sections > .' + _this.htmlClassPrefix + 'section.' + _this.htmlClassPrefix + 'results-content').show();

                var aysQuizLoader = form.find('div[data-role="loader"]');
                aysQuizLoader.addClass(aysQuizLoader.data('class'));
                aysQuizLoader.removeClass('ays-survey-loader');

                setTimeout(function () {
                    _this.sendSurveyData(data, $(e.target));
                }, 2000);

                _this.goTo();
                // if (parseInt(next_sibilings_count) > 0 && ($(this).parents('.step').attr('data-question-id') || $(this).parents('.step').next().attr('data-question-id'))) {
                //     current_fs = $(this).parents('form').find('div[data-question-id]');
                // }

                // aysAnimateStep(ays_quiz_container.data('questEffect'), current_fs, next_fs);
            }
        });
    }

    AysSurveyPlugin.prototype.validateButtonsVisibility = function() {
        var _this = this;
        var nextQuestionType = _this.next_fs.find('input[name^="ays_questions"]').attr('type');
        var buttonsDiv = _this.next_fs.find('.ays_buttons_div');
        var enableArrows = _this.$el.find(".ays_qm_enable_arrows").val();

        if(_this.dbOptions.enable_arrows){
            enableArrows = _this.dbOptions.enable_arrows == 'on' ? true : false;
        }else{
            enableArrows = parseInt(enableArrows) == 1 ? true : false;
        }
        var nextArrowIsDisabled = buttonsDiv.find('i.ays_next_arrow').hasClass('ays_display_none');
        var nextButtonIsDisabled = buttonsDiv.find('input.ays_next').hasClass('ays_display_none');

        if(_this.next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(_this.next_fs.find('textarea[name^="ays_questions"]').attr('type')==='text' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'checkbox' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'checkbox' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'number' && nextArrowIsDisabled && nextButtonIsDisabled){
           buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'number' && enableArrows){
           buttonsDiv.find('input.ays_next').addClass('ays_display_none');
           buttonsDiv.find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'text' && nextArrowIsDisabled && nextButtonIsDisabled){
           next_fs.find('.ays_buttons_div').find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'text' && enableArrows){
           next_fs.find('.ays_buttons_div').find('input.ays_next').addClass('ays_display_none');
           next_fs.find('.ays_buttons_div').find('i.ays_next_arrow').removeClass('ays_display_none');
        }

        if(nextQuestionType === 'date' && nextArrowIsDisabled && nextButtonIsDisabled){
            buttonsDiv.find('input.ays_next').removeClass('ays_display_none');
        }
        if(nextQuestionType === 'date' && enableArrows){
            buttonsDiv.find('input.ays_next').addClass('ays_display_none');
            buttonsDiv.find('.ays_next_arrow').removeClass('ays_display_none');
        }

        var isRequiredQuestion = (_this.dbOptions.make_questions_required && _this.dbOptions.make_questions_required == "on") ? true : false;
        if(isRequiredQuestion === true){
            if(_this.next_fs.find('.information_form').length === 0){
                if(enableArrows){
                    buttonsDiv.find('i.ays_next_arrow').attr('disabled', 'disabled');
                }else{
                    buttonsDiv.find('input.ays_next').attr('disabled', 'disabled');
                }
            }
        }
    }

    AysSurveyPlugin.prototype.goToTop = function( el ) {
        el.get(0).scrollIntoView({
            block: "center",
            behavior: "smooth"
        });
    }

    AysSurveyPlugin.prototype.goTo = function() {
        var surveyAnimationTop;

        if(this.dbOptions != 'undefined'){
            surveyAnimationTop = (this.dbOptions.survey_animation_top && this.dbOptions.survey_animation_top != 0) ? parseInt(this.dbOptions.survey_animation_top) : 200;
        }else{
            surveyAnimationTop = 200;
        }
        if(this.dbOptions.survey_enable_animation_top){
            $('html, body').animate({
                scrollTop: this.$el.offset().top - surveyAnimationTop + 'px'
            }, 'fast');
        }
        return this; // for chaining...
    }

    AysSurveyPlugin.prototype.keydown = function(){
        var _this = this, $this = _this.$el;
        $this.find('input').on('focus', function () {
            $(window).on('keydown', function (event) {
                if (event.keyCode === 13) {
                    return false;
                }
            });
        });

        $this.find('input').on('blur', function () {
            $(window).off('keydown');
        });
    }

    AysSurveyPlugin.prototype.aysAnimateStep = function(animation, current_fs, next_fs){
        var _this = this;
        if(typeof next_fs !== "undefined"){
            switch(animation){
                case "lswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });

                    current_fs.addClass('swing-out-right-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                        next_fs.css('display', 'flex');
                        next_fs.addClass('swing-in-left-fwd');
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        next_fs.css({
                            'position':'relative',
                            'pointer-events': 'auto'
                        });
                        current_fs.removeClass('swing-out-right-bck');
                        next_fs.removeClass('swing-in-left-fwd');
                        _this.animating = false;
                    },1000);
                break;
                case "rswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });

                    current_fs.addClass('swing-out-left-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                        next_fs.css('display', 'flex');
                        next_fs.addClass('swing-in-right-fwd');
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        next_fs.css({
                            'position':'relative',
                            'pointer-events': 'auto'
                        });
                        current_fs.removeClass('swing-out-left-bck');
                        next_fs.removeClass('swing-in-right-fwd');
                        _this.animating = false;
                    },1000);
                break;
                case "shake":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.scale = 1 - (1 - now) * 0.2;
                            _this.left = (now * 50) + "%";
                            _this.opacity = 1 - now;
                            current_fs.css({
                                'transform': 'scale(' + _this.scale + ')',
                                'position': 'absolute',
                                'top':0,
                                'opacity': 1,
                                'pointer-events': 'none'
                            });
                            next_fs.css({
                                'left': _this.left,
                                'opacity': _this.opacity,
                                'display':'flex',
                                'position':'relative',
                                'pointer-events': 'none'
                            });
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            current_fs.css({
                                'pointer-events': 'auto',
                                'opacity': 1,
                                'position': 'static'
                            });
                            next_fs.css({
                                'display':'flex',
                                'position':'relative',
                                'transform':'scale(1)',
                                'opacity': 1,
                                'pointer-events': 'auto'
                            });
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                case "fade":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.opacity = 1 - now;
                            current_fs.attr('style' , 'position: absolute !important;width: 100% !important;pointer-events: none !important;');
                            next_fs.attr('style' , 'opacity: '+_this.opacity+' !important;position: relative !important;display: block !important;width: 100% !important;pointer-events: none !important;');
                        },
                        duration: 500,
                        complete: function () {
                            current_fs.hide();
                            current_fs.attr('style' , 'position: static !important;pointer-events: auto !important;');
                            next_fs.attr('style' , 'display: block !important;position: relative !important;opacity: 1 !important;pointer-events: auto !important;');
                            _this.animating = false;
                        }
                    });
                break;
                default:
                    current_fs.animate({}, {
                        step: function (now, mx) {
                            current_fs.css({
                                'pointer-events': 'none'
                            });
                            next_fs.css({
                                'position':'relative',
                                'pointer-events': 'none'
                            });
                        },
                        duration: 0,
                        complete: function () {
                            current_fs.hide();
                            current_fs.css({
                                'pointer-events': 'auto'
                            });
                            next_fs.css({
                                'display':'block',
                                'position':'relative',
                                'transform':'scale(1)',
                                'pointer-events': 'auto'
                            });
                            _this.animating = false;
                        }
                    });
                break;
            }
        }else{
            switch(animation){
                case "lswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });
                    current_fs.addClass('swing-out-right-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        current_fs.removeClass('swing-out-right-bck');
                        _this.animating = false;
                    },1000);
                break;
                case "rswing":
                    current_fs.parents('.ays-questions-container').css({
                        perspective: '800px',
                    });
                    current_fs.addClass('swing-out-left-bck');
                    current_fs.css({
                        'pointer-events': 'none'
                    });
                    setTimeout(function(){
                        current_fs.css({
                            'position': 'absolute',
                        });
                    },400);
                    setTimeout(function(){
                        current_fs.hide();
                        current_fs.css({
                            'pointer-events': 'auto',
                            'position': 'static'
                        });
                        current_fs.removeClass('swing-out-left-bck');
                        _this.animating = false;
                    },1000);
                case "shake":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.scale = 1 - (1 - now) * 0.2;
                            _this.left = (now * 50) + "%";
                            _this.opacity = 1 - now;
                            current_fs.css({
                                'transform': 'scale(' + _this.scale + ')',
                            });
                        },
                        duration: 800,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                case "fade":
                    current_fs.animate({opacity: 0}, {
                        step: function (now, mx) {
                            _this.opacity = 1 - now;
                        },
                        duration: 500,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        },
                        easing: 'easeInOutBack'
                    });
                break;
                default:
                    current_fs.animate({}, {
                        step: function (now, mx) {

                        },
                        duration: 0,
                        complete: function () {
                            current_fs.hide();
                            _this.animating = false;
                        }
                    });
                break;
            }
        }
    }

    AysSurveyPlugin.prototype.activeStep = function(button, action) {
        var _this = this;
        _this.current_fs = button.parents('.' + _this.htmlClassPrefix + 'section');
        if(action == 'next'){
            _this.next_fs = button.parents('.' + _this.htmlClassPrefix + 'section').next();
        }
        if(action == 'prev'){
            _this.next_fs = button.parents('.' + _this.htmlClassPrefix + 'section').prev();
        }
        _this.current_fs.removeClass('active-section');
        _this.next_fs.addClass('active-section');
    }

    AysSurveyPlugin.prototype.startLiveProgressBar = function(button) {
        var _this = this;
        if (button.parents('.step').next().find('.information_form').length === 0 ){
            var questions_count = _this.$el.find('form').find('div[data-question-id]').length;
            var curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) + 1;
            var final_width = ((curent_number+1) / questions_count * 100) + "%";
            if(button.parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
            }else{
                button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
            }
            button.parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 500);
        }
    }

    AysSurveyPlugin.prototype.actionLiveProgressBar = function(button, action) {
        var _this = this;
        var questions_count = _this.$el.find('form').find('div[data-question-id]').length;
        var curent_number;
        if(action == 'next'){
            curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) + 1;
        }
        if(action == 'prev'){
            curent_number = _this.$el.find('form').find('div[data-question-id]').index(button.parents('div[data-question-id]')) - 1;
        }
        if(curent_number != questions_count){
            if((button.hasClass('ays_finish')) == false){
                if (!($(this).hasClass('start_button'))) {
                    var current_width = button.parents('.ays-quiz-container').find('.ays-live-bar-fill').width();
                    var final_width = ((curent_number+1) / questions_count * 100) + "%";
                    if(button.parents('.ays-quiz-container').find('.ays-live-bar-percent').hasClass('ays-live-bar-count')){
                        button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(curent_number+1));
                    }else{
                        button.parents('.ays-quiz-container').find('.ays-live-bar-percent').text(parseInt(final_width));
                    }
                    button.parents('.ays-quiz-container').find('.ays-live-bar-fill').animate({'width': final_width}, 500);
                }
            }
        }else{
            button.parents('.ays-quiz-container').find('.ays-live-bar-wrap').removeClass('rubberBand').addClass('bounceOut');
            setTimeout(function () {
                button.parents('.ays-quiz-container').find('.ays-live-bar-wrap').css('display','none');
            },300)
        }

        if(questions_count === curent_number){
            if(_this.current_fs.hasClass('.information_form').length !== 0){
                _this.current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().slideUp(500);
                setTimeout(function () {
                    _this.current_fs.parents('.ays-quiz-container').find('.ays-quiz-timer').parent().remove();
                },500);
            }
        }
    }

    AysSurveyPlugin.prototype.checkForm = function( button ){
        var _this = this;
        _this.animating = false;

        var section = button.parents('.' + _this.htmlClassPrefix + 'section');
        var requiredQuestions = section.find('[data-required="true"]');
        if ( requiredQuestions.length !== 0) {
            var empty_inputs = 0;
            var errorQuestions = section.find('.ays-has-error');
            section.find('.ays-has-error').removeClass('ays-has-error');
            for (var i = 0; i < requiredQuestions.length; i++) {
                var item = requiredQuestions.eq(i);
                var checkMinVotes = requiredQuestions.eq(i).data('isMin');
                if( item.data('type') == 'text' || item.data('type') == 'email' || item.data('type') == 'name' || item.data('type') == 'short_text' || item.data('type') == 'number' ){
                    var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                    if( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() == '' ){
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        if( item.data('type') == 'email' ){
                            if ( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ) {
                                errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';
                                item.addClass('ays-has-error');
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                                item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                                _this.goToTop( item );
                                item.find( '.' + _this.htmlClassPrefix + 'input' ).focus();
                                empty_inputs++;
                            }
                        }else{
                            continue;
                        }
                    }
                }

                var errorFlag = false;
                var checker = false;
                if ( item.data('type') == 'radio' || item.data('type') == 'checkbox' ) {
                    if(typeof checkMinVotes != 'undefined' && checkMinVotes){
                        checker = _this.checkMinVotes(item);
                        if(!checker){
                            return false;
                        }
                    }
                    if( item.find('input[type="'+ item.data('type') +'"]:checked').length == 0 ){
                        errorFlag = true;
                    }

                    if( item.find('.' + _this.htmlClassPrefix + 'answer-other-input').length != 0 && 
                        item.find('.' + _this.htmlClassPrefix + 'answer-label-other > input[value="0"]:checked').length > 0 ){
                        if( item.find('.' + _this.htmlClassPrefix + 'answer-other-input').val() == '' ){
                            errorFlag = true;
                            item.find('.' + _this.htmlClassPrefix + 'answer-other-input').focus();
                        }
                    }

                    if( errorFlag ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }

                if ( item.data('type') == 'select' ) {
                    if( item.find('.' + _this.htmlClassPrefix + 'question-select').aysDropdown('get value') == '' ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + _this.dbOptions.survey_required_questions_message + '</span>';
                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }
            // if (!(/^\d+$/.test(button.parents('.step').find('input[name="ays_user_phone"]').val()))) {
            //     if (button.parents('.step').find('input[name="ays_user_phone"]').attr('type') !== 'hidden') {
            //         button.parents('.step').find('input[name="ays_user_phone"]').addClass('ays_red_border');
            //         // button.parents('.step').find('input[name="ays_user_phone"]').addClass('animated');
            //         // button.parents('.step').find('input[name="ays_user_phone"]').addClass('shake');
            //         empty_inputs++;
            //     }

            // }
            // var errorQuestions = section.find('.ays-has-error');
            for (var i = 0; i < errorQuestions.length; i++) {
                var item = errorQuestions.eq(i);
                if( item.data('type') == 'email' ){
                    if( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';

                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }

            if (empty_inputs !== 0) {
                return false;
            }else{
                return true;
            }
        }
        else{
            var empty_inputs = 0;
            var errorQuestions = section.find('.ays-has-error');
            for (var i = 0; i < errorQuestions.length; i++) {
                var item = errorQuestions.eq(i);
                if( item.data('type') == 'email' ){
                    if( ! (_this.emailValivatePattern.test( item.find( '.' + _this.htmlClassPrefix + 'input' ).val() ) ) ){
                        var errorMessage = '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                        errorMessage += '<span>' + aysSurveyLangObj.emailValidationError + '</span>';

                        item.addClass('ays-has-error');
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').html(errorMessage);
                        item.find('.' + _this.htmlClassPrefix + 'question-validation-error').show();
                        _this.goToTop( item );
                        item.find( '.' + _this.htmlClassPrefix + 'input' ).focus();
                        empty_inputs++;
                        break;
                    }else{
                        continue;
                    }
                }
            }
            var questions = section.find('.' + _this.htmlClassPrefix + 'question[data-is-min="true"]');
            
            if(questions.length > 0){
                for(var i = 0; i < questions.length; i++){
                   var checker =  _this.checkMinVotes(questions.eq(i));
                   if(!checker){
                       return false;
                   }
                }
            }
            if (empty_inputs !== 0) {
                return false;
            }else{
                return true;
            }

        }
    }

    AysSurveyPlugin.prototype.blockedContent = function( blocked ) {
        var _this = this;

        if( blocked ){
            var blockedContent = _this.$el.hasClass( _this.htmlClassPrefix + 'blocked-content' );
            if ( blockedContent ) {
                var limitAttemptCount  = _this.dbOptions[ _this.dbOptionsPrefix + 'limit_users' ];
                var redirectUrl = _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_url' ];
                if( limitAttemptCount && redirectUrl != ''){
                    var redirectTimerHTML = '<div class="' + _this.htmlClassPrefix + 'redirect-timer ' + _this.htmlClassPrefix + 'countdown-timer">' + 
                        aysSurveyLangObj.redirectAfter + ' <span class="' + _this.htmlClassPrefix + 'countdown-time">' +  _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_delay_seconds' ]  + '</span>' +
                    '</div>';
                    
                    _this.$el.find('.' + _this.htmlClassPrefix + 'section-header').prepend( $( redirectTimerHTML ) );

                    var timer = _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_delay' ] + 2;
                    _this.timer( timer, {
                        blockedContent: true,
                        redirectUrl: _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_url' ]
                    });
                }
            }
        }

        if( _this.$el.find('.ays_survey_login_form').length > 0 ){
            var surveyLoginForm = _this.$el.find('.ays_survey_login_form');
            var usernameInput = surveyLoginForm.find('input[type="text"]');
            var passwordInput = surveyLoginForm.find('input[type="password"]');
            var checkboxInput = surveyLoginForm.find('input[type="checkbox"]');
            var submitInput = surveyLoginForm.find('input[type="submit"]');
            _this.makeSurveyStyleInput( usernameInput );
            _this.makeSurveyStyleInput( passwordInput );
            _this.makeSurveyStyleSubmit( submitInput );
            if( checkboxInput.length > 0 ){
                _this.makeSurveyStyleCheckbox( checkboxInput );
            }
        }
    }

    AysSurveyPlugin.prototype.selects = function(){
        var _this = this;
        _this.$el.find('.ays-field').on('click', function() {
            if ($(this).find(".select2").hasClass('select2-container--open')) {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_down');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_up');
            } else {
                $(this).find('b[role="presentation"]').removeClass('ays_fa ays_fa_chevron_up');
                $(this).find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
            }
        });

        _this.$el.find('select.ays-select').on("select2:selecting", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });

        _this.$el.find('select.ays-select').on("select2:closing", function(e){
            $(this).parents('.ays-quiz-container').find('b[role="presentation"]').addClass('ays_fa ays_fa_chevron_down');
        });

        _this.$el.find('select.ays-select').on("select2:select", function(e){
            $(this).parent().find('.ays-select-field-value').attr("value", $(this).val());
            if ($(this).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') && $(this).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                $(this).parents('div[data-question-id]').find('.ays_next').trigger('click');
            }
            if($(this).parents(".ays-questions-container").find('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                if ($(this).find('option:selected').data("chisht") == 1) {
                    $(this).parents('.ays-field').addClass('correct correct_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "green");
                } else {
                    $(this).parents('.ays-field').addClass('wrong wrong_div');
                    $(this).parents('.ays-field').find('.select2-selection.select2-selection--single').css("border-bottom-color", "red");
                }
                if ($(this).find('option:selected').data("chisht") == 1) {
                    $(e.target).parents().eq(3).find('.right_answer_text').fadeIn();
                }
                else {
                    $(e.target).parents().eq(3).find('.wrong_answer_text').fadeIn();
                }
                $(this).attr("disabled", true);
                $(e.target).next().css("background-color", "#777");
                $(e.target).next().find('.selection').css("background-color", "#777");
                $(e.target).next().find('.select2-selection').css("background-color", "#777");
            }
            var this_select_value = $(this).val();
            $(this).find("option").removeAttr("selected");
            $(this).find("option[value='"+this_select_value+"']").attr("selected", true);
        });
    }

    AysSurveyPlugin.prototype.answersField = function(){
        var _this = this;

        _this.$el.on('change', 'input[name^="ays_questions"]', function (e) {
            var quizContainer = _this.$el;
            if(typeof myOptions != 'undefined'){
                var isRequiredQuestion = (_this.dbOptions.make_questions_required && _this.dbOptions.make_questions_required == "on") ? true : false;
                if(isRequiredQuestion === true){
                    if($(e.target).attr('type') === 'radio' || $(e.target).attr('type') === 'checkbox'){
                        if($(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]:checked').length != 0){
                            if (!$(e.target).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none')){
                                $(e.target).parents('div[data-question-id]').find('input.ays_next').removeAttr('disabled');
                                $(e.target).parents('div[data-question-id]').find('input.ays_early_finish').removeAttr('disabled');
                            }else if(!$(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                $(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').removeAttr('disabled');
                                $(e.target).parents('div[data-question-id]').find('i.ays_early_finish').removeAttr('disabled');
                            }
                        }else{
                            if (!$(e.target).parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none')){
                                $(e.target).parents('div[data-question-id]').find('input.ays_next').attr('disabled', true);
                                $(e.target).parents('div[data-question-id]').find('input.ays_early_finish').attr('disabled', true);
                            }else if(!$(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                $(e.target).parents('div[data-question-id]').find('i.ays_next_arrow').attr('disabled', true);
                                $(e.target).parents('div[data-question-id]').find('i.ays_early_finish').attr('disabled', true);
                            }
                        }
                    }
                }
            }

            if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                }
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('.ays_next').trigger('click');
                    }
                }
                if ($(e.target).parents().eq(4).hasClass('enable_correction')) {
                    if (checked_inputs.attr('type') === "radio") {
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                    } else if (checked_inputs.attr('type') === "checkbox") {
                        $(e.target).attr('disabled', true);
                        $(e.target).off('change');
                    }
                }
                return false;
            }

            if (quizContainer.find('form').hasClass('enable_correction')) {
                var right_answer_sound = quizContainer.find('.ays_quiz_right_ans_sound').get(0);
                var wrong_answer_sound = quizContainer.find('.ays_quiz_wrong_ans_sound').get(0);
                var finishAfterWrongAnswer = (_this.dbOptions.finish_after_wrong_answer && _this.dbOptions.finish_after_wrong_answer == "on") ? true : false;
                if ($(e.target).parents().eq(1).find('input[name="ays_answer_correct[]"]').length !== 0) {
                    var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                    if (checked_inputs.attr('type') === "radio") {

                        checked_inputs.nextAll().addClass('answered');
                        checked_inputs.parent().addClass('checked_answer_div');
                        if (checked_inputs.prev().val() == 1){
                            checked_inputs.nextAll().addClass('correct')
                            checked_inputs.parent().addClass('correct_div');
                        }else{
                            checked_inputs.nextAll().addClass('wrong');
                            checked_inputs.parent().addClass('wrong_div');
                        }

                        if (checked_inputs.prev().val() == 1) {
                            if(_this.dbOptions.answers_rw_texts && (_this.dbOptions.answers_rw_texts == 'on_passing' || _this.dbOptions.answers_rw_texts == 'on_both')){
                                var explanationTime = _this.dbOptions.explanation_time && _this.dbOptions.explanation_time != "" ? parseInt(_this.dbOptions.explanation_time) : 4;
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.right_answer_text').slideDown(250);
                                }
                                _this.explanationTimeout = setTimeout(function(){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                        checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                    }
                                }, explanationTime * 1000);
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                }
                            }
                            if((right_answer_sound)){
                                resetPlaying([right_answer_sound, wrong_answer_sound]);
                                setTimeout(function(){
                                    right_answer_sound.play();
                                }, 10);
                            }
                        } else {
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').parent().addClass('correct_div').addClass('checked_answer_div');
                            $(e.target).parents('.ays-quiz-answers').find('input[name="ays_answer_correct[]"][value="1"]').nextAll().addClass('correct answered');

                            if(_this.dbOptions.answers_rw_texts && (_this.dbOptions.answers_rw_texts == 'on_passing' || _this.dbOptions.answers_rw_texts == 'on_both')){
                                var explanationTime = _this.dbOptions.explanation_time && _this.dbOptions.explanation_time != "" ? parseInt(_this.dbOptions.explanation_time) : 4;
                                if(! $(e.target).parents('.step').hasClass('not_influence_to_score')){
                                    $(e.target).parents().eq(3).find('.wrong_answer_text').slideDown(250);
                                }
                                _this.explanationTimeout = setTimeout(function(){
                                    if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                        checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                        if(finishAfterWrongAnswer){
                                            goToLastPage(e);
                                        }else{
                                            checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                        }
                                    }else{
                                        if(finishAfterWrongAnswer){
                                            goToLastPage(e);
                                        }
                                    }
                                }, explanationTime * 1000);
                            }else{
                                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }else{
                                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                                    }
                                }else{
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }
                                }
                            }
                            if((wrong_answer_sound)){
                                resetPlaying([right_answer_sound, wrong_answer_sound]);
                                setTimeout(function(){
                                    wrong_answer_sound.play();
                                }, 10);
                            }
                        }
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').attr('disabled', true);
                        $(e.target).parents('div[data-question-id]').find('input[name^="ays_questions"]').off('change');
                        $(e.target).parents('div[data-question-id]').find('.ays-field').css({
                            'pointer-events': 'none'
                        });

                    }else if(checked_inputs.attr('type') === "checkbox"){
                        checked_inputs = $(e.target);
                        if (checked_inputs.length === 1) {
                            checked_inputs.parent().addClass('checked_answer_div');
                            if(checked_inputs.prev().val() == 1){
                                if((right_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        right_answer_sound.play();
                                    }, 10);
                                }
                                checked_inputs.parent().addClass('correct_div');
                                checked_inputs.nextAll().addClass('correct answered');
                            }else{
                                if((wrong_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        wrong_answer_sound.play();
                                    }, 10);
                                }
                                if(finishAfterWrongAnswer){
                                    goToLastPage(e);
                                }
                                checked_inputs.parent().addClass('wrong_div');
                                checked_inputs.nextAll().addClass('wrong answered');
                            }
                        }else{
                            for (var i = 0; i < checked_inputs.length; i++) {
                                if(checked_inputs.eq(i).prev().val() == 1){
                                    if((right_answer_sound)){
                                        resetPlaying([right_answer_sound, wrong_answer_sound]);
                                        setTimeout(function(){
                                            right_answer_sound.play();
                                        }, 10);
                                    }
                                    checked_inputs.eq(i).nextAll().addClass('correct answered');
                                    checked_inputs.eq(i).parent().addClass('correct_div');
                                    checked_inputs.eq(i).parent().addClass('checked_answer_div');
                                }else{
                                    if((wrong_answer_sound)){
                                        resetPlaying([right_answer_sound, wrong_answer_sound]);
                                        setTimeout(function(){
                                            wrong_answer_sound.play();
                                        }, 10);
                                    }
                                    if(finishAfterWrongAnswer){
                                        goToLastPage(e);
                                    }
                                    checked_inputs.eq(i).parent().addClass('checked_answer_div');
                                    checked_inputs.eq(i).nextAll().addClass('wrong answered');
                                    checked_inputs.eq(i).parent().addClass('wrong_div');
                                }
                            }
                            if(checked_inputs.eq(i).prev().val() == 1){
                                checked_inputs.eq(i).next().addClass('correct answered');
                                if((right_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        right_answer_sound.play();
                                    }, 10);
                                }
                            }else{
                                checked_inputs.eq(i).next().addClass('wrong answered');
                                if((wrong_answer_sound)){
                                    resetPlaying([right_answer_sound, wrong_answer_sound]);
                                    setTimeout(function(){
                                        wrong_answer_sound.play();
                                    }, 10);
                                }
                            }

                        }
                        $(e.target).attr('disabled', true);
                        $(e.target).off('change');
                    }
                }
            }else{
                if($(e.target).attr('type') === 'radio') {
                    $(e.target).parents('.ays-quiz-answers').find('.checked_answer_div').removeClass('checked_answer_div');
                    $(e.target).parents('.ays-field').addClass('checked_answer_div');
                }
                if($(e.target).attr('type') === 'checkbox') {
                    if(!$(e.target).parents('.ays-field').hasClass('checked_answer_div')){
                        $(e.target).parents('.ays-field').addClass('checked_answer_div');
                    }else{
                        $(e.target).parents('.ays-field').removeClass('checked_answer_div');
                    }
                }
                var checked_inputs = $(e.target).parents().eq(1).find('input:checked');
                if (checked_inputs.parents('div[data-question-id]').find('input.ays_next').hasClass('ays_display_none') &&
                    checked_inputs.parents('div[data-question-id]').find('i.ays_next_arrow').hasClass('ays_display_none')) {
                    if (checked_inputs.attr('type') === 'radio') {
                        checked_inputs.parents('div[data-question-id]').find('input.ays_next').trigger('click');
                    }
                }
            }
        });

        _this.$el.find('button.ays_check_answer').on('click', function (e) {
            var thisAnswerOptions;
            var quizContainer = _this.$el;
            var right_answer_sound = quizContainer.find('.ays_quiz_right_ans_sound').get(0);
            var wrong_answer_sound = quizContainer.find('.ays_quiz_wrong_ans_sound').get(0);
            var questionId = $(this).parents('.step').data('questionId');
            var finishAfterWrongAnswer = (_this.dbOptions.finish_after_wrong_answer && _this.dbOptions.finish_after_wrong_answer == "on") ? true : false;
            thisAnswerOptions = _this.QuizQuestionsOptions[questionId];
            if($(this).parent().find('.ays-text-input').val() !== ""){
                if ($(e.target).parents('form[id^="ays_finish_quiz"]').hasClass('enable_correction')) {
                    if($(e.target).parents('.step').hasClass('not_influence_to_score')){
                        return false;
                    }
                    $(this).css({
                        animation: "bounceOut .5s",
                    });
                    setTimeout(function(){
                        $(e.target).parent().find('.ays-text-input').css('width', '100%');
                        $(e.target).css("display", "none");
                    },480);
                    $(e.target).parent().find('.ays-text-input').css('background-color', '#eee');
                    $(this).parent().find('.ays-text-input').attr('disabled', 'disabled');
                    $(this).attr('disabled', 'disabled');
                    $(this).off('change');
                    $(this).off('click');

                    var input = $(this).parent().find('.ays-text-input');
                    var type = input.attr('type');
                    var userAnsweredText = input.val().trim();

                    var trueAnswered = false;
                    var thisQuestionAnswer = thisAnswerOptions.question_answer.toLowerCase();

                    if(type == 'date'){
                        var correctDate = new Date(thisAnswerOptions.question_answer),
                            correctDateYear = correctDate.getFullYear(),
                            correctDateMonth = correctDate.getMonth(),
                            correctDateDay = correctDate.getDate();
                        var userDate = new Date(userAnsweredText),
                            userDateYear = userDate.getFullYear(),
                            userDateMonth = userDate.getMonth(),
                            userDateDay = userDate.getDate();
                        if(correctDateYear == userDateYear && correctDateMonth == userDateMonth && correctDateDay == userDateDay){
                            trueAnswered = true;
                        }
                    }else if(type != 'number'){
                        thisQuestionAnswer = thisQuestionAnswer.split('%%%');
                        for(var i = 0; i < thisQuestionAnswer.length; i++){
                            if(userAnsweredText.toLowerCase() == thisQuestionAnswer[i].trim()){
                                trueAnswered = true;
                                break;
                            }
                        }
                    }else{
                        if(userAnsweredText.toLowerCase() == thisQuestionAnswer.trim()){
                            trueAnswered = true;
                        }
                    }

                    if(trueAnswered){
                        if((right_answer_sound)){
                            resetPlaying([right_answer_sound, wrong_answer_sound]);
                            setTimeout(function(){
                                right_answer_sound.play();
                            }, 10);
                        }
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(39,174,96,0.5)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(1);
                    }else{
                        if((wrong_answer_sound)){
                            resetPlaying([right_answer_sound, wrong_answer_sound]);
                            setTimeout(function(){
                                wrong_answer_sound.play();
                            }, 10);
                        }
                        $(this).parent().find('.ays-text-input').css('background-color', 'rgba(243,134,129,0.8)');
                        $(this).parent().find('input[name="ays_answer_correct[]"]').val(0);
                        var rightAnswerText = '<div class="ays-text-right-answer">';

                        if(type == 'date'){
                            var correctDate = new Date(thisAnswerOptions.question_answer),
                                correctDateYear = correctDate.getFullYear(),
                                correctDateMonth = (correctDate.getMonth() + 1) < 10 ? "0"+(correctDate.getMonth() + 1) : (correctDate.getMonth() + 1),
                                correctDateDay = (correctDate.getDate() < 10) ? "0"+correctDate.getDate() : correctDate.getDate();
                            rightAnswerText += [correctDateMonth, correctDateDay, correctDateYear].join('/');
                        }else if(type != 'number'){
                            rightAnswerText += thisQuestionAnswer[0];
                        }else{
                            rightAnswerText += thisQuestionAnswer;
                        }

                        rightAnswerText += '</div>';
                        $(this).parents('.ays-quiz-answers').append(rightAnswerText);
                        $(this).parents('.ays-quiz-answers').find('.ays-text-right-answer').slideDown(500);
                        if(finishAfterWrongAnswer){
                            goToLastPage(e);
                        }
                    }
                }
            }
        });
    }

    AysSurveyPlugin.prototype.makeSurveyStyleInput = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'question-input-box">'+
            '<div class="' + _this.htmlClassPrefix + 'input-underline"></div>'+
            '<div class="' + _this.htmlClassPrefix + 'input-underline-animation"></div>'+
        '</div>';
        html = $( html );
        
        html.insertAfter( input );
        
        html.prepend( input );
        
        input.addClass( _this.htmlClassPrefix + 'input' );
        input.addClass( _this.htmlClassPrefix + 'question-input' );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleCheckbox = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'answer-label-content">'+
            '<div class="' + _this.htmlClassPrefix + 'answer-icon-content">'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-ink"></div>'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-1">'+
                    '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-2">'+
                        '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-3"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<span class=""></span>'+
        '</div>';
        html = $( html );
        
        var inputCheckboxLabel = input.parents('label');
        var inputCheckbox = input;
        
        inputCheckboxLabel.addClass('ays-survey-answer-label');
        html.find('span').text( inputCheckboxLabel.text() );
        inputCheckboxLabel.text('');
        input.remove();
        inputCheckboxLabel.prepend( inputCheckbox );
        html.insertAfter( inputCheckbox );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleRadio = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'answer-label-content">'+
            '<div class="' + _this.htmlClassPrefix + 'answer-icon-content">'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-ink"></div>'+
                '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-1">'+
                    '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-2">'+
                        '<div class="' + _this.htmlClassPrefix + 'answer-icon-content-3"></div>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<span class=""></span>'+
        '</div>';
        html = $( html );
        
        var inputCheckboxLabel = input.parents('label');
        var inputCheckbox = input;
        
        inputCheckboxLabel.addClass('ays-survey-answer-label');
        html.find('span').text( inputCheckboxLabel.text() );
        inputCheckboxLabel.text('');
        input.remove();
        inputCheckboxLabel.prepend( inputCheckbox );
        html.insertAfter( inputCheckbox );
    }

    AysSurveyPlugin.prototype.makeSurveyStyleSubmit = function( input ) {
        var _this = this;

        var html = '<div class="' + _this.htmlClassPrefix + 'section-buttons">'+
            '<div class="' + _this.htmlClassPrefix + 'section-button-container">'+
                '<div class="' + _this.htmlClassPrefix + 'section-button-content">'+
                '</div>'+
            '</div>'+
        '</div>';
        html = $( html );
        
        html.insertAfter( input );
        
        html.find('.' + _this.htmlClassPrefix + 'section-button-content').append( input );

        input.addClass('ays-survey-section-button');
        input.removeClass('button button-primary');
    }


    /**
     * @return {string}
     */
    AysSurveyPlugin.prototype.GetFullDateTime = function() {
        var now = new Date();
        var strDateTime = [[now.getFullYear(), AddZero(now.getMonth() + 1), AddZero(now.getDate())].join("-"), [AddZero(now.getHours()), AddZero(now.getMinutes()), AddZero(now.getSeconds())].join(":")].join(" ");
        return strDateTime;
    }

    /**
     * @return {string}
     */
    AysSurveyPlugin.prototype.AddZero = function (num) {
        return (num >= 0 && num < 10) ? "0" + num : num + "";
    }

    /**
     * @return {boolean}
     */
    AysSurveyPlugin.prototype.validatePhoneNumber = function (input) {
      	var phoneno = /^[+ 0-9-]+$/;
      	if (input.value.match(phoneno)) {
      		  return true;
      	} else {
      		  return false;
      	}
    }

    AysSurveyPlugin.prototype.sendSurveyData = function(data, element){
        var _this = this;
        if(typeof _this.sendSurveyData.counter == 'undefined'){
            _this.sendSurveyData.counter = 0;
        }
        if(window.navigator.onLine){
            _this.sendSurveyData.counter++;
            $.ajax({
                url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response){
                    if(response.status === true){
                        _this.doSurveyResult(response);
                    }else{
                        if(_this.sendSurveyData.counter >= 5){
                            swal.fire({
                                type: 'error',
                                html: aysSurveyLangObj.sorry + ".<br>" + aysSurveyLangObj.unableStoreData + "."
                            });
                            // _this.goQuizFinishPage(element);
                        }else{
                            if(window.navigator.onLine){
                                setTimeout(function(){
                                    _this.sendSurveyData(data, element);
                                },3000);
                            }else{
                                _this.sendSurveyData(data, element);
                            }
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    if(_this.sendSurveyData.counter >= 5){
                        swal.fire({
                            type: 'error',
                            html: aysSurveyLangObj.sorry + ".<br>" + aysSurveyLangObj.unableStoreData + "."
                        });
                        // _this.goQuizFinishPage(element);
                    }else{
                        setTimeout(function(){
                            _this.sendSurveyData(data, element);
                        },3000);
                    }
                }
            });
        }else{
            swal.fire({
                type: 'warning',
                html: aysSurveyLangObj.connectionLost + ".<br>" + aysSurveyLangObj.checkConnection + "."
            });
            _this.sendSurveyData.counter = 0;
            // _this.goQuizFinishPage(element);
        }
    }

    AysSurveyPlugin.prototype.goQuizFinishPage = function(element){
        var _this = this;
        var currentFS = _this.$el.find('.step.active-step');
        var next_sibilings_count = _this.$el.find('.ays_question_count_per_page').val();
        if (parseInt(next_sibilings_count) > 0 &&
            (element.parents('.step').attr('data-question-id') ||
             element.parents('.step').next().attr('data-question-id'))) {
            currentFS = _this.$el.find('div[data-question-id]');
        }
        currentFS.prev().css('display', 'flex');
        _this.aysAnimateStep(_this.$el.data('questEffect'), currentFS, currentFS.prev());
        // currentFS.animate({opacity: 0}, {
        //     step: function(now, mx) {
        //         options.scale = 1 - (1 - now) * 0.2;
        //         options.left = (now * 50)+"%";
        //         options.opacity = 1 - now;
        //         currentFS.css({
        //             'transform': 'scale('+options.scale+')',
        //             'position': '',
        //             'pointer-events': 'none'
        //         });
        //         currentFS.prev().css({
        //             'left': options.left,
        //             'opacity': options.opacity,
        //             'pointer-events': 'none'
        //         });
        //     },
        //     duration: 800,
        //     complete: function(){
        //         currentFS.hide();
        //         currentFS.css({
        //             'opacity': '1',
        //             'pointer-events': 'auto',
        //         });
        //         currentFS.prev().css({
        //             'transform': 'scale(1)',
        //             'position': 'relative',
        //             'opacity': '1',
        //             'pointer-events': 'auto'
        //         });
        //         options.animating = false;
        //     },
        //     easing: 'easeInOutBack'
        // });
        if(_this.dbOptions.enable_correction == 'on'){
            if(currentFS.prev().find('input:checked').length > 0){
                currentFS.prev().find('.ays-field input').attr('disabled', 'disabled');
                currentFS.prev().find('.ays-field input').on('click', function(){
                    return false;
                });
                currentFS.prev().find('.ays-field input').on('change', function(){
                    return false;
                });
            }
            if(currentFS.prev().find('option:checked').length > 0){
                currentFS.prev().find('.ays-field select').attr('disabled', 'disabled');
                currentFS.prev().find('.ays-field select').on('click', function(){
                    return false;
                });
                currentFS.prev().find('.ays-field select').on('change', function(){
                    return false;
                });
            }
            if(currentFS.prev().find('textarea').length > 0){
                if(currentFS.prev().find('textarea').val() !== ''){
                    currentFS.prev().find('.ays-field textarea').attr('disabled', 'disabled');
                    currentFS.prev().find('.ays-field textarea').on('click', function(){
                        return false;
                    });
                    currentFS.prev().find('.ays-field textarea').on('change', function(){
                        return false;
                    });
                }
            }
        }
    }

    AysSurveyPlugin.prototype.doSurveyResult = function( response ){
        var _this = this;
        if( response.status ){
            // var formResultsContainer = _this.$el.find('.' + _this.htmlClassPrefix + 'results-content');
            var formResults = _this.$el.find('.' + _this.htmlClassPrefix + 'thank-you-page');
            if (_this.$el.hasClass('enable_questions_result')) {

            }

            var aysQuizLoader = _this.$el.find('div[data-role="loader"]');
            aysQuizLoader.addClass('ays-survey-loader');
            aysQuizLoader.removeClass(aysQuizLoader.data('class'));
            _this.$el.find('.ays-survey-loader-with-text').hide();
            _this.$el.find('.ays-survey-loader-with-custom-gif').hide();
            if( response.message ){
                formResults.prepend( $( '<div>' + response.message + '</div>' ) );
            }

            if( response.socialHeading ){
                _this.$el.find(".ays-survey-social-shares-heading").append(response.socialHeading);
            }

            if( _this.dbOptions[ _this.dbOptionsPrefix + 'redirect_after_submit' ] ){
                if(!( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] )){
                     _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] = 0;
                }
                
                if( parseInt( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] ) > 0 ){
                    if( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_url' ] != '' ){
                        var redirectAfterSubmitTimerHTML = '<div class="' + _this.htmlClassPrefix + 'redirect-timer ' + _this.htmlClassPrefix + 'countdown-timer">' + 
                            aysSurveyLangObj.redirectAfter + ' <span class="' + _this.htmlClassPrefix + 'countdown-time">' + _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_seconds' ] + '</span>' +
                        '</div>';
                        formResults.prepend( $( redirectAfterSubmitTimerHTML ) );
                        var timer = parseInt( _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_delay' ] ) + 2;
                        _this.timer( timer,  {
                            blockedContent: true,
                            redirectUrl: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_url' ],
                            redirectNewTab: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_new_tab' ]
                        });
                    }
                }
                else{

                    _this.timer( timer,  {
                        blockedContent: true,
                        redirectUrl: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_url' ],
                        redirectNewTab: _this.dbOptions[ _this.dbOptionsPrefix + 'submit_redirect_new_tab' ]
                    });
                }
            }

            formResults.css({'display':'block'});
        }else{

        }
    }

    AysSurveyPlugin.prototype.aysResetQuiz = function ($quizContainer){
        var cont = $quizContainer.find('div[data-question-id]');
        cont.find('input[type="text"], textarea, input[type="number"], input[type="url"], input[type="email"]').each(function(){
            $(this).val('');
        });
        cont.find('select').each(function(){
            $(this).val('');
        });
        cont.find('select.ays-select').each(function(){
            $(this).val(null).trigger('change');
        });
        cont.find('select option').each(function(){
            $(this).removeAttr('selected');
        });
        cont.find('input[type="radio"], input[type="checkbox"]').each(function(){
            $(this).removeAttr('checked');
        });
    }

    AysSurveyPlugin.prototype.aysAutofillData = function (data, currentEl){
        $.ajax({
            url: window.aysSurveyMakerAjaxPublic.ajaxUrl,
            method: 'post',
            dataType: 'json',
            data: data,
            success: function (response) {
                if(response !== null){
                    if( response.data ){
                        currentEl.find(".ays-survey-question[data-type='name'] input.ays-survey-question-input").val(response.data.display_name);
                        currentEl.find(".ays-survey-question[data-type='email'] input.ays-survey-question-email-input").val(response.data.user_email);
                    }
                } //:last-of-type
            }
        });
    }

    AysSurveyPlugin.prototype.checkMinVotes = function( item ){
        var _this = this;
        var options;
        var questionId = item.find('.' + _this.htmlClassPrefix + 'question-id').val();
        options = _this.dbOptions[ _this.dbOptionsPrefix + 'checkbox_options' ][questionId];
        if(options.enable_max_selection_count){
            var allVotesCount = item.find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]').length;
            var checkedCount = item.find('.' + _this.htmlClassPrefix + 'answer input[type="checkbox"][name^="' + _this.htmlClassPrefix + 'answers"]:checked').length;
            var MinVotes = options.min_selection_count;
            var MaxVotes = options.max_selection_count;
            if(MinVotes > MaxVotes){
                MinVotes = MaxVotes;
            }
            if(MinVotes > allVotesCount){
                MinVotes = allVotesCount;
            }
            var errorMessage = '';
            if(MinVotes <= checkedCount){
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').hide();
            }
            if(MinVotes > checkedCount){
                errorMessage += '<img src="' + aysSurveyMakerAjaxPublic.warningIcon + '" alt="error">';
                errorMessage += '<span>' + aysSurveyLangObj.minimumVotes + ' ' + MinVotes + '</span>';
                item.addClass('ays-has-error');
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').html(errorMessage);
                item.find('.' + _this.htmlClassPrefix + 'votes-count-validation-error').show();
                _this.goToTop( item );
                return false;
            }
            else{
                return true;
            }
        }
        return true;
    }

    AysSurveyPlugin.prototype.toggleFullscreen = function (elem) {
        var _this = this;
        elem = elem || document.documentElement;
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
            _this.aysSurveyFullScreenActivate( elem );
            _this.aysSurveyFullScreenWindowActivator( elem );
        }else{
            _this.aysSurveyFullScreenDeactivate( elem );
            _this.aysSurveyFullScreenWindowDeactivator();
        }
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenActivate = function (elem) {
        $(elem).css({'background-color':'#fff'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-close-full-screen').css({'display':'inline'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-open-full-screen').css('display','none');
        $(elem).find('.ays-survey-full-screen-mode').css({'padding-right':'15px'});
        $(elem).css({'overflow':'auto'});
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenDeactivate = function (elem) {
        $(elem).css({'background-color':'initial'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-open-full-screen').css({'display':'inline'});
        $(elem).find('.ays-survey-full-screen-mode .ays-survey-close-full-screen').css('display','none');        
        $(elem).find('.ays-survey-full-screen-mode').css({'padding-right':'0'});
        $(elem).css({'overflow':'initial'});
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenDeactivateAll = function (elem) {
        var _this = this;
        document.addEventListener('fullscreenchange', function(event) {
            if (!document.fullscreenElement) {
                var eventTarget = event.target;
                if( $( eventTarget ).hasClass('ays-survey-container') ){
                    _this.aysSurveyFullScreenDeactivate( eventTarget );
                }
            }
        }, false);
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenWindowActivator = function (elem) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        }else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        }else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    }

    AysSurveyPlugin.prototype.aysSurveyFullScreenWindowDeactivator = function () {
        if(document.exitFullscreen) {
            document.exitFullscreen();
        }else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        }else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }

    AysSurveyPlugin.prototype.aysSurveyCheckTextLimit = function(question, options,e,current) {
        var $this = current;
        var _this = this;
        var box = question.find('.ays-survey-question-text-message');
        var questionTextMessage = question.find('.ays-survey-question-text-message-span');
        // Maximum length of a text field
        var enable_question_text_max_length = (options.enable_word_limitation && options.enable_word_limitation != "") ? options.enable_word_limitation : false;
        // Length
        var question_text_max_length = (options.limit_length && options.limit_length != "") ? parseInt(options.limit_length) : '';
        // Limit by
        var question_limit_text_type = (options.limit_by && options.limit_by != "") ? options.limit_by : '';
        // Show word/character counter
        var question_enable_text_message = (options.limit_counter && options.limit_counter != '') ? options.limit_counter : false;

        var remainder = '';
        if(question_text_max_length != '' && question_text_max_length != 0){
            switch ( question_limit_text_type ) {
                case 'char':
                    var tval = $this.val();
                    var tlength = tval.length;
                    var set = question_text_max_length;
                    var remain = parseInt(set - tlength);
                    if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
                        $this.val((tval).substring(0, tlength - 1));
                    }
                    if (e.type=="keyup") {
                        var tval = $this.val().trim();
                        if(tval.length > 0 && tval != null){
                            var wordsLength = $this[0].value.split('').length;
                            if (wordsLength > question_text_max_length) {
                                var trimmed = tval.split('', question_text_max_length).join("");
                                $this.val(trimmed);
                            }
                        }
                    }
                    remainder = remain;
                    break;
                case 'word':
                    if (e.type=="keyup") {
                        var tval = $this.val().trim();
                        if(tval.length > 0 && tval != null){
                            var wordsLength = $this[0].value.match(/\S+/g).length;
                            if (wordsLength > question_text_max_length) {
                                var trimmed = tval.split(/\s+/, question_text_max_length).join(" ");
                                $this.val(trimmed + " ");
                            }
                            remainder = question_text_max_length - wordsLength;
                        }
                    }
                    break;
                default:
                    break;
            }
            if (e.type=="keyup") {
                if ( question_enable_text_message ) {
                    if(question_text_max_length != '' && question_text_max_length != 0){
                        if (remainder <= 0) {
                            remainder = 0;
                            if (! box.hasClass(_this.htmlClassPrefix + ' question-text-error-message') ) {
                                box.addClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }else{
                            if ( box.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                box.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }
                        if (tval.length == 0 || tval == null) {
                            if ( box.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                box.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                            remainder = question_text_max_length;
                        }

                        questionTextMessage.html( remainder );
                    }
                }
            }
        }
    }
    
    AysSurveyPlugin.prototype.aysSurveyCheckNumberLimit = function(question, options,e,current) {
        var $this = current;
        var _this = this;
        var errorBox = question.find('.ays-survey-number-limit-message-box');
        var limitBox = question.find('.ays-survey-question-text-message');
        var questionTextMessage = question.find('.ays-survey-question-text-message-span');
        // Enable Limitation
        var enableQuestionLimit = (options.enable_number_limitation && options.enable_number_limitation != "") ? options.enable_number_limitation : false;
        // Min Value
        var questionTextMinValue = (options.number_min_selection && options.number_min_selection != "") ? parseInt(options.number_min_selection) : '';
        // Max Value
        var questionTextMaxValue = (options.number_max_selection && options.number_max_selection != "") ? parseInt(options.number_max_selection) : '';
        // Show error message
        var enableTextMessage = (options.enable_number_error_message && options.enable_number_error_message != '') ? options.enable_number_error_message : false;
        // Max Length
        var questionTextMaxLength = (options.number_limit_length && options.number_limit_length != "") ? parseInt(options.number_limit_length) : '';
        // Show char length
        var questionEnableTextMessage = (options.enable_number_limit_counter && options.enable_number_limit_counter != '') ? options.enable_number_limit_counter : false;
        if(questionTextMinValue >= questionTextMaxValue){
            questionTextMaxValue = questionTextMinValue;
        }
        if(enableQuestionLimit){
            var remainder = '';
            if(questionTextMaxLength != '' && questionTextMaxLength != 0){
                var tval = $this.val();
                var tlength = tval.length;
                var set = questionTextMaxLength;
                var remain = parseInt(set - tlength);
                if (remain <= 0 && e.which !== 0 && e.charCode !== 0) {
                    $this.val((tval).substring(0, tlength - 1));
                }
                if (e.type=="keyup") {
                    var tval = $this.val().trim();
                    if(tval.length > 0 && tval != null){
                        var wordsLength = $this[0].value.split('').length;
                        if (wordsLength > questionTextMaxLength) {
                            var trimmed = tval.split('', questionTextMaxLength).join("");
                            $this.val(trimmed);
                        }
                    }
                }
                remainder = remain;
            }
        
            var enteredValue = $this.val().trim();
            var inputValue = parseInt( enteredValue );
            if(questionTextMinValue != '' && questionTextMinValue != 0){
                if( ! isNaN(inputValue) ){
                    if (e.type=="keyup") {
                        if ( inputValue < questionTextMinValue ) {
                            $this.val(questionTextMinValue);
                        }
                    }
                }
            }
            if(questionTextMaxValue != '' && questionTextMaxValue != 0){
                if (e.type=="keyup") {
                    if ( inputValue > questionTextMaxValue ) {
                        $this.val(questionTextMaxValue);
                    }
                }
            }
            if (e.type=="keyup") {
                if ( questionEnableTextMessage ) {
                    if(questionTextMaxLength != '' && questionTextMaxLength != 0){
                        if (remainder <= 0) {
                            remainder = 0;
                            if (! limitBox.hasClass(_this.htmlClassPrefix + ' question-text-error-message') ) {
                                limitBox.addClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }else{
                            if ( limitBox.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                limitBox.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                        }
                        if (tval.length == 0 || tval == null) {
                            if ( limitBox.hasClass(_this.htmlClassPrefix + 'question-text-error-message') ) {
                                limitBox.removeClass(_this.htmlClassPrefix + 'question-text-error-message')
                            }
                            remainder = questionTextMaxLength;
                        }

                        questionTextMessage.html( remainder );
                    }
                }
            }
            if(enableTextMessage){
                errorBox.show();
            }
        }
    }
    
    AysSurveyPlugin.prototype.aysSurveyonTabPress = function() {
        var _this = this;
        _this.$el.on('keydown', (e) => {
            var selectedBox = $(e.target);
            if( !selectedBox.hasClass( 'ays-survey-answer-text-inputs' ) ){
                if (e.keyCode === 32) {  
                    e.preventDefault();
                    if(selectedBox.hasClass('ays-survey-section-button-container')){
                        selectedBox.find('.ays-survey-section-button').trigger("click");
                    }
                    else{
                        selectedBox.trigger("click");
                    }
                }
            }
        });
    }
    
    /**
     * @return {string}
     */
    function AddZero(num) {
        return (num >= 0 && num < 10) ? "0" + num : num + "";
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

    function validatePhoneNumber(input) {
        var phoneno = /^[+ 0-9-]+$/;
        if (input.value.match(phoneno)) {
            return true;
        } else {
            return false;
        }
    }


    $.fn.serializeFormJSON = function () {
        var o = {},
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

    $.fn.AysSurveyMaker = function(options) {
        return this.each(function() {
            if (!$.data(this, 'AysSurveyMaker')) {
                $.data(this, 'AysSurveyMaker', new AysSurveyPlugin(this, options));
            } else {
                try {
                    $(this).data('AysSurveyMaker').init();
                } catch (err) {
                    console.error('AysSurveyMaker has not initiated properly');
                }
            }
        });
    };

})(jQuery);
