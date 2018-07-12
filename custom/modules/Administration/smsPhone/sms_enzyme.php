<?php
/*
 * This class consolidates the functions needed for SMS
 * specially those that are reusable
 */
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

class sms_enzyme {
	
	var $module;
	var $module_sing;
	var $mod_bean_files;
	
	function __construct($mod=NULL) {
		if (!is_null($mod) and $mod!='Home') {
			$this->module = $mod;
			$this->set_variables();
		}
	}
	
	private function set_variables () {
		if ($this->module != NULL and array_key_exists($this->module,$GLOBALS["beanList"])) {
			$this->module_sing = $GLOBALS["beanList"][$this->module];
			$this->mod_bean_files = $GLOBALS["beanFiles"][$this->module_sing];
			return true;
		} else {
			return false;
		}
	}

	function get_custom_phone_field() {
		$this->set_variables();
		$path = "./custom/Extension/modules/{$this->module}/";
		$file = "SMSEnableFields.php";
		$result = NULL;
		if (file_exists($path.$file)) {
			include($path.$file);
			$object = $this->module_sing=='aCase' ? 'Case' : $this->module_sing;
			if (!empty($smsPrimaryField)) {
				$result = $smsPrimaryField; 
			}  
		}  
		return $result;  
	} 
	function get_modules_with_sms() {
		$this->set_variables();
		$arr = array();
		$path = "./custom/Extension/modules/{$this->module}/";
		$file = "SMSEnableFields.php";
		if (file_exists($path.$file)) {
			include($path.$file);
			if (!empty($smsEnableFields)) $arr[] = $this->module_sing;
		} 
		return $arr;
	}
 
	function load_module_fields($identifier='', $holder='select') {
		$html = "";
		if ($this->set_variables()) {
			$local_mod_strings = return_module_language('en_us', $this->module); 
			$object = new $this->module_sing();
			if($holder=='select') {	#load inside a <select> element
				$html .= "<select name='{$identifier}' id='{$identifier}'  style='width:100%;'>";
				foreach($object->field_name_map as $fn) {
					if ( !empty($fn["vname"]) && !empty($local_mod_strings[$fn["vname"]]) ) {	// plot only the fields with vname
						$html .= "<option value=\"{$fn['name']}\">" . str_replace(":", "", $local_mod_strings[$fn["vname"]]) .  "</option>"; 
					}
				}
				$html .= "</select>";
			} 
		}
		return  $html;
	}
	
	function save_sms_macro($module, $field, $macro, $macro_to_remove='') { 
		if (strpos($macro,"%1")===false and $macro_to_remove=='') {
			echo "Macro string must contain %1.";
			return false;	// the string must contain %1
		}
		
		require_once('modules/Studio/DropDowns/DropDownHelper.php');
		$params = array();
		$params['dropdown_name'] = "sms_mod_macro_list";
		$count = 0;  
		
		//if (!empty($GLOBALS["app_list_strings"]["sms_mod_macro_list"])) {
			$mod_wd_macro = $GLOBALS["app_list_strings"]["sms_mod_macro_list"];
			foreach($mod_wd_macro as $k => $v) {
				if ($k != $macro_to_remove) {
					$params['slot_'.$count] = $count;
					$params['key_'.$count] = $k;
					$params['value_'.$count] = $v;
					$count++;
				}  
			} 
			if ($module!='' and $field!='') {
				$params['slot_'.$count] = $count;
				$params['key_'.$count] = $module;
				$params['value_'.$count] = $field . ":" . $macro;
			}
			DropDownHelper::saveDropDown($params); 
			return true;
		//} else {
		//	return false;
		//}
		
	}
	private function slash_special_chars($string) {
		$s = str_split($string);
		foreach($s as  $index => $char) { 
			if (preg_match("/[^A-Za-z0-9]/", $char)) {
				if($char == " ") 
					$s[$index] = "\s";
				elseif($char != '') 
					$s[$index] = "\\".$char;
			}  
		} 
		return implode("", $s);
	}
	
	function parse_sms_macro($msg) {  
	
		//$keyword = strtolower(substr($msg, 0, strpos($msg, " ")));
		$keyword = substr($msg, 0, strpos($msg, " "));
		$code = '';
		$field = '';
		$module = '';

		# strip off keyword
		$msg = ltrim($msg, $keyword);
		$msg = trim($msg);
		
		# get the rest of the message;
		$actual_msg = $msg;

		# get first word and check if it's a macro 
		//$user_macro = strtolower(substr($msg, 0, strpos($msg, " ")));
		$user_macro = substr($msg, 0, strpos($msg, " "));
  
		if(!empty($GLOBALS["app_list_strings"]["sms_mod_macro_list"])) {
				$mod_wd_macro = $GLOBALS["app_list_strings"]["sms_mod_macro_list"];
				if (sizeof($mod_wd_macro)) {
						# find match macro
						foreach($mod_wd_macro as $mod => $v) {
								# get predefined macro string format
								$macro = strtolower(substr($v, strpos($v, ":")+1));
								$field = strtolower(substr($v, 0, strpos($v, ":")));
								# break left and right string of the identifier data or code
								$a = array(
										'left' => substr($macro, 0, strpos($macro, "%1")),
										'right' => substr($macro, strpos($macro, "%1")+2),
								);
								$pattern = "/^" . $this->slash_special_chars($a['left']) . "[0-9]+" . $this->slash_special_chars($a['right']) . "/";
								if ((int)preg_match($pattern, $user_macro)) {
										$module = $mod;
										$code = rtrim(ltrim($user_macro, $a['left']), $a['right']);
										break;
								}
						}
				}
		}

		//return array("module" => $module, "code" => $code, "field" => $field, "macro" => $user_macro, "message" => $actual_msg);
		$chunks = array(
			"module" => $module, 
			"code" => $code, 
			"field" => $field, 
			"message" => $actual_msg,
			"macro" => $user_macro,
			);
		return $chunks;
 
	}

	
	function delete_button_attributes($version) { 
		$v = explode(".", $version);
		if ($v[0] == '6') {
			$src = "themes/Sugar/images/delete_inline.png";
			$class = "id-ff-remove";
		} else {
			$src = "themes/Sugar/images/delete_inline.gif";
			$class = "removeButton0";
		} 
		return array('src' => $src, 'class' => $class);
	}
	
}

?>
