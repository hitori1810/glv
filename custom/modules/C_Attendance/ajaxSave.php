<?php
    $cl= '';
    $class_reason = 'not_comment';
    $reason_id = 'editable_reason';
    $key= '';
    switch ($_POST['value']) {
        case 'L':
            $cl= 'late';
            break;  
        case 'A':
            $cl= 'notalowabsent';
            break;
        case 'P':
            $cl = 'present';
            break;
        default: 
            $class_reason = 'comment';
            $key = $_POST['value'];
            break;
    }
    $attendance_id = $_POST['attendance_id'];        
    if(!empty($_POST['value']) ){
        if($class_reason!='comment')
        {
            if(empty($attendance_id) || $attendance_id == 'null'){
                $attendance = new C_Attendance();
                $attendance->student_id=$_POST['student_id'];
                $attendance->class_id=$_POST['class_id'];
                $attendance->leaving_date= substr($_POST['datetime'], 0, 10);
                $attendance->meeting_id=$_POST['meeting_id'];
                $attendance->leaving_type = $_POST['value'];                 
                $attendance->save(); 
                $attendance_id = $attendance->id; 
            }else{
                $sql = "UPDATE c_attendance SET leaving_type='".$_POST['value']."', absent_reason = ''  WHERE id='".$_POST['attendance_id']."'";
                $result = $GLOBALS['db']->query($sql);               

            } 
        }
        else 
        {
            $sql = "UPDATE c_attendance SET absent_reason='".$_POST['value']."' WHERE id='$attendance_id'";
            $result = $GLOBALS['db']->query($sql);
            if($attendance_id=='null')
                $class_reason = "";
        }
        echo "$cl|$key|$class_reason|$attendance_id|$reason_id";
    }else{
       $q5 = "DELETE FROM c_attendance WHERE id='$attendance_id'";
       $result = $GLOBALS['db']->query($q5);  
    }
    
?>
