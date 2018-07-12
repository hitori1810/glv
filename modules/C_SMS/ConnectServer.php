<?php
    if(!defined('sugarEntry'))define('sugarEntry', true);   
    include("include/nusoap/nusoap.php");
    class SMS{

        var $client = null;
        var $username = '';
        var $password = '';
        function SMS($url, $username, $password){

            $this->username = $username;
            $this->password = $password;
            $this->client = new nusoap_client($url,true); 
        }
        function send_sms($phone, $text, $from){
            $params = array(
            'code' => $this->password,
            'account' => $this->username,
            'phone' => $phone,
            'from' => $from,
            'sms' => $text,

            );

            $login_results = $this->client->call('sendSms',$params);

            return $login_results;
        }
    }
?>
