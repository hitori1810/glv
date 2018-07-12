<?php
    ini_set('zlib.output_compression','Off');
    if(isset($_REQUEST['isTempFile']) && ($_REQUEST['type']=="SugarFieldImage") && !empty($_REQUEST['id'])) {
        $local_location =  "upload://{$_REQUEST['id']}";   
        if(!file_exists( $local_location )) {
            $local_location = "include/images/default-profile.png";
        }
    } else {
        $local_location = "include/images/default-profile.png";
    }

    if(!file_exists( $local_location ) || strpos($local_location, "..")) {
        die("Can load image");
    } else {   
        $download_location = $local_location;
        $name = "";
        if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
            $name = urlencode($name);
            $name = str_replace("+", "_", $name);
        } 
        header("Pragma: public");
        header("Cache-Control: maxage=1, post-check=0, pre-check=0"); 
        $mime = getimagesize($download_location);
        if(!empty($mime)) {
            header("Content-Type: {$mime['mime']}");
        } else {
            header("Content-Type: image/png");
        }   
        // disable content type sniffing in MSIE
        header("X-Content-Type-Options: nosniff");
        header("Content-Length: " . filesize($local_location));
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
        set_time_limit(0);      
        @ob_end_clean();
        ob_clean();    
        readfile($download_location);
        @ob_flush();
    }
?>