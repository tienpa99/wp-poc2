<?php 
if (!current_user_can('manage_options'))  die('Access Denied');
if(!defined('ABSPATH')) exit;
global $wpdb;
$RWTabs_FontFam_Table = $wpdb->prefix . "rich_web_tabs_font_family";
$RWTabs_Icons_Table   = $wpdb->prefix . "rich_web_tabs_icons";
$RWTabs_Themes_Table  = $wpdb->prefix . "rich_web_tabs_themes_data";
$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
// Install check
$RW_Tabs_Checking_Install=$wpdb->get_results($wpdb->prepare("SELECT  table_name FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s",$wpdb->dbname,$RWTabs_Themes_Table));
if (count($RW_Tabs_Checking_Install) != 0) {
    $Rich_Web_Tabs_Data_Checking = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Themes_Table WHERE id>%d",0));
    if (count($Rich_Web_Tabs_Data_Checking) == 0) {
        require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Install.php');
    }else {
        $RW_Tabs_Data_For_Old = array_column(json_decode(json_encode($Rich_Web_Tabs_Data_Checking),true),'Rich_Web_Tabs_T_N_S');
        if ( !in_array('Rich_Web_Tabs_tabs_15',$RW_Tabs_Data_For_Old) && file_exists(RW_TABS_PLUGIN_DIR_URL.'/Style/RW_Tabs_Tab_Styles/rw_tabs_tab_15.css.php') ) {
            apply_filters( 'RW_Tabs_Old_Used_Filter', true );
        }
    }
} else {
    require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Install.php');
}

$RW_Tabs_Build = $RW_Tabs_Build_ID = $RW_Tabs_Build_Theme = $RW_Tabs_Notice = false;
$RW_Tabs_Build_Edit = [];
$RW_Tabs_NewOld =  $RW_Tabs_Shortcode = $RW_Tabs_Theme_Proporties = $RW_Tabs_Build_Type = $RW_Tabs_Builder_Theme_ID = $RW_Tabs_Builder_Theme_TNS = '';
if (isset($_GET['page'])) {
    if (filter_var(sanitize_text_field($_GET['page']), FILTER_SANITIZE_STRING) != "RW_Tabs_Create") {
        return false;
    }
    if (isset($_GET['rw_theme']) && current_user_can('manage_options')) {
        if (is_int(filter_var(sanitize_text_field($_GET['rw_theme']), FILTER_VALIDATE_INT))) {
            $RW_Tabs_Build_Theme = filter_var(sanitize_text_field($_GET['rw_theme']), FILTER_VALIDATE_INT);
            $RW_Tabs_Build_Check = $wpdb->get_row($wpdb->prepare("SELECT * FROM $RWTabs_Themes_Table WHERE id = %d", $RW_Tabs_Build_Theme));
            if ($RW_Tabs_Build_Check) {
                $RW_Tabs_Build = true;
                $RW_Tabs_NewOld = "new";
                $Rich_Web_Tabs_Next_ID=$wpdb->get_results($wpdb->prepare("SELECT  AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s",$wpdb->dbname,$RWTabs_Manager_Table));
                $RW_Tabs_Shortcode = (int) $Rich_Web_Tabs_Next_ID[0]->AUTO_INCREMENT;
                $RW_Tabs_Theme_Proporties = json_decode($RW_Tabs_Build_Check->Rich_Web_Tabs_Prop);
                $RW_Tabs_Build_Type = strpos(filter_var($RW_Tabs_Build_Check->Rich_Web_Tabs_T_N_S, FILTER_SANITIZE_STRING), "acd") !== false ? "accordion" : "tab" ; 
                $RW_Tabs_Builder_Theme_TNS = filter_var($RW_Tabs_Build_Check->Rich_Web_Tabs_T_N_S, FILTER_SANITIZE_STRING);
                $RW_Tabs_Builder_Theme_ID = filter_var($RW_Tabs_Build_Check->id, FILTER_VALIDATE_INT);
            }else {
                $RW_Tabs_Notice = 'Theme is not defined.';
                echo $RW_Tabs_Notice;
            }
        }else {
            $RW_Tabs_Notice = 'Security Error';
            echo $RW_Tabs_Notice;
        }
    }else if (isset($_GET['rw_tab']) && current_user_can('manage_options')) {
        if (is_int(filter_var(sanitize_text_field($_GET['rw_tab']), FILTER_VALIDATE_INT))) {
            $RW_Tabs_Build_ID = filter_var(sanitize_text_field($_GET['rw_tab']), FILTER_VALIDATE_INT);
            $RW_Tabs_Build_Check = $wpdb->get_row($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id = %d", $RW_Tabs_Build_ID));
            if ($RW_Tabs_Build_Check) {
                $RW_Tabs_Build = true;
                $RW_Tabs_Build_Edit = $RW_Tabs_Build_Check;
                $RW_Tabs_NewOld = "edit";
                $RW_Tabs_Theme_Proporties = json_decode($RW_Tabs_Build_Check->Tabs_Props);
                $RW_Tabs_Build_Type = strpos(filter_var($RW_Tabs_Build_Check->Tabs_TNS, FILTER_SANITIZE_STRING), "acd") !== false ? "accordion" : "tab" ;  
                $RW_Tabs_Builder_Theme_TNS = filter_var($RW_Tabs_Build_Check->Tabs_TNS, FILTER_SANITIZE_STRING);
                $RW_Tabs_Builder_Theme_ID = filter_var($RW_Tabs_Build_Check->Tabs_Theme, FILTER_VALIDATE_INT);
            }else{
                $RW_Tabs_Notice = 'Tab is not defined.';
                echo $RW_Tabs_Notice;
            }
        }else {
            $RW_Tabs_Notice = 'Security Error';
            echo $RW_Tabs_Notice;
        }
    }
}else{
    return false;
}


$Rich_Web_Tabs_Fonts_Family=$wpdb->get_results($wpdb->prepare("SELECT Font_family FROM $RWTabs_FontFam_Table WHERE id>%d",0));
$Rich_WebFontCount = json_decode($Rich_Web_Tabs_Fonts_Family[0]->Font_family);
$Rich_Web_Tabs_Icons=$wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Icons_Table WHERE id>%d",0));
$Rich_WebIconCount = json_decode($Rich_Web_Tabs_Icons[0]->Icons);
?>

<script>  jQuery(window).load(function() { jQuery('#RW-Tabs-Loader').hide(); }); 

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
</script>

<style>
    /* Loader Section CSS RW */
    section#RW-Tabs-Loader {position: fixed;left: 0;right: 0;top: 0;bottom: 0;z-index: 999999;display: flex;height: 100vh;flex-direction: column;flex-wrap: wrap;align-items: center;align-content: center;background: rgba(0,0,0,0.5);justify-content: center;}
    .RW-Tabs-Loader_Cont{ position: relative; top: 50%; transform:translateY(-50%); -webkit-transform:translateY(-50%); -ms-transform:translateY(-50%); -moz-transform:translateY(-50%); -o-transform:translateY(-50%); }
    .RW-Tabs-Loader_Cubics{ width: 50px !important; height: 50px !important; }
    .RW-Tabs-Loader_Cubics{ width: 50px; height: 50px; margin: 20px auto; position: relative; transform: rotateZ(45deg); -o-transform: rotateZ(45deg); -ms-transform: rotateZ(45deg); -webkit-transform: rotateZ(45deg); -moz-transform: rotateZ(45deg); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeOne { position: relative; transform: rotateZ(45deg); -o-transform: rotateZ(45deg); -ms-transform: rotateZ(45deg); -webkit-transform: rotateZ(45deg); -moz-transform: rotateZ(45deg); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeOne { float: left; width: 50%; height: 50%; position: relative; transform: scale(1.1); -o-transform: scale(1.1); -ms-transform: scale(1.1); -webkit-transform: scale(1.1); -moz-transform: scale(1.1); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeOne:before { content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: #6ecae9; animation: RW-Tabs-Loader_AnimFold 2.76s infinite linear both; -o-animation: RW-Tabs-Loader_AnimFold 2.76s infinite linear both; -ms-animation: RW-Tabs-Loader_AnimFold 2.76s infinite linear both; -webkit-animation: RW-Tabs-Loader_AnimFold 2.76s infinite linear both; -moz-animation: RW-Tabs-Loader_AnimFold 2.76s infinite linear both; transform-origin: 100% 100%; -o-transform-origin: 100% 100%; -ms-transform-origin: 100% 100%; -webkit-transform-origin: 100% 100%; -moz-transform-origin: 100% 100%; }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeTwo { transform: scale(1.1) rotateZ(90deg); -o-transform: scale(1.1) rotateZ(90deg); -ms-transform: scale(1.1) rotateZ(90deg); -webkit-transform: scale(1.1) rotateZ(90deg); -moz-transform: scale(1.1) rotateZ(90deg); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeThree { transform: scale(1.1) rotateZ(180deg); -o-transform: scale(1.1) rotateZ(180deg); -ms-transform: scale(1.1) rotateZ(180deg); -webkit-transform: scale(1.1) rotateZ(180deg); -moz-transform: scale(1.1) rotateZ(180deg); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeFour { transform: scale(1.1) rotateZ(270deg); -o-transform: scale(1.1) rotateZ(270deg); -ms-transform: scale(1.1) rotateZ(270deg); -webkit-transform: scale(1.1) rotateZ(270deg); -moz-transform: scale(1.1) rotateZ(270deg); }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeTwo:before { animation-delay: 0.35s; -o-animation-delay: 0.35s; -ms-animation-delay: 0.35s; -webkit-animation-delay: 0.35s; -moz-animation-delay: 0.35s; }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeThree:before { animation-delay: 0.69s; -o-animation-delay: 0.69s; -ms-animation-delay: 0.69s; -webkit-animation-delay: 0.69s; -moz-animation-delay: 0.69s; }
    .RW-Tabs-Loader_Cubics .RW-Tabs-Loader_CubeFour:before { animation-delay: 1.04s; -o-animation-delay: 1.04s; -ms-animation-delay: 1.04s; -webkit-animation-delay: 1.04s; -moz-animation-delay: 1.04s; }
	@keyframes RW-Tabs-Loader_AnimFold { 0%, 10% { transform: perspective(136px) rotateX(-180deg); opacity: 0; } 25%, 75% { transform: perspective(136px) rotateX(0deg); opacity: 1; } 90%, 100% { transform: perspective(136px) rotateY(180deg); opacity: 0; } }
	@-o-keyframes RW-Tabs-Loader_AnimFold { 0%, 10% { -o-transform: perspective(136px) rotateX(-180deg); opacity: 0; } 25%, 75% { -o-transform: perspective(136px) rotateX(0deg); opacity: 1; } 90%, 100% { -o-transform: perspective(136px) rotateY(180deg); opacity: 0; } }
	@-ms-keyframes RW-Tabs-Loader_AnimFold { 0%, 10% { -ms-transform: perspective(136px) rotateX(-180deg); opacity: 0; } 25%, 75% { -ms-transform: perspective(136px) rotateX(0deg); opacity: 1; } 90%, 100% { -ms-transform: perspective(136px) rotateY(180deg); opacity: 0; } }
	@-webkit-keyframes RW-Tabs-Loader_AnimFold { 0%, 10% { -webkit-transform: perspective(136px) rotateX(-180deg); opacity: 0; } 25%, 75% { -webkit-transform: perspective(136px) rotateX(0deg); opacity: 1; } 90%, 100% { -webkit-transform: perspective(136px) rotateY(180deg); opacity: 0; } }
	@-moz-keyframes RW-Tabs-Loader_AnimFold { 0%, 10% { -moz-transform: perspective(136px) rotateX(-180deg); opacity: 0; } 25%, 75% { -moz-transform: perspective(136px) rotateX(0deg); opacity: 1; } 90%, 100% { -moz-transform: perspective(136px) rotateY(180deg); opacity: 0; } }
	.RW-Tabs-Loading_Text{ position:relative; text-align:center; margin-top:10px; font-size: 18px !important; font-family:Arial !important; color: #6ecae9 !important; }
    


    <?php if ($RW_Tabs_Build === false) { ?>
    #RW-Tabs-Options-Content{width: 100%;}
    #RW_Tabs_Opt_Nav{display: flex;gap: 7px;overflow-x: auto;background-color: var(--rw-tabs-grid-layout-bgc);border-bottom: 2px var(--rw-tabs-bcg-color) solid;flex-wrap: nowrap;flex-direction: row;justify-content: flex-start;align-items: flex-start;padding:10px 30px 0px 30px;}
    .RW_Tabs_Opt_Nav-Link { text-decoration: none; color: #6ecae9; padding: 8px 19px; background-color: var(--rw-tabs-grid-layout-bgc); border: 0; font-size: 16px; cursor: pointer; box-shadow: 0 0 8px grey; margin: 6px; border-radius: 7px; outline: 0;}
    .RW_Tabs_Opt_Nav-Link.active ,.RW_Tabs_Opt_Nav-Link:hover{color: white;background-color: var(--rw-tabs-bcg-color);outline: 0;}
    #RW_Tabs_Options {background-color: var(--rw-tabs-grid-layout-bgc);display: grid;grid-gap: 30px;padding: 20px 36px;grid-template-columns: var(--rw-tabs-grid-layout);}
    .RW_Tabs_Opt_Content {background: #ffffff;border-radius: 1.2rem;overflow: hidden;box-shadow: 0.5rem 0.5rem 1rem rgba(51, 51, 51, 0.2), 0.5rem 0.5rem 1rem rgba(51, 51, 51, 0.2);}
    .RW_Tabs_Opt_Content-Header {height: 125px;background-image: linear-gradient( to right, rgb(240 240 241), rgb(110 202 233) );}
    .RW_Tabs_Opt_Content-Body {margin-bottom: 1rem;display: flex;flex-direction: column;justify-content: center;align-items: center;align-content: center;}
    .RW_Tabs_Opt_Content-Img{cursor:pointer;z-index:1;margin-top: -90px;border: 0.5rem solid #fff;width: 80%;border-radius: 7%;height:8em;box-shadow: 0px 7px 10px 0px #c5c7cb;}
    .RW_Tabs_Opt_Content-Img-Overlay {background: #3a3a3a;z-index: 1;cursor: pointer;opacity: 0;margin-top: -120px;border: 0.5rem solid #3a3a3a;width: 80%;border-radius: 7%;height: 8em;box-shadow: 0px 7px 10px 0px #3a3a3a;display: flex;flex-direction: row;flex-wrap: nowrap;align-content: center;justify-content: center;align-items: center;}    
    .RW_Tabs_Opt_Content-Img:hover + .RW_Tabs_Opt_Content-Img-Overlay,.RW_Tabs_Opt_Content-Img-Overlay:hover {opacity: 0.7; z-index: 1;}
    .RW_Tabs_Opt_Content-Name{color: #7e8084;}
    .RW_Tabs_Opt_Content-Img-Overlay > i {color: #6ecae9;font-size: 30px;}
    .RW_Tabs_Opt_Content-Footer {display: flex;justify-content: center;padding: 1rem;}
    .RWTabs_Actioned_Btn,.RWTabs_Choose_Btn,.RWTabs_Upgrade_Btn{position: relative;color: #71cbea;padding: 2px;border: none;border-radius: 3px;font-size: 14px;background: linear-gradient( to right, rgb(240 240 241), rgb(110 202 233) );cursor:pointer;-webkit-touch-callout: none;-webkit-user-select: none;   -khtml-user-select: none;     -moz-user-select: none;      -ms-user-select: none;          user-select: none;  box-shadow: 1rem -3rem 20rem rgb(51 51 51 / 20%), 0.5rem 0.5rem 1rem rgb(51 51 51 / 20%);        }
    .RWTabs_Choose_Btn::before,.RWTabs_Upgrade_Btn::before{position: absolute;top: -30%;left: 50%;transform: translateX(-50%);padding: 0 5px;font-size: 20px;background: #ffffff;}
    .RWTabs_Choose_Btn::before{font-family: FontAwesome;content: "\f040";}
    .RWTabs_Upgrade_Btn::before{font-family: FontAwesome;content: "\f288";}
    a.RWTabs_Choose_Btn:hover{text-decoration:none;color: #71cbea;}
    .RWTabs_Actioned_Btn::before{position: absolute;top: -35%;left: 43%;font-size: 20px;background: #ffffff;font-family: FontAwesome;content:"\f021";-webkit-animation: fa-spin 2s infinite linear;      animation: fa-spin 2s infinite linear;}
    .RWTabs_Choose_Btn_Inner,.RWTabs_DemoBtn_Inner,.RWTabs_Upgrade_Btn_Inner{background: #ffffff;padding: 5px;cursor:pointer;}
    .RW_Tabs_Opt_Content-Footer > a,.RW_Tabs_Opt_Content-Footer > a:focus,.RW_Tabs_Opt_Content-Footer > a:active,.RW_Tabs_Opt_Content-Footer > a:hover{ text-decoration: none;outline: none;border: none;box-shadow:none; }
    .RW_Tabs_Opt_Content-Img-Overlay,.RW_Tabs_Opt_Content-Img-Overlay:focus,.RW_Tabs_Opt_Content-Img-Overlay:active,.RW_Tabs_Opt_Content-Img-Overlay:hover{ text-decoration: none;outline: none;}
    <?php } ?>

    #RW-Tabs-Content{display:flex;display:-ms-flexbox;display:-webkit-box;width:100vw;height:100vh;position:fixed;left:0;right:0;top:0;bottom:0;z-index:100049;background:#fff;}
    #RW-Tabs-Panel{width:var(--rw-tabs-panel-width);max-width:var(--rw-tabs-panel-max-width);min-width:var(--rw-tabs-panel-min-width);background:#e6e9ec;height:100%;display:grid;grid-template-rows:5vh 90vh 5vh;}
    #RW-Tabs-Panel:not(.ui-resizable-resizing){-webkit-transition:all .5s ease-in-out;-o-transition:all .5s ease-in-out;transition:all .5s ease-in-out;}
    #RW-Tabs-Preview-Panel:not(.ui-resizable-resizing){-webkit-transition:all .5s ease-in-out;-o-transition:all .5s ease-in-out;transition:all .5s ease-in-out;}
    .RW-Tabs-Panel-Hidden{margin-left:calc(var(--rw-tabs-panel-width) * -1)!important;}
    #RW-Tabs-Preview-Panel{width:var(--rw-tabs-preview-panel-width);background:#f0f0f1;position:relative;left:0;right:0;top:0;bottom:0;}
    #RW_Tabs_Iframe_Container{position:relative;width:var(--rw-tabs-preview-container-width);height:var(--rw-tabs-preview-container-height);-webkit-box-sizing:content-box;box-sizing:border-box;position:relative;display:flex;justify-content:center;align-items:center;}
    #RW-Tabs-Panel-Header{background-color:var(--rw-tabs-bcg-color);display:-ms-flexbox;display:-webkit-flex;display:flex;-webkit-flex-direction:row;-ms-flex-direction:row;flex-direction:row;-webkit-align-content:center;-ms-flex-line-pack:center;align-content:center;align-items:center;padding:0 10px;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;}
    #RW-Tabs-Panel-Header>.RW_Tabs_Head_Img{width:100px;height:90%;}
    #RW-Tabs-Panel-Header>.RW_Tabs_Head_Img>img{width:100%;height:100%;}
    #RW-Tabs-Panel-Header>.RW_Tabs_Head_Back,#RW-Tabs-Panel-Header>.RW_Tabs_Head_Globe{font-size:17px;font-family:initial;font-weight:bolder;color:#fff;cursor:pointer;}
    #RW-Tabs-Panel-Header>.RW_Tabs_Head_Name{font-size:17px;font-family:initial;font-weight:bolder;color:#fff;}
    #RW-Tabs-Panel-Content{background:#f0f0f1;overflow-y:auto;overflow-x:hidden;display:grid;grid-template-rows:var(--rw_tabs_panel_grid_sys);}
    #RW-Tabs-Panel-Footer{background-color:#3c434a;display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:flex-end;align-content:center;align-items:center;gap:10px;padding:0 5px;}
    #RW-Tabs-Panel-Content_Nav{width:100%;background-color:#fff;display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:flex-start;}
    .RW-Tabs-Panel-Content_Nav-Bar{height:100%;background-color:#fff;border-bottom:3px solid #fff;flex:1;display:flex;flex-direction:column;gap:2px;cursor:pointer;align-content:center;justify-content:center;align-items:center;}
    .RW-Tabs-Panel-Content_Nav-Bar>i{font-size:16px;}
    .RW-Tabs-Panel-Content_Nav-Bar>span{user-select:none;}
    .RW-Tabs-Panel-Content_Nav-Bar.active,.RW-Tabs-Panel-Content_Nav-Bar:hover{border-bottom:3px solid var(--rw-tabs-bcg-color);background-image:-webkit-linear-gradient(top,#f1f3f5,#fff);}
    #RW-Tabs-Panel-switcher{position:absolute;left:100%;top:50%;width:15px;height:50px;-webkit-transform:translateY(-50%);-ms-transform:translateY(-50%);transform:translateY(-50%);background-color:#f0f0f1;font-size:15px;-webkit-box-shadow:3px 1px 5px rgba(0,0,0,.1);box-shadow:3px 1px 5px rgba(0,0,0,.1);cursor:pointer;display:flex;flex-direction:column;justify-content:center;align-items:center;z-index:100;}
    #RW-Tabs-Panel-switcher:hover{background-color:var(--rw-tabs-bcg-color);color:#fff;}
    #RW_Tabs_Preview_Iframe{width:100%;background-color:#fff;overflow:auto;padding:10px;max-width:calc(var(--rw-tabs-preview-panel-width) - 10vw); min-height:90vh;max-height: 90vh;-webkit-box-shadow: 5px 5px 15px 5px #c3c4c7; box-shadow: 5px 5px 15px 5px #c3c4c7;}
    main#RW-Tabs-Nav-Content{width:100%;height:100%;}
    main#RW-Tabs-Nav-Content>section{width:100%;}
    .rw-tabs-acc-item{background-color:#fff;color:#444;cursor:pointer;padding:18px;border:none;text-align:left;outline:0;font-size:15px;transition:.4s;margin-top:10px;}
    .rw-tabs-acc-item-switch{display:flex;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;background-color:#fff;color:#444;cursor:pointer;border:none;text-align:left;outline:0;font-size:15px;transition:.4s;margin-top:20px;}
    .rw-tabs-active-item{border-bottom:solid 1px #444;}
    .rw-tabs-acc-item:after{content:'\002B';color:#444;font-weight:700;float:right;margin-left:5px;}
    .rw-tabs-active-item:after{content:"\2212";}
    .rw_tabs_control-panel{padding:0 18px;background-color:#fff;height:0;overflow:hidden;transition:height .2s ease-out;}
    #rw_tabs_control_special_bgc-img{padding:15px 1px;display:none;}
    #rw_tabs_bgc-img-container{width:100%;position:relative;cursor:pointer;}
    #rw_tabs_bgc-img-container:not(:hover) .rw_tabs_bgc-img-inner-choose{bottom:-30px;display:none;}
    #rw_tabs_bgc-img-container:hover .rw_tabs_bgc-img-inner-choose{position:absolute;bottom:0;left:0;right:0;height:27px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out;cursor:pointer;overflow:hidden;display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-flex:1;-ms-flex-positive:1;flex-grow:1;color:#fff;background-color:rgba(109,120,130,.85);font-size:11px;}
    #rw_tabs_bgc-img-container:after{-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out;}
    #rw_tabs_bgc-img-container:hover:after{content:"";position:absolute;top:0;bottom:0;left:0;right:0;background-color:rgba(0,0,0,.2);pointer-events:none;}
    .rw_tabs_bgc-img-inner{background-size:100% 100%;background-repeat:no-repeat;aspect-ratio:var(--rw_tabs_img_aspect,16/9);margin-bottom:10px;border:2px solid #ddd;}
    .rw_tabs_control-panel_tabsContent,.rw_tabs_control-panel_tabsGlobal,.rw_tabs_control-panel_tabsMenu{padding:5px 18px;background-color:#fff;height:auto;overflow:hidden;margin-top:10px;}
    .rw_tabs_control-panel-flex{padding:15px 5px;display:flex;flex-direction:column;flex-wrap:wrap;align-content:flex-start;justify-content:flex-start;align-items:flex-start;gap:10px;}
    .rw-control-panel-title{font-size:13px;line-height:1;margin:10px 0 0 0;}
    .rw_tabs_fields{display:flex;flex-direction:row;width:100%;border:1px solid;align-content:center;align-items:center;}
    .rw_tabs_fields>span{margin-left:10px;word-break:break-all;}
    .rw_tabs_actions{display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:center;align-items:center;align-content:center;place-items:center;margin-left:auto;}
    .rw_tabs_field-action{border-left:1px solid;padding:10px 10px;cursor:pointer;position:relative;}
    .rw_tabs_add-item:hover:after,.rw_tabs_field-action:hover:after{background-color:#000;color:#fff;text-align:center;border-radius:6px;padding:5px 12px;position:absolute;z-index:1;bottom:100%;left:50%;transform:translate(-50%);content:attr(data-title);}
    .RW_Tabs_Fixed_Bar_Button[aria-hidden=true]:hover:after{background:linear-gradient(135deg ,#6ecae9,#312a6c);color:#fff;text-align:center;border-radius:6px;padding:5px 12px;position:absolute;z-index:1;bottom:100%;left:50%;transform:translate(-50%);content:attr(data-rw-title);width:max-content;}
    .RW_Tabs_Fixed_Bar_Button[aria-hidden=true]:hover:before,.rw_tabs_add-item:hover:before,.rw_tabs_field-action:hover:before{content:"";position:absolute;top:0;left:50%;margin-left:-5px;border-width:5px;border-style:solid;border-color:#000 transparent transparent transparent;}
    .RW_Tabs_Fixed_Bar_Button[aria-hidden=true]:hover:before{border-color:#4d75a6 transparent transparent transparent;}
    .rw_tabs_add-item{width:150px;background-color:var(--rw-tabs-bcg-color);color:#fff;font-size:11px;padding:7px 21px;display:flex;flex-direction:row;justify-content:center;align-items:baseline;gap:5px;font-size:12px;font-family:cursive;margin:0 auto;cursor:pointer;border-radius:3px;margin-bottom:15px;position:relative;}
    .rw_tabs_container_opt{display:flex;flex-direction:column;flex-wrap:wrap;align-content:flex-start;justify-content:center;padding:15px 0;}
    .rw_tabs_container_control{display:flex;flex-direction:row;flex-wrap:nowrap;align-items:center;width:100%;padding:5px 0;justify-content:space-between;}
    .rw_tabs_control_shortcode{display:flex;flex-direction:column;flex-wrap:wrap;align-items:center;justify-content:space-between;width:35%;}
    div#RW-Tabs-Panel-Content::-webkit-scrollbar{width:5px;height:5px;}
    div#RW-Tabs-Panel-Content::-webkit-scrollbar-thumb{border-radius:8px;box-shadow:inset 0 0 5px rgb(0 0 0 / 30%);background-color:var(--rw-tabs-bcg-color);border:1px solid var(--rw-tabs-bcg-color);}
    div#RW_Tabs_Preview_Iframe::-webkit-scrollbar{width:6px;height:8px;}
    div#RW_Tabs_Preview_Iframe::-webkit-scrollbar-thumb{border-radius:8px;box-shadow:inset 0 0 5px rgb(0 0 0 / 30%);background-color:#e3e3e9;border:1px solid #e3e3e9;}
    
    .rw_tabs_container_control>input[type=range]{width:70%;}
    .rw_tabs_container_control>input[type=number]{width:20%;}
    .rw_tabs_container_control>label{margin-right:auto;}
    .rw_tabs_container_control>.pickr>.pcr-button{border:solid 2px #f0f0f1;}
    .rw_tabs_input-container{display:flex;flex-direction:row;width:100%;margin-top:5px;margin-bottom:15px;border:1px solid #ddd;}
    .rw_tabs_input-cont_shortcode{margin-bottom:5px;}
    .rw_tabs_input-container>input{border:none;flex:1;outline:0;}
    .rw_tabs_input-container>span{background:#ddd;padding:10px;position:relative;cursor:pointer;}
    .rw_tabs_input-container>input:focus{outline:0;border:none;}
    .RW_Tabs_Upd_Content{color:#fff;margin-top:10px;padding:5px 7px;border-radius:5px;background-color:#6ecae9;box-shadow:0 0 10px #30a9d1;border:1px solid #30a9d1;cursor:pointer;float:right;}
    .RW_Tabs_Upd_Content:hover{background-color:#30a9d1;color:#fff;box-shadow:0 0 10px #30a9d1;}
    #rw_tabs_container_control_Text{padding:15px 0;}
    #wp-RW_Tabs_Tab_Content_Area-editor-container{height:auto!important;}
    .RW_Tabs_Switch_Prev { position: relative; display: block; vertical-align: top; width: 80px; height: 25px; padding: 3px;  margin-top: -3px; background: linear-gradient(to bottom, #eeeeee, #FFFFFF 25px); background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF 25px); border-radius: 18px; box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05); cursor: pointer; }
    .RW_Tabs_Switch_Prev-input { position: absolute; top: 0; left: 0; opacity: 0; }
    .RW_Tabs_Switch_Prev-label { position: relative; display: block; height: inherit; font-size: 10px; text-transform: uppercase; background: #ff0000; border-radius: inherit; box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15); }
    .RW_Tabs_Switch_Prev-label:before, .RW_Tabs_Switch_Prev-label:after { position: absolute; top: 50%; margin-top: -.5em; line-height: 1; -webkit-transition: inherit; -moz-transition: inherit; -o-transition: inherit; transition: inherit; }
    .RW_Tabs_Switch_Prev-label:before { content: attr(data-off); right: 11px; color: #ff0000; }
    .RW_Tabs_Switch_Prev-label:after { content: attr(data-on); left: 11px; color: #FFFFFF; opacity: 0; }
    .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-label { background: #E1B42B; }
    .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-label:before { opacity: 0; }
    .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-label:after { opacity: 1; }
    .RW_Tabs_Switch_Prev-handle { position: absolute; top: 4px; left: 4px; width: 28px; height: 28px; background: linear-gradient(to bottom, #FFFFFF 40%, #f0f0f0); background-image: -webkit-linear-gradient(top, #FFFFFF 40%, #f0f0f0); border-radius: 100%; box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2); }
    .RW_Tabs_Switch_Prev-handle:before { content: ""; position: absolute; top: 50%; left: 50%; margin: -6px 0 0 -6px; width: 12px; height: 12px; background: linear-gradient(to bottom, #eeeeee, #FFFFFF); background-image: -webkit-linear-gradient(top, #eeeeee, #FFFFFF); border-radius: 6px; box-shadow: inset 0 1px rgba(0, 0, 0, 0.02); }
    .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-handle { left: 74px; box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2); }
    .RW_Tabs_Switch_Prev-light { padding: 0; background: #FFF; background-image: none; }
    .RW_Tabs_Switch_Prev-light .RW_Tabs_Switch_Prev-label { background: #FFF; border: solid 2px #ff0000; box-shadow: none; }
    .RW_Tabs_Switch_Prev-light .RW_Tabs_Switch_Prev-label:after { color: #79e271; }
    .RW_Tabs_Switch_Prev-light .RW_Tabs_Switch_Prev-label:before { right: inherit; left: 11px; }
    .RW_Tabs_Switch_Prev-light .RW_Tabs_Switch_Prev-handle { top: 5px; left: 55px; background: #ff0000; width: 20px; height: 20px; box-shadow: none; }
    .RW_Tabs_Switch_Prev-light .RW_Tabs_Switch_Prev-handle:before { background: #fe8686; }
    .RW_Tabs_Switch_Prev-light .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-label { background: #FFF; border-color: #79e271; }
    .RW_Tabs_Switch_Prev-light .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-handle { left: 55px; box-shadow: none; background: #79e271 }
    .RW_Tabs_Switch_Prev-light .RichWeb_Switch_But ~ .RW_Tabs_Switch_Prev-handle:before { background: rgba(255,255,255,0.7); }
    .RW_Tabs_Switch_Prev-label, .RW_Tabs_Switch_Prev-handle { transition: all 0.3s ease; -webkit-transition: all 0.3s ease; -moz-transition: all 0.3s ease; -o-transition: all 0.3s ease; }
    .ui-resizable { position: relative;}
    #RW-Tabs-Preview-Panel{display:grid;grid-template-rows:100vh;}
    #RW_Tabs_Responsive_Iframe{width:100%;height:100%;display:flex;justify-content:center;align-items:center;background-color:#f0f0f1;}
    #RW_Tabs_Responsive_Switch_Cont{display:none;justify-content:space-between;align-items:center;background-color:#3c434a;padding:0 10px;}
    #RW_Tabs_Preview-Panel-Nav{top:0;width:84%;height:25vh;background:none!important;position:absolute;margin-left:8%;margin-right:8%;z-index:999998;visibility:hidden;}
    #RW_Tabs_Preview-Shortcode-Panel{background-color:#fff;border:1px solid #c3c4c7;border-top:none;box-shadow:0 0 0 transparent;height:90%;width:100%;border-bottom-left-radius:5px;visibility:hidden;}
    #RW_Tabs_Preview-Panel-Switch{background-color:#fff;width:20%;height:14%;margin-left:80%;margin-top:-1px;border:1px solid #c3c4c7;border-top:none;border-bottom-left-radius:5px;border-bottom-right-radius:5px;display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:center;align-items:center;font-size:14px;cursor:pointer;visibility:visible;}
    #RW_Tabs_Preview-Panel-Switch>span:after{display:inline-block;font-family:FontAwesome;text-rendering:auto;-webkit-font-smoothing:antialiased;margin-left:10px;}
    #RW_Tabs_Preview-Panel-Switch[aria-hidden="true"]>span:after{content:"\f0d7";}
    #RW_Tabs_Preview-Panel-Switch[aria-hidden="false"]>span:after{content:"\f0d8";}
    #RW_Tabs_ShortcodePanel-Inner{display:flex;flex-direction:row;flex-wrap:wrap;justify-content:space-evenly;align-items:center;padding:0 18px;height:100%;overflow:hidden;}
    #RW_Tabs_Update_Button{content:attr('data-rw-content');}
    #RW_Tabs_Update_Button,.RWTabs_Back_toDashboard{background-color:var(--rw-tabs-bcg-color);color:#fff;font-size:11px;padding:7px 21px;display:flex;flex-direction:row;justify-content:center;align-items:baseline;gap:5px;font-size:12px;font-family:cursive;cursor:pointer;border-radius:3px;}
    .RWTabs_Back_toDashboard{margin-right:auto;text-decoration:none;outline:0!important;border:none!important;cursor:pointer;}
    .RWTabs_Back_toDashboard:focus,.RWTabs_Back_toDashboard:hover,.RWTabs_Back_toDashboard:active,.RWTabs_Back_toDashboard.active{color:#ffffff;text-decoration:none;outline:0!important;border:none!important;}

    .RWTabs_Back_Sect{background-color:#fff;color:var(--rw-tabs-bcg-color);font-size:11px;padding:7px 21px;display:flex;flex-direction:row;justify-content:center;align-items:baseline;gap:5px;font-size:12px;font-family:cursive;cursor:pointer;border-radius:3px;}
    #RWTabs_Switch_toResponsive{width:22px;height:22px;cursor:pointer;padding:5px 12px;border-radius:3px;}
    #RWTabs_Switch_toResponsive>img{width:100%;height:100%;}
    #RWTabs_Switch_toResponsive:hover,#RWTabs_Switch_toResponsive.activated{background:var(--rw-tabs-bcg-color);}
    .RW_Tabs_Responsive_Switch>img{width:20px;height:20px;}
    .RW_Tabs_Responsive_Switch_Elem{display:flex;align-items:center;gap:10px;padding:0 7px;color:#fff;width:25%;}
    .RW_Tabs_Responsive_Switch{-webkit-transition:background-color .3s ease-out;-o-transition:background-color .3s ease-out;transition:background-color .3s ease-out;text-align:center;width:22px;height:22px;margin:0 3.5px;line-height:22px;-webkit-border-radius:3px;border-radius:3px;cursor:pointer;color:#fff;font-size:18px;padding:1px;}
    .RW_Tabs_Responsive_Switch.activeMode,.RW_Tabs_Responsive_Switch:hover{background-color:#f0f0f1;}
    .RW_Tabs_Responsive_Switch_Elem:nth-child(1){justify-content:flex-start;}
    .RW_Tabs_Responsive_Switch_Elem:nth-child(2){justify-content:center;}
    #RW_Tabs_Disable_Responsive{justify-content:flex-end;cursor:pointer;}
    #RW_Tabs_Disable_Responsive:hover>i{color:#6ecae9;}
    #RW_Tabs_Responsive_Switch_W,#RW_Tabs_Responsive_Switch_H{background-color:transparent;color:#fff;border:1px solid #fff!important;padding:0 3px;width:60px;font-size:12px;line-height:16px;height:18px;}
    #RW_Tabs_Iframe_Container>.ui-resizable-handle{display:-webkit-box!important;display:-ms-flexbox!important;display:flex!important;}
    #RW_Tabs_Iframe_Container>.ui-resizable-e{right:10px;}
    #RW_Tabs_Iframe_Container>.ui-resizable-w{left:10px;}
    #RW_Tabs_Iframe_Container>.ui-resizable-s{top:unset!important;;bottom:0px!important;}
    #RW_Tabs_Iframe_Container>.ui-resizable-e{-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:flex-end;-ms-flex-pack:end;justify-content:flex-end;width:80px;top:0;height:100%;width:10px;cursor:ew-resize;}
    #RW_Tabs_Iframe_Container>.ui-resizable-w{-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:flex-start;-ms-flex-pack:start;justify-content:flex-start;width:80px;top:0;height:100%;width:10px;cursor:ew-resize;}
    #RW_Tabs_Iframe_Container>.ui-resizable-s{-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;height:80px;left:0;height:10px;width:100%;cursor:ns-resize;}
    #RW_Tabs_Iframe_Container .ui-resizable-handle{top:0;position:absolute;}
    .ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }
    #RW_Tabs_Iframe_Container>.ui-resizable-e:before,#RW_Tabs_Iframe_Container>.ui-resizable-w:before{content:"";display:block;background-color:hsla(0,0%,100%,.2);width:4px;height:50px;-webkit-border-radius:3px;border-radius:3px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out;}
    #RW_Tabs_Iframe_Container:not(.ui-resizable-resizing)>.ui-resizable-e:hover:before,#RW_Tabs_Iframe_Container:not(.ui-resizable-resizing)>.ui-resizable-w:hover:before{background-color:hsla(0,0%,100%);height:100px;}
    #RW_Tabs_Iframe_Container>.ui-resizable-s:before{content:"";display:block;background-color:hsla(0,0%,100%,.2);width:50px;height:4px;-webkit-border-radius:3px;border-radius:3px;-webkit-transition:all .2s ease-in-out;-o-transition:all .2s ease-in-out;transition:all .2s ease-in-out;}
    #RW_Tabs_Iframe_Container:not(.ui-resizable-resizing)>.ui-resizable-s:hover:before{background-color:hsla(0,0%,100%);width:100px;}
    #RW_Tabs_Iframe_Container.ui-resizable-resizing>.ui-resizable-s:before{background-color:hsla(0,0%,100%);width:50px;}
    #RW_Tabs_Iframe_Container.ui-resizable-resizing>.ui-resizable-e:before,#RW_Tabs_Iframe_Container.ui-resizable-resizing>.ui-resizable-w:before{background-color:hsla(0,0%,100%);height:50px;}
    #RW-Tabs-Panel>.ui-resizable-handle{position:absolute;font-size:.1px;display:block;}
    #RW-Tabs-Panel>.ui-resizable-e{cursor:e-resize;width:50px;right:-30px;top:0;height:100%;}
    .RW_Tabs_Select_Label{cursor:pointer;position:relative;}
    .RW_Tabs_Select_Label:hover:after{background-color:#000;color:#fff;text-align:center;border-radius:6px;padding:5px 12px;position:absolute;z-index:1;bottom:100%;left:50%;transform:translate(-50%);content:attr(data-title);}
    .RW_Tabs_Select_Label:hover:before{content:"";position:absolute;top:0;left:50%;margin-left:-5px;border-width:5px;border-style:solid;border-color:#000 transparent transparent transparent;}
    .RW_Tabs_Preview_Select{display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:flex-end;align-items:center;gap:5px;}
    .RW_Tabs_Preview_Select>input{margin:0;padding:0;-webkit-appearance:none;-moz-appearance:none;appearance:none;display:none!important;}
    .RW_Tabs_Preview_Select input:active+.RW_Tabs_Select_Label{opacity:.9;}
    .RW_Tabs_Preview_Select input:checked+.RW_Tabs_Select_Label{-webkit-filter:none;-moz-filter:none;filter:none;}
    .RW_Tabs_Select_Label{cursor:pointer;background-size:contain;background-repeat:no-repeat;display:inline-block;-webkit-transition:all 100ms ease-in;-moz-transition:all 100ms ease-in;transition:all 100ms ease-in;-webkit-filter:brightness(1.8) grayscale(1) opacity(.7);-moz-filter:brightness(1.8) grayscale(1) opacity(.7);filter:brightness(1.8) grayscale(1) opacity(.7);}
    .RW_Tabs_Select_Label:hover{-webkit-filter:brightness(1.2) grayscale(.5) opacity(.9);-moz-filter:brightness(1.2) grayscale(.5) opacity(.9);filter:brightness(1.2) grayscale(.5) opacity(.9);}
    .RW_Tabs_Select_Label[data-title="Left"],.RW_Tabs_Select_Label[data-title="Right"]{width:30px;height:30px;}
    .RW_Tabs_Select_Label[data-title="Center"]{width:40px;height:30px;}
    .RW_Tabs_Select_Label[data-title="Horizontal"],.RW_Tabs_Select_Label[data-title="Vertical"]{width:50px;height:36px;}
    .RW_Tabs_Select_Label[data-title="No-Wrap"]{width:60px;height:30px;}
    .RW_Tabs_Select_Label[data-title="Wrap"]{width:30px;height:30px;}
    section#RW-Tabs-Notice-Section{position:fixed;left:0;right:0;top:0;bottom:0;z-index:9999999;display:flex;height:100vh;flex-direction:column;flex-wrap:wrap;align-items:center;align-content:center;background:#6ecae9;justify-content:center;}
    section#RW-Tabs-Notice-Section>#RW-Tabs-Notice-Section-Inner{width:50%;display:flex;flex-direction:column;align-content:center;justify-content:center;align-items:center;margin-bottom:80px;}
    #RW-Tabs-Notice-Section-Inner>.RW-Tabs-Notice-Msg>h1,h2{text-align:center;color:#fff;}
    .rw-tabs-acc-item-pro{cursor:not-allowed;position:relative;}
    .rw-tabs-acc-item-pro>.RW_Tabs_Switch_Prev{opacity:.4;pointer-events:none;cursor:not-allowed;}
    .rw-tabs-acc-item-pro:hover:before{content:"";position:absolute;top:-7px;left:50%;margin-left:-5px;border-width:5px;border-style:solid;border-color:#ff3939 transparent transparent transparent;}
    .rw-tabs-acc-item-pro:hover:after{background-color:#ff3939;color:#fff;text-align:center;border-radius:6px;padding:5px 12px;position:absolute;z-index:1;bottom:130%;left:50%;transform:translate(-50%);content:'Pro Option';}
    #wpcontent{padding-left: 0 !important;}
    *, *::before, *::after {margin:0px;padding:0px;text-rendering: optimizeLegibility;-webkit-font-smoothing: antialiased;}
    :root{--rw-tabs-bcg-color: #6ecae9;--rw-tabs-panel-width:25vw;--rw-tabs-panel-min-width:25vw;--rw-tabs-panel-max-width:40vw;--rw-tabs-preview-panel-width: 75vw;--rw-tabs-body-overflow:auto;--rw-tabs-grid-layout : 1fr 1fr 1fr 1fr;--rw-tabs-grid-layout-bgc: #f0f0f1;--rw_tabs_panel_grid_sys : 10% 90%;--rw-tabs-preview-container-width: 100%;--rw-tabs-preview-container-height: 100%;}
    @media (max-width: 1176px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr 1fr 1fr;}}
    @media (max-width: 768px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr 1fr;}.RW_Tabs_Manager-Item {  width: 45%; }}
    @media (max-width: 540px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr;}.RW_Tabs_Manager-Item {  width: 90%; }}
    body{overflow:var(--rw-tabs-body-overflow);}
    #RW_Tabs_Fixed_Bar {position: fixed;z-index: 999998;right: 40px;bottom: 100px;opacity: 1;}
    .RW_Tabs_Fixed_Bar_Button, .RW_Tabs_Fixed_Bar_Button:focus, .RW_Tabs_Fixed_Bar_Button:hover {cursor: pointer;position: absolute;right: -10px;}
    .RW_Tabs_Fixed_Bar_Button > img {width: 70px;height: 70px;border-radius: 60%;transition: all 0.3s ease-in-out;box-shadow: 0 3px 20px rgb(0 0 0 / 20%);}
    .RW_Tabs_Fixed_Bar_Links{display: flex;flex-direction: column;justify-content: flex-start;row-gap: 12px;align-items: flex-end;transition: all 0.4s ease-in-out;}
    .RW_Tabs_Fixed_Bar_Links > a,.RW_Tabs_Fixed_Bar_Links > a:hover ,.RW_Tabs_Fixed_Bar_Links > a:focus,.RW_Tabs_Fixed_Bar_Links > a:active{outline:none !important;text-decoration:none !important;border:none !important;-moz-outline-style: none !important;box-shadow: none !important;}
    .RW_Tabs_Fixed_Bar_Links > a:last-child{margin-bottom:12px;}
    .RW_Tabs_FlexLinks{display: flex;flex-direction: row;justify-content: flex-end;align-items: center;column-gap: 14px;}
    .RW_Tabs_FlexLinks > div{width: 40px;height: 40px;border-radius: 50%;transition: all 0.3s ease-in-out;background: linear-gradient( 135deg , #6ecae9, #312a6c);display: flex;align-content: center;flex-direction: column;justify-content: center;align-items: center;color: #ffffff;}
    .RW_Tabs_Fixed_Bar_Links > a:hover > .RW_Tabs_FlexLinks > div{box-shadow: 0 3px 20px rgba(0,0,0,0.2);}
    .RW_Tabs_Fixed_Bar_Links > a:hover > .RW_Tabs_FlexLinks > span{background:#3e3e43;}
    .RW_Tabs_FlexLinks > span{color: #fff;background: #8b8b97;font-size: 12px;padding: 5px 10px;height: auto !important;transition: all 0.2s ease-out;border-radius: 3px;-moz-border-radius: 3px;-webkit-border-radius: 3px;}

</style>
<!-- RW Tabs Loader -->
<section id="RW-Tabs-Loader">
	<div class="RW-Tabs-Loader_Div"><div class="RW-Tabs-Loader_Cont"><div class="RW-Tabs-Loader_Cubics"><div class="RW-Tabs-Loader_CubeOne "></div><div class="RW-Tabs-Loader_CubeOne RW-Tabs-Loader_CubeTwo"></div><div class="RW-Tabs-Loader_CubeOne RW-Tabs-Loader_CubeFour"></div><div class="RW-Tabs-Loader_CubeOne RW-Tabs-Loader_CubeThree"></div></div><div class="RW-Tabs-Loading_Text">Please Wait !</div></div></div>
</section>


<!-- RW Tabs Fixed Bar -->
<section id="RW_Tabs_Fixed_Bar_Section">
            <div id="RW_Tabs_Fixed_Bar" >
                <div class="RW_Tabs_Fixed_Bar_Links" style="display:none;">
                    <a href="https://rich-web.org/wp-tab-accordion/" target="_blank">
                        <div class="RW_Tabs_FlexLinks">
                            <span>Upgrade to Pro</span>
                            <div>
                                <i class="rich_web rich_web-shopping-cart"></i>
                            </div>
                        </div>
                    </a>
                    <a href="https://wordpress.org/support/plugin/tabbed/" target="_blank">
                        <div class="RW_Tabs_FlexLinks">
                            <span>Support</span>
                            <div>
                                <i class="rich_web rich_web-commenting-o"></i>
                            </div>
                        </div>
                    </a>
                    <a href="https://wordpress.org/support/plugin/tabbed/" target="_blank">
                        <div class="RW_Tabs_FlexLinks">
                            <span>Contact Us</span>
                            <div>
                                <i class="rich_web rich_web-send"></i>
                            </div>
                        </div>
                    </a>
                </div>
				<div  class="RW_Tabs_Fixed_Bar_Button" data-rw-title="Click to see" aria-hidden="true">
					<img src="<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/barlogo.gif');?>" alt="RW Tabs Bar" >
				</div>
			</div>
</section>


<?php if ($RW_Tabs_Build === false) { 
    $Rich_Web_Tabs_Data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Themes_Table WHERE id>%d",0));
    $Rich_Web_Tabs_Data_Edit = array_column(json_decode(json_encode($Rich_Web_Tabs_Data),true), 'Rich_Web_Tabs_Prop', 'id');
    $Rich_Web_Tabs_Data_Names = array_column(json_decode(json_encode($Rich_Web_Tabs_Data),true), 'Rich_Web_Tabs_T_T', 'id');
    $RW_Tabs_Tab_All = ["Rich_Web_Tabs_tabs_1","Rich_Web_Tabs_tabs_2","Rich_Web_Tabs_tabs_3","Rich_Web_Tabs_tabs_4","Rich_Web_Tabs_tabs_5","Rich_Web_Tabs_tabs_6","Rich_Web_Tabs_tabs_7","Rich_Web_Tabs_tabs_8","Rich_Web_Tabs_tabs_9","Rich_Web_Tabs_tabs_11","Rich_Web_Tabs_tabs_12","Rich_Web_Tabs_tabs_13","Rich_Web_Tabs_tabs_14","Rich_Web_Tabs_tabs_15","Rich_Web_Tabs_tabs_16","Rich_Web_Tabs_tabs_17","Rich_Web_Tabs_tabs_18","Rich_Web_Tabs_tabs_19","Rich_Web_Tabs_tabs_20","Rich_Web_Tabs_tabs_21","Rich_Web_Tabs_tabs_22","Rich_Web_Tabs_tabs_23","Rich_Web_Tabs_tabs_24","Rich_Web_Tabs_tabs_25","Rich_Web_Tabs_tabs_26","Rich_Web_Tabs_tabs_27","Rich_Web_Tabs_tabs_28","Rich_Web_Tabs_tabs_29","Rich_Web_Tabs_tabs_30","Rich_Web_Tabs_tabs_31","Rich_Web_Tabs_tabs_32"];
    $RW_Tabs_Acd_All = ["Rich_Web_Tabs_acd_1","Rich_Web_Tabs_acd_2","Rich_Web_Tabs_acd_3","Rich_Web_Tabs_acd_4","Rich_Web_Tabs_acd_5","Rich_Web_Tabs_acd_6","Rich_Web_Tabs_acd_7","Rich_Web_Tabs_acd_8","Rich_Web_Tabs_acd_9","Rich_Web_Tabs_acd_10","Rich_Web_Tabs_acd_11","Rich_Web_Tabs_acd_12","Rich_Web_Tabs_acd_13","Rich_Web_Tabs_acd_14","Rich_Web_Tabs_acd_15","Rich_Web_Tabs_acd_16","Rich_Web_Tabs_acd_17","Rich_Web_Tabs_acd_18","Rich_Web_Tabs_acd_19","Rich_Web_Tabs_acd_20","Rich_Web_Tabs_acd_21","Rich_Web_Tabs_acd_22","Rich_Web_Tabs_acd_23","Rich_Web_Tabs_acd_24","Rich_Web_Tabs_acd_25","Rich_Web_Tabs_acd_26","Rich_Web_Tabs_acd_27","Rich_Web_Tabs_acd_28","Rich_Web_Tabs_acd_29","Rich_Web_Tabs_acd_30","Rich_Web_Tabs_acd_31"];
    $RW_Tabs_T_Inserted = $RW_Tabs_A_Inserted = $RW_Tabs_T_N_Inserted = $RW_Tabs_A_N_Inserted = [];
    foreach ($Rich_Web_Tabs_Data as $key => $value) :
        if ($Rich_Web_Tabs_Data[$key]->Rich_Web_Tabs_T_Ty == 'Rich_Tabs_1') :
            $RW_Tabs_T_Inserted[] = $Rich_Web_Tabs_Data[$key]->Rich_Web_Tabs_T_N_S;
        elseif($Rich_Web_Tabs_Data[$key]->Rich_Web_Tabs_T_Ty == 'Rich_Tabs_2'):
            $RW_Tabs_A_Inserted[] = $Rich_Web_Tabs_Data[$key]->Rich_Web_Tabs_T_N_S;
        endif;
    endforeach;
    $RW_Tabs_T_N_Inserted = array_values(array_diff($RW_Tabs_Tab_All,$RW_Tabs_T_Inserted));
    $RW_Tabs_A_N_Inserted = array_values(array_diff($RW_Tabs_Acd_All,$RW_Tabs_A_Inserted));
    $RW_Tabs_TA_N_Inserted = array_merge($RW_Tabs_T_N_Inserted,$RW_Tabs_A_N_Inserted);
?>

<section id="RW-Tabs-Options-Content">
    <?php include "Tabs-Rich-Web-Header.php";  ?>

    <main id="RW-Tabs-Options-Layout" >
    <!-- Navigation -->
        <div id="RW_Tabs_Opt_Nav">
    	    <button class="RW_Tabs_Opt_Nav-Link all active" onclick="RW_Tabs_Opt_Filter('all')">All</button>
    	    <button class="RW_Tabs_Opt_Nav-Link free" onclick="RW_Tabs_Opt_Filter('free')">Free</button>
    	    <button class="RW_Tabs_Opt_Nav-Link pro" onclick="RW_Tabs_Opt_Filter('pro')">Pro</button>
    	    <button class="RW_Tabs_Opt_Nav-Link rw_tabs" onclick="RW_Tabs_Opt_Filter('rw_tabs')">Tabs</button>
    	    <button class="RW_Tabs_Opt_Nav-Link rw_accordion" onclick="RW_Tabs_Opt_Filter('rw_accordion')">Accordions</button>
	    </div>

    <!-- THEMES -->
        <div id="RW_Tabs_Options">
        <?php for ($i=0; $i < count($Rich_Web_Tabs_Data); $i++): 
            $RW_Tabs_Demo_Url = '';
            $RW_Tabs_TA_TNS_Number = (int) filter_var(esc_html($Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_N_S) , FILTER_SANITIZE_NUMBER_INT);
            ?>
            <div id="RW_Tabs_Opt_Content_<?php esc_attr_e($Rich_Web_Tabs_Data[$i]->id); ?>" class="RW_Tabs_Opt_Content <?php echo   $RW_Tabs_TA_TNS_Number < 9 ? 'free' : 'pro'; ?>   <?php echo   $Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_Ty == 'Rich_Tabs_1' ? 'rw_tabs' : 'rw_accordion'; ?>">
                <div class="RW_Tabs_Opt_Content-Header"></div>
                <div class="RW_Tabs_Opt_Content-Body">
                <img  <?php if ($Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_Ty == "Rich_Tabs_1") { 
                    $RW_Tabs_TA_TNS_Number_Img =  $RW_Tabs_TA_TNS_Number < 10 ? (int) $RW_Tabs_TA_TNS_Number   : (int) $RW_Tabs_TA_TNS_Number - 1;
                    $RW_Tabs_Demo_Url = "https://rich-web.org/wordpress-tabs-" . $RW_Tabs_TA_TNS_Number_Img;
                    ?>
                    src="<?php echo  esc_url(RW_TABS_PLUGIN_URL."Images/RW_Images/WordPress-Tab-".$RW_Tabs_TA_TNS_Number_Img.".png");?>"
                    <?php }else if($Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_Ty == "Rich_Tabs_2"){
                    $RW_Tabs_Demo_Url = "https://rich-web.org/wordpress-tab-accordion-faq-" . $RW_Tabs_TA_TNS_Number;
                        ?>
                    src="<?php echo  esc_url(RW_TABS_PLUGIN_URL."Images/RW_Images/WordPress-Accordion-".$RW_Tabs_TA_TNS_Number.".png");?>"
                    <?php } ?>
                alt="<?php esc_attr_e($Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_T); ?>" class="RW_Tabs_Opt_Content-Img">
                <a  href="<?php echo esc_url($RW_Tabs_Demo_Url); ?>" target="_blank" class="RW_Tabs_Opt_Content-Img-Overlay">
                    <i class="rich_web rich_web-eye"></i>
                </a>
                  <h1 class="RW_Tabs_Opt_Content-Name"><?php esc_html_e($Rich_Web_Tabs_Data[$i]->Rich_Web_Tabs_T_T); ?></h1>
                </div>
                <div class="RW_Tabs_Opt_Content-Footer">
                  <a class="RWTabs_Choose_Btn" href="<?php echo esc_url( add_query_arg( 'rw_theme', $Rich_Web_Tabs_Data[$i]->id ) ); ?>" >
                      <div class="RWTabs_Choose_Btn_Inner">
                        Choose Theme
                      </div>
                  </a>
                </div>
            </div>
        <?php  endfor; ?>
        <?php for ($n=0; $n < count($RW_Tabs_TA_N_Inserted); $n++): 
            $RW_Tabs_Demo_Url = '';
            $RW_Tabs_TA_Number = (int) filter_var($RW_Tabs_TA_N_Inserted[$n] , FILTER_SANITIZE_NUMBER_INT);
            if (strpos($RW_Tabs_TA_N_Inserted[$n], "Rich_Web_Tabs_tabs") !== false) :
                $RW_Tabs_TA_Number = $RW_Tabs_TA_Number > 10 ? $RW_Tabs_TA_Number - 1 : $RW_Tabs_TA_Number;
                $RW_Tabs_TA_Name = 'Tabs - ' . $RW_Tabs_TA_Number; 
                $RW_Tabs_TA_Type = 'tabs';
                $RW_Tabs_Demo_Url = "https://rich-web.org/wordpress-tabs-" . $RW_Tabs_TA_Number;
            else:   
                $RW_Tabs_TA_Name = 'Accordion - ' . $RW_Tabs_TA_Number;
                $RW_Tabs_TA_Type = 'accordion';
                $RW_Tabs_Demo_Url = "https://rich-web.org/wordpress-tab-accordion-faq-" . $RW_Tabs_TA_Number;
            endif;
        ?> 
            <div id="RW_Tabs_Opt_Content_<?php echo  $RW_Tabs_TA_N_Inserted[$n]; ?>" class="RW_Tabs_Opt_Content <?php echo   $RW_Tabs_TA_Number < 9 ? 'free' : 'pro'; ?> <?php echo   $RW_Tabs_TA_Type == 'tabs' ? 'rw_tabs' : 'rw_accordion'; ?>">
                <div class="RW_Tabs_Opt_Content-Header"></div>
                <div class="RW_Tabs_Opt_Content-Body">
                <img  <?php if ($RW_Tabs_TA_Type == "tabs") { ?>
                    src="<?php echo  esc_url(RW_TABS_PLUGIN_URL."Images/RW_Images/WordPress-Tab-".$RW_Tabs_TA_Number.".png");?>"
                    <?php }else if($RW_Tabs_TA_Type == "accordion"){ ?>
                    src="<?php echo  esc_url(RW_TABS_PLUGIN_URL."Images/RW_Images/WordPress-Accordion-".$RW_Tabs_TA_Number.".png");?>"
                    <?php } ?>
                alt="<?php  esc_attr_e($RW_Tabs_TA_Name); ?>" class="RW_Tabs_Opt_Content-Img">
                <a href="<?php echo esc_url($RW_Tabs_Demo_Url); ?>" target="_blank" class="RW_Tabs_Opt_Content-Img-Overlay" >
                    <i class="rich_web rich_web-eye"></i>
                </a>
                  <h1 class="RW_Tabs_Opt_Content-Name"><?php  esc_html_e($RW_Tabs_TA_Name); ?></h1>
                </div>
                <div class="RW_Tabs_Opt_Content-Footer">
                  <a href="<?php echo esc_url("https://rich-web.org/wp-tab-accordion/"); ?>" target="_blank">
                    <div class="RWTabs_Upgrade_Btn"  >
                        <div class="RWTabs_Upgrade_Btn_Inner">
                          Upgrade to Pro
                        </div>
                    </div>
                  </a>
                </div>
            </div>
        <?php  endfor; ?>
        </div>

    </main>

</section>

<?php } 
if ($RW_Tabs_Build === true && $RW_Tabs_Build_Theme !== false || $RW_Tabs_Build === true && $RW_Tabs_Build_ID !== false) {
    $RW_Tabs_ColorPicker_Arr = [
        "RW_Tabs_Menu_Bgc" => "--rw_tabs_menu_bgc","RW_Tabs_Menu_BC" => "--rw_tabs_menu_bc","RW_Tabs_Item_Bgc" => "--rw_tabs_menu_item-bgc","RW_Tabs_Item_Col" => "--rw_tabs_menu_item-c","RW_Tabs_HoverItem_Bgc" => "--rw_tabs_menu_item-h-bgc","RW_Tabs_HoverItem_Col" => "--rw_tabs_menu_item-h-c","RW_Tabs_ActiveItem_Bgc" => "--rw_tabs_menu_item-a-bgc","RW_Tabs_ActiveItem_Col" => "--rw_tabs_menu_item-a-c","RW_Tabs_C_Bgc_F" => "--rw_tabs_content_bgc","RW_Tabs_C_Bgc_S" => "--rw_tabs_content_bgc2","RW_Tabs_C_BC" => "--rw_tabs_content_bc"
    ]; 
    $RW_Tabs_ColorPicker_Arr_Acd = [
        "RW_Tabs_Item_Bgc" => "--rw_tabs_menu_item-bgc","RW_Tabs_Item_Col" => "--rw_tabs_menu_item-c","RW_Tabs_HoverItem_Bgc" => "--rw_tabs_menu_item-h-bgc","RW_Tabs_HoverItem_Col" => "--rw_tabs_menu_item-h-c","RW_Tabs_ActiveItem_Bgc" => "--rw_tabs_menu_item-a-bgc","RW_Tabs_ActiveItem_Col" => "--rw_tabs_menu_item-a-c","RW_Tabs_C_Bgc_F" => "--rw_tabs_content_bgc","RW_Tabs_C_Bgc_S" => "--rw_tabs_content_bgc2","RW_Tabs_C_BC" => "--rw_tabs_content_bc","RW_Tabs_LeftIcon" => "--rw_tabs_menu_item-li_c","RW_Tabs_LeftIcon_Hover" => "--rw_tabs_menu_item-li_c_h","RW_Tabs_LeftIcon_Active" => "--rw_tabs_menu_item-li_c_a","RW_Tabs_RightIcon" => "--rw_tabs_menu_item-ri_c","RW_Tabs_RightIcon_Hover" => "--rw_tabs_menu_item-ri_c_h","RW_Tabs_RightIcon_Active" => "--rw_tabs_menu_item-ri_c_a","RW_Tabs_Item_Border" => "--rw_tabs_menu_item-bc","RW_Tabs_Item_Border_Hover" => "--rw_tabs_menu_item-bc_h","RW_Tabs_Item_Border_Active" => "--rw_tabs_menu_item-bc_a","RWTabs_Item_BoxShadow" => "--rw_tabs_menu_bsh_b","RWTabs_Item_BoxShadow_Hover" => "--rw_tabs_menu_bsh_h","RWTabs_Item_BoxShadow_Active" => "--rw_tabs_menu_bsh_a"
    ]; 
    $RW_Tabs_Special_Colors_Arr =[
        "RW_Tabs_Spec_Item_C" => "--rw_tabs_menu_item-c"  ,"RW_Tabs_Spec_Item_H_C" => "--rw_tabs_menu_item-h-c" ,"RW_Tabs_Spec_Item_A_C" => "--rw_tabs_menu_item-a-c"
    ];
?>
<!-- RW Notice -->
<!-- $RW_Tabs_Build_Check -->
<section id="RW-Tabs-Notice-Section" style="display:none;">
    <div id="RW-Tabs-Notice-Section-Inner">
        <div><img src="<?php echo esc_url( RW_TABS_PLUGIN_URL.'Images/rich-web-logo.png');?>" alt="RW Logo"></div>
        <div class="RW-Tabs-Notice-Msg">
            <h1>Sorry,Dear customer!</h1>
            <h2>
                Tabs and Accordion builder is optimized for desktop computers and tablets.
            </h2>
        </div>
        <div class="RWTabs_Back_Sect"><i class="rich_web rich_web-home"></i> Back to Dashboard</div>
    </div>
</section>


<!-- RW Builder -->
<section id="RW-Tabs-Content" >
    <div id="RW-Tabs-Panel">
        <!--  Panel Header -->
        <div id="RW-Tabs-Panel-Header">
            <div class="RW_Tabs_Head_Back" >
               <span style="display:none;"> <i class="rich_web rich_web-angle-left"></i>Back</span>     
            </div>
            <div class="RW_Tabs_Head_Name" style="display:none;">
                <span>Edit Tab</span>  
            </div>
            <div class="RW_Tabs_Head_Img">
                <img src="<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rich-web-logo.png');?>" alt="Rich Web" title="Rich Web">   
            </div>
            <div class="RW_Tabs_Head_Globe" data-section="Global">
            </div>
        </div>
        <!--  Panel Header End -->
        <div id="RW-Tabs-Panel-Content">
            <!-- Panel Switcher -->
            <div id="RW-Tabs-Panel-switcher" onclick="RW_Tabs_Toggle_Panel()" title="Hide Panel">
                <i class="rich_web rich_web-chevron-left" aria-hidden="true" title="Hide Panel"></i>
            </div>
            <!-- Panel Switcher End -->
            <!-- Panel Content Start -->
            <header id="RW-Tabs-Panel-Content_Nav">
                <div class="RW-Tabs-Panel-Content_Nav-Bar active" data-section="Tabs" data-for="All">
                    <i class='rich_web rich_web-folder'></i>
                <?php echo  $RW_Tabs_Build_Type == "tab" ? '<span data-rw-option="tab">Tabs</span>' : '<span data-rw-option="accordion">Accordion</span>' ; ?>
                </div>
                <div class="RW-Tabs-Panel-Content_Nav-Bar" data-section="Menu" data-for="All">
                    <i class='rich_web rich_web-th-large '></i>
                    <span>Menu</span>
                </div>
                <div class="RW-Tabs-Panel-Content_Nav-Bar" data-section="Desc" data-for="All" >
                    <i class='rich_web rich_web-cog'></i>
                    <span>Content</span>
                </div>
                <!-- Edit Panel Header -->
                <div class="RW-Tabs-Panel-Content_Nav-Bar" data-section="Tabs_Menu" data-for="Edit" style="display:none;">
                    <span>Menu</span>
                </div>
                <div class="RW-Tabs-Panel-Content_Nav-Bar" data-section="Tabs_Desc" data-for="Edit"  style="display:none;">
                    <span>Content</span>
                </div>
                <!-- Edit Panel Header End -->
            </header>
            <!-- Panel Content End -->
            <main id="RW-Tabs-Nav-Content">
                <!-- Section for Tabs  -->
                <section id="RW-Tabs-Nav-Content_Tabs" style="display:inline-grid;">
                    <!-- Tabs Fields Sec -->
                    <?php echo  $RW_Tabs_Build_Type == "tab" ?
                     '<div class="rw-tabs-acc-item" data-rw-option="tab">Tabs Fields</div>' :
                     '<div class="rw-tabs-acc-item" data-rw-option="accordion">Accordion Fields</div>' ; ?>
                    
                    <div class="rw_tabs_control-panel">
                        <div class="rw_tabs_control-panel-flex">
                            <?php 
                            
                        if ($RW_Tabs_NewOld=='edit') {
                            $rw_tabs_static = 1;
                            $rw_tabs_fields = json_decode($RW_Tabs_Build_Edit->Tabs_Fields) ;
                            foreach ($rw_tabs_fields as $key => $value) :
                                if ($RW_Tabs_Build_Type == "tab") {
                                    echo sprintf("
                                        <div class='rw_tabs_fields' data-sort='%d' id='%s'>
                                            <span data-subicon='%s' data-subtitle='%s'>
                                                %s
                                            </span>
                                            <div class='rw_tabs_actions' data-elem='%d'>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Edit'  data-title='Edit'>
                                                    <i class='rich_web rich_web-pencil'></i>
                                                </div>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Copy' data-title='Copy'>
                                                    <i class='rich_web rich_web-files-o'></i>
                                                </div>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Delete'  data-title='Delete'>
                                                    <i class='rich_web rich_web-trash'></i>
                                                </div>
                                            </div>
                                            <textarea  style='display:none;' id='%s'>%s</textarea>
                                            <input  type='text' style='display:none;' class='rw_tabs_spec_c' id='%s' data-switch='%s' data-col='%s' data-hover='%s' data-active='%s'>
                                        </div>
                                        ",
                                        esc_attr($rw_tabs_static),
                                        "rw_tabs_field-".esc_attr($rw_tabs_static),
                                        esc_attr(filter_var($value->Tabs_Icon, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Subtitle, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Subtitle, FILTER_SANITIZE_STRING)),
                                        esc_attr($rw_tabs_static),
                                        "rw_tabs_desc_".esc_attr($rw_tabs_static),
                                        wp_specialchars_decode($value->Tabs_Content),
                                        "rw_tabs_spec_c-".esc_attr($rw_tabs_static),
                                        esc_attr(filter_var($value->Tabs_Special_Color, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Special_Color_B, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Special_Color_H, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Special_Color_A, FILTER_SANITIZE_STRING))
                                    );
                                    $rw_tabs_static++;
                                }else{
                                    echo sprintf("
                                        <div class='rw_tabs_fields' data-sort='%d' id='%s'>
                                            <span data-subicon='%s' data-subtitle='%s'>
                                                %s
                                            </span>
                                            <div class='rw_tabs_actions' data-elem='%d'>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Edit'  data-title='Edit'>
                                                    <i class='rich_web rich_web-pencil'></i>
                                                </div>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Copy' data-title='Copy'>
                                                    <i class='rich_web rich_web-files-o'></i>
                                                </div>
                                                <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Delete'  data-title='Delete'>
                                                    <i class='rich_web rich_web-trash'></i>
                                                </div>
                                            </div>
                                            <textarea  style='display:none;' id='%s'>%s</textarea>
                                        </div>
                                        ",
                                        esc_attr($rw_tabs_static),
                                        "rw_tabs_field-".esc_attr($rw_tabs_static),
                                        esc_attr(filter_var($value->Tabs_Icon, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Subtitle, FILTER_SANITIZE_STRING)),
                                        esc_attr(filter_var($value->Tabs_Subtitle, FILTER_SANITIZE_STRING)),
                                        esc_attr($rw_tabs_static),
                                        "rw_tabs_desc_".esc_attr($rw_tabs_static),
                                        wp_specialchars_decode($value->Tabs_Content)
                                    );
                                    $rw_tabs_static++;
                                }
                            endforeach;
                        }else {
                            for ($i=1; $i < 4; $i++) {
                                echo sprintf("
                                    <div class='rw_tabs_fields' data-sort='%d' id='%s'>
                                        <span data-subicon='%s' data-subtitle='%s'>
                                            %s
                                        </span>
                                        <div class='rw_tabs_actions' data-elem='%d'>
                                            <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Edit'  data-title='Edit'>
                                                <i class='rich_web rich_web-pencil'></i>
                                            </div>
                                            <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Copy' data-title='Copy'>
                                                <i class='rich_web rich_web-files-o'></i>
                                            </div>
                                            <div class='rw_tabs_field-action RW_Tabs_Prev_Act_Delete'  data-title='Delete'>
                                                <i class='rich_web rich_web-trash'></i>
                                            </div>
                                        </div>
                                        <textarea  style='display:none;' id='%s'>%s</textarea>
                                        <input  type='text' style='display:none;' class='rw_tabs_spec_c' id='%s' data-switch='%s' data-col='%s' data-hover='%s' data-active='%s'>
                                    </div>
                                    ",
                                    esc_attr($i),
                                    "rw_tabs_field-".esc_attr($i),
                                    "none",
                                    esc_attr(ucfirst($RW_Tabs_Build_Type))." ".esc_attr($i),
                                    esc_attr(ucfirst($RW_Tabs_Build_Type))." ".esc_attr($i),
                                    esc_attr($i),
                                    "rw_tabs_desc_".esc_attr($i),
                                    esc_attr(ucfirst($RW_Tabs_Build_Type))." ".esc_attr($i)." Content",
                                    "rw_tabs_spec_c-".esc_attr($i),
                                    "off",
                                    "",
                                    "",
                                    ""
                                );
                            }
                        }
                            ?>
                        </div>
                        <div class="rw_tabs_add-item" data-title="Add" ><i class="rich_web rich_web-plus"></i> Add Item</div>
                    </div>
                    <!-- Tabs Fields Sec End-->
                    <!-- General Options -->
                    <div class="rw-tabs-acc-item">General Options</div>
                    <div class="rw_tabs_control-panel">
                        <div class="rw_tabs_container_opt">
                        <?php echo  $RW_Tabs_Build_Type == "tab" ?
                            '<p class="rw-control-panel-title" data-rw-option="tab">Tabs width(%)</p>' :
                            '<p class="rw-control-panel-title" data-rw-option="accordion">Accordion width(%)</p>' ; ?>
                            <div class="rw_tabs_container_control">
                                <input type="range"  class="RW_Tabs_Width" min="40" max="100" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Width), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_Width" min="40" max="100"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Width), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_Width" oninput="this.previousElementSibling.value=this.value">
                            </div>
                            <div class="rw_tabs_container_control">
                            <?php echo  $RW_Tabs_Build_Type == "tab" ?
                            '<label for="RW_Tabs_T_Align" data-rw-option="tab">Tabs align</label>' :
                            '<label for="RW_Tabs_T_Align" data-rw-option="accordion">Accordion align</label>' ; ?>
                                <div class="RW_Tabs_Preview_Select">
                                    <input id="RW_Tabs_T_Align_Left" type="radio" name="RW_Tabs_T_Align" value="flex-start" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Align == "flex-start") echo 'checked="checked"';  ?>/>
                                    <label data-title="Left" class="RW_Tabs_Select_Label"  style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_left.png');?>);"  for="RW_Tabs_T_Align_Left"></label>
                                    <input id="RW_Tabs_T_Align_Center"  type="radio" name="RW_Tabs_T_Align" value="center" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Align == "center") echo 'checked="checked"';  ?>/>
                                    <label data-title="Center" class="RW_Tabs_Select_Label" style=" background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_center.png');?>);" for="RW_Tabs_T_Align_Center"></label>
                                    <input id="RW_Tabs_T_Align_Right"  type="radio" name="RW_Tabs_T_Align" value="flex-end" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Align == "flex-end") echo 'checked="checked"';  ?>/>
                                    <label data-title="Right" class="RW_Tabs_Select_Label " style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_right.png');?>);" for="RW_Tabs_T_Align_Right"></label>
                                </div>
                            </div>
                            <?php if ($RW_Tabs_Build_Type == "tab") : ?>
                                <div class="rw_tabs_container_control"  data-rw-option="tab">
                                    <label for="RW_Tabs_T_Type">Tabs type</label>
                                    <div class="RW_Tabs_Preview_Select">
                                        <input id="RW_Tabs_T_Type_Horizontal" type="radio" name="RW_Tabs_T_Type" value="horizontal" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Type == "horizontal") echo 'checked="checked"';  ?>/>
                                        <label data-title="Horizontal" class="RW_Tabs_Select_Label"  style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_horizontal.png');?>);"  for="RW_Tabs_T_Type_Horizontal"></label>
                                        <input id="RW_Tabs_T_Type_Vertical"  type="radio" name="RW_Tabs_T_Type" value="vertical" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Type == "vertical") echo 'checked="checked"';  ?>/>
                                        <label data-title="Vertical" class="RW_Tabs_Select_Label " style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_vertical.png');?>);" for="RW_Tabs_T_Type_Vertical"></label>
                                    </div>
                                </div>
                                <div class="rw_tabs_container_control" data-rw-option="tab">
                                    <label for="RW_Tabs_T_Mobile">Mobile type</label>
                                    <select name="RW_Tabs_T_Mobile" id="RW_Tabs_T_Mobile">
                                        <option value="accordion" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Mobile == "row") echo 'selected="selected"';  ?>>Accordion</option>
                                        <option value="tab" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Mobile == "row-reverse") echo 'selected="selected"';  ?>>Tab</option>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- General Options End -->
                </section>
                <!-- Section for Tabs  End -->
                <!-- Section for Menu  -->
                <section id="RW-Tabs-Nav-Content_Menu" style="display:none;">
                    <!-- Menu Left Icon -->
                    <?php if ($RW_Tabs_Build_Type == "accordion") : ?>
                    <div class="rw-tabs-acc-item" data-rw-option="accordion">Left icon</div>
                    <div class="rw_tabs_control-panel" data-rw-option="accordion">
                        <div class="rw_tabs_container_control">
                            <div id="RW_Tabs_LeftIcon-Cont"></div>
                            <label for="RW_Tabs_LeftIcon">Icon color</label>
                            <input type="text" name="RW_Tabs_LeftIcon" id="RW_Tabs_LeftIcon"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_B), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_LeftIcon-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_LeftIcon_Hover-Cont"></div>
                            <label for="RW_Tabs_LeftIcon_Hover">Icon hover</label>
                            <input type="text" name="RW_Tabs_LeftIcon_Hover" id="RW_Tabs_LeftIcon_Hover"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_H), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_LeftIcon_Hover-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_LeftIcon_Active-Cont"></div>
                            <label for="RW_Tabs_LeftIcon_Active">Icon active</label>
                            <input type="text" name="RW_Tabs_LeftIcon_Active" id="RW_Tabs_LeftIcon_Active"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_A), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_LeftIcon_Active-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_C_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <p class="rw-control-panel-title">Icon size</p>
                        <div class="rw_tabs_container_control">
                            <input type="range"  class="RW_Tabs_LeftIcon_Size" min="8" max="48" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_S), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_LeftIcon_Size" min="8" max="48"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_LI_S), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_LeftIcon_Size" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <!-- Menu Left Icon End -->
                    <!-- Menu Right Icon -->
                    <div class="rw-tabs-acc-item" data-rw-option="accordion">Right icon</div>
                    <div class="rw_tabs_control-panel" data-rw-option="accordion">
                        <div class="rw_tabs_container_control">
                            <label for="RW_Tabs_Right_Icon_S">Icon style</label>
                            <select name="RW_Tabs_RightIcon_Style" id="RW_Tabs_RightIcon_Style">
                                <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "none") echo 'selected="selected"';  ?> value="none">            None    </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "sort-desc") echo 'selected="selected"';  ?> value="sort-desc">       Style 1 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "circle") echo 'selected="selected"';  ?> value="circle">          Style 2 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "angle-double-up") echo 'selected="selected"';  ?> value="angle-double-up"> Style 3 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "arrow-circle-up") echo 'selected="selected"';  ?> value="arrow-circle-up"> Style 4 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "angle-up") echo 'selected="selected"';  ?> value="angle-up">        Style 5 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_RightIcon == "plus") echo 'selected="selected"';  ?> value="plus">            Style 6 </option>
                            </select>
                        </div>
                        <div class="rw_tabs_container_control">
                            <div id="RW_Tabs_RightIcon-Cont"></div>
                            <label for="RW_Tabs_RightIcon">Icon color</label>
                            <input type="text" name="RW_Tabs_RightIcon" id="RW_Tabs_RightIcon"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_B), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_RightIcon-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_RightIcon_Hover-Cont"></div>
                            <label for="RW_Tabs_RightIcon_Hover">Icon hover</label>
                            <input type="text" name="RW_Tabs_RightIcon_Hover" id="RW_Tabs_RightIcon_Hover"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_H), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_RightIcon_Hover-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_RightIcon_Active-Cont"></div>
                            <label for="RW_Tabs_RightIcon_Active">Icon active</label>
                            <input type="text" name="RW_Tabs_RightIcon_Active" id="RW_Tabs_RightIcon_Active"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_A), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_RightIcon_Active-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_C_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <p class="rw-control-panel-title">Icon size</p>
                        <div class="rw_tabs_container_control">
                            <input type="range"  class="RW_Tabs_RightIcon_Size" min="8" max="48" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_S), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_RightIcon_Size" min="8" max="48"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_RI_S), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_RightIcon_Size" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <!-- Menu Right Icon End -->
                    <!-- Menu Navigation Options -->
                    <?php elseif($RW_Tabs_Build_Type == "tab") : 

                        switch ($RW_Tabs_Theme_Proporties->RWTabs_Type) {
                            case 'vertical':
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Changer_Pos = "flex-start";
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Wrap = "nowrap";
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Gap = 3;
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Height = 150;
                                break;
                            case 'horizontal':
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Changer_Pos = "row";
                                $RW_Tabs_Theme_Proporties->RWTabs_M_Width = 150;
                                break;
                        }

                        
                        ?>
                    <div class="rw-tabs-acc-item" data-rw-option="tab">Global Options</div>
                    <div class="rw_tabs_control-panel" data-rw-option="tab">


                        <div class="rw_tabs_container_control" data-rw-type="vertical" >
                            <label for="RW_Tabs_V_M_Align">Menu position</label>
                            <div class="RW_Tabs_Preview_Select">
                                <input id="RW_Tabs_V_M_Align_Left" type="radio" name="RW_Tabs_V_M_Align" value="row" 
                                    <?php if ($RW_Tabs_Theme_Proporties->RWTabs_Type == 'vertical') {
                                        if($RW_Tabs_Theme_Proporties->RWTabs_M_Pos == "row"){ echo 'checked="checked"';  }
                                    }else{
                                        if($RW_Tabs_Theme_Proporties->RWTabs_M_Changer_Pos == "row"){ echo 'checked="checked"';  }
                                    } ?>
                                />
                                <label data-title="Left" class="RW_Tabs_Select_Label"  style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_left.png');?>);"  for="RW_Tabs_V_M_Align_Left"></label>
                                <input id="RW_Tabs_V_M_Align_Right"  type="radio" name="RW_Tabs_V_M_Align" value="row-reverse" 
                                <?php if ($RW_Tabs_Theme_Proporties->RWTabs_Type == 'vertical') {
                                            if($RW_Tabs_Theme_Proporties->RWTabs_M_Pos == "row-reverse") echo 'checked="checked"';                                    
                                    }  ?>
                                />
                                <label data-title="Right" class="RW_Tabs_Select_Label " style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_right.png');?>);" for="RW_Tabs_V_M_Align_Right"></label>
                            </div>
                        </div>

                        <p class="rw-control-panel-title" data-rw-type="vertical" >Menu width</p>
                        <div class="rw_tabs_container_control" data-rw-type="vertical" > 
                            <input type="range"  class="RW_Tabs_V_M_Width" min="50" max="300" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Width), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_V_M_Width" min="50" max="300"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Width), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_V_M_Width" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    
                        <div class="rw_tabs_container_control" data-rw-type="horizontal">
                            <label for="RW_Tabs_M_Align">Menu position</label>
                            <div class="RW_Tabs_Preview_Select">
                                <input id="RW_Tabs_M_Align_Left" type="radio" name="RW_Tabs_M_Align" value="flex-start" 
                                <?php if ($RW_Tabs_Theme_Proporties->RWTabs_Type == 'horizontal') {
                                        if($RW_Tabs_Theme_Proporties->RWTabs_M_Pos == "flex-start"){ echo 'checked="checked"';  }
                                    }else{
                                        if($RW_Tabs_Theme_Proporties->RWTabs_M_Changer_Pos == "flex-start"){ echo 'checked="checked"';  }
                                    } ?> />
                                <label data-title="Left" class="RW_Tabs_Select_Label"  style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_left.png');?>);"  for="RW_Tabs_M_Align_Left"></label>
                                <input id="RW_Tabs_M_Align_Center"  type="radio" name="RW_Tabs_M_Align" value="center"  <?php if ($RW_Tabs_Theme_Proporties->RWTabs_Type == 'horizontal') { if($RW_Tabs_Theme_Proporties->RWTabs_M_Pos == "center"){ echo 'checked="checked"';  } } ?> />
                                <label data-title="Center" class="RW_Tabs_Select_Label" style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_center.png');?>);" for="RW_Tabs_M_Align_Center"></label>
                                <input id="RW_Tabs_M_Align_Right"  type="radio" name="RW_Tabs_M_Align" value="flex-end"  <?php if ($RW_Tabs_Theme_Proporties->RWTabs_Type == 'horizontal') { if($RW_Tabs_Theme_Proporties->RWTabs_M_Pos == "flex-end"){ echo 'checked="checked"';  } } ?> />
                                <label data-title="Right" class="RW_Tabs_Select_Label " style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_right.png');?>);" for="RW_Tabs_M_Align_Right"></label>
                            </div>
                        </div>



                        <div class="rw_tabs_container_control" data-rw-type="horizontal" >
                            <label for="RW_Tabs_M_Flex-Wrap">Menu wrap</label>
                            <div class="RW_Tabs_Preview_Select">
                                <input id="RW_Tabs_M_Flex-Wrap_No"  type="radio" name="RW_Tabs_M_Flex-Wrap" value="nowrap" <?php if($RW_Tabs_Theme_Proporties->RWTabs_M_Wrap == "nowrap") echo 'checked="checked"';  ?>/>
                                <label data-title="No-Wrap" class="RW_Tabs_Select_Label " style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_nowrap.png');?>);" for="RW_Tabs_M_Flex-Wrap_No"></label>
                                <input id="RW_Tabs_M_Flex-Wrap_Yes" type="radio" name="RW_Tabs_M_Flex-Wrap" value="wrap" <?php if($RW_Tabs_Theme_Proporties->RWTabs_M_Wrap == "wrap") echo 'checked="checked"';  ?>/>
                                <label data-title="Wrap" class="RW_Tabs_Select_Label"  style="background-image:url(<?php echo esc_url(RW_TABS_PLUGIN_URL.'Images/rwtabs_wrap.png');?>);"  for="RW_Tabs_M_Flex-Wrap_Yes"></label>
                            </div>
                        </div>

                        <p class="rw-control-panel-title" data-rw-type="horizontal" >Menu height</p>
                        <div class="rw_tabs_container_control" data-rw-type="horizontal">
                            <input type="range"  class="RW_Tabs_M_Height" min="50" max="200" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Height), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_M_Height" min="50" max="200"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Height), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_M_Height" oninput="this.previousElementSibling.value=this.value">
                        </div>

                        <p class="rw-control-panel-title" data-rw-type="horizontal">Place between items</p>
                        <div class="rw_tabs_container_control" data-rw-type="horizontal">
                            <input type="range"  class="RW_Tabs_M_Gap" min="0" max="15" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_M_Gap" min="0" max="15"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_M_Gap" oninput="this.previousElementSibling.value=this.value">
                        </div>



                        <div class="rw_tabs_container_control" >
                            <div id="RW_Tabs_Menu_Bgc-Cont"></div>
                            <label for="RW_Tabs_Menu_Bgc">Menu background</label>
                            <input type="text"  name="RW_Tabs_Menu_Bgc" id="RW_Tabs_Menu_Bgc"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_M_Bgc), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_Menu_Bgc-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_M_Bgc), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control" >
                            <div id="RW_Tabs_Menu_BC-Cont"></div>
                            <label for="RW_Tabs_Menu_BC">Menu border color</label>
                            <input type="text" name="RW_Tabs_Menu_BC" id="RW_Tabs_Menu_BC" value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_M_BC), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_Menu_BC-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_M_BC), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>

                    </div>
                    <?php endif; ?>
                    <!-- Menu Navigation Options End-->
                    <!-- Menu Items Options -->

                    <div class="rw-tabs-acc-item">Menu Items</div>
                    <div class="rw_tabs_control-panel">
                        <p class="rw-control-panel-title">Font size</p>
                        <div class="rw_tabs_container_control">
                            <input type="range"  class="RW_Tabs_Item_FS" min="8" max="48" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_FontSize), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_Item_FS" min="8" max="48"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_FontSize), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_Item_FS" oninput="this.previousElementSibling.value=this.value">
                        </div>
                        <div class="rw_tabs_container_control">
                            <label for="RW_Tabs_Item_FF">Font family</label>
                            <select name="RW_Tabs_Item_FF" id="RW_Tabs_Item_FF">
                            <?php foreach ($Rich_WebFontCount as $key => $Rich_Web_Tabs_Font) : ?> 
                                <option value="<?php echo  esc_attr($Rich_Web_Tabs_Font) ;?>" <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_FontFamily == $Rich_Web_Tabs_Font) echo 'selected="selected"';  ?>   style="font-family:<?php echo  esc_attr($Rich_Web_Tabs_Font) ;?>;">
                                 <?php echo  esc_html($Rich_Web_Tabs_Font) ;?>
                                </option>
                             <?php endforeach; ?>
                            </select>
                        </div>


                        <?php if ($RW_Tabs_Build_Type == "tab") : ?>
                            <p class="rw-control-panel-title" data-rw-option="tab">Icon size</p>
                            <div class="rw_tabs_container_control" data-rw-option="tab">
                                <input type="range"  class="RW_Tabs_Item_IS" min="8" max="72" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_IconSize), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_Item_IS" min="8" max="72"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_IconSize), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_Item_IS" oninput="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <?php elseif($RW_Tabs_Build_Type == "accordion") : ?>
                            <div class="rw_tabs_container_control" data-rw-option="accordion">
                                <label for="RW_Tabs_Item_Text_Style">Text style</label>
                                <select name="RW_Tabs_Item_Text_Style" id="RW_Tabs_Item_Text_Style">
                                    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_none") echo 'selected="selected"';  ?> value="style_ti_none"> None    </option>
					        	    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_1") echo 'selected="selected"';  ?> value="style_ti_1">    Style 1 </option>
					        	    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_2") echo 'selected="selected"';  ?> value="style_ti_2">    Style 2 </option>
					        	    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_3") echo 'selected="selected"';  ?> value="style_ti_3">    Style 3 </option>
					        	    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_4") echo 'selected="selected"';  ?> value="style_ti_4">    Style 4 </option>
					        	    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Text_S == "style_ti_5") echo 'selected="selected"';  ?> value="style_ti_5">    Style 5 </option>
                                </select>
                            </div>

                            <p class="rw-control-panel-title" data-rw-option="accordion">Place between menus</p>
                            <div class="rw_tabs_container_control" data-rw-option="accordion">
                                <input type="range"  class="RW_Tabs_M_Gap" min="0" max="15" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_M_Gap" min="0" max="15"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_M_Gap" oninput="this.previousElementSibling.value=this.value">
                            </div>
                            <p class="rw-control-panel-title" data-rw-option="accordion">Place between menu content</p>
                            <div class="rw_tabs_container_control" data-rw-option="accordion">
                                <input type="range"  class="RW_Tabs_M_C_Gap" min="0" max="15" value="<?php echo $RW_Tabs_Theme_Proporties->RW_Tabs_M_C_Gap == "" ? esc_attr('0') : filter_var(esc_attr($RW_Tabs_Theme_Proporties->RW_Tabs_M_C_Gap), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_M_C_Gap" min="0" max="15"  value="<?php echo $RW_Tabs_Theme_Proporties->RW_Tabs_M_C_Gap == "" ? esc_attr('0') : filter_var(esc_attr($RW_Tabs_Theme_Proporties->RW_Tabs_M_C_Gap), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_M_C_Gap" oninput="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                                            <!-- Menu Items Options  End-->
                    <!-- Menu Box Shadow Options -->
                    <div class="rw-tabs-acc-item"  data-rw-option="accordion">Box Shadow</div>
                    <div class="rw_tabs_control-panel"  data-rw-option="accordion">
                        <div class="rw_tabs_container_control">
                            <label for="RW_Tabs_Menu_BoxShadow">Box shadow style</label>
                            <select name="RW_Tabs_Menu_BoxShadow" id="RW_Tabs_Menu_BoxShadow">
                                <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "none") echo 'selected="selected"';  ?> value="none">         None     </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_1") echo 'selected="selected"';  ?> value="style_bsh_1"> Style 1   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_2") echo 'selected="selected"';  ?> value="style_bsh_2"> Style 2   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_3") echo 'selected="selected"';  ?> value="style_bsh_3"> Style 3   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_4") echo 'selected="selected"';  ?> value="style_bsh_4"> Style 4   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_5") echo 'selected="selected"';  ?> value="style_bsh_5"> Style 5   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_6") echo 'selected="selected"';  ?> value="style_bsh_6"> Style 6   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_7") echo 'selected="selected"';  ?> value="style_bsh_7"> Style 7   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_8") echo 'selected="selected"';  ?> value="style_bsh_8"> Style 8   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_9") echo 'selected="selected"';  ?> value="style_bsh_9"> Style 9   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_10") echo 'selected="selected"';  ?> value="style_bsh_10">Style 10  </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_11") echo 'selected="selected"';  ?> value="style_bsh_11">Style 11  </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_12") echo 'selected="selected"';  ?> value="style_bsh_12">Style 12  </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_13") echo 'selected="selected"';  ?> value="style_bsh_13">Style 13  </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_14") echo 'selected="selected"';  ?> value="style_bsh_14">Style 14  </option>
                                <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_S == "style_bsh_15") echo 'selected="selected"';  ?> value="style_bsh_15">Style 15  </option>
                            </select>
                        </div>
                        <div class="rw_tabs_container_control">
                            <div id="RWTabs_Item_BoxShadow-Cont"></div>
                                <label for="RWTabs_Item_BoxShadow">Shadow color</label>
                                <input type="text" name="RWTabs_Item_BoxShadow" id="RWTabs_Item_BoxShadow"
                                      value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_B), FILTER_SANITIZE_STRING);?>">
                                <input type="text"  id="RWTabs_Item_BoxShadow-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RWTabs_Item_BoxShadow_Hover-Cont"></div>
                            <label for="RWTabs_Item_BoxShadow_Hover">Shadow hover</label>
                            <input type="text" name="RWTabs_Item_BoxShadow_Hover" id="RWTabs_Item_BoxShadow_Hover"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_H), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RWTabs_Item_BoxShadow_Hover-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>   
                        <div class="rw_tabs_container_control">
                             <div id="RWTabs_Item_BoxShadow_Active-Cont"></div>
                            <label for="RWTabs_Item_BoxShadow_Active">Shadow active</label>
                            <input type="text" name="RWTabs_Item_BoxShadow_Active" id="RWTabs_Item_BoxShadow_Active"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_A), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RWTabs_Item_BoxShadow_Active-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Box_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>   
                    </div>
                    <!-- Menu Box Shadow Options  End-->
                     <!-- Menu Items Borders -->
                    <div class="rw-tabs-acc-item" data-rw-option="accordion">Menu border</div>
                    <div class="rw_tabs_control-panel" data-rw-option="accordion">
                            <div class="rw_tabs_container_control">
                                <label for="RW_Tabs_Menu_BS">Border style</label>
                                <select name="RW_Tabs_Menu_BS" id="RW_Tabs_Menu_BS">
                                    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_BS == "none") echo 'selected="selected"';  ?>  value="none">None</option>
                                    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_BS == "solid") echo 'selected="selected"';  ?>  value="solid">Solid</option>
                                    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_BS == "dotted") echo 'selected="selected"';  ?>  value="dotted">Dotted</option>
                                    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_BS == "dashed") echo 'selected="selected"';  ?>  value="dashed">Dashed</option>
                                </select>
                            </div>
                            <div class="rw_tabs_container_control">
                                <div id="RW_Tabs_Item_Border-Cont"></div>
                                    <label for="RW_Tabs_Item_Border">Border color</label>
                                    <input type="text" name="RW_Tabs_Item_Border" id="RW_Tabs_Item_Border"
                                          value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_B), FILTER_SANITIZE_STRING);?>">
                                    <input type="text"  id="RW_Tabs_Item_Border-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                            </div>
                            <div class="rw_tabs_container_control">
                                 <div id="RW_Tabs_Item_Border_Hover-Cont"></div>
                                <label for="RW_Tabs_Item_Border_Hover">Border hover</label>
                                <input type="text" name="RW_Tabs_Item_Border_Hover" id="RW_Tabs_Item_Border_Hover"
                                      value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_H), FILTER_SANITIZE_STRING);?>">
                                <input type="text"  id="RW_Tabs_Item_Border_Hover-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                            </div>
                            <div class="rw_tabs_container_control">
                                 <div id="RW_Tabs_Item_Border_Active-Cont"></div>
                                <label for="RW_Tabs_Item_Border_Active">Border active</label>
                                <input type="text" name="RW_Tabs_Item_Border_Active" id="RW_Tabs_Item_Border_Active"
                                      value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_A), FILTER_SANITIZE_STRING);?>">
                                <input type="text"  id="RW_Tabs_Item_Border_Active-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_BC_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                            </div>
                            <p class="rw-control-panel-title" data-rw-option="accordion">Border radius</p>
                            <div class="rw_tabs_container_control" data-rw-option="accordion">
                                <input type="range"  class="RW_Tabs_Item_Border_R" min="0" max="15" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_BR), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_Item_Border_R" min="0" max="15"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_BR), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_Item_Border_R" oninput="this.previousElementSibling.value=this.value">
                            </div>
                            <p class="rw-control-panel-title" data-rw-option="accordion">Border width</p>
                            <div class="rw_tabs_container_control" data-rw-option="accordion">
                                <input type="range"  class="RW_Tabs_Item_Border_W" min="0" max="15" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_BW), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                                <input type="number" name="RW_Tabs_Item_Border_W" min="0" max="15"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_Item_BW), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_Item_Border_W" oninput="this.previousElementSibling.value=this.value">
                            </div>
                    </div>
                            <!-- Menu Items Borders  End-->
                        <?php endif; ?>
                    

                    <!-- Menu Items Colors -->
                    <div class="rw-tabs-acc-item">Menu Colors</div>
                    <div class="rw_tabs_control-panel">

                    <?php if($RW_Tabs_Build_Type == "accordion") : ?>
                        <div class="rw_tabs_container_control"  data-rw-option="accordion">
                            <label for="RW_Tabs_Menu_Bgc_Style">Background style</label>
                            <select name="RW_Tabs_Menu_Bgc_Style" id="RW_Tabs_Menu_Bgc_Style">
                                <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_none") echo 'selected="selected"';  ?> value="style_bg_none"> None    </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_1") echo 'selected="selected"';  ?> value="style_bg_1">    Style 1 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_2") echo 'selected="selected"';  ?> value="style_bg_2">    Style 2 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_3") echo 'selected="selected"';  ?> value="style_bg_3">    Style 3 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_4") echo 'selected="selected"';  ?> value="style_bg_4">    Style 4 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_5") echo 'selected="selected"';  ?> value="style_bg_5">    Style 5 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_6") echo 'selected="selected"';  ?> value="style_bg_6">    Style 6 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_7") echo 'selected="selected"';  ?> value="style_bg_7">    Style 7 </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_S == "style_bg_8") echo 'selected="selected"';  ?> value="style_bg_8">    Style 8 </option>
                            </select>
                        </div>
                    <?php endif; ?>

                        <div class="rw_tabs_container_control">
                            <div id="RW_Tabs_Item_Bgc-Cont"></div>
                            <label for="RW_Tabs_Item_Bgc">Item background</label>
                            <input type="text" name="RW_Tabs_Item_Bgc" id="RW_Tabs_Item_Bgc"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_B), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_Item_Bgc-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_Item_Col-Cont"></div>
                            <label for="RW_Tabs_Item_Col">Item color</label>
                            <input type="text" name="RW_Tabs_Item_Col" id="RW_Tabs_Item_Col"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_B), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_Item_Col-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_B), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_ActiveItem_Bgc-Cont"></div>
                            <label for="RW_Tabs_ActiveItem_Bgc">Active item background</label>
                            <input type="text" name="RW_Tabs_ActiveItem_Bgc" id="RW_Tabs_ActiveItem_Bgc"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_A), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_ActiveItem_Bgc-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_ActiveItem_Col-Cont"></div>
                            <label for="RW_Tabs_ActiveItem_Col">Active item color</label>
                            <input type="text" name="RW_Tabs_ActiveItem_Col" id="RW_Tabs_ActiveItem_Col"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_A), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_ActiveItem_Col-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_A), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_HoverItem_Bgc-Cont"></div>
                            <label for="RW_Tabs_HoverItem_Bgc">Hover item background</label>
                            <input type="text" name="RW_Tabs_HoverItem_Bgc" id="RW_Tabs_HoverItem_Bgc"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_H), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_HoverItem_Bgc-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Bgc_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                        <div class="rw_tabs_container_control">
                             <div id="RW_Tabs_HoverItem_Col-Cont"></div>
                            <label for="RW_Tabs_HoverItem_Col">Hover item color</label>
                            <input type="text" name="RW_Tabs_HoverItem_Col" id="RW_Tabs_HoverItem_Col"
                                  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_H), FILTER_SANITIZE_STRING);?>">
                            <input type="text"  id="RW_Tabs_HoverItem_Col-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_Item_Col_H), FILTER_SANITIZE_STRING);?>" style="display:none;">
                        </div>
                    </div>
                    <!-- Menu Items Colors End-->
                </section>
                <!-- Section for Menu End  -->
                <!-- Section for Menu Description  -->
                <section id="RW-Tabs-Nav-Content_Desc" style="display:none;">
                    <div class="rw-tabs-acc-item">Content Options</div>
                    <div class="rw_tabs_control-panel">
                        <div class="rw_tabs_container_control">
                            <label for="RW_Tabs_C_Type">Background type</label>
                            <select name="RW_Tabs_C_Type" id="RW_Tabs_C_Type">
                                <option value="color">Color</option>
                                <option value="transparent">Transparent</option>
                                <?php if ($RW_Tabs_Build_Type == "tab") : 
                                    echo '
                                    <option value="gradient" data-rw-option="tab">Gradient</option>
                                    ';
                                elseif($RW_Tabs_Build_Type == "accordion") : 
                                    echo '
                                    <option value="gradient" data-rw-option="accordion">Gradient(Left to Right)</option>
                                    <option value="gradient-top" data-rw-option="accordion">Gradient(Top to Bottom)</option>
                                    ';
                                endif; ?>

                            </select>
                        </div>
                        <?php 
                            switch ($RW_Tabs_Theme_Proporties->RWTabs_C_Type) {
                                case 'color':
                                    $RW_Tabs_Theme_Proporties->RWTabs_C_Col_S = '#FFFFFF';
                                    break;
                                case 'transparent':
                                    $RW_Tabs_Theme_Proporties->RWTabs_C_Col_F = '#FFFFFF';
                                    $RW_Tabs_Theme_Proporties->RWTabs_C_Col_S = '#FFFFFF';
                                    break;
                            }
                        ?>
                        <div class="rw_tabs_container_control rw_tabs_control_desc_cf">
                            <div id="RW_Tabs_C_Bgc_F-Cont"></div>
                            <label for="RW_Tabs_C_Bgc_F">Background first</label>
                            <input type="text" name="RW_Tabs_C_Bgc_F" id="RW_Tabs_C_Bgc_F"
                              value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>">
                              <input type="text" style="display:none;" id="RW_Tabs_C_Bgc_F-Out" value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>">
                        </div>
                        <div class="rw_tabs_container_control rw_tabs_control_desc_cs" style="display:none;">
                            <div id="RW_Tabs_C_Bgc_S-Cont"></div>
                            <label for="RW_Tabs_C_Bgc_S">Background second</label>
                            <input type="text" name="RW_Tabs_C_Bgc_S" id="RW_Tabs_C_Bgc_S"
                              value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_C_Col_S), FILTER_SANITIZE_STRING);?>">
                              <input type="text" style="display:none;" id="RW_Tabs_C_Bgc_S-Out" value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RWTabs_C_Col_S), FILTER_SANITIZE_STRING);?>">
                        </div>
                        <p class="rw-control-panel-title">Border width</p>
                        <div class="rw_tabs_container_control">
                            <input type="range"  class="RW_Tabs_C_BW" min="0" max="10" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_C_BW), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_C_BW" min="0" max="10"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_C_BW), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_C_BW" oninput="this.previousElementSibling.value=this.value">
                        </div>
                        <div class="rw_tabs_container_control">
                            <div id="RW_Tabs_C_BC-Cont"></div>
                            <label for="RW_Tabs_C_BC">Border color</label>
                            <input type="text" name="RW_Tabs_C_BC" id="RW_Tabs_C_BC"
                              value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RW_Tabs_C_BC), FILTER_SANITIZE_STRING);?>">
                              <input type="text" style="display:none;" id="RW_Tabs_C_BC-Out"  value="<?php echo filter_var(esc_html($RW_Tabs_Theme_Proporties->RW_Tabs_C_BC), FILTER_SANITIZE_STRING);?>">
                        </div>
                        <p class="rw-control-panel-title">Border radius</p>
                        <div class="rw_tabs_container_control">
                            <input type="range"  class="RW_Tabs_C_BR" min="0" max="20" value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_C_BR), FILTER_VALIDATE_INT); ?>" oninput="this.nextElementSibling.value=this.value" >
                            <input type="number" name="RW_Tabs_C_BR" min="0" max="20"  value="<?php echo filter_var(esc_attr($RW_Tabs_Theme_Proporties->RWTabs_C_BR), FILTER_VALIDATE_INT); ?>" id="RW_Tabs_C_BR" oninput="this.previousElementSibling.value=this.value">
                        </div>
                        <?php if($RW_Tabs_Build_Type == "accordion") : ?>

                        <div class="rw_tabs_container_control" data-rw-option="accordion">
                            <label for="RWTabs_Cont_Anim">Animation style</label>
                            <select name="RWTabs_Cont_Anim" id="RWTabs_Cont_Anim">
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "none") echo 'selected="selected"';  ?> value="none">      None      </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "bounce") echo 'selected="selected"';  ?> value="bounce">    Bounce    </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "clip") echo 'selected="selected"';  ?> value="clip">      Clip      </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "drop") echo 'selected="selected"';  ?> value="drop">      Drop      </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "fade") echo 'selected="selected"';  ?> value="fade">      Fade      </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "highlight") echo 'selected="selected"';  ?> value="highlight"> Highlight </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "pulsate") echo 'selected="selected"';  ?> value="pulsate">   Pulsate   </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "shake") echo 'selected="selected"';  ?> value="shake">     Shake     </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "size") echo 'selected="selected"';  ?> value="size">      Size      </option>
							    <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_Cont_Anim == "slide") echo 'selected="selected"';  ?> value="slide">     Slide     </option>
                            </select>
                        </div>
                        <?php elseif($RW_Tabs_Build_Type == "tab") : ?>

                        <div class="rw_tabs_container_control"  data-rw-option="tab">
                            <label for="RWTabs_C_Anim">Animation style</label>
                            <select name="RWTabs_C_Anim" id="RWTabs_C_Anim">
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "") echo 'selected="selected"';  ?> value="">None</option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "Random") echo 'selected="selected"';  ?> value="Random">          Random            </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "Scale") echo 'selected="selected"';  ?> value="Scale" >          Scale             </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "FadeUp") echo 'selected="selected"';  ?> value="FadeUp">          Fade Up           </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "FadeDown") echo 'selected="selected"';  ?> value="FadeDown">        Fade Down         </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "FadeLeft") echo 'selected="selected"';  ?> value="FadeLeft">        Fade Left         </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "FadeRight") echo 'selected="selected"';  ?> value="FadeRight">       Fade Right        </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "SlideUp") echo 'selected="selected"';  ?> value="SlideUp">         Slide Up          </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "SlideDown") echo 'selected="selected"';  ?> value="SlideDown">       Slide Down        </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "SlideLeft") echo 'selected="selected"';  ?> value="SlideLeft">       Slide Left        </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "SlideRight") echo 'selected="selected"';  ?> value="SlideRight">      Slide Right       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "ScrollDown") echo 'selected="selected"';  ?> value="ScrollDown">      Scroll Down       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "ScrollUp") echo 'selected="selected"';  ?> value="ScrollUp">        Scroll Up         </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "ScrollRight") echo 'selected="selected"';  ?> value="ScrollRight">     Scroll Right      </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "ScrollLeft") echo 'selected="selected"';  ?> value="ScrollLeft">      Scroll Left       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "Bounce") echo 'selected="selected"';  ?> value="Bounce">          Bounce            </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "BounceLeft") echo 'selected="selected"';  ?> value="BounceLeft">      Bounce Left       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "BounceRight") echo 'selected="selected"';  ?> value="BounceRight">     Bounce Right      </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "BounceDown") echo 'selected="selected"';  ?> value="BounceDown">      Bounce Down       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "BounceUp") echo 'selected="selected"';  ?> value="BounceUp">        Bounce Up         </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "HorizontalFlip") echo 'selected="selected"';  ?> value="HorizontalFlip">  Horizontal Flip   </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "VerticalFlip") echo 'selected="selected"';  ?> value="VerticalFlip">    Vertical Flip     </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "RotateDownLeft") echo 'selected="selected"';  ?> value="RotateDownLeft">  Rotate Down Left  </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "RotateDownRight") echo 'selected="selected"';  ?> value="RotateDownRight"> Rotate Down Right </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "RotateUpLeft") echo 'selected="selected"';  ?> value="RotateUpLeft">    Rotate Up Left    </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "RotateUpRight") echo 'selected="selected"';  ?> value="RotateUpRight">   Rotate Up Right   </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "TopZoom") echo 'selected="selected"';  ?> value="TopZoom">         Top Zoom          </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "BottomZoom") echo 'selected="selected"';  ?> value="BottomZoom">      Bottom Zoom       </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "LeftZoom") echo 'selected="selected"';  ?> value="LeftZoom">        Left Zoom         </option>
						        <option <?php if($RW_Tabs_Theme_Proporties->RWTabs_C_Anim == "RightZoom") echo 'selected="selected"';  ?> value="RightZoom">       Right Zoom        </option>
                            </select>
                        </div>

                        <?php endif; ?>
                    </div>    
                </section>
                <!-- Section for Menu Description End -->
                <!-- Section for Tabs Edit Menu  -->
                <section id="RW-Tabs-Nav-Content_Tabs_Menu" style="display:none;">
                        <div class="rw_tabs_control-panel_tabsMenu">
                            <p class="rw-control-panel-title">Tabs title</p>
                            <div class="rw_tabs_container_control">
                                <div class="rw_tabs_input-container">
                                    <input  type="text" placeholder="Tabs Subtitle" id="RW_Tabs_Subtitle_Sect">
                                    <span class="rich_web rich_web-refresh rw_tabs_restore_icon" data-title="Restore"></span>
                                </div>
                            </div>
                            <div class="rw_tabs_container_control">
                                <label for="Rich_Web_Tabs_SubTIcon">Tabs icon</label>
                                <div id="RW_Tabs_Icon_Picker-Cont"></div>
                                <select name="Rich_Web_Tabs_SubTIcon" id="Rich_Web_Tabs_SubTIcon" style="font-family: 'FontAwesome', Arial;">
							    <?php  foreach ($Rich_WebIconCount as $Icon_Name => $Icon_Type) : ?>
							    	<option value="rich_web-<?php echo  strtolower(str_replace(" ", "-", $Icon_Name)); ?>"> rich_web-<?php echo  strtolower(str_replace(" ", "-", $Icon_Name)); ?></option>
							     <?php endforeach; ?>
						        </select>
                            </div>

                        <?php if ($RW_Tabs_Build_Type == "tab") : ?>

                            <div class="rw-tabs-acc-item-switch" data-rw-option="tab">
                              <span>Special Colors</span>  
                                <label class="RW_Tabs_Switch_Prev RW_Tabs_Switch_Prev-light">
    					        <input class="RW_Tabs_Switch_Prev-input" type="checkbox"  id="RW_Tabs_Special_Colors">
    					        <span class="RW_Tabs_Switch_Prev-label" data-on="On" data-off="Off"></span> 
    					        <span class="RW_Tabs_Switch_Prev-handle"></span>
    					        </label>
                            </div>
                            <div class="rw_tabs_control-panel" id="rw_tabs_control_special_col" data-rw-option="tab"></div>
                            <div class="rw-tabs-acc-item-switch rw-tabs-acc-item-pro" data-rw-option="tab">
                              <span>Background Image</span>  
                                <label class="RW_Tabs_Switch_Prev RW_Tabs_Switch_Prev-light">
    					        <input class="RW_Tabs_Switch_Prev-input" type="checkbox"  id="RW_Tabs_Special_Image">
    					        <span class="RW_Tabs_Switch_Prev-label" data-on="On" data-off="Off"></span> 
    					        <span class="RW_Tabs_Switch_Prev-handle"></span>
    					        </label>
                            </div>
                            <div class="rw_tabs_control-panel" id="rw_tabs_control_special_bgc-img" data-rw-option="tab" >
                                <div id="rw_tabs_bgc-img-container" data-rw-option="tab">
                                    <div class="rw_tabs_bgc-img-inner" id="myprefix-preview-image"></div>
                                    <div class="rw_tabs_bgc-img-inner-choose">Choose image</div>
                                </div>
                                <input type="hidden" name="myprefix_image_id" id="myprefix_image_id" value="" class="regular-text" />
                            </div>
                            <div class="rw-tabs-acc-item-switch rw-tabs-acc-item-pro" data-rw-option="tab" >
                              <span>Special Background</span>  
                                <label class="RW_Tabs_Switch_Prev RW_Tabs_Switch_Prev-light">
    					        <input class="RW_Tabs_Switch_Prev-input" type="checkbox" id="RW_Tabs_Special_Bgc" >
    					        <span class="RW_Tabs_Switch_Prev-label" data-on="On" data-off="Off"></span> 
    					        <span class="RW_Tabs_Switch_Prev-handle"></span>
    					        </label>
                            </div>
                            <div class="rw_tabs_control-panel" id="rw_tabs_control_special_bgc" data-rw-option="tab" ></div>
                        <?php endif; ?>
                        </div> 
                </section>
                <!-- Section for Tabs Edit Menu End -->
                <!-- Section for Tabs Edit Desc  -->
                <section id="RW-Tabs-Nav-Content_Tabs_Desc" style="display:none;">
                    <div class="rw_tabs_control-panel_tabsContent">
                        <?php echo $RW_Tabs_Build_Type == "tab" ? '<p class="rw-control-panel-title" data-rw-option="tab">Tab content</p>' :
                                                                  '<p class="rw-control-panel-title" data-rw-option="accordion">Accordion content</p>'  ; ?>
                        
                        <div id="rw_tabs_container_control_Text">
                            <?php 
                            $elem = 'RW_Tabs_Tab_Content_Area';
                            $content = "";
                            $args = array(
                                'tinymce'       => array(
                                    'toolbar1' => 'formatselect, fontselect, fontsizeselect, image, media, code,  bold, italic, underline, blockquote, bullist, numlist, alignleft ,aligncenter, alignright, wp_more, fullscreen, strikethrough, hr, forecolor,backcolor , pastetext, removeformat, charmap, outdent, indent, undo, redo',
                                    'fontsize_formats' => '8px 10px 12px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px 42px 44px 46px 48px',
                                    'font_formats'=> 'Abadi MT Condensed Light = abadi mt condensed light; Aharoni = aharoni; Aldhabi = aldhabi; Andalus = andalus; Angsana New = angsana new; AngsanaUPC = angsanaupc; Aparajita = aparajita; Arabic Typesetting = arabic typesetting; Arial = arial; Arial Black = arial black; Batang = batang; BatangChe = batangche; Browallia New = browallia new; BrowalliaUPC = browalliaupc; Calibri = calibri; Calibri Light = calibri light; Calisto MT = calisto mt; Cambria = cambria; Candara = candara; Century Gothic = century gothic; Comic Sans MS = comic sans ms; Consolas = consolas; Constantia = constantia; Copperplate Gothic = copperplate gothic; Copperplate Gothic Light = copperplate gothic light; Corbel = corbel; Cordia New = cordia new; CordiaUPC = cordiaupc; Courier New = courier new; DaunPenh = daunpenh; David = david; DFKai-SB = dfkai-sb; DilleniaUPC = dilleniaupc; DokChampa = dokchampa; Dotum = dotum; DotumChe = dotumche; Ebrima = ebrima; Estrangelo Edessa = estrangelo edessa; EucrosiaUPC = eucrosiaupc; Euphemia = euphemia; FangSong = fangsong; Franklin Gothic Medium = franklin gothic medium; FrankRuehl = frankruehl; FreesiaUPC = freesiaupc; Gabriola = gabriola; Gadugi = gadugi; Gautami = gautami; Georgia = georgia; Gisha = gisha; Gulim = gulim; GulimChe = gulimche; Gungsuh = gungsuh; GungsuhChe = gungsuhche; Impact = impact; IrisUPC = irisupc; Iskoola Pota = iskoola pota; JasmineUPC = jasmineupc; KaiTi = kaiti; Kalinga = kalinga; Kartika = kartika; Khmer UI = khmer ui; KodchiangUPC = kodchiangupc; Kokila = kokila; Lao UI = lao ui; Latha = latha; Leelawadee = leelawadee; Levenim MT = levenim mt; LilyUPC = lilyupc; Lucida Console = lucida console; Lucida Handwriting Italic = lucida handwriting italic; Lucida Sans Unicode = lucida sans unicode; Malgun Gothic = malgun gothic; Mangal = mangal; Manny ITC = manny itc; Marlett = marlett; Meiryo = meiryo; Meiryo UI = meiryo ui; Microsoft Himalaya = microsoft himalaya; Microsoft JhengHei = microsoft jhenghei; Microsoft JhengHei UI = microsoft jhenghei ui; Microsoft New Tai Lue = microsoft new tai lue; Microsoft PhagsPa = microsoft phagspa; Microsoft Sans Serif = microsoft sans serif; Microsoft Tai Le = microsoft tai le; Microsoft Uighur = microsoft uighur; Microsoft YaHei = microsoft yahei; Microsoft YaHei UI = microsoft yahei ui; Microsoft Yi Baiti = microsoft yi baiti; MingLiU_HKSCS = mingliu_hkscs; MingLiU_HKSCS-ExtB = mingliu_hkscs-extb; Miriam = miriam; Mongolian Baiti = mongolian baiti; MoolBoran = moolboran; MS UI Gothic = ms ui gothic; MV Boli = mv boli; Myanmar Text = myanmar text; Narkisim = narkisim; Nirmala UI = nirmala ui; News Gothic MT = news gothic mt; NSimSun = nsimsun; Nyala = nyala; Palatino Linotype = palatino linotype; Plantagenet Cherokee = plantagenet cherokee; Raavi = raavi; Rod = rod; Sakkal Majalla = sakkal majalla; Segoe Print = segoe print; Segoe Script = segoe script; Segoe UI Symbol = segoe ui symbol; Shonar Bangla = shonar bangla; Shruti = shruti; SimHei = simhei; SimKai = simkai; Simplified Arabic = simplified arabic; SimSun = simsun; SimSun-ExtB = simsun-extb; Sylfaen = sylfaen; Tahoma = tahoma; Times New Roman = times new roman; Traditional Arabic = traditional arabic; Trebuchet MS = trebuchet ms; Tunga = tunga; Utsaah = utsaah; Vani = vani; Vijaya = vijaya'
                                ),
                            );
                            wp_editor($content,$elem,$args);  ?>
                            <button class="RW_Tabs_Upd_Content">Update content</button>
                        </div>         
                    </div>
                </section>
                <!-- Section for Tabs Edit Desc End -->
            </main>
        </div>
        <div id="RW-Tabs-Panel-Footer">
            <a href="<?php echo esc_url( sprintf( '?page=%s', esc_attr('RW_Tabs_Menu')));?>" class="RWTabs_Back_toDashboard"> Back to <i class="rich_web rich_web-wordpress"></i></a>
            <div id="RW_Tabs_Update_Button" data-rw-content="<?php echo $RW_Tabs_Build_ID === false ? esc_attr('Save') :esc_attr('Update');  ?>"><?php echo $RW_Tabs_Build_ID === false ? esc_html('Save') :esc_html('Update');  ?></div>
        </div>
    </div>

    <div id="RW-Tabs-Preview-Panel">
        <div id="RW_Tabs_Preview-Panel-Nav">
            <div id="RW_Tabs_Preview-Shortcode-Panel" style="display:none;">
                <div id="RW_Tabs_ShortcodePanel-Inner">
                        <div class="rw_tabs_control_shortcode">
                                <p class="rw-control-panel-title" style="text-align: center;">Copy & paste the shortcode directly into any WordPress post or page.</p>
                                <div class="rw_tabs_input-container rw_tabs_input-cont_shortcode">
                                    <input type="text" id="RW_Tabs_Shortcode_id" disabled="">
                                    <span class="rich_web rich_web-files-o rw_tabs_shortcode_global" data-title="Copy" data-copy="shortcode"></span>
                                </div>
                        </div>
                        <div class="rw_tabs_control_shortcode">
                                <p class="rw-control-panel-title" style="text-align: center;">Copy & paste this code into a template file to include the tabs within your theme.</p>
                                <div class="rw_tabs_input-container rw_tabs_input-cont_shortcode">
                                    <input type="text" id="RW_Tabs_Shortcode_ID_Page" disabled="">
                                    <span class="rich_web rich_web-files-o rw_tabs_shortcode_global" data-title="Copy" data-copy="code"></span>
                                </div>
                        </div>
                        <div class="rw_tabs_control_shortcode">
                        <?php echo $RW_Tabs_Build_Type == "tab" ? ' <p class="rw-control-panel-title" data-rw-option="tab">Tab name</p>' :
                                                                  '<p class="rw-control-panel-title" data-rw-option="accordion">Accordion name</p>'  ; ?>
                                <div class="rw_tabs_input-container rw_tabs_input-cont_shortcode">
                                    <input type="text" placeholder="Tabs Subtitle" id="RW_Tabs_Global_Name" value="<?php echo $RW_Tabs_NewOld=="edit" ? esc_attr($RW_Tabs_Build_Check->Tabs_Name) : "New ".ucfirst(esc_js($RW_Tabs_Build_Type));  ?>">
                                    <span class="rich_web rich_web-refresh rw_tabs_restore_global" data-title="Restore"></span>
                                </div>
                        </div>
                </div>
            </div>
            <div id="RW_Tabs_Preview-Panel-Switch" aria-hidden="true">
                <span>Tabs Option</span> 
            </div>
        </div>

        <div id="RW_Tabs_Responsive_Iframe">
            <div id="RW_Tabs_Iframe_Container">
                <div id="RW_Tabs_Preview_Iframe">
                    <?php
                    
                        include "Tabs-Rich-Web-Preview.php";  
                    ?>
                </div>
                <!-- <iframe id="RW_Tabs_Preview_Iframe" src="" allowfullscreen="1"></iframe> -->
            </div>
        </div>
    </div>
</section>

<script>
    var RW_Tabs_New_Acd_Tab,RW_Tabs_Global_Name,RW_Tabs_Global_Rest,RW_Tabs_Global_ID,RW_Tabs_Global_Create,RW_Tabs_Global_Update,RW_Tabs_Edit_Action_Number,RW_Tabs_Preview_Style,RW_Tabs_Iframe_Root,document,RW_Tabs_Content_Container,RW_Tabs_Menu,RW_Tabs_Data_Responsive,RW_Tabs_Resp_MinWidth,RW_Tabs_Resp_MaxWidth,RW_Tabs_Resp_MinHeight,RW_Tabs_Panel_Switch_Width,RW_Tabs_Edited_T_Count,RW_Tabs_Iframe_Content_Window,RWTabs_Theme_Id,RWTabs_Theme_TNS,RW_Tabs_Acd_Tab,RWTabs_MyAttachment;
    var RW_Tabs_Panel_Root = document.querySelector(':root');
    var RW_Tabs_New_Tab_Save = 'false';
    var RW_Tabs_generateRandomNumber = (min, max) =>  {  return Math.floor(Math.random() * (max - min) + min); };
    var RW_Tabs_ColorPicker_Arr = new Object();
    RW_Tabs_Global_ID = <?php echo $RW_Tabs_Build_ID === false ? esc_js($RW_Tabs_Shortcode): esc_js($RW_Tabs_Build_ID); ?>;
    RWTabs_Theme_Id = '<?php echo esc_js( $RW_Tabs_Builder_Theme_ID); ?>';
    RWTabs_Theme_TNS = '<?php echo esc_js( $RW_Tabs_Builder_Theme_TNS); ?>';
    // window.onbeforeunload = () => '';
    // <!-- Admin notice -->
    jQuery(window).on('resize',function () {
        if (jQuery(window).outerWidth() <= 1024) {
            jQuery('#RW-Tabs-Notice-Section').css('display','');
        }else{
            jQuery('#RW-Tabs-Notice-Section').css('display','none');
        }
    });

    RW_Tabs_Iframe_Root =  document.querySelector(':root');
    RW_Tabs_New_Acd_Tab = "<?php echo ucfirst(esc_js($RW_Tabs_Build_Type)); ?>";
    <?php if ($RW_Tabs_Build_Type == "tab") { ?>
        RW_Tabs_Div_MenuCont = document.querySelector('#RW_Tabs_T_H_'+RW_Tabs_Global_ID);
        RW_Tabs_Menu =  document.querySelector('#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID);
        RW_Tabs_Content_Container =  document.querySelector('.Rich_Web_Tabs_tt_container'+RW_Tabs_Global_ID);
    <?php } else { ?>
        RW_Tabs_Div_MenuCont = RW_Tabs_Menu = document.querySelector('#RW_Tabs_Accordion_'+RW_Tabs_Global_ID);
    <?php } ?>
    RW_Tabs_Preview_Style = RW_Tabs_Iframe_Root.style;
    // RW_Tabs_Iframe_Content_Window = document.getElementById("RW_Tabs_Preview_Iframe").contentWindow;
    RW_Tabs_Preview_Style.setProperty('--rw-tabs-body-overflow','hidden');
    jQuery('section#RW-Tabs-Content').css('display','flex');  
    jQuery('section#RW-Tabs-Loader').css('display','none');
    jQuery('#RW_Tabs_Shortcode_id').val(`[Rich_Web_Tabs id="${RW_Tabs_Global_ID}"]`)
    var leftTr = '<'; var rightTr = '>';
    jQuery('#RW_Tabs_Shortcode_ID_Page').val(`${leftTr}?php echo do_shortcode('[Rich_Web_Tabs id="${RW_Tabs_Global_ID}"]');?${rightTr}`);
    RW_Tabs_Global_Rest = RW_Tabs_Global_Name =  jQuery('#RW_Tabs_Global_Name').val();
    if (RW_Tabs_New_Acd_Tab == "Tab") {
        jQuery('input[name="RW_Tabs_M_Align"]').on('click',function () {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_pos',jQuery(this).val());
        });
        jQuery('input[name="RW_Tabs_V_M_Align"]').on('click',function () {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_v_menu_pos',jQuery(this).val());
        });
        jQuery('input[name="RW_Tabs_M_Flex-Wrap"]').on('click',function () {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_wrap',jQuery(this).val());
        });
        jQuery('select#RWTabs_C_Anim').on('change',function () {
            RW_Tabs_Refresh_Anim(jQuery(this).val());
        });
        jQuery('input#RW_Tabs_M_Height,input.RW_Tabs_M_Height').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item_height',jQuery(this).val() + 'px');
        });
        jQuery('input#RW_Tabs_V_M_Width,input.RW_Tabs_V_M_Width').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_vertical_width',jQuery(this).val() + 'px');
        });
        jQuery('input#RW_Tabs_Item_IS,input.RW_Tabs_Item_IS').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_text_icon_size',jQuery(this).val() + 'px');
        });
        jQuery('input[name="RW_Tabs_T_Type"]').on('click',function () {
            jQuery(RW_Tabs_Div_MenuCont).attr('data-rw-desctop',jQuery(this).val());
            if (jQuery(this).val() == 'horizontal') {
                jQuery('*[data-rw-type="vertical"]').css('display','none');
                jQuery('*[data-rw-type="horizontal"]').css('display','');
                jQuery('#RW_Tabs_M_Height').trigger('change');
                jQuery('#RW_Tabs_M_Height').trigger('input');
                jQuery('input[name="RW_Tabs_M_Align"][value="flex-start"]').trigger('click');
                jQuery('input[name="RW_Tabs_M_Flex-Wrap"][value="nowrap"]').trigger('click');
                jQuery('#RW_Tabs_M_Gap').trigger('change');
            }else{
                jQuery('*[name="RW_Tabs_V_M_Align"]').attr('checked',false);
                jQuery('*[data-rw-type="horizontal"]').css('display','none');
                jQuery('*[data-rw-type="vertical"]').css('display','');
                jQuery('#RW_Tabs_V_M_Width').trigger('change');
                jQuery('#RW_Tabs_V_M_Width').trigger('input');
                jQuery('input[name="RW_Tabs_V_M_Align"][value="row"]').trigger('click');
            }
        });
        if (jQuery('input[name="RW_Tabs_T_Type"]:checked').val() == 'horizontal') {
            jQuery('*[data-rw-type="vertical"]').css('display','none');
        }else{
            jQuery('*[data-rw-type="horizontal"]').css('display','none');
        }

        
    }else{
        jQuery('input#RW_Tabs_RightIcon_Size,input.RW_Tabs_RightIcon_Size').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-ri_s',jQuery(this).val() + 'px');
        });
        jQuery('input#RW_Tabs_LeftIcon_Size,input.RW_Tabs_LeftIcon_Size').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-li_s',jQuery(this).val() + 'px');
        });
        jQuery('input#RW_Tabs_Item_Border_R,input.RW_Tabs_Item_Border_R').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-br',jQuery(this).val() + 'px');
        });
        jQuery('input#RW_Tabs_Item_Border_W,input.RW_Tabs_Item_Border_W').on('change',function() {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-bw',jQuery(this).val() + 'px');
        });
        jQuery('select#RW_Tabs_Menu_BS').on('change',function () {
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-bs',jQuery(this).val());
        });
        jQuery('select#RW_Tabs_Menu_BoxShadow').on('change',function () {
            jQuery(RW_Tabs_Menu).attr('data-rw-box',jQuery(this).val());
        });
        jQuery('select#RW_Tabs_Menu_Bgc_Style').on('change',function () {
            jQuery(RW_Tabs_Menu).attr('data-rw-bgc',jQuery(this).val());
        });
        jQuery('select#RW_Tabs_Item_Text_Style').on('change',function () {
            jQuery(RW_Tabs_Menu).attr('data-rw-text',jQuery(this).val());
        });
        jQuery('select#RW_Tabs_RightIcon_Style').on('change',function () {
            RW_Tabs_Return_Right_Icon(jQuery(this).val());
            RW_Tabs_Refresh_Acd();
        });
        jQuery('select#RWTabs_Cont_Anim').on('change',function () {
            RW_Tabs_Change_Anim(jQuery(this).val());
        });
    }
    <?php if ($RW_Tabs_Build_Type == "tab") { 
        foreach ($RW_Tabs_ColorPicker_Arr as $key => $value) : ?>
            const pickr<?php echo esc_js($key);?> = Pickr.create({
                el: '#<?php echo esc_js($key);?>',
                container:'#<?php echo esc_js($key);?>-Cont',
                default:jQuery('#<?php echo esc_js($key);?>').val(),
                theme: 'monolith',
                autoReposition: true,
                comparison: false,
                components: { opacity: true,hue: true,interaction: { input: true, } }
            });
            pickr<?php echo esc_js($key);?>.on('change', (color, source, instance) => {
            var myColor = color.toHEXA().toString();
            pickr<?php echo esc_js($key);?>.setColor(myColor,false);
            jQuery('#<?php echo esc_js($key);?>-Out').val(myColor);
                RW_Tabs_Preview_Style.setProperty('<?php echo  esc_js($value); ?>',myColor);
            });
         <?php endforeach; 
    }else{
         foreach ($RW_Tabs_ColorPicker_Arr_Acd as $key => $value) : ?>
            const pickr<?php echo esc_js($key);?> = Pickr.create({
                el: '#<?php echo esc_js($key);?>',
                container:'#<?php echo esc_js($key);?>-Cont',
                default:jQuery('#<?php echo esc_js($key);?>').val(),
                theme: 'monolith',
                autoReposition: true,
                comparison: false,
                components: { opacity: true,hue: true,interaction: { input: true, } }
            });
            pickr<?php echo esc_js($key);?>.on('change', (color, source, instance) => {
            var myColor = color.toHEXA().toString();
            pickr<?php echo esc_js($key);?>.setColor(myColor,false);
            jQuery('#<?php echo esc_js($key);?>-Out').val(myColor);
                RW_Tabs_Preview_Style.setProperty('<?php echo  $value; ?>',myColor);
            });
        <?php endforeach; 
    } ?>
    //Edit Action 
    jQuery(document).on("click", ".RW_Tabs_Prev_Act_Edit" , function() {
        var Edited_Id = jQuery(this).parent().attr('data-elem');
        RW_Tabs_Edit_Action_Number = Edited_Id;
        if (RW_Tabs_New_Acd_Tab == "Tab") {
             if (jQuery('#rw_tabs_spec_c-'+RW_Tabs_Edit_Action_Number).attr('data-switch') == 'on') {
                 rw_tabs_change_col(RW_Tabs_Edit_Action_Number);
             }
             jQuery('#RW_Tabs_Special_Colors').attr('onclick','rw_tabs_change_col('+RW_Tabs_Edit_Action_Number+')');
        }
        jQuery('#RW-Tabs-Panel-Header > .RW_Tabs_Head_Name,#RW-Tabs-Panel-Header > .RW_Tabs_Head_Back > span').css('display','block');
        jQuery('#RW_Tabs_Subtitle_Sect').val(jQuery('#rw_tabs_field-'+RW_Tabs_Edit_Action_Number).children('span').html().trim());
        jQuery('#Rich_Web_Tabs_SubTIcon').val('rich_web-'+jQuery('#rw_tabs_field-'+RW_Tabs_Edit_Action_Number).children('span').attr('data-subicon'));
        jQuery('span.selector-arrow-right').trigger('click'); jQuery('span.selector-arrow-left').trigger('click');
        jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-for="All"],#RW-Tabs-Panel-Header > .RW_Tabs_Head_Img').css('display','none');
        jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-for="Edit"]').css('display','flex');
        RW_Tabs_Panel_Root.style.setProperty('--rw_tabs_panel_grid_sys','6% 94%');
        jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-section="Tabs_Menu"]').trigger('click');
        if (RW_Tabs_New_Acd_Tab == "Tab") {
            var EditedItem = jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+RW_Tabs_Edit_Action_Number+'"]')).attr('data-id');
            RW_Tabs_Tmce_setContent(jQuery(document.querySelector('.Rich_Web_Tabs_tt_tab'+RW_Tabs_Global_ID+'[data-parent="'+EditedItem+'"]')).html(),'RW_Tabs_Tab_Content_Area' );
        }else{
            RW_Tabs_Tmce_setContent(jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > div > div')).html(),'RW_Tabs_Tab_Content_Area' );
        }
        jQuery('button.RW_Tabs_Upd_Content').on('click',function () {
            jQuery('#rw_tabs_desc_'+RW_Tabs_Edit_Action_Number).html(RW_Tabs_Tmce_getContent('RW_Tabs_Tab_Content_Area'));
            if (RW_Tabs_New_Acd_Tab == "Tab") {
                jQuery(document.querySelector('.Rich_Web_Tabs_tt_tab'+RW_Tabs_Global_ID+'[data-parent="'+EditedItem+'"]')).html(RW_Tabs_Tmce_getContent('RW_Tabs_Tab_Content_Area'));
                jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+RW_Tabs_Edit_Action_Number+'"]')).trigger('click');
            }else{
                jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > div > div')).html(RW_Tabs_Tmce_getContent('RW_Tabs_Tab_Content_Area'));
                if (!jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > h3')).hasClass('active') ) {
                     jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > h3')).trigger('click');
                }
            }
        })
        //Icon Picker
        jQuery('#Rich_Web_Tabs_SubTIcon').fontIconPicker({
            autoClose: true, emptyIcon: true, emptyIconValue: 'none', theme: 'fip-grey', iconsPerPage: 20, hasSearch: true, appendTo: jQuery('#RW_Tabs_Icon_Picker-Cont')
            }).on( 'change', function() {
            var setIcon = jQuery( this ).val();
            setIcon =  null == setIcon ? 'none' : setIcon.replace("rich_web-", "");
            if (RW_Tabs_New_Acd_Tab == "Tab") {
                jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > .RW_Tabs_Menu_Title > i')).attr('class', '').attr('class','rich_web rich_web-'+setIcon);
            }else{
                jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > h3 > i')).attr('class', '').attr('class','rich_web rich_web-'+setIcon);
            }
            jQuery('#rw_tabs_field-'+RW_Tabs_Edit_Action_Number).children('span').attr('data-subicon',setIcon);
        });
         //End Icon
        //Restore Tab Subtitle value
        jQuery('.rw_tabs_restore_icon').on('click',function () {
            jQuery('#RW_Tabs_Subtitle_Sect').val(jQuery('#rw_tabs_field-'+RW_Tabs_Edit_Action_Number).children('span').attr('data-subtitle'));
            jQuery('#RW_Tabs_Subtitle_Sect').trigger('input');
        });
        //On Change Subtitle
        jQuery('#RW_Tabs_Subtitle_Sect').on('input',function () {
            jQuery('#rw_tabs_field-'+RW_Tabs_Edit_Action_Number).children('span').html(jQuery(this).val());
            if (RW_Tabs_New_Acd_Tab == "Tab") {
                jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > .RW_Tabs_Menu_Title > i > span')).text(jQuery(this).val());
            }else{
                jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+RW_Tabs_Edit_Action_Number+'"] > h3 > .rw_tabs_act_st_title')).text(jQuery(this).val());
            }
        })
        //Back Button Code
        jQuery('.RW_Tabs_Head_Back').on('click',function () {
            if (jQuery('.RW-Tabs-Panel-Content_Nav-Bar').hasClass('active')) {
                if (RW_Tabs_New_Acd_Tab == "Tab") {
                    jQuery('#RW_Tabs_Subtitle_Sect,input#myprefix_image_id').val('');
                    jQuery('#rw_tabs_control_special_col,#rw_tabs_control_special_bgc,#RW_Tabs_Icon_Picker-Cont').html('');
                    document.getElementById('rw_tabs_control_special_col').style.height = null;
                    jQuery('#RW_Tabs_Special_Colors').removeClass('RichWeb_Switch_But');
                }else{
                    jQuery('#RW_Tabs_Icon_Picker-Cont').html(''); 
                }
                jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-for="All"]').css('display','flex');
                jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-for="Edit"],#RW-Tabs-Panel-Header > .RW_Tabs_Head_Name,#RW-Tabs-Panel-Header > .RW_Tabs_Head_Back > span').css('display','none');
                RW_Tabs_Panel_Root.style.setProperty('--rw_tabs_panel_grid_sys','10% 90%');
                jQuery('.RW-Tabs-Panel-Content_Nav-Bar[data-section="Tabs"]').trigger('click');
                jQuery('#RW-Tabs-Panel-Header > .RW_Tabs_Head_Img').css('display','block');
                Edited_Id = EditedItem   = RW_Tabs_Edit_Action_Number = '';
            }
        })
    });
    //Edit Action End
    jQuery('#RW_Tabs_Global_Name').on('input',function () { RW_Tabs_Global_Name = jQuery(this).val(); })
    jQuery('.rw_tabs_restore_global').on('click',function () { jQuery('#RW_Tabs_Global_Name').val(RW_Tabs_Global_Rest).trigger('input'); });
    //Add Item Start
    jQuery(document).on("click", ".rw_tabs_add-item" , function() {
            RW_Tabs_Edited_T_Count = +RW_Tabs_Edited_T_Count + 1;
            var rand_sort_number= RW_Tabs_generateRandomNumber(100000, 999999);
            if (RW_Tabs_New_Acd_Tab == "Tab") {
                var rand_tab_number= RW_Tabs_generateRandomNumber(10000, 99999);
                jQuery('.rw_tabs_control-panel-flex').append('<div class="rw_tabs_fields  ui-sortable-handle" data-sort="'+`${rand_sort_number}`+'" id="rw_tabs_field-'+rand_sort_number+'"><span data-subicon="none" data-subtitle="New Tab">New Tab</span><div class="rw_tabs_actions" data-elem="'+`${rand_sort_number}`+'"><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Edit" data-title="Edit"><i class="rich_web rich_web-pencil"></i></div><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Copy"  data-title="Copy"><i class="rich_web rich_web-files-o"></i></div><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Delete"  data-title="Delete"><i class="rich_web rich_web-trash"></i></div></div><textarea  style="display:none;" id="rw_tabs_desc_'+`${rand_sort_number}`+'"><p>New Tab SubContent</p></textarea><input type="text" style="display:none;"  class="rw_tabs_spec_c" id="rw_tabs_spec_c-'+rand_sort_number+'" data-switch="off" data-col="" data-hover="" data-active=""></div>'); 
                jQuery(RW_Tabs_Menu).append('<li class="RW_Tabs_T_Item-H RW_Tabs_Non_Img_Opt" data-id="'+rand_tab_number+'" data-sort="'+rand_sort_number+'"><span class="rich_web_tab_li_span RW_Tabs_Menu_Title"><i class="rich_web rich_web"><span>New Tab</span></i></span></li>');
                jQuery(RW_Tabs_Content_Container).append(`<div data-style="${jQuery(RW_Tabs_Content_Container).attr('data-style')}" data-parent="${rand_tab_number}" class="Rich_Web_Tabs_tt_tab Rich_Web_Tabs_tt_tab${RW_Tabs_Global_ID}"><p>New Tab SubContent</p></div>`);
                RW_Tabs_Add_StyleSheet(RW_Tabs_Global_ID,rand_tab_number,rand_sort_number);	
            } else {
                jQuery(RW_Tabs_Menu).append(`<div class="Rich_Web_Tabs_Accordion_Content_${RW_Tabs_Global_ID}" data-sort="${rand_sort_number}"><h3 class="Rich_Web_Tabs_h3_${RW_Tabs_Global_ID}"><i id="rich-web-acd-icon${RW_Tabs_Global_ID}" class="rich_web"></i><div class="rw_tabs_act_st_div_l${RW_Tabs_Global_ID}"><span class="rw_tabs_act_st_l${RW_Tabs_Global_ID}"></span></div><span class="rw_tabs_act_st_r${RW_Tabs_Global_ID}"></span><span class="rw_tabs_act_st_title">New Accordion</span><div class="arrowDown${RW_Tabs_Global_ID}"></div><div class="collapseIcon${RW_Tabs_Global_ID} ic_${RW_Tabs_Global_ID}"><i class="rich_web rich_web-plus"></i></div></h3><div class="rw_tabs_acd_cont${RW_Tabs_Global_ID}" style="display: none;"><div id="rw_tabs_acd_cont${RW_Tabs_Global_ID}" style="">New Accordion Content</div></div></div>`);
                jQuery('.rw_tabs_control-panel-flex').append(`<div class="rw_tabs_fields  ui-sortable-handle" data-sort="${rand_sort_number}" id="rw_tabs_field-${rand_sort_number}"><span data-subicon="none" data-subtitle="New Accordion">New Accordion</span><div class="rw_tabs_actions" data-elem="${rand_sort_number}"><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Edit" data-title="Edit"><i class="rich_web rich_web-pencil"></i></div><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Copy"  data-title="Copy"><i class="rich_web rich_web-files-o"></i></div><div class="rw_tabs_field-action RW_Tabs_Prev_Act_Delete"  data-title="Delete"><i class="rich_web rich_web-trash"></i></div></div><textarea  style="display:none;" id="rw_tabs_desc_${rand_sort_number}"><p>New Accordion Content</p></textarea></div>`); 
                RW_Tabs_Refresh_Acd();
            }
            RW_Tabs_Message(`<i class="rich_web rich_web-check"></i> <b>Success:</b> New Tab added`,'success');

    });
    //Add Item End
    jQuery(document).on("click", ".RW_Tabs_Prev_Act_Copy" , function() {
            RW_Tabs_Edited_T_Count = +RW_Tabs_Edited_T_Count + 1;
            var ClonedId = jQuery(this).parent().attr('data-elem');
            RW_Tabs_Prev_Act_Copy(ClonedId);
    });
    jQuery('select#RW_Tabs_C_Type').on('change',function () {
      var  rw_tabs_content_bgc1 = getComputedStyle(RW_Tabs_Iframe_Root).getPropertyValue('--rw_tabs_content_bgc');
      var  rw_tabs_content_bgc2 = getComputedStyle(RW_Tabs_Iframe_Root).getPropertyValue('--rw_tabs_content_bgc2');
        if (RW_Tabs_New_Acd_Tab == "Tab") {
            var Rich_Web_Tabs_tt_tab =  document.querySelector('.Rich_Web_Tabs_tt_tab'+RW_Tabs_Global_ID);
            jQuery(RW_Tabs_Content_Container).attr('data-style',jQuery(this).val());
            jQuery(Rich_Web_Tabs_tt_tab).attr('data-style',jQuery(this).val());
        }else{ 
            jQuery(RW_Tabs_Div_MenuCont).attr('data-style',jQuery(this).val());
        }
      switch (jQuery(this).val()) {
    		case "color":
    			    jQuery('.rw_tabs_control_desc_cf').css('display','flex');
    				jQuery('.rw_tabs_control_desc_cs').css('display','none');
    				break;
    		case "transparent":
    				jQuery('.rw_tabs_control_desc_cs,.rw_tabs_control_desc_cf').css('display','none');
    			break;
    		case "gradient":
                jQuery('.rw_tabs_control_desc_cs,.rw_tabs_control_desc_cf').css('display','flex');
    			break;
            case "gradient-top":
                jQuery('.rw_tabs_control_desc_cs,.rw_tabs_control_desc_cf').css('display','flex');
    			break;    
      }
    });
    jQuery('select#RW_Tabs_C_Type').trigger('change');
    jQuery('input[name="RW_Tabs_T_Align"]').on('click',function () {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_section_align',jQuery(this).val());
    });  
    jQuery(document).on("click", ".RW_Tabs_Prev_Act_Delete" , function() {
            RW_Tabs_Edited_T_Count = +RW_Tabs_Edited_T_Count - 1;
            var DeletedID = jQuery(this).parent().attr('data-elem');
            var DeletedName = jQuery('#rw_tabs_field-'+DeletedID).children('span').text();
            jQuery('.rw_tabs_fields[data-sort="'+DeletedID+'"]').remove();
            if (RW_Tabs_New_Acd_Tab == "Tab") {
                var DelElem =  jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+DeletedID+'"]'));
                jQuery(document.querySelector('div.Rich_Web_Tabs_tt_tab'+RW_Tabs_Global_ID+'[data-parent="'+jQuery(DelElem).attr("data-id")+'"]')).remove();
                jQuery(DelElem).remove();
                if (RW_Tabs_Edited_T_Count != 0 && jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H.active')).length == 0 ) {
                    jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H')).first().trigger('click');
                }
            }else{
                var DelElem =  jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+DeletedID+'"]'));
                jQuery(DelElem).remove();
                if (RW_Tabs_Edited_T_Count != 0 && jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+' > .Rich_Web_Tabs_h3_'+RW_Tabs_Global_ID+'.active')).length == 0 ) {
                    jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+' > .Rich_Web_Tabs_h3_'+RW_Tabs_Global_ID)).first().trigger('click');
                }
            }
            RW_Tabs_Message(`<i class="rich_web rich_web-times"></i>  The <b> ${DeletedName} </b> deleted`,'error');
    });
    jQuery('input#RW_Tabs_Width,input.RW_Tabs_Width').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_div_width',jQuery(this).val() + '%');
    });
    jQuery('input#RW_Tabs_M_Gap,input.RW_Tabs_M_Gap').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_gap',jQuery(this).val() + 'px');
    });
    jQuery('input#RW_Tabs_M_C_Gap,input.RW_Tabs_M_C_Gap').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_content_gap',jQuery(this).val() + 'px');
    });
    jQuery('input#RW_Tabs_Item_FS,input.RW_Tabs_Item_FS').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_text_font_size',jQuery(this).val() + 'px');
    });
    jQuery('select#RW_Tabs_Item_FF').on('change',function () {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_text_font_family',jQuery(this).val());
    });
    jQuery('input#RW_Tabs_C_BW,input.RW_Tabs_C_BW').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_content_bw',jQuery(this).val() + 'px');
    });
    jQuery('input#RW_Tabs_C_BR,input.RW_Tabs_C_BR').on('change',function() {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_content_br',jQuery(this).val() + 'px');
    });
    jQuery('.rw_tabs_control-panel-flex').on('mousemove',function () {
        jQuery('.rw_tabs_control-panel-flex').sortable({
            cursor: 'move',
    		update: function( event, ui ){ 
                if (RW_Tabs_New_Acd_Tab == "Tab") {
                    if (document.querySelector('.RW_Tabs_T_Item-H[data-sort="'+jQuery(ui.item).prev().attr('data-sort')+'"]') == null) {
                    jQuery(document.querySelector('.RW_Tabs_T_Item-H[data-sort="'+jQuery(ui.item).attr('data-sort')+'"]')).insertBefore(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H:first-child'))
                    }else{
                        jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+jQuery(ui.item).attr('data-sort')+'"]')).insertAfter(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+jQuery(ui.item).prev().attr('data-sort')+'"]'))
                    } 
                }else{
                    if (document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+jQuery(ui.item).prev().attr('data-sort')+'"]') == null) {
                        jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+jQuery(ui.item).attr('data-sort')+'"]')).insertBefore(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+':first-child'))
                    }else{
                        jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+jQuery(ui.item).attr('data-sort')+'"]')).insertAfter(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+jQuery(ui.item).prev().attr('data-sort')+'"]'))
                    } 
                }
    		},
    	})
    });


//ShortcodeRW
jQuery(document).on("click", ".rw_tabs_shortcode_global" , function() {
    var RW_Tabs_Create_Elem = document.createElement("input");
    var RW_Tabs_Short_ID = jQuery(this).attr('data-copy') == 'code' ?  jQuery('#RW_Tabs_Shortcode_ID_Page').val() : jQuery('#RW_Tabs_Shortcode_id').val(); 
    var RW_Tabs_Msg = jQuery(this).attr('data-copy') == 'code' ? `<i class="rich_web rich_web-check"></i> <b>Success:</b> Shortcode Code Copied` : `<i class="rich_web rich_web-check"></i> <b>Success:</b> Shortcode Copied`; 
    RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&lt;", "<");
    RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&gt;", ">");
    RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&#039;", "'");
    RW_Tabs_Short_ID = RW_Tabs_Short_ID.replace("&#039;", "'");
    RW_Tabs_Create_Elem.setAttribute("value", RW_Tabs_Short_ID);
    document.body.appendChild(RW_Tabs_Create_Elem);
    RW_Tabs_Create_Elem.select();
    document.execCommand("copy");
    document.body.removeChild(RW_Tabs_Create_Elem);
    RW_Tabs_Message(RW_Tabs_Msg,'info',true);
});
//ShortcodeRW End


//Tabs_Panel_Nav_Actions
jQuery('.RW-Tabs-Panel-Content_Nav-Bar').on('click',function() {
        jQuery('.RW-Tabs-Panel-Content_Nav-Bar').removeClass('active');
        jQuery('#RW-Tabs-Nav-Content section').css('display','none');
        jQuery('section#RW-Tabs-Nav-Content_'+jQuery(this).attr('data-section')).css('display','inline-grid');
        jQuery(this).addClass('active');
});


//Tabs Parametr SWitcher
jQuery('.rw-tabs-acc-item').on('click',function() {
    jQuery(this).toggleClass('rw-tabs-active-item');
    var panel = this.nextElementSibling;
    if (panel.style.height) {
      panel.style.height = null;
    }else {
      panel.style.height = 'auto';
    } 
});



//Admin_Panel_Resizer
jQuery(function() {
    jQuery('#RW-Tabs-Panel').resizable({
        handles: 'e',
        resize: function( event, ui ) {
            jQuery('#RW-Tabs-Preview-Panel').addClass('ui-resizable-resizing');
            var  v  = document.getElementById('RW-Tabs-Panel').clientWidth;
            var w = Math.max(document.documentElement.clientWidth, window.innerWidth || 0);
            var vw = (v * 100) / w;
            RW_Tabs_Panel_Root.style.setProperty('--rw-tabs-panel-width', vw+'vw');
            jQuery(this).css('width',vw+'vw' );
            RW_Tabs_Panel_Root.style.setProperty('--rw-tabs-preview-panel-width',100 - vw+'vw');
        },
        stop: function() {
            jQuery('#RW-Tabs-Preview-Panel').removeClass('ui-resizable-resizing');
        }
    });
});

jQuery(document).on("click", "#RW_Tabs_Preview-Panel-Switch" , function() {
    if (jQuery(this).attr('aria-hidden') == 'true') {
        jQuery('#RW_Tabs_Preview-Shortcode-Panel').css('visibility','visible').slideDown();
        jQuery(this).attr('aria-hidden','false')
    }else{
        jQuery('#RW_Tabs_Preview-Shortcode-Panel').slideUp(400);
        setTimeout(() => {
            jQuery('#RW_Tabs_Preview-Shortcode-Panel').css('visibility','hidden');
        }, 500);
        jQuery(this).attr('aria-hidden','true')
    }
});

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


function RW_Tabs_Toggle_Panel() {
    if (jQuery('#RW-Tabs-Panel').hasClass('RW-Tabs-Panel-Hidden')) {
        jQuery('#RW-Tabs-Panel').removeClass('RW-Tabs-Panel-Hidden');
        RW_Tabs_Panel_Switch_Width = getComputedStyle(document.documentElement).getPropertyValue('--rw-tabs-panel-width');
        RW_Tabs_Panel_Root.style.setProperty('--rw-tabs-preview-panel-width',100 - + parseInt(RW_Tabs_Panel_Switch_Width) + 'vw');
        jQuery('#RW-Tabs-Panel-switcher').attr('title','Hide Panel').html('<i class="rich_web rich_web-chevron-left" title="Hide Panel"></i>');
    }else{
        RW_Tabs_Panel_Root.style.setProperty('--rw-tabs-preview-panel-width','100vw');
        jQuery('#RW-Tabs-Panel').addClass('RW-Tabs-Panel-Hidden');
        jQuery('#RW-Tabs-Panel-switcher').attr('title','Show Panel').html('<i class="rich_web rich_web-chevron-right" title="Show Panel"></i>');
    }
}

//Tmce editor content set/get
function RW_Tabs_Tmce_getContent(editor_id, textarea_id) {
    if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
    if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
      return tinyMCE.get(editor_id).getContent();
    }else{
      return jQuery('#'+textarea_id).val();
    }
}

function RW_Tabs_Tmce_setContent(content, editor_id, textarea_id) {
    if ( typeof editor_id == 'undefined' ) editor_id = wpActiveEditor;
    if ( typeof textarea_id == 'undefined' ) textarea_id = editor_id;
    if ( jQuery('#wp-'+editor_id+'-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id) ) {
      return tinyMCE.get(editor_id).setContent(content);
    }else{
      return jQuery('#'+textarea_id).val(content);
    }
}

function RW_Tabs_Add_StyleSheet(RW_Tabs_Menu_ID,RW_Tabs_Random_Num,RW_Tabs_Random_Sort_Num) {
    var sheet = document.querySelector("#RW_Tabs_Style").sheet;
    if (jQuery('#rw_tabs_spec_c-'+RW_Tabs_Random_Sort_Num).attr('data-switch') == 'on') {
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-c_'+RW_Tabs_Random_Sort_Num,jQuery('#rw_tabs_spec_c-'+RW_Tabs_Random_Sort_Num).attr('data-col'));
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-h-c_'+RW_Tabs_Random_Sort_Num,jQuery('#rw_tabs_spec_c-'+RW_Tabs_Random_Sort_Num).attr('data-hover'));
        RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-a-c_'+RW_Tabs_Random_Sort_Num,jQuery('#rw_tabs_spec_c-'+RW_Tabs_Random_Sort_Num).attr('data-active'));
    }
        var styles = `@media screen and (min-width: 554px) {
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id='${RW_Tabs_Random_Num}']{
    		background: var(--rw_tabs_menu_item-bgc);
    		color: var(--rw_tabs_menu_item-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-c));
    	} 
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id='${RW_Tabs_Random_Num}']:hover{
    		background: var(--rw_tabs_menu_item-h-bgc);
    		color: var(--rw_tabs_menu_item-h-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-h-c));
    	}
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id='${RW_Tabs_Random_Num}'].active{
    		background: var(--rw_tabs_menu_item-a-bgc);
    		color: var(--rw_tabs_menu_item-a-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-a-c));
    	}
        }`;
        var stylesResp = `@media screen and (max-width: 553px) {
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id="${RW_Tabs_Random_Num}"]{
    		background: var(--rw_tabs_menu_item-bgc);
    		color: var(--rw_tabs_menu_item-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-c));
    	} 
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id="${RW_Tabs_Random_Num}"]:hover{
    		background: var(--rw_tabs_menu_item-h-bgc);
    		color: var(--rw_tabs_menu_item-h-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-h-c));
    	}
    	ul#RW_Tabs_T_Menu-H-${RW_Tabs_Menu_ID} > .RW_Tabs_T_Item-H[data-id="${RW_Tabs_Random_Num}"].active{
    		background: var(--rw_tabs_menu_item-a-bgc);
    		color: var(--rw_tabs_menu_item-a-c_${RW_Tabs_Random_Sort_Num},var(--rw_tabs_menu_item-a-c));
    	}`;
    sheet.insertRule(styles, 0);
    sheet.insertRule(stylesResp, 0);
}

function RW_Tabs_Prev_Act_Copy(ClonedId) {
    var rand_tab_number= RW_Tabs_generateRandomNumber(10000, 99999);
    var rand_sort_number = RW_Tabs_generateRandomNumber(1000, 9999);
    jQuery('.rw_tabs_fields[data-sort="'+ClonedId+'"]').clone(true).attr({'data-sort': rand_sort_number,'id': `rw_tabs_field-${rand_sort_number}`,}).appendTo('.rw_tabs_control-panel-flex');
    var RW_Tabs_New_Copied = jQuery('.rw_tabs_fields[data-sort="'+rand_sort_number+'"]');
    jQuery(RW_Tabs_New_Copied).children('.rw_tabs_actions').attr('data-elem',rand_sort_number);
    jQuery(RW_Tabs_New_Copied).children('span').attr('data-subtitle',jQuery(RW_Tabs_New_Copied).children('span').text());
    jQuery(RW_Tabs_New_Copied).children('textarea').attr('id','rw_tabs_desc_'+rand_sort_number+'').html(jQuery('.rw_tabs_fields[data-sort="'+ClonedId+'"]').children('textarea').html());
    if (RW_Tabs_New_Acd_Tab == "Tab") {
        jQuery(RW_Tabs_New_Copied).children('.rw_tabs_spec_c').attr('id','rw_tabs_spec_c-'+rand_sort_number+'');
        var ClonedItem = jQuery(document.querySelector('ul#RW_Tabs_T_Menu-H-'+RW_Tabs_Global_ID+' > .RW_Tabs_T_Item-H[data-sort="'+ClonedId+'"]'));
        var ClonedItemIframeID = jQuery(ClonedItem).attr('data-id');
        var ClonedItemDesc = jQuery(document.querySelector('.Rich_Web_Tabs_tt_tab'+RW_Tabs_Global_ID+'[data-parent="'+ClonedItemIframeID+'"]')).html();
        jQuery(RW_Tabs_Content_Container).append(`<div data-style="${jQuery(RW_Tabs_Content_Container).attr('data-style')}" data-parent="${rand_tab_number}" class="Rich_Web_Tabs_tt_tab Rich_Web_Tabs_tt_tab${RW_Tabs_Global_ID}">${ClonedItemDesc}</div>`);
        jQuery(ClonedItem).clone(true).attr('data-id',rand_tab_number).attr('data-sort',rand_sort_number).removeClass('active').appendTo(RW_Tabs_Menu);
        RW_Tabs_Add_StyleSheet(RW_Tabs_Global_ID,rand_tab_number,rand_sort_number);
    }else{
        var ClonedItem = jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+ClonedId+'"]'));
        jQuery(ClonedItem).clone(true).attr('data-sort',rand_sort_number).appendTo(RW_Tabs_Menu);
        jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+rand_sort_number+'"]')).children('h3').removeClass('active');
        jQuery(document.querySelector('.Rich_Web_Tabs_Accordion_Content_'+RW_Tabs_Global_ID+'[data-sort="'+rand_sort_number+'"]')).children('div').css('display','none');
        RW_Tabs_Refresh_Acd();
    }
    RW_Tabs_Message(`<i class="rich_web rich_web-check"></i> <b>Success:</b> The ${jQuery(RW_Tabs_New_Copied).children('span').attr('data-subtitle')} copied`,'success');
}

jQuery('#RW_Tabs_T_Mobile').on('change',function () {
    jQuery(RW_Tabs_Menu).attr('data-rw-mobile',jQuery(this).val());

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


function rw_tabs_change_col(RW_ID_For_Edit) {
    var panelSwitchCol = document.getElementById('rw_tabs_control_special_col');
    if (jQuery('#RW_Tabs_Special_Colors').hasClass('RichWeb_Switch_But')) {
    	jQuery('#RW_Tabs_Special_Colors').removeClass('RichWeb_Switch_But');
        jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr({
            "data-switch": 'off',"data-col": '',"data-hover": '',"data-active": ''
        })
        panelSwitchCol.style.height = null;
        jQuery('#rw_tabs_control_special_col').html('');
        RW_Tabs_Preview_Style.removeProperty('--rw_tabs_menu_item-c_'+RW_ID_For_Edit);
        RW_Tabs_Preview_Style.removeProperty('--rw_tabs_menu_item-h-c_'+RW_ID_For_Edit);
        RW_Tabs_Preview_Style.removeProperty('--rw_tabs_menu_item-a-c_'+RW_ID_For_Edit);
    }else{
        jQuery('#rw_tabs_control_special_col').html(`
            <div class="rw_tabs_container_control"><div id="RW_Tabs_Spec_Item_C-Cont"></div><label for="RW_Tabs_Spec_Item_C">Text Color</label><input type="text" name="RW_Tabs_Spec_Item_C" id="RW_Tabs_Spec_Item_C" value=""></div>
            <div class="rw_tabs_container_control"><div id="RW_Tabs_Spec_Item_H_C-Cont"></div><label for="RW_Tabs_Spec_Item_H_C">Text Hover</label><input type="text" name="RW_Tabs_Spec_Item_H_C" id="RW_Tabs_Spec_Item_H_C" value=""></div>
            <div class="rw_tabs_container_control"><div id="RW_Tabs_Spec_Item_A_C-Cont"></div><label for="RW_Tabs_Spec_Item_A_C">Text Current</label><input type="text" name="RW_Tabs_Spec_Item_A_C" id="RW_Tabs_Spec_Item_A_C"    value=""></div> 
        `);
        if (jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr("data-switch") == "on") {
            var RW_Tabs_Item_Col   = jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr("data-col");
            var RW_Tabs_Item_H_Col = jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr("data-hover");
            var RW_Tabs_Item_A_Col = jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr("data-active");
        }else{
            var RW_Tabs_Item_Col   = getComputedStyle(document.querySelector(':root')).getPropertyValue('--rw_tabs_menu_item-c');
            var RW_Tabs_Item_H_Col = getComputedStyle(document.querySelector(':root')).getPropertyValue('--rw_tabs_menu_item-h-c');
            var RW_Tabs_Item_A_Col = getComputedStyle(document.querySelector(':root')).getPropertyValue('--rw_tabs_menu_item-a-c');
            jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr({
                "data-switch": 'on',"data-col": RW_Tabs_Item_Col,"data-hover": RW_Tabs_Item_H_Col,"data-active": RW_Tabs_Item_A_Col
            })
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-c_'+RW_ID_For_Edit,RW_Tabs_Item_Col);
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-h-c_'+RW_ID_For_Edit,RW_Tabs_Item_H_Col);
            RW_Tabs_Preview_Style.setProperty('--rw_tabs_menu_item-a-c_'+RW_ID_For_Edit,RW_Tabs_Item_A_Col);
        }
        var RW_Tabs_Special_Colors_Arr = new Object();
        RW_Tabs_Special_Colors_Arr["RW_Tabs_Spec_Item_C"]  =  RW_Tabs_Item_Col  ;
        RW_Tabs_Special_Colors_Arr["RW_Tabs_Spec_Item_H_C"]  = RW_Tabs_Item_H_Col;
        RW_Tabs_Special_Colors_Arr["RW_Tabs_Spec_Item_A_C"]  = RW_Tabs_Item_A_Col;  
        var RW_Tabs_Special_Color_Data = new Object();
        RW_Tabs_Special_Color_Data["RW_Tabs_Spec_Item_C"]  =   "data-col" ;
        RW_Tabs_Special_Color_Data["RW_Tabs_Spec_Item_H_C"]  = "data-hover";
        RW_Tabs_Special_Color_Data["RW_Tabs_Spec_Item_A_C"]  = "data-active";  
        <?php foreach ($RW_Tabs_Special_Colors_Arr as $key => $value) : ?>
             const pickr<?php echo esc_js($key);?> = Pickr.create({
                 el: '#<?php echo esc_js($key);?>',
                 container:'#<?php echo esc_js($key);?>-Cont',
                 default:RW_Tabs_Special_Colors_Arr["<?php echo esc_js($key); ?>"],
                 theme: 'monolith', // or 'monolith', or 'nano'
                 autoReposition: true,
                 comparison: false,
                 components: { opacity: true, hue: true, interaction: { input: true, } }
             });
             pickr<?php echo esc_js($key);?>.on('change', (color, source, instance) => {
                var myColor = color.toHEXA().toString();
                pickr<?php echo esc_js($key);?>.setColor(myColor,false);
                RW_Tabs_Preview_Style.setProperty('<?php echo  esc_js($value); ?>_'+RW_ID_For_Edit,myColor);
                jQuery('#rw_tabs_spec_c-'+RW_ID_For_Edit).attr(RW_Tabs_Special_Color_Data["<?php echo  esc_js($key); ?>"],myColor)
             });
        <?php endforeach; ?>
        jQuery('#RW_Tabs_Special_Colors').addClass('RichWeb_Switch_But');
        panelSwitchCol.style.height = 'auto';
    }
}

jQuery(document).on("click", "#RW_Tabs_Update_Button" , function() {
    var TabsProporties = [];
    var GlobalProporties = {};
    var BaseProporties = {};
    jQuery('#RW-Tabs-Loader').css('display','');
    if (RW_Tabs_New_Acd_Tab == "Tab") {
        jQuery('.rw_tabs_control-panel-flex > .rw_tabs_fields').each(function () {
            var elemId = jQuery(this).attr('data-sort');
            var TabsProportiesObj = {
                Tabs_Subtitle:jQuery(this).children('span').text(),
                Tabs_Icon:jQuery(this).children('span').attr('data-subicon'),
                Tabs_Content:jQuery('#rw_tabs_desc_'+elemId).html(),
                Tabs_Special_Color:jQuery('#rw_tabs_spec_c-'+elemId).attr('data-switch'),
                Tabs_Special_Color_B:jQuery('#rw_tabs_spec_c-'+elemId).attr('data-col'),
                Tabs_Special_Color_H:jQuery('#rw_tabs_spec_c-'+elemId).attr('data-hover'),
                Tabs_Special_Color_A:jQuery('#rw_tabs_spec_c-'+elemId).attr('data-active'),
            };
            TabsProporties.push(TabsProportiesObj);
        });
        GlobalProporties = {
            RWTabs_Width:jQuery('#RW_Tabs_Width').val(),
            RWTabs_Align:jQuery('input[name="RW_Tabs_T_Align"]:checked').val(),
            RWTabs_Type:jQuery('input[name="RW_Tabs_T_Type"]:checked').val(),
            RWTabs_Mobile:jQuery('#RW_Tabs_T_Mobile').val(),
            RWTabs_M_Bgc : jQuery('#RW_Tabs_Menu_Bgc-Out').val(),
            RWTabs_M_BC : jQuery('#RW_Tabs_Menu_BC-Out').val(),
            RWTabs_Item_Col_B : jQuery('#RW_Tabs_Item_Col-Out').val(),
            RWTabs_Item_Col_H : jQuery('#RW_Tabs_HoverItem_Col-Out').val(),
            RWTabs_Item_Col_A : jQuery('#RW_Tabs_ActiveItem_Col-Out').val(),
            RWTabs_Item_Bgc_B : jQuery('#RW_Tabs_Item_Bgc-Out').val(),
            RWTabs_Item_Bgc_H : jQuery('#RW_Tabs_HoverItem_Bgc-Out').val(),
            RWTabs_Item_Bgc_A : jQuery('#RW_Tabs_ActiveItem_Bgc-Out').val(),
            RWTabs_Item_FontSize : jQuery('#RW_Tabs_Item_FS').val(),
            RWTabs_Item_FontFamily : jQuery('#RW_Tabs_Item_FF').val(),
            RWTabs_Item_IconSize : jQuery('#RW_Tabs_Item_IS').val(),
            RWTabs_C_Type : jQuery('#RW_Tabs_C_Type').val(),
            RWTabs_C_BW :  jQuery('#RW_Tabs_C_BW').val(),
            RW_Tabs_C_BC :  jQuery('#RW_Tabs_C_BC-Out').val(),
            RWTabs_C_BR :  jQuery('#RW_Tabs_C_BR').val(),
            RWTabs_C_Anim :  jQuery('#RWTabs_C_Anim').val(),
        };
        BaseProporties = {
            RWTabs_Name :  RW_Tabs_Global_Name,
            RWTabs_Theme_id :  RWTabs_Theme_Id,
            RWTabs_Short_id :  RW_Tabs_Global_ID,
            RWTabs_TNS :  RWTabs_Theme_TNS,
        };
         if (jQuery('input[name="RW_Tabs_T_Type"]:checked').val() == 'horizontal') {
             GlobalProporties['RWTabs_M_Pos'] = jQuery('input[name="RW_Tabs_M_Align"]:checked').val();
             GlobalProporties['RWTabs_M_Wrap'] = jQuery('input[name="RW_Tabs_M_Flex-Wrap"]:checked').val();
             GlobalProporties['RWTabs_M_Height'] = jQuery('#RW_Tabs_M_Height').val();
             GlobalProporties['RWTabs_M_Gap'] = jQuery('#RW_Tabs_M_Gap').val();
         }else{
             GlobalProporties['RWTabs_M_Pos'] = jQuery('input[name="RW_Tabs_V_M_Align"]:checked').val();
             GlobalProporties['RWTabs_M_Width'] = jQuery('#RW_Tabs_V_M_Width').val();
         }
         if (jQuery('#RW_Tabs_C_Type').val() == 'color') {
              GlobalProporties['RWTabs_C_Col_F'] = jQuery('#RW_Tabs_C_Bgc_F-Out').val();
         } else if(jQuery('#RW_Tabs_C_Type').val() == 'gradient'){
             GlobalProporties['RWTabs_C_Col_F'] = jQuery('#RW_Tabs_C_Bgc_F-Out').val();
             GlobalProporties['RWTabs_C_Col_S'] = jQuery('#RW_Tabs_C_Bgc_S-Out').val();
         }
    }else{
        jQuery('.rw_tabs_control-panel-flex > .rw_tabs_fields').each(function () {
            var elemId = jQuery(this).attr('data-sort');
            var TabsProportiesObj = {
                Tabs_Subtitle:jQuery(this).children('span').text(),
                Tabs_Icon:jQuery(this).children('span').attr('data-subicon'),
                Tabs_Content:jQuery('#rw_tabs_desc_'+elemId).html(),
            };
            TabsProporties.push(TabsProportiesObj);
        });
        GlobalProporties = {
            RWTabs_Width : jQuery('#RW_Tabs_Width').val(),
            RWTabs_Align : jQuery('input[name="RW_Tabs_T_Align"]:checked').val(),
            RWTabs_M_Gap : jQuery('#RW_Tabs_M_Gap').val(),
            RW_Tabs_M_C_Gap : jQuery('#RW_Tabs_M_C_Gap').val(),
            RWTabs_Item_FontSize : jQuery('#RW_Tabs_Item_FS').val(),
            RWTabs_Item_FontFamily : jQuery('#RW_Tabs_Item_FF').val(),
            RWTabs_Item_Text_S : jQuery('#RW_Tabs_Item_Text_Style').val(),
            RWTabs_Item_Col_B : jQuery('#RW_Tabs_Item_Col-Out').val(),
            RWTabs_Item_Col_H : jQuery('#RW_Tabs_HoverItem_Col-Out').val(),
            RWTabs_Item_Col_A : jQuery('#RW_Tabs_ActiveItem_Col-Out').val(),
            RWTabs_Item_Bgc_B : jQuery('#RW_Tabs_Item_Bgc-Out').val(),
            RWTabs_Item_Bgc_H : jQuery('#RW_Tabs_HoverItem_Bgc-Out').val(),
            RWTabs_Item_Bgc_A : jQuery('#RW_Tabs_ActiveItem_Bgc-Out').val(),
            RWTabs_Item_Bgc_S : jQuery('#RW_Tabs_Menu_Bgc_Style').val(),
            RWTabs_Item_Box_B : jQuery('#RWTabs_Item_BoxShadow-Out').val(),
            RWTabs_Item_Box_H : jQuery('#RWTabs_Item_BoxShadow_Hover-Out').val(),
            RWTabs_Item_Box_A : jQuery('#RWTabs_Item_BoxShadow_Active-Out').val(),
            RWTabs_Item_Box_S : jQuery('#RW_Tabs_Menu_BoxShadow').val(),
            RWTabs_Item_BC_B : jQuery('#RW_Tabs_Item_Border-Out').val(),
            RWTabs_Item_BC_H : jQuery('#RW_Tabs_Item_Border_Hover-Out').val(),
            RWTabs_Item_BC_A : jQuery('#RW_Tabs_Item_Border_Active-Out').val(),
            RWTabs_Item_BS : jQuery('#RW_Tabs_Menu_BS').val(),
            RWTabs_Item_BR : jQuery('#RW_Tabs_Item_Border_R').val(),
            RWTabs_Item_BW : jQuery('#RW_Tabs_Item_Border_W').val(),
            RWTabs_Item_LI_C_B : jQuery('#RW_Tabs_LeftIcon-Out').val(),
            RWTabs_Item_LI_C_H : jQuery('#RW_Tabs_LeftIcon_Hover-Out').val(),
            RWTabs_Item_LI_C_A : jQuery('#RW_Tabs_LeftIcon_Active-Out').val(),
            RWTabs_Item_LI_S : jQuery('#RW_Tabs_LeftIcon_Size').val(),
            RWTabs_Item_RI_C_B : jQuery('#RW_Tabs_RightIcon-Out').val(),
            RWTabs_Item_RI_C_H : jQuery('#RW_Tabs_RightIcon_Hover-Out').val(),
            RWTabs_Item_RI_C_A : jQuery('#RW_Tabs_RightIcon_Active-Out').val(),
            RWTabs_Item_RI_S : jQuery('#RW_Tabs_RightIcon_Size').val(),
            RWTabs_RightIcon : jQuery('#RW_Tabs_RightIcon_Style').val(),
            RWTabs_C_Type : jQuery('#RW_Tabs_C_Type').val(),
            RWTabs_C_BW : jQuery('#RW_Tabs_C_BW').val(),
            RW_Tabs_C_BC : jQuery('#RW_Tabs_C_BC-Out').val(),
            RWTabs_C_BR : jQuery('#RW_Tabs_C_BR').val(),
            RWTabs_Cont_Anim : jQuery('#RWTabs_Cont_Anim').val(),
        };
        if (jQuery('#RW_Tabs_C_Type').val() == 'color') {
             GlobalProporties['RWTabs_C_Col_F'] = jQuery('#RW_Tabs_C_Bgc_F-Out').val();
        } else if(jQuery('#RW_Tabs_C_Type').val() == 'gradient' || jQuery('#RW_Tabs_C_Type').val() == 'gradient-top'){
            GlobalProporties['RWTabs_C_Col_F'] = jQuery('#RW_Tabs_C_Bgc_F-Out').val();
            GlobalProporties['RWTabs_C_Col_S'] = jQuery('#RW_Tabs_C_Bgc_S-Out').val();
        }
        BaseProporties={
            RWTabs_Name :  RW_Tabs_Global_Name,
            RWTabs_Theme_id :  RWTabs_Theme_Id,
            RWTabs_Short_id :  RW_Tabs_Global_ID,
            RWTabs_TNS :  RWTabs_Theme_TNS,
        };
    } 
	var data = {
        GlobalProporties : JSON.stringify(GlobalProporties),
        TabsProporties : JSON.stringify(TabsProporties),
        BaseProporties : JSON.stringify(BaseProporties),
        RW_Build_Type:'<?php echo esc_js($RW_Tabs_Build_Type);  ?>',
	    action: 'RW_Tabs_Save_Data', 
        rw_tabs_nonce_field : rwtabs_object.rw_tabs_ajax_wp_nonce,
	};

	jQuery.post(rwtabs_object.ajaxurl, data, function(response) {
        RW_Tabs_New_Tab_Save = RW_Tabs_Global_ID;
        jQuery('#RW-Tabs-Loader').css('display','none');
        if (jQuery('#RW_Tabs_Update_Button').attr('data-rw-content') == 'Save') {
            RW_Tabs_Message(`<i class="rich_web rich_web-check"></i>The <strong>${RW_Tabs_Global_Name}</strong> saved.`,'success');
        } else {
            RW_Tabs_Message(`<i class="rich_web rich_web-check"></i>The <strong>${RW_Tabs_Global_Name}</strong> updated.`,'success');
        }
        jQuery('#RW_Tabs_Update_Button').attr('data-rw-content','Update').html('Update');
    })
});
</script>

<?php } ?>