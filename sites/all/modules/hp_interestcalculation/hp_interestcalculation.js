function showHint(url,corporation,scheme,account_number,to_date)
{
	var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
         xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
         xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    } 
    xmlhttp.onreadystatechange=function(){
                               if (xmlhttp.readyState==4 && xmlhttp.status==200){
	                               if(xmlhttp.responseText != ''){
                                      document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                                   }else{
	                                  document.getElementById("txtHint").innerHTML="Please wait....";
	                               }
                               }
                               }
    xmlhttp.open("GET",url+"process.php?corporation="+corporation+"&scheme="+scheme+"&account_number="+account_number+"&to_date="+to_date,true);
    xmlhttp.send();
}


function showval(){
  document.getElementById('showval').style.display="block";
  document.getElementById('plus').style.display="none";
}

function hideval(){
  document.getElementById('showval').style.display="none";
  document.getElementById('plus').style.display="block";
}