<?php
//require_once('nusoap/nusoap.php');
require_once("custom/modules/C_SMS/SMS/Connector.php");
class SMSSender{
    /**
    * Function send a sms to target
    *
    * @param mixed $phone_number
    * @param mixed $content content of  sms
    * @return mixed
    */

    function sendSMS($phone_number = '', $content = '', $parent_type = 'Users',$parent_id = '1', $team_id = ''){
        global $current_user, $sugar_config;
        if (empty($team_id))
            $team_id = $current_user->team_id;

        $team = BeanFactory::getBean('Teams', $team_id);

        $sms_config = array();
        $sms_config = json_decode(html_entity_decode($team->sms_config),true);
        if (empty($team->sms_config)) return  0;
        if (empty($content)) return 0;

        $phone_number = preg_replace("/&#?[a-z0-9]+;/i", '', $phone_number);
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        $phone_number = preg_replace('/\s+/', '', $phone_number);
        if(substr($phone_number,0 , 1) != '0' && substr($phone_number,0 , 2) != '84') $phone_number = '0'.$phone_number;
        $phone_number = (substr($phone_number,0 , 1) == '0') ? substr_replace($phone_number,'84',0,1) : $phone_number;

        $supplier = "other";
        $phoneNumberPrefix = $GLOBALS['app_list_strings']['phone_number_prefix_options'];
        foreach ($phoneNumberPrefix as $key => $value){
            if ((substr($phone_number,0 , 4) == $key) || (substr($phone_number,0 , 5) == $key))
                $supplier = $value;
        }

        //Generate template to content - Bui Kim Tung 28/09/2015
        if($parent_type != "Users"){
            $content    = last_parse_SMS($content, $parent_type, $parent_id);
        }
        //Replace "test" to "t est" with Viettel
        if($supplier == 'viettel'){
            $content = str_replace("test", 't_est', $content);
            $content = str_replace("Test", 'T_est', $content);
            $content = str_replace("TEST", 'T_EST', $content);
        }

        $content        = viToEn($content);


        $ws_server 		= $sms_config['sms_ws_link'];
        $ws_pass 		= $sms_config['sms_ws_pass'];
        $ws_account 	= $sms_config['sms_ws_account'];
        $ws_brandname   = $sms_config['sms_ws_brandname'];
        $ws_supplier    = $sms_config['sms_ws_supplier'];
        $ws_groupid 	= $sms_config['sms_ws_groupid'];

        //Khóa chức năng gửi SMS
        if(!$sugar_config['sendSMS'])
            return "-1";

        // Edit by Tung Bui 01/12/2015 - Fix loi khong gui duoc SMS tren Junior
        $SMS_Provider   = new SMS_Provider($ws_server,$ws_account,$ws_pass);
        $result         = $SMS_Provider->send_sms($phone_number,$content,$ws_brandname,$ws_supplier,$ws_groupid);

        return $result;
    }
}

?>
