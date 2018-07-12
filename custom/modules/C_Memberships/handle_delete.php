<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class handleDelete_Card {
        function handleDelete_Card(&$bean, $event, $arguments)
        {  
           $ct = BeanFactory::getBean('Contacts',$bean->c_memberships_contacts_2contacts_idb);
           define('UPLOAD_DIR', 'upload/');
           define('RESIZE_DIR', 'custom/uploads/imagesResize/');
           unlink(UPLOAD_DIR.$ct->picture);
           unlink(RESIZE_DIR.$ct->picture);                  
        }
    }
?>
