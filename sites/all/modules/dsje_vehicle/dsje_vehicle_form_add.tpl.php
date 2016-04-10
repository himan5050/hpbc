<div id="form-container">
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
<tr class="oddrow">
	<td align="center"><h2>Vehicle Entry Form</h2></td>   
	</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['reg_no']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['vehicle_type']); ?></td>
</tr>
 <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['model']); ?></td>
</tr>
	 <tr class="oddrow">
      <td><?php print drupal_render($form['date_purchase']); ?></td>
    </tr>
	  <tr class="evenrow">
      <td><?php print drupal_render($form['mfd']); ?></td>
    </tr>
<tr class="oddrow">
	<td align="center" colspan="2" class="back"><?php print drupal_render($form); ?></td>

</tr>



</table>

</div>