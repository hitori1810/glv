<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Attendance &raquo; ApolloCRM</title>
<script type="text/javascript" src="include/javascript/jquery/jquery.js"></script>
<script type="text/javascript" src="custom/modules/C_Attendance/js/monitor.js"></script>

<link rel='stylesheet' type='text/css' href='custom/include/javascripts/Bootstrap/css/bootstrap.css'>
<link rel='stylesheet' type='text/css' href='custom/include/javascripts/Bootstrap/css/view_custom.css'>
{literal}
<style>
    span
    {
    font-size: 25px;
    }
    #check_info{
    width: 100%;
    height: 100%;
    display:none;
    }
    #defaut_info{
    width: 100%;
    height: 100%;
    }  
    #tbl_result td{
    vertical-align: top;
    height: 20px;
    color: white;
    font-size: 30px;
    }
    #tbl_result td div{
    width: 100%;
    height: 100%;
    overflow:hidden; 
    }
    #tbl_result td#image{
    padding:20px;
    max-height: 20px;
    text-align:center;
    }

</style>

{/literal}
<table width="100%" height = "100%" style = "background-color: #2980B9">
    <tr>
        <td width = "40%">
            <table width="100%" height = "100%"  >
                <tr>
                    <td align="left"  nowrap>
                        <button class="bbtn-all bbtn dsize  btn-wonka-land-1 " id="btn_back" style="margin-top:5px;margin-bottom:5px;margin-right:5px;margin-left:5px;">
                            <span class="bbtn-img-right bbtn-custom-txt"><img src="custom/themes/default/images/back_icon.png"></span>
                            <span class="bbtn-txt-center bbtn-custom-txt" style='font-size: 15px'>Back</span>       
                        </button>
                    </td>
                </tr>
                <tr>
                    <td align="center"  nowrap><img src="custom/themes/default/images/apollo360.jpg"></td>
                </tr>
                <tr>
                    <td align="center"  nowrap><label for="card_number"></label>
                        <input name="card_number" type="text" id="card_number" value="" size="50" style="width:60%;font-size:18pt;text-align:center;color:#3c3c3b"/></td>
                    <td colspan="2" align="center" nowrap></td>
                </tr>


                <tr>
                    <td  align="center" nowrap>
                        <button class="bbtn-all bbtn dsize btn-limekiln-3 " id="btn_search" style="margin-top:10px;margin-bottom:10px;margin-right:0px;margin-left:0px;">
                            <span class="bbtn-img-right bbtn-custom-txt"><img src="custom/themes/default/images/search_icon.png"></span><span style="clear:both;display:none"></span>
                            <span class="bbtn-txt-center bbtn-custom-txt">Search</span>       
                        </button>
                        &nbsp;&nbsp;


                        <!--<button class="bbtn-all bbtn dsize btn-wonka-land-1 " id="btn_enrollment" style="margin-top:10px;margin-bottom:10px;margin-right:0px;margin-left:0px;">
                        <span class="bbtn-img-right bbtn-custom-txt"><img src="custom/themes/default/images/enrollment_icon.png"></span><span style="clear:both;display:none"></span>
                        <span class="bbtn-txt-center bbtn-custom-txt">Enrollment</span>       
                        </button>
                        &nbsp;&nbsp;


                        <button class="bbtn-all bbtn btn-wonka-land-1" id="btn_payment" style="margin-top:10px;margin-bottom:10px;margin-right:0px;margin-left:0px;">
                        <span class="bbtn-img-right bbtn-custom-txt"><img src="custom/themes/default/images/payment_icon.png"></span><span style="clear:both;display:none"></span>
                        <span class="bbtn-txt-center bbtn-custom-txt">Payment</span>       
                        </button>
                        &nbsp;&nbsp;-->
                    </td>
                </tr>
                <tr><td height = "30%"></td></tr>


            </table>

        </td>
        <td width = "60%">
            <div id="check_info">
            </div>
            <div id="defaut_info">
                <table width="100%" height = "100%" border="0" style ="border-left:2px solid white" >
                    <tr>
                        <td colspan="2" align="center" nowrap>
                            <img name="no_image" id="no_image" src="custom/themes/default/images/apollo360.jpg"/>
                            <br><br>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>

</table>
<div id="mainDiv" style="filter: alpha(opacity = 50);display:none;z-index: 1000; border: medium none; margin: 0pt; padding: 0pt; width: 100%; height: 100%; top: 0pt; left: 0pt; background-color: rgb(0, 0, 0); opacity: 0.2; cursor: wait; position: fixed;" ></div>
<div id="SubDiv" style="z-index: 1001; position: fixed; padding: 0px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(0, 0, 0); border: 3px solid #000000; background-color: rgb(255, 255, 255); cursor: wait; display: none;" >
    <img style="margin-top: 10px;" src="custom/include/images/loader32.gif">
    <br/>
    <h2>Please Wait...</h2>
</div>

