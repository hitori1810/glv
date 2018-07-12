<?php
switch ($_POST['type']) {
    case 'ajaxSaveGeneralConfig':
        echo ajaxSaveGeneralConfig();
        break;  
    case 'ajaxApplyVoucherConfig':
        echo ajaxApplyVoucherConfig();
        break;   
}    

function ajaxSaveGeneralConfig(){      
    $admin = new Administration();  
    $admin->retrieveSettings();                                                                   
    $admin->saveSetting('custom', 'default_mapping_lead', $_POST['default_mapping_lead']);        
    
    $admin->saveSetting('custom', 'refer_voucher_type', $_POST['refer_voucher_type']);
    $admin->saveSetting('custom', 'refer_voucher_value', $_POST['refer_voucher_value']);

    return json_encode(array(
        "success" => 1,              
    ));
}

function ajaxApplyVoucherConfig(){
    $voucherType    = $_POST['refer_voucher_type'];
    $voucherValue   = $_POST['refer_voucher_value'];
    
    if($voucherType == 'amount'){
        $amount     = $voucherValue;    
        $percent    = 0;    
    }
    else{
        $amount     = 0;    
        $percent    = $voucherValue;    
    }
    
    $sql = "
    UPDATE j_voucher
    SET discount_amount = $amount
    , discount_percent = $percent
    WHERE deleted <> 1
    AND student_id IS NOT NULL
    AND student_id <> ''
    ";
    $GLOBALS['log']->fatal($sql);
    
    return json_encode(array(
        "success" => 1,              
    ));
}
?>
