
function showUser(str,url)
{

if (str=="")
  {
document.getElementById("employee_id").value = "";
	document.getElementById("office_id").value = "";
	document.getElementById("officeaddresspreid").value = "";
	document.getElementById("designation_id").value = "";
	document.getElementById("department_id").value = "";
	document.getElementById("edit-phone").value = "";
	document.getElementById("edit-mobile").value = "";
	document.getElementById("edit-email").value = "";
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
	
	document.getElementById("employee_id").value = val[0];
	document.getElementById("office_id").value = val[1];
	document.getElementById("officeaddresspreid").value = val[2];
	document.getElementById("designation_id").value = val[3];
	document.getElementById("department_id").value = val[4];
	document.getElementById("edit-phone").value = val[5];
	document.getElementById("edit-mobile").value = val[6];
	document.getElementById("edit-email").value = val[7];
    }
  }
 
xmlhttp.open("GET",url+"/employee.php?q="+str,true);
xmlhttp.send();
}


function showUsert(str,url)
{

if (str=="")
  {
document.getElementById("employee_id").value = "";
	document.getElementById("office_id").value = "";
	document.getElementById("designation_id").value = "";
	document.getElementById("department_id").value = "";
	document.getElementById("edit-phone").value = "";
	document.getElementById("edit-mobile").value = "";
	document.getElementById("edit-email").value = "";
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
	
	document.getElementById("employee_id").value = val[0];
	document.getElementById("office_id").value = val[1];
	document.getElementById("designation_id").value = val[2];
	document.getElementById("department_id").value = val[3];
	document.getElementById("edit-phone").value = val[4];
	document.getElementById("edit-mobile").value = val[5];
	document.getElementById("edit-email").value = val[6];
    }
  }
 
xmlhttp.open("GET",url+"/employeetransfer.php?q="+str,true);
xmlhttp.send();
}

function changeMenutransfer(sel)
{

var opt = sel.options[sel.selectedIndex].value;

if(opt == '77'){
$("#categoryname").show();	
	
}
else{
$("#categoryname").hide();	
	
}


}

$(document).ready(function()
    {
        var lab1 = $('#categorttransfer-wrapper label');
        lab1.each(function() { $(this).html($(this).html().replace(":", ":*")); });
		
		
	});
	
	
$(document).ready (function(){

//$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");



var mop= $("#edit-action-wrapper option:selected").val();

 if(mop=='77')
{
$("#categoryname").css("display","table-row");


}

	});
