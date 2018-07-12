<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


require_once('include/api/ServiceBase.php');
require_once('include/api/ServiceDictionaryRest.php');
require_once('include/SugarOAuth2/SugarOAuth2Server.php');

class RestService extends ServiceBase {

    public $user;
    /**
     * The request headers
     * @var array
     */
    
    public $request_headers = array();
    
    public $platform = 'base';

    /**
     * The leading portion of the URI for building request URIs with in the API
     * @var
     */
    protected $resourceURIBase;
    
    /**
     * The response headers that will be sent
     * @var array
     */
    protected $response_headers = array();

    /**
     * The minimum version accepted
     * @var integer
     */
    protected $min_version = 10;

    /**
     * The maximum version accepted
     * @var integer
     */
    protected $max_version = 10;

    /**
     * The acl action attempting to be run
     * @var string
     */
    public $action = 'view';

    /**
     * This function executes the current request and outputs the response directly.
     */
    public function execute() {
        try {
            $rawPath = $this->getRawPath();
            
            $this->setRequestHeaders();

            list($version,$path) = $this->parsePath($rawPath);

            if($this->min_version > $version || $this->max_version < $version) {
                throw new SugarApiExceptionIncorrectVersion("Please change your url to reflect version between {$this->min_version} and {$this->max_version}");
            }

            $authenticateUser = $this->authenticateUser();

            $isLoggedIn = $authenticateUser['isLoggedIn'];
            $loginException = $authenticateUser['exception'];

            // Figure out the platform
            if ( $isLoggedIn ) {
                if ( isset($_SESSION['platform']) ) {
                    $this->platform = $_SESSION['platform'];
                }
            } else {
                // Since we don't have a session we have to allow the user to specify their platform
                // However, since the results from the same URL will be different with
                // no variation in the oauth_token header we need to only take it as a GET request.
                if ( !empty($_GET['platform']) ) {
                    $this->platform = basename($_GET['platform']);
                }
            }


            $route = $this->findRoute($path,$version,$_SERVER['REQUEST_METHOD'],$this->platform);

            if ( $route == false ) {
                throw new SugarApiExceptionNoMethod('Could not find any route that accepted a path like: '.$rawPath);
            }

            if ( !isset($route['noLoginRequired']) || $route['noLoginRequired'] == false ) {
                if ( !$isLoggedIn ) {
                    if(!$loginException) {
                        // @TODO Localize exception strings
                        throw new SugarApiExceptionNeedLogin("No valid authentication for user.");                        
                    } else {
                        throw $loginException;
                    }
                }
            }

            if ( $isLoggedIn ) {
                // This is needed to load in the app_strings and the app_list_strings and the such
                $this->loadUserEnvironment();
            } else {
                $this->loadGuestEnvironment();
            }

            // This loads the path variables in, so that on the /Accounts/abcd, $module is set to Accounts, and $id is set to abcd
            $pathVars = $this->getPathVars($path,$route);

            if ( count($_GET) > 0 ) {
                // This has some get arguments, let's parse those in
                // We need to pre-parse this for JSON-encoded arguments because the XSS stuff will mangle them, and to keep symmetrywith POST style data
                $getVars = $_GET;
                if ( !empty($route['jsonParams']) ) {
                    foreach ( $route['jsonParams'] as $fieldName ) {
                        if ( isset($_GET[$fieldName]) && !empty($_GET[$fieldName]) &&  $_GET[$fieldName]{0} == '{' ) {
                            // This may be JSON data
                            $rawValue = $GLOBALS['RAW_REQUEST'][$fieldName];
                            $jsonData = json_decode($rawValue,true,32);
                            if ( $jsonData == null ) {
                                // Did not decode, could be a string that just happens to start with a '{', don't mangle it further
                                continue;
                            }
                            // Need to dig through this array and make sure all of the elements in here are safe
                            $getVars[$fieldName] = securexss($jsonData);
                        }
                    }
                }
            } else {
                $getVars = array();
            }


            if ( isset($route['rawPostContents']) && $route['rawPostContents'] ) {
                // This route wants the raw post contents
                // We just ignore it here, the function itself has to know how to deal with the raw post contents
                // this will mostly be used for binary file uploads.
                $postVars = array();
            } else if ( count($_POST) > 0 ) {
                // They have normal post arguments
                $postVars = securexss($_POST);
            } else {
                $postContents = null;
                if ( !empty($GLOBALS['HTTP_RAW_POST_DATA']) ) {
                    $postContents = $GLOBALS['HTTP_RAW_POST_DATA'];
                } else {
                    $postContents = file_get_contents('php://input');
                }
                if ( !empty($postContents) ) {
                    // This looks like the post contents are JSON
                    // Note: If we want to support rest based XML, we will need to change this
                    $postVars = json_decode($postContents,true);
                    if ( !is_array($postVars) ) {
                        // FIXME: Handle improperly encoded JSON
                        $postVars = array();
                    }
                    $postVars = securexss($postVars);
                } else {
                    // No posted variables
                    $postVars = array();
                }
            }

            // I know this looks a little weird, overriding post vars with get vars, but
            // in the case of REST, get vars are fairly uncommon and pretty explicit, where
            // the posted document is probably the output of a generated form.
            $argArray = array_merge($postVars,$getVars,$pathVars);

            // Trying to fetch correct module while API use search
            $module = $route['className'];

            if (isset($argArray['module'])) {
                $module = $argArray['module'];
            } elseif (isset($argArray['module_list'])) {
                $module = $argArray['module_list'];
            }

            SugarMetric_Manager::getInstance()->setTransactionName('rest_' . $module . '_' . $route['method']);

            $apiClass = $this->loadApiClass($route);
            $apiMethod = $route['method'];

            $output = $apiClass->$apiMethod($this,$argArray);

            $this->respond($output, $route, $argArray);

        } catch ( Exception $e ) {
            $this->handleException($e);
        }
    }

    /**
     * Set the Request headers in an array
     * @return bool
     */
    public function setRequestHeaders() {
        $headers = array();
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace('HTTP_', '', $key);
            $headers[$header] = $value;
        }
        $this->request_headers = $headers;
        return true;
    }

    /**
     * Gets the raw path of the request
     *
     * @return string
     */
    public function getRawPath() {
        if ( !empty($_REQUEST['__sugar_url']) ) {
            $rawPath = $_REQUEST['__sugar_url'];
        } else if ( !empty($_SERVER['PATH_INFO']) ) {
            $rawPath = $_SERVER['PATH_INFO'];
        } else {
            $rawPath = '/';
        }

        return $rawPath;
    }

    /**
     * Gets the leading portion of the URI for a resource
     *
     * @param array|string $resource The resource to fetch a URI for as an array
     *                               of path parts or as a string
     * @return string The path to the resource
     */
    public function getResourceURI($resource, $options = array()) {
        $this->setResourceURIBase($options);

        // Empty resources are simply the URI for the current request
        if (empty($resource)) {
            $siteUrl = SugarConfig::get('site_url');
            return $siteUrl . $_SERVER['REQUEST_URI'];
        }

        if (is_string($resource)) {
            $parts = explode('/', $resource);
            return $this->getResourceURI($parts);
        } elseif (is_array($resource)) {
            // Logic here is, if we find a GET route for this resource then it
            // should be valid. In most cases, where there is a POST|PUT|DELETE
            // route that does not have a GET, we're not going to be handing that
            // URI out anyway, so this is a safe validation assumption.
            list($version,) = $this->parsePath($this->getRawPath());
            $route = $this->findRoute($resource, $version, 'GET');
            if ($route != false) {
                return $this->resourceURIBase . implode('/', $resource);
            }
        }
    }

    /**
     * For cases in which HTML is the requested response type but json is the
     * intended body content, this returns an array of status code and message.
     * This will also be used by the exception handler when dispatching exceptions
     * under the same requested response type conditions.
     *
     * @param string $message
     * @param int $code
     * @return array
     */
    public function getHXRReturnArray($message, $code = 200) {
        return array(
            'xhr' => array(
                'code' => $code,
                'message' => $message,
            ),
        );
    }

    /**
     * Attempts to find the route for this request, API version and request method
     *
     * @param array $path The request path
     * @param int $version The API version number
     * @param string $requestType The request method
     * @param string $platform The platform of the request
     * @return mixed
     */
    protected function findRoute($path, $version, $requestType, $platform = 'base') {
        // Load service dictionary
        $this->dict = $this->loadServiceDictionary('ServiceDictionaryRest');
        return $this->dict->lookupRoute($path, $version, $requestType, $platform);
    }

    /**
     * Maps the route path with the request path to set variables from the request
     *
     * @param array $path The request path
     * @param array $route The route for this request
     * @return array
     */
    protected function getPathVars($path,$route) {
        $outputVars = array();
        foreach ( $route['pathVars'] as $i => $varName ) {
            if ( !empty($varName) ) {
                $outputVars[$varName] = $path[$i];
            }
        }

        return $outputVars;
    }

    /**
     * Handles exception responses
     *
     * @param Exception $exception
     */
    protected function handleException(Exception $exception) {
        if ( is_a($exception,"SugarApiException") ) {
            $httpError = $exception->getHttpCode();
            $errorLabel = $exception->getErrorLabel();
            $message = $exception->getMessage();
        } else if ( is_a($exception,"OAuth2ServerException") ) {
            //The OAuth2 Server uses a slightly different exception API
            $httpError = $exception->getHttpCode();
            $errorLabel = $exception->getMessage();
            $message = $exception->getDescription();
        } else {
            $httpError = 500;
            $errorLabel = 'unknown_error';
            $message = $exception->getMessage();
        }
        header("HTTP/1.1 {$httpError} ".$errorLabel);

        $GLOBALS['log']->error('An exception happened: ( '.$httpError.': '.$errorLabel.')'.$message);

        // For edge cases when an HTML response is needed as a wrapper to JSON
        if (isset($_REQUEST['format']) && $_REQUEST['format'] == 'sugar-html-json') {
            if (!isset($_REQUEST['platform']) || (isset($_REQUEST['platform']) && $_REQUEST['platform'] == 'portal')) {
                $message = htmlentities(json_encode($this->getHXRReturnArray($message, $httpError)));
                header("HTTP/1.0 200 Success");
                echo($message);
                die();
            }
        }

        // Send proper headers
        header("Content-Type: application/json");
        header("Cache-Control: no-store");

        $replyData = array(
            'error'=>$errorLabel,
        );
        if( !empty($message) ) {
            $replyData['error_message'] = $message;
        }

        echo(json_encode($replyData));
        die();
    }

    /**
     * Parses the request uri or request path as well as fetching the API request
     * version
     *
     * @param string $rawPath
     * @return array
     */
    protected function parsePath($rawPath) {
        $pathBits = explode('/',trim($rawPath,'/'));

        $versionBit = array_shift($pathBits);

        $version = (float)ltrim($versionBit,'v');

        return array($version,$pathBits);

    }

    /**
     * Handles authentication of the current user
     *
     * @param string $platform The platform type for this request
     * @returns bool Was the login successful
     * @throws SugarApiExceptionNeedLogin
     */
    protected function authenticateUser() {
        $valid = false;
        
        $token = $this->grabToken();

        if ( !empty($token) ) {
            try {
                $oauthServer = SugarOAuth2Server::getOAuth2Server();
                $oauthServer->verifyAccessToken($token);
                if ( isset($_SESSION['authenticated_user_id']) ) {
                    $valid = true;
                    $GLOBALS['current_user'] = BeanFactory::getBean('Users',$_SESSION['authenticated_user_id']);
                }
            } catch ( OAuth2AuthenticateException $e ) {
                // This was failing if users were passing an oauth token up to a public url.
                $valid = false;
            }
        }

        if ( $valid === false ) {
            // In the case of large file uploads that are too big for the request too handle AND
            // the auth token being sent as part of the request body, you will get a no auth error
            // message on uploads. This check is in place specifically for file uploads that are too
            // big to be handled by checking for an empty request body for POST and PUT file requests.

            // Grab our path elements of the request and see if this is a files request
            $pathParts = $this->parsePath($this->getRawPath());
            if (isset($pathParts[1]) && is_array($pathParts[1]) && in_array('file', $pathParts[1])) {
                // If this is a POST request then we can inspect the $_FILES and $_POST arrays
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // If the post and files array are both empty on a POST request...
                    if (empty($_FILES) && empty($_POST)) {
                        throw new SugarApiExceptionRequestTooLarge('Request is too large');
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
                    // PUT requests need to read the php://input stream
                    // Keep in mind that reading php://input is a one time deal
                    // But since we are bound for an exception here this is a safe
                    // consumption
                    $input = file_get_contents('php://input');
                    if (empty($input)) {
                        throw new SugarApiExceptionRequestTooLarge('Request is too large');
                    }
                }
            }
            $exception = (isset($e)) ? $e : false;
            return array('isLoggedIn' => false, 'exception' => $exception);
        }

        SugarApplication::trackLogin();

        // Setup visibility where needed
        $oauthServer->setupVisibility();

        LogicHook::initialize()->call_custom_logic('', 'after_session_start');

        $this->user = $GLOBALS['current_user'];

        return array('isLoggedIn' => true, 'exception' => false);
    }

    /**
     * Looks in all the various nooks and crannies and attempts to find an authentication header
     *
     * @returns string The oauth token
     */
    protected function grabToken()
    {
        // Bug 61887 - initial portal load dies with undefined variable error
        // Initialize the return var in case all conditionals fail
        $sessionId = '';
        
        if ( isset($_SERVER['HTTP_OAUTH_TOKEN']) ) {
            // Passing a session id claiming to be an oauth token
            $sessionId = $_SERVER['HTTP_OAUTH_TOKEN'];
        } else if ( isset($_POST['oauth_token']) ) {
            $sessionId = $_POST['oauth_token'];
        } else if ( isset($_GET['oauth_token']) ) {
            $sessionId = $_GET['oauth_token'];
        } else if ( function_exists('apache_request_headers') ) {
            // Some PHP implementations don't populate custom headers by default
            // So we have to go for a hunt
            $headers = apache_request_headers();
            foreach ( $headers as $key => $value ) {
                if ( strtolower($key) == 'oauth_token' ) {
                    $sessionId = $value;
                    break;
                }
            }
        }

        return $sessionId;
    }

    /**
     * Sets the proper Content-Type header for the response based on either a
     * 'format' request arg or an Accept header.
     *
     * @TODO Handle Accept header parsing to determine content type
     * @access protected
     * @param array $args The request arguments
     */
    protected function setContentTypeHeader($args) {
        if (isset($args['format']) && $args['format'] == 'sugar-html-json') {
            $this->setHeader('Content-Type', 'text/html');
        } else {
            // @TODO: Handle other response types here
            $this->setHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Sends the content to the client
     *
     * @TODO Handle proper content disposition based on response content type
     * @access protected
     * @param mixed $content
     * @param array $args The request arguments
     */
    protected function sendContent($content, $args) {
        $response = json_encode($content);
        if (isset($args['format']) && $args['format'] == 'sugar-html-json' && (!isset($args['platform']) || $args['platform'] == 'portal')) {
            $response = htmlentities($response);
        }
        echo $response;
    }

    /**
     * Set a response header
     * @param string $header 
     * @param string $info 
     * @return bool
     */
    public function setHeader($header, $info) {
        $this->response_headers[$header] = $info;
        return true;
    }

    /**
     * Check if the response headers have a header set
     * @param string $header 
     * @return bool
     */
    public function hasHeader($header) {
        // do a case insensitive check
        $headers = array_change_key_case($this->response_headers, CASE_LOWER);
        return array_key_exists(strtolower($header), $headers);
    }

    /**
     * Send the response headers
     * @return bool
     */
    public function sendHeaders($etag) {
        if(headers_sent()) {
            return false;
        }
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->setGetHeaders();
            if (!empty($etag)){
                $this->generateETagHeader($etag);
            }
        }
        else {
            $this->setPostHeaders();
        }

        foreach($this->response_headers AS $header => $info) {
            header("{$header}: {$info}");
        }
        return true;
    }

    /**
     * Set Post Headers
     * @return bool
     */
    protected function setPostHeaders() {
        if(!$this->hasHeader('Cache-Control')) {
            $this->setHeader('Cache-Control', 'no-cache, must-revalidate');    
        }
        if(!$this->hasHeader('Pragma')) {
            $this->setHeader('Pragma', 'no-cache');
        }
        if(!$this->hasHeader('Expires')) {
            $this->setHeader('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
        }
        return true;
    }

    /**
     * Explicitly Set Get Headers
     * @return bool
     */
    protected function setGetHeaders() {
        if(!$this->hasHeader('Cache-Control')) {
            $this->setHeader('Cache-Control','');
        }
        if(!$this->hasHeader('Pragma')) {
            $this->setHeader('Pragma', '');
        }
        if(!$this->hasHeader('Expires')) {
            $this->setHeader('Expires', '');
        }        
        return true;
    }
    /**
     * Sets the leading portion of any request URI for this API instance
     *
     * @access protected
     */
    protected function setResourceURIBase($options = array()) {
        // Only do this if it hasn't been done already
        if (empty($this->resourceURIBase)) {
            // Default the base part of the request URI
            $apiBase = 'api/rest.php/';

            // Check rewritten URLs AND request uri vs script name
            if (isset($_REQUEST['__sugar_url']) && strpos($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME']) === false) {
                // This is a forwarded rewritten URL
                $apiBase = 'rest/';
            }

            // Get our version
            preg_match('#v(?>\d+)/#', $_SERVER['REQUEST_URI'], $m);
            if (isset($m[0])) {
                $apiBase .= $m[0];
            }

            // This is for our URI return value
            $siteUrl = SugarConfig::get('site_url');

            if (isset($options['relative']) && $options['relative'] === true) {
                $siteUrl = '';
            } else {
                $siteUrl .= '/';
            }

            // Get the file uri bas
            $this->resourceURIBase = $siteUrl . $apiBase;
        }
    }

    /**
     * Handles the response
     *
     * @param array|string $output The output to send
     * @param array $route The route for this request
     * @param array $args The request arguments
     */
    protected function respond($output, $route, $args) {
        // TODO: gzip, and possibly XML based output
        if (!empty($route['rawReply'])) {
            $etag = empty($route['noEtag']) ? md5($output) : false;
            $this->sendHeaders($etag);
            echo $output;
        } else {
            // Handle content type header sending
            $this->setContentTypeHeader($args);
            $etag = empty($route['noEtag']) ? md5(json_encode($output)) : false;
            $this->sendHeaders($etag);
            // Send the content
            $this->sendContent($output, $args);
        }

        $this->response_headers = array();
    }
    /**
	 * generateETagHeader
	 *
	 * This function generates the necessary cache headers for using ETags with dynamic content. You
	 * simply have to generate the ETag, pass it in, and the function handles the rest.
	 *
	 * @param string $etag ETag to use for this content.
	 */
	protected function generateETagHeader($etag){
        if(isset($_SERVER["HTTP_IF_NONE_MATCH"])){
            if($etag == $_SERVER["HTTP_IF_NONE_MATCH"]){
                ob_clean();
                header("ETag: " . $etag);
                header("Cache-Control:");
                header('Expires: ');
                header("Pragma:");
                header("Status: 304 Not Modified");
                header($_SERVER['SERVER_PROTOCOL']." 304 Not Modified");
                die();
            }
        }
        $this->setHeader('ETag', $etag);
	}
}
