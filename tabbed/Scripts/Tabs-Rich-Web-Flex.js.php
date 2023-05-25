<script>
//Tabs Accordion Manager Copy Funcion
function RW_Tabs_Man_Copy_Opt(Copied_ID,Copied_THEME) {
    var data = {
        action: 'RW_Tabs_Man_Copy_Opt', 
        Copied_ID: Copied_ID,
        rw_tabs_nonce_field : rwtabs_object.rw_tabs_ajax_wp_nonce,
    };
    jQuery.post(rwtabs_object.ajaxurl, data, function(dataResponseCopy) {
        jQuery('#RW-Tabs-Options-Manager').append(`<div class="RW_Tabs_Manager-Item" id="RW_Tabs_Manager-Item-${dataResponseCopy.id}" data-rw-search="${dataResponseCopy.Tabs_Name}"><div class="RW_Tabs_Item-Content"><section class="RW_Tabs_Item-Actions"><a class="rw_tabs_item_act RW_Tabs_Item_Edit_Act" href="?page=RW_Tabs_Create&rw_tab=${dataResponseCopy.id}"><i class="rich_web rich_web-pencil"></i></a><a class="rw_tabs_item_act RW_Tabs_Item_Copy_Act"  onclick="RW_Tabs_Man_Copy_Opt(${dataResponseCopy.id},'${Copied_THEME}')"><i class="rich_web rich_web-files-o"></i></a><a class="rw_tabs_item_act RW_Tabs_Item_Delete_Act"  onclick="RW_Tabs_Man_Delete_Opt(${dataResponseCopy.id})"><i class="rich_web rich_web-trash"></i></a><a class="rw_tabs_item_act RW_Tabs_Item_Prev_Act" href="<?php echo esc_url(home_url()); ?>?rw_tabs_preview=${dataResponseCopy.id}" target="blank"><i class="rich_web rich_web-eye"></i></a></section><section class="RW_Tabs_Item-Props RW_Tabs_Item-Props-Copied"><div class="RW_Tabs_Item-Props-Field"><span>Menu item counts</span><span class="RW_Tabs_Item-Field-Col">${dataResponseCopy.SubTitles_Count}</span></div><div class="RW_Tabs_Item-Props-Field"><span>Theme</span><span class="RW_Tabs_Item-Field-Col">${Copied_THEME}</span></div><div class="RW_Tabs_Item-Props-Field"><span>Created at</span><span class="RW_Tabs_Item-Field-Col">${dataResponseCopy.created_at}</span></div><div class="RW_Tabs_Item-Props-Field"><span>Updated at</span><span class="RW_Tabs_Item-Field-Col">${dataResponseCopy.updated_at}</span></div><div class="RW_Tabs_Item-Props-Field"><span>Shortcode</span><span class="RW_Tabs_Item-Field-Col">[Rich_Web_Tabs id="${dataResponseCopy.id}"] <i class="rich_web rich_web-files-o RW_Tabs_Short_Copy" data-rw-shortid="${dataResponseCopy.id}"></i></span></div></section><section class="RW_Tabs_Item-Name">${dataResponseCopy.Tabs_Name}</section></div></div>`);
        document.getElementById("RW_Tabs_Manager-Item-"+dataResponseCopy.id).scrollIntoView({behavior: "smooth", block: "end", inline: "nearest"});
        RW_Tabs_Message( `<i class="rich_web rich_web-check"></i> <b>Success:</b> Tabs Copied.`,'success');
    }); 
}

//Tabs Accordion Manager Delete Funcion
function RW_Tabs_Man_Delete_Opt(Deleted_ID) {
    jQuery('#RW_Tabs_Delete_Sec').css('display','block');
    jQuery('#RW_Tabs_PopUp_Close , .RW_Tabs_PopUp-Cancel').on('click',function (){ jQuery('#RW_Tabs_Delete_Sec').css('display','none'); })
    jQuery('.RW_Tabs_PopUp-Delete').on('click',function (){ 
        var data = {
            action: 'RW_Tabs_Man_Delete_Opt', 
            Deleted_ID: Deleted_ID,
            rw_tabs_nonce_field : rwtabs_object.rw_tabs_ajax_wp_nonce,
        };
        jQuery.post(rwtabs_object.ajaxurl, data, function(response) {
            jQuery('#RW_Tabs_Delete_Sec').css('display','none');
            jQuery('#RW_Tabs_Manager-Item-'+Deleted_ID).remove();
            if (jQuery('.RW_Tabs_Manager-Item').length == 0) {
                jQuery('#RW_Tabs_Opt_Manager_Nav').css('display','none');
                jQuery('#RW-Tabs-Options-Manager').html(`<article class="RW_Tabs_Nothing_Result"><h1>Nothing Found</h1><p>Sorry, but we can't get anything created with  <strong>our plugin</srong>. <br /></p></article></hr><button class="RWTabs_Create_New">Create Now</button>`).addClass('RW-Tabs-Options-Manager-No');
            }
            RW_Tabs_Message( `<i class="rich_web rich_web-check"></i> <b>Success:</b> Tabs deleted.`,'success');
        });
    });
}


jQuery(document).on("input", "#RWTabs_Search" , function() {
    if (jQuery(this).val().length === 0) {
        jQuery('#RW_Tabs_Nothing_Result').css(`display`,'none');
        jQuery('#RW-Tabs-Options-Manager').removeClass('RW-Tabs-Options-Manager-No');
        jQuery( "div.RW_Tabs_Manager-Item" ).css('display','');
    }else{
        jQuery( "div.RW_Tabs_Manager-Item" ).css('display','none');
        if (jQuery( "div.RW_Tabs_Manager-Item[data-rw-search*='"+jQuery('#RWTabs_Search').val()+"' i]" ).length == 0) {
            jQuery('#RW_Tabs_Nothing_Result').css(`display`,'');
            jQuery('#RW-Tabs-Options-Manager').addClass('RW-Tabs-Options-Manager-No');
        }else{
            jQuery('#RW_Tabs_Nothing_Result').css(`display`,'none');
            jQuery('#RW-Tabs-Options-Manager').removeClass('RW-Tabs-Options-Manager-No');
            jQuery( "div.RW_Tabs_Manager-Item[data-rw-search*='"+jQuery('#RWTabs_Search').val()+"' i]" ).css('display','');
        }
    }
});



jQuery(document).on("click", ".RW_Tabs_Short_Copy" , function() {
        var RW_Tabs_Create_Elem = document.createElement("input");
        var RW_Tabs_Short_ID = `[Rich_Web_Tabs id="${jQuery(this).attr('data-rw-shortid')}"]`; 
        var RW_Tabs_Msg = `<i class="rich_web rich_web-check"></i> <b>Success:</b> Shortcode Copied`; 
        RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&lt;", "<");
        RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&gt;", ">");
        RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&#039;", "'");
        RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&#039;", "'");
        RW_Tabs_Create_Elem.setAttribute("value", RW_Tabs_Short_ID);
        document.body.appendChild(RW_Tabs_Create_Elem);
        RW_Tabs_Create_Elem.select();
        document.execCommand("copy");
        document.body.removeChild(RW_Tabs_Create_Elem);
        RW_Tabs_Message(RW_Tabs_Msg,'info');
});
function RW_Tabs_Message(message,typeMessage,position = false){
		if (parseInt(jQuery(window).outerWidth()) > 500) {
			RW_Notif_Width = 400;
		} else {
			RW_Notif_Width = parseInt(jQuery(window).outerWidth()) - 60;
		}
		notifitPos = position == true ? 'bottom': 'right' ;
        if (typeMessage == "success") {
           var  offsetRW= 15;
        } else {
           var  offsetRW= 0;
        }
 		notif({
 			msg:message,
 			type:typeMessage,
 			color:"#fff",
 			zindex:'9999999',
			width: RW_Notif_Width,
            offset: offsetRW,
			position : notifitPos,
 			timeout: 3000,
 		});
}

function RW_Tabs_Opt_Filter(Filter) {
    event.preventDefault();
     if (Filter == 'all') {
         jQuery('.RW_Tabs_Opt_Content').css('display', '');
     } else{
         jQuery('.RW_Tabs_Opt_Content').css('display', 'none');
         jQuery('.'+Filter).css('display', '');
     } 
     jQuery('.RW_Tabs_Opt_Nav-Link').removeClass('active')
     jQuery('#RW_Tabs_Opt_Nav > .' + Filter).addClass('active')  
}

jQuery(document).on("click", ".RW_Tabs_Fixed_Bar_Button" , function() {
    if (jQuery('.RW_Tabs_Fixed_Bar_Button').attr('aria-hidden') == 'true') {
        jQuery('.RW_Tabs_Fixed_Bar_Links').css({opacity: 0, display: 'flex'}).animate({
                opacity: 1
            }, 'ease');
        jQuery('.RW_Tabs_Fixed_Bar_Button').attr('aria-hidden','false')
    }else{
        jQuery('.RW_Tabs_Fixed_Bar_Links').animate({
                opacity: 0,
                display:'none'
            }, 'ease');
        jQuery('.RW_Tabs_Fixed_Bar_Button').attr('aria-hidden','true')
    }
});
</script>