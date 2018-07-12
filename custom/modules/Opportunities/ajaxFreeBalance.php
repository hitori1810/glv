<?php

    if(!empty($_POST['parent_id'])){
        $student_id = $_POST['parent_id'];
        if($_POST['parent_type'] == 'Leads')
            $student_id = $GLOBALS['db']->getOne("SELECT contact_id FROM leads WHERE id = '{$_POST['parent_id']}' AND deleted = 0"); 
        $sql = "SELECT DISTINCT
        IFNULL(c_payments.id, '') primaryid,
        IFNULL(c_payments.payment_type, '') payment_type,
        c_payments.payment_amount payment_amount,
        c_payments.remain remain
        FROM
        c_payments
        INNER JOIN
        contacts_c_payments_1_c l1_1 ON c_payments.id = l1_1.contacts_c_payments_1c_payments_idb
        AND l1_1.deleted = 0
        INNER JOIN
        contacts l1 ON l1.id = l1_1.contacts_c_payments_1contacts_ida
        AND l1.deleted = 0
        WHERE
        (((l1.id = '$student_id')
        AND (c_payments.payment_type IN ('FreeBalance' , 'Moving in', 'Transfer in', 'Deposit'))
        AND (c_payments.remain > 0)))
        AND c_payments.deleted = 0";

        $payment_list = $GLOBALS['db']->fetchArray($sql);
        $html = "<select name='payment_list[]' multiple id='payment_list'>";
        for($i =0 ; $i < count($payment_list) ; $i++){
            if($i == 0)
                $sle = "selected";
            else
                $sle = "";
            $html .= "<option $sle amount='".format_number($payment_list[$i]['remain'])."' value='{$payment_list[$i]['primaryid']}'>{$payment_list[$i]['payment_type']} : ".format_number($payment_list[$i]['remain'])."</option>";  
        }
        $html .= "</select>";
        //Javascript
    $js = <<<EOQ
    <script type="text/javascript">
        $('#payment_list').change(Calculated);  
    </script>
EOQ;
        echo json_encode(array(
            "success" => "1",
            "html" => $html.$js,
        ));
    }else{
        echo json_encode(array(
            "success" => "0",
            "html" => '',
        ));
    }
?>
