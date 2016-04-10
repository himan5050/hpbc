<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
global $base_url;
require_once ('tcpdf/pdfcss.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// create new PDF document
$pdf = new TCPDF(P, PDF_UNIT, A4, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HPBFDC');
$pdf->SetTitle('HPBFDC');
$pdf->SetSubject('HPBFDC');
$pdf->SetKeywords('HPBFDC');
$pdf->SetPrintHeader(false);
//$pdf->SetHeaderData('tcpdf/images/hpsc.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
// set header and footer fonts
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//set margins
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
//set some language-dependent strings
$pdf->setLanguageArray($l);
// set font
$pdf->SetFont('times', '', 9);
// add a page
$pdf->AddPage();

$pdf->setCellHeightRatio(0.69);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

global $user, $base_url;
$loan_id = $_REQUEST['loan_id'];
$amount = $_REQUEST['amount'];
$amid = $_REQUEST['amid'];
	$query = "SELECT r.*,l.fname,l.lname,l.account_id FROM tbl_loan_repayment r,tbl_loanee_detail l,tbl_loan_detail ld WHERE r.loanee_id = l.loanee_id AND ld.reg_number = l.reg_number AND r.id = '".$amid."'"; 
	$res = db_query($query);
	$re = db_fetch_object($res);
	if($re->paytype == 'Promoter Share')
	{
		$currentdate = date("d-m-Y",strtotime($re->payment_date));
		$othercharges = 'N/A';
		$installment = 'N/A';
		$lin=$base_url."/nodues/".$re->account_id;
		$rsword = convert_number(round($amount,2));
		$name = ucwords($re->fname.' '.$re->lname);
		$ben = round($amount,2);
		$gamount = round($amount,2);
		$famount = round($amount,2);
		$amount = 'N/A';
		$accnumber = $re->account_id;
	}else{
		$query = "SELECT *,sm.scheme_name as schemename FROM tbl_loanee_detail l,tbl_loan_detail ld,tbl_loan_amortisaton la,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.loan_id = la.loan_id AND ld.scheme_name = sm.loan_scheme_id AND ld.loan_id = '".$loan_id."'"; 
		$res = db_query($query);
		$lquery = "SELECT *,sm.scheme_name as schemename FROM tbl_loanee_detail l,tbl_loan_detail ld,tbl_loan_amortisaton la,tbl_scheme_master sm WHERE ld.reg_number = l.reg_number AND ld.loan_id = la.loan_id AND ld.scheme_name = sm.loan_scheme_id AND la.payment_id = '".$amid."'"; 
		$lres = db_query($lquery);
		$am = db_fetch_object($lres);
		$loan = db_fetch_object($res);
		$currentdate = date("d-m-Y",strtotime($am->payment_date));
		$othercharges = $am->other_charges_paid + $am->LD_paid;
		$installment = $am->principal_paid + $am->interest_paid;
		$lin=$base_url."/nodues/".$loan->account_id;
		$rsword = convert_number(round($amount,2));
		$name = ucwords($loan->fname.' '.$loan->lname);
		$gamount = 'N/A';
		$famount = round($amount,2);
		$accnumber = $loan->loanacc_id;
                $recnumber = $amid;
		$ben = 'N/A';
	}
	//$phone = getMessage('corporation', 'phone', '');
        $phone = '01892-264326';
	$for = getMessage('corporation', 'for', '');
	//$scstname = getMessage('corporation', 'name', '');
        $scstname = 'HIMACHAL BACKWARD CLASSES FINANCE AND DEVELOPMENT CORPORATION';
	//$address = getMessage('corporation', 'address', '');
        $address = 'KANGRA (H.P.)<BR/><BR/>Reciept';
	//$gamount = $amount + $loan->processing_fee;
	//$processing_fee = ($loan->processing_fee != '0.00')?$loan->processing_fee:'-';
	$tot=round($amount,2);
	$processing_fee = 'N/A';
$output = <<<EOD
		<div id="scst-receipt" style="page-break-inside:avoid;">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		 <tr><td></td></tr>
		  <tr>
			<td colspan="3" align="center"><strong font="16pt">$scstname</strong><br><br><strong>$address</strong></td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td width="28%" align="left"><strong>Reciept No.:</strong> $recnumber</td>
			<td width="30%">&nbsp;</td>
			<td width="42%" align="left"><strong>Dated:</strong> $currentdate</td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">Received with thanks from Sh./Smt./Ms./Mr. &nbsp;<b>$name</b></td>
			</tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">a sum of Rs.: <b>$famount</b> Rupees &nbsp; <b>$rsword</b> Only<br /><br />
			  by cash/demand draft/cheque subject to realisation: ..................... dated: <b>$currentdate</b><br />
			</td>
			</tr>
		  <tr>
			<td colspan="3"><div><table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
				<td colspan="2" align="left">Installment: <b>$installment</b></td>
				</tr><tr><td></td></tr>
			  <tr>
				<td width="49%" align="left">Others Charges: <b>$othercharges</b></td>
				<td width="51%" align="left">Beneficiary Share $ben</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">Processing Fee: <b>$processing_fee</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left">Total: <b>$tot</b></td>
				<td align="left">Total: <b>$gamount</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Account No.: </strong> $accnumber</td>
				<td align="left"><strong>Scheme: </strong> $loan->schemename</td>
			  </tr><tr><td></td></tr>
			  
			  <tr>
				<td align="left"><strong>Total Amount Rs.</strong> $famount</td>
				<td align="left">For: <strong>$for</strong></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td colspan="2"><div><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr >
					<td align="left"><strong><br/>Authorised Signatory</strong></td>
					<td align="left"><strong><br/>Manager/C.A.O</strong></td>
				  </tr>
				    <tr >
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
				  </tr>
				</table></div></td>
				</tr>
			</table></div></td>
		  </tr>
		</table>
	</div>

<div id="scst-receipt" style="page-break-inside:avoid;">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td></td></tr>
		  <tr>
			<td colspan="3" align="center"><strong font="16pt">$scstname</strong><br><br><strong>$address</strong></td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td width="28%" align="left"><strong>Reciept No.:</strong> $recnumber</td>
			<td width="30%">&nbsp;</td>
			<td width="42%" align="left"><strong>Dated:</strong> $currentdate</td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">Received with thanks from Sh./Smt./Ms./Mr. &nbsp;<b>$name</b></td>
			</tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">a sum of Rs.: <b>$famount</b> Rupees &nbsp; <b>$rsword</b> Only<br /><br />
			  by cash/demand draft/cheque subject to realisation: ..................... dated: <b>$currentdate</b><br />
			</td>
			</tr>
		  <tr>
			<td colspan="3"><div><table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
				<td colspan="2" align="left">Installment: <b>$installment</b></td>
				</tr><tr><td></td></tr>
			  <tr>
				<td width="49%" align="left">Others Charges: <b>$othercharges</b></td>
				<td width="51%" align="left">Beneficiary Share $ben</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">Processing Fee: <b>$processing_fee</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left">Total: <b>$tot</b></td>
				<td align="left">Total: <b>$gamount</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Account No.: </strong> $accnumber</td>
				<td align="left"><strong>Scheme: </strong> $loan->schemename</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Total Amount Rs.</strong> $famount</td>
				<td align="left">For: <strong>$for</strong></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td colspan="2"><div><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr >
					<td align="left"><strong><br/>Authorised Signatory</strong></td>
					<td align="left"><strong><br/>Manager/C.A.O</strong></td>
				  </tr>
				     <tr >
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
				  </tr>
				</table></div></td>
				</tr>
			</table></div></td>
		  </tr>
		</table>
	</div>
<div id="scst-receipt" style="page-break-inside:avoid;">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr><td></td></tr>
		  <tr>
			<td colspan="3" align="center"><strong font="16pt">$scstname</strong><br><br><strong>$address</strong></td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td width="28%" align="left"><strong>Reciept No.:</strong> $recnumber</td>
			<td width="30%">&nbsp;</td>
			<td width="42%" align="left"><strong>Dated:</strong> $currentdate</td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">Received with thanks from Sh./Smt./Ms./Mr. &nbsp;<b>$name</b></td>
			</tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">a sum of Rs.: <b>$famount</b> Rupees &nbsp; <b>$rsword</b> Only<br /><br />
			  by cash/demand draft/cheque subject to realisation: ..................... dated: <b>$currentdate</b><br />
			</td>
			</tr>
		  <tr>
			<td colspan="3"><div><table width="100%" border="0" cellspacing="0" cellpadding="0" >
			  <tr>
				<td colspan="2" align="left">Installment: <b>$installment</b></td>
				</tr><tr><td></td></tr>
			  <tr>
				<td width="49%" align="left">Others Charges: <b>$othercharges</b></td>
				<td width="51%" align="left">Beneficiary Share $ben</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">Processing Fee: <b>$processing_fee</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left">Total: <b>$tot</b></td>
				<td align="left">Total: <b>$gamount</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Account No.: </strong> $accnumber</td>
				<td align="left"><strong>Scheme: </strong> $loan->schemename</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Total Amount Rs.</strong> $famount</td>
				<td align="left">For: <strong>$for</strong></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td colspan="2"><div><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr >
					<td align="left"><strong><br/>Authorised Signatory</strong></td>
					<td align="left"><strong><br/>Manager/C.A.O</strong></td>
				  </tr>
				     <tr >
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
					<td align="left"><tr >
					<td align="left"><strong><br/><br/><br/><br/>_____________________</strong><br/></td>
					</tr><strong>Signature</strong></td>
				  </tr>
				</table></div></td>
				</tr>
			</table></div></td>
		  </tr>
		</table>
	</div>

EOD;
 // print  using Write()
	ob_end_clean();
$pdf->writeHTML($output, true, 0, true, true);
//Close and output PDF document
$pdf->Output('cash_receipt_'.time().'.pdf', 'I');
?>