$(document).ready(function(){
							var val2= $('#status2_id').val();
							
							if(val2==95){
								$('#dispaly-hired').css("display","table");
								
								}else{
									$('#dispaly-hired').css("display","none");
									}
							});

function hired(){
	 var val = document.getElementById('status2_id').value;
	 if(val == 95){
	 
		  document.getElementById('dispaly-hired').style.display='table';
		 }else{
			   document.getElementById('dispaly-hired').style.display='none';
			 }
	}