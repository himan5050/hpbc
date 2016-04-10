function showUsera(str,url)
{

if (str=="")
  {
document.getElementById("namee").value = "";
	document.getElementById("district").value = "";
	document.getElementById("total_amount").value = "";
	
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
	// alert(val[4]);
    //document.getElementById("edit-employee-name").innerHTML=xmlhttp.responseText;
	document.getElementById("namee").value = val[0];
	document.getElementById("district").value = val[1];
	document.getElementById("total_amount").value = val[2];
	
    }
  }
 
xmlhttp.open("GET",url+"/alrformdata.php?q="+str,true);
xmlhttp.send();
}


function showbaldiff()
{ 
   var a =parseFloat(document.getElementById('amount_deposited_dm').value);
   var c =parseFloat(document.getElementById('amount_deposited_tehs').value);
   var d =parseFloat(document.getElementById('amount').value);
   var e=a+c;
  var b=parseFloat(document.getElementById('total_amount').value);
   document.getElementById('amount_recovered').value=parseFloat(e).toFixed(2);
  document.getElementById('balance').value=parseFloat((d-e)).toFixed(2);
}


