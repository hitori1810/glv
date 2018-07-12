<?php
namespace TechAPI;

/**
 * Logwriter
 * 
 * @author ISC--DAIDP
 * @since 23/09/2015
 */
class LogWriter
{
    private static $instance = null;
    private $path = null;
    
    
    /**
     * init log path 
     */
    private function __construct()
    {
        $arrConfigs = Constant::getLogConfigs();
        
        $this->path = trim($arrConfigs['log_path']);
        $sSeperator = '';
        
        if (! preg_match('/\/$|\\$/', $this->path)) {
            $sSeperator = '/';
        }
        
        $this->path .= $sSeperator . $this->getFileName($arrConfigs['log_filename']);
    }
    
    
    /**
     * Get log file name
     * 
     * @param string $filename
     * @return string
     */
    private function getFileName($filename)
    {
        $filename = explode('.', trim($filename));
        if (count($filename) > 1) {
            $ext = '.'.array_pop($filename);
        }
        else {
            $ext = '';
        }
        
        return implode('.', $filename) . '_' . date('Y-m-d') . $ext;
    }
    
    
    /**
     * get instance of class
     * 
     * @return \TechAPI\LogWriter
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        
        return self::$instance;
    }
    
    
    /**
     * Write log
     * 
     * @param string $message
     * @param string $code
     */
    public function log($message, $code)
    {
        error_log("[" .  date('d-m-Y h:i:s') . "] ERROR(" . $code . "): $message\n", 3, $this->path);
    }
}