<?php

class ProspectsViewEdit extends ViewEdit {

    function ProspectsViewEdit(){
        parent::ViewEdit();
    }

    function preDisplay(){
        parent::preDisplay();
    }
    public function display()
    {
        $team_id 	= $GLOBALS['current_user']->team_id;

        $_type = $GLOBALS['db']->getOne("SELECT team_type FROM teams WHERE id = '{$team_id}'");
        $q1 = "SELECT DISTINCT
        IFNULL(campaigns.id, '') primaryid,
        IFNULL(campaigns.name, '') name,
        IFNULL(campaigns.lead_source, '') source
        FROM
        campaigns
        INNER JOIN
        team_sets_teams tst ON tst.team_set_id = campaigns.team_set_id
        AND tst.deleted = 0
        INNER JOIN
        teams ts ON ts.id = tst.team_id AND ts.deleted = 0
        AND ts.id = '{$this->bean->team_id}'
        WHERE
        (((campaigns.end_date >= '".date('Y-m-d')."')))
        AND campaigns.deleted = 0
        ORDER BY name ASC";
        $campaignList = $GLOBALS['db']->fetchArray($q1);
        $campArr = array();
        foreach($campaignList as $key => $camp){
            if(empty($camp['source']))
                $camp['source'] = 'Other';
            $campArr[$camp['source']][$camp['primaryid']] = $camp['name']; 
        }
        //generate Lead Source
        $html_source = '<select title="'.translate('LBL_LEAD_SOURCE').'" style="width: 40%;" name="lead_source" id="lead_source">';
        foreach($GLOBALS['app_list_strings']['lead_source_list'] as $key => $value){
            $sel = ($this->bean->lead_source == $key) ? 'selected' : '';
            $html_source .= "<option $sel value='$key' json='".json_encode($campArr[$key], JSON_UNESCAPED_UNICODE )."'>$value</option>";
        }
        $html_source .= '</select>';

        $html_source .= '<select title="'.translate('LBL_CAMPAIGN').'" style="width: 30%; margin-left: 4px; display:none;" name="campaign_id" id="campaign_id">';
        $html_source .= "<option value=''>-none-</option>";
        if(!empty($this->bean->campaign_id))
            $html_source .= "<option selected value='{$this->bean->campaign_id}'>{$this->bean->campaign_name}</option>";    
            
        $html_source .= '</select>';
        $this->ss->assign('lead_source', $html_source);


        $this->ss->assign('NATIONALITY', $html);
        if(ACLController::checkAccess('J_Marketingplan', 'import', true))
            $this->ss->assign('is_role_mkt', '1');
        else
            $this->ss->assign('is_role_mkt', '1');
        //End: Generate Lead Source

        $this->bean->assigned_user_name = get_assigned_user_name($this->bean->assigned_user_id);

        if(!empty($this->bean->campaign_id) && empty($this->bean->campaign_name))
            $this->bean->campaign_id = '';

        if(!empty($this->bean->j_school_prospects_1j_school_ida) && empty($this->bean->j_school_prospects_1_name))
            $this->bean->j_school_prospects_1j_school_ida = '';
        //Generate School
        if(!empty($this->bean->j_school_prospects_1j_school_ida)){
            $school = BeanFactory::getBean('J_School',$this->bean->j_school_prospects_1j_school_ida);
            $school->name = $school->level.': '.$school->name;
            if(!empty($school->address_address_street)){
                $school->name .= " ({$school->address_address_street})";
            }
            $this->bean->j_school_prospects_1_name = $school->name;
        }

        $color = '';
        switch($this->bean->status) {
            case 'New':
                $color = "textbg_green";
                break;
            case 'In Process':
                $color = "textbg_blue";
                break;
            case 'Dead':
                $color = "textbg_black";
                break;
            case 'Converted':
                $color = "textbg_red";
                break;
        }
        $this->ss->assign('STATUS',"<span class='$color'>{$this->bean->status}</span>");
        parent::display();
    }
}

?>