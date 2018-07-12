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


class J_TeachercontractViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }
    public function display()
    {
        if(!isset($this->bean->id) || empty($this->bean->id)){
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Mon" value="Mon">'.translate('LBL_MON','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Tue" value="Tue">'.translate('LBL_TUE','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Wed" value="Wed">'.translate('LBL_WED','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Thu" value="Thu">'.translate('LBL_THU','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Fri" value="Fri">'.translate('LBL_FRI','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Sat" value="Sat">'.translate('LBL_SAT','J_Teachercontract').' &nbsp  </label>';
            $html .='<label id="ct_date"><input type="checkbox" name="dayoff[]" id="Sun" value="Sun">'.translate('LBL_SUN','J_Teachercontract').' </label>';
            $this->ss->assign('no_contract', '<span style="color:red;font-weight:bold"> Auto-generate </span>');
        }
        else
        {
            $sql="SELECT day_off FROM j_teachercontract WHERE deleted=0 AND id='".$this->bean->id."'";
            $result = unencodeMultienum(htmlspecialchars_decode($GLOBALS['db']->getone($sql)));
            $html .= $this->_loadDayOff($result);
        }
        $this->ss->assign('DAY_OFF', $html);

        if($_POST['isDuplicate'] == 'true'){
            $this->bean->name         = translate('LBL_AUTO_GENERATE','Accounts');
        }else{
            if(empty($this->bean->id))
                $this->bean->name     = translate('LBL_AUTO_GENERATE','Accounts');
        }

        parent::display();
    }

    private function _loadDayOff(array $selectDay) {
        $html = '';
        $days = array(
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat',
            'Sun',
        );
        foreach ($days as $day) {
            $html .= '<label id="ct_date"><input type="checkbox" name="dayoff[]" id="'.$day.'" value="'.$day.'" '.((in_array($day, $selectDay)) ? 'checked': '').'>'.translate('LBL_'.strtoupper($day),'J_Teachercontract').' &nbsp  </label>';
        }
        return $html;
    }
    //public function predisplay()
    //        {
    //            parent::predisplay();
    //            if(!isset($this->bean->id) || empty($this->bean->id)){
    //
    //                //$this->bean->no_contract = 'Auto-Generate';
    //            }
    //            else{
    //            }
    //
    //        }

}