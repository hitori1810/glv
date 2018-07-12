<?php
class AutoCodeHelper {
    
    static function createCode($module_name){
        $focus = BeanFactory::newBean($module_name);

        $table = $focus->table_name;
        $sql = "SELECT name, code_separator, code_field, is_reset, custom_table, add_date, date_format, zero_padding, first_num FROM c_configid WHERE custom_table = '{$table}' AND deleted = 0 ORDER BY date_entered DESC LIMIT 1;";
        $res = $GLOBALS['db']->query($sql);
        if($r = $GLOBALS['db']->fetchByAssoc($res)){

            //pattern to detect valid code.
            $pattern = "/^" . $r['name'] . ($r['add_date']?"[0-9]{".strlen(date($r['date_format']))."}":"") . $r['code_separator'] . "([0-9]+)$/";

            if(isset($pattern)) {

                $r['is_reset'] == '1' ? $len = strlen($r['name']) + strlen(date($r['date_format'])) : $len = strlen($r['name']);
                $r['is_reset'] == '1' ? $str = $r['name'].date($r['date_format']) : $str = $r['name'];

                $query = "SELECT ".$r['code_field']." FROM " . $r['custom_table'] ." WHERE ( ". $r['code_field'] . " <> '' AND ". $r['code_field'] . " IS NOT NULL) AND LEFT({$r['code_field']},$len) = '$str' ORDER BY RIGHT(" . $r['code_field'] . ", ".$r['zero_padding'].") DESC LIMIT 1";
                $result = $GLOBALS['db']->query($query);


                if($row = $GLOBALS['db']->fetchByAssoc($result)){
                    $last_code = $row[$r['code_field']];
                }else{
                    //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                    $last_code = $r['name'] . ($r['add_date']?date($r['date_format']):"") . $r['code_separator'] . $r['first_num'];
                }


                preg_match($pattern, $last_code, $matches);
                $num=$matches[1];
                $num++;
                $pads = $r['zero_padding'] - strlen($num);
                $new_code = $r['name'] . ($r['add_date']?date($r['date_format']):"") . $r['code_separator'];

                //preform the lead padding 0
                for($i=0; $i < $pads; $i++) {
                    $new_code .= "0";
                }
                $new_code .= $num;
                //return code
                return $new_code;
            }  
        }
        return ''; // Return empty: No code generated 
    }
}
?>
