<?php
//$rid = getRole($form['program_uid']['#value']);
$cnode=node_load($form['#parameters'][2]->nid);
?>
 
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">
<?php if($form['#parameters'][2]->nid){
?>
		<tr class="oddrow">
		<td align="left" ><h2>Budget Consumption Edit Form</h2></td>
		</tr>		
      <?php
		}else{ ?>
		<tr class="oddrow">
		<td align="left" ><h2>Budget Consumption Entry Form</h2></td>
		</tr>
		<?php } ?>
       <tr class="evenrow">
      <td align="left" ><?php print drupal_render($form['headtype']); ?>
	  </td>
    </tr>
	<tr class="oddrow" >
		<td align="left" >
		<?php print drupal_render($form['branch']); ?>
		</td>
	</tr>
			<tr class="evenrow">
			<td class="form-text3" ><?php print drupal_render($form['fin_year']); ?></td>
        </tr>
</table>


<?php
//if($cnode->headtype && $cnode->branch && $cnode->fin_year){

if($cnode->headtype==248){
$no=db_query("select schemeName_name from tbl_schemenames ");
}else {
//expenditure
$kk= "SELECT lookupType_id,lookupType_name  FROM tbl_lookuptypes WHERE lookupType_id=90";

$tt= db_query($kk);
$rst=db_fetch_object($tt);
$sqlsender = "SELECT * FROM tbl_lookups WHERE status=1 AND lookupType_id= '".$rst->lookupType_id."' ORDER BY lookup_name ASC";

$no= db_query($sqlsender);
//$senderarray['']= '--Select--';


}
$i=-1; ?>
<div style="overflow-x:scroll; border:1px solid #ccc;">  
<table id="headchange">
<tr class="evenrow" id="headchange"><td align="left"  id="headchange" colspan="13"><?php print drupal_render($form['budget']); ?></td></tr>
<tr class="oddrow budgettextwidth">

  <td align="center">Schemes</td>
  <td  align="center">April</td>
  <td align="center">May</td>
  <td align="center">June</td>
  <td align="center">July</td>
  <td align="center">August</td>
  <td align="center">September</td>
  <td align="center">October</td>
  <td align="center">November</td>
  <td align="center">December</td>
  <td align="center">January</td>
  <td align="center">February</td>
  <td align="center">March</td>

</tr>



<?php
while($noo=db_fetch_object($no)){


$i++;

?>
	<tr class="evenrow budgettextwidth" id="headchange"><td id="headchange"><?php print drupal_render($form[$i.'getscheme']); ?></td>
    <td id="headchange"><?php print drupal_render($form[$i.'consume_apr']); print drupal_render($form[$i.'apr']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_may']);print drupal_render($form[$i.'may']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_jun']);print drupal_render($form[$i.'jun']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_jul']);print drupal_render($form[$i.'jul']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_aug']); print drupal_render($form[$i.'aug']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_sept']); print drupal_render($form[$i.'sept']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_oct']); print drupal_render($form[$i.'oct']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_nov']);  print drupal_render($form[$i.'nov']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_dec']); print drupal_render($form[$i.'dec']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_jan']); print drupal_render($form[$i.'jan']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_feb']); print drupal_render($form[$i.'feb']); ?></td>
	<td id="headchange"><?php print drupal_render($form[$i.'consume_mar']); print drupal_render($form[$i.'mar']); ?></td>
<?php }?>

</table> </div><table>
		<tr class="oddrow" >
      <td align="center" class="back"><?php  print drupal_render($form); ?></td>
    </tr>
  </table>
 