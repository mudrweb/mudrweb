/**
 * Live Form Validation for Nette 2.0
 *
 * @author  Radek Ježdík, David Grudl
 * @license New BSD License
 */

var LiveForm = {
        options: {
                controlErrorClass: 'form-control-error',
                errorMessageClass: 'form-error-message',
                validMessageClass: 'form-valid-message',
                showValid: false,
                messageTag: 'span',
                messageIdPostfix: '_message',
                wait: 300
        },

        forms: { }
};

LiveForm.addError = function(el, message) {
        if (!this.forms[el.form.id].hasError) {
                this.forms[el.form.id].hasError = true;
        }

        this.addClass(el, this.options.controlErrorClass);

        if (!message) {
                message = '&nbsp;';
        }

        if (message != 'Jméno je již použito, zvolte prosím jiné.' && message != 'Název stránky je již použit, zvolte prosím jiný.') {
            var error = this.getMessageElement(el);
            error.innerHTML = '<span class="icon-ui-red icon-ui-red-alert"></span><span>' + message + '</span>';
        }
}

LiveForm.removeError = function(el) {
        this.removeClass(el, this.options.controlErrorClass);
        var err_el = document.getElementById(el.id + this.options.messageIdPostfix);

        if (this.options.showValid && this.showValid(el)) {
                err_el = this.getMessageElement(el);
                err_el.className = this.options.validMessageClass;
                return;
        }

        if (err_el) {
                err_el.parentNode.removeChild(err_el);                
        }
}

LiveForm.showValid = function(el) {
        var showValid = true;
        if(el.type) {
                var type = el.type.toLowerCase();
                if(type == 'checkbox' || type == 'radio')
                        showValid = false;
        }
        var rules = rules || eval('[' + (el.getAttribute('data-Nette-rules') || '') + ']');
        if(rules.length == 0) {
                showValid = false;
        }

        if (this.hasClass(el, 'dont-show-when-valid')) {
                return false;
        }

        return showValid;
}

// if needed CHANGE these handlers to use jQuery events instead
LiveForm.setUpHandlers = function(el) {
        if (!this.hasClass(el, 'no-js-validation')) {
                var handler = function(event) {
                        event = event || window.event;
                        Nette.validateControl(event.target ? event.target : event.srcElement);
                };

                var self = this;

                el.onchange = handler;
                el.onblur = handler;
                el.onkeydown = function (event) {
                        if (self.options.wait >= 200) {
                                // Hide validation span tag.
                                self.removeClass(this, self.options.controlErrorClass);
                                self.removeClass(this, self.options.validMessageClass);
                                var error = self.getMessageElement(this);
                                error.innerHTML = '';
                                error.className = '';
                                // Cancel timeout to run validation handler
                                if (self.timeout) {
                                        clearTimeout(self.timeout);
                                }
                        }
                }
                el.onkeyup = function(event) {
                        event = event || window.event;
                        if (event.keyCode !== 9) {
                                if (self.timeout) clearTimeout(self.timeout);
                                        self.timeout = setTimeout(function() {
                                        handler(event);
                                }, self.options.wait);
                        }
                };
        }
}

LiveForm.getMessageElement = function(el) {
        id = el.id + this.options.messageIdPostfix;
                
        var error = document.getElementById(id)                
            if (!error) { 
                    error = document.createElement(this.options.messageTag);                     
                    error.id = id;                     
                    el.parentNode.appendChild(error);                             
            }
            if (el.style.display == 'none')
                    error.style.display = 'none';                   
            error.className = this.options.errorMessageClass;
            error.innerHTML = '';        

        return error;
}

LiveForm.addClass = function(el, className) {
        if (!el.className) {
                el.className = className;
        } else if (!this.hasClass(el, className)) {
                el.className += ' ' + className;
        }
}

LiveForm.hasClass = function(el, className) {
        if (el.className)
                return el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
        return false;
}

LiveForm.removeClass = function(el, className) {
        if (this.hasClass(el, className)) {
                var reg = new RegExp('(\\s|^)'+ className + '(\\s|$)');
                var m = el.className.match(reg);
                el.className = el.className.replace(reg, (m[1] == ' ' && m[2] == ' ') ? ' ' : '');
        }
}

///////////////////////////////////////////////////////////////////////////////////////////

var Nette = Nette || { };

Nette.addEvent = function (element, on, callback) {
        var original = element['on' + on];
        element['on' + on] = function () {
                if (typeof original === 'function' && original.apply(element, arguments) === false) {
                        return false;
                }
                return callback.apply(element, arguments);
        };
};


Nette.getValue = function(elem) {
        var i, len;
        if (!elem) {
                return null;

        } else if (!elem.nodeName) { // radio
                for (i = 0, len = elem.length; i < len; i++) {
                        if (elem[i].checked) {
                                return elem[i].value;
                        }
                }
                return null;

        } else if (elem.nodeName.toLowerCase() === 'select') {
                var index = elem.selectedIndex, options = elem.options;

                if (index < 0) {
                        return null;

                } else if (elem.type === 'select-one') {
                        return options[index].value;
                }

                for (i = 0, values = [], len = options.length; i < len; i++) {
                        if (options[i].selected) {
                                values.push(options[i].value);
                        }
                }
                return values;

        } else if (elem.type === 'checkbox') {
                return elem.checked;

        } else if (elem.type === 'radio') {
                return Nette.getValue(elem.form.elements[elem.name]);

        } else {
                return elem.value.replace(/^\s+|\s+$/g, '');
        }
};


Nette.validateControl = function(elem, rules, onlyCheck) {
        rules = rules || eval('[' + (elem.getAttribute('data-nette-rules') || '') + ']');
        for (var id in rules) {
                var rule = rules[id], op = rule.op.match(/(~)?([^?]+)/);
                rule.neg = op[1];
                rule.op = op[2];
                rule.condition = !!rule.rules;
                var el = rule.control ? elem.form.elements[rule.control] : elem;

                var success = Nette.validateRule(el, rule.op, rule.arg);
                if (success === null) continue;
                if (rule.neg) success = !success;

                // user specific rule - username availibility check
                if (elem && rule.op == "\\AdminModule\\AdminDefaultPresenter::userexists") {
                    var classroom = elem.className;
                    var isThereError = classroom.indexOf('object_error');
                    if (isThereError != -1) {                                                
                        success = false;
                    }                             
                }

                // user specific rule - subdomain availibility check
                if (elem && rule.op == "\\AdminModule\\AdminDefaultPresenter::subdomainexists") {
                    var classroom_s = elem.className;
                    var isThereError_s = classroom_s.indexOf('object_error');
                    if (isThereError_s != -1) {                                                
                        success = false;
                    }                             
                }

                if (rule.condition && success) {
                        if (!Nette.validateControl(elem, rule.rules, onlyCheck)) {
                                return false;
                        }
                } else if (!rule.condition && !success) {
                        if (el.disabled) continue;
                        if (!onlyCheck) {
                                Nette.addError(el, rule.msg.replace('%value', Nette.getValue(el)));
                        }
                        return false;
                }
        }
        if (!onlyCheck) {
                LiveForm.removeError(elem);
        }
        return true;
};


Nette.validateForm = function(sender) {
        var form = sender.form || sender;
        LiveForm.forms[form.id].hasError = false;

        if (form['nette-submittedBy'] && form['nette-submittedBy'].getAttribute('formnovalidate') !== null) {
                return true;
        }
        var ok = true;
        for (var i = 0; i < form.elements.length; i++) {
                var elem = form.elements[i];                
                if (!(elem.nodeName.toLowerCase() in {
                        input:1,
                        select:1,
                        textarea:1
                }) || (elem.type in {
                        hidden:1,
                        submit:1,
                        image:1,
                        reset: 1
                }) || elem.disabled || elem.readonly) {
                        continue;
                }
                if (!Nette.validateControl(elem)) {
                        ok = false;
                }
        }
        return ok;
};


Nette.addError = function(elem, message) {
        if (elem.focus && !LiveForm.forms[elem.form.id].hasError) {
                elem.focus();
        }
        LiveForm.addError(elem, message);
};


Nette.validateRule = function(elem, op, arg) {
        var val = Nette.getValue(elem);

        if (elem.getAttribute) {
                if (val === elem.getAttribute('data-nette-empty-value')) {
                        val = '';
                }
        }

        if (op.charAt(0) === ':') {
                op = op.substr(1);
        }
        op = op.replace('::', '_');
        op = op.replace('\\', '');
        op = op.replace('\\', '');
        return Nette.validators[op] ? Nette.validators[op](elem, arg, val) : null;
};

function strtr(str, list)
{
    for( var c in list)
    {
        str = String(str).replace(new RegExp(c, "g"), list[c])
    }
    return str
}

Nette.validators = {

        AdminPresenter_itemnamecheck: function(elem, args, val) {
            var transfTable = {
            'ä' : 'a',
            'Ä' : 'A',
            'á' : 'a',
            'Á' : 'A',
            'à' : 'a',
            'À' : 'A',
            'ã' : 'a',
            'Ã' : 'A',
            'â' : 'a',
            'Â' : 'A',
            'č' : 'c',
            'Č' : 'C',
            'ć' : 'c',
            'Ć' : 'C',
            'ď' : 'd',
            'Ď' : 'D',
            'ě' : 'e',
            'Ě' : 'E',
            'é' : 'e',
            'É' : 'E',
            'ë' : 'e',
            'Ë' : 'E',
            'è' : 'e',
            'È' : 'E',
            'ê' : 'e',
            'Ê' : 'E',
            'í' : 'i',
            'Í' : 'I',
            'ï' : 'i',
            'Ï' : 'I',
            'ì' : 'i',
            'Ì' : 'I',
            'î' : 'i',
            'Î' : 'I',
            'ľ' : 'l',
            'Ľ' : 'L',
            'ĺ' : 'l',
            'Ĺ' : 'L',
            'ń' : 'n',
            'Ń' : 'N',
            'ň' : 'n',
            'Ň' : 'N',
            'ñ' : 'n',
            'Ñ' : 'N',
            'ó' : 'o',
            'Ó' : 'O',
            'ö' : 'o',
            'Ö' : 'O',
            'ô' : 'o',
            'Ô' : 'O',
            'ò' : 'o',
            'Ò' : 'O',
            'õ' : 'o',
            'Õ' : 'O',
            'ő' : 'o',
            'Ő' : 'O',
            'ř' : 'r',
            'Ř' : 'R',
            'ŕ' : 'r',
            'Ŕ' : 'R',
            'š' : 's',
            'Š' : 'S',
            'ś' : 's',
            'Ś' : 'S',
            'ť' : 't',
            'Ť' : 'T',
            'ú' : 'u',
            'Ú' : 'U',
            'ů' : 'u',
            'Ů' : 'U',
            'ü' : 'u',
            'Ü' : 'U',
            'ù' : 'u',
            'Ù' : 'U',
            'ũ' : 'u',
            'Ũ' : 'U',
            'û' : 'u',
            'Û' : 'U',
            'ý' : 'y',
            'Ý' : 'Y',
            'ž' : 'z',
            'Ž' : 'Z',
            'ź' : 'z',
            'Ź' : 'Z'
            };
            
            var valNoDiacritics = strtr(val, transfTable);
            var valNoDiacriticsLowerCase = valNoDiacritics.toLowerCase();            
            
            if (valNoDiacriticsLowerCase.match(/^[a-zA-Z0-9\s]+$/)) {            
                return true;
            } else {
                return false;
            }                        
        },

        AdminModuleAdminDefaultPresenter_userexists: function(elem, args, val) {
                       
//            $.getJSON('http://localhost/admin/admin-default/?do=autoComplete',{'text':$('#frmregUserForm-username').val()},function(payload) {            
//                if (payload.autoComplete[0] == 'notok') {
////                    $("#frmregUserForm-username").removeClass("object_ok");
////                    $("#frmregUserForm-username").addClass("object_error");                
////                    $("#usernameAvailableStatus").html('&nbsp;<span id="username_stats" class="icon-ui-red icon-ui-red-close"></span><span>Jméno je již použito!</span>');                
//                    return false;
//                } else {
////                    $("#frmregUserForm-username").removeClass('object_error'); // if necessary
////                    $("#frmregUserForm-username").addClass("object_ok");                
////                    $("#usernameAvailableStatus").html('&nbsp;<span id="username_stats" class="icon-ui-green icon-ui-green-tick"></span><span>Jméno je k dispozici</span>');                    
//                    return true;
//                }
//            });            
                        
//             var classroom = elem.parentNode.className;
//             var isThereError = classroom.indexOf('frmregUserForm-username_message');
//             if (isThereError != -1) {
//                return false;
//             } else {
//                return true;        
//             }
            
            return true;
        },  

        AdminModuleAdminDefaultPresenter_subdomainexists: function(elem, args, val) {
            return true;
        },

        filled: function(elem, arg, val) {                
                return val !== '' && val !== false && val !== null;
        },

        valid: function(elem, arg, val) {
                return Nette.validateControl(elem, null, true);
        },

        equal: function(elem, arg, val) {
                if (arg === undefined) {
                        return null;
                }
                arg = Nette.isArray(arg) ? arg : [arg];
                for (var i = 0, len = arg.length; i < len; i++) {
                        if (val == (arg[i].control ? Nette.getValue(elem.form.elements[arg[i].control]) : arg[i])) {
                                return true;
                        }
                }
                return false;
        },

        minLength: function(elem, arg, val) {
                return val.length >= arg;
        },

        maxLength: function(elem, arg, val) {
                return val.length <= arg;
        },

        length: function(elem, arg, val) {
                arg = Nette.isArray(arg) ? arg : [arg, arg];
                return (arg[0] === null || val.length >= arg[0]) && (arg[1] === null || val.length <= arg[1]);
        },

        email: function(elem, arg, val) {
                return (/^[^@\s]+@[^@\s]+\.[a-z]{2,10}$/i).test(val);
        },

        url: function(elem, arg, val) {
                return (/^.+\.[a-z]{2,6}(\/.*)?$/i).test(val);
        },

        regexp: function(elem, arg, val) {
                var parts = typeof arg === 'string' ? arg.match(/^\/(.*)\/([imu]*)$/) : false;
                if (parts) {
                        try {
                                return (new RegExp(parts[1], parts[2].replace('u', ''))).test(val);
                        } catch (e) {}
                }
},

pattern: function(elem, arg, val) {
        try {
                return typeof arg === 'string' ? (new RegExp('^(' + arg + ')$')).test(val) : null;
        } catch (e) {}
},

integer: function(elem, arg, val) {
        return (/^-?[0-9]+$/).test(val);
},

'float': function(elem, arg, val) {
        return (/^-?[0-9]*[.,]?[0-9]+$/).test(val);
},

range: function(elem, arg, val) {
        return Nette.isArray(arg) ? ((arg[0] === null || parseFloat(val) >= arg[0]) && (arg[1] === null || parseFloat(val) <= arg[1])) : null;
},

submitted: function(elem, arg, val) {
        return elem.form['nette-submittedBy'] === elem;
}
};


Nette.toggleForm = function(form) {
        for (var i = 0; i < form.elements.length; i++) {
                if (form.elements[i].nodeName.toLowerCase() in {
                        input:1,
                        select:1,
                        textarea:1,
                        button:1
                }) {
                        Nette.toggleControl(form.elements[i]);
                }
        }
};


Nette.toggleControl = function(elem, rules, firsttime) {
        rules = rules || eval('[' + (elem.getAttribute('data-nette-rules') || '') + ']');
        var has = false, __hasProp = Object.prototype.hasOwnProperty, handler = function() {
                Nette.toggleForm(elem.form);
        };

        for (var id in rules) {
                var rule = rules[id], op = rule.op.match(/(~)?([^?]+)/);
                rule.neg = op[1];
                rule.op = op[2];
                rule.condition = !!rule.rules;
                if (!rule.condition) continue;

                var el = rule.control ? elem.form.elements[rule.control] : elem;
                var success = Nette.validateRule(el, rule.op, rule.arg);
                if (success === null) continue;
                if (rule.neg) success = !success;

                if (Nette.toggleControl(elem, rule.rules, firsttime) || rule.toggle) {
                        has = true;
                        if (firsttime) {
                                if (!el.nodeName) { // radio
                                        for (var i = 0; i < el.length; i++) {
                                                Nette.addEvent(el[i], 'click', handler);
                                        }
                                } else if (el.nodeName.toLowerCase() === 'select') {
                                        Nette.addEvent(el, 'change', handler);
                                } else {
                                        Nette.addEvent(el, 'click', handler);
                                }
                        }
                        for (var id2 in rule.toggle || []) {
                                if (__hasProp.call(rule.toggle, id2)) {
                                        Nette.toggle(id2, success ? rule.toggle[id2] : !rule.toggle[id2]);
                                }
                        }
                }
        }
        return has;
};


Nette.toggle = function(id, visible) {
        var elem = document.getElementById(id);
        if (elem) {
                elem.style.display = visible ? "" : "none";
        }
};


Nette.initForm = function(form) {
        form.noValidate = true;

        LiveForm.forms[form.id] = {
                hasError: false
        };

        Nette.addEvent(form, 'submit', function() {
                return Nette.validateForm(form);
        });

        Nette.addEvent(form, 'click', function(e) {
                e = e || event;
                var target = e.target || e.srcElement;
                form['nette-submittedBy'] = (target.type in {
                        submit:1,
                        image:1
                }) ? target : null;
        });

        for (var i = 0; i < form.elements.length; i++) {
                Nette.toggleControl(form.elements[i], null, true);
                LiveForm.setUpHandlers(form.elements[i]);
        }

        if (/MSIE/.exec(navigator.userAgent)) {
                var labels = {},
                wheelHandler = function() {
                        return false;
                },
                clickHandler = function() {
                        document.getElementById(this.htmlFor).focus();
                        return false;
                };

                for (i = 0, elms = form.getElementsByTagName('label'); i < elms.length; i++) {
                        labels[elms[i].htmlFor] = elms[i];
                }

                for (i = 0, elms = form.getElementsByTagName('select'); i < elms.length; i++) {
                        Nette.addEvent(elms[i], 'mousewheel', wheelHandler); // prevents accidental change in IE
                        if (labels[elms[i].htmlId]) {
                                Nette.addEvent(labels[elms[i].htmlId], 'click', clickHandler); // prevents deselect in IE 5 - 6
                        }
                }
        }
};


Nette.isArray = function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
};


Nette.addEvent(window, 'load', function () {
        for (var i = 0; i < document.forms.length; i++) {
                Nette.initForm(document.forms[i]);
        }
});

// flash messages
$("div.flash").livequery(function () {
	var el = $(this);
	setTimeout(function () {
		el.animate({"opacity": 0}, 2000);
		el.slideUp();
	}, 9000);
});