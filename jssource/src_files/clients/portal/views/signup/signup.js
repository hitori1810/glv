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

({
    events: {
        "click [name=cancel_button]": "cancel",
        "click [name=signup_button]": "signup",
        "change select[name=country]": "render"
    },
    initialize: function(options) {
        // Adds the metadata for the Login module
        app.metadata.set(this._metadata);
        app.data.declareModels();

        // Reprepare the context because it was initially prepared without metadata
        app.controller.context.prepare(true);

        // Attach the metadata to the view
        this.options.meta = _.clone(this._metadata.modules[this.options.module].views[this.options.name].meta);

        app.view.View.prototype.initialize.call(this, options);

        // use modal template for the fields
        this.fallbackFieldTemplate = "modal";

        // display the success message on rerendering
        this.signup_success = false;
    },
    render: function() {
        if (app.config && app.config.logoURL) {
            this.logoURL = app.config.logoURL
        }
        app.view.View.prototype.render.call(this);

        this.stateField = this.$('select[name=state]');
        this.countryField = this.$('select[name=country]');
        this.toggleStateField();
        return this;
    },
    toggleStateField: function() {
        if (this.countryField.val() == 'USA') {
            this.stateField.parent().show();
        } else {
            this.stateField.parent().hide();
            this.context.attributes.model.attributes.state = undefined;
        }
    },
    cancel: function() {
        app.router.goBack();
    },
    signup: function() {
        var self = this;
        var oEmail = this.model.get("email");
        if (oEmail) {
            this.model.set({
                "email": [
                    {"email_address": oEmail}
                ]
            }, {silent: true});
        }
        var validFlag = this.model.isValid();
        this.model.set({"email": oEmail}, {silent: true});
        if (validFlag) {
            $('#content').hide();
            app.alert.show('signup', {level:'process', title:app.lang.getAppString('LBL_PORTAL_SIGNUP_PROCESS'), autoClose:false});
            
            var contactData = {
                first_name: this.model.get("first_name"),
                last_name: this.model.get("last_name"),
                email: [{
                    "email_address" : this.model.get("email"),
                    "is_primary": true,
                    "is_invalid": false,
                    "opted_out": false
                }],
                phone_work: this.model.get("phone_work"),
                primary_address_state: this.model.get("state"),
                primary_address_country: this.model.get("country"),
                title: this.model.get("jobtitle"),
                account_name: this.model.get("company")
            };
            var pref_lang = app.lang.getLanguage();
            if (pref_lang) {
                contactData.preferred_language = pref_lang;
            }
            app.api.signup(contactData, null,
                {
                    error: function() {
                        app.alert.dismiss('signup');
                        $('#content').show();
                    },
                    success: function() {
                        app.alert.dismiss('signup');

                        // display the success message
                        self.signup_success = true;

                        // show a Back button
                        self.options.meta.buttons = self._backButton;

                        self.render();
                        $('#content').show();
                    }
                });
        }
    },
    _backButton: [
        {
            name: "cancel_button",
            type: "button",
            label: "LBL_BACK",
            value: "signup",
            primary: false
        }
    ],
    // Base metadata for Login module and login view
    _metadata: {
        _hash: '',
        "modules": {
            "Signup": {
                "fields": {
                    "first_name": {
                        "name": "first_name",
                        "type": "varchar",
                        "required": true
                    },
                    "last_name": {
                        "name": "last_name",
                        "type": "varchar",
                        "required": true
                    },
                    "email": {
                        "name": "email",
                        "type": "email",
                        "required": true
                    },
                    "phone_work": {
                        "name": "phone_work",
                        "type": "phone"
                    },
                    "state": {
                        "name": "state",
                        "type": "enum",
                        "options": "state_dom"
                    },
                    "country": {
                        "name": "country",
                        "type": "enum",
                        "options": "countries_dom",
                        "required": true
                    },
                    "company": {
                        "name": "company",
                        "type": "varchar",
                        "required": true
                    },
                    "jobtitle": {
                        "name": "jobtitle",
                        "type": "varchar"
                    },
                    "hr1": {
                        "name": "hr1",
                        "type": "hr"
                    }
                },
                "views": {
                    "signup": {
                        "meta": {
                            "buttons": [
                                {
                                    name: "cancel_button",
                                    type: "button",
                                    label: "LBL_CANCEL_BUTTON_LABEL",
                                    value: "signup",
                                    primary: false,
                                    'class': 'pull-left'
                                },
                                {
                                    name: "signup_button",
                                    type: "button",
                                    label: "LBL_SIGNUP_BUTTON_LABEL",
                                    value: "signup",
                                    primary: true,
                                    'class': 'pull-right'
                                }
                            ],
                            "panels": [
                                {
                                    "fields": [
                                        {name: "first_name", label: "LBL_PORTAL_SIGNUP_FIRST_NAME"},
                                        {name: "last_name", label: "LBL_PORTAL_SIGNUP_LAST_NAME"},
                                        {name: "hr1", label: ""},
                                        {name: "email", label: "LBL_PORTAL_SIGNUP_EMAIL"},
                                        {name: "phone_work", label: "LBL_PORTAL_SIGNUP_PHONE"},
                                        {name: "country", label: "LBL_PORTAL_SIGNUP_COUNTRY"},
                                        {name: "state", label: "LBL_PORTAL_SIGNUP_STATE"},
                                        {name: "hr1", label: ""},
                                        {name: "company", label: "LBL_PORTAL_SIGNUP_COMPANY"},
                                        {name: "jobtitle", label: "LBL_PORTAL_SIGNUP_JOBTITLE"}
                                    ]
                                }
                            ]
                        }
                    }
                },
                "layouts": {
                    "signup": {
                        "meta": {
                            "type": "simple",
                            "components": [
                                {view: "signup"}
                            ]
                        }
                    }
                }
            }
        },
        app_list_strings: {
            'countries_dom': {
                'ABU DHABI': 'Abu Dhabi',
                'ADEN': 'Aden',
                'AFGHANISTAN': 'Afghanistan',
                'ALBANIA': 'Albania',
                'ALGERIA': 'Algeria',
                'AMERICAN SAMOA': 'American Samoa',
                'ANDORRA': 'Andorra',
                'ANGOLA': 'Angola',
                'ANTARCTICA': 'Antarctica',
                'ANTIGUA': 'Antigua',
                'ARGENTINA': 'Argentina',
                'ARMENIA': 'Armenia',
                'ARUBA': 'Aruba',
                'AUSTRALIA': 'Australia',
                'AUSTRIA': 'Austria',
                'AZERBAIJAN': 'Azerbaijan',
                'BAHAMAS': 'Bahamas',
                'BAHRAIN': 'Bahrain',
                'BANGLADESH': 'Bangladesh',
                'BARBADOS': 'Barbados',
                'BELARUS': 'Belarus',
                'BELGIUM': 'Belgium',
                'BELIZE': 'Belize',
                'BENIN': 'Benin',
                'BERMUDA': 'Bermuda',
                'BHUTAN': 'Bhutan',
                'BOLIVIA': 'Bolivia',
                'BOSNIA': 'Bosnia',
                'BOTSWANA': 'Botswana',
                'BOUVET ISLAND': 'Bouvet Island',
                'BRAZIL': 'Brazil',
                'BRITISH ANTARCTICA TERRITORY': 'British Antarctica Territory',
                'BRITISH INDIAN OCEAN TERRITORY': 'British Indian Ocean Territory',
                'BRITISH VIRGIN ISLANDS': 'British Virgin Islands',
                'BRITISH WEST INDIES': 'British West Indies',
                'BRUNEI': 'Brunei',
                'BULGARIA': 'Bulgaria',
                'BURKINA FASO': 'Burkina Faso',
                'BURUNDI': 'Burundi',
                'CAMBODIA': 'Cambodia',
                'CAMEROON': 'Cameroon',
                'CANADA': 'Canada',
                'CANAL ZONE': 'Canal Zone',
                'CANARY ISLAND': 'Canary Island',
                'CAPE VERDI ISLANDS': 'Cape Verdi Islands',
                'CAYMAN ISLANDS': 'Cayman Islands',
                'CEVLON': 'Cevlon',
                'CHAD': 'Chad',
                'CHANNEL ISLAND UK': 'Channel Island UK',
                'CHILE': 'Chile',
                'CHINA': 'China',
                'CHRISTMAS ISLAND': 'Christmas Island',
                'COCOS (KEELING) ISLAND': 'Cocos (Keeling) Island',
                'COLOMBIA': 'Colombia',
                'COMORO ISLANDS': 'Comoro Islands',
                'CONGO': 'Congo',
                'CONGO KINSHASA': 'Congo Kinshasa',
                'COOK ISLANDS': 'Cook Islands',
                'COSTA RICA': 'Costa Rica',
                'CROATIA': 'Croatia',
                'CUBA': 'Cuba',
                'CURACAO': 'Curacao',
                'CYPRUS': 'Cyprus',
                'CZECH REPUBLIC': 'Czech Republic',
                'DAHOMEY': 'Dahomey',
                'DENMARK': 'Denmark',
                'DJIBOUTI': 'Djibouti',
                'DOMINICA': 'Dominica',
                'DOMINICAN REPUBLIC': 'Dominican Republic',
                'DUBAI': 'Dubai',
                'ECUADOR': 'Ecuador',
                'EGYPT': 'Egypt',
                'EL SALVADOR': 'El Salvador',
                'EQUATORIAL GUINEA': 'Equatorial Guinea',
                'ESTONIA': 'Estonia',
                'ETHIOPIA': 'Ethiopia',
                'FAEROE ISLANDS': 'Faeroe Islands',
                'FALKLAND ISLANDS': 'Falkland Islands',
                'FIJI': 'Fiji',
                'FINLAND': 'Finland',
                'FRANCE': 'France',
                'FRENCH GUIANA': 'French Guiana',
                'FRENCH POLYNESIA': 'French Polynesia',
                'GABON': 'Gabon',
                'GAMBIA': 'Gambia',
                'GEORGIA': 'Georgia',
                'GERMANY': 'Germany',
                'GHANA': 'Ghana',
                'GIBRALTAR': 'Gibraltar',
                'GREECE': 'Greece',
                'GREENLAND': 'Greenland',
                'GUADELOUPE': 'Guadeloupe',
                'GUAM': 'Guam',
                'GUATEMALA': 'Guatemala',
                'GUINEA': 'Guinea',
                'GUYANA': 'Guyana',
                'HAITI': 'Haiti',
                'HONDURAS': 'Honduras',
                'HONG KONG': 'Hong Kong',
                'HUNGARY': 'Hungary',
                'ICELAND': 'Iceland',
                'IFNI': 'Ifni',
                'INDIA': 'India',
                'INDONESIA': 'Indonesia',
                'IRAN': 'Iran',
                'IRAQ': 'Iraq',
                'IRELAND': 'Ireland',
                'ISRAEL': 'Israel',
                'ITALY': 'Italy',
                'IVORY COAST': 'Ivory Coast',
                'JAMAICA': 'Jamaica',
                'JAPAN': 'Japan',
                'JORDAN': 'Jordan',
                'KAZAKHSTAN': 'Kazakhstan',
                'KENYA': 'Kenya',
                'KOREA': 'Korea',
                'KOREA, SOUTH': 'Korea, South',
                'KUWAIT': 'Kuwait',
                'KYRGYZSTAN': 'Kyrgyzstan',
                'LAOS': 'Laos',
                'LATVIA': 'Latvia',
                'LEBANON': 'Lebanon',
                'LEEWARD ISLANDS': 'Leeward Islands',
                'LESOTHO': 'Lesotho',
                'LIBYA': 'Libya',
                'LIECHTENSTEIN': 'Liechtenstein',
                'LITHUANIA': 'Lithuania',
                'LUXEMBOURG': 'Luxembourg',
                'MACAO': 'Macao',
                'MACEDONIA': 'Macedonia',
                'MADAGASCAR': 'Madagascar',
                'MALAWI': 'Malawi',
                'MALAYSIA': 'Malaysia',
                'MALDIVES': 'Maldives',
                'MALI': 'Mali',
                'MALTA': 'Malta',
                'MARTINIQUE': 'Martinique',
                'MAURITANIA': 'Mauritania',
                'MAURITIUS': 'Mauritius',
                'MELANESIA': 'Melanesia',
                'MEXICO': 'Mexico',
                'MOLDOVIA': 'Moldovia',
                'MONACO': 'Monaco',
                'MONGOLIA': 'Mongolia',
                'MOROCCO': 'Morocco',
                'MOZAMBIQUE': 'Mozambique',
                'MYANAMAR': 'Myanamar',
                'NAMIBIA': 'Namibia',
                'NEPAL': 'Nepal',
                'NETHERLANDS': 'Netherlands',
                'NETHERLANDS ANTILLES': 'Netherlands Antilles',
                'NETHERLANDS ANTILLES NEUTRAL ZONE': 'Netherlands Antilles Neutral Zone',
                'NEW CALADONIA': 'New Caladonia',
                'NEW HEBRIDES': 'New Hebrides',
                'NEW ZEALAND': 'New Zealand',
                'NICARAGUA': 'Nicaragua',
                'NIGER': 'Niger',
                'NIGERIA': 'Nigeria',
                'NORFOLK ISLAND': 'Norfolk Island',
                'NORWAY': 'Norway',
                'OMAN': 'Oman',
                'OTHER': 'Other',
                'PACIFIC ISLAND': 'Pacific Island',
                'PAKISTAN': 'Pakistan',
                'PANAMA': 'Panama',
                'PAPUA NEW GUINEA': 'Papua New Guinea',
                'PARAGUAY': 'Paraguay',
                'PERU': 'Peru',
                'PHILIPPINES': 'Philippines',
                'POLAND': 'Poland',
                'PORTUGAL': 'Portugal',
                'PORTUGUESE TIMOR': 'Portuguese Timor',
                'PUERTO RICO': 'Puerto Rico',
                'QATAR': 'Qatar',
                'REPUBLIC OF BELARUS': 'Republic of Belarus',
                'REPUBLIC OF SOUTH AFRICA': 'Republic of South Africa',
                'REUNION': 'Reunion',
                'ROMANIA': 'Romania',
                'RUSSIA': 'Russia',
                'RWANDA': 'Rwanda',
                'RYUKYU ISLANDS': 'Ryukyu Islands',
                'SABAH': 'Sabah',
                'SAN MARINO': 'San Marino',
                'SAUDI ARABIA': 'Saudi Arabia',
                'SENEGAL': 'Senegal',
                'SERBIA': 'Serbia',
                'SEYCHELLES': 'Seychelles',
                'SIERRA LEONE': 'Sierra Leone',
                'SINGAPORE': 'Singapore',
                'SLOVAKIA': 'Slovakia',
                'SLOVENIA': 'Slovenia',
                'SOMALILIAND': 'Somaliliand',
                'SOUTH AFRICA': 'South Africa',
                'SOUTH YEMEN': 'South Yemen',
                'SPAIN': 'Spain',
                'SPANISH SAHARA': 'Spanish Sahara',
                'SRI LANKA': 'Sri Lanka',
                'ST. KITTS AND NEVIS': 'St. Kitts And Nevis',
                'ST. LUCIA': 'St. Lucia',
                'SUDAN': 'Sudan',
                'SURINAM': 'Surinam',
                'SW AFRICA': 'SW Africa',
                'SWAZILAND': 'Swaziland',
                'SWEDEN': 'Sweden',
                'SWITZERLAND': 'Switzerland',
                'SYRIA': 'Syria',
                'TAIWAN': 'Taiwan',
                'TAJIKISTAN': 'Tajikistan',
                'TANZANIA': 'Tanzania',
                'THAILAND': 'Thailand',
                'TONGA': 'Tonga',
                'TRINIDAD': 'Trinidad',
                'TUNISIA': 'Tunisia',
                'TURKEY': 'Turkey',
                'UGANDA': 'Uganda',
                'UKRAINE': 'Ukraine',
                'UNITED ARAB EMIRATES': 'United Arab Emirates',
                'UNITED KINGDOM': 'United Kingdom',
                'UPPER VOLTA': 'Upper Volta',
                'URUGUAY': 'Uruguay',
                'US PACIFIC ISLAND': 'US Pacific Island',
                'US VIRGIN ISLANDS': 'US Virgin Islands',
                'USA': 'USA',
                'UZBEKISTAN': 'Uzbekistan',
                'VANUATU': 'Vanuatu',
                'VATICAN CITY': 'Vatican City',
                'VENEZUELA': 'Venezuela',
                'VIETNAM': 'Vietnam',
                'WAKE ISLAND': 'Wake Island',
                'WEST INDIES': 'West Indies',
                'WESTERN SAHARA': 'Western Sahara',
                'YEMEN': 'Yemen',
                'ZAIRE': 'Zaire',
                'ZAMBIA': 'Zambia',
                'ZIMBABWE': 'Zimbabwe'
            },
            "state_dom": {
                "AL": "Alabama",
                "AK": "Alaska",
                "AZ": "Arizona",
                "AR": "Arkansas",
                "CA": "California",
                "CO": "Colorado",
                "CT": "Connecticut",
                "DE": "Delaware",
                "DC": "District Of Columbia",
                "FL": "Florida",
                "GA": "Georgia",
                "HI": "Hawaii",
                "ID": "Idaho",
                "IL": "Illinois",
                "IN": "Indiana",
                "IA": "Iowa",
                "KS": "Kansas",
                "KY": "Kentucky",
                "LA": "Louisiana",
                "ME": "Maine",
                "MD": "Maryland",
                "MA": "Massachusetts",
                "MI": "Michigan",
                "MN": "Minnesota",
                "MS": "Mississippi",
                "MO": "Missouri",
                "MT": "Montana",
                "NE": "Nebraska",
                "NV": "Nevada",
                "NH": "New Hampshire",
                "NJ": "New Jersey",
                "NM": "New Mexico",
                "NY": "New York",
                "NC": "North Carolina",
                "ND": "North Dakota",
                "OH": "Ohio",
                "OK": "Oklahoma",
                "OR": "Oregon",
                "PA": "Pennsylvania",
                "RI": "Rhode Island",
                "SC": "South Carolina",
                "SD": "South Dakota",
                "TN": "Tennessee",
                "TX": "Texas",
                "UT": "Utah",
                "VT": "Vermont",
                "VA": "Virginia ",
                "WA": "Washington",
                "WV": "West Virginia",
                "WI": "Wisconsin",
                "WY": "Wyoming"
            }
        }
    }
})
