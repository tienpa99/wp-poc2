<?php 
if (!current_user_can('manage_options'))  die('Access Denied');
if(!defined('ABSPATH')) exit;
global $wpdb;
$RWTabs_Themes_Table  = $wpdb->prefix . "rich_web_tabs_themes_data";
$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";

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

$RW_Tabs_Manager_Records=$wpdb->get_results($wpdb->prepare("SELECT `id`,`Tabs_Name`,`Tabs_Theme`,`SubTitles_Count`,`Tabs_TNS`,`created_at`,`updated_at` FROM $RWTabs_Manager_Table WHERE id>%d ORDER BY id ASC",0));
$RWTabs_Bool = $Rich_Web_Tabs_Data = $Rich_Web_Tabs_Data_Names ='';
if (count($RW_Tabs_Manager_Records) != 0) {
     $RWTabs_Bool = true; 
     $Rich_Web_Tabs_Data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Themes_Table WHERE id>%d",0));
     $Rich_Web_Tabs_Data_Names = array_column(json_decode(json_encode($Rich_Web_Tabs_Data),true), 'Rich_Web_Tabs_T_T', 'id');
}


?>
<script>

</script>
<style>
    #wpcontent{padding-left: 0 !important;}
    *, *::before, *::after {margin:0px;padding:0px;text-rendering: optimizeLegibility;-webkit-font-smoothing: antialiased;}
    :root{--rw-tabs-bcg-color: #6ecae9;--rw-tabs-panel-width:25vw;--rw-tabs-panel-min-width:25vw;--rw-tabs-panel-max-width:40vw;--rw-tabs-preview-panel-width: 75vw;--rw-tabs-body-overflow:auto;--rw-tabs-grid-layout : 1fr 1fr 1fr 1fr;--rw-tabs-grid-layout-bgc: #f0f0f1;--rw_tabs_panel_grid_sys : 10% 90%;--rw-tabs-preview-container-width: 100%;--rw-tabs-preview-container-height: 100%;}
    @media (max-width: 1176px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr 1fr 1fr;}}
    @media (max-width: 768px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr 1fr;}.RW_Tabs_Manager-Item {  width: 45%; }}
    @media (max-width: 540px) { #RW_Tabs_Options {  --rw-tabs-grid-layout : 1fr;}.RW_Tabs_Manager-Item {  width: 90%; }}


    /* RW_Tabs_Search Button Area */
    #RW_Tabs_Opt_Manager_Nav {display: flex;overflow-x: auto;background-color: #fff;border-bottom: 2px var(--rw-tabs-bcg-color) solid;flex-wrap: nowrap;flex-direction: row;justify-content:space-between;align-items: center;color:#fff;padding: 16px 18px 10px 15px;}
    #RW_Tabs_Nothing_Result,.RW_Tabs_Nothing_Result  {border-left: 6px solid var(--rw-tabs-bcg-color);padding-left:20px;}
    .RWTabs_Create_New{text-decoration:none;color:#fff;margin-left:10px;padding:6px 15px;background-color:#6ecae9;box-shadow:-3px 5px 10px 0 #30a9d1;border-radius:5px 30px;outline:0!important;border:none!important;cursor:pointer;}
    .RWTabs_Create_New:focus,.RWTabs_Create_New:hover,.RWTabs_Create_New:active{box-shadow:-3px 5px 10px 0 #30a9d1;color:#ffffff;text-decoration:none;outline:0!important;border:none!important;}
    .RWTabs_btnSearch:focus {outline: none;}
    button.RWTabs_btnSearch {border: none;height: 30px;font-size: 12px;padding: 4px;position: absolute;width: 30px;color: var(--rw-tabs-bcg-color);}
    .RW_Tabs_Manager_Search{position: relative;}
    .RW_Tabs_Manager_Search #RWTabs_Search {background: none;border-color: unset;border:none;outline: unset;border-radius: 15px;border-width: 0 0 1px;transition: all 0.8s ease-in-out;width: 30px;}
    .RW_Tabs_Manager_Search #RWTabs_Search:focus { background: radial-gradient(ellipse at top left, rgba(0, 0, 0, 0) 65%, var(--rw-tabs-bcg-color) 140%); border-radius: 0 15px 15px 0; width: 250px; color: #2c3338; box-shadow: unset;}
    .RW_Tabs_Manager_Search #RWTabs_Search:focus ~ button.RWTabs_btnSearch {background: var(--rw-tabs-bcg-color);color: #fff;left: 222px;transform: rotate(720deg);}
    .RW_Tabs_Manager_Search button {transition: all 0.8s ease-in-out;}
    .RW_Tabs_Manager_Search button.RWTabs_btnSearch {cursor:pointer;background: #fff;border: 1px solid var(--rw-tabs-bcg-color);border-radius: 50%;height: 30px;left: 10px;width: 30px;} 
    .RW_Tabs_Item-Field-Col>i{color:#5bc1e4;margin-left:3px;cursor:pointer;}
    
    /* RW Modal CSS */
    #RW_Tabs_Delete_Sec{position:fixed;left:0;top:0;width:100%;height:100vh;z-index:1000;background:rgba(0,0,0,.2);transition:transform .3s ease-out,-webkit-transform .3s ease-out;}
    #RW_Tabs_PopUp_Div{position:fixed;width:100%;z-index:9999999999999;top:50%;transform:translateY(-50%);left:0;text-align:center;}
    #RW_Tabs_PopUp_Content{position:relative;background:#fff;margin:0 auto;padding:5px 10px;color:#000;border:2px solid #fff;float:left;left:50%;transform:translateX(-50%);width:200px;padding:20px;border-radius:5px;border:none;text-align:center;font-size:14px;}
    #RW_Tabs_PopUp_Content>header{display:-ms-flexbox;display:-webkit-flex;display:flex;-ms-flex-align:start;align-items:center;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;padding:1rem 1rem;border-bottom:1px solid #dee2e6;flex-direction:column;flex-wrap:wrap;}
    #RW_Tabs_PopUp_Content>header>div{width:80px;height:80px;margin:0 auto;border-radius:50%;z-index:9;text-align:center;border:3px solid #f15e5e;}
    #RW_Tabs_PopUp_Content>header>div>i{color:#f15e5e;font-size:46px;display:inline-block;margin-top:13px;}
    #RW_Tabs_PopUp_Content>header>p{text-align:center;font-size:26px;margin:3px 0 0;}
    #RW_Tabs_PopUp_Close{position:absolute;top:0;right:3px;text-decoration:none;cursor:pointer;padding:0;background-color:transparent;border:0;float:right;font-size:1.5rem;font-weight:700;line-height:1;text-shadow:0 1px 0 #fff;opacity:.5;}
    #RW_Tabs_PopUp_Content>footer{margin-top:10px;display:grid;grid-template-columns:1fr 1fr;}
    #RW_Tabs_PopUp_Content>footer>div{opacity:.7;color:#fff;border-radius:4px;border:none;border-radius:3px;margin:0 5px;padding:5px;cursor:pointer;}
    #RW_Tabs_PopUp_Content>footer>div:nth-child(1){background:#a8a8a8;}
    #RW_Tabs_PopUp_Content>footer>div:nth-child(2){background:#ee3535;}
    #RW_Tabs_PopUp_Content>footer>div:hover{opacity:1;}

    /* RW-Tabs-Manager Content */
    #RW-Tabs-Options-Manager {display: flex;flex-direction: row;flex-wrap: wrap;align-content: flex-end;justify-content: space-around;align-items: center;column-gap: 20px;row-gap: 25px;padding-top: 20px;}
    .RW-Tabs-Options-Manager-No{align-content: flex-start !important;vertical-align: middle !important;height: 50vh !important;padding-top: 40px !important;}
    .RW_Tabs_Manager-Item {width: 30%;}
    .RW_Tabs_Item-Content {background-color: white;border-radius: 0.25rem;box-shadow: 0 20px 40px -14px rgba(0, 0, 0, 0.25);display: flex;flex-direction: column;overflow: hidden;}
    .RW_Tabs_Item-Name{display:flex;flex:1 1 auto;flex-direction:column;padding:1rem;color:#fff;font-size:1.25rem;font-weight:300;letter-spacing:2px;text-transform:uppercase;background:#6ecae9;text-align:center;}
    .RW_Tabs_Item-Actions{width:100%;display:flex;flex-direction:row;justify-content:flex-start;flex-wrap:nowrap;color:#fff;background-color:#6ecae9;font-size:15px;}
    .RW_Tabs_Item-Actions>.rw_tabs_item_act{display:flex;align-content:center;justify-content:center;align-items:center;vertical-align:middle;width:25%;padding:15px 0;cursor:pointer;}
    .RW_Tabs_Item-Actions>.rw_tabs_item_act:not(:last-child){border-right:1px solid #5bc1e4;}
    .RW_Tabs_Item-Actions>.rw_tabs_item_act,.RW_Tabs_Item-Actions>.rw_tabs_item_act:hover,.RW_Tabs_Item-Actions>.rw_tabs_item_act:focus,.RW_Tabs_Item-Actions>.rw_tabs_item_act:active{
        color:#ffffff;
        text-decoration:none;
        outline:none;
        box-shadow: none;
    }
    .RW_Tabs_Item-Props-Copied:after{display:block;padding:0 10px;content:"Copied Item";position:absolute;left:0;bottom:0;font-size:14px;font-family:monospace;color:#fff;text-align:center;background:var(--rw-tabs-bcg-color);transform-origin:0 0;transform:rotate(270deg);transition:.1s ease-in-out;}
    .RW_Tabs_Item-Props{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;gap:5px;padding:20px 15px;overflow: hidden;position: relative;}
    .RW_Tabs_Item-Props-Field{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;border-bottom:1px solid #d0c5ac;justify-content:space-between;}
    /* RW-Tabs-Manager Content END */

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

<section id="RW-Tabs-Options-Content">
    <?php require_once("Tabs-Rich-Web-Header.php");  ?>
    <div id="RW_Tabs_Opt_Manager_Nav" <?php if($RWTabs_Bool != true)  echo esc_attr('style="display:none;"'); ?>>
        <div class="RW_Tabs_Manager_Search" >
            <input type="text" id="RWTabs_Search" placeholder="Search by name">
            <button  class="RWTabs_btnSearch" onclick="this.previousElementSibling.focus()">
              <i class="rich_web rich_web-search"></i>
            </button>
        </div>
        <div>
            <a href="<?php echo esc_url( sprintf( '?page=%s', esc_attr('RW_Tabs_Create'))); ?>" class="RWTabs_Create_New">Create Now</a>
        </div>
    </div>

    <main id="RW-Tabs-Options-Manager" >
        <article style="display:none;" id="RW_Tabs_Nothing_Result"><h1>Nothing Found</h1><p>Sorry, but we can't get anything  like <strong>your search keyword.</strong><br /></p></article></hr>
        <?php
        if ($RWTabs_Bool == true) {
            for ($c=0; $c < count($RW_Tabs_Manager_Records); $c++) { ?>
                <div class="RW_Tabs_Manager-Item" id="RW_Tabs_Manager-Item-<?php esc_attr_e($RW_Tabs_Manager_Records[$c]->id); ?>" data-rw-search="<?php esc_attr_e($RW_Tabs_Manager_Records[$c]->Tabs_Name); ?>">
                    <div class="RW_Tabs_Item-Content">
                        <section class="RW_Tabs_Item-Actions">
                            <a class="RW_Tabs_Item_Edit_Act rw_tabs_item_act" href="<?php echo esc_url( sprintf( '?page=%s&rw_tab=%d', esc_attr('RW_Tabs_Create'), absint( esc_html($RW_Tabs_Manager_Records[$c]->id) ))); ?>" ><i class="rich_web rich_web-pencil"></i></a>
                            <a class="RW_Tabs_Item_Copy_Act rw_tabs_item_act" onclick="RW_Tabs_Man_Copy_Opt(<?php echo esc_js($RW_Tabs_Manager_Records[$c]->id); ?>,'<?php echo esc_js($Rich_Web_Tabs_Data_Names[(int) $RW_Tabs_Manager_Records[$c]->Tabs_Theme]); ?>')"><i class="rich_web rich_web-files-o"></i></a>
                            <a class="RW_Tabs_Item_Delete_Act rw_tabs_item_act" onclick="RW_Tabs_Man_Delete_Opt(<?php echo esc_js($RW_Tabs_Manager_Records[$c]->id); ?>)"><i class="rich_web rich_web-trash"></i></a>
                            <a class="RW_Tabs_Item_Prev_Act rw_tabs_item_act" target="_blank" href="<?php echo esc_url( sprintf( '%s?rw_tabs_preview=%s', esc_url(home_url()),absint( esc_html($RW_Tabs_Manager_Records[$c]->id) ))); ?>"><i class="rich_web rich_web-eye"></i></a>
                        </section>
                        <section class="RW_Tabs_Item-Props">
                            <div class="RW_Tabs_Item-Props-Field"><span>Menu item counts</span><span class="RW_Tabs_Item-Field-Col"><?php esc_html_e($RW_Tabs_Manager_Records[$c]->SubTitles_Count); ?></span></div>
                            <div class="RW_Tabs_Item-Props-Field"><span>Theme</span><span class="RW_Tabs_Item-Field-Col"><?php esc_html_e($Rich_Web_Tabs_Data_Names[(int) $RW_Tabs_Manager_Records[$c]->Tabs_Theme]); ?></span></div>
                            <div class="RW_Tabs_Item-Props-Field"><span>Created at</span><span class="RW_Tabs_Item-Field-Col"><?php esc_html_e($RW_Tabs_Manager_Records[$c]->created_at); ?></span></div>
                            <div class="RW_Tabs_Item-Props-Field"><span>Updated at</span><span class="RW_Tabs_Item-Field-Col"><?php esc_html_e($RW_Tabs_Manager_Records[$c]->updated_at); ?></span></div>
                            <div class="RW_Tabs_Item-Props-Field">
                                <span>Shortcode</span><span class="RW_Tabs_Item-Field-Col">[Rich_Web_Tabs id="<?php esc_html_e($RW_Tabs_Manager_Records[$c]->id); ?>"] <i class="rich_web rich_web-files-o RW_Tabs_Short_Copy" data-rw-shortid="<?php esc_attr_e($RW_Tabs_Manager_Records[$c]->id); ?>"></i></span>
                            </div>
                        </section>
                        <section class="RW_Tabs_Item-Name"><?php esc_html_e($RW_Tabs_Manager_Records[$c]->Tabs_Name); ?></section>
                    </div>
                </div>         
            <?php }
        }else{ ?>
            <article  class="RW_Tabs_Nothing_Result">
                <h1>Nothing Found</h1>
                <p>Sorry, but we can't get anything created with <strong>our plugin.</strong><br /></p>
            </article>
            </hr>
            <a href="<?php echo esc_url( sprintf( '?page=%s', esc_attr('RW_Tabs_Create'))); ?>" class="RWTabs_Create_New">Create Now</a>
       <?php } ?>
    </main>

</section>

<section id="RW_Tabs_Delete_Sec" style="display: none;">
    <div id="RW_Tabs_PopUp_Div" >
		<div id="RW_Tabs_PopUp_Content">
            <header><div><i class="rich_web rich_web-exclamation-triangle"></i></div><p>Are you sure?</p></header>
            <main>Do you really want to delete this records? This process cannot be undone.</main>
            </hr>
            <footer><div class="RW_Tabs_PopUp-Cancel">Cancel</div><div class="RW_Tabs_PopUp-Delete">Delete</div></footer>
            <button type="button" id="RW_Tabs_PopUp_Close">×</button>
		</div>
	</div>
</section>



	