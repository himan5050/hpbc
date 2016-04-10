<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
require_once('tcpdf/pdfcss.php');

// create new PDF document
$pdf = new TCPDF(L, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('SC and ST');
$pdf->SetTitle('SC and ST');
$pdf->SetSubject('SC and ST');
$pdf->SetKeywords('SC and ST');

$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, 'Nikhil Bhawan, Power House Road Saproon, Solan-173211');
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('helvetica', '', 10);
// add a page
$pdf->AddPage();


if($_REQUEST['op'] == 'claim'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Detail of claims made by Employee</td></tr>
</table>';
	
if(isset($_GET['emp_id']) && $_GET['emp_id']!='')
	  {
	    $cond.='and at.emp_id="'.$_GET['emp_id'].'"';
	  }

   $s="select at.emp_id,at.date,at.net_amount,at.status,tj.employee_name from medical_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".$from."' and at.date<='".$to."') and at.emp_id=tj.employee_id";
   $q=db_query($s,$db);
	$n=1;
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$to).'</td></tr>
</table><br>';
 




$output .='<table cellpadding="3" cellspacing="2" class="tbl_border">
<tr>
<td class="header2">S. No.</td>
<td class="header2">Employee Id</td>
<td class="header2">Employee Name</td>
<td class="header2">Claim Type</td>
<td class="header2">Claim Date</td>
<td class="header2">Amount</td>
<td class="header2">Status</td>
</tr>';





	 
	
	 if($_GET['type']=='m' || $_GET['type']=='' )
	  {
	
	if($n)
	{
	  while($r=db_fetch_array($q))
	  { $qua++; 
	  
	                      if($r['status']==1)
		                  {
						   $st= "Approved";
						  }
						  else if($r['status']==2)
						  {
						    $st= "Rejected";
						  }
						  else if($r['status']==3)
						  {
						    $st= "Queried";
						  }
						  else if($r['status']==0)
						  {
						   $st= "Pending";
						  }
	  
		
		 if($qua%2==0)
	  {
	    $cl="header4_1";
		  $output .='<tr ><td class="header4_1">'.$qua.'</td><td class="header4_1">'.$r['emp_id'].'</td><td class="header4_1">'.ucwords($r['employee_name']).'</td><td class="header4_1">Medical</td><td class="header4_1" align="center">'.date('d-m-Y',$r['date']).'</td><td class="header4_1" align="right">'.$r['net_amount'].'</td><td  class="header4_1">'. $st.'</td></tr>';
	  }
	  else
	  {
	    $cl="header4_2";
		$output .='<tr ><td class="header4_2">'.$qua.'</td><td class="header4_2">'.$r['emp_id'].'</td><td class="header4_2">'.ucwords($r['employee_name']).'</td><td class="header4_2">Medical</td><td class="header4_2" align="center">'.date('d-m-Y',$r['date']).'</td><td class="header4_2" align="right">'.$r['net_amount'].'</td><td class="header4_2">'.$st.'</td></tr>';
	  } 
		
		
	  }
	}
	}
	
	
	$sa="select at.emp_id,at.date,at.total_amount,at.status,tj.employee_name from tour_claim as at,tbl_joinings as tj where 1=1  ".$cond." and (at.date>='".$from."' and at.date<='".$to."') and at.emp_id=tj.employee_id";
	$qa=db_query($sa,$db);
	$na=1;
	if($_GET['type']=='t' || $_GET['type']=='' )
	  {
	if($na)
	{
	  while($ra=db_fetch_array($qa))
	  {  
	    
	      $qua++;
	if($qua%2==0)
	  {
	    $cl="header4_1";
	  }
	  else
	  {
	    $cl="header4_2";
	  }
	     if($r['status']==1)
		                  {
						   $st= "Approved";
						  }
						  else if($r['status']==2)
						  {
						    $st= "Rejected";
						  }
						  else if($r['status']==3)
						  {
						    $st= "Queried";
						  }
						  else if($r['status']==0)
						  {
						   $st= "Pending";
						  }
	    $output.='<tr ><td class="'.$cl.'">'.$qua.'</td><td class="'.$cl.'">'.$ra['emp_id'].'</td><td class="'.$cl.'">'.ucwords($ra['employee_name']).'</td><td class="'.$cl.'">Tour</td><td class="'.$cl.'" align="center">'.date('d-m-Y',$ra['date']).'</td><td class="'.$cl.'" align="right">'.$ra['total_amount'].'</td><td class="'.$cl.'">'.$st.'</td></tr>';
	  }
	}
	  }
	
	  
	  $output .="</table>";
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('claim_'.time().'.pdf', 'I');
}


/*if($_REQUEST['op'] == 'trial_balance'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:985px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Trial Balance</td></tr>
</table><br/>';
	
$s="select * from accountgroups";
$q=DB_query($s,$db);
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();
//echo $TableHeader;

$pe="select * from periods where periodno='".$to."'";
$peq=db_query($pe);
$per=db_fetch_array($peq);
$da=explode('-',$per['lastdate_in_period']);
if($da[1]=='1')
{
 $mo="January";
}
if($da[1]=='2')
{
 $mo="February";
}
if($da[1]=='3')
{
 $mo="March";
}
if($da[1]=='4')
{
 $mo="April";
}
if($da[1]=='5')
{
 $mo="May";
}
if($da[1]=='6')
{
 $mo="June";
}
if($da[1]=='7')
{
 $mo="July";
}
if($da[1]=='8')
{
 $mo="August";
}
if($da[1]=='9')
{
 $mo="September";
}
if($da[1]=='10')
{
 $mo="October";
}
if($da[1]=='11')
{
 $mo="November";
}
if($da[1]=='12')
{
 $mo="December";
}
 $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">Trail Balance As On '.$mo.' '.$da[0].'</td></tr>

</table>';
 




$output .='<table  cellpadding="3" cellspacing="2" class="tbl_border">
<tr ><td class="header2">Account</td>
<td class="header2">Account Name</td>
<td class="header2">Debit</td>
<td class="header2">Credit</td>

</tr>';
while($r=db_fetch_array($q))
{
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$r['groupname']."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period>='-55' and chartdetails.period<='".$to."' ";
 $qq=db_query($sq,$db);
 if (1){
 //echo "<tr><td colspan='4'><b>".$r['groupname']."</b></td></tr>";
 //echo "<b>".$r['groupname']."</b></br>";
 
 while($qr=db_fetch_array($qq))
 { 
  $accode=$qr['accountcode'];

  
  if(in_array($accode,$acc))
    {
      if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$account[$accode]['cr']+$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $account[$accode]['de'] =$account[$accode]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
		
    }
  else
    {
     $acc[]=$qr['accountcode'];
	$account[$accode]['name']=$qr['accountname'];
	$account[$accode]['group']=$r['groupname'];
	  if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $account[$accode]['cr'] =$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $account[$accode]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
		
  
    }
	}
 }
  
}
$nn=1;
foreach($acc as $acid)
{ 
   $totaldebit[]=$account[$acid]['de'];
   $totalcredit[]=$account[$acid]['cr'];
    if($account[$acid]['de']=='')
     {
      $account[$acid]['de']=0;
     }
	 if($account[$acid]['cr']=='')
     {
      $account[$acid]['cr']=0;
     }
	 
	 
	if($nn%2==0)
	{
	  $cl="header4_1"; 
	}
	else
	{
	 $cl="header4_2";
	}
  	
	                      
	    $output.='<tr><td class="'.$cl.'">'.$acid.'</td><td class="'.$cl.'">'.$account[$acid]['name'].'</td><td class="'.$cl.'">'.$account[$acid]['de'].'</td><td class="'.$cl.'">'.$account[$acid]['cr'].'</td></tr>';
		$nn++;
		}
 $output.='<tr><td style="border-top-color:#ccc; border-top:1px solid;"></td><td style="border-top-color:#ccc; border-top:1px solid;"><b>Total:</b></td><td style="border-top-color:#ccc; border-top:1px solid;"><b>'.round(array_sum($totaldebit),2).'</b></td><td style="border-top-color:#ccc; border-top:1px solid;"><b>'.round(array_sum($totalcredit),2).'</b></td></tr>';
 $output.='</table><br />';
	

	
	  
	  $output.='<tr><td></td></tr></table>';
	
	

 
		 $output .= '</table>';
	// $output;
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('trial_balance_'.time().'.pdf', 'I');
}*/


if($_REQUEST['op'] == 'incexp'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
//width:540px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$fro=explode('-',$from);
$tro=explode('-',$to);
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Income Expenditure Report</td></tr></table><br>
<table><tr><td class="header1"><b>From Date :</b> '.$fro[2].'-'.$fro[1].'-'.$fro[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.$tro[2].'-'.$tro[1].'-'.$tro[0].'</td></tr></table><br>';
	

	$data='';
	  $acg=array();
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();
//$inex="<table><tr><td colspan='4'>Income</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
$data ='<table cellpadding="0" cellspacing="2" border="0" class="tbl_border"><tr><td colspan="3" align="center" class="header2"><h2>Income</h2></td></tr>';
$data .='<tr><td class="header2"><b>Code</b></td><td class="header2"><b>A/C</b></td><td class="header2"><b>Income</b></td></tr>';
 

$s="select * from accountgroups where sectioninaccounts='1'";
$q=db_query($s);
while($r=db_fetch_array($q))
{
 $acg[]=$r['groupname'];
 
 $ss="select * from accountgroups where parentgroupname='".$r['groupname']."' ";
 $ssq=db_query($ss);
  while($ssr=db_fetch_array($ssq))
  {
    $acg[]=$ssr['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
$f=1;
foreach($acg as $acco)
{
  
 $sq="select chartmaster.accountcode as accountcode,chartmaster.accountname,gltrans.amount,gltrans.trandate from chartmaster,gltrans where chartmaster.group_='".$acco."' and gltrans.account=chartmaster.accountcode and gltrans.trandate>='".$from."' and gltrans.trandate<='".$to."' ";
 $qq=db_query($sq);

 while($qr=db_fetch_array($qq))
 {  if($f%2==0)
 {
   $cla="header4_1";
 }
 else
 {
   $cla="header4_2";
 }
  $accode=$qr['accountcode'];
if($qr['amount']>0)
{
  $debtotal=$debtotal+$qr['amount'];
 

	$data .= '<tr>
  				<td class="'.$cla.'" align="right">'.$qr['accountcode'].'</td>
				<td class="'.$cla.'">'.ucwords($qr['accountname']).'</td>
				<td class="'.$cla.'" align="right">'.$qr['amount'].'</td>
		  </tr>';
		   $f++;
     }
	 
   }
  
   }


foreach($acc as $acid)
{ 
 
   $totaldebit[]=$account[$acid]['de'];
   $totalcredit[]=$account[$acid]['cr'];
    if($account[$acid]['de']=='')
     {
      $account[$acid]['de']=0;
     }
	 if($account[$acid]['cr']=='')
     {
      $account[$acid]['cr']=0;
     }
	 
 
		  
}
$data.= '<tr><td>Total</td><td>&nbsp;</td>
				<td align="right">'.round($debtotal,2).'</td></tr>';
$data .='</table>';




//expenditure goes nichus


//$data;
//$pdf->writeHTML($data, true, 0, true, true);
$datae='';
$acge=array();
$debtotale=0;
$cretotale=0;
$totaldebite=array();
$totalcredite=array();
$acce=array();
$accounte=array();
$datae ='<table cellpadding="0" cellspacing="2" border="0" class="tbl_border"><tr><td colspan="3" align="center" class="header2"><h2>Expenditure</h2></td></tr>';
$datae .='<tr><td class="header2"><b>Code</b></td><td class="header2"><b>A/C</b></td><td class="header2"><b>Expenditure</b></td></tr>';
 

$se="select * from accountgroups where sectioninaccounts='3'";
$qe=db_query($se);
while($re=db_fetch_array($qe))
{
 $acge[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $sseq=db_query($sse);
  while($sser=db_fetch_array($sseq))
  {
    $acge[]=$sser['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
$fe=1;
foreach($acge as $accoe)
{
  
 $sqe="select chartmaster.accountcode as accountcode,chartmaster.accountname,gltrans.amount,gltrans.trandate from chartmaster,gltrans where chartmaster.group_='".$accoe."' and gltrans.account=chartmaster.accountcode and gltrans.trandate>='".$from."' and gltrans.trandate<='".$to."' ";
 $qqe=db_query($sqe);

 while($qre=db_fetch_array($qqe))
 { if($fe%2==0)
 {
   $cla="header4_1";
 }
 else
 {
   $cla="header4_2";
 }
  $accodee=$qre['accountcode'];
if($qre['amount']<0)
{
  $debtotale=$debtotale+$qre['amount'];
 

	$datae .= '<tr>
  				<td class="'.$cla.'" align="right">'.$qre['accountcode'].'</td>
				<td class="'.$cla.'">'.ucwords($qre['accountname']).'</td>
				<td class="'.$cla.'" align="right">'.$qre['amount'].'</td>
		  </tr>';
		  $fe++;
     }
	 
   }
   
   }

/*$f=1;
foreach($acce as $acide)
{ 

   $totaldebit[]=$account[$acid]['de'];
   $totalcredit[]=$account[$acid]['cr'];
    if($account[$acid]['de']=='')
     {
      $account[$acid]['de']=0;
     }
	 if($account[$acid]['cr']=='')
     {
      $account[$acid]['cr']=0;
     }
	 
 
		  $f++;
}*/
$datae .= '<tr><td>Total</td><td>&nbsp;</td><td align="right">'.round($debtotale,2).'</td></tr>';
 $datae .='</table>';

 //$datae;
 
  $output .='<table><tr><td><div>'.$datae.'</div></td><td><div>'.$data.'</div></td></tr></table>';

// $output .='<table><tr><td valign="top" '.$class.'>'.$data.'</td><td valign="top" '.$class.'>'.$datae.'</td></tr><table>';
 
 
	
	

	

 
		 
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('incexp_'.time().'.pdf', 'I');
}



if($_REQUEST['op'] == 'daily_cashbook'){
global $user, $base_url;
$from =$_REQUEST['sdate'];


$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$dat=explode('-',$from);
$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center" class="header_report">
Daily Cash Book for '.$dat[2].'-'.$dat[1].'-'.$dat[0].' </td></tr>
<tr><td colspan="5" class="header1" align="right"></td></tr>
</table>';
	


   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>Date :</b> '. $dat[2].'-'.$dat[1].'-'.$dat[0].'</td></tr>
</table><br/>';
 

$bank=array();
  
 $ss="select * from bankaccounts";
 $ssq=db_query($ss,$db);
 
 while($ssr=db_fetch_array($ssq))
 {
   $bank[]=$ssr['accountcode'];
 }
 
  $acto=0;
 $ob="select accountcode from bankaccounts where type='Cash' OR type='Cheque' OR type='Saving'";
 $obq=db_query($ob,$db);
 while($obr=db_fetch_array($obq))
 {
   $aco="select opening_balance from chartmaster where accountcode='".$obr['accountcode']."'";
   $acoq=db_query($aco,$db);
   $acor=db_fetch_array($acoq);
   $acto=$acto+$acor['opening_balance'];
 }
  $opb="select sum(amount)as totamount from banktrans where transdate< '".$from."'";
 $opbq=db_query($opb,$db);
 $opbr=db_fetch_array($opbq);
 
  //$s="SELECT banktrans.ref, banktrans.bankact, banktrans.banktranstype, banktrans.amount as bamount, banktrans.transno, chartmaster.accountname FROM banktrans,chartmaster WHERE banktrans.transdate ='".$date."'and banktrans.bankact=chartmaster.accountcode";
  $s="SELECT banktrans.ref, gltrans.account,gltrans.voucher_no, banktrans.banktranstype, banktrans.amount as bamount, banktrans.transno, gltrans.chequeno,chartmaster.accountname
FROM banktrans,gltrans,chartmaster WHERE banktrans.transdate ='".$from."'and banktrans.transno=gltrans.typeno and banktrans.transdate=gltrans.trandate and gltrans.account=chartmaster.accountcode ";
		
				
    $q=db_query($s,$db);		
	$cash=0;
	$cheque=0;
	//$ssnu=db_num_rows($q);
	$ssnu=1;
	$nn=1;
	$output .='<table border="0" cellpadding="3" cellspacing="2"  class="tbl_border" style="width:985px">
	<tr><td colspan="12" class="header2">Opening Balance:'.round(($opbr['totamount']+$acto),2).' </td></tr>
	<tr ><td colspan="6" align="center" style="text-decoration:underline;" class="header2"><b>Receipts</b></td><td colspan="6" align="center" style="text-decoration:underline;" class="header2"><b>Payments</b></td></tr>
<tr><td class="header2" width="8%">Voucher No.</td><td class="header2" width="8%">A/C Code</td><td class="header2" width="10%">A/C Head</td><td class="header2" width="8%">Particulars</td><td class="header2" width="8%">Cash Amount</td><td class="header2" width="8%">Bank Amount</td><td class="header2" width="7%">Voucher No.</td><td class="header2" width="7%">A/C Code</td><td class="header2" width="10%">A/C Head</td><td class="header2" width="8%">Particulars</td><td class="header2" width="8%">Cash Amount</td><td class="header2" width="10%">Bank Amount</td></tr>';		
	
	while($r=db_fetch_array($q))
	{ 
	   if($nn%2==0)
	   {
	     $cl="header4_1";
	   }
	   else
	   {
	     $cl="header4_2";
	   }
	if(!(in_array($r['account'],$bank)))
	  {
	//echo $r['account'];
	
	  if($r['bamount']>0)
	  { // echo "test";
	     if($r['banktranstype']=='Cash')
		 {
		   $cash=$r['bamount'];
		   $cheque=0;
		 }
		 else if($r['banktranstype']=='Cheque' )
		 {
		   $cash=0;
		   $cheque=$r['bamount'];
		 }
		  
	    $output .='<tr ><td class="'.$cl.'">'.$r["voucher_no"].'</td><td class="'.$cl.'">'.$r['account'].'</td><td class="'.$cl.'">'.$r["accountname"].'</td><td class="'.$cl.'">'.$r["ref"].'</td><td class="'.$cl.'">'.$cash.'</td><td class="'.$cl.'">'.$cheque.'</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td></tr>';
		}
		else if($r['bamount']<0)
	  {
	     if($r['banktranstype']=='Cash')
		 {
		   $cash=$r['bamount'];
		   $cheque=0;
		 }
		 else if($r['banktranstype']=='Cheque' )
		 {
		   $cash=0;
		   $cheque=$r['bamount'];
		 }
	    $output .='<tr><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">--</td><td class="'.$cl.'">'.$r["voucher_no"].'</td><td class="'.$cl.'">'.$r['account'].'</td><td class="'.$cl.'">'.$r["accountname"].'</td><td class="'.$cl.'">'.$r["ref"].'</td><td class="'.$cl.'">'.$cash.'</td><td class="'.$cl.'">'.$cheque.'</td></tr>';
		}$nn++;
	}	
	
	}
	
	
	//$data.="<tr><td colspan='10' align='right' style='font-size:10px;'>*This is a Computer Generated Report. Signature Not Required*</td></tr></table>";
	$output .='</table>';		
	
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('daily_cashbook_'.time().'.pdf', 'I');
}


if($_REQUEST['op'] == 'audit'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$office=$_REQUEST['office'];
$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

        $s23="select * from tbl_corporations where corporation_id='".$office."'"; 
		$q23=db_query($s23,$db);
		$r23=db_fetch_array($q23);

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Audit Report</td></tr>
</table>';
	
   $s="select at.emp_id,at.date,at.net_amount,at.status,tj.employee_name from medical_claim as at,tbl_joinings as tj where  (at.date>='".$from."' and at.date<='".$to."') and at.emp_id=tj.employee_id";
   $q=db_query($s,$db);
	$n=1;
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$to).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Office :</b> '.ucwords($r23['corporation_name']).'</td></tr>
</table><br>';
 




$output .=' <table class="tbl_border" cellspacing="2" cellpadding="3">
	  <tr><td width="10%" class="header2"><b>Audit Date</b></td><td width="10%" class="header2"><b>Office Name</b></td><td width="10%" class="header2"><b>Section Name</b></td><td width="10%" class="header2"><b>Auditor</b></td><td width="10%" class="header2"><b>Remark</b></td><td width="12%" class="header2"><b>NCs</b></td><td width="8%" class="header2"><b>Description</b></td><td width="10%" class="header2"><b>Severity</b></td><td width="10%" class="header2"><b>Clause</b></td><td width="9%" class="header2"><b>Action Taken</b></td></tr>';

 $s="select * from audit_detail where auditdate>='".$from."' and auditdate<='".$to."' and auditoffice='".$office."' order by auditdate";
	  $q=db_query($s,$db);
	  $k=1;
	  while($r=db_fetch_array($q))
	  {  if($k%2==0)
	        {
		      $cl="header4_1";
	        }
			else
			{
			  $cl="header4_2";	
			}
	    $s1="select name from users where uid='".$r['auditor']."'"; 
		$q1=db_query($s1,$db);
		$r1=db_fetch_array($q1);
		
		$s2="select * from tbl_corporations where corporation_id='".$r['auditoffice']."'"; 
		$q2=db_query($s2,$db);
		$r2=db_fetch_array($q2);
	  
	   //nc goes here
	   $nc="select * from nsc_detail where audit_id='".$r['audit_id']."'";	
	   $ncq=db_query($nc,$db);
	  $nctd ='<table style="border:none;" cellpadding="3" cellspacing="3">';
	  
	   while($ncr=db_fetch_array($ncq))
	   {
	     
	     $nctd .='<tr ><td width="12%" style="border:none;" class="'.$cl.'"><div style="word-wrap:break-word; width:60px;">'.ucwords($ncr['nsc']).'</div></td><td width="8%" style="border:none;" class="'.$cl.'"><div style="word-wrap:break-word; width:60px;">'.ucwords($ncr['description']).'</div></td><td width="10%" style="border:none;" class="'.$cl.'"><div style="word-wrap:break-word; width:60px;">'.$ncr['sevirity'].'</div></td><td width="9.5%" style="border:none;" class="'.$cl.'"><div style="word-wrap:break-word; width:60px;">'.ucwords($ncr['clause']).'</div></td><td width="7%" style="border:none;" class="'.$cl.'"><div style="word-wrap:break-word; width:60px;" >'.ucwords($ncr['corrective_report']).'</div></td></tr>';
		 
	   }
	   $nctd.='</table>';
	   
	   
	   $output .='<tr><td valign="top" width="10%" class="'.$cl.'" align="center">'.date('d-m-Y',$r['auditdate']).'</td><td valign="top" width="10%" class="'.$cl.'">'.ucwords($r2['corporation_name']).'</td><td valign="top" width="10%" class="'.$cl.'">'.ucwords($r['section']).'</td><td valign="top" width="10%" class="'.$cl.'">'.ucwords($r1['name']).'</td><td width="10%" valign="top" class="'.$cl.'">'.ucwords($r['remark']).'</td><td colspan="5" width="10%" class="'.$cl.'">'.ucwords($nctd).'</td><td class="'.$cl.'"></td></tr>';
	   	$k++;	   
	  }
	$output .='</table>';



	 
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('audit_'.time().'.pdf', 'I');
}



if($_REQUEST['op'] == 'reconcillation'){
global $user, $base_url;
$bankaccount =$_REQUEST['bankaccount'];
//$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$fro=explode('-',$from);
$tro=explode('-',$to);
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Bank Reconcillation Report</td></tr></table><br>';
	
//$pdf->writeHTML($output, true, 0, true, true);



	$sql = "SELECT MAX(period) as p
			FROM chartdetails 
			WHERE accountcode='".$bankaccount."'";
	$PrdResult = db_query($sql);
	$myrow = db_fetch_object($PrdResult);
	$LastPeriod = $myrow->p;

	$SQL = "SELECT bfwd+actual AS balance
			FROM chartdetails 
			WHERE period='" . $LastPeriod . "' 
			AND accountcode='".$bankaccount."'";

	//$ErrMsg = _('The bank account balance could not be returned by the SQL because');
	$BalanceResult = db_query($SQL);

	$myrow = db_fetch_object($BalanceResult);
	$Balance = $myrow->balance;

	/* Now need to get the currency of the account and the current table ex rate */
	$SQL = "SELECT rate,
					bankaccounts.currcode as currcode,
					bankaccounts.bankaccountname as bankaccountname
			FROM bankaccounts INNER JOIN currencies
			ON bankaccounts.currcode=currencies.currabrev
			WHERE bankaccounts.accountcode = '".$bankaccount."'";
	//$ErrMsg = _('Could not retrieve the currency and exchange rate for the selected bank account');
	$CurrencyResult = db_query($SQL,$db);
	$CurrencyRow =  db_fetch_object($CurrencyResult);
	$ExRate = $CurrencyRow->rate;
	$BankCurrCode = $CurrencyRow->currcode;
	$BankAccountName = $CurrencyRow->bankaccountname;

	$output .='<table class="tbl_border" cellspacing="2" cellpadding="3">
			<tr><td class="header2"><b>'.$BankAccountName;
			
	/*if ($_SESSION['CompanyRecord']['currencydefault']!=$BankCurrCode){
		echo  ' (' . $BankCurrCode . ' @ ' . $ExRate .')';
	}*/
	$output .='</b></td>
			<td class="header2"><b>'.number_format($Balance*$ExRate,$CurrDecimalPlaces).'</b></td></tr>';

 //$datae;
 $SQL = "SELECT amount/exrate AS amt,
					amountcleared,
					(amount/exrate)-amountcleared as outstanding,
					ref,
					transdate,
					systypes.typename,
					transno
				FROM banktrans,
					systypes
				WHERE banktrans.type = systypes.typeid
				AND banktrans.bankact='" . $bankaccount . "'
				AND amount < 0
				AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	//$output .='<tr><td></td></tr>'; /*Bang in a blank line */

	//$ErrMsg = _('The unpresented cheques could not be retrieved by the SQL because');
	$UPChequesResult = db_query($SQL);

	$output .='<tr><td colspan="6" class="header4_1"><b>Add back unpresented cheques:</b></td></tr>';

	$TableHeader = '<tr>
				    <td width="15%" class="header2">Date</td>
					<td width="15%" class="header2">Type</td>
					<td width="15%" class="header2">Number</td>
					<td width="24%" class="header2">Reference</td>
					<td width="15%" class="header2">Orig Amount</td>
					<td width="15%" class="header2">Outstanding</td>
					</tr>';

	$output .= $TableHeader;

	$j = 1;
	$k=0; //row colour counter
	$TotalUnpresentedCheques =0;

	while ($myrow1=db_fetch_array($UPChequesResult)) {
		 if($j%2==0)
	{
	 $cla="header4_1";
	}
	else
	{
	  $cla="header4_2";
	}

$DisplayTranDat=explode('-',$myrow1['transdate']);
$DisplayTranDate=$DisplayTranDat[2]."-".$DisplayTranDat[1]."-".$DisplayTranDat[0];
		$output .='<tr><td align="center" class="'.$cla.'">'.$DisplayTranDate.'</td>
				<td class="'.$cla.'">'.$myrow1['typename'].'</td>
				<td class="'.$cla.'">'.$myrow1['transno'].'</td>
				<td class="'.$cla.'">'.$myrow1['ref'].'</td>
				<td class="'.$cla.'" align="right">'.number_format($myrow1['amt'],$CurrDecimalPlaces).'</td>
				<td class="'.$cla.'" align="right">'.number_format($myrow1['outstanding'],$CurrDecimalPlaces).'</td>
				</tr>';

		$TotalUnpresentedCheques +=$myrow1['outstanding'];

		$j++;
		If ($j == 18){
			$j=1;
			$output .= $TableHeader;
		}
	}
	
	
	//end of while loop
	$output .='<tr><td colspan="5" class="header4_2"><b>Total of all unpresented cheques</b></td><td align="right" class="header4_2">'. number_format($TotalUnpresentedCheques,$CurrDecimalPlaces) .'</td></tr>';

	$SQL = "SELECT amount/exrate AS amt,
				amountcleared,
				(amount/exrate)-amountcleared as outstanding,
				ref,
				transdate,
				systypes.typename,
				transno
			FROM banktrans,
				systypes
			WHERE banktrans.type = systypes.typeid
			AND banktrans.bankact='".$bankaccount."'
			AND amount > 0
			AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	//$output .='<tr></tr>'; /*Bang in a blank line */

	//$ErrMsg = _('The uncleared deposits could not be retrieved by the SQL because');

	$UPChequesResult = db_query($SQL);

	$output .='<tr><td class="header4_1" colspan="6"><b>Less deposits not cleared:</b></td></tr>';

	$TableHeader = '<tr>
				    <td width="15%" class="header2">Date</td>
					<td width="15%" class="header2">Type</td>
					<td width="15%" class="header2">Number</td>
					<td width="24%" class="header2">Reference</td>
					<td width="15%" class="header2">Orig Amount</td>
					<td width="15%" class="header2">Outstanding</td>
					</tr>';

	$output .= $TableHeader;

	$j = 1;
	$kk=1; //row colour counter
	$TotalUnclearedDeposits =0;

	while ($myrow=DB_fetch_array($UPChequesResult)) {
		
    if($kk%2==0)
	{
	 $cla="header4_1";
	}
	else
	{
	  $cla="header4_2";
	}
		$output .='<tr><td align="center"  class="'.$cla.'">'.$DisplayTranDate.'</td>
				<td class="'.$cla.'" >'.$myrow['typename'].'</td>
				<td class="'.$cla.'">'.$myrow['transno'].'</td>
				<td class="'.$cla.'">'.$myrow['ref'].'</td>
				<td class="'.$cla.'" align="right">'.number_format($myrow['amt'],$CurrDecimalPlaces).'</td>
				<td class="'.$cla.'" align="right">'.number_format($myrow['outstanding'],$CurrDecimalPlaces).'</td>
				</tr>' ;

		$TotalUnclearedDeposits +=$myrow['outstanding'];

		$j++;
		If ($j == 18){
			$j=1;
			$output .= $TableHeader;
		}
		$kk++;
	}
	//end of while loop
	$output .='
			<tr >
				<td colspan="4" class="header4_1"><b>Total of all uncleared deposits</b></td>
				<td align="right" class="header4_1">' . number_format($TotalUnclearedDeposits,$CurrDecimalPlaces) . '</td><td class="header4_1"></td></tr>';
	$FXStatementBalance = ($Balance*$ExRate) - $TotalUnpresentedCheques -$TotalUnclearedDeposits;
	//<td colspan=6><b>' . _('Bank statement balance should be') . ' (' . $BankCurrCode . ')</b></td>
	$output .='
			<tr >
				<td colspan="5" class="header2"><b>Bank statement balance should be(INR)</b></td>
				<td align="right" class="header2">' . number_format($FXStatementBalance,$CurrDecimalPlaces) . '</td><td class="header4_1"></td></tr>';

 
  $output .='</table>';

ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('reconcillation_'.time().'.pdf', 'I');
}


if($_REQUEST['op'] == 'auditschedule'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
$office=$_REQUEST['office'];
$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">
Audit Schedule Report</td></tr>
</table><br>';
	
   $s="select at.emp_id,at.date,at.net_amount,at.status,tj.employee_name from medical_claim as at,tbl_joinings as tj where  (at.date>='".$from."' and at.date<='".$to."') and at.emp_id=tj.employee_id";
   $q=db_query($s,$db);
	$n=1;
	$s5="select * from tbl_corporations where corporation_id='".$office."'"; 
		$q5=db_query($s5,$db);
		$r5=db_fetch_array($q5);
   $output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. date('d-m-Y',$from).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.date('d-m-Y',$to).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Office :</b> '.$r5['corporation_name'].'</td></tr>
</table><br>';
 




$output .=' <table class="tbl_border" cellspacing="2" cellpadding="3" style="width:2250px;">
	  <tr><td width="10%" class="header2" width="3%" ><b>S. No.</b></td><td width="10%" class="header2"><b>Office Name</b></td><td width="10%" class="header2"><b>Auditor</b></td><td width="10%" class="header2"><b>Audit Start Date</b></td><td width="10%" class="header2"><b>Period</b></td></tr>';

 $s="select * from audit_plan where startdate>='".$from."' and startdate<='".$to."' and auditoffice='".$office."' order by startdate";
	  $q=db_query($s,$db);
	  $k=1;
	  while($r=db_fetch_array($q))
	  {  if($k%2==0)
	        {
		      $cl="header4_1";
	        }
			else
			{
			  $cl="header4_2";	
			}
	    $s1="select name from users where uid='".$r['auditor']."'"; 
		$q1=db_query($s1,$db);
		$r1=db_fetch_array($q1);
		
		$s2="select * from tbl_corporations where corporation_id='".$r['auditoffice']."'"; 
		$q2=db_query($s2,$db);
		$r2=db_fetch_array($q2);
	  
	   //nc goes here
	    /* $s3="select * from audit_plan where id='".$r['audit_id']."'"; 
		$q3=DB_query($s3,$db);
		$r3=DB_fetch_array($q3);*/
	   
	   
	   $output .='<tr><td valign="top" width="3%" class="'.$cl.'" align="center">'.$k.'</td><td valign="top" width="10%" class="'.$cl.'">'.ucwords($r2['corporation_name']).'</td><td valign="top" width="10%" class="'.$cl.'">'.ucwords($r1['name']).'</td><td valign="top" width="10%" class="'.$cl.'" align="center">'.date('d-m-Y',$r['startdate']).'</td><td valign="top" width="10%" class="'.$cl.'">'.$r['period'].'</td></tr>';
	   	$k++;	   
	  }
	$output .='</table>';



	 
	
	

 
		 $output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('auditschedule_'.time().'.pdf', 'I');
}

if($_REQUEST['op'] == 'trial_balance'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:985px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Trial Balance With Budget</td></tr>
</table><br/>';
	
$SQL = "SELECT accountgroups.groupname,
			accountgroups.parentgroupname,
			accountgroups.pandl,
			chartdetails.accountcode ,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $from . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
			Sum(CASE WHEN chartdetails.period='" . $from . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.actual ELSE 0 END) AS monthactual,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.budget ELSE 0 END) AS monthbudget,
			Sum(CASE WHEN chartdetails.period='" . $to . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
		FROM chartmaster INNER JOIN accountgroups ON chartmaster.group_ = accountgroups.groupname
			INNER JOIN chartdetails ON chartmaster.accountcode= chartdetails.accountcode
		GROUP BY accountgroups.groupname,
				accountgroups.pandl,
				accountgroups.sequenceintb,
				accountgroups.parentgroupname,
				chartdetails.accountcode,
				chartmaster.accountname
		ORDER BY accountgroups.pandl desc,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";


	$AccountsResult = db_query($SQL);

	//echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Trial Balance</a></div>';

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */

	$output .='<table cellpadding="2" class="selection">';
	/*$output .='<tr><th colspan=6><font size=3 color=blue><b>Trial Balance for the month of ') . $PeriodToDate .
		_(' and for the ') . $NumberOfMonths . _(' months to ') . $PeriodToDate .'</b></font></th></tr>';*/
	$TableHeader = '<tr>
					<td class="header2">Account</td>
					<td class="header2">Account Name</td>
					<td class="header2">Month Actual</td>
					<td class="header2">Month Budget</td>
					<td class="header2">Period Actual</td>
					<td class="header2">Period Budget</td>
					</tr>';

	$j = 1;
	$k=0; //row colour counter
	$ActGrp ='';
	$ParentGroups = array();
	$Level =1; //level of nested sub-groups
	$ParentGroups[$Level]='';
	$GrpActual =array(0);
	$GrpBudget =array(0);
	$GrpPrdActual =array(0);
	$GrpPrdBudget =array(0);

	$PeriodProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;
	$MonthProfitLoss = 0;
	$MonthBudgetProfitLoss = 0;
	$BFwdProfitLoss = 0;
	$CheckMonth = 0;
	$CheckBudgetMonth = 0;
	$CheckPeriodActual = 0;
	$CheckPeriodBudget = 0;

	while ($myrow=DB_fetch_array($AccountsResult)) {

		if ($myrow['groupname']!= $ActGrp ){
			if ($ActGrp !=''){ //so its not the first account group of the first account displayed
				if ($myrow['parentgroupname']==$ActGrp){
					$Level++;
					$ParentGroups[$Level]=$myrow['groupname'];
					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]='';
				} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
					$output .='<tr>
						<td colspan="2"><b>'.$ParentGroups[$Level].' Total </b></td>
						<td align="right"><b>'.number_format($GrpActual[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpBudget[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpPrdActual[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpPrdBudget[$Level],2).'</b></td>
						</tr>';

					$GrpActual[$Level] =0;
					$GrpBudget[$Level] =0;
					$GrpPrdActual[$Level] =0;
					$GrpPrdBudget[$Level] =0;
					$ParentGroups[$Level]=$myrow['groupname'];
				} else {
					do {
						$output .='<tr>
							<td colspan="2"><b>'.$ParentGroups[$Level].' Total</b></td>
							<td align="right"><b>'.number_format($GrpActual[$Level],2).'</b></td>
							<td align="right"><b>'.number_format($GrpBudget[$Level],2).'</b></td>
							<td align="right"><b>'.number_format($GrpPrdActual[$Level],2).'</b></td>
							<td align="right"><b>'.number_format($GrpPrdBudget[$Level],2).'</b></td>
							</tr>';

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
						$Level--;

						$j++;
					} while ($Level>0 and $myrow['groupname']!=$ParentGroups[$Level]);

					if ($Level>0){
						$output .='<tr>
						<td colspan="2"><b>'.$ParentGroups[$Level].' Total</b></td>
						<td align="right"><b>'.number_format($GrpActual[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpBudget[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpPrdActual[$Level],2).'</b></td>
						<td align="right"><b>'.number_format($GrpPrdBudget[$Level],2).'</b></td>
						</tr>';

						$GrpActual[$Level] =0;
						$GrpBudget[$Level] =0;
						$GrpPrdActual[$Level] =0;
						$GrpPrdBudget[$Level] =0;
						$ParentGroups[$Level]='';
					} else {
						$Level=1;
					}
				}
			}
			$ParentGroups[$Level]=$myrow['groupname'];
			$ActGrp = $myrow['groupname'];
			$output .='<tr>
				<td colspan="6"><b>'.$myrow['groupname'].'</b></td>
				</tr>';
			$output .=$TableHeader;
			$j++;
		}

		/*if ($k==1){
			$output .='<tr class="header4_1">';
			$k=0;
		} else {
			$output .='<tr class="header4_2">';
			$k++;
		}*/
		/*MonthActual, MonthBudget, FirstPrdBFwd, FirstPrdBudgetBFwd, LastPrdBudgetCFwd, LastPrdCFwd */


		if ($myrow['pandl']==1){

			$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
			$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];

			$PeriodProfitLoss += $AccountPeriodActual;
			$PeriodBudgetProfitLoss += $AccountPeriodBudget;
			$MonthProfitLoss += $myrow['monthactual'];
			$MonthBudgetProfitLoss += $myrow['monthbudget'];
			$BFwdProfitLoss += $myrow['firstprdbfwd'];
		} else { /*PandL ==0 its a balance sheet account */
			if ($myrow['accountcode']==$RetainedEarningsAct){
				$AccountPeriodActual = $BFwdProfitLoss + $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $BFwdProfitLoss + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			} else {
				$AccountPeriodActual = $myrow['lastprdcfwd'];
				$AccountPeriodBudget = $myrow['firstprdbfwd'] + $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
			}

		}

		if (!isset($GrpActual[$Level])) {
			$GrpActual[$Level]=0;
		}
		if (!isset($GrpBudget[$Level])) {
			$GrpBudget[$Level]=0;
		}
		if (!isset($GrpPrdActual[$Level])) {
			$GrpPrdActual[$Level]=0;
		}
		if (!isset($GrpPrdBudget[$Level])) {
			$GrpPrdBudget[$Level]=0;
		}
		$GrpActual[$Level] +=$myrow['monthactual'];
		$GrpBudget[$Level] +=$myrow['monthbudget'];
		$GrpPrdActual[$Level] +=$AccountPeriodActual;
		$GrpPrdBudget[$Level] +=$AccountPeriodBudget;

		$CheckMonth += $myrow['monthactual'];
		$CheckBudgetMonth += $myrow['monthbudget'];
		$CheckPeriodActual += $AccountPeriodActual;
		$CheckPeriodBudget += $AccountPeriodBudget;

		$ActEnquiryURL = $myrow['accountcode'];
if($j%2==0)
	{
	  $cl="header4_1"; 
	}
	else
	{
	 $cl="header4_2";
	}
		$output .='<tr><td class="'.$cl.'">'.$ActEnquiryURL.'</td>
			<td class="'.$cl.'">'.$myrow['accountname'].'</td>
			<td class="'.$cl.'" align="right">'.number_format($myrow['monthactual'],2).'</td>
			<td class="'.$cl.'" align="right">'.number_format($myrow['monthbudget'],2).'</td>
			<td class="'.$cl.'" align="right">'.number_format($AccountPeriodActual,2).'</td>
			<td class="'.$cl.'" align="right">'.number_format($AccountPeriodBudget,2).'</td>
			</tr>';

		$j++;
	}
	//end of while loop


	if ($ActGrp !=''){ //so its not the first account group of the first account displayed
		if ($myrow['parentgroupname']==$ActGrp){
			$Level++;
			$ParentGroups[$Level]=$myrow['groupname'];
		} elseif ($ParentGroups[$Level]==$myrow['parentgroupname']) {
			$output .='<tr>
				<td colspan="2">'.$ParentGroups[$Level].' </td>
				<td align="right">'.number_format($GrpActual[$Level],2).'</td>
				<td align="right">'.number_format($GrpBudget[$Level],2).'</td>
				<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
				<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
				</tr>';

			$GrpActual[$Level] =0;
			$GrpBudget[$Level] =0;
			$GrpPrdActual[$Level] =0;
			$GrpPrdBudget[$Level] =0;
			$ParentGroups[$Level]=$myrow['groupname'];
		} else {
			do {
				$output .='<tr>
					<td colspan="2"><b>'.$ParentGroups[$Level].' Total </b></td>
					<td align="right"><b>'.number_format($GrpActual[$Level],2).'</b></td>
					<td align="right"><b>'.number_format($GrpBudget[$Level],2).'</b></td>
					<td align="right"><b>'.number_format($GrpPrdActual[$Level],2).'</b></td>
					<td align="right"><b>'.number_format($GrpPrdBudget[$Level],2).'</b></td>
					</tr>';

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
				$Level--;

				$j++;
			} while (isset($ParentGroups[$Level]) and ($myrow['groupname']!=$ParentGroups[$Level] and $Level>0));

			if ($Level >0){
				$output .='<tr>
				<td colspan="2">'.$ParentGroups[$Level].' Total </td>
				<td align="right">'.number_format($GrpActual[$Level],2).'</td>
				<td align="right">'.number_format($GrpBudget[$Level],2).'</td>
				<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
				<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
				</tr>';

				$GrpActual[$Level] =0;
				$GrpBudget[$Level] =0;
				$GrpPrdActual[$Level] =0;
				$GrpPrdBudget[$Level] =0;
				$ParentGroups[$Level]='';
			} else {
				$Level =1;
			}
		}
	}



	$output .='<tr bgcolor="#ffffff">
			<td colspan="2" class="header2"><b> Check Totals</b></td>
			<td class="header2" align="right">'.number_format($CheckMonth,2).'</td>
			<td class="header2" align="right">'.number_format($CheckBudgetMonth,2).'</td>
			<td class="header2" align="right">'.number_format($CheckPeriodActual,2).'</td>
			<td class="header2" align="right">'.number_format($CheckPeriodBudget,2).'</td>
		</tr>';

	$output .='</table>';
	
	

 
		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('trial_balance_'.time().'.pdf', 'I');
}

if($_REQUEST['op'] == 'reconcillation1'){
global $user, $base_url;
$bankaccount =$_REQUEST['bankaccount'];
//$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:985px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Trial Balance</td></tr>
</table><br/>';
	
//$RetainedEarningsAct=3500;

/*Get the balance of the bank account concerned */

	$sql = "SELECT MAX(period) 
			FROM chartdetails 
			WHERE accountcode='" . $bankaccount."'";
	$PrdResult = db_query($sql);
	$myrow = db_fetch_row($PrdResult);
	$LastPeriod = $myrow[0];

	$SQL = "SELECT bfwd+actual AS balance
			FROM chartdetails 
			WHERE period='" . $LastPeriod . "' 
			AND accountcode='" . $bankaccount."'";

	
	$BalanceResult = db_query($SQL);

	$myrow = db_fetch_array($BalanceResult);
	$Balance = $myrow['balance'];

	/* Now need to get the currency of the account and the current table ex rate */
	$SQL = "SELECT rate,
					bankaccounts.currcode as currcode,
					bankaccounts.bankaccountname as bankaccountname
			FROM bankaccounts INNER JOIN currencies
			ON bankaccounts.currcode=currencies.currabrev
			WHERE bankaccounts.accountcode = '" . $bankaccount."'";
//	$ErrMsg = _('Could not retrieve the currency and exchange rate for the selected bank account');
	$CurrencyResult = db_query($SQL);
	$CurrencyRow =  db_fetch_array($CurrencyResult);
	$ExRate = $CurrencyRow['rate'];
	$BankCurrCode = $CurrencyRow['currcode'];
	$BankAccountName = $CurrencyRow['bankaccountname'];

	$output .='<br /><table class="selection">
			<tr class="even"><td colspan="6"><b>' . $BankAccountName . ' Balance as at ' . Date($_SESSION['DefaultDateFormat']);
			
	/*if ($_SESSION['CompanyRecord']['currencydefault']!=$BankCurrCode){
		echo  ' (' . $BankCurrCode . ' @ ' . $ExRate .')';
	}*/
	$output .='</b></td>
			<td valign="bottom" class="number"><b>' . number_format($Balance*$ExRate,$CurrDecimalPlaces) . '</b></td></tr>';

	$SQL = "SELECT amount/exrate AS amt,
					amountcleared,
					(amount/exrate)-amountcleared as outstanding,
					ref,
					transdate,
					systypes.typename,
					transno
				FROM banktrans,
					systypes
				WHERE banktrans.type = systypes.typeid
				AND banktrans.bankact='" . $bankaccount . "'
				AND amount < 0
				AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	$output .='<tr></tr>'; /*Bang in a blank line */

	$ErrMsg = _('The unpresented cheques could not be retrieved by the SQL because');
	$UPChequesResult = db_query($SQL);

	$output .='<tr class="oddrow"><td colspan=7><b>Add back unpresented cheques:</b></td></tr>';

	$TableHeader = '<tr>
					<td>Date</td>
					<td>Type</td>
					<td>Number</td>
					<td>Reference</td>
					<td>Orig Amount</td>
					<td colspan="2">Outstanding</td>
					
					</tr>';

	$output .=$TableHeader;

	$j = 1;
	$k=0; //row colour counter
	$TotalUnpresentedCheques =0;

	while ($myrow=DB_fetch_array($UPChequesResult)) {
		if ($k==1){
			$output .='<tr class="even">';
			$k=0;
		} else {
			$output .='<tr class="odd">';
			$k++;
		}

$DisplayTranDat=explode('/',ConvertSQLDate($myrow['transdate']));
$DisplayTranDate=$DisplayTranDat[0]."-".$DisplayTranDat[1]."-".$DisplayTranDat[2];
		$output .='<td align="center">'.$DisplayTranDate.'</td>
				<td align="left">'.$myrow['typename'].'</td>
				<td align="right">'.$myrow['transno'].'</td>
				<td align="left">'.$myrow['ref'].'</td>
				<td align="right" class="number">'.number_format($myrow['amt'],$CurrDecimalPlaces).'</td>
				<td align="right" colspan="2" class="number">'.number_format($myrow['outstanding'],$CurrDecimalPlaces).'</td>
				</tr>';

		$TotalUnpresentedCheques +=$myrow['outstanding'];

		$j++;
		If ($j == 18){
			$j=1;
			$output .=$TableHeader;
		}
	}
	//end of while loop
	$output .='<tr></tr>
			<tr class="even"><td colspan="6"><b>Total of all unpresented cheques</b></td><td align="right" class="number">' . number_format($TotalUnpresentedCheques,$CurrDecimalPlaces) . '</td></tr>';

	$SQL = "SELECT amount/exrate AS amt,
				amountcleared,
				(amount/exrate)-amountcleared as outstanding,
				ref,
				transdate,
				systypes.typename,
				transno
			FROM banktrans,
				systypes
			WHERE banktrans.type = systypes.typeid
			AND banktrans.bankact='" . $bankaccount . "'
			AND amount > 0
			AND ABS((amount/exrate)-amountcleared)>0.009 ORDER BY transdate";

	$output .='<tr></tr>'; /*Bang in a blank line */

	//$ErrMsg = _('The uncleared deposits could not be retrieved by the SQL because');

	$UPChequesResult = db_query($SQL);

	$output .='<tr class="oddrow"><td colspan="7"><b>Less deposits not cleared:</b></td></tr>';

	$TableHeader = '<tr>
					<td>Date</td>
					<td>Type</td>
					<td>Number</td>
					<td>Reference</td>
					<td>Orig Amount</td>
					<td colspan="2">Outstanding</td>
					</tr>';

	$output .='<tr>' . $TableHeader;

	$j = 1;
	$k=0; //row colour counter
	$TotalUnclearedDeposits =0;

	while ($myrow=db_fetch_array($UPChequesResult)) {
		if ($k==1){
			$output .='<tr class="even">';
			$k=0;
		} else {
			$output .='<tr class="odd">';
			$k++;
		}

		$output .='<td align="center">'.$DisplayTranDate.'</td>
				<td align="left">'.$myrow['typename'].'</td>
				<td align="right">'.$myrow['transno'].'</td>
				<td align="left">'.$myrow['ref'].'</td>
				<td align="right" class="number">'.number_format($myrow['amt'],$CurrDecimalPlaces).'</td>
				<td align="right" colspan="2" class="number">'.number_format($myrow['outstanding'],$CurrDecimalPlaces).'</td>
				</tr>';

		$TotalUnclearedDeposits +=$myrow['outstanding'];

		$j++;
		If ($j == 18){
			$j=1;
			$output .=$TableHeader;
		}
	}
	//end of while loop
	$output .='<tr></tr>
			<tr class="even">
				<td colspan="6"><b>Total of all uncleared deposits</b></td>
				<td align="right" class="number">' . number_format($TotalUnclearedDeposits,$CurrDecimalPlaces) . '</td>
			</tr>';
	$FXStatementBalance = ($Balance*$ExRate) - $TotalUnpresentedCheques -$TotalUnclearedDeposits;
	//<td colspan=6><b>' . _('Bank statement balance should be') . ' (' . $BankCurrCode . ')</b></td>
	$output .='<tr></tr>
			<tr class="oddrow">
				<td colspan="6"><b>Bank statement balance should be(INR)</b></td>
				<td align="right" class="number">' . number_format($FXStatementBalance,$CurrDecimalPlaces) . '</td></tr>';

	/*if (isset($_POST['DoExchangeDifference'])){
		$output .='<input type="hidden" name="DoExchangeDifference" value=' . $FXStatementBalance . '>';
		$output .='<tr><td colspan="6">Enter the actual bank statement balance (' . $BankCurrCode . ')</b></td>
				<td class="number"><input type="text" name="BankStatementBalance" maxlength="15" size="15" value=' . $_POST['BankStatementBalance'] . '><td></tr>';
		$output .='<tr><td colspan="7" align="center"><input type="submit" name="PostExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '" onclick="return confirm(\'' . _('This will create a general ledger journal to write off the exchange difference in the current balance of the account. It is important that the exchange rate above reflects the current value of the bank account currency') . ' - ' . _('Are You Sure?') . '\');"></td></tr>';

	}*/



	/*if ($_SESSION['CompanyRecord']['currencydefault']!=$BankCurrCode AND !isset($_POST['DoExchangeDifference'])){

		echo '<tr><td colspan=7><hr></td></tr>
				<tr><td colspan=7>' . _('It is normal for foreign currency accounts to have exchange differences that need to be reflected as the exchange rate varies. This reconciliation is prepared using the exchange rate set up in the currencies table (see the set-up tab). This table must be maintained with the current exchange rate before running the reconciliation. If you wish to create a journal to reflect the exchange difference based on the current exchange rate to correct the reconciliation to the actual bank statement balance click below.') . '</td></tr>';
		echo '<tr><td colspan=7 align="center"><input type=submit name="DoExchangeDifference" value="' . _('Calculate and Post Exchange Difference') . '"></td></tr>';		
		
	}*/
	$output .='</table>';

	
	

 
		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('reconcillation_'.time().'.pdf', 'I');
}

if($_REQUEST['op'] == 'balancesheet'){
global $user, $base_url;
//$bankaccount =$_REQUEST['bankaccount'];
$period = $_REQUEST['period'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:985px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;
$Sections = array();
$sql = 'SELECT sectionid, sectionname FROM accountsection ORDER by sectionid';
$SectionResult = db_query($sql);
while( $secrow = db_fetch_array($SectionResult) ) {
	$Sections[$secrow['sectionid']] = $secrow['sectionname'];
}
//db_free_result($SectionResult);
$_REQUEST['Detail']='Detailed';
$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Balance Sheet</td></tr>
</table><br/>';
	

$RetainedEarningsAct=$_REQUEST['earning'];
/*Get the balance of the bank account concerned */

	
	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $period . "'";
	$PrdResult = db_query($sql);
	$myrow = db_fetch_array($PrdResult);
	$BalanceDate = $myrow['lastdate_in_period'];

	/*Calculate B/Fwd retained earnings */

	$SQL = "SELECT Sum(CASE WHEN chartdetails.period='" . $period . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS accumprofitbfwd,
			Sum(CASE WHEN chartdetails.period='" . ($period - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lyaccumprofitbfwd
		FROM chartmaster INNER JOIN accountgroups
		ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
		ON chartmaster.accountcode= chartdetails.accountcode
		WHERE accountgroups.pandl=1";

	$AccumProfitResult = db_query($SQL);

	$AccumProfitRow = db_fetch_array($AccumProfitResult); /*should only be one row returned */

	$SQL = "SELECT accountgroups.sectioninaccounts,
			accountgroups.groupname,
			accountgroups.parentgroupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			Sum(CASE WHEN chartdetails.period='" . $period . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS balancecfwd,
			Sum(CASE WHEN chartdetails.period='" . ($period - 12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lybalancecfwd
		FROM chartmaster INNER JOIN accountgroups
		ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
		ON chartmaster.accountcode= chartdetails.accountcode
		WHERE accountgroups.pandl=0
		GROUP BY accountgroups.groupname,
			chartdetails.accountcode,
			chartmaster.accountname,
			accountgroups.parentgroupname,
			accountgroups.sequenceintb,
			accountgroups.sectioninaccounts
		ORDER BY accountgroups.sectioninaccounts,
			accountgroups.sequenceintb,
			accountgroups.groupname,
			chartdetails.accountcode";

	$AccountsResult = db_query($SQL);
//	echo '<div class="breadcrumb">Home &raquo; <a href="'.$_SERVER['PHP_SELF'].'">Balance Sheet</a></div>';
   $bald=explode('-',$BalanceDate);
	$output .='<div ><table class="selection"><tr><td colspan="6"><div class="centre"><b>Balance Sheet as at ' . $bald[2].'-'.$bald[1].'-'.$bald[0] .'</b></div></td></tr></table><br><table>';

	if ($_REQUEST['Detail']=='Detailed'){
		$TableHeader = '<tr>
				<td class="header2">Account</td>
				<td class="header2">Account Name</td>
				<td colspan="2" class="header2">BalanceDate</td>
				<td colspan="2" class="header2">Last Year</td>
				</tr>';
	} else { /*summary */
		$TableHeader = '<tr>
				<td colspan="2"></td>
				<td colspan="2">BalanceDate</td>
				<td colspan="2">Last Year</td>
				</tr>';
	}


	$k=0; //row colour counter
	$Section='';
	$SectionBalance = 0;
	$SectionBalanceLY = 0;

	$LYCheckTotal = 0;
	$CheckTotal = 0;

	$ActGrp ='';
	$Level=0;
	$ParentGroups=array();
	$ParentGroups[$Level]='';
	$GroupTotal = array(0);
	$LYGroupTotal = array(0);

	$output .=$TableHeader;
	$j=0; //row counter

	while ($myrow=db_fetch_array($AccountsResult)) {
		$AccountBalance = $myrow['balancecfwd'];
		$LYAccountBalance = $myrow['lybalancecfwd'];

		if ($myrow['accountcode'] == $RetainedEarningsAct){
			$AccountBalance += $AccumProfitRow['accumprofitbfwd'];
			$LYAccountBalance += $AccumProfitRow['lyaccumprofitbfwd'];
		}

		if ($myrow['groupname']!= $ActGrp AND $ActGrp != '') {
			if ($myrow['parentgroupname']!=$ActGrp){
				while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0){
					if ($_REQUEST['Detail']=='Detailed'){
						$output .='<tr>
							<td colspan="2"></td>
      							<td><hr></td>
							<td></td>
							<td><hr></td>
							<td></td>
							</tr>';
					}
					$output .='<tr><td colspan="2">'.$ParentGroups[$Level].'</td>
						<td >'.number_format($GroupTotal[$Level]).'</td>
						<td></td>
						<td >'.number_format($LYGroupTotal[$Level]).'</td>
						</tr>';
					$GroupTotal[$Level] = 0;
					$LYGroupTotal[$Level] = 0;
					$ParentGroups[$Level]='';
					$Level--;
					$j++;
				}
				if ($_REQUEST['Detail']=='Detailed'){
					$output .='<tr>
						<td colspan="2"></td>
						<td><hr></td>
						<td></td>
						<td><hr></td>
						<td></td>
						</tr>';
				}

				$output .='<tr><td colspan="2">'.$ParentGroups[$Level].'</td>
					<td class=number>'.number_format($GroupTotal[$Level]).'</td>
					<td></td>
					<td class=number>'.number_format($LYGroupTotal[$Level]).'</td>
					</tr>';
				$GroupTotal[$Level] = 0;
				$LYGroupTotal[$Level] = 0;
				$ParentGroups[$Level]='';
				$j++;
			}
		}
		//$pdf->writeHTML($output, true, 0, true, true);
		if ($myrow['sectioninaccounts']!= $Section ){

			if ($Section!=''){
				if ($_REQUEST['Detail']=='Detailed'){
					$output .='<tr>
					<td colspan="2"></td>
					<td><hr></td>
					<td></td>
					<td><hr></td>
					<td></td>
					</tr>';
				} else {
					$output .='<tr>
					<td colspan="3"></td>
					<td><hr></td>
					<td></td>
					<td><hr></td>
					</tr>';
				}

				$output .='<tr>
					<td colspan="3">'.$Sections[$Section].'</td>
					<td >'.number_format($SectionBalance).'</td>
					<td></td>
					<td >'.number_format($SectionBalanceLY).'</td>
				</tr>';
				$j++;
			}
			$SectionBalanceLY = 0;
			$SectionBalance = 0;
			$Section = $myrow['sectioninaccounts'];


			if ($_REQUEST['Detail']=='Detailed'){
				$output .='<tr>
					<td colspan="6"><b>'.$Sections[$myrow['sectioninaccounts']].'</b></td>
					</tr>';
			}
		}

		if ($myrow['groupname']!= $ActGrp){

			if ($ActGrp!='' AND $myrow['parentgroupname']==$ActGrp){
				$Level++;
			}

			if ($_REQUEST['Detail']=='Detailed'){
				$ActGrp = $myrow['groupname'];
				$output .='<tr>
				<td colspan="6"><b>'.$myrow['groupname'].'</b></td>
				</tr>';
				$output .=$TableHeader;
			}
			$GroupTotal[$Level]=0;
			$LYGroupTotal[$Level]=0;
			$ActGrp = $myrow['groupname'];
			$ParentGroups[$Level]=$myrow['groupname'];
			$j++;
		}

		$SectionBalanceLY +=	$LYAccountBalance;
		$SectionBalance	  +=	$AccountBalance;
		for ($i=0;$i<=$Level;$i++){
			$LYGroupTotal[$i] += $LYAccountBalance;
			$GroupTotal[$i] += $AccountBalance;
		}
		$LYCheckTotal	  +=	$LYAccountBalance;
		$CheckTotal  	  +=	$AccountBalance;


		if ($_REQUEST['Detail']=='Detailed'){

			/*if ($k==1){
				$output .='<tr class="header4_1">';
				$k=0;
			} else {
				$output .='<tr class="header4_2">';
				$k++;
			}*/
        if($j%2==0)
		{
		  $cla="header4_1";
		}
		else
		{
		  $cla="header4_2";
		}
		//$cla="header4_2";
			$ActEnquiryURL = $myrow['accountcode'];

			$output .='<tr><td class="'.$cla.'">'.$ActEnquiryURL.'</td>
					<td class="'.$cla.'">'.$myrow['accountname'].'</td>
					<td class="'.$cla.'">'.number_format($AccountBalance).'</td>
					<td class="'.$cla.'"></td>
					<td class="'.$cla.'">'.number_format($LYAccountBalance).'</td>
					<td class="'.$cla.'"></td>
					</tr>';

			/*printf($PrintString,
				$ActEnquiryURL,
				$myrow['accountname'],
				number_format($AccountBalance),
				number_format($LYAccountBalance)
				);*/
			$j++;

		}

	}
	//end of loop


	while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0){
		if ($_REQUEST['Detail']=='Detailed'){
			$output .='<tr>
				<td colspan="2"></td>
				<td><hr></td>
				<td></td>
				<td><hr></td>
				<td></td>
				</tr>';
		}
		$output .='<tr><td colspan="2">'.$ParentGroups[$Level].'</td>
			<td >'.number_format($GroupTotal[$Level]).'</td>
			<td></td>
			<td >'.number_format($LYGroupTotal[$Level]).'</td>
			</tr>';
		$Level--;
	}
	if ($_REQUEST['Detail']=='Detailed'){
		$output .='<tr>
			<td colspan="2"></td>
			<td><hr></td>
			<td></td>
			<td><hr></td>
			<td></td>
			</tr>';
	}

	$output .='<tr><td colspan="2">'.$ParentGroups[$Level].'</td>
		<td class="number">'.number_format($GroupTotal[$Level]).'</td>
		<td></td>
		<td class="number">'.number_format($LYGroupTotal[$Level]).'</td>
		</tr>';

	if ($_REQUEST['Detail']=='Detailed'){
		$output .='<tr>
		<td colspan="2"></td>
		<td><hr></td>
		<td></td>
		<td><hr></td>
		<td></td>
		</tr>';
	} else {
		$output .='<tr>
		<td colspan="3"></td>
		<td><hr></td>
		<td></td>
		<td><hr></td>
		</tr>';
	}

	$output .='<tr>
		<td colspan="3">'.$Sections[$Section].'</td>
		<td>'.number_format($SectionBalance).'</td>
		<td></td>
		<td>'.number_format($SectionBalanceLY).'</td>
	</tr>';

	$Section = $myrow['sectioninaccounts'];

	if (isset($myrow['sectioninaccounts']) and $_REQUEST['Detail']=='Detailed'){
		$output .='<tr>
			<td colspan="6"><b>'.$Sections[$myrow['sectioninaccounts']].'</b></td>
			</tr>';
	}

	$output .='<tr>
		<td colspan="3"></td>
      		<td><hr></td>
		<td></td>
		<td><hr></td>
		</tr>';






	$output .='<tr>
		<td colspan="3" class="header2">Check Total</td>
		<td class="header2">'.number_format($CheckTotal).'</td>
		<td class="header2"></td>
		<td class="header2">'.number_format($LYCheckTotal).'</td>
		</tr>';

	$output .='<tr>
		<td colspan="3"></td>
      		<td><hr></td>
		<td></td>
		<td><hr></td>
		</tr>';

	$output .='</table>';
	

		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('balancesheet_'.time().'.pdf', 'I');
}



if($_REQUEST['op'] == 'profitloss'){
global $user, $base_url;
//$bankaccount =$_REQUEST['bankaccount'];
$periodfrom = $_REQUEST['periodfrom'];
$periodto = $_REQUEST['periodto'];
$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:985px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;
$Sections = array();
$sql = 'SELECT sectionid, sectionname FROM accountsection ORDER by sectionid';
$SectionResult = db_query($sql);
while( $secrow = db_fetch_array($SectionResult) ) {
	$Sections[$secrow['sectionid']] = $secrow['sectionname'];
}



//db_free_result($SectionResult);
$_REQUEST['Detail']='Detailed';
$cond='';
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report"  align="center">
Profit And Loss Report</td></tr>
</table><br/>';
	



	

	$NumberOfMonths = $periodto - $periodfrom + 1;

	$sql = "SELECT lastdate_in_period FROM periods WHERE periodno='" . $periodto . "'";
	$PrdResult = db_query($sql, $db);
	$myrow = db_fetch_array($PrdResult);
	$PeriodToDate = $myrow['lastdate_in_period'];


	$SQL = "SELECT accountgroups.sectioninaccounts,
					accountgroups.parentgroupname,
					accountgroups.groupname,
					chartdetails.accountcode,
					chartmaster.accountname,
					SUM(CASE WHEN chartdetails.period='" . $periodfrom . "' THEN chartdetails.bfwd ELSE 0 END) AS firstprdbfwd,
					SUM(CASE WHEN chartdetails.period='" . $periodfrom . "' THEN chartdetails.bfwdbudget ELSE 0 END) AS firstprdbudgetbfwd,
					SUM(CASE WHEN chartdetails.period='" . $periodto . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lastprdcfwd,
					SUM(CASE WHEN chartdetails.period='" . ($periodfrom - 12) . "' THEN chartdetails.bfwd ELSE 0 END) AS lyfirstprdbfwd,
					SUM(CASE WHEN chartdetails.period='" . ($periodto-12) . "' THEN chartdetails.bfwd + chartdetails.actual ELSE 0 END) AS lylastprdcfwd,
					SUM(CASE WHEN chartdetails.period='" . $periodto . "' THEN chartdetails.bfwdbudget + chartdetails.budget ELSE 0 END) AS lastprdbudgetcfwd
				FROM chartmaster INNER JOIN accountgroups
				ON chartmaster.group_ = accountgroups.groupname INNER JOIN chartdetails
				ON chartmaster.accountcode= chartdetails.accountcode
				WHERE accountgroups.pandl=1
				GROUP BY accountgroups.sectioninaccounts,
					accountgroups.parentgroupname,
					accountgroups.groupname,
					chartdetails.accountcode,
					chartmaster.accountname,
					accountgroups.sequenceintb
				ORDER BY accountgroups.sectioninaccounts,
					accountgroups.sequenceintb,
					accountgroups.groupname,
					accountgroups.sequenceintb,
					chartdetails.accountcode";
		
	$AccountsResult = db_query($SQL);

$daa=explode('-',$PeriodToDate);
if($daa[1]=='01')
{
 $mo="January";
}
if($daa[1]=='02')
{
 $mo="February";
}
if($daa[1]=='03')
{
 $mo="March";
}
if($daa[1]=='04')
{
 $mo="April";
}
if($daa[1]=='05')
{
 $mo="May";
}
if($daa[1]=='06')
{
 $mo="June";
}
if($daa[1]=='07')
{
 $mo="July";
}
if($daa[1]=='08')
{
 $mo="August";
}
if($daa[1]=='09')
{
 $mo="September";
}
if($daa[1]=='10')
{
 $mo="October";
}
if($daa[1]=='11')
{
 $mo="November";
}
if($daa[1]=='12')
{
 $mo="December";
}
	$output .='<b><p class="page_title_text">Statement of Profit and Loss for the ' . $NumberOfMonths . ' months to and including '. $mo.' '.$daa[0].'</p></b>';

	/*show a table of the accounts info returned by the SQL
	Account Code ,   Account Name , Month Actual, Month Budget, Period Actual, Period Budget */

	$output .='<table cellpadding="2" cellspacing="2" border="0">';

	if ($_REQUEST['Detail']=='Detailed'){
		$TableHeader = '<tr >
							<td class="header2">Account</td>
							<td class="header2">Account Name</td>
							<td colspan="2" class="header2">Current Year</td>
							<td colspan="2" class="header2">Period Budget</td>
							<td colspan="2" class="header2">Previous Year</td>
						</tr>';
	} else { /*summary */
		$TableHeader = '<tr class="header2">
							<td colspan="2" class="header2"></td>
							<td colspan="2" class="header2">Current Year</td>
							<td colspan="2" class="header2">Period Budget</td>
							<td colspan="2" class="header2">Previous Year</td>
						</tr>';
	}


	$j = 1;
	$k=0; //row colour counter
	$Section='';
	$SectionPrdActual= 0;
	$SectionPrdLY 	 = 0;
	$SectionPrdBudget= 0;

	$PeriodProfitLoss = 0;
	$PeriodProfitLoss = 0;
	$PeriodLYProfitLoss = 0;
	$PeriodBudgetProfitLoss = 0;


	$ActGrp ='';
	$ParentGroups = array();
	$Level = 0;
	$ParentGroups[$Level]='';
	$GrpPrdActual = array(0);
	$GrpPrdLY = array(0);
	$GrpPrdBudget = array(0);
	$TotalIncome=0;
	$TotalBudgetIncome=0;
	$TotalLYIncome=0;

	while ($myrow=db_fetch_array($AccountsResult)) {


		if ($myrow['groupname']!= $ActGrp){
			if ($myrow['parentgroupname']!=$ActGrp AND $ActGrp!=''){
					while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
					if ($_REQUEST['Detail']=='Detailed'){
						$output .='<tr>
								<td colspan="2"></td>
								<td colspan="6"><hr></td>
							</tr>';
						$ActGrpLabel = str_repeat('',$Level) . $ParentGroups[$Level] . 'total';
					} else {
						$ActGrpLabel = str_repeat('',$Level) . $ParentGroups[$Level];
					}
				if ($Section ==1){ /*Income */
						$output .='<tr>
								<td colspan="2">'.$ActGrpLabel.' </td>
								<td></td>
								<td align="right">'.number_format(-$GrpPrdActual[$Level],2).'</td>
								<td></td>
								<td align="right">'.number_format(-$GrpPrdBudget[$Level],2).'</td>
								<td></td>
								<td align="right">'.number_format(-$GrpPrdLY[$Level],2).'</td>
								</tr>';
					} else { /*Costs */
						$output .='<tr>
								<td colspan="2">'.$ActGrpLabel.'</td>
								<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
								<td></td>
								<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
								<td></td>
								<td align="right">'.number_format($GrpPrdLY[$Level],2).'</td>
								<td></td>
								</tr>';
					}
					$GrpPrdLY[$Level] = 0;
					$GrpPrdActual[$Level] = 0;
					$GrpPrdBudget[$Level] = 0;
					$ParentGroups[$Level] ='';
					$Level--;
				}//end while
				//still need to print out the old group totals
				if ($_REQUEST['Detail']=='Detailed'){
						$output .='<tr>
								<td colspan="2"></td>
								<td colspan="6"><hr></td>
							</tr>';
						$ActGrpLabel = str_repeat('',$Level) . $ParentGroups[$Level] . ' total';
					} else {
						$ActGrpLabel = str_repeat('',$Level) . $ParentGroups[$Level];
					}

				if ($Section ==1){ /*Income */
					$output .='<tr>
							<td colspan="2">'.$ActGrpLabel.' </td>
							<td></td>
							<td align="right">'.number_format(-$GrpPrdActual[$Level],2).'</td>
							<td></td>
							<td align="right">'.number_format(-$GrpPrdBudget[$Level],2).'</td>
							<td></td>
							<td align="right">'.number_format(-$GrpPrdLY[$Level],2).'</td>
							</tr>';
				} else { /*Costs */
					$output .='<tr>
							<td colspan="2">'.$ActGrpLabel.' </td>
							<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
							<td></td>
							<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
							<td></td>
							<td align="right">'.number_format($GrpPrdLY[$Level],2).'</td>
							<td></td>
							</tr>';
				}
				$GrpPrdLY[$Level] = 0;
				$GrpPrdActual[$Level] = 0;
				$GrpPrdBudget[$Level] = 0;
				$ParentGroups[$Level] ='';
			}
			$j++;
		}

		if ($myrow['sectioninaccounts']!= $Section){

			if ($SectionPrdLY+$SectionPrdActual+$SectionPrdBudget !=0){
				if ($Section==1) { /*Income*/

					$output .='<tr>
							<td colspan="3"></td>
      						<td><hr></td>
							<td><hr></td>
							<td><hr></td>
							<td><hr></td>
							<td><hr></td>
						</tr>';

					$output .='<tr>
							<td colspan="2">'.$Sections[$Section].'</td>
							<td></td>
							<td align="right">'.number_format(-$SectionPrdActual,2).'</td>
							<td></td>
							<td align="right">'.number_format(-$SectionPrdBudget,2).'</td>
							<td></td>
							<td align="right">'.number_format(-$SectionPrdLY,2).'</td>
							</tr>';
							$TotalIncome = -$SectionPrdActual;
							$TotalBudgetIncome = -$SectionPrdBudget;
							$TotalLYIncome = -$SectionPrdLY;
				} else {
					$output .='<tr>
							<td colspan="2"></td>
		      				<td><hr></td>
							<td><hr></td>
							<td><hr></td>
							<td><hr></td>
							<td><hr></td>
							</tr>';
							$output .='<tr>
							<td colspan="2">'.$Sections[$Section].'</td>
							<td></td>
							<td align="right">'.number_format($SectionPrdActual,2).'</td>
							<td></td>
							<td align="right">'.number_format($SectionPrdBudget,2).'</td>
							<td></td>
							<td align="right">'.number_format($SectionPrdLY,2).'</td>
							</tr>';
				}
				if ($Section==2){ /*Cost of Sales - need sub total for Gross Profit*/
					$output .='<tr>
							<td colspan="2"></td>
							<td colspan="6"><hr></td>
						</tr>';
					$output .='<tr>
							<td colspan="2">Gross Profit</td>
							<td></td>
							<td align="right">'.number_format($TotalIncome - $SectionPrdActual,2).'</td>
							<td></td>
							<td align="right">'.number_format($TotalBudgetIncome - $SectionPrdBudget,2).'</td>
							<td></td>
							<td align="right">'.number_format($TotalLYIncome - $SectionPrdLY,2).'</td>
							</tr>';
	
					if ($TotalIncome !=0){
						$PrdGPPercent = 100*($TotalIncome - $SectionPrdActual)/$TotalIncome;
					} else {
						$PrdGPPercent =0;
					}
					if ($TotalBudgetIncome !=0){
						$BudgetGPPercent = 100*($TotalBudgetIncome - $SectionPrdBudget)/$TotalBudgetIncome;
					} else {
						$BudgetGPPercent =0;
					}
					if ($TotalLYIncome !=0){
						$LYGPPercent = 100*($TotalLYIncome - $SectionPrdLY)/$TotalLYIncome;
					} else {
						$LYGPPercent = 0;
					}
					$output .='<tr>
							<td colspan="2"></td>
							<td colspan="6"><hr></td>
						</tr>';
					$output .='<tr>
							<td colspan="2">Gross Profit Percent</td>
							<td></td>
							<td align="right">'.number_format($PrdGPPercent,1) . '%</td>
							<td></td>
							<td align="right">'.number_format($BudgetGPPercent,1) . '%</td>
							<td></td>
							<td align="right">'.number_format($LYGPPercent,1). '%</td>
							</tr><tr><td colspan="8"> </td></tr>';
					$j++;
				}
			}
			$SectionPrdLY =0;
			$SectionPrdActual =0;
			$SectionPrdBudget =0;

			$Section = $myrow['sectioninaccounts'];

			if ($_REQUEST['Detail']=='Detailed'){
				$output .='<tr>
					<td colspan="8"><b>'.$Sections[$myrow['sectioninaccounts']].'</b></td>
					</tr>';
			}
			$j++;

		}



		if ($myrow['groupname']!= $ActGrp){

			if ($myrow['parentgroupname']==$ActGrp AND $ActGrp !=''){ //adding another level of nesting
				$Level++;
			}

			$ParentGroups[$Level] = $myrow['groupname'];
			$ActGrp = $myrow['groupname'];
			if ($_REQUEST['Detail']=='Detailed'){
				$output .='<tr>
					<th colspan="8"><b>'.$myrow['groupname'].'</b></th>
					</tr>';
					$output .=$TableHeader;
			}
		}

		$AccountPeriodActual = $myrow['lastprdcfwd'] - $myrow['firstprdbfwd'];
		$AccountPeriodLY = $myrow['lylastprdcfwd'] - $myrow['lyfirstprdbfwd'];
		$AccountPeriodBudget = $myrow['lastprdbudgetcfwd'] - $myrow['firstprdbudgetbfwd'];
		$PeriodProfitLoss += $AccountPeriodActual;
		$PeriodBudgetProfitLoss += $AccountPeriodBudget;
		$PeriodLYProfitLoss += $AccountPeriodLY;

		for ($i=0;$i<=$Level;$i++){
			if (!isset($GrpPrdLY[$i])) {$GrpPrdLY[$i]=0;}
			$GrpPrdLY[$i] +=$AccountPeriodLY;
			if (!isset($GrpPrdActual[$i])) {$GrpPrdActual[$i]=0;}
			$GrpPrdActual[$i] +=$AccountPeriodActual;
			if (!isset($GrpPrdBudget[$i])) {$GrpPrdBudget[$i]=0;}
			$GrpPrdBudget[$i] +=$AccountPeriodBudget;
		}
		$SectionPrdLY +=$AccountPeriodLY;
		$SectionPrdActual +=$AccountPeriodActual;
		$SectionPrdBudget +=$AccountPeriodBudget;

		if ($_REQUEST['Detail']=='Detailed'){

			/*if ($k==1){
				echo '<tr class="even">';
				$k=0;
			} else {
				echo '<tr class="odd">';
				$k++;
			}*/
           if($j%2==0)
		   {
		     $cla="header4_1";
		   }
		   else
		   {
		     $cla="header4_2";
		   }
		  // $cla="header4_2";
			$ActEnquiryURL = $myrow['accountcode'];

			if ($Section ==1){
				 $output .='<tr><td class="'.$cla.'">'.$ActEnquiryURL.'</td>
						<td class="'.$cla.'">'.$myrow['accountname'].'</td>
						<td class="'.$cla.'"></td>
						<td class="'.$cla.'" align="right">'.number_format(-$AccountPeriodActual,2).'</td>
						<td class="'.$cla.'"></td>
						<td class="'.$cla.'" align="right">'.number_format(-$AccountPeriodBudget,2).'</td>
						<td class="'.$cla.'"></td>
						<td class="'.$cla.'" align="right">'.number_format(-$AccountPeriodLY,2).'</td>
						</tr>';
			} else {
				$output .='<tr><td class="'.$cla.'">'.$ActEnquiryURL.'</td>
						<td class="'.$cla.'">'.$myrow['accountname'].'</td>
						<td class="'.$cla.'" align="right">'.number_format($AccountPeriodActual,2).'</td>
						<td class="'.$cla.'"></td>
						<td class="'.$cla.'" align="right">'.number_format($AccountPeriodBudget,2).'</td>
						<td class="'.$cla.'"></td>
						<td class="'.$cla.'" align="right">'.number_format($AccountPeriodLY,2).'</td>
						<td class="'.$cla.'"></td>
						</tr>';
			}

			$j++;
		}
	}
	//end of loop


	if ($myrow['groupname']!= $ActGrp){
		if ($myrow['parentgroupname']!=$ActGrp AND $ActGrp!=''){
			while ($myrow['groupname']!=$ParentGroups[$Level] AND $Level>0) {
				if ($_REQUEST['Detail']=='Detailed'){
					$output .='<tr>
						<td colspan="2"></td>
						<td colspan="6"><hr></td>
					</tr>';
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . 'total';
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}
				if ($Section ==1){ /*Income */
					$output .='<tr>
						<td colspan="2">'.$ActGrpLabel.' </td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdActual[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdBudget[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdLY[$Level],2).'</td>
						</tr>';
				} else { /*Costs */
					$output .='<tr>
						<td colspan="2">'.$ActGrpLabel.' </td>
						<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format($GrpPrdLY[$Level],2).'</td>
						<td></td>
						</tr>';
				}
				$GrpPrdLY[$Level] = 0;
				$GrpPrdActual[$Level] = 0;
				$GrpPrdBudget[$Level] = 0;
				$ParentGroups[$Level] ='';
				$Level--;
			}//end while
			//still need to print out the old group totals
			if ($_REQUEST['Detail']=='Detailed'){
					$output .='<tr>
							<td colspan="2"></td>
							<td colspan="6"><hr></td>
						</tr>';

					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level] . 'total';
				} else {
					$ActGrpLabel = str_repeat('___',$Level) . $ParentGroups[$Level];
				}

			if ($Section ==1){ /*Income */
				$output .='<tr>
						<td colspan="2">'.$ActGrpLabel.' </td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdActual[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdBudget[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format(-$GrpPrdLY[$Level],2).'</td>
						</tr>';
			} else { /*Costs */
				$output .='<tr>
						<td colspan="2">'.$ActGrpLabel.' </td>
						<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format($GrpPrdActual[$Level],2).'</td>
						<td></td>
						<td align="right">'.number_format($GrpPrdBudget[$Level],2).'</td>
						<td></td>
						</tr>';
			}
			$GrpPrdLY[$Level] = 0;
			$GrpPrdActual[$Level] = 0;
			$GrpPrdBudget[$Level] = 0;
			$ParentGroups[$Level] ='';
		}
		$j++;
	}

	if ($myrow['sectioninaccounts']!= $Section){

		if ($Section==1) { /*Income*/

			$output .='<tr>
					<td colspan="3"></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
				</tr>';

			$output .='<tr>
					<td colspan="2">'.$Sections[$Section].'</td>
					<td></td>
					<td align="right">'.number_format(-$SectionPrdActual,2).'</td>
					<td></td>
					<td align="right">'.number_format(-$SectionPrdBudget,2).'</td>
					<td></td>
					<td align="right">'.number_format(-$SectionPrdLY,2).'</td>
					</tr>';
					$TotalIncome = -$SectionPrdActual;
					$TotalBudgetIncome = -$SectionPrdBudget;
					$TotalLYIncome = -$SectionPrdLY;
		} else {
			$output .='<tr>
					<td colspan="2"></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
					<td><hr></td>
				</tr>';
			$output .='<tr>
					<td colspan="2">'.$Sections[$Section].'</td>
					<td></td>
					<td align="right">'.number_format($SectionPrdActual,2).'</td>
					<td></td>
					<td align="right">'.number_format($SectionPrdBudget,2).'</td>
					<td></td>
					<td align="right">'.number_format($SectionPrdLY,2).'</td>
					</tr>';
		}
		if ($Section==2){ /*Cost of Sales - need sub total for Gross Profit*/
			$output .='<tr>
					<td colspan="2"></td>
					<td colspan="6"><hr></td>
				</tr>';
			$output .='<tr>
					<td colspan="2"><font size=4>Gross Profit</font></td>
					<td></td>
					<td align="right">'.number_format($TotalIncome - $SectionPrdActual,2).'</td>
					<td></td>
					<td align="right">'.number_format($TotalBudgetIncome - $SectionPrdBudget,2).'</td>
					<td></td>
					<td align="right">'.number_format($TotalLYIncome - $SectionPrdLY,2).'</td>
					</tr>';

			if ($TotalIncome !=0){
				$PrdGPPercent = 100*($TotalIncome - $SectionPrdActual)/$TotalIncome;
			} else {
				$PrdGPPercent =0;
			}
			if ($TotalBudgetIncome !=0){
				$BudgetGPPercent = 100*($TotalBudgetIncome - $SectionPrdBudget)/$TotalBudgetIncome;
			} else {
				$BudgetGPPercent =0;
			}
			if ($TotalLYIncome !=0){
				$LYGPPercent = 100*($TotalLYIncome - $SectionPrdLY)/$TotalLYIncome;
			} else {
				$LYGPPercent = 0;
			}
			$output .='<tr>
					<td colspan="2"></td>
					<td colspan="6"><hr></td>
				</tr>';
			$output .='<tr>
					<td colspan="2">Gross Profit Percent</td>
					<td></td>
					<td align="right">'.number_format($PrdGPPercent,1) . '%</td>
					<td></td>
					<td align="right">'.number_format($BudgetGPPercent,1) . '%</td>
					<td></td>
					<td align="right">'.number_format($LYGPPercent,1). '%</td>
					</tr><tr><td colspan="8"> </td></tr>';
			$j++;
		}

		$SectionPrdLY =0;
		$SectionPrdActual =0;
		$SectionPrdBudget =0;

		$Section = $myrow['sectioninaccounts'];

		if ($_REQUEST['Detail']=='Detailed' and isset($Sections[$myrow['sectioninaccounts']])){
			$output .='<tr>
				<td colspan="6"><b>'.$Sections[$myrow['sectioninaccounts']].'</b></td>
				</tr>';
		}
		$j++;

	}

	$output .='<tr>
			<td colspan="2"></td>
			<td colspan="6"><hr></td>
		</tr>';

	$output .='<tr bgcolor="#ffffff">
			<td colspan="2"><b>Profit - Loss</b></td>
			<td></td>
			<td align="right">'.number_format(-$PeriodProfitLoss,2).'</td>
			<td></td>
			<td align="right">'.number_format(-$PeriodBudgetProfitLoss,2).'</td>
			<td></td>
			<td align="right">'.number_format(-$PeriodLYProfitLoss,2).'</td>
			</tr>';

	if ($TotalIncome !=0){
		$PrdNPPercent = 100*(-$PeriodProfitLoss)/$TotalIncome;
	} else {
		$PrdNPPercent =0;
	}
	if ($TotalBudgetIncome !=0){
		$BudgetNPPercent=100*(-$PeriodBudgetProfitLoss)/$TotalBudgetIncome;
	} else {
		$BudgetNPPercent=0;
	}
	if ($TotalLYIncome !=0){
		$LYNPPercent = 100*(-$PeriodLYProfitLoss)/$TotalLYIncome;
	} else {
		$LYNPPercent = 0;
	}
	$output .='<tr>
			<td colspan="2"></td>
			<td colspan="6"><hr></td>
		</tr>';

	$output .='<tr>
			<td colspan="2">Net Profit Percent</td>
			<td></td>
			<td align="right">'.number_format($PrdNPPercent,1) . '%</td>
			<td></td>
			<td align="right">'.number_format($BudgetNPPercent,1) . '%</td>
			<td></td>
			<td align="right">'.number_format($LYNPPercent,1). '%</td>
			</tr><tr><td colspan="8"> </td>
			</tr>';

	$output .='<tr>
			<td colspan="2"></td>
			<td colspan="6"><hr /></td>
		</tr>';

	$output .='</table>';
	

	

		 //$output .= '</table>';
	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('profitloss_'.time().'.pdf', 'I');
}



if($_REQUEST['op'] == 'chequebook'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$cond='';

$fr=explode('-',$from);
//$from=$fr[2].'-'.$fr[1].'-'.$fr[0];
$tr=explode('-',$to);
//$to=$tr[2].'-'.$tr[1].'-'.$tr[0];
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">
Cheque Book</td></tr>
</table><br>';
	$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>From Date :</b> '. $fr[2].'-'.$fr[1].'-'.$fr[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>To Date :</b> '.$tr[2].'-'.$tr[1].'-'.$tr[0].'</td></tr>
</table><br>';
   $output .='<table cellspacing="2" cellpadding="3" class="tbl_border"><tr><td class="header2">S.No.</td><td class="header2">Date</td><td class="header2">Cheque No</td><td class="header2">Cheque Type</td><td class="header2">Amount</td><td class="header2">Purpose</td></tr>';

 $s="select banktrans.transdate,banktrans.amount,banktrans.ref,banktrans.type from banktrans where transdate>='".$from."' and transdate<='".$to."' and banktranstype='Cheque'  ";
 $q=db_query($s);
 $n=1;
 while($r=db_fetch_array($q))
 {  if($n%2==0)
 {
   $cla="header4_1";
 }
 else
 {
   $cla="header4_2";
 }
    $ss="select * from gltrans where type='".$r['type']."' and trandate='".$r['transdate']."' and (chequeno!=0)";
	$ssq=db_query($ss);
	$ssr=db_fetch_array($ssq);
 if($r['amount']>0)
   {
     $typ="Paid";
   }
   else
   {
     $typ="Received";
   }
   
   $fro=explode('-',$r['transdate']);
   $output .='<tr ><td class="'.$cla.'" align="center">'.$n.'</td><td align="center" class="'.$cla.'">'. $fro[2].'-'.$fro[1].'-'.$fro[0].'</td><td class="'.$cla.'" align="right">'.$ssr['chequeno'].'</td><td class="'.$cla.'">'.$typ.'</td><td class="'.$cla.'" align="right">'.$r['amount'].'</td><td class="'.$cla.'">'.$r['ref'].'</td></tr>';
   $n++;
 }

$output .='</table>';



	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('chequebook_'.time().'.pdf', 'I');
}


if($_REQUEST['op'] == 'journalbook'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];

$output='';
// define some HTML content with style
$output .= <<<EOF
<style>
td.header_first{
color:111111;
font-family:Verdana;
font-size: 12pt;
text-align:center;
background-color:#ffffff;
}
td.header_report{
color:111111;
font-family:Verdana;
font-size: 16pt;
text-align:center;
font-weight:bold;
background-color:#ffffff;
}
table{
width:980px;
}
table.tbl_border{border:1px solid #ffffff;
background-color:#a7c942;
}
td.header1 {
        color:#3b3c3c;
		background-color:#ffffff;
		font-family:Verdana;
		font-size: 11pt;
		font-weight: normal;
}

td.header2 {
border-bottom-color:#FFFFFF;
border-left-color:#ffffff;
color: #ffffff;
background-color:#a7c942;
font-family:Verdana;
font-size: 10pt;
font-weight: bold;
}
td.header3 {
color: #222222;
background-color:#dddddd;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
}
td.header4 {
color: #222222;
font-family:Verdana;
font-size: 11pt;
font-weight: bold;
background-color:#eeeeee;
}
td.header4_1 {
color:#222222;
background-color:#ffffff;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;
}
td.header4_2  {
color:#222222;
background-color:#eaf2d3;
font-family:Verdana;
font-size: 11pt;
font-weight: normal;		
}
td.msg{
color:#FF0000; 
text-align:left;
}
</style>
EOF;

$fr=explode('-',$from);
//$from=$fr[2].'-'.$fr[1].'-'.$fr[0];
$tr=explode('-',$to);
//$to=$tr[2].'-'.$tr[1].'-'.$tr[0];
// Header Title

$pe="select lastdate_in_period from periods where periodno='".$from."'";
$peq=db_query($pe);
$per=db_fetch_array($peq);
$daa=explode('-',$per['lastdate_in_period']);

if($daa[1]=='01')
{
 $mo="January";
}
if($daa[1]=='02')
{
 $mo="February";
}
if($daa[1]=='03')
{
 $mo="March";
}
if($daa[1]=='04')
{
 $mo="April";
}
if($daa[1]=='05')
{
 $mo="May";
}
if($daa[1]=='06')
{
 $mo="June";
}
if($daa[1]=='07')
{
 $mo="July";
}
if($daa[1]=='08')
{
 $mo="August";
}
if($daa[1]=='09')
{
 $mo="September";
}
if($daa[1]=='10')
{
 $mo="October";
}
if($daa[1]=='11')
{
 $mo="November";
}
if($daa[1]=='12')
{
 $mo="December";
}
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" align="center">
Journal Book</td></tr>
</table>';
	$output .='<table cellpadding="3" cellspacing="2" border="0">
<tr><td class="header_first" align="left">
</td></tr>
<tr><td class="header1"><b>For Period:</b> '. $mo.' '.$daa[0].'</td></tr>
</table><br>';
  
 
   /*if (isset($SelectedAccount) && $SelectedAccount==''){
		prnMsg(_('A Account Must Be Selected'),'info');
		include('includes/footer.inc');
		exit;
	}*/
	/*if (!isset($SelectedPeriod)){
		prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.inc');
		exit;
	}*/
	/*Is the account a balance sheet or a profit and loss account */
	
	$result = db_query("SELECT pandl
				FROM accountgroups
				INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_");
	$PandLRow = db_fetch_array($result);
	if ($PandLRow['pandl']==1){
		$PandLAccount = True;
	}else{
		$PandLAccount = False; /*its a balance sheet account */
	}

	$FirstPeriodSelected = $from;
	$LastPeriodSelected = $to;

	//if ($_POST['tag']==0) {
 		$sql= "SELECT type,
			typename,
			gltrans.typeno,
			trandate,
			narrative,
			amount,
			periodno,
			tag,voucher_no
		FROM gltrans, systypes
		WHERE  systypes.typeid=gltrans.type
		AND systypes.typeid=0
		AND posted=1
		AND periodno>='" . $FirstPeriodSelected . "'
		AND periodno<='" . $LastPeriodSelected . "'
		ORDER BY periodno, gltrans.trandate, counterindex";

	 /*}else {
 		$sql= "SELECT type,
			typename,
			gltrans.typeno,
			trandate,
			narrative,
			amount,
			periodno,
			tag,voucher_no
		FROM gltrans, systypes
		WHERE systypes.typeid=gltrans.type
		AND systypes.typeid=0
		AND posted=1
		AND periodno>= '" . $FirstPeriodSelected . "'
		AND periodno<= '" . $LastPeriodSelected . "'
		AND tag='".$_POST['tag']."'
		ORDER BY periodno, gltrans.trandate, counterindex";
	}*/

	$namesql = "SELECT accountname FROM chartmaster ";
	$nameresult = db_query($namesql);
	$namerow=db_fetch_array($nameresult);
	$SelectedAccountName=$namerow['accountname'];
	//$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
	$TransResult = db_query($sql);

	 $output .='<table class="tbl_border" cellspacing="2" cellpadding="3">';

	
	$TableHeader = '<tr>
			<td width="10%" class="header2">Voucher No.</td>
			<td width="10%" class="header2">Date</td>
			<td width="10%" class="header2">Debit</td>
			<td width="10%" class="header2">Credit</td>
			<td width="43%" class="header2">Narrative</td>
			<td class="header2">Balance</td>
			</tr>';

	$output .=$TableHeader;

	if ($PandLAccount==True) {
		$RunningTotal = 0;
	} else {
			// added to fix bug with Brought Forward Balance always being zero
					$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE  chartdetails.period='" . $FirstPeriodSelected . "'";

				//$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
				$ChartDetailsResult = db_query($sql);
				$ChartDetailRow = db_fetch_array($ChartDetailsResult);
				// --------------------

		$RunningTotal =$ChartDetailRow['bfwd'];
	/*	if ($RunningTotal < 0 ){ //its a credit balance b/fwd
			$output .='<tr class="even">
				<td colspan="3"><td>
				</td></td>
				<td ><b></b></td>
				<td></td>
				</tr>';
		} else { //its a debit balance b/fwd
			$output .='<tr class="even">
				<td colspan="3"></td>
				<td ><b></b></td>
				<td colspan="2"></td>
				</tr>';
		}
	*/}
	
	$PeriodTotal = 0;
	$PeriodNo = -9999;
	$ShowIntegrityReport = False;
	$j = 1;
	$k=0; //row colour counter
	$IntegrityReport='';
	while ($myrow=db_fetch_array($TransResult)) {
		if ($myrow['periodno']!=$PeriodNo){
			if ($PeriodNo!=-9999){ //ie its not the first time around
				/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/

				$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE  chartdetails.period='" . $PeriodNo . "'";

			//	$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
				$ChartDetailsResult = db_query($sql);
				$ChartDetailRow = db_fetch_array($ChartDetailsResult);
               
			   $pe="select lastdate_in_period from periods where periodno='".$PeriodNo."'";
			   $peq=db_query($pe);
			   $per=db_fetch_array($peq);
			   $lasd=explode('-',$per['lastdate_in_period']);
			   $lastdate=$lasd[2].'-'.$lasd[1].'-'.$lasd[0];
				$output .='<tr >
					<td colspan="3"><b>Total for period' .$lastdate. '</b></td>';
				if ($PeriodTotal < 0 ){ //its a credit balance b/fwd
					if ($PandLAccount==True) {
						$RunningTotal = 0;
					}
					$output .='<td></td>
						<td ><b>' . number_format(-$PeriodTotal,2) . '</b></td>
						<td></td>
						</tr>';
				} else { //its a debit balance b/fwd
					if ($PandLAccount==True) {
						$RunningTotal = 0;
					}
					$output .='<td ><b>' . number_format($PeriodTotal,2) . '</b></td>
						<td colspan="2"></td>
						</tr>';
				}
				/*$IntegrityReport .= '<br />Period: ' . $PeriodNo  . 'Account movement per transaction: '  . number_format($PeriodTotal,2) . ' Movement per ChartDetails record : ' . number_format($ChartDetailRow['actual'],2) . ' Period difference : ' . number_format($PeriodTotal -$ChartDetailRow['actual'],3);*/

				if (ABS($PeriodTotal -$ChartDetailRow['actual'])>0.01){
					$ShowIntegrityReport = True;
				}
			}
			$PeriodNo = $myrow['periodno'];
			$PeriodTotal = 0;
		}

		if ($k%2==0){
			$cla="header4_1";
			
		} else {
			$cla="header4_2";
			
		}

		$RunningTotal += $myrow['amount'];
		$PeriodTotal += $myrow['amount'];

		if($myrow['amount']>=0){
			$DebitAmount = number_format($myrow['amount'],2);
			$CreditAmount = '';
		} else {
			$CreditAmount = number_format(-$myrow['amount'],2);
			$DebitAmount = '';
		}

		$FormatedTranDat = $myrow['trandate'];
		$dat=explode('-',$FormatedTranDat);
		$FormatedTranDate=$dat[2]."-".$dat[1]."-".$dat[0];
		//$URL_to_TransDetail = $rootpath . '/GLTransInquiry.php?' . SID . '&TypeID=' . $myrow['type'] . '&TransNo=' . $myrow['typeno'];
      $URL_to_TransDetail='';
		$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$myrow['tag'] . "'";
		$tagresult=db_query($tagsql);
		$tagrow = db_fetch_array($tagresult);
		if ($tagrow['tagdescription']=='') {
			$tagrow['tagdescription']='None';
		}
		$output .='<tr><td width="10%" class="'.$cla.'">'.$myrow['voucher_no'].'</td>
			
			<td width="10%" align="center" class="'.$cla.'">'.$FormatedTranDate.'</td>
			<td width="10%" align="right" class="number" class="'.$cla.'">'.$DebitAmount.'</td>
			<td width="10%" align="right" class="number" class="'.$cla.'">'.$CreditAmount.'</td>
			<td width="43%" class="'.$cla.'">'.ucwords($myrow['narrative']).'</td>
			<td align="right" class="number" class="'.$cla.'"><b>'.number_format($RunningTotal,2).'</b></td>
			
			</tr>';
$k++;
	}

	
	$output .='</table>';




	// $output;
	ob_end_clean();
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('journalbook_'.time().'.pdf', 'I');
}