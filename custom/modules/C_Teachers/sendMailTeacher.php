<?php
    require_once("custom/modules/C_Teachers/TrainerCron.php");
    array_map('unlink', glob("custom/uploads/pdf/*"));
    $si = TrainerCron::create_pdf_tc();
    $si = TrainerCron::send_mail_tc(); 
    echo json_encode(array(
        "success" => "1",
    ));
    header("Refresh:0");
?>
