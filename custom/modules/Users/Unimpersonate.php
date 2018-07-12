<?php
    global $masquerade_user;

    if(!empty($_SESSION['impersonating_user']) && is_admin($_SESSION['impersonating_user'])) {
        global $current_user;

        $GLOBALS['log']->debug("User ".$_SESSION['impersonating_user']->user_name." is unimpersonted from ".$current_user->user_name);

        $GLOBALS['current_user'] = $_SESSION['impersonating_user'];
        $current_user = $_SESSION['impersonating_user'];
        $_SESSION['authenticated_user_id'] = $current_user->id;

        unset($_SESSION['impersonating_user']);


        header('Location: index.php?module=Users&action=ListView');
    }
?>