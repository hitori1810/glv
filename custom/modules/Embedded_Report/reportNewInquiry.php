<?php
    include("custom/modules/Embedded_Report/_helper.php");
    global $timedate, $current_user;
    $filter = $this->where;
    $parts = explode("AND", $filter);

    $start = get_string_between($parts[0],"'","'");
    $end = get_string_between($parts[1],"'","'");
    $team_id = get_string_between($parts[2],"'","'");
 //   $team_short_name = get_string_between($parts[3],"'","'");

    $sql_team = '';
    if(isset($team_id) && !empty($team_id)){
        $sql_team = "AND (l1.id = '$team_id')";
    } /*else if(isset($team_short_name) && !empty($team_short_name)){
        $team_id = $GLOBALS['db']->getOne("SELECT id FROM teams WHERE short_name = '{$team_short_name}'");
        $sql_team = "AND (l1.id = '$team_id')";
    }*/
    $full_list = getListEnquiries($start, $end, $sql_team );
    $html  = '<table class="reportlistView" border="0" cellpadding="0" cellspacing="0"><thead>
    <tr>
    <th class="reportlistViewThS1">No.</th>
    <th class="reportlistViewThS1">Student ID</th>
    <th class="reportlistViewThS1">Student Name</th>
    <th class="reportlistViewThS1">Inquiry Date</th>
    <th class="reportlistViewThS1">Age</th>
    <th class="reportlistViewThS1">Email</th>
    <th class="reportlistViewThS1">Adress</th>
    <th class="reportlistViewThS1">Contact</th>
    <th class="reportlistViewThS1">Phone</th>
    <th class="reportlistViewThS1">Kind Of Course</th>
    <th class="reportlistViewThS1">Source</th>
    <th class="reportlistViewThS1">Campaign</th>'.
    ($current_user->team_type == 'Junior') ? '<th class="reportlistViewThS1">First EC</th>' : '<th class="reportlistViewThS1">First SM</th>'.'
    <th class="reportlistViewThS1">Inquiry Type</th>
    <th class="reportlistViewThS1">Inquiry Status</th>
    <th class="reportlistViewThS1">Payment Status</th>
    <th class="reportlistViewThS1">Center</th>
    </tr>
    </thead><tbody>
    ';


    $no = 1;
    foreach ($full_list as $key => $value){
        $html .= '<tr class="Array">';
        $html .= '<td class="oddListRowS1" bgcolor>'.$no.'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['contact_id'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['full_name'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$timedate->to_display_date($value['date_entered'],false).'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['age'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['email_address'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['primary_address_street'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['guardian_name'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['phone_mobile'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['prefer_koc'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['lead_source'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['campaign_name'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['full_name_EC'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['inquiry_type'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['status'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['payment_status'].'</td>';
        $html .= '<td class="oddListRowS1" bgcolor>'.$value['short_name'].'</td>';
        $html .= '</tr>';
        $no++;
    }
    $html .= '</tbody></table>';
    echo $html;

