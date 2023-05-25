<?php
	if(!defined('ABSPATH')) exit;
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
  global $wpdb;
  $RWTabs_Themes_Table  = $wpdb->prefix . "rich_web_tabs_themes_data";
  $RW_Tabs_Checking_Install=$wpdb->get_results($wpdb->prepare("SELECT  table_name FROM information_schema.TABLES WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s",$wpdb->dbname,$RWTabs_Themes_Table));
  if (count($RW_Tabs_Checking_Install) != 0) {
      $Rich_Web_Tabs_Data_Checking = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Themes_Table WHERE id>%d",0));
      if (count($Rich_Web_Tabs_Data_Checking) == 0) {
          require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Install.php');
      } 
  } else {
      require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Install.php');
  }
	require_once( 'Tabs-Rich-Web-Header.php' );
?>
<style type="text/css">
	#RichWeb_Products_Layout {
        background-color: #f0f0f1;
        display: grid;
        grid-gap: 30px;
        padding: 20px 36px;
        grid-template-columns: 1fr 1fr 1fr 1fr;
    }
	.Rich_Web_Product_Card {
	    background: #ffffff;
	    border-radius: 1.2rem;
	    overflow: hidden;
	    box-shadow: 0.5rem 0.5rem 1rem rgba(51, 51, 51, 0.2), 0.5rem 0.5rem 1rem rgba(51, 51, 51, 0.2);
	}
	.Rich_Web_Product_Card-Header {
	    height: 125px;
	}
	.Rich_Web_Product_Card-Body {
	    margin-bottom: 1rem;
	    display: flex;
	    flex-direction: column;
	    justify-content: center;
	    align-items: center;
	    align-content: center;
	}
	.Rich_Web_Product_Card-Img{
	    margin-top: -90px;
	    border: 0.5rem solid #fff;
	    border-radius: 7%;
	    box-shadow: 0px 7px 10px 0px #c5c7cb;
	}
	.Rich_Web_Product_Card-Name{
	    color: #7e8084;
	}
	.Rich_Web_Product_Card-Footer {
	  display: flex;
	  justify-content: center;
	  padding: 1rem;
	}
	.Rich_Web_Product_Card-Desc {
    	width: 90%;
	}
  .RWTabs_Demo_Btn::before{
      font-family: FontAwesome;
      content: "\f06e";
  }
  .RWTabs_Demo_Btn{
      position: relative;
      color: #71cbea;
      padding: 2px;
      border: none;
      border-radius: 3px;
      font-size: 14px;
      background: linear-gradient( to right, rgb(240 240 241), rgb(110 202 233) );
      cursor:pointer;
      -webkit-touch-callout: none;
      -webkit-user-select: none;  
       -khtml-user-select: none;  
         -moz-user-select: none;  
          -ms-user-select: none;  
              user-select: none;  
      box-shadow: 1rem -3rem 20rem rgb(51 51 51 / 20%), 0.5rem 0.5rem 1rem rgb(51 51 51 / 20%);        
  }
  .RWTabs_Demo_Btn::before{
      position: absolute;
      top: -30%;
      left: 50%;
      transform: translateX(-50%);
      padding: 0 5px;
      font-size: 20px;
      background: #ffffff;
  }
  .RWTabs_DemoBtn_Inner{
      background: #ffffff;
      padding: 5px;
      cursor:pointer;
  }
  @media (max-width: 1176px) {                             
    #RichWeb_Products_Layout {
      grid-template-columns : 1fr 1fr 1fr;
    }
  }
  @media (max-width: 768px) {                             
    #RichWeb_Products_Layout {
      grid-template-columns : 1fr 1fr;
    }
  }
  @media (max-width: 540px) {                             
    #RichWeb_Products_Layout {
      grid-template-columns : 1fr;
    }
  }
</style>
<section id="RichWeb_Products_Layout"> 
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(0 115 170) );" ></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Slider-Image.png',__FILE__));?>"
                alt="Image Slider" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Image Slider</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				 		 <span>S</span>lider Rich Web is one of the most important plugins for WordPress websites. Besides, by beautiful and unrepeatable effects, your slider gives more professional look to your website. Slider Image is one of the best in responsive Slider images plugins. Plugin allows you to modify all setting, such as colors, fonts and sizes, which are corresponding, to standards of the slider. Rich Web Slider Image has that all features, that you can expect from another free slider images plugin. You can create unlimited sliders and images.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-image-slider/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(255 180 0) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Slider-Video.png',__FILE__));?>"
                alt="Video Slider" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Video Slider</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  <span>S</span>lider Video plugin is a great way to create a stunning video slider without programming skills. Fully responsive, works on any mobile device. You can attract more people to your website and amaze them with effective slideshows, that show your videos amazing way. It is very easy. It is necessary to select the video ( currently supports in Youtube, Vimeo, Vevo and MP4) that you would like to show in a Slider using the Rich Web, wich creates a responsive slideshow, thumbnail slider or slider post feed. Slider Video Plugin supports Youtube, Vimeo, Vevo and MP4 videos. It is fully responsive works on iPhone, IPAD, Android, Firefox, Chrome, Safari, Opera and Internet Explorer.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-video-slider/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(5 174 226) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Forms.png',__FILE__));?>"
                alt="Contact Form" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Contact Form</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  		<span>R</span>ich is a WordPress form creator with a multiple choice, that allows to create WordPress form for several minutes. As soon as possible, you can create fully functional contact form without writing a single line of code. Forms Plugin allows to change all settings like the colors, fonts and sizes, which are appropriates to forms standards. Rich Web form has all functions, that you can expect from the other free forms plugin.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-contact-form/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(41 117 153) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Gallery-Image.png',__FILE__));?>"
                alt="Image Gallery" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Image Gallery</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  		<span>P</span>hoto Gallery is awesome WordPress gallery plugin with many useful features and effects. The photo plugin was created and specially designed for photos. Photo Gallery plugin is the responsive photo gallery plugin of the WordPress. There are 8 major versions of gallery style. Photo Gallery plugin is compatible with WordPress themes. You can change the colors of the gallery, sizes, font size and distance from powerful settings panel of gallery plugin.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-image-gallery/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(134 113 240) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Tabs.png',__FILE__));?>"
                alt="Tabs" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Tabs</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  		<span>T</span>abs plugin is fully responsive. Tabs plugin is for creating responsive tabbed panels with unlimited options and transition animations support. If you wish to spice up your corporate website, blog, ecommerce site or a message board, with tabbed it’s easy to show any content, video, price or data tables, form or other elements.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-tab-accordion/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(0 115 170) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Coming-Soon.png',__FILE__));?>"
                alt="Coming-Soon" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Coming-Soon</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  <span>C</span>oming Soon plugin is a responsive, modern and clean under construction & coming soon WordPress Plugin. This minimal template is packed with a countdown timer, ajax subscription form, social icons and about page where you can write a little bit about yourself and add your phone, email and address information.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-coming-soon/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(0 115 170) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Share-Button.png',__FILE__));?>"
                alt="Share-Button" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Share Button</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  		<span>S</span>ocial Share Buttons are a fun way to display your social media buttons. Social network is one of the popular places where people get information about everything in the world.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-share-button/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
			<div class="Rich_Web_Product_Card">
                <div class="Rich_Web_Product_Card-Header" style="background-image: linear-gradient( to right, rgb(240 240 241),rgb(0 115 170) );"></div>
                <div class="Rich_Web_Product_Card-Body">
                <img  
                src="<?php echo esc_url(plugins_url('/Images/Products/Timeline.png',__FILE__));?>"
                alt="Timeline" class="Rich_Web_Product_Card-Img">
                  <h1 class="Rich_Web_Product_Card-Name">RW Timeline</h1>
				  <div class="Rich_Web_Product_Card-Desc">
				  		<span>E</span>vent Timeline is an advanced WordPress timeline plugin that showcases your life history timeline or your company ‘ s story timeline in a responsive horizontal or vertical chronological order based on the year and the date of your posts. It is best plugin to create a timeline theme. You can also convert your blog posts into a blog timeline by using this awesome timeline template maker plugin.
				  </div>
                </div>
                <div class="Rich_Web_Product_Card-Footer">
                  <div class="RWTabs_Demo_Btn" onclick="window.open(`https:&#47;&#47;rich-web.org/wp-event-timeline/`)">
                      <div class="RWTabs_DemoBtn_Inner" >
                        View Demo
                      </div>
                  </div>
                </div>
            </div>
</section>