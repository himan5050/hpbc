$(document).ready (function(){

on
$('#edit-p-interest').onkeypress(function(){

var from = $('#edit-p-interest').val();
var to = $('#edit-p-principle').val();
var days = from+to;

//alert(parseDate(from));

$('#edit-p-total').val(days);


});



});


