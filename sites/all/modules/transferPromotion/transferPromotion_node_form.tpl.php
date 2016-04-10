<?php
$rid = getRole($form['program_uid']['#value']);

?>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Transfer/Promotion Edit Form</h2></td>
		</tr>		
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['employee_id']); ?></td>
		</tr>		
		<?php if($rid==3){
		?>		
		<?php }?>
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Transfer/Promotion Entry Form</h2></td>
		</tr>		
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['employee_id']); ?></td>
		</tr>
      <?php } ?>
    <tr  class="oddrow">
      <td align="left"><?php print drupal_render($form['employee_name']); ?>
	  </td>
    </tr>	
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['prev_officeid']); ?></td>
    </tr>	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['prev_designationid']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['prev_Departmentid']); ?></td>
    </tr>
	<tr class="oddrow">
   <td align="left"><?php print drupal_render($form['phone']); ?></td>
</tr>
<tr class="evenrow">
   <td><?php print drupal_render($form['mobile']); ?></td>
</tr>
<tr class="oddrow">
  <td align="left"><?php print drupal_render($form['email']); ?></td>
</tr>	
	
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['action']); ?></td>
    </tr>
    <tr class="evenrow" style="display:none" id="categoryname">
      <td align="left"><?php print drupal_render($form['categoryname']); ?></td>
    </tr>
    
  <tr class="oddrow">
		<td align="left"><div id="newfield"><?php print drupal_render($form['orderno']); ?></div></td>
		</tr>
		<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['order_date']); ?></td>
    </tr>
   <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['releiving_date']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['joining_date']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['current_officeid']); ?></td>
    </tr>
		<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['current_designationid']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['current_Departmentid']); ?></td>
    </tr>    
    <tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>