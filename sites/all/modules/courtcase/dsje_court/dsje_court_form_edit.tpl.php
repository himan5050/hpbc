<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-conatiner">
<tr class="oddrow">
	<td align="center"><h2>Court Case Hearing Edit Form</h2></td>   
</tr>
<tr class="evenrow">

	<td align="left"><?php print drupal_render($form['case_no']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['hearing_date']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['current_hearing_date']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['next_hearing_date']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['status']); ?></td>
</tr>
<tr class="oddrow">
	<td class="back" align="center"><?php print drupal_render($form); ?></td>
</tr>
</table>

