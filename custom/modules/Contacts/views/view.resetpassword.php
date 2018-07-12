<?php
class ContactsViewResetPassword extends SugarView{
    function ContactsViewResetPassword(){

        parent::SugarView();
    }
    function preDisplay(){
        $studentID = $_POST['student_id'];

        $student = new Contact();
        $student->retrieve($studentID);
        if(empty($student->user_id))
            echo json_encode(
                array(
                    'success' => 1,
                    'new_password' => '',
                    "errorLabel" => "Please, Active portal account first !!",
                )
            );
        else{
            $studentUser = new User();
            $studentUser->retrieve($student->user_id);
            if(strpos($student->team_name,'360'))
                $student->password_generated = 'portal2017';
            else   $student->password_generated = 'portal2017';

            $additionalData = array(
                'link' => false,
                'password' => $student->password_generated,
                'system_generated_password' => '0',
            );
            $studentUser->setNewPassword($additionalData['password'], '0');
            //$studentUser->sendEmailForPassword($template, $additionalData);

            $studentUser2 = new User();
            $studentUser2->retrieve($student->user_id);
            $student->portal_password = $studentUser2->user_hash;
            $student->save();
            echo json_encode(
                array(
                    'success' => 1,
                    'new_password' => $student->password_generated,
                    "errorLabel" => "The password has been reset successfully !!
                    <br><br>New Password: &nbsp;<b style='color:red;'>{$student->password_generated}</b>",
                )
            );
        }

    }
}
?>

