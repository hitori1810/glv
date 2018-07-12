<?php
    //Get list teaching sesssion
    function get_list_teaching_session($id, $start_date, $end_date, $week_dates, $start_time, $end_time){
        global $timedate;
        $ss_list = array();
        foreach($week_dates as $wd){
            $dates = get_array_weekdates($start_date, $end_date, $wd);
            foreach($dates as $dt){
                //Change to DB format
                $db_start = $timedate->to_db($dt.' '.$start_time);
                $db_end = $timedate->to_db($dt.' '.$end_time);
                $db_date = $timedate->to_db_date($dt,false);

                $sql = "SELECT DISTINCT id, name FROM meetings
                WHERE teacher_id = '$id'
                AND ((('$db_start' >= date_start) AND ('$db_start' < date_end))
                OR (('$db_end' > date_start) AND ('$db_end' <= date_end)) 
                OR (('$db_start' < date_start) AND ('$db_end' > date_end)))
                AND meeting_type = 'Session' AND deleted=0";
                $result = $GLOBALS['db']->query($sql);
                while($row = $GLOBALS['db']->fetchByAssoc($result)){
                    $ss_list[$row['id']] = $row['name'];    
                }
            }   
        }
        return $ss_list;    
    }

    /**
    * Check teacher is free in range date
    * 
    * @param id 
    * @param dd/mm/yyyy
    * @param dd/mm/yyyy
    * @param array many WeekDays
    * @param time
    * @param time
    * @return bool true => free
    */   
    function check_teacher_range($id, $start_date, $end_date, $week_dates, $start_time, $end_time){
        global $timedate;
        foreach($week_dates as $wd){
            $dates = get_array_weekdates($start_date, $end_date, $wd);
            foreach($dates as $dt){
                //Change to DB format
                $db_start = $timedate->to_db($dt.' '.$start_time);
                $db_end = $timedate->to_db($dt.' '.$end_time);
                $db_date = $timedate->to_db_date($dt,false);
                $isfree = check_teacher($id, $db_start, $db_end) && check_teacher_holiday($id,$db_date);
                if(!$isfree){
                    return false;
                }
            }   
        }
        return true;    
    }
    /**
    * Check Room is free in range date
    * 
    * @param id 
    * @param dd/mm/yyyy
    * @param dd/mm/yyyy
    * @param many WeekDays
    * @param time
    * @param time
    * @return bool true => free
    */
    function check_room_range($id, $start_date, $end_date, $week_dates, $start_time, $end_time){
        global $timedate;
        foreach($week_dates as $wd){
            $dates = get_array_weekdates($start_date, $end_date, $wd);

            foreach($dates as $dt){
                //Change to DB format
                $db_start = $timedate->to_db($dt.' '.$start_time);
                $db_end = $timedate->to_db($dt.' '.$end_time);

                $isfree = check_room($id, $db_start, $db_end);
                if(!$isfree){
                    return false;
                }
            }   
        }
        return true;  
    }


    /**
    * Check teacher is free in range date
    * 
    * @param id 
    * @param dd/mm/yyyy
    * @param dd/mm/yyyy
    * @param 1 Weekdate
    * @param time
    * @param time
    * @return bool true => free
    */   
    function check_teacher_date($id, $start_date, $end_date, $week_dates, $start_time, $end_time){
        global $timedate; 
        if(check_teacher_contract_date($id, $timedate->to_db_date($end_date,false))){
            $dates = get_array_weekdates($start_date, $end_date, $week_dates);
            foreach($dates as $dt){

                //Change to DB format
                $db_start = $timedate->to_db($dt.' '.$start_time);
                $db_end = $timedate->to_db($dt.' '.$end_time);
                $db_date = $timedate->to_db_date($dt,false);

                $isfree = check_teacher($id, $db_start, $db_end) && check_teacher_holiday($id,$db_date) && check_teacher_contract_date($id, $timedate->to_db($start_date), $timedate->to_db($end_date));
                if(!$isfree){
                    return false;
                }  
            }
            return true;
        }
        else return false;  
    }
    /**
    * Check Room is free in 1 Weekdate
    * 
    * @param id 
    * @param dd/mm/yyyy
    * @param dd/mm/yyyy
    * @param Weekdate
    * @param time
    * @param time
    * @return bool true => free
    */
    function check_room_date($id, $start_date, $end_date, $week_dates, $start_time, $end_time){
        global $timedate;
        $dates = get_array_weekdates($start_date, $end_date, $week_dates);
        foreach($dates as $dt){

            //Change to DB format
            $db_start = $timedate->to_db($dt.' '.$start_time);
            $db_end = $timedate->to_db($dt.' '.$end_time);


            $isfree = check_room($id, $db_start, $db_end);
            if(!$isfree){
                return false;
            }
        }   
        return true;  
    }

    /**
    * Check teacher is free in 1 day
    * 
    * @param id
    * @param dd/mm/yyyy
    * @param time
    * @param time
    * @return bool true => free
    */
    function check_teacher($id, $db_start, $db_end){

        $sql = "SELECT DISTINCT id FROM meetings
        WHERE teacher_id = '$id'
        AND ((('$db_start' >= date_start) AND ('$db_start' < date_end))
        OR (('$db_end' > date_start) AND ('$db_end' <= date_end)) 
        OR (('$db_start' < date_start) AND ('$db_end' > date_end)))
        AND deleted=0";
        $id = $GLOBALS['db']->getOne($sql);

        if(!empty($id)) return false;
        else return true;
    }

    /**
    * Check room is free in 1 day
    * 
    * @param id
    * @param dd/mm/yyyy
    * @param time
    * @param time
    * @return bool true => free
    */
    function check_room($id, $db_start, $db_end){

        $sql = "SELECT DISTINCT id FROM meetings
        WHERE room_id = '$id'
        AND ((('$db_start' >= date_start) AND ('$db_start' < date_end))
        OR (('$db_end' > date_start) AND ('$db_end' <= date_end)) 
        OR (('$db_start' < date_start) AND ('$db_end' > date_end)))  
        AND deleted=0";
        $id = $GLOBALS['db']->getOne($sql);

        if(!empty($id)) return false;
        else return true; 
    }

    function check_teacher_holiday($id, $db_date)
    {
        $sql = "SELECT id FROM holidays
        WHERE teacher_id = '$id'
        AND holiday_date = '$db_date' AND deleted = 0";
        $id = $GLOBALS['db']->getOne($sql);

        if(!empty($id)) return false;
        else return true;
    }
    //create by leduytan 8/11/2014 check teacher contract date
    function check_teacher_contract_date($id, $end_date)
    {
        $sql = "SELECT id from c_teachers
        WHERE id = '$id' AND contract_until >= '$end_date' AND deleted = '0'";
        $id = $GLOBALS['db']->getOne($sql); 
        if(empty($id)) return false;
        else return true;
    }
    // END: Function check_teacher 
    // END: Function check_room


    /**
    * Get array week days from date to date
    * 
    * @param display date $start_date. eg:1/12/2013
    * @param display date $end_date. eg:30/12/2013
    * @param day of week $weekdate. eg:Tue
    * @return array eg: : array = 0: string = "03/12/2013"  1: string = "10/12/2013"
    */
    function get_array_weekdates($start_date, $end_date, $weekdate){
        global $timedate;
        date_default_timezone_set("Asia/Bangkok");
        // $start_date = $start_date.' 00:00:00';
        //  $end_date = $end_date.' 23:59:59';
        $start = strtotime($timedate->to_db_date($start_date,false));
        $end = strtotime($timedate->to_db_date($end_date,false));
        $end = strtotime('+ 23 hours', $end);

        $days = array();
        $i = 0;

        while($start <= $end){
            if (date('D', $start) == $weekdate){
                $days[$i]=$timedate->to_display_date(date('Y-m-d', $start));
                $i++;
            }
            $start = strtotime('+1 day', $start);
        }
        return $days;
    }
    /**
    * Get array week days from date to date DB Format
    * 
    * @param display date $start_date. eg:1/12/2013
    * @param display date $end_date. eg:30/12/2013
    * @param day of week $weekdate. eg:Tue
    * @return array eg: : array = 0: string = "03/12/2013"  1: string = "10/12/2013"
    */
    function get_array_weekdates_db($start_date, $end_date, $weekdate){
        global $timedate;
        date_default_timezone_set("Asia/Bangkok");
        // $start_date = $start_date.' 00:00:00';
        //  $end_date = $end_date.' 23:59:59';
        $days = array();
        $i = 0;
            $start = strtotime($timedate->to_db_date($start_date,false));
            $end = strtotime($timedate->to_db_date($end_date,false));
            $end = strtotime('+ 23 hours', $end);
            while($start <= $end){
                if (array_key_exists(date('D', $start), $weekdate)){
                    $days[$i]=date('Y-m-d', $start);
                    $i++;
                }
                $start = strtotime('+1 day', $start);
            }    
        return $days;
    }
    //END: Function: get_array_weekdates

    /**
    * Get number week days from date to date
    * 
    * @param display date $start_date. eg:1/12/2013
    * @param display date $end_date. eg:30/12/2013
    * @param day of week $weekdate. eg:Tue
    * @return array eg: : array = Wed: string = "10"  Thu: string = "9"
    */
    function get_number_weekdates($start_date, $end_date, $weekdate){
        global $timedate;
        date_default_timezone_set("Asia/Bangkok");
        // $start_date = $start_date.' 00:00:00';
        //  $end_date = $end_date.' 23:59:59';
        $start = strtotime($timedate->to_db_date($start_date,false));
        $end = strtotime($timedate->to_db_date($end_date,false));
        $end = strtotime('+ 23 hours', $end);

        $days = array();
        $count = 0;

        while($start <= $end){
            if (date('D', $start) == $weekdate){
                $count++;
            }
            $start = strtotime('+1 day', $start);
        }
        return $count;
    }
    //END
    
    ////////////////////////////---------------------------------////////////////////////////
    ///////////////////////////Check teacher - Check Room Junior//////////////////////////// 
    
    function check_teacher_junior($id, $db_start, $db_end){

        $sql = "SELECT DISTINCT id FROM meetings
        WHERE teacher_id = '$id'
        AND ((('$db_start' >= date_start) AND ('$db_start' < date_end))
        OR (('$db_end' > date_start) AND ('$db_end' <= date_end)) 
        OR (('$db_start' < date_start) AND ('$db_end' > date_end)))
        AND (meeting_type = 'Placement Test' OR meeting_type = 'Demo') AND deleted=0";
        $id = $GLOBALS['db']->getOne($sql);

        if(!empty($id)) return false;
        else return true;
    }

    /**
    * Check room is free in 1 day
    * 
    * @param id
    * @param dd/mm/yyyy
    * @param time
    * @param time
    * @return bool true => free
    */
    function check_room_junior($id, $db_start, $db_end){

        $sql = "SELECT DISTINCT id FROM meetings
        WHERE room_id = '$id'
        AND ((('$db_start' >= date_start) AND ('$db_start' < date_end))
        OR (('$db_end' > date_start) AND ('$db_end' <= date_end)) 
        OR (('$db_start' < date_start) AND ('$db_end' > date_end)))  
        AND (meeting_type = 'Placement Test' OR meeting_type = 'Demo') AND deleted=0";
        $id = $GLOBALS['db']->getOne($sql);

        if(!empty($id)) return false;
        else return true; 
    }
?>
