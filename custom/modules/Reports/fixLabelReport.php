<?php
    function fixLabel()
    {
        $js = 
        <<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() {
           return $('#rowid2').find('td').eq(1)[0] != null;
        },
        function() {
            $("#filters").children().each(function(){
                $(this).find('td:eq(1)').find("select").remove();
                var label = $(this).find('td:eq(1)').text();
                // Fix label here
                label = label.replace("LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE","Packages");
                label = label.replace("Opportunities","Enrollments");
                label = label.replace("Contacts","Students");
                label = label.replace("Meetings","Sessions");
                $(this).find('td:eq(1)').text(label);  
            });
            
        }        
        );
        </script>
EOQ;
        echo $js;    
    }                        

    fixLabel();
?>
