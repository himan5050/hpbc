function acrdetail(a,url,b)
{
	
//alert(b);
 $.post(url+"/acrdetail.php", {'m1': a,'m2':b}, function(data) {
	 
	 var value = data.split('|');
	 $("#edit-acr-status").val(value[0]);
	 $("#edit-acr-no").val(value[1]);
	 
	 });
  //document.getElementById('fieldnoc').style.display="none";
}