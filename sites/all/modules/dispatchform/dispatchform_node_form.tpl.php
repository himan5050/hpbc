<?php
//$rid = getRole($form['program_uid']['#value']);

?>

  <table width="100%" cellpadding="1" cellspacing="1" border="1" id="form_container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Dispatch Edit Form</h2></td>
		</tr>
		<tr class="evenrow">
		<td align="left" ><?php print drupal_render($form['dispatch_no']); ?></td>
		</tr>
		<?php /*?><tr class="oddrow">
		<td align="left"><?php //print drupal_render($form['user_type']); ?></td>
		</tr><?php */?>
		<?php if($rid==3){
		?>
		<?php /*?><tr class="evenrow">
		<td align="left"><?php //print drupal_render($form['working_zone_id1']); ?></td>
		</tr><?php */?>
		<?php }?>
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Dispatch Entry Form</h2></td>
		</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['dispatch_no']); ?></td>
		</tr>
		
      <?php } ?>   
   <tr class="oddrow">
		<td align="left"><?php print drupal_render($form['sender_details']); ?></td>
		</tr>
		<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['file_no']); ?></td>
    </tr>
		<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['subject']); ?></td>
    </tr>
	 <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['person_name']); ?></td>
	</tr>
	<tr  class="oddrow">
      <td align="left"><?php print drupal_render($form['person_details']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['date1']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['mod']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['amount']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['dispatch_type']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>