<?php
namespace TechAPI;

/**
 * Set error in request
 * 
 * @author ISC--DAIDP
 * @since 21/09/2015
 */
class Error
{
    const EMPTY_CLIENT_ID     = 2001;
    const EMPTY_CLIENT_SECRET = 2002;
    const EXPIRED_TOKEN       = 2003;
    const CURL_ERROR          = 2004;
    
    /**
     * Error messages
     * 
     * @var array
     */
    protected static $arrMessage = array(
        self::EMPTY_CLIENT_ID       => '"client_id" bắt buộc và không được trống.',
        self::EMPTY_CLIENT_SECRET   => '"client_secrect" bắt buộc và không được trống.',
        self::EXPIRED_TOKEN         => '"access_token" has been expired.',
        self::CURL_ERROR            => 'Connect timeout.'
    );
    
    
    /**
     * Set error message
     * 
     * @param int $code
     * @param string $message
     * @throws Exception
     */
    public static function setError($code, $message = null)
    {
        if ($message === null) {
            $message = isset(self::$arrMessage[$code]) ? self::$arrMessage[$code] : '';
        }

        throw new Exception($message, $code);
    }
}