<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Vehicle Insurance Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Vehicle Insurance Entry Form</h2></td>
		</tr>
		<?php } ?>
       <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['notification']); ?>
	  </td>
    </tr>
	<tr class="oddrow">
		<td align="left">
		<?php print drupal_render($form['reg_no']); ?>
		</td>
	</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['date_insurance']); ?></td>
		</tr>
			<tr class="oddrow">
			<td class="form-text3"><?php print drupal_render($form['date_from']); ?></td>
         </tr>
   <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['date_to']); ?></td>
		</tr>
  <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['policy_no']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['sum_insured']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['person_name']); ?></td>
    </tr>
	<tr class="evenrow">
    <td align="left"><?php print drupal_render($form['add_type']); ?></td>
    </tr>
    <tr class="oddrow">
      <td><?php print drupal_render($form['add_line1']); ?></td>
    </tr>
	  <tr class="evenrow">
      <td><?php print drupal_render($form['add_line2']); ?></td>
    </tr>
	<tr class="oddrow">
   <td align="left"><?php print drupal_render($form['state_id']); ?></td>
</tr>
<tr class="evenrow">
   <td><div id="zone"><?php print drupal_render($form['district_id']); ?></div></td>
</tr>
<tr class="oddrow">
  <td align="left"><div id="state"><?php print drupal_render($form['tehsil_id']); ?></div></td>
</tr>
<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['block']); ?></td>
    </tr>
	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['panchayat']); ?></td>
    </tr>
<tr class="evenrow">
      <td align="left" ><?php print drupal_render($form['pincode']); ?></td>
    </tr>
<tr class="oddrow">
      <td colspan="2" style="text-align:center;" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 