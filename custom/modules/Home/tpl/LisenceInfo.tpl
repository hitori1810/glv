<script type="text/javascript" src="custom/modules/Home/js/LisenceInfo.js"></script>
<link rel="stylesheet" type="text/css" href="custom/modules/Home/css/LisenceInfo.css">

<h1>CRM FOR EDU - {$VERSION}</h1>
<h3>{$MOD.LBL_EXPIRY_DATE}: {$EXPIRY_DATE}</h3>
<br>
<table class="tbl_info" cellspacing="0">
    <tbody>    
    <tr>    
        <th>
            
        </th>     
        <th class="td_2">
            {$MOD.LBL_LIMIT}
        </th>  
        <th class="td_2">
            {$MOD.LBL_USED}
        </th>   
        <th class="td_2">
            {$MOD.LBL_PERCENT}
        </th>   
    </tr>
    <tr>                                        
        <td class="td_1">{$MOD.LBL_LIMIT_USER}:
        </td>  
        <td class="td_2">{$LIMIT_USER}
        </td>
        <td class="td_2 {$CLASS_USED_USER}">{$USED_USER}
        </td>  
        <td class="td_2">{$PERCENT_LIMIT_USER}%
        </td>  
    </tr>
    <tr>                   
        <td class="td_1">{$MOD.LBL_LIMIT_CENTER}:
        </td>    
        <td class="td_2">{$LIMIT_CENTER}
        </td>
        <td class="td_2 {$CLASS_USED_CENTER}">{$USED_CENTER}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_CENTER}%
        </td>   
    </tr>
    <tr>                   
        <td class="td_1">{$MOD.LBL_LIMIT_LEAD}:
        </td>  
        <td class="td_2">{$LIMIT_LEAD}
        </td>
        <td class="td_2 {$CLASS_USED_LEAD}">{$USED_LEAD}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_LEAD}%
        </td>  
    </tr>
    <tr>
        <td class="td_1">{$MOD.LBL_LIMIT_STUDENT}:
        </td>   
        <td class="td_2">{$LIMIT_STUDENT}
        </td>
        <td class="td_2 {$CLASS_USED_STUDENT}">{$USED_STUDENT}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_STUDENT}%
        </td>  
    </tr>
    <tr>
        <td class="td_1">{$MOD.LBL_LIMIT_MAIL}:
        </td>   
        <td class="td_2">{$LIMIT_MAIL}
        </td>
        <td class="td_2 {$CLASS_USED_MAIL}">{$USED_MAIL}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_MAIL}%
        </td>  
    </tr>
    <tr>                   
        <td class="td_1">{$MOD.LBL_LIMIT_HARD_DISK}:
        </td>   
        <td class="td_2">{$LIMIT_DISK}
        </td>
        <td class="td_2 {$CLASS_USED_DISK}">{$USED_DISK}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_DISK}%
        </td>  
    </tr>
    <tr>                   
        <td class="td_1">{$MOD.LBL_LIMIT_DB_STORAGE}:
        </td>   
        <td class="td_2">{$LIMIT_DB}
        </td>
        <td class="td_2 {$CLASS_USED_DB}">{$USED_DB}
        </td> 
        <td class="td_2">{$PERCENT_LIMIT_DB}%
        </td>  
    </tr>
    </tbody>
</table>    
</br>
<div id="div_action" style="text-align:center;">
    <u><a href="index.php?">RETURN HOMEPAGE</a></u>
</div>

                