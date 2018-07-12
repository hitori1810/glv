//$("#reportDetailsTable tr:eq(1) td:first").html("<b>Modules:</b> Contacts, Contacts  &gt;  Membership Card, Contacts  &gt;  Teams");
SUGAR.util.doWhen('$("#rowid1 td:eq(1)").text() != ""', function() {
    $("#rowid1 td:eq(1)").html("&nbsp;&nbsp;&nbsp;Class > Center");
    $("#rowid1 td:eq(3)").text("")
    $("#reportDetailsTable").hide();
    $("#rowid0 td:eq(4) select").val('tp_last_7_days')
    $("#rowid0 td:eq(4) select option").hide()
    $("#rowid0 td:eq(4) select option[value='tp_last_7_days']").show()
    $("#rowid0 td:eq(4) select option[value='between_dates']").show()
    
    
});

