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
<?php
$op=$_REQUEST['op'];
if($op=='Generate')
{  

 if(empty($_REQUEST['startdate']['date'])){
 echo '<span style="color:#ff0000"><b>Start Date is required</b></span>';
 }
if(empty($_REQUEST['enddate']['date'])){
 echo '&nbsp;&nbsp;&nbsp;<span style="color:#ff0000"><b>End Date is required</b></span>';
 }
 if(strtotime($_REQUEST['startdate']['date']) > strtotime($_REQUEST['enddate']['date']) && !empty($_REQUEST['startdate']['date']) && !empty($_REQUEST['enddate']['date']))
   {
	  echo '<span style="color:#ff0000"><b>Start Date Can Not Be Greater Than To Date</b></span>';
   }
}
	   ?>
<div id="rec_participant">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
  
	<tr>	<td align="left" class="tdform-width"><fieldset><legend>ALR Cases With Status</legend>  
 <table align="left" class="frmtbl">
 	<tr>
    	<td><strong>Start Date: <span style="color:#FF0000">*</span></strong></td>
    	<td><div class="maincoldate"><?php print drupal_render($form['startdate']); ?></div></td>
        <td><strong>End Date: <span style="color:#FF0000">*</span></strong></td>
        <td><div class="maincoldate"><?php print drupal_render($form['enddate']); ?></div></td>
        <td align="right"><div style="margin-right:60px;"><?php print drupal_render($form); ?></div></td></tr>    
	</table>
	</fieldset></td></tr>
  </table>
</div>
<?php

$op=$_REQUEST['op'];
if($op=='Generate')
{  
 if(($_REQUEST['startdate']['date'])!='' && ($_REQUEST['enddate']['date'])!='')
   {
   global $base_url;

$std=explode('-',$_REQUEST['startdate']['date']);
$startdate=strtotime($std[2].'-'.$std[1].'-'.$std[0]);
$entd=explode('-',$_REQUEST['enddate']['date']);
 $enddate=strtotime($entd[2].'-'.$entd[1].'-'.$entd[0]);


//$startdate=strtotime($_REQUEST['startdate']['date']);
//$enddate=strtotime($_REQUEST['enddate']['date']);
 $wr="select account_number from tbl_writ where tbl_writ.current_time >=".$startdate." and tbl_writ.current_time <=".$enddate." group by account_number";
//echo $wr="select account_number from tbl_writ where DATE_FORMAT(tbl_writ.current_time,'%%d-%%m-%%Y') BETWEEN ".$_REQUEST['startdate']['date']." and ".$_REQUEST['enddate']['date']."  group by account_number";
$wrq=db_query($wr);

 $wr1="select count(account_number) as numaccount from tbl_writ where tbl_writ.current_time>=".$startdate." and tbl_writ.current_time<=".$enddate." group by account_number";
 //$wr1="select count(account_number) as numaccount from tbl_writ where tbl_writ.current_time BETWEEN  $startdate  and $enddate  group by account_number";
$wrq1=db_query($wr1);
$wrqn=db_fetch_array($wrq1);
if($wrqn['numaccount']>0)
{

  $output='<table>
		  <tr class="oddrow"><td colspan="13"><h2 style="text-align:left;">ALR Cases With Status</h2></td></tr>
		  <tr><td colspan="6" align="right"><a href="'.$base_url.'/generatedefaulterpdf.php?op=alrcases&startdate='.$startdate.'&enddate='.$enddate.'" target="_blank"><img src="account/images/pdf_icon.gif" style="float:right;" alt="Export to pdf"/></a></td></tr>
		  <tr><td colspan="5" align="right"></td></tr>
<tr><th><b>S. No.</b></th>
<th><b>Account No.</b></th>
<th><b>Loanee Name</b></th>
<th><b>Loanee Address</b></th>
<th><b>Last Payment made</b></th>
<th><span style="float:left; width:150px;"><b>Date of ALR</b></span><span><b>Status of ALR</b></span></th>
</tr>';


$l=1;
while($wrr=db_fetch_array($wrq))
{
 $wrr['account_number'];


$sql="select tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.address1,tbl_loanee_detail.address2 from tbl_loanee_detail 
where alr_status=2 and tbl_loanee_detail.account_id='".$wrr['account_number']."'";
$query=db_query($sql);

while($res=db_fetch_array($query))
{   
   if($l%2==0)
   {
    $cla="even";
   }
   else
   {
     $cla="odd";
   }
   $ss="select max(payment_date) as last_date from tbl_loan_amortisaton where loanacc_id='".$res['account_id']."'";
   $q=db_query($ss);
   $r=db_fetch_array($q);
    $st="select * from tbl_writ where account_number ='".$res['account_id']."'";
   $qt=db_query($st);
   $sta="<table style='border:0px;'>";
   while($rt=db_fetch_array($qt))
   {  
   
  // echo '<pre>';
   //print_r($rt);
   
   if($rt['status']=='amapp_property')
       {
         $stat="Movable Property Attached";
       }
	   else if($rt['status']=='iamapp_property')
	   {
	      $stat="Fixed Property Attached";
	   }
	   else
	   {
	    $stat=$rt['status'];
	   }
	  // echo $rt['current_time']."<br>";
	   
     $sta .="<tr><td>";
	 if($rt['current_time']!='') { 
	$sta .= date('d-m-Y',$rt['current_time']); }
	$sta .="</td><td>".ucwords($stat)."</td></tr>";
   }
   $sta .="</table>";
   $ld=explode('-',$r['last_date']);
   $output .='<tr class="'.$cla.'"><td align="center">'.$l.'</td><td align="right">'.$res['account_id'].'</td><td>'.ucwords($res['fname']).'</td><td>'.ucwords($res['address1']).'<br>'.ucwords($res['address2']).'</td><td align="center">'.$ld[2].'-'.$ld[1].'-'.$ld[0].'</td><td>'.$sta.'</td></tr>';
   $l++;
}
}
$output .='</table>';
echo $output;
}else
	echo "<div style='color:red'><b>No records found</b></div>";
}
  }

?>