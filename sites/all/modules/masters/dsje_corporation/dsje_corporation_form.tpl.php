<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Office Entry Form</h2></td>   
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['corporation_name']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['BO_code']); ?></td>
</tr>
<tr class="evenrow">
	<td align="left" class="form-text1"><?php print drupal_render($form['corporation_type']); ?></td>
</tr>
 <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['add_type']); ?></td>
</tr>
<tr class="evenrow">
      <td class="form-text1"><?php print drupal_render($form['add_line1']); ?></td>
</tr>
<tr class="oddrow">
      <td class="form-text1"><?php print drupal_render($form['add_line2']); ?></td>
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
      <td align="left"><div id="block_test"><?php print drupal_render($form['block_name']); ?></div></td>
</tr>

<tr class="evenrow">
      <td align="left" style="display:none" id="rural"><div id="panchayt_test"><?php print drupal_render($form['panchayt_name']); ?></div></td>
</tr>


<tr class="evenrow">
      <td align="left" class="form-text1"><?php print drupal_render($form['pincode']); ?></td>
</tr>


<tr class="oddrow">
	<td align="center" class="back"><?php print drupal_render($form); ?></td>
</tr>
</table>