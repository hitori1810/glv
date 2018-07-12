<?php
$q1 = "SELECT DISTINCT Ifnull(j_ptresult.id, '') primaryid,
Ifnull(j_ptresult.parent, '') parent,
Ifnull(l2.lead_source, '') l2_lead_source,
Ifnull(l1.lead_source, '') l1_lead_source
FROM   j_ptresult
LEFT JOIN leads_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.leads_j_ptresult_1j_ptresult_idb AND l1_1.deleted = 0
LEFT JOIN leads l1 ON l1.id = l1_1.leads_j_ptresult_1leads_ida AND l1.deleted = 0
LEFT JOIN contacts_j_ptresult_1_c l2_1 ON j_ptresult.id = l2_1.contacts_j_ptresult_1j_ptresult_idb AND l2_1.deleted = 0
LEFT JOIN contacts l2 ON l2.id = l2_1.contacts_j_ptresult_1contacts_ida AND l2.deleted = 0
WHERE j_ptresult.deleted = 0";
$rs1 = $GLOBALS['db']->query($q1);
$count = 0;
while($row1 = $GLOBALS['db']->fetchByAssoc($rs1)){
    $source = '';
    if($row1['parent'] == 'Leads')
        $source = $row1['l1_lead_source'];
    else $source = $row1['l2_lead_source'];
    if(!empty($source))
        $q2 = "UPDATE j_ptresult SET lead_source='$source' WHERE id='{$row1['primaryid']}'";
    $GLOBALS['db']->query($q2);
    $count++;
}
echo "<b>Updated $count</b>";
?>