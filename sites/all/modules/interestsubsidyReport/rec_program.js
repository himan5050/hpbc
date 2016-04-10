$(document).ready(function(){


$('#edit-maturity-date-datepicker-popup-0').change(function(){

var to = $('#edit-maturity-date-datepicker-popup-0').val();
var from  = $('#edit-fdr-date-datepicker-popup-0').val();
var days = daydiff1(parseDate1(from), parseDate1(to))+1;
//alert(parseDate(from));
if(days>0){
$('#edit-days').val(days);
}
else{
$('#edit-days').val(0);
}

});

$('#edit-fdr-date-datepicker-popup-0').change(function(){

var to = $('#edit-maturity-date-datepicker-popup-0').val();
var from  = $('#edit-fdr-date-datepicker-popup-0').val();
var days = daydiff1(parseDate1(from), parseDate1(to))+1;
//alert(parseDate(from));
if(days>0){
$('#edit-days').val(days);
}
else{
$('#edit-days').val(0);
}

});

function parseDate1(str) {
    var mdy = str.split('-');
   // return mdy[1];
	return new Date(mdy[2], mdy[1]-1, mdy[0]);
}

function daydiff1(first, second) {
    return Math.floor((second-first)/(1000*60*60*24));
}

});