
function showlawyer(str,url)
{
	
if (str=="")
  {
 document.getElementById("edit-lawyer-id").innerHTML="";
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
	document.getElementById("fee_id").value = val[0];
	document.getElementById("phone_id").value = val[1];
	document.getElementById("email_id").value = val[2];
	
	
    }
  }
xmlhttp.open("GET",url+"/lawyer.php?q="+str,true);
xmlhttp.send();
}

function showcourt(str,url)
{
	
if (str=="")
  {
 document.getElementById("edit-lawyer-id").innerHTML="";
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
	document.getElementById("phone_id").value = val[0];
	document.getElementById("edit-lawyer-id").selected = val[1];
	
	
    }
  }
xmlhttp.open("GET",url+"/lawyer.php?q="+str,true);
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


$(document).ready(function()
    {
        var lab1 = $('#edit-next-hearing-date-wrapper label');
        lab1.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		

  });
