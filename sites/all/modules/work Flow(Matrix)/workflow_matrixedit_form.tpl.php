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
   $sql = "select rid,name from role where rid  IN(4,3,7,8,12) ORDER BY name ASC";
  $res = db_query($sql);
  $i=1;
   while($rs = db_fetch_object($res)){
 	if($i%2==0){
	$class = "evenrow";
	}else{
	$class = "oddrow";
	}
?>
<tr class="<?php print $class; ?>">
	<td align="left" colspan="2">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
	        <td valign="top" style="width:400px" class="form-complaint"><?php print drupal_render($form[$rs->rid]); ?></td>  
			<td valign="top" style="width:400px"><?php print drupal_render($form['time'.$i]); ?></td>  
		</tr>	
	</table>
	</td>
</tr>
<?php
	 $i++;
  }
?>


<tr>
	<td align="left" colspan="2"><?php print drupal_render($form); ?></td>
</tr>
</table>
</div>