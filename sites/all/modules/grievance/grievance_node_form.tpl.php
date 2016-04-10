<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>Grievance Form</h2></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['application_type']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['office']); ?></td>
</tr>
<tr class="evenrow">
<td><?php print drupal_render($form['section']); ?></td>
</tr>
<!--<tr class="oddrow">
<td><?php //print drupal_render($form['datecurrent']); ?></td>
</tr>-->
<tr class="oddrow">
<td align="center"><h2>Application Detail</h2></td>
</tr>
<tr class="evenrow">
<td><?php print drupal_render($form['application_name']); ?></td>
</tr>
<tr class="oddrow">
<td align="left"><?php print drupal_render($form['application_category']); ?></td>
</tr>
<!--<tr class="evenrow">
   <td align="left"><?php //print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="oddrow">
   <td ><div id="zone"><?php //print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="evenrow">
  <td align="left" ><div id="state"><?php //print drupal_render($form['tehsil_id']); ?></div></td>
</tr>-->


<tr class="evenrow">
<td align="left"><?php print drupal_render($form['permanent_address']); ?></td>
</tr>
<tr class="oddrow">
<td><?php print drupal_render($form['correspondence_address']); ?></td>
</tr>
<tr class="evenrow">
<td align="left"><?php print drupal_render($form['telephone_number']); ?></td>
</tr>
<tr class="oddrow">
<td><?php print drupal_render($form['mobile_number']); ?></td>
</tr>
<tr class="evenrow">
<td align="left"><?php print drupal_render($form['email_address']); ?></td>
</tr>

<tr class="oddrow">
	<td align="center" class="back"> <?php print drupal_render($form); ?></td>
</tr>
</table>
