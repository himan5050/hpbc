<style>
.filefield-element .widget-preview {
    float: left;
    margin: 0 124px 0 0;
    padding: 0 10px 0 0;
	margin-bottom:10px;
}	
fieldset {  }
</style>

<?php
//$rid = getRole($form['program_uid']['#value']);

?>

  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form_container">
   <?php if($form['#parameters'][2]->nid){
	  ?>	  
  <tr class="oddrow">
  <td align="center"><h2>Employee Edit Form</h2></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['employee_id']); ?></td>  
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['employee_name']); ?></td>
  </tr>
   <?php
		}else{ ?>
  <tr class="oddrow">
  <td align="center"><h2>Employee Entry Form</h2></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['employee_id']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['employee_name']); ?></td>
  </tr>
  <?php } ?>
  <tr class="evenrow">
  <td><?php print drupal_render($form['father_name']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['nationality']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['dob']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['doj']); ?></td>
  </tr>
  
  
  <tr class="evenrow">
  <td><?php print drupal_render($form['employee_type']); ?></td>
  </tr>
  
  <tr class="oddrow">
  <td><?php print drupal_render($form['basic_pay']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['grade_pay']); ?></td>
  </tr>
  
  <tr class="oddrow">
  <td><?php print drupal_render($form['religion']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><div id="religion"><?php print drupal_render($form['caste']); ?></div></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['edu_qual']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['prof_qual']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['mark']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['height']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['officeid']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['designationid']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['Departmentid']); ?></td>
  </tr>
 
  <tr class="evenrow">
  <td><?php print drupal_render($form['gender']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['adhar']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['email']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['phone']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['mobile']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['add_type']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['add_line1']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['add_line2']); ?></td>
  </tr>
  <tr class="evenrow">
   <td align="left" class="form-text1"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="oddrow">
   <td class="form-text1"><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="evenrow">
  <td align="left" class="form-text1"><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
   <tr class="oddrow">
  <td><?php print drupal_render($form['block']); ?></td>
  </tr>
  <tr class="evenrow">
  <td><?php print drupal_render($form['panchayat']); ?></td>
  </tr>
  <tr class="oddrow">
  <td><?php print drupal_render($form['pincode']); ?></td>
  </tr>
 
 
  
    <tr class="evenrow">
  <td class="medical_examination"><?php print drupal_render($form['field_medical_examination']); ?><div class="medical_examination_upload"><div><?php print drupal_render($form['field_medical_upload']); ?></div><div ><?php print drupal_render($form['medical_by']); ?></div><div ><?php print drupal_render($form['medical_on']); ?></div><div ><?php print drupal_render($form['medical_sno']); ?></div><div ><?php print drupal_render($form['medical_certified_by']); ?></div><div ><?php print drupal_render($form['medical_designationid']); ?></div></div></td>
  </tr>
      
    
  <tr class="oddrow">
  	<td class="character"><?php print drupal_render($form['field_character_certificate']); ?><div class="character_upload"><div><?php print drupal_render($form['field_character_upload']); ?></div><div ><?php print drupal_render($form['character_sno']); ?></div><div ><?php print drupal_render($form['character_certified_by']); ?></div><div ><?php print drupal_render($form['character_designationid']); ?></div></div></td>
  </tr>
 
  
  <tr class="evenrow">
  	<td class="allegiance"><?php print drupal_render($form['field_allegiance']); ?><div class="allegiance_upload"><div><?php print drupal_render($form['field_allegiance_upload']); ?></div><div ><?php print drupal_render($form['allegiance_sno']); ?></div><div ><?php print drupal_render($form['allegiance_certified_by']); ?></div><div ><?php print drupal_render($form['allegiance_designationid']); ?></div></div></td>
  </tr>
 
  
  <tr class="oddrow">
  	<td class="oath"><?php print drupal_render($form['field_oath']); ?>	<div class="oath_upload"><div><?php print drupal_render($form['field_oath_upload']); ?></div><div ><?php print drupal_render($form['oath_sno']); ?></div><div ><?php print drupal_render($form['oath_certified_by']); ?></div><div ><?php print drupal_render($form['oath_designationid']); ?></div></div></td>
  </tr>
 
  
  <tr class="evenrow">
  	<td class="marital"><?php print drupal_render($form['field_marital']); ?><div class="marital_upload"><div><?php print drupal_render($form['field_marital_upload']); ?></div><div ><?php print drupal_render($form['marital_sno']); ?></div><div ><?php print drupal_render($form['marital_certified_by']); ?></div><div ><?php print drupal_render($form['marital_designationid']); ?></div></div></td>
  </tr>
  
  <tr class="oddrow">
  	<td class="declaration"><?php print drupal_render($form['field_declaration']); ?><div class="declaration_upload"><div><?php print drupal_render($form['field_declaration_upload']); ?></div><div ><?php print drupal_render($form['declaration_sno']); ?></div><div ><?php print drupal_render($form['declaration_certified_by']); ?></div><div ><?php print drupal_render($form['declaration_designationid']); ?></div></div></td>
  </tr>
  
  
   <tr class="evenrow">
  	<td class="verification"><?php print drupal_render($form['field_verification']); ?><div class="verification_upload"><div><?php print drupal_render($form['field_verification_upload']); ?></div><div ><?php print drupal_render($form['verification_sno']); ?></div><div ><?php print drupal_render($form['verification_certified_by']); ?></div><div ><?php print drupal_render($form['verification_designationid']); ?></div></div></td>
  </tr>
  
  
    <tr class="oddrow">
  	<td class="family"><?php print drupal_render($form['field_family']); ?>	<div class="family_upload"><div><?php print drupal_render($form['field_family_upload']); ?></div><div ><?php print drupal_render($form['family_sno']); ?></div><div ><?php print drupal_render($form['family_certified_by']); ?></div><div ><?php print drupal_render($form['family_designationid']); ?></div></div></td>
  </tr>
 
   <tr class="evenrow">
  	<td class="training"><?php print drupal_render($form['field_training']); ?><div class="training_upload"><div><?php print drupal_render($form['field_training_upload']); ?></div></div></td>
  </tr>
 
  
   <tr class="oddrow">
  	<td class="gpf"><?php print drupal_render($form['field_gpf']); ?><div class="gpf_upload"><div><?php print drupal_render($form['field_gpf_upload']); ?></div><div ><?php print drupal_render($form['gpf_sno']); ?></div><div ><?php print drupal_render($form['gpf_nomination']); ?></div><div ><?php print drupal_render($form['gpf_certified_by']); ?></div><div ><?php print drupal_render($form['gpf_designationid']); ?></div></div></td>
  </tr>
  
  
   <tr class="evenrow">
  	<td class="dcr"><?php print drupal_render($form['field_dcr']); ?><div class="dcr_upload"><div><?php print drupal_render($form['field_dcr_upload']); ?></div><div ><?php print drupal_render($form['dcr_nomination']); ?></div><div ><?php print drupal_render($form['dcr_certified_by']); ?></div><div ><?php print drupal_render($form['dcr_designationid']); ?></div></div></td>
  </tr>
  
 <tr class="oddrow">
  <td align="left"><?php print drupal_render($form['field_photo_upload']); ?></td>
  </tr>

  <tr class="evenrow">
  <td align="left"><?php print drupal_render($form['field_resume_upload']); ?></td>
  </tr>
  
   <tr class="oddrow">
  <td><?php print drupal_render($form['employeegrade2']); ?></td>
  </tr>
 
 <tr class="evenrow">
  <td><?php print drupal_render($form['nomineedetails']); ?></td>
  </tr>
  
  <tr class="oddrow">
  <td><?php print drupal_render($form['biometricfield']); ?></td>
  </tr>
  
  <?php if($form['#parameters'][2]->nid){
	  ?>
   <tr class="oddrow">
  <td><?php print drupal_render($form['status2']); ?></td>
  </tr>
  
   
  <?php } ?>
 
  
  
 
 <?php if($form['#parameters'][2]->nid){}else{ ?>
  <tr class="oddrow">
  <td><?php print drupal_render($form['logindetails']); ?></td>
  </tr>
  
<?php } ?>
  <tr class="evenrow">
  <td align="center" class="back"><?php print drupal_render($form); ?></td>
  </tr>   
  </table>