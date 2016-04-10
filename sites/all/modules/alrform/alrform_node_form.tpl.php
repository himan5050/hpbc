<div id="form-container">
  <table width="100%" cellpadding="2" cellspacing="1" border="0">
    
     
     
		<tr class="oddrow">
		<td align="center"><h2>Arrear of Land Revenue</h2></td>
		</tr>
		<tr class="evenrow">
		<td align="left" class="form-text1"><?php print drupal_render($form['case_no']); ?></td>
		</tr>
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['namee']); ?></td>
		</tr>
		<tr class="evenrow">
		<td align="left"><div id="workingzone_div"><?php print drupal_render($form['district']); ?></div></td>
		</tr>
     
    <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['total_amount']); ?></td>
    </tr>
     <tr class="evenrow">
      <td align="left" id="address_load"><?php print drupal_render($form['talwana']); ?></td>
    </tr>
    <tr class="oddrow">
		<td align="left"><?php print drupal_render($form['datee']); ?></td>
		</tr>
		<tr class="evenrow">
		<td align="left"><div id="workingzone_div"><?php print drupal_render($form['receipt_no']); ?></div></td>
		</tr>
     
    <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['amount']); ?></td>
    </tr>
     <tr class="evenrow">
      <td align="left" id="address_load"><?php print drupal_render($form['amount_deposited_tehs']); ?></td>
    </tr>
    <tr class="oddrow">
      <td align="left" id="address_load"><?php print drupal_render($form['amount_deposited_dm']); ?></td>
    </tr>
    <tr class="evenrow">
      <td align="left" id="address_load"><?php print drupal_render($form['amount_recovered']); ?></td>
    </tr>
    <tr class="oddrow">
      <td align="left" id="address_load"><?php print drupal_render($form['balance']); ?></td>
    </tr>
    <tr class="evenrow">
      <td align="left" id="address_load"><?php print drupal_render($form['remarks']); ?></td>
    </tr>
   
    <tr>
      <td colspan="2" align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 
</div>
