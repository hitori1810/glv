<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');



    class J_TeachercontractViewDetail extends ViewDetail {
        function display() {
            $sql="SELECT day_off FROM j_teachercontract WHERE deleted=0 AND id='".$this->bean->id."'";
            $result = unencodeMultienum(htmlspecialchars_decode($GLOBALS['db']->getone($sql)));
            foreach($result as $key =>$value)
            {
                $html .='<label>'.$value.' &nbsp</label>';
            }
            $this->ss->assign('DAYOFF', $html);
            $this->ss->assign('name', '<span class="textbg_orange">'.$this->bean->name.'</span>');
            parent::display();
        }
    }
?>