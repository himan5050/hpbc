<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>Escalation Add/ Edit Form </legend>
    
    <table align="left" class="frmtbl">
    <tr><td><div class="maincol"><?php print drupal_render($form['claim']); ?></div></td><td><div class="maincol"><?php print drupal_render($form['workflow_details_id']); ?></div></td><td><div class="maincol"><?php print drupal_render($form['submit']); ?>
	</div></td></tr>
	<tr><td colspan="3" align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td></tr>    
    </td></tr>
	</table>
	






<?php 
session_start();
if($_REQUEST['op'] == 'Go' && $_REQUEST['claim'] && $_REQUEST['workflow_details_id']){
     $_SESSION['claim'] = $_REQUEST['claim'];
	 $_SESSION['workflow_details_id'] = $_REQUEST['workflow_details_id'];
	 
	
}


print_r($_SESSION);
?>



</fieldset>
  </table>
</div>
</form>
<form method="post"><input type="test" ><input type="submit" name="sub" value="add"></form>




