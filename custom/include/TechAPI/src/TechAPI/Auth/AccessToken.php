<?php
namespace TechAPI\Auth;


use TechAPI\Constant;
/**
 * Access token singleton class
 * 
 * @author ISC--DAIDP
 * @since 22/09/2015
 */
final class AccessToken
{
    const SESSION_NAME = 'tech_sms_oauth';
    
    private static $instance = null;
    
    private $access_token = '';
    
    private $expired      = 0;
    
    private $token_type   = '';
    
    private $scope        = '';
    
    private $refresh_token = '';
    
    
    /**
     * Constructor
     */
    private function __construct()
    {
        if (! isset($_SESSION)) {
            session_start();
        }
        
        // read old access token
        if (Constant::isCache()) {
            $this->_setAccessToken($this->_getAccessTokenData());
        }
    }
    
    
    /**
     * Get class instance
     * 
     * @return \TechAPI\Auth\AccessToken
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        
        return self::$instance;
    }
    
    
    /**
     * Set access token When request token success
     * 
     * @param array $arrData
     */
    public function setAccessToken($arrData)
    {
        // process expried time
        $arrData['expired'] = time() + $arrData['expires_in'] - 10; // trừ hao 10s
        unset($arrData['expires_in']);
        
        // store access token
        $this->_setAccessToken($arrData);
        
        $_SESSION[self::SESSION_NAME] = serialize(array_filter(get_object_vars($this)));
    }
    
    
    /**
     * Private set access token
     * 
     * @param array $arrData
     */
    private function _setAccessToken($arrData)
    {
        foreach ($arrData as $key => $val)
        {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
    }
    
    
    /**
     * Get access token data
     * 
     * @return array
     */
    private function _getAccessTokenData()
    {
        return !empty($_SESSION[self::SESSION_NAME]) ? unserialize($_SESSION[self::SESSION_NAME]) : array();
    }
    
    
    /**
     * Get access token
     * 
     * @return string|NULL
     */
    public function getAccessToken()
    {
        if ($this->access_token && $this->expired > time()) {
            return $this->access_token;
        }
         
        return '';
    }
    
    
    /**
     * Convert class to string (echo, parse string)
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getAccessToken();
    }
    
    
    /**
     * Clear cache access token
     */
    public function clear()
    {
        unset($_SESSION[self::SESSION_NAME]);
        $this->access_token  = '';
        $this->expired       = 0;
        $this->refresh_token = '';
        $this->scope         = '';
        $this->token_type    = '';
    }
}