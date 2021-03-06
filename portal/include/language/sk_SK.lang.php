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
    'Analyst' => 'Analytik',
    'Competitor' => 'Konkurent',
    'Customer' => 'Zákazník',
    'Integrator' => 'Integrátor',
    'Investor' => 'Investor',
    'Other' => 'Iné',
    'Partner' => 'Partner',
    'Press' => 'Tlač',
    'Prospect' => 'Prospekt',
    'Reseller' => 'Predajca',
  ),
  'activity_dom' => 
  array (
    'Call' => 'Volanie',
    'Email' => 'Email',
    'Meeting' => 'Schôdzka',
    'Note' => 'Poznámka',
    'Task' => 'Úloha',
  ),
  'bopselect_type_dom' => 
  array (
    'Equals' => 'Sa rovná',
  ),
  'bselect_type_dom' => 
  array (
    'bool_false' => 'Nie',
    'bool_true' => 'Áno',
  ),
  'bug_priority_dom' => 
  array (
    'High' => 'Vysoko',
    'Low' => 'Nízko',
    'Medium' => 'Stredne',
    'Urgent' => 'Urgentné',
  ),
  'bug_resolution_dom' => 
  array (
    '' => '',
    'Accepted' => 'Akceptovaný',
    'Duplicate' => 'Duplikovať',
    'Fixed' => 'Pevný',
    'Invalid' => 'Neplatný',
    'Later' => 'Neskôr',
    'Out of Date' => 'Zastaraný',
  ),
  'bug_status_dom' => 
  array (
    'Assigned' => 'Pridelený',
    'Closed' => 'Zatvorený',
    'New' => 'Nový',
    'Pending' => 'Prebieha',
    'Rejected' => 'Zamietnutý',
  ),
  'bug_type_dom' => 
  array (
    'Defect' => 'Vada',
    'Feature' => 'Vlastnosť',
  ),
  'call_direction_dom' => 
  array (
    'Inbound' => 'Prichádzajúci',
    'Outbound' => 'Odchádzajúci',
  ),
  'call_status_dom' => 
  array (
    'Held' => 'Pozdržaný',
    'Not Held' => 'Nepozdržaný',
    'Planned' => 'Plánované',
  ),
  'campaign_status_dom' => 
  array (
    '' => '',
    'Active' => 'Aktívny',
    'Complete' => 'Kompletné',
    'In Queue' => 'V poradí',
    'Inactive' => 'Neaktívny',
    'Planning' => 'Plánovanie',
    'Sending' => 'Posiela',
  ),
  'campaign_type_dom' => 
  array (
    '' => '',
    'Email' => 'Email',
    'Mail' => 'Pošta',
    'Print' => 'Tlač',
    'Radio' => 'Rádio',
    'Telesales' => 'Tele-obchody',
    'Television' => 'Televízia',
    'Web' => 'Web',
  ),
  'campainglog_activity_type_dom' => 
  array (
    '' => '',
    'contact' => 'Kontakt vytvorený',
    'invalid email' => 'Vrátené správy, nesprávny email',
    'lead' => 'Vytvorené Príležitosti',
    'link' => 'Kliknúť na link',
    'removed' => 'Odhlásené',
    'send error' => 'Vrátené správy, Iné',
    'targeted' => 'Správy odoslané/pokus',
    'viewed' => 'Prezreté správy',
  ),
  'campainglog_target_type_dom' => 
  array (
    'Contacts' => 'Kontakty',
    'Leads' => 'Príležitosti',
    'Prospects' => 'Ciele',
    'Users' => 'Používatelia',
  ),
  'case_priority_dom' => 
  array (
    'P1' => 'Vysoko',
    'P2' => 'Stredne',
    'P3' => 'Nízko',
  ),
  'case_relationship_type_dom' => 
  array (
    '' => '',
    'Alternate Contact' => 'Alternatívny kontakt',
    'Primary Contact' => 'Primárny kontakt',
  ),
  'case_status_dom' => 
  array (
    'Assigned' => 'Pridelený',
    'Closed' => 'Uzatvorený',
    'Duplicate' => 'Duplikovať',
    'New' => 'Nový',
    'Pending Input' => 'Čakajúci na vstup',
    'Rejected' => 'Zamietnutý',
  ),
  'checkbox_dom' => 
  array (
    '' => '',
    1 => 'Áno',
    2 => 'Nie',
  ),
  'contract_expiration_notice_dom' => 
  array (
    1 => '1 deň',
    3 => '3 dni',
    5 => '5 dní',
    7 => '1 týždeň',
    14 => '2 týždne',
    21 => '3 týždne',
    31 => '1 mesiac',
  ),
  'contract_payment_frequency_dom' => 
  array (
    'halfyearly' => 'Polročne',
    'monthly' => 'Mesačne',
    'quarterly' => 'Štvrťročne',
    'yearly' => 'Ročne',
  ),
  'contract_status_dom' => 
  array (
    'inprogress' => 'V činnosti',
    'notstarted' => 'Nezačatý',
    'signed' => 'Podpísaný',
  ),
  'cselect_type_dom' => 
  array (
    'Does not Equal' => 'Sa nerovná',
    'Equals' => 'Sa rovná',
  ),
  'document_category_dom' => 
  array (
    '' => '',
    'Knowledege Base' => 'Báza znalostí',
    'Marketing' => 'Marketing',
    'Sales' => 'Predaje',
  ),
  'document_status_dom' => 
  array (
    'Active' => 'Aktívny',
    'Draft' => 'Draft',
    'Expired' => 'Uplynutý',
    'FAQ' => 'FAQ',
    'Pending' => 'Prebieha',
    'Under Review' => 'Kontrolovaný',
  ),
  'document_subcategory_dom' => 
  array (
    '' => '',
    'FAQ' => 'FAQ',
    'Marketing Collateral' => 'Marketingová záruka',
    'Product Brochures' => 'Produktové brožúry',
  ),
  'document_template_type_dom' => 
  array (
    '' => '',
    'eula' => 'EULA',
    'license' => 'Licenčná zmluva',
    'mailmerge' => 'Zlúčenie emailov',
    'nda' => 'NDA',
  ),
  'dom_cal_month_long' => 
  array (
    1 => 'Január',
    2 => 'Február',
    3 => 'Marec',
    4 => 'Apríl',
    5 => 'Máj',
    6 => 'Jún',
    7 => 'Júl',
    8 => 'August',
    9 => 'September',
    10 => 'Október',
    11 => 'November',
    12 => 'December',
  ),
  'dom_email_bool' => 
  array (
    'bool_false' => 'Nie',
    'bool_true' => 'Áno',
  ),
  'dom_email_distribution' => 
  array (
    '' => '--nič--',
    'direct' => 'Priame pridelenie',
    'leastBusy' => 'Najmenej-zaneprázdnený',
    'roundRobin' => 'Round-Robin',
  ),
  'dom_email_editor_option' => 
  array (
    '' => 'Predvolený emailový formát',
    'html' => 'HTML',
    'plain' => 'Čistý emailový text',
  ),
  'dom_email_errors' => 
  array (
    1 => 'Vyberte len jedného používateľa pri priamom zadávaní položiek.',
    2 => 'Musíte zadávať len zaškrtnuté položky pri priamom zadávaní.',
  ),
  'dom_email_link_type' => 
  array (
    '' => 'Systémovo predvolený poštový klient',
    'mailto' => 'Externý poštový klient',
    'sugar' => 'SugarCRM poštový klient',
  ),
  'dom_email_server_type' => 
  array (
    '' => '--nič--',
    'imap' => 'IMAP',
    'pop3' => 'POP3',
  ),
  'dom_email_status' => 
  array (
    'archived' => 'Archivované',
    'closed' => 'Uzatvorený',
    'draft' => 'V Koncepte',
    'read' => 'Čítať',
    'replied' => 'Odpovedané',
    'send_error' => 'Chyba odoslania',
    'sent' => 'Odoslané',
    'unread' => 'Neprečítané',
  ),
  'dom_email_types' => 
  array (
    'archived' => 'Archivované',
    'draft' => 'Draft',
    'inbound' => 'Prichádzajúci',
    'out' => 'Odoslané',
  ),
  'dom_int_bool' => 
  array (
    1 => 'Áno',
  ),
  'dom_mailbox_type' => 
  array (
    'bounce' => 'Pružné zaobchádzanie',
    'bug' => 'Vytvoriť Chybu',
    'contact' => 'Vytvoriť Kontakt',
    'pick' => 'Vytvoriť [akýkoľvek]',
    'sales' => 'Vytvoriť Príležitosť',
    'support' => 'Vytvoriť Prípad',
    'task' => 'Vytvoriť úlohu',
  ),
  'dom_meeting_accept_options' => 
  array (
    'accept' => 'Akceptovaný',
    'decline' => 'Zamietnutý',
    'tentative' => 'Predbežný',
  ),
  'dom_meeting_accept_status' => 
  array (
    'accept' => 'Akceptovaný',
    'decline' => 'Zamietnutý',
    'none' => 'Nič',
    'tentative' => 'Predbežný',
  ),
  'dom_report_types' => 
  array (
    'detailed_summary' => 'Detailné zhrnutie',
    'summary' => 'Zhrnutie',
    'tabular' => 'Riadky a stĺpce',
  ),
  'dom_switch_bool' => 
  array (
    '' => '--nič--',
    'off' => 'Nie',
    'on' => 'Áno',
  ),
  'dom_timezones' => 
  array (
    -12 => '(GMT - 12) Medzinárodná dátová hranica Západ',
    -11 => '(GMT - 11) Midway Island, Samoa',
    -10 => '(GMT - 10) Hawaii',
    -9 => '(GMT - 9) Aliaška',
    -8 => '(GMT - 8) San Francisco',
    -7 => '(GMT - 7) Phoenix',
    -6 => '(GMT - 6) Saskatchewan',
    -5 => '(GMT - 5) New York',
    -4 => '(GMT - 4) Santiago',
    -3 => '(GMT - 3) Buenos Aires',
    -2 => '(GMT - 2) Mid-Atlantic',
    -1 => '(GMT - 1) Azory',
    1 => '(GMT + 1) Madrid',
    2 => '(GMT + 2) Atény',
    3 => '(GMT + 3) Moskva',
    4 => '(GMT + 4) Kábul',
    5 => '(GMT + 5) Jekaterinburg',
    6 => '(GMT + 6) Astana',
    7 => '(GMT + 7) Bangkok',
    8 => '(GMT + 8) Perth',
    9 => '(GMT + 9) Seol',
    10 => '(GMT + 10) Brisbane',
    11 => '(GMT + 11) Šalamúnove ostrovy',
    12 => '(GMT + 12) Auckland',
  ),
  'dom_timezones_extra' => 
  array (
    -12 => '(GMT-12) Medzinárodná dátova čiara Západ',
    -11 => '(GMT-11) Midway Island, Samoa',
    -10 => '(GMT-10) Hawaii',
    -9 => '(GMT-9) Aliaška',
    -8 => '(GMT-8) (PST)',
    -7 => '(GMT-7) (MST)',
    -6 => '(GMT-6) (CST)',
    -5 => '(GMT-5) (EST)',
    -4 => '(GMT-4) Santiago',
    -3 => '(GMT-3) Buenos Aires',
    -2 => '(GMT-2) Mid-Atlantic',
    -1 => '(GMT-1) Azory',
    1 => '(GMT+1) Madrid',
    2 => '(GMT+2) Atény',
    3 => '(GMT+3) Moskva',
    4 => '(GMT+4) Kábul',
    5 => '(GMT+5) Jekaterinburg',
    6 => '(GMT+6) Astana',
    7 => '(GMT+7) Bangkok',
    8 => '(GMT+8) Perth',
    9 => '(GMT+9) Seol',
    10 => '(GMT+10) Brisbane',
    11 => '(GMT+11) Šalamúnové ostrovy',
    12 => '(GMT+12) Auckland',
  ),
  'dselect_type_dom' => 
  array (
    'Does not Equal' => 'Sa nerovná',
    'Equals' => 'Sa rovná',
    'Less Than' => 'Je menej ako',
    'More Than' => 'Je viac ako',
  ),
  'dtselect_type_dom' => 
  array (
    'Less Than' => 'je menej ako',
    'More Than' => 'bol viac ako',
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
    'active' => 'Aktívny',
    'inactive' => 'Neaktívny',
  ),
  'employee_status_dom' => 
  array (
    'Active' => 'Aktívny',
    'Leave of Absence' => 'Voľno',
    'Terminated' => 'Ukončený',
  ),
  'forecast_schedule_status_dom' => 
  array (
    'Active' => 'Aktívny',
    'Inactive' => 'Neaktívny',
  ),
  'forecast_type_dom' => 
  array (
    'Direct' => 'Priamy',
    'Rollup' => 'Kumulatívny',
  ),
  'industry_dom' => 
  array (
    '' => '',
    'Apparel' => 'Oblečenie',
    'Banking' => 'Bankovníctvo',
    'Biotechnology' => 'Biotechnológia',
    'Chemicals' => 'Chemikálie',
    'Communications' => 'Komunikácie',
    'Construction' => 'Stavba',
    'Consulting' => 'Poradenstvo',
    'Education' => 'Vzdelávanie',
    'Electronics' => 'Elektronika',
    'Energy' => 'Energie',
    'Engineering' => 'Inžinierstvo',
    'Entertainment' => 'Zábava',
    'Environmental' => 'Prostredie',
    'Finance' => 'Financie',
    'Government' => 'Vláda',
    'Healthcare' => 'Zdravotníctvo',
    'Hospitality' => 'Pohostinstvo',
    'Insurance' => 'Poistenie',
    'Machinery' => 'Mechanizmus',
    'Manufacturing' => 'Výroba',
    'Media' => 'Média',
    'Not For Profit' => 'Neziskové',
    'Other' => 'Iné',
    'Recreation' => 'Rekreácia',
    'Retail' => 'Maloobchod',
    'Shipping' => 'Doprava',
    'Technology' => 'Technológia',
    'Telecommunications' => 'Telekomunikácie',
    'Transportation' => 'Preprava',
    'Utilities' => 'Poplatky',
  ),
  'language_pack_name' => 'US English',
  'layouts_dom' => 
  array (
    'Invoice' => 'Faktúra',
    'Standard' => 'Návrh',
    'Terms' => 'Platobné podmienky',
  ),
  'lead_source_dom' => 
  array (
    '' => '',
    'Cold Call' => 'Predajný telefonát',
    'Conference' => 'Konferencia',
    'Direct Mail' => 'Priama pošta',
    'Email' => 'Email',
    'Employee' => 'Zamestnanec',
    'Existing Customer' => 'Existujúci zákazník',
    'Other' => 'Iné',
    'Partner' => 'Partner',
    'Public Relations' => 'verejné vzťahy',
    'Self Generated' => 'Samo vytvorené',
    'Trade Show' => 'Veľtrh',
    'Web Site' => 'Webstránka',
    'Word of mouth' => 'Hovorené slovo',
  ),
  'lead_status_dom' => 
  array (
    '' => '',
    'Assigned' => 'Pridelené',
    'Converted' => 'Konvertovaný',
    'Dead' => 'Ukončený',
    'In Process' => 'V priebehu',
    'New' => 'Nový',
    'Recycled' => 'Recyklovaný',
  ),
  'lead_status_noblank_dom' => 
  array (
    'Assigned' => 'Pridelený',
    'Converted' => 'Konvertovaný',
    'Dead' => 'Ukončený',
    'In Process' => 'V priebehu',
    'New' => 'Nový',
    'Recycled' => 'Recyklovaný',
  ),
  'meeting_status_dom' => 
  array (
    'Held' => 'Pozdržaný',
    'Not Held' => 'Nepozdržaný',
    'Planned' => 'Plánovaný',
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
    'Bugs' => 'Hlásenie chýb',
    'Cases' => 'Prípady',
    'FAQ' => 'FAQ',
    'Home' => 'Domov',
    'KBDocuments' => 'Báza znalostí',
    'Newsletters' => 'Noviny',
    'Notes' => 'Poznámky',
    'Teams' => 'Tímy',
    'Users' => 'Používatelia',
  ),
  'moduleListSingular' => 
  array (
    'Bugs' => 'Bug',
    'Cases' => 'Prípad',
    'Home' => 'Domov',
    'Notes' => 'Poznámka',
    'Teams' => 'Tím',
    'Users' => 'Používateľ',
  ),
  'mselect_type_dom' => 
  array (
    'Equals' => 'Sa rovná',
    'in' => 'Je jeden z',
  ),
  'notifymail_sendtype' => 
  array (
    'SMTP' => 'SMTP',
  ),
  'opportunity_relationship_type_dom' => 
  array (
    '' => '',
    'Business Decision Maker' => 'Osoba s biznis rozhodovacími právomocami',
    'Business Evaluator' => 'Biznis hodnotiteľ',
    'Executive Sponsor' => 'Vedúci sponzor',
    'Influencer' => 'Ovplyvniteľ',
    'Other' => 'Iné',
    'Primary Decision Maker' => 'Osoba s hlavnými rozhodovacími právomocami',
    'Technical Decision Maker' => 'Osoba s technickými rozhodovacími právomocami',
    'Technical Evaluator' => 'Technický hodnotiteľ',
  ),
  'opportunity_type_dom' => 
  array (
    '' => '',
    'Existing Business' => 'Existujúci biznis',
    'New Business' => 'Nový biznis',
  ),
  'order_stage_dom' => 
  array (
    'Cancelled' => 'Zrušené',
    'Confirmed' => 'Potvrdený',
    'On Hold' => 'V poradí',
    'Pending' => 'Prebieha',
    'Shipped' => 'Posielame',
  ),
  'payment_terms' => 
  array (
    '' => '',
    'Net 15' => 'Net 15',
    'Net 30' => 'Net 30',
  ),
  'pricing_formula_dom' => 
  array (
    'Fixed' => 'Pevná cena',
    'IsList' => 'Rovnaký ako zoznam',
    'PercentageDiscount' => 'Zľava zo zoznamu',
    'PercentageMarkup' => 'Zisková prirážka nad nákladmi',
    'ProfitMargin' => 'Zisk',
  ),
  'product_category_dom' => 
  array (
    '' => '',
    'Accounts' => 'Účty',
    'Activities' => 'Aktivity',
    'Bug Tracker' => 'Hlásenie chýb',
    'Calendar' => 'Kalendár',
    'Calls' => 'Hovory',
    'Campaigns' => 'Kampane',
    'Cases' => 'Prípady',
    'Contacts' => 'Kontakty',
    'Currencies' => 'Meny',
    'Dashboard' => 'Prístrojová doska',
    'Documents' => 'Dokumenty',
    'Emails' => 'Emaily',
    'Feeds' => 'Krátke novinky',
    'Forecasts' => 'Prognózy',
    'Help' => 'Pomoc',
    'Home' => 'Domov',
    'Leads' => 'Príležitosti',
    'Meetings' => 'Schôdzky',
    'Notes' => 'Poznámky',
    'Opportunities' => 'Príležitosti',
    'Outlook Plugin' => 'Outlook Plugin',
    'Product Catalog' => 'Katalóg produktov',
    'Products' => 'Produkty',
    'Projects' => 'Projekty',
    'Quotes' => 'Ponuky',
    'RSS' => 'RSS',
    'Releases' => 'Verzie',
    'Studio' => 'Štúdio',
    'Upgrade' => 'aktualizácia',
    'Users' => 'Používatelia',
  ),
  'product_status_dom' => 
  array (
    'Orders' => 'Objednané',
    'Quotes' => 'Rezervované',
    'Ship' => 'Odoslané',
  ),
  'product_status_quote_key' => 'Rezervácie',
  'product_template_status_dom' => 
  array (
    'Available' => 'Na sklade',
    'Unavailable' => 'Vypredané',
  ),
  'project_task_priority_options' => 
  array (
    'High' => 'Vysoko',
    'Low' => 'Nízko',
    'Medium' => 'Stredne',
  ),
  'project_task_status_options' => 
  array (
    'Completed' => 'Dokončený',
    'Deferred' => 'Odložený',
    'In Progress' => 'V činnosti',
    'Not Started' => 'Nezačatý',
    'Pending Input' => 'Čakajúci na vstup',
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
    'default' => 'Prednastavený',
    'exempt' => 'Stlačený list - podľa ID',
    'exempt_address' => 'Stlačený zoznam - podľa emailovej adresy',
    'exempt_domain' => 'Stlačený zoznam - podľa domény',
    'seed' => 'Pôvod',
    'test' => 'Test',
  ),
  'query_calc_oper_dom' => 
  array (
    '*' => '(X) Vynásobený',
    '+' => '(+) Plus',
    '-' => '(-) Mínus',
    '/' => '(/) Delený',
  ),
  'quote_relationship_type_dom' => 
  array (
    '' => '',
    'Business Decision Maker' => 'Osoba s biznis rozhodovacími právomocami',
    'Business Evaluator' => 'Biznis hodnotiteľ',
    'Executive Sponsor' => 'Vedúci sponzor',
    'Influencer' => 'Ovplyvniteľ',
    'Other' => 'Iné:',
    'Primary Decision Maker' => 'Osoba s hlavnými rozhodovacími právomocami',
    'Technical Decision Maker' => 'Osoba s technickými rozhodovacími právomocami',
    'Technical Evaluator' => 'Technický hodnotiteľ',
  ),
  'quote_stage_dom' => 
  array (
    'Closed Accepted' => 'Uzatvorené akceptované',
    'Closed Dead' => 'Uzatvorené ukončenie',
    'Closed Lost' => 'Uzatvorená prehra',
    'Confirmed' => 'Potvrdené',
    'Delivered' => 'Doručené',
    'Draft' => 'Koncept',
    'Negotiation' => 'Jednanie',
    'On Hold' => 'V poradí',
  ),
  'quote_type_dom' => 
  array (
    'Orders' => 'Objednávka',
    'Quotes' => 'Rezervácia',
  ),
  'record_type_display' => 
  array (
    'Accounts' => 'Účet',
    'Bugs' => 'Bug',
    'Cases' => 'Prípad',
    'Contacts' => 'Kontakty',
    'Leads' => 'Príležitosť',
    'Opportunities' => 'Obchodné príležitosti',
    'ProductTemplates' => 'Produkt',
    'Project' => 'Projekt',
    'ProjectTask' => 'Projektová úloha',
    'Quotes' => 'Rezervácie',
    'Tasks' => 'Úloha',
  ),
  'record_type_display_notes' => 
  array (
    'Accounts' => 'Účty spoločností',
    'Bugs' => 'Bug',
    'Calls' => 'Volanie',
    'Cases' => 'Prípad',
    'Contacts' => 'Kontakty',
    'Contracts' => 'Zmluvy',
    'Emails' => 'Emaily',
    'Leads' => 'Príležitosti',
    'Meetings' => 'Schôdzka',
    'Opportunities' => 'Príležitosti',
    'ProductTemplates' => 'Produkt',
    'Products' => 'Produkty',
    'Project' => 'Projekt',
    'ProjectTask' => 'Projektová úloha',
    'Quotes' => 'Rezervácie',
  ),
  'reminder_max_time' => '3600',
  'reminder_time_options' => 
  array (
    60 => '1 minútu pred',
    300 => '5 minút pred',
    600 => '10 minút pred',
    900 => '15 minút pred',
    1800 => '30 minút pred',
    3600 => '1 hodina pred',
  ),
  'sales_probability_dom' => 
  array (
    'Closed Lost' => '0',
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
    'Closed Lost' => 'Uzatvorená prehra',
    'Closed Won' => 'Uzatvorená výhra',
    'Id. Decision Makers' => 'ID osôb s rozhodovacími právomocami',
    'Needs Analysis' => 'Analýza potrieb',
    'Negotiation/Review' => 'Rokovanie / Správa',
    'Perception Analysis' => 'Analýza dojmov',
    'Proposal/Price Quote' => 'Návrh / Cenová ponuka',
    'Prospecting' => 'Vyhľadávané',
    'Qualification' => 'Kvalifikácia',
    'Value Proposition' => 'Návrh hodnôt',
  ),
  'salutation_dom' => 
  array (
    '' => '',
    'Dr.' => 'Doktor',
    'Mr.' => 'Pán',
    'Mrs.' => 'Pani',
    'Ms.' => 'Pani',
    'Prof.' => 'Profesor',
  ),
  'schedulers_times_dom' => 
  array (
    'completed' => 'Dokončený',
    'failed' => 'Neúspešný',
    'in progress' => 'V činnosti',
    'no curl' => 'Nebeží: žiadne cURL nie je k dispozícii',
    'not run' => 'Za dobou ukončenia, nevykonané',
    'ready' => 'Pripravené',
  ),
  'source_dom' => 
  array (
    '' => '',
    'Forum' => 'Fórum',
    'InboundEmail' => 'Email',
    'Internal' => 'Interný',
    'Web' => 'Web',
  ),
  'support_term_dom' => 
  array (
    '+1 year' => '1 rok',
    '+2 years' => '2 roky',
    '+6 months' => '6 mesiacov',
  ),
  'task_priority_dom' => 
  array (
    'High' => 'Vysoko',
    'Low' => 'Nízko',
    'Medium' => 'Stredne',
  ),
  'task_status_dom' => 
  array (
    'Completed' => 'Dokončený',
    'Deferred' => 'Odložený',
    'In Progress' => 'V činnosti',
    'Not Started' => 'Nezačatý',
    'Pending Input' => 'Očakáva vstup',
  ),
  'tax_class_dom' => 
  array (
    'Non-Taxable' => 'Nezdaniteľné',
    'Taxable' => 'Zdaniteľné',
  ),
  'tselect_type_dom' => 
  array (
    14440 => '4 hodiny',
    28800 => '8 hodín',
    43200 => '12 hodín',
    86400 => '1 deň',
    172800 => '2 dni',
    259200 => '3 dni',
    345600 => '4 dni',
    432000 => '5 dní',
    604800 => '1 týždeň',
    1209600 => '2 týždne',
    1814400 => '3 týždne',
    2592000 => '30 dní',
    5184000 => '60 dní',
    7776000 => '90 dní',
    10368000 => '120 dní',
    12960000 => '150 dní',
    15552000 => '180 dní',
  ),
  'user_status_dom' => 
  array (
    'Active' => 'Aktívny',
    'Inactive' => 'Neaktívny',
  ),
  'wflow_action_datetime_type_dom' => 
  array (
    'Existing Value' => 'Existujúca hodnota',
    'Triggered Date' => 'Dátum spustenia',
  ),
  'wflow_action_type_dom' => 
  array (
    'new' => 'Nový záznam',
    'update' => 'Aktualizovať záznam',
    'update_rel' => 'Aktualizovať súvisiaci záznam',
  ),
  'wflow_address_type_dom' => 
  array (
    'bcc' => 'BCC:',
    'cc' => 'CC:',
    'to' => 'Komu:',
  ),
  'wflow_address_type_invite_dom' => 
  array (
    'bcc' => 'BCC:',
    'cc' => 'CC:',
    'invite_only' => '(Pozvať len)',
    'to' => 'Komu:',
  ),
  'wflow_address_type_to_only_dom' => 
  array (
    'to' => 'Komu:',
  ),
  'wflow_adv_enum_type_dom' => 
  array (
    'advance' => 'Posuň rozbaľovací zoznam dopredu pomocou',
    'retreat' => 'Posuň rozbaľovací zoznam späť pomocou',
  ),
  'wflow_adv_team_type_dom' => 
  array (
    'current_team' => 'Tím prihlásených používateľov',
    'team_id' => 'Aktuálny tím spustených záznamov',
  ),
  'wflow_adv_user_type_dom' => 
  array (
    'assigned_user_id' => 'Používateľ pridelený k spustenému záznamu',
    'created_by' => 'Používateľ, ktorý spustil záznam',
    'current_user' => 'Aktuálny používateľ',
    'modified_user_id' => 'Používateľ, ktorý naposledy upravoval spustený záznam',
  ),
  'wflow_alert_type_dom' => 
  array (
    'Email' => 'Email',
    'Invite' => 'Pozvi',
  ),
  'wflow_array_type_dom' => 
  array (
    'future' => 'Nová hodnota',
    'past' => 'Stará hodnota',
  ),
  'wflow_fire_order_dom' => 
  array (
    'actions_alerts' => 'Akcie a potom upozornenia',
    'alerts_actions' => 'Upozornenia a potom akcie',
  ),
  'wflow_record_type_dom' => 
  array (
    'All' => 'Nové a aktualizované záznamy',
    'New' => 'Len nové záznamy',
    'Update' => 'Len aktualizované záznamy',
  ),
  'wflow_rel_type_dom' => 
  array (
    'all' => 'Všetky súvisiace',
    'filter' => 'Súvisiace s filtrom',
  ),
  'wflow_relate_type_dom' => 
  array (
    'Manager' => 'Užívateľský manažér',
    'Self' => 'Používateľ',
  ),
  'wflow_relfilter_type_dom' => 
  array (
    'all' => 'Všetky súvisiace',
    'any' => 'Každé súvisiace',
  ),
  'wflow_set_type_dom' => 
  array (
    'Advanced' => 'Rozšírené možnosti',
    'Basic' => 'Základné možnosti',
  ),
  'wflow_source_type_dom' => 
  array (
    'Custom Template' => 'Vlastná šablóna',
    'Normal Message' => 'Normálna správa',
    'System Default' => 'Prednastavené systémom',
  ),
  'wflow_type_dom' => 
  array (
    'Normal' => 'Keď je záznam uložený',
    'Time' => 'Keď uplynie doba',
  ),
  'wflow_user_type_dom' => 
  array (
    'current_user' => 'Aktuálni používatelia',
    'rel_user' => 'Príbuzní používatelia',
    'rel_user_custom' => 'Súvisiaci vlastný používateľ',
    'specific_role' => 'Špecifická úloha',
    'specific_team' => 'Špecifický tím',
    'specific_user' => 'Špecifický používateľ',
  ),
);

$app_strings = array (
  'ERROR_FULLY_EXPIRED' => 'Licencia Vašej spoločnosti pre SugarCRM uplynula pred viac ako 30 dňami a je treba doviesť až k dnešnému dňu. Iba administrátori sa môžu prihlásiť.',
  'ERROR_LICENSE_EXPIRED' => 'Licencia Vašej spoločnosti pre SugarCRM vypršala je potrebné aktualizovať. Iba administrátori môžu prihlásiť',
  'ERROR_NO_RECORD' => 'Chyba pri načítaní záznamov. Tento záznam môže byť odstránený alebo nemusí byť povolené jeho zobrazenie.',
  'ERR_CREATING_FIELDS' => 'Chyba pri doplňovaní dodatočných informáčných polí:',
  'ERR_CREATING_TABLE' => 'Chyba pri vytvorení tabuľky:',
  'ERR_DELETE_RECORD' => 'K odstráneniu kontaktu musíte zadať číslo záznamu.',
  'ERR_EXPORT_DISABLED' => 'Exporty zablokované.',
  'ERR_EXPORT_TYPE' => 'Chyba pri exportovaní',
  'ERR_INVALID_AMOUNT' => 'Prosím vložte platné množstvo.',
  'ERR_INVALID_DATE' => 'Prosím vložte platný dátum.',
  'ERR_INVALID_DATE_FORMAT' => 'Dátumový formát musí byť:',
  'ERR_INVALID_DAY' => 'Prosím vložte platný deň.',
  'ERR_INVALID_EMAIL_ADDRESS' => 'neplatný email.',
  'ERR_INVALID_HOUR' => 'Prosím vložte platnú hodinu.',
  'ERR_INVALID_MONTH' => 'Prosím vložte platný mesiac.',
  'ERR_INVALID_REQUIRED_FIELDS' => 'Neplatné požadované pole:',
  'ERR_INVALID_TIME' => 'Prosím vložte platný čas.',
  'ERR_INVALID_VALUE' => 'Neplatná hodnota:',
  'ERR_INVALID_YEAR' => 'Prosím vložte platný rok zo 4 číslic.',
  'ERR_MISSING_REQUIRED_FIELDS' => 'Chýba požadované pole:',
  'ERR_NEED_ACTIVE_SESSION' => 'Pre export obsahu je potrebné aktívne spojenie.',
  'ERR_NOTHING_SELECTED' => 'Prosím, vyberte si pred pokračovaním.',
  'ERR_OPPORTUNITY_NAME_DUPE' => 'Opportunita s názvom %s už existuje. Prosím vložte iný názov.',
  'ERR_OPPORTUNITY_NAME_MISSING' => 'Neoblo vložené meno opportunity. Prosím vložte meno opportunity.',
  'ERR_PORTAL_LOGIN_FAILED' => 'Nie je možné sa prihlásiť. Prosím kontaktujte administrátora.',
  'ERR_RESOURCE_MANAGEMENT_INFO' => 'Vrtiť sa <a href="index.php">Domov</a>',
  'ERR_SELF_REPORTING' => 'Užívateľ nemôže sebe hlásiť.',
  'ERR_SQS_NO_MATCH' => 'Žiadna zhoda',
  'ERR_SQS_NO_MATCH_FIELD' => 'Žiadna zhoda s poľom.',
  'LBL_ACCOUNT' => 'Účet',
  'LBL_ACCOUNTS' => 'Účty',
  'LBL_ACCUMULATED_HISTORY_BUTTON_KEY' => 'H',
  'LBL_ACCUMULATED_HISTORY_BUTTON_LABEL' => 'Zobraz zhrnutie',
  'LBL_ACCUMULATED_HISTORY_BUTTON_TITLE' => 'Zobraz zhrnutie',
  'LBL_ADDITIONAL_DETAILS' => 'Ďalšie detaily',
  'LBL_ADDITIONAL_DETAILS_CLOSE' => 'Zavrieť',
  'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE' => 'Kliknutím zavriete',
  'LBL_ADD_BUTTON' => 'Pridať',
  'LBL_ADD_BUTTON_KEY' => 'A',
  'LBL_ADD_BUTTON_TITLE' => 'Pridať',
  'LBL_ADD_DOCUMENT' => 'Pridať dokument',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_KEY' => 'L',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_LABEL' => 'Pridať k zoznamu cieľov',
  'LBL_ADD_TO_PROSPECT_LIST_BUTTON_TITLE' => 'Pridať k zoznamu cieľov',
  'LBL_ADMIN' => 'Admin',
  'LBL_ALT_HOT_KEY' => '',
  'LBL_ARCHIVE' => 'Archív',
  'LBL_ASSIGNED_TO' => 'Pridelený k',
  'LBL_ASSIGNED_TO_USER' => 'Pridelené k užívateľovi',
  'LBL_BACK' => 'Späť',
  'LBL_BILL_TO_ACCOUNT' => 'Faktúrovať účet',
  'LBL_BILL_TO_CONTACT' => 'Faktúrovať kontakt',
  'LBL_BROWSER_TITLE' => 'SugarCRM- Komerčný Open Source CRM',
  'LBL_BUGS' => 'Chyby',
  'LBL_BY' => 'podľa',
  'LBL_CALLS' => 'Volania',
  'LBL_CAMPAIGNS_SEND_QUEUED' => 'Poslať kampaňové emaily v poradí',
  'LBL_CANCEL_BUTTON_KEY' => 'X',
  'LBL_CANCEL_BUTTON_LABEL' => 'Zrušiť',
  'LBL_CANCEL_BUTTON_TITLE' => 'Zrušiť',
  'LBL_CASE' => 'Udalosť',
  'LBL_CASES' => 'Udalosti',
  'LBL_CHANGE_BUTTON_KEY' => 'G',
  'LBL_CHANGE_BUTTON_LABEL' => 'Zmena',
  'LBL_CHANGE_BUTTON_TITLE' => 'Zmena',
  'LBL_CHANGE_PASSWORD' => 'Zmena generovaného hesla',
  'LBL_CHARSET' => 'UTF-8',
  'LBL_CHECKALL' => 'Skontrolovať všetko',
  'LBL_CLEARALL' => 'Zmazať všetko',
  'LBL_CLEAR_BUTTON_KEY' => 'C',
  'LBL_CLEAR_BUTTON_LABEL' => 'Zmazať',
  'LBL_CLEAR_BUTTON_TITLE' => 'Zmazať',
  'LBL_CLOSEALL_BUTTON_KEY' => 'Q',
  'LBL_CLOSEALL_BUTTON_LABEL' => 'Zavrieť všetko',
  'LBL_CLOSEALL_BUTTON_TITLE' => 'Zavrieť všetko',
  'LBL_CLOSE_WINDOW' => 'Zavrieť okno',
  'LBL_COMPOSE_EMAIL_BUTTON_KEY' => 'L',
  'LBL_COMPOSE_EMAIL_BUTTON_LABEL' => 'Zostaviť Email',
  'LBL_COMPOSE_EMAIL_BUTTON_TITLE' => 'Zostaviť Email',
  'LBL_CONTACT' => 'Kontakt',
  'LBL_CONTACTS' => 'Kontakty',
  'LBL_CONTACT_LIST' => 'Zoznam kontaktov',
  'LBL_CREATED' => 'Vytvoril',
  'LBL_CREATED_BY_USER' => 'Vytvorené užívateľom',
  'LBL_CREATE_BUTTON_LABEL' => 'Vytvoriť',
  'LBL_CURRENT_USER_FILTER' => 'Iba moje položky:',
  'LBL_DATE_ENTERED' => 'Dátum vytvorenia',
  'LBL_DATE_MODIFIED' => 'Dátum úpravy',
  'LBL_DELETE' => 'Vymazať',
  'LBL_DELETED' => 'Vymazaný',
  'LBL_DELETE_BUTTON' => 'Vymazať',
  'LBL_DELETE_BUTTON_KEY' => 'D',
  'LBL_DELETE_BUTTON_LABEL' => 'Vymazať',
  'LBL_DELETE_BUTTON_TITLE' => 'Vymazať',
  'LBL_DIRECT_REPORTS' => 'Priame reporty',
  'LBL_DISPLAY_COLUMNS' => 'Zobraziť stĺpce',
  'LBL_DONE_BUTTON_KEY' => 'X',
  'LBL_DONE_BUTTON_LABEL' => 'Dokončené',
  'LBL_DONE_BUTTON_TITLE' => 'Dokončené',
  'LBL_DST_NEEDS_FIXIN' => 'Aplikácia vyžaduje Daylight Saving Time opravy majú byť použité. Prosím, choďte na opravu cez odkaz v administrátorskej konzole a použite letný čas na opravu.',
  'LBL_DUPLICATE_BUTTON' => 'Duplikovať',
  'LBL_DUPLICATE_BUTTON_KEY' => 'u',
  'LBL_DUPLICATE_BUTTON_LABEL' => 'Duplikovať',
  'LBL_DUPLICATE_BUTTON_TITLE' => 'Duplikovať',
  'LBL_DUP_MERGE' => 'najsť duplikáty',
  'LBL_EDIT_BUTTON' => 'Upraviť',
  'LBL_EDIT_BUTTON_KEY' => 'E',
  'LBL_EDIT_BUTTON_LABEL' => 'Upraviť',
  'LBL_EDIT_BUTTON_TITLE' => 'Upraviť',
  'LBL_EMAILS' => 'Emaily',
  'LBL_EMAIL_PDF_BUTTON_KEY' => 'M',
  'LBL_EMAIL_PDF_BUTTON_LABEL' => 'Email ako PDF',
  'LBL_EMAIL_PDF_BUTTON_TITLE' => 'Email ako PDF',
  'LBL_EMPLOYEES' => 'Pracovníci',
  'LBL_ENTER_DATE' => 'Vložiť dátum',
  'LBL_EXPORT' => 'Export',
  'LBL_EXPORT_ALL' => 'Všetko exportovať',
  'LBL_FULL_FORM_BUTTON_KEY' => 'F',
  'LBL_FULL_FORM_BUTTON_LABEL' => 'plný formulár',
  'LBL_FULL_FORM_BUTTON_TITLE' => 'plný formulár',
  'LBL_HIDE' => 'Skryť',
  'LBL_HIDE_COLUMNS' => 'Skryť stĺpce',
  'LBL_ID' => 'ID',
  'LBL_IMPORT' => 'Import',
  'LBL_IMPORT_PROSPECTS' => 'Importovať ciele',
  'LBL_LAST_VIEWED' => 'Naposledy zobrazené',
  'LBL_LEADS' => 'Príležitosti',
  'LBL_LISTVIEW_MASS_UPDATE_CONFIRM' => 'Ste si istý, že si želáte aktualizovať celý zoznam?',
  'LBL_LISTVIEW_NO_SELECTED' => 'Aby ste mohli pokračovať prosím vyberte aspoň 1 záznam.',
  'LBL_LISTVIEW_OPTION_CURRENT' => 'Aktuálna strana',
  'LBL_LISTVIEW_OPTION_ENTIRE' => 'Celý zoznam',
  'LBL_LISTVIEW_OPTION_SELECTED' => 'Vybrané nahrávky',
  'LBL_LISTVIEW_SELECTED_OBJECTS' => 'Vybrané:',
  'LBL_LIST_ACCOUNT_NAME' => 'Názov účtu',
  'LBL_LIST_ASSIGNED_USER' => 'Užívateľ',
  'LBL_LIST_CONTACT_NAME' => 'Meno kontaktu',
  'LBL_LIST_CONTACT_ROLE' => 'Rola',
  'LBL_LIST_EMAIL' => 'Email',
  'LBL_LIST_NAME' => 'Meno',
  'LBL_LIST_OF' => 'z',
  'LBL_LIST_PHONE' => 'Telefón',
  'LBL_LIST_TEAM' => 'Tím',
  'LBL_LIST_USER_NAME' => 'Užívateľské meno',
  'LBL_LOADING' => 'Načítava',
  'LBL_LOCALE_NAME_EXAMPLE_FIRST' => 'John',
  'LBL_LOCALE_NAME_EXAMPLE_LAST' => 'Doe',
  'LBL_LOCALE_NAME_EXAMPLE_SALUTATION' => 'Pán',
  'LBL_LOGIN_SESSION_EXCEEDED' => 'Server je preťažený. Skúste to prosím neskôr.',
  'LBL_LOGIN_TO_ACCESS' => 'Prosím, prihláste sa pre prístup k tejto oblasti.',
  'LBL_LOGOUT' => 'Odhlásenie',
  'LBL_MAILMERGE' => 'Zlúčenie emailov',
  'LBL_MAILMERGE_KEY' => 'M',
  'LBL_MASS_UPDATE' => 'Hromadná aktualizácia',
  'LBL_MEETINGS' => 'Schôdzky',
  'LBL_MEMBERS' => 'Členovia',
  'LBL_MODIFIED' => 'Upravil',
  'LBL_MODIFIED_BY_USER' => 'Zmenil užívateľ',
  'LBL_MY_ACCOUNT' => 'Môj účet',
  'LBL_NAME' => 'Názov',
  'LBL_NEW_BUTTON_KEY' => 'N',
  'LBL_NEW_BUTTON_LABEL' => 'Vytvoriť',
  'LBL_NEW_BUTTON_TITLE' => 'Vytvoriť',
  'LBL_NEXT_BUTTON_LABEL' => 'Ďalší',
  'LBL_NONE' => '--žiadny--',
  'LBL_NOTES' => 'Poznámky',
  'LBL_NO_RECORDS_FOUND' => 'Nenašiel sa žiadny záznam',
  'LBL_OPENALL_BUTTON_KEY' => 'O',
  'LBL_OPENALL_BUTTON_LABEL' => 'Všetko otvorené',
  'LBL_OPENALL_BUTTON_TITLE' => 'Všetko otvorené',
  'LBL_OPENTO_BUTTON_KEY' => 'T',
  'LBL_OPENTO_BUTTON_LABEL' => 'Ak chcete otvoriť:',
  'LBL_OPENTO_BUTTON_TITLE' => 'Ak chcete otvoriť:',
  'LBL_OPPORTUNITIES' => 'Príležitosti',
  'LBL_OPPORTUNITY' => 'Príležitosť',
  'LBL_OPPORTUNITY_NAME' => 'Názov príležitosti',
  'LBL_OR' => 'ALEBO',
  'LBL_PERCENTAGE_SYMBOL' => '%',
  'LBL_PRODUCTS' => 'Produkty',
  'LBL_PRODUCT_BUNDLES' => 'Produktové balíčky',
  'LBL_PROJECTS' => 'Projekty',
  'LBL_PROJECT_TASKS' => 'Projektové úlohy',
  'LBL_QUOTES' => 'Ponuky',
  'LBL_QUOTES_SHIP_TO' => 'Ponuky (Poslať kam)',
  'LBL_QUOTE_TO_OPPORTUNITY_KEY' => 'O',
  'LBL_QUOTE_TO_OPPORTUNITY_LABEL' => 'Vytvoriť príležitosť z ponuky',
  'LBL_QUOTE_TO_OPPORTUNITY_TITLE' => 'Vytvoriť príležitosť z ponuky',
  'LBL_RELATED_RECORDS' => 'súvisiace záznamy',
  'LBL_REMOVE' => 'Odstrániť',
  'LBL_REQUIRED_SYMBOL' => '*',
  'LBL_SAVED' => 'Uložené',
  'LBL_SAVED_LAYOUT' => 'Dispozícia bola uložená.',
  'LBL_SAVED_VIEWS' => 'uložiť zobrazenie',
  'LBL_SAVE_BUTTON_KEY' => 'S',
  'LBL_SAVE_BUTTON_LABEL' => 'Uložiť',
  'LBL_SAVE_BUTTON_TITLE' => 'Uložiť',
  'LBL_SAVE_NEW_BUTTON_KEY' => 'V',
  'LBL_SAVE_NEW_BUTTON_LABEL' => 'Uložiť & Vytvoriť nový',
  'LBL_SAVE_NEW_BUTTON_TITLE' => 'Uložiť & Vytvoriť nový',
  'LBL_SAVING' => 'Ukladanie',
  'LBL_SAVING_LAYOUT' => 'Uložené rozvrhnutie ..',
  'LBL_SEARCH' => 'Vyhľadávanie',
  'LBL_SEARCH_BUTTON_KEY' => 'Q',
  'LBL_SEARCH_BUTTON_LABEL' => 'Vyhľadávanie',
  'LBL_SEARCH_BUTTON_TITLE' => 'Vyhľadávanie',
  'LBL_SEARCH_CRITERIA' => 'vyhľadať kritéria',
  'LBL_SELECT_BUTTON_KEY' => 'T',
  'LBL_SELECT_BUTTON_LABEL' => 'Výber',
  'LBL_SELECT_BUTTON_TITLE' => 'Výber',
  'LBL_SELECT_CONTACT_BUTTON_KEY' => 'T',
  'LBL_SELECT_CONTACT_BUTTON_LABEL' => 'Vybrať Kontakt',
  'LBL_SELECT_CONTACT_BUTTON_TITLE' => 'Vybrať Kontakt',
  'LBL_SELECT_REPORTS_BUTTON_LABEL' => 'Vybrať si zo správ',
  'LBL_SELECT_REPORTS_BUTTON_TITLE' => 'Vybrať si zo správ',
  'LBL_SELECT_USER_BUTTON_KEY' => 'U',
  'LBL_SELECT_USER_BUTTON_LABEL' => 'Výber užívateľa...',
  'LBL_SELECT_USER_BUTTON_TITLE' => 'Výber užívateľa...',
  'LBL_SERVER_RESPONSE_RESOURCES' => 'Resources used to construct this page (queries, files)',
  'LBL_SERVER_RESPONSE_TIME' => 'Doba odozvy servera:',
  'LBL_SERVER_RESPONSE_TIME_SECONDS' => 'sekundy',
  'LBL_SHIP_TO_ACCOUNT' => 'odoslané kontaktu',
  'LBL_SHIP_TO_CONTACT' => 'odoslané kontaktu',
  'LBL_SHORTCUTS' => 'klávesové skratky',
  'LBL_SHOW' => 'Zobraziť',
  'LBL_SQS_INDICATOR' => '',
  'LBL_STATUS' => 'Stav:',
  'LBL_STATUS_UPDATED' => 'Vaš Stav na túto akciu bol aktualizovaný!',
  'LBL_SUBJECT' => 'Predmet',
  'LBL_SYNC' => 'Synchronizácia',
  'LBL_TASKS' => 'Úlohy',
  'LBL_TEAM' => 'Tím:',
  'LBL_TEAMS_LINK' => 'Tím',
  'LBL_TEAM_ID' => 'ID tímu:',
  'LBL_THOUSANDS_SYMBOL' => 'K',
  'LBL_TRACK_EMAIL_BUTTON_KEY' => 'K',
  'LBL_TRACK_EMAIL_BUTTON_LABEL' => 'Archivovať Email [Alt+K]',
  'LBL_TRACK_EMAIL_BUTTON_TITLE' => 'Archivovať Email',
  'LBL_UNAUTH_ADMIN' => 'Neoprávnený prístup do administrácie',
  'LBL_UNDELETE' => 'obnoviť',
  'LBL_UNDELETE_BUTTON' => 'obnoviť',
  'LBL_UNDELETE_BUTTON_LABEL' => 'obnoviť',
  'LBL_UNDELETE_BUTTON_TITLE' => 'obnoviť',
  'LBL_UNSYNC' => 'odsynchronizovať',
  'LBL_UPDATE' => 'Aktualizovať',
  'LBL_USERS' => 'Užívatelia',
  'LBL_USERS_SYNC' => 'synchronizácia užívateľov',
  'LBL_USER_LIST' => 'Zoznam užívateľov',
  'LBL_VIEW_BUTTON' => 'Zobraziť',
  'LBL_VIEW_BUTTON_KEY' => 'V',
  'LBL_VIEW_BUTTON_LABEL' => 'Zobraziť',
  'LBL_VIEW_BUTTON_TITLE' => 'Zobraziť',
  'LBL_VIEW_PDF_BUTTON_KEY' => 'P',
  'LBL_VIEW_PDF_BUTTON_LABEL' => 'Vytlačiť vo formáte PDF',
  'LBL_VIEW_PDF_BUTTON_TITLE' => 'Vytlačiť vo formáte PDF',
  'LNK_ABOUT' => 'O',
  'LNK_ADVANCED_SEARCH' => 'Pokročilý',
  'LNK_BASIC_SEARCH' => 'Základné',
  'LNK_DELETE' => 'vymazať',
  'LNK_DELETE_ALL' => 'všetko vymazať',
  'LNK_EDIT' => 'editovať',
  'LNK_GET_LATEST' => 'získať posledný',
  'LNK_GET_LATEST_TOOLTIP' => 'Nahradiť najnovšou verziou',
  'LNK_HELP' => 'Pomoc',
  'LNK_LIST_END' => 'Ukončenie',
  'LNK_LIST_NEXT' => 'Ďalší',
  'LNK_LIST_PREVIOUS' => 'Predošlý',
  'LNK_LIST_RETURN' => 'Späť na zoznam',
  'LNK_LIST_START' => 'Štart',
  'LNK_LOAD_SIGNED' => 'podpísať',
  'LNK_LOAD_SIGNED_TOOLTIP' => 'Nahraďte podpísaný dokument',
  'LNK_PRINT' => 'Tlač',
  'LNK_REMOVE' => 'Odstrániť',
  'LNK_RESUME' => 'pokračovať',
  'LNK_VIEW_CHANGE_LOG' => 'Zobraziť Zmenu Prihlásenia',
  'LOGIN_LOGO_ERROR' => 'Prosím nahradiť SugarCRM loga.',
  'NTC_CLICK_BACK' => 'Prosím, kliknite na tlačidlo Späť prehliadača a chybu opraviť.',
  'NTC_DATE_FORMAT' => '(yyyy-mm-dd)',
  'NTC_DATE_TIME_FORMAT' => '(yyyy-mm-dd 24:00)',
  'NTC_DELETE_CONFIRMATION' => 'Ste si istí, že chcete zmazať tento záznam?',
  'NTC_DELETE_CONFIRMATION_MULTIPLE' => 'Ste si istí, že chcete zmazať vybraný záznam (y)?',
  'NTC_LOGIN_MESSAGE' => 'prosím zadajte vaše používateľské meno a heslo',
  'NTC_NO_ITEMS_DISPLAY' => 'nič',
  'NTC_REMOVE_CONFIRMATION' => 'Ste si istým že chcete zrušiť túto väzbu produktu?',
  'NTC_REQUIRED' => 'Označuje povinné pole',
  'NTC_SUPPORT_SUGARCRM' => 'Podpora SugarCRM open source projekt s darovaním cez PayPal - je to rýchle, bezplatné a bezpečné!',
  'NTC_TIME_FORMAT' => '(24:00)',
  'NTC_WELCOME' => '<i>Vitajte</i>',
  'NTC_YEAR_FORMAT' => '(yyyy)',
);

