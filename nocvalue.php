<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
session_start();
global $base_url;
$office=$_REQUEST["m1"];
$emp_id=$_REQUEST["m2"];
$_SESSION['emp_id']=$emp_id;
$_SESSION['brancho']=$office;
if($_SESSION['brancho'] || $office){
$data .= '<table><thead><tr><th>S. No.</th><th>Particular</th><th>remarks</th><th>Amount</th></tr><thead><tbody>';
$sql ="select * from tbl_nocdetail where branch='".$_SESSION['brancho']."' AND emp_id ='".$emp_id."'";
$res = db_query($sql);
$counter = 0;
while($rs = db_fetch_object($res)){
$counter++;
if($counter%2 == 0){$class='even';}
else{$class='odd';}
$data .='<tr class='.$class.'><td>'.$counter.'</td><td>'.$rs->particulars.'</td><td>'.$rs->remarks.'</td><td>'.$rs->amount_due.'</td><tr>';
}
$data .='</tbody> </table>';
$data .='<table><tr><td align="left"><a href="javascript:void(0)" onclick="shownocdiv();"> <b>ADD Due Amount<b></a></td></tr></table>
<div id="fieldnoc" style="display:none">
   <form name="form" method="post">
<table>
       <tr class="evenrow">   
      <td width="50%">Particulars: <span style="color:#FF0000">*</span></td>
	 <td><textarea cols="60" rows="5" name="particulars" id="edit-particulars" onkeypress="return textonlywithdotnemax(event,&quot;edit-particulars&quot;,200)" class="form-textarea resizable required  textarea-processed"></textarea><input type="hidden" value='.$_SESSION["brancho"].' name="brancho" /></td>		   		   
    </tr>
	
	<tr class="oddrow">
	   <td>Due Amount:<span style="color:#FF0000">*</span></td><td><input type="text" maxlength="128" name="amount_due" id="edit-amount-due" size="60" value="" onkeypress="return paypaymain_custom(event,&quot;edit-amount-due&quot;,10)" class="form-text required"></td></tr>
	<tr class="evenrow">
	  <td>Remarks: <span style="color:#FF0000">*</span></td>
	  <td><textarea cols="60" rows="5" name="remarks" id="edit-remarks" onkeypress="return textonlywithdotnemax(event,&quot;edit-remarks&quot;,200)" class="form-textarea resizable required  textarea-processed"></textarea></td>	  
    </tr>
	
    <tr class="oddrow">
	  <td align="center" colspan="2"><input type="submit" name="save" value="Add" onkeypress="return shownocvalue("'.$base_url.'","'.$_SESSION["brancho"].'","'.$empid.'")" /> </td>
    </tr>
    </table>
	</form>
    </div>';

}
if($counter >1){echo $data;}
else{ 
$data ='<table><thead><tr><th>S. No.</th><th>Particular</th><th>remarks</th><th>Amount</th></tr><thead></table>
<table><tr><td align="left"><a href="javascript:void(0)" onclick="shownocdiv();"> <b>ADD Due Amount<b></a></td></tr></table>
<div id="fieldnoc" style="display:none">
   <form name="form" method="post">
<table>
  <tr class="evenrow">   
      <td width="50%">Particulars: <span style="color:#FF0000">*</span></td>
	 <td><textarea cols="60" rows="5" name="particulars" id="edit-particulars" onkeypress="return textonlywithdotnemax(event,&quot;edit-particulars&quot;,200)" class="form-textarea resizable required  textarea-processed"></textarea><input type="hidden" value='.$_SESSION["brancho"].' name="brancho" /></td>		   		   
    </tr>
	
	<tr class="oddrow">
	   <td>Due Amount:<span style="color:#FF0000">*</span></td><td><input type="text" maxlength="128" name="amount_due" id="edit-amount-due" size="60" value="" onkeypress="return paypaymain_custom(event,&quot;edit-amount-due&quot;,10)" class="form-text required"></td></tr>
	<tr class="evenrow">
	  <td>Remarks: <span style="color:#FF0000">*</span></td>
	  <td><textarea cols="60" rows="5" name="remarks" id="edit-remarks" onkeypress="return textonlywithdotnemax(event,&quot;edit-remarks&quot;,200)" class="form-textarea resizable required  textarea-processed"></textarea></td>	  
    </tr>
	
    <tr class="oddrow">
	  <td align="center" colspan="2"><input type="submit" name="save" value="Add" onkeypress="return shownocvalue("'.$base_url.'","'.$_SESSION["brancho"].'","'.$empid.'")" />  </td>
    </tr>
    </table>
	</form>
    </div>';




echo $data;
}

?>