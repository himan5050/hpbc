<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Bank Branch Entry Form</h2></td>   
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['bank_name']); ?></td>
</tr>
<tr class="oddrow">
   <td align="left"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="evenrow">
   <td ><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left" ><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
<tr class="evenrow">
  <td align="left" ><?php print drupal_render($form['bankbranch_name']); ?></td>
</tr>
<tr class="oddrow">
  <td align="left" ><?php print drupal_render($form['ifsc']); ?></td>
</tr>

<tr class="evenrow">

	<td align="left" class="form-text1"><?php print drupal_render($form['email']); ?></td>
</tr>

<tr class="oddrow">
	<td colspan="2" align="center" class="back"><?php print drupal_render($form); ?></td>
</tr>
</table>