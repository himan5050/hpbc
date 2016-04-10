$(document).ready(function(){
							var val1= $('#headtype_id').val();
							var val2= $('#branch_id').val();
							var val3= $('$fin_year_id').val();

							if(val1 && val2 && val3){
							$('#complicated').css("display","block");
								
								}else{
									$('#complicated').css("display","none");
									}
							});

function selectedType(url){
	
	var val1 = document.getElementById('headtype_id').value;
	var val2 = document.getElementById('branch_id').value;
	var val3 = document.getElementById('fin_year_id').value;
	//val = val1+'|'+val2+'|'+val3;
	//alert(val);
if(val1 && val2 && val3){
	
		 document.getElementById('complicated').style.display='block';
		}else{
		   document.getElementById('complicated').style.display='none';
			}
	


	}