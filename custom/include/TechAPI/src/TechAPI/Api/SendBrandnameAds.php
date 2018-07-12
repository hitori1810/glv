<?php
namespace TechAPI\Api;


/**
 * Send brandname Ads
 * 
 * @author ISC--DAIDP
 * @since Jun 1, 2016
 */
class SendBrandnameAds implements ApiInterface
{
    const ACTION = '/api/push-brandname-ads';
    

    /**
     * @var string
     */
    protected $CampaignCode = '';
    
    /**
     * @var string
     */
    protected $PhoneList      = '';

    
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