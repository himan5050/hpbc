<?php
error_reporting(0);

global $user, $base_url,$language;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
  <?php
		   $nodec = node_load(arg(1));
		  	if($nodec->type == 'page' || $nodec->type == 'scheme_name' || $nodec->type == 'application_forms' || $nodec->type == 'list_scst' || $nodec->type == 'story' || $nodec->type == 'latestnews' || $nodec->type == 'welcome' || $nodec->type == 'scheduled'|| $nodec->type == 'cast'){
			echo '<style type="text/css">.field-label{display:none;}</style>';
			
			}else{
			  $title = "";
			  echo '<style type="text/css">ul.primary li a {display:none; border:none;}ul.primary {
    border: 0;
    border-collapse: collapse;
    height: auto;
    line-height: normal;
    list-style: none outside none;
    margin: 5px;
    padding: 0 0 0 1em;
    white-space: nowrap;
}</style>';
			}
			?>
            
 <link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/template.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/constant.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/tabs.css" type="text/css" />      
            
<script language="javascript" type="text/javascript">



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
    var AllowRegex  = /^[\ba-zA-Z\s\.\,\-\/\(\)]$/;
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
        var AllowRegex  = /^[\ba-zA-Z0-9- ]$/;
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




  </script>


    <?php print $head ?>
    <?php 
		if(arg(0) != 'loan')
		{
			$_SESSION['sstatus'] = '';
			unset($_SESSION['sstatus']);
		}
	  //drupal_set_message(arg(0) .'-'. arg(1).$_GET['q']);
	  if($_GET['q'] == 'loan/quarterly_progress'){
		  ?>
          <title><?php print 'Quaterly Progress Report';  ?></title>
          <?php
		  }else{
	?>
    
    <title><?php print $head_title; } ?></title>
    
    
    
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
  </head>
 <body id="body" class="body">
<div id="color1">
  <div id="color2">
    <div id="top-gradient"></div>
    <div class="main">
      <div id="top">
        <div id="top-left">
          <div id="top-right">
            <div id="logo" title="HBCFDC">
              <div class="space">
                <?php
          // Prepare header
          $site_fields = array();
          if ($site_name) {
            $site_fields[] = check_plain($site_name);
          }
          if ($site_slogan) {
            $site_fields[] = check_plain($site_slogan);
          }
          $site_title = implode(' ', $site_fields);
          if ($site_fields) {
            $site_fields[0] = '<span>'. $site_fields[0] .'</span>';
          }
          $site_html = implode(' ', $site_fields);

          if ($logo || $site_title) {
            print '<h1><a href="'. check_url($front_page) .'" title="'. $site_title .'">';
            if ($logo) {
              print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';
            }
            print $site_html .'</a></h1>';
          }
        ?>
              </div>
            </div>
          <div class="h-cont"></div>
          </div>
        </div>
      </div>
      <div id="shadow-left">
        <div id="shadow-right">
          <div class="width bg">
            <div id="mid">
              <div id="search">
                <div class="module-search">
                   <?php print $search_box; ?>
                </div>
              </div>
              <div id="topmenu"  style="overflow: visible;">
              <div class="module-topmenu">
              <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links) ?>
        <?php endif; ?></div>
              </div>
              <div class="clr"></div>
            </div>
            <div id="content">
              <div class="width">
			  <div class="width">                   
                      <div id="header">
                        <div class="logo2"></div>
 </div>
                    </div>    

   <?php 
     $nid =  arg(1);
		    $cnode = node_load($nid);
			 
		  if(($cnode->type=='latestnews' && arg(2) == "") || (arg(0) == 'emi_cal') || ($cnode->type=='story' && arg(2) == "") || ($cnode->type=='event' && arg(2) == "")  || arg(1) == '1155'){
		  	?>
            
<?php 
		  } ?>
<div class="adminpanel">


<?php
  $username = $user->name;

if($language->language == 'hi'){
$node211 = $base_url.'/hi/welcome-deshboard';
$logout=$base_url.'/hi/logout';
$change_password = $base_url.'/hi/scst/resetpassword';
}else{
$node211 = $base_url.'/welcome-deshboard';
$logout=$base_url.'/logout';
$change_password = $base_url.'/scst/resetpassword';
}

if ($user->uid != 0) {
  // code for the logout button
  echo ''.'Hi&nbsp;'.$username.'!&nbsp; | &nbsp;'."<a href='$node211'>Dashboard</a>&nbsp; | &nbsp;";
  echo "<a href=' $logout'>Log out</a>&nbsp; | &nbsp;";
 echo "<a href=' $change_password'>Change Password</a>";
}
else {
 //cho "<a href=\"?q=user\">Login</a>";
}
?>
</div>



<?php print $breadcrumb; ?>

   	  
		  <?php
		   ///for news page
		      $nid =  arg(1);
		    $cnode = node_load($nid);
			 
		  if(($cnode->type=='latestnews' && arg(2) == "") || (arg(0) == 'emi_cal') || ($cnode->type=='story' && arg(2) == "") || ($cnode->type=='event' && arg(2) == "") || arg(1) == '1155'){
			  
			  ?>
                <div id="main-content-home">   
   
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
		
          <?php 
		  $nid =  arg(1);
		    $cnode = node_load($nid);
		  if(arg(1)){
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title.'</h2>';
		  }else{
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title.'</h2>';
		  }
		 // if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title; .'</h2>'; endif; ?>
		  
		  
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>         
          <?php
	      echo '<div id="content-news">';
			  print $content;
			  echo '</div>';
		  	   ?> 
          <?php print $feed_icons ?>
              <?php
		
		    echo '</div><div id="latest-news">';
			echo '<div id="lastest-news-block"><div class="RightSidebarHome">';
			//echo '<div id="emicalpagenews">';
			echo '<div id="latestnews"><h1>Latest News</h1>';
		  
			    print $latestnews;
			  echo '</div></div></div>';
			  
			
			 			  
			  echo '</div>';
			
		  }else{
			  
			  ?>
              <div id="main-content">   
   
          <?php if ($mission): print '<div id="mission">'. $mission .'</div>'; endif; ?>
          <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
		
          <?php 
		  $nid =  arg(1);
		    $cnode = node_load($nid);
		  if(arg(1)){
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title.'</h2>';
		  }else{
			  print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title.'</h2>';
		   }
		 // if ($title): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $cnode->title; .'</h2>'; endif; ?>
		  
		  
          <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
          <?php if ($show_messages && $messages): print $messages; endif; ?>
          <?php print $help; ?>         
          <div class="moduletable">
                      <div id="TabbedPanel1" class="TabbedPanels">
                        <div class="tabborder">
                          <div class="wrapper">
                            <div class="TabModulesGroup">
                              <div class="TabContent" id="user2"> 
	     
		  	    
          <?php print $feed_icons ?>
              <?php
		    print $content.'</div>';
			
		  }
		  
		  ?>
          
       
 <p></p>
                    
                    <div class="width ind">
                      <table class="blog" cellpadding="0" cellspacing="0">
                      </table>
                    </div>
                  </div>
                </div>
                
  <div id="footer" class="png">
        <div class="footer-right png">
          <div class="footer-bg png">
            <?php print $footermenu;?>
          </div>
        </div>
      </div><?php print $closure;?>
  </div>
</div>
<!-- /layout -->

  
  </body>
</html>
