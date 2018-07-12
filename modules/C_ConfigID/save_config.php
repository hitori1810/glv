<?php
    //if($code_field == 'new_field'){
    //            $the_name = $dictionary[$module_name]['fields'][$_POST['custom_field']];
    //            $the_array = array();
    //            $the_array['name'] = 101;
    //            $the_array[] = 'Add Auto-Increment Code';
    //            $the_array[] = "modules/C_ConfigID/AutoCode.php";
    //            $the_array[] = "AutoCode";
    //            $the_array[] = "addCode";
    //               
    //         write_array_to_file()   
    //        }

    if(isset($_POST) && count($_POST)){
        $module_name    = $_POST['module_name'];
        $code_field     = $_POST['code_field'];
        $prefix         = $_POST['name'];
        $code_separator = $_POST['code_separator'];
        $is_reset       = $_POST['is_reset'];
        $date_format    = $_POST['date_format'];
        if(!empty($date_format))
            $add_date = '1';  
        else
            $add_date = '0';

        if($date_format == 'custom')
            $_format = $_POST['custom_format'];
        else $_format = $date_format;  


        $zero_padding = $_POST['zero_padding'];
        $first_num = $_POST['first_num'];
        $record_id = $_POST['id'];
        $action = $_POST['act'];
        if($action == "save" || $action == "update"){
            if($action == "save")
                $cf = BeanFactory::newBean('C_ConfigID');
            elseif($action == "update")    
                $cf = BeanFactory::getBean('C_ConfigID',$record_id);
            $cf->module_name    = $module_name;
            $cf->custom_table   = strtolower($module_name);
            $cf->code_field     = $code_field;
            $cf->name           = $prefix;
            $cf->code_separator = $code_separator;
            $cf->is_reset       = $is_reset;
            $cf->add_date       = $add_date;
            $cf->date_format    = $_format;
            $cf->zero_padding   = $zero_padding;
            $cf->first_num      = $first_num;
            $cf->save();
            $id = $cf->id;        

            //add Logic hook
            $action_array = array();
            $action_array[] = 101;
            $action_array[] = 'Add Auto-Increment Code';
            $action_array[] = "modules/C_ConfigID/AutoCode.php";
            $action_array[] = "AutoCode";
            $action_array[] = "addCode";
            check_logic_hook_file($module_name,'before_save',$action_array);

            $json = array(
                'id'                => $id,
                'prefix'            => $prefix,
                'code_separator'    => $code_separator,
                'code_field'        => $code_field,
                'module_name'       => $module_name,
                'date_format'       => $_format,
                'is_reset'          => $is_reset,
                'zero_padding'      => $zero_padding,
                'first_num'         => $first_num,
            );
            $json_en = json_encode($json);


            if($id){
                echo json_encode(
                    array(
                        "success" => "1",
                        "record_id" => $id,
                        "json_en" => $json_en,
                        "name" => $prefix,
                        "code_separator" => $code_separator,
                        "code_field" => $code_field,
                        "module_name" => $module_name,
                        "add_date" => $add_date,
                        "date_format" => $_format,
                        "zero_padding" => $zero_padding,
                        "is_reset" => $is_reset,
                        "first_num" => $first_num,
                    )
                );
            }
            else{
                echo json_encode(array("success" => "0"));
            }
        }
        else if($action == "delete"){
            $sql = "DELETE FROM c_configid WHERE id='$record_id';";
            $res = $GLOBALS['db']->query($sql);
            if($res){
                echo json_encode(array( "success" => "1","record_id" => $record_id));
            }else{
                echo json_encode(array("success" => "0"));
            }    
        }
    }else{
        echo json_encode(array("success" => "0"));
}