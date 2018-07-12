<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('custom/modules/C_SMS/SMS/sms.php');  
$sms = new sms();
$sms->retrieve_settings();
if (empty($sms->params['sms_instance_id']) or empty($sms->params['domain_name'])) {
	header('./index.php?module=Administration&action=smsProvider');
}

include_once("custom/modules/Administration/smsPhone/smsPhone.js.php");

if (isset($_GET['option'])) {

	# this part is the processor  
	
	$mod_key = $_GET["m"]; 
	$mod_key_sing = $GLOBALS["beanList"][$mod_key];
	$mod_bean_files = $GLOBALS["beanFiles"][$mod_key_sing];
	
	require_once($mod_bean_files);	 
	
	$mod = new $mod_key_sing();
	$path = "./custom/Extension/modules/{$mod_key}/Ext/Vardefs/";
	$file = "sms_fields_for_" . strtolower($mod_key) . ".php";
 	 
	if (isset($_GET['option'])) {
	
		switch($_GET['option']) {
		  
			# load all fields
			case "1":
	 			$local_mod_strings = return_module_language('en_us', $mod_key);
	   			$selected_fields = array();
	
	  			if (file_exists($path.$file)) {
	 				$handle = fopen($path.$file, "r");
	 				while (!feof($handle)) {
						$line = fgets($handle, filesize($path.$file));
						$object = $mod_key_sing=='aCase' ? 'Case' : $mod_key_sing; 
						if (preg_match("/.*dictionary\[\'" . $object . "\'\]\[\'fields\'\]\[\'.*\'\]\[\'type\'\].*/", $line)) {
							$str = str_replace("\$dictionary['" . $object . "']['fields']['", "", $line);
					        $tmp = explode("'", $str);
					        if (!in_array($tmp[0], $selected_fields)) array_push($selected_fields, $tmp[0]);
						}
					}
					fclose($handle);
	 			}
	 			
	 			echo "<div class='field_panel' id='original'>";
			 	foreach($mod->field_name_map as $fn) {
			 		if ( !empty($fn["vname"]) && !empty($local_mod_strings[$fn["vname"]]) ) {	// plot only the fields with vname 
	 		 			if (!in_array($fn['name'], $selected_fields)) {	// plot only unselected fields
		 		 			echo "<div class='item' id=\"{$fn['name']}\" onclick='select(this);' title='Click to select or deselect'>" .
		 		 					str_replace(":", "", $local_mod_strings[$fn["vname"]]) . 
		 		 				 "</div>";
	 		 			}
			 		}
	 		 	}
	 		 	echo "</div>";
	 		 	
	 		 	echo "<div class='mover'><div id='to_right' onclick=\"move_to('right');\">&raquo;</div><div id='to_left' onclick=\"move_to('left');\">&laquo;</div></div>";
	 		 	
				echo "<div class='field_panel' id='selection'>";
				foreach($mod->field_name_map as $fn) {
			 		if ( !empty($fn["vname"]) && !empty($local_mod_strings[$fn["vname"]]) ) {	// plot only the fields with vname 
	 		 			if (in_array($fn['name'], $selected_fields)) {	// plot only unselected fields
		 		 			echo "<div class='item' id=\"{$fn['name']}\" onclick='select(this);' title='Click to select or deselect'>" .
		 		 					str_replace(":", "", $local_mod_strings[$fn["vname"]]) . 
		 		 				 "</div>";
	 		 			}
			 		}
	 		 	}
				echo "</div>";
				
	 			break;
			
	 		# save selected phone fields
			case "2":		

				$res = false;

				if (!file_exists($path)) mkdir_recursive($path);
	 			if (isset($_GET["fields"])) {
					if (sizeof($_GET["fields"])) {
						$handle = fopen($path.$file, "w+");
						$content = "<?php\n";
						foreach ($_GET["fields"] as $field) {
							$object = $mod_key_sing=='aCase' ? 'Case' : $mod_key_sing;
							$content .= "\$dictionary['".$object."']['fields']['".$field."']['type'] = 'function';\n" . 
										"\$dictionary['".$object."']['fields']['".$field."']['function'] = array('name'=>'sms_phone', 'returns'=>'html', 'include'=>'custom/fieldFormat/sms_phone_fields.php');\n\n";
						}
						$content .= "\n?>";

						fwrite($handle, $content);
						fclose($handle); 
					}
				} else {
					// remove existing file
					if (file_exists($path.$file)) unlink($path.$file);
				}			
	 			
				$html  = "<div>Selected fields were successfully loaded for customization.</div>"; 
				$html .= "<div>To effect the changes, click <Strong>Repair</strong> button.<br><br>";
				$html .= "<input type='button' class='button' value='Repair' onclick=\"location.href='./index.php?module=Administration&action=repair'\">";
				$html .= "</div>";
				echo $html;
				
	 			break;
	 			
			default:
				echo "<div>Invalid URL</div>";
		} 
	}  
	
} else {

	# this will be the main interface
  
	echo "<div class='moduleTitle'><h2><a href='index.php?module=Administration&action=index'>Administration</a><span class='pointer'>&raquo;</span>Field Selector</h2></div>";	// just a space
	
	$modules = $GLOBALS["app_list_strings"]["c_sms_module_selected_list"];
	asort($modules);
	echo '<br/><br/>';
	echo "<div style='margin-left:-20px;display: inline !important;position: absolute;top: 100px;left: 40px;'><input type='button' id='save' class='button' value='Save' disabled='disabled' onclick='save_customization();'></div>";
    echo '<br/><br/>';
	echo "<div style='margin-bottom:5px;'><div>Select a related module</div>";
	echo "<select id='module' onchange=\"load_fields('panel');\"  style='min-width:100px;'>"; 
	foreach($modules as $mod => $label) {
		$key = ($mod=='-BLANK-') ? "" : $mod;
		$val = ($mod=='-BLANK-') ? "" : $label;  
		echo "<option value='{$key}'>" . $val . "</option>";
	}
	echo "</select></div>";
	echo "<p style='font-size:small;font-style:italic;font-weight:normal;'>You may need to set a relationship between the SMS module and the other modules. " .
			"Click <a href='./index.php?module=Administration&action=customUsage'>here</a> to set the relationship.</p>";
	echo "<table  border='0' cellspacing='0' cellpadding='0' class='edit view' width='100%' >";
	echo "<tr><th style='text-align:left;'>To configure a field as an sms number, move the field to the right panel</th></tr>"; 
	echo "<tr><td><div id='field_panels'><em>Please select a related module.</em></div></td></tr>";
	echo "</table>";    
	
}  	
?>
