<?php
    //require_once('nusoap/nusoap.php');
    require_once("modules/C_SMS/ConnectServer.php");
    class sendSMS{
        /**
        * Function send a sms to target
        *
        * @param mixed $phone_number
        * @param mixed $content content of  sms
        * @return mixed
        */

        function send_a_sms($phone_number = '', $content = ''){
            global $sugar_config;
            $WS = 'http://bc.vht.com.vn:8440/vht/services/sms?wsdl';
            $ws_pass = '';
            $ws_account = '';
            $ws_brandname = '';
            $sms = new SMS($WS,$ws_account, $ws_pass);
            //$formats =  array('#########','##########'); // format phone number
                //if($this->validate_telephone_number($phone_number,$formats)){
                    return $sms->send_sms($phone_number, $content,$ws_brandname);
            //}
        }

        /**
        * Check vaild phone number
        *
        * @param mixed $number  phone number
        * @param mixed $formats  pattern format vaild number
        * @return bool
        */
        function validate_telephone_number($number, $formats)
        {
            $format = trim(ereg_replace("[0-9]", "#", $number));
            return (in_array($format, $formats)) ? true : false;
        }

    }
//	$sms = new sendSMS();
//	var_dump($sms->send_a_sms('84905999207','Hello Lap'));

?>
