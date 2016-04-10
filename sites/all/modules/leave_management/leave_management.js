$(document).ready (function(){

var marital=$("#leave-management-form input:radio:checked").val();
//alert(marital);

if(marital == undefined){
$("#fromdatefromclass").css("display","none");
$("#todatefromclass").css("display","none");


}else if(marital == 1){
$("#fromdatefromclass").css("display","table-row");
$("#todatefromclass").css("display","none");
//$("#edit-from-date-datepicker-popup-0").val('');
$("#edit-to-date-datepicker-popup-0").val('');
//$("#edit-no-of-daye").val('');
}
else{
$("#fromdatefromclass").css("display","table-row");
$("#todatefromclass").css("display","table-row");
//$("#edit-no-of-daye").val('');
}

$("#leave-management-form .form-radios input:radio").click(function(){

var character= $("#leave-management-form input:radio:checked").val();
//alert(character);
if(character == 1){
$("#fromdatefromclass").css("display","table-row");
$("#todatefromclass").css("display","none");
$("#edit-from-date-datepicker-popup-0").val('');
$("#edit-to-date-datepicker-popup-0").val('');
$("#edit-no-of-daye").val('');
$('#edit-no-of-daye').val('1/2');

}
else{
	$('#edit-no-of-daye').val('');
	$("#fromdatefromclass").css("display","table-row");
$("#todatefromclass").css("display","table-row");

}

});

/*$('#edit-to-date-datepicker-popup-0').change(function(){

var from = $('#edit-from-date-datepicker-popup-0').val();
var to = $('#edit-to-date-datepicker-popup-0').val();
var days = daydiff(parseDate(from), parseDate(to))+1;
//alert(parseDate(from));
if(days>0){
$('#edit-no-of-daye').val(days);
}
else{
$('#edit-no-of-daye').val(0);
}

});


function parseDate(str) {
    var mdy = str.split('-');
   // return mdy[1];
	return new Date(mdy[2], mdy[1]-1, mdy[0]);
}

function daydiff(first, second) {
    return Math.floor((second-first)/(1000*60*60*24));
}
*/
 

});


function showempname(a,url)
{
	
//alert(url);
 $.post(url+"/empidtoname.php", {'m1': a}, function(data) {
	//alert(data);
	 $("#edit-emp-name").val(data); });
}

function datediff(a,url)
{
	var from = $('#edit-from-date-datepicker-popup-0').val();
var to = $('#edit-to-date-datepicker-popup-0').val();
var character= $("#leave-management-form input:radio:checked").val();
//alert(character);
//alert(to);
 $.post(url+"/datediff.php", {'m1':from,'m2':to}, function(data) {
	//alert(data);
	 if(character ==2){
	 $("#edit-no-of-daye").val(data); 
	 }
	 else{
$("#edit-no-of-daye").val('1/2'); 
	 }
	 });
} 