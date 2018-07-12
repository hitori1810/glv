<?php

    interface sms_interface {
        public function authenticate($account_id); 
        public function send_message($to,$text,$parent_type,$parent_id,$user_id);
        public function send_batch_message($to_array,$text,$parent_type);
    }
?>