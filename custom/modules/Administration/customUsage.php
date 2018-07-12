<?php

	require_once('custom/modules/C_SMS/SMS/sms.php');  
	$sms = new sms();
	$sms->retrieve_settings();
	if (empty($sms->params['sms_instance_id']) or empty($sms->params['domain_name'])) {
		header('./index.php?module=Administration&action=smsProvider');
	}

	echo "<script type=\"text/javascript\" language=\"JavaScript\" src=\"custom/modules/Administration/customUsage/javascript/customUsage.js\"></script>";  
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"custom/modules/Administration/customUsage/style/customPhone.css\" />";
	if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
	require_once('modules/Administration/Common.php'); 
	require_once('modules/Studio/DropDowns/DropDownHelper.php');
	require_once('ModuleInstall/ModuleInstaller.php');
	 
	$module_name = "C_SMS";
	$module_name_lower = "c_sms"; 
	
	if(isset($_GET["fields"]) && !empty($_GET["fields"])) {
		global $app_list_strings;
		//check and remove existing logic_hook.
		if(isset($app_list_strings[$module_name_lower."_module_selected_list"])&&!empty($app_list_strings[$module_name_lower."_module_selected_list"])){
			foreach($app_list_strings[$module_name_lower."_module_selected_list"] as $module){
				if(in_array($module, $app_list_strings["moduleList"])){
					$directory="custom/modules/".$module."/";
					if(file_exists("custom/modules/".$module."/logic_hooks.php")){
						$content=file("custom/modules/".$module."/logic_hooks.php");
						$logic_hook= sugar_fopen($directory."logic_hooks.php", "w+");
						$countline=count($content);
						$lines="<?php \n";
						for($i=1;$i<$countline;$i++){
							if(strstr($content[$i],'customlogic.php')){
								unset($content[$i]);
							}else{
								$lines.= $content[$i];
							}
						} 
						fwrite( $logic_hook, $lines);
						fclose( $logic_hook ); 
					}
					 
				}
			}
		}//end if isset dropdown.
	 
		$modules = $GLOBALS["app_list_strings"]['moduleList']; 
		asort($modules);
		$installer= new ModuleInstaller();
		$params= array();
		$params['dropdown_name'] = $module_name_lower.'_module_selected_list';
		$count=0;
		$tabledicext="";
		foreach($_GET["fields"] as $key) {
			$this->modules= $key;
			if(array_key_exists($key ,$modules)){ 
				$params['slot_' . $count]=$count;
				$params["key_".$count]=$key;
				$params["value_".$count]=$GLOBALS["app_list_strings"]['moduleList'][$key];
			
				//write vardef files.
				$object = $GLOBALS['beanList'][$key];
				if ($object == 'aCase') $object = 'Case';
				$the_string =   "<?php\n" . "// created: " . date('Y-m-d H:i:s') . "\n";
				$the_string .= "\$dictionary['{$object}']['fields']['".strtolower($object)."_{$module_name_lower}'] = array (
								  'name' => '".strtolower($object)."_{$module_name_lower}',
									'type' => 'link',
									'relationship' => '".strtolower($object)."_{$module_name_lower}',
									'source'=>'non-db',
									'vname'=>'LBL_".strtoupper($module_name)."',
							)". ";\n?>\n";
				$dir = 'custom/Extension/modules/'.$key.'/Ext/Vardefs/';
				$the_file= $dir.'_custom_usage_module_vardefs.php';
				if(!is_dir($the_file))mkdir_recursive($dir);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $the_string);
					fclose( $fh );
				}
				
				//write vardef files for izeno_Usage.
				$object= $GLOBALS['beanList'][$key];
				if ($object == 'aCase') $object = 'Case';
				$the_string = "<?php\n" . "// created: " . date('Y-m-d H:i:s') . "\n";
				$the_string .= "\$dictionary['{$module_name}']['fields']['".strtolower($object)."_{$module_name_lower}'] = array (
									'name' => '".strtolower($object)."_{$module_name_lower}',
									'type' => 'link',
									'relationship' => '".strtolower($object)."_{$module_name_lower}',
									'source'=>'non-db',
									'vname'=>'LBL_".strtoupper($module_name)."',
							);\n ?>\n";
				$dir ='custom/Extension/modules/'.$module_name.'/Ext/Vardefs/';
				$the_file= $dir.'_custom_usage_module_vardefs.php';
				if(!is_dir($the_file))mkdir_recursive($dir);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $the_string);
					fclose( $fh );
				}

				//write metadata
				$metastring= "<?php \n" . "// created: " . date('Y-m-d H:i:s') . "\n\$dictionary['".strtolower($object)."_{$module_name_lower}'] =  array(
							  'relationships' => array (
								'".strtolower($object)."_{$module_name_lower}' => 
								array (
								'lhs_module'=> '".$key."', 
								'lhs_table'=> '".strtolower($key)."', 
								'lhs_key' => 'id',
								'rhs_module'=> '{$module_name}', 
								'rhs_table'=> '{$module_name_lower}', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'{$key}',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>";
				$dir = 'custom/metadata/';
				$the_file = $dir.$module_name.'_'.strtolower($key).'.php';
				if(!is_dir($the_file)) mkdir_recursive($dir);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $metastring);
					fclose( $fh );
				}
				//write language file
				$language= "<?php \n" . " // created: ". date('Y-m-d H:i:s') ."\n
							\$mod_strings['LBL_".strtoupper($module_name)."'] = 'SMS';
							?>";
				$dir ='custom/Extension/modules/'.$key.'/Ext/Language/';
				$the_file= $dir.'en_us._custom_usage_module_lang.php';
				if(!is_dir($the_file))mkdir_recursive($dir);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $language);
					fclose( $fh );
				}
				//write layoutdef;
				$layout = "<?php \n" .
							" // created: ". date('Y-m-d H:i:s') . "\n
							\$layout_defs['{$key}']['subpanel_setup']['".strtolower($object)."_".$module_name_lower."'] = array (
							  'order' => 250,
							  'module' => '{$module_name}',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_".strtoupper($module_name)."',
							  'get_subpanel_data' => '".strtolower($object)."_".$module_name_lower."',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							?>";
				$dir ='custom/Extension/modules/'.$key.'/Ext/Layoutdefs/';
				$the_file= $dir.'_custom_usage_module.php';
				if(!is_dir($the_file))mkdir_recursive($dir);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $layout);
					fclose( $fh );
				}
						
				//write table dictionary
				$tabledic= "<?php \n" .
						" // created: ". date('Y-m-d H:i:s') ."\n
						include('custom/metadata/".$module_name."_".strtolower($key).".php');	\n			
						?>";
				$dir_table='custom/Extension/application/Ext/TableDictionary/';
				$the_file= $dir_table.$module_name."_".strtolower($key).".php";
				if(!is_dir($the_file))mkdir_recursive($dir_table);
				if( $fh = @sugar_fopen( $the_file, 'wb' ) ) {
					fwrite( $fh, $tabledic);
					fclose( $fh );
				}
				$tabledicext .= "include('custom/metadata/".$module_name."_".strtolower($key).".php');\n";
				
				//write logic hook
		   
			} elseif($key=='-BLANK-'){
				$params['slot_' . $count]=$count;
				$params["key_".$count]=$key;
				$params["value_".$count]=$key;
			}
			$count++;
		} // end of foreach
		
		$dir_tablecust='custom/application/Ext/TableDictionary/';
		$the_file= $dir_tablecust."tabledictionary.ext.php";	
		if(!is_dir($the_file)) mkdir_recursive($dir_tablecust);
		$content=file($the_file);
		if( $fg = fopen( $the_file, 'w+' ) ) {
			$lines="<?php \n";
			for($i=1;$i<count($content)-1;$i++){
				$lines .= $content[$i]."\n";
			}
			$lines .= $tabledicext."\n ?>";
			fwrite( $fg, $lines);
			fclose( $fg );       
		}
		DropDownHelper::saveDropDown($params);
		unset($_GET);
 
		echo "<div>Click Repair button to apply your customization.</div>";
		
		$ver = explode(".",$GLOBALS['sugar_config']['sugar_version']);
		if ($ver[0]=='6')
			echo "<input type='button' id='repair' class='button' value='Repair' onclick=\"window.location.href='./index.php?module=Administration&action=repair'\" />";
		else
			echo "<input type='button' id='repair' class='button' value='Repair' onclick=\"window.location.href='./index.php?module=Administration&action=repairSelectModule'\" />";
			
		//rebuild files
		//$installer->rebuild_vardefs();
		//$installer->rebuild_layoutdefs();
		//$installer->rebuild_relationships(); 
	
	} else {

		echo "<div class='moduleTitle'><h2><a href='index.php?module=Administration&action=index'>Administration</a><span class='pointer'>&raquo;</span>SMS & Modules Relationship</h2></div>";	// just a space

		$modules = $GLOBALS['moduleList']; 	
		asort($modules);
		echo "<div id='field_panels'></div>";
		echo "<div style='margin-bottom:5px;'><input type='button' id='save' class='button' value='Save' disabled='disabled'></div>";
		echo "<table id='mod_tbl' border='0' cellspacing='0' cellpadding='0' class='edit view'  width='100%' >";
		echo "<tr><th style='text-align:left;'>Move a module to the right panel to create a relationship with SMS</th></tr>"; 
 		echo "<tr><td>"; 
		echo "<div class='field_panel' id='original'>"; 
		foreach($modules as $mod) {
			if(!is_array($GLOBALS["app_list_strings"][$module_name_lower."_module_selected_list"])){
				$GLOBALS["app_list_strings"][$module_name_lower."_module_selected_list"] = array();
            }

			if(!array_key_exists($mod, $GLOBALS["app_list_strings"][$module_name_lower."_module_selected_list"])){
				echo "<div class=\"item\" id=\"{$mod}\" onclick=\"select(this);\" title=\"Click to select or deselect\">";
				echo $GLOBALS["app_list_strings"]["moduleList"][$mod];
				echo "</div>";
			}
		}
		echo "</div>";
		echo "<div class='mover'><div id='to_right' onclick=\"move_to('right');\">&raquo;</div><div id='to_left' onclick=\"move_to('left');\">&laquo;</div></div>"; 
		echo "<div class='field_panel' id='selection'>";
		echo "<div class='item' id=\"-BLANK-\" onclick='select(this);' title='Click to select or deselect' style=\"display:none;\">-BLANK-</div>";
		if(!empty($GLOBALS["app_list_strings"][$module_name_lower."_module_selected_list"])){
			foreach($GLOBALS["app_list_strings"][$module_name_lower."_module_selected_list"] as $k=>$v){
				if($k!='-BLANK-'){
					echo "<div class='item' id=\"{$k}\" onclick='select(this);' title='Click to select or deselect'>" . $v . "</div>";
				}
			}
		} 
		echo "</div></td></tr> </table>"; 	
	 
		echo "<div style='clear:both;'></div>"; 
	
	}
 
 
?>
