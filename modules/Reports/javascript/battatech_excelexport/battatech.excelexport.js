/*
* jQuery Client Side Excel Export Plugin Library
* http://www.battatech.com/
*
* Copyright (c) 2013 Batta Tech Private Limited
* Licensed under https://github.com/battatech/battatech_excelexport/blob/master/LICENSE.txt
*/

(function ($) {

    $datatype = {
        Table: 1
        , Json: 2
        , Xml: 3
        , JqGrid: 4
    }

    var $defaults = {
        containerid: null
        , datatype: $datatype.Table
        , dataset: null
        , columns: null
        , returnUri: false
        , worksheetName: "My Worksheet"
        , encoding: "utf-8"
    };

    var $settings = $defaults;

    $.fn.btechco_excelexport = function (options) {
        $settings = $.extend({}, $defaults, options);

        var gridData = [];
        var excelData;

        return Initialize();

        function Initialize() {
            BuildDataStructure();

            switch ($settings.datatype) {
                case 1:
                    excelData = Export(ConvertFromTable($settings.containerid));
                    break;
                case 2:
                    excelData = Export(ConvertDataStructureToTable());
                    break;
                case 3:
                    excelData = Export(ConvertDataStructureToTable());
                    break;
                case 4:
                    excelData = Export(ConvertDataStructureToTable());
                    break;
            }

            if ($settings.returnUri) {
                return excelData;
            }
            else{
                var blob = new Blob([excelData], {type: "application/csv;charset=utf-8;"});
                saveAs(blob, $settings.worksheetName+".xls");
            }
        }

        function BuildDataStructure() {
            switch ($settings.datatype) {
                case 1:
                    break;
                case 2:
                    gridData = $settings.dataset;
                    break;
                case 3:
                    $($settings.dataset).find("row").each(function (key, value) {
                        var item = {};

                        if (this.attributes != null && this.attributes.length > 0) {
                            $(this.attributes).each(function () {
                                item[this.name] = this.value;
                            });

                            gridData.push(item);
                        }
                    });
                    break;
                case 4:
                    $($settings.dataset).find("rows > row").each(function (key, value) {
                        var item = {};

                        if (this.children != null && this.children.length > 0) {
                            $(this.children).each(function () {
                                item[this.tagName] = $(this).text();
                            });

                            gridData.push(item);
                        }
                    });
                    break;
            }
        }

        function ConvertFromTable(containerid) {
            /**
            * HB
            * Xử lý remove các tag html ko mong đợi
            */
            var htmltable = $('#' + containerid).clone();
            htmltable.find("script,noscript,style").remove();
            htmltable.prepend("<style> table, td {border:thin solid black} th {background: #CCF;} table {border-collapse:collapse}</style>");
            htmltable.find("input#expandAllState").remove();
            htmltable.find("input#expandCollapse").remove();
            htmltable.find("table#query_table").remove();
            //    htmltable.find("br").remove();
            htmltable.find("img").remove();
            //Xu ly remove the a va` remove tr -none- - Lap nguyen Edited
            htmltable.find('th.reportGroup1ByTableEvenListRowS1').closest('td').closest('tr').remove();
            htmltable.find('table.reportGroupBySpaceTableView').remove();

            htmltable.find('a').each(function() {
                var href = $(this).attr('href');
                if( (href.indexOf('J_Payment') != -1 || href.indexOf('J_StudentSituations') != -1 || href.indexOf('J_Class') != -1 || href.search('Contacts') != -1 || href.search('Leads') != -1 || href.search('J_Discount') != -1 || href.search('J_Coursefee') != -1) && (href.indexOf( 'javascript' ) == -1)){
                    $(this).attr('href', 'http://'+document.domain+'/'+href);
                }else{
                    var content = $(this).text();
                    $(this).parent().text(content);
                }

            });
            //Xu ly remove checkbox = yes or no - Lap nguyen Edited
            htmltable.find(':checkbox').each(function() {
                if($(this).is(':checked'))
                    $(this).parent().text('Yes');
                else $(this).parent().text('No');
            });

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
            //            htmltable.find("td").each(function() {
            //                jQuery(this).css("font-size","16px");
            //            });
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

            htmltable.prepend("<h2 style='text-align:center;'>"+ jQuery("div.moduleTitle h2:first-child").text()+ "</h2>"+"<h4 style='text-align:center;'>"+new Date().toString().substring(0, 33)+ "</h4>"+"<h3 style='text-align:center;'>"+date_str+ "</h3>"+"<h3 style='text-align:center;'>"+team_str + month_year+"</h3>");

            return htmltable.html();
        }

        function ConvertDataStructureToTable() {
            var result = "<table>";

            result += "<thead><tr>";
            $($settings.columns).each(function (key, value) {
                if (this.ishidden != true) {
                    result += "<th";
                    if (this.width != null) {
                        result += " style='width: " + this.width + "'";
                    }
                    result += ">";
                    result += this.headertext;
                    result += "</th>";
                }
            });
            result += "</tr></thead>";

            result += "<tbody>";
            $(gridData).each(function (key, value) {
                result += "<tr>";
                $($settings.columns).each(function (k, v) {
                    if (value.hasOwnProperty(this.datafield)) {
                        if (this.ishidden != true) {
                            result += "<td";
                            if (this.width != null) {
                                result += " style='width: " + this.width + "'";
                            }
                            result += ">";
                            result += value[this.datafield];
                            result += "</td>";
                        }
                    }
                });
                result += "</tr>";
            });
            result += "</tbody>";

            result += "</table>";
            return result;
        }

        function Export(htmltable) {
            var excelFile = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:excel' xmlns='http://www.w3.org/TR/REC-html40'>";
            excelFile += "<head>";
            excelFile += '<meta http-equiv="Content-type" content="text/html;charset=utf-8"/>';
            excelFile += "<!--[if gte mso 9]>";
            excelFile += "<xml>";
            excelFile += "<x:ExcelWorkbook>";
            excelFile += "<x:ExcelWorksheets>";
            excelFile += "<x:ExcelWorksheet>";
            excelFile += "<x:Name>";
            excelFile += "{worksheet}";
            excelFile += "</x:Name>";
            excelFile += "<x:WorksheetOptions>";
            excelFile += "<x:DisplayGridlines/>";
            excelFile += "</x:WorksheetOptions>";
            excelFile += "</x:ExcelWorksheet>";
            excelFile += "</x:ExcelWorksheets>";
            excelFile += "</x:ExcelWorkbook>";
            excelFile += "</xml>";
            excelFile += "<![endif]-->";
            excelFile += "</head>";
            excelFile += "<body>";
            excelFile += htmltable.replace(/"/g, '\'');
            excelFile += "</body>";
            excelFile += "</html>";

            var uri = "data:application/vnd.ms-excel;base64,";
            var ctx = { worksheet: $settings.worksheetName, table: htmltable };
            if($settings.returnUri)
                return (uri + base64(format(excelFile, ctx)));
            else
                return format(excelFile, ctx);
        }

        function base64(s) {
            return window.btoa(unescape(encodeURIComponent(s)));
        }

        function format(s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; });
        }
    };
})(jQuery);
