<?php

/* $Id: GLAccountInquiry.php 4630 2011-07-14 10:27:29Z daintree $*/

include ('includes/session.inc');
$title = _('General Ledger Account Inquiry');
include('includes/header.inc');
include('includes/GLPostings.inc');

function filecheck($path)
{
$fn=explode("/",$path);
  $mn=count($fn);
  $fn[$mn-1];
 
 $fl=explode("?",$fn[$mn-1]);
 return $fl[0];
  
}
$sr=filecheck($_SERVER['HTTP_REFERER']);
$sel=filecheck($_SERVER['SCRIPT_NAME']);
if($sr==$sel)
{
  
}
else
{
 unset($_SESSION['acc']);
}

if (isset($_POST['Account'])){
	$SelectedAccount = $_POST['Account'];
	
} elseif (isset($_GET['Account'])){
	$SelectedAccount = $_GET['Account'];
	$getaccount=1;
	$_SESSION['acc']=$_GET['Account'];
}

if (isset($_POST['Period'])){
	$SelectedPeriod = $_POST['Period'];
} elseif (isset($_GET['Period'])){
	$SelectedPeriod = $_GET['Period'];
}





//echo '<div class="page_help_text">' . _('Use the keyboard Shift key to select multiple periods') . '</div><br />';

echo '<form method="POST" action="' . $_SERVER['SCRIPT_NAME'] . '">';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

/*Dates in SQL format for the last day of last month*/
$DefaultPeriodDate = Date ('Y-m-d', Mktime(0,0,0,Date('m'),0,Date('Y')));


if(isset($_SESSION['acc']))
{
  $cond.="and accountcode='".$_SESSION['acc']."'";
}
 $sql = "SELECT accountcode, accountname FROM chartmaster where 1=1 ".$cond." ORDER BY accountcode";
$Account = DB_query($sql,$db);
/*Show a form to allow input of criteria for TB to show */
echo '<div class="breadcrumb"><a href="/'.$u[1].'">Home</a> &raquo; <a href="journalbook.php">Journal Book</a></div>

<table style="border:none;">
<tr>	<td align="" class="tdform-width"><fieldset><legend>Journal Book</legend>
<table align="left" class="frmtbl">
<tr><td></div></td>';

//Select the tag
/*echo '<tr><td>' . _('Select Tag') . ':</td><td><select name="tag">';

$SQL = "SELECT tagref,
			tagdescription
		FROM tags
		ORDER BY tagref";

$result=DB_query($SQL,$db);
echo '<option value=0>0 - '._('All tags');
while ($myrow=DB_fetch_array($result)){
	if (isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']){
		echo '<option selected value=' . $myrow['tagref'] . '>' . $myrow['tagref'].' - ' .$myrow['tagdescription'];
	} else {
		echo '<option value=' . $myrow['tagref'] . '>' . $myrow['tagref'].' - ' .$myrow['tagdescription'];
	}
}
echo '</select></td></tr>';*/
// End select tag
echo '<td><div class="divwrapper"><div class="forperiodrange">'._('For Period Range').': <span style="color:#FF0000">*</span></div><div class="maincol"><select size="4" Name=Period[] multiple>';
$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno desc";
$Periods = DB_query($sql,$db);
$id=0;
while ($myrow=DB_fetch_array($Periods,$db)){
	if(isset($SelectedPeriod[$id]) and $myrow['periodno'] == $SelectedPeriod[$id]){
		echo '<option selected value=' . $myrow['periodno'] . '>' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period']));
		$id++;
	} else {
		echo '<option value=' . $myrow['periodno'] . '>' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period']));
	}
}
echo '</select></div></div></td>
<td><div class="divwrapper"><div class="generatebtn"><input type=submit name="Show" value="'._('Show Journals').'"></div></div></td>
		</tr></tr></table></fieldset>
		</table></form>';

/* End of the Form  rest of script is what happens if the show button is hit*/

if (isset($_POST['Show'])){
 
   /*if (isset($SelectedAccount) && $SelectedAccount==''){
		prnMsg(_('A Account Must Be Selected'),'info');
		include('includes/footer.inc');
		exit;
	}*/
	if (!isset($SelectedPeriod)){
		prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.inc');
		exit;
	}
	/*Is the account a balance sheet or a profit and loss account */
	
	$result = DB_query("SELECT pandl
				FROM accountgroups
				INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_",$db);
	$PandLRow = DB_fetch_row($result);
	if ($PandLRow[0]==1){
		$PandLAccount = True;
	}else{
		$PandLAccount = False; /*its a balance sheet account */
	}

	$FirstPeriodSelected = min($SelectedPeriod);
	$LastPeriodSelected = max($SelectedPeriod);

	if ($_POST['tag']==0) {
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

	} else {
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
	}

	$namesql = "SELECT accountname FROM chartmaster ";
	$nameresult = DB_query($namesql, $db);
	$namerow=DB_fetch_array($nameresult);
	$SelectedAccountName=$namerow['accountname'];
	$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
	$TransResult = DB_query($sql,$db,$ErrMsg);
	$numr=DB_num_rows($TransResult);
$corpbranch=getCorporationBranch($_SESSION['uid'],$db);
    if($numr>0)
	{
	echo '<br /><table><tr class="oddrow"><td colspan="6"><h2>Journal Book</h2></td></tr><tr><td colspan="6" align="right"><a href="/'.$u[1].'/generateaccountpdf.php?sdate='.$FirstPeriodSelected.'&edate='.$LastPeriodSelected.'&op=journalbook&branch='.$corpbranch.'" target="blank"><img src="images/pdf_icon.gif"/></a></td></tr>';

	
	$TableHeader = '<tr>
			<th>' . _('Voucher No.') . '</th>
			
			<th>' . _('Date') . '</th>
			<th>' . _('Debit') . '</th>
			<th>' . _('Credit') . '</th>
			<th>' . _('Narrative') . '</th>
			<th>' . _('Balance') . '</th>
			
			</tr>';

	echo $TableHeader;

	if ($PandLAccount==True) {
		$RunningTotal = 0;
	} else {
			// added to fix bug with Brought Forward Balance always being zero
					$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE  chartdetails.period='" . $FirstPeriodSelected . "'";

				$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
				$ChartDetailsResult = DB_query($sql,$db,$ErrMsg);
				$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
				// --------------------

		$RunningTotal =$ChartDetailRow['bfwd'];
		/*if ($RunningTotal < 0 ){ //its a credit balance b/fwd
			echo '<tr class="even">
				<td colspan=3><td>
				</td></td>
				<td class=number><b></b></td>
				<td></td>
				</tr>';
		} else { //its a debit balance b/fwd
			echo '<tr class"even">
				<td colspan=3></td>
				<td class=number><b></b></td>
				<td colspan=2></td>
				</tr>';
		}*/
	}
	$PeriodTotal = 0;
	$PeriodNo = -9999;
	$ShowIntegrityReport = False;
	$j = 1;
	$k=0; //row colour counter
	$IntegrityReport='';
	while ($myrow=DB_fetch_array($TransResult)) {
		if ($myrow['periodno']!=$PeriodNo){
			if ($PeriodNo!=-9999){ //ie its not the first time around
				/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/

				$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE  chartdetails.period='" . $PeriodNo . "'";

				$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
				$ChartDetailsResult = DB_query($sql,$db,$ErrMsg);
				$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
               
			   $pe="select lastdate_in_period from periods where periodno='".$PeriodNo."'";
			   $peq=DB_query($pe,$db);
			   $per=DB_fetch_array($peq);
			   $lasd=explode('-',$per['lastdate_in_period']);
			   $lastdate=$lasd[2].'-'.$lasd[1].'-'.$lasd[0];
				echo '<tr bgcolor="#FDFEEF">
					<td colspan=3><b>' . _('Total for period') . ' ' . $lastdate . '</b></td>';
				if ($PeriodTotal < 0 ){ //its a credit balance b/fwd
					if ($PandLAccount==True) {
						$RunningTotal = 0;
					}
					echo '<td></td>
						<td class=number><b>' . round(abs($PeriodTotal)) . '</b></td>
						<td></td>
						</tr>';
				} else { //its a debit balance b/fwd
					if ($PandLAccount==True) {
						$RunningTotal = 0;
					}
					echo '<td class=number><b>' . round(abs($PeriodTotal)) . '</b></td>
						<td colspan=2></td>
						</tr>';
				}
				$IntegrityReport .= '<br />' . _('Period') . ': ' . $PeriodNo  . _('Account movement per transaction') . ': '  . round(abs($PeriodTotal)) . ' ' . _('Movement per ChartDetails record') . ': ' . round(abs($ChartDetailRow['actual'])) . ' ' . _('Period difference') . ': ' . round(abs($PeriodTotal -$ChartDetailRow['actual']));

				if (ABS($PeriodTotal -$ChartDetailRow['actual'])>0.01){
					$ShowIntegrityReport = True;
				}
			}
			$PeriodNo = $myrow['periodno'];
			$PeriodTotal = 0;
		}

		if ($k==1){
			echo '<tr class="even">';
			$k=0;
		} else {
			echo '<tr class="odd">';
			$k++;
		}

		$RunningTotal += $myrow['amount'];
		$PeriodTotal += $myrow['amount'];

		if($myrow['amount']>=0){
			$DebitAmount = round(abs($myrow['amount']));
			$CreditAmount = '';
		} else {
			$CreditAmount = round(abs($myrow['amount']));
			$DebitAmount = '';
		}

		$FormatedTranDat = $myrow['trandate'];
		$dat=explode('-',$FormatedTranDat);
		$FormatedTranDate=$dat[2]."-".$dat[1]."-".$dat[0];
		$URL_to_TransDetail = $rootpath . '/GLTransInquiry.php?' . SID . '&TypeID=' . $myrow['type'] . '&TransNo=' . $myrow['typeno'];

		$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$myrow['tag'] . "'";
		$tagresult=DB_query($tagsql,$db);
		$tagrow = DB_fetch_array($tagresult);
		if ($tagrow['tagdescription']=='') {
			$tagrow['tagdescription']=_('None');
		}
		printf('<td>%s</td>
			
			<td align="center">%s</td>
			<td align="right" class=number>%s</td>
			<td align="right" class=number>%s</td>
			<td>%s</td>
			<td align="right" class=number><b>%s</b></td>
			
			</tr>',
			$myrow['voucher_no'],
			
			
			$FormatedTranDate,
			$DebitAmount,
			$CreditAmount,
			ucwords($myrow['narrative']),
			round(abs($RunningTotal))
			);

	}

	
	echo '</table>';
} /* end of if Show button hit */
else
{
  echo "<span style='color:#FF0000'><b>No Record(s) Found</b></span>";	
}
}

if (isset($ShowIntegrityReport) and $ShowIntegrityReport==True){
	if (!isset($IntegrityReport)) {$IntegrityReport='';}
	prnMsg( _('There are differences between the sum of the transactions and the recorded movements in the ChartDetails table') . '. ' . _('A log of the account differences for the periods report shows below'),'warn');
	echo '<p>'.$IntegrityReport;
}
include('includes/footer.inc');
?>