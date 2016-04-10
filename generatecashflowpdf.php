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

//$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, '','');
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




/*if($_REQUEST['op'] == 'cashflow'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
//$voucher=$_REQUEST['voucher'];
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
width:665px;
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
$deb=0;
$cre=0;
// Header Title
$output .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Cash Flow Report</td></tr>
</table>';

global $opening;

$acgc=array();
$debtotalc=0;
$cretotalc=0;
$totaldebitc=array();
$totalcreditc=array();
$accc=array();
$accountc=array();

$da=0;
$gda="select lastdate_in_period from periods where periodno='".$from."' OR periodno='".$to."' order by periodno";
$gdaq=db_query($gda);
while($gdar=db_fetch_array($gdaq))
{
if($da==0)
{
  $dafr=$gdar['lastdate_in_period'];
  }
  else
  {
    $dato=$gdar['lastdate_in_period'];
  }
  $da++;
}

$s="select * from accountgroups where sectioninaccounts='1'";
$q=db_query($s);
while($r=db_fetch_array($q))
{
 $acgc[]=$r['groupname'];
 
 $ss="select * from accountgroups where parentgroupname='".$r['groupname']."' ";
 $ssq=db_query($ss);
  while($ssr=db_fetch_array($ssq))
  {
    $acgc[]=$ssr['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
foreach($acgc as $accoc)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoc."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$from."' ";
 $qq=db_query($sq,$db);

 while($qr=db_fetch_array($qq))
 { 
  $accocde=$qr['accountcode'];

  
  if(in_array($accocde,$accc))
    {
      if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$accountc[$accocde]['cr']+$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de'] =$accountc[$accocde]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accc[]=$qr['accountcode'];
	$accountc[$accocde]['name']=$qr['accountname'];
	$accountc[$accocde]['group']=$r['groupname'];
	  if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$f=1;
foreach($accc as $acid)
{ if($f%2==0)
{
$cl="header4_1";
} 
else
{
$cl="header4_2";
}

   $totaldebitc[]=$accountc[$acid]['de'];
   $totalcreditc[]=$accountc[$acid]['cr'];
    if($accountc[$acid]['de']=='')
     {
      $accountc[$acid]['de']=0;
     }
	 if($accountc[$acid]['cr']=='')
     {
      $accountc[$acid]['cr']=0;
     }
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
 
		  $f++;
}
 


$acgce=array();
$debtotalce=0;
$cretotalce=0;
$totaldebitce=array();
$totalcreditce=array();
$accce=array();
$accountce=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=db_query($se);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";

while($re=db_fetch_array($qe))
{
 $acgce[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=db_query($sse);
  while($ssre=db_fetch_array($ssqe))
  {
    $acgce[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
foreach($acgce as $accoce)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoce."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$from."' ";
 $qqe=db_query($sqe);

 while($qre=db_fetch_array($qqe))
 { 
  $accocde=$qre['accountcode'];

  
  if(in_array($accocde,$accce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$accountce[$accocde]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de'] =$accountce[$accocde]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accce[]=$qre['accountcode'];
	$accountce[$accocde]['name']=$qre['accountname'];
	$accountce[$accocde]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$z=1;
foreach($accce as $acide)
{ if($z%2==0)
{
$cl="header4_1";
}
else
{
$cl="header4_2";
}

   $totaldebitce[]=$accountce[$acide]['de'];
   $totalcreditce[]=$accountce[$acide]['cr'];
    if($accountce[$acide]['de']=='')
     {
      $accountce[$acide]['de']=0;
     }
	 if($accountce[$acide]['cr']=='')
     {
      $accountce[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";

		$z++;
}




$carry=round((array_sum($totaldebitc))-(array_sum($totalcreditce)),2);

$opening=$carry;


for($g=$from;$g<=$to;$g++)
{
  $mon[]=$g;
 }
 
//$mon=array('16');
$mm=1;
foreach($mon as $mont)
{

$pe="select lastdate_in_period from periods where periodno='".$mont."'";
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
 $acg=array();
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();



$data ='<table>';
if($mm==1){
  $data .='<tr><td colspan="2" align="center"><b>Cash In</b></td><td align="center"><b>'.$mo.'</b></td></tr>';
}
else{
$data .='<tr><td align="center"><b>'.$mo.'</b></td></tr>';
}


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
foreach($acg as $acco)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$acco."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qq=db_query($sq);

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
$f=1;
foreach($acc as $acid)
{ if($f%2==0)
{
$cl="header4_1";
} 
else
{
$cl="header4_2";
}

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
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
  $data.= '<tr class="'.$cl.'">';
               if($mm==1)
			   {
  				$data.= '<td align="right">'.$acid.'</td><td align="left">'.ucwords($account[$acid]['name']).'</td>';
				}
				$data.= '<td align="right">'.round($account[$acid]['de'],2).'</td> </tr>';
		  $f++;
}
 
         $data.= '<tr class="odd">';
            if($mm==1)
			   {
				$data .='<td  colspan="2"><b>Total Cash In</b></td>';
				}
				$data .='<td align="right">'.round(array_sum($totaldebit),2).'</td></tr>';
				
				 $data.= '<tr class="even">';
            if($mm==1)
			   {
				$data .='<td class="header2" colspan="2"><b>Total Cash Available</b></td>';
				}
				$data .='<td class="header2" align="right">'.round((array_sum($totaldebit)+$opening),2).'</td></tr>';
				
$data .='</table>';
 


$acge=array();
$debtotale=0;
$cretotale=0;
$totaldebite=array();
$totalcredite=array();
$acce=array();
$accounte=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=db_query($se);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
$data .='<table>';
if($mm==1)
		{
$data .='<tr class="evenrow"><td align="center" colspan="2"><b>Cash Out</b></td><td align="center">'.$mo.'</td></tr>';
}
else
{
 $data .='<tr><td align="center">'.$mo.'</td></tr>';
}
while($re=db_fetch_array($qe))
{
 $acge[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=db_query($sse);
  while($ssre=db_fetch_array($ssqe))
  {
    $acge[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
foreach($acge as $accoe)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoe."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qqe=db_query($sqe);

 while($qre=db_fetch_array($qqe))
 { 
  $accode=$qre['accountcode'];

  
  if(in_array($accode,$acce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$accounte[$accode]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de'] =$accounte[$accode]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $acce[]=$qre['accountcode'];
	$accounte[$accode]['name']=$qre['accountname'];
	$accounte[$accode]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$z=1;
foreach($acce as $acide)
{ if($z%2==0)
{
$cl="header4_1";
}
else
{
$cl="header4_2";
}

   $totaldebite[]=$accounte[$acide]['de'];
   $totalcredite[]=abs($accounte[$acide]['cr']);
    if($accounte[$acide]['de']=='')
     {
      $accounte[$acide]['de']=0;
     }
	 if($accounte[$acide]['cr']=='')
     {
      $accounte[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";
$data .='<tr class="'.$cl.'">';
               if($mm==1)
			   {
				$data .='<td align="right">'.$acide.'</td>
				<td>'.ucwords($accounte[$acide]['name']).'</td>';
				}
				$data.='<td align="right">'.round(abs($accounte[$acide]['cr']),2).'</td></tr>';
		$z++;
}



$data.= '<tr class="odd">';
 if($mm==1)
			   {
				$data.='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Total Cash Out</b></td>';
				}
				$data.='<td style="border-top:1px solid; border-top-color:#ccc;" align="right">'.round(array_sum($totalcredite),2).'</td></tr>';
				
				$cico=round((array_sum($totaldebit))-(array_sum($totalcredite)),2);
				$data.='<tr class="even">';
if($mm==1)
{
$data .='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Monthly Cash Flow (Cash In - Cash Out)</b></td>';
}
$data .='<td  align="right"><b>'.round((array_sum($totaldebit))-(array_sum($totalcredite)),2).'</b></td></tr>';

$data.= '<tr class="odd">';
if($mm==1)
{
$data .='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Begining Cash Balance</b></td>';
}
$data .='<td  align="right"><b>'.round($opening,2).'</b></td></tr>';

$data.= '<tr class="even">';
if($mm==1)
{
$data .='<td class="header2" colspan="2"><b>Ending Cash Balance</b></td>';
}
$data .='<td  align="right" class="header2"><b>'.round(($cico+$opening),2).'</b></td></tr>';
				
$data.='</table>';
$dat .='<td>'.$data.'</td>';
 
 $mm++;
 
 $opening=round(($cico+$opening),2);
 }
 $output .='<table border="0"><tr class="oddrow"><td><h2>Cash Flow From '.$dafr.' To '.$dato.'</h2></td></tr><tr><td align="right"></td></tr></table><table><tr>'.$dat.'</tr></table>';
 
 //echo $record;
	
	// $output;
	
	 // print a block of text using Write()
	$pdf->writeHTML($output, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('cashflow_'.time().'.pdf', 'I');
}
*/

if($_REQUEST['op'] == 'cashflow'){
global $user, $base_url;
$from =$_REQUEST['sdate'];
$to = $_REQUEST['edate'];
//$voucher=$_REQUEST['voucher'];
$te ='';
// define some HTML content with style
$branch=$_REQUEST['branch'];
db_set_active('scst_branch_'.$branch);

$te .= <<<EOF
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
$deb=0;
$cre=0;
// Header Title


global $opening;

$acgc=array();
$debtotalc=0;
$cretotalc=0;
$totaldebitc=array();
$totalcreditc=array();
$accc=array();
$accountc=array();

$da=0;
$gda="select lastdate_in_period from periods where periodno='".$from."' OR periodno='".$to."' order by periodno";
$gdaq=db_query($gda);
while($gdar=db_fetch_array($gdaq))
{
if($da==0)
{
  $dafr=$gdar['lastdate_in_period'];
  $daa1=explode('-',$dafr);

if($daa1[1]=='01')
{
 $mo1="January";
}
if($daa1[1]=='02')
{
 $mo1="February";
}
if($daa1[1]=='03')
{
 $mo1="March";
}
if($daa1[1]=='04')
{
 $mo1="April";
}
if($daa1[1]=='05')
{
 $mo1="May";
}
if($daa1[1]=='06')
{
 $mo1="June";
}
if($daa1[1]=='07')
{
 $mo1="July";
}
if($daa1[1]=='08')
{
 $mo1="August";
}
if($daa1[1]=='09')
{
 $mo1="September";
}
if($daa1[1]=='10')
{
 $mo1="October";
}
if($daa1[1]=='11')
{
 $mo1="November";
}
if($daa1[1]=='12')
{
 $mo1="December";
}
  }
  else
  {
    $dato=$gdar['lastdate_in_period'];
	 $daa2=explode('-',$dato);

if($daa2[1]=='01')
{
 $mo2="January";
}
if($daa2[1]=='02')
{
 $mo2="February";
}
if($daa2[1]=='03')
{
 $mo2="March";
}
if($daa2[1]=='04')
{
 $mo2="April";
}
if($daa2[1]=='05')
{
 $mo2="May";
}
if($daa2[1]=='06')
{
 $mo2="June";
}
if($daa2[1]=='07')
{
 $mo2="July";
}
if($daa2[1]=='08')
{
 $mo2="August";
}
if($daa2[1]=='09')
{
 $mo2="September";
}
if($daa2[1]=='10')
{
 $mo2="October";
}
if($daa2[1]=='11')
{
 $mo2="November";
}
if($daa2[1]=='12')
{
 $mo2="December";
}
  }
  $da++;
}

$s="select * from accountgroups where sectioninaccounts='1'";
$q=db_query($s);
while($r=db_fetch_array($q))
{
 $acgc[]=$r['groupname'];
 
 $ss="select * from accountgroups where parentgroupname='".$r['groupname']."' ";
 $ssq=db_query($ss);
  while($ssr=db_fetch_array($ssq))
  {
    $acgc[]=$ssr['groupname'];
  }
}
//print_r($acg);
//echo "Income <br>";
foreach($acgc as $accoc)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoc."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$from."' ";
 $qq=db_query($sq,$db);

 while($qr=db_fetch_array($qq))
 { 
  $accocde=$qr['accountcode'];

  
  if(in_array($accocde,$accc))
    {
      if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$accountc[$accocde]['cr']+$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de'] =$accountc[$accocde]['de']+$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accc[]=$qr['accountcode'];
	$accountc[$accocde]['name']=$qr['accountname'];
	$accountc[$accocde]['group']=$r['groupname'];
	  if($qr['actual']<0)
        {
          $cred=$qr['actual'];
	     // $account[$accode]['credit'] +=$qr['actual'];
	      $accountc[$accocde]['cr'] =$cred;
        }
		elseif($qr['actual']>0)
        {
          $debt=$qr['actual'];
	      $accountc[$accocde]['de']=$debt;
	     //$account[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$f=1;
foreach($accc as $acid)
{ if($f%2==0)
{
$cl="header4_1";
} 
else
{
$cl="header4_2";
}

   $totaldebitc[]=$accountc[$acid]['de'];
   $totalcreditc[]=$accountc[$acid]['cr'];
    if($accountc[$acid]['de']=='')
     {
      $accountc[$acid]['de']=0;
     }
	 if($accountc[$acid]['cr']=='')
     {
      $accountc[$acid]['cr']=0;
     }
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
 
		  $f++;
}
 


$acgce=array();
$debtotalce=0;
$cretotalce=0;
$totaldebitce=array();
$totalcreditce=array();
$accce=array();
$accountce=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=db_query($se);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";

while($re=db_fetch_array($qe))
{
 $acgce[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=db_query($sse);
  while($ssre=db_fetch_array($ssqe))
  {
    $acgce[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
foreach($acgce as $accoce)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoce."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period<'".$from."' ";
 $qqe=db_query($sqe);

 while($qre=db_fetch_array($qqe))
 { 
  $accocde=$qre['accountcode'];

  
  if(in_array($accocde,$accce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$accountce[$accocde]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de'] =$accountce[$accocde]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $accce[]=$qre['accountcode'];
	$accountce[$accocde]['name']=$qre['accountname'];
	$accountce[$accocde]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accountce[$accocde]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accountce[$accocde]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$z=1;
foreach($accce as $acide)
{ if($z%2==0)
{
$cl="header4_1";
}
else
{
$cl="header4_2";
}

   $totaldebitce[]=$accountce[$acide]['de'];
   $totalcreditce[]=$accountce[$acide]['cr'];
    if($accountce[$acide]['de']=='')
     {
      $accountce[$acide]['de']=0;
     }
	 if($accountce[$acide]['cr']=='')
     {
      $accountce[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";

		$z++;
}




$carry=round((array_sum($totaldebitc))-(array_sum($totalcreditce)));

$opening=$carry;


for($g=$from;$g<=$to;$g++)
{
  $mon[]=$g;
 }
 
//$mon=array('16');
$mm=1;
foreach($mon as $mont)
{
  
$pe="select lastdate_in_period from periods where periodno='".$mont."'";
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

$cmo[]=$mo;

 $acg=array();
$debtotal=0;
$cretotal=0;
$totaldebit=array();
$totalcredit=array();
$acc=array();
$account=array();



/*$data ='<table>';
if($mm==1){
  $data .='<tr><td colspan="2" align="center"><b>Cash In</b></td><td align="center"><b>'.$mo.'</b></td></tr>';
}
else{
$data .='<tr><td align="center"><b>'.$mo.'</b></td></tr>';
}
*/

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
foreach($acg as $acco)
{
  
  $sq="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$acco."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qq=db_query($sq);

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
$f=1;
foreach($acc as $acid)
{ if($f%2==0)
{
$cl="header4_1";
} 
else
{
$cl="header4_2";
}

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
	 
  //$data.= "<tr><td>".$acid."</td><td>".$account[$acid]['name']."</td><td>".$account[$acid]['de']."</td><td>".$account[$acid]['cr']."</td></tr>";
  /*$data.= '<tr class="'.$cl.'">';
               if($mm==1)
			   {
  				$data.= '<td align="right">'.$acid.'</td><td align="left">'.ucwords($account[$acid]['name']).'</td>';
				}
				$data.= '<td align="right">'.round($account[$acid]['de'],2).'</td> </tr>';*/
		  $f++;
		  $accou=$account[$acid]['name'];
		  
	$inco[$accou][$mo]=	round($account[$acid]['de']); 
}
     $inco['Total Cash In'][$mo]=round(array_sum($totaldebit)); 
 
/*         $data.= '<tr class="odd">';
            if($mm==1)
			   {
				$data .='<td  colspan="2"><b>Total Cash In</b></td>';
				}
				$data .='<td align="right">'.round(array_sum($totaldebit),2).'</td></tr>';
				
				 $data.= '<tr class="even">';
            if($mm==1)
			   {
				$data .='<td class="header2" colspan="2"><b>Total Cash Available</b></td>';
				}
				$data .='<td class="header2" align="right">'.round((array_sum($totaldebit)+$opening),2).'</td></tr>';
				
$data .='</table>';*/
 


$acge=array();
$debtotale=0;
$cretotale=0;
$totaldebite=array();
$totalcredite=array();
$acce=array();
$accounte=array();
$se="select * from accountgroups where sectioninaccounts='3'";
$qe=db_query($se);
//$datae="<table><tr><td colspan='4'>Expenditure</td></tr><tr><td>Id</td><td>A/C</td><td>Income</td><td>Exp</td></tr>";
/*$data .='<table>';
if($mm==1)
		{
$data .='<tr class="evenrow"><td align="center" colspan="2"><b>Cash Out</b></td><td align="center">'.$mo.'</td></tr>';
}
else
{
 $data .='<tr><td align="center">'.$mo.'</td></tr>';
}*/
while($re=db_fetch_array($qe))
{
 $acge[]=$re['groupname'];
 
 $sse="select * from accountgroups where parentgroupname='".$re['groupname']."' ";
 $ssqe=db_query($sse);
  while($ssre=db_fetch_array($ssqe))
  {
    $acge[]=$ssre['groupname'];
  }
}
//print_r($acge);
//echo "Expenditure <br>";
foreach($acge as $accoe)
{
  
 $sqe="select distinct(chartmaster.accountcode),chartmaster.accountname,chartdetails.budget,chartdetails.actual,chartdetails.bfwd from chartmaster,chartdetails where chartmaster.group_='".$accoe."' and chartdetails.accountcode=chartmaster.accountcode and chartdetails.period='".$mont."' ";
 $qqe=db_query($sqe);

 while($qre=db_fetch_array($qqe))
 { 
  $accode=$qre['accountcode'];

  
  if(in_array($accode,$acce))
    {
      if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$accounte[$accode]['cr']+$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de'] =$accounte[$accode]['de']+$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }	  
  }
  else
    {
     $acce[]=$qre['accountcode'];
	$accounte[$accode]['name']=$qre['accountname'];
	$accounte[$accode]['group']=$re['groupname'];
	  if($qre['actual']<0)
        {
          $crede=$qre['actual'];
	     // $accounte[$accode]['credit'] +=$qr['actual'];
	      $accounte[$accode]['cr'] =$crede;
        }
		elseif($qre['actual']>0)
        {
          $debte=$qre['actual'];
	      $accounte[$accode]['de']=$debte;
	     //$accounte[$accode]['debit'] +=$qr['actual'];
        }
	}
   }
}
$z=1;
foreach($acce as $acide)
{ /*if($z%2==0)
{
$cl="header4_1";
}
else
{
$cl="header4_2";
}*/

   $totaldebite[]=abs($accounte[$acide]['de']);
   $totalcredite[]=abs($accounte[$acide]['cr']);
    if($accounte[$acide]['de']=='')
     {
      $accounte[$acide]['de']=0;
     }
	 if($accounte[$acide]['cr']=='')
     {
      $accounte[$acide]['cr']=0;
     }
 // $datae.= "<tr><td>".$acide."</td><td>".$accounte[$acide]['name']."</td><td>".$accounte[$acide]['de']."</td><td>".$accounte[$acide]['cr']."</td></tr>";
/*$data .='<tr class="'.$cl.'">';
               if($mm==1)
			   {
				$data .='<td align="right">'.$acide.'</td>
				<td>'.ucwords($accounte[$acide]['name']).'</td>';
				}
				$data.='<td align="right">'.round(abs($accounte[$acide]['cr']),2).'</td></tr>';*/
		$z++;
		
		 $accoue=$accounte[$acide]['name'];
		  
	$expo[$accoue][$mo]=round(abs($accounte[$acide]['cr'])); 
}
$expo['Total Cash Out'][$mo]=round(array_sum($totalcredite)); 


/*$data.= '<tr class="odd">';
 if($mm==1)
			   {
				$data.='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Total Cash Out</b></td>';
				}
				$data.='<td style="border-top:1px solid; border-top-color:#ccc;" align="right">'.round(array_sum($totalcredite),2).'</td></tr>';
				
				$cico=round((array_sum($totaldebit))-(array_sum($totalcredite)),2);
				$data.='<tr class="even">';
if($mm==1)
{
$data .='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Monthly Cash Flow (Cash In - Cash Out)</b></td>';
}
$data .='<td  align="right"><b>'.round((array_sum($totaldebit))-(array_sum($totalcredite)),2).'</b></td></tr>';

$data.= '<tr class="odd">';
if($mm==1)
{
$data .='<td style="border-top:1px solid; border-top-color:#ccc;" colspan="2"><b>Begining Cash Balance</b></td>';
}
$data .='<td  align="right"><b>'.round($opening,2).'</b></td></tr>';

$data.= '<tr class="even">';
if($mm==1)
{
$data .='<td class="header2" colspan="2"><b>Ending Cash Balance</b></td>';
}
$data .='<td  align="right" class="header2"><b>'.round(($cico+$opening),2).'</b></td></tr>';
				
$data.='</table>';
$dat .='<td>'.$data.'</td>';*/
 
 $mm++;
$cico=round((array_sum($totaldebite))-(array_sum($totalcredite)));
 
 $expo['Monthly Cash Flow (Cash In - Cash Out)'][$mo]=round((array_sum($totaldebite))-(array_sum($totalcredite)));
 $expo['Begining Cash Balance'][$mo]=round($opening);
 $expo['Ending Cash Balance'][$mo]=round(($cico+$opening));
 
 $opening=round(($cico+$opening));
 }
/* $output .='<table border="0"><tr class="oddrow"><td><h2>Cash Flow From '.$dafr.' To '.$dato.'</h2></td></tr><tr><td align="right"></td></tr></table><table><tr>'.$dat.'</tr></table>';*/
 
 //echo $record;

	/*print_r($expo);
	echo "<br><br>";
	print_r($totres);*/
	// $output;
	$inarr=sizeof($inco);
	$exarr=sizeof($expo);
	$totatt=1;
	$te .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td class="header_report" colspan="5" align="center">
Cash Flow Report</td></tr>
</table><br>';
$te .='<table cellpadding="0" cellspacing="0" border="0">
<tr><td  colspan="5" align="left"><b>Cash Flow From '.$mo1.' '.$daa1[0].' To '.$mo2.' '.$daa2[0].'</b></td></tr>
</table><br>';
	$te .='<table class="tbl_border" cellpadding="3" cellspacing="2">';
	
	$te .= '<tr><td class="header2"><b>Cash In</b></td>';
	
	for($i=0;$i<sizeof($cmo);$i++){
	   $te .='<td class="header2"><b>'.$cmo[$i].'</b></td>';
	}
	$te .='</tr>';
	$num=1;
	foreach($inco as $ac => $mon)
	{  
	  $te .='<tr>';
	  $t=1;
		 if($totarr%2==0)
		 {
		   $cla="header4_1";
		 }
		 else
		 {
		  $cla="header4_2";
		 }
	   foreach($mon as $moo => $month)
	     { 
		    if($num<$inarr)
			{
			   if($t==1)
				 {
				 $te .='<td class="'.$cla.'">'.ucwords($ac).'</td><td class="'.$cla.'">'.$month.'</td>';
				 }
				 else
				 {
				  $te .='<td class="'.$cla.'">'.$month.'</td>';
				 }
			}
			else
			{
			    if($t==1)
				 {
				 $te .='<td class="header2"><b>'.ucwords($ac).'</b></td><td class="header2"><b>'.$month.'</b></td>';
				 }
				 else
				 {
				  $te .='<td class="header2"><b>'.$month.'</b></td>';
				 }
			}	 
		  $t++;
	     }
		  $te .='</tr>';
	     $num++;
		 $totarr++;
	}
	
	
	
	
	
		$te .= '<tr><td class="header2"><b>Cash Out</b></td>';
	$numm=1;
	for($i=0;$i<sizeof($cmo);$i++){
	   $te .='<td class="header2"><b>'.$cmo[$i].'</b></td>';
	}
	$te .='</tr>';
	foreach($expo as $ac => $mon)
	{  
	  $te .='<tr>';
	  $tt=1;
	   if($totarr%2==0)
		 {
		   $cla="header4_1";
		 }
		 else
		 {
		  $cla="header4_2";
		 }
	   foreach($mon as $month)
	     {
		   if($numm<($exarr-3))
			{
			   if($tt==1)
				{
				$te .='<td class="'.$cla.'">'.ucwords($ac).'</td><td class="'.$cla.'">'.$month.'</td>';
				}
				else
				{
				  $te .='<td class="'.$cla.'">'.$month.'</td>';
				}
			 }
			 else
			 {
			   if($tt==1)
				{
				$te .='<td class="header2"><b>'.ucwords($ac).'</b></td><td class="header2"><b>'.$month.'</b></td>';
				}
				else
				{
				  $te .='<td class="header2"><b>'.$month.'</b></td>';
				}
			 }	
		  $tt++;
	     }
		  $te .='</tr>';
	     $numm++;
		 $totarr++;
	}
	$te .='</table>';
	ob_end_clean();
	//echo $te;
	//exit;
	 // print a block of text using Write()
	$pdf->writeHTML($te, true, 0, true, true);
	//Close and output PDF document
	$pdf->Output('cashflow_'.time().'.pdf', 'I');
	
	db_set_active('default');
}



