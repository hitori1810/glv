<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


/**
 * This file is here to provide a HTML template for the rest help api.
 */
?>
<!DOCTYPE HTML>
<html>
<head>
<title>SugarCRM Auto Generated API Help</title>
<style type="text/css">
    table#endpointList {
        width: 100%;
        border-collapse: collapse;
    }
    .hidden {
        display: none;
    }
    .endpointMain {
        background: #eeeeee;
    }
    code {
        white-space: pre;
        height: 150px;
        overflow-x: scroll;
        display: inline-block;
        background: #eeeeff;
    }
    .params table, .params table td, .params table th {
        border: 1px solid #000000;
        border-collapse: collapse;
    }
    .params table {
        empty-cells: show;
    }
    .params table th {
        background-color: #efefef;
    }
    .params table th, .params table td {
        padding: 2px 4px;
    }
    .codesample .note {
        background: #ffffff;
        font-style: italic;
    }
    .showHide {
        cursor: pointer;
        padding: 0 3px;
        text-align: center;
        width: 20px;
    }

</style>
<script type="text/javascript" src="<?php echo SugarConfig::get('site_url') ?>/include/javascript/jquery/jquery.js"></script>
</head>

<body>
<h1>SugarCRM API</h1>

<table id="endpointList" border="1" cellspacing="0" cellpadding="2">
<?php
  foreach ( $endpointList as $i => $endpoint ) {
      if ( empty($endpoint['shortHelp']) ) { continue; }
?>
  <tr id="endpoint_<?php echo $i ?>" class="endpointMain">
    <td class="showHide" id="showHide<?php echo $i ?>">+</td>
    <td class="reqType"><?php echo htmlspecialchars($endpoint['reqType']) ?></td>
    <td class="fullPath"><?php echo htmlspecialchars($endpoint['fullPath']) ?></td>
    <td class="shortHelp"><?php echo htmlspecialchars($endpoint['shortHelp']) ?></td>
    <td class="score"><?php echo sprintf("%.02f",$endpoint['score']) ?></td>
  </tr>
  <tr id="endpoint_<?php echo $i ?>_full" class="endpointExtra hidden">
    <td class="empty">&nbsp;</td>
    <td class="fullHelp" colspan="4">
      <?php
      if ( file_exists($endpoint['longHelp']) ) {
          echo file_get_contents($endpoint['longHelp']);
      } else if ( !empty($endpoint['longHelp']) ) {
          echo 'Long help file not found: ' . htmlspecialchars($endpoint['longHelp']);
      } else {
          echo 'No additional help.';
      }
      ?>
      <hr>
      <b>File:</b><?php echo $endpoint['file']; ?><br>
      <b>Method:</b><?php echo $endpoint['method']; ?><br>
    </td>
  </tr>
<?php
  }
?>
</table>
<script type="text/javascript">
    $(function() {
        $('.showHide').click(function() {
            var id = $(this).attr('id').replace('showHide', '');
            var currentSign = $(this).text();
            var newSign = currentSign == '+' ? '-' : '+';
            $('#endpoint_' + id + '_full').toggle();
            $(this).text(newSign);
        });
    });
</script>
</body> </html>
