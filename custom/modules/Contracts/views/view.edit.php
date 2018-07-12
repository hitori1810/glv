<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ContractsViewEdit extends ViewEdit
{

    public function display()
    {
        global $timedate;
        if($_POST['isDuplicate'] == 'true'){
            $this->bean->status             = 'notstarted';
            $this->bean->contract_id        = translate('LBL_AUTO_GENERATE','Accounts');
        }else{
            if(empty($this->bean->id))
                $this->bean->contract_id    = translate('LBL_AUTO_GENERATE','Accounts');
        }
        // Get Spilt payment
        $sqlGetPayDetail = "SELECT DISTINCT
        IFNULL(payment_no, '0')         pay_no,
        IFNULL(payment_amount, '0')     pay_amount,
        IFNULL(before_discount, '0')    before_discount,
        IFNULL(discount_amount, '0')    discount_amount,
        IFNULL(payment_amount, '0')     pay_amount,
        payment_date invoice_date,
        IFNULL(type, '')                pay_type
        FROM j_paymentdetail
        WHERE contract_id = '{$this->bean->id}'
        AND ((payment_id IS NULL) OR (payment_id = ''))
        AND deleted = 0
        AND status <> 'Cancelled'
        ORDER BY pay_no;";
        $rsGetPayDetail = $GLOBALS['db']->query($sqlGetPayDetail);
        $payDtlStatus = array();
        while($payDetail = $GLOBALS['db']->fetchByAssoc($rsGetPayDetail)){
            $this->ss->assign('PAY_DTL_BEF_DISCOUNT_'.$payDetail['pay_no'],$payDetail['before_discount']);
            $this->ss->assign('PAY_DTL_DIS_AMOUNT_'.$payDetail['pay_no'],$payDetail['discount_amount']);
            $this->ss->assign('PAY_DTL_AMOUNT_'.$payDetail['pay_no'],$payDetail['pay_amount']);
            $this->ss->assign('PAY_DTL_TYPE_'.$payDetail['pay_no'],$payDetail['pay_type']);
            $this->ss->assign('PAY_DTL_INVOICE_DATE_'.$payDetail['pay_no'], $timedate->to_display_date($payDetail['invoice_date'],false));
            $payDtlStatus[$payDetail['pay_no']] = $payDetail['pay_status'];
        }

        //Load Account
        if(!empty($_POST['account_id']) && $_POST['return_module'] == 'Accounts'){
             $this->bean->account_id = $_POST['account_id'];
        }

        $account = BeanFactory::getBean('Accounts', $this->bean->account_id);
        $this->bean->account_name = $account->name;
        $this->bean->account_phone = $account->phone_office;
        $this->bean->account_tax_code = $account->tax_code;
        $this->bean->account_bank_name = $account->bank_name;
        $this->bean->account_bank_number = $account->bank_number;
        $this->bean->account_address = $account->billing_address_street;

        parent::display();
    }
}
?>
