<?php
    require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');
    class SugarFieldDob extends SugarFieldBase {

        function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
            $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);        
            return $this->fetch($this->findTemplate('EditView'));      
        }
        function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
            $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
            return $this->fetch($this->findTemplate('DetailView'));
        }
        public function getListViewSmarty($parentFieldArray, $vardef, $displayParams, $col){
            $f_day = $vardef['key'].'_day';
            $f_month = $vardef['key'].'_month';
            $f_year = $vardef['key'].'_year';
            $sql = "SELECT $f_day, $f_month, $f_year FROM ".strtolower($_REQUEST['module'])." WHERE id = '{$parentFieldArray['ID']}'";
            $row = $GLOBALS['db']->fetchByAssoc($GLOBALS['db']->query($sql));
            $v_month = $GLOBALS['app_list_strings']['month_options'][$row[$f_month]];
            
            $this->ss->assign('f_dob', $row[$f_day].' '.$v_month.' '.$row[$f_year]); 
            return $this->fetch($this->findTemplate('ListView'));
        }
    }
?>

