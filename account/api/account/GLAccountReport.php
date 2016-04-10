<?php
/* $Id: GLAccountReport.php 4618 2011-07-02 23:04:59Z daintree $*/

include ('includes/session.inc');

if (isset($_POST['Period'])){
	$SelectedPeriod = $_POST['Period'];
} elseif (isset($_GET['Period'])){
	$SelectedPeriod = $_GET['Period'];
}

if (isset($_POST['RunReport'])){

	if (!isset($SelectedPeriod)){
		prnMsg(_('A period or range of periods must be selected from the list box'),'info');
		include('includes/footer.inc');
		exit;
	}
	if (!isset($_POST['Account'])){
		prnMsg(_('An account or range of accounts must be selected from the list box'),'info');
		include('includes/footer.inc');
		exit;
	}

	include('includes/PDFStarter.php');

/*PDFStarter.php has all the variables for page size and width set up depending on the users default preferences for paper size */

	$pdf->addInfo('Title',_('GL Account Report'));
	$pdf->addInfo('Subject',_('GL Account Report'));
	$line_height=12;
	$PageNumber = 1;
	$FontSize=10;
	NewPageHeader();

	foreach ($_POST['Account'] as $SelectedAccount){
		/*Is the account a balance sheet or a profit and loss account */
		$result = DB_query("SELECT chartmaster.accountname,
								accountgroups.pandl
							FROM accountgroups
							INNER JOIN chartmaster ON accountgroups.groupname=chartmaster.group_
							WHERE chartmaster.accountcode='" . $SelectedAccount . "'",$db);
		$AccountDetailRow = DB_fetch_row($result);
		$AccountName = $AccountDetailRow[0];
		if ($AccountDetailRow[1]==1){
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
						gltrans.trandate,
						gltrans.narrative,
						gltrans.amount,
						gltrans.account,
						gltrans.periodno,
						gltrans.voucher_no,
						gltrans.tag
					FROM gltrans INNER JOIN systypes
					ON gltrans.type=systypes.typeid
					WHERE gltrans.account = '" . $SelectedAccount . "'
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "' 
					AND periodno<='" . $LastPeriodSelected . "' 
					ORDER BY periodno, 
						gltrans.trandate, 
						counterindex";

		} else {
	 		$sql= "SELECT gltrans.type,
						gltrans.typename,
						gltrans.typeno,
						gltrans.trandate,
						gltrans.narrative,
						gltrans.amount,
						gltrans.account,
						gltrans.periodno,
						gltrans.voucher_no,
						gltrans.tag
					FROM gltrans INNER JOIN systypes
					ON gltrans.type=systypes.typeid
					WHERE gltrans.account = '" . $SelectedAccount . "'
					AND posted=1
					AND periodno>='" . $FirstPeriodSelected . "'
					AND periodno<='" . $LastPeriodSelected . "'
	                AND tag='" . $_POST['tag'] . "'
	                ORDER BY periodno, 
							gltrans.trandate, 
							counterindex";
		}

		$ErrMsg = _('The transactions for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved because') ;
		$TransResult = DB_query($sql,$db,$ErrMsg);

		if ($YPos < ($Bottom_Margin + (5 * $line_height))){ //need 5 lines grace otherwise start new page
			$PageNumber++;
			NewPageHeader();
		}

		$YPos -=$line_height;
		//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,$SelectedAccount . ' - ' . $AccountName . ' ' . ': ' . _('Listing for Period'). ' ' . $FirstPeriodSelected . ' ' . _('to') . ' ' . $LastPeriodSelected);

		if ($PandLAccount==True) {
			$RunningTotal = 0;
		} else {
			$sql = "SELECT bfwd,
						actual,
						period
					FROM chartdetails
					WHERE chartdetails.accountcode='" .  $SelectedAccount . "' 
					AND chartdetails.period='" . $FirstPeriodSelected . "'";

			$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
			$ChartDetailsResult = DB_query($sql,$db,$ErrMsg);
			$ChartDetailRow = DB_fetch_array($ChartDetailsResult);

			$RunningTotal =$ChartDetailRow['bfwd'];
			$YPos -=$line_height;
			$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize, _('Brought Forward Balance'));

			if ($RunningTotal < 0 ){ //its a credit balance b/fwd
   			  $LeftOvers = $pdf->addTextWrap(210,$YPos,50,$FontSize, number_format(-$RunningTotal,2) , 'right');
			} else { //its a debit balance b/fwd
               $LeftOvers = $pdf->addTextWrap(160,$YPos,50,$FontSize, number_format($RunningTotal,2) , 'right');
			}
		}
		$PeriodTotal = 0;
		$PeriodNo = -9999;

		$j = 1;
		$k=0; //row colour counter

		while ($myrow=DB_fetch_array($TransResult)) {

			if ($myrow['periodno']!=$PeriodNo){
				if ($PeriodNo!=-9999){ //ie its not the first time around
					/*Get the ChartDetails balance b/fwd and the actual movement in the account for the period as recorded in the chart details - need to ensure integrity of transactions to the chart detail movements. Also, for a balance sheet account it is the balance carried forward that is important, not just the transactions*/
					$sql = "SELECT bfwd,
								actual,
								period
							FROM chartdetails
							WHERE chartdetails.accountcode='" . $SelectedAccount . "' 
							AND chartdetails.period='" . $PeriodNo . "'";

					$ErrMsg = _('The chart details for account') . ' ' . $SelectedAccount . ' ' . _('could not be retrieved');
					$ChartDetailsResult = DB_query($sql,$db,$ErrMsg);
					$ChartDetailRow = DB_fetch_array($ChartDetailsResult);
           			$YPos -=$line_height;
                	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize, _('Period Total'));
					if ($PeriodTotal < 0 ){ //its a credit balance b/fwd
	                   $LeftOvers = $pdf->addTextWrap(210,$YPos,50,$FontSize, number_format(-$PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']) , 'right');
                    } else { //its a debit balance b/fwd
                       $LeftOvers = $pdf->addTextWrap(160,$YPos,50,$FontSize, number_format($PeriodTotal,$_SESSION['CompanyRecord']['decimalplaces']) , 'right');
					}
				}
				$PeriodNo = $myrow['periodno'];
				$PeriodTotal = 0;
			}

			$RunningTotal += $myrow['amount'];
			$PeriodTotal += $myrow['amount'];

			if($myrow['amount']>=0){
				$DebitAmount = number_format($myrow['amount'],$_SESSION['CompanyRecord']['decimalplaces']);
				$CreditAmount = '';
			} elseif ($myrow['amount']<0){
				$CreditAmount = number_format(-$myrow['amount'],$_SESSION['CompanyRecord']['decimalplaces']);
				$DebitAmount = '';
			}

			$FormatedTranDate = ConvertSQLDate($myrow['trandate']);

		/*	$tagsql="SELECT tagdescription FROM tags WHERE tagref='".$myrow['tag'] . "'";
			$tagresult=DB_query($tagsql,$db);
			$tagrow = DB_fetch_array($tagresult);*/

			// to edit this block
			$YPos -=$line_height;
			$FontSize=8;

			//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,50,$FontSize,$myrow['typename']);
			$LeftOvers = $pdf->addTextWrap(60,$YPos,50,$FontSize,$myrow['voucher_no'],'right');
			$LeftOvers = $pdf->addTextWrap(180,$YPos,50,$FontSize,$FormatedTranDate);
			$LeftOvers = $pdf->addTextWrap(220,$YPos,50,$FontSize,$DebitAmount,'right');
			$LeftOvers = $pdf->addTextWrap(280,$YPos,50,$FontSize,$CreditAmount,'right');
			$LeftOvers = $pdf->addTextWrap(350,$YPos,130,$FontSize,$myrow['narrative']);
			//$LeftOvers = $pdf->addTextWrap(470,$YPos,80,$FontSize,$tagrow['tagdescription']);
            $LeftOvers = $pdf->addTextWrap(470,$YPos,50,$FontSize,$AccountName);
			$LeftOvers = $pdf->addTextWrap(530,$YPos,50,$FontSize,$SelectedAccount);
			if ($YPos < ($Bottom_Margin + (5*$line_height))){
				$PageNumber++;
				NewPageHeader();
				//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize,$SelectedAccount . ' - ' . $AccountName);
			}

		}
		$YPos -=$line_height;
		if ($PandLAccount==True){
			//$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,200,$FontSize, _('Total Period Movement'));
		} else { /*its a balance sheet account*/
		//	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,150,$FontSize, _('Balance C/Fwd'));
		}
		if ($RunningTotal < 0){
		  // $LeftOvers = $pdf->addTextWrap(210,$YPos,50,$FontSize, number_format(-$RunningTotal,$_SESSION['CompanyRecord']['decimalplaces']) , 'right');
		} else { //its a debit balance b/fwd
          // $LeftOvers = $pdf->addTextWrap(160,$YPos,50,$FontSize, number_format($RunningTotal,$_SESSION['CompanyRecord']['decimalplaces']) , 'right');
        }
       	$YPos -=$line_height;
       	//draw a line under each account printed
        $pdf->line($Left_Margin, $YPos,$Page_Width-$Right_Margin, $YPos);
        $YPos -=$line_height;
	} /*end for each SelectedAccount */
	/*Now check that there is some output and print the report out */
	if (count($_POST['Account'])==0) {
	   prnMsg(_('An account or range of accounts must be selected from the list box'),'info');
	   include('includes/footer.inc');
	   exit;

	} else { //print the report

	    $pdf->OutputD($_SESSION['DatabaseName'] . '_GL_Accounts_' . date('Y-m-d') . '.pdf');
	    $pdf->__destruct();
	} //end if the report has some output
} /* end of if PrintReport button hit */
 else {
	$title = _('General Ledger Account Report');
	include('includes/header.inc');
	include('includes/GLPostings.inc');

	echo '<p class="page_title_text"><img src="'.$rootpath.'/css/'.$theme.'/images/transactions.png" title="' . _('General Ledger Account Inquiry') . '" alt="" />' . ' ' . _('General Ledger Account Report') . '</p>';

	echo '<div class="page_help_text">' . _('Use the keyboard Shift key to select multiple accounts and periods') . '</div><br />';

	echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	/*Dates in SQL format for the last day of last month*/
	$DefaultPeriodDate = Date ('Y-m-d', Mktime(0,0,0,Date('m'),0,Date('Y')));

	/*Show a form to allow input of criteria for the report */
	echo '<table cellpadding="2" cellspacing="1" border="0" id="wrapper">
		        
				<tr class="oddrow"><td colspan="2" align="center"><font size="3">General Ledger Account Report</font></td></tr>
				<tr class="evenrow">
		         <td>'._('Selected Accounts') . ':</td>
		         <td><select name="Account[]" multiple>';
	$sql = "SELECT accountcode, accountname FROM chartmaster ORDER BY accountcode";
	$AccountsResult = DB_query($sql,$db);
	$i=0;
	while ($myrow=DB_fetch_array($AccountsResult,$db)){
		if(isset($_POST['Account'][$i]) AND $myrow['accountcode'] == $_POST['Account'][$i]){
			echo '<option selected value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . $myrow['accountname'] . '</option>';
			$i++;
		} else {
			echo '<option value="' . $myrow['accountcode'] . '">' . $myrow['accountcode'] . ' ' . $myrow['accountname']  . '</option>';
		}
	}
	echo '</select></td>';

	echo '<tr class="oddrow"><td>'._('For Period range').':</td>
			<td><select Name=Period[] multiple>';
	$sql = "SELECT periodno, lastdate_in_period FROM periods ORDER BY periodno DESC";
	$Periods = DB_query($sql,$db);
	$id=0;

	while ($myrow=DB_fetch_array($Periods,$db)){
		if (isset($SelectedPeriod[$id]) and $myrow['periodno'] == $SelectedPeriod[$id]){
			echo '<option selected value="' . $myrow['periodno'] . '">' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
			$id++;
		} else {
			echo '<option value=' . $myrow['periodno'] . '>' . _(MonthAndYearFromSQLDate($myrow['lastdate_in_period'])) . '</option>';
		}
	}
	echo '</select></td></tr>';

	//Select the tag
	echo '<tr class="evenrow">
			<td>' . _('Select Tag') . ':</td>
			<td><select name="tag">';

	$SQL = "SELECT tagref,
					tagdescription
				FROM tags
				ORDER BY tagref";

	$result=DB_query($SQL,$db);
	echo '<option value=0>0 - '._('All tags') . '</option>';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['tag']) and $_POST['tag']==$myrow['tagref']){
		   echo '<option selected value=' . $myrow['tagref'] . '>' . $myrow['tagref'].' - ' .$myrow['tagdescription']  . '</option>';
		} else {
		   echo '<option value=' . $myrow['tagref'] . '>' . $myrow['tagref'].' - ' .$myrow['tagdescription']  . '</option>';
		}
	}
	echo '</select></td></tr><tr class="oddrow"><td colspan="2" align="center"><input type=submit name="RunReport" value="' ._('Run Report'). '"></td></tr>';
	// End select tag

	echo '</table></p>
		
			</form>';

	include ('includes/footer.inc');
	exit;
}


function NewPageHeader () {
	global $PageNumber,
				$pdf,
				$YPos,
				$Page_Height,
				$Page_Width,
				$Top_Margin,
				$FontSize,
				$Left_Margin,
				$Right_Margin,
				$line_height;
				$SelectedAccount;
				$AccountName;

	/*PDF page header for GL Account report */

	if ($PageNumber > 1){
		$pdf->newPage();
	}

	$FontSize=10;
	$YPos= $Page_Height-$Top_Margin;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,$_SESSION['CompanyRecord']['coyname']);
	$YPos -=$line_height;
	$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,300,$FontSize,_('GL Account Report'));
	$FontSize=8;
	$LeftOvers = $pdf->addTextWrap($Page_Width-$Right_Margin-120,$YPos,120,$FontSize,_('Printed') . ': ' . Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);

	$YPos -=(2*$line_height);

	/*Draw a rectangle to put the headings in     */

	$pdf->line($Left_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos+$line_height);
	$pdf->line($Left_Margin, $YPos+$line_height,$Left_Margin, $YPos- $line_height);
	$pdf->line($Left_Margin, $YPos- $line_height,$Page_Width-$Right_Margin, $YPos- $line_height);
	$pdf->line($Page_Width-$Right_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos- $line_height);

	/*set up the headings */
	$XPos = $Left_Margin+1;

	//$LeftOvers = $pdf->addTextWrap($XPos,$YPos,50,$FontSize,_('Type'),'centre');
	$LeftOvers = $pdf->addTextWrap(100,$YPos,50,$FontSize,_('Voucher No.'),'centre');
	$LeftOvers = $pdf->addTextWrap(180,$YPos,50,$FontSize,_('Voucher Date'),'centre');
	$LeftOvers = $pdf->addTextWrap(250,$YPos,50,$FontSize,_('Debit'),'centre');
	$LeftOvers = $pdf->addTextWrap(300,$YPos,50,$FontSize,_('Credit'),'centre');
	$LeftOvers = $pdf->addTextWrap(350,$YPos,130,$FontSize,_('Narrative'),'centre');
	$LeftOvers = $pdf->addTextWrap(470,$YPos,50,$FontSize,_('A/C Name'),'centre');
	$LeftOvers = $pdf->addTextWrap(530,$YPos,50,$FontSize,_('A/C Code'),'centre');

	$YPos =$YPos - (2*$line_height);
}
?>