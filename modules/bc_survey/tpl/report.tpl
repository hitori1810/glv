{*
/**
* Create Status Report, Question Wise Report and Individual report Of Survey
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Original Author Biztech Co.
*/
*}
{assign var="active" value=""}


{if $type eq 'individual'}
    {assign var="ind_active" value="active"}
{/if}
{if $type eq 'status_open_ended'}
    {assign var="open_ended_status_active" value="active"}
    {assign var="status_active" value="active"}
{/if}
{if $type eq 'status_combined'}
    {assign var="status_active" value="active"}
    {assign var="combined_status_active" value="active"}
{/if}
{if $type eq 'status_email'}
    {assign var="status_active" value="active"}
    {assign var="email_status_active" value="active"}
{/if}
{if $type eq 'question_open_ended'}
    {assign var="open_ended_question_active" value="active"}
    {assign var="que_active" value="active"}
{/if}
{if $type eq 'question_email'}
    {assign var="que_active" value="active"}
    {assign var="email_question_active" value="active"}
{/if}
{if $type eq 'question_combined'}
    {assign var="que_active" value="active"}
    {assign var="combined_question_active" value="active"}
{/if}

{*Back To Survey Button*}
<link rel="stylesheet" type="text/css" href="custom/include/css/font-awesome/css/font-awesome.min.css"/>

<div class="survey-report-title"><div class="f-left"><label class="report_title">SURVEY REPORTS</label></div>

    <div class="f-right"> <a class="button back-to-survey" title="Back To Survey" id="analyse_survey" href="index.php?module=bc_survey&action=DetailView&record={$survey_id}">Back to Survey</a>
    </div>

</div>

{*Tab View*}

<div class="survey-form-body" style="min-height: 651px;">
    <div class="report_table_title">
        <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=status" id="status_report" class="report_title {$status_active}">Status Report</a>
        <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=question" class="report_title {$que_active}">Question Wise Report</a>
        <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=individual" class="report_title {$ind_active}">Individual Report</a>
    </div>
    <div class="report-tab-content">
        {if $type eq 'status' || $type eq 'status_open_ended' || $type eq 'status_email' || $type eq 'status_combined'}
            <div class="status_report_table">
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=status_combined" class="report_title {$combined_status_active}">Combined</a>
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=status_email" class="report_title {$email_status_active}">Email</a>
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=status_open_ended" id="status_report" class="report_title {$open_ended_status_active}">Open Ended</a>
              </div>
            <div class="report_header">{$survey_name} Status</div>
            {if is_array($survey_status)}
                {*Pie Chart For Status Report*}
                {literal}
                    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
                    <script type="text/javascript">
                        //for Pie chart
                        $.ajax({
                            url: 'https://www.google.com/jsapi?callback',
                            cache: true,
                            dataType: 'script',
                            success: function () {
                                google.load('visualization', '1', {packages: ['corechart'], 'callback': function () {
                                        var data = google.visualization.arrayToDataTable([
                                            ['Task', 'Survey Status'],
                                            ['Not viewed', {/literal}{if $survey_status.email_not_opened}{$survey_status.email_not_opened}{else}0{/if}{literal}],
                                                                    ['Viewed', {/literal}{if $survey_status.Pending}{$survey_status.Pending}{else}0{/if}{literal}],
                                                                                            ['Submitted', {/literal}{if $survey_status.Submitted}{$survey_status.Submitted}{else}0{/if}{literal}],
                                                                                                                ]);
                                                                                                                var options = {
                                                                                                                    title: '',
                                                                                                                    is3D: true,
                                                                                                                };
                                                                                                                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                                                                                                                chart.draw(data, options);
                                                                                                            }
                                                                                                        });

                                                                                                        //For linear chart ///////////////////////////////////////
                                                                                                        var start_date = '{/literal}{$survey_start_date}{literal}';
                                                                                                        var end_date = '{/literal}{$survey_end_date}{literal}';
                                                                                                        var lineChart_data = {/literal}{$line_chart}{literal};
                                                                                                        var max_count = {/literal}{$linechart_max_count}{literal};
                                                                                                        google.load('visualization', '1', {'packages': ['line'], 'callback': function () {
                                                                                                                var data = google.visualization.arrayToDataTable(lineChart_data);
                                                                                                                var options = {
                                                                                                                    title: '',
                                                                                                                     pointSize: 7,
                                                                                                                    legend: {position: 'bottom'},
                                                                                                                    hAxis: {viewWindowMode: "explicit", viewWindow: {min: start_date, max: end_date}},
                                                                                                                    vAxis: {format: '0', viewWindowMode: "explicit", viewWindow: {min: 0, max: max_count}},
                                                                                                                    is3D: true,
                                                                                                                };
                                                                                                                data.sort([{column: 0}]);
                                                                                                                var chart = new google.visualization.LineChart(document.getElementById('line_chart'));
                                                                                                                chart.draw(data, options);
                                                                                                            }
                                                                                                        });
                                                                                                    }
                                                                                                });

                    </script>
                {/literal}
                <div id="piechart_3d" style="width: 50%; height: 500px; float: left;"></div>
                <div id="line_chart" style="width: 50%; height: 500px; float: right;"></div>
            {else}
                <div id="question">  {$survey_status} </div>
            {/if}
        {/if}

        {if $type eq 'question_email' || $type eq 'question_open_ended' || $type eq 'question_combined' || $type eq 'question' }
            <div class="question_report_table">
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=question_combined" class="report_title {$combined_question_active}">Combined</a>
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=question_email" class="report_title {$email_question_active}">Email</a>
               <a href="index.php?module=bc_survey&action=viewreport&survey_id={$survey_id}&type=question_open_ended" id="status_report" class="report_title {$open_ended_question_active}">Open Ended</a>
              </div>
            <img src="themes/default/images/loading.gif" id="loading-image"  class="ajax-loader" style="display:none; left: 30%; top: 70%; position: absolute;"/>
            {*For Bar Chart*}
            {literal}
                <script>
                    // bar chart for multiple choice questions
                    var datadisplay = {/literal}{$data_display}{literal};// data of bar chart


                    $('#loading-image').show();
                    $(".survey-form-body").css("opacity", 0.4);
                    $.ajax({
                        url: 'https://www.gstatic.com/charts/loader.js',
                        cache: true,
                        dataType: 'script',
                        success: function () {

                            google.charts.load('current', {packages: ['corechart', 'bar'], 'callback': function ()
                                {
                                    if ({/literal}{$chart_id}{literal}) { // if chart id exists to generate bar chart
                                        $.each({/literal}{$chart_id}{literal}, function (key, value) {
                                            var chart_id = value;
                                            if (datadisplay[chart_id]) {
                                                var chart_data = datadisplay[chart_id]['chart_values'];
                                                var chart_title = datadisplay[chart_id]['chart_title'];
                                                if (chart_data != null) {
                                                    var rows = chart_data;
                                                    var data = google.visualization.arrayToDataTable(rows);
                                                    var options = {
                                                        width: 500,
                                                        height: 300,
                                                        legend: {position: 'none'},
                                                        title: chart_title,
                                                        bars: 'horizontal', // Required for Material Bar Charts.
                                                        axes: {
                                                            x: {
                                                                0: {side: 'top', label: 'Percentage'} // Top x-axis.
                                                            },
                                                            y: {
                                                                0: {label: 'Submitted Data'}
                                                            }
                                                        },
                                                        bar: {groupWidth: "50%"},
                                                        vAxis: {title:'Submitted Data'},
                                                        hAxis: {format: "#\'%\'", viewWindowMode: "explicit", viewWindow: {min: 0, max: 100},title:'Percentage'}
                                                    };
                                                    var chart = new google.visualization.BarChart(document.getElementById(chart_id));
                                                    chart.draw(data, options);
                                                }
                                            }
                                        });
                                    }
                                    //matrix chart
                                    if ({/literal}{$matrix_chart_ids}{literal}) {

                                        $.each({/literal}{$matrix_chart_ids}{literal}, function (key, value) {

                                            var chart_id = value;
                                            var chart_data = datadisplay[chart_id]['chart_values'];
                                            var chart_title = datadisplay[chart_id]['chart_title'];
                                            if (chart_data != null) {
                                                var rows = chart_data;
                                                var data = google.visualization.arrayToDataTable(rows);
                                                var options = {
                                                    title: chart_title,
                                                    isStacked: 'percent',
                                                    is3D: true,
                                                    height: 400,
                                                    bars: 'horizontal', // Required for Material Bar Charts.
                                                    legendTextStyle: {color: '#000'},
                                                    titleTextStyle: {color: '#000'},
                                                    colorAxis: {colors: ['#990033', '#330066']},
                                                    vAxis: {viewWindowMode: "explicit", viewWindow: {min: 0},title:'Rows'},
                                                    hAxis: {viewWindowMode: "explicit", viewWindow: {min: 0},title :'Percentage'},
                                                    // colors: ['#1b9e77', '#7570b3'],
                                                };
                                                var chart = new google.visualization.BarChart(document.getElementById(chart_id));
                                                chart.draw(data, options);
                                            }
                                        });
                                    }

                                    //scale type of question coulmn chart
                                    if ({/literal}{$scale_chart_ids}{literal}) {

                                        $.each({/literal}{$scale_chart_ids}{literal}, function (key, value) {
                                            var chart_id = value;
                                            var chart_data = datadisplay[chart_id]['chart_values'];
                                            var chart_title = datadisplay[chart_id]['chart_title'];
                                            if (chart_data != null) {
                                                var rows = chart_data;
                                                var data = google.visualization.arrayToDataTable(rows);
                                                var options = {
                                                    width: 500,
                                                    pointSize: 7,
                                                    legend: {position: 'none'},
                                                    title: chart_title,
                                                    bars: 'horizontal', // Required for Material Bar Charts.
                                                    axes: {
                                                        x: {
                                                            0: {side: 'top', label: 'Percentage'} // Top x-axis.
                                                        },
                                                        y: {
                                                            0: {label: 'Submitted Data'}
                                                        }
                                                    },
                                                    bar: {groupWidth: "90%"},
                                                    vAxis: {viewWindowMode: "explicit", viewWindow: {min: 0} , format: '0',title:'Submitted Data'},
                                                    hAxis: {viewWindowMode: "explicit", viewWindow: {min: 0} , format: '0',title:'Percentage'}
                                                };

                                                var chart = new google.visualization.LineChart(document.getElementById(chart_id));
                                                chart.draw(data, options);
                                            }
                                        });
                                    }
                                }
                            });
                        },
                        complete: function (jqXHR, textStatus) {
                            $('#loading-image').hide();
                            $(".survey-form-body").css("opacity", 1);
                        }
                    });
                    //matrix type of question coulmn chart with more options of rows & colmuns
                </script>
            {/literal}
            <div class="report_header">Question Summary Report for {$survey_name} <span>(Total Responses :   {$total_responses})</span></div>
            {if is_array($survey)}

                {foreach from=$survey.page item=queReportdata key=key}
                    {*Print Linear Chart *}
                    {if $key neq "page_title"}
                        {if $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'RadioButton' || $queReportdata.que_type eq 'Matrix' || $queReportdata.que_type eq 'Scale'}
                            {if $queReportdata.que_type eq 'Matrix'}
                                {if !$ans_skipp.$key}
                                    <label class="answer_skipp"><b>Answered Person : 0&nbsp;&nbsp;&nbsp; Skipped Person : {$total_responses}</b></label>
                                {else}
                                    <label class="answer_skipp"><b>Answered Person : {$ans_skipp.$key.answered}&nbsp;&nbsp;&nbsp; Skipped Person : {$ans_skipp.$key.skipped}</b></label>
                                {/if}
                                <div id="{$key}" style="margin-top: 30px;"></div>
                            {else}

                                <div class="report_answer_table">
                                    {if $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'RadioButton'}
                                        {if !$ans_skipp.$key}
                                            <label class="answer_skipp"><b>Answered Person : 0&nbsp;&nbsp;&nbsp; Skipped Person : {$total_responses}</b></label>
                                        {else}
                                            <label class="answer_skipp"><b>Answered Person : {$ans_skipp.$key.answered}&nbsp;&nbsp;&nbsp; Skipped Person : {$ans_skipp.$key.skipped}</b></label>
                                        {/if}
                                    {elseif $queReportdata.que_type eq 'ContactInformation' || $queReportdata.que_type eq 'Rating' ||$queReportdata.que_type eq 'Matrix' || $queReportdata.que_type eq 'Scale'}
                                        {if !$ans_skipp.$key}
                                            <label class="answer_skipp"><b>Answered Person : 0&nbsp;&nbsp;&nbsp; Skipped Person : {$total_responses}</b></label>
                                        {else}
                                            <label class="answer_skipp"><b>Answered Person : {$ans_skipp.$key.answered}&nbsp;&nbsp;&nbsp; Skipped Person : {$ans_skipp.$key.skipped}</b></label>
                                        {/if}
                                    {/if}

                                    <div id="{$key}" style="width: 50%; height: 300px; float: left;margin-top: 30px;"></div>
                                    <div style="width: 50%; float: right; margin-top: 70px;">
                                    {/if}
                                {/if}
                                {if $queReportdata.que_type eq 'ContactInformation' || $queReportdata.que_type eq 'Rating'}
                                    {if !$ans_skipp.$key}
                                        <label class="answer_skipp"><b>Answered Person : 0&nbsp;&nbsp;&nbsp; Skipped Person : {$total_responses}</b></label>
                                    {else}
                                        <label class="answer_skipp"><b>Answered Person : {$ans_skipp.$key.answered}&nbsp;&nbsp;&nbsp; Skipped Person : {$ans_skipp.$key.skipped}</b></label>
                                    {/if}
                                {/if}
                                 {if $queReportdata.que_type eq 'CommentTextbox' || $queReportdata.que_type eq 'Rating' || $queReportdata.que_type eq 'ContactInformation' || $queReportdata.que_type eq 'Textbox' || $queReportdata.que_type eq 'Matrix' || $queReportdata.que_type eq 'Date'}
                                <div class="report_question_table report_answer_table">
                                    {/if}
                                    {if $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'RadioButton' || $queReportdata.que_type eq 'Scale'}
                                    <table class="report_question_table multi_report_table">
                                        {else}
                                    <table class="report_question_table">
                                        {/if}
                                        {if $queReportdata.que_type neq "Image" && $queReportdata.que_type neq "Video" && $queReportdata.que_type neq "Matrix"}

                                            <tr class="question"><td colspan="6">{$queReportdata.name}</td></tr>
                                            {/if}

                                        {*Print textbox And CommentBox value *}
                                        {if $queReportdata.que_type eq 'ContactInformation' || $queReportdata.que_type eq 'Rating'}
                                            <tr class="thead">
                                                <th width="80%" colspan="3">Submitted Data</th>
                                            </tr>

                                        {elseif $queReportdata.que_type eq 'CommentTextbox' || $queReportdata.que_type eq 'Textbox' || $queReportdata.que_type eq 'Date'}
                                            {*Skipped Person And Submitted Person Value*}
                                            {assign var="submitted" value=0}
                                            {assign var="skipped" value=0}
                                            {foreach from=$queReportdata.answers item=answers}
                                                {if $answers.ans_name neq ''}
                                                {capture assign=submitted}{$submitted+1}{/capture}
                                            {/if}
                                        {/foreach}

                                    {capture assign="skipped"}{math equation="x - y" x=$total_responses y=$submitted}{/capture}
                                    {*                                {capture assign=skipped}{math $total_responses - $submitted}{/capture}*}
                                    <tr>
                                        <th width="80%" style="text-align: center !important;">Answered Person : {$submitted}&nbsp;&nbsp;&nbsp; Skipped Person : {$skipped}</th>
                                    </tr>
                                    <tr class="thead">
                                        <th width="80%">Submitted Data</th>
                                    </tr>

                                {elseif $queReportdata.que_type neq "Image" && $queReportdata.que_type neq "Video"}
                                    {if $queReportdata.que_type eq "Matrix"}

                                        <center><div id="matrix{$key}" name="matrix{$key}" style="margin-top:50px;  margin-bottom:50px;"></div></center>
                                        {else}
                                            {if $queReportdata.enable_scoring eq 1}
                                            <tr class="thead">
                                                <th width="80%" colspan="3">Submitted Data</th>
                                                <th width="10%">Weight</th>
                                                <th width="10%">Percentage</th>
                                                <th width="10%">Count</th>
                                            </tr>
                                        {else}
                                            <tr class="thead">
                                                <th width="80%" colspan="3">Submitted Data</th>
                                                <th width="10%">Percentage</th>
                                                <th width="10%">Count</th>
                                            </tr>
                                        {/if}
                                    {/if}
                                {/if}
                                <tr><td class="two-col-table">
                                        {assign var="rating_div" value=0}
                                        {if !$queReportdata.answers}
                                            {if $queReportdata.que_type eq 'Scale'}
                                        <tr>
                                            <td colspan="3">N/A</td>
                                            <td>N/A</td>
                                            <td>0</td>
                                        </tr>
                                    {/if}
                                {else}
                                    {foreach from=$queReportdata.answers item=answers key=ansid}
                                        {if $queReportdata.que_type eq 'ContactInformation'}
                                            <table>
                                                {if  $answers.ans_name|@count gt 0}
                                                    {foreach from=$answers.ans_name key=title item=answer_text}
                                                        {foreach from=$answer_text  item=sub_answer_text}
                                                            <tr  class="respond_con">
                                                                <th>{$title} :</th>
                                                                <td>{if $sub_answer_text}{$sub_answer_text}{else}N/A{/if}</td>
                                                            </tr>
                                                        {/foreach}
                                                    {/foreach}
                                                {/if}
                                            </table>

                                        {elseif $queReportdata.que_type eq 'CommentTextbox' || $queReportdata.que_type eq 'Textbox' || $queReportdata.que_type eq 'Date'}
                                            {foreach from=$answers.ans_name key=title item=answer_text}
                                                {if $answer_text neq ''}
                                                    <tr>
                                                        <td>
                                                            <p class="respond_con">{$answer_text}</p>
                                                        </td>
                                                    </tr>
                                                {/if}
                                            {/foreach}
                                        {elseif $queReportdata.que_type eq 'Rating'}
                                            {*For rating Display*}
                                            <tr>
                                                {if $rating_div eq 0}
                                                    <td colspan="3">
                                                        <div class="rating{$key}"></div>

                                                        {literal}
                                                            <script>
                                                                var rating_final_count = new Array();
                                                                rating_final_count = {/literal}{$rating_count}{literal};

                                                                if ({/literal}{$queReportdata.max_size}{literal} != null) {
                                                                    var starCount = {/literal}{$queReportdata.max_size}{literal};
                                                                } else {
                                                                    var starCount = 5;
                                                                }
                                                                var star = new Array();
                                                                for (var counter = starCount; counter >= 0; counter--)
                                                                {
                                                                    var stars = '';
                                                                    for (var star_loop = 0; star_loop < counter; star_loop++) {
                                                                        stars += '<i class="fa fa-star fa-2x" style="font-size:18px;color:#F4B30A; display: inline !important; margin-right:3px;">&nbsp;</i>';
                                                                    }
                                                                    if (stars == null || stars == '') {
                                                                        stars = '<i class="fa fa-star fa-2x" style="font-size:18px; display: inline !important; margin-right:3px;">&nbsp; </i>';
                                                                    }

                                                                    star[counter] = stars;
                                                                }
                                                                var question_report_html = '';
                                                                for (var counter = starCount; counter >= 0; counter--)
                                                                {
                                                                    question_report_html += '     <div class= "rating-block"><div class = "rating" style="width:16%"> ' + star[counter] + '</div>  <div style="width: 750px;margin-top: -22px; margin-left: 50px;" id="progressbar-' + counter + '_{/literal}{$key}{literal}" class="rating-bar"></div><div style="margin-left: 10px; margin-top: -30px;" class="rating-count">' + rating_final_count['{/literal}{$key}{literal}'][counter] + '</div></div>';
                                                                }
                                                                $(".rating{/literal}{$key}{literal}").html(question_report_html);
                                                            </script>
                                                        {/literal}
                                                        {$rating.$key}
                                                    {/if}
                                                    {assign var="rating_div" value=$rating_div+1}
                                            </tr>
                                        {else}
                                            {if $queReportdata.que_type neq 'Matrix'}
                                                {*{$queReportdata.matrix_row|@count}*}
                                                {if $queReportdata.enable_scoring eq 1}
                                                    <tr>
                                                        <td colspan="3">{$answers.ans_name}</td>
                                                        <td>{$answers.weight}</td>
                                                        <td>{if $answers.percent}{$answers.percent}%{else}N/A{/if}</td>
                                                        <td>{$answers.sub_ans}</td>
                                                    </tr>
                                                {else}
                                                    <tr>
                                                        <td colspan="3">{$answers.ans_name}</td>
                                                        <td>{if $answers.percent}{$answers.percent}%{else}N/A{/if}</td>
                                                        <td>{$answers.sub_ans}</td>
                                                    </tr>
                                                {/if}
                                            {/if}
                                        {/if}
                                    {/foreach}
                                {/if}
                                {if $queReportdata.que_type eq 'Matrix'}
                                    {literal}
                                        <script>
                                        var data = {/literal}{$displaymatrix}{literal};
                                        var rowcount = 0;
                                        var colcount = 0;
                                        var html = '';
                                        $.each(data, function (page_id, page_data) {
                                            $.each(page_data, function (que_id, que_title) {
                                                if (que_id != "page_title") {
                                                    var question_report_html = '';
                                                    question_report_html += "<div class='middle-content'><table>";

                                                    var rows = que_title.matrix_row;
                                                    var cols = que_title.matrix_col;
                                                    //count number of rows & columns
                                                    try {
                                                        var row_count = Object.keys(rows).length + 1;
                                                        var col_count = Object.keys(cols).length;
                                                    } catch (e) {
                                                    }
                                                    // adjusting td width as per column
                                                    var width = Math.round(70 / (col_count + 1)) - 1;
                                                    for (var i = 1; i <= row_count; i++) {
                                                        question_report_html += '<tr>';
                                                        for (var j = 1; j <= col_count + 1; j++) {
                                                            //First row & first column as blank
                                                            if (j == 1 && i == 1) {
                                                                question_report_html += "<td class='matrix-span' style='width:" + width + "%;text-align:left;border: 1px solid #D4CECE; padding:10px; margin:0px;'>&nbsp;</td>";
                                                            }
                                                            // Rows Label
                                                            if (j == 1 && i != 1) {
                                                                question_report_html += "<td class='matrix-span' style='font-weight:bold; width:" + width + "%;;text-align:left;border: 1px solid #D4CECE;padding:10px; margin:0px;'>" + rows[i - 1] + "</td>";
                                                            } else {
                                                                //Columns label
                                                                if (j <= col_count + 1 && cols[j - 1] != null && !(j == 1 && i == 1) && (i == 1 || j == 1))
                                                                {
                                                                    question_report_html += "<td class='matrix-span' style='font-weight:bold; width:" + width + "%;border: 1px solid #D4CECE;padding:10px; margin:0px;'>" + cols[j - 1] + "</td>";

                                                                }
                                                                //Display answer input (RadioButton or Checkbox)
                                                                else if (j != 1 && i != 1 && cols[j - 1] != null) {
                                                                    var row = i - 1;
                                                                    var col = j - 1;
                                                                    question_report_html += "<td class='matrix-span' style='width:" + width + "%;border: 1px solid #D4CECE;padding:10px; margin:0px; '  id='" + row + "_" + col + "' name='matrix" + row + "'>0&nbsp;(0%)</td>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                    question_report_html += "</tr></table>";
                                                    question_report_html += '</div>';
                                                }

                                                $("#matrix" + que_id).html(question_report_html);

                                                $.each({/literal}{$matrix_data}{literal}, function (queid, values) {
                                                    if (queid != 'page_title') {
                                                        $.each(values, function (row, row_values) {
                                                            $.each(row_values, function (col, value) {
                                                                $('#matrix' + queid).find("#" + row + "_" + col + "").html(value);
                                                            });
                                                        });
                                                    }
                                                });
                                            });
                                        });</script>
                                        {/literal}
                                    {/if}
                            </table>
                            {if $queReportdata.que_type eq 'CommentTextbox' || $queReportdata.que_type eq 'Rating' || $queReportdata.que_type eq 'ContactInformation' || $queReportdata.que_type eq 'Textbox' || $queReportdata.que_type eq 'Matrix' || $queReportdata.que_type eq 'Scale'}
                        </div>
                        {/if}
                        {if $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'RadioButton' || $queReportdata.que_type eq 'Matrix' || $queReportdata.que_type eq 'Scale'}

                        </div>
                    {/if}
                    {if $queReportdata.enable_scoring eq 1}
                        {if $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'RadioButton'}
                        {if $queReportdata.is_nps}
                        {capture assign=nps}{math equation="((promoters - detractors) / total) * 100" promoters=$queReportdata.promoters detractors=$queReportdata.detractors total=$total_responses}{/capture}
                        {capture assign=nps_percent}{math equation="(nps / 2)+50" nps=$nps}{/capture}

                        <table style="width: 37%;" class="report_question_table multi_report_table">
                        <tbody>
                        <tr>
                        <td width="40%"><b> Net Promoter Score:</b></td>
                        <td width="30%"><b style="color: green;font-size: 30px;">{if $nps > 0} +{/if}{$nps|string_format:"%.0f"}</b></td>
                        <td width="30%" rowspan="4"><div id="nps_gauge_{$queReportdata.question_id}"></div></td>
                        </tr>

                        <tr>
                        <td>Detractors: </td>
                        <td>{$queReportdata.detractors} ({$queReportdata.detractors/$total_responses*100|string_format:"%.0f"}%)</td>
                        </tr>

                        <tr>
                        <td>Passive: </td>
                        <td>{$queReportdata.passive} ({$queReportdata.passive/$total_responses*100|string_format:"%.0f"}%)</td>
                        </tr>

                        <tr>
                        <td>Promoters: </td>
                        <td>{$queReportdata.promoters} ({$queReportdata.promoters/$total_responses*100|string_format:"%.0f"}%)</td>
                        </tr>

                        </tbody>
                        </table>

                                    {literal}
                <script>
                    $('#loading-image').show();
                    $(".survey-form-body").css("opacity", 0.4);
                    $.ajax({
                        url: 'https://www.gstatic.com/charts/loader.js',
                        cache: true,
                        dataType: 'script',
                        success: function () {
                                google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawNPS);
      function drawNPS() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['% NPS', {/literal}{$nps_percent|string_format:"%.0f"}{literal}],
        ]);

        var options = {
          width: 400, height: 120,
          redFrom: 0, redTo: 50,
          yellowFrom: 50, yellowTo: 80,
          greenFrom:80, greenTo: 100,
          minorTicks: 10
        };
        var chart = new google.visualization.Gauge(document.getElementById('nps_gauge_{/literal}{$queReportdata.question_id}{literal}'));
        chart.draw(data, options);
      }
                        },
                        complete: function (jqXHR, textStatus) {
                            $('#loading-image').hide();
                            $(".survey-form-body").css("opacity", 1);
                        }
                    });
                    //matrix type of question coulmn chart with more options of rows & colmuns
                </script>
            {/literal}

                        {else}
                        {capture assign=average}{math equation="avg / total" avg=$queReportdata.average total=$total_responses}{/capture}
                        <b class="btm-answer-label"><label style='font-size: 14px;'> Average : <font color='green'>{$average|string_format:"%.2f"}</font> Out of {$queReportdata.sum_score}</label></b>
                        {/if}
                    {/if}
                {/if}
                {if $queReportdata.que_type eq 'DrodownList' || $queReportdata.que_type eq 'MultiSelectList' || $queReportdata.que_type eq 'Checkbox' || $queReportdata.que_type eq 'RadioButton'}
                </div>
            {/if}
        {/if}
    {/foreach}
    {$Que_pageNumbers}
{else}
    <div id="question">There is no submission for this Survey.</div>
{/if}
{/if}
{*Individual Report*}
{if $type eq 'individual'}
    <div class="report_header">Individual Report for {$survey_name}</div>
    {if $name == ''}
        <div id="question">There is no submission for this Survey.</div>
    {else}
        <div>
            <div class="search-block">
                <b>Name </b><input type="text" name="name" id="name"  onKeydown="Javascript: if (event.keyCode == 13)
                            getSearchResult(this, '{$type}', '{$survey_id}',{$page}, 'search');" placeholder=" Search By Name">
                &nbsp;
                <b>Module </b><select name="module_names" id="module_names" onKeydown="Javascript: if (event.keyCode == 13)
                            getSearchResult(this, '{$type}', '{$survey_id}',{$page}, 'search');">
                    <option value=''>All</option>
                    <option value="Accounts">Accounts</option>
                    <option value="Contacts">Contacts</option>
                    <option value="Leads">Leads</option>
                    <option value="Prospects">Targets</option>
                </select>
                &nbsp;
                <b>Type </b><select name="type" id="type" onKeydown="Javascript: if (event.keyCode == 13)
                            getSearchResult(this, '{$type}', '{$survey_id}',{$page}, 'search');">
                    <option value="Combined">Combined</option>
                    <option value="Email">Email</option>
                    <option value="Open Ended">Open Ended</option>
                </select>
            </div>
            <div class="search-block">
                <input type="button" name="Search" id="Search" value="SEARCH" onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search');">
                <input type="button" name="Clear" id="Clear" value="CLEAR" onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'clear');">
                <input type="button" name="Export" id="Export" value="Export Result" onclick="ExportData('{$type}', '{$survey_id}', 1, 'clear');">
            </div>
            <div id="validate_search"></div>
            <div class="select-que">
                <table class="individual_report_table" id="search_result">
                    <tr class="thead">
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="name_label" style="cursor: pointer;">Name&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="module_label" style="cursor: pointer;">Module&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="type_label" style="cursor: pointer;">Type&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="send_date_label" style="cursor: pointer;">Survey Send Date&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="recieve_date_label" style="cursor: pointer;">Survey Receive Date&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th><label onclick="getSearchResult(this, '{$type}', '{$survey_id}', 1, 'search')" class="Ascending" id="change_request_label" style="cursor: pointer;">Change Request&nbsp;&nbsp;<i class="fa fa-sort" aria-hidden="true"></i></label></th>
                        <th style="width:80px;">Resend</th>
                        <th style="width:80px;">Delete</th>
                    </tr>
                    {foreach from=$name key=module_id  item=module_detail}
                        <tr id="{$module_detail.submission_id}">
                            {if $module_detail.module_id eq ''}
                                <td><a href="javascript:void(0);" onclick="getReports('{$survey_id}',{$page}, '', '{$module_detail.submission_id}', '{$module_detail.name}');">{$module_detail.name}</a>
                            </td>

                            {else}
                            <td><a href="javascript:void(0);" onclick="getReports('{$survey_id}',{$page}, '{$module_detail.module_id}', '{$module_detail.submission_id}', '{$module_detail.name}');">{$module_detail.name}</a>
                            </td>

                            {/if}
                            <td>{$module_detail.module_name}</td>
                            <td>{$module_detail.type}</td>
                            <td>{$module_detail.send_date}</td>
                            <td>{$module_detail.receive_date}</td>
                            <td id="request_status">
                                {if $module_detail.change_request eq 'N/A' || $module_detail.change_request eq 'Approved'}
                                    {$module_detail.change_request}
                                {else}
                                    {if $module_detail.type eq 'Email'}
                                    <a href="javascript:void(0);" onclick="ApproveChRequest(this, '{$survey_id}', '{$module_detail.module_id}', '{$module_detail.module_name}');">{$module_detail.change_request}</a>
                                    {else}
                                        <a href="javascript:void(0);" onclick="ApproveChRequest(this, '{$survey_id}', '{$module_detail.module_id}', '{$module_detail.type}');">{$module_detail.change_request}</a>
                                {/if}
                                {/if}
                            </td>
                            <td id="re-send">
                                {if ($module_detail.survey_status eq 'Submitted' || $module_detail.survey_status eq 'Viewed') && ($module_detail.type eq 'Email')}
                                    <a title="Re-send" onclick="reSendSurvey(this, '{$survey_id}', '{$module_detail.module_id}', '{$module_detail.module_name}');" href="javascript:void(0);"><img src="custom/include/images/re-send.png" style="height: 22px;"></a>
                                    {/if}
                            </td>
                            <td style="text-align: center;">
                                <a title="Delete Response" onclick="deleteSubmission(this, '{$survey_id}', '{$module_detail.submission_id}', '{$module_detail.module_id}','{$module_detail.type}','{$module_detail.name}');" href="javascript:void(0);"><img src="custom/include/images/trash.png" style="height: 22px;"></a>
                            </td>
                        </tr>
                    {/foreach}
                </table>
            </div>
        </div>
    {/if}
    {$Individual_pageNumbers}
{/if}
{literal}
    <script type="text/javascript">

        function close_survey_div() {
            $('#backgroundpopup').fadeOut(function () {
                $('#backgroundpopup').remove();
            });
            $('#indivisual_report_main_div').fadeOut(function () {
                $('#indivisual_report_main_div').remove();
            });
        }
        function getReports(survey_id, page, module_id, submission_id, name) {

            // var module_id = $('#'+survey_id+"_"+page).val();
            $('<input>').attr({
                type: 'hidden',
                id: 'selectedRecord',
                name: 'selectedRecord'
            }).appendTo('head');
            $("#selectedRecord").val(module_id);
            $.ajax({
                url: "index.php",
                type: "POST",
                data: {'module': 'bc_survey', 'action': 'getReports','customer_name': name, survey_id: survey_id, module_id: module_id, page: page, submission_id: submission_id},
                success: function (result) {
                    $('body').append('<div id="backgroundpopup">&nbsp;</div>');
                    if ($("#indivisual_report_main_div").length == 0) {
                        $('body').append('<div id="indivisual_report_main_div"> <a onclick="close_survey_div();" href="javascript:void(0);" class="close_link"></a></div>');
                    }
                    $('#backgroundpopup').fadeIn();
                    $('#indivisual_report_main_div').fadeIn();
                    $('#indivisual_report_main_div').html('<div id="indivisual_report">'
                            + result +
                            ' <a onclick="close_survey_div();" href="javascript:void(0);" class="close_link"></a>' +
                            '</div>');
                }
            });
        }
        function getSearchResult(el, report_type, survey_id, page, button_clicked) {
            if (button_clicked == 'clear') {
                $("#name").val('');
                $("#module_names").val('');
                $("#survey_status").val('');
                $("#type").val('Combined');
            }
            var name_value = trim($("#name").val());
            var name = (name_value) ? name_value : '';
            var type = ($("#module_names").val()) ? $("#module_names").val() : '';
            var status = ($("#survey_status").val()) ? $("#survey_status").val() : '';
            var survey_type = ($("#type").val()) ? $("#type").val() : '';
            var sort_type = ($(el).attr('class')) ? $(el).attr('class') : '';
            var sort_name = ($(el).attr('id')) ? $(el).attr('id') : '';
            var dataArray = {'report_type': report_type,
                'survey_id': survey_id,
                'name': name,
                'search_value': name,
                'module_type': type,
                'survey_type': survey_type,
                'survey_status': status,
                'sorting_type': sort_type,
                'sorting_name': sort_name,
                'page': page};
            var Data = JSON.stringify(dataArray);
            $.ajax({
                url: "index.php?module=bc_survey&action=getSearchResult",
                type: "POST",
                data: {newData: Data},
                success: function (result) {
                    var html_data = result.split('||');
                    $("#search_result").html(html_data[0]);
                    $('.numbers').html(html_data[1]);
                    if (sort_type == "Ascending") {
                        $('#' + sort_name).removeClass("Ascending");
                        $('#' + sort_name).addClass("Descending");
                    }else{
                        $('#' + sort_name).addClass("Ascending");
                        $('#' + sort_name).removeClass("Descending");
                }
                }
            });
        }
        function ExportData(report_type, survey_id, page) {
            var name_value = $("#name").val().replace(/([;&,\.\+\*\~':"\!\^#$%@?/{}\\[\]\(\)=>\|])/g, '');
            var name = (name_value) ? name_value : '';
            var type = ($("#module_names").val()) ? $("#module_names").val() : '';
            var status = ($("#survey_status").val()) ? $("#survey_status").val() : '';
            var survey_type = ($("#type").val()) ? $("#type").val() : '';
            window.location.assign("index.php?module=bc_survey&action=exportToExcel&report_type=" + report_type + "&survey_id=" + survey_id + "&module_name=" + name + "&module_type=" + type + "&survey_status=" + status + "&page=" + page + "&survey_type=" + survey_type);
        }
        function ApproveChRequest(element, survey_id, module_id, module_type) {
            var el_td = $(element).parent('td');
            var status = $(element).text();
            var parent = $(element).parent('td');
            var dropDown = '<select id="status" style="width:100px;"><option value="N/A">Select</option><option value="Approved">Approved</option></select>';
            var dropDownFn = $(dropDown).change(function () {
                $.ajax({
                    url: "index.php",
                    type: "POST",
                    data: {module: 'bc_survey', action: 'approveRequest', survey_id: survey_id, module_name: module_type, module_id: module_id, status: $(this).val()},
                    beforeSend: function () {
                        $(el_td).append("<img style='color:red;padding-left: 10px;vertical-align: middle;' id='survey_loader' src= " + SUGAR.themes.loading_image + ">");
                    },
                    complete: function () {
                        $("#survey_loader").remove();
                    }, success: function (result) {
                        var response = JSON.parse(result);
                        if (response['status'] == "sucess") {
                            parent.html(response['request_status']);
                            alert('Email has sent successfully.');
                        } else {
                            alert('It seems there is some error!');
                        }
                    }
                });
            });
            parent.html(dropDownFn);
        }
        function reSendSurvey(el, survey_id, module_id, module_type) {
            $.ajax({
                url: "index.php",
                type: "POST",
                data: {module: 'bc_survey', action: 'approveRequest', survey_id: survey_id, module_name: module_type, module_id: module_id, resubmit: 1},
                beforeSend: function () {
                    if ($("#survey_loader").length == 0) {
                        $(el).parent('td').append("<img style='color:red;padding-left: 10px;vertical-align: middle;padding-bottom: 5px;' id='survey_loader' src= " + SUGAR.themes.loading_image + ">");
                    }
                },
                complete: function () {
                    $("#survey_loader").remove();
                }, success: function (result) {
                    var response = JSON.parse(result);
                    if (response['status'] == "sucess") {
                        $("#survey_loader").remove();
                        alert('Email for resubmission survey has sent successfully.');
                    } else {
                        alert('It seems there is some error!');
                    }
                }
            });
        }
        function deleteSubmission(el, survey_id, submission_id, module_id,module_type,customer_name) {
            if (confirm("Are You sure to Remove This Submission ?")) {
            $.ajax({
               url: "index.php",
                type: "POST",
                    data: {
                        module: 'bc_survey',
                        action: 'deleteSubmissionFromIndividual',
                        survey_id: survey_id,
                        submission_id: submission_id,
                        module_id: module_id,
                        module_type:module_type,
                        customer_name:customer_name
                },
                success: function (result) {
                        $('#' + submission_id).remove();
                }
            });
        }
        }
    </script>
{/literal}
</div>    </div>
