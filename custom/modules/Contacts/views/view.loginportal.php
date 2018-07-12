<?php    
    class ContactsViewLoginPortal extends SugarView{
        var $bean2;        
        function ContactsViewLoginPortal(){      
            $this->bean2 = new Contact();   
            $this->record = $_REQUEST['record'] ;
            $this->bean2->retrieve($this->record);   
            
            $this->display();
        }

        function display(){    
            require_once("custom/include/utils/RestHelper.php");              
            $client = new RestHelper();  
            $session_id = $client->getSessionID($this->bean2->user_id);
            if($session_id) {
                $url = $GLOBALS['sugar_config']['portal_url']."/user/login?MSID=$session_id";  
                
                echo "<script>location.href = '{$url}'</script>"; 
                die();         
            } else {
                echo "<h3>This students can't login in portal!</h3>";  
            }        
        }
    }
?>
