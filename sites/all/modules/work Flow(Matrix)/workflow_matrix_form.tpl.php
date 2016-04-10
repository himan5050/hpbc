<?php
//echo '<pre>';
 //print_r($form['#parameters'][2]->nid);
//echo '<pre>'; 
?>
<style>
#dms-agreement input {
  width:auto;
}
</style>

<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<tr class="oddrow">

	   <td align="center" colspan="2"><h2>Complaint Flow(Matrix)</h2></td>
	 
</tr>
<tr class="evenrow">
	<td align="left" colspan="2"><?php print drupal_render($form['complaint_category']); ?></td>
</tr>

<?php 
 // $sql = "select COUNT(*) AS count from role where rid NOT IN(1,2)";
 // $res = db_query($sql);
 // $rs = db_fetch_object($res);
 //for($i=1; $i<=$rs->count; $i++){
  $sql = "select rid,name from role where rid IN(4,3,7,8,12) ORDER BY name ASC";
  $res = db_query($sql);
  // $role[''] = array('' =>'--Select--');
  $i=1;
   while($rs = db_fetch_object($res)){
    
	if($i%2==0){
	$class = "evenrow";
	}else{
	$class = "oddrow";
	}
    	
 
?>
<tr class="<?php print $class; ?>">
	<td nowrap="nowrap" width="35%"><?php print drupal_render($form[$rs->rid]); ?></td><td nowrap="nowrap" width="80%"><?php print drupal_render($form['time'.$i]); ?></td>
    
</tr>
<?php
	 $i++;
  }
?>


<tr>
	<td colspan="2"><?php print drupal_render($form); ?></td>
</tr>
</table>
