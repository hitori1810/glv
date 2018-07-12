<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/Contacts/css/dialogExport.css'}">
{sugar_getscript file='custom/modules/Contacts/js/dialogExport.js'}
    <div id="div_dialog_export" title="{$MOD.BTN_EXPORT_FORM}" style="display: none;" >
    <div id ="change_template">
        <input type="hidden" id="default_template" value="{$DEFAULT_TEMPLATE}"></input>
        <label for="template" class="span5">{$MOD.LBL_CHOOSE_TEMPLATE}:</label>
        <select name='slc_template' id='slc_template'>
            <option value="AD_PremiumRegistration_Form_DN.docx">AD_PremiumRegistration_Form_DN.docx</option>
            <option value="AD_PremiumRegistration_Form_HP.docx">AD_PremiumRegistration_Form_HP.docx</option>
            <option value="AD_Registration_Form_DN.docx">AD_Registration_Form_DN.docx</option>
            <option value="AD_Registration_Form_HP.docx">AD_Registration_Form_HP.docx</option>
            <option value="Junior_PremiumRegistration_Forms_HN.docx">Junior_PremiumRegistration_Forms_HN.docx</option>
            <option value="Junior_PremiumRegistration_Forms_Nationwide.docx">Junior_PremiumRegistration_Forms_Nationwide.docx</option>
            <option value="Junior_Registration_Forms_Nationwide.docx">Junior_Registration_Forms_Nationwide.docx</option>
        </select>
    </div>
</div>
