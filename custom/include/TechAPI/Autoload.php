<?php
define('TECH_API_LIB_PATH', realpath(__DIR__ . '/src'));

/**
 * Tech SDK
 * 
 * @author ISC--DAIDP
 * @since 09/10/2015
 * @version 1.0
 */
class TechAPIAutoloader
{
    private static $isLoaded = false;
    
    
    /**
     * Load class required
     * 
     * @param unknown $class
     * @return boolean
     */
    public static function loadClassLoader($class)
    {
        $classPart = explode('\\', $class);
        array_filter($classPart);
        
        if ($classPart[0] != 'TechAPI') {
            return false;
        }
        
        $classPath = TECH_API_LIB_PATH . sprintf('/%s.php', implode(DIRECTORY_SEPARATOR, $classPart));
        
        if (file_exists($classPath))
        {
            require_once $classPath; 
            return true; 
        }
        
        return false;
    }
    
    
    /**
     * Register Auto load class
     * 
     * @return boolean
     */
    public static function register()
    {
        if (self::$isLoaded) {
            return false;
        }
        
        spl_autoload_register(array(__CLASS__, 'loadClassLoader'));
        
        self::$isLoaded = true;
    }
}