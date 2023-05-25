<?php
	function RW_Tabs_Man_Copy_Opt_Callback(){
		if (!current_user_can('manage_options') || ! wp_verify_nonce( $_POST['rw_tabs_nonce_field'], 'rw-tabs-ajax-nonce' ) ) {
			wp_die('Security Check Fail');
		}
		global $wpdb;
		$RW_Tabs_Copied_ID = sanitize_text_field($_POST['Copied_ID']);
		$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
		$RW_Tabs_CU_Time= date("d.m.Y h:i:sa");
		$RW_Tabs_Manager=$wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id = %d", $RW_Tabs_Copied_ID));
		$wpdb->query($wpdb->prepare("INSERT INTO $RWTabs_Manager_Table (id,Tabs_Name,Tabs_Theme,SubTitles_Count,Tabs_Fields,Tabs_Props,Tabs_TNS,created_at,updated_at) VALUES (%d, %s, %d, %d, %s, %s, %s, %s, %s)", '',$RW_Tabs_Manager[0]->Tabs_Name,$RW_Tabs_Manager[0]->Tabs_Theme, $RW_Tabs_Manager[0]->SubTitles_Count,$RW_Tabs_Manager[0]->Tabs_Fields,$RW_Tabs_Manager[0]->Tabs_Props,$RW_Tabs_Manager[0]->Tabs_TNS,$RW_Tabs_CU_Time,$RW_Tabs_CU_Time ));
		$RW_Tabs_Manager_Copied=$wpdb->get_results($wpdb->prepare("SELECT `id`,`Tabs_Name`,`Tabs_Theme`,`SubTitles_Count`,`created_at`,`updated_at` FROM $RWTabs_Manager_Table WHERE id > %d ORDER BY id DESC LIMIT 1", 0));
		wp_send_json($RW_Tabs_Manager_Copied[0]);
		wp_die();
	}

	function RW_Tabs_Man_Delete_Opt_Callback(){
		if (!current_user_can('manage_options') || ! wp_verify_nonce( $_POST['rw_tabs_nonce_field'], 'rw-tabs-ajax-nonce' ) ) {
			wp_die('Security Check Fail');
		}
		global $wpdb;
		$RW_Tabs_Deleted_ID = sanitize_text_field($_POST['Deleted_ID']);
		$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
		$wpdb->query($wpdb->prepare("DELETE FROM $RWTabs_Manager_Table WHERE id=%d", $RW_Tabs_Deleted_ID));
		wp_send_json_success();
		wp_die();
	}

	function RW_Tabs_Save_Data_Callback() {
		if (!current_user_can('manage_options') || ! wp_verify_nonce( $_POST['rw_tabs_nonce_field'], 'rw-tabs-ajax-nonce' ) ) {
			wp_die('Security Check Fail');
		}
		$RW_Build_Type = '';
		$counts_of_tab = 0;
		$RW_Colors_Array = [];
		//Check Type 
		switch (sanitize_text_field($_POST['RW_Build_Type'])) {
			case 'accordion':
			case 'tab':
				$RW_Build_Type = sanitize_text_field($_POST['RW_Build_Type']);
				break;
			default:
				wp_die('Security Check Fail');
				break;
		}
		// Decoded request
		$TabsProporties = json_decode(stripslashes($_POST['TabsProporties']),true);
		$GlobalProporties = json_decode(stripslashes($_POST['GlobalProporties']),true);
		// Default Arrays

		$RW_Numbers_Array = [
			 "RWTabs_Width" => ["min" => 0, "max" => 100], "RWTabs_Item_FontSize" => ["min" => 8, "max" => 48], "RWTabs_C_BW" => ["min" => 0, "max" => 10], "RWTabs_C_BR" => ["min" => 0, "max" => 20]
		];
		$RW_Options_Array = [
			"RWTabs_Align" => ['flex-start','center','flex-end'],
			"RWTabs_C_Type" => ['color','transparent','gradient'],
			"RWTabs_Item_FontFamily" => ["Abadi MT Condensed Light","Aharoni","Aldhabi","Andalus","Angsana New","AngsanaUPC","Aparajita","Arabic Typesetting","Arial","Arial Black","Batang","BatangChe","Browallia New","BrowalliaUPC","Calibri","Calibri Light","Calisto MT","Cambria","Candara","Century Gothic","Comic Sans MS","Consolas","Constantia","Copperplate Gothic","Copperplate Gothic Light","Corbel","Cordia New","CordiaUPC","Courier New","DaunPenh","David","DFKai-SB","DilleniaUPC","DokChampa","Dotum","DotumChe","Ebrima","Estrangelo Edessa","EucrosiaUPC","Euphemia","FangSong","Franklin Gothic Medium","FrankRuehl","FreesiaUPC","Gabriola","Gadugi","Gautami","Georgia","Gisha","Gulim","GulimChe","Gungsuh","GungsuhChe","Impact","IrisUPC","Iskoola Pota","JasmineUPC","KaiTi","Kalinga","Kartika","Khmer UI","KodchiangUPC","Kokila","Lao UI","Latha","Leelawadee","Levenim MT","LilyUPC","Lucida Console","Lucida Handwriting Italic","Lucida Sans Unicode","Malgun Gothic","Mangal","Manny ITC","Marlett","Meiryo","Meiryo UI","Microsoft Himalaya","Microsoft JhengHei","Microsoft JhengHei UI","Microsoft New Tai Lue","Microsoft PhagsPa","Microsoft Sans Serif","Microsoft Tai Le","Microsoft Uighur","Microsoft YaHei","Microsoft YaHei UI","Microsoft Yi Baiti","MingLiU_HKSCS","MingLiU_HKSCS-ExtB","Miriam","Mongolian Baiti","MoolBoran","MS UI Gothic","MV Boli","Myanmar Text","Narkisim","Nirmala UI","News Gothic MT","NSimSun","Nyala","Palatino Linotype","Plantagenet Cherokee","Raavi","Rod","Sakkal Majalla","Segoe Print","Segoe Script","Segoe UI Symbol","Shonar Bangla","Shruti","SimHei","SimKai","Simplified Arabic","SimSun","SimSun-ExtB","Sylfaen","Tahoma","Times New Roman","Traditional Arabic","Trebuchet MS","Tunga","Utsaah","Vani","Vijaya"]
		];
		

		if ($RW_Build_Type == "accordion") {

			//Tabs Sanitizing 
			if (count($TabsProporties) == 0) {
				wp_send_json_error('No tabs found');
			}else {
				foreach ($TabsProporties as $key => $value) {
					$RW_Tabs_Textarea = sanitize_textarea_field($value['Tabs_Content']);
					$RW_Tabs_Textarea = str_replace("[", "&#91;",$RW_Tabs_Textarea);
					$RW_Tabs_Textarea = str_replace( "]", "&#93;",$RW_Tabs_Textarea);
					$TabsProporties[$key]['Tabs_Subtitle'] =  sanitize_text_field($value['Tabs_Subtitle']);
					$TabsProporties[$key]['Tabs_Icon'] =  sanitize_text_field($value['Tabs_Icon']);
					$TabsProporties[$key]['Tabs_Content'] =  $RW_Tabs_Textarea;
					$counts_of_tab++;
				}
			}
			$RW_Numbers_Array["RWTabs_M_Gap"] = ["min" => 0, "max" => 15];
			$RW_Numbers_Array["RW_Tabs_M_C_Gap"] = ["min" => 0, "max" => 15];
			$RW_Numbers_Array["RWTabs_Item_LI_S"] = ["min" => 8, "max" => 48];
			$RW_Numbers_Array["RWTabs_Item_RI_S"] = ["min" => 8, "max" => 48];
			$RW_Numbers_Array["RWTabs_Item_BR"] = ["min" => 0, "max" => 15];
			$RW_Numbers_Array["RWTabs_Item_BW"] = ["min" => 0, "max" => 15];
			$RW_Options_Array["RWTabs_Item_Text_S"] = ["style_ti_none","style_ti_1","style_ti_2","style_ti_3","style_ti_4","style_ti_5"];
			$RW_Options_Array["RWTabs_C_Type"]  = ['color','transparent','gradient','gradient-top'];
			$RW_Options_Array["RWTabs_Item_BS"]  = ["none","solid","dotted","dashed"];
			$RW_Options_Array["RWTabs_RightIcon"]  = ["none","sort-desc","circle","angle-double-up","arrow-circle-up","angle-up","plus"];
			$RW_Options_Array["RWTabs_Cont_Anim"]  = ["none","bounce","clip","drop","fade","highlight","pulsate","shake","size","slide"];
			$RW_Options_Array["RWTabs_Item_Box_S"]  = ["none","style_bsh_1","style_bsh_2","style_bsh_3","style_bsh_4","style_bsh_5","style_bsh_6","style_bsh_7","style_bsh_8","style_bsh_9","style_bsh_10","style_bsh_11","style_bsh_12","style_bsh_13","style_bsh_14","style_bsh_15"];
			$RW_Options_Array["RWTabs_Item_Bgc_S"]  = ["style_bg_none","style_bg_1","style_bg_2","style_bg_3","style_bg_4","style_bg_5","style_bg_6","style_bg_7","style_bg_8"];
			$RW_Colors_Array = [
				"RWTabs_Item_Col_B","RWTabs_Item_Col_H","RWTabs_Item_Col_A","RWTabs_Item_Bgc_B","RWTabs_Item_Bgc_H","RWTabs_Item_Bgc_A","RWTabs_Item_Box_B","RWTabs_Item_Box_H","RWTabs_Item_Box_A","RWTabs_Item_BC_B","RWTabs_Item_BC_H","RWTabs_Item_BC_A","RWTabs_Item_LI_C_B","RWTabs_Item_LI_C_H","RWTabs_Item_LI_C_A","RWTabs_Item_RI_C_B","RWTabs_Item_RI_C_H","RWTabs_Item_RI_C_A","RW_Tabs_C_BC"
			];
			// Color Type
			switch ($GlobalProporties['RWTabs_C_Type']) {
				case 'color':
					$RW_Colors_Array[] = 'RWTabs_C_Col_F';
					break;
				case 'gradient':
				case 'gradient-top':
					$RW_Colors_Array[] = 'RWTabs_C_Col_F';
					$RW_Colors_Array[] = 'RWTabs_C_Col_S';
					break;
			}

		}elseif ($RW_Build_Type == "tab") {
			// Tab Type
			switch (sanitize_text_field($GlobalProporties['RWTabs_Type'])) {
				case 'horizontal':
					$RW_Options_Array["RWTabs_M_Pos"] = ['flex-start','center','flex-end'];
					$RW_Options_Array["RWTabs_M_Wrap"] = ["nowrap","wrap"];
					$RW_Numbers_Array["RWTabs_M_Height"] = ["min" => 50, "max" => 200];
					$RW_Numbers_Array["RWTabs_M_Gap"] = ["min" => 0, "max" => 15];
					break;
				case 'vertical':
					$RW_Options_Array["RWTabs_M_Pos"] = ['row','row-reverse'];
					$RW_Numbers_Array["RWTabs_M_Width"] = ["min" => 50, "max" => 300];
					break;
				default:
					wp_send_json_error('No valid option tab type');
					break;
			}
			$RW_Numbers_Array["RWTabs_Item_IconSize"] = ["min" => 8, "max" => 72];
			if (count($TabsProporties) == 0) {
				wp_send_json_error('No tabs found');
			}else {
				foreach ($TabsProporties as $key => $value) {
					$TabsProporties[$key]['Tabs_Subtitle'] =  sanitize_text_field($value['Tabs_Subtitle']);
					$TabsProporties[$key]['Tabs_Icon'] =  sanitize_text_field($value['Tabs_Icon']);
					$RW_Tabs_Textarea = sanitize_textarea_field($value['Tabs_Content']);
					$RW_Tabs_Textarea = str_replace("[", "&#91;",$RW_Tabs_Textarea);
					$RW_Tabs_Textarea = str_replace( "]", "&#93;",$RW_Tabs_Textarea);
					$TabsProporties[$key]['Tabs_Content'] =  $RW_Tabs_Textarea;
					$TabsProporties[$key]['Tabs_Special_Color'] =  sanitize_text_field($value['Tabs_Special_Color']);
					$TabsProporties[$key]['Tabs_Special_Color_B'] = sanitize_text_field($value['Tabs_Special_Color_B']);
					$TabsProporties[$key]['Tabs_Special_Color_H'] = sanitize_text_field($value['Tabs_Special_Color_H']);
					$TabsProporties[$key]['Tabs_Special_Color_A'] = sanitize_text_field($value['Tabs_Special_Color_A']);
					$TabsProporties[$key]['Tabs_Special_Bgc'] =  "off";
					$TabsProporties[$key]['Tabs_Special_Bgc_B'] = "";
					$TabsProporties[$key]['Tabs_Special_Bgc_H'] = "";
					$TabsProporties[$key]['Tabs_Special_Bgc_A'] = "";
					$TabsProporties[$key]['Tabs_Special_Img'] = "off";
					$TabsProporties[$key]['Tabs_Special_Img_Link'] = "";
					$counts_of_tab++;
				}
			}
			$RW_Options_Array["RWTabs_C_Anim"]  = ["","Random","Scale","FadeUp","FadeDown","FadeLeft","FadeRight","SlideUp","SlideDown","SlideLeft","SlideRight","ScrollDown","ScrollUp","ScrollRight","ScrollLeft","Bounce","BounceLeft","BounceRight","BounceDown","BounceUp","HorizontalFlip","VerticalFlip","RotateDownLeft","RotateDownRight","RotateUpLeft","RotateUpRight","TopZoom","BottomZoom","LeftZoom","RightZoom"];
			$RW_Options_Array["RWTabs_Mobile"]  = ['accordion','tab'];
			$RW_Options_Array["RWTabs_Type"]  = ['horizontal','vertical'];
			$RW_Colors_Array = [
				"RWTabs_M_Bgc","RWTabs_M_BC","RWTabs_Item_Col_B","RWTabs_Item_Col_H","RWTabs_Item_Col_A","RWTabs_Item_Bgc_B","RWTabs_Item_Bgc_H","RWTabs_Item_Bgc_A","RW_Tabs_C_BC"
			];
			// Color Type
			switch ($GlobalProporties['RWTabs_C_Type']) {
				case 'color':
					$RW_Colors_Array[] = 'RWTabs_C_Col_F';
					break;
				case 'gradient':
					$RW_Colors_Array[] = 'RWTabs_C_Col_F';
					$RW_Colors_Array[] = 'RWTabs_C_Col_S';
					break;
			}
		}


		foreach ($RW_Options_Array as $key => $value) {
			if ( in_array($GlobalProporties[$key],$value) ) {
				$GlobalProporties[$key] = sanitize_text_field($GlobalProporties[$key]);
			}else{
				wp_send_json_error('No valid option '.$key);
			}	
		}
		foreach ($RW_Colors_Array as $key => $value) {
			if ($GlobalProporties[$value] == '') {
				wp_send_json_error('No valid color '.$value);
			}else{
				$GlobalProporties[$value] = sanitize_text_field($GlobalProporties[$value]);
			}
		}
		foreach ($RW_Numbers_Array as $key => $value) {
			if (RW_Tabs_Sanitize_Range_Num($GlobalProporties[$key],$value['min'],$value['max']) == false) {
				wp_send_json_error('No valid number '.$key);
			}else{
				$GlobalProporties[$key] = sanitize_text_field($GlobalProporties[$key]);
			}			
		}


		$BaseProporties = json_decode(stripslashes($_POST['BaseProporties']),true);
		$BaseProporties['RWTabs_TabsCount'] = $counts_of_tab;
		$BaseProporties['RWTabs_Name'] = sanitize_text_field($BaseProporties['RWTabs_Name']);
		$BaseProporties['RWTabs_Theme_id'] = sanitize_text_field($BaseProporties['RWTabs_Theme_id']);
		$BaseProporties['RWTabs_Short_id'] = sanitize_text_field($BaseProporties['RWTabs_Short_id']);
		$BaseProporties['RWTabs_TNS'] = sanitize_text_field($BaseProporties['RWTabs_TNS']);
		

		global $wpdb;
		$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
		$RW_Tabs_Check_Query = $wpdb->get_row($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id = %d", (int) $BaseProporties['RWTabs_Short_id']));
		$RWTabs_Time = date("d.m.Y h:i:sa");
		if ($RW_Tabs_Check_Query) {
			$Response_Message = $wpdb->query($wpdb->prepare("UPDATE $RWTabs_Manager_Table set Tabs_Name=%s, SubTitles_Count=%d, Tabs_Fields=%s, Tabs_Props= %s, updated_at = %s  WHERE id=%d", $BaseProporties['RWTabs_Name'], $BaseProporties['RWTabs_TabsCount'],json_encode($TabsProporties,true),json_encode($GlobalProporties,true),$RWTabs_Time,$BaseProporties['RWTabs_Short_id']));
			if (!$Response_Message) {
				wp_send_json_error('Query Error');
			}else{
				wp_send_json_success($BaseProporties['RWTabs_Short_id']);
			}
			
		}else{
			$Response_Message = $wpdb->query($wpdb->prepare("INSERT INTO $RWTabs_Manager_Table (id,Tabs_Name,Tabs_Theme,SubTitles_Count,Tabs_Fields,Tabs_Props,Tabs_TNS,created_at,updated_at) VALUES (%d, %s, %d, %d, %s, %s, %s, %s, %s)",'',$BaseProporties["RWTabs_Name"],$BaseProporties["RWTabs_Theme_id"], $BaseProporties['RWTabs_TabsCount'],json_encode($TabsProporties,true),json_encode($GlobalProporties,true),$BaseProporties['RWTabs_TNS'],$RWTabs_Time,$RWTabs_Time ));
			if (!$Response_Message) {
				wp_send_json_error('Query Error');
			}else{
				wp_send_json_success($wpdb->insert_id);
			}
		}
		wp_die();
	} 
?>