
function showinterest(str,url)
{
	
// alert("hi");
 //document.getElementById("edit-corp-reg-no").innerHTML="";

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
	// alert(val[0]);
       // document.getElementById("edit-employee-name").innerHTML=xmlhttp.responseText;
	document.getElementById("edit-Loanee-details").value = val[0];
	document.getElementById("edit-bank-acc-no").value = val[1];
	document.getElementById("edit-tot-adv-loan").value = val[2];
	document.getElementById("edit-dis-date-datepicker-popup-0").value = val[3];
	
    }
  }
  //alert("hi");
xmlhttp.open("GET",url+"/interest.php?q="+str,true);
xmlhttp.send(str);

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

























$(document).ready (function(){


$('#edit-p-principle-wrapper #edit-p-principle').keyup(function(){
//alert('text');
var from = $('#edit-p-principle').val();
var to = $('#edit-p-interest').val();
var days = parseFloat(from)+parseFloat(to);
$('#edit-p-total').val(days.toFixed(2));



});

$('#edit-p-interest-wrapper #edit-p-interest').keyup(function(){
//alert('text');
var from = $('#edit-p-principle').val();
var to = $('#edit-p-interest').val();
var days = parseFloat(from)+parseFloat(to);

//alert(parseDate(from));

$('#edit-p-total').val(days.toFixed(2));



});


$('#edit-d-Principle-wrapper #edit-d-Principle').keyup(function(){
var to1 = $('#edit-d-Principle').val();
var from1 = $('#edit-d-interest').val();

var days1 = parseFloat(from1)+parseFloat(to1);
$('#edit-d-total').val(days1.toFixed(2));

});

$('#edit-d-interest-wrapper #edit-d-interest').keyup(function(){
var to1 = $('#edit-d-Principle').val();
var from1 = $('#edit-d-interest').val();

var days1 = parseFloat(from1)+parseFloat(to1);

//alert(parseDate(from));

$('#edit-d-total').val(days1.toFixed(2));






});

var testt= $("#edit-status-subsidy-wrapper select option:selected").val();


//alert(testt);
if(testt == 'released')
{
	$("#bank_nameinterest").show();
		$("#chequenointerest").show();
		$("#date1interest").show();
}

else if(testt == 'pending')
{
$("#bank_nameinterest").hide();
$("#chequenointerest").hide();
$("#date1interest").hide();
}



});

function changeintrest(sel)
{
var opt = sel.options[sel.selectedIndex].value;


if(opt=="released")
{
		$("#bank_nameinterest").show();
		$("#chequenointerest").show();
		$("#date1interest").show();


}


else if(opt=="pending"){
$("#bank_nameinterest").hide();
$("#chequenointerest").hide();
$("#date1interest").hide();

}



}


$(document).ready(function()
    {
        var lab1 = $('#bank_nameinterest-wrapper label');
        lab1.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		
		 var lab2 = $('#chequenointerest-wrapper label');
        lab2.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		 var lab3 = $('#date1interest-wrapper label');
        lab3.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
	});

