<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_PaymentViewEdit extends ViewEdit
{
    public function preDisplay(){
        if(empty($_GET['payment_type']) && empty($this->bean->payment_type))
            $this->bean->payment_type = 'Enrollment';

        if(empty($this->bean->id) && !empty($_GET['payment_type'])){  
            // Get lisence info
            $lisence = getLisenceOnlineCRM();  
            if($_GET['payment_type'] == "Placement Test" && $lisence['version'] == "Free"){  
                $_GET['payment_type'] = "Cashholder";
                $this->bean->payment_type = "Cashholder";
            } 
            if(in_array($_GET['payment_type'], array("Transfer Out", "Moving Out")) 
            && in_array($lisence['version'], array("Free","Standard"))){  
                $_GET['payment_type'] = "Cashholder";
                $this->bean->payment_type = "Cashholder";
            }   
            else
                $this->bean->payment_type = $_GET['payment_type'];
        }
        parent::preDisplay();
    }
    public function display(){
        global $timedate, $current_user, $locale, $mod_strings, $app_list_strings;
        require_once("custom/include/_helper/class_utils.php");
        if (!empty($this->bean->id)){
            $countUsed = $GLOBALS['db']->getOne("SELECT count(id) count FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_idb = '{$this->bean->id}' AND deleted = 0");
            if( ($countUsed > 0 || !checkDataLockDate($this->bean->date_entered))  && !$current_user->isAdmin()){
                echo '
                <script type="text/javascript">
                alert("This transaction was completed and invoiced. You can not edit !");
                location.href=\'index.php?module=J_Payment&action=DetailView&record='.$this->bean->id.'\';
                </script>';
                die();
            }

            if(($this->bean->is_outstanding == 1) && ($this->bean->payment_type == 'Enrollment')){
                $start_study    = $timedate->to_db_date($this->bean->start_study,false);
                $payment_date   = $timedate->to_db_date($this->bean->payment_date,false);
                if($start_study > $payment_date){
                    $this->bean->is_outstanding = 0;
                }else{
                    echo '
                    <script type="text/javascript">
                    alert("THIS IS OUTSTANDING CASE. Should follow step if you want to Edit.\nStep 1: Delete this payment\nStep 2: Then create Enrollment again !");
                    location.href=\'index.php?module=J_Payment&action=DetailView&record='.$this->bean->id.'\';
                    </script>';
                    die();
                }
            }
        }
        $disableEditArray = array('Delay', 'Schedule Change', 'Transfer In', 'Transfer Out', 'Moving In', 'Moving Out', 'Refund', 'Book/Gift', 'Corporate', 'Transfer From AIMS', 'Merge AIMS');
        if(in_array($this->bean->payment_type,$disableEditArray) && !empty($this->bean->id)){
            echo '
            <script type="text/javascript">
            alert("");
            location.href=\'index.php?module=J_Payment&action=DetailView&record='.$this->bean->id.'\';
            </script>';
            die();
        }




        //In Case create
        $class_seleted = array();
        $dis_selected = array();
        $team_type = $current_user->team_type;
        $parent_type = "Contacts";
        //Assign Student from $_GET
        if(!empty($_REQUEST['student_id'])){
            $student = BeanFactory::getBean('Contacts',$_REQUEST['student_id']);
            $this->bean->contacts_j_payment_1_name = $student->last_name.' '.$student->first_name;
            $this->bean->contacts_j_payment_1contacts_ida = $student->id;

            $this->bean->team_id = $student->team_id;

            if($student->team_id != $student->team_set_id){
                //get Teams by Team Set Id
                $teamSetBean = new TeamSet();
                $teams = $teamSetBean->getTeams($student->team_set_id);
                foreach ($teams as $team)
                    if($team->id == $current_user->team_id)
                        $this->bean->team_id = $team->id;
            }
            $this->bean->team_set_id = $this->bean->team_id;
            $team_type = getTeamType($student->team_id);
        }
        elseif(!empty($_REQUEST['lead_id']) || $this->bean->parent_type == 'Leads'){
            $lead_id = $_REQUEST['lead_id'];
            if(!empty($this->bean->lead_id))
                $lead_id = $this->bean->lead_id;

            $leadBean = BeanFactory::getBean("Leads", $lead_id);
            $leadFullname = $locale->getLocaleFormattedName($leadBean->first_name, $leadBean->last_name, $leadBean->salutaion);
            $this->bean->contacts_j_payment_1_name          = $leadFullname;
            $this->bean->contacts_j_payment_1contacts_ida   = $leadBean->id;
            $this->bean->team_id                            = $leadBean->team_id;

            if($leadBean->team_id != $leadBean->team_set_id){
                //get Teams by Team Set Id
                $teamSetBean = new TeamSet();
                $teams = $teamSetBean->getTeams($leadBean->team_set_id);
                foreach ($teams as $team)
                    if($team->id == $current_user->team_id)
                        $this->bean->team_id = $team->id;
            }
            $this->bean->team_set_id = $this->bean->team_id;
            $this->bean->assigned_user_name = $leadBean->assigned_user_name;
            $this->bean->assigned_user_id   = $leadBean->assigned_user_id;   
            $parent_type = "Leads";
        }
        $this->ss->assign('parent_type', $parent_type);
        $this->ss->assign('team_type',$team_type);
        $this->ss->assign('limit_discount_percent', "<script>var limit_discount_percent = 100;</script>" );
        $this->ss->assign('min_points_loyalty', "<script>var min_points = 0;</script>" );
        if(empty($this->bean->id)){
            $this->bean->name = 'Auto-Generate';
            $is_create = true;

            if($this->bean->payment_type == 'Enrollment'){
                $this->ss->assign('payment_type', $app_list_strings['payment_type_payment_list'][$this->bean->payment_type]."<input type='hidden' name='payment_type' id='payment_type' value='{$this->bean->payment_type}'>");
            }
            elseif ($this->bean->payment_type == 'Transfer Out' || $this->bean->payment_type == 'Moving Out' || $this->bean->payment_type == 'Transfer From AIMS'){
                $this->ss->assign('payment_type',"<input type='hidden' name='payment_type' id='payment_type' value='".$this->bean->payment_type."'>");
            }
            else{  
                if($parent_type == 'Contacts'){
                    $paymentTypeOptions = array(
                        'Cashholder'        => $app_list_strings['payment_type_payment_list']['Cashholder'],
                        'Deposit'           => $app_list_strings['payment_type_payment_list']['Deposit'],
                        'Placement Test'    => $app_list_strings['payment_type_payment_list']['Placement Test'],
                        'Book/Gift'         => $app_list_strings['payment_type_payment_list']['Book/Gift'],      
                    );
                }elseif($parent_type == 'Leads'){
                    $paymentTypeOptions = array(                                                         
                        'Placement Test'  => $app_list_strings['payment_type_payment_list']['Placement Test'],
                        'Deposit'         => $app_list_strings['payment_type_payment_list']['Deposit'],      
                    );  
                }

                //add by Tung Bui - Disable Payment PT on Free Lisence      
                $lisence = getLisenceOnlineCRM();  
                if($lisence['version'] == "Free"){
                    unset($paymentTypeOptions['Placement Test']);
                }

                $this->ss->assign('payment_type', $paymentTypeOptions);      
            }

            $this->ss->assign('payment_type_selected', $this->bean->payment_type);
            
            if ($this->bean->payment_type == 'Transfer From AIMS'){
                $q9 = "SELECT DISTINCT
                IFNULL(teams.id, '') primaryid,
                IFNULL(teams.name, '') teams_name,
                IFNULL(teams.team_type, '') teams_team_type,
                IFNULL(teams.code_prefix, '') teams_code_prefix
                FROM
                teams
                WHERE
                (((teams.team_type = 'Junior')
                AND ((teams.private IS NULL
                OR teams.private = '0'))))
                AND teams.deleted = 0";
                $rs9 = $GLOBALS['db']->query($q9);
                $team_list = array(
                    '' => '-none-',
                );
                while($row9 = $GLOBALS['db']->fetchByAssoc($rs9)){
                    //Kiem tra co team con ko
                    $q10 = "SELECT DISTINCT
                    IFNULL(COUNT(teams.id), 0) count
                    FROM
                    teams
                    INNER JOIN
                    teams l1 ON teams.parent_id = l1.id
                    AND l1.deleted = 0
                    WHERE
                    (l1.id = '{$row9['primaryid']}')
                    AND teams.deleted = 0";
                    $count = $GLOBALS['db']->getOne($q10);
                    if($count <= 0)
                        $team_list[$row9['primaryid']] = $row9['teams_name'];
                }
                $this->ss->assign('from_AIMS_center_id', $team_list);
            }                           


            $this->ss->assign('discount_list', "<input type='hidden' name='discount_list' id='discount_list' value=''/>" );

            if($this->bean->payment_type == 'Book/Gift' || $this->bean->payment_type == 'Cashholder' || $this->bean->payment_type == 'Deposit'){
                //Book Manage
                $book_list  = getBookList();
                $html_tpl   = getHtmlAddRow($book_list,'','','','','',true);
                $html_tpl   .= getHtmlAddRow($book_list,'','','','','',false);
                //ADD Book Template
                $this->ss->assign('html_tpl',$html_tpl);
            }

            // Assign default value for payment detail - tung Bui
            $this->ss->assign('PAY_DTL_TYPE_OPTIONS', $GLOBALS['app_list_strings']['payment_detail_type_options']);

            $this->ss->assign('loyalty_list', "<input type='hidden' name='loyalty_list' id='loyalty_list' value=''/>" );
            $this->ss->assign('loy_loyalty_points', "0" );
        }
        else{ //In Case edit
            // If payment have payment detail printed, can not edit
            $is_create = false;
            //GENERATE Payment Type
            $this->ss->assign('payment_type',"{$app_list_strings['payment_type_payment_list'][$this->bean->payment_type]}<input type='hidden' name='payment_type' id='payment_type' value='{$this->bean->payment_type}'/>");
            if($this->bean->payment_type == 'Enrollment') {

                $classInfoArray = json_decode(html_entity_decode($this->bean->content),true);
                $class_seleted  = array_keys($classInfoArray);

                //Remove Start Study and End Study
                $this->bean->start_study    = '';
                $this->bean->end_study      = '';

            }
            //GET DISCOUNT SELETED
            $q5 = "SELECT DISTINCT
            IFNULL(j_discount.id, '') primaryid,
            j_discount.discount_amount discount_amount,
            IFNULL(j_discount.maximum_discount_percent, 0) maximum_discount_percent,
            j_discount.discount_percent discount_percent
            FROM
            j_discount
            INNER JOIN
            j_payment_j_discount_1_c l1_1 ON j_discount.id = l1_1.j_payment_j_discount_1j_discount_idb
            AND l1_1.deleted = 0
            INNER JOIN
            j_payment l1 ON l1.id = l1_1.j_payment_j_discount_1j_payment_ida
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$this->bean->id}')))
            AND j_discount.deleted = 0";
            $rs5 = $GLOBALS['db']->query($q5);
            while($row = $GLOBALS['db']->fetchByAssoc($rs5) ){
                $dis_selected[$row['primaryid']]['id']             = $row['primaryid'];
                $dis_selected[$row['primaryid']]['dis_percent']    = format_number($row['discount_percent'],2,2);
                $dis_selected[$row['primaryid']]['dis_amount']     = format_number($row['discount_amount']);
                $dis_selected[$row['primaryid']]['maximum_percent']= format_number($row['maximum_discount_percent'],2,2);
            }
            $dis_list = json_encode($dis_selected);
            $this->ss->assign('discount_list', "<input type='hidden' name='discount_list' id='discount_list' value='$dis_list'/>" );

            //GET Partnership selected
            $q9 = "SELECT DISTINCT
            IFNULL(l1.id, '') l1_id,
            IFNULL(l1_1.discount_id, '') discount_id
            FROM
            j_payment
            INNER JOIN
            j_partnership_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_partnership_j_payment_1j_payment_idb
            AND l1_1.deleted = 0
            INNER JOIN
            j_partnership l1 ON l1.id = l1_1.j_partnership_j_payment_1j_partnership_ida
            AND l1.deleted = 0
            WHERE
            (((j_payment.id = '{$this->bean->id}')))
            AND j_payment.deleted = 0";
            $rs9 = $GLOBALS['db']->query($q9);
            while($row = $GLOBALS['db']->fetchByAssoc($rs9) ){
                $part_selected[$row['discount_id']]['discount_id']      = $row['discount_id'];
                $part_selected[$row['discount_id']]['partnership_id']   = $row['l1_id'];
            }

            //--------------------Add Book Teamplate------////////////////
            if($this->bean->payment_type == 'Book/Gift') {
                $book_list  = getBookList();
                $html_tpl   = getHtmlAddRow($book_list,'','','','','',true);
                $q1 = "SELECT DISTINCT
                IFNULL(l3.id, '') book_id,
                IFNULL(l3.name, '') book_name,
                IFNULL(j_inventorydetail.id, '') primaryid,
                j_inventorydetail.quantity quantity,
                l3.unit unit,
                j_inventorydetail.price price,
                j_inventorydetail.amount amount
                FROM
                j_inventorydetail
                INNER JOIN
                j_inventory l1 ON j_inventorydetail.inventory_id = l1.id
                AND l1.deleted = 0
                INNER JOIN
                j_payment_j_inventory_1_c l2_1 ON l1.id = l2_1.j_payment_j_inventory_1j_inventory_idb
                AND l2_1.deleted = 0
                INNER JOIN
                j_payment l2 ON l2.id = l2_1.j_payment_j_inventory_1j_payment_ida
                AND l2.deleted = 0
                INNER JOIN
                product_templates l3 ON j_inventorydetail.book_id = l3.id
                AND l3.deleted = 0
                WHERE
                (((l2.id = '{$this->bean->id}')))
                AND j_inventorydetail.deleted = 0";
                $details = $GLOBALS['db']->fetchArray($q1);
                foreach($details as $detail)
                    $html_tpl .= getHtmlAddRow($book_list , $detail['book_id'], $detail['unit'], $detail['quantity'],format_number($detail['price']),format_number($detail['amount']), false);

                //ADD Book Template
                $this->ss->assign('html_tpl',$html_tpl);
            }

            // Get Spilt payment - by Tung Bui
            $this->ss->assign('PAY_DTL_TYPE_OPTIONS', $GLOBALS['app_list_strings']['payment_detail_type_options']);

            $sqlGetPayDetail = "SELECT DISTINCT
            IFNULL(payment_no, '0')         pay_no,
            IFNULL(payment_amount, '0')     pay_amount,
            IFNULL(payment_method, '')      payment_method,
            IFNULL(card_type, '')           card_type,
            IFNULL(before_discount, '0')    before_discount,
            IFNULL(discount_amount, '0')    discount_amount,
            IFNULL(payment_amount, '0')     pay_amount,
            IFNULL(status, '')              pay_status,
            IFNULL(payment_date, '')        pay_date,
            IFNULL(type, '')                pay_type
            FROM j_paymentdetail
            WHERE payment_id = '{$this->bean->id}'
            AND deleted <> 1
            AND status <> 'Cancelled'
            ORDER BY payment_no;";
            $rsGetPayDetail = $GLOBALS['db']->query($sqlGetPayDetail);
            $payDtlStatus = array();
            while($payDetail = $GLOBALS['db']->fetchByAssoc($rsGetPayDetail)){
                $this->ss->assign('PAY_DTL_BEF_DISCOUNT_'.$payDetail['pay_no'],$payDetail['before_discount']);
                $this->ss->assign('PAY_DTL_DIS_AMOUNT_'.$payDetail['pay_no'],$payDetail['discount_amount']);
                $this->ss->assign('PAY_DTL_AMOUNT_'.$payDetail['pay_no'],$payDetail['pay_amount']);
                //    $this->ss->assign('PAY_DTL_METHOD_'.$payDetail['pay_no'],$payDetail['payment_method']);
                //    $this->ss->assign('PAY_DTL_CARD_TYPE_'.$payDetail['pay_no'],$payDetail['card_type']);
                $this->ss->assign('PAY_DTL_TYPE_'.$payDetail['pay_no'],$payDetail['pay_type']);
                //    $this->ss->assign('PAY_DTL_DATE_'.$payDetail['pay_no'],$timedate->to_display_date($payDetail['pay_date']));
                $payDtlStatus[$payDetail['pay_no']] = $payDetail['pay_status'];
            }


            $this->ss->assign('loyalty_list', "<input type='hidden' name='loyalty_list' id='loyalty_list' value=''/>" );
            $loy_loyalty_points = $GLOBALS['db']->getOne("SELECT ABS(IFNULL(point, 0)) point FROM j_loyalty WHERE payment_id = '{$this->bean->id}' AND type = 'Redemption' AND deleted = 0");
            if(empty($loy_loyalty_points)) $loy_loyalty_points = 0;
            $this->ss->assign('loy_loyalty_points', $loy_loyalty_points );
        }

        if($this->bean->payment_type == 'Enrollment'){
            // generate option classes for current user
            if(!empty($this->bean->team_id) && ($this->bean->team_id != $current_user->team_id))
                $qTeam = "AND j_class.team_id = '{$this->bean->team_id}'";
            else
                $qTeam = "AND j_class.team_id = '{$current_user->team_id}'";

            $q1 = "SELECT DISTINCT
            IFNULL(j_class.id, '') primaryid,
            IFNULL(j_class.class_code, '') j_class_class_code,
            IFNULL(j_class.name, '') j_class_name,
            j_class.start_date j_class_start_date,
            j_class.end_date j_class_end_date,
            j_class.class_type class_type,
            j_class.hours j_class_hours,
            j_class.kind_of_course kind_of_course,
            IFNULL(j_class.description, '') j_class_description,
            IFNULL(j_class.short_schedule, '') j_class_short_schedule
            FROM
            j_class
            WHERE
            ((((j_class.status = 'Planning' AND j_class.end_date >= '{$timedate->nowDbDate()}')
            OR j_class.status = 'In Progress')
            OR (j_class.id IN ('".implode("','",$class_seleted)."')
            OR (j_class.class_type = 'Waiting Class')
            OR (j_class.kind_of_course IN('Outing Trip', 'Cambridge') AND j_class.end_date >= '".date('Y-m-01')."' ) )))
            $qTeam
            AND j_class.deleted = 0
            ORDER BY class_type ASC,j_class_start_date ASC";

            $rs1 = $GLOBALS['db']->query($q1);
            $classOptions = "";
            $class_arr = array();
            while($row = $GLOBALS['db']->fetchByAssoc($rs1))
                $class_arr[] = $row['primaryid'];

            //Get list SS by each class
            $q2 = "SELECT DISTINCT
            IFNULL(meetings.id, '') primaryid,
            IFNULL(l1.id, '') class_id,
            meetings.date_start date_start,
            meetings.lesson_number lesson_number,
            meetings.delivery_hour delivery_hour
            FROM
            meetings
            INNER JOIN
            j_class l1 ON meetings.ju_class_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((l1.id IN ('".implode("','",$class_arr)."')  )
            AND (meetings.session_status <> 'Cancelled')))
            AND meetings.deleted = 0
            ORDER BY class_id, date_start ASC";
            $rs2 = $GLOBALS['db']->query($q2);
            $ssClass = array();
            $cr_class = '';
            while($ss = $GLOBALS['db']->fetchByAssoc($rs2) ){
                if($ss['class_id'] != $cr_class){
                    $cr_class = $ss['class_id'];
                    $key = 0;
                }
                $ssClass[$cr_class][$key]['primaryid']       = $ss['primaryid'];
                $ssClass[$cr_class][$key]['date_start']      = $ss['date_start'];
                $ssClass[$cr_class][$key]['lesson_number']   = $ss['lesson_number'];
                $ssClass[$cr_class][$key]['delivery_hour']   = $ss['delivery_hour'];
                $key++;
            }

            $lessonPlan = array(
                36    => '1',
                72    => '2',
                108   => '3',
                120   => '2',
                144   => '3',
            );

            $rs1 = $GLOBALS['db']->query($q1);
            while($row = $GLOBALS['db']->fetchByAssoc($rs1) ) {
                $start_date =   $timedate->to_display_date($row['j_class_start_date'],true);
                $end_date   =   $timedate->to_display_date($row['j_class_end_date'],true);
                //sclass schedule
                $classScheuleHtml = '<ul style="margin-left: 0;">';
                $main_schedule = json_decode(html_entity_decode($row['j_class_short_schedule']));
                foreach($main_schedule as $key => $value ){
                    if($GLOBALS['current_language'] == 'vn_vn'){
                        foreach($GLOBALS['app_list_strings']['week_frame_class_list'] as $labelKey => $label){
                            $key = str_replace($labelKey,$label,$key);    
                        }    
                    }
                    $classScheuleHtml .= '<li>'.$key.'</li>';
                }
                $delivery_hour = 0;
                //get sessions
                $arr = array();
                $ssList = $ssClass[$row['primaryid']];
                $num_periods    = $lessonPlan[(int)$row['j_class_hours']];
                if(empty($num_periods)) $num_periods = 1;
                $hour_per_point = $row['j_class_hours'] / $num_periods;
                $sum_hour       = 0;
                $classScheuleHtml .= '<li>'.$GLOBALS['mod_strings']['LBL_POINTS_OF_TIME'].': <table>';
                $flag_point1 = true;
                $flag_point2 = true;
                $flag_point3 = true;
                $set_start_point_2 = true;
                $set_start_point_3 = true;

                for($i = 0; $i < count($ssList); $i++) {
                    $date_start_int = date('Y-m-d', strtotime("+7 hours ".$ssList[$i]['date_start']));
                    if($date_start_int != $last_date_start_int)
                        $delivery_hour = $ssList[$i]['delivery_hour'];
                    else $delivery_hour += $ssList[$i]['delivery_hour'];

                    $arr[$date_start_int]  = $delivery_hour;

                    $sum_hour += $ssList[$i]['delivery_hour'];
                    //Chia cac moc thoi gian
                    if($sum_hour > ($hour_per_point * 1) && $set_start_point_2){
                        $start_p_2 = $timedate->to_display_date($date_start_int);
                        $set_start_point_2 = false;
                    }
                    if($sum_hour > ($hour_per_point * 2) && $set_start_point_3){
                        $start_p_3 = $timedate->to_display_date($date_start_int);
                        $set_start_point_3 = false;
                    }

                    if(($sum_hour >= ($hour_per_point * 1)) && $flag_point1){
                        $classScheuleHtml .= "<tr><td>0 - $hour_per_point".$GLOBALS['mod_strings']['LBL_HOURS'].":</td> <td>". $timedate->to_display_date($ssList[0]['date_start'])." &#x279c; {$timedate->to_display_date($date_start_int)}</td></tr>";
                        $flag_point1 = false;
                        $endpoint1 = $date_start_int;
                    }

                    if(($sum_hour >= ($hour_per_point * 2)) && $flag_point2){
                        $classScheuleHtml .= "<tr><td>$hour_per_point - ".($hour_per_point * 2).$GLOBALS['mod_strings']['LBL_HOURS'].":</td> <td>$start_p_2 &#x279c; {$timedate->to_display_date($date_start_int)}</td></tr>";
                        $flag_point2 = false;
                        $endpoint2 = $date_start_int;
                    }

                    if(($sum_hour >= ($hour_per_point * 3)) && $flag_point3){
                        $classScheuleHtml .= "<tr><td>".($hour_per_point * 2)." - ".($hour_per_point * 3).$GLOBALS['mod_strings']['LBL_HOURS'].":</td> <td>$start_p_3 &#x279c; {$timedate->to_display_date($date_start_int)}</td></tr>";
                        $flag_point3 = false;
                    }
                    $last_date_start_int = $date_start_int;
                }
                if($flag_point1 && $flag_point2 && $flag_point3)
                    $classScheuleHtml .= '<tr><td>-none-</td></tr>';
                $classScheuleHtml .= '</table></li>';
                if($row['class_type'] == 'Waiting Class'){
                    $date_start_int = date('Y-m-d', strtotime($row['j_class_start_date']));
                    $arr[$date_start_int]  = $row['j_class_hours'];
                    $classScheuleHtml = "<li>".$GLOBALS['mod_strings']['LBL_TYPE'].": <span class=\"textbg_orange\">".$GLOBALS['app_list_strings']['type_class_list'][$row['class_type']]."</span></li>";
                }else
                    $classScheuleHtml .= "<li>".$GLOBALS['mod_strings']['LBL_TYPE'].": <span class=\"textbg_green\">".$GLOBALS['app_list_strings']['type_class_list'][$row['class_type']]."</span></li>";

                $classScheuleHtml .= '</ul>';
                $json_ss = json_encode($arr);
                $strig = '';
                if (in_array($row['primaryid'], $class_seleted))
                    $strig = "selected";

                $classOptions .= "<option $strig value='{$row['primaryid']}' start_date='$start_date' end_date='$end_date' class_type='".$GLOBALS['app_list_strings']['type_class_list'][$row['class_type']]."' class_name='{$row['j_class_name']}' kind_of_course='{$row['kind_of_course']}' json_ss='$json_ss' main_schedule='$classScheuleHtml'>{$row['j_class_class_code']}</option>";
            }

            $this->ss->assign('CLASS_OPTIONS', $classOptions);

        }

        if (!empty($this->bean->j_coursefee_j_payment_1j_coursefee_ida)){
            $currentCourseFeeStatement = "OR j_coursefee.id = '{$this->bean->j_coursefee_j_payment_1j_coursefee_ida}'";
        }
        $ext_team_c = "AND j_coursefee.team_set_id IN
        (SELECT
        tst.team_set_id
        FROM
        team_sets_teams tst
        INNER JOIN
        team_memberships team_memberships ON tst.team_id = team_memberships.team_id
        AND team_memberships.user_id = '".$current_user->id."'
        AND team_memberships.deleted = 0)";
        if($current_user->isAdmin()){
            $ext_team_c = '';
        }
        //Get Course Fee
        $q3 = "SELECT DISTINCT
        IFNULL(j_coursefee.id, '') primaryid,
        IFNULL(j_coursefee.name, '') j_coursefee_name,
        IFNULL(j_coursefee.type_of_course_fee, '') type_of_course_fee,
        j_coursefee.unit_price j_coursefee_unit_price,
        j_coursefee.is_accumulate is_accumulate,
        j_coursefee.inactive_date j_coursefee_inactive_date,
        j_coursefee.apply_date j_coursefee_apply_date,
        j_coursefee.status status,
        IFNULL(j_coursefee.apply_for, '') apply_for  
        FROM
        j_coursefee
        INNER JOIN
        teams l1 ON j_coursefee.team_id = l1.id AND l1.team_type = '$team_type'
        WHERE j_coursefee.deleted = 0
        AND((j_coursefee.status = 'Active'
        $ext_team_c)
        $currentCourseFeeStatement)
        ORDER BY j_coursefee_name ASC";

        $rs3 = $GLOBALS['db']->query($q3);
        $coursefee = '<select id="coursefee" class="coursefee" name="j_coursefee_j_payment_1j_coursefee_ida" >
        <option value="" type="0" is_accumulate="0" price="0">--- '.$GLOBALS['mod_strings']['LBL_PLEASE_SELECT'].' ---</option>';
        while($_fee = $GLOBALS['db']->fetchByAssoc($rs3)){
            $_fee['apply_for'] = str_replace("^","",$_fee['apply_for']);
            if($this->bean->j_coursefee_j_payment_1j_coursefee_ida == $_fee['primaryid'])
                $fee_seleted = "selected";
            else $fee_seleted = "";
            $coursefee .= "<option $fee_seleted value='{$_fee['primaryid']}' type='{$_fee['type_of_course_fee']}' 
            price='{$_fee['j_coursefee_unit_price']}' is_accumulate='{$_fee['is_accumulate']}'
            apply_for='{$_fee['apply_for']}'
            >{$_fee['j_coursefee_name']} (".format_number($_fee['j_coursefee_unit_price'])."/".$GLOBALS['app_list_strings']['type_of_course_fee_list'][$_fee['type_of_course_fee']].")</option>";
        }
        $coursefee .=  '</select>';
        $this->ss->assign('coursefee',$coursefee);

        $discountRowsHtml = generateDiscountRows($dis_selected,$part_selected);
        //END : Generate
        $this->ss->assign('discount_rows', $discountRowsHtml);
        //Check Access Assigned To - Role First EC
        if(ACLController::checkAccess('J_Payment', 'import', false))
            $this->ss->assign('lock_assigned_to','<input type="hidden" id="lock_assigned_to" value="0">');
        else
            $this->ss->assign('lock_assigned_to','<input type="hidden" id="lock_assigned_to" value="1">');

        //Format number ratio
        $ratio = format_number($this->bean->ratio,6,6);
        $this->ss->assign('ratio',$ratio);

        //Add Sponsor
        $this->ss->assign('html_tpl_spon',getSponsorAddRow('','','','','','',true));
        $q6 = "SELECT DISTINCT
        IFNULL(j_sponsor.id, '') primaryid,
        IFNULL(j_sponsor.name, '') name,
        IFNULL(j_sponsor.sponsor_number, '') sponsor_number,
        IFNULL(j_sponsor.foc_type, '') foc_type,
        IFNULL(j_sponsor.campaign_code, '') campaign_code,
        IFNULL(j_sponsor.voucher_id, '') voucher_id,
        j_sponsor.amount j_sponsor_amount,
        j_sponsor.percent j_sponsor_percent,
        j_sponsor.total_down total_down
        FROM
        j_sponsor
        INNER JOIN
        j_payment l1 ON j_sponsor.payment_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$this->bean->id}')
        AND (j_sponsor.type = 'Sponsor')))
        AND j_sponsor.deleted = 0
        ORDER BY name ASC";
        $rs6 = $GLOBALS['db']->query($q6);
        $count_sp = 0;
        $spon_arr = array();
        while($spon = $GLOBALS['db']->fetchByAssoc($rs6)){
            $spon_arr[$count_sp]['sponsor_number']   = $spon['sponsor_number'];
            $spon_arr[$count_sp]['foc_type']         = $spon['foc_type'];
            $spon_arr[$count_sp]['sponsor_amount']   = format_number($spon['j_sponsor_amount']);
            $spon_arr[$count_sp]['sponsor_percent']  = format_number($spon['j_sponsor_percent'],2,2);
            $spon_arr[$count_sp]['total_down']       = format_number($spon['total_down']);
            $html_spon = getSponsorAddRow($spon['campaign_code'],$spon['voucher_id'], $spon['sponsor_number'], $spon['foc_type'], $spon_arr[$count_sp]['sponsor_amount'], $spon_arr[$count_sp]['sponsor_percent'],false);
            $count_sp++;
        }
        if($count_sp > 0)
            $this->ss->assign('html_spon',$html_spon);
        else
            $this->ss->assign('html_spon',getSponsorAddRow('','','','','',false));

        $spon_list = json_encode($spon_arr);
        $this->ss->assign('sponsor_list',"<input type='hidden' name='sponsor_list' id='sponsor_list' value='$spon_list'/>");
        if(empty($this->bean->aims_id))
            $this->bean->aims_id = '';

        //Add by Tung Bui - assign lisence version
        $lisence = getLisenceOnlineCRM();    
        $this->ss->assign('lisence_version', $lisence['version']);

        // Dirty trick to clear cache, a must for EditView:
        if(file_exists('cache/modules/' . $this->bean->module_dir . '/EditView.tpl'))
            unlink('cache/modules/' . $this->bean->module_dir . '/EditView.tpl');

        if( $this->bean->payment_type == 'Enrollment') {
            unset($this->ev->defs['panels']['LBL_SELECT_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_OTHER_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_BOOK_LIST']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_MOVING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER']);  
            unset($this->ev->defs['panels']['LBL_PAYMENT_REFUND']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER_FROM_AIMS']);
        }
        elseif($this->bean->payment_type == 'Cambridge' || $this->bean->payment_type == 'Deposit' || $this->bean->payment_type == 'Cashholder' || $this->bean->payment_type == 'Placement Test'|| $this->bean->payment_type == 'Book/Gift'|| $this->bean->payment_type == 'Outing Trip'){
            unset($this->ev->defs['panels']['LBL_ENROLLMENT']);
            unset($this->ev->defs['panels']['LBL_SELECT_PAYMENT']);  
            unset($this->ev->defs['panels']['LBL_PRICING']);      
            unset($this->ev->defs['panels']['LBL_SELECT_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_MOVING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER']);  
            unset($this->ev->defs['panels']['LBL_PAYMENT_REFUND']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER_FROM_AIMS']);
        }
        elseif( $this->bean->payment_type == 'Moving Out'){
            unset($this->ev->defs['panels']['LBL_ENROLLMENT']);
            unset($this->ev->defs['panels']['LBL_PRICING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_PLAN']);
            unset($this->ev->defs['panels']['LBL_OTHER_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_BOOK_LIST']);
            unset($this->ev->defs['panels']['LBL_OTHER']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER']);  
            unset($this->ev->defs['panels']['LBL_PAYMENT_REFUND']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER_FROM_AIMS']);
        }
        elseif($this->bean->payment_type == 'Transfer Out'){
            unset($this->ev->defs['panels']['LBL_ENROLLMENT']);
            unset($this->ev->defs['panels']['LBL_PRICING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_PLAN']);
            unset($this->ev->defs['panels']['LBL_OTHER_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_BOOK_LIST']);
            unset($this->ev->defs['panels']['LBL_OTHER']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_MOVING']);   
            unset($this->ev->defs['panels']['LBL_PAYMENT_REFUND']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER_FROM_AIMS']);
        }
        elseif($this->bean->payment_type == 'Transfer From AIMS'){
            unset($this->ev->defs['panels']['LBL_ENROLLMENT']);
            unset($this->ev->defs['panels']['LBL_PRICING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_PLAN']);
            unset($this->ev->defs['panels']['LBL_OTHER_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_BOOK_LIST']);
            unset($this->ev->defs['panels']['LBL_OTHER']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_MOVING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_REFUND']);   
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER']);
            unset($this->ev->defs['panels']['LBL_SELECT_PAYMENT']);
        }
        elseif($this->bean->payment_type == 'Refund'){
            $this->bean->payment_type = 'Refund';
            unset($this->ev->defs['panels']['LBL_ENROLLMENT']);
            unset($this->ev->defs['panels']['LBL_PRICING']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_PLAN']);
            unset($this->ev->defs['panels']['LBL_OTHER_PAYMENT']);
            unset($this->ev->defs['panels']['LBL_BOOK_LIST']);
            unset($this->ev->defs['panels']['LBL_OTHER']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_MOVING']);  
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER']);
            unset($this->ev->defs['panels']['LBL_PAYMENT_TRANSFER_FROM_AIMS']);
        }
        $this->ss->assign('APP', $app_list_strings['payment_type_payment_list']);
        parent::display();
    }
}

// Generate Add row template
function getHtmlAddRow( $book_list, $book_id, $book_unit, $book_quantity, $book_price, $book_amount, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $tpl_addrow = "<tr class='row_tpl' $display>";
    $htm_sel    = '<select name="book_id[]" class="book_id" style="width:199px;"><option value="" price="0" unit="">-none-</option>';
    $first_opt  = true;
    $previous   = '';
    foreach($book_list as $key => $value){
        if ($value['category'] != $previous){
            if(!$first_opt)
                $htm_sel .= '</optgroup>';
            else
                $first_opt = false;

            $htm_sel       .= '<optgroup label="' .$value['category'] . '">';
            $htm_sel       .= '<option style="color: green;" value="full-set">-- select full-set --</option>';
            $previous   = $value['category'];
        }
        $sel = '';
        if(!empty($book_id) && $book_id == $value["primaryid"])
            $sel = 'selected';
        $htm_sel .= '<option '.$sel.' value="'.$value["primaryid"].'" price="'.format_number($value['list_price']).'" unit="'.$value['unit'].'">'.$value["name"].'</option>';
    }
    $htm_sel .= '</optgroup></select>';
    $tpl_addrow .= '<td scope="col" align="center">'.$htm_sel.'</td>';
    $tpl_addrow .= '<td align="center"><span class="book_unit">'.$book_unit.'</span></td>';
    $tpl_addrow .= '<td align="center"><select name="book_quantity[]" class="book_quantity">'.get_select_options_with_id($GLOBALS['app_list_strings']['quantityy_list'],$book_quantity).'</select></td>';
    $tpl_addrow .= '<td nowrap align="center"><input class="currency input_readonly book_price" type="text" name="book_price[]" size="13" value="'.$book_price.'" style="font-weight: bold;" readonly></td>';
    $tpl_addrow .= '<td nowrap align="center"><input class="currency input_readonly book_amount" type="text" name="book_amount[]" size="13" value="'.$book_amount.'" style="font-weight: bold;" readonly></td>';
    $tpl_addrow .= "<td align='center'><button type='button' class='btn btn-danger btnRemove'>Remove</button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}

// Generate Discount Rows for "Get Discount" table - by Tung Bui 16/11/2015
function generateDiscountRows($dis_selected, $part_selected){
    global $current_user;
    $html = '';
    $countTr = 0;
    $ext_team = "AND j_discount.team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
    if($current_user->isAdmin()){
        $ext_team = '';
    }
    $ext_part_dis = '';
    if(!empty($part_selected))
        $ext_part_dis = "OR (j_discount.id IN ('".implode("','",array_keys($part_selected))."'))";
    //get Discount Reward
    $sqlGetReward = "SELECT DISTINCT
    IFNULL(j_discount.id, '') primaryid,
    IFNULL(j_discount.name, '') discount_name,
    IFNULL(j_discount.course, '') discount_course,
    j_discount.discount_amount discount_amount,
    j_discount.discount_percent discount_percent,
    IFNULL(j_discount.discount_days, 0) discount_days,
    IFNULL(j_discount.policy, '') policy,
    j_discount.is_ratio is_ratio,
    j_discount.is_auto_set is_auto_set,
    j_discount.is_discount_on_top is_discount_on_top,
    j_discount.is_catch_limit is_catch_limit,
    j_discount.is_chain_discount is_chain_discount,
    IFNULL(j_discount.description, '') description,
    IFNULL(l2.id, '') l2_id,
    j_discount.disable_list disable_list
    FROM
    j_discount
    LEFT JOIN
    j_discount_j_discount_1_c l1_1 ON j_discount.id = l1_1.j_discount_j_discount_1j_discount_ida
    AND l1_1.deleted = 0
    LEFT JOIN
    j_discount l1 ON l1.id = l1_1.j_discount_j_discount_1j_discount_idb
    AND l1.deleted = 0
    INNER JOIN
    teams l2 ON j_discount.team_id = l2.id
    AND l2.deleted = 0 AND l2.team_type = '{$current_user->team_type}'
    WHERE
    (((j_discount.type = 'Reward')
    AND (j_discount.status = 'Active')
    $ext_team
    ))
    AND j_discount.deleted = 0
    ORDER BY CASE
    WHEN j_discount.course = '1' THEN 0
    WHEN j_discount.course = '2' THEN 1
    WHEN j_discount.course = '3' THEN 2
    WHEN j_discount.course = '4' THEN 3
    WHEN j_discount.course = '5' THEN 4
    WHEN j_discount.course = '6' THEN 5
    WHEN j_discount.course = '7' THEN 6
    WHEN j_discount.course = '8' THEN 7
    WHEN j_discount.course = '9' THEN 8
    WHEN j_discount.course = '10' THEN 9
    WHEN j_discount.course = '15' THEN 10
    WHEN j_discount.course = '20' THEN 11
    WHEN j_discount.course = '25' THEN 12
    ELSE 13
    END ASC";

    $resultReward = $GLOBALS['db']->query($sqlGetReward);
    $rewardOptionsHtml .= '<select multiple id="discount_reward" name="discount_reward"><option value="" dis_amount="" dis_percent="" is_chain_discount="0">-none-</option>';
    $strRewardSelected = "";
    $countReward = 0;
    $disableListOfReward = "";
    // Create reward options
    while($rowReward = $GLOBALS['db']->fetchByAssoc($resultReward)){
        if ($disableListOfReward == "") $disableListOfReward = $rowReward["disable_list"];
        $discount_amount = (intval($rowReward['discount_amount']) == 0) ? '' : format_number($rowReward['discount_amount']);
        $discount_percent = (intval($rowReward['discount_percent']) == 0) ? '' : format_number($rowReward['discount_percent'],2,2);
        $strSelected = '';
        if(isset($dis_selected[$rowReward['primaryid']])){
            $strSelected = 'selected';
            $strRewardSelected = 'checked';
        }
        $rewardOptionsHtml .= "<option ".$strSelected." dis_amount='$discount_amount' dis_percent='$discount_percent' dis_is_ratio='{$rowReward['is_ratio']}' dis_is_catch_limit='{$rowReward['is_catch_limit']}' is_chain_discount='{$rowReward['is_chain_discount']}' is_discount_on_top='{$rowReward['is_discount_on_top']}' value='{$rowReward['primaryid']}' style='text-align: center;'>{$rowReward['discount_course']}</option>";
        $countReward++;
    }
    $rewardOptionsHtml .= '</select>';

    // Create tr for Reward
    if($countReward > 0){
        $countTr ++;
        $discountTrClass =  (($countTr % 2) == 0) ? 'evenListRowS1' : 'oddListRowS1';
        $html .= '<tr style="cursor:pointer" class="discount_group0 '.$discountTrClass.' unlocked" id="row_discount_reward" colspan = "6">';
        $html .= "<td><input ".$strRewardSelected." type='hidden' class='dis_check' value='Reward'><input type='hidden' class='dis_is_ratio' value='1'><input type='hidden' class='dis_is_ratio' value='1'><input type='hidden' class='dis_is_catch_limit' value='1'><input type='hidden' class='dis_type' value='Reward'>";
        $html .= "<input type='hidden' class='disable_discount_list' value='".$disableListOfReward."'></td>";
        $html .= "<td class='dis_name'>Atlantic Reward</td>";
        $html .= "<td align='center'></td>";
        $html .= "<td align='center'></td>";
        $html .= "<td align='center' style='vertical-align:middle;'><label>Course: </label>$rewardOptionsHtml</td>";
        $html .= "<td></td>";
        $html .= "</tr>";
    }

    //Add Partnership
    $sqlPNS = "SELECT DISTINCT
    IFNULL(j_partnership.id, '') primaryid,
    IFNULL(j_partnership.name, '') partnership_name,
    j_partnership.discount_amount discount_amount,
    j_partnership.discount_percent discount_percent,
    j_partnership.loyalty_type loyalty_type,
    IFNULL(j_partnership.hours, 0) hours,
    IFNULL(j_discount.discount_days, 0) discount_days,
    CONCAT(IFNULL(j_discount.description, ''), ' ', IFNULL(j_discount.policy, '')) description,
    IFNULL(j_discount.id, '') discount_id,
    j_discount.is_ratio is_ratio,
    IFNULL(j_discount.category, '') category,
    j_discount.is_auto_set is_auto_set,
    j_discount.is_discount_on_top is_discount_on_top,
    j_discount.is_catch_limit is_catch_limit,
    j_discount.is_chain_discount is_chain_discount,
    IFNULL(j_discount.maximum_discount_percent, 0) maximum_discount_percent,
    IFNULL(j_discount.name, '') discount_name,
    j_discount.date_entered discount_date_entered,
    IFNULL(j_discount.disable_list, '') disable_list
    FROM
    j_partnership
    INNER JOIN
    j_discount_j_partnership_1_c j_discount_1 ON j_partnership.id = j_discount_1.j_discount_j_partnership_1j_partnership_idb
    AND j_discount_1.deleted = 0
    INNER JOIN
    j_discount j_discount ON j_discount.id = j_discount_1.j_discount_j_partnership_1j_discount_ida
    AND j_discount.deleted = 0
    INNER JOIN
    teams l2 ON j_discount.team_id = l2.id
    AND l2.deleted = 0 AND l2.team_type = '{$current_user->team_type}'
    WHERE
    (((j_discount.type = 'Partnership')
    AND (((j_discount.status = 'Active')
    AND (j_partnership.status = 'Active'))
    $ext_part_dis)
    $ext_team))
    AND j_partnership.deleted = 0
    ORDER BY discount_id, CASE
    WHEN j_partnership.loyalty_type = 'Blue' THEN 0
    WHEN j_partnership.loyalty_type = 'Gold' THEN 1
    WHEN j_partnership.loyalty_type = 'Platinum' THEN 3
    ELSE 4
    END ASC, hours, discount_date_entered, partnership_name ASC";
    $rowParts = $GLOBALS['db']->fetchArray($sqlPNS);
    $runPart = '###';
    $optParts = array();
    foreach($rowParts as $key=>$rowPart){
        if($runPart != $rowPart['discount_id']){
            $runPart                             = $rowPart['discount_id'];
            $optParts[$runPart]['name']          = $rowPart['discount_name'];
            $optParts[$runPart]['description']   = nl2br($rowPart['description']);
            $optParts[$runPart]['option']        = '<option value="" dis_amount="" dis_percent="">-none-</option>';
            if ($disablePart == "") $disablePart = $rowPart['disable_list'];
            $optParts[$runPart]['disablePart']   = $rowPart['disable_list'];
            $optParts[$runPart]['is_chain_discount']= $rowPart['is_chain_discount'];
            $optParts[$runPart]['is_ratio']      = $rowPart['is_ratio'];
            $optParts[$runPart]['category']      = $rowPart['category'];
            $optParts[$runPart]['is_auto_set']   = $rowPart['is_auto_set'];
            $optParts[$runPart]['is_discount_on_top']   = $rowPart['is_discount_on_top'];
            $optParts[$runPart]['is_catch_limit']= $rowPart['is_catch_limit'];
            $optParts[$runPart]['maximum_percent']= $rowPart['maximum_discount_percent'];
        }
        $selectedPart   = ((isset($part_selected[$runPart])) && $part_selected[$runPart]['partnership_id'] == $rowPart['primaryid']) ? 'selected' : '';
        $disAmountPart  = (intval($rowPart['discount_amount']) == 0) ? '' : format_number($rowPart['discount_amount']);
        $disPercentPart = (intval($rowPart['discount_percent']) == 0) ? '' : format_number($rowPart['discount_percent'],2,2);
        $optParts[$runPart]['option'] .= "<option $selectedPart dis_amount='$disAmountPart' dis_percent='$disPercentPart' apply_with_loyalty='{$rowPart['loyalty_type']}' apply_with_hour='{$rowPart['hours']}' value='{$rowPart['primaryid']}'>{$rowPart['partnership_name']}</option>";
    }

    //get Discount Other/Gift
    $ext_other_dis = '';
    if(!empty($dis_selected))
        $ext_other_dis = "OR (j_discount.id IN ('".implode("','",array_keys($dis_selected))."') AND (j_discount.type IN('Gift', 'Hour', 'Other')))";

    $sqlGetDiscount = "SELECT DISTINCT
    IFNULL(j_discount.id, '') primaryid,
    IFNULL(j_discount.name, '') discount_name,
    j_discount.discount_amount discount_amount,
    j_discount.discount_percent discount_percent,
    IFNULL(j_discount.discount_days, 0) discount_days,
    IFNULL(j_discount.policy, '') policy,
    IFNULL(j_discount.category, '') category,
    j_discount.is_ratio is_ratio,
    j_discount.is_auto_set is_auto_set,
    j_discount.is_discount_on_top is_discount_on_top,
    j_discount.type type,
    j_discount.content content,
    j_discount.is_catch_limit is_catch_limit,
    j_discount.is_chain_discount is_chain_discount,
    IFNULL(j_discount.description, '') description,
    IFNULL(j_discount.maximum_discount_percent, 0) maximum_discount_percent,
    j_discount.start_date j_discount_start_date,
    j_discount.disable_list disable_list
    FROM
    j_discount
    INNER JOIN
    teams l2 ON j_discount.team_id = l2.id
    AND l2.deleted = 0 AND l2.team_type = '{$current_user->team_type}'
    WHERE
    (((j_discount.status = 'Active')
    AND (j_discount.type IN('Gift', 'Hour', 'Other')
    OR (j_discount.id IN ('".implode("','",array_keys($optParts))."')))
    $ext_team
    $ext_other_dis))
    AND j_discount.deleted = 0
    ORDER BY CASE
    WHEN (category = '' OR category IS NULL) THEN 0
    WHEN category = 'Seasonal Discount' THEN 1
    WHEN category = 'Standard Discount' THEN 2
    WHEN category = 'Special Discount' THEN 3
    ELSE 4
    END ASC";

    $resultDiscount = $GLOBALS['db']->query($sqlGetDiscount);
    $runCat = '###';
    $catCount = 0;
    // Create tr for Discount
    while($rowDiscount = $GLOBALS['db']->fetchByAssoc($resultDiscount)){
        if($runCat != $rowDiscount['category']){
            $runCat = $rowDiscount['category'];
            $catCount++;
            if(!empty($runCat)){
                $html .= "<tr><th bgcolor='#FF0000' colspan='6'>
                <a href='javascript:void(0)' id='collapseLink$catCount' onclick='collapseDiscount($catCount);'>
                <img border='0' id='img_hide' src='themes/default/images/basic_search.gif'></a>
                <a href='javascript:void(0)' style='display:none;' id='expandLink$catCount' onclick='expandDiscount($catCount);'>
                <img border='0' id='img_show' src='themes/default/images/advanced_search.gif'></a>
                {$rowDiscount['category']}</th></tr>";
            }
        }
        if($rowDiscount['type'] == 'Partnership'){
            $Dpart_id = $rowDiscount['primaryid'];
            $countTr++;
            $discountTrClass =  (($countTr % 2) == 0) ? 'evenListRowS1' : 'oddListRowS1';
            $strSelected = (isset($dis_selected[$Dpart_id])) ? 'checked' : '';
            $html .= '<tr style="cursor:pointer" class="discount_group'.$catCount.' '.$discountTrClass.' unlocked" id="row_'.$Dpart_id.'" colspan = "6">';
            $html .= "<td><input type='checkbox' $strSelected class='dis_check' is_auto_set='{$optParts[$Dpart_id]['is_auto_set']}' is_discount_on_top='{$optParts[$Dpart_id]['is_discount_on_top']}' is_chain_discount='{$optParts[$Dpart_id]['is_chain_discount']}' value='$Dpart_id' maximum_percent='{$optParts[$Dpart_id]['maximum_percent']}'><input type='hidden' class='dis_is_ratio' value='{$optParts[$Dpart_id]['is_ratio']}'><input type='hidden' class='dis_is_catch_limit' value='{$optParts[$Dpart_id]['is_catch_limit']}'><input type='hidden' class='dis_type' value='Partnership'>";
            $html .= "<input type='hidden' class='disable_discount_list' value='{$optParts[$Dpart_id]['disablePart']}'></td>";
            $html .= "<td class='dis_name'>{$optParts[$Dpart_id]['name']}</td>";
            $html .= "<td align='center'></td>";
            $html .= "<td align='center'></td>";
            $html .= "<td align='center'><select name='discount_partnership' class='discount_partnership' style='width: 100%; height: auto;'>{$optParts[$Dpart_id]['option']}</select></td>";
            $html .= "<td>{$optParts[$Dpart_id]['description']}</td>";
            $html .= "</tr>";
        }else{
            if($rowDiscount['type'] == 'Hour'){
                $discount_amount = '';
                $discount_percent = '';
                $maximum_discount_percent = format_number($rowDiscount['maximum_discount_percent'],2,2);
                $strRewardSelected = (isset($dis_selected[$rowDiscount['primaryid']])) ? 'checked' : '';
                $countTr++;
                $discountTrClass = (($countTr % 2) == 0) ? 'evenListRowS1' : 'oddListRowS1';
                $html .= '<tr style="cursor:pointer" class="discount_group'.$catCount.' '.$discountTrClass.' unlocked" id="row_'.$rowDiscount['primaryid'].'" colspan = "6">';
                $html .= "<td><input ".$strRewardSelected." type='checkbox' is_auto_set='{$rowDiscount['is_auto_set']}' is_discount_on_top='{$rowDiscount['is_discount_on_top']}' is_chain_discount='{$rowDiscount['is_chain_discount']}' class='dis_check' maximum_percent='$maximum_discount_percent' value='{$rowDiscount['primaryid']}'><input type='hidden' class='dis_is_ratio' value='{$rowDiscount['is_ratio']}'><input type='hidden' class='dis_content' value='{$rowDiscount['content']}'><input type='hidden' class='dis_is_catch_limit' value='{$rowDiscount['is_catch_limit']}'><input type='hidden' class='dis_type' value='Hour'>";
                $html .= "<input type='hidden' class='disable_discount_list' value='{$rowDiscount['disable_list']}'>
                <input type='hidden' class='dis_amount' value='$discount_amount'>
                <input type='hidden' class='dis_percent' value='$discount_percent'></td>";
                $html .= "<td class='dis_name'>{$rowDiscount['discount_name']}</td>";
                $html .= "<td align='center'>$discount_percent</td>";
                $html .= "<td align='center'>$discount_amount</td>";
                $strReadonly = '"';
                if($rowDiscount['is_auto_set'])
                    $strReadonly = ' input_readonly" readonly';
                $html .= "<td align='center'><i>Promotion Hours:</i>".'<input tabindex="0" autocomplete="off" type="text" name="dis_hours[]" class="dis_hours'.$strReadonly.' value="" size="4" maxlength="10" style="text-align: center;color: rgb(165, 42, 42);">'."</td>";
                $html .= "<td>".$rowDiscount['policy'] . nl2br($rowDiscount['description'])."</td>";
                $html .= "</tr>";
            }else{
                $discount_amount = (intval($rowDiscount['discount_amount']) == 0) ? '' : format_number($rowDiscount['discount_amount']);
                $discount_percent = (intval($rowDiscount['discount_percent']) == 0) ? '' : format_number($rowDiscount['discount_percent'],2,2);
                $maximum_discount_percent = format_number($rowDiscount['maximum_discount_percent'],2,2);
                $strRewardSelected = (isset($dis_selected[$rowDiscount['primaryid']])) ? 'checked' : '';
                $countTr++;
                $discountTrClass = (($countTr % 2) == 0) ? 'evenListRowS1' : 'oddListRowS1';
                $html .= '<tr style="cursor:pointer" class="discount_group'.$catCount.' '.$discountTrClass.' unlocked" id="row_'.$rowDiscount['primaryid'].'" colspan = "6">';
                $html .= "<td><input ".$strRewardSelected." type='checkbox' is_auto_set='{$rowDiscount['is_auto_set']}' is_discount_on_top='{$rowDiscount['is_discount_on_top']}' is_chain_discount='{$rowDiscount['is_chain_discount']}' class='dis_check' maximum_percent='$maximum_discount_percent' value='{$rowDiscount['primaryid']}'><input type='hidden' class='dis_is_ratio' value='{$rowDiscount['is_ratio']}'><input type='hidden' class='dis_is_catch_limit' value='{$rowDiscount['is_catch_limit']}'><input type='hidden' class='dis_type' value='Other'>";
                $html .= "<input type='hidden' class='disable_discount_list' value='{$rowDiscount['disable_list']}'></td>";
                $html .= "<td class='dis_name'>{$rowDiscount['discount_name']}</td>";
                $html .= "<td align='center'>$discount_percent <input type='hidden' class='dis_percent' value='$discount_percent'></td>";
                $html .= "<td align='center'>$discount_amount <input type='hidden' class='dis_amount' value='$discount_amount'></td>";
                $html .= "<td>{$rowDiscount['policy']}</td>";
                $html .= "<td>".nl2br($rowDiscount['description'])."</td>";
                $html .= "</tr>";
            }
        }
    }
    return $html;
}

// Generate Add row Sponsor
function getSponsorAddRow( $campaign_code ,$voucher_id, $sponsor_number, $foc_type, $sponsor_amount, $sponsor_percent, $showing){
    if($showing)
        $display = 'style="display:none;"';
    $display_campaign_code = 'display:none;';
    if($foc_type != 'Referral')
        $display_campaign_code = 'display:none;';
    $tpl_addrow  = "<tr class='row_tpl_sponsor' $display>";
    $tpl_addrow .= '<td scope="col" align="center"><input type="hidden" name="voucher_id[]" class="voucher_id" value="'.$voucher_id.'"><select title="'.translate('LBL_CAMPAIGN_CODE','J_Sponsor').'" style="width: 75px; '.$display_campaign_code.'" class="campaign_code" name="campaign_code[]">'.get_select_options_with_id($GLOBALS['app_list_strings']['campaign_code_list'],$campaign_code).'</select><input size="10" name="sponsor_number[]" style="text-transform: uppercase;" class="sponsor_number" value="'.$sponsor_number.'" type="text"></td>';

    $tpl_addrow .= '<td align="center"><select name="foc_type[]" class="foc_type">'.get_select_options_with_id($GLOBALS['app_list_strings']['foc_type_payment_list'],$foc_type).'</select></td>';

    $tpl_addrow .= '<td nowrap align="center"><input class="currency sponsor_amount" type="text" name="sponsor_amount[]" size="10" value="'.$sponsor_amount.'" style="font-weight: bold;"></td>';

    $tpl_addrow .= '<td nowrap align="center"><input class="currency sponsor_percent" type="text" name="sponsor_percent[]" size="10" value="'.$sponsor_percent.'" style="font-weight: bold;"></td>';

    $tpl_addrow .= "<td align='center'><button type='button' class='btnRemoveSponsor'><img src='themes/default/images/id-ff-remove-nobg.png' alt='Remove'></button></td>";
    $tpl_addrow .= '</tr>';
    return $tpl_addrow;
}

function getBookList($book_selected){
    return $GLOBALS['db']->fetchArray("SELECT DISTINCT
        IFNULL(product_templates.id, '') primaryid,
        IFNULL(product_templates.name, '') name,
        product_templates.list_price list_price,
        product_templates.unit unit,
        IFNULL(l2.name, '') parent,
        IFNULL(l2.id, '') parent_id,
        IFNULL(l2.list_order, '') parent_order,
        IFNULL(l1.name, '') category,
        IFNULL(l1.id, '') category_id,
        IFNULL(l1.list_order, '') list_order
        FROM
        product_templates
        INNER JOIN
        product_categories l1 ON product_templates.category_id = l1.id
        AND l1.deleted = 0
        LEFT JOIN
        product_categories l2 ON l1.parent_id = l2.id AND l2.deleted = 0
        WHERE
        (((product_templates.status2 = 'Active')))
        AND product_templates.deleted = 0
    ORDER BY CAST(l1.list_order AS UNSIGNED INTEGER), name ASC");
}