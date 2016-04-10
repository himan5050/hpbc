<?php
global $user;
$uid = $user->uid;

//$rid = getRole($form['program_uid']['#value']);
  $sqlrole = "select * from users_roles where uid='".$uid."'";
  $res = db_query($sqlrole);
 $rs = db_fetch_object($res);
 $rid = $rs->rid;
//$rid=10;
$cnode=node_load($form['#parameters'][2]->nid);
?>

  <table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
      <?php if($form['#parameters'][2]->nid){
	  ?>
		<tr class="oddrow">
		<td align="center"><h2>Helpdesk Complaint Edit Form</h2></td>
		</tr>
     <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="center"><h2>Helpdesk Complaint Entry Form</h2></td>
		</tr>
      <?php } ?>
     <tr   class="evenrow">
      <td ><?php print drupal_render($form['application_name']); ?>
	 </td>
    </tr>
	<tr class="oddrow">
      <td align="left"><?php print drupal_render($form['complaint_type']); ?></td>
    </tr>
	<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['related_to']); ?></td>
		</tr>	
		
		
		
		<?php $rd =arg(2); 
		$rel=db_query("select related_to,soft from tbl_helpdesklogcomplaint where nid='".$cnode->nid."'");
		$related=db_fetch_object($rel);
		if($rd == 'edit' && !empty($related->soft)){
		?> 
		<tr class="evenrow" id="">
		<td align="left"><?php print drupal_render($form['editsoft']); ?></td>
		</tr>
		<?php  } else { ?>
		<tr class="evenrow" id="display-soft">
		<td align="left"><?php print drupal_render($form['soft']); ?></td>
		</tr>	
		<?php } ?>
		
		
		
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['subject']); ?></td>
		</tr>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['details']); ?></td>
		</tr>
		<!--<tr class="evenrow">
		<td align="left"><?php //print drupal_render($form['date_time']); ?></td>
		</tr>-->
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['priority']); ?></td>
		</tr>	
			
		<?php   if($rid == 10 || $rid==11){ ?>
					<tr class="evenrow">
					<td align="left"><?php print drupal_render($form['assign_to']); ?></td>
					</tr>
					<tr class="oddrow">
					<td align="left"><?php print drupal_render($form['status2']); ?></td>
					</tr>
       <?php } ?>
		<?php   if(($rid == 10 || $rid==11) && (!empty($form['#parameters'][2]->nid))){ ?>
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['comment']); ?></td>
		</tr>
		<?php } ?>		
		
		<?php if($rid != 10 && $rid != 11 && (!empty($form['#parameters'][2]->nid))){
	  ?>		
		<tr class="evenrow">
		<td align="left"><?php print drupal_render($form['status2']); ?></td>
		</tr>
		<tr class="oddrow">
		<td align="left"><?php print drupal_render($form['comment']); ?></td>
		</tr>   <?php } ?>
		
	<tr class="evenrow" >
      <td align="center" class="back"><?php print drupal_render($form); ?></td>
    </tr>
  </table>
