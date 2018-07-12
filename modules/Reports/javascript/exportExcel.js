/**
* Lap Nguyen 25-05-2015
* ############################
* Xu ly Export phia Client, su dung JQuery Library
* + Luu du lai ten report
* + Xu ly tieu de, title va noi dung report
* + fixed bug Fix bug Unlimit row - HEHEHE
* 
*/  
$("#exportToExcel").on('click', function () {
    ajaxStatus.showStatus('Exporting...');
    var uri = $("#report_results").btechco_excelexport({
        containerid     : "report_results",
        datatype        : $datatype.Table,
        worksheetName   : report_def.report_name,
        returnUri       : false  //Fix bug Unlimit row - By Lap Nguyen - HEHEHE
    });
    ajaxStatus.hideStatus();
});


/**
* HB 
* 21-10-2014
* Xu ly Export tren phia Server + sử dụng PHP Excel
*/
function exportExcel2(){
    var htmltable = ConvertFromTable('report_results') 
    jQuery.ajax({  //Make the Ajax Request
        type: "POST",
        url: "index.php?module=Reports&action=exportExcel&sugar_body_only=true",
        data: {
            htmltable: htmltable.html(),
        },
        success: function(data){  
            alert("OK")
        }  

    });

}

function ConvertFromTable(containerid) {
    /**
    * HB
    * Xử lý remove các tag html ko mong đợi
    */
    var htmltable = $('#' + containerid).clone();
    htmltable.find("script,noscript,style").remove();
    htmltable.find("input#expandAllState").remove();
    htmltable.find("input#expandCollapse").remove();
    htmltable.find("br").remove();
    htmltable.find("img").remove();
    //Xu ly remove the a va` remove tr -none- - Lap nguyen Edited
    htmltable.find('a').each(function() {
        var content = $(this).text();
        $(this).parent().text(content);
    });
    htmltable.find('th.reportGroup1ByTableEvenListRowS1').remove();
    //END
    htmltable.html(htmltable.html().trim());

    /**
    * Xu ly replace một số kí tự đặc biệt trước khi xuất ra Excel
    * */
    htmltable.find(".number_align,.reportGroup1ByTableEvenListRowS1,.reportGroupNByTableEvenListRowS1,.reportGroupByDataChildTablelistViewThS1,.reportlistViewMatrixThS1,.reportlistViewMatrixThS2,.reportlistViewMatrixThS3,.reportlistViewMatrixThS4,.reportGroupByDataMatrixEvenListRowS1,.reportGroupByDataMatrixEvenListRowS2,.reportGroupByDataMatrixEvenListRowS3,.reportGroupByDataMatrixEvenListRowS4").each(function() {
        jQuery(this).html(jQuery(this).html().replace("đ", ""));
        jQuery(this).html(jQuery(this).html().replace("$", ""));
        jQuery(this).html(jQuery(this).html().replace("VND", ""));
        jQuery(this).html(jQuery(this).html().replace("USD", ""));
    });

    /**
    * Xu ly format bold text các header trong Matrix, Sum & Detail Report
    */
    htmltable.find(".reportGroup1ByTableEvenListRowS1,.reportGroupNByTableEvenListRowS1,.reportlistViewThS1,table.reportlistView th").each(function() {
        jQuery(this).css("font-weight","bold");
        jQuery(this).css("background-color","#dcdcdc");

    });
    /**
    * Xu ly tieu de report
    * 
    */
    var date_str = '';
    if($('#jscal_field').val() != null && $('#jscal_field2').val() != null)
        var date_str = $('#jscal_field').val()+' - '+$('#jscal_field2').val();  

    var team_str = ''; 
    if($("#Teams\\:name\\:name\\:1").val() != null)
        var team_str = $("#Teams\\:name\\:name\\:1").val();
    if($("#Teams\\:id\\:name\\:1").val() != null)
        var team_str = $("#Teams\\:id\\:name\\:1").val();


    var month_year = ''; 
    if($("select[name=input] option[value='2014']").length > 0)
        var month_year = ' ('+$("select[name=input] option:selected").eq(0).text() +' - '+  $("select[name=input] option:selected").eq(1).text()+') ';

    htmltable.prepend("<h2 style='text-align:center;'>"+ jQuery("div.moduleTitle h2:first-child").text()+ "</h2>"+"<h5 style='text-align:center;'>"+new Date().toString().substring(0, 33)+ "</h5>"+"<h3 style='text-align:center;'>"+date_str+ "</h3>"+"<h3 style='text-align:center;'>"+team_str + month_year+"</h3>");

    return htmltable.html();
}




