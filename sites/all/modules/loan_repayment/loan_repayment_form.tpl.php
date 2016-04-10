<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">
	<td align="center"><h2>LokMitra - Loan Re Payment Collection form</h2></td>
</tr>
<tr class="evenrow">
	<td align="left"><?php print drupal_render($form['loan_account']); ?></td>
</tr>
<tr class="oddrow">
	<td align="left"><?php print drupal_render($form['loanee_name']); ?></td>
</tr>
<tr class="evenrow">
<td><?php print drupal_render($form['loanee_address']); ?></td>
</tr>

<tr class="oddrow">
<td><?php print drupal_render($form['mode_paymentt']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="cashamount">
<td align="left"><?php print drupal_render($form['cash_amount']); ?></td>
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


<tr class="oddrow" style="display:none" id="chequeno">
<td align="left"><?php print drupal_render($form['cheque_no']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="chequedate">
<td><?php print drupal_render($form['cheque_date']); ?></td>
</tr>
<tr class="oddrow" style="display:none" id="infavour">
<td align="left"><?php print drupal_render($form['infavour']); ?></td>
</tr>
<tr class="evenrow" style="display:none" id="bank_name">
<td><?php print drupal_render($form['bank_name']); ?></td>
</tr>
<tr class="oddrow" style="display:none" id="cheque_amount">
<td align="left"><?php print drupal_render($form['cheque_amount']); ?></td>
</tr>


<tr class="evenrow">
	<td align="center" class="back"> <?php print drupal_render($form); ?></td>
</tr>
</table>
