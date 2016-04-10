<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Head Master(Budget) Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Head Master(Budget) Entry Form</h2></td>
		</tr>
		<?php } ?>
       <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['code']); ?>
	  </td>
    </tr>
	<tr class="oddrow">
		<td align="left">
		<?php print drupal_render($form['name1']); ?>
		</td>
	</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['fund']); ?><div class="description">*Sharing details : 51% - state govt. and 49% - central govt.</div></td>
		</tr>
		



			<tr class="oddrow">
			<td class="form-text3"><?php print drupal_render($form['type1']); ?></td>
         </tr>
		 <tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 