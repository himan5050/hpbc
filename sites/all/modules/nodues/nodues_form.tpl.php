<style type="text/css">
.container-inline-date .form-item, .container-inline-date .form-item input {
width: 100px;
display: inline;
}

input[type="text"] {
width: 100px;
height: 18px;
margin: 0;
padding: 2px;
vertical-align: middle;
font-family: sans-serif;
font-size: 14px;
border: #BCBCBC 1px solid;
}
.maincoldate{margin-top:30px;}
</style>

<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>No Dues Form</legend>  
 <table align="left" class="frmtbl listingpage_scrolltable">
 	<tr>
    	
    	<td><b>Account No.: <span class="form-required" title="This field is required.">*</span></b></td><td><?php print drupal_render($form['account']); ?></td>
        
        <td align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td></tr>    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php
$op=$_REQUEST['op'];
if($op=='Generate')
{  
 if(($_REQUEST['account'])!='' )
   {
   global $base_url;
    $sql="select tbl_loanee_detail.fname,tbl_loanee_detail.account_id,tbl_loanee_detail.loanee_id,tbl_loan_detail.sanction_date,tbl_loan_detail.remark from tbl_loan_detail,tbl_loanee_detail where tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number and tbl_loanee_detail.account_id='".$_REQUEST['account']."'";
	$query=db_query($sql);
	$res=db_fetch_array($query);
    
	$dat=explode('-',$res['sanction_date']);
	$output ='<table>';
	$output .='<tr><td><b>Name:</b> '.ucwords($res['fname']).'</td><td><b>Loan Account No.: </b> '.$res['account_id'].'</td></tr>
	           <tr><td><b>Date of Issue:</b> '.$dat[2].'-'.$dat[1].'-'.$dat[0].'</td><td><b>Remark: </b> '.ucwords($res['remark']).'</td></tr>';
			   
	 $qua="select * from tbl_guarantor_detail where loanee_id='".$res['loanee_id']."'";
	$quaq=db_query($qua);
	$i=1;
	while($quar=db_fetch_array($quaq))	  
	{  
	  if($i==1)
	  {
	  $output .='<tr><td><b>Guarantor:</b> '.ucwords($quar['gname']).'</td><td></td></tr>';
	  }
	  else
	  {
	    $output .='<tr><td>               '.ucwords($quar['gname']).'</td><td></td></tr>';
	  }
	  $i++;
	} 
			   
	$output .='</table>';
	
	echo $output;
	
  }
}
?>