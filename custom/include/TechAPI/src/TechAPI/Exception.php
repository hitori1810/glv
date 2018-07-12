<?php
namespace TechAPI;

/**
 * Tech API Exception.
 * Can add method write log here
 * 
 * @author ISC--DAIDP
 * @since 21/09/2015
 */
class Exception extends \Exception
{
    public function __construct($message, $code = null, $previous = null)
    {
        if (Constant::isWriteLog()) {
            LogWriter::getInstance()->log($message, $code);
        }
        
        parent::__construct($message, $code, $previous);
    }
}