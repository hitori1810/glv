<?php

class CampaignsViewEdit extends ViewEdit {

    function CampaignsViewEdit(){
        parent::ViewEdit();
    }

    function preDisplay(){
        parent::preDisplay();
    }
    public function display(){
        //generate Lead Source
        $html_source = '<select title="'.translate('LBL_LEAD_SOURCE').'" style="width: 40%;" name="lead_source" id="lead_source">';
        foreach($GLOBALS['app_list_strings']['lead_source_list'] as $key => $value){
            $sel = ($this->bean->lead_source == $key) ? 'selected' : '';
            $html_source .= "<option $sel value='$key' json='".json_encode($campArr[$key], JSON_UNESCAPED_UNICODE )."'>$value</option>";
        }
        $html_source .= '</select>';
        $this->ss->assign('lead_source', $html_source);


        parent::display();
    }
}