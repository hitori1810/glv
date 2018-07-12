<?php
$dictionary['C_Teachers']['fields']['phone_mobile']['type'] = 'function';
$dictionary['C_Teachers']['fields']['phone_mobile']['function'] = array('name'=>'sms_phone', 'returns'=>'html', 'include'=>'custom/fieldFormat/sms_phone_fields.php');


?>