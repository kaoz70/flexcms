var yimaPage = function() {
    var basic = function() {

        //--Jquery Select2--
        $("#e1").select2();
        $("#e2").select2({
            placeholder: "Select a State",
            allowClear: true
        });

        //--Tags Input--
        $('#tagsinput').tagsinput({
            tagClass: 'label label-primary'
        });

        //--Date Picker--
        $('#date-picker').datepicker();


        //--Time Picker--
        $('#timepicker').timepicker();


        //--Date Range Picker--
        $('#daterangepicker').daterangepicker();

        //--Autosize--
        $('#autosize').autosize({ append: "\n" });


        //--Spinbox--
        $('.spinbox').spinbox();


        //--JQuery Knob--
        $(".knob").knob();

        //--Color Picker--
        $('.colorpicker').each(function() {
            $(this).minicolors({
                control: $(this).attr('data-control') || 'hue',
                defaultValue: $(this).attr('data-defaultValue') || '',
                inline: $(this).attr('data-inline') === 'true',
                letterCase: $(this).attr('data-letterCase') || 'lowercase',
                opacity: $(this).attr('data-opacity'),
                position: $(this).attr('data-position') || 'bottom left',
                change: function(hex, opacity) {
                    if (!hex) return;
                    if (opacity) hex += ', ' + opacity;
                    try {
                        console.log(hex);
                    } catch (e) {
                    }
                },
                theme: 'bootstrap'
            });

        });

        //--Range Slider--
        $("#range_01").ionRangeSlider();

        $("#range_02").ionRangeSlider({
            type: "double",
            grid: true,
            min: 0,
            max: 1000,
            from: 200,
            to: 800,
            prefix: "$"
        });

        $("#range_03").ionRangeSlider({
            type: "double",
            grid: true,
            min: -1000,
            max: 1000,
            from: -500,
            to: 500,
            step: 250
        });

        $("#range_04").ionRangeSlider({
            grid: true,
            from: 3,
            values: [
                "January", "February", "March",
                "April", "May", "June",
                "July", "August", "September",
                "October", "November", "December"
            ]
        });
        $("#range_05").ionRangeSlider({
            grid: true,
            min: 1000,
            max: 1000000,
            from: 300000,
            step: 1000,
            prettify_enabled: true,
            prettify_separator: "."
        });

        $("#range_06").ionRangeSlider({
            type: "double",
            min: 100,
            max: 200,
            from: 120,
            to: 152,
            prefix: "Range: ",
            postfix: " light years",
            decorate_both: false,
            values_separator: " to "
        });
    }

    var advanced = function() {
        //--Rating--
        $("#rateit5").bind('rated', function(event, value) { $('#value5').text('You\'ve rated it: ' + value); });
        $("#rateit5").bind('reset', function() { $('#value5').text('Rating reset'); });
        $("#rateit5").bind('over', function(event, value) { $('#hover5').text('Hovering over: ' + value); });


        //--XEditable--
        $(function() {
            //ajax mocks
            $.mockjaxSettings.responseTime = 500;

            $.mockjax({
                url: '/post',
                response: function(settings) {
                    log(settings, this);
                }
            });

            $.mockjax({
                url: '/error',
                status: 400,
                statusText: 'Bad Request',
                response: function(settings) {
                    this.responseText = 'Please input correct value';
                    log(settings, this);
                }
            });

            $.mockjax({
                url: '/status',
                status: 500,
                response: function(settings) {
                    this.responseText = 'Internal Server Error';
                    log(settings, this);
                }
            });

            $.mockjax({
                url: '/groups',
                response: function(settings) {
                    this.responseText = [
                        { value: 0, text: 'Guest' },
                        { value: 1, text: 'Service' },
                        { value: 2, text: 'Customer' },
                        { value: 3, text: 'Operator' },
                        { value: 4, text: 'Support' },
                        { value: 5, text: 'Admin' }
                    ];
                    log(settings, this);
                }
            });

            function log(settings, response) {
                var s = [], str;
                s.push(settings.type.toUpperCase() + ' url = "' + settings.url + '"');
                for (var a in settings.data) {
                    if (settings.data[a] && typeof settings.data[a] === 'object') {
                        str = [];
                        for (var j in settings.data[a]) {
                            str.push(j + ': "' + settings.data[a][j] + '"');
                        }
                        str = '{ ' + str.join(', ') + ' }';
                    } else {
                        str = '"' + settings.data[a] + '"';
                    }
                    s.push(a + ' = ' + str);
                }
                s.push('RESPONSE: status = ' + response.status);

                if (response.responseText) {
                    if ($.isArray(response.responseText)) {
                        s.push('[');
                        $.each(response.responseText, function(i, v) {
                            s.push('{value: ' + v.value + ', text: "' + v.text + '"}');
                        });
                        s.push(']');
                    } else {
                        s.push($.trim(response.responseText));
                    }
                }
                s.push('--------------------------------------\n');
                $('#console').val(s.join('\n') + $('#console').val());
            }

        });

        $(function() {

            //defaults
            $.fn.editable.defaults.url = '/post';

            //enable / disable
            $('#enable').click(function() {
                $('#user .editable').editable('toggleDisabled');
            });

            //editables 
            $('#username').editable({
                url: '/post',
                type: 'text',
                pk: 1,
                name: 'username',
                title: 'Enter username'
            });

            $('#firstname').editable({
                validate: function(value) {
                    if ($.trim(value) == '') return 'This field is required';
                }
            });

            $('#sex').editable({
                prepend: "not selected",
                source: [
                    { value: 1, text: 'Male' },
                    { value: 2, text: 'Female' }
                ],
                display: function(value, sourceData) {
                    var colors = { "": "gray", 1: "green", 2: "blue" },
                        elem = $.grep(sourceData, function(o) { return o.value == value; });

                    if (elem.length) {
                        $(this).text(elem[0].text).css("color", colors[value]);
                    } else {
                        $(this).empty();
                    }
                }
            });

            $('#status').editable();

            $('#group').editable({
                showbuttons: false
            });

            $('#vacation').editable({
                datepicker: {
                    todayBtn: 'linked'
                }
            });

            $('#dob').editable();


            $('#meeting_start').editable({
                format: 'yyyy-mm-dd hh:ii',
                viewformat: 'dd/mm/yyyy hh:ii',
                validate: function(v) {
                    if (v && v.getDate() == 10) return 'Day cant be 10!';
                },
                datetimepicker: {
                    todayBtn: 'linked',
                    weekStart: 1
                }
            });

            $('#comments').editable({
                showbuttons: 'bottom'
            });

            $('#note').editable();
            $('#pencil').click(function(e) {
                e.stopPropagation();
                e.preventDefault();
                $('#note').editable('toggle');
            });

            var countries = [];
            $.each({ "BD": "Bangladesh", "BE": "Belgium", "BF": "Burkina Faso", "BG": "Bulgaria", "BA": "Bosnia and Herzegovina", "BB": "Barbados", "WF": "Wallis and Futuna", "BL": "Saint Bartelemey", "BM": "Bermuda", "BN": "Brunei Darussalam", "BO": "Bolivia", "BH": "Bahrain", "BI": "Burundi", "BJ": "Benin", "BT": "Bhutan", "JM": "Jamaica", "BV": "Bouvet Island", "BW": "Botswana", "WS": "Samoa", "BR": "Brazil", "BS": "Bahamas", "JE": "Jersey", "BY": "Belarus", "O1": "Other Country", "LV": "Latvia", "RW": "Rwanda", "RS": "Serbia", "TL": "Timor-Leste", "RE": "Reunion", "LU": "Luxembourg", "TJ": "Tajikistan", "RO": "Romania", "PG": "Papua New Guinea", "GW": "Guinea-Bissau", "GU": "Guam", "GT": "Guatemala", "GS": "South Georgia and the South Sandwich Islands", "GR": "Greece", "GQ": "Equatorial Guinea", "GP": "Guadeloupe", "JP": "Japan", "GY": "Guyana", "GG": "Guernsey", "GF": "French Guiana", "GE": "Georgia", "GD": "Grenada", "GB": "United Kingdom", "GA": "Gabon", "SV": "El Salvador", "GN": "Guinea", "GM": "Gambia", "GL": "Greenland", "GI": "Gibraltar", "GH": "Ghana", "OM": "Oman", "TN": "Tunisia", "JO": "Jordan", "HR": "Croatia", "HT": "Haiti", "HU": "Hungary", "HK": "Hong Kong", "HN": "Honduras", "HM": "Heard Island and McDonald Islands", "VE": "Venezuela", "PR": "Puerto Rico", "PS": "Palestinian Territory", "PW": "Palau", "PT": "Portugal", "SJ": "Svalbard and Jan Mayen", "PY": "Paraguay", "IQ": "Iraq", "PA": "Panama", "PF": "French Polynesia", "BZ": "Belize", "PE": "Peru", "PK": "Pakistan", "PH": "Philippines", "PN": "Pitcairn", "TM": "Turkmenistan", "PL": "Poland", "PM": "Saint Pierre and Miquelon", "ZM": "Zambia", "EH": "Western Sahara", "RU": "Russian Federation", "EE": "Estonia", "EG": "Egypt", "TK": "Tokelau", "ZA": "South Africa", "EC": "Ecuador", "IT": "Italy", "VN": "Vietnam", "SB": "Solomon Islands", "EU": "Europe", "ET": "Ethiopia", "SO": "Somalia", "ZW": "Zimbabwe", "SA": "Saudi Arabia", "ES": "Spain", "ER": "Eritrea", "ME": "Montenegro", "MD": "Moldova, Republic of", "MG": "Madagascar", "MF": "Saint Martin", "MA": "Morocco", "MC": "Monaco", "UZ": "Uzbekistan", "MM": "Myanmar", "ML": "Mali", "MO": "Macao", "MN": "Mongolia", "MH": "Marshall Islands", "MK": "Macedonia", "MU": "Mauritius", "MT": "Malta", "MW": "Malawi", "MV": "Maldives", "MQ": "Martinique", "MP": "Northern Mariana Islands", "MS": "Montserrat", "MR": "Mauritania", "IM": "Isle of Man", "UG": "Uganda", "TZ": "Tanzania, United Republic of", "MY": "Malaysia", "MX": "Mexico", "IL": "Israel", "FR": "France", "IO": "British Indian Ocean Territory", "FX": "France, Metropolitan", "SH": "Saint Helena", "FI": "Finland", "FJ": "Fiji", "FK": "Falkland Islands (Malvinas)", "FM": "Micronesia, Federated States of", "FO": "Faroe Islands", "NI": "Nicaragua", "NL": "Netherlands", "NO": "Norway", "NA": "Namibia", "VU": "Vanuatu", "NC": "New Caledonia", "NE": "Niger", "NF": "Norfolk Island", "NG": "Nigeria", "NZ": "New Zealand", "NP": "Nepal", "NR": "Nauru", "NU": "Niue", "CK": "Cook Islands", "CI": "Cote d'Ivoire", "CH": "Switzerland", "CO": "Colombia", "CN": "China", "CM": "Cameroon", "CL": "Chile", "CC": "Cocos (Keeling) Islands", "CA": "Canada", "CG": "Congo", "CF": "Central African Republic", "CD": "Congo, The Democratic Republic of the", "CZ": "Czech Republic", "CY": "Cyprus", "CX": "Christmas Island", "CR": "Costa Rica", "CV": "Cape Verde", "CU": "Cuba", "SZ": "Swaziland", "SY": "Syrian Arab Republic", "KG": "Kyrgyzstan", "KE": "Kenya", "SR": "Suriname", "KI": "Kiribati", "KH": "Cambodia", "KN": "Saint Kitts and Nevis", "KM": "Comoros", "ST": "Sao Tome and Principe", "SK": "Slovakia", "KR": "Korea, Republic of", "SI": "Slovenia", "KP": "Korea, Democratic People's Republic of", "KW": "Kuwait", "SN": "Senegal", "SM": "San Marino", "SL": "Sierra Leone", "SC": "Seychelles", "KZ": "Kazakhstan", "KY": "Cayman Islands", "SG": "Singapore", "SE": "Sweden", "SD": "Sudan", "DO": "Dominican Republic", "DM": "Dominica", "DJ": "Djibouti", "DK": "Denmark", "VG": "Virgin Islands, British", "DE": "Germany", "YE": "Yemen", "DZ": "Algeria", "US": "United States", "UY": "Uruguay", "YT": "Mayotte", "UM": "United States Minor Outlying Islands", "LB": "Lebanon", "LC": "Saint Lucia", "LA": "Lao People's Democratic Republic", "TV": "Tuvalu", "TW": "Taiwan", "TT": "Trinidad and Tobago", "TR": "Turkey", "LK": "Sri Lanka", "LI": "Liechtenstein", "A1": "Anonymous Proxy", "TO": "Tonga", "LT": "Lithuania", "A2": "Satellite Provider", "LR": "Liberia", "LS": "Lesotho", "TH": "Thailand", "TF": "French Southern Territories", "TG": "Togo", "TD": "Chad", "TC": "Turks and Caicos Islands", "LY": "Libyan Arab Jamahiriya", "VA": "Holy See (Vatican City State)", "VC": "Saint Vincent and the Grenadines", "AE": "United Arab Emirates", "AD": "Andorra", "AG": "Antigua and Barbuda", "AF": "Afghanistan", "AI": "Anguilla", "VI": "Virgin Islands, U.S.", "IS": "Iceland", "IR": "Iran, Islamic Republic of", "AM": "Armenia", "AL": "Albania", "AO": "Angola", "AN": "Netherlands Antilles", "AQ": "Antarctica", "AP": "Asia/Pacific Region", "AS": "American Samoa", "AR": "Argentina", "AU": "Australia", "AT": "Austria", "AW": "Aruba", "IN": "India", "AX": "Aland Islands", "AZ": "Azerbaijan", "IE": "Ireland", "ID": "Indonesia", "UA": "Ukraine", "QA": "Qatar", "MZ": "Mozambique" }, function(k, v) {
                countries.push({ id: k, text: v });
            });
            $('#country').editable({
                source: countries,
                select2: {
                    width: 200,
                    placeholder: 'Select country',
                    allowClear: false
                }
            });

            $('#user .editable').on('hidden', function(e, reason) {
                if (reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable');
                    if ($('#autoopen').is(':checked')) {
                        setTimeout(function() {
                            $next.editable('show');
                        }, 300);
                    } else {
                        $next.focus();
                    }
                }
            });

        });
    }

    return {
        advanced: advanced,
        init: function() {
            basic();
            advanced();
        }
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        if (yima.isAspMvc() === true) {
            yimaPage.advanced();
        } else {
            yimaPage.init();
        }
    }
});