{{assign var="day" value=$vardef.key|cat:'_day'}}
{{assign var="month" value=$vardef.key|cat:'_month'}}
{{assign var="year" value=$vardef.key|cat:'_year'}}
{{assign var="date" value=$vardef.key|cat:'_date'}}

<span id={{$vardef.name}}>{$fields.{{$date}}.value}</span>