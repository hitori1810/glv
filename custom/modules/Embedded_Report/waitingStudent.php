<?php
    global $timedate;
    //    echo $this->query;
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");
    $team = get_string_between($parts[2],"'","'");
    $kind_of_course = get_string_between($parts[3],"'","'");
    $level = get_string_between($parts[4],"'","'");
