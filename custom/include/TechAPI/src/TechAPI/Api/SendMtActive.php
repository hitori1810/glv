<?php
namespace TechAPI\Api;


/**
 * Send MT Active
 * 
 * @author ISC--DAIDP
 * @since 21/09/2015
 */
class SendMtActive implements ApiInterface
{
    const ACTION = '/api/push-mtactive';
    
    /**
     * @var int
     */
    protected $MOId       = 0;
    
    /**
     * @var string
     */
    protected $Telco      = '';
    
    /**
     * @var string
     */
    protected $ServiceNum = '';
    
    /**
     * @var string
     */
    protected $Phone      = '';
    
    /**
     * @var string
     */
    protected $Syntax     = '';
    
    /**
     * @var string
     */
    protected $Message    = '';
    
    
    /**
     * Construction and set data
     * 
     * @param array $data
     */
    public function __construct(array $data)
    {
        // set data
        foreach ($data as $key => $val)
        {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
        
    }
    
    
    /**
     * Action to request to api
     * 
     * @return string;
     */
    public function getAction()
    {
        return self::ACTION;
    }
    

    /**
     * Get array data
     * 
     * @return array
     */
    public function toArray()
    {
        $arrData = get_object_vars($this);
        $arrData['Message'] = base64_encode($arrData['Message']);
        
        return $arrData;
    }
    
    
    /**
     * Get param. Ex: getTelco()
     * 
     * @param string $name
     * @param mixed $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $name = preg_replace('/^get/', '', $name);
        
        if (isset($this->$name)) {
            return $this->$name;
        }
        
        return false;
    }
}