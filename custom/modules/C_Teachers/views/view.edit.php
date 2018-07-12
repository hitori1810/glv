<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class C_TeachersViewEdit extends ViewEdit
{

    public function display()
    {
        if($_POST['isDuplicate'] == 'true'){
            $this->bean->teacher_id         = translate('LBL_AUTO_GENERATE','Accounts');
        }else{
            if(empty($this->bean->id))
                $this->bean->teacher_id     = translate('LBL_AUTO_GENERATE','Accounts');
        }
        parent::display();
    }
}
?>
