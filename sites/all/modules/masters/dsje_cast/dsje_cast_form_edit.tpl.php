<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Caste Edit Form</h2></td>
</tr>

<tr class="evenrow">
	<td align="left" ><?php print drupal_render($form['religion_id']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" ><?php print drupal_render($form['cast_type_id']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left" ><?php print drupal_render($form['cast_name']); ?></td>
</tr>
<tr class="oddrow">
	<td colspan="2" align="center" class="back"><?php print drupal_render($form['cast_id']); print drupal_render($form); ?></td>
</tr>
</table>
