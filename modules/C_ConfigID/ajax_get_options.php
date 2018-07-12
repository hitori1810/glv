<?php

    $module_name = $_POST['module_name'];
    $bean = BeanFactory::getBean($module_name);
    $field_defs = $bean->field_defs;
    $html = "<select name='code_field' id='code_field'><option value=''>-none-</option><option value='new_field'>**Create new field</option>";
    foreach($field_defs as $field_name => $field_def){
        if(($field_def['type'] == 'varchar' || $field_def['dbType'] == 'varchar') && $field_def['source'] != 'non-db'){
            $html .= "<option value='$field_name'>".str_replace(':','',translate($field_def['vname'],$module_name))."</option>";  
        }   
    }
    $html .= '</select>';
    
    echo json_encode(array(
        "success" => "1",
        "html" => $html,
    ));
?>
