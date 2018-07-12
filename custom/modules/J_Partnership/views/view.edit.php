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


    class J_PartnershipViewEdit extends ViewEdit
    {
        public function __construct()
        {
            parent::ViewEdit();
            $this->useForSubpanel = true;
            $this->useModuleQuickCreateTemplate = true;
        }
        public function display()
        {
//            if(!isset($this->bean->id) || empty($this->bean->id)){
//                $schema_no = getHtmlAddRow('','',true);
//                $schema  = getHtmlAddRow('','',false);   
//            }
//            else 
//            {
//                $schema_no = getHtmlAddRow('','',true);
//                $this->bean->load_relationship('j_partnership_j_discount_1');
//                $rela= $this->bean->j_partnership_j_discount_1->getBeans(); 
//                if(count($rela)==''){
//                    $schema  = getHtmlAddRow('','',false); 
//                }
//                else{
//                    foreach ($rela as $re) { 
//                        $schema  .= getHtmlAddRow($re->id,$re->name,false); 
//                    } 
//                }    
//            }
//
//            $this->ss->assign('SCHEMA', $schema);
//            $this->ss->assign('SCHEMA_NO', $schema_no);
            parent::display();   
        }
    }

    // Generate Add row template
    function getHtmlAddRow( $discount_id, $discount_name, $showing){
        if($showing)                            
            $display = 'style="display:none;"';    
        $tpl_addrow  = "<tr class='row_tpl' $display  >";
        $tpl_addrow .= '<td nowrap align="center">
        <div><input name="discount_name[]" value="'.$discount_name.'" class="discount_name" type="text" style="margin-right: 10px;"><input name="discount_id[]" value="'.$discount_id.'"  class="discount_id" type="hidden"><button type="button" class="btn_choose_discount" onclick="clickChooseDiscount($(this))" ><img src="themes/default/images/id-ff-select.png"></button>
        <button type="button" name="btn_clr_discount_name" id="btn_clr_discount_name" onclick="clickClearDiscount($(this))"><img src="themes/default/images/id-ff-clear.png"></button><br><br><div>
        </td>';
        $tpl_addrow .= "<td align='center'><button type='button' class='btn btn-danger btnRemove'>Remove</button></td>";
        $tpl_addrow .= '</tr>';
        return $tpl_addrow;
}