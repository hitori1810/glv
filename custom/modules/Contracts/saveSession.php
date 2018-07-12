<?php
    $_SESSION['contract_id'] = $_GET['contract_id'];
    
    header('Location: index.php?module=Import&action=Step1&import_module=Contacts&return_module=Contracts&return_action=index');
?>
