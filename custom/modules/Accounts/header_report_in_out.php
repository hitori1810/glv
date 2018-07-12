<?php
    $filter = $this->where;
    $parts = explode("AND", $filter);



    // Ve Layout


    $html = "<h2>Lay In: $lay_in </h2><br>";
    $html .= "<h2>Early Out: $ear_out </h2><br>";
    $html .= "<h2>OverTime: $overtime </h2><br>";
    $html .= "<h2>Out without purpose: $out_without</h2><br>";
    $html .= "<h2>Absent dates: $absent_date m </h2><br>";
    $html .= "<h2>Total days worked: $total_worked (days)</h2><br><br>";
    echo  $html;


    //END

    //Sample Data:
    /**
    * $data = array(
    'username'  =>  'userTest',
    'email'     =>  'emailTest',
    'password'  =>  'passwordTest'
    );
    $qry = qry_insert('users',$data);
    */
    function qry_insert($table, $data){
        $fields = array_keys($data);
        $values = array_map( "mysql_real_escape_string", array_values( $data ) );
        return $GLOBALS['db']->query("INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');");
    }
