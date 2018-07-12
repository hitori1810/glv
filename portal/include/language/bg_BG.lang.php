<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

	

$app_list_strings = array (
  'account_type_dom' => 
  array (
    '' => '',
    'Analyst' => 'Анализатор',
    'Competitor' => 'Конкурент',
    'Customer' => 'Клиент',
    'Integrator' => 'Интегратор',
    'Investor' => 'Инвеститор',
    'Other' => 'Други',
    'Partner' => 'Партньор',
    'Press' => 'Медиа',
    'Prospect' => 'В процес на преговори',
    'Reseller' => 'Дистрибутор',
  ),
  'activity_dom' => 
  array (
    'Call' => 'Обаждане',
    'Email' => 'Електронна поща',
    'Meeting' => 'Среща',
    'Note' => 'Описание',
    'Task' => 'Задача',
  ),
  'bopselect_type_dom' => 
  array (
    'Equals' => 'Е равно на',
  ),
  'bselect_type_dom' => 
  array (
    'bool_false' => 'Не',
    'bool_true' => 'Да',
  ),
  'bug_priority_dom' => 
  array (
    'High' => 'Висока',
    'Low' => 'Ниска',
    'Medium' => 'Средна',
    'Urgent' => 'Належаща',
  ),
  'bug_resolution_dom' => 
  array (
    '' => '',
    'Accepted' => 'Приет',
    'Duplicate' => 'Дублирай',
    'Fixed' => 'Определен',
    'Invalid' => 'Невалиден',
    'Later' => 'Отложен',
    'Out of Date' => 'С изтекъл срок',
  ),
  'bug_status_dom' => 
  array (
    'Assigned' => 'Разпределен',
    'Closed' => 'Затворен',
    'New' => 'Нов',
    'Pending' => 'Висяща',
    'Rejected' => 'Отхвърлен',
  ),
  'bug_type_dom' => 
  array (
    'Defect' => 'Недостатък (Дефект)',
    'Feature' => 'Свойство (Черта)',
  ),
  'call_direction_dom' => 
  array (
    'Inbound' => 'Входящо',
    'Outbound' => 'Изходящо',
  ),
  'call_status_dom' => 
  array (
    'Held' => 'Проведена',
    'Not Held' => 'Несъстояла се',
    'Planned' => 'Планирана',
  ),
  'campaign_status_dom' => 
  array (
    '' => '',
    'Active' => 'Активен',
    'Complete' => 'Завършена',
    'In Queue' => 'Чакаща за изпращане',
    'Inactive' => 'Неактивен',
    'Planning' => 'Планирана',
    'Sending' => 'Изпратена',
  ),
  'campaign_type_dom' => 
  array (
    '' => '',
    'Email' => 'Електронна поща',
    'Mail' => 'Поща',
    'Print' => 'Печат',
    'Radio' => 'Radio бутон',
    'Telesales' => 'Телемаркетинг',
    'Television' => 'Телевизия',
    'Web' => 'Уеб',
  ),
  'campainglog_activity_type_dom' => 
  array (
    '' => '',
    'contact' => 'Създадени контакти',
    'invalid email' => 'Върнати, невалиден адрес',
    'lead' => 'Създадени потенциални клиенти',
    'link' => 'Посетили адреса',
    'removed' => 'Да не се изпраща електронна поща',
    'send error' => 'Върнати, други грешки',
    'targeted' => 'Изпратени / направен опит за изпращане',
    'viewed' => 'Прочели съобщението',
  ),
  'campainglog_target_type_dom' => 
  array (
    'Contacts' => 'Контакти',
    'Leads' => 'Потенциални клиенти',
    'Prospects' => 'Целеви клиенти',
    'Users' => 'Потребители',
  ),
  'case_priority_dom' => 
  array (
    'P1' => 'Висока',
    'P2' => 'Средна',
    'P3' => 'Ниска',
  ),
  'case_relationship_type_dom' => 
  array (
    '' => '',
    'Alternate Contact' => 'Алтернативни контакти',
    'Primary Contact' => 'Основен Контакт',
  ),
  'case_status_dom' => 
  array (
    'Assigned' => 'Разпределен',
    'Closed' => 'Затворен',
    'Duplicate' => 'Дублирай',
    'New' => 'Нов',
    'Pending Input' => 'Висяща',
    'Rejected' => 'Отхвърлен',
  ),
  'checkbox_dom' => 
  array (
    '' => '[-blank-]',
    1 => 'Да',
    2 => 'Не',
  ),
  'contract_expiration_notice_dom' => 
  array (
    1 => '1 ден',
    3 => '3 дни',
    5 => '5 дни',
    7 => '1 седмица',
    14 => '2 седмици',
    21 => '3 седмици',
    31 => '1 месец',
  ),
  'contract_payment_frequency_dom' => 
  array (
    'halfyearly' => 'На полугодие',
    'monthly' => 'Месечно',
    'quarterly' => 'На тримесечие',
    'yearly' => 'Годишно',
  ),
  'contract_status_dom' => 
  array (
    'inprogress' => 'В процес на изпълнение',
    'notstarted' => 'Планирана',
    'signed' => 'Подписан',
  ),
  'cselect_type_dom' => 
  array (
    'Does not Equal' => 'Е различно от',
    'Equals' => 'Е равно на',
  ),
  'document_category_dom' => 
  array (
    '' => '',
    'Knowledege Base' => 'База от знания',
    'Marketing' => 'Маркетинг',
    'Sales' => 'Продажби',
  ),
  'document_status_dom' => 
  array (
    'Active' => 'Активен',
    'Draft' => 'Работен вариант',
    'Expired' => 'С изтекъл срок на валидност',
    'FAQ' => 'Често задавани въпроси',
    'Pending' => 'Висяща',
    'Under Review' => 'В процес на обработка',
  ),
  'document_subcategory_dom' => 
  array (
    '' => '',
    'FAQ' => 'Често задавани въпроси',
    'Marketing Collateral' => 'Маркетингови материали',
    'Product Brochures' => 'Брошури',
  ),
  'document_template_type_dom' => 
  array (
    '' => '',
    'eula' => 'EULA',
    'license' => 'Лицензионно споразумение',
    'mailmerge' => 'Сливане на писма',
    'nda' => 'NDA',
  ),
  'dom_cal_month_long' => 
  array (
    1 => 'Януари',
    2 => 'Февруари',
    3 => 'Март',
    4 => 'Април',
    5 => 'Май',
    6 => 'Юни',
    7 => 'Юли',
    8 => 'Август',
    9 => 'Септември',
    10 => 'Октомври',
    11 => 'Ноември',
    12 => 'Декември',
  ),
  'dom_email_bool' => 
  array (
    'bool_false' => 'Не',
    'bool_true' => 'Да',
  ),
  'dom_email_distribution' => 
  array (
    '' => '--Няма данни--',
    'direct' => 'Отговорник',
    'leastBusy' => 'зает',
    'roundRobin' => 'Round-Robin',
  ),
  'dom_email_editor_option' => 
  array (
    '' => 'E-мейл формат по подразбиране',
    'html' => 'HTML формат',
    'plain' => 'Текст формат',
  ),
  'dom_email_errors' => 
  array (
    1 => 'Моля, изберете само един потребител при директно присвояване на запис.',
    2 => 'Необходимо е да присвоявате само маркираните записи при директно присвояване на запис.',
  ),
  'dom_email_link_type' => 
  array (
    '' => 'Пощенски клиент по подразбиране',
    'mailto' => 'Външен пощенски клиент',
    'sugar' => 'SugarCRM Пощенски клиент',
  ),
  'dom_email_server_type' => 
  array (
    '' => '-Няма данни--',
    'imap' => 'IMAP',
    'pop3' => 'POP3',
  ),
  'dom_email_status' => 
  array (
    'archived' => 'Архивни',
    'closed' => 'Затворен',
    'draft' => 'Черново',
    'read' => 'Прочетено',
    'replied' => 'Отговорено',
    'send_error' => 'С грешка при изпращане',
    'sent' => 'Изпратено',
    'unread' => 'Нечетени',
  ),
  'dom_email_types' => 
  array (
    'archived' => 'Архивни',
    'draft' => 'Работен вариант',
    'inbound' => 'Входящо',
    'out' => 'Изпратено',
  ),
  'dom_int_bool' => 
  array (
    1 => 'Да',
  ),
  'dom_mailbox_type' => 
  array (
    'bounce' => 'Bounce Handling',
    'bug' => 'Добавяне на проблем',
    'contact' => 'Създаване на контакт',
    'pick' => 'Създай [Всички]',
    'sales' => 'Създаване на потенциален клиент',
    'support' => 'Въвеждане на казус',
    'task' => 'Добавяне на задача',
  ),
  'dom_meeting_accept_options' => 
  array (
    'accept' => 'Приеми',
    'decline' => 'Отклони',
    'tentative' => 'Пробен',
  ),
  'dom_meeting_accept_status' => 
  array (
    'accept' => 'Приет',
    'decline' => 'Отклонена',
    'none' => 'няма',
    'tentative' => 'Пробен',
  ),
  'dom_report_types' => 
  array (
    'detailed_summary' => 'Обобщаваща с детайли',
    'summary' => 'Обобаваща',
    'tabular' => 'Списъчна',
  ),
  'dom_switch_bool' => 
  array (
    '' => 'Не',
    'off' => 'Не',
    'on' => 'Да',
  ),
  'dom_timezones' => 
  array (
    -12 => '(GMT - 12) International Date Line West',
    -11 => '(GMT - 11) Остров Мидуей, Самоа',
    -10 => '(GMT - 10) Хаваи',
    -9 => '(GMT - 9) Аляска',
    -8 => '(GMT - 8) Сан Франциско',
    -7 => '(GMT - 7) Феникс',
    -6 => '(GMT - 6) Саскачеван',
    -5 => '(GMT - 5) Ню Йорк',
    -4 => '(GMT - 4) Сантяго',
    -3 => '(GMT - 3) Буенос Айрес',
    -2 => '(GMT - 2) Среден Атлантик',
    -1 => '(GMT - 1) Азорски острови',
    1 => '(GMT + 1) Мадрид',
    2 => '(GMT + 2) Атина',
    3 => '(GMT + 3) Москва',
    4 => '(GMT + 4) Кабул',
    5 => '(GMT + 5) Екатеринбург',
    6 => '(GMT + 6) Астана',
    7 => '(GMT + 7) Банкок',
    8 => '(GMT + 8) Пърт',
    9 => '(GMT + 9) Сеул',
    10 => '(GMT + 10) Бризбейн',
    11 => '(GMT + 11) Соломон. острови',
    12 => '(GMT + 12) Оукланд',
  ),
  'dom_timezones_extra' => 
  array (
    -12 => '(GMT-12) International Date Line West',
    -11 => '(GMT-11) Остров Мидуей, Самоа',
    -10 => '(GMT-10) Хаваи',
    -9 => '(GMT-9) Аляска',
    -8 => '(GMT-8) (PST)',
    -7 => '(GMT-7) (MST)',
    -6 => '(GMT-6) (CST)',
    -5 => '(GMT-5) (EST)',
    -4 => '(GMT-4) Сантяго',
    -3 => '(GMT-3) Буенос Айрес',
    -2 => '(GMT-2) Среден Атлантик',
    -1 => '(GMT-1) Азорски острови',
    1 => '(GMT+1) Мадрид',
    2 => '(GMT+2) Атина',
    3 => '(GMT+3) Москва',
    4 => '(GMT+4) Кабул',
    5 => '(GMT+5) Екатеринбург',
    6 => '(GMT+6) Астана',
    7 => '(GMT+7) Банкок',
    8 => '(GMT+8) Пърт',
    9 => '(GMT+9) Сеул',
    10 => '(GMT+10) Бризбейн',
    11 => '(GMT+11) Соломон. острови',
    12 => '(GMT+12) Оукланд',
  ),
  'dselect_type_dom' => 
  array (
    'Does not Equal' => 'Е различно от',
    'Equals' => 'Е равно на',
    'Less Than' => 'Е с по-малки стойности от',
    'More Than' => 'Е с по-големи стойности от',
  ),
  'dtselect_type_dom' => 
  array (
    'Less Than' => 'помалко от',
    'More Than' => 'повече от',
  ),
  'duration_intervals' => 
  array (
    15 => '15',
    30 => '30',
    45 => '45',
  ),
  'email_marketing_status_dom' => 
  array (
    '' => '',
    'active' => 'Активен',
    'inactive' => 'Неактивен',
  ),
  'employee_status_dom' => 
  array (
    'Active' => 'Активен',
    'Leave of Absence' => 'В отпуска',
    'Terminated' => 'Напуснал',
  ),
  'forecast_schedule_status_dom' => 
  array (
    'Active' => 'Активен',
    'Inactive' => 'Неактивен',
  ),
  'forecast_type_dom' => 
  array (
    'Direct' => 'Директен',
    'Rollup' => 'Rollup',
  ),
  'industry_dom' => 
  array (
    '' => '',
    'Apparel' => 'Текстилна промишленост',
    'Banking' => 'Банково дело',
    'Biotechnology' => 'Биотехнологии',
    'Chemicals' => 'Химическа промишленост',
    'Communications' => 'Комуникации',
    'Construction' => 'Строителство',
    'Consulting' => 'Консултантски услуги',
    'Education' => 'Образование',
    'Electronics' => 'Електроника',
    'Energy' => 'Природни ресурси и енергия',
    'Engineering' => 'Инженерна дейност',
    'Entertainment' => 'Забавление',
    'Environmental' => 'Екология',
    'Finance' => 'Финанси',
    'Government' => 'Правителствено и политика',
    'Healthcare' => 'Здравеопазване',
    'Hospitality' => 'Хотелиерство',
    'Insurance' => 'Застрахователна дейност',
    'Machinery' => 'Оборудване',
    'Manufacturing' => 'Производство',
    'Media' => 'Медии',
    'Not For Profit' => 'С нестопанска цел',
    'Other' => 'Други',
    'Recreation' => 'Ваканционни услуги',
    'Retail' => 'Търговия на дребно',
    'Shipping' => 'Превоз',
    'Technology' => 'Технологии',
    'Telecommunications' => 'Телекомуникации',
    'Transportation' => 'Транспортна дейност',
    'Utilities' => 'Комунални услуги',
  ),
  'language_pack_name' => 'Български',
  'layouts_dom' => 
  array (
    'Invoice' => 'Фактура',
    'Standard' => 'Предложение',
    'Terms' => 'Условия на плащане',
  ),
  'lead_source_dom' => 
  array (
    '' => '',
    'Cold Call' => 'Телефонен разговор',
    'Conference' => 'Конференция',
    'Direct Mail' => 'Стационарна поща',
    'Email' => 'Електронна поща',
    'Employee' => 'Служител',
    'Existing Customer' => 'Съществуващ клиент',
    'Other' => 'Други',
    'Partner' => 'Партньор',
    'Public Relations' => 'Връзки с обществеността',
    'Self Generated' => 'Самостоятелно генерирани',
    'Trade Show' => 'Търговско изложение',
    'Web Site' => 'Сайт на компанията',
    'Word of mouth' => 'Препоръка',
  ),
  'lead_status_dom' => 
  array (
    '' => '',
    'Assigned' => 'Разпределен',
    'Converted' => 'Преобразуван:',
    'Dead' => 'Загубен',
    'In Process' => 'В опашката?',
    'New' => 'Нов',
    'Recycled' => 'Възобновен',
  ),
  'lead_status_noblank_dom' => 
  array (
    'Assigned' => 'Разпределен',
    'Converted' => 'Преобразуван:',
    'Dead' => 'Загубен',
    'In Process' => 'В опашката?',
    'New' => 'Нов',
    'Recycled' => 'Възобновен',
  ),
  'meeting_status_dom' => 
  array (
    'Held' => 'Проведена',
    'Not Held' => 'Несъстояла се',
    'Planned' => 'Планирана',
  ),
  'messenger_type_dom' => 
  array (
    '' => '',
    'AOL' => 'AOL',
    'MSN' => 'MSN',
    'Yahoo!' => 'Yahoo!',
  ),
  'moduleList' => 
  array (
    'Bugs' => 'Проблеми',
    'Cases' => 'Казуси',
    'FAQ' => 'Често задавани въпроси',
    'Home' => 'Начало',
    'KBDocuments' => 'База от знания',
    'Newsletters' => 'Бюлетини',
    'Notes' => 'Бележки',
    'Teams' => 'Екип',
    'Users' => 'Потребители',
  ),
  'moduleListSingular' => 
  array (
    'Bugs' => 'Проблем',
    'Cases' => 'Казус',
    'Home' => 'Начало',
    'Notes' => 'Описание',
    'Teams' => 'Екип',
    'Users' => 'Потребител',
  ),
  'mselect_type_dom' => 
  array (
    'Equals' => 'Е',
    'in' => 'Спада към',
  ),
  'notifymail_sendtype' => 
  array (
    'SMTP' => 'SMTP',
  ),
  'opportunity_relationship_type_dom' => 
  array (
    '' => '',
    'Business Decision Maker' => 'Вземащ бизнес решения',
    'Business Evaluator' => 'Бизнес оценител',
    'Executive Sponsor' => 'Спонсор',
    'Influencer' => 'Лобист',
    'Other' => 'Други',
    'Primary Decision Maker' => 'Първоначално решение',
    'Technical Decision Maker' => 'Вземащ технически решения',
    'Technical Evaluator' => 'Технически оценител',
  ),
  'opportunity_type_dom' => 
  array (
    '' => '',
    'Existing Business' => 'Съществуващ бизнес',
    'New Business' => 'Нов бизнес',
  ),
  'order_stage_dom' => 
  array (
    'Cancelled' => 'Отменена',
    'Confirmed' => 'Потвърдена',
    'On Hold' => 'Висяща',
    'Pending' => 'Висяща',
    'Shipped' => 'Изпратен',
  ),
  'payment_terms' => 
  array (
    '' => '',
    'Net 15' => 'Отложено плащане - 15 дни',
    'Net 30' => 'Отложено плащане - 30 дни',
  ),
  'pricing_formula_dom' => 
  array (
    'Fixed' => 'Фиксирана цена',
    'IsList' => 'По каталожна цена',
    'PercentageDiscount' => 'Отстъпка от каталожна цена',
    'PercentageMarkup' => 'Надбавка върху цената',
    'ProfitMargin' => 'Норма на печалба',
  ),
  'product_category_dom' => 
  array (
    '' => '',
    'Accounts' => 'Организации',
    'Activities' => 'Дейности',
    'Bug Tracker' => 'Проблеми',
    'Calendar' => 'Календар',
    'Calls' => 'Обаждания',
    'Campaigns' => 'Кампании',
    'Cases' => 'Казуси',
    'Contacts' => 'Контакти',
    'Currencies' => 'Валути',
    'Dashboard' => 'Електронно табло',
    'Documents' => 'Документи',
    'Emails' => 'Електронна поща',
    'Feeds' => 'Новини',
    'Forecasts' => 'Планиране',
    'Help' => 'Помощ',
    'Home' => 'Начало',
    'Leads' => 'Потенциални клиенти',
    'Meetings' => 'Срещи',
    'Notes' => 'Бележки',
    'Opportunities' => 'Възможности',
    'Outlook Plugin' => 'Outlook Plugin',
    'Product Catalog' => 'Продуктов каталог',
    'Products' => 'Продукти',
    'Projects' => 'Проекти',
    'Quotes' => 'Оферти',
    'RSS' => 'RSS новини',
    'Releases' => 'Версии',
    'Studio' => 'Студио',
    'Upgrade' => 'Актуализация',
    'Users' => 'Потребители',
  ),
  'product_status_dom' => 
  array (
    'Orders' => 'Поръчан',
    'Quotes' => 'Офериран',
    'Ship' => 'Изпратен',
  ),
  'product_status_quote_key' => 'Оферти',
  'product_template_status_dom' => 
  array (
    'Available' => 'Наличен на склад',
    'Unavailable' => 'Изчерпан',
  ),
  'project_task_priority_options' => 
  array (
    'High' => 'Висока',
    'Low' => 'Ниска',
    'Medium' => 'Средна',
  ),
  'project_task_status_options' => 
  array (
    'Completed' => 'Приключена',
    'Deferred' => 'Отложена',
    'In Progress' => 'В процес на изпълнение',
    'Not Started' => 'Планирана',
    'Pending Input' => 'Висяща',
  ),
  'project_task_utilization_options' => 
  array (
    25 => '25',
    50 => '50',
    75 => '75',
    100 => '100',
  ),
  'prospect_list_type_dom' => 
  array (
    'default' => 'По подразбиране',
    'exempt' => 'Блокиращ - по идентификатор',
    'exempt_address' => 'Блокиращ - по адрес',
    'exempt_domain' => 'Блокиращ - по домейн',
    'seed' => 'За сведение',
    'test' => 'Тестов',
  ),
  'query_calc_oper_dom' => 
  array (
    '*' => '(X) Умножено по',
    '+' => '(+) Плюс',
    '-' => '(-) Минус',
    '/' => '(/) Разделено по',
  ),
  'quote_relationship_type_dom' => 
  array (
    '' => '',
    'Business Decision Maker' => 'Вземащ бизнес решения',
    'Business Evaluator' => 'Бизнес оценител',
    'Executive Sponsor' => 'Спонсор',
    'Influencer' => 'Лобист',
    'Other' => 'Други',
    'Primary Decision Maker' => 'Първоначално решение',
    'Technical Decision Maker' => 'Вземащ технически решения',
    'Technical Evaluator' => 'Технически оценител',
  ),
  'quote_stage_dom' => 
  array (
    'Closed Accepted' => 'Приета',
    'Closed Dead' => 'Замразена',
    'Closed Lost' => 'Загубена',
    'Confirmed' => 'Потвърдена',
    'Delivered' => 'Изпратена',
    'Draft' => 'Работен вариант',
    'Negotiation' => 'Преговори по офертата',
    'On Hold' => 'Висяща',
  ),
  'quote_type_dom' => 
  array (
    'Orders' => 'Поръчка',
    'Quotes' => 'Оферта',
  ),
  'record_type_display' => 
  array (
    'Accounts' => 'Организация',
    'Bugs' => 'Проблем',
    'Cases' => 'Казус',
    'Contacts' => 'Контакти',
    'Leads' => 'Потенциален клиент',
    'Opportunities' => 'Свързан с възможност:',
    'ProductTemplates' => 'Продукт',
    'Project' => 'Проекти',
    'ProjectTask' => 'Задача по проект',
    'Quotes' => 'Оферта',
    'Tasks' => 'Задача',
  ),
  'record_type_display_notes' => 
  array (
    'Accounts' => 'Организация',
    'Bugs' => 'Проблем',
    'Calls' => 'Обаждане',
    'Cases' => 'Казус',
    'Contacts' => 'Контакт',
    'Contracts' => 'Договор',
    'Emails' => 'Електронна поща',
    'Leads' => 'Потенциален клиент',
    'Meetings' => 'Среща',
    'Opportunities' => 'Свързан с възможност:',
    'ProductTemplates' => 'Продукт',
    'Products' => 'Продукт',
    'Project' => 'Проекти',
    'ProjectTask' => 'Задача по проект',
    'Quotes' => 'Оферта',
  ),
  'reminder_max_time' => '3600',
  'reminder_time_options' => 
  array (
    60 => '1 минута по-рано',
    300 => '5 минути по-рано',
    600 => '10 минути по-рано',
    900 => '15 минути по-рано',
    1800 => '30 минути по-рано',
    3600 => '1 час по-рано',
  ),
  'sales_probability_dom' => 
  array (
    'Closed Lost' => '',
    'Closed Won' => '100',
    'Id. Decision Makers' => '40',
    'Needs Analysis' => '25',
    'Negotiation/Review' => '80',
    'Perception Analysis' => '50',
    'Proposal/Price Quote' => '65',
    'Prospecting' => '10',
    'Qualification' => '20',
    'Value Proposition' => '30',
  ),
  'sales_stage_dom' => 
  array (
    'Closed Lost' => 'Загубена',
    'Closed Won' => 'Спечелена',
    'Id. Decision Makers' => 'Определяне на ключовите фигури',
    'Needs Analysis' => 'Анализ на нуждите на клиента',
    'Negotiation/Review' => 'Преговори/Преглед на офертата',
    'Perception Analysis' => 'Определяне на стратегия за преговорите',
    'Proposal/Price Quote' => 'Предложение/Ценова оферта',
    'Prospecting' => 'Потенциален',
    'Qualification' => 'Класификация на сделката',
    'Value Proposition' => 'Разработване на решение',
  ),
  'salutation_dom' => 
  array (
    '' => '',
    'Dr.' => 'Доктор',
    'Mr.' => 'Г-н',
    'Mrs.' => 'Г-жа',
    'Ms.' => 'Г-жа',
    'Prof.' => 'Професор',
  ),
  'schedulers_times_dom' => 
  array (
    'completed' => 'Приключена',
    'failed' => 'Завършил неуспешно',
    'in progress' => 'В процес на изпълнение',
    'no curl' => 'Не е извършен: Няма cURL',
    'not run' => 'Не е извършен, с пропуснато време за стартиране',
    'ready' => 'Готов за изпълнение',
  ),
  'source_dom' => 
  array (
    '' => '',
    'Forum' => 'Форум',
    'InboundEmail' => 'Електронна поща',
    'Internal' => 'Вътрешен',
    'Web' => 'Уеб',
  ),
  'support_term_dom' => 
  array (
    '+1 year' => 'На една година',
    '+2 years' => 'На две години',
    '+6 months' => 'На шест месеца',
  ),
  'task_priority_dom' => 
  array (
    'High' => 'Висока',
    'Low' => 'Ниска',
    'Medium' => 'Средна',
  ),
  'task_status_dom' => 
  array (
    'Completed' => 'Приключена',
    'Deferred' => 'Отложена',
    'In Progress' => 'В процес на изпълнение',
    'Not Started' => 'Планирана',
    'Pending Input' => 'Висяща',
  ),
  'tax_class_dom' => 
  array (
    'Non-Taxable' => 'Необлагаем',
    'Taxable' => 'Облагаем',
  ),
  'tselect_type_dom' => 
  array (
    14440 => '4 часа',
    28800 => '8 часа',
    43200 => '12 часа',
    86400 => '1 ден',
    172800 => '2 дни',
    259200 => '3 дни',
    345600 => '4 дни',
    432000 => '5 дни',
    604800 => '1 седмица',
    1209600 => '2 седмици',
    1814400 => '3 седмици',
    2592000 => '30 дни',
    5184000 => '60 дни',
    7776000 => '90 дни',
    10368000 => '120 дни',
    12960000 => '150 дни',
    15552000 => '180 дни',
  ),
  'user_status_dom' => 
  array (
    'Active' => 'Активен',
    'Inactive' => 'Неактивен',
  ),
  'wflow_action_datetime_type_dom' => 
  array (
    'Existing Value' => 'Текуща стойност',
    'Triggered Date' => 'Стартова дата',
  ),
  'wflow_action_type_dom' => 
  array (
    'new' => 'Нов запис',
    'update' => 'Актуализиране на запис',
    'update_rel' => 'Актуализиране на свързани записи',
  ),
  'wflow_address_type_dom' => 
  array (
    'bcc' => 'Скрито копие до:',
    'cc' => 'Копие до:',
    'to' => 'До:',
  ),
  'wflow_address_type_invite_dom' => 
  array (
    'bcc' => 'Скрито копие до:',
    'cc' => 'Копие до:',
    'invite_only' => '(Invite Only)',
    'to' => 'До:',
  ),
  'wflow_address_type_to_only_dom' => 
  array (
    'to' => 'До:',
  ),
  'wflow_adv_enum_type_dom' => 
  array (
    'advance' => 'Преместване в падащото меню назад',
    'retreat' => 'Преместване в падащото меню напред',
  ),
  'wflow_adv_team_type_dom' => 
  array (
    'current_team' => 'Team of Logged-in User',
    'team_id' => 'Настоящият екип създаде запис',
  ),
  'wflow_adv_user_type_dom' => 
  array (
    'assigned_user_id' => 'User assigned to triggered record',
    'created_by' => 'User who created triggered record',
    'current_user' => 'Логване',
    'modified_user_id' => 'Модифицирано от',
  ),
  'wflow_alert_type_dom' => 
  array (
    'Email' => 'Електронна поща',
    'Invite' => 'Покана',
  ),
  'wflow_array_type_dom' => 
  array (
    'future' => 'Нова стойност',
    'past' => 'Предишна стойност',
  ),
  'wflow_fire_order_dom' => 
  array (
    'actions_alerts' => 'Действия с последващи известявания',
    'alerts_actions' => 'Известявание с последващи действия',
  ),
  'wflow_record_type_dom' => 
  array (
    'All' => 'Нови и съществуващи записи',
    'New' => 'Само нови записи',
    'Update' => 'Само съществуващи записи',
  ),
  'wflow_rel_type_dom' => 
  array (
    'all' => 'Всички са свързани',
    'filter' => 'Филтър на свързване',
  ),
  'wflow_relate_type_dom' => 
  array (
    'Manager' => 'Потребители',
    'Self' => 'Потребител',
  ),
  'wflow_relfilter_type_dom' => 
  array (
    'all' => 'всички свързани с',
    'any' => 'всички свързани с',
  ),
  'wflow_set_type_dom' => 
  array (
    'Advanced' => 'Допълнителни настройки',
    'Basic' => 'Базови настройки',
  ),
  'wflow_source_type_dom' => 
  array (
    'Custom Template' => 'Потребителски шаблон',
    'Normal Message' => 'Стандартно съобщение',
    'System Default' => 'По подразбиране',
  ),
  'wflow_type_dom' => 
  array (
    'Normal' => 'При запазване на записи',
    'Time' => 'След изтичане на срока',
  ),
  'wflow_user_type_dom' => 
  array (
    'current_user' => 'Текущи потребители',
    'rel_user' => 'Свързани потребители',
    'rel_user_custom' => 'Свързани потребители',
    'specific_role' => 'Определена роля',
    'specific_team' => 'Определен екип',
    'specific_user' => 'Определен потребител',
  ),
);

$app_strings = array (
  'ERROR_FULLY_EXPIRED' => 'Лицензът Ви изтича в рамките на 30 дни и следва да се поднови . Достъпът е разрешен единствено за администратора.',
  'ERROR_LICENSE_EXPIRED' => 'Лицензът Ви следва да се актуализира. Достъпът е разрешен единствено за администратора',
  'ERROR_NO_RECORD' => 'Грешка при опит за достъп до записа.  Може да е изтрит или не разполагате с необходимите права за достъп до записа.',
  'ERR_CREATING_FIELDS' => 'Грешка при попълване на допълнителна информация в полетата: ',
  'ERR_CREATING_TABLE' => 'Грешка при създаване на таблица: ',
  'ERR_DELETE_RECORD' => 'Трябва да въведете номер на записа, за да изтриете този контакт.',
  'ERR_EXPORT_DISABLED' => 'Експортирането на данни е забранено.',
  'ERR_EXPORT_TYPE' => 'Грешка при експортиране ',
  'ERR_INVALID_AMOUNT' => 'Моля, въведете валидна сума.',
  'ERR_INVALID_DATE' => 'Моля, въведете валидна дата.',
  'ERR_INVALID_DATE_FORMAT' => 'Форматът на датата трябва да е: ',
  'ERR_INVALID_DAY' => 'Моля, въведете валиден ден.',
  'ERR_INVALID_EMAIL_ADDRESS' => 'невалиден адрес за електронна поща.',
  'ERR_INVALID_HOUR' => 'Моля, въведете валиден час.',
  'ERR_INVALID_MONTH' => 'Моля, въведете валиден месец.',
  'ERR_INVALID_REQUIRED_FIELDS' => 'Липсва задължително поле:',
  'ERR_INVALID_TIME' => 'Моля, въведете валиден час.',
  'ERR_INVALID_VALUE' => 'Невалидна стойност:',
  'ERR_INVALID_YEAR' => 'Моля, въведете валидна година във формат ГГГГ.',
  'ERR_MISSING_REQUIRED_FIELDS' => 'Липсва задължително поле:',
  'ERR_NEED_ACTIVE_SESSION' => 'Необходимо е активна сесия, за да бъде експортирано съдържанието.',
  'ERR_NOTHING_SELECTED' => 'Моля, маркирайте преди да продължите.',
  'ERR_OPPORTUNITY_NAME_DUPE' => 'Сделка с име %s вече съществува.  Моля, въведете име различно от вече съществуващото.',
  'ERR_OPPORTUNITY_NAME_MISSING' => 'Не е въведено име на сделката.',
  'ERR_PORTAL_LOGIN_FAILED' => 'Не може да се създаде портал потребителска сесия. Моля свържете се с администратора.',
  'ERR_RESOURCE_MANAGEMENT_INFO' => 'Върнете се към <a href="index.php">Началната страница</a>',
  'ERR_SELF_REPORTING' => 'Потребителят не може да докладва сам на себе си.',
  'ERR_SQS_NO_MATCH' => 'Няма съвпадения',
  'ERR_SQS_NO_MATCH_FIELD' => 'Няма съвпадения за полето: ',
  'LBL_ACCOUNT' => 'Организация',
  'LBL_ACCOUNTS' => 'Организации',
  'LBL_ACCUMULATED_HISTORY_BUTTON_KEY' => 'H',
  'LBL_ACCUMULATED_HISTORY_BUTTON_LABEL' => 'Резюме',
  'LBL_ACCUMULATED_HISTORY_BUTTON_TITLE' => 'Резюме [Alt+H]',
  'LBL_ADDITIONAL_DETAILS' => 'Допълнителни детайли',
  'LBL_ADDITIONAL_DETAILS_CLOSE' => 'Затвори',
  'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE' => 'Натиснете за затваряне',
  'LBL_ADD_BUTTON' => 'Добави',
  'LBL_ADD_BUTTON_KEY' => 'A',
  'LBL_ADD_BUTTON_TITLE' => 'Добави [Alt+A]',
  'LBL_ADD_DOCUMENT' => 'Добавяне на документ',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY' => 'L',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL' => 'Добави към целева група',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_TITLE' => 'Добави към целева група',
  'LBL_ADMIN' => 'Администриране',
  'LBL_ALT_HOT_KEY' => 'Alt+',
  'LBL_ARCHIVE' => 'Архив',
  'LBL_ASSIGNED_TO' => 'Отговорник:',
  'LBL_ASSIGNED_TO_USER' => 'Отговорник',
  'LBL_BACK' => 'Върни',
  'LBL_BILL_TO_ACCOUNT' => 'Сметка на Организацията',
  'LBL_BILL_TO_CONTACT' => 'Сметка на Контакта',
  'LBL_BROWSER_TITLE' => 'SugarCRM - Commercial Open Source CRM',
  'LBL_BUGS' => 'Проблеми',
  'LBL_BY' => 'by',
  'LBL_CALLS' => 'Обаждания',
  'LBL_CAMPAIGNS_SEND_QUEUED' => 'Изпрати електронните съобщения от опашката',
  'LBL_CANCEL_BUTTON_KEY' => 'X',
  'LBL_CANCEL_BUTTON_LABEL' => 'Отмени',
  'LBL_CANCEL_BUTTON_TITLE' => 'Отмени [Alt+X]',
  'LBL_CASE' => 'Казус',
  'LBL_CASES' => 'Казуси',
  'LBL_CHANGE_BUTTON_KEY' => 'G',
  'LBL_CHANGE_BUTTON_LABEL' => 'Промени',
  'LBL_CHANGE_BUTTON_TITLE' => 'Промени [Alt+G]',
  'LBL_CHANGE_PASSWORD' => 'Смяна на парола',
  'LBL_CHARSET' => 'UTF-8',
  'LBL_CHECKALL' => 'Чекирайте всички
',
  'LBL_CLEARALL' => 'Изчисти всички полета',
  'LBL_CLEAR_BUTTON_KEY' => 'C',
  'LBL_CLEAR_BUTTON_LABEL' => 'Изчисти',
  'LBL_CLEAR_BUTTON_TITLE' => 'Изчисти [Alt+C]',
  'LBL_CLOSEALL_BUTTON_KEY' => 'Q',
  'LBL_CLOSEALL_BUTTON_LABEL' => 'Затвори всичко',
  'LBL_CLOSEALL_BUTTON_TITLE' => 'Затвори всичко [Alt+I]',
  'LBL_CLOSE_WINDOW' => 'Затвори прозореца',
  'LBL_COMPOSE_EMAIL_BUTTON_KEY' => 'L',
  'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => 'Ново писмо',
  'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => 'Напиши писмо [Alt+L]',
  'LBL_CONTACT' => 'Контакт',
  'LBL_CONTACTS' => 'Контакти',
  'LBL_CONTACT_LIST' => 'Списък с контакти:',
  'LBL_CREATED' => 'Създадено от',
  'LBL_CREATED_BY_USER' => 'Създадено от потребител',
  'LBL_CREATE_BUTTON_LABEL' => 'Създай',
  'LBL_CURRENT_USER_FILTER' => 'Моите записи:',
  'LBL_DATE_ENTERED' => 'Създадено на:',
  'LBL_DATE_MODIFIED' => 'Последно модифицирано:',
  'LBL_DELETE' => 'Изтриване',
  'LBL_DELETED' => 'Изтрити',
  'LBL_DELETE_BUTTON' => 'Изтриване',
  'LBL_DELETE_BUTTON_KEY' => 'D',
  'LBL_DELETE_BUTTON_LABEL' => 'Изтриване',
  'LBL_DELETE_BUTTON_TITLE' => 'Изтрий [Alt+D]',
  'LBL_DIRECT_REPORTS' => 'Докладва на',
  'LBL_DISPLAY_COLUMNS' => 'Показвани колони',
  'LBL_DONE_BUTTON_KEY' => 'X',
  'LBL_DONE_BUTTON_LABEL' => 'Добави',
  'LBL_DONE_BUTTON_TITLE' => 'Съставете [Alt+X]',
  'LBL_DST_NEEDS_FIXIN' => 'Приложението изисква лятното часово време да се приложи. Моля, посетете раздел <a href="index.php?module=Administration&action=DstFix">Системна поддръжка</a> от секция Администриране и изберете Daylight Saving Time fix.',
  'LBL_DUPLICATE_BUTTON' => 'Дублирай',
  'LBL_DUPLICATE_BUTTON_KEY' => 'U',
  'LBL_DUPLICATE_BUTTON_LABEL' => 'Дублирай',
  'LBL_DUPLICATE_BUTTON_TITLE' => 'Дублирай [Alt+U]',
  'LBL_DUP_MERGE' => 'Търсене на дублирани записи',
  'LBL_EDIT_BUTTON' => 'Редактиране',
  'LBL_EDIT_BUTTON_KEY' => 'E',
  'LBL_EDIT_BUTTON_LABEL' => 'Редактиране',
  'LBL_EDIT_BUTTON_TITLE' => 'Редактирай [Alt+E]',
  'LBL_EMAILS' => 'Електронна поща',
  'LBL_EMAIL_PDF_BUTTON_KEY' => 'M',
  'LBL_EMAIL_PDF_BUTTON_LABEL' => 'Изпрати в PDF формат',
  'LBL_EMAIL_PDF_BUTTON_TITLE' => 'Изпрати в PDF формат [Alt+M]',
  'LBL_EMPLOYEES' => 'Служители',
  'LBL_ENTER_DATE' => 'Въведи дата',
  'LBL_EXPORT' => 'Експортиране',
  'LBL_EXPORT_ALL' => 'Експортирай всичко',
  'LBL_FULL_FORM_BUTTON_KEY' => 'F',
  'LBL_FULL_FORM_BUTTON_LABEL' => 'Разширен шаблон',
  'LBL_FULL_FORM_BUTTON_TITLE' => 'Разширен шаблон [Alt+F]',
  'LBL_HIDE' => 'Скрий',
  'LBL_HIDE_COLUMNS' => 'Скрити колони',
  'LBL_ID' => 'ИД',
  'LBL_IMPORT' => 'Импортиране',
  'LBL_IMPORT_PROSPECTS' => 'Импортиране на записи с целеви клиенти',
  'LBL_LAST_VIEWED' => 'Разгледани записи',
  'LBL_LEADS' => 'Потенциални клиенти',
  'LBL_LISTVIEW_MASS_UPDATE_CONFIRM' => 'Сигурни ли сте, че искате да актуализирате целия списък?',
  'LBL_LISTVIEW_NO_SELECTED' => 'Необходимо е да маркирате поне 1 запис, за да продължите.',
  'LBL_LISTVIEW_OPTION_CURRENT' => 'Текуща страница',
  'LBL_LISTVIEW_OPTION_ENTIRE' => 'Цял списък',
  'LBL_LISTVIEW_OPTION_SELECTED' => 'Маркираните записи',
  'LBL_LISTVIEW_SELECTED_OBJECTS' => 'Маркирани: ',
  'LBL_LIST_ACCOUNT_NAME' => 'Oрганизация',
  'LBL_LIST_ASSIGNED_USER' => 'Потребител',
  'LBL_LIST_CONTACT_NAME' => 'Контакт',
  'LBL_LIST_CONTACT_ROLE' => 'Роля',
  'LBL_LIST_EMAIL' => 'Електронна поща',
  'LBL_LIST_NAME' => 'Име',
  'LBL_LIST_OF' => 'of',
  'LBL_LIST_PHONE' => 'Телефон',
  'LBL_LIST_TEAM' => 'Екип',
  'LBL_LIST_USER_NAME' => 'Потребител',
  'LBL_LOADING' => 'Зарежда се ...',
  'LBL_LOCALE_NAME_EXAMPLE_FIRST' => 'Георги',
  'LBL_LOCALE_NAME_EXAMPLE_LAST' => 'Иванов',
  'LBL_LOCALE_NAME_EXAMPLE_SALUTATION' => 'Г-н',
  'LBL_LOGIN_SESSION_EXCEEDED' => 'Сървърът е зает. Моля опитайте по-късно.',
  'LBL_LOGIN_TO_ACCESS' => 'Моля, авторизирайте се, за достъп до тези записи.',
  'LBL_LOGOUT' => 'Изход',
  'LBL_MAILMERGE' => 'Сливане на писма',
  'LBL_MAILMERGE_KEY' => 'M',
  'LBL_MASS_UPDATE' => 'Масова актуализация',
  'LBL_MEETINGS' => 'Срещи',
  'LBL_MEMBERS' => 'Членове',
  'LBL_MODIFIED' => 'Модифицирано от',
  'LBL_MODIFIED_BY_USER' => 'Модифицирано от потребител',
  'LBL_MY_ACCOUNT' => 'Персонални настройки',
  'LBL_NAME' => 'Име',
  'LBL_NEW_BUTTON_KEY' => 'N',
  'LBL_NEW_BUTTON_LABEL' => 'Създай',
  'LBL_NEW_BUTTON_TITLE' => 'Създай [Alt+N]',
  'LBL_NEXT_BUTTON_LABEL' => 'Следваща',
  'LBL_NONE' => '--Няма данни--',
  'LBL_NOTES' => 'Бележки',
  'LBL_NO_RECORDS_FOUND' => '0 намерени записа',
  'LBL_OPENALL_BUTTON_KEY' => 'O',
  'LBL_OPENALL_BUTTON_LABEL' => 'Отворете всички',
  'LBL_OPENALL_BUTTON_TITLE' => 'Отворете всички [Alt+O]',
  'LBL_OPENTO_BUTTON_KEY' => 'T',
  'LBL_OPENTO_BUTTON_LABEL' => 'Отворено за: ',
  'LBL_OPENTO_BUTTON_TITLE' => 'Отворено за: [Alt+T]',
  'LBL_OPPORTUNITIES' => 'Възможности',
  'LBL_OPPORTUNITY' => 'Свързан с възможност:',
  'LBL_OPPORTUNITY_NAME' => 'Сделка',
  'LBL_OR' => 'ИЛИ',
  'LBL_PERCENTAGE_SYMBOL' => '%',
  'LBL_PRODUCTS' => 'Продукти',
  'LBL_PRODUCT_BUNDLES' => 'Продуктов асортимент',
  'LBL_PROJECTS' => 'Проекти',
  'LBL_PROJECT_TASKS' => 'Задачи по проекта',
  'LBL_QUOTES' => 'Оферти',
  'LBL_QUOTES_SHIP_TO' => 'Quotes Ship to',
  'LBL_QUOTE_TO_OPPORTUNITY_KEY' => 'O',
  'LBL_QUOTE_TO_OPPORTUNITY_LABEL' => 'Създай сделка',
  'LBL_QUOTE_TO_OPPORTUNITY_TITLE' => 'Създай сделка [Alt+O]',
  'LBL_RELATED_RECORDS' => 'Свързани записи',
  'LBL_REMOVE' => 'Изтрий',
  'LBL_REQUIRED_SYMBOL' => '*',
  'LBL_SAVED' => 'Запазено',
  'LBL_SAVED_LAYOUT' => 'Подредбата е запазена.',
  'LBL_SAVED_VIEWS' => 'Saved Views',
  'LBL_SAVE_BUTTON_KEY' => 'S',
  'LBL_SAVE_BUTTON_LABEL' => 'Съхрани',
  'LBL_SAVE_BUTTON_TITLE' => 'Съхрани [Alt+S]',
  'LBL_SAVE_NEW_BUTTON_KEY' => 'V',
  'LBL_SAVE_NEW_BUTTON_LABEL' => 'Съхрани и създай нов запис',
  'LBL_SAVE_NEW_BUTTON_TITLE' => 'Съхрани и създай нов запис [Alt+V]',
  'LBL_SAVING' => 'Запазване',
  'LBL_SAVING_LAYOUT' => 'Запазване на подредбата ...',
  'LBL_SEARCH' => 'Търси',
  'LBL_SEARCH_BUTTON_KEY' => 'Q',
  'LBL_SEARCH_BUTTON_LABEL' => 'Търси',
  'LBL_SEARCH_BUTTON_TITLE' => 'Търси [Alt+Q]',
  'LBL_SEARCH_CRITERIA' => 'Съхранени критерии',
  'LBL_SELECT_BUTTON_KEY' => 'T',
  'LBL_SELECT_BUTTON_LABEL' => 'Избери',
  'LBL_SELECT_BUTTON_TITLE' => 'Избери [Alt+T]',
  'LBL_SELECT_CONTACT_BUTTON_KEY' => 'T',
  'LBL_SELECT_CONTACT_BUTTON_LABEL' => 'Избери контакт',
  'LBL_SELECT_CONTACT_BUTTON_TITLE' => 'Избери контакт [Alt+T]',
  'LBL_SELECT_REPORTS_BUTTON_LABEL' => 'Избери със справка',
  'LBL_SELECT_REPORTS_BUTTON_TITLE' => 'Избери със справка',
  'LBL_SELECT_USER_BUTTON_KEY' => 'U',
  'LBL_SELECT_USER_BUTTON_LABEL' => 'Избери потребител',
  'LBL_SELECT_USER_BUTTON_TITLE' => 'Избери потребител [Alt+U]',
  'LBL_SERVER_RESPONSE_RESOURCES' => 'Ресурси, използвани за създаването на текущата страница (заявки, файлове)',
  'LBL_SERVER_RESPONSE_TIME' => 'Време за изпълнение на заявката:',
  'LBL_SERVER_RESPONSE_TIME_SECONDS' => 'секунди.',
  'LBL_SHIP_TO_ACCOUNT' => 'Ship to Account
',
  'LBL_SHIP_TO_CONTACT' => 'Ship to Contact
',
  'LBL_SHORTCUTS' => 'Меню',
  'LBL_SHOW' => 'Покажи',
  'LBL_SQS_INDICATOR' => '',
  'LBL_STATUS' => 'Статус:',
  'LBL_STATUS_UPDATED' => 'Статусът Ви за това събитие бе актуализиран!',
  'LBL_SUBJECT' => 'Относно',
  'LBL_SYNC' => 'Синхронизация',
  'LBL_TASKS' => 'Задачи',
  'LBL_TEAM' => 'Екип:',
  'LBL_TEAMS_LINK' => 'Екип',
  'LBL_TEAM_ID' => 'Екип:',
  'LBL_THOUSANDS_SYMBOL' => 'хил.',
  'LBL_TRACK_EMAIL_BUTTON_KEY' => 'хил.',
  'LBL_TRACK_EMAIL_BUTTON_LABEL' => 'Създаване на запис за изпратена поща',
  'LBL_TRACK_EMAIL_BUTTON_TITLE' => 'Създаване на запис за изпратена електронна поща [Alt+K]',
  'LBL_UNAUTH_ADMIN' => 'Неправомерен достъп до административен раздел',
  'LBL_UNDELETE' => 'Възстанови',
  'LBL_UNDELETE_BUTTON' => 'Възстанови',
  'LBL_UNDELETE_BUTTON_LABEL' => 'Възстанови',
  'LBL_UNDELETE_BUTTON_TITLE' => 'Възстанови [Alt+D]',
  'LBL_UNSYNC' => 'Синхронизирай',
  'LBL_UPDATE' => 'Актуализирай',
  'LBL_USERS' => 'Потребители',
  'LBL_USERS_SYNC' => 'Синхронизация на профилите',
  'LBL_USER_LIST' => 'Списък с потребители',
  'LBL_VIEW_BUTTON' => 'Разгледай',
  'LBL_VIEW_BUTTON_KEY' => 'V',
  'LBL_VIEW_BUTTON_LABEL' => 'Разгледай',
  'LBL_VIEW_BUTTON_TITLE' => 'Разгледай [Alt+V]',
  'LBL_VIEW_PDF_BUTTON_KEY' => 'P',
  'LBL_VIEW_PDF_BUTTON_LABEL' => 'Принтирай в PDF',
  'LBL_VIEW_PDF_BUTTON_TITLE' => 'Принтирай в PDF [Alt+P]',
  'LNK_ABOUT' => 'За програмата',
  'LNK_ADVANCED_SEARCH' => 'Разширено търсене',
  'LNK_BASIC_SEARCH' => 'Основно',
  'LNK_DELETE' => 'Изтрий',
  'LNK_DELETE_ALL' => 'Изтрий всички',
  'LNK_EDIT' => 'редактирай',
  'LNK_GET_LATEST' => 'Добави на-новите',
  'LNK_GET_LATEST_TOOLTIP' => 'Обнови с последна версия',
  'LNK_HELP' => 'Помощ',
  'LNK_LIST_END' => 'Край',
  'LNK_LIST_NEXT' => 'Следваща',
  'LNK_LIST_PREVIOUS' => 'Предишна',
  'LNK_LIST_RETURN' => 'Връщане към списъка',
  'LNK_LIST_START' => 'Начало',
  'LNK_LOAD_SIGNED' => 'Подпис',
  'LNK_LOAD_SIGNED_TOOLTIP' => 'Замени с подписан документ',
  'LNK_PRINT' => 'Печат',
  'LNK_REMOVE' => 'изтрий',
  'LNK_RESUME' => 'Резюме',
  'LNK_VIEW_CHANGE_LOG' => 'Разгледай направените промени',
  'LOGIN_LOGO_ERROR' => 'Моля, изтрийте логото на SugarCRM.',
  'NTC_CLICK_BACK' => 'Натиснете бутонът за предишен екран в браузъра и отстранете проблема.',
  'NTC_DATE_FORMAT' => '(гггг-мм-дд)',
  'NTC_DATE_TIME_FORMAT' => '(гггг-мм-дд 24:00)',
  'NTC_DELETE_CONFIRMATION' => 'Сигурни ли сте, че желаете да изтриете този запис?',
  'NTC_DELETE_CONFIRMATION_MULTIPLE' => 'Сигурни ли сте, че искате да изтриете избраните записи?',
  'NTC_LOGIN_MESSAGE' => 'Моля, въведете потребителско име и парола.',
  'NTC_NO_ITEMS_DISPLAY' => 'няма',
  'NTC_REMOVE_CONFIRMATION' => 'Сигурни ли сте, че искате да изтриете тази връзка?',
  'NTC_REQUIRED' => 'Задължителни полета ',
  'NTC_SUPPORT_SUGARCRM' => 'Support the SugarCRM open source project with a donation through PayPal - it\'s fast, free and secure!',
  'NTC_TIME_FORMAT' => '(24:00)',
  'NTC_WELCOME' => 'Потребител:',
  'NTC_YEAR_FORMAT' => '(гггг)',
);

