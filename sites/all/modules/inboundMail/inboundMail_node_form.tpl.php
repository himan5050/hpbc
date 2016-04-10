<?php
$node = node_load($form['#parameters'][2]->nid);
//echo '<pre>';
//print_r($node);exit;
//$rid = getRole($form['program_uid']['#value']);

?>
 <div id="form-container"> 
  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">    
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Inbound Mail Edit Form</h2></td>
		</tr>
		<tr class="evenrow">
		<td align="left" class="form-text1"><?php print drupal_render($form['diary_no']); ?></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Inbound Mail Entry Form</h2></td>
		</tr>
		<tr class="evenrow">
		<td align="left" class="form-text1"><?php print drupal_render($form['diary_no']); ?></td>
		</tr>	
      <?php } ?>   
    
		  <tr class="oddrow">            
             <td class="form-text3"><?php print drupal_render($form['person_details']); ?></td>
          </tr>   
   <tr class="evenrow">
		<td align="left"><?php print drupal_render($form['subject']); ?></td>
		</tr>		
		<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['date1']); ?></td>
    </tr>	
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['address_to']); ?></td>
    </tr>	
		<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['file_no']); ?></td>
    </tr>
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['mod']); ?></td>
    </tr>
	<!--<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['entry_by']); ?></td>
    </tr>-->
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['assigned_to']); ?></td>
    </tr>	
	<tr class="evenrow">
      <td align="left"><?php print drupal_render($form['field_document_upload']); ?></td>
    </tr>	
	<?php 
	$check=db_query("select employee_id from tbl_joinings where program_uid='".$user->uid."' ");
	$chk=db_fetch_object($check);	
	if( $form['#parameters'][2]->nid){	
	?>	
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['status1']); ?></td>
    </tr> 
	  <tr class="evenrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
	<?php   } else{?>	
	
	<tr class="oddrow">
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
	<?php 
	 }
	?>
	
  </table>
</div>