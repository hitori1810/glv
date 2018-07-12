<?php
require_once ("custom/modules/C_SMS/SMS/sms_interface.php");
include_once("./modules/C_SMS/C_SMS.php");
include_once("custom/modules/C_SMS/SMS/SMSSender.php");
require_once("custom/include/utils/parseTemplate.php");
class sms implements sms_interface {

    var $ismsc_auth = "/auth.php";
    var $ismsc_send = "/send.php";
    var $ismsc_send_multi = "/sendToMulti.php";
    var $config_file = "./custom/sms/sms_config.php";

    var $params = array();
    var $parent_type;
    var $parent_id;
    var $type;
    var $focus;
    var $response_text;

    function authenticate($account_id) {
        $ch = curl_init($this->params['izeno_url'].$this->ismsc_auth);
        $param = "account_id=" . urlencode($account_id);
        $param .= "&domain_name=" . urlencode($this->params['domain_name']);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return 	$response;
    }


    private function send($to, $text) {
        $ch = curl_init($this->params['izeno_url'].$this->ismsc_send);
        $param = "account_id=" . urlencode($this->params['sms_instance_id']);
        $param .= "&domain_name=" . urlencode($this->params['domain_name']);
        $param .= "&phone_number=" . urlencode($to);
        $param .= "&message=" . str_replace("%26%23039%3B", "%27", rawurlencode(htmlspecialchars_decode($text)));
        $param .= "&sender=" . str_replace("%26%23039%3B", "%27", rawurlencode(htmlspecialchars_decode($this->params['sender'])));

        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    function send_message($to, $text, $parent_type, $parent_id, $user_id, $template_id = "", $date_in_content = "", $team_id = "") {
        $result = SMSSender::sendSMS($to, $text, $parent_type, $parent_id, $team_id);
        $return = (int)$result;
        $status = 'RECEIVED';
        if($return <= 0)
            $status = 'FAILED';
        $this->createSMSRecord($to, $text, $status, $parent_type, $parent_id, $user_id, $template_id, $date_in_content,$team_id);
        return $return;
    }

    function resend($sms_id, $to, $text) {
        global $current_user;
        $izeno_SMS = new C_SMS();
        $izeno_SMS->retrieve($sms_id);
        if (isset($izeno_SMS->id) and !empty($izeno_SMS->id)) {
            $to = preg_replace('/[^0-9]/', '', $to);
            $response = $this->send($to, $text);
            $izeno_SMS->phone_number = strlen($to) ? $to." " : "-none-";
            $izeno_SMS->description = $text;
            $izeno_SMS->api_message = $response['API_MSG'];
            $izeno_SMS->delivery_status = $response['STATUS'];
            $izeno_SMS->type = "outbound";
            $izeno_SMS->save();
        } else {
            $this->response_text = "Javascript fault: Unable to send message.";
        }
        $this->response_text = $response['API_MSG'];
        return $this->response_text;
    }

    private function send_to_multi($to_array, $text,$parent_type, $template_id = "", $date_in_content = "") {
        $summary = array();
        for($i = 0; $i<count($to_array); $i++){
            $arrData = $to_array[$i];
            $phone = $arrData[1];
            $module = $parent_type;
            $parent_id = $arrData[2];
            $phone = preg_replace('/[^0-9]/', '', $phone);

            //Find student to save SMS record - by Tung Bui
            if ($parent_type == "J_StudentSituations"){
                $studentSituation = BeanFactory::getBean("J_StudentSituations", $parent_id);
                $student = BeanFactory::getBean("Contacts", $studentSituation->student_id);
                $module = "Contacts";
                $parent_id = $student->id;
                $phone = $student->phone_mobile;
            }

            //Can not detect phone number
            if ($phone == "") continue;

            //Generate some varible - by Tung Bui
            $smsContent = last_parse_SMS($text, $module, $parent_id);

            if( SMSSender::sendSMS($phone,$smsContent,$module,$parent_id)){
                $this->createSMSRecord($phone,$smsContent,'RECEIVED',$module,$parent_id,$GLOBALS['current_user']->id, $template_id, $date_in_content);
                $summary[$i] = array(0 => 'Send to '.$phone, 1 => 'Received');
            }
            else{
                $this->createSMSRecord($phone,$smsContent,'FAILED',$module,$parent_id,$GLOBALS['current_user']->id, $template_id, $date_in_content);
                $summary[$i] = array(0 => 'Send to '.$phone, 1 => 'Failed');
            }
        }
        return $summary;
    }

    # need to create batch sending on izeno
    function send_batch_message($to_array,$text,$parent_type, $template_id = "", $date_in_content = "") {
        global $current_user;
        $summary = $this->send_to_multi($to_array, $text,$parent_type,$template_id,$date_in_content);
        return $summary;

    }

    function retrieve_settings() {
        $this->params = array();
        if (file_exists($this->config_file)) {
            include($this->config_file);
            if (isset($sms_config)) {
                $this->params = $sms_config;
            }
        }
    }

    function save_settings() {
        $handle = fopen($this->config_file, "w+");
        $content = "<?php\n";
        foreach($this->params as $key => $val) {
            $content .= "\$sms_config[\"{$key}\"] = \"{$val}\";\n";
        }
        $content .= "?>";
        fputs($handle, $content);
        fclose($handle);
    }


    function uses_template() {
        $this->retrieve_settings();
        if(isset($this->params['uses_sms_template']))
            return $this->params['uses_sms_template'];
        else
            return false;	// by default, SMS do not use templates
    }

    function get_supported_countries() {
        $ch = curl_init($this->sms_controller);
        $param = "req=country_list";
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    function countSms($content){
        $maximum_messages = 3;
        $length = strlen($content);
        $per_message = 160;
        if($length > $per_message)
            $per_message = 153;
        $message = ceil($length/$per_message);
        return $message;
    }

    function createSMSRecord($phone_number, $message, $status, $parent_type, $parent_id, $user_id, $template_id = "", $date_in_content = "", $team_id = ""){
        global $app_list_strings, $timedate;
        $receiver = BeanFactory::getBean($parent_type,$parent_id);
        //Generate template to content - Bui Kim Tung 28/09/2015
        if($parent_type != "Users"){
            $message = last_parse_SMS($message, $parent_type, $parent_id);
        }
        if(empty($team_id))
            $team_id = $GLOBALS['current_user']->team_id;

        $fixedPhoneNumber = preg_replace('/[^0-9]/', '', $phone_number);
        $fixedPhoneNumber = (substr($fixedPhoneNumber,0 , 1) == '0') ? substr_replace($fixedPhoneNumber,'84',0,1) : $fixedPhoneNumber;
        $phoneNumberPrefix = $app_list_strings['phone_number_prefix_options'];
        $supplier = "other";
        foreach ($phoneNumberPrefix as $key => $value) {
            if ((substr($fixedPhoneNumber,0 , 4) == $key) || (substr($fixedPhoneNumber,0 , 5) == $key)){
                $supplier = $value;
            }
        }

        $c_sms 						= new C_SMS();
        $c_sms->name 				= 'Send to '.$receiver->name.' - '.$phone_number;
        $c_sms->description 		= $message;
        $c_sms->parent_type 		= $parent_type;
        $c_sms->parent_id 			= $parent_id;
        $c_sms->phone_number        = $phone_number;
        $c_sms->supplier     		= $supplier;
        $c_sms->delivery_status     = $status;
        $message                    = viToEn($message);
        $c_sms->message_count       = $this->countSms($message);
        $c_sms->template_id         = $template_id;
        $c_sms->date_in_content 	= (!empty($date_in_content)) ? $date_in_content : $timedate->nowDate();
        $c_sms->assigned_user_id 	= $user_id;
        $c_sms->team_id 			= $team_id;
        $c_sms->team_set_id 		= $c_sms->team_id;
        $c_sms->save();
    }
}
?>
