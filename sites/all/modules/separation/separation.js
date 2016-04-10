
function showemp(str,url)
{

if (str=="")
  {
	
//document.getElementById("rempdetail").value = "";
	
  return;
  } 
  var img = url+'/sites/all/modules/separation/spinner.gif';
document.getElementById("rempdetail").innerHTML="<div style='text-align:center;'><img src="+img+" style='text-align: center;float: none;'/></div>";
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
	  
     document.getElementById("rempdetail").innerHTML=xmlhttp.responseText;
	  
	  
	}
  }
 
xmlhttp.open("GET",url+"/employeedetail.php?q="+str,true);
xmlhttp.send();
}



$(document).ready (function(){
//alert("test");
var marital=$("#separation-edit-form input:radio:checked").val();
var s = $('#edit-branchoffice').val();
if(s ==''){
$('.adddue').css('display','none');
}

var er = $('#main-content ul li').val();

if(marital == 2 || marital == undefined){
$("#noccss").css("display","none");
$("#noccss").val("");


}
else{
$("#noccss").css("display","table-row");

}

$("#separation-edit-form input:radio").click(function(){

var character= $("#separation-edit-form input:radio:checked").val();

if(character == 2){
$("#noccss").css("display","none");

$("#noccss").val("");


}
else{
$("#noccss").css("display","table-row");

}

});


$('#edit-branchoffice').change(function(){
var a = $('#edit-branchoffice').val();
//alert(a);
$.post(url+"/nocvalue.php", {'m1': a}, function(data) {
	 $("#ufile").html(data); });
});

});


function shownocdiv()
{
  document.getElementById('fieldnoc').style.display="block";
}


function shownocvalue(url,a,b)
{
	
//alert(url);
 $.post(url+"/nocvalue.php", {'m1': a,'m2':b}, function(data) {
	 $("#nocvalue").html(data); });
  document.getElementById('fieldnoc').style.display="none";
}