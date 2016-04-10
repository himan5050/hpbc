<style>
.container-inline-date .form-item input, .container-inline-date .form-item select, .container-inline-date .form-item option {
width: 210px;}
input[type="text"] {width:210px;}
}
</style>
<?php
//$rid = getRole($form['program_uid']['#value']);

?>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
       <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Vacancy Edit Form</h2></td>
		</tr>
	  <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Vacancy Entry Form</h2></td>
		</tr>
	  <?php } ?>
   		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['order_no']); ?></td>
			 </tr>
    	<tr class="oddrow">
			<td align="left"><?php print drupal_render($form['vacancy_title']); ?></td>
		</tr>
    	<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['job_description']); ?></td>
	</tr>
			<tr class="oddrow">
			<td align="left"><?php print drupal_render($form['pay_details']); ?></td>
       </tr>
   <tr class="evenrow">
		<td align="left"><div style="margin-left:30px;" >Period:<div class="outbond"><div class="outcol"><?php print drupal_render($form['duration_years']); ?></div><div class="outcol"><?php print drupal_render($form['duration_months']); ?></div></div></div>
		</td>
				</tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['date_from']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['date_last']); ?></td>
    </tr>
	
	  <?php if($form['#parameters'][2]->nid){
	  ?>
 <tr class="oddrow">
      <td align="left"><?php print drupal_render($form['status2']); ?></td>
    </tr>
	  <?php } ?>	  
  
   </table>
  <table id="dispaly-hired" >  
  
<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['employee_id']); ?></td>
    </tr>	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['employee_name']); ?></td>
    </tr>
	   <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['new_organization']); ?></td>
    </tr>	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['corporation_office']); ?></td>
    </tr>
    <tr class="evenrow">
      <td align="left"><?php print drupal_render($form['designation']); ?></td>
    </tr>
	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['department']); ?></td>
    </tr>
	
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['phone']); ?></td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['mobile']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['email']); ?></td>
    </tr>
	
	
		
	</table>
	
	 
	<table>
	
		  <?php if($form['#parameters'][2]->nid){
	  ?>
 <tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
	  <?php } else{?>
	
	 <tr class="oddrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
	<?php }?>
	</table>