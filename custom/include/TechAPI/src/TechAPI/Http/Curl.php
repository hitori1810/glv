<?php
namespace TechAPI\Http;

use TechAPI\Constant;
use TechAPI\Error;

/**
 * CURL class
 * 
 * @author ISC--DAIDP
 * @since 21/09/2015
 */
class Curl
{
    /**
     * Execute an HTTP Request
     */
    public function execute(Request $request)
    {
        $curl = curl_init();
        
        if ($request->getPostBody()) {
          curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getPostBody());
        }
        
        $requestHeaders = $request->getRequestHeaders();
        if ($requestHeaders && is_array($requestHeaders))
        {
            $curlHeaders = array();
            foreach ($requestHeaders as $k => $v) {
                $curlHeaders[] = "$k: $v";
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);
        }
        
        curl_setopt($curl, CURLOPT_URL, $request->getUrl());
        curl_setopt($curl, CURLOPT_TIMEOUT, Constant::getTimeout());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getRequestMethod());
        curl_setopt($curl, CURLOPT_USERAGENT, $request->getUserAgent());
        
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_HEADER, true);
        
        $response = curl_exec($curl);
        
        if ($response === false) {
            Error::setError(Error::CURL_ERROR, curl_error($curl));
        }

        return json_decode($response, true);
    }
}