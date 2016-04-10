
<!-- Dynamic Version by: Nannette Thacker -->
<!-- http://www.shiningstar.net -->
<!-- Original by :  Ronnie T. Moore -->
<!-- Web Site:  The JavaScript Source -->
<!-- Use one function for multiple text areas on a page -->
<!-- Limit the number of characters per textarea -->
<!-- Begin

//  End -->



function textonlywithdotnemaxcontact(e,id,maxlimit){

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
		
		
		function textonlywithdotnemaxcon(e,maxlimit){

 var valn = document.getElementById("edit-message").value;
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