$(document).ready (function(){

//$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");


var test= $("#edit-applicaint-bpl-wrapper option:selected").val();
var mop= $("#edit-mode-payment-wrapper option:selected").val();

//alert(test);
if(test == '1' || test == '')
{
$("#modepayment").css("display","none");	



}
else
{
if(mop=='')
{
$("#modepayment").css("display","none");	
}
else
{
if(mop=='ipo')
{
$("#ipoedit").css("display","table-row");
$("#currdatefieldedit").css("display","table-row");
$("#cashipoedit").css("display","table-row");
}


else if(mop=='mo')
{
$("#currdatemoedit").css("display","table-row");
$("#cashmoedit").css("display","table-row");

}

else if(mop=='cash')
{
$("#currdatecashedit").css("display","table-row");
$("#cashcashedit").css("display","table-row");

}


}

	
	
$("#modepayment").css("display","table-row");
}




});


function changeMenu12(sel)
{

var opt = sel.options[sel.selectedIndex].value;
if(opt=='0')
{
$("#modepayment").show();
}
else
{
$("#modepayment").hide();
$("#ipoedit").hide();
$("#currdatefieldedit").hide();
$("#cashipoedit").hide();
$("#currdatemoedit").hide();
$("#cashmoedit").hide();
$("#currdatecashedit").hide();
$("#cashcashedit").hide();
}

}

function changeMenu1(sel)
{
var opt = sel.options[sel.selectedIndex].value;
if(opt=="ipo")
{
$("#ipoedit").show();
$("#currdatefieldedit").show();
$("#cashipoedit").show();
}
else
{
$("#ipoedit").hide();
$("#currdatefieldedit").hide();
$("#cashipoedit").hide();
}

if(opt=="mo")
{
$("#currdatemoedit").show();
$("#cashmoedit").show();

}
else
{
$("#currdatemoedit").hide();
$("#cashmoedit").hide();
}

if(opt=="cash")
{
$("#currdatecashedit").show();
$("#cashcashedit").show();

}
else
{

$("#currdatecashedit").hide();
$("#cashcashedit").hide();
}
}

function fononlyn12(e) {
	
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


$(document).ready(function()
    {
        var lab1 = $('#edit-mode-payment-wrapper label');
        lab1.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		
		 var lab2 = $('#edit-ipono-wrapper label');
        lab2.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		 var lab3 = $('#edit-currdatefield-wrapper label');
        lab3.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		 var lab4 = $('#edit-cashipo-wrapper label');
        lab4.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		 var lab5 = $('#edit-currdatemo-wrapper label');
        lab5.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		var lab6 = $('#edit-cashmo-wrapper label');
        lab6.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		var lab7 = $('#edit-currdatecash-wrapper label');
        lab7.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		 var lab8 = $('#edit-cashcash-wrapper label');
        lab8.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
    });









