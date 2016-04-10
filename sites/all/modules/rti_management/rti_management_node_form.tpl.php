<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>RTI Form</h2></td>
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
<td><?php print drupal_render($form['type_complaint']); ?></td>
</tr>
<tr class="evenrow">
<td align="left"><?php print drupal_render($form['applicaint_bpl']); ?></td>
</tr>
<tr class="oddrow" style="display:none" id="modepayment">
<td><?php print drupal_render($form['mode_payment']); ?></td>
</tr>

<tr class="evenrow" style="display:none" id="ipoedit">
<td align="left"><?php print drupal_render($form['ipono']); ?></td>
</tr>
<tr class="oddrow" style="display:none" id="currdatefieldedit">
<td><?php print drupal_render($form['currdatefield']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="cashipoedit">
<td align="left"><?php print drupal_render($form['cashipo']); ?></td>
</tr>

 <tr class="oddrow" style="display:none" id="currdatemoedit">
<td align="left"><?php print drupal_render($form['currdatemo']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="cashmoedit">
<td><?php print drupal_render($form['cashmo']); ?></td>
</tr>
 <tr class="oddrow" style="display:none" id="currdatecashedit">
<td align="left"><?php print drupal_render($form['currdatecash']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="cashcashedit">
<td><?php print drupal_render($form['cashcash']); ?></td>
</tr>

<tr class="oddrow">
	<td align="center" class="back"> <?php print drupal_render($form); ?></td>
</tr>
</table>
