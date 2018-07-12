{sugar_getscript file="custom/include/javascripts/myDatePicker.js"}
<script type="text/javascript" src="custom/modules/Users/js/create_demo_user.js"></script>
<link rel="stylesheet" type="text/css" href="custom/modules/Users/css/create_demo_user.css">                             

<h1 class="colcenter">{$MOD.TITLE_CREATE_DEMO_USER}</h1>

<table class="tbl_info" cellspacing="0">
    <thead>
        <tr>
            <th colspan="3">{$MOD.LBL_ACCOUNT_INFO}</th>
            <th style="text-align: right;">
                <button type="button" class="button primary" onclick="createUser()">{$MOD.LBL_CREATE_USER}</button>
            </th>
        </tr>
    </thead>
    <tbody>   
        <tr>
            <td class="td_1"><label>{$MOD.LBL_ACCOUNT_NAME}:</label></td>
            <td class="td_2">
                <input type="text" id="account_name" name="account_name" class="account_name" value=""/>
                    
            </td>  
            <td>
                &nbsp;{$MOD.LBL_ACCOUNT_NAME_DESCRIPTION}
            </td>
        </tr>
        <tr>  
            <td class="td_1"><label>{$MOD.LBL_EXPIRY_DATE}:</label></td>
            <td class="td_2">
                <input type="text" id="expiry_date" name="expiry_date" class="expiry_date date_picker" value="{$DEFAULT_EXPIRY_DATE}"/>
                    
            </td> 
            <td>
                &nbsp;{$MOD.LBL_EXPIRY_DATE_DESCRIPTION}
            </td>
        </tr> 
    </tbody>
</table>
<br/>

<table class="tbl_info" id="tbl_main" cellspacing="0">
    <thead>
        <tr>
            <th colspan="3">{$MOD.LBL_USER_LIST}</th>
            <th style="text-align: right;">
                <button type="button" class="btnGegerateUsername" onclick="generateUsername()">{$MOD.LBL_GENERATE_USERNAME}</button>
                <button type="button primary" class="btnAdd" onclick="addRowUser()">{$MOD.LBL_ADD_USER}</button>
            </th>
        </tr>
    </thead>        
</table>
<br>
<div class="div_user_list">
     <table class="tbl_user_list">
         <thead>
             <th>{$MOD.LBL_USER_EMAIL}</th>
             <th>{$MOD.LBL_FIRST_NAME}</th>
             <th>{$MOD.LBL_LAST_NAME}</th>
             <th>{$MOD.LBL_USER_NAME}</th>
             <th>{$MOD.LBL_PASSWORD}</th>
             <th>{$MOD.LBL_ROLE}</th>
         </thead>
         <tbody>
         </tbody>
         <tfoot style="display: none;">
            <tr>
                <td>    
                    <input type="text" name="user_company_email[]" class="user_company_email" onchange="handleUpdateEmail($(this));"/> 
                </td>
                <td>                          
                    <input type="text" name="user_first_name[]" class="user_first_name"/> 
                </td>
                <td>                          
                    <input type="text" name="user_last_name[]" class="user_last_name"/> 
                </td>
                <td>                          
                    <input type="text" name="user_account[]" class="user_account"/> 
                </td>
                <td>    
                    {$MOD.LBL_AUTO_GENERATED}
                </td>
                <td>   
                    <select name="user_role[]" class="user_role">
                        {$ROLE_OPTIONS}
                    </select>
                </td>
                <td class="td_2">
                    <button type="button" class="btnDel btn-danger" onclick="delRow($(this))">{$MOD.LBL_DELETE_ROW}</button>
                </td>
            </tr>
         </tfoot>
     </table>
 </div>