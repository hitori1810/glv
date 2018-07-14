<?php
include_once("custom/include/utils.php");

/**
* CustomUtils
* Modified By Thanh Le At 04/2015
* Removed AjaxgetAddress
*/

function getAvailableModules() {
    require_once('modules/MySettings/TabController.php');
    global $app_list_strings;
    // Unused modules
    $excludeModule = array('Home', 'Emails', 'Forecasts', 'Bugs', 'Reports', 'Calendar');

    $controller = new TabController();
    $tabs = $controller->get_tabs_system();

    // Loop all the enabled modules name to convert into an array of: module_name => module_label
    $availableModules = array('' => '');
    foreach($tabs[0] as $moduleName) {  // $tabs[0] is the list of avaiable modules
        if(!in_array($moduleName, $excludeModule)) {
            $moduleLabel = $app_list_strings['moduleList'][$moduleName];    // Get module label
            $availableModules[$moduleName] = $moduleLabel;
        }
    }

    return $availableModules;
}

/**
* Convert vietnamese name to no marks
*/
function viToEn($str){
    $str = html_entity_decode_utf8($str);
    //Convert Unicode Dung San
    $str=preg_replace('/(à|á|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ)/','a',$str);
    $str=preg_replace('/(Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ)/','A',$str);

    $str=preg_replace("/(é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ)/",'e',$str);
    $str=preg_replace("/(É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ)/",'E',$str);

    $str=preg_replace("/(í|ì|ỉ|ị|ĩ)/",'i',$str);
    $str=preg_replace("/(Í|Ì|Ỉ|Ĩ|Ị)/",'i',$str);

    $str=preg_replace("/(ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ)/",'o',$str);
    $str=preg_replace("/(Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ)/",'O',$str);

    $str=preg_replace("/(ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự)/",'u',$str);
    $str=preg_replace("/(Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự)/",'U',$str);

    $str=preg_replace("/(ý|ỳ|ỷ|ỹ|ỵ)/",'y',$str);
    $str=preg_replace("/(Ý|Ỳ|Ỷ|Ỹ|Ỵ)/",'Y',$str);

    $str=preg_replace("/(đ)/",'d',$str);
    $str=preg_replace("/(Đ)/",'D',$str);


    //Convert Unicode To Hop
    $str=preg_replace('/(à|á|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ)/','a',$str);
    $str=preg_replace('/(Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ)/','A',$str);

    $str=preg_replace("/(é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ)/",'e',$str);
    $str=preg_replace("/(É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ)/",'E',$str);

    $str=preg_replace("/(í|ì|ỉ|ị|ĩ)/",'i',$str);
    $str=preg_replace("/(Í|Ì|Ỉ|Ĩ|Ị)/",'i',$str);

    $str=preg_replace("/(ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ)/",'o',$str);
    $str=preg_replace("/(Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ)/",'O',$str);

    $str=preg_replace("/(ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự)/",'u',$str);
    $str=preg_replace("/(Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự)/",'U',$str);

    $str=preg_replace("/(ý|ỳ|ỷ|ỹ|ỵ)/",'y',$str);
    $str=preg_replace("/(Ý|Ỳ|Ỷ|Ỹ|Ỵ)/",'Y',$str);

    $str=preg_replace("/(đ)/",'d',$str);
    $str=preg_replace("/(Đ)/",'D',$str);
    $str=preg_replace("/(`)/",'',$str);
    return $str;
}

function getTeamType($team_id){
//    return $GLOBALS['db']->getOne("SELECT team_type FROM teams WHERE id = '$team_id' AND deleted = 0");
    //Update by Tung Bui - Always use type Junior
    return "Junior";
}

function getParentTeamName($team_id){
    return $GLOBALS['db']->getOne("SELECT l1.name
        FROM teams
        LEFT JOIN teams l1 ON l1.id = teams.parent_id AND l1.deleted <> 1
        WHERE teams.id = '$team_id' AND teams.deleted = 0");
}

/**
* Generate an array of string dates between 2 dates
*
* @param string $start Start date
* @param string $end End date
* @param string $format Output format (Default: Y-m-d)
*
* @return array
*/
function getDatesFromRange($start, $end, $format = 'Y-m-d') {
    $array = array();
    $interval = new DateInterval('P1D');

    $realEnd = new DateTime($end);
    $realEnd->add($interval);

    $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

    foreach($period as $date) {
        $array[] = $date->format($format);
    }

    return $array;
}
function get_string_between($string, $start = "'", $end = "'"){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}       
function getUserId($profile_url){
    $COOKIEFILE = 'cookie.txt';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $COOKIEFILE);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $COOKIEFILE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_URL, 'https://findmyfbid.com/');
    $data = array('url' => $profile_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    preg_match_all('!\d+!', $result, $userId);
    return $userId[0][0];
}

function getSaintListOptions(){
    $options = array();
    $sql = "SELECT id, name
    FROM c_saint
    WHERE deleted <> 1";
    $result = $GLOBALS['db']->query($sql);
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        $options[$row['id']] = $row['name'];
    }
    return $options;
}

function getGradeOptions(){
    $options = array();
    $sql = "SELECT id, name
    FROM c_grade
    WHERE deleted <> 1";
    $result = $GLOBALS['db']->query($sql);
    while($row = $GLOBALS['db']->fetchByAssoc($result)){
        $options[$row['id']] = $row['name'];
    }
    return $options;
}