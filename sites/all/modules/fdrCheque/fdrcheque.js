
	
	
$(document).ready (function(){

//$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");



var mop= $("#edit-fdr-type-wrapper option:selected").val();

 if(mop=='227')
{
//$("#edit-account_no-wrapper").html();
$("#edit-account_no-wrapper").html("<label >Registration No.: </label><input type='text' value='N/A' name='Registration No.' readonly=readonly>");



}

	});
