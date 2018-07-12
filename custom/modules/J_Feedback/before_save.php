<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class before_save_feedback {


    function make_full_type(&$bean, $event, $arguments){
        global $timedate;
        //before save feedback set name =type + relate
        if($bean->type_feedback_list == 'Customer'){
            //Add To Response
            $pre_feedback = $bean->fetched_row['feedback'];
            if(!empty($_POST['feedback'])){
                if(empty($pre_feedback)) $pre_feedback = '';
                else $pre_feedback .= '<br>';

                $bean->feedback = $pre_feedback.'<b>'.$timedate->nowDate().': </b><span>'.$_POST['feedback'].'</span>';
            }else $bean->feedback = $pre_feedback; 
            $bean->plain_text =  str_replace( "<br>", "\n", strip_tags(html_entity_decode($bean->feedback),'<br>'));
        }
    } 


    ///to mau id va status Quyen.Cao
    function listViewColorBack(&$bean, $event, $arguments){
        //to mau status
        switch ($bean->status) {
            case "New":
                $colorClass = "textbg_green"; 
                break;
            case "Assigned":
                $colorClass = "textbg_bluelight";
                break;
            case "In Progress":
                $colorClass = "textbg_blue";
                break;
            case "Done":
                $colorClass = "textbg_red";
                break;
        } 
        $bean->status = '<span class="'.$colorClass.'">'.$bean->status.'</span>'; 

    }           
}
?>