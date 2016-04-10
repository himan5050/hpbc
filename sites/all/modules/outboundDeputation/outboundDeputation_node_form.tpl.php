<?php
//$rid = getRole($form['program_uid']['#value']);

?>
 <div id="form-conatiner">
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Outbound Deputation Edit Form</h2></td>
		</tr>		
	
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Outbound Deputation Entry Form</h2></td>
		</tr>
		
      <?php } ?>
   
    
    
            
			<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['order_no']); ?></td>
		
			 </tr>
     
			<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['date_order']); ?></td>
	</tr>
			<tr class="evenrow">
			<td align="left"><?php print drupal_render($form['date_joining']); ?></td>
         </tr>
   <tr class="oddrow">
		<td align="left"><?php print drupal_render($form['employee_id']); ?></td>
		</tr>
    <tr  class="evenrow">
      <td align="left"><?php print drupal_render($form['employee_name']); ?>
	  </td>
    </tr>
   
	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['prev_officeid']); ?></td>
    </tr>
    
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['prev_officeaddress']); ?></td>
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
      <td align="left"><?php print drupal_render($form['new_organization']); ?></td>
    </tr>
     
	     <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['new_corporation_office']); ?></td>
    </tr>
       <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['new_corporation_address']); ?></td>
    </tr>

    <tr class="oddrow">
      <td><?php print drupal_render($form['new_department']); ?></td>
    </tr>
	  <tr class="evenrow">
      <td><?php print drupal_render($form['new_designation']); ?></td>
    </tr>    
	<!--<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['date_from']); ?></td>
    </tr>-->
	
	<tr class="oddrow">
		<td align="left"><div style="margin-left:30px;" >Period:<div class="outbond"><div class="outcol"><?php print drupal_render($form['duration_years']); ?></div><div class="outcol"><?php print drupal_render($form['duration_months']); ?></div></div></div></td>
		</tr>	
	<tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
</div>
