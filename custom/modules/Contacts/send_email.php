<?php
    require_once("include/SugarPHPMailer.php");
    require_once("include/workflow/alert_utils.php");
    require_once("custom/modules/C_Teachers/_helper.php");
    global $current_user;
    $team_id = $current_user->team_id;
    $admin = new Administration();
    $admin->retrieveSettings();
    $ct = BeanFactory::getBean('Contacts',$_POST['record']);

    $mail = new SugarPHPMailer;
    setup_mail_object($mail, $admin);
    $email = $_POST['email'];
    if(empty($_POST['email']))
    $email =  $ct->email1;
    $mail->addAddress($email, vn_str_filter($ct->name));  // Add a recipient
    $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
    $mail->isHTML(true); //  Set email format to HTML
    $mail->Subject = '[CRM] Here is your Username and Password login to Student Portal !'.$name;
    $mail->Body    = 'Dear <b>'.vn_str_filter($ct->name).'!</b><br>Your Username is: '.$_POST['username'].'<br>Password : '.$_POST['password'].'<br> Now, you can access to the student portal. Go to http://portal360.atlantic.edu.vn and log in with your account !';
    if(!$mail->Send())
    {
        $GLOBALS['log']->warn("Notifications: error sending e-mail (method: {$mail->Mailer}), (error: {$mail->ErrorInfo})");
        echo json_encode(array(
            "success" => "0",
        ));
    }else
      echo json_encode(array(
            "success" => "1",
        ));

?>
