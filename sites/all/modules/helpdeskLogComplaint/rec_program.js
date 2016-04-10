$(document).ready(function(){
						  
						   var val2= $("#related2_id-wrapper option:selected").val();
						  
						 
						  if (val2==120){
							   $('#display-soft').css("display","table-row");
								}
								else{
									$('#display-soft').css("display","none");
									}
							
									
							$("#related2_id-wrapper select").change(function(){
							var val2= $("#related2_id-wrapper option:selected").val();
						    
						   if (val2==120){
							   $("#display-soft").css("display","table-row");
							  
								}
								else{
									$("#display-soft").css("display","none");
									}
									});
									
							});
						   
						   
						   						   
						   
						   