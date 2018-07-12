<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
require_once("include/nusoap/nusoap.php");
class SMS_Provider{

    var $client = null;
    var $username = '';
    var $password = '';

    function SMS_Provider($url, $username, $password){

        $this->username = $username;
        $this->password = $password;
        $this->client   = new nusoap_client($url,true);
    }
    /**
    * function send sms to phone number
    *
    * @param mixed $message
    * @param mixed $phone
    * @param mixed $sender
    * @param mixed $deptId
    * @param mixed $groupId
    * @return mixed
    */

    function send_sms($phone, $text, $from, $supplier, $groupID = ''){
        if(!empty($this->password) && !empty($this->username)){
            if($supplier == 'VHT' || empty($supplier)){
                $params = array(
                    'code'      => $this->password,
                    'account'   => $this->username,
                    'phone'     => $phone,
                    'from'      => $from,
                    'sms'       => $text,
                );
                $login_results = $this->client->call('sendSms',$params);
            }

            elseif($supplier == 'VIETGUYS'){
                $params = array(
                    'account'       => $this->username,
                    'passcode'      => $this->password,
                    'service_id'    => $from,
                    'phone'         => $phone,
                    'sms'           => $text,
                    'transactionid' => '',
                    'json'          => 2
                );
                $login_results = $this->client->call('send',array('sms' => $params) );
            }elseif($supplier == 'GAPIT'){
                $params = array(
                    'dest'         => $phone,
                    'name'         => $from,
                    'msgBody'      => $text,
                    'contentType'  => 'text',
                    'serviceID'    => 'G-API',
                    'mtID'         => '0',
                    'cpID'         => $groupID,
                    'username'     => $this->username,
                    'password'     => $this->password,
                );
                $login_results = $this->client->call('SendMT', $params  );
                if($login_results['SendMTResult'] == '200') $login_results = 1;
                else $login_results = -1;
            }
            elseif($supplier == 'VMG'){
                $params = array(
                    'msisdn'    => $phone,
                    'alias'     => $from,
                    'message'   => $text,
                    'sendTime'  =>'',
                    'authenticateUser'=> $this->username,
                    'authenticatePass'=> $this->password
                );
                $login_results = $this->client->call('BulkSendSms',$params ,'','','');


                if($login_results['BulkSendSmsResult']['error_code'] == 0) $login_results = 1;
                else $login_results = -1;
            }
            else $login_results = -1;
        }else $login_results = -1;

        return $login_results;
    }
}
?>