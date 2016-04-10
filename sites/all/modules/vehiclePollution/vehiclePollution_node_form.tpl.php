<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form_container">   
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Vehicle Pollution Certificate Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Vehicle Pollution Certificate Entry Form</h2></td>
		</tr>		
      <?php } ?> 
          <tr  class="evenrow">
      <td align="left"><?php print drupal_render($form['notification']); ?>
	  </td>
    </tr>
	<tr class="oddrow">
		<td align="left" class="form-text1"><?php print drupal_render($form['reg_no']); ?></td>
	</tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['certificate_no']); ?></td>
    </tr>	
			<tr class="oddrow">
		<td align="left" class="form-text1"><?php print drupal_render($form['date_pollution']); ?></td>
		</tr>
			<tr class="evenrow">
			<td><?php print drupal_render($form['date_valid']); ?></td>
         </tr>
	<tr class="oddrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>

