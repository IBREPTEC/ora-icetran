/*
 * 
 * Lince Form Bundle
 * @author Ícaro Guerreiro < i@icaroguerreiro.com >
 * @version 0.3
 *
 * Add class in your inputs: 'input-cep', 'input-cpf', 'input-datebr', 'input-tel', 'input-real', 'input-alpha', 'input-numeric' or 'input-email'.
 * 
 */

// formrestrict.js < https://github.com/treyhunner/jquery-formrestrict/ >
(function(e){"use strict";var t="input";if(!("oninput"in document||"oninput"in e("<input>")[0])){t+=" keypress keyup"}jQuery.fn.restrict=function(n){return this.each(function(){var r=e(this);r.bind(t,function(){var e=r.val();var t=n(e);if(e!==t){r.val(t)}})})};jQuery.fn.regexRestrict=function(t){var n=function(e){return e.replace(t,"")};e(this).restrict(n)}})(jQuery);
// alphanumeric.js < https://github.com/johnantoni/jquery.alphanumeric/ >
(function(e){jQuery.fn.alphanumeric=function(e){t(this,e,true,true,false)};jQuery.fn.numeric=function(e){t(this,e,false,true,false)};jQuery.fn.alpha=function(e){t(this,e,true,false,false)};jQuery.fn.alphanumericSpaces=function(e){t(this,e,true,true,true)};jQuery.fn.numericSpaces=function(e){t(this,e,false,true,true)};jQuery.fn.alphaSpaces=function(e){t(this,e,true,false,true)};var t=function(t,n,r,i,s){var o="";if(s)o+=" ";if(i)o+="0-9";if(r){if(n==undefined||!n.allcaps)o+="a-z";if(n==undefined||!n.nocaps)o+="A-Z"}if(n!=undefined&&n.allow!=undefined)o+=RegExp.escape(n.allow);e(t).regexRestrict(RegExp("[^"+o+"]","g"))}})(jQuery);RegExp.escape=function(e){return e.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")};
// maskedinput.js < https://github.com/digitalBush/jquery.maskedinput/ >
(function(e){function t(){var e=document.createElement("input"),t="onpaste";e.setAttribute(t,"");return typeof e[t]==="function"?"paste":"input"}var n=t()+".mask",r=navigator.userAgent,i=/iphone/i.test(r),s=/android/i.test(r),o;e.mask={definitions:{9:"[0-9]",z:"[A-Za-z]","*":"[A-Za-z0-9]"},dataName:"rawMaskFn",placeholder:"_"};e.fn.extend({caret:function(e,t){var n;if(this.length===0||this.is(":hidden")){return}if(typeof e=="number"){t=typeof t==="number"?t:e;return this.each(function(){if(this.setSelectionRange){this.setSelectionRange(e,t)}else if(this.createTextRange){n=this.createTextRange();n.collapse(true);n.moveEnd("character",t);n.moveStart("character",e);n.select()}})}else{if(this[0].setSelectionRange){e=this[0].selectionStart;t=this[0].selectionEnd}else if(document.selection&&document.selection.createRange){n=document.selection.createRange();e=0-n.duplicate().moveStart("character",-1e5);t=e+n.text.length}return{begin:e,end:t}}},unmask:function(){return this.trigger("unmask")},mask:function(t,r){var u,a,f,l,c,h;if(!t&&this.length>0){u=e(this[0]);return u.data(e.mask.dataName)()}r=e.extend({placeholder:e.mask.placeholder,completed:null},r);a=e.mask.definitions;f=[];l=h=t.length;c=null;e.each(t.split(""),function(e,t){if(t=="?"){h--;l=e}else if(a[t]){f.push(new RegExp(a[t]));if(c===null){c=f.length-1}}else{f.push(null)}});return this.trigger("unmask").each(function(){function v(e){while(++e<h&&!f[e]);return e}function m(e){while(--e>=0&&!f[e]);return e}function g(e,t){var n,i;if(e<0){return}for(n=e,i=v(t);n<h;n++){if(f[n]){if(i<h&&f[n].test(p[i])){p[n]=p[i];p[i]=r.placeholder}else{break}i=v(i)}}S();u.caret(Math.max(c,e))}function y(e){var t,n,i,s;for(t=e,n=r.placeholder;t<h;t++){if(f[t]){i=v(t);s=p[t];p[t]=n;if(i<h&&f[i].test(s)){n=s}else{break}}}}function b(e){var t=e.which,n,r,s;if(t===8||t===46||i&&t===127){n=u.caret();r=n.begin;s=n.end;if(s-r===0){r=t!==46?m(r):s=v(r-1);s=t===46?v(s):s}E(r,s);g(r,s-1);e.preventDefault()}else if(t==27){u.val(d);u.caret(0,x());e.preventDefault()}}function w(t){var n=t.which,i=u.caret(),o,a,l;if(t.ctrlKey||t.altKey||t.metaKey||n<32){return}else if(n){if(i.end-i.begin!==0){E(i.begin,i.end);g(i.begin,i.end-1)}o=v(i.begin-1);if(o<h){a=String.fromCharCode(n);if(f[o].test(a)){y(o);p[o]=a;S();l=v(o);if(s){setTimeout(e.proxy(e.fn.caret,u,l),0)}else{u.caret(l)}if(r.completed&&l>=h){r.completed.call(u)}}}t.preventDefault()}}function E(e,t){var n;for(n=e;n<t&&n<h;n++){if(f[n]){p[n]=r.placeholder}}}function S(){u.val(p.join(""))}function x(e){var t=u.val(),n=-1,i,s;for(i=0,pos=0;i<h;i++){if(f[i]){p[i]=r.placeholder;while(pos++<t.length){s=t.charAt(pos-1);if(f[i].test(s)){p[i]=s;n=i;break}}if(pos>t.length){break}}else if(p[i]===t.charAt(pos)&&i!==l){pos++;n=i}}if(e){S()}else if(n+1<l){u.val("");E(0,h)}else{S();u.val(u.val().substring(0,n+1))}return l?i:c}var u=e(this),p=e.map(t.split(""),function(e,t){if(e!="?"){return a[e]?r.placeholder:e}}),d=u.val();u.data(e.mask.dataName,function(){return e.map(p,function(e,t){return f[t]&&e!=r.placeholder?e:null}).join("")});if(!u.attr("readonly"))u.one("unmask",function(){u.unbind(".mask").removeData(e.mask.dataName)}).bind("focus.mask",function(){clearTimeout(o);var e,n;d=u.val();e=x();o=setTimeout(function(){S();if(e==t.length){u.caret(0,e)}else{u.caret(e)}},10)}).bind("blur.mask",function(){x();if(u.val()!=d)u.change()}).bind("keydown.mask",b).bind("keypress.mask",w).bind(n,function(){setTimeout(function(){var e=x(true);u.caret(e);if(r.completed&&e==u.val().length)r.completed.call(u)},0)});x()})}})})(jQuery);
// maskMoney.js < https://github.com/plentz/jquery-maskmoney/ >
(function(e){if(!e.browser){e.browser={};e.browser.mozilla=/mozilla/.test(navigator.userAgent.toLowerCase())&&!/webkit/.test(navigator.userAgent.toLowerCase());e.browser.webkit=/webkit/.test(navigator.userAgent.toLowerCase());e.browser.opera=/opera/.test(navigator.userAgent.toLowerCase());e.browser.msie=/msie/.test(navigator.userAgent.toLowerCase())}var t={destroy:function(){var t=e(this);t.unbind(".maskMoney");if(e.browser.msie){this.onpaste=null}return this},mask:function(){return this.trigger("mask")},init:function(t){t=e.extend({symbol:"",symbolStay:false,thousands:",",decimal:".",precision:2,defaultZero:true,allowZero:false,allowNegative:false},t);return this.each(function(){function i(){r=true}function s(){r=false}function o(t){t=t||window.event;var o=t.which||t.charCode||t.keyCode;if(o==undefined)return false;if(o<48||o>57){if(o==45){i();n.val(g(n));return false}else if(o==43){i();n.val(n.val().replace("-",""));return false}else if(o==13||o==9){if(r){s();e(this).change()}return true}else if(e.browser.mozilla&&(o==37||o==39)&&t.charCode==0){return true}else{c(t);return true}}else if(u(n)){return false}else{c(t);var a=String.fromCharCode(o);var f=n.get(0);var l=b(f);var p=l.start;var d=l.end;f.value=f.value.substring(0,p)+a+f.value.substring(d,f.value.length);h(f,p+1);i();return false}}function u(e){var t=e.val().length>=e.attr("maxlength")&&e.attr("maxlength")>=0;var n=b(e.get(0));var r=n.start;var i=n.end;var s=n.start!=n.end&&e.val().substring(r,i).match(/\d/)?true:false;return t&&!s}function a(t){t=t||window.event;var o=t.which||t.charCode||t.keyCode;if(o==undefined)return false;var u=n.get(0);var a=b(u);var f=a.start;var l=a.end;if(o==8){c(t);if(f==l){u.value=u.value.substring(0,f-1)+u.value.substring(l,u.value.length);f=f-1}else{u.value=u.value.substring(0,f)+u.value.substring(l,u.value.length)}h(u,f);i();return false}else if(o==9){if(r){e(this).change();s()}return true}else if(o==46||o==63272){c(t);if(u.selectionStart==u.selectionEnd){u.value=u.value.substring(0,f)+u.value.substring(l+1,u.value.length)}else{u.value=u.value.substring(0,f)+u.value.substring(l,u.value.length)}h(u,f);i();return false}else{return true}}function f(e){var r=v();if(n.val()==r){n.val("")}else if(n.val()==""&&t.defaultZero){n.val(m(r))}else{n.val(m(n.val()))}if(this.createTextRange){var i=this.createTextRange();i.collapse(false);i.select()}}function l(r){if(e.browser.msie){o(r)}if(n.val()==""||n.val()==m(v())||n.val()==t.symbol){if(!t.allowZero){n.val("")}else if(!t.symbolStay){n.val(v())}else{n.val(m(v()))}}else{if(!t.symbolStay){n.val(n.val().replace(t.symbol,""))}else if(t.symbolStay&&n.val()==t.symbol){n.val(m(v()))}}}function c(e){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}}function h(e,t){var r=n.val().length;n.val(d(e.value));var i=n.val().length;t=t-(r-i);y(n,t)}function p(){var e=n.val();n.val(d(e))}function d(e){e=e.replace(t.symbol,"");var n="0123456789";var r=e.length;var i="",s="",o="";if(r!=0&&e.charAt(0)=="-"){e=e.replace("-","");if(t.allowNegative){o="-"}}if(r==0){if(!t.defaultZero)return s;s="0.00"}for(var u=0;u<r;u++){if(e.charAt(u)!="0"&&e.charAt(u)!=t.decimal)break}for(;u<r;u++){if(n.indexOf(e.charAt(u))!=-1)i+=e.charAt(u)}var a=parseFloat(i);a=isNaN(a)?0:a/Math.pow(10,t.precision);s=a.toFixed(t.precision);u=t.precision==0?0:1;var f,l=(s=s.split("."))[u].substr(0,t.precision);for(f=(s=s[0]).length;(f-=3)>=1;){s=s.substr(0,f)+t.thousands+s.substr(f)}return t.precision>0?m(o+s+t.decimal+l+Array(t.precision+1-l.length).join(0)):m(o+s)}function v(){var e=parseFloat("0")/Math.pow(10,t.precision);return e.toFixed(t.precision).replace(new RegExp("\\.","g"),t.decimal)}function m(e){if(t.symbol!=""){var n="";if(e.length!=0&&e.charAt(0)=="-"){e=e.replace("-","");n="-"}if(e.substr(0,t.symbol.length)!=t.symbol){e=n+t.symbol+e}}return e}function g(e){var n=e.val();if(t.allowNegative){if(n!=""&&n.charAt(0)=="-"){return n.replace("-","")}else{return"-"+n}}else{return n}}function y(t,n){e(t).each(function(e,t){if(t.setSelectionRange){t.focus();t.setSelectionRange(n,n)}else if(t.createTextRange){var r=t.createTextRange();r.collapse(true);r.moveEnd("character",n);r.moveStart("character",n);r.select()}});return this}function b(e){var t=0,n=0,r,i,s,o,u;if(typeof e.selectionStart=="number"&&typeof e.selectionEnd=="number"){t=e.selectionStart;n=e.selectionEnd}else{i=document.selection.createRange();if(i&&i.parentElement()==e){o=e.value.length;r=e.value.replace(/\r\n/g,"\n");s=e.createTextRange();s.moveToBookmark(i.getBookmark());u=e.createTextRange();u.collapse(false);if(s.compareEndPoints("StartToEnd",u)>-1){t=n=o}else{t=-s.moveStart("character",-o);t+=r.slice(0,t).split("\n").length-1;if(s.compareEndPoints("EndToEnd",u)>-1){n=o}else{n=-s.moveEnd("character",-o);n+=r.slice(0,n).split("\n").length-1}}}}return{start:t,end:n}}var n=e(this);var r=false;n.unbind(".maskMoney");n.bind("keypress.maskMoney",o);n.bind("keydown.maskMoney",a);n.bind("blur.maskMoney",l);n.bind("focus.maskMoney",f);n.bind("mask.maskMoney",p)})}};e.fn.maskMoney=function(n){if(t[n]){return t[n].apply(this,Array.prototype.slice.call(arguments,1))}else if(typeof n==="object"||!n){return t.init.apply(this,arguments)}else{e.error("Method "+n+" does not exist on jQuery.maskMoney")}}})(window.jQuery||window.Zepto);
// validate.js < http://rickharrison.github.io/validate.js/ >
(function(e){e.extend(e.fn,{validate:function(t){if(!this.length){if(t&&t.debug&&window.console){console.warn("Nothing selected, can't validate, returning nothing.")}return}var n=e.data(this[0],"validator");if(n){return n}this.attr("novalidate","novalidate");n=new e.validator(t,this[0]);e.data(this[0],"validator",n);if(n.settings.onsubmit){this.validateDelegate(":submit","click",function(t){if(n.settings.submitHandler){n.submitButton=t.target}if(e(t.target).hasClass("cancel")){n.cancelSubmit=true}if(e(t.target).attr("formnovalidate")!==undefined){n.cancelSubmit=true}});this.submit(function(t){function r(){var r;if(n.settings.submitHandler){if(n.submitButton){r=e("<input type='hidden'/>").attr("name",n.submitButton.name).val(e(n.submitButton).val()).appendTo(n.currentForm)}n.settings.submitHandler.call(n,n.currentForm,t);if(n.submitButton){r.remove()}return false}return true}if(n.settings.debug){t.preventDefault()}if(n.cancelSubmit){n.cancelSubmit=false;return r()}if(n.form()){if(n.pendingRequest){n.formSubmitted=true;return false}return r()}else{n.focusInvalid();return false}})}return n},valid:function(){if(e(this[0]).is("form")){return this.validate().form()}else{var t=true;var n=e(this[0].form).validate();this.each(function(){t=t&&n.element(this)});return t}},removeAttrs:function(t){var n={},r=this;e.each(t.split(/\s/),function(e,t){n[t]=r.attr(t);r.removeAttr(t)});return n},rules:function(t,n){var r=this[0];if(t){var i=e.data(r.form,"validator").settings;var s=i.rules;var o=e.validator.staticRules(r);switch(t){case"add":e.extend(o,e.validator.normalizeRule(n));delete o.messages;s[r.name]=o;if(n.messages){i.messages[r.name]=e.extend(i.messages[r.name],n.messages)}break;case"remove":if(!n){delete s[r.name];return o}var u={};e.each(n.split(/\s/),function(e,t){u[t]=o[t];delete o[t]});return u}}var a=e.validator.normalizeRules(e.extend({},e.validator.classRules(r),e.validator.attributeRules(r),e.validator.dataRules(r),e.validator.staticRules(r)),r);if(a.required){var f=a.required;delete a.required;a=e.extend({required:f},a)}return a}});e.extend(e.expr[":"],{blank:function(t){return!e.trim(""+e(t).val())},filled:function(t){return!!e.trim(""+e(t).val())},unchecked:function(t){return!e(t).prop("checked")}});e.validator=function(t,n){this.settings=e.extend(true,{},e.validator.defaults,t);this.currentForm=n;this.init()};e.validator.format=function(t,n){if(arguments.length===1){return function(){var n=e.makeArray(arguments);n.unshift(t);return e.validator.format.apply(this,n)}}if(arguments.length>2&&n.constructor!==Array){n=e.makeArray(arguments).slice(1)}if(n.constructor!==Array){n=[n]}e.each(n,function(e,n){t=t.replace(new RegExp("\\{"+e+"\\}","g"),function(){return n})});return t};e.extend(e.validator,{defaults:{messages:{},groups:{},rules:{},errorClass:"error",validClass:"valid",errorElement:"label",focusInvalid:true,errorContainer:e([]),errorLabelContainer:e([]),onsubmit:true,ignore:":hidden",ignoreTitle:false,onfocusin:function(e,t){this.lastActive=e;if(this.settings.focusCleanup&&!this.blockFocusCleanup){if(this.settings.unhighlight){this.settings.unhighlight.call(this,e,this.settings.errorClass,this.settings.validClass)}this.addWrapper(this.errorsFor(e)).hide()}},onfocusout:function(e,t){if(!this.checkable(e)&&(e.name in this.submitted||!this.optional(e))){this.element(e)}},onkeyup:function(e,t){if(t.which===9&&this.elementValue(e)===""){return}else if(e.name in this.submitted||e===this.lastElement){this.element(e)}},onclick:function(e,t){if(e.name in this.submitted){this.element(e)}else if(e.parentNode.name in this.submitted){this.element(e.parentNode)}},highlight:function(t,n,r){if(t.type==="radio"){this.findByName(t.name).addClass(n).removeClass(r)}else{e(t).addClass(n).removeClass(r)}},unhighlight:function(t,n,r){if(t.type==="radio"){this.findByName(t.name).removeClass(n).addClass(r)}else{e(t).removeClass(n).addClass(r)}}},setDefaults:function(t){e.extend(e.validator.defaults,t)},messages:{required:"<span class='infoErro'>Campo Obrigatório!</span>",remote:"Please fix this field.",email:"<span>Insira um email válido.</span>",url:"Please enter a valid URL.",date:"Please enter a valid date.",dateISO:"Please enter a valid date (ISO).",number:"Please enter a valid number.",digits:"Please enter only digits.",creditcard:"Please enter a valid credit card number.",equalTo:"Please enter the same value again.",maxlength:e.validator.format("Please enter no more than {0} characters."),minlength:e.validator.format(""),rangelength:e.validator.format("Please enter a value between {0} and {1} characters long."),range:e.validator.format("Please enter a value between {0} and {1}."),max:e.validator.format("Please enter a value less than or equal to {0}."),min:e.validator.format("Please enter a value greater than or equal to {0}.")},autoCreateRanges:false,prototype:{init:function(){function r(t){var n=e.data(this[0].form,"validator"),r="on"+t.type.replace(/^validate/,"");if(n.settings[r]){n.settings[r].call(n,this[0],t)}}this.labelContainer=e(this.settings.errorLabelContainer);this.errorContext=this.labelContainer.length&&this.labelContainer||e(this.currentForm);this.containers=e(this.settings.errorContainer).add(this.settings.errorLabelContainer);this.submitted={};this.valueCache={};this.pendingRequest=0;this.pending={};this.invalid={};this.reset();var t=this.groups={};e.each(this.settings.groups,function(n,r){if(typeof r==="string"){r=r.split(/\s/)}e.each(r,function(e,r){t[r]=n})});var n=this.settings.rules;e.each(n,function(t,r){n[t]=e.validator.normalizeRule(r)});e(this.currentForm).validateDelegate(":text, [type='password'], [type='file'], select, textarea, "+"[type='number'], [type='search'] ,[type='tel'], [type='url'], "+"[type='email'], [type='datetime'], [type='date'], [type='month'], "+"[type='week'], [type='time'], [type='datetime-local'], "+"[type='range'], [type='color'] ","focusin focusout keyup",r).validateDelegate("[type='radio'], [type='checkbox'], select, option","click",r);if(this.settings.invalidHandler){e(this.currentForm).bind("invalid-form.validate",this.settings.invalidHandler)}},form:function(){this.checkForm();e.extend(this.submitted,this.errorMap);this.invalid=e.extend({},this.errorMap);if(!this.valid()){e(this.currentForm).triggerHandler("invalid-form",[this])}this.showErrors();return this.valid()},checkForm:function(){this.prepareForm();for(var e=0,t=this.currentElements=this.elements();t[e];e++){this.check(t[e])}return this.valid()},element:function(t){t=this.validationTargetFor(this.clean(t));this.lastElement=t;this.prepareElement(t);this.currentElements=e(t);var n=this.check(t)!==false;if(n){delete this.invalid[t.name]}else{this.invalid[t.name]=true}if(!this.numberOfInvalids()){this.toHide=this.toHide.add(this.containers)}this.showErrors();return n},showErrors:function(t){if(t){e.extend(this.errorMap,t);this.errorList=[];for(var n in t){this.errorList.push({message:t[n],element:this.findByName(n)[0]})}this.successList=e.grep(this.successList,function(e){return!(e.name in t)})}if(this.settings.showErrors){this.settings.showErrors.call(this,this.errorMap,this.errorList)}else{this.defaultShowErrors()}},resetForm:function(){if(e.fn.resetForm){e(this.currentForm).resetForm()}this.submitted={};this.lastElement=null;this.prepareForm();this.hideErrors();this.elements().removeClass(this.settings.errorClass).removeData("previousValue")},numberOfInvalids:function(){return this.objectLength(this.invalid)},objectLength:function(e){var t=0;for(var n in e){t++}return t},hideErrors:function(){this.addWrapper(this.toHide).hide()},valid:function(){return this.size()===0},size:function(){return this.errorList.length},focusInvalid:function(){if(this.settings.focusInvalid){try{e(this.findLastActive()||this.errorList.length&&this.errorList[0].element||[]).filter(":visible").focus().trigger("focusin")}catch(t){}}},findLastActive:function(){var t=this.lastActive;return t&&e.grep(this.errorList,function(e){return e.element.name===t.name}).length===1&&t},elements:function(){var t=this,n={};return e(this.currentForm).find("input, select, textarea").not(":submit, :reset, :image, [disabled]").not(this.settings.ignore).filter(function(){if(!this.name&&t.settings.debug&&window.console){console.error("%o has no name assigned",this)}if(this.name in n||!t.objectLength(e(this).rules())){return false}n[this.name]=true;return true})},clean:function(t){return e(t)[0]},errors:function(){var t=this.settings.errorClass.replace(" ",".");return e(this.settings.errorElement+"."+t,this.errorContext)},reset:function(){this.successList=[];this.errorList=[];this.errorMap={};this.toShow=e([]);this.toHide=e([]);this.currentElements=e([])},prepareForm:function(){this.reset();this.toHide=this.errors().add(this.containers)},prepareElement:function(e){this.reset();this.toHide=this.errorsFor(e)},elementValue:function(t){var n=e(t).attr("type"),r=e(t).val();if(n==="radio"||n==="checkbox"){return e("input[name='"+e(t).attr("name")+"']:checked").val()}if(typeof r==="string"){return r.replace(/\r/g,"")}return r},check:function(t){t=this.validationTargetFor(this.clean(t));var n=e(t).rules();var r=false;var i=this.elementValue(t);var s;for(var o in n){var u={method:o,parameters:n[o]};try{s=e.validator.methods[o].call(this,i,t,u.parameters);if(s==="dependency-mismatch"){r=true;continue}r=false;if(s==="pending"){this.toHide=this.toHide.not(this.errorsFor(t));return}if(!s){this.formatAndAdd(t,u);return false}}catch(a){if(this.settings.debug&&window.console){console.log("Exception occurred when checking element "+t.id+", check the '"+u.method+"' method.",a)}throw a}}if(r){return}if(this.objectLength(n)){this.successList.push(t)}return true},customDataMessage:function(t,n){return e(t).data("msg-"+n.toLowerCase())||t.attributes&&e(t).attr("data-msg-"+n.toLowerCase())},customMessage:function(e,t){var n=this.settings.messages[e];return n&&(n.constructor===String?n:n[t])},findDefined:function(){for(var e=0;e<arguments.length;e++){if(arguments[e]!==undefined){return arguments[e]}}return undefined},defaultMessage:function(t,n){return this.findDefined(this.customMessage(t.name,n),this.customDataMessage(t,n),!this.settings.ignoreTitle&&t.title||undefined,e.validator.messages[n],"<strong>Warning: No message defined for "+t.name+"</strong>")},formatAndAdd:function(t,n){var r=this.defaultMessage(t,n.method),i=/\$?\{(\d+)\}/g;if(typeof r==="function"){r=r.call(this,n.parameters,t)}else if(i.test(r)){r=e.validator.format(r.replace(i,"{$1}"),n.parameters)}this.errorList.push({message:r,element:t});this.errorMap[t.name]=r;this.submitted[t.name]=r},addWrapper:function(e){if(this.settings.wrapper){e=e.add(e.parent(this.settings.wrapper))}return e},defaultShowErrors:function(){var e,t;for(e=0;this.errorList[e];e++){var n=this.errorList[e];if(this.settings.highlight){this.settings.highlight.call(this,n.element,this.settings.errorClass,this.settings.validClass)}this.showLabel(n.element,n.message)}if(this.errorList.length){this.toShow=this.toShow.add(this.containers)}if(this.settings.success){for(e=0;this.successList[e];e++){this.showLabel(this.successList[e])}}if(this.settings.unhighlight){for(e=0,t=this.validElements();t[e];e++){this.settings.unhighlight.call(this,t[e],this.settings.errorClass,this.settings.validClass)}}this.toHide=this.toHide.not(this.toShow);this.hideErrors();this.addWrapper(this.toShow).show()},validElements:function(){return this.currentElements.not(this.invalidElements())},invalidElements:function(){return e(this.errorList).map(function(){return this.element})},showLabel:function(t,n){var r=this.errorsFor(t);if(r.length){r.removeClass(this.settings.validClass).addClass(this.settings.errorClass);r.html(n)}else{r=e("<"+this.settings.errorElement+">").attr("for",this.idOrName(t)).addClass(this.settings.errorClass).html(n||"");if(this.settings.wrapper){r=r.hide().show().wrap("<"+this.settings.wrapper+"/>").parent()}if(!this.labelContainer.append(r).length){if(this.settings.errorPlacement){this.settings.errorPlacement(r,e(t))}else{r.insertAfter(t)}}}if(!n&&this.settings.success){r.text("");if(typeof this.settings.success==="string"){r.addClass(this.settings.success)}else{this.settings.success(r,t)}}this.toShow=this.toShow.add(r)},errorsFor:function(t){var n=this.idOrName(t);return this.errors().filter(function(){return e(this).attr("for")===n})},idOrName:function(e){return this.groups[e.name]||(this.checkable(e)?e.name:e.id||e.name)},validationTargetFor:function(e){if(this.checkable(e)){e=this.findByName(e.name).not(this.settings.ignore)[0]}return e},checkable:function(e){return/radio|checkbox/i.test(e.type)},findByName:function(t){return e(this.currentForm).find("[name='"+t+"']")},getLength:function(t,n){switch(n.nodeName.toLowerCase()){case"select":return e("option:selected",n).length;case"input":if(this.checkable(n)){return this.findByName(n.name).filter(":checked").length}}return t.length},depend:function(e,t){return this.dependTypes[typeof e]?this.dependTypes[typeof e](e,t):true},dependTypes:{"boolean":function(e,t){return e},string:function(t,n){return!!e(t,n.form).length},"function":function(e,t){return e(t)}},optional:function(t){var n=this.elementValue(t);return!e.validator.methods.required.call(this,n,t)&&"dependency-mismatch"},startRequest:function(e){if(!this.pending[e.name]){this.pendingRequest++;this.pending[e.name]=true}},stopRequest:function(t,n){this.pendingRequest--;if(this.pendingRequest<0){this.pendingRequest=0}delete this.pending[t.name];if(n&&this.pendingRequest===0&&this.formSubmitted&&this.form()){e(this.currentForm).submit();this.formSubmitted=false}else if(!n&&this.pendingRequest===0&&this.formSubmitted){e(this.currentForm).triggerHandler("invalid-form",[this]);this.formSubmitted=false}},previousValue:function(t){return e.data(t,"previousValue")||e.data(t,"previousValue",{old:null,valid:true,message:this.defaultMessage(t,"remote")})}},classRuleSettings:{required:{required:true},email:{email:true},url:{url:true},date:{date:true},dateISO:{dateISO:true},number:{number:true},digits:{digits:true},creditcard:{creditcard:true}},addClassRules:function(t,n){if(t.constructor===String){this.classRuleSettings[t]=n}else{e.extend(this.classRuleSettings,t)}},classRules:function(t){var n={};var r=e(t).attr("class");if(r){e.each(r.split(" "),function(){if(this in e.validator.classRuleSettings){e.extend(n,e.validator.classRuleSettings[this])}})}return n},attributeRules:function(t){var n={};var r=e(t);var i=r[0].getAttribute("type");for(var s in e.validator.methods){var o;if(s==="required"){o=r.get(0).getAttribute(s);if(o===""){o=true}o=!!o}else{o=r.attr(s)}if(/min|max/.test(s)&&(i===null||/number|range|text/.test(i))){o=Number(o)}if(o){n[s]=o}else if(i===s&&i!=="range"){n[s]=true}}if(n.maxlength&&/-1|2147483647|524288/.test(n.maxlength)){delete n.maxlength}return n},dataRules:function(t){var n,r,i={},s=e(t);for(n in e.validator.methods){r=s.data("rule-"+n.toLowerCase());if(r!==undefined){i[n]=r}}return i},staticRules:function(t){var n={};var r=e.data(t.form,"validator");if(r.settings.rules){n=e.validator.normalizeRule(r.settings.rules[t.name])||{}}return n},normalizeRules:function(t,n){e.each(t,function(r,i){if(i===false){delete t[r];return}if(i.param||i.depends){var s=true;switch(typeof i.depends){case"string":s=!!e(i.depends,n.form).length;break;case"function":s=i.depends.call(n,n);break}if(s){t[r]=i.param!==undefined?i.param:true}else{delete t[r]}}});e.each(t,function(r,i){t[r]=e.isFunction(i)?i(n):i});e.each(["minlength","maxlength"],function(){if(t[this]){t[this]=Number(t[this])}});e.each(["rangelength","range"],function(){var n;if(t[this]){if(e.isArray(t[this])){t[this]=[Number(t[this][0]),Number(t[this][1])]}else if(typeof t[this]==="string"){n=t[this].split(/[\s,]+/);t[this]=[Number(n[0]),Number(n[1])]}}});if(e.validator.autoCreateRanges){if(t.min&&t.max){t.range=[t.min,t.max];delete t.min;delete t.max}if(t.minlength&&t.maxlength){t.rangelength=[t.minlength,t.maxlength];delete t.minlength;delete t.maxlength}}return t},normalizeRule:function(t){if(typeof t==="string"){var n={};e.each(t.split(/\s/),function(){n[this]=true});t=n}return t},addMethod:function(t,n,r){e.validator.methods[t]=n;e.validator.messages[t]=r!==undefined?r:e.validator.messages[t];if(n.length<3){e.validator.addClassRules(t,e.validator.normalizeRule(t))}},methods:{required:function(t,n,r){if(!this.depend(r,n)){return"dependency-mismatch"}if(n.nodeName.toLowerCase()==="select"){var i=e(n).val();return i&&i.length>0}if(this.checkable(n)){return this.getLength(t,n)>0}return e.trim(t).length>0},email:function(e,t){return this.optional(t)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(e)},url:function(e,t){return this.optional(t)||/^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(e)},date:function(e,t){return this.optional(t)||!/Invalid|NaN/.test((new Date(e)).toString())},dateISO:function(e,t){return this.optional(t)||/^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}$/.test(e)},number:function(e,t){return this.optional(t)||/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(e)},digits:function(e,t){return this.optional(t)||/^\d+$/.test(e)},creditcard:function(e,t){if(this.optional(t)){return"dependency-mismatch"}if(/[^0-9 \-]+/.test(e)){return false}var n=0,r=0,i=false;e=e.replace(/\D/g,"");for(var s=e.length-1;s>=0;s--){var o=e.charAt(s);r=parseInt(o,10);if(i){if((r*=2)>9){r-=9}}n+=r;i=!i}return n%10===0},minlength:function(t,n,r){var i=e.isArray(t)?t.length:this.getLength(e.trim(t),n);return this.optional(n)||i>=r},maxlength:function(t,n,r){var i=e.isArray(t)?t.length:this.getLength(e.trim(t),n);return this.optional(n)||i<=r},rangelength:function(t,n,r){var i=e.isArray(t)?t.length:this.getLength(e.trim(t),n);return this.optional(n)||i>=r[0]&&i<=r[1]},min:function(e,t,n){return this.optional(t)||e>=n},max:function(e,t,n){return this.optional(t)||e<=n},range:function(e,t,n){return this.optional(t)||e>=n[0]&&e<=n[1]},equalTo:function(t,n,r){var i=e(r);if(this.settings.onfocusout){i.unbind(".validate-equalTo").bind("blur.validate-equalTo",function(){e(n).valid()})}return t===i.val()},remote:function(t,n,r){if(this.optional(n)){return"dependency-mismatch"}var i=this.previousValue(n);if(!this.settings.messages[n.name]){this.settings.messages[n.name]={}}i.originalMessage=this.settings.messages[n.name].remote;this.settings.messages[n.name].remote=i.message;r=typeof r==="string"&&{url:r}||r;if(i.old===t){return i.valid}i.old=t;var s=this;this.startRequest(n);var o={};o[n.name]=t;e.ajax(e.extend(true,{url:r,mode:"abort",port:"validate"+n.name,dataType:"json",data:o,success:function(r){s.settings.messages[n.name].remote=i.originalMessage;var o=r===true||r==="true";if(o){var u=s.formSubmitted;s.prepareElement(n);s.formSubmitted=u;s.successList.push(n);delete s.invalid[n.name];s.showErrors()}else{var a={};var f=r||s.defaultMessage(n,"remote");a[n.name]=i.message=e.isFunction(f)?f(t):f;s.invalid[n.name]=true;s.showErrors(a)}i.valid=o;s.stopRequest(n,o)}},r));return"pending"}}});e.format=e.validator.format})(jQuery);(function(e){var t={};if(e.ajaxPrefilter){e.ajaxPrefilter(function(e,n,r){var i=e.port;if(e.mode==="abort"){if(t[i]){t[i].abort()}t[i]=r}})}else{var n=e.ajax;e.ajax=function(r){var i=("mode"in r?r:e.ajaxSettings).mode,s=("port"in r?r:e.ajaxSettings).port;if(i==="abort"){if(t[s]){t[s].abort()}t[s]=n.apply(this,arguments);return t[s]}return n.apply(this,arguments)}}})(jQuery);(function(e){e.extend(e.fn,{validateDelegate:function(t,n,r){return this.bind(n,function(n){var i=e(n.target);if(i.is(t)){return r.apply(i,arguments)}})}})})(jQuery);
// additional-methods.js
(function(){function e(e){return e.replace(/<.[^<>]*?>/g," ").replace(/&nbsp;|&#160;/gi," ").replace(/[.(),;:!?%#$'"_+=\/\-]*/g,"")}jQuery.validator.addMethod("maxWords",function(t,n,r){return this.optional(n)||e(t).match(/\b\w+\b/g).length<=r},jQuery.validator.format("Please enter {0} words or less."));jQuery.validator.addMethod("minWords",function(t,n,r){return this.optional(n)||e(t).match(/\b\w+\b/g).length>=r},jQuery.validator.format("Please enter at least {0} words."));jQuery.validator.addMethod("rangeWords",function(t,n,r){var i=e(t);var s=/\b\w+\b/g;return this.optional(n)||i.match(s).length>=r[0]&&i.match(s).length<=r[1]},jQuery.validator.format("Please enter between {0} and {1} words."))})();jQuery.validator.addMethod("letterswithbasicpunc",function(e,t){return this.optional(t)||/^[a-z\-.,()'"\s]+$/i.test(e)},"Letters or punctuation only please");jQuery.validator.addMethod("alphanumeric",function(e,t){return this.optional(t)||/^\w+$/i.test(e)},"Letters, numbers, and underscores only please");jQuery.validator.addMethod("lettersonly",function(e,t){return this.optional(t)||/^[a-z]+$/i.test(e)},"Letters only please");jQuery.validator.addMethod("nowhitespace",function(e,t){return this.optional(t)||/^\S+$/i.test(e)},"No white space please");jQuery.validator.addMethod("ziprange",function(e,t){return this.optional(t)||/^90[2-5]\d\{2\}-\d{4}$/.test(e)},"Your ZIP-code must be in the range 902xx-xxxx to 905-xx-xxxx");jQuery.validator.addMethod("zipcodeUS",function(e,t){return this.optional(t)||/\d{5}-\d{4}$|^\d{5}$/.test(e)},"The specified US ZIP Code is invalid");jQuery.validator.addMethod("integer",function(e,t){return this.optional(t)||/^-?\d+$/.test(e)},"A positive or negative non-decimal number please");jQuery.validator.addMethod("vinUS",function(e){if(e.length!==17){return false}var t,n,r,i,s,o;var u=["A","B","C","D","E","F","G","H","J","K","L","M","N","P","R","S","T","U","V","W","X","Y","Z"];var a=[1,2,3,4,5,6,7,8,1,2,3,4,5,7,9,2,3,4,5,6,7,8,9];var f=[8,7,6,5,4,3,2,10,0,9,8,7,6,5,4,3,2];var l=0;for(t=0;t<17;t++){i=f[t];r=e.slice(t,t+1);if(t===8){o=r}if(!isNaN(r)){r*=i}else{for(n=0;n<u.length;n++){if(r.toUpperCase()===u[n]){r=a[n];r*=i;if(isNaN(o)&&n===8){o=u[n]}break}}}l+=r}s=l%11;if(s===10){s="X"}if(s===o){return true}return false},"The specified vehicle identification number (VIN) is invalid.");jQuery.validator.addMethod("dateITA",function(e,t){var n=false;var r=/^\d{1,2}\/\d{1,2}\/\d{4}$/;if(r.test(e)){var i=e.split("/");var s=parseInt(i[0],10);var o=parseInt(i[1],10);var u=parseInt(i[2],10);var a=new Date(u,o-1,s);if(a.getFullYear()===u&&a.getMonth()===o-1&&a.getDate()===s){n=true}else{n=false}}else{n=false}return this.optional(t)||n},"Please enter a correct date");jQuery.validator.addMethod("iban",function(e,t){if(this.optional(t)){return true}if(!/^([a-zA-Z0-9]{4} ){2,8}[a-zA-Z0-9]{1,4}|[a-zA-Z0-9]{12,34}$/.test(e)){return false}var n=e.replace(/ /g,"").toUpperCase();var r=n.substring(0,2);var i={AL:"\\d{8}[\\dA-Z]{16}",AD:"\\d{8}[\\dA-Z]{12}",AT:"\\d{16}",AZ:"[\\dA-Z]{4}\\d{20}",BE:"\\d{12}",BH:"[A-Z]{4}[\\dA-Z]{14}",BA:"\\d{16}",BR:"\\d{23}[A-Z][\\dA-Z]",BG:"[A-Z]{4}\\d{6}[\\dA-Z]{8}",CR:"\\d{17}",HR:"\\d{17}",CY:"\\d{8}[\\dA-Z]{16}",CZ:"\\d{20}",DK:"\\d{14}",DO:"[A-Z]{4}\\d{20}",EE:"\\d{16}",FO:"\\d{14}",FI:"\\d{14}",FR:"\\d{10}[\\dA-Z]{11}\\d{2}",GE:"[\\dA-Z]{2}\\d{16}",DE:"\\d{18}",GI:"[A-Z]{4}[\\dA-Z]{15}",GR:"\\d{7}[\\dA-Z]{16}",GL:"\\d{14}",GT:"[\\dA-Z]{4}[\\dA-Z]{20}",HU:"\\d{24}",IS:"\\d{22}",IE:"[\\dA-Z]{4}\\d{14}",IL:"\\d{19}",IT:"[A-Z]\\d{10}[\\dA-Z]{12}",KZ:"\\d{3}[\\dA-Z]{13}",KW:"[A-Z]{4}[\\dA-Z]{22}",LV:"[A-Z]{4}[\\dA-Z]{13}",LB:"\\d{4}[\\dA-Z]{20}",LI:"\\d{5}[\\dA-Z]{12}",LT:"\\d{16}",LU:"\\d{3}[\\dA-Z]{13}",MK:"\\d{3}[\\dA-Z]{10}\\d{2}",MT:"[A-Z]{4}\\d{5}[\\dA-Z]{18}",MR:"\\d{23}",MU:"[A-Z]{4}\\d{19}[A-Z]{3}",MC:"\\d{10}[\\dA-Z]{11}\\d{2}",MD:"[\\dA-Z]{2}\\d{18}",ME:"\\d{18}",NL:"[A-Z]{4}\\d{10}",NO:"\\d{11}",PK:"[\\dA-Z]{4}\\d{16}",PS:"[\\dA-Z]{4}\\d{21}",PL:"\\d{24}",PT:"\\d{21}",RO:"[A-Z]{4}[\\dA-Z]{16}",SM:"[A-Z]\\d{10}[\\dA-Z]{12}",SA:"\\d{2}[\\dA-Z]{18}",RS:"\\d{18}",SK:"\\d{20}",SI:"\\d{15}",ES:"\\d{20}",SE:"\\d{20}",CH:"\\d{5}[\\dA-Z]{12}",TN:"\\d{20}",TR:"\\d{5}[\\dA-Z]{17}",AE:"\\d{3}\\d{16}",GB:"[A-Z]{4}\\d{14}",VG:"[\\dA-Z]{4}\\d{16}"};var s=i[r];if(typeof s!=="undefined"){var o=new RegExp("^[A-Z]{2}\\d{2}"+s+"$","");if(!o.test(n)){return false}}var u=n.substring(4,n.length)+n.substring(0,4);var a="";var f=true;var l;for(var c=0;c<u.length;c++){l=u.charAt(c);if(l!=="0"){f=false}if(!f){a+="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ".indexOf(l)}}var h="";var p="";for(var d=0;d<a.length;d++){var v=a.charAt(d);p=""+h+""+v;h=p%97}return h===1},"Please specify a valid IBAN");jQuery.validator.addMethod("dateNL",function(e,t){return this.optional(t)||/^(0?[1-9]|[12]\d|3[01])[\.\/\-](0?[1-9]|1[012])[\.\/\-]([12]\d)?(\d\d)$/.test(e)},"Please enter a correct date");jQuery.validator.addMethod("phoneNL",function(e,t){return this.optional(t)||/^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)[1-9]((\s|\s?\-\s?)?[0-9]){8}$/.test(e)},"Please specify a valid phone number.");jQuery.validator.addMethod("mobileNL",function(e,t){return this.optional(t)||/^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)6((\s|\s?\-\s?)?[0-9]){8}$/.test(e)},"Please specify a valid mobile number");jQuery.validator.addMethod("postalcodeNL",function(e,t){return this.optional(t)||/^[1-9][0-9]{3}\s?[a-zA-Z]{2}$/.test(e)},"Please specify a valid postal code");jQuery.validator.addMethod("bankaccountNL",function(e,t){if(this.optional(t)){return true}if(!/^[0-9]{9}|([0-9]{2} ){3}[0-9]{3}$/.test(e)){return false}var n=e.replace(/ /g,"");var r=0;var i=n.length;for(var s=0;s<i;s++){var o=i-s;var u=n.substring(s,s+1);r=r+o*u}return r%11===0},"Please specify a valid bank account number");jQuery.validator.addMethod("giroaccountNL",function(e,t){return this.optional(t)||/^[0-9]{1,7}$/.test(e)},"Please specify a valid giro account number");jQuery.validator.addMethod("bankorgiroaccountNL",function(e,t){return this.optional(t)||$.validator.methods["bankaccountNL"].call(this,e,t)||$.validator.methods["giroaccountNL"].call(this,e,t)},"Please specify a valid bank or giro account number");jQuery.validator.addMethod("time",function(e,t){return this.optional(t)||/^([01]\d|2[0-3])(:[0-5]\d){1,2}$/.test(e)},"Please enter a valid time, between 00:00 and 23:59");jQuery.validator.addMethod("time12h",function(e,t){return this.optional(t)||/^((0?[1-9]|1[012])(:[0-5]\d){1,2}(\ ?[AP]M))$/i.test(e)},"Please enter a valid time in 12-hour am/pm format");jQuery.validator.addMethod("phoneUS",function(e,t){e=e.replace(/\s+/g,"");return this.optional(t)||e.length>9&&e.match(/^(\+?1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/)},"Please specify a valid phone number");jQuery.validator.addMethod("phoneUK",function(e,t){e=e.replace(/\(|\)|\s+|-/g,"");return this.optional(t)||e.length>9&&e.match(/^(?:(?:(?:00\s?|\+)44\s?)|(?:\(?0))(?:\d{2}\)?\s?\d{4}\s?\d{4}|\d{3}\)?\s?\d{3}\s?\d{3,4}|\d{4}\)?\s?(?:\d{5}|\d{3}\s?\d{3})|\d{5}\)?\s?\d{4,5})$/)},"Please specify a valid phone number");jQuery.validator.addMethod("mobileUK",function(e,t){e=e.replace(/\(|\)|\s+|-/g,"");return this.optional(t)||e.length>9&&e.match(/^(?:(?:(?:00\s?|\+)44\s?|0)7(?:[45789]\d{2}|624)\s?\d{3}\s?\d{3})$/)},"Please specify a valid mobile number");jQuery.validator.addMethod("phonesUK",function(e,t){e=e.replace(/\(|\)|\s+|-/g,"");return this.optional(t)||e.length>9&&e.match(/^(?:(?:(?:00\s?|\+)44\s?|0)(?:1\d{8,9}|[23]\d{9}|7(?:[45789]\d{8}|624\d{6})))$/)},"Please specify a valid uk phone number");jQuery.validator.addMethod("postcodeUK",function(e,t){return this.optional(t)||/^((([A-PR-UWYZ][0-9])|([A-PR-UWYZ][0-9][0-9])|([A-PR-UWYZ][A-HK-Y][0-9])|([A-PR-UWYZ][A-HK-Y][0-9][0-9])|([A-PR-UWYZ][0-9][A-HJKSTUW])|([A-PR-UWYZ][A-HK-Y][0-9][ABEHMNPRVWXY]))\s?([0-9][ABD-HJLNP-UW-Z]{2})|(GIR)\s?(0AA))$/i.test(e)},"Please specify a valid UK postcode");jQuery.validator.addMethod("strippedminlength",function(e,t,n){return jQuery(e).text().length>=n},jQuery.validator.format(""));jQuery.validator.addMethod("email2",function(e,t,n){return this.optional(t)||/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i.test(e)},jQuery.validator.messages.email);jQuery.validator.addMethod("url2",function(e,t,n){return this.optional(t)||/^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)*(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(e)},jQuery.validator.messages.url);jQuery.validator.addMethod("creditcardtypes",function(e,t,n){if(/[^0-9\-]+/.test(e)){return false}e=e.replace(/\D/g,"");var r=0;if(n.mastercard){r|=1}if(n.visa){r|=2}if(n.amex){r|=4}if(n.dinersclub){r|=8}if(n.enroute){r|=16}if(n.discover){r|=32}if(n.jcb){r|=64}if(n.unknown){r|=128}if(n.all){r=1|2|4|8|16|32|64|128}if(r&1&&/^(5[12345])/.test(e)){return e.length===16}if(r&2&&/^(4)/.test(e)){return e.length===16}if(r&4&&/^(3[47])/.test(e)){return e.length===15}if(r&8&&/^(3(0[012345]|[68]))/.test(e)){return e.length===14}if(r&16&&/^(2(014|149))/.test(e)){return e.length===15}if(r&32&&/^(6011)/.test(e)){return e.length===16}if(r&64&&/^(3)/.test(e)){return e.length===16}if(r&64&&/^(2131|1800)/.test(e)){return e.length===15}if(r&128){return true}return false},"Please enter a valid credit card number.");jQuery.validator.addMethod("ipv4",function(e,t,n){return this.optional(t)||/^(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)\.(25[0-5]|2[0-4]\d|[01]?\d\d?)$/i.test(e)},"Please enter a valid IP v4 address.");jQuery.validator.addMethod("ipv6",function(e,t,n){return this.optional(t)||/^((([0-9A-Fa-f]{1,4}:){7}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}:[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){5}:([0-9A-Fa-f]{1,4}:)?[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){4}:([0-9A-Fa-f]{1,4}:){0,2}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){3}:([0-9A-Fa-f]{1,4}:){0,3}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){2}:([0-9A-Fa-f]{1,4}:){0,4}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){6}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(([0-9A-Fa-f]{1,4}:){0,5}:((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|(::([0-9A-Fa-f]{1,4}:){0,5}((\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b)\.){3}(\b((25[0-5])|(1\d{2})|(2[0-4]\d)|(\d{1,2}))\b))|([0-9A-Fa-f]{1,4}::([0-9A-Fa-f]{1,4}:){0,5}[0-9A-Fa-f]{1,4})|(::([0-9A-Fa-f]{1,4}:){0,6}[0-9A-Fa-f]{1,4})|(([0-9A-Fa-f]{1,4}:){1,7}:))$/i.test(e)},"Please enter a valid IP v6 address.");jQuery.validator.addMethod("pattern",function(e,t,n){if(this.optional(t)){return true}if(typeof n==="string"){n=new RegExp("^(?:"+n+")$")}return n.test(e)},"Invalid format.");jQuery.validator.addMethod("require_from_group",function(e,t,n){var r=this;var i=n[1];var s=$(i,t.form).filter(function(){return r.elementValue(this)}).length>=n[0];if(!$(t).data("being_validated")){var o=$(i,t.form);o.data("being_validated",true);o.valid();o.data("being_validated",false)}return s},jQuery.format("Please fill at least {0} of these fields."));jQuery.validator.addMethod("skip_or_fill_minimum",function(e,t,n){var r=this,i=n[0],s=n[1];var o=$(s,t.form).filter(function(){return r.elementValue(this)}).length;var u=o>=i||o===0;if(!$(t).data("being_validated")){var a=$(s,t.form);a.data("being_validated",true);a.valid();a.data("being_validated",false)}return u},jQuery.format("Please either skip these fields or fill at least {0} of them."));jQuery.validator.addMethod("accept",function(e,t,n){var r=typeof n==="string"?n.replace(/\s/g,"").replace(/,/g,"|"):"image/*",i=this.optional(t),s,o;if(i){return i}if($(t).attr("type")==="file"){r=r.replace(/\*/g,".*");if(t.files&&t.files.length){for(s=0;s<t.files.length;s++){o=t.files[s];if(!o.type.match(new RegExp(".?("+r+")$","i"))){return false}}}}return true},jQuery.format("Apenas extensões .jpg, .png, .pdf, .doc são aceitadas."));jQuery.validator.addMethod("extension",function(e,t,n){n=typeof n==="string"?n.replace(/,/g,"|"):"png|jpe?g|gif";return this.optional(t)||e.match(new RegExp(".("+n+")$","i"))},jQuery.format("Please enter a value with a valid extension."));

// Validating CPF
function TestaCPF(strCPF) { var Soma; var Resto; Soma = 0; if (strCPF == "00000000000") return false; for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i); Resto = (Soma * 10) % 11; if ((Resto == 10) || (Resto == 11)) Resto = 0; if (Resto != parseInt(strCPF.substring(9, 10)) ) return false; Soma = 0; for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i); Resto = (Soma * 10) % 11; if ((Resto == 10) || (Resto == 11)) Resto = 0; if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false; return true; } 
function checkCPF(cpf) {
    if(TestaCPF(cpf.replace('-', '').replace('.', '').replace('.', '')) == false
        || cpf == "111.111.111-11"
        || cpf == "222.222.222-22"
        || cpf == "333.333.333-33"
        || cpf == "444.444.444-44"
        || cpf == "555.555.555-55"
        || cpf == "666.666.666-66"
        || cpf == "777.777.777-77"
        || cpf == "888.888.888-88"
        || cpf == "999.999.999-99"
    ) {
        return false;
    } else 
        return true;
}

// Validating CEP
function checkCEPValid(cep) {

    if(!$.trim(cep)
       || cep == "00000-000"
       || cep == "11111-111"
       || cep == "22222-222"
       || cep == "33333-333"
       || cep == "44444-444"
       || cep == "55555-555"
       || cep == "66666-666"
       || cep == "77777-777"
       || cep == "88888-888"
       || cep == "99999-999"
    ){
        return false;
    } else {
        return true;
    }
}

// Validating Email
function checkEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}

// Recebe os GET
function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}

//Faz ação como se o usuário tivesse clicado no valor selecionado (Parâmetros: id do select, e valor que será clicado)
function clicarValor(id, valor) {
    $('#' + id + ' ul li').each(function(index) {
        if ($(this).attr('data-select-value') == valor) {
            $(this).click();
        }
    });
}

//Recarrega um select do id passado no parâmetro
function regarregarLinceform(id) {
    $(".input-select").each(function(index) {
        if ($(this).attr("name") == id) {
            $('#' + id).remove();
            var selectDad = index;
            if($(this).val()) {
                var placeHolder = $(this).children('option[value="'+$(this).val()+'"]').text();
            } else {
                var placeHolder = $(this).children(".select-placeholder").first().text();
            }
            var selectID = id;
            $(this).removeAttr("id");
            $(this).addClass("real-select-"+selectDad);
            $("<div id='"+selectID+"' class='select select-"+selectDad+"' data-select-index='"+selectDad+"'><span class='select-checked'>"+placeHolder+"</span><ul></ul></div>").insertBefore(this);
            $(this).children("*").each(function(index) {
                if( !$(this).hasClass("select-placeholder")){
                    var optionText = $(this).text();
                    $(".select-"+selectDad+" ul").append("<li data-select-index='"+index+"' data-select-value='"+$(this).val()+"'>"+optionText+"</li>");
                }
            });

            //Cria chamada para quando clicar no valor
            $("#" + id + " ul li").click(function() {
                var optionIndex = $(this).attr("data-select-index");
                var optionValue = $(this).attr("data-select-value");
                var selectIndex = $(this).parent().parent().attr("data-select-index");
                $(this).parent().children("li").removeClass("checked");
                $(this).addClass("checked");
                $(".real-select-"+selectIndex).val(optionValue);
                $(this).parent().parent().children("span").html( $(this).text());
            });

            $("#" + id).click(function() {
                if ($(this).hasClass("open")) {
                    $(this).removeClass("open");
                } else {
                    $("#" + id).removeClass("open");
                    $(this).addClass("open");
                }
            });
        }
    });
}

//Masks & Allow Inputs
jQuery(function($) {
    // Masks
    $('.input-cep').mask("99999-999");
    $('.input-datebr').mask("99/99/9999");
    $('.input-cpf').mask("999.999.999-99");
    // Valida e Mask do Tel
    $('.input-tel').focusout(function(){
        var phone, element;
        element = $(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    }).trigger('focusout');
    $('.input-tel').keydown(function(event) {
        // Allow special chars + arrows 
        if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 
            || event.keyCode == 27 || event.keyCode == 13 
            || (event.keyCode == 65 && event.ctrlKey === true) 
            || (event.keyCode >= 35 && event.keyCode <= 39)){
            return;
        } else {
            // If it's not a number stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }
        }   
    });
    $('.input-real').maskMoney({symbol:'R$ ', thousands:'.', decimal:',', symbolStay: true });

    // Allow Inputs
    $('.input-alpha').alphanumeric({allow:". ãõÃÕáéíóúâêîôûÁÉÍÓÚÂÊÎÔÛÇç-!@#$%&*()+/?," });
    $('.input-numeric').numeric();
    $('.input-email').alphanumeric({allow:".@_-" });

    $('.textarea').alphanumeric({allow:". ãõÃÕáéíóúâêîôûÁÉÍÓÚÂÊÎÔÛÇç-!@#$%&*()+/?," });

    // Custom Styles

	//Select
	$(".input-select").css("position","absolute").css("left","-9999px");
	$(".input-select").each(function(index) {
		var selectDad = index;
		if($(this).val()) {
			var placeHolder = $(this).children('option[value="'+$(this).val()+'"]').text();
		} else {
			var placeHolder = $(this).children(".select-placeholder").first().text();
		}
		var selectID = $(this).attr("id");
		$(this).removeAttr("id");
		$(this).addClass("real-select-"+selectDad);
		$("<div id='"+selectID+"' class='select select-"+selectDad+"' data-select-index='"+selectDad+"'><span class='select-checked'>"+placeHolder+"</span><ul></ul></div>").insertBefore(this);
		$(this).children("*").each(function(index) {
			if( !$(this).hasClass("select-placeholder")){
				var optionText = $(this).text();
				$(".select-"+selectDad+" ul").append("<li data-select-index='"+index+"' data-select-value='"+$(this).val()+"'>"+optionText+"</li>");
			}
		});
	});
	// Open Select
	$(".select").click(function() {
		if($(this).hasClass("open")) {
			$(this).removeClass("open");
		} else {
			$(".select").removeClass("open");
			$(this).addClass("open");
		}
	})
	// Close Select
	$(document).click(function(event) {
	    if( $(event.target).hasClass('select') || $(event.target).hasClass('select-checked') ) {
	    } else {
			$(".select").removeClass("open");
	    }
	});
	// Click Option Change Value
	$(".select ul li").click(function() {
		var optionIndex = $(this).attr("data-select-index");
		var optionValue = $(this).attr("data-select-value");
		var selectIndex = $(this).parent().parent().attr("data-select-index");
		$(this).parent().children("li").removeClass("checked");
		$(this).addClass("checked");
		$(".real-select-"+selectIndex).val(optionValue);
		$(this).parent().parent().children("span").html( $(this).text());
	})
	// File Input
	$(".input-file").css("position","absolute").css("left","-9999px");
	$(".input-file").each(function(index) {
		var selectID = $(this).attr("id");
		var filePlaceholder = $(this).attr("placeholder");
		$("<input type='text' class='file-archive' disabled>").insertAfter(this);
		$("<label class='label-file' for='"+selectID+"'>"+filePlaceholder+"</label>").insertAfter(this);
	});
	$(".input-file").change(function(){
		var fileName = $(this).val().replace(/\\/g, '');
		fileName = fileName.replace('C:fakepath', '');
		$(this).parent().children(".file-archive").val(fileName);
	});
    // onBlur Remove

    // Validate CEP
    $('.input-cep').blur(function() {
        $(this).parent().children("label.error").remove();
        if (!checkCEPValid($(this).val())) {
            $(this).val("");
            $(this).parent().append('<label for="" class="error"><span>Erro no CEP</span></label>')
        }
    });

    // Validate CPF
    $('.input-cpf').blur(function() {
        $(this).parent().children("label.error").remove();
        if(!checkCPF($(this).val())) {
            $(this).val("");
            $(this).parent().append('<label for="" class="error"><span>Erro no CPF</span></label>')
        }
    });

    // Validate DateBR
    $('.input-datebr').blur(function() {
        $(this).parent().children("label.error").remove();
        var datebr = $(this).val().split("/");
        if(datebr[0] > 31 || datebr[1] > 12) {
            $(this).val("");
            $(this).parent().append('<label for="" class="error"><span>Data Inválida</span></label>')
        }
    });

    // Validate Email
    $('.input-email').blur(function() {
        $(this).parent().children("label.error").remove();
        if(!checkEmail($(this).val())) {
            $(this).val("");
            $(this).parent().append('<label for="" class="error"><span>Erro no Email</span></label>');
        }
    });

    // Validate Captcha
    $('.input-captcha').blur(function() {
        $(this).parent().children("label.error").remove();
        if($(this).val() != $(this).attr("data-lince-captcha") ) {
            $(this).val("");
            $(this).parent().append('<label for="" class="error"></label>');
        }
    });

    // Validate onSubmit form
    $('form').submit(function (e) {
        $("label.error").remove();
        if( $(this).valid() ) {
            $(this).append('<input type="hidden" name="form_id" value="'+$(this).attr('id')+'">');
            return true;
        } else {
            e.preventDefault();
            alert("Preencha todos os campos");
        }
    });
});

$(document).ready(function() {

  // Desativado Validação Default do HTML5
  $( "form" ).attr( "novalidate", true );

  // Alerta de Sucesso ou Erro
  var form_id = getUrlParameter('form_id');

  if (getUrlParameter('form') == 'sucess') {
  	alertForm = $('#'+form_id).attr('data-sucess');
  	identify = $('#'+form_id).attr('identify-form');
  	if(identify == 'guia')
  	{
  		$('.form-guia').hide();
  		$('.tituloPop').hide();
  		$( ".box-download" ).show( "slow" );
  	}
    alert(alertForm);
  } else if ( getUrlParameter('form') == 'error' ) {
  	alertForm = $('#'+form_id).attr('data-error');
    alert(alertForm);
  }
  
});
