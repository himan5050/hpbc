<table cellpadding="2" cellspacing="1" border="1" id="form-container">

  <?php if($form['#parameters'][2]->nid){
	  ?>	
<tr class="oddrow">
	<td><h2>Court Case Edit Form</h2></td>   
</tr>
<?php
}
else{
?>
<tr class="oddrow">
	<td><h2>Court Case Entry Form</h2></td>   
</tr>
<?php
}

?>
<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['courtcase_id']); ?></td>
</tr>
<tr class="oddrow">

	<td align="left"><?php print drupal_render($form['court_name_id']); ?></td>
</tr>

<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['date1']); ?></td>
</tr>
<tr class="oddrow">

	<td align="left"><?php print drupal_render($form['hearing_date']); ?></td>
</tr>


<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['title_case']); ?></td>
</tr>

<tr class="oddrow">

	<td align="left"><?php print drupal_render($form['case_detail']); ?></td>
</tr>

<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['loan_account']); ?></td>
</tr>
<tr class="oddrow">

	<td align="left"><?php print drupal_render($form['name_opposite']); ?></td>
</tr>

<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['opposite_address']); ?></td>
</tr>
<tr class="oddrow">
   <td align="left"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="evenrow">
   <td><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left"><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>

<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['lawyer_id']); ?></td>
</tr>
<tr class="oddrow">

	<td align="left" ><?php print drupal_render($form['fee_charge']); ?></td>
</tr>
<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['phone_no']); ?></td>
</tr>
<tr class="oddrow">

	<td align="left"><?php print drupal_render($form['email']); ?></td>
</tr>
<tr class="evenrow">
	<td align="center" class="back"><?php  print drupal_render($form); ?></td>
</tr>
</table>
