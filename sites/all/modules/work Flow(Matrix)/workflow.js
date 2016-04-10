function showwork(){
  var val=document.getElementById("role").value;
  if(val > 0){
    document.getElementById("workflowfiled").style.display="block";
  }else{
    document.getElementById("workflowfiled").style.display="none";
  }
}


/*function addmorefield(){
  alert("here");	
}*/



function changevalue(url,a)
{
	//field
$.post(url+"/changevalue.php", {'m': a}, function(data) { $("#drop2").html(data); });
document.getElementById('value').style.display="none";
document.getElementById('field').style.display="none";
}

function showvalue(a)
{
 $.post("changevalue.php", {'m1': a,'m2':'show'}, function(data) { $("#value").html(data); });
 document.getElementById('value').style.display="block";
 document.getElementById('field').style.display="none";
}

function showdiv()
{
  document.getElementById('field').style.display="block";
}

function choseval(){
  var val=document.getElementById("usr").value
  if(val == 'roles'){
     document.getElementById('roles').style.display="block";
     document.getElementById('user').style.display="none";
  }
  if(val == 'users'){
      document.getElementById('roles').style.display="none";
     document.getElementById('user').style.display="block";
  }

  if(val == ''){
     document.getElementById('roles').style.display="none";
     document.getElementById('user').style.display="none";
  }
}