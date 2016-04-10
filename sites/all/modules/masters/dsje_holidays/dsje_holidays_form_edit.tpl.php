<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Holiday Edit Form</h2></td>
</tr>

<tr class="evenrow">
	<td align="left" ><?php print drupal_render($form['holidays_name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" ><?php print drupal_render($form['start_date']); ?></td>
</tr>

<tr class="evenrow">
	<td colspan="2" align="center" class="back"><?php print drupal_render($form['holidays_id']); print drupal_render($form); ?></td>
</tr>
</table>
