<style type="text/css">
#edit-language-wrapper { visibility:hidden; width:0px; }
</style>
<?php
//$rid = getRole($form['program_uid']['#value']);

?>
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Budget Received Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Budget Received Entry Form</h2></td>
		</tr>
		<?php } ?>
       <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['head2']); ?>
	  </td>
    </tr>
	<tr class="oddrow">
		<td align="left">
		<?php print drupal_render($form['date1']); ?>
		</td>
	</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['amount']); ?></td>
		</tr>
			<tr class="oddrow">
			<td class="form-text3"><?php print drupal_render($form['fin_year']); ?></td>
         </tr>
		<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
 