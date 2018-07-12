<?php
    require_once("modules/Calendar/CalendarUtils.php");
      /**
    * Get this Week
    * $dateinweek[d-m-Y] = D</br>d
    */
    function getweek(){

        global $timedate;

        $stardate_obj=CalendarUtils::get_first_day_of_week($timedate->getNow());
        $stardate = $stardate_obj->date;

        $enddate_obj = $stardate_obj->get("+7 day");
        $enddate = $enddate_obj->date;

        $dateinweek = array();
        while ($stardate_obj->getTimestamp() < $enddate_obj->getTimestamp() ) {
            $dateinweek[$stardate_obj->format("Y-m-d")] = $stardate_obj->format("D").'</br>'.$stardate_obj->format("d"); 
            $stardate_obj = $stardate_obj->get("+1 day");
        }
        return $dateinweek;
    }
    /**
    * Get this Month
    * 
    */
    function getmonth(){
        global $timedate;

        $month_start_obj = $timedate->getNow()->get_day_by_index_this_month(0);
        $month_end_obj = $month_start_obj->get("+".$month_start_obj->format('t')." days");

        $dateinmonth= array();

        while ($month_start_obj->getTimestamp() < $month_end_obj->getTimestamp() ) {
            $dateinmonth[$month_start_obj->format("Y-m-d")] = $month_start_obj->format("D").'</br>'.$month_start_obj->format("d"); 
            $month_start_obj = $month_start_obj->get("+1 day");
        }
        return $dateinmonth;
    }
    /**
    * Get currentday
    * 
    */
    function getcurrentday(){
        $today=date('Y-m-d');
        $currentday= array();
        $d = date('d',strtotime($today)).'</br>'.date('D',strtotime($today));
        $dm= date('Y-m-d',strtotime($today));
        $currentday[$dm] =$d;
        return $currentday;
    }
    /**
    * Get custom month
    * 
    */
    function getcmonth($month){
        $start = date('Y-m-d', mktime(0,0,0,$month,01,date('Y')));
        $end  = date('Y-m-t', mktime(0,0,0,$month,01,date('Y')));
        $dateofmonth= array();
        while(strtotime($start) <= strtotime($end)){
            $d= date('d', strtotime($start)).'</br>'.date('D', strtotime($start));
            $dm = date('Y-m-d', strtotime($start));
            $dateofmonth[$dm] = $d;
            $start = date('Y-m-d', strtotime('+1 day',strtotime($start))); 
        }
        return $dateofmonth;
    } 
?>
