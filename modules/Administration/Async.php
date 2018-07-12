<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

/*********************************************************************************

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 *********************************************************************************/

require_once("include/entryPoint.php");

if (!is_admin($GLOBALS['current_user'])) {
    sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);
}

$json = getJSONObj();
$out = "";

switch($_REQUEST['adminAction']) {
	///////////////////////////////////////////////////////////////////////////
	////	REPAIRXSS
	case "refreshEstimate":
		include("include/modules.php"); // provide $moduleList
        $target = '';
        if (!empty($_REQUEST['bean'])) {
            $target = $_REQUEST['bean'];
        }

        $count = 0;
		$toRepair = array();
		
		if($target == 'all') {
			$hide = array('Activities', 'Home', 'iFrames', 'Calendar', 'Dashboard');
		
			sort($moduleList);
			$options = array();
			
			foreach($moduleList as $module) {
				if(!in_array($module, $hide)) {
					$options[$module] = $module;
				}
			}

			foreach($options as $module) {
				if(!isset($beanFiles[$beanList[$module]]))
					continue;
				
				$file = $beanFiles[$beanList[$module]];
				
				if(!file_exists($file))
					continue;
					
				require_once($file);
				$bean = new $beanList[$module]();
				
				$q = "SELECT count(*) as count FROM {$bean->table_name}";
				$r = $bean->db->query($q);
				$a = $bean->db->fetchByAssoc($r);
				
				$count += $a['count'];
				
				// populate to_repair array
				$q2 = "SELECT id FROM {$bean->table_name}";
				$r2 = $bean->db->query($q2);
				$ids = '';
				while($a2 = $bean->db->fetchByAssoc($r2)) {
					$ids[] = $a2['id'];
				}
				$toRepair[$module] = $ids;
			}
		} elseif(in_array($target, $moduleList)) {
			require_once($beanFiles[$beanList[$target]]);
			$bean = new $beanList[$target]();
			$q = "SELECT count(*) as count FROM {$bean->table_name}";
			$r = $bean->db->query($q);
			$a = $bean->db->fetchByAssoc($r);
			
			$count += $a['count'];
			
			// populate to_repair array
			$q2 = "SELECT id FROM {$bean->table_name}";
			$r2 = $bean->db->query($q2);
			$ids = '';
			while($a2 = $bean->db->fetchByAssoc($r2)) {
				$ids[] = $a2['id'];
			}
			$toRepair[$target] = $ids;
		}
		
		$out = array('count' => $count, 'target' => $target, 'toRepair' => $toRepair);
	break;
	
	case "repairXssExecute":
		if(isset($_REQUEST['bean']) && !empty($_REQUEST['bean']) && isset($_REQUEST['id']) && !empty($_REQUEST['id'])) {
			include("include/modules.php"); // provide $moduleList
			$target = $_REQUEST['bean'];
			require_once($beanFiles[$beanList[$target]]);
			
			$ids = $json->decode(from_html($_REQUEST['id']));
			$count = 0;
			foreach($ids as $id) {
				if(!empty($id)) {
					$bean = new $beanList[$target]();
					$bean->retrieve($id,true,false);
					$bean->new_with_id = false;
					$bean->save(); // cleanBean() is called on save()
					$count++;
				}
			}
			
			$out = array('msg' => "success", 'count' => $count);
		} else {
			$mod_strings = return_module_language($GLOBALS['current_language'], 'Administration');
			$out = array('msg' => $mod_strings['LBL_REPAIRXSSEXECUTE_FAILED']);
		}
	break;
	////	END REPAIRXSS
	///////////////////////////////////////////////////////////////////////////
	
	default:
		die();
	break;	
}

$ret = $json->encode($out, true);
echo $ret;
