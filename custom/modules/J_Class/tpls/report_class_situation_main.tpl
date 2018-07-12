<style>
{literal}
.reportDataChildtablelistView th {
    padding-right: 5px;
    padding-left: 5px;
}
{/literal}
</style>

<table width="100%" border="0" cellpadding="0" cellspacing="1" class="reportDataChildtablelistView">
    <tbody>
        <tr height="20">
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_NO}
            </th>                           
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_KIND_OF_COURSE}
            </th>                           
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_NAME}
            </th>                           
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_CLASS_CODE}
            </th>                           
            <!--<th scope="col" nowrap="" style="text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_DAY}
            </th>     -->                      
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_STUDY_TIME}
            </th>                           
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_START_DATE_REPORT}
            </th>                           
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_END_DATE_REPORT}
            </th>                   
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_LESSON_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_COMPLETED_HOURS}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" colspan="6">    
                {$MOD.LBL_NO_STUDENTS}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" rowspan="3">    
                {$MOD.LBL_TEACHER_REPORT}
            </th> 
        </tr>
        <tr>
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" colspan="3">    
                {$MOD.LBL_LAST_WEEK}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;" colspan="3">    
                {$MOD.LBL_THIS_PERIOD}
            </th> 
        </tr>
        <tr>
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_PAID_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_SPONSOR_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_TOTAL_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_PAID_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_SPONSOR_REPORT}
            </th> 
            <th scope="col" nowrap="" style="background: #CCF;text-align: center; vertical-align: middle;">    
                {$MOD.LBL_TOTAL_REPORT}
            </th> 
        </tr>
        {$REPORT_DATA}
    </tbody>
</table>
<br>
{$SUMMATION_TABLE}
{sugar_getscript file="custom/modules/J_Class/js/ClassSituationsReportHandler.js"}