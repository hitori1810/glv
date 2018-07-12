<?php
    // Prepare Data
    if($_POST['location'] == '181 PHO HUE')
        $team_id = 'bb96d785-f77a-b077-ba26-543305f988ed';
    elseif($_POST['location'] == 'HITC 239 XUAN THUY')
        $team_id = '4676d1e2-8d4e-5611-38e8-537da8e79494';
    elseif($_POST['location'] == '277 NGUYEN TRAI')
        $team_id = '86fbaa73-7941-e54b-acab-5358cbbb40df';

    if($_POST['type'] == 'STUDENT')
        $description = "ĐĂNG KÝ TƯ VẤN TỪ WEBSITE - BẠN NÀY LÀ SINH VIÊN";
    elseif($_POST['type'] == 'WORKER')
        $description = "ĐĂNG KÝ TƯ VẤN TỪ WEBSITE - BẠN NÀY LÀ NGƯỜI ĐI LÀM";

    $data = array(
        'first_name'  =>  $_POST['name'],
        'email1'  =>  $_POST['email'],
        'birthdate'  =>  $_POST['year'],
        'phone_mobile'  =>  $_POST['phone'],
        'team_id'  =>  $team_id,
        'team_set_id'  =>  $team_id,
        'issues_content'  =>  'Câu hỏi: '.mysql_real_escape_string($_POST['question']),
        'description'  =>  mysql_real_escape_string($description),
        'lead_source'  =>  'Web Site',
        'status'  =>  'New',
        'potential'  =>  'High',
        'working_date'  =>  date('Y-m-d'),        
    );
    if(count($data) > 1){
        if(quick_insert('Leads',$data))
            echo json_encode(array("success" => "1"));
        else
            echo json_encode(array("success" => "0"));
    }else
        echo json_encode(array("success" => "0"));


    //** Function Quick Create ******

    function quick_insert($table, $data){
        $fields = array_keys($data);
        $values = array_values($data);
        
   //     $GLOBALS['log']->fatal("INSERT INTO $table(".implode(",",$fields).") VALUES ('".implode("','", $values )."');");
        $obj = new Lead();
        $obj->disable_row_level_security = true;
        foreach ($data as $field => $value) {
            $obj->$field = $value;   
        }
        $obj->save();
        
        if(!empty($obj->id)) return true;
        else return false;   
    }
