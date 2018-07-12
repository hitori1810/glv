<?php 
include("include/generic/SugarWidgets/SugarWidgetFielddatetime.php");
    function getWidgetDate($layout_def){
        
        switch ($layout_def['qualifier_name']){
            case 'on':
                return queryFilterOn($layout_def);
                break;
            case 'before':
                return $sf->queryFilterBefore($layout_def);
                break;
            case 'after':
                return $sf->queryFilterAfter($layout_def);
                break;
            case 'between_dates':
                return $sf->queryFilterBetween_Dates($layout_def);
                break;
            case 'not_equals_str':
                return $sf->queryFilterNot_Equals_str($layout_def);
                break;
            case 'tp_yesterday':
                return $sf->queryFilterTP_yesterday($layout_def);
                break;
            case 'tp_today':
                return $sf->queryFilterTP_today($layout_def);
                break;
            case 'tp_tomorrow':
                return $sf->queryFilterTP_tomorrow($layout_def);
                break;
            case 'tp_last_7_days':
                return $sf->queryFilterTP_last_7_days($layout_def);
                break;
            case 'tp_next_7_days':
                return $sf->queryFilterTP_next_7_days($layout_def);
                break;
            case 'tp_last_month':
                return $sf->queryFilterTP_last_month($layout_def);
                break;
            case 'tp_this_month':
                return $sf->queryFilterTP_this_month($layout_def);
                break;
            case 'tp_next_month':
                return $sf->queryFilterTP_next_month($layout_def);
                break;
            case 'tp_last_30_days':
                return $sf->queryFilterTP_last_30_days($layout_def);
                break;
            case 'tp_next_30_days':
                return $sf->queryFilterTP_next_30_days($layout_def);
                break;
            case 'tp_last_quarter':
                return $sf->queryFilterTP_last_quarter($layout_def);
                break;
            case 'tp_this_quarter':
                return $sf->queryFilterTP_this_quarter($layout_def);
                break;
            case 'tp_next_quarter':
                return $sf->queryFilterTP_next_quarter($layout_def);
                break;
            case 'tp_last_year':
                return $sf->queryFilterTP_last_year($layout_def);
                break;
            case 'tp_this_year':
                return $sf->queryFilterTP_this_year($layout_def);
                break;
            case 'tp_next_year':
                return $sf->queryFilterTP_next_year($layout_def);
                break;
            default:
                return false;
                break;
        } 
    }

?>
