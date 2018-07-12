<?php
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

$viewdefs['ProductCategories']['EditView'] = array(
    'templateMeta' => array('form' => array('buttons'=>array('SAVE', 
                                            array('customCode' => '<input class="button" type="submit" value="{$APP.LBL_SAVE_NEW_BUTTON_LABEL}" onclick="this.form.action.value=\'Save\'; this.form.isDuplicate.value=\'true\'; this.form.return_action.value=\'EditView\'; return check_form(\'EditView\');" title="{$APP.LBL_SAVE_NEW_BUTTON_TITLE}"/>')
                                            )),
                            'maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'), 
                                            array('label' => '10', 'field' => '30')
                                            ),
    'javascript' => '<script language="javascript" type="text/javascript">' .
    'function get_popup() {ldelim}
		var parent_name = document.EditView.parent_name.value;
    	var parent_id = document.EditView.parent_id.value;
		var button_query = "index.php?module=ProductCategories&action=Popup&html=Popup_picker&tree=CatCat&form=EditView" +"&parent_name=" + parent_name + "&parent_category_id=" + parent_id ;
 		var button_params = "width=600,height=400,resizable=1,scrollbars=1";
		window.open(button_query + "&query=true", "Test", button_params);
	{rdelim}</script>',
),
 'panels' =>array (
  'default' => array (
  
    array (
      array('name'=>'name', 'displayParams'=>array('required'=>true)),
    ),
  
      
    array (
       array('name'=>'parent_id', 
             'customCode'=>'<input name="type" type="hidden" value="{$fields.type.value}">' .
             		       '<input name="parent_name" readonly type="text" value="{$fields.parent_name.value}">' .
             		       '<input name="parent_id" type="hidden" value="{$fields.parent_id.value}">' .
             		       '<input class="button" type="button" onclick="return get_popup();" language="javascript" name="button" value="{$APP.LBL_SELECT_BUTTON_LABEL}" tabindex="2" title="{$APP.LBL_SELECT_BUTTON_TITLE}"/>' .
                           '<input class="button" type="button" value="{$APP.LBL_CLEAR_BUTTON_LABEL}" name="button" onclick="this.form.parent_name.value = \'\'; this.form.parent_id.value = \'\'" language="javascript" title="{$APP.LBL_CLEAR_BUTTON_TITLE}"/>'
             ),
    ),
    
    
    array (
      array('name'=>'description'),
    ),
    
    array (
       array('name'=>'list_order', 
             'displayParams'=>array('size'=>5, 'postCode'=>'&nbsp;{$MOD.NTC_LIST_ORDER}', 'required'=>true)),
    ),
  ),
)


);
?>