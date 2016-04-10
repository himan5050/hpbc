$(document).ready (function(){
$('#edit-phone-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-mobile-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-medical-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-medical-on-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-medical-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-medical-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-medical-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-character-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-character-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-character-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-allegiance-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-allegiance-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-allegiance-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-oath-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-oath-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-oath-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-marital-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-marital-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-marital-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-declaration-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-declaration-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-declaration-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-verification-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-verification-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-verification-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-family-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-family-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-family-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-gpf-sno-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-gpf-nomination-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-gpf-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-gpf-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-dcr-nomination-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-dcr-certified-by-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });
$('#edit-dcr-designationid-wrapper label').each(function() { $(this).html($(this).html().replace(":", ": *")); });






$("#edit-field-medical-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-character-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-allegiance-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-oath-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-declaration-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-family-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-training-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-gpf-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-dcr-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-verification-upload-0-upload-wrapper label").css("display","none");
$("#edit-field-marital-upload-0-upload-wrapper label").css("display","none");

var test= $(".medical_examination .form-radios input:radio:checked").val();
var character=$(".character .form-radios input:radio:checked").val();
var allegiance=$(".allegiance .form-radios input:radio:checked").val();
var oath=$(".oath .form-radios input:radio:checked").val();
var declaration=$(".declaration .form-radios input:radio:checked").val();
var family=$(".family .form-radios input:radio:checked").val();
var training=$(".training .form-radios input:radio:checked").val();
var gpf=$(".gpf .form-radios input:radio:checked").val();
var dcr=$(".dcr .form-radios input:radio:checked").val();
var verification=$(".verification .form-radios input:radio:checked").val();
var marital=$(".marital .form-radios input:radio:checked").val();

//alert(character);
if(test == 1 || test == undefined){
$(".medical_examination_upload").css("display","none");



/*$("#edit-medical-by").val("");
$("#edit-medical-on-datepicker-popup-0").val("");
$("#edit-medical-sno").val("");
$("#edit-medical-certified-by").val("");
$("#edit-medical-designationid").val("");
*/
}
else{
$(".medical_examination_upload").css("display","inline");
}

$(".medical_examination input:radio").click(function(){

var test= $(".medical_examination .form-radios input:radio:checked").val();
//alert(test);
if(test == 1){
$(".medical_examination_upload").css("display","none");
//$("#edit-field-medical-upload-0-upload-wrapper input").attr("type","text");

}
else{
$(".medical_examination_upload").css("display","inline");
}

});
///character
if(character == 1 || character == undefined){
$(".character_upload").css("display","none");
$("#edit-character-sno").val("");
$("#edit-character-certified-by").val("");
$("#edit-character-designationid").val("");

}
else{
$(".character_upload").css("display","inline");
}

$(".character input:radio").click(function(){

var character= $(".character .form-radios input:radio:checked").val();
//alert(test);
if(character == 1){
$(".character_upload").css("display","none");

}
else{
$(".character_upload").css("display","inline");
}

});

//allegiance

if(allegiance == 1 || allegiance == undefined){
$(".allegiance_upload").css("display","none");
$("#edit-allegiance-sno").val("");
$("#edit-allegiance-certified-by").val("");
$("#edit-allegiance-designationid").val("");
}
else{
$(".allegiance_upload").css("display","inline");
}

$(".allegiance input:radio").click(function(){

var allegiance= $(".allegiance .form-radios input:radio:checked").val();
//alert(test);
if(allegiance == 1){
$(".allegiance_upload").css("display","none");


}
else{
$(".allegiance_upload").css("display","inline");
}

});

//oath
if(oath == 1 || oath == undefined){
$(".oath_upload").css("display","none");

}
else{
$(".oath_upload").css("display","inline");
}

$(".oath input:radio").click(function(){

var oath= $(".oath .form-radios input:radio:checked").val();
//alert(test);
if(oath == 1){
$(".oath_upload").css("display","none");

}
else{
$(".oath_upload").css("display","inline");
}

});

//declaration
if(declaration == 1 || declaration == undefined){
$(".declaration_upload").css("display","none");

}
else{
$(".declaration_upload").css("display","inline");
}

$(".declaration input:radio").click(function(){

var declaration= $(".declaration .form-radios input:radio:checked").val();
//alert(test);
if(declaration == 1){
$(".declaration_upload").css("display","none");

}
else{
$(".declaration_upload").css("display","inline");
}

});
//family
if(family == 1 || family == undefined){
$(".family_upload").css("display","none");

}
else{
$(".family_upload").css("display","inline");
}

$(".family input:radio").click(function(){

var family= $(".family .form-radios input:radio:checked").val();
//alert(test);
if(family == 1){
$(".family_upload").css("display","none");

}
else{
$(".family_upload").css("display","inline");
}

});

///training
if(training == 1 || training == undefined){
$(".training_upload").css("display","none");

}
else{
$(".training_upload").css("display","inline");
}

$(".training input:radio").click(function(){

var training= $(".training .form-radios input:radio:checked").val();
//alert(test);
if(training == 1){
$(".training_upload").css("display","none");

}
else{
$(".training_upload").css("display","inline");
}

});

//gpf
if(gpf == 1 || gpf == undefined){
$(".gpf_upload").css("display","none");

}
else{
$(".gpf_upload").css("display","inline");
}

$(".gpf input:radio").click(function(){

var gpf= $(".gpf .form-radios input:radio:checked").val();
//alert(test);
if(gpf == 1){
$(".gpf_upload").css("display","none");

}
else{
$(".gpf_upload").css("display","inline");
}

});


//dcr
if(dcr == 1 || dcr == undefined){
$(".dcr_upload").css("display","none");

}
else{
$(".dcr_upload").css("display","inline");
}

$(".dcr input:radio").click(function(){

var dcr= $(".dcr .form-radios input:radio:checked").val();
//alert(test);
if(dcr == 1){
$(".dcr_upload").css("display","none");

}
else{
$(".dcr_upload").css("display","inline");
}

});

//verification

if(verification == 1 || verification==undefined){
$(".verification_upload").css("display","none");

}
else{
$(".verification_upload").css("display","inline");
}



$(".verification input:radio").click(function(){

var verification= $(".verification .form-radios input:radio:checked").val();
//alert(test);
if(verification == 1){
$(".verification_upload").css("display","none");

}
else{
$(".verification_upload").css("display","inline");
}

});





if(marital == 1 || marital== undefined){
$(".marital_upload").css("display","none");

}
else{
$(".marital_upload").css("display","inline");
}




$(".marital input:radio").click(function(){

var marital= $(".marital .form-radios input:radio:checked").val();
//alert(test);
if(marital == 1){
$(".marital_upload").css("display","none");

}
else{
$(".marital_upload").css("display","inline");
}

});






});