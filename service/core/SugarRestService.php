<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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
 * Copyright (C) 2004-2014 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


require_once('service/core/SugarWebService.php');
require_once('service/core/SugarRestServiceImpl.php');

/**
 * Base class for rest services
 *
 */
class SugarRestService extends SugarWebService{
	protected $implementationClass = 'SugarRestServiceImpl';
	protected $restURL = "";
	protected $registeredFunc = array();

	/**
	 * Get Sugar REST class name for input/return type
	 *
	 * @param string $name
	 * @return string
	 */
	protected function _getTypeName($name)
	{
		if(empty($name)) return 'SugarRest';

		$name = clean_string($name, 'ALPHANUM');
		$type = '';
		switch(strtolower($name)) {
			case 'json':
				$type = 'JSON';
				break;
			case 'rss':
				$type = 'RSS';
				break;
			case 'serialize':
				$type = 'Serialize';
				break;
		}
		$classname = "SugarRest$type";
		if(!SugarAutoLoader::fileExists('service/core/REST/' . $classname . '.php')) {
			return 'SugarRest';
		}
		return $classname;
	}

	/**
	 * Constructor.
	 *
	 * @param String $url - REST url
	 */
	function __construct($url){
		$GLOBALS['log']->info('Begin: SugarRestService->__construct');
		$this->restURL = $url;

		$this->responseClass = $this->_getTypeName(@$_REQUEST['response_type']);
		$this->serverClass = $this->_getTypeName(@$_REQUEST['input_type']);
		$GLOBALS['log']->info('SugarRestService->__construct serverclass = ' . $this->serverClass);
		require_once('service/core/REST/'. $this->serverClass . '.php');
		$GLOBALS['log']->info('End: SugarRestService->__construct');
	} // ctor

	/**
	 * Its a no op method
	 *
	 * @access public
	 */
	public function registerType($name, $typeClass, $phpType, $compositor, $restrictionBase, $elements, $attrs, $arrayType){
  	} // fn

  	/**
  	 * This method registers all the functions you want to expose as services with REST
  	 *
  	 * @param String $function - name of the function
  	 * @param Array $input - assoc array of input values: key = param name, value = param type
  	 * @param Array $output - assoc array of output values: key = param name, value = param type
	 * @access public
  	 */
	function registerFunction($function, $input, $output){
		if(in_array($function, $this->excludeFunctions))return;
		$this->registeredFunc[$function] = array('input'=> $input, 'output'=>$output);
	} // fn

	/**
	 * It passes request data to REST server and sends response back to client
	 * @access public
	 */
	function serve(){
		$GLOBALS['log']->info('Begin: SugarRestService->serve');
		require_once('service/core/REST/'. $this->responseClass . '.php');
		$response  = $this->responseClass;

		$responseServer = new $response($this->implementation);
		$this->server->faultServer = $responseServer;
		$responseServer->faultServer = $responseServer;
		$responseServer->generateResponse($this->server->serve());
		$GLOBALS['log']->info('End: SugarRestService->serve');
	} // fn

	/**
	 * Enter description here...
	 *
	 * @param Array $excludeFunctions - All the functions you don't want to register
	 */
	function register($excludeFunctions = array()){

	} // fn

	/**
	 * This mehtod returns registered implementation class
	 *
	 * @return String - implementationClass
	 * @access public
	 */
	public function getRegisteredImplClass() {
		return $this->implementationClass;
	} // fn

	/**
	 * This mehtod returns registry class
	 *
	 * @return String - registryClass
	 * @access public
	 */
	public function getRegisteredClass() {
		return $this->registryClass;
	} // fn

	/**
	 * Sets the name of the registry class
	 *
	 * @param String $registryClass
	 * @access public
	 */
	function registerClass($registryClass){
		$this->registryClass = $registryClass;
	}

	/**
	 * This function registers implementation class name and creates an instance of rest implementation class
	 * it will be made on this class object
	 *
	 * @param String $implementationClass
	 * @access public
	 */
	function registerImplClass($className){
		$GLOBALS['log']->info('Begin: SugarRestService->registerImplClass');
		$this->implementationClass = $className;
		$this->implementation = new $this->implementationClass();
		$this->server = new $this->serverClass($this->implementation);
		$this->server->registerd = $this->registeredFunc;
		$GLOBALS['log']->info('End: SugarRestService->registerImplClass');
	} // fn

	/**
	 * This function sets the fault object on the REST
	 *
	 * @param SoapError $errorObject - This is an object of type SoapError
	 * @access public
	 */
	function error($errorObject){
		$GLOBALS['log']->info('Begin: SugarRestService->error');
		$this->server->fault($errorObject);
		$GLOBALS['log']->info('End: SugarRestService->error');
	} // fn

	/**
	 * This mehtod returns server
	 *
	 * @return String - server
	 * @access public
	 */
	function getServer(){
		return $this->server;
	} // fn


}
