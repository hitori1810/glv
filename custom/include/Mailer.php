<?php
    require_once('include/SugarPHPMailer.php');
    class Mailer{
        /**
        * Function use send one email for receiver
        *
        * @param mixed $mailRecipient
        * @param mixed $mailBody
        * @param mixed $recipient
        * @param mixed $mailSubj   mail subject
        * @bool mixed $log  log sent mail
        */
        function sendMail($receiver = array(), $template_id, $replace_array, $log = false){
            $mail = new SugarPHPMailer();
            $admin = new Administration();
            $admin->retrieveSettings();
            $mail->setMailerForSystem();
            $mail->ClearAllRecipients();
            if ($admin->settings['mail_sendtype'] == "SMTP") {
                $mail->Host = $admin->settings['mail_smtpserver'];
                $mail->Port = $admin->settings['mail_smtpport'];
                if ($admin->settings['mail_smtpauth_req']) {
                    $mail->SMTPAuth = TRUE;
                    $mail->Username = $admin->settings['mail_smtpuser'];
                    $mail->Password = $admin->settings['mail_smtppass'];
                }
                $mail->Mailer   = "smtp";
                $mail->SMTPKeepAlive = true;
            }
            else {
                $mail->mailer = 'sendmail';
            }
            $mail->Sender   = $admin->settings['notify_fromaddress'];
            $mail->From     = $admin->settings['notify_fromaddress'];
            $mail->FromName = $admin->settings['notify_fromname'];
            $mail->ContentType = "text/html"; //"text/html"

            $emailtemplate= new EmailTemplate();  
            $emailtemplate->retrieve($template_id);
            $mail->Subject = $emailtemplate->subject;

            //escape email template contents.
            $mailBody->body_html=from_html($emailtemplate->body_html);

            // Replace content
            foreach ($replace_array as $key => $value) {
                $mailBody->body_html = str_replace($key, $value, $mailBody->body_html);
            }

            $mail->Body = wordwrap($mailBody->body_html);
            if($receiver['email'] != ''){
                $validation = new EmailMan();
                if(!$validation->valid_email_address($receiver['email'])){
                    return;
                }
                else{
                    $mail->AddAddress($receiver['email'], $receiver['name']);
                }
            }
            $mail->prepForOutbound();
            $result = $mail->Send();
            if($log) { 
                $email_log = new C_NotifyEmailTracker();
                $email_log->email = $receiver['email'];
                $email_log->notify_type = 'log_send_mail';
                $email_log->name = "[".(date('Y-m-d'))."]".$receiver_name;
                if(isset($receiver['id']) && $receiver['id'] != '') {
                    $email_log->assigned_user_id = $receiver['id'];
                    $email_log->recipients = "^".$receiver['id']."^";
                }
                $email_log->date_sent = $GLOBALS['timedate']->nowDbDate();         
                $email_log->is_success = ($result)?'1':'0';         
                $email_log->email_template = $template_id; 
                $email_log->body_html = $mailBody->body_html;
                $email_log->save();        
            }
            return $result;
        }
        /***
        * @param $parameters (mailReceiver => 'long@gmail.com',
        *                     receiverName => 'anh Long dep trai',
        *                     template_id => 'id template dang su dung');
        * @param $replaceArray ($bien => 'gia tri thay the')
        * @param array $attachFile (array(path => '', name => ''))
        * @return bool
        * @throws Exception
        * @throws phpmailerException
        */

        
    }
?>
