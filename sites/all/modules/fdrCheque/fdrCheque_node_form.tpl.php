<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>FDR Cheque Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>FDR Cheque Entry Form</h2></td>
		</tr>
		<?php } ?>

<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['fdr_type']); ?>
	  </td>
</tr>
       <tr class="oddrow">
      <td align="left" id="headloan2"><?php print drupal_render($form['account_no']); ?>
	  </td>
    </tr>
	<tr class="evenrow">
		<td align="left">
		<?php print drupal_render($form['cheque_no']); ?>
		</td>
	</tr>
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['bank_name']); ?></td>
		</tr>
		
		<tr class="evenrow">
		<td align="left" id="bankbranchchange"><?php print drupal_render($form['bankbranch_name']); ?></td>
		</tr>
		
		
			<tr class="oddrow">
			<td class="form-text3" id="principal1"><?php print drupal_render($form['amount']); ?></td>
         </tr>
   <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['cheque_status']); ?></td>
		</tr>
  <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['fdr_status']); ?></td>
    </tr>
<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 