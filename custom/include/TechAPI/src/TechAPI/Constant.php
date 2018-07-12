<?php
namespace TechAPI;

/**
 * Constans configs
 * 
 * @author ISC--DAIDP
 * @since 22/09/2015
 */
class Constant
{
    const MODE_LIVE    = 'live';
    const MODE_SANDBOX = 'sandbox';
    
    /**
     * @var string
     */
    private static $mode = self::MODE_LIVE;
    
    /**
     * Server Endpoint
     * 
     * @var array
     */
    private static $endpoint = array(
        self::MODE_LIVE     => 'http://service.sms.fpt.net',
        self::MODE_SANDBOX  => 'http://sandbox.sms.fpt.net'
    );
    
    /**
     * @var int
     */
    private static $connectTimeout = 30;
    
    /**
     * Enable write log error
     * 
     * @var boolean
     */
    private static $enableLog      = true;
    
    /**
     * Log path
     * 
     * @var string
     */
    private static $logPath        = '';
    
    /**
     * File name of log
     * 
     * @var string
     */
    private static $logFileName    = 'tech-api-error.log';
    
    /**
     * Use cache access token
     * 
     * @var boolean
     */
    private static $cache          = true;
    
    
    /**
     * Set configs
     * 
     * @param array $configs
     */
    public static function configs(array $configs)
    {
        // merge with default data
        $arrDefault = array(
            'mode'            => self::$mode,
            'connect_timeout' => self::$connectTimeout,
            'enable_log'      => self::$enableLog,
            'log_path'        => self::$logPath,
            'log_filename'    => self::$logFileName,
            'enable_cache'    => self::$cache
        );
        $arrConfig = array_merge($arrDefault, $configs);
        
        // set configs
        self::_setMode($arrConfig['mode']);
        self::$connectTimeout = $arrConfig['connect_timeout'];
        self::$enableLog      = $arrConfig['enable_log'];
        self::$logPath        = $arrConfig['log_path'];
        self::$cache          = $arrConfig['enable_cache'];
        self::$logFileName    = $arrConfig['log_filename'];
    }
    
    
    /**
     * Set mode
     * 
     * @param string $mode
     */
    private static function _setMode($mode)
    {
        if ($mode == self::MODE_LIVE || $mode == self::MODE_SANDBOX) {
            self::$mode = $mode;
        }
    }
    
    
    /**
     * Is live mode
     * 
     * @return boolean
     */
    public static function isLive()
    {
        return self::$mode === self::MODE_LIVE;
    }
    
    
    /**
     * Is sandbox mode
     * 
     * @return boolean
     */
    public static function isSandbox()
    {
        return self::$mode === self::MODE_SANDBOX;
    }
    
    
    /**
     * Get mode
     * 
     * @return string
     */
    public static function getMode()
    {
        return self::$mode;
    }
    
    
    /**
     * Get endpoint url
     * 
     * @return string
     */
    public static function getEndpoint()
    {
        return self::$endpoint[self::$mode]; 
    }
    
    
    /**
     * Get curl connect timeout
     * 
     * @return number
     */
    public static function getTimeout()
    {
        return self::$connectTimeout;
    }
    
    
    /**
     * is enable cache
     * 
     * @return boolean
     */
    public static function isCache()
    {
        return self::$cache;
    }
    
    
    /**
     * Is write log when error
     * 
     * @return boolean
     */
    public static function isWriteLog()
    {
        return self::$enableLog;
    }
    
    
    /**
     * Get log config
     * 
     * @return array
     */
    public static function getLogConfigs()
    {
        return array(
            'enable_log'   => self::$enableLog,
            'log_path'     => self::$logPath,
            'log_filename' => self::$logFileName
        );
    }
}