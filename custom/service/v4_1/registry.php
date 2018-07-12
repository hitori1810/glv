<?php
if (!defined('sugarEntry')) define('sugarEntry', true);

require_once('service/v4_1/registry.php');

class registry_v4_1_custom extends registry_v4_1
{

    protected function registerFunction()
    {
        parent::registerFunction();

        $this->serviceClass->registerFunction(
            'get_sugar_language',   // Method name
            array('session' => 'xsd:string', 'type' => 'xsd:string', 'language' => 'xsd:string'), // Input types
            array('return' => 'xsd:string')   // Output types
        );

        $this->serviceClass->registerFunction(
            'change_password',   // Method name
            array('session' => 'xsd:string', 'current_password' => 'xsd:string', 'new_password' => 'xsd:string'), // Input types
            array('return' => 'xsd:string')   // Output types
        );

        $this->serviceClass->registerFunction(
            'set_user_preferences',   // Method name
            array('session' => 'xsd:string', 'preferences' => 'xsd:string'), // Input types
            array('return' => 'xsd:string')   // Output types
        );

        $this->serviceClass->registerFunction(
            'get_enrollment_list',   // Method name
            array('session' => 'xsd:string', 'student_id' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'get_lms_classes_list',   // Method name
            array('session' => 'xsd:string', 'student_id' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'get_loyalty_point',   // Method name
            array('session' => 'xsd:string', 'student_id' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'update_myelt_status',   // Method name
            array('session' => 'xsd:string', 'module' => 'xsd:string',
                'status' => 'xsd:string', 'module_id'=>'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'input_lms_membership',   // Method name
            array('session' => 'xsd:string',
                'student_id' => 'xsd:string',
                'class_id' => 'xsd:string',
                'class_code' => 'xsd:string',
                'membership_link' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'get_schedules',   // Method name
            array('session' => 'xsd:string', 'month' => 'xsd:string', 'year' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'getGradebookDetail',   // Method name
            array('session' => 'xsd:string', 'student_id' => 'xsd:string', 'class_id' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'getCertificate',   // Method name
            array('session' => 'xsd:string', 'studentID' => 'xsd:string', 'classID' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );
        $this->serviceClass->registerFunction(
            'check_session',   // Method name
            array('session' => 'xsd:string'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

        $this->serviceClass->registerFunction(
            'entryPoint',   // Method name
            array('session' => 'xsd:string', 'function' => 'xsd:string', 'param' => 'xsd:Array'), // Input types
            array('return' => 'xsd:Array')   // Output types
        );

    }
}