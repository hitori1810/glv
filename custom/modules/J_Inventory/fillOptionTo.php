<?php

    //when type is export => option have 3 type: teacher, corp, center, in case fill value in option choose team to 
    $html= '<select style="margin-left: 20px;" class="choose_team_to" name="choose_team_to" id="choose_team_to">';
    if($_POST['to_list']=="Teacher/Library"){
        //select all Teacher/Library 
        $sql_teacher  = "select * from accounts where deleted=0 and type_of_account='Teacher/Library'"; 
        $result_teacher = $GLOBALS['db']->query($sql_teacher);
        while($choose_teacher = $GLOBALS['db']->fetchByAssoc($result_teacher)){
            if($choose_teacher['id']==$_POST['input_id_to'])  {
                $html.= '<option selected value="'.$choose_teacher['id'].'">'.$choose_teacher['name'].'</option>'; 
            } 
            else{
                $html.= '<option value="'.$choose_teacher['id'].'">'.$choose_teacher['name'].'</option>'; 
            }     
        }
    }
    if($_POST['to_list']=="Corp/BEP") {
        //select all Corp/BEP
        $sql_corp  = "select * from accounts where deleted=0 and type_of_account='Corp/BEP'"; 
        $result_corp = $GLOBALS['db']->query($sql_corp);
        while($choose_corp = $GLOBALS['db']->fetchByAssoc($result_corp)){
            if($choose_corp['id']==$_POST['input_id_to'])  {
                $html.= '<option selected value="'.$choose_corp['id'].'">'.$choose_corp['name'].'</option>';
            } 
            else{
                $html.= '<option value="'.$choose_corp['id'].'">'.$choose_corp['name'].'</option>'; 
            }      

        }
    }
    if($_POST['to_list']=="Center") {
        //select all Teams of Junior
        $sql_team_to  = "select * from teams where deleted=0 and private <> 1 and parent_id not in (1,'','896046f1-4445-2c4f-d553-5555cc344d8c','896046f1-4445-2c4f-d553-5555cc344d8c') "; 
        $result_to = $GLOBALS['db']->query($sql_team_to);
        while($choose_team_to = $GLOBALS['db']->fetchByAssoc($result_to)){
            if($choose_team_to['id']==$_POST['input_id_to'])  {
                $html.= '<option selected value="'.$choose_team_to['id'].'">'.$choose_team_to['name'].'</option>';
            } 
            else{
                $html.= '<option value="'.$choose_team_to['id'].'">'.$choose_team_to['name'].'</option>'; 
            }     
        }
    }
    $html .= '</select>';
    echo $html;
?>
