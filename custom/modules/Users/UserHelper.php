<?php
/**
 * Created by PhpStorm.
 * User: HaiLong
 * Date: 2/6/2015
 * Time: 7:06 PM
 */

class UserHelper {
    //<editor-fold defaultstate="collapsed" desc="SET COOKIE REMEMBER">
    function setCookieRememberLogin($string_cookie) {
        setcookie('authenticated_user_language', $_SESSION['authenticated_user_language'], time() + 60 * 60 * 24 * 30, '/');
        setcookie('authenticated_user_string_cookie', $string_cookie, time() + 60 * 60 * 24 * 30, '/');
    }
    //</editor-fold>

    //<editor-fold defaultstate="collapsed" desc="REMOVE COOKIE REMEMBER">
    function deleteCookieRememberLogin() {
        setcookie('authenticated_user_language', '', time() - 60 * 60 * 24 * 30, '/');
        setcookie('authenticated_user_string_cookie', '', time() - 60 * 60 * 24 * 30, '/');
    }
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="GET COOKIE REMEMBER">
    function getCookieRememberLogin() {
        $_SESSION['authenticated_user_language'] = $_COOKIE['authenticated_user_language'];
        $string_cookie = $_COOKIE['authenticated_user_string_cookie'];
        $query = "SELECT system_key FROM systems";
        $unique_key = $GLOBALS['db']->getOne($query);
        //Decode string cookie
        $string_cookie = blowfishDecode($unique_key, $string_cookie);
        $str = explode("----", $string_cookie);
        $password = blowfishDecode($unique_key, $str[1]);
        $result = array(
            'user_name' => $str[0],
            'user_pass' => $password
        );
        return $result;
    }
    //</editor-fold>
    
    //<editor-fold defaultstate="collapsed" desc="LOGIN WITH SOAP">
    function loginWithSoap() {
        require_once("include/nusoap/nusoap.php");
        $server_url = $GLOBALS['sugar_config']['site_url'] . "/service/v4_1/soap.php?wsdl";
        $key_login = UserHelper::getCookieRememberLogin();
        if ($key_login['user_name'] != '' && $key_login['user_pass'] != '') {
            $credentials = array(
                'user_name' => $key_login['user_name'],
                'password' => md5($key_login['user_pass'])
            );
            $sugarClient = new nusoapclient($server_url, TRUE);
            $proxy = $sugarClient->getProxy();
            $result = $proxy->login($credentials, 'cloudpro');
            $session_id = $result['id'];
            //$result = $proxy->seamless_login($session_id);
            $_REQUEST['MSID'] = $session_id;
        }

    }
    //</editor-fold>
}