<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class ContractsViewDetail extends ViewDetail {

    function ContractsViewDetail(){
        parent::ViewDetail();
    }

    function display() {
        if(isset($_SESSION['contract_id']))
            unset($_SESSION['contract_id']);
        $cr_number_of_student = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(contacts.id) contacts__allcount
            FROM
            contacts
            INNER JOIN
            contracts_contacts l1_1 ON contacts.id = l1_1.contact_id
            AND l1_1.deleted = 0
            INNER JOIN
            contracts l1 ON l1.id = l1_1.contract_id
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$this->bean->id}')))
            AND contacts.deleted = 0");

        //Load Payment Detail Contract
        global $timedate;
        $sql = "SELECT DISTINCT
        IFNULL(j_paymentdetail.id, '') primaryid,
        j_paymentdetail.payment_amount payment_amount,
        j_paymentdetail.payment_date payment_date,
        IFNULL(j_paymentdetail.invoice_number, '') invoice_number
        FROM
        j_paymentdetail
        INNER JOIN
        contracts l1 ON j_paymentdetail.contract_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$this->bean->id}')))
        AND j_paymentdetail.deleted = 0
        AND ((j_paymentdetail.payment_id IS NULL) OR (j_paymentdetail.payment_id = ''))
        AND j_paymentdetail.status = 'Paid'
        ORDER BY j_paymentdetail.payment_no ASC";
        $pmd_ct = $GLOBALS['db']->fetchArray($sql);
        $pmd = array();
        $count = 1;
        $paidAmount = 0;
        foreach($pmd_ct as $key=>$value){
            $pmd[$count]['pmd_id']         = $value['primaryid'];
            $pmd[$count]['pmd_amount']     = format_number($value['payment_amount']);
            $pmd[$count]['pmd_date']       = $timedate->to_display_date($value['payment_date'],false);
            $pmd_date = 'payment_date_'.$count;
            $this->bean->$pmd_date         = $pmd[$count]['pmd_date'];
            $pmd[$count]['pmd_invoice_no'] = $value['invoice_number'];
            $count++;
            $paidAmount += $value['payment_amount'];
        }
        $this->ss->assign('pmd',$pmd);
        $unpaidAmount = $this->bean->total_contract_value -  $paidAmount;
        $this->ss->assign('PAID_AMOUNT','<span class="textbg_bluelight" id="pmd_paid_amount">'.format_number($paidAmount).'</span>');
        $this->ss->assign('UNPAID_AMOUNT','<span class="textbg_redlight" id="pmd_unpaid_amount">'.format_number($unpaidAmount).'</span>');


        $this->ss->assign('card_type', $GLOBALS['app_list_strings']['card_type_payments_list']);
        $this->ss->assign('bank_type', $GLOBALS['app_list_strings']['bank_name_list']);
        $this->ss->assign('payment_method', $GLOBALS['app_list_strings']['payment_method_junior_list']);
        $this->ss->assign('module_name', 'Contracts');
        $this->ss->assign('number_student', $cr_number_of_student.'/'.$this->bean->number_of_student);
        //Load Account
        $account = BeanFactory::getBean('Accounts', $this->bean->account_id);
        $this->bean->account_phone = $account->phone_office;
        $this->bean->account_tax_code = $account->tax_code;
        $this->bean->account_bank_name = $account->bank_name;
        $this->bean->account_bank_number = $account->bank_number;
        $this->bean->account_address = $account->billing_address_street;
        $this->ss->assign('use_type', $GLOBALS['app_list_strings']['use_type_options'][$this->bean->use_type]);
        
        //Add by Tung Bui - assign payment method
        $this->ss->assign('payment_method_options', $GLOBALS['app_list_strings']['payment_method_junior_list']);
        
        parent::display();
    }
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['c_classes_contracts_1']);
        echo $subpanel->display();
    }
}
?>