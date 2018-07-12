<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class OpportunitiesViewDetail extends ViewDetail {

    function OpportunitiesViewDetail(){
        parent::ViewDetail();

    }
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);

        /*Sub-Panel buttons hiding code*/
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][0]);//hiding create
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][1]);//hiding select
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']['top_buttons'][0]);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']['top_buttons'][1]);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['quotes']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['opportunities_c_refunds_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contracts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['documents']);

        echo $subpanel->display();
    }
    function display() {
        global $timedate;
        //Dialog
        echo $GLOBALS['app_strings']['LBL_THONGBAO_VAOLOP'];

        $currency = new Currency();
        if(isset($this->bean->currency_id) && !empty($this->bean->currency_id))
        {
            $currency->retrieve($this->bean->currency_id);
            if( $currency->deleted != 1){
                $this->ss->assign('CURRENCY', $currency->iso4217 .' '.$currency->symbol);
            }else {
                $this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());
            }
        }else{
            $this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());
        }
        //Show tax rate - By Lap Nguyen
        $this->ss->assign('TAX_RATE', $this->bean->tax_rate = format_number($this->bean->tax_rate * 100,2,2));
        //Custom Pop-up create payment in Detail

        if($this->bean->sales_stage != 'Deleted'){

            require_once('custom/modules/Opportunities/getElements.php');
            $total_pay = 0;
            $total_unpaid = 0;
            if($this->bean->c_invoices_opportunities_1c_invoices_ida == "" && $this->bean->sales_stage != 'Deleted'){
                $bt = '<input type="button" class="button" value="Generate Invoice" id="gerenare_invoice">';
                $html =  html('INV');
                $this->ss->assign('CUSTOM_BUTTON',$bt);
                $this->ss->assign('CUSTOM_CODE',$html);
            } else {
                $q1 = "SELECT DISTINCT IFNULL(c_payments.id,'') primaryid ,c_payments.payment_amount c_payments_payment_amount ,c_payments.payment_attempt c_payments_payment_attempt ,IFNULL( c_payments.currency_id,'') C_PAYMENTS_PAYMENT_AMOF1CE92 ,IFNULL(c_payments.status,'') c_payments_status ,c_payments.payment_date c_payments_payment_date FROM c_payments INNER JOIN c_invoices_c_payments_1_c l1_1 ON c_payments.id=l1_1.c_invoices_c_payments_1c_payments_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_c_payments_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_opportunities_1_c l2_1 ON l1.id=l2_1.c_invoices_opportunities_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.c_invoices_opportunities_1opportunities_idb AND l2.deleted=0 WHERE (((l2.id='{$this->bean->id}' ))) AND c_payments.deleted=0 AND c_payments.remain=0  ORDER BY c_payments_payment_attempt ASC";
                $rs1 = $GLOBALS['db']->query($q1);
                while($r1 = $GLOBALS['db']->fetchByAssoc($rs1)){
                    if($r1['c_payments_status'] == "Unpaid"){
                        $button_html .= "<a id='{$r1['primaryid']}' class='payment-popup' payment-amount='{$r1['c_payments_payment_amount']}' style='text-decoration: none; cursor: pointer;'><span class='textbg_orange'>".format_number($r1['c_payments_payment_amount'],0,0)."</span></a> ";
                        $total_unpaid += $r1['c_payments_payment_amount'];
                    }elseif($r1['c_payments_status'] == "Deleted"){
                        $button_html .= "<a style='text-decoration: none;' id='{$r1['primaryid']}' href='index.php?module=C_Payments&return_module=Opportunities&action=DetailView&record={$r1['primaryid']}'><span class='textbg_black'>".format_number($r1['c_payments_payment_amount'],0,0)."</span></a> ";
                    }
                    elseif($r1['c_payments_status'] == "Paid"){
                        $button_html .= "<a style='text-decoration: none;' id='{$r1['primaryid']}' href='index.php?module=C_Payments&return_module=Opportunities&action=DetailView&record={$r1['primaryid']}'><span class='textbg_green'>".format_number($r1['c_payments_payment_amount'],0,0)."</span></a> ";
                        $total_pay += $r1['c_payments_payment_amount'];
                        if($r1['c_payments_payment_date'] >= '2016-10-01')
                            $total_unpaid+= $r1['c_payments_payment_amount'];
                    }
                }
                $this->ss->assign('BUTTON_HTML',$button_html);
                $this->ss->assign('ct_date','01/10/2016');
                $this->ss->assign('ct_unpaid_amount',format_number($total_unpaid));
                $this->ss->assign('PAYPOPUP', html('PAY'));
            }

            //button close enrollment
            if(ACLController::checkAccess('C_DeliveryRevenue', 'edit', false) && $this->bean->sales_stage != 'Closed'){
                $bt_close = '<input type="button" class="button" id="close_enrollment" value="Close Enrollment & Drop Balance">
                <input type="hidden" id="total_payment_amount" value="'.$total_pay.'">';
                $this->ss->assign('BT_CLOST_ENR',$bt_close);
            }
            echo '<div id="dialog-confirm_10" title="Thông báo" style="display:none;">
            <form name="Close_enrollment">
            <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;">
            </span>Bạn có muốn kết thúc khóa học này ko? Số dư của học viên sẽ được tính dựa vào ngày kết thúc khóa học. Vui lòng làm theo từng bước bên dưới.
            <br><br> <b>Bước 1:</b> Chọn ngày kết thúc khóa học: <span class="dateTime"><input disabled name="expire_date" size="10" id="expire_date" type="text" value="'.$timedate->nowDate().'">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="expire_date_trigger" align="absmiddle"></span>
            <script type="text/javascript">
            Calendar.setup ({
            inputField : "expire_date",
            daFormat : cal_date_format,
            daFormat : cal_date_format,
            button : "expire_date_trigger",
            singleClick : true,
            dateStr : "'.$timedate->nowDate().'",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
            }
            );
            addToValidate("Close_enrollment", "expire_date", "date", true, "Expire date");
            </script><br> <b>Bước 2:</b> Số dư sẽ được tính bên dưới<br><br>
            <span>Tổng tiền đã đóng: </span><span id="total_payment_amount_text" style="font-weight:bold;">'.format_number($total_pay,0,0).'</span><br><br>
            <span>Số tiền đã học:    </span><span id="total_delivery" style="font-weight:bold;"></span><br><br>
            <span>Số dư của HV:      </span><span id="total_free_balance" style="font-weight:bold;"></span><br>
            <br>
            <b>Bước 3:</b> Chọn ngày Drop Balance : <span class="dateTime"><input disabled name="drop_date" size="10" id="drop_date" type="text" value="'.$timedate->nowDate().'">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="drop_date_trigger" align="absmiddle"></span>
            <script type="text/javascript">
            Calendar.setup ({
            inputField : "drop_date",
            ifFormat : cal_date_format,
            daFormat : cal_date_format,
            button : "drop_date_trigger",
            singleClick : true,
            dateStr : "'.$timedate->nowDate().'",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
            }
            );
            addToValidate("Close_enrollment", "drop_date", "date", true, "Drop date");
            </script>
            <br><br>
            <b>Bước 4:</b>
            <br>Click <b>Drop Balance to Free Balance</b> : Số dư được chuyển vào tài khoản Free Balance của học viên, Free Balance có thể sử dụng để đăng ký một khóa học khác hoặc trả lại học viên.<br><br>
            Click <b>Drop Balance to Revenue</b> :      Số dư của học viên sẽ được chuyển vào Doanh thu ngày: <span id="expire_date_text">'.$timedate->nowDate().'</span> của Center: <span id="team_name_text">'.$this->bean->team_name.'</span><br><br>
            Click <b>Cancel</b> :                       Để hủy bỏ thao tác
            </div>
            </form>
            </body>';

            if(ACLController::checkAccess('C_Refunds', 'edit', false) && $this->bean->sales_stage != 'Closed')
                $bt_refund .= '<input type="button" onClick="window.open(\'index.php?module=C_Refunds&action=EditView&return_module=C_Refunds&return_action=DetailView&contact_id='.$this->bean->contact_id.'&enroll_id='.$this->bean->id.'&type=Normal\',\'_blank\');" class="button" id="transfer_student" value="Create Refund">';
            $this->ss->assign('BT_REFUND',$bt_refund);
        }

        //Custom button Add to class
        if(ACLController::checkAccess('C_Classes', 'import', false) && $this->bean->sales_stage == 'Success'){
            $class = BeanFactory::getBean('C_Classes', $_SESSION['class_id']);
            $html_ref = "";
            $html_ref .= '<input class="button" type="button" onClick="open_popup(\'C_Classes\',600,400, \'&class_id_advanced='.$class->class_id.'&type_advanced=Practice\' ,true,true,{\'call_back_function\':\'showPopupConfirm\',\'form_name\':\'DetailView\',\'field_to_name_array\':{\'id\':\'class_id\'},},\'Select\',true);" value="'.$GLOBALS['mod_strings']['LBL_ADD_TO_CLASS'].'" id="add_to_class">';
            $this->ss->assign('ADDTOCLASS',$html_ref);
        }
        // Mở tạm
        //            if($this->bean->sales_stage != 'Deleted' && $this->bean->added_to_class != '1'){
        if($this->bean->sales_stage != 'Deleted'){
            $this->ss->assign('DELETE_BT','<input title="Delete" accesskey="d" class="button" type="submit" name="Delete" value="Delete" id="delete_button">');
        }
        //Change total in invoice
        $q1 = "SELECT DISTINCT c_invoices.amount c_invoices_amount, IFNULL(c_invoices.id,'') primaryid ,IFNULL( c_invoices.currency_id,'') c_invoices_amount_currency FROM c_invoices INNER JOIN c_invoices_opportunities_1_c l1_1 ON c_invoices.id=l1_1.c_invoices_opportunities_1c_invoices_ida INNER JOIN opportunities l1 ON l1.id=l1_1.c_invoices_opportunities_1opportunities_idb WHERE (((l1.id='{$this->bean->id}' ))) AND c_invoices.deleted=0";
        $total_in_invoice = $GLOBALS['db']->getOne($q1);
        $this->bean->total_in_invoice = $total_in_invoice;

        //Check lock info - By Lap Nguyen
        if($GLOBALS['sugar_config']['lock_info']){
            $now_date = strtotime($timedate->nowDbDate());
            $check_date = strtotime('first day of next month '.$timedate->to_db_date($this->bean->date_closed,false))+ ( (intval($GLOBALS['sugar_config']['lock_date']) - 1) * 86400 );
        }else{
            $now_date = 1;
            $check_date = 2;
        }

        $field_date = '<span id="date_text_span">'.$this->bean->date_closed.'</span>';
        if(ACLController::checkAccess('Opportunities', 'edit', false)){
            $field_date .= '
            <div id="div_date_1">
            <input class="button" type="button" id="edit_date" value="Edit" style="padding: 2px;margin-bottom: 5px;">
            </div>';
            $field_date .= '<div id="div_date_2" style="display: none;">
            <span class="dateTime">
            <input class="date_input" autocomplete="off" type="text" name="date_text" id="date_text" value="'.$this->bean->date_closed.'" size="11" maxlength="10">
            <img src="themes/Sugar/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="date_text_trigger">
            </span>
            <input class="button primary" type="button" id="save_date" value="Save" style="padding: 2px;margin-bottom: 5px;"></div>';
        }
        $this->ss->assign('FIELD_DATE',$field_date);

        if($this->bean->sales_stage == 'Closed' && ($GLOBALS['current_user']->isAdminForModule('Users')))
            $this->ss->assign('BT_UNDO','<input class="button" type="button" id="undo_button" value="Undo">');
        //Add Button Convert 360 -> Junior
        if($GLOBALS['current_user']->isAdmin()){
            $this->ss->assign('convert_to_360', '<input class="button" type="button" id="btn_convert_to_360" value="Convert to 360">');
        }
        if(!empty($this->bean->payment_id)){
            $revenue_link = '';

            $revenue_link =  '<a target="_blank" href="index.php?action=DetailView&module=J_Payment&record='.$this->bean->payment_id.'"> >>Convert to Payment Link<< </a>';

            $this->ss->assign('revenue_link', $revenue_link);
        }



        parent::display();
    }
}
?>