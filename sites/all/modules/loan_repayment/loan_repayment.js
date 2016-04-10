$(document).ready (function(){

//$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");


var testt= $("#edit-mode-paymentt-wrapper option:selected").val();


//alert(test);
if(testt == 'cash')
{
	
$("#cashamount").css("display","table-row");	



}

else if(testt == 'cheque')
{
	
$("#chequeno").css("display","table-row");		
$("#chequedate").css("display","table-row");	

$("#infavour").css("display","table-row");	
$("#bank_name").css("display","table-row");	
$("#cheque_amount").css("display","table-row");	
	
	
}




});







function showloaneename(str,url)
{

if (str=="")
  {
 //document.getElementById("edit-loan-account").innerHTML="";\
 document.getElementById("edit-loanee-name").value = "";
	document.getElementById("edit-loanee-address").value = "";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
	 var val = xmlhttp.responseText.split('|');	
	 
    //document.getElementById("edit-employee-name").innerHTML=xmlhttp.responseText;
	document.getElementById("edit-loanee-name").value = val[0];
	document.getElementById("edit-loanee-address").value = val[1];
	
	
    }
  }
xmlhttp.open("GET",url+"/loanrepayment.php?q="+str,true);
xmlhttp.send();
}



function getBaseURL() {
    var url = location.href;  // entire url including querystring - also: window.location.href;
    var baseURL = url.substring(0, url.indexOf('/', 14));


    if (baseURL.indexOf('http://localhost') != -1) {
        // Base Url for localhost
        var url = location.href;  // window.location.href;
        var pathname = location.pathname;  // window.location.pathname;
        var index1 = url.indexOf(pathname);
        var index2 = url.indexOf("/", index1 + 1);
        var baseLocalUrl = url.substr(0, index2);

        return baseLocalUrl + "/";
    }
    else {
        // Root Url for domain name
        return baseURL + "/";
    }

}

function changeMenulonee(sel)
{
var opt = sel.options[sel.selectedIndex].value;
if(opt=="cash")
{
$("#cashamount").show();

}
else
{
$("#cashamount").hide();

}

if(opt=="cheque")
{
$("#chequeno").show();
$("#chequedate").show();
$("#infavour").show();
$("#bank_name").show();
$("#cheque_amount").show();


}
else
{
$("#chequeno").hide();
$("#chequedate").hide();
$("#infavour").hide();
$("#bank_name").hide();
$("#cheque_amount").hide();

}

}

$(document).ready(function()
    {
        var lab11 = $('#edit-cash-amount-wrapper label');
        lab11.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		
		 var lab22 = $('#chequeno label');
        lab22.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		 var lab33 = $('#edit-cheque-date-wrapper label');
        lab33.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		 var lab44 = $('#edit-infavour-wrapper label');
        lab44.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		 var lab55 = $('#bank_name label');
        lab55.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		var lab66 = $('#edit-cheque-amount-wrapper label');
        lab66.each(function() { $(this).html($(this).html().replace(":", ": *")); });
		
		
		
    });


