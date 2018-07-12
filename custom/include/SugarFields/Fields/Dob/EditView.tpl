{{assign var="day" value=$vardef.key|cat:'_day'}}
{{assign var="month" value=$vardef.key|cat:'_month'}}
{{assign var="year" value=$vardef.key|cat:'_year'}}
{{assign var="date" value=$vardef.key|cat:'_date'}}

{sugar_getscript file="custom/include/SugarFields/Fields/Dob/Dob.js"}
{literal}
<script type="text/javascript">
    $(function() {
        DOB.init('{{$vardef.key}}');
    });
</script>
{/literal}

<select name="{{$day}}" id="{{$day}}" title="{{$vardef.help}}" style="padding: 5px;width: 60px" tabindex="{{$tabindex}}">
    {html_options options=$fields.{{$day}}.options selected=$fields.{{$day}}.value}
</select>
<select name="{{$month}}" id="{{$month}}" title="{{$vardef.help}}" style="padding: 5px;width: 60px" tabindex="{{$tabindex}}">
    {html_options options=$fields.{{$month}}.options selected=$fields.{{$month}}.value}
</select>
<input type="text" name="{{$year}}" id="{{$year}}" style="width:122px;vertical-align: top;" maxlength='4' value='{$fields.{{$year}}.value}'  {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}><span class="dateFormat"> dd/mm/yyyy</span>
<input type="hidden" name="{{$date}}" id="{{$date}}" value='{$fields.{{$date}}.value}'/>