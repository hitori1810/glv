<?php

class ContactsViewEdit extends ViewEdit {

    function ContactsViewEdit(){
        parent::ViewEdit();
    }

    function preDisplay(){       
        parent::preDisplay();
    }
    public function display()
    {
        global $beanFiles, $current_user;

        $selectSaintHtml = $this->getSaintOptions("saint", $this->bean->saint);
        $selectParent1SaintHtml = $this->getSaintOptions("guardian_saint_1", $this->bean->guardian_saint_1);
        $selectParent2SaintHtml = $this->getSaintOptions("guardian_saint_2", $this->bean->guardian_saint_2);
        
        $this->ss->assign("SELECT_SAINT_HTML", $selectSaintHtml);
        $this->ss->assign("SELECT_PARENT_1_SAINT_HTML", $selectParent1SaintHtml);
        $this->ss->assign("SELECT_PARENT_2_SAINT_HTML", $selectParent2SaintHtml);
        
        parent::display();
    }

    function getSaintOptions($elementId, $selectedId){
        $html = "";
        $saintOptions = getSaintListOptions();
        array_unshift($saintOptions, "Chưa chọn");

        $html = "";
        $html .= "<select id='$elementId' name='$elementId'>";
        foreach($saintOptions as $key => $value){
            $selected = $selectedId == $key ? "selected" : "";

            $html .= "<option $selected value='$key'>$value</option>";    
        }

        $html .= "</select>";

        return $html;    
    }
}