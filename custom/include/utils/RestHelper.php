<?php
class RestHelper {
    var $appName = 'CustomerPortal';
    var $version = '1';
    var $serviceUrl = '';
    function __construct() {
        $this->serviceUrl = $GLOBALS['sugar_config']['site_url'].'/custom/service/v4_1/rest.php';
      //  $this->serviceUrl = 'http://172.16.10.3/custom/service/v4_1/rest.php';
    }

    // Make request
    public function call($method, $parameters) {
        ob_start();
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->serviceUrl);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);

        $post = array(
            'method' => $method,
            'input_type' => 'JSON',
            'response_type' => 'JSON',
            'rest_data' => json_encode($parameters)
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl);
        curl_close($curl);

        if(!$result) {
            return null;
        }

        ob_end_flush();
        $response = json_decode($result);

        return $response;
    }

    function getSessionID($userId) {
        $param = array(
            'student_id' => $userId,
        );
        return $this->call('generateSessionId', $param);
    }
}
?>
