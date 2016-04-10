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
		$rsword = convert_number($amount);
		$name = ucwords($re->fname.' '.$re->lname);
		$ben = $amount;
		$gamount = $amount;
		$famount = $amount;
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
		$rsword = convert_number($amount);
		$name = ucwords($loan->fname.' '.$loan->lname);
		$gamount = 'N/A';
		$famount = $amount;
		$accnumber = $loan->account_id;
		$ben = 'N/A';
	}
	$phone = getMessage('corporation', 'scstphone', '');
	$for = getMessage('corporation', 'scstfor', '');
	$scstname = getMessage('corporation', 'scstname', '');
	$address = getMessage('corporation', 'scstaddress', '');
	//$gamount = $amount + $loan->processing_fee;
	//$processing_fee = ($loan->processing_fee != '0.00')?$loan->processing_fee:'-';
	$processing_fee = 'N/A';
$output = <<<EOD
		<div id="scst-receipt">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		  <tr>
			<td colspan="3" align="right"><strong>PH.:</strong> $phone</td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="center"><h1>$scstname</h1><br><strong>$address</strong></td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td width="28%" align="left"><strong>No.:</strong> $accnumber</td>
			<td width="30%">&nbsp;</td>
			<td width="42%" align="left"><strong>Dated:</strong> $currentdate</td>
		  </tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">Received with thanks from Sh./Smt./Ms./Mr. &nbsp;<b>$name</b></td>
			</tr><tr><td></td></tr>
		  <tr>
			<td colspan="3" align="left">a sum of Rs.: <b>$famount</b> Rupees &nbsp; <b>$rsword</b> Only<br /><br />
			  by cash/demand draft/cheque subject to realisation: ..................... dated: <b>$currentdate</b><br /><br />
			  Description :<b>Payment Receipt</b> Account No.:<b>$accnumber</b></td>
			</tr><tr><td></td></tr>
		  <tr>
			<td colspan="3"><div style="border:dotted #ccc 1px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" >
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
				<td align="left">Total: <b>$amount</b></td>
				<td align="left">Total: <b>$gamount</b></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td align="left"><strong>Beneficiary Account No.: </strong> $accnumber</td>
				<td align="left"><strong>Scheme: </strong> $loan->schemename</td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td>&nbsp;</td>
				<td align="left">For: <strong>$for</strong></td>
			  </tr><tr><td></td></tr>
			  <tr>
				<td colspan="2"><div style="border:dotted #ccc 1px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="left"><strong>Total Amount Rs.</strong> $famount</td>
					<td><strong>Cashier/Field Asstt.</strong></td>
					<td><strong>Accountant/Manager</strong></td>
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