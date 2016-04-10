
function showcourthearing(str,url)
{
	
if (str=="")
  {
 //document.getElementById("edit-case-no").innerHTML="";
 document.getElementById("hearing_id-datepicker-popup-0").value =" ";
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
	 var val = xmlhttp.responseText;	
	 
    //document.getElementById("edit-employee-name").innerHTML=xmlhttp.responseText;
	//document.getElementById("hearing_id-datepicker-popup-0").value = val;
	
	document.getElementById("hearing_id").value = val;
    }
  }
 
xmlhttp.open("GET",url+"/court.php?q="+str,true);
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



