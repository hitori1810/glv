<?php
namespace TechAPI\Api;


/**
 * Create campaign
 * 
 * @author ISC--DAIDP
 * @since Aug 3, 2016
 */
class CreateCampaign implements ApiInterface
{
    const ACTION = '/api/create-campaign';
    
    /**
     * @var string
     */
    protected $CampaignName = '';
    
    /**
     * @var string
     */
    protected $BrandName = '';
    
    /**
     * @var string
     */
    protected $Message    = '';
    
    /**
     * @var string
     */
    protected $ScheduleTime = '';
    
    /**
     * @var int
     */
    protected $Quota    = 1;
    
    
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