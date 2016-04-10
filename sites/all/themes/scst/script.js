$(document).ready(function(){

$('#main-content form:not(.filter) :input:visible:first').focus()
});


///////////alphanumeric without space

function alphanumericspace(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\b\ta-zA-Z0-9]$/;
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







function onlychar(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z\s]$/;
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


function textCounter(field,maxlimit) {
if (field.value.length > maxlimit) // if too long...trim it!
field.value = field.value.substring(0, maxlimit);
// otherwise, update 'characters left' counter
else
cntfield.value = maxlimit - field.value.length;
}





function addressvalidation(e)
{
    var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegextab  =/[\b\t]/;
		 if (AllowRegextab.test(character)==true){
			  return true;
			  //alert("here");
		}
		else if(AllowRegextab.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
			 return true;
		}
		else {
		}


    var AllowRegex  = /^[\ba-zA-Z\s\.\,\-\/\(\)%&]$/;
    var AllowRegex1  =/[0-9\b\t]/;

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
function textonlywithdotnemax(e,id,maxlimit){

 var valn = document.getElementById(id).value;
    var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegextab  =/[\b\t]/;
		 if (AllowRegextab.test(character)==true && valn.length > maxlimit){
			  return true;
			  //alert("here");
		}
		else if(valn.length > maxlimit && AllowRegextab.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
			 return true;
		}
		else {
		 if(valn.length > maxlimit){
		   return false;
		 }
		}


 if(valn.length > maxlimit){
   return false;
 }
     
    var AllowRegex  = /^[\ba-zA-Z\s\.\,\-\/\(\)]$/;
    var AllowRegex1  =/[0-9\b\t]/;

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

function textonlywithdotnemaxquery(e,maxlimit){

 var valn = document.getElementById("query").value;
    var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegextab  =/[\b\t]/;
		 if (AllowRegextab.test(character)==true && valn.length > maxlimit){
			  return true;
			  //alert("here");
		}
		else if(valn.length > maxlimit && AllowRegextab.test(character)==true || (e.keyCode==37)|| (e.keyCode==39)|| (e.keyCode==46)){
			 return true;
		}
		else {
		 if(valn.length > maxlimit){
		   return false;
		 }
		}


 if(valn.length > maxlimit){
   return false;
 }
     
    var AllowRegex  = /^[\ba-zA-Z\s\.\,\-\/\(\)]$/;
    var AllowRegex1  =/[0-9\b\t]/;

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


function textnospeno(e,id,maxlimit){
 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }
        var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
    var AllowRegex  = /^[\ba-zA-Z\s ]$/;
    var AllowRegex1  =/^[\ba-zA-Z\s ]$/;

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


function fononlyn_custom(e,id,maxlimit){

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
}

function paypay_custom(e,id,maxlimit){
 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }
    
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\0-9.]$/;
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


function textnospe(e,id,maxlimit){
 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }
        var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
    var AllowRegex  = /^[\ba-zA-Z\s ]$/;
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



function keywords(e,id,maxlimit){
 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }
        var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
    var AllowRegex  = /^[\ba-zA-Z\s\, ]$/;
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



 function textonlywithdotne(e){
        var code;
	
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;
        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
    var AllowRegex  = /^[\ba-zA-Z0-9\s\.\,\-\/\(\)]$/;
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
		 var AllowRegex  = /^[\ba-zA-Z.\s\(\)]$/;
        <!--/*//var AllowRegex  = /^[\ba-zA-Z.\s\&\(\)]$/;*/-->
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
////alphanumeric2

function alphanumeric2(e){
var code;


        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z0-9- \t]$/;
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


///amt

function paypay(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        //var AllowRegex  = /^[\0-9\b\t\.]$/;
        var AllowRegex  = /^[0-9.\b\t]$/;
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

function emailvali(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\b\ta-zA-Z0-9-\.\@\_\- ]$/;
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


//alphabet

function alphabet(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
      //  var AllowRegex  = /^([\b0-9]+(\b\.\[0-9]+)?$)/;
		 var AllowRegex  = /^[\b\ta-zA-Z ]$/;
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


///

/////
function alphanumeric(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\b\ta-zA-Z0-9 ]$/;
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

///alphanumeric with dot



function alphanumericdot(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\b\ta-zA-Z0-9\.\ ]$/;
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






/////////


function alphanumeric_custom(e,id,maxlimit){

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

         var AllowRegex  =/^[\ba-zA-Z0-9 \b\t]$/;
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

///alphanumeric1

function alphanumeric1(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\ba-zA-Z0-9 ]$/;
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

////
function textcoursename(e){
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
       <!--// /*var AllowRegex  = /^[\ba-zA-Z.\s\&\(\)]$/;*/-->
		 var AllowRegex  = /^[\ba-zA-Z.\s\(\)]$/;
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
function validPhone(e) {
	
var code;
if (!e) var e = window.event;
if (e.keyCode) code = e.keyCode;
else if (e.which) code = e.which;
var character = String.fromCharCode(code);
    //var AllowRegex  = /[0-9\b\t]|\+\-/;
         var AllowRegex  =/[0-9\-\b\t]/;
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
        var AllowRegex  = /^[\ba-zA-Z\s\(\)]$/;
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

function paypay_customher(e,id,maxlimit){
 var valn = document.getElementById(id).value;
 
 if(valn.length > maxlimit){
   return false;
 }
    
var code;
        if (!e) var e = window.event;
        if (e.keyCode) code = e.keyCode;

        else if (e.which) code = e.which;
        var character = String.fromCharCode(code);
        var AllowRegex  = /^[\0-9.\t]$/;
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
         if (AllowRegex.test(character)==true || (e.keyCode==48)|| (e.keyCode==57) || (e.keyCode==57)){
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
