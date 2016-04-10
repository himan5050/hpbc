
function showbank(str,url)
{


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
	
	var testing = $('#edit-fdr-type').val();
	var testing1 = $('#edit-account_no').val();
	
	if(testing == '' || testing1 == ''){
	document.getElementById("edit-amount").value = '';
	document.getElementById("edit-cheque_no").value = '';	
	document.getElementById("edit-cheque-no").value = '';
	}
	
	if(testing != 227){
	document.getElementById("edit-bank-name").value = val[0];
	
	document.getElementById("edit-bank-name").readOnly = true;
	}else{
document.getElementById("edit-bankbranch-name").value = '';
	document.getElementById("edit-bank-name").value = '';
	document.getElementById("edit-amount").value = '';
	document.getElementById("edit-cheque_no").value = '';
	document.getElementById("edit-cheque-no").value = '';
	document.getElementById("edit-bank-name").readOnly = false;
	}
	//document.getElementById("edit-bank-name").html= '<intput type="text" value=val[0] readonly="readonly">';
	
    }
  }
 
xmlhttp.open("GET",url+"/showbankauto.php?q="+str,true);
xmlhttp.send();
}


$(document).ready(function(){

var lab1 = $('#edit-account_no-wrapper label');
        lab1.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		

$('#edit-maturity-date-datepicker-popup-0').change(function(){

var to = $('#edit-maturity-date-datepicker-popup-0').val();
var from  = $('#edit-fdr-date-datepicker-popup-0').val();
var days = daydiff1(parseDate1(from), parseDate1(to))+1;
//alert(parseDate(from));
if(days>0){
$('#edit-days').val(days);
}
else{
$('#edit-days').val(0);
}

});




$('#edit-account_no').change(function(){

var leanee_id = $('#edit-account_no').val();


});


$('#edit-fdr-date-datepicker-popup-0').change(function(){

var to = $('#edit-maturity-date-datepicker-popup-0').val();
var from  = $('#edit-fdr-date-datepicker-popup-0').val();
var days = daydiff1(parseDate1(from), parseDate1(to))+1;
//alert(parseDate(from));
if(days>0){
$('#edit-days').val(days);
}
else{
$('#edit-days').val(0);
}

});

function parseDate1(str) {
    var mdy = str.split('-');
   // return mdy[1];
	return new Date(mdy[2], mdy[1]-1, mdy[0]);
}

function daydiff1(first, second) {
    return Math.floor((second-first)/(1000*60*60*24));
}

});