$(document).ready(function(){

$('#main-content form:not(.filter) :input:visible:first').focus()
});


function textonlywithdotne(e){
        var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
    var AllowRegex  = /^[\ba-zA-Z\s\.\,\-\/\(\)\&]$/;
    var AllowRegex1  =/[0-9\b]/;

          var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                 if (AllowRegex.test(character)==true ||
AllowRegex1.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

          if (AllowRegex.test(character)==true ||
AllowRegex1.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
        }else{
                 if (AllowRegex.test(character)==true || AllowRegex1.test(character)==true
|| (e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
               return true;
                 }
                 else{
                    return false;
                 }
          }
        }

function textonlyn(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z.\s\&\(\)]$/;
        var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                        if (AllowRegex.test(character)==true){
                        return true;
                        }
                        else{
                        return false;
                        }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

                  if (AllowRegex.test(character)==true){
                   return true;
                 }
                 else{
                        return false;
                 }
                }else{
         if (AllowRegex.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)||
(e.keyCode==46)){
           return true;
         }
         else{
                return false;
         }
  }
}

function alphanumeric(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        //var AllowRegex  = /^[\ba-zA-Z0-9]$/;
		var AllowRegex  = /^[\ba-zA-Z0-9.\s\&\(\)]$/;
        var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                        if (AllowRegex.test(character)==true){
                        return true;
                        }
                        else{
                        return false;
                        }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

                  if (AllowRegex.test(character)==true){
                   return true;
                 }
                 else{
                        return false;
                 }
                }else{
         if (AllowRegex.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)||
(e.keyCode==46)){
           return true;
         }
         else{
                return false;
         }
  }
}
function alphanumericdot(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        //var AllowRegex  = /^[\ba-zA-Z0-9]$/;
		var AllowRegex  = /^[\ba-zA-Z0-9\s\(\)]$/;
        var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                        if (AllowRegex.test(character)==true){
                        return true;
                        }
                        else{
                        return false;
                        }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

                  if (AllowRegex.test(character)==true){
                   return true;
                 }
                 else{
                        return false;
                 }
                }else{
         if (AllowRegex.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)||
(e.keyCode==46)){
           return true;
         }
         else{
                return false;
         }
  }
}

function textcoursename(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z.\s\&\(\)]$/;
        var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                        if (AllowRegex.test(character)==true){
                        return true;
                        }
                        else{
                        return false;
                        }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

                  if (AllowRegex.test(character)==true){
                   return true;
                 }
                 else{
                        return false;
                 }
                }else{
         if (AllowRegex.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)||
(e.keyCode==46)){
           return true;
         }
         else{
                return false;
         }
  }
}




function fononlyn(e) {
	
var code;
if (!e) var e = window.event;
if (e.keyCode) code = e.keyCode;
else if (e.which) code = e.which;
var character = String.fromCharCode(code);
  //  var AllowRegex  = /[0-9]|\+\-/;
         var AllowRegex  =/[0-9\b\t]/;
   var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                 if (AllowRegex.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

          if (AllowRegex.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
        }else{
                 if (AllowRegex.test(character)==true || (e.keyCode==37)||
(e.keyCode==39)||
(e.keyCode==46)){
               return true;
                 }
                 else{
                    return false;
                 }
          }
}
function textonlynw(e){
  var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z\s\(\)\&]$/;
        var AllowRegex1  =/[0-9\b]/;
        var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
        if(is_chrome){
         if (AllowRegex.test(character)==true || AllowRegex1.test(character)==true){
              return true;
         }
         else{
            return false;
         }
        }
        if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

          if (AllowRegex.test(character)==true ||
AllowRegex1.test(character)==true ||
(e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
               return true;
                 }
                 else{
                    return false;
                 }
        }else{
                 if (AllowRegex.test(character)==true || AllowRegex1.test(character)==true
|| (e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
               return true;
                 }
                 else{
                    return false;
                 }
          }

}

function paypaymain_custom(e,id,maxlimit){

 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }else{
	//alert('here');
var code;
if (!e) var e = window.event;
if (e.keyCode) code = e.keyCode;
else if (e.which) code = e.which;
var character = String.fromCharCode(code);

         var AllowRegex  =/^[0-9.\b\t]$/;
   var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
                if(is_chrome){
                 if (AllowRegex.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
                }
                if ( (navigator.appName == 'Microsoft Internet Explorer') || (is_chrome
=='true')){

          if (AllowRegex.test(character)==true){
               return true;
                 }
                 else{
                    return false;
                 }
        }else{
                 if (AllowRegex.test(character)==true || (e.keyCode==37)||
(e.keyCode==39)||
(e.keyCode==46)){
               return true;
                 }
                 else{
                    return false;
                 }
          }
 }
}
// JavaScript Document