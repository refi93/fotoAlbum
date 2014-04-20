(function($) {
    $.fn.formValidation = function(rules) {             // Takto vyzera definovanie jQuery pluginu

        var self = this;                                // Aby sme sa mohli odkazovat sami na seba
        self.rules = rules;                             // Ulozme pravidla z parametra do nasho objektu

        //$("input, textarea", this).blur(function()
        $("input, textarea", this).keyup(function()     // Ked pustime tlacitko v jednom z formularovych komponentov
        {
            var fieldName = $(this).attr("id");         // Zistime ID tohto komponentu
            validateField(fieldName);                   // Pustime validaciu
        });

        function validateField(fieldName)               // Zvaliduje policko a ak je chybne, vypise info
        {
            var fieldRules = self.rules[fieldName];     // Ziskame zoznam pravidiel pre tento field
            if (fieldRules === null)                    // Ak neexistuju pravidla, mozme oznacit za OK a pokracovat
            {
                $('.error[data-field="' + fieldName + '"]').html('<img src="images/ok.png" alt="validation success" />');
                return true;
            }
            var value = $("#" + fieldName).val();       // Ziskame momentalnu hodnotu komponentu (tuto budeme validovat)
            var fieldResult = true;                     // Momentalne je hodnota validna
            for (var j in fieldRules)                   // Pre kazde pravidlo
            {
                var rule = fieldRules[j];               // ziskajme zoznam, ktoreho prva polozka je nazov pravidla a dalsie su pripadne parametre
                var ruleName = rule[0];                 // nazov pravidla
                if (ruleName === "required")            // ak sa policko vyzaduje
                {
                    var result = required(value);       // spusti prislusnu funkciu
                    if (!result)                        // ak to nie je validne
                    {
                        fieldResult = false;            // Validacia neuspesna
                        // Nastavime info pre toto pole
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field is required.');
                        break;                          // Nepustaj dalsie validacie, staci ze je chybna jedna
                    }
                }
                else if (ruleName === "alphanumeric")   // Ak policko obsahuje iba znaky a cisla
                {
                    var result = alphanumeric(value);   // spusti funkciu
                    if (!result)                        // Ak chyba
                    {
                        fieldResult = false;            // Toto sa vsade opakuje, ale vzdy iny message
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field must contain only letters and numbers.');
                        break;
                    }
                }
                else if (ruleName === "matches")        // Ci sa zhoduje s inym prvkom
                {
                    var otherElem = rule[1];            // Nazov (ID) prvku
                    var result = matches(value, otherElem); // Pustime pravidlo
                    if (!result)                        // A zas to iste
                    {
                        fieldResult = false;
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field must match field ' + otherElem + ".");
                        break;
                    }
                }
                else if (ruleName === "min_length")     // Dalsie pravidlo s 1 parametrom
                {
                    var minLength = parseInt(rule[1]);
                    var result = min_length(value, minLength);
                    if (!result)
                    {
                        fieldResult = false;
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field must contain at least ' + minLength + " characters.");
                        break;
                    }
                }
                else if (ruleName === "max_length")     // Dalsie pravidlo s 1 parametrom
                {
                    var maxLength = parseInt(rule[1]);
                    var result = max_length(value, maxLength);
                    if (!result)
                    {
                        fieldResult = false;
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field must contain at most ' + maxLength + " characters.");
                        break;
                    }
                }
                else if (ruleName === "valid_email")    // validny email?
                {
                    var result = valid_email(value);
                    if (!result)
                    {
                        fieldResult = false;
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> This field must be a valid e-mail address.');
                        break;
                    }
                }
                /// NEW CODE
                if (ruleName === "ajax")
                {
                    var url = rule[1];
                    var result = ajax_validate(value, url);
                    if (!result)
                    {
                        fieldResult = false;
                        $('.error[data-field="' + fieldName + '"]').html('<img src="images/error.png" alt="validation error" /> ' + self.ajaxMessage);
                        break;
                    }

                }
                /// NEW CODE
            }

            if (fieldResult === true)                   // Prebehlo vsetko a stale sme OK?
            {
                $('.error[data-field="' + fieldName + '"]').html('<img src="images/ok.png" alt="validation success" />');
            }
            return fieldResult;                         // Vratme vysledok validacie
        }

        this.submit(function()                          // Ked sa snazime odoslat formular
        {
            var overallResult = true;                   // Celkovy result

            $(".error", this).html("");                 // Vymazeme policka na informacie

            for (var fieldName in self.rules)           // Pre kazde policko pre ktore mame validacne pravidla
            {
                var result = validateField(fieldName);  // Validuj
                if (!result)                            // Ak nastala chyba
                    overallResult = false;              // Nepovol odoslat formular
            }

            return overallResult;                       // Vratime vysledok celej validacie formularu. Ak sa vrati false, zamedzi sa odoslanie formularu a ostaneme na stranke
        });


        /////////////// PRAVIDLA //////////////

        function ajax_validate(value, url)
        {
            var result = false;
            var myData = {fieldValue: value};
            var xhr = $.ajax({
                url: url,
                async: false,
                data: myData,
                method: "get",
                dataType: "json",
                cache: false,
                processData: true
            });
            xhr.done(function(data)
            {
                if (data.status === true)
                {
                    result = true;
                } 
                else
                {
                    self.ajaxMessage = data.message;
                }
            });
            xhr.fail(function(xhr, textStatus, error) 
            {
                console.log("Error " + textStatus);
            });

            return result;

            /* AJAX bez jQuery
            var xhr;
            if (window.XMLHttpRequest)
                xhr = new XMLHttpRequest();
            else
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
             
            xhr.onreadystatechange = function()
            {
                if (xhr.readyState == 4 && xhr.status == 200)
                {
                    var jsonString = xhr.responseText;
                    var out = JSON.parse(jsonString);
                    if (out.status === false)
                    {
                        self.ajaxMessage = out.message;
                    }
                    else
                    {
                        result = true;
                    }
                }
            };
           
            xhr.open("GET", url + "?fieldValue=" + value, false);
            xhr.send();            
          
            return result;
            //*/
        }







        function ajax_validate(value, url)
        {
            var result = false;

            /// AJAX with jQuery ///            
            //*
            var myData = {fieldValue: value};
            var xhr = $.ajax({
                url: url,
                data: myData,
                processData: true,
                cache: false,
                async: false,
                method: 'get',
                dataType: 'json'
            });
            xhr.done(function(data)
            {
                console.log(data);
                if (data.status === true)
                {
                    result = true;
                }
                else
                {
                    self.ajaxMessage = data.message;
                    result = false;
                }
            });
            xhr.fail(function(xhr, textStatus, errorThrown)
            {
                console.log(url);
                console.log(textStatus);
                console.log(errorThrown);
                result = false;
            });
            //*/
            /// AJAX with jQuery ///           
            return result;
        }

        function required(value)
        {
            if (value === undefined || value.length == 0)
                return false;
            return true;
        }

        function matches(value, elemName)
        {
            if (value === undefined || value.length == 0)// Toto sa kontroluje pre required, ak pole je prazdne ale nepozadujeme ho (a tak ci tak ma pravidla), nech nebezi ina validacia
                return true;

            var otherValue = $("#" + elemName).val();
            return value === otherValue;
        }

        function min_length(value, min)
        {
            if (value === undefined || value.length == 0)
                return true;

            if (value.length >= min)
                return true;
            return false;
        }

        function max_length(value, max)
        {
            if (value === undefined || value.length == 0)
                return true;

            if (value.length <= max)
                return true;
            return false;
        }

        function alphanumeric(value)
        {
            if (value === undefined || value.length == 0)
                return true;
            //var regex = new RegExp("^[a-z0-9]+$", "i");
            //return regex.exec(value) != null;
            return /^[a-z0-9]+$/i.exec(value) !== null;     // Jednoduchy regularny vyraz
        }

        function valid_email(value)
        {
            if (value === undefined || value.length == 0)
                return true;

            // Dalsi regularny vyraz, vygoogleny a su tam chyby, napr. neakceptuje top-level domain .museum
            return /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i.exec(value) !== null;

        }
    };
})(jQuery);                                                 // Koniec pluginu