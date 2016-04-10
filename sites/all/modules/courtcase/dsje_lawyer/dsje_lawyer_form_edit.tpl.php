<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Lawyer Edit Form</h2></td>   
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['lawyer_name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['practicing_since']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['court_name_id']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['specialization']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['fee_charge']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['category_id']); ?></td>
</tr>
<tr class="evenrow">
  <td align="left" class="form-text1"><?php print drupal_render($form['address']); ?></td> 
</tr>
<tr class="oddrow">
  <td align="left" class="form-text1"><?php print drupal_render($form['state_id']); ?></td> 
</tr>
<tr class="evenrow">
  <td class="form-text1"><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['phone_no']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['email']); ?></td>
</tr>
<tr class="evenrow">
  <td align="left" class="form-text1"><?php print drupal_render($form['lawyerother_detail']); ?></td>
</tr>
<tr class="oddrow">
	<td align="center" class="back"><?php print drupal_render($form); ?></td>
</tr>
</table>
