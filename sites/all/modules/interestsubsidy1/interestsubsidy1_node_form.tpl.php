<?php
//$rid = getRole($form['program_uid']['#value']);

?>

  <table width="100%" cellpadding="1" cellspacing="1" border="1" id="form_container">
    
     <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="evenrow">
		<td align="center"><h2>Interest Subsidy Edit Form</h2></td>
		</tr>
		
		<tr class="oddrow">
		<td align="left"><?php //print drupal_render($form['user_type']); ?></td>
		</tr>
		<?php if($rid==3){
		?>
		<tr class="evenrow">
		<td align="left"><?php //print drupal_render($form['working_zone_id1']); ?></td>
		</tr>
		<?php }?>
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Interest Subsidy Entry Form</h2></td>
		</tr>
		
      <?php } ?> 
       
   <tr class="oddrow">
		<td align="left"><?php print drupal_render($form['corp_reg_no']); ?></td>
		</tr>
		<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['Loanee_details']); ?></td>
    </tr>
		<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['bank_acc_no']); ?></td>
    </tr>
	 <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['tot_adv_loan']); ?></td>
	</tr>
	<tr  class="oddrow">
      <td align="left"><?php print drupal_render($form['dis_date']); ?></td>
    </tr>
	<tr class="evenrow"><td class="fieldsetsml"><fieldset><legend>Amount Due During Half Year </legend><table><tr class="evenrow">
      <td align="left"><?php print drupal_render($form['d_Principle']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['d_interest']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['d_total']); ?></td></tr></table></fieldset></td>
    </tr>
	<tr class="oddrow"><td class="fieldsetsml"><fieldset><legend>Amount Deposited During Half Year </legend><table><tr class="oddrow">
      <td align="left"><?php print drupal_render($form['p_principle']); ?></td>
    </tr>
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['p_interest']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['p_total']); ?></td></tr></table></fieldset></td>
    </tr>
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['interest_sub_due']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['status_subsidy']); ?></td>
    </tr>
    <tr class="evenrow" style="display:none" id="bank_nameinterest">
      <td align="left"><?php print drupal_render($form['bank_name']); ?></td>
    </tr>
	<tr class="oddrow" style="display:none" id="chequenointerest">
      <td align="left"><?php print drupal_render($form['cheque_no']); ?></td>
    </tr>
      <tr class="evenrow" style="display:none" id="date1interest">
      <td align="left"><?php print drupal_render($form['date1']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>