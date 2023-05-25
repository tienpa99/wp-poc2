<style>
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
    .RWTabs_Actioned_Btn::before{position: absolute;top: -35%;left: 43%;font-size: 20px;background: #ffffff;font-family: FontAwesome;content:"\f021";-webkit-animation: fa-spin 2s infinite linear;      animation: fa-spin 2s infinite linear;}
    .RWTabs_Choose_Btn_Inner,.RWTabs_DemoBtn_Inner,.RWTabs_Upgrade_Btn_Inner{background: #ffffff;padding: 5px;cursor:pointer;}
    .RW_Tabs_Opt_Content-Footer > a,.RW_Tabs_Opt_Content-Footer > a:focus,.RW_Tabs_Opt_Content-Footer > a:active,.RW_Tabs_Opt_Content-Footer > a:hover{ text-decoration: none;outline: none;border: none;box-shadow:none; }
    .RW_Tabs_Opt_Content-Img-Overlay,.RW_Tabs_Opt_Content-Img-Overlay:focus,.RW_Tabs_Opt_Content-Img-Overlay:active,.RW_Tabs_Opt_Content-Img-Overlay:hover{ text-decoration: none;outline: none;}
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
    #RW-Tabs-Content{display:flex;display:-ms-flexbox;display:-webkit-box;width:100vw;height:100vh;position:fixed;left:0;right:0;top:0;bottom:0;z-index:100049;background:#fff;}
    #RW-Tabs-Panel{width:var(--rw-tabs-panel-width);max-width:var(--rw-tabs-panel-max-width);min-width:var(--rw-tabs-panel-min-width);background:#e6e9ec;height:100%;display:grid;grid-template-rows:5vh 90vh 5vh;}
    #RW-Tabs-Panel:not(.ui-resizable-resizing){-webkit-transition:all .5s ease-in-out;-o-transition:all .5s ease-in-out;transition:all .5s ease-in-out;}
    #RW-Tabs-Preview-Panel:not(.ui-resizable-resizing){-webkit-transition:all .5s ease-in-out;-o-transition:all .5s ease-in-out;transition:all .5s ease-in-out;}
    .RW-Tabs-Panel-Hidden{margin-left:calc(var(--rw-tabs-panel-width) * -1)!important;}
    #RW-Tabs-Preview-Panel{width:var(--rw-tabs-preview-panel-width);background:#f0f0f1;position:relative;left:0;right:0;top:0;bottom:0;}
    #RW_Tabs_Iframe_Container{position:relative;width:var(--rw-tabs-preview-container-width);height:var(--rw-tabs-preview-container-height);-webkit-box-sizing:content-box;box-sizing:border-box;position:relative;}
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
    #RW_Tabs_Preview_Iframe{width:100%;height:100%;background-color:#fff;}
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
    #RW_Tabs_Responsive_Iframe{width:100%;height:100%;display:flex;justify-content:center;align-items:center;background-color:#23282d;}
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
    .RWTabs_Back_toDashboard{margin-right:auto;}
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
</style>