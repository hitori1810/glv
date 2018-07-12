<?PHP
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

    /**
    * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
    */
    require_once('modules/J_Class/J_Class_sugar.php');
    class J_Class extends J_Class_sugar {
        var $adultKOC = array(
            'GE' => 'General English',
            'BE' => 'Business English',
            'Pronunciation' => 'Pronunciation',
            //'IELTS' => 'IELTS',
            'Toeic' => 'Toeic', 
        );

        /**
        * This is a depreciated method, please start using __construct() as this method will be removed in a future version
        *
        * @see __construct
        * @depreciated
        */
        function J_Class(){
            self::__construct();
        }

        public function __construct(){
            parent::__construct();
        }

        public function isAdultKOC() {
            if (isset($this->adultKOC[$this->kind_of_course])) {
                return true;
            }
            return false;            
        }

        public function getKOC() {
            return $this->adultKOC;            
        }

    }
?>