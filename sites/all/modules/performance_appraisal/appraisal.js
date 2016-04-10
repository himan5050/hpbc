
function showempdetail(str,url)
{
	
// var img = url+'/sites/all/modules/performance_appraisal/ajax.gif';
//document.getElementById("rempdetail").innerHTML="<div style='text-align:center;'><img src="+img+" style='text-align: center;float: none;'/></div>";
if (str=="")
  {
document.getElementById("employee_id").value = "";
	document.getElementById("office_id").value = "";
	document.getElementById("designation_id").value = "";
	document.getElementById("department_id").value = "";
	document.getElementById("p_a_year_id").value = "";
	document.getElementById("pyear_id").value = "";
	document.getElementById("acr_id").value = "";
	document.getElementById("selectyear").value = "";
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
	//alert(val[7]);
    //document.getElementById("edit-employee-name").innerHTML=xmlhttp.responseText;
	document.getElementById("employee_id").value = val[0];
	document.getElementById("office_id").value = val[2];
	document.getElementById("designation_id").value = val[1];
	document.getElementById("department_id").value = val[3];
	document.getElementById("p_a_year_id").value = val[4];
	document.getElementById("pyear_id").value = val[5];
	document.getElementById("acr_id").value = val[6];
//document.getElementById('selectyear').html=val[2];
//document.getElementById('selectyear').html("");
/*alert(val[7]);
*/
var selectbox = document.getElementById('selectyear');
var i;
for(i=selectbox.options.length-1;i>=0;i--)
{
selectbox.remove(i);
}

var itemid = document.getElementById('selectyear');
//alert(itemid);



var data = val[7].split( ',' );

    var len = data.length;															
//alert(len);
    var optn = document.createElement("OPTION");
    optn.text = '--Select--';
    optn.value = '';
    itemid.options.add(optn);
	if(len == 1){
   // var currentYear = (new Date).getFullYear();
	//optn.text = currentYear;
   // optn.value = currentYear;
   // itemid.options.add(optn);
	

	}
	else{

	for( i=0; i < len-1; i ++ )
    {
    	var opt = document.createElement('option');
    	opt.text = data[i];

    	try
    	{
    		itemid.add( opt, null );						
    	}
    	catch(ex)
    	{
    		itemid.add( opt );		
    	}		
    }		
	
	}



//document.getElementById('selectyear').options.add(new Option("1", "1"));
    }
  }
 
xmlhttp.open("GET",url+"/apraisalemployee.php?q="+str,true);
xmlhttp.send();
}



$(document).ready(function(){


});
