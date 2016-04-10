<?php

/* $Id: GLJournal.php 4565 2011-05-13 10:50:42Z daintree $*/

//include('includes/DefineJournalClass.php');

include('includes/session.inc');
$title = _('Daily Cash Book');


include('includes/SQL_CommonFunctions.inc');
//include('includes/DateFunctions.inc');
include('includes/header.inc');

//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' .
	//	_('General Ledger Accounts') . '" alt="" />' . ' ' . $title . '</p>';
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="'.$_SERVER['SCRIPT_NAME'].'">Daily Cash Book</a></div><form action="' . $_SERVER['SCRIPT_NAME'] . '" method="post" name="form">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

//echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/maintenance.png" title="' . _('Search') . '" alt="" />' . ' ' . $title.'</p>';

// A new table in the first column of the main table



echo '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="border:none;">

<tr>	<td align="left" class="tdform-width"><fieldset><legend>Daily Cash Book</legend>
 <table align="left" class="frmtbl">
		<tr><td align="left" class="tdform-width"><div class="maincol">Date: <span style="color:#FF0000">*</span></div></td><td>
		<div class="rptdate"><input type="text" name="JournalProcessDate" class="date" alt="'.$_SESSION['DefaultDateFormat'].'" maxlength=10 size=11 onChange="isDate(this, this.value, '."'".$_SESSION['DefaultDateFormat']."'".')" ></div></td><td align="left"><input type="submit" name="Process" value="Generate" /></td>';
echo '</tr></table></filedset>
	</table>';
/* close off the table in the first column  */

echo '<div class="centre"></div><br /><br />';


echo '</form>';



if(isset($_POST['Process']))
{
  $bank=array();
   $da=$_POST['JournalProcessDate'];
   $dat=explode('-',$da);
   $date=$dat[2]."-".$dat[1]."-".$dat[0];
 $ss="select * from bankaccounts";
 $ssq=DB_query($ss,$db);
 
 while($ssr=DB_fetch_array($ssq))
 {
   $bank[]=$ssr['accountcode'];
 }
 
 $acto=0;
 $ob="select accountcode from bankaccounts where type='Cash' OR type='Cheque' OR type='Saving'";
 $obq=DB_query($ob,$db);
 while($obr=DB_fetch_array($obq))
 {
   $aco="select opening_balance from chartmaster where accountcode='".$obr['accountcode']."'";
   $acoq=DB_query($aco,$db);
   $acor=DB_fetch_array($acoq);
   $acto=$acto+$acor['opening_balance'];
 }
 $opb="select sum(amount)as totamount from gltrans,chartmaster where gltrans.account=chartmaster.accountcode AND trandate< '".$date."'";
 $opbq=DB_query($opb,$db);
 $opbr=DB_fetch_array($opbq);
  $s="SELECT gltrans.*,amount as bamount,chartmaster.accountname FROM gltrans, chartmaster WHERE gltrans.account=chartmaster.accountcode AND gltrans.trandate ='".$date."'";
 //$s="SELECT gltrans.voucher_no, banktrans.ref, gltrans.account,banktrans.banktranstype, banktrans.amount as bamount, banktrans.transno, gltrans.chequeno,chartmaster.accountname FROM banktrans,gltrans,chartmaster WHERE banktrans.transdate ='".$date."'and banktrans.transno=gltrans.typeno and banktrans.transdate=gltrans.trandate and gltrans.account=chartmaster.accountcode ";
		
		//echo $s;		
    $q=DB_query($s,$db);		
	$cash=0;
	$cheque=0;
	$nn=1;
$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
	$ssnu=DB_num_rows($q);
	if($ssnu>0)
	{
	$data="<div style='overflow-x:scroll;  width:910px; border:#ccc 1px solid;'><table border='1' cellpadding='2' cellspacing='1'><tr class='oddrow'><td align='left' colspan='12'><h2>Daily Cash Book</h2></td></tr><tr><td align='center' colspan='11'>Opening Balance:".round(($opbr['totamount']+$acto),2)."</td><td align='right' colspan='4'><a href='/".$u[1]."/generateaccountpdf.php?sdate=".$date."&op=daily_cashbook&branch=".$corpbranch."' target='blank'><img src='images/pdf_icon.gif'/></a></td></<tr><tr ><td colspan='12' align='right'><strong>&nbsp;&nbsp;Report Date :</strong> &nbsp;&nbsp; ".$dat[0]."-".$dat[1]."-".$dat[2]."</td></tr><tr><td colspan='6' align='center' width=450><b>Receipts</b></td><td colspan='6' align='center' width=600><b>Payments</b></td></tr>
<tr><th>Voucher No.</th><th>A/C Code</th><th>A/C Head</th><th>Particulars</th><th>Cash Amount</th><th>Bank Amount</th><th width='80px'>Voucher No.</th><th>A/C Code</th><th>A/C Head</th><th>Particulars</th><th>Cash Amount</th><th>Bank Amount</th></tr>";		
	while($r=DB_fetch_array($q))
	{ //if((in_array($r['account'],$bank)))
	  //{
	//echo $r['account'];
	  if($r['bamount']<0)
	  {  
	     if($r['transtype']=='Cash')
		 {
		   $cash=round(abs($r['bamount']));
		   $cheque=0;
		 }
		 else if($r['transtype']=='Cheque' )
		 {
		   $cash=0;
		   $cheque=round(abs($r['bamount']));
		 }
		 if($r['type'] == 12)
		  {
			if($nn%2==0)
			{ 
			$cla='even';
			}
			else
			{
			  $cla='odd';
			}
		  
			$data.="<tr class='".$cla."'><td>".$r['voucher_no']."</td><td>".$r['account']."</td><td>".$r['accountname']."</td><td width='7%'><div style='word-wrap:break-word; width:90px;'>".$r['narrative']."</div></td><td align='right'>".$cash."</td><td align='right'>".$cheque."</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td></tr>";
			$nn++;
		 }
		}
		else if($r['bamount']>0)
	  {
	     if($r['transtype']=='Cash')
		 {
		   $cash= round(abs($r['bamount']));
		   $cheque=0;
		 }
		 else if($r['transtype']=='Cheque' )
		 {
		   $cash=0;
		   $cheque= round(abs($r['bamount']));
		 }
		 if($r['type'] == 1)
		  {
			if($nn%2==0)
			{ 
			$cla='even';
			}
			else
			{
			  $cla='odd';
			}
			$data.="<tr class='".$cla."'><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>--</td><td>".$r['voucher_no']."</td><td>".$r['account']."</td><td>".$r['accountname']."</td><td width='7%'><div style='word-wrap:break-word; width:80px;'>".$r['narrative']."</td><td align='right'>".$cash."</td><td align='right'>".$cheque."</td></tr>";
			$nn++;
		 }
		}
	//}	
	
	
	} 
	}
	else 
	{ 
	 if($_POST['JournalProcessDate'] == ""){
   echo "<span style='color:red'><b>Please Select Date</b></span><br/>"; 
}else{
	  echo "<span style='color:red'><b>No Record(s) Found</b></span><br/>";
	  }
	}
	//$data.="<tr><td colspan='10' align='right' style='font-size:10px;'>*This is a Computer Generated Report. Signature Not Required*</td></tr></table>";
	$data.="</table></div>";		
	
	echo $data;	
}
include('includes/footer.inc');
?>