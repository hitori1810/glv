<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class C_MembershipsViewEdit extends ViewEdit
{
    /**
    * @see SugarView::display()
    */
    public function display()
    {
        if($_POST['isDuplicate'] == 'true')
            $this->bean->name         = translate('LBL_AUTO_GENERATE','Accounts');
        else{
            if(empty($this->bean->id))
                $this->bean->name     = translate('LBL_AUTO_GENERATE','Accounts');
        }
        parent::display();
    }
}
?>
