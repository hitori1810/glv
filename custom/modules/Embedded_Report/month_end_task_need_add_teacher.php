<?php
$this->$query_name = str_replace('sum(IFNULL(meetings.duration_cal,0)) meetings_sum_duration_cal','(IFNULL(meetings.duration_cal,0)) meetings_sum_duration_cal',$this->$query_name);
?>
