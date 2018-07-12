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


    class ProductTemplatesViewEdit extends ViewEdit
    {
        public function __construct()
        {
            parent::ViewEdit();
            $this->useForSubpanel = true;
            $this->useModuleQuickCreateTemplate = true;
        }
        public function display()
        {
            $html = '';
            $html .= '<select name="Supplier" id="Supplier" >'; 
            if(!isset($this->bean->id) || empty($this->bean->id)){
                $sql = 'SELECT `name` FROM accounts WHERE type_of_account="Supplier"';
                $result = $GLOBALS['db']->query($sql);
                while($supplier = $GLOBALS['db']->fetchByAssoc($result))
                {
                    $html .= '<option value="'.$supplier['name'].'">'.$supplier['name'].'</option>';
                }

            }
            else
            {
                $sql = 'SELECT `name` FROM accounts WHERE type_of_account="Supplier"';
                $result = $GLOBALS['db']->query($sql);
                while($supplier = $GLOBALS['db']->fetchByAssoc($result))
                {
                    if($this->bean->supplier==$supplier['name']){
                        $html .= '<option value="'.$supplier['name'].'" selected>'.$supplier['name'].'</option>';  
                    }
                    else{
                        $html .= '<option value="'.$supplier['name'].'">'.$supplier['name'].'</option>'; 
                    }

                }

            }
            $html .= '</select>';
            $this->ss->assign('SUPPLIER', $html); 
            parent::display();   
        }


}