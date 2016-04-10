<style>
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
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>LokMitra - Loan Re payment Collection Report</legend>
	
    <table align="left" class="frmtbl">
  <tr><td width="3%">&nbsp;</td>
	  <td align="left"><div class="form-item"><label>Mode of Payment:</label></div></td>
	  <td><?php print drupal_render($form['mode_paymentt']); ?></td>
  	  <td><div class="form-item"><label>From Date:</label></div></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><div class="form-item"><label>To Date:</label></div></td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
  </tr>
  <tr>
 <tr><td colspan="7" align="right"><div style="margin-right: 75px;"><?php print drupal_render($form); ?></div></td></tr>
  
  </table></fieldset>
  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
global $user;	
$uid=$user->uid;	

 $sqlrole = "select * from users_roles where uid=".$uid;
$res = db_query($sqlrole);
$rs = db_fetch_object($res);
 $as = $rs->rid;
$as1 = $rs->uid;
		
	
	
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['mode_paymentt'] == ''){
  form_set_error('form','Please enter any one of search field..');
}else if($_REQUEST['from_date']['date'] != '' && $_REQUEST['to_date']['date'] == ''){
  form_set_error('form','Please enter To Date');
}
else if($_REQUEST['to_date']['date'] != '' && $_REQUEST['from_date']['date'] == ''){
  form_set_error('form','Please enter From Date');
}
else {
	$from = $_REQUEST['from_date']['date'];
	$to = $_REQUEST['to_date']['date'];
	$fromtime =  date('Y-m-d',strtotime($from));
	$totime = date('Y-m-d',strtotime($to));
	$mode_payment = $_REQUEST['mode_paymentt'];
	
	
	
if($as == 16)
{	
  
    $sql = "SELECT * FROM tbl_loaneerepayment inner join users on(users.uid=tbl_loaneerepayment.createdby)    

where 1=1";

//$sql = "select 


$cond = '';	

    if($mode_payment=='1')
	{
	$cond = " AND users.uid='$uid'";		
		
	}
	
	else if($mode_payment == 'cash'){
	 $cond .= " AND tbl_loaneerepayment.mode_payment='$mode_payment' AND users.uid='$uid' ";
	}
	
	
	else if($mode_payment == 'cheque'){
	 $cond .= " AND tbl_loaneerepayment.mode_payment='$mode_payment' AND users.uid='$uid'";
	}
	
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loaneerepayment.createdon BETWEEN '$fromtime' AND '$totime') AND users.uid='$uid'";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loaneerepayment.createdon='$from' AND users.uid='$uid' ";
		}
		if($to!=''){
			$cond .= " AND tbl_loaneerepayment.createdon='$to' AND users.uid='$uid' ";
		}
	}
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/loaneelokreportpdf.php?op=loneefeefee_report";
   if($mode_payment){
		 $pdfurl.= "&mode_payment=$mode_payment";
	}
				
		
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<table cellpadding="3" cellspacing="2" border="0" width="100%" id="wrapper2">
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">LokMitra - Loan Re Payment Collection Report</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   
if($mode_payment == '1')
{ 

   $output .='<tr>
                <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loanee Name</th>
				<th>Date</th>
				<th>Mode Of Payment</th>
				<th>Amount </th>	
				<th>Cheque No.</th>
				<th>Cheque Date</th>
				<th>Infavour Of </th>
				<th>Bank Name </th>
				
			 </tr>';
			 
			 }
			 
			else if($mode_payment == 'cash')
{ 

   $output .='<tr>
               <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loan Name</th>
				<th>Date</th>
				<th>Mode Of Payment</th>
				<th>Amount</th>
										
			 </tr>';
			 
			 }
			 else if($mode_payment == 'cheque'){
			  $output .='<tr>
                <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loan Name</th>
				<th>Date</th>
				<th>Mode Of Payment</th>
				<th>Amount </th>
				<th>Cheque No. </th>
				<th>Date </th>
				<th>Infavour Of  </th>
				<th>Bank Name  </th>
				
				
				
				
				
				
										
			 </tr>';
			 
			 }

 $limit=10;			 
			 
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 //echo "select sum(cashipo+cashmo+cashcash) as sumamount, cashipo,cashmo,cashcash,ipono,currdatefield,currdatemo,currdatecash from tbl_rti_management GROUP BY nid";exit;
	
	if($mode_payment == '1')
	{
	$cond1 = 'cash_amount+cheque_amount';	
		
	}
	
	if($mode_payment == 'cash'){
		$cond1 = "cash_amount";
	}
	else if($mode_payment == 'cheque'){
		$cond1 = "cheque_amount";
	}
	
	
	
	
	 
	 //$sum = "select sum ";
	$conddate ="1=1";
	if($from!='' && $to!=''){
		 $conddate .= " AND (tbl_loaneerepayment.createdon BETWEEN '$fromtime' AND '$totime') ";
	}
	
	
	 //echo "select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate";exit;
	$sqlamount=db_query("select sum(".$cond1.") as sumamount from tbl_loaneerepayment where $conddate");
	
$sqlqu=db_fetch_object($sqlamount);

//echo $lo1 =$sqlqu->ipo;exit;

$sumamount =$sqlqu->sumamount;
	
	$ipono =$rs->ipono;
	$ipodate =$rs->currdatefield;
	
	$currdatemo =$rs->currdatemo;
	
	$currdatecash=$rs->currdatecash;
	if($rs->cash_amount){$amount =$rs->cash_amount;}
	else if($rs->cheque_amount){$amount =$rs->cheque_amount;}
	
	if($rs->createdon){$date =date("d-m-Y",strtotime($rs->createdon));}
	
	if($rs->cheque_date){$date1 =date("d-m-Y",strtotime($rs->cheque_date));}
	else{
		
		$date1 ='NA';
	}
	
		
		
	if($mode_payment == '1')
{ 	
		
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					  <td align="center">'.$date.'</td>
					  <td>'.ucwords($rs->mode_payment).'</td>
					  <td align="right">'.round($amount).'</td>
					  <td align="left">'.$rs->cheque_no.'</td>
					  <td align="center">'.$date1.'</td>
					  <td align="right">'.ucwords($rs->infavour).'</td>
					  <td align="left">'.ucwords($rs->bank_name).'</td>
						
					  
					  
					  
					  
	            </tr>';
				
				}
				
				else if($mode_payment == 'cash')
				{
				
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
				$output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					 
					   <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					<td align="center">'.$date.'</td>
					
					  <td align="left">'.ucwords($rs->mode_payment).'</td>
					   
					  <td align="right">'.round($amount).'</td>
					 
					  
					  
					  
					  
	            </tr>';
				
				}
				else{
					
						
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
				$output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					    <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					<td align="center">'.$date.'</td>
					  <td align="left">'.ucwords($rs->mode_payment).'</td>
					  <td align="right">'.$rs->cheque_amount.'</td> 
					  <td align="left">'.$rs->cheque_no.'</td>
					  <td align="center">'.$date1.'</td>
					   <td align="left">'.ucwords($rs->infavour).'</td>
					    <td align="left">'.ucwords($rs->bank_name).'</td>
						
					  
					  
					  
					  
					  
	            </tr>';
				
				
				
				}
				
				$cc += $amount;
				
		
	
   }
   
 
  
  if($counter > 0){
  
  $output .='</table>';
   echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }
  else if($fromtime > $totime){
	  
	form_set_error('form','To Date should be more than From Date.');  
	  
  }
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 }
 
 
 
 else {
	
	
	
  
    $sql = "SELECT * FROM tbl_loaneerepayment     

where 1=1";

//$sql = "select 


$cond = '';	

    if($mode_payment=='1')
	{
	$cond = '';		
		
	}
	
	else if($mode_payment == 'cash'){
	 $cond .= " AND tbl_loaneerepayment.mode_payment='$mode_payment'";
	}
	
	
	else if($mode_payment == 'cheque'){
	 $cond .= " AND tbl_loaneerepayment.mode_payment='$mode_payment'";
	}
	
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loaneerepayment.createdon BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loaneerepayment.createdon='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_loaneerepayment.createdon='$to' ";
		}
	}
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/loaneelokreportpdf.php?op=loneefeefee_report";
   if($mode_payment){
		 $pdfurl.= "&mode_payment=$mode_payment";
	}
				
		
	if($from){
		 $pdfurl.= "&from_date=$from";
	}
	
	if($to){
		 $pdfurl.= "&to_date=$to";
	}
	
	$pdfurl1=$pdfurl;
   
   
   $pdfimage = $base_url.'/'.drupal_get_path('theme','scst')."/images/pdf_icon.gif";
	

	$output = '<div class="listingpage_scrolltable"><table cellpadding="3" cellspacing="2" border="0" width="100%" id="form-container">
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">LokMitra - Loan Re Payment Collection Report</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   
if($mode_payment == '1')
{ 

   $output .='<tr>
                <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loan Name</th>
				<th>Date </th>
				<th>Mode Of Payment</th>
				<th>Amount </th>	
				<th>Cheque No.</th>
				<th>Date</th>
				<th>Infavour Of </th>
				<th>Bank Name </th>
				
			 </tr>';
			 
			 }
			 
			else if($mode_payment == 'cash')
{ 

   $output .='<tr>
               <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loan Name</th>
				<th>Date: </th>
				<th>Mode Of Payment</th>
				<th>Amount</th>
										
			 </tr>';
			 
			 }
			 else if($mode_payment == 'cheque'){
			  $output .='<tr>
                <th>S. No.</th>
                <th>Loan Account No.</th>
   				<th>Loan Name</th>
				<th>Date</th>
				<th>Mode Of Payment</th>
				<th>Amount </th>
				<th>Cheque No. </th>
				<th>Cheque Date </th>
				<th>Infavour Of  </th>
				<th>Bank Name  </th>
				
				
				
				
				
				
										
			 </tr>';
			 
			 }
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*10;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 //echo "select sum(cashipo+cashmo+cashcash) as sumamount, cashipo,cashmo,cashcash,ipono,currdatefield,currdatemo,currdatecash from tbl_rti_management GROUP BY nid";exit;
	
	if($mode_payment == '1')
	{
	$cond1 = 'cash_amount+cheque_amount';	
		
	}
	
	if($mode_payment == 'cash'){
		$cond1 = "cash_amount";
	}
	else if($mode_payment == 'cheque'){
		$cond1 = "cheque_amount";
	}
	
	
	
	
	 
	 //$sum = "select sum ";
	$conddate ="1=1";
	if($from!='' && $to!=''){
		 $conddate .= " AND (tbl_loaneerepayment.createdon BETWEEN '$fromtime' AND '$totime') ";
	}
	
	
	 //echo "select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate";exit;
	$sqlamount=db_query("select sum(".$cond1.") as sumamount from tbl_loaneerepayment where $conddate");
	
$sqlqu=db_fetch_object($sqlamount);

//echo $lo1 =$sqlqu->ipo;exit;

$sumamount =$sqlqu->sumamount;
	
	$ipono =$rs->ipono;
	$ipodate =$rs->currdatefield;
	
	$currdatemo =$rs->currdatemo;
	
	$currdatecash=$rs->currdatecash;
	if($rs->cash_amount){$amount =$rs->cash_amount;}
	else if($rs->cheque_amount){$amount =$rs->cheque_amount;}
	
	if($rs->createdon){$date =date("d-m-Y",strtotime($rs->createdon));}
	
	if($rs->cheque_date){$date1 =date("d-m-Y",strtotime($rs->cheque_date));}
	else{
		
	$date1="NA";	
	}
	

		
	if($mode_payment == '1')
{ 	
      if(($rs->cheque_no)=='')
	    {
		  $chn='NA';	
		}
		 else
		 {
			$chn=  $rs->cheque_no;
		 }
		 
		 if(($rs->infavour)=='')
	    {
		  $ifo='NA';	
		}
		 else
		 {
			$ifo= $rs->infavour;
		 }
		 
		  if(($rs->bank_name)=='')
	    {
		  $bn='NA';	
		}
		 else
		 {
			$bn=$rs->bank_name;
		 }
		
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
		<td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					  <td align="center">'.$date.'</td>
					  <td>'.ucwords($rs->mode_payment).'</td>
					  <td align="right">'.round($amount).'</td>
					  <td align="left">'.$chn.'</td>
					  <td align="center">'.$date1.'</td>
					  <td align="right">'.ucwords($ifo).'</td>
					  <td align="left">'.ucwords($bn).'</td>
						
					  
					  
					  
					  
	            </tr>';
				
				}
				
				else if($mode_payment == 'cash')
				{
				
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
				$output .='<tr class="'.$cl.'">
					<td class="center" width="5%">'.$counter.'</td>
					 
					   <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					<td align="center">'.$date.'</td>
					
					  <td align="left">'.ucwords($rs->mode_payment).'</td>
					   
					  <td align="right">'.round($amount).'</td>
					  
					  
					  
					  
	            </tr>';
				
				}
				else{
					
						
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
				$output .='<tr class="'.$cl.'">
					    <td class="center" width="5%">'.$counter.'</td>
					    <td align="left">'.$rs->loan_account.'</td>
					  <td align="left">'.ucwords($rs->loanee_name).'</td>
					<td align="center">'.$date.'</td>
					  <td align="left">'.ucwords($rs->mode_payment).'</td>
					  <td align="right">'.$rs->cheque_amount.'</td> 
					  <td align="left">'.$rs->cheque_no.'</td>
					  <td align="center">'.$date1.'</td>
					   <td align="left">'.ucwords($rs->infavour).'</td>
					    <td align="left">'.ucwords($rs->bank_name).'</td>
						
					  
					  
					  
					  
					  
	            </tr>';
				
				
				
				}
				
				$cc += $amount;
				
		
	
   }
   
 
  
  if($counter > 0){
  
  $output .='</table></div>';
   echo $output .= theme('pager', NULL, 10, 0);
     //echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.$sumamount.'</td></tr>';
  }
else if($fromtime > $totime){
	  
	form_set_error('form','To Date should be more than From Date.');  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 	
	
	
 
	 
	 
	 }
}

		
}
?>