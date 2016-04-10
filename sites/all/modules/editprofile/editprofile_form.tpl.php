<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Edit Profile form</h2></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['dateofbirth']); ?></td>
</tr>

<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['emailid']); ?></td>
</tr>

<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['mobile']); ?></td>
</tr>

<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['phone']); ?></td>
</tr>

<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['add_line1']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['add_line2']); ?></td>
</tr>

<tr class="oddrow">
   <td align="left" ><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="evenrow">
   <td ><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left" ><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>

<tr class="evenrow">
	<td align="center" class="back"> <?php print drupal_render($form); ?></td>
</tr>
</table>
