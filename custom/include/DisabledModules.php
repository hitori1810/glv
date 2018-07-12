<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    
    /*
    *   DisabledModules.php 
    *   Author: Hieu Nguyen
    *   Date: 2017-08-09
    *   Pupose: Allow to configure for disabled modules (hide them in all views in the system)
    */

    $disabledModules = array(
        // Module name
        'bc_automizer_actions',
        'bc_automizer_condition',
        'C_HelpTextConfig',
        'C_KeyboardSetting',
        'C_Rooms',
        'c_Timekeeping',
        'C_Timesheet',   
        'ContractTypes',
        'Forecasts',
        'ForecastSchedule',
        'J_Inventory',
        'J_Inventorydetail',
        'EAPM',
        'KBDocuments',
        'Products',         
        'ProductTypes',
        'Project',
        'ProjectResources',
        'ProjectTask',
        'Quotas',
        'Quotes',
        'bc_automizer_actions',
        'bc_automizer_condition',
        'bc_submission_data',
        'bc_survey',
        'bc_survey_answers',
        'bc_survey_automizer',
        'bc_survey_language',
        'bc_survey_pages',
        'bc_survey_questions',
        'bc_survey_submission',
        'bc_survey_template',  
    );
    
    
    // Get lisence info
    $lisence = getLisenceOnlineCRM();
    
    switch($lisence['version']){
        case "Free":
            $disabledModules[] = 'Campaigns';
            $disabledModules[] = 'CampaignTrackers';
            $disabledModules[] = 'CampaignLog';
            $disabledModules[] = 'EmailMarketing';
            $disabledModules[] = 'Emails';    
                   
            $disabledModules[] = 'Accounts';           
            $disabledModules[] = 'Contracts';    
                   
            $disabledModules[] = 'C_Teachers';           
            $disabledModules[] = 'J_Teachercontract';   
                    
            $disabledModules[] = 'J_Feedback';           
            $disabledModules[] = 'J_PTResult'; 
            $disabledModules[] = 'J_Gradebook'; 
            $disabledModules[] = 'J_GradebookConfig'; 
            $disabledModules[] = 'J_GradebookDetail'; 
                      
            $disabledModules[] = 'J_Discount';           
            $disabledModules[] = 'J_Partnership';           
            $disabledModules[] = 'J_Sponsor';           
            $disabledModules[] = 'J_Voucher';           
            break;
        case "Standard":                    
            $disabledModules[] = 'Accounts';           
            $disabledModules[] = 'Contracts';    
                   
            $disabledModules[] = 'C_Teachers';           
            $disabledModules[] = 'J_Teachercontract';   
                    
            $disabledModules[] = 'J_Feedback';   
            $disabledModules[] = 'J_Gradebook'; 
            $disabledModules[] = 'J_GradebookConfig'; 
            $disabledModules[] = 'J_GradebookDetail';     
            break;
        case "Profesional":
            break;
        default:
            break;
    }