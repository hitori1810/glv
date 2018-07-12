<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_ClassViewDetail extends ViewDetail
{
    /**
    * @see SugarView::display()
    *
    * We are overridding the display method to manipulate the portal information.
    * If portal is not enabled then don't show the portal fields.
    */
    public function display()
    {
        $this->ss->assign("team_type", getTeamType($this->bean->team_id));
        global $timedate, $current_user;

        require_once("custom/include/_helper/junior_revenue_utils.php");
        require_once("custom/include/_helper/class_utils.php");
        $this->ss->assign('MOD', return_module_language($GLOBALS['current_language'], 'J_Class'));
        //DETAILVIEW LAYOUT
        //kind Of course
        $koc = '';
        $koc .='<label>'.$this->bean->koc_name.'</label>&nbsp &nbsp &nbsp  '.$GLOBALS['mod_strings']['LBL_LEVEL'].': <label>'.str_replace(',',', ',str_replace('^','',$this->bean->level)).'</label>';
        if(!empty($this->bean->modules)){
            $koc .='&nbsp; &nbsp; &nbsp;'.$GLOBALS['mod_strings']['LBL_MODULE'].': <label>'.$this->bean->modules.'</label>';
        }

        $this->ss->assign('KOC',$koc);

        // Display schedule
        $html = '';
        $short_schedule = json_decode(html_entity_decode($this->bean->short_schedule));
        foreach($short_schedule as $key => $value ){
            if($GLOBALS['current_language'] == 'vn_vn'){
                foreach($GLOBALS['app_list_strings']['week_frame_class_list'] as $labelKey => $label){
                    $key = str_replace($labelKey,$label,$key);    
                }    
            }
            
            $html .= '<li>';
            $html .= $value.': '.$key;
            $html .= '</li>';
        }

        $this->ss->assign("SCHEDULE", $html);

        //upgrade to class
        $this->bean->load_relationship('j_class_j_class_1');
        $upgrade_to_class = reset($this->bean->j_class_j_class_1->getBeans());
        $atag = '<a href="index.php?module=J_Class&action=DetailView&record='.$upgrade_to_class->id.'" TARGET=_blank>'.$upgrade_to_class->name.'</a>';
        $this->ss->assign("UTC", $atag);

        //Upgrade Button
        if($this->bean->isupgrade == 0){
            $btn = '<input title="Upgrade" class="button" onclick="var _form = document.getElementById(\'formDetailView\'); _form.return_module.value=\'J_Class\'; _form.return_action.value=\'DetailView\'; _form.isDuplicate.value=true; _form.action.value=\'EditView\'; _form.return_id.value=\''.$this->bean->id.'\';SUGAR.ajaxUI.submitForm(_form);" type="button" name="Duplicate" value="'.$GLOBALS['mod_strings']['BTN_UPGRADE'].'" id="duplicate_button">';
            $this->ss->assign("UPGRADE_BUTTON", $btn);
        }

        //add By Lam Hai 01/06/2016 - get list for function export student list
        $kindOfCourse = $this->bean->kind_of_course;
        $checkAdult = $this->bean->isAdultKOC();
        $sqlGetStudents = "
        SELECT DISTINCT
        IFNULL(l2.id, '') st_id,
        l2.contact_id student_id,
        CONCAT(IFNULL(l2.last_name, ''),
        ' ',
        IFNULL(l2.first_name, '')) name,
        IFNULL(MIN(j_studentsituations.start_study), '') start_study
        FROM
        j_studentsituations
        INNER JOIN
        j_class l1 ON j_studentsituations.ju_class_id = l1.id
        AND l1.deleted = 0
        INNER JOIN
        contacts l2 ON j_studentsituations.student_id = l2.id
        AND l2.deleted = 0
        WHERE
        (((l1.id = '{$this->bean->id}')
        AND (j_studentsituations.type IN ('Enrolled' , 'OutStanding',
        'Settle',
        'Stopped',
        'Moving In'))))
        AND j_studentsituations.deleted = 0
        GROUP BY st_id
        ORDER BY l2.first_name";
        $rsGetStudents  = $GLOBALS['db']->query($sqlGetStudents);
        $studentNo      = 1;
        $studentHtml    = '';
        $mindate        = date('Y-m-d');
        while($rowStudent = $GLOBALS['db']->fetchByAssoc($rsGetStudents)) {
            $studentHtml .= '<tr>
            <td class="checkbox"><input type="checkbox" class="check" name = "student_id[]" value="'. $rowStudent['st_id']. '"></td>
            <td>'. $studentNo. '</td>
            <td>'. $rowStudent['student_id']. '</td>
            <td class="studentName">'. $rowStudent['name'] . '</td>
            </tr>';
            $studentNo ++;
            if($mindate > $rowStudent['start_study'])
            $mindate = $rowStudent['start_study'];
        }
        $situationDate = $timedate->to_display_date($mindate, false);
        $this->ss->assign('CLASS_ID', $this->bean->id);
        $this->ss->assign('STUDENT_LIST', $studentHtml);
        $this->ss->assign('ISADULT', $checkAdult?1:0);
        //end By Lam Hai

        //Button Submit In Progress
        $btnSubmitInProgress = "";
        if($this->bean->status == "Planning" && (checkDataLockDate($situationDate))){
            $btnSubmitInProgress = '<input type="button" class="button" id="submit_in_progress" name="submit_in_progress" value="'.$GLOBALS['mod_strings']['LBL_SUBMIT_IN_PROGRESS'].'"></input>';
        }
        $this->ss->assign("BTN_SUBMIT_IN_PROGRESS", $btnSubmitInProgress);

        //Get list Session & Parse to Json
        $arr = array();
        $q2 = "SELECT DISTINCT
        IFNULL(meetings.id, '') primaryid,
        meetings.date_start date_start,
        meetings.week_no week_no,
        meetings.lesson_number lesson_number,
        meetings.delivery_hour delivery_hour
        FROM
        meetings
        INNER JOIN
        j_class l1 ON meetings.ju_class_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$this->bean->id}')
        AND (meetings.session_status <> 'Cancelled')))
        AND meetings.deleted = 0
        ORDER BY date_start ASC";
        $rs2 = $GLOBALS['db']->query($q2);
        $count_ss   = 0;
        $today      = date('Y-m-d');
        $defautDate = '';
        $week_nos = array();
        while($ss = $GLOBALS['db']->fetchByAssoc($rs2)){
            $date_start_int = date('Y-m-d',strtotime("+7 hours ".$ss['date_start']));

            if($date_start_int != $last_date_start_int)
                $delivery_hour = $ss['delivery_hour'];
            else $delivery_hour += $ss['delivery_hour'];

            $arr[$date_start_int]  = $delivery_hour;

            $last_date_start_int = $date_start_int;
            $count_ss++;

            if($date_start_int >= $today && empty($defautDate))
                $defautDate = $date_start_int;

            if(!in_array($ss['week_no'],$week_nos)) $week_nos[$count_ss] = $ss['week_no'];
        }

        $json_ss = json_encode($arr);
        $this->ss->assign("json_ss", $json_ss);

        //Outstanding Variable
        $this->ss->assign("today",$timedate->nowDate());

        if($_GET['function'] == 'addOutstanding'){
            $student_id 	= $_GET['student_id'];
            $sql = "SELECT CONCAT(IFNULL(last_name, ''), ' ', IFNULL(first_name, '')) student_name FROM contacts WHERE id = '$student_id'";
            $student_name = $GLOBALS['db']->getOne($sql);
            $this->ss->assign("ot_student_id", $student_id);
            $this->ss->assign("ot_student_name", $student_name);
        }
        //Handle Demo
        if($_GET['function'] == 'addDemo'){
            $student_type 	= $_GET['student_type'];
            $student_id 	= $_GET['student_id'];
            $sql = "SELECT CONCAT(IFNULL($student_type.last_name, ''), ' ', IFNULL($student_type.first_name, '')) student_name FROM $student_type WHERE id = '$student_id'";
            $student_name = $GLOBALS['db']->getOne($sql);
            $this->ss->assign("dm_student_id", $student_id);
            $this->ss->assign("dm_student_name", $student_name);
            $this->ss->assign("dm_student_type", $student_type);
        }

        $this->ss->assign('session_cancel_reason_options',get_select_options_with_id($GLOBALS['app_list_strings']['session_cancel_reason_options'],''));
        $this->ss->assign('teaching_type_options',get_select_options_with_id($GLOBALS['app_list_strings']['teaching_type_options'],''));
        $this->ss->assign('next_session_date',$timedate->to_display_date($defautDate,false));

        // create option for function export attendance list
        $lessonListHtml = "";
        foreach($week_nos as $lesson => $week_no) {
            $lessonListHtml .= "<option value='{$week_no}'>".$week_no."</option>";
        }
        $this->ss->assign('LESSON_LIST',$lessonListHtml);

        //Check Role Button Export
        $btnExport = "";
        if(ACLController::checkAccess('J_Class', 'export', true)) {
            if($this->bean->class_type == "Normal Class"){
                //$btnExport .= '<input type="button" class="button" id="export_attendance" name="export_attendance" value="'.$GLOBALS['mod_strings']['BTN_TOP_EXPORT_ATTENDANCE'].'" onclick="showDialogExportAttendance();"/>' ;
                $btnExport .= '<input type="button" class="button" id="export" name="export" value="'.$GLOBALS['mod_strings']['BTN_EXPORT'].'" />';
            }

        }
        $this->ss->assign("BTN_EXPORT", $btnExport);
        $this->ss->assign("TEAMTYPE", getTeamType($this->bean->team_id));


        //updateClassSession($this->bean->id);
        $this->bean->closed_date =$timedate->nowDate();
        
        //Display status
        $status = $GLOBALS['app_list_strings']['status_class_list'][$this->bean->status];
        switch ($status) {
            case "Planning":
                $statusCss = "textbg_green";
                break;
            case "In Progress":     
                $statusCss = "textbg_bluelight";
                break;  
            case "Finish":     
                $statusCss = "textbg_black";
                break;
            case "Closed":    
                $statusCss = "textbg_red";
                break;    
            default:              
                $statusCss = "textbg_green";
                break;
        }  

        $this->ss->assign('STATUS', $status);
        $this->ss->assign('STATUS_CSS', $statusCss);
        // Get lisence info
        $lisence = getLisenceOnlineCRM();     
        $this->ss->assign('lisence',$lisence['version']);
        
        parent::display();
    }

    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);

        //hide subpanel Panel Session if type is waiitng
        if($this->bean->class_type == "Waiting Class"){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_j_gradebook_1']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_contacts_1']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings_syllabus']);
        }
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_c_teachers_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_j_teachercontract_1']);
        //        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_studentsituations']);
        
        
        // Order Subpanel                                                                                     
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_contacts_1']['order'] = 1;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_studentsituations']['order'] = 2; 
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings']['order'] = 3;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings_syllabus']['order'] = 4;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_j_gradebook_1']['order'] = 5;   
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_j_feedback_1']['order'] = 6;
         
         
        //Disable  transfer in Free/ Standard version
        $lisence = getLisenceOnlineCRM();
        if(in_array($lisence['version'], array("Free","Standard"))){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings_syllabus']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_meetings']['top_buttons'][1]);
        }
        
        echo $subpanel->display();
    }
}