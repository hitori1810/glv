<div id="table_issue_dialog" title="Thông báo" style="display:none;">
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Một số hồ sơ dưới đây không được update đúng khi lịch lớp bị thay đổi. Chương trình sẽ đưa ra hướng giải quyết và bạn phải thực hiện:<br><br>
        <table width="80%">
        <tbody>
        <tr><th nowrap="" width="5%">No.</th>
        <th nowrap="">Name</th>
        <th nowrap="">Hours enroll</th>
        <th nowrap="">Hours in session</th>
        <th nowrap="">Solutation</th></tr>
        {$list_issue}
        </tbody>
        </table><br><br>
        
        <b>TH 1: Số giờ học thực tế < số giờ đã enroll:</b> TH này đa số là học viên thiếu tiền học buổi cuối cùng và chương trình không thể thêm học viên vào buổi này.<br>
        Bạn vui lòng kiểm tra lại:<br> 
          + Nếu đúng bạn dùng chức năng đóng thêm tiền học ở trên.<br>
          + Nếu không đúng bạn liên hệ admin để add lại các buổi thiếu<br><br><br> 
        <b>TH 2: Số giờ học thực tế > số giờ đã enroll:</b> Trường hợp này bạn báo admin để remove học viên ra khỏi các buổi bị dư nhé .<br><br>                                                                                                                                                                 
</div>