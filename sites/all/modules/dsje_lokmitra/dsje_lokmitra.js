$(document).ready (function(){

//$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");


var test= $("#edit-add-type-wrapper option:selected").val();

//alert(test);
if(test == 175)
{


	
	
$("#rural").css("display","table");
}




});

function changeMenu1234(sel)
{

var opt = sel.options[sel.selectedIndex].value;
if(opt=='175')
{
$("#rural").show();
}
else
{
$("#rural").hide();	
}
}

$(document).ready(function()
    {
        var lab12 = $('#edit-panchayt-name-wrapper label');
        lab12.each(function() { $(this).html($(this).html().replace(":", ":*")); });
	});