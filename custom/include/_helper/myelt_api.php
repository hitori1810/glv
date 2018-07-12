<?php
/**
 * Created by PhpStorm.
 * User: hoangtunglam
 * Date: 11/10/2017
 * Time: 16:40
 */
function callAPI($type, $method, $xml_data)
{
    $serviceConfig = array(
        'url' => 'https://myelt.heinle.com',
        'prefix' => '/ilrn/services/',
        'action' => 'http://www.imsglobal.org/soap/lis/pms2p0/',
        'default_user_password' => '123456'
    );

    $url = $serviceConfig['url'] . $serviceConfig['prefix'] . $type;
    $credential = $GLOBALS['sugar_config']['myelt_credential'];
    $headers = array(
        "POST " . $serviceConfig['prefix'] . $type . " HTTP/1.1",
        "Content-type: text/xml;charset=\"UTF-8\"",
        "Accept: text/xml",
        "SOAPAction: \"" . $serviceConfig['action'] . $method . "\"",
        "Content-length: " . strlen($xml_data),
        "Host: myelt.heinle.com",
        "Connection: Keep-Alive",
        "User-Agent: Apache-HttpClient/4.5.2",
        "Authorization: Basic " . base64_encode($credential));

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURL_HTTP_VERSION_1_1,1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, "Apache-HttpClient/4.5.2");

// Apply the XML to our curl call
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);

    $data = curl_exec($ch);

    if (curl_errno($ch)) {
        return array(
            'connection' => 'error',
        );
    } else {
        // Show me the result
        preg_match_all("/<ns1:imsx_codeMajor xsi:type=\"ns1:imsx_CodeMajor.Type\">(.*?)<\/ns1:imsx_codeMajor>/s",
            $data, $status);
        preg_match_all("/<ns1:imsx_codeMinorFieldName xsi:type=\"xsd:string\">(.*?)<\/ns1:imsx_codeMinorFieldName>/s",
            $data, $error_message);


        return array(
            'connection' => 'success',
            'status' => $status[1],
            'error_message' => (array)$error_message[1],
        );
    }

}

function createCourseOffering($class_code, $start_date, $end_date)
{
    return "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:urn=\"urn:coursemanagementservicesyncservices.axisws.webservices.session.ilrn.com\">
   <soapenv:Header>
      <urn:imsx_syncRequestHeaderInfo>
         <urn:imsx_version>V1.0</urn:imsx_version>
         <urn:imsx_messageIdentifier>?</urn:imsx_messageIdentifier>
         <!--Optional:-->
         <urn:imsx_sendingAgentIdentifier>APEN2</urn:imsx_sendingAgentIdentifier>
      </urn:imsx_syncRequestHeaderInfo>
   </soapenv:Header>
   <soapenv:Body>
      <urn:createCourseOfferingRequest>
         <urn:sourcedId>" . $class_code . "</urn:sourcedId>
         <urn:courseOfferingRecord>
            <urn:sourcedGUID>
               <urn:sourcedId>" . $class_code . "</urn:sourcedId>
            </urn:sourcedGUID>
            <urn:courseOffering>
               <urn:title>
                  <urn:language>en-US</urn:language>
                  <urn:textString>" . $class_code . "</urn:textString>
               </urn:title>
               <urn:timeFrame>
                  <urn:begin>" . $start_date . "T00:00:00+0700</urn:begin>
                  <urn:end>" . $end_date . "T23:59:59+0700</urn:end>
               </urn:timeFrame>
            </urn:courseOffering>
         </urn:courseOfferingRecord>
      </urn:createCourseOfferingRequest>
   </soapenv:Body>
</soapenv:Envelope>";
}

function createCourseOfferingFromCourseOffering($sourceId, $class_code)
{
    return "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:urn=\"urn:coursemanagementservicesyncservices.axisws.webservices.session.ilrn.com\">
   <soapenv:Header>
      <urn:imsx_syncRequestHeaderInfo>
         <urn:imsx_version>V1.0</urn:imsx_version>
         <urn:imsx_messageIdentifier>?</urn:imsx_messageIdentifier>
         <!--Optional:-->
         <urn:imsx_sendingAgentIdentifier>APEN2</urn:imsx_sendingAgentIdentifier>
      </urn:imsx_syncRequestHeaderInfo>
   </soapenv:Header>
   <soapenv:Body>
      <urn:createCourseOfferingFromCourseOfferingRequest>
         <urn:sourcedId>" . $sourceId . "</urn:sourcedId>
         <urn:newSourcedId>" . $class_code . "</urn:newSourcedId>
      <urn:academicSession><urn:language/><urn:textString/></urn:academicSession></urn:createCourseOfferingFromCourseOfferingRequest>
   </soapenv:Body>
</soapenv:Envelope>";
}

function updateCourseOffering($class_code, $start_date, $end_date)
{
    return "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:urn=\"urn:coursemanagementservicesyncservices.axisws.webservices.session.ilrn.com\">
   <soapenv:Header>
      <urn:imsx_syncRequestHeaderInfo>
         <urn:imsx_version>V1.0</urn:imsx_version>
         <urn:imsx_messageIdentifier>?</urn:imsx_messageIdentifier>
         <!--Optional:-->
         <urn:imsx_sendingAgentIdentifier>APEN2</urn:imsx_sendingAgentIdentifier>
      </urn:imsx_syncRequestHeaderInfo>
   </soapenv:Header>
   <soapenv:Body>
      <urn:updateCourseOfferingRequest>
         <urn:sourcedId>" . $class_code . "</urn:sourcedId>
         <urn:courseOfferingRecord>
            <urn:sourcedGUID>
               <urn:sourcedId>" . $class_code . "</urn:sourcedId>
            </urn:sourcedGUID>
            <urn:courseOffering>
               <urn:title>
                  <urn:language>en-US</urn:language>
                  <urn:textString>" . $class_code . "</urn:textString>
               </urn:title>
               <urn:timeFrame>
                  <urn:begin>" . $start_date . "T00:00:00+0700</urn:begin>
                  <urn:end>" . $end_date . "T23:59:59+0700</urn:end>
               </urn:timeFrame>
            </urn:courseOffering>
         </urn:courseOfferingRecord>
      </urn:updateCourseOfferingRequest>
   </soapenv:Body>
</soapenv:Envelope>";
}

function deleteCourseOffering($class_code)
{
    return "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:urn=\"urn:coursemanagementservicesyncservices.axisws.webservices.session.ilrn.com\">
   <soapenv:Header>
      <urn:imsx_syncRequestHeaderInfo>
         <urn:imsx_version>V1.0</urn:imsx_version>
         <urn:imsx_messageIdentifier>?</urn:imsx_messageIdentifier>
         <urn:imsx_sendingAgentIdentifier>APEN2</urn:imsx_sendingAgentIdentifier>
      </urn:imsx_syncRequestHeaderInfo>
   </soapenv:Header>
   <soapenv:Body>
      <urn:deleteCourseOfferingRequest>
         <urn:sourcedId>" . $class_code . "</urn:sourcedId>
      </urn:deleteCourseOfferingRequest>
   </soapenv:Body>
</soapenv:Envelope>";
}

?>