<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>IT Asset Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>IT Asset Entry Form</h2></td>
		</tr>
		<?php } ?>
       <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['section']); ?>
	  </td>
    </tr>
	<tr class="oddrow">
		<td align="left">
		<?php print drupal_render($form['asset_type']); ?>
		</td>
	</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['quantity']); ?></td>
		</tr>
			<tr class="oddrow">
			<td class="form-text3"><?php print drupal_render($form['amount']); ?></td>
         </tr>
   <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['proc_cost']); ?></td>
		</tr>
  <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['asset_details']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['company_name']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['sum_insured']); ?></td>
    </tr>
	<tr class="evenrow">
    <td align="left"><?php print drupal_render($form['date_renewal']); ?></td>
    </tr>
    <tr class="oddrow">
      <td><?php print drupal_render($form['claim_details']); ?></td>
    </tr>
	  <tr class="evenrow">
      <td><?php print drupal_render($form['vendor_name']); ?></td>
    </tr>
	<tr class="oddrow">
   <td align="left"><?php print drupal_render($form['date_amc']); ?></td>
</tr>
<tr class="evenrow">
   <td><div id="zone"><?php print drupal_render($form['amount_amc']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left"><div id="state"><?php print drupal_render($form['contract_details']); ?></div></td>
</tr>
<tr class="evenrow">
      <td colspan="2" style="text-align:center;" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 