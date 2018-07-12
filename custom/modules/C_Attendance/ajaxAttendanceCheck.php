<?php
    require_once('custom/modules/C_Attendance/_helperdatetime.php');
    $class_id =  $_POST["class_id"];
    $temp = array();
    if($_POST["ac_for"]=="today")
        $temp = getcurrentday();
    else
        if($_POST["ac_for"]=="this_week")
            $temp = getweek();
        else
        {
            if($_POST["ac_for"]=="this_month")
                $temp = getmonth();
            else if ($_POST["ac_for"]=="option")
            {
                $temp = getcmonth($_POST['select_month']);
            }
    }

    if($temp)
    {
        $date_start_temp = reset(array_keys($temp));
        $date_end_temp = end(array_keys($temp));
        $sql = "
        SELECT date_start, id  
        FROM meetings
        WHERE class_id = '{$class_id}' AND deleted = 0 AND LEFT( date_start, 10 )  between '{$date_start_temp}' and '{$date_end_temp}' 
        ORDER BY date_start"; 
    }
    $result = $GLOBALS['db']->query($sql);
    $no = 0;
    $dates_month = array();
    $meeting_id = array();
    while($row = $GLOBALS['db']->fetchByAssoc($result)) {
        /*get day of week*/
        $day_of_week = $GLOBALS['timedate']->to_db_date($GLOBALS['timedate']->to_display_date($row['date_start']), false);
        /*get array of date*/
        $dates_month[$row['date_start']] = $GLOBALS['timedate']->to_display_date($row['date_start']);
        $meeting_id[$row['date_start']] = $row['id'];    
    }

    $title_from = $GLOBALS['timedate']->to_display_date($date_start_temp);
    $title_to = $GLOBALS['timedate']->to_display_date($date_end_temp);  

    $html = "<div class='page-header'nowrap>";
    $html .= "<h1>Result </h1>";
    if($_POST["ac_for"]=="today")
    {
        $html .= "<h2 style = 'color: blue;' align='center'>".translate('LBL_AC_TITLE','C_Attendance')." {$title_from}</h2>";        
    }
    else 
        $html .= "<h2 style = 'color: blue;' align='center'>".translate('LBL_AC_TITLE','C_Attendance')." ".translate('LBL_FROM','C_Attendance')." {$title_from}"." ".translate('LBL_TO','C_Attendance')." {$title_to}</h2>"; 
    $html .= "</div>";
    $html .= "<table width='100%' id='celebs' border='1'>";  
    $html .= "<thead><tr>";
    $html .= "<th rowspan='2' colspan='1' style='text-align: left; width: 20px'> </th>";
    $html .= "<th rowspan='2' colspan='1' style='text-align: left; width: 20%'>".translate('LBL_ID','Contacts')."</th>";
    $html .= "<th rowspan='2' colspan='1' style='text-align: left; width: 20%'>".translate('LBL_CONTACT_NAME','Contacts')."</th>";


    foreach($dates_month as $key=>$value){ 
        $day_of_week = date('D', strtotime($GLOBALS['timedate']->to_db_date($value, false)));
        $date_month = date('d', strtotime($GLOBALS['timedate']->to_db_date($value, false))); 
        date('Y-m-d') == date('Y-m-d', strtotime($GLOBALS['timedate']->to_db_date($value, false)))  ? $addclass = 'today' : $addclass = '';
        $html .= "<th class='$addclass' colspan='2' rowspan='1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$day_of_week} {$date_month}</th>"; 
    } 


    $html .= "</tr><tr>";
    foreach($dates_month as $key=>$value){ 
      $html .= "<th style='text-align: center;' rowspan='1' colspan='1' > Type </th>";  
      $html .= "<th style='text-align: left;' rowspan='1' colspan='1' > Comment </th>";  
    }
    $html .= "</tr></thead>";
    $html .= "<tbody>"; 


    $no = 1;
    $class = BeanFactory::getBean('C_Classes',$class_id);
    $class->load_relationship('c_classes_contacts_1');
    $student_beans = $class->c_classes_contacts_1->getBeans();

    foreach ($student_beans as $key => $student) {
        $html .= "<tr>";
        $html .= "<td > {$no} </td>";
        $html .= "<td > {$student->contact_id}</td>";
        $html .= "<td > {$student->name} </td>";        
        foreach($dates_month as $key_date=>$date){
            //$html .= "<td id='editable' datetime = '{$GLOBALS['timedate']->to_db_date($key_date)}'></td>";

            $html .= check_date($key_date,$student->id,$class_id, $meeting_id[$key_date]);
        }
        $html .= "</tr>";
        $no++;      
    }

    $html.= "</tbody>";
    $html .= "</table>";
    $js = '
    <script type="text/javascript">        
        $(document).ready(function() {
            var table = $("#celebs");
            var oTable = table.dataTable({
                "sPaginationType": "full_numbers",


                '.$fixcols1.'

                "bStateSave": true, oLanguage:{
                    "sLengthMenu": "'.$GLOBALS['app_strings']['LBL_JDATATABLE1'].'_MENU_'.$GLOBALS['app_strings']['LBL_JDATATABLE2'].'",
                    "sZeroRecords": "'.$GLOBALS['app_strings']['LBL_JDATATABLE13'].'",
                    "sInfo": "'.$GLOBALS['app_strings']['LBL_JDATATABLE6'].'_START_'.$GLOBALS['app_strings']['LBL_JDATATABLE7'].'_END_'.$GLOBALS['app_strings']['LBL_JDATATABLE8'].'_TOTAL_'.$GLOBALS['app_strings']['LBL_JDATATABLE2'].'",
                    "sInfoEmpty": "'.$GLOBALS['app_strings']['LBL_JDATATABLE15'].'",
                    "sEmptyTable": "'.$GLOBALS['app_strings']['LBL_JDATATABLE14'].'",
                    "sInfoFiltered": "",
                    "oPaginate": {
                    "sFirst": "'.$GLOBALS['app_strings']['LBL_JDATATABLE9'].'",
                    "sNext": "'.$GLOBALS['app_strings']['LBL_JDATATABLE10'].'",
                    "sPrevious": "'.$GLOBALS['app_strings']['LBL_JDATATABLE11'].'",
                    "sLast": "'.$GLOBALS['app_strings']['LBL_JDATATABLE12'].'", 
                    },
                    "sSearch": "'.$GLOBALS['app_strings']['LBL_JDATATABLE3'].'",
                    },});

                '.$fixcols2.' 

                $("#editable", oTable.fnGetNodes()).editable("index.php?module=C_Attendance&action=ajaxSave&sugar_body_only=true", {
                    placeholder : "",
                    indicator   : "<img src=\'themes/default/images/loading.gif\' style=\'width:15px; height:15px;\'/>",
                    data   : "'.getOptions().'",
                    type        : "select",
                    width       : "100px",
                    submit      : "OK",
                    height      : "140px",
                    "callback": function(sValue, y) {
                        var fetch = sValue.split("|");
                        $(this).attr("class", fetch[0]);
                        $(this).text("");
                        $(this).attr("attendance_id", fetch[3]);
                        $(this).closest("td").next().attr("class", fetch[2]);
                        $(this).closest("td").next().attr("attendance_id", fetch[3]);                   
                    },
                    submitdata  : function ( value, settings ) {
                        return { 
                            student_id    : this.getAttribute("student_id"),
                            class_id      : $("#class_id").val(),
                            datetime      : this.getAttribute("datetime"),
                            meeting_id    : this.getAttribute("meeting_id"),
                            attendance_id : this.getAttribute("attendance_id"),
                        };
                    },
                });
            
            




            '.'




            $("#editable_reason", oTable.fnGetNodes()).editable("index.php?module=C_Attendance&action=ajaxSave&sugar_body_only=true", {
                placeholder : "",
                indicator   : "<img src=\'themes/default/images/loading.gif\' style=\'width:15px; height:15px;\'/>",
                data   : "",
                type        : "text",
                width       : "90px",
                submit      : "OK",
                height      : "20px",
                "callback": function(sValue, y) {
                    var fetch = sValue.split("|");
                    $(this).attr("class", fetch[2]);
                    $(this).text("");
                    $(this).attr("absent_reason", fetch[1]);
                    $(this).attr("attendance_id", fetch[3]);
                    tooltip();                 
                },
                submitdata  : function ( value, settings ) {
                    return { 
                        student_id    : this.getAttribute("student_id"),
                        class_id      : $("#class_id").val(),
                        datetime      : this.getAttribute("datetime"),
                        meeting_id    : this.getAttribute("meeting_id"),
                        attendance_id : this.getAttribute("attendance_id"),
                        };
                    },
            }); 
                tooltip();       
            });
            
            
            
            
            function tooltip()
               {
                $.fn.qtip.styles.tooltipDefault = {
                            background  : "#FFFF99",
                            color       : "green",
                            textAlign   : "left",
                            border      : {
                                width   : 2,
                                radius  : 4,
                                color   : "#C1CFDD"
                            },
                            width       : 100
                        }

                       
               $( ".comment" ).each( function( ) {
                        $( this ).qtip( {
                            content     : $(this).attr("absent_reason"),
                            position    : {
                                    target  : "mouse"
                                    },
                            style       : "tooltipDefault"
                            } );
                        } );
           };    
    
    
    
    
    </script>';
    function check_date($date,$student_id,$class_id, $meeting_id){
        // $date = str_replace('/','-',$date).'-'.date('Y');
        $class = '';
        $absent_reason_id = "id = 'editable_reason'";
        $editable_reason = '';
        $class_comment = "class = ''";
        $editable = "id='editable'";
        $absent_reason = "absent_reason = ''";
        $datetime="datetime='$date'";
        $meetingid = "meeting_id = '{$meeting_id}'";
        $studentid ="student_id = '{$student_id}'";

        $sql2 = "SELECT id, leaving_type, absent_reason FROM c_attendance WHERE student_id='{$student_id}' AND meeting_id='$meeting_id' AND deleted='0'";
        $result2 = $GLOBALS['db']->query($sql2);
        $row2 = $GLOBALS['db']->fetchByAssoc($result2);

        if($row2['id']!= ''){
            $attendance_id = "attendance_id = '{$row2['id']}'";
            $value = $row2['leaving_type'];
            switch ($value) {
                case '':
                    $class .= 'class = "unapproved"';
                    break;
                case 'A':
                    $class .= 'class = "notalowabsent"';
                    break;
                case 'L':
                    $class .= 'class = "late"';
                    break;
                case 'P':
                    $class .= 'class = "present"';
                    break;
            }
            if($row2['absent_reason']!='')
            {
                $class_comment = "class = 'comment'";
                $absent_reason = "absent_reason = '".$row2['absent_reason']."'";                        
            }
            else
                $class_comment = "class = 'not_comment'";


        }
        $rs = "<td $class id = 'editable' $datetime $studentid $meetingid $attendance_id></td>";
        $rs .= "<td  $class_comment $absent_reason_id $datetime $studentid $meetingid $attendance_id $absent_reason></td>"; 
        return $rs;
    }
    $help .= "<br><br><br><h3 style='color:blue'>Legends</h3>";
    $help .= "<img src='custom/include/images/icon_persent.png' border='0' width='16' height='16' alt='Present'> Present <br>";
    $help .= "<img src='custom/include/images/icon_late.png' border='0' width='16' height='16' alt='Late'> Late <br>";
    $help .= "<img src='custom/include/images/icon_unescused.png' border='0' width='16' height='16' alt='Absence'> Absence <br>";
    $help .= "<img src='custom/include/images/icon_comment.png' border='0' width='16' height='16' alt='Absent Reason Saved'> With Comment<br>";
    $help .= "<img src='custom/include/images/icon_not_comment.png' border='0' width='16' height='16' alt='Absent Reason NOT Saved'> No Comment";
    echo json_encode(array(
        "success" => "1",
        "html" => $html.$js,
        "help" => $help,
    ));
    function getOptions(){
        $sl = "{";
        foreach($GLOBALS['app_list_strings']['leaving_type_student_list'] as $key => $value){
            $sl .= "'$key':'$value',";  
        }
        $sl .= '}';
        return $sl;    
    }

?>
