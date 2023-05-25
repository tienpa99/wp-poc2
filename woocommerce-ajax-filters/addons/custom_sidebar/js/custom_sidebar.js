var berocket_custom_sidebar_close,
berocket_custom_sidebar_open,
berocket_custom_sidebar_hide;
(function ($){
    berocket_custom_sidebar_close = function () {
        $('.berocket_ajax_filters_sidebar_toggle').removeClass( "active" );
        $('#berocket-ajax-filters-sidebar').removeClass('active');
        $('body').removeClass('berocket_ajax_filters_sidebar_active');
    }
    berocket_custom_sidebar_open = function () {
        $('.berocket_ajax_filters_sidebar_toggle').addClass( "active" );
        $('#berocket-ajax-filters-sidebar').addClass('active');
        $('body').addClass('berocket_ajax_filters_sidebar_active');
    }
    $(document).on('berocket_custom_sidebar_close', berocket_custom_sidebar_close);
    $(document).on('berocket_custom_sidebar_open', berocket_custom_sidebar_open);
    $(document).on('click', '.berocket_ajax_filters_sidebar_toggle', function (e){
        e.preventDefault();
        if( $(this).is('.active') && $('#berocket-ajax-filters-sidebar').is('.active') ) {
            berocket_custom_sidebar_close();
        } else {
            berocket_custom_sidebar_open();
        }
    });
    $(document).on('click', '#berocket-ajax-filters-sidebar-shadow, #berocket-ajax-filters-sidebar-close', function (e) {
        e.preventDefault();
        berocket_custom_sidebar_close();
    });
    $(document).ready(function() {
        berocket_custom_sidebar_hide();
        $(document).on('berocket_filters_document_ready', berocket_custom_sidebar_hide);
        $(document).on('berocket_ajax_products_loaded', berocket_custom_sidebar_hide);
    });
    berocket_custom_sidebar_hide = function() {
        if( $('#berocket-ajax-filters-sidebar').children().not('#berocket-ajax-filters-sidebar-close').not('.bapf_mt_none').length ) {
            $('.berocket_ajax_filters_sidebar_toggle').show();
        } else {
            $('.berocket_ajax_filters_sidebar_toggle').hide();
            $(document).trigger('berocket_custom_sidebar_close');
        }
    }
    bapf_custom_sidebar_close_on_filtering = function(xhr, url, send_data, callback_func, type) {
        console.log(type);
        if(type == 'default' && typeof(the_ajax_script) != 'undefined' && the_ajax_script.custom_sidebar_close) {
            $(document).trigger('berocket_custom_sidebar_close');
        }
        return xhr;
    }
    if ( typeof(berocket_add_filter) == 'function' ) {
        berocket_add_filter('ajax_load_from_url_beforeSend', bapf_custom_sidebar_close_on_filtering);
    } else {
        jQuery(document).on('berocket_hooks_ready', function() {
            berocket_add_filter('ajax_load_from_url_beforeSend', bapf_custom_sidebar_close_on_filtering);
        });
    }
})(jQuery);