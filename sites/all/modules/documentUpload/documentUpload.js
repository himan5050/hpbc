$(document).ready(function(){
$('#rem').css('background-color','#E8E8E8');
 var level = $("#edit-access-level option:selected").val();
	
	  if(level == 4){
		  $('.droleget').css('display','table');
		 $('.dgetuser').css('display','none');
		 $('#rem').css('background-color','#F7F7F7');
		 
	  }
	  else if(level == 5){
		  $('.dgetuser').css('display','table');
		 $('.droleget').css('display','none');
		 $('#rem').css('background-color','#F7F7F7');
	  }
	  else{
	 $('.dgetuser').css('display','none');
$('.droleget').css('display','none');
$('#rem').css('background-color','#E8E8E8');
	  }
$("#edit-access-level").change(function () {
     
	  var level = $("#edit-access-level option:selected").val();
	
	  if(level == 4){
		  $('.droleget').css('display','table');
		 $('.dgetuser').css('display','none');
		 $('#rem').css('background-color','#F7F7F7');
	  }
	  else if(level == 5){
		  $('.dgetuser').css('display','table');
		 $('.droleget').css('display','none');
		 $('#rem').css('background-color','#F7F7F7');
	  }
	  else{
	 $('.dgetuser').css('display','none');
$('.droleget').css('display','none');
$('#rem').css('background-color','#E8E8E8');
	  }
});
/*
$('#edit-file-discription').keypress(function(){
var tval = $(this).val();
var sp = /[^a-zA-Z 0-9]+/g;
if(tval.length == 200){
return false; 
}
if (!sp.test(tval)){

	return true;
     }
else{
		 return false;
     }

 });

 $('#edit-file-keywords').keypress(function(){
var tval = $(this).val();
var sp = /[^a-zA-Z 0-9 ,]+/g;
if(tval.length == 200){
return false; 
}
if (!sp.test(tval)){

	return true;
     }
else{
		 return false;
     }

 });

*/

});