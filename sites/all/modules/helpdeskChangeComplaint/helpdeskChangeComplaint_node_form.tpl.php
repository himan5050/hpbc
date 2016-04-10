<?php
//$rid = getRole($form['program_uid']['#value']);
?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Helpdesk Change Complaint Edit Form</h2></td>
		</tr>
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Helpdesk Change Complaint Entry Form</h2></td>
		</tr>
      <?php } ?>
     <tr   class="evenrow">
      <td ><?php print drupal_render($form['application_name']); ?>
	 </td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['change_type']); ?></td>
    </tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['details']); ?></td>
		</tr>
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['reason']); ?></td>
		</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['priority']); ?></td>
		</tr>	
	<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['assign_to']); ?></td>
		</tr>
	<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
