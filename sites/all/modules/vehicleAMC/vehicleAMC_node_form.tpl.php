<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" class="tab01" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
	<tr class="evenrow">
		<td align="center"><h2>AMC Edit Form</h2></td>
	</tr>
		<?php if($rid==3){	?>		
		<?php }?>
        <?php
		}else{ ?>
	<tr class="evenrow">
		<td align="center"><h2>AMC Entry Form</h2></td>
	</tr>		
        <?php } ?>   
   
	<tr class="oddrow">
        <td align="left"><?php print drupal_render($form['name_vendor']); ?></td>
    </tr>
	<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['amc_details']); ?></td>
	</tr>
	<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['date_from']); ?></td>
	</tr>
	<tr class="evenrow">
		<td><?php print drupal_render($form['date_valid']); ?></td>
    </tr>
   	<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['terms']); ?></td>
	</tr>
       	<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['reg_no']); ?></td>
	</tr>
	<tr class="oddrow">
      <td class="back" align="center" ><?php print drupal_render($form); ?></td>
    </tr>
  </table> 

