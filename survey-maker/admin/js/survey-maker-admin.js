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

    $.fn.goToTop = function() {
        this.get(0).scrollIntoView({
            block: "center",
            behavior: "smooth"
        });
    }

    $.fn.goTo = function() {
        $('html, body').animate({
            scrollTop: this.offset().top - 200 + 'px'
        }, 'fast');
        return this; // for chaining...
    }

    $.fn.goToNormal = function() {
        $('html, body').animate({
            scrollTop: this.offset().top - 200 + 'px'
        }, 'normal');
        return this; // for chaining...
    }

	$(document).ready(function () {

        setInterval(function () {
            $('div.ays-quiz-maker-wrapper h1 i.ays_fa').toggleClass('pulse');
            $(document).find('.ays_heart_beat i.ays_fa').toggleClass('ays_pulse');
        }, 1000);


        // Disabling submit when press enter button on inputing
        $(document).on("input", 'input', function(e){
            if(e.keyCode == 13){
                if($(document).find("#ays-survey-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }
        });

        $(document).on("keydown", function(e){
            if(e.target.nodeName == "TEXTAREA"){
                return true;
            }
            if(e.keyCode == 13){
                if($(document).find("#ays-survey-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }

            if ( ( e.ctrlKey && e.which == 83 ) && !( e.which == 19 ) ){
                var saveButton = $(document).find("input#ays-button-apply, input.ays-survey-gen-settings-save");
                if( saveButton.length > 0 ){
                    e.preventDefault();
                    saveButton.trigger("click");
                }
                return false;
            }

            if(e.keyCode === 27){
                $(document).find('.ays-modal').aysModal('hide');
                return false;
            }
        });

        var answerAddedByEnter = false;
        $(document).on("keyup", ".ays-survey-answers-conteiner .ays-survey-answer-box input.ays-survey-input", function(e){
            answerAddedByEnter = false;
        });

        $(document).on("focus", ".ays-survey-answers-conteiner .ays-survey-answer-box input.ays-survey-input", function(e){
            var _this = $(this);
            setTimeout(function(){
                _this.get(0).setSelectionRange(0, _this.get(0).value.length);
                // _this.select();
            }, 1);
        });

        $(document).on("keydown", ".ays-survey-answers-conteiner .ays-survey-answer-box input.ays-survey-input", function(e){
            if( answerAddedByEnter === false ) {
                answerAddedByEnter = true;
                
                if(e.keyCode === 13){
                    aysSurveyAddAnswer( $(this), true );
                }

                if(e.keyCode == 38 && !e.ctrlKey && !e.shiftKey ){
                    var ansCont = $(this).parents('.ays-survey-answer-row');
                    if( ansCont.prev().length > 0 ){
                        ansCont.prev().find(".ays-survey-answer-box input.ays-survey-input").trigger('focus');
                    }else{
                        return false;
                    }
                }

                if(e.keyCode === 40 && !e.ctrlKey && !e.shiftKey ){
                    var ansCont = $(this).parents('.ays-survey-answer-row');
                    if( ansCont.next().length > 0 ){
                        ansCont.next().find(".ays-survey-answer-box input.ays-survey-input").trigger('focus');
                    }else{
                        var questCont = $(this).parents('.ays-survey-question-answer-conteiner');
                        questCont.find('.ays-survey-action-add-answer').trigger('click');
                    }
                }

                if(e.keyCode === 8){
                    if( $(this).val() == '' ){
                        var thatNext = $(this).parents('.ays-survey-answer-row').next().find('input.ays-survey-input');
                        var thatPrev = $(this).parents('.ays-survey-answer-row').prev().find('input.ays-survey-input');
                        if( thatPrev.length > 0 ){
                            thatPrev.trigger('focus');
                            $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                        }else if( thatNext.length > 0 ){
                            thatNext.trigger('focus');
                            $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                        }
                        return false;
                    }
                }

                if(e.keyCode === 46){
                    if( $(this).val() == '' ){
                        var thatNext = $(this).parents('.ays-survey-answer-row').next().find('input.ays-survey-input');
                        var thatPrev = $(this).parents('.ays-survey-answer-row').prev().find('input.ays-survey-input');
                        if( thatNext.length > 0 ){
                            thatNext.trigger('focus');
                            $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                        }else if( thatPrev.length > 0 ){
                            thatPrev.trigger('focus');
                            $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-delete').trigger('click');
                        }
                        return false;
                    }
                }
            }
        });

        // Notifications dismiss button
        var $html_name_prefix = 'ays_';
        $(document).on('click', '.notice-dismiss', function (e) {
            changeCurrentUrl('status');
        });

        if(location.href.indexOf('del_stat')){
            setTimeout(function(){
                changeCurrentUrl('del_stat');
                changeCurrentUrl('mcount');
            }, 500);
        }

        var aysSurveyTextArea = $(document).find('textarea.ays-survey-question-input-textarea');
        autosize(aysSurveyTextArea);
        var aysSurveyDescriptionTextArea = $(document).find('textarea.ays-survey-section-description');
        autosize(aysSurveyDescriptionTextArea);


        function changeCurrentUrl(key){
            var linkModified = location.href.split('?')[1].split('&');
            for(var i = 0; i < linkModified.length; i++){
                if(linkModified[i].split("=")[0] == key){
                    linkModified.splice(i, 1);
                }
            }
            linkModified = linkModified.join('&');
            window.history.replaceState({}, document.title, '?'+linkModified);
        }

        // Quiz toast close button
        jQuery('.quiz_toast__close').click(function(e){
            e.preventDefault();
            var parent = $(this).parent('.quiz_toast');
            parent.fadeOut("slow", function() { $(this).remove(); } );
        });
        
        var toggle_ddmenu = $(document).find('.toggle_ddmenu');
        toggle_ddmenu.on('click', function () {
            var ddmenu = $(this).next();
            var state = ddmenu.attr('data-expanded');
            switch (state) {
                case 'true':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(0deg)'
                    });
                    ddmenu.attr('data-expanded', 'false');
                    break;
                case 'false':
                    $(this).find('.ays_fa').css({
                        transform: 'rotate(90deg)'
                    });
                    ddmenu.attr('data-expanded', 'true');
                    break;
            }
        });

        var ays_results = $(document).find('.ays_result_read');
        for (var i in ays_results) {
            if (typeof ays_results.eq(i).val() != 'undefined') {
                if ( ays_results.eq(i).val() == 0 || ays_results.eq(i).val() == 2 ) {
                    ays_results.eq(i).parents('tr').addClass('ays_read_result');
                }
            }
        }
        
        // $('[data-toggle="popover"]').popover();
        
        $(document).find('.ays-survey-sections-conteiner [data-toggle="popover"]').popover();
        $(document).find('.aysFormeditorViewFatRoot [data-toggle="popover"]').popover();
        $(document).find('.ays_survey_previous_next [data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();

        $(document).find('.ays_survey_aysDropdown').aysDropdown();
        $(document).find('[data-toggle="dropdown"]').dropdown();
        // $('.dropdown-toggle').dropdown()

        // Disabling submit when press enter button on inputing
        $(document).on("input", 'input', function(e){
            if(e.keyCode == 13){
                if($(document).find("#ays-question-form").length !== 0 ||
                   $(document).find("#ays-survey-category-form").length !== 0 ||
                   $(document).find("#ays-survey-settings-form").length !== 0){
                    return false;
                }
            }
        });

        $(document).find('strong.ays-survey-shortcode-box.ays_survey_each_sub_user_info_header_button button').on('mouseleave', function(){
            var _this = $(this);

            _this.attr( 'data-original-title', SurveyMakerAdmin.clickForCopy );
            _this.attr( 'title', SurveyMakerAdmin.clickForCopy );
        });

        // Modal close
        $(document).find('.ays-close').on('click', function () {
            $(document).find('.ays-modal').aysModal('hide');
        });


        $(document).on('change', '.ays_toggle_checkbox', function (e) {
            var state = $(this).prop('checked');
            var parent = $(this).parents('.ays_toggle_parent');
            
            if($(this).hasClass('ays_toggle_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_target').show(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_target').hide(250);
                        break;
                }
            }
        });
        
        $(document).on('change', '.ays_toggle_select', function (e) {
            var state = $(this).val();
            var toggle = $(this).data('hide');
            var parent = $(this).parents('.ays_toggle_parent');
            
            if($(this).hasClass('ays_toggle_slide')){
                if (toggle == state) {
                    parent.find('.ays_toggle_target').slideUp(250);
                    parent.find('.ays_toggle_target_inverse').slideDown(150);
                }else{
                    parent.find('.ays_toggle_target').slideDown(150);
                    parent.find('.ays_toggle_target_inverse').slideUp(250);
                }
            }else{
                if (toggle == state) {
                    parent.find('.ays_toggle_target').hide(150);
                    parent.find('.ays_toggle_target_inverse').show(250);
                }else{
                    parent.find('.ays_toggle_target').show(250);
                    parent.find('.ays_toggle_target_inverse').hide(150);
                }
            }
        });


        $(document).find('#ays-category').select2({
            placeholder: 'Select category'
        });

        $(document).find('#ays-status').select2({
            placeholder: 'Select status'
        });


        // Tabulation
        $(document).find('.nav-tab-wrapper a.nav-tab').on('click', function (e) {
            if(! $(this).hasClass('no-js')){
                var elemenetID = $(this).attr('href');
                var active_tab = $(this).attr('data-tab');
                $(document).find('.nav-tab-wrapper a.nav-tab').each(function () {
                    if ($(this).hasClass('nav-tab-active')) {
                        $(this).removeClass('nav-tab-active');
                    }
                });
                $(this).addClass('nav-tab-active');
                $(document).find('.ays-survey-tab-content').each(function () {
                    $(this).css('display', 'none');
                });
                $(document).find("[name='ays_survey_tab']").val(active_tab);
                $('.ays-survey-tab-content' + elemenetID).css('display', 'block');
                e.preventDefault();
            }
        });


        // Survey category form submit
        // Checking the issues
        $(document).find('#ays-survey-category-form').on('submit', function(e){
            var submitFlag = true;
            if($(document).find('#ays-title').val() == ''){
                $(document).find('#ays-title').val('Survey category').trigger('input');
                submitFlag = false;
            }
            
            var $this = $(this)[0];
            if( submitFlag ){
                $this.submit();
            }else{
                // e.preventDefault();
                // $this.submit();
            }
        });

        // Survey form submit
        // Checking the issues
        $(document).find('#ays-survey-form').on('submit', function(e){
            var $this = $(this)[0];
            var submitFlag = true;
            if($(document).find('#ays-survey-title').val() == ''){
                $(document).find('#ays-survey-title').val('Survey').trigger('input');
                // submitFlag = false;
            }

            // $(document).find('.ays-survey-section-title').each(function(){
            //     if( $(this).val() == ''){
            //         $(this).val('Untitled section').trigger('input');
            //         // submitFlag = false;
            //     }
            // });
            
            if( submitFlag ){
                $this.submit();
            }else{
            //     e.preventDefault();
            //     $this.submit();
            }
        });

        // Submit buttons disableing with loader
        $(document).find('.ays-survey-loader-banner').on('click', function () {        
            var $this = $(this);
            submitOnce($this);
        });

        function submitOnce(subButton){
            var subLoader = subButton.parents('div').find('.ays_survey_loader_box');
            if ( subLoader.hasClass("display_none") ) {
                subLoader.removeClass("display_none");
            }
            subLoader.css({
                "padding-left": "8px",
                "display": "inline-block"
            });

            setTimeout(function() {
                $(document).find('.ays-survey-loader-banner').attr('disabled', true);
            }, 10);

            setTimeout(function() {
                $(document).find('.ays-survey-loader-banner').attr('disabled', false);
                subButton.parents('div').find('.ays_survey_loader_box').css('display', 'none');
            }, 5000);

        }

        // Delete confirmation
        $(document).on('click', '.ays_confirm_del', function(e){            
            e.preventDefault();
            var message = $(this).data('message');
            var confirm = window.confirm('Are you sure you want to delete '+message+'?');
            if(confirm === true){
                window.location.replace($(this).attr('href'));
            }
        });


        // $(document).find('.cat-filter-apply-top').on('click', function(e) {
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterby-top"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterby',
        //         value: catFilter
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.cat-filter-apply-bottom').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterby-bottom"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterby',
        //         value: catFilter
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.user-filter-apply-top').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser-top"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterbyuser',
        //         value: catFilter
        //     });
        //     newLink = catFilterForListTable(newLink, {
        //         what: 'ays_survey_tab',
        //         value: 'poststuff'
        //     });
        //     document.location.href = newLink;
        // });

        // $(document).find('.user-filter-apply-bottom').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser-bottom"]').val();
        //     var link = location.href;
        //     var newLink = catFilterForListTable(link, {
        //         what: 'filterbyuser',
        //         value: catFilter
        //     });
        //     newLink = catFilterForListTable(newLink, {
        //         what: 'ays_survey_tab',
        //         value: 'poststuff'
        //     });
        //     document.location.href = newLink;
        // });


        // $(document).find('.user-filter-apply').on('click', function(e){
        //     e.preventDefault();
        //     var catFilter = $(document).find('select[name="filterbyuser"]').val();
        //     var link = location.href;
        //     var linkFisrtPart = link.split('?')[0];
        //     var linkModified = link.split('?')[1].split('&');
        //     alert(catFilter)
        //     for(var i = 0; i < linkModified.length; i++){
        //         if(linkModified[i].split("=")[0] == "wpuser"){
        //             linkModified.splice(i, 1);
        //         }
        //     }
        //     link = linkFisrtPart + "?" + linkModified.join('&');
            
        //     if( catFilter != '' ){
        //         catFilter = "&wpuser="+catFilter;
        //         catFilter = "&ays_survey_tab=poststuff";
        //         document.location.href = link+catFilter;
        //     }else{
        //         document.location.href = link;
        //     }
        // });

        // FILTERS FOR LIST TABEL
        $(document).find('.ays-survey-question-tab-all-filter-button-top, .ays-survey-question-tab-all-filter-button-bottom').on('click', function(e){
            e.preventDefault();
            var $this = $(this);
            var parent = $this.parents('.tablenav');
            var link = location.href;

            var html_name = '';
            var top_or_bottom = 'top';

            if ( parent.hasClass('bottom') ) {
                top_or_bottom = 'bottom';
            }

            var catFilter = $(document).find('select[name="filterby-'+ top_or_bottom +'"]').val();
            var userFilter = $(document).find('select[name="filterbyuser-'+ top_or_bottom +'"]').val();

            if(typeof catFilter != "undefined"){
                link = catFilterForListTable(link, {
                    what: 'filterby',
                    value: catFilter
                });
            }
            if(typeof userFilter != "undefined"){
                link = catFilterForListTable(link, {
                    what: 'filterbyuser',
                    value: userFilter
                });
            }
            if($(this).hasClass('ays-survey-question-filter-each-submission')){
                link = catFilterForListTable(link, {
                            what: 'ays_survey_tab',
                            value: 'poststuff'
                        });
            }
            document.location.href = link;
        });

        function catFilterForListTable(link, options){
            if( options.value != '' ){
                options.value = "&" + options.what + "=" + options.value;
                var linkModifiedStart = link.split('?')[0];
                var linkModified = link.split('?')[1].split('&');
                for(var i = 0; i < linkModified.length; i++){
                    if(linkModified[i].split("=")[0] == options.what){
                        linkModified.splice(i, 1);
                    }
                }
                linkModified = linkModified.join('&');
                return linkModifiedStart + "?" + linkModified + options.value;
            }else{
                var linkModifiedStart = link.split('?')[0];
                var linkModified = link.split('?')[1].split('&');
                for(var i = 0; i < linkModified.length; i++){
                    if(linkModified[i].split("=")[0] == options.what){
                        linkModified.splice(i, 1);
                    }
                }
                linkModified = linkModified.join('&');
                return linkModifiedStart + "?" + linkModified;
            }
        }

        $(document).on('change', '#import_file', function(e){
            var pattern = /(.csv|.xlsx|.json)$/g;
            if(pattern.test($(this).val())){
                $(this).parents('form').find('input[name="ays_survey_import"]').removeAttr('disabled')
            }
        });


        // Add Image 
        $(document).on('click', '.ays-survey-add-image', function (e) {
            openMediaUploaderForImage(e, $(this));
        });

        // Remove Image
        $(document).on('click', '.removeImage', function (e) {
            var $this = $(this);
            $this.parents('.ays-survey-image-container').find('.ays-survey-image-body').fadeOut(500);
            $this.parents('.ays-survey-image-container').find('.ays-survey-add-image').text( SurveyMakerAdmin.addImage );
            setTimeout(function(){
                $(document).find("#ays_survey_logo_enable_image_url").prop("checked" , false);
                $this.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open-close').hide();
                $this.parents('.ays-survey-image-container').parent().find('.ays-survey-logo-open').hide();
                $this.parents('.ays-survey-image-container').find('.ays-survey-img').removeAttr('src');
                $this.parents('.ays-survey-image-container').find('.ays-survey-img-src').val('');
                $(document).find('.ays-survey-logo-url-box').addClass('display_none_not_important');
            }, 500);
        });

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////ARO START///////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        function aysSurveyQuestionSortableHelper( event ) {
            var clone = $(event.target).parents('.ays-survey-question-answer-conteiner').clone(true, true);
            clone.find('.ays-survey-question-image-container').remove();
            clone.find('.ays-survey-other-answer-and-actions-row').remove();
            clone.find('.ays-survey-answers-conteiner').html('<div class="ays-survey-sortable-ect">…</div>');
            return clone;
        }

        var sectionDragHandle = {
            handle: '.ays-survey-section-dlg-dragHandle',
            appendTo: "parent",
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'ays-survey-sortable-placeholder',
            tolerance: "pointer",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            // helper: aysSurveyQuestionSortableHelper,
            sort: function(e, ui){
                ui.placeholder.css('height', ui.helper.height());
            },
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var sections = sortableContainer.find('.ays-survey-section-box');
                sections.each(function(i){
                    $(this).find('.ays-survey-section-ordering').val(i+1);
                    $(this).find('.ays-survey-section-number').text(i+1);
                });
            }
        };

        var questionDragHandle = {
            handle: '.ays-survey-question-dlg-dragHandle',
            appendTo: "parent",
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'ays-survey-sortable-placeholder',
            tolerance: "pointer",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            helper: aysSurveyQuestionSortableHelper,
            connectWith: '.ays-survey-sections-conteiner .ays-survey-section-questions',
            sort: function(e, ui){
                ui.placeholder.css('height', ui.helper.height());
            },
            receive: function( event, ui ){
                var section = $(event.target).parents('.ays-survey-section-box');
                var oldSection = ui.sender.parents('.ays-survey-section-box');
                draggedQuestionUpdate( ui.item, section, oldSection);
            },
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var oldSection = false;
                if(ui.sender != null){
                    oldSection = ui.sender.parents('.ays-survey-section-box');
                }
                var questions = sortableContainer.find('.ays-survey-question-answer-conteiner');
                if( questions.length == 0 ){
                    swal.fire({
                        type: 'warning',
                        text: SurveyMakerAdmin.minimumCountOfQuestions
                    });
                    setTimeout(function(){
                        draggedQuestionUpdate( ui.item, sortableContainer.parents('.ays-survey-section-box'), oldSection );
                    }, 1);
                    return false;
                }
                questions.each(function(i){
                    $(this).find('.ays-survey-question-ordering').val(i+1);
                });
            }
        };

        var answerDragHandle = {
            handle: '.ays-survey-answer-dlg-dragHandle',
            cursor: 'move',
            opacity: 0.8,
            axis: 'y',
            placeholder: 'clone',
            tolerance: "pointer",
            helper: "clone",
            revert: true,
            forcePlaceholderSize: true,
            forceHelperSize: true,
            update: function( event, ui ){
                var sortableContainer = $(event.target);
                var answers = sortableContainer.find('.ays-survey-answer-row');
                answers.each(function(i){
                    $(this).find('.ays-survey-answer-ordering').val(i+1);
                });
            }
        }
        // Answers ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner .ays-survey-answers-conteiner').sortable(answerDragHandle);
        // Question ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner .ays-survey-section-questions').sortable(questionDragHandle);
        // Question ordering jQuery UI
        $(document).find('.ays-survey-sections-conteiner').sortable(sectionDragHandle);

        // Collapse All
        $(document).on('click', '.ays-survey-collapse-all', function (e) {
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(){
                var section = $(this);
                section.find('.ays-survey-action-collapse-question').each(function(){
                    collapseQuestion( $(this) );
                });
                collapseSection( section.find('.ays-survey-action-collapse-section') );
            });
        });

        // Expand All
        $(document).on('click', '.ays-survey-expand-all', function (e) {
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(){
                var section = $(this);
                section.find('.ays-survey-action-collapse-question').each(function(){
                    expandQuestion( $(this) );
                });
                expandSection( section.find('.ays-survey-action-expand-section') );
            });
        });

        // Collapse Section Questions
        $(document).on('click', '.ays-survey-collapse-sec-quests', function (e) {
            var $this = $(this);
            var section = $this.parents('.ays-survey-section-box');
            section.find('.ays-survey-action-collapse-question').each(function(){
                collapseQuestion( $(this) );
            });
        });

        // Collapse Section Questions
        $(document).on('click', '.ays-survey-section-questions-required', function (e) {
            makeAllQuestionsRequired($(this) , true);
        });

        // Expand Section Questions
        $(document).on('click', '.ays-survey-expand-sec-quests', function (e) {
            var $this = $(this);
            var section = $this.parents('.ays-survey-section-box');
            section.find('.ays-survey-action-collapse-question').each(function(){
                expandQuestion( $(this) );
            });
        });

        // Collapse Question
        $(document).on('click', '.ays-survey-action-collapse-question', function (e) {
            var $this = $(this);
            collapseQuestion( $this );
        });

        // Expand Question
        $(document).on('click', '.ays-survey-action-expand-question, .ays-survey-question-wrap-collapsed-contnet', function (e) {
            var $this = $(this);
            
            if(!$(e.target).hasClass('dropdown-item')){
                expandQuestion( $this );
            }
        });

        // Collapse Section
        $(document).on('click', '.ays-survey-action-collapse-section', function (e) {
            var $this = $(this);
            collapseSection( $this );
        });

        // Expand Section
        $(document).on('click', '.ays-survey-action-expand-section', function (e) {
            var $this = $(this);
            expandSection( $this );
        });


        function collapseQuestion( _this ){
            var questsCont = _this.parents('.ays-survey-question-answer-conteiner');
            var questsText = questsCont.find('.ays-survey-question-input-textarea').val();
            questsCont.find('.ays-survey-question-wrap-collapsed-contnet-text').text( questsText );
            questsCont.find('.ays-survey-question-wrap-collapsed').removeClass('display_none');
            questsCont.find('.ays-survey-question-wrap-expanded').addClass('display_none');
            questsCont.find('.ays-survey-question-collapsed-input').val('collapsed');
            aysSurveySectionsInitQuestionsCollapse();
        }

        function expandQuestion( _this ){
            var questsCont = _this.parents('.ays-survey-question-answer-conteiner');
            questsCont.find('.ays-survey-question-wrap-collapsed').addClass('display_none');
            questsCont.find('.ays-survey-question-wrap-expanded').removeClass('display_none');
            questsCont.find('.ays-survey-question-collapsed-input').val('expanded');
            aysSurveySectionsInitQuestionsCollapse();
            aysAutosizeUpdate();
        }

        function collapseSection( _this ){
            var sectionCont = _this.parents('.ays-survey-section-box');
            var sectionText = sectionCont.find('.ays-survey-section-title').val();
            sectionCont.find('.ays-survey-section-wrap-collapsed-contnet-text').text( sectionText );
            sectionCont.find('.ays-survey-section-wrap-collapsed').removeClass('display_none');
            sectionCont.find('.ays-survey-section-wrap-expanded').addClass('display_none');
            sectionCont.find('.ays-survey-section-collapsed-input').val('collapsed');
        }

        function expandSection( _this ){
            var sectionCont = _this.parents('.ays-survey-section-box');
            sectionCont.find('.ays-survey-section-wrap-collapsed').addClass('display_none');
            sectionCont.find('.ays-survey-section-wrap-expanded').removeClass('display_none');
            sectionCont.find('.ays-survey-section-collapsed-input').val('expanded');
            aysAutosizeUpdate();
        }

        function aysAutosizeUpdate(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var aysSurveyQuestionTextArea = sectionCont.find('textarea.ays-survey-question-input-textarea');
            var aysSurveySectionDescriptionTextArea = sectionCont.find('textarea.ays-survey-section-description');
            autosize.update(aysSurveyQuestionTextArea);
            autosize.update(aysSurveySectionDescriptionTextArea);
        }

        // Add Question and Answer Image 
        $(document).on('click', '.ays-survey-add-question-image, .ays-survey-add-answer-image', function (e) {
            var dataType = $(this).data('type');
            openMediaUploader(e, $(this), dataType);
        });

        // Add Answer Button
        $(document).on('click', '.ays-survey-action-add-answer', function(e){
            var $this = $(this);
            aysSurveyAddAnswer( $this, false );
        });

        // Delete Answer Button
        $(document).on('click', '.ays-survey-answer-delete', function(e){
            var $this = $(this);
            var itemId = $this.parents('.ays-survey-question-answer-conteiner').data('id');
            var answerId = $this.parents('.ays-survey-answer-row').data('id');
            var length = $this.parents('.ays-survey-answers-conteiner').find('.ays-survey-answer-delete').length - 1;
            var parent = $this.parents('.ays-survey-answers-conteiner');
            var hideDeleteButton = parent.find('.ays-survey-answer-delete');

            if(length == 1){
                hideDeleteButton.css('visibility','hidden');
            }else{
                hideDeleteButton.removeAttr('style');
            }
            if( ! $this.parents('.ays-survey-answer-row').hasClass('ays-survey-new-answer') ){
                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                $this.parents('form').append( delImp );
            }

            $this.parents('.ays-survey-answer-row [data-toggle="popover"]').popover('hide');
            $this.parents('.ays-survey-answer-row').remove();

            parent.find('.ays-survey-answer-ordering').each(function(i){
                $(this).val( i + 1 );
            });
        });

        // Add "Other" Answer
        $(document).on('click', '.ays-survey-other-answer-add', function(e){
            var $this = $(this);
            var parent = $this.parents('.ays-survey-question-answer-conteiner');
            var checkbox = parent.find('.ays-survey-other-answer-checkbox').attr('checked', 'checked');
            var oterAnswer = parent.find('.ays-survey-other-answer-row');
            oterAnswer.removeAttr('style');
            $this.parents('.ays-survey-other-answer-add-wrap').css('display','none');
        });

        // Delete "Other" Answer
        $(document).on('click', '.ays-survey-other-answer-delete', function(e){
            var $this = $(this);
            var parent = $this.parents('.ays-survey-question-answer-conteiner');
            var checkbox = parent.find('.ays-survey-other-answer-checkbox').removeAttr('checked');
            var oterAnswer = parent.find('.ays-survey-other-answer-row');
            oterAnswer.css('display','none');
            parent.find('.ays-survey-other-answer-add-wrap').removeAttr('style');
        });

        // Dublicate Question Button
        $(document).on('click', '.ays-survey-action-duplicate-question', function(e){
            var $this = $(this);
            var sectionId = $this.parents('.ays-survey-section-box').attr('data-id');
            var thisSection = $this.parents('.ays-survey-section-box');
            var sectionName = $this.parents('.ays-survey-section-box').attr('data-name');
            var cloningElement = $this.parents('.ays-survey-question-answer-conteiner');
            var itemId = cloningElement.data('id');
            var question_length = cloningElement.parent().find('.ays-survey-question-answer-conteiner').length + 1;
            var question_type = cloningElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('get value');

            // var questionsCount = questionsLength;
            var questionsCountBox = thisSection.find(".ays-survey-action-questions-count span").text(question_length);

            cloningElement.find('.ays-survey-question-type').aysDropdown('destroy');
            var clonedLimitBy = cloningElement.find('.ays-survey-question-word-limit-by-select select').val();
            
            var clone = cloningElement.clone(true, false).attr('data-id', question_length).addClass('ays-survey-new-question').insertAfter( cloningElement );
            var newElement = $this.parents('.ays-survey-section-box').find('.ays-survey-question-answer-conteiner.ays-survey-new-question[data-id = '+ question_length +']');

            newElement.attr('data-id', question_length);
            newElement.data('id', question_length);
            newElement.attr('data-name', 'questions_add');
            newElement.find('input[name="ays_question_ids[]"]').remove();
            newElement.find('textarea.ays-survey-question-input.ays-survey-input').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'title') );
            newElement.find('input.ays-survey-question-img-src').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'image') );
            newElement.find('input.ays-survey-input-required-question').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'required') );
            newElement.find('input.ays-survey-question-ordering').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'ordering') );
            newElement.find('input.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'collapsed'));
            newElement.find('input.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'user_variant') );
            newElement.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_max_selection_count'));
            newElement.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'max_selection_count'));
            newElement.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'min_selection_count'));
            // Text limitation options
            newElement.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, question_length, 'options', 'enable_word_limitation'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_by'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_length'));
            newElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'limit_counter'));
            // Number limitation options start
            newElement.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, question_length, 'options', 'enable_number_limitation'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_min_selection'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_max_selection'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_error_message'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_number_error_message'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'number_limit_length'));
            newElement.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'enable_number_limit_counter'));
            // Number limitation options end

            // Input types placeholders
            newElement.find('.ays-survey-remove-default-border.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'placeholder'));
            
            newElement.find('input.ays-survey-question-image-caption').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'image_caption') );
            newElement.find('input.ays-survey-question-img-caption-enable').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'image_caption_enable') );

            newElement.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( sectionName, sectionId, question_length, 'options', 'with_editor'));
            if( newElement.find('select.ays-survey-question-type').length ){
                newElement.find('select.ays-survey-question-type').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'type') );
            }else if( newElement.find('.ays-survey-question-type select').length ){
                newElement.find('.ays-survey-question-type select').attr( 'name', newQuestionAttrName( sectionName, sectionId, question_length, 'type') );
            }
            var ddElem = newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('get text');
            newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('set text', ddElem);
            newElement.find('.ays-survey-question-conteiner .ays-survey-question-type select option[value="'+question_type+'"]').prop('selected', true);
            // newElement.find('.ays-survey-question-conteiner .ays-survey-question-type').aysDropdown('set selected', question_type);
            newElement.find('.ays_question_ids').val(question_length);
            newElement.find('.ays_question_old_ids').remove();

            var answer = newElement.find('.ays-survey-answers-conteiner .ays-survey-answer-box input.ays-survey-input').each(function(i){
                $(this).parents('.ays-survey-answer-row').attr( 'data-id', i+1 );
                $(this).parents('.ays-survey-answer-row').attr( 'data-name', 'answers_add' );
                $(this).parents('.ays-survey-answer-row').addClass('ays-survey-new-answer');
                $(this).attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'title') );
                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'image') );
                $(this).parents('.ays-survey-answer-row').find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, newElement.attr('data-name'), question_length, i+1, 'ordering') );
            });

            newElement.find('.ays-survey-answers-conteiner').sortable(answerDragHandle);
            newElement.find('.ays-survey-question-word-limit-by-select select option[value="'+clonedLimitBy+'"]').prop('selected', true);

            if( question_type == 'select' ){
                newElement.find('.ays-survey-other-answer-row').hide();
                newElement.find('.ays-survey-other-answer-checkbox').prop('checked', false );
                newElement.find('.ays-survey-other-answer-add-wrap').hide();
            }
            
            newElement.find('.ays-survey-question-type').aysDropdown();
            cloningElement.find('.ays-survey-question-type').aysDropdown();

            var aysSurveyTextArea = newElement.find('textarea.ays-survey-question-input-textarea');
            autosize(aysSurveyTextArea);
            if( newElement.find('.dropdown .divider').length == 0 ){
                newElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');
                newElement.find('.dropdown .item[data-value="matrix_scale"]').before('<div class="divider"></div>');
            }
            if( cloningElement.find('.dropdown .divider').length == 0 ){
                cloningElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');
                cloningElement.find('.dropdown .item[data-value="matrix_scale"]').before('<div class="divider"></div>');
            }
            $this.parents('.ays-survey-section-box').find('.ays-survey-question-ordering').each(function(i){
                $(this).val(i+1);
            });
            // newElement.find('.ays-survey-question-type').dropdown('refresh');
            // var answerImg = newElement.find('.aysFormeditorAnswerConteiner input.quantumWizTextinputSimpleinputInput').each(function(i){
            //     $(this).attr('name', $html_name_prefix + 'section_add['+ sectionId +'][question]['+ question_length +'][answer]['+ i +'][title]');
            // });

            setTimeout(function(){
                newElement.goToTop();
            }, 100 );

            reInitPopovers( newElement );
            aysSurveySectionsInitQuestionsCollapse();
        });

        // Remove Question Button
        $(document).on('click', '.ays-survey-action-delete-question', function(e){
            var $this = $(this);
            var length = $this.parents('.ays-survey-section-questions').find('.ays-survey-question-answer-conteiner').length;
            var status = true;
            if(length == 1){
                swal.fire({
                    type: 'warning',
                    text: SurveyMakerAdmin.minimumCountOfQuestions
                });
                status = false;
            }else{
                swal({
                    html:"<h4>"+ SurveyMakerAdmin.questionDeleteConfirmation +"</h4>",
                    type: 'error',
                    showCloseButton: true,
                    showCancelButton: true,
                    allowOutsideClick: true,
                    allowEscapeKey: true,
                    allowEnterKey: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: SurveyMakerAdmin.yes,
                    cancelButtonText: SurveyMakerAdmin.cancel
                }).then( function(result) {
                    
                    if( result.dismiss && result.dismiss == 'close' ){
                        return false;
                    }

                    var deleteQuest = false;
                    if (result.value) deleteQuest = true;

                    if( deleteQuest ){
                        if( ! $this.parents('.ays-survey-question-answer-conteiner').hasClass('ays-survey-new-question') ){
                            var qId = $this.parents('.ays-survey-question-answer-conteiner').data('id');
                            var delImp = '<input type="hidden" name="'+ $html_name_prefix +'questions_delete[]" value="'+ qId +'">';
                            $this.parents('form').append( delImp );
                        }
                        var section = $this.parents('.ays-survey-section-box');
                        section.find(".ays-survey-action-questions-count span").text(length - 1);
                        $this.parents('.ays-survey-question-answer-conteiner').remove();
                    }
                } );
            }

            if (status == false) {
                e.preventDefault();
            }

        });

        // Remove Section Button
        $(document).on('click', '.ays-survey-delete-section', function(e){
            var $this = $(this);

            swal({
                html:"<h4>"+ SurveyMakerAdmin.sectionDeleteConfirmation +"</h4>",
                type: 'error',
                showCloseButton: true,
                showCancelButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                allowEnterKey: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: SurveyMakerAdmin.yes,
                cancelButtonText: SurveyMakerAdmin.cancel
            }).then( function(result) {
                
                if( result.dismiss && result.dismiss == 'close' ){
                    return false;
                }

                var deleteQuest = false;
                if (result.value) deleteQuest = true;

                if( deleteQuest ){
                    var parent = $this.parents('.ays-survey-section-box');
                    if( ! parent.hasClass('ays-survey-new-section') ){
                        var sId = parent.data('id');
                        var delImp = '<input type="hidden" name="'+ $html_name_prefix +'sections_delete[]" value="'+ sId +'">';
                        $this.parents('form').append( delImp );
                    }
                    parent.remove();

                    var sectionCont = $(document).find('.ays-survey-sections-conteiner');
                    var sections = sectionCont.find('.ays-survey-section-box');
                    var addQuestionButton = $(document).find('.ays-survey-general-action[data-action="add-question"]');

                    var length = sectionCont.find('.ays-survey-section-box').length;
                    sections.each(function(i, el){
                        $(this).find('.ays-survey-section-number').text(i+1);
                        $(this).find('.ays-survey-sections-count').text( sections.length );
                    });

                    if (length == 1) {
                        addQuestionButton.dropdown('dispose');
                        addQuestionButton.removeAttr('data-toggle');
                        sections.find('.ays-survey-section-head-top').addClass('display_none');
                        sections.find('.ays-survey-section-head').removeClass('ays-survey-section-head-topleft-border-none');
                        sections.find('.ays-survey-section-actions-more .ays-survey-delete-section').addClass('display_none');
                    }

                    aysSurveySectionsInitToAddQuestions();
                }
            } );
        });

        // Remove Answer Image
        $(document).on('click', '.removeAnswerImage', function (e) {
            var $this = $(this);
            $this.parents('.ays-survey-answer-image-container').fadeOut(500);
            setTimeout(function(){
                $this.parents('.ays-survey-answer-image-container').find('.ays-survey-answer-img').removeAttr('src');
                $this.parents('.ays-survey-answer-image-container').find('.ays-survey-answer-img-src').val('');
            }, 500);
        });

        // Survey Question Image actions Buttons
        $(document).on('click', '.ays-survey-question-img-action', function(e){
            var $this = $(this);
            
            var action = $this.attr('data-action');
            
            switch( action ){
                case 'edit-image':
                    $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-add-question-image').trigger('click');
                break;
                case 'delete-image':
                    $this.parents('.ays-survey-question-image-container').fadeOut(500);
                    setTimeout(function(){
                        $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img').removeAttr('src');
                        $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-src').val('');
                    }, 500);
                break;
                case 'add-caption':
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-image-caption-text-row').removeClass('display_none');
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-caption-enable').val('on');
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-input.ays-survey-question-image-caption').focus();
                    $this.attr('data-action' , 'close-caption');
                    $this.text(SurveyMakerAdmin.closeQuestionImageCaption);
                break;
                case 'close-caption':
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-image-caption-text-row').addClass('display_none');                    
                    $this.parents('.ays-survey-question-image-container').find('.ays-survey-question-img-caption-enable').val('off');
                    $this.attr('data-action' , 'add-caption');
                    $this.text(SurveyMakerAdmin.addQuestionImageCaption);
                break;
            }
        });

        // Survey General actions Buttons
        $(document).on('click', '.ays-survey-general-action', function(e){
            var $this = $(this);
            var action = $this.data('action');
            switch( action ){
                case 'add-question':
                    if(! $this.attr('data-toggle')){
                        var sectionCont = $(document).find('.ays-survey-sections-conteiner');
                        var sections = sectionCont.find('.ays-survey-section-box');
                        aysSurveyAddQuestion( sections.data('id'), false, sections );
                    }
                break;
                case 'import-question':
                
                break;
                case 'add-section-header':
                
                break;
                case 'add-image':
                
                break;
                case 'add-video':
                
                break;
                case 'add-section':
                    aysSurveyAddSection( null, null );
                    aysSurveySectionsInitToAddQuestions();
                break;
                case 'save-changes':
                    $(document).find('#ays-button-apply-top').trigger('click');
                break;
                case 'make-questions-required':
                    makeAllQuestionsRequired($this , false);
                break;
            }
        });

        // Survey Question actions Buttons
        $(document).on('click', '.ays-survey-question-action', function(e){
            var $this = $(this);
            var action = $this.attr('data-action');
            var $thisEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count,.ays-survey-question-min-selection-count');
            var $thisWordEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations');
            var $thisNumberEl = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations');
            var enableNumberLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations-checkbox');
            
            var currentSection = $this.parents('.ays-survey-section-box');
            var currentQuestion = $this.parents('.ays-survey-question-answer-conteiner');
            var sectionId = currentSection.attr('data-id');
            var sectionName = currentSection.attr('data-name');
            var questionId = currentQuestion.attr('data-id');
            var questionName = currentQuestion.attr('data-name');

            switch( action ){
                case 'max-selection-count-enable':
                    var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
                    enableCheckbox.prop('checked', true);
                    $thisEl.removeClass('display_none');
                    $this.attr('data-action', 'max-selection-count-disable');
                break;
                case 'max-selection-count-disable':
                    var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
                    enableCheckbox.prop('checked', false);
                    $thisEl.addClass('display_none');
                    $this.attr('data-action', 'max-selection-count-enable');
                break;
                case 'word-limitation-enable':
                    var enableTextLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
                    enableTextLimit.prop('checked', true);
                    $thisWordEl.removeClass('display_none');
                    $this.attr('data-action', 'word-limitation-disable');
                break;
                case 'word-limitation-disable':
                    var enableTextLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
                    enableTextLimit.prop('checked', false);
                    $thisWordEl.addClass('display_none');
                    $this.attr('data-action', 'word-limitation-enable');
                break;
                case 'number-word-limitation-enable':
                    enableNumberLimit.prop('checked', true);
                    $thisNumberEl.removeClass('display_none');
                    $this.attr('data-action', 'number-word-limitation-disable');
                break;
                case 'number-word-limitation-disable':
                    enableNumberLimit.prop('checked', false);
                    $thisNumberEl.addClass('display_none');
                    $this.attr('data-action', 'number-word-limitation-enable');
                break;
                case 'move-to-section':
                    var popup = $(document).find('#ays-survey-move-to-section');
                    popup.attr('data-section-id', sectionId);
                    popup.attr('data-section-name', sectionName);
                    popup.attr('data-question-id', questionId);
                    popup.attr('data-question-name', questionName);
                    aysSurveySectionsInitToMoveQuestions( currentSection, currentQuestion );
                    popup.aysModal('show');
                break;
            }
        });

        $(document).on('show.bs.dropdown', '.ays-survey-question-more-actions', function(e){
            // var $this = $(this);
            // var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
            // if( enableCheckbox.prop('checked') == true ){
            //     $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.disableMaxSelectionCount);
            // }else{
            //     $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.enableMaxSelectionCount);
            // }

            // if( $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-type').aysDropdown( 'get value' ) == 'checkbox' ){
            //     $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').removeClass('display_none');
            // }
            var $this = $(this);
            // var question = $this.parents('.ays-survey-question-answer-conteiner');
            var questionType = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-type').aysDropdown( 'get value' );
            var enableCheckbox = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-max-selection-count-checkbox');
            var enableWordLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-word-limitations-checkbox');
            var enableNumberLimit = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-question-number-limitations-checkbox');
            if( enableCheckbox.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.disableSelectionCount);
            }else{
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').text(SurveyMakerAdmin.enableSelectionCount);
            }

            if( questionType == 'checkbox' ){
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="max-selection-count"]').addClass('display_none');
            }

            if( enableWordLimit.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').text(SurveyMakerAdmin.disableWordLimitation);
            }else{
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').text(SurveyMakerAdmin.enableWordLimitation);
            }
            
            if( questionType == 'short_text' ||  questionType == 'text' ){
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="word-limitation-"]').addClass('display_none');
            }

            if( enableNumberLimit.prop('checked') == true ){
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').text(SurveyMakerAdmin.disableNumberLimitation);
            }else{
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').text(SurveyMakerAdmin.enableNumberLimitation);
            }
            
            if( questionType == 'number' ){
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').removeClass('display_none');
            }else{
                $this.find('.ays-survey-question-action[data-action^="number-word-limitation-"]').addClass('display_none');
            }
        });

        $(document).on('click', '.ays-survey-add-question-to-this-section', function(e){
            var section = $(this).parents('.ays-survey-section-box');

            var sectionId = section.attr('data-id');
            aysSurveyAddQuestion( sectionId, false, section );

        });

        $(document).on('click', '.ays-survey-add-new-section-from-bottom', function(e){
            var afterSectionId = $(this).parents('.ays-survey-section-box').attr('data-id');
            var newSection = $(this).parents('.ays-survey-section-box').hasClass('ays-survey-new-section');
            aysSurveyAddSection( afterSectionId, newSection );
            aysSurveySectionsInitToAddQuestions();
        });

        $(document).on('click', '.ays-survey-duplicate-section', function(e){
            var afterSectionId = $(this).parents('.ays-survey-section-box').data('id');
            $(this).parents('.ays-survey-section-box').find(".ays-survey-delete-section").removeClass('display_none');
            var newSection = $(this).parents('.ays-survey-section-box').hasClass('ays-survey-new-section');
            aysSurveyDuplicateSection( $(this), afterSectionId, newSection);
        });

        $(document).on('click', '.ays-survey-add-question-into-section', function(e){
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+ $(this).data( 'id' ) +'"]:not(.ays-survey-new-section)');
            if( $(this).hasClass('ays-survey-add-new-question-into-section') ){
                section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box.ays-survey-new-section[data-id="'+ $(this).data( 'id' ) +'"]');
            }
            var sectionId = $(this).attr( 'data-id' );
            aysSurveyAddQuestion( sectionId, false, section );
        });

        $(document).on('change', '.ays-survey-question-type', function(e) {
            var $this = $(this);
            var parent = $this.parents('.ays-survey-section-box');
            var sectionId = parent.attr('data-id');
            var questionId = $this.parents('.ays-survey-question-answer-conteiner').attr('data-id');
            var questionDataName = $this.parents('.ays-survey-question-answer-conteiner').attr('data-name');
            var questionType = $this.aysDropdown('get value'); //$this.val();
            var questionTypeBeforeChange = $this.parents('.ays-survey-question-type-box').find('.ays-survey-check-type-before-change').val();
            var answerIds = $this.parents('.ays-survey-question-answer-conteiner').find('.ays-survey-answers-conteiner .ays-survey-answer-row');
            $this.parents('.ays-survey-question-type-box').find('.ays-survey-check-type-before-change').val(questionType);

            if( questionType == 'radio' || questionType == 'checkbox' || questionType == 'select' ){
                if ( questionTypeBeforeChange == 'yesorno' ) {
                    if (answerIds != undefined) {
                        answerIds.each(function(e) {
                            var answerId = $(this).data('id');
                            if( ! $(this).hasClass('ays-survey-new-answer') ){
                                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                                $this.parents('form').append( delImp );
                            }
                        });
                    }
                }
            }else{
                if ( questionTypeBeforeChange == 'radio' || questionTypeBeforeChange == 'checkbox' || questionTypeBeforeChange == 'select' || questionTypeBeforeChange == 'yesorno' ) {
                    if (answerIds != undefined) {
                        answerIds.each(function(e) {
                            var answerId = $(this).data('id');
                            if( ! $(this).hasClass('ays-survey-new-answer') ){
                                var delImp = '<input type="hidden" name="'+ $html_name_prefix +'answers_delete[]" value="'+ answerId +'">';
                                $this.parents('form').append( delImp );
                            }
                        });
                    }
                }
            }

            switch( questionType ){
                case 'radio':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'checkbox':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'select':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'text':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'short_text':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'number':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'yesorno':
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
                break;
                case 'email':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                case 'name':
                    aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId , questionId , questionDataName, questionType, false , parent );
                break;
                default:
                    aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId , questionId , questionDataName, questionType, questionTypeBeforeChange, false , parent );
            }
        });

        setTimeout(function(){
            if($(document).find('#ays_survey_custom_css').length > 0){
                if(wp.codeEditor){
                    wp.codeEditor.initialize($(document).find('#ays_survey_custom_css'), cm_settings);
                }
            }
        }, 500);

        $(document).find('a[href="#tab2"]').on('click', function (e) {
            setTimeout(function(){
                if($(document).find('#ays_survey_custom_css').length > 0){
                    var ays_survey_custom_css = $(document).find('#ays_survey_custom_css').html();
                    if(wp.codeEditor){
                        $(document).find('#ays_survey_custom_css').next('.CodeMirror').remove();
                        wp.codeEditor.initialize($(document).find('#ays_survey_custom_css'), cm_settings);
                        $(document).find('#ays_survey_custom_css').html(ays_survey_custom_css);
                    }
                }
            }, 500);
        });

        $(document).find('#ays_survey_schedule_active, #ays_survey_schedule_deactive, #ays_survey_change_creation_date').datetimepicker({
            controlType: 'select',
            oneLine: true,
            dateFormat: "yy-mm-dd",
            timeFormat: "HH:mm:ss",
            afterInject: function(){
                $(document).find('.ui-datepicker-buttonpane button.ui-state-default').addClass('button');
                $(document).find('.ui-datepicker-buttonpane button.ui-state-default.ui-priority-primary').addClass('button-primary').css('float', 'right');
            }
        });

        $(document).on('click', '.ays-survey-open-question-editor', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            // For question
            var question = $(this).parents('.ays-survey-question-answer-conteiner');
            var questionId = question.data('id');
            var questionName = question.data('name');
            // For section
            var section = question.parents('.ays-survey-section-box');
            var sectionId = section.data('id');
            var sectionName = section.data('name');
            var questionContent = question.find('textarea.ays-survey-question-input-textarea').val();
            // For question
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', questionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', questionName );
            // For section
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-section-id', sectionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-section-name', sectionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-section-id', sectionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-section-name', sectionName );

            if ( $(document).find("#wp-ays_survey_question_editor-wrap").hasClass("tmce-active") ){
                window.tinyMCE.get('ays_survey_question_editor').setContent( questionContent );
            }else{
                $(document).find('#ays_survey_question_editor').val( questionContent );
            }

            editorPopup.aysModal('open');
        });

        $(document).on('dblclick click', '.ays-survey-question-preview-box', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            var question = $(this).parents('.ays-survey-question-answer-conteiner');
            var questionId = question.data('id');
            var questionName = question.data('name');
            var questionContent = question.find('textarea.ays-survey-question-input-textarea').val();
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-apply-question-changes').attr( 'data-question-name', questionName );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', questionId );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', questionName );

            var SurveyTinyMCE = window.tinyMCE.get('ays_survey_question_editor');
            if(SurveyTinyMCE != null){
                SurveyTinyMCE.setContent( questionContent );
            }
            else{
                $(document).find('#ays_survey_question_editor').val( questionContent );
            }

            editorPopup.aysModal('open');
        });

        $(document).on('click', '.ays-survey-back-to-textarea', function(e){
            var editorPopup = $(document).find('#ays-edit-question-content');
            var questionId = $(this).attr('data-question-id');
            var questionName = $(this).attr('data-question-name');
            var question = $(document).find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionName+'"]');
            var aysSurveyQuestionTextArea = question.find('textarea.ays-survey-question-input-textarea');

            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-id', '' );
            editorPopup.find('.ays-survey-back-to-textarea').attr( 'data-question-name', '' );

            question.find('.ays-survey-open-question-editor-flag').val('off');

            question.find('.ays-survey-question-input-box').removeClass('display_none');
            question.find('.ays-survey-question-preview-box').addClass('display_none');

//            setTimeout( function(){
                autosize.update(aysSurveyQuestionTextArea);
//            }, 100 );

            editorPopup.aysModal('hide');
        });

        $(document).on('click', '.ays-survey-move-question-into-section', function(e){
            var popup = $(document).find('#ays-survey-move-to-section');
            var sectionId = popup.attr('data-section-id');
            var sectionDataName = popup.attr('data-section-name');
            var questionId = popup.attr('data-question-id');
            var questionDataName = popup.attr('data-question-name');
            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+ $(this).data( 'id' ) +'"]:not(.ays-survey-new-section)');
            if( $(this).hasClass('ays-survey-move-new-question-into-section') ){
                section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box.ays-survey-new-section[data-id="'+ $(this).data( 'id' ) +'"]');
            }
            var oldSection = $(document).find('.ays-survey-section-box[data-id="'+sectionId+'"][data-name="'+sectionDataName+'"]');
            var question = oldSection.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var questions = oldSection.find('.ays-survey-question-answer-conteiner');
            
            if( questions.length <= 1 ){
                swal.fire({
                    type: 'warning',
                    text: SurveyMakerAdmin.minimumCountOfQuestions
                });
            }else{
                setTimeout(function(){
                    question.appendTo( section.find('.ays-survey-section-questions') );
                    draggedQuestionUpdate( question, section );
                    popup.aysModal('hide');
                }, 1);
            }
        });

        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////ARO END/////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        aysSurveySectionsInitToAddQuestions();
        function aysSurveySectionsInitToAddQuestions(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var addQuestionButton = $(document).find('.ays-survey-general-action[data-action="add-question"]');
            var ddmenu = addQuestionButton.parent().find('.dropdown-menu');
            ddmenu.html('');
            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                }

                var newClassToAddQuestion = '';
                if( _this.hasClass('ays-survey-new-section') ){
                    newClassToAddQuestion = 'ays-survey-add-new-question-into-section';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );

                var buttonItem = '<button class="dropdown-item ays-survey-add-question-into-section '+ newClassToAddQuestion +'" data-id="'+ $(this).data('id') +'" type="button">';
                buttonItem += 'Add into Section '+ (i+1);
                buttonItem += '</button>';
                ddmenu.append(buttonItem);
            });
            if(sections.length > 1){
                addQuestionButton.attr('data-toggle', 'dropdown');
                addQuestionButton.attr('aria-expanded', 'false');
            }else{
                addQuestionButton.dropdown('dispose');
                addQuestionButton.removeAttr('data-toggle');
            }
        }

        aysSurveySectionsInitQuestionsCollapse();
        function aysSurveySectionsInitQuestionsCollapse(){
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                var collapseButtonAddClass = 'ays-survey-collapse-sec-quests';
                var collapseButtonRemoveClass = 'ays-survey-expand-sec-quests';
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                    collapseButtonAddClass = 'ays-survey-expand-sec-quests';
                    collapseButtonRemoveClass = 'ays-survey-collapse-sec-quests';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );
                _this.find('.ays-survey-collapse-section-questions').addClass( collapseButtonAddClass );
                _this.find('.ays-survey-collapse-section-questions').removeClass( collapseButtonRemoveClass );
            });
        }

        function aysSurveyAddQuestion( sectionId, returnElem = false, sectionElem = null ){
            var section = sectionElem;
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner');
            var clonedElement = cloningElement.clone( true, false );
            // var questionsLength = section.find('.ays-survey-question-answer-conteiner .ays-survey-question-input-box .ays-survey-input[name^="ays_section_add"]').length;
            var questionsLength = section.find('.ays-survey-question-answer-conteiner').length;
            var defaultQuestionType = $(document).find('input[name="ays_default_question_type"]').val();
            var answers = clonedElement.find('.ays-survey-answers-conteiner .ays-survey-answer-row');

            // Generate unique number for every new question
            var generatedUniqueNumber = aysSurveyUniqueNumber();
            var questionId = generatedUniqueNumber;

            var questionName = clonedElement.attr('data-name');
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }

            var questionsCount = questionsLength;
            var questionsCountBox = section.find(".ays-survey-action-questions-count span").text(questionsCount + 1);
            // Answers ordering jQuery UI
            clonedElement.find('.ays-survey-answers-conteiner').sortable(answerDragHandle);


            var aysSurveyTextArea = clonedElement.find('textarea.ays-survey-question-input-textarea');
            setTimeout( function(){
                autosize(aysSurveyTextArea);
            }, 100 );

            clonedElement.addClass('ays-survey-new-question');
            clonedElement.attr('data-id', questionId);
            clonedElement.find('textarea.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'title'));
            clonedElement.find('select.ays-survey-question-type').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'type'));
            clonedElement.find('.ays-survey-question-img-src').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'image'));
            clonedElement.find('.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'user_variant'));
            clonedElement.find('.ays-survey-input-required-question').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'required'));
            clonedElement.find('.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'collapsed'));
            clonedElement.find('.ays-survey-question-ordering').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'ordering'));
            clonedElement.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_max_selection_count'));
            clonedElement.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'max_selection_count'));
            clonedElement.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            clonedElement.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, questionId, 'options', 'enable_word_limitation'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_by'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_length'));
            clonedElement.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'limit_counter'));
            // Number limitation options start
            clonedElement.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName(sectionName, sectionId, questionId, 'options', 'enable_number_limitation'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_min_selection'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_max_selection'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_error_message'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_number_error_message'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'number_limit_length'));
            clonedElement.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_number_limit_counter'));
            // Number limitation options end
            clonedElement.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'with_editor'));
            
            clonedElement.find('.ays-survey-question-image-caption').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'image_caption'));
            clonedElement.find('.ays-survey-question-img-caption-enable').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'image_caption_enable'));
            clonedElement.find('.ays-survey-question-ordering').val(questionId);

            answers.each(function(j){
                var answerId = j+1; //answers.find('.ays-survey-answer-box input.ays-survey-input').length;
                $(this).addClass('ays-survey-new-answer');
                $(this).attr('data-id', answerId);
                $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                $(this).find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'image'));
                $(this).find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));

                var deleteButton = $(this).find('.ays-survey-answer-delete');
                if( answers.length == 1){
                    deleteButton.css('visibility', 'hidden');
                }else{
                    deleteButton.removeAttr('style');
                }
            });
            
            clonedElement.find('select.ays-survey-question-type').aysDropdown();
            clonedElement.find('.dropdown .item[data-value="email"]').before('<div class="divider"></div>');
            clonedElement.find('.dropdown .item[data-value="matrix_scale"]').before('<div class="divider"></div>');

            var sectionCollapsedInput = section.find('.ays-survey-section-collapsed-input');
            var sectionCollapsed = sectionCollapsedInput.val() == 'collapsed' ? true : false;
            
            if( sectionCollapsed ){
                expandSection( sectionCollapsedInput );
            }

            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-section-questions').append(clonedElement);
                clonedElement = section.find('.ays-survey-question-answer-conteiner.ays-survey-new-question[data-id="'+questionId+'"]');
            }

            clonedElement.find('.ays-survey-question-type').aysDropdown('set selected', defaultQuestionType);
            clonedElement.find('.ays-survey-question-type').aysDropdown('set value', defaultQuestionType);

            setTimeout(function(){
                clonedElement.goToTop();
            }, 100 );

            reInitPopovers( clonedElement );
            aysSurveySectionsInitQuestionsCollapse();
        }
        
        // Generate unique number
        function aysSurveyUniqueNumber() {
            var randomNumber = Math.random();
            var timestamp    = Date.now();
            var uniqueNumber = Math.floor(randomNumber * timestamp);
            return uniqueNumber;
        }

        function aysSurveyAddSection( afterSectionId, newSection ){

            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var sectionsAdd = sectionCont.find('.ays-survey-new-section');

            var section = $(document).find('.ays-question-to-clone .ays-survey-section-box');
            var clonedElement = section.clone( true, false );
            var sectionNewId = sectionsAdd.length + 1;

            clonedElement.attr('data-id', sectionNewId);
            clonedElement.attr('data-name', 'ays_section_add');
            clonedElement.addClass('ays-survey-new-section');
            clonedElement.find('.ays-survey-section-title').attr('name', newSectionAttrName( sectionNewId, 'title' ));
            clonedElement.find('.ays-survey-section-description').attr('name', newSectionAttrName( sectionNewId, 'description' ));
            clonedElement.find('.ays-survey-section-ordering').attr('name', newSectionAttrName( sectionNewId, 'ordering' ));
            clonedElement.find('.ays-survey-section-collapsed-input').attr('name', newSectionAttrName( sectionNewId, 'options', 'collapsed' ));
            
            if( typeof afterSectionId !== 'undefined' && afterSectionId !== null ){
                if( newSection === true ){
                    clonedElement.insertAfter( sectionCont.find('.ays-survey-section-box.ays-survey-new-section[data-id="' + afterSectionId + '"]') );
                }else{
                    clonedElement.insertAfter( sectionCont.find('.ays-survey-section-box.ays-survey-old-section[data-id="' + afterSectionId + '"]') );
                }
            }else{
                sectionCont.append( clonedElement );
            }

            var defaultQuestionType = $(document).find('input[name="ays_default_question_type"]').val();
            var question = aysSurveyAddQuestion( sectionNewId, true, clonedElement );
            clonedElement.find('.ays-survey-section-questions').append( question );

            question.find('.ays-survey-question-type').aysDropdown('set selected', defaultQuestionType);
            question.find('.ays-survey-question-type').aysDropdown('set value', defaultQuestionType);
            
            clonedElement.find('.ays-survey-section-questions').sortable(questionDragHandle);

            sectionCont = $(document).find('.ays-survey-sections-conteiner');
            sections = sectionCont.find('.ays-survey-section-box');

            sections.each(function(i){
                $(this).find('.ays-survey-section-ordering').val( i + 1 );
            });

            var aysSurveyDescriptionTextArea = clonedElement.find('textarea.ays-survey-section-description');
            setTimeout( function(){
                autosize(aysSurveyDescriptionTextArea);
            }, 100 );
            
            sectionCont.find('.ays-survey-section-head-top').removeClass('display_none');
            sectionCont.find('.ays-survey-section-head').addClass('ays-survey-section-head-topleft-border-none');

            sectionCont.find('.ays-survey-section-box').each(function( index ){
                $(this).find('.ays-survey-section-number').text( index + 1 );
            });

            sectionCont.find('.ays-survey-sections-count').text( sections.length );

            // sectionCont.find('.invisible').removeClass( 'invisible' );
            sectionCont.find('.ays-survey-section-actions-more .ays-survey-delete-section').removeClass('display_none');
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-survey-answer-dlg-dragHandle .ays-survey-icons').addClass( 'invisible' );
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-question-img-icon-content').parents('.ays-survey-answer-icon-box').addClass( 'invisible' );
            // sectionCont.find('.ays-survey-other-answer-and-actions-row .ays-survey-other-answer-delete-icon').parents('.ays-survey-answer-icon-box').addClass( 'invisible' );

            setTimeout(function(){
                clonedElement.goToTop();
            }, 100 );

            reInitPopovers( clonedElement );
        }

        function aysSurveyDuplicateSection( currentButton, afterSectionId, newSection){
            var sectionCont  = currentButton.parents('.ays-survey-sections-conteiner');
            var sections     = sectionCont.find('.ays-survey-section-box');
            var sectionsAdd  = sectionCont.find('.ays-survey-new-section');
            var sectionNewId = sectionsAdd.length + 1;

            var currentElement = currentButton.parents(".ays-survey-section-box");
            var clonedElement = currentElement.clone( true, false );
            clonedElement = aysSetNewSectionNames(clonedElement, sectionNewId);

            if( typeof afterSectionId !== 'undefined' && afterSectionId !== null ){
                clonedElement.insertAfter( currentElement );
            }else{
                sectionCont.append( clonedElement );
            }

            // Get all questions
            var allQuestions = clonedElement.find('.ays-survey-question-answer-conteiner');
            allQuestions.each(function(i){
                var questionElem = this;
                var questionId = i+1;
                var answers = $(this).find('.ays-survey-answers-conteiner .ays-survey-answer-row');

                // Set new names for questions
                aysSetNewQuestionNames($(questionElem), sectionNewId, questionId);
                answers.each(function(j){
                    var answerId = j;
                    // Set new names for answers
                    aysSetNewAnswerNames($(this), sectionNewId, questionId, answerId);
    
                    var deleteButton = $(this).find('.ays-survey-answer-delete');
                    if( answers.length == 1){
                        deleteButton.css('visibility', 'hidden');
                    }else{
                        deleteButton.removeAttr('style');
                    }
                });
            });


            clonedElement.find('.ays-survey-section-questions').sortable(questionDragHandle);

            sectionCont  = $(document).find('.ays-survey-sections-conteiner');
            sections     = sectionCont.find('.ays-survey-section-box');
            sections.each(function(i){
                $(this).find('.ays-survey-section-ordering').val( i + 1 );
                $(this).find('.ays-survey-section-number').text( i + 1 );
            });

            sectionCont.find('.ays-survey-sections-count').text( sections.length );

            sectionCont.find('.ays-survey-section-head-top').removeClass('display_none');
            sectionCont.find('.ays-survey-section-head').addClass('ays-survey-section-head-topleft-border-none');
            setTimeout(function(){
                clonedElement.goTo();
            }, 100 );
            reInitPopovers( clonedElement );
        }

        function aysSetNewSectionNames(element, sectionId){
            element.attr('data-id', sectionId);
            element.attr('data-name', 'ays_section_add');
            element.addClass('ays-survey-new-section');
            element.find('.ays-survey-section-title').attr('name', newSectionAttrName( sectionId, 'title' ));
            element.find('.ays-survey-section-description').attr('name', newSectionAttrName( sectionId, 'description' ));
            element.find('.ays-survey-section-ordering').attr('name', newSectionAttrName( sectionId, 'ordering' ));
            element.find('.ays-survey-section-collapsed-input').attr('name', newSectionAttrName( sectionId, 'options', 'collapsed' ));
            element.find('.ays-survey-delete-section').removeClass("display_none");
            element.find('.dropdown-menu.dropdown-menu-right').removeClass("show");
            element.find('input[name*="ays_sections_ids"]').remove();
            return element;
        }

        function aysSetNewQuestionNames(element, sectionId, questionId){
            element.removeClass('ays-survey-old-question');
            element.addClass('ays-survey-new-question');
            element.attr('data-id', questionId);
            element.attr('data-name', 'questions_add');
            var question_type = element.find('.ays-survey-check-type-before-change').val();
            
            element.find('textarea.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'title'));
            element.find('.ays-survey-question-type select').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'type'));
            element.find('.ays-survey-question-img-src').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'image'));
            element.find('.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'user_variant'));
            element.find('.ays-survey-input-required-question').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'required'));
            element.find('.ays-survey-question-collapsed-input').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'collapsed'));
            element.find('.ays-survey-question-ordering').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'ordering'));
            element.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_max_selection_count'));
            element.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'max_selection_count'));
            element.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            element.find('.ays-survey-question-word-limitations-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_word_limitation'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_by'));
            element.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_length'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'limit_counter'));
            // Number limitation options start
            element.find('.ays-survey-question-number-limitations-checkbox').attr('name', newQuestionAttrName('ays_section_add', sectionId, questionId, 'options', 'enable_number_limitation'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_min_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_max_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'number_limit_length'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'enable_number_limit_counter'));
            // Number limitation options end

            // Input types placeholders
            element.find('.ays-survey-question-types-input-with-placeholder').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'placeholder'));

            
            element.find('.ays-survey-question-image-caption').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'image_caption'));
            element.find('.ays-survey-question-img-caption-enable').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'image_caption_enable'));

            element.find('.ays-survey-open-question-editor-flag').attr('name', newQuestionAttrName( 'ays_section_add', sectionId, questionId, 'options', 'with_editor'));
            element.find('.ays-survey-question-ordering').val(questionId);
            
            element.find('.ays-survey-question-type').aysDropdown('set selected', question_type);
            element.find('.ays-survey-question-type').aysDropdown('set value', question_type);
            element.find('.ays-survey-question-type').aysDropdown();
        }

        function aysSetNewAnswerNames(element, sectionId, questionId, answerId){
            element.addClass('ays-survey-new-answer');
            element.attr('data-id', answerId);
            element.find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'title'));
            element.find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'image'));
            element.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( 'ays_section_add', sectionId, 'questions_add', questionId, answerId, 'ordering'));           
        }

        function aysSurveyQuestionType_Radio_Checkbox_Select_Html( sectionId, questionId, questionDataName, questionType, questionTypeBeforeChange, returnElem = false, sectionElem = null ){

            var removeHtml = true; // Remove Html
            if(questionTypeBeforeChange == 'radio' || questionTypeBeforeChange == 'select' || questionTypeBeforeChange == 'checkbox'){
                removeHtml = false;
            }

            var section = $(document).find('.ays-survey-sections-conteiner .ays-survey-section-box[data-id="'+sectionId+'"]');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner .ays-survey-answers-conteiner');
            var cloningElement_2 = $(document).find('.ays-question-to-clone .ays-survey-question-answer-conteiner .ays-survey-other-answer-and-actions-row');

            if( questionType == 'yesorno' ){
                cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-type-yes-or-no');
                removeHtml = true;
            }

            var clonedElement = cloningElement.clone( true, false );
            var clonedElement_2 = cloningElement_2.clone( true, false );
            
            var answers = clonedElement.find('.ays-survey-answer-row');
            clonedElement.sortable(answerDragHandle);

            var questionName = questionDataName;
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            question.find('.ays-survey-question-word-limitations').addClass('display_none');
            question.find('.ays-survey-question-number-limitations').addClass('display_none');
            var placeholderVal = '';
            var questionTypeIconClass = '';
            var answer_icon = clonedElement.find('.ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
            var other_answer_icon = clonedElement_2.find('.ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
            switch( questionType ){
                case 'radio':
                case 'yesorno':
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
                break;
                case 'checkbox':
                    questionTypeIconClass = SurveyMakerAdmin.icons.checkboxUnchecked;
                break;
                case 'select':
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
                break;
                default:
                    questionTypeIconClass = SurveyMakerAdmin.icons.radioButtonUnchecked;
            }

            other_answer_icon.attr('src', questionTypeIconClass);
            answer_icon.attr('src', questionTypeIconClass);

            clonedElement_2.find('.ays-survey-other-answer-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'user_variant'));
            clonedElement_2.find('.ays-survey-question-max-selection-count-checkbox').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'enable_max_selection_count'));
            clonedElement_2.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', newQuestionAttrName( sectionName, sectionId, questionId, 'options', 'max_selection_count'));

            answers.each(function(i){
                var answerId = i+1;
                $(this).addClass('ays-survey-new-answer');
                $(this).attr('data-id', answerId);
                $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'title'));
                $(this).find('.ays-survey-answer-img-src').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'image'));
                $(this).find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, 'ordering'));
            });

            if( questionType == 'select' ){
                clonedElement_2.find('.ays-survey-other-answer-add-wrap').hide();
                clonedElement_2.find('.ays-survey-other-answer-row').hide();
                clonedElement_2.find('.ays-survey-other-answer-checkbox').prop('checked', false );
                question.find('.ays-survey-other-answer-add-wrap').hide();
                question.find('.ays-survey-other-answer-checkbox').prop('checked', false );
            }else{
                clonedElement_2.find('.ays-survey-other-answer-add-wrap').show();
                if( question.find('.ays-survey-other-answer-checkbox').prop('checked') == true ){
                    question.find('.ays-survey-other-answer-add-wrap').hide();
                }else{
                    question.find('.ays-survey-other-answer-add-wrap').show();
                }
            }

            var enableMaxSelectionCount = question.find('.ays-survey-question-max-selection-count-checkbox').prop('checked');
            if( questionType == "checkbox" && enableMaxSelectionCount ){
                question.find('.ays-survey-question-more-option-wrap').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-more-option-wrap').addClass('display_none');
            }

            if( questionType != "checkbox" ){
                question.find('.ays-survey-question-actions-pro[data-action="go-to-section-based-on-answers-enable"]').removeClass('display_none');                
            }
            
            
            question.find('.ays-survey-question-more-actions').removeClass('display_none');
            

            if( returnElem ){
                clonedElement = $( clonedElement.html() );
                var clonedElementArr = new Array(clonedElement, clonedElement_2);
                return clonedElementArr;
            }else{
                // Remove Html
                if (removeHtml) {
                    clonedElement = $( clonedElement.html() );
                    section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answers-conteiner').html(clonedElement);
                }else{
                    var answer_icon_tags = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answer-icon-box.ays-survey-answer-icon-just img');
                    switch( questionType ){
                        case 'radio':
                        case 'yesorno':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                        break;
                        case 'checkbox':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.checkboxUnchecked);
                        break;
                        case 'select':
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                        break;
                        default:
                            answer_icon_tags.attr('src', SurveyMakerAdmin.icons.radioButtonUnchecked);
                    }
                }
                
                if( questionType == 'select' || questionType == 'checkbox' || questionType == 'radio' || questionType == 'yesorno'){
                    var addAnswerRow = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-other-answer-and-actions-row');
                    clonedElement_2.find('.ays-survey-other-answer-row .ays-survey-answer-icon-box .invisible').removeClass( 'invisible' );
                    
                    if ( removeHtml || questionType == 'select' ) {
                        addAnswerRow.html(clonedElement_2.html());
                    }
                }
            }
        }

        function aysSurveyQuestionType_Text_ShortText_Number_Html( sectionId, questionId, questionDataName, questionType, returnElem = false, sectionElem = null ){
            var section = sectionElem;
            var cloningElement = $(document).find('.ays-question-to-clone .ays-survey-question-types');
            var question = section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"]');
            var clonedElement = cloningElement.clone( true, false );
            
            var answers = clonedElement.find('.ays-survey-answer-row');

            var questionName = questionDataName;
            var sectionName = section.attr('data-name');
            if( sectionElem !== null ){
                sectionName = sectionElem.attr('data-name');
            }
            

            var placeholderVal = '';
            var questionTypeClass = '';
            placeholderVal = SurveyMakerAdmin.inputAnswerText;
            switch( questionType ){
                case 'text':
                    questionTypeClass = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
                break;
                case 'short_text':
                    questionTypeClass = 'ays-survey-question-type-short-text-box ays-survey-question-type-all-text-types-box';
                break;
                case 'number':
                    questionTypeClass = 'ays-survey-question-type-number-box ays-survey-question-type-all-text-types-box';
                break;
                case 'email':
                    placeholderVal = SurveyMakerAdmin.emailField;
                    questionTypeClass = 'ays-survey-question-type-email-box ays-survey-question-type-all-text-types-box';
                break;
                case 'name':
                    placeholderVal = SurveyMakerAdmin.nameField;
                    questionTypeClass = 'ays-survey-question-type-name-box ays-survey-question-type-all-text-types-box';
                break;
                default:
                    placeholderVal = SurveyMakerAdmin.inputAnswerText;
                    questionTypeClass = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
            }

            var enableWordLimitation = question.find('.ays-survey-question-word-limitations-checkbox').prop('checked');
            if( (questionType == "short_text" || questionType == "text") && enableWordLimitation ){
                question.find('.ays-survey-question-word-limitations').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-word-limitations').addClass('display_none');
            }

            var enableNumberLimitation = question.find('.ays-survey-question-number-limitations-checkbox').prop('checked');
            if( questionType == "number" && enableNumberLimitation ){
                question.find('.ays-survey-question-number-limitations').removeClass('display_none');
            }else{
                question.find('.ays-survey-question-number-limitations').addClass('display_none');
            }
            
            // if( questionType == "short_text" || questionType == "text" || questionType == "number"){
            //     question.find('.ays-survey-question-more-actions').removeClass('display_none');
            // }else{
            //     question.find('.ays-survey-question-more-actions').addClass('display_none');
            // }
            // question.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').aysDropdown();
            // question.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').aysDropdown();



            var answerId = answers.find('input.ays-survey-question-types-input').length;
            answers.attr('data-id', answerId);
            answers.find('input.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'placeholder'));
            answers.find('input.ays-survey-question-types-input').attr('placeholder', placeholderVal);
            answers.find('input.ays-survey-question-types-input').val(placeholderVal);
            answers.find('.ays-survey-question-types-box').addClass(questionTypeClass);

            // question.find('.ays-survey-question-more-actions').addClass('display_none');
            question.find('.ays-survey-question-more-option-wrap').addClass('display_none');            
            question.find('.ays-survey-question-actions-pro[data-action="go-to-section-based-on-answers-enable"]').addClass('display_none');
            
            if( returnElem ){
                return clonedElement;
            }else{
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-answers-conteiner').html(clonedElement);
                section.find('.ays-survey-question-answer-conteiner[data-id="'+questionId+'"][data-name="'+questionDataName+'"] .ays-survey-other-answer-and-actions-row').html('');
            }
        }

        function aysSurveyAddAnswer( currentAnswer, afterAnswer ){
            var $this = currentAnswer;
            var section = $this.parents('.ays-survey-section-box');
            var sectionId = section.attr('data-id');
            var questsCont = $this.parents('.ays-survey-question-answer-conteiner');
            var itemId = questsCont.data('id');
            var answersCont = questsCont.find('.ays-survey-answers-conteiner');
            var cloningElement = answersCont.find('.ays-survey-answer-row:first-child');
            var id = cloningElement.data('id');
            var answerLength = answersCont.find('.ays-survey-answer-row').length + 1;

            var clone = cloningElement.clone(true, false).attr('data-id', answerLength);

            if( afterAnswer === true ){
                clone.insertAfter(currentAnswer.parents('.ays-survey-answer-row'));
            }else{
                clone.appendTo(answersCont);
            }

            questsCont = clone.parents('.ays-survey-question-answer-conteiner');
            itemId = questsCont.data('id');
            var answerContainer = questsCont.find('.ays-survey-answers-conteiner');
            var length = answerContainer.find('.ays-survey-answer-row').length;
            var clonedElem = clone.find('.ays-survey-input');
            var clonedElemInp = clonedElem.val('Option '+ length).attr('placeholder', 'Option '+ length);

            clonedElem.attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'title'));
            // clonedElem.find('.aysAnswerImgConteiner').html('');
            // clonedElemInp.select();
            clonedElemInp.trigger('focus');

            clone.addClass('ays-survey-new-answer');
            clone.find('.ays-survey-answer-image-container').hide();
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img').removeAttr('src');
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').val('');
            clone.find('.ays-survey-answer-image-container .ays-survey-answer-img-src').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'image'));
            clone.find('.ays-survey-answer-ordering').attr('name', newAnswerAttrName( section.attr('data-name'), sectionId, questsCont.attr('data-name'), itemId, length, 'ordering'));

            answersCont.find('.ays-survey-answer-ordering').each(function(i){
                $(this).val( i + 1 );
            });

            // clone.find('.ays-survey-answer-ordering').val( length );
            var deleteButton = answerContainer.find('.ays-survey-answer-delete');
            if(length == 1){
                deleteButton.css('visibility', 'hidden');
            }else{
                deleteButton.removeAttr('style');
            }
            answerContainer.sortable(answerDragHandle);
            reInitPopovers( clone );
        }

        function draggedQuestionUpdate( element, section, oldSection){

            
            var questionsLength = section.find('.ays-survey-question-answer-conteiner').length;
            var questionsCount = questionsLength;
            var questionsCountBox = section.find(".ays-survey-action-questions-count span").text(questionsCount);

            if(oldSection){
                var oldQuestionsLength = oldSection.find('.ays-survey-question-answer-conteiner').length;
                var oldQuestionsCount = oldQuestionsLength;
                var questionsCountBox = oldSection.find(".ays-survey-action-questions-count span").text(oldQuestionsCount);
            }

            var answers = element.find('.ays-survey-answers-conteiner .ays-survey-answer-row');
            var questionName = element.attr('data-name');
            var questionId = element.attr('data-id');
            if( element.hasClass('ays-survey-new-question') ){
                questionId = section.find('.ays-survey-question-answer-conteiner[data-name="questions_add"]').length;
            }
            
            var sectionName = section.attr('data-name');
            var sectionId = section.attr('data-id');

            element.find('textarea.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'title'));
            element.find('.ays-survey-question-type-box select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'type'));
            element.find('.ays-survey-question-img-src').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'image'));
            element.find('.ays-survey-other-answer-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'user_variant'));
            element.find('.ays-survey-input-required-question').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'required'));
            element.find('.ays-survey-question-collapsed-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'collapsed'));
            element.find('.ays-survey-question-ordering').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'ordering'));
            element.find('.ays-survey-question-max-selection-count-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_max_selection_count'));
            element.find('.ays-survey-question-max-selection-count input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'max_selection_count'));
            element.find('.ays-survey-question-min-selection-count input.ays-survey-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'min_selection_count'));
            // Text limitation options
            element.find('.ays-survey-question-word-limitations-checkbox').attr('name', updateQuestionAttrName(sectionName, sectionId, questionName, questionId, 'options', 'enable_word_limitation'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-question-word-limit-by-select select').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_by'));
            element.find('.ays-survey-question-more-option-wrap-limitations input.ays-survey-limit-length-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_length'));
            element.find('.ays-survey-question-more-option-wrap-limitations .ays-survey-text-limitations-counter-input').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'limit_counter'));
            // Number limitation options start
            element.find('.ays-survey-question-number-limitations-checkbox').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_limitation'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-min-votes').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_min_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-max-votes').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_max_selection'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-error-message').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-enable-error-message').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_error_message'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-limit-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'number_limit_length'));
            element.find('.ays-survey-question-number-limitations input.ays-survey-number-number-limit-length').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'enable_number_limit_counter'));
            // Number limitation options end

            // Input types placeholders
            element.find('.ays-survey-remove-default-border.ays-survey-question-types-input.ays-survey-question-types-input-with-placeholder').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'placeholder'));
            
            element.find('.ays-survey-question-image-caption').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'image_caption'));
            element.find('.ays-survey-question-img-caption-enable').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'image_caption_enable'));

            element.find('.ays-survey-open-question-editor-flag').attr('name', updateQuestionAttrName( sectionName, sectionId, questionName, questionId, 'options', 'with_editor'));

            answers.each(function(){
                var answerId = $(this).attr('data-id');
                var answerName = $(this).hasClass('ays-survey-new-answer') ? 'answers_add' : 'answers';
                $(this).find('.ays-survey-answer-box input.ays-survey-input').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'title'));
                $(this).find('.ays-survey-answer-img-src').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'image'));
                $(this).find('.ays-survey-answer-ordering').attr('name', updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, 'ordering'));
            });

            setTimeout(function(){
                element.goToTop();
            }, 100 );
        }

        function newSectionAttrName(sectionId, field, field2 = null){
            if(field2 !== null){
                return $html_name_prefix + 'section_add['+ sectionId +']['+ field +']['+ field2 +']';
            }
            return $html_name_prefix + 'section_add['+ sectionId +']['+ field +']';
        }

        function newQuestionAttrName(sectionName, sectionId, questionId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +'][questions_add]['+ questionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +'][questions_add]['+ questionId +']['+ field +']';
        }

        function newAnswerAttrName( sectionName, sectionId, questionName, questionId, answerId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][answers_add]['+ answerId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +'][answers_add]['+ answerId +']['+ field +']';
        }

        function updateSectionAttrName( sectionName, sectionId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ field +']';
        }

        function updateQuestionAttrName( sectionName, sectionId, questionName, questionId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ field +']';
        }

        function updateAnswerAttrName( sectionName, sectionId, questionName, questionId, answerName, answerId, field, field2 = null){
            if(field2 !== null){
                return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ answerName +']['+ answerId +']['+ field +']['+ field2 +']';
            }
            return sectionName + '['+ sectionId +']['+ questionName +']['+ questionId +']['+ answerName +']['+ answerId +']['+ field +']';
        }

        function reInitPopovers( parentElement ){
            parentElement.find('[data-toggle="popover"]').each(function(){
                $(this).popover();
            });
        }
        
        function aysSurveySectionsInitToMoveQuestions( currentSection, currentQuestion ){
            var popup = $(document).find('#ays-survey-move-to-section');
            var currentSectionId = popup.attr('data-section-id');
            var sectionCont = $(document).find('.ays-survey-sections-conteiner');
            var sections = sectionCont.find('.ays-survey-section-box');
            var moveSectionsCont = popup.find('.ays-survey-move-to-section-sections-wrap');
            moveSectionsCont.html('');

            sections.each(function(i){
                var _this = $(this);
                var sectionQuestionsCollapsedInputs = _this.find('.ays-survey-question-collapsed-input');
                var sectionQuestionsCollapsedValues = [];
                var sectionQuestionsExpandedValues = [];
                sectionQuestionsCollapsedInputs.each(function(){
                    if( $(this).val() == 'expanded' ){
                        sectionQuestionsExpandedValues.push( true );
                    }else{
                        sectionQuestionsCollapsedValues.push( true );
                    }
                });

                var collapseButtonText = SurveyMakerAdmin.collapseSectionQuestions;
                if( sectionQuestionsExpandedValues.length == 0 ){
                    collapseButtonText = SurveyMakerAdmin.expandSectionQuestions;
                }

                var disabled = '';
                if( currentSectionId == _this.attr('data-id') ){
                    disabled = ' disabled ';
                }

                var newClassToAddQuestion = '';
                if( _this.hasClass('ays-survey-new-section') ){
                    newClassToAddQuestion = 'ays-survey-move-new-question-into-section';
                }

                _this.find('.ays-survey-collapse-section-questions').text( collapseButtonText );

                var buttonItem = '<button class="dropdown-item ays-survey-move-question-into-section '+ newClassToAddQuestion +'" ' + disabled + ' data-id="'+ $(this).data('id') +'" type="button">';
                buttonItem += aysCreateSectionName( _this, i+1, SurveyMakerAdmin.moveToSection ); //SurveyMakerAdmin.addIntoSection + ' ' + (i+1);
                buttonItem += '</button>';
                moveSectionsCont.append(buttonItem);
            });
        }
        
        function aysCreateSectionName( section, index, text ){
            var sectionName = section.find('.ays-survey-section-title').val();
            var name = text + ' ' + index;
            if( sectionName == '' ){
                name +=  ' (Untitled Form) ';
            }else{
                name +=  ' ('+ sectionName +') ';
            }
            return name;
        }


        $(document).find('#ays_select_surveys').select2({
            allowClear: true,
            placeholder: false
        });
        
        $(document).find('#ays_user_roles').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectUserRoles
        });

        $(document).find('#ays_survey_default_type').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectQuestionDefaultType
        });

        $(document).find('.ays-survey-submission-select').aysDropdown();

        $(document).find('.ays-survey-sections-conteiner .ays-survey-question-type').aysDropdown({
            // allowClear: true,
            // placeholder: false
        });

        /* CONTINUE FROM HERE */
        // $(document).find('.ays-survey-sections-conteiner .ays-survey-question-type').aysDropdown({
        //     // allowClear: true,
        //     // placeholder: false
        // });

        $(document).find('.ays-survey-sections-conteiner .dropdown .item[data-value="email"]').before('<div class="divider"></div>');
        $(document).find('.ays-survey-sections-conteiner .dropdown  .item[data-value="matrix_scale"]').before('<div class="divider"></div>');
        


        // ===============================================================
        // ======================      Ani      ==========================
        // ===============================================================

        var count = 0;
        $(document).find('.ays_survey_previous_next').on('click',function(){
            if($(this).attr('data-name') == 'ays_survey_next'){
                if($(document).find('.ays_number_of_result').val() == $(document).find('.ays_number_of_result').attr('max')){
                    count = $(document).find('.ays_number_of_result').attr('min');
                    $(document).find('.ays_number_of_result').val(count);
                }else{
                    var selectVal = parseInt($(document).find('.ays_number_of_result').val());
                    count = selectVal + 1;
                    $(document).find('.ays_number_of_result').val(count);
                }
            }else if($(this).attr('data-name') == 'ays_survey_previous'){
                if($(document).find('.ays_number_of_result').val() == $(document).find('.ays_number_of_result').attr('min')){
                    count = $(document).find('.ays_number_of_result').attr('max');
                    $(document).find('.ays_number_of_result').val(count);
                }else{
                    var selectVal = parseInt($(document).find('.ays_number_of_result').val());
                    count = selectVal - 1;
                    $(document).find('.ays_number_of_result').val(count);
                }
            }
            $(document).find('.ays_number_of_result').trigger('change');
        });
        
        $(document).find('.ays_number_of_result').on('change', refreshSubmissionData);
        // $(document).find('.ays_number_of_result').on('input', refreshSubmissionData);

        function refreshSubmissionData(){
            var submissionPrevVal;
            var $this  = $(this);

            var parent = $(this).parents('.ays_survey_previous_next_conteiner');
            var mainParent = $this.parents('.ays_survey_container_each_result');
            var submissionInputs = mainParent.find(".ays_number_of_result ");
            var currentNumber = $this.val();
            if(+$this.val() > +$this.attr("max") || $this.val() == "" || +$this.val() == 0){
                currentNumber = $this.attr("max");
            }
            submissionInputs.val(currentNumber);
            
            var submissionIdStr = parent.find('.ays_submissions_id_str').val();
            var submissionIdArr = submissionIdStr.split(",");
            var submissionElVal = parseInt(parent.find('.ays_number_of_result').val());
            var surveyId = $(document).find('.ays_number_of_result').attr('data-id');
            var submissionId = '';
            var data = {};
            
            if(submissionElVal < 0){
                submissionElVal = 1;
                parseInt(parent.find('.ays_number_of_result').val(1));
            }else if(submissionElVal > parseInt(parent.find('.ays_number_of_result').attr('max'))){
                var maxVal = parseInt(parent.find('.ays_number_of_result').attr('max'));
                parseInt(parent.find('.ays_number_of_result').val(maxVal));
                submissionElVal = maxVal;
            }
            
            if(submissionElVal>submissionPrevVal || submissionElVal+1){
                submissionId = submissionIdArr[submissionElVal-1];
            }else{
                submissionId = submissionIdArr[submissionElVal+1];
            } 
            
            data.action = 'ays_survey_submission_report';
            data.submissionId = submissionId;
            data.surveyId = surveyId;
            data.nonce = SurveyMakerAdmin.nonce;    
            var preloader = $(this).parents('.ays_survey_container_each_result').find('.question_result_container').find('div.ays_survey_preloader');
            preloader.css({'display':'flex'});
            $.ajax({
                url: ajaxurl,
                dataType: 'json',
                data: data,
                method: 'post',
                success: function(response){
                    if (response.status) {
                        preloader.css({'display':'none'});
                        var questionsData = response.questions;
                        var questionsInHTML = $(document).find('.ays_questions_answers');
                        $(document).find(".ays_survey_each_sub_user_info_name").html(response.user_info.user_name);
                        $(document).find(".ays_survey_each_sub_user_info_email").html(response.user_info.user_email);
                        $(document).find(".ays_survey_each_sub_user_info_user_ip").html(response.user_info.user_ip);
                        $(document).find(".ays_survey_each_sub_user_info_sub_date").html(response.user_info.submission_date);
                        $(document).find(".ays_survey_each_sub_user_info_sub_id").html(response.user_info.id);
                        $(document).find(".ays_survey_each_sub_user_info_header_button button").attr("data-clipboard-text", response.user_info_for_copy);
                        $.each(questionsInHTML, function(){
                            var question = $(this);
                            var qId = question.data('id');
                            var qType = question.data('type');
                            
                            question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                            question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                            question.find('.ays_each_question_answer .ays-survey-submission-select option').removeAttr('selected');
                            question.find('.ays_text_answer').html('');

                            if( typeof questionsData[qId] != 'undefined'){
                                var surveyAnswer = questionsData[qId];
                                switch( qType ){
                                    case 'radio':
                                    case 'yesorno':
                                        surveyAnswer = questionsData[qId].answer;
                                        var surveyOtherAnswer = questionsData[qId].otherAnswer;
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="radio"]').each(function(){
                                            var thisParent = $(this).parents(".ays_each_question_answer");
                                            if( surveyAnswer == $(this).data('id') ){

                                                if(thisParent.hasClass("ays-survey-answer-label-other") && surveyOtherAnswer != ''){
                                                    $(this).prop('checked', true);
                                                }
                                                else if(!thisParent.hasClass("ays-survey-answer-label-other")){
                                                    $(this).prop('checked', true);
                                                }
                                                
                                            }
                                        });
                                        
                                        question.find('.ays-survey-answer-other-input').html((surveyOtherAnswer));
                                        break;
                                    case 'checkbox':
                                        surveyAnswer = questionsData[qId].answer;
                                        var surveyOtherAnswer = questionsData[qId].otherAnswer;
                                        question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                                        question.find('.ays_each_question_answer input[type="checkbox"]').each(function(){
                                            if( Array.isArray(surveyAnswer) ){
                                                for(var i=0; i < surveyAnswer.length; i++){
                                                    if( surveyAnswer[i] == $(this).data('id') ){
                                                        $(this).prop('checked', true);
                                                    }
                                                }
                                            }else{
                                                if( surveyAnswer == $(this).data('id') ){
                                                    $(this).prop('checked', true);
                                                }
                                            }
                                        });
                                        question.find('.ays-survey-answer-other-input').html((surveyOtherAnswer));
                                        break;
                                    case 'select':
                                        if( parseInt( surveyAnswer ) == 0 ){
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('clear');
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', '');
                                        }else{
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set selected', surveyAnswer);
                                            question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', surveyAnswer);
                                        }
                                        break;
                                    case 'text':
                                        if( typeof surveyAnswer !== 'string' ){
                                            surveyAnswer = '';
                                        }
                                        surveyAnswer = nl2br( surveyAnswer );
                                    case 'short_text':
                                    case 'number':
                                    case 'name':
                                    case 'email':
                                        var elem = question.find('.ays_text_answer');
                                        elem.html( surveyAnswer );
                                        break;
                                }
                            }else{
                                switch( qType ){
                                    case 'radio':
                                    case 'yesorno':
                                        question.find('.ays_each_question_answer input[type="radio"]').removeAttr('checked');
                                        question.find('.ays-survey-answer-other-input').val('');
                                        break;
                                    case 'checkbox':
                                        question.find('.ays_each_question_answer input[type="checkbox"]').removeAttr('checked');
                                        question.find('.ays-survey-answer-other-input').val('');
                                        break;
                                    case 'select':
                                        question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('clear');
                                        question.find('.ays_each_question_answer .ays-survey-submission-select').aysDropdown('set value', '');
                                        break;
                                    case 'text':
                                    case 'short_text':
                                    case 'number':
                                    case 'name':
                                    case 'email':
                                        var elem = question.find('.ays_text_answer');
                                        elem.html( '' );
                                        break;
                                }
                            }
                        });
                    }
                }
            });
            submissionPrevVal = submissionElVal;
        }



        // ===============================================================
        // ======================      Xcho      =========================
        // ===============================================================


        $('#ays_slack_client').on('input', function () {
            var clientId = $(this).val();
            if (clientId == '') {
                $("#slackOAuth2").addClass('disabled btn-outline-secondary');
                $("#slackOAuth2").removeClass('btn-secondary');
                return false;
            }
            var scopes = "channels%3Ahistory%20" +
                "channels%3Aread%20" +
                "channels%3Awrite%20" +
                "groups%3Aread%20" +
                "groups%3Awrite%20" +
                "mpim%3Aread%20" +
                "mpim%3Awrite%20" +
                "im%3Awrite%20" +
                "im%3Aread%20" +
                "chat%3Awrite%3Abot%20" +
                "chat%3Awrite%3Auser";
            var url = "https://slack.com/oauth/authorize?client_id=" + clientId + "&scope=" + scopes + "&state=" + clientId;
            $("#slackOAuth2").attr('data-src', url);//.toggleClass('disabled btn-outline-secondary btn-secondary');
            $("#slackOAuth2").removeClass('disabled btn-outline-secondary');
            $("#slackOAuth2").addClass('btn-secondary');
        });
        $("#slackOAuth2").on('click', function () {
            var url = $(this).attr('data-src');
            if (!url) {
                return false;
            }
            location.replace(url)
        });
        $('#ays_slack_secret').on('input', function(e) {
            if($(this).val() == ''){
                $("#slackOAuthGetToken").addClass('disabled btn-outline-secondary');
                $("#slackOAuthGetToken").removeClass('btn-secondary');
                return false;
            }
            
            $("#slackOAuthGetToken").removeClass('disabled btn-outline-secondary');
            $("#slackOAuthGetToken").addClass('btn-secondary');
        });
        
        // Limitations PRO
        $('#ays_survey_users_roles,#ays_survey_users_pro').select2();

        $(document).find('#ays_survey_default_type').select2({
            allowClear: true,
            placeholder: SurveyMakerAdmin.selectQuestionDefaultType
        });

        $(document).find(".ays-survey-min-votes-field").on("input" , function(){
            var $this = $(this);
            var minVal = parseInt($(this).val());
            var maxField = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-max-votes-field");
            if(minVal >= maxField.val())
            maxField.val(minVal);
        });

        $(document).find(".ays-survey-max-votes-field").on("change" , function(){
            var $this = $(this);
            var maxVal = parseInt($(this).val());
            var minField = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-min-votes-field");
            var minVal = $this.parents(".ays-survey-question-more-options-wrap").find(".ays-survey-min-votes-field").val();
            if(maxVal == ''){
                return;
            }
            if(maxVal < minVal){
                $this.val(minVal);
            }
        });

        $(document).on('click', '.ays_toggle_loader_radio', function (e) {
            var dataFlag = $(this).attr('data-flag');
            var dataType = $(this).attr('data-type');
            var state = false;
            if (dataFlag == 'true') {
                state = true;
            }

            var parent = $(this).parents('.ays_toggle_loader_parent');
            if($(this).hasClass('ays_toggle_loader_slide')){
                switch (state) {
                    case true:
                        parent.find('.ays_toggle_loader_target').slideDown(250);
                        break;
                    case false:
                        parent.find('.ays_toggle_loader_target').slideUp(250);
                        break;
                }
            }else{
                switch (state) {
                    case true:                        
                        switch( dataType ){
                            case 'text':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').show(250);
                                parent.find('.ays_toggle_loader_target[data-type="gif"]').hide(250);
                            break;
                            case 'gif':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').show(250);
                                parent.find('.ays_toggle_loader_target.ays_gif_loader_width_container[data-type="'+ dataType +'"]').css({
                                    'display': 'flex',
                                    'justify-content': 'center',
                                    'align-items': 'center'
                                });
                                parent.find('.ays_toggle_loader_target[data-type="text"]').hide(250);
                            break;
                            default:
                                parent.find('.ays_toggle_loader_target').show(250);
                            break;
                        }
                        break;
                    case false:                       
                        switch( dataType ){
                            case 'text':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').hide(250);
                            break;
                            case 'gif':
                                parent.find('.ays_toggle_loader_target[data-type="'+ dataType +'"]').hide(250);
                            break;
                            default:
                                parent.find('.ays_toggle_loader_target').hide(250);
                            break;
                        }
                        break;
                }
            }
        });

        $(document).find(".add_survey_loader_custom_gif,.ays-edit-survey-loader-custom-gif").on("click" , function(e){
            openMediaUploaderForGifLoader(e, $(this));
        });

        $(document).on('click', '.ays-survey-image-wrapper-delete-wrap', function () {
            var wrap = $(this).parents('.ays-survey-image-wrap');
            wrap.find('.ays-survey-image-container').fadeOut(500);
            setTimeout(function(){
                wrap.find('img.ays_survey_img_loader_custom_gif').attr('src', '');
                wrap.find('input.ays-survey-image-path').val('');
                wrap.find('button.add_survey_loader_custom_gif').show();
            }, 450);
        });

        $(document).find("#ays-survey-form .nav-tab").on("click" , function(){
            var $this = $(this);
            if($this.data("tab") != "undefined" && $this.data("tab") == 'tab1'){
                refreshSurveyColor($this);
            }
        });        

        function refreshSurveyColor(element){
            var customCss = $(document).find("#ays-survey-custom-css-additional");
            var parentElement = element.parents("#ays-survey-form");
            var surveyColor = parentElement.find("#ays_survey_color").val();
            var newCss = '#ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head {'
                    newCss += 'border-top-color: '+surveyColor;
                newCss += '}';
                newCss += '#ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head-top .ays-survey-section-counter {'
                    newCss += 'background-color: '+surveyColor;
                newCss += '}';
                newCss += '#ays-survey-form .ays-survey-input:focus ~ .ays-survey-input-underline-animation {'
                    newCss += 'background-color: '+surveyColor;
                newCss += '}';
                newCss += '#ays-survey-form .dropdown-item:hover {'
                    newCss += 'background-color: '+surveyColor+'29;';
                newCss += '}';
                newCss += '#ays-survey-form .dropdown-item:focus {'
                    newCss += 'background-color: '+surveyColor+'29;';
                newCss += '}';
                newCss += '#ays-survey-form .dropdown-item:active {'
                    newCss += 'background-color: '+surveyColor+';';
                newCss += '}';
                newCss += '#ays-survey-form .switch-checkbox-thumb {'
                    newCss += 'border-color: '+surveyColor;
                newCss += '}';
            customCss.html(newCss);
        }

        var checkCountdownIsExists = $(document).find('#ays-survey-maker-countdown-main-container');
        if ( checkCountdownIsExists.length > 0 ) {
            var second  = 1000,
                minute  = second * 60,
                hour    = minute * 60,
                day     = hour * 24;

            var countdownEndTime = SurveyMakerAdmin.surveyBannerDate,
            // var countdownEndTime = "DEC 31, 2022 23:59:59",
            countDown = new Date(countdownEndTime).getTime(),
            x = setInterval(function() {

                var now = new Date().getTime(),
                    distance = countDown - now;

                var countDownDays    = document.getElementById("ays-survey-countdown-days");
                var countDownHours   = document.getElementById("ays-survey-countdown-hours");
                var countDownMinutes = document.getElementById("ays-survey-countdown-minutes");
                var countDownSeconds = document.getElementById("ays-survey-countdown-seconds");

                if(countDownDays !== null || countDownHours !== null || countDownMinutes !== null || countDownSeconds !== null){
                    countDownDays.innerText = Math.floor(distance / (day)),
                    countDownHours.innerText = Math.floor((distance % (day)) / (hour)),
                    countDownMinutes.innerText = Math.floor((distance % (hour)) / (minute)),
                    countDownSeconds.innerText = Math.floor((distance % (minute)) / second);
                    // countDownDays.innerText     = Math.floor(distance / (day)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
                    // countDownHours.innerText    = Math.floor((distance % (day)) / (hour)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
                    // countDownMinutes.innerText  = Math.floor((distance % (hour)) / (minute)).toLocaleString(undefined,{minimumIntegerDigits: 2})+" : ",
                    // countDownSeconds.innerText  = Math.floor((distance % (minute)) / second).toLocaleString(undefined,{minimumIntegerDigits: 2});

                }

                //do something later when date is reached
                if (distance < 0) {
                    var headline  = document.getElementById("ays-survey-countdown-headline"),
                        countdown = document.getElementById("ays-survey-countdown"),
                        content   = document.getElementById("ays-survey-countdown-content");

                  // headline.innerText = "Sale is over!";
                  countdown.style.display = "none";
                  content.style.display = "block";

                  clearInterval(x);
                }
            }, 1000);
        }

        $(document).on('click', '.ays-survey-add-cover-image', function (e) {
            openMediaUploaderForCoverImage(e, $(this));
        });

        $(document).on('click', '.removeCoverImage', function (e) {
            var $this = $(this);
            var thisParent = $this.parents('.ays_survey_cover_image_main');
            thisParent.find('.ays-survey-add-cover-image').html( SurveyMakerAdmin.addImage );
            thisParent.find('.ays-survey-image-body').fadeOut(500);
            thisParent.find('.ays-survey-cover-image-options,.ays-survey-cover-image-options-hr').fadeOut(500);
            setTimeout(function(){
                $this.parents('.ays-survey-image-container').find('.ays-survey-cover-img').removeAttr('src');
                $this.parents('.ays-survey-image-container').find('.ays-survey-cover-img-src').val('');
            }, 500);
        });

        $(document).on('click', '.ays_survey_each_sub_user_info_header_button button', function () {
            selectElementContentsCopy($(this));
        });

        var surveyResults = $(document).find('.ays_survey_result');
        for (var i in surveyResults) {
            surveyResults.eq(i).parents('tr').addClass('ays_survey_results');
        }

        $(document).on("click", ".ays-survey-view-detailed-button", getEachSubmission);

        $(document).on("click", ".ays-survey-themes-main-div-only-pro", function(){
            window.open("https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" , "_blank");
        });

        $(document).on('click', '#ays-surveys-next-button,#ays-surveys-prev-button', function(e){
            e.preventDefault();
            var confirm;
            if($(e.target).hasClass("ays-survey-prev-survey-button")){
                confirm = window.confirm( SurveyMakerAdmin.prevSurveyPage );
            }
            else{
                confirm = window.confirm( SurveyMakerAdmin.nextSurveyPage );                    
            }
            if(confirm){
                window.location.replace($(this).attr('href'));
            }
        });

        $(document).find('#ays-survey-title').on('input', function(e){
            var surveyTitleVal = $(this).val();
            var surveyTitle = aysSurveystripHTML( surveyTitleVal );
            $(document).find('.ays_survey_title_in_top').html( surveyTitle );
        });

        // Select message vars surveys page
        $(document).find('.ays-survey-message-vars-icon').on('click', function(e){
            $(this).parents(".ays-survey-message-vars-box").find(".ays-survey-message-vars-data").toggle('fast');
        });

        $(document).find('.ays-survey-open-surveys-list').on('click', function(e){
            $(this).parents(".ays-survey-subtitle-main-box").find(".ays-survey-surveys-data").toggle('fast');
        });
        
        $(document).on( "click" , function(e){

            if($(e.target).closest('.ays-survey-message-vars-box,.ays-survey-subtitle-main-box').length != 0){
                
            } 
            else{
                $(document).find(".ays-survey-message-vars-box .ays-survey-message-vars-data,.ays-survey-subtitle-main-box .ays-survey-surveys-data").hide('fast');
            }
            // 
         });

        $(document).find(".ays-survey-go-to-surveys").on("click" , function(e){
            e.preventDefault();
            var confirmRedirect = window.confirm('Are you sure you want to redirect to another survey? Note that the changes made in this survey will not be saved.');
            if(confirmRedirect){
                window.location = $(this).attr("href");
            }
        });

        $(document).find('.ays-survey-message-vars-each-data').on('click', function(e){
            var messageVar = $(this).find(".ays-survey-message-vars-each-var").val();
            var mainParent = $(this).parents('.ays-survey-box-for-mv');
            var dataTMCE   = mainParent.find('.ays-survey-message-vars-data').attr('data-tmce');
            
            if ( mainParent.find("#wp-"+dataTMCE+"-wrap").hasClass("tmce-active") ){
                window.tinyMCE.get(dataTMCE).setContent( window.tinyMCE.get(dataTMCE).getContent() + messageVar + " " );
            }else{
                mainParent.find('#'+dataTMCE).append( " " + messageVar + " ");
            }
        });
        /* */

        $(document).find('.ays-survey-add-new-button-video').on('click', function(e){
            $(document).find(".page-title-action").trigger("click");
        });

        $(document).find('.ays-survey-aysDropdown-answer-view').on('change', function(e){
            var answerViewAligne = $(document).find('.ays-survey-aysDropdown-answer-view-alignment');
            answerViewAligne.aysDropdown('destroy');
            var answerViewType = $(this).aysDropdown('get value');
            
            var dataObj = {
                'list': [
                    {
                        name     : 'Left',
                        value    : 'flex-start',
                        selected : true
                    },
                    {
                        name     : 'Right',
                        value    : 'flex-end',
                    },
                    {
                        name     : 'Center',
                        value    : 'center',
                    }
                ],
                'grid': [
                    {
                        name     : 'Space around',
                        value    : 'space-around',
                        selected : true
                    },
                    {
                        name     : 'Space between',
                        value    : 'space-between',
                    },
                    {
                        name     : 'Left',
                        value    : 'flex-start',
                    },
                    {
                        name     : 'Right',
                        value    : 'flex-end',
                    },
                    {
                        name     : 'Center',
                        value    : 'center',
                    }
                ]
            }
            answerViewAligne.aysDropdown({
                values:  dataObj[answerViewType]
                });
        });

    });

})( jQuery );
