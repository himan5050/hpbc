  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Appeal Against ACR Form</h2></td>
</tr>

<tr class="evenrow">
	<td align="left" ><?php print drupal_render($form['year']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" ><?php print drupal_render($form['acr_no']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left" ><?php print drupal_render($form['acr_status']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" ><?php print drupal_render($form['discription']); ?></td>
</tr>

<tr class="evenrow">
	<td colspan="2" align="center" class="back"><?php echo l('Back','appeal-against-acrlist'); print drupal_render($form); ?></td>
</tr>
</table>
