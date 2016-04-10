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
.maincol .form-item label {
display: inline;
font-weight: bold;
float: left;
width: 110px;
margin-top: 5px;
}
.maincoldate{margin-top:30px;}
</style>

<div id="rec_participant">
  <table width="100%" style="border:none;" id="form-container">
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>FDR Register</legend>
	
    <table align="left" class="frmtbl">
  <tr><td width="3%">&nbsp;</td>
	  <td align="left"><div class="form-item"><label>District:</label></div></td><td><?php print drupal_render($form['district_id']); ?></div></td>
     
  	  <td><div class="form-item"><label>From Date:</label></div></td><td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><div class="form-item"><label>To Date:</label></div></td><td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
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
		
	
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['district_id'] == ''){
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
	
	$district_id = $_REQUEST['district_id'];
	
    $sql = "SELECT tbl_scheme_master.scheme_name,tbl_loan_detail.reg_number,tbl_loan_detail.bank_acc_no,tbl_loan_detail.disbursed_amount,tbl_loanee_detail.account_id,tbl_loanee_detail.fname,tbl_loanee_detail.lname,tbl_fdr.amount as fdramount,tbl_fdr.bank_name,tbl_loanee_detail.loanee_id  FROM tbl_loanee_detail inner join tbl_loan_detail on (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)     

inner join tbl_scheme_master on (tbl_loan_detail.scheme_name=tbl_scheme_master.loan_scheme_id) 
inner join tbl_fdr on (tbl_fdr.account_no=tbl_loanee_detail.loanee_id) 
where 1=1";

//$sql = "select 


$cond = '';	

    
	
	
	
	if($district_id){
	 $cond .= " AND tbl_loanee_detail.district='$district_id'";
	}
	
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_loan_detail.sanction_date BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_loan_detail.sanction_date='$to' ";
		}
	}
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/fdrregisterpdf.php?op=fdr_report";
   
	
	if($district_id){
		 $pdfurl.= "&district_id=$district_id";
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
	
	<tr class=oddrow><td align="left" colspan=15 ><h2 style="text-align:left">FDR Report</h2></td></tr>
	<tr>
	<td align="right" colspan=15>
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   

			 
	
			 
			  $output .='<tr>
                <th>S. No.</th>
                <th>Scheme Name</th>
   				<th>Account No.</th>
				<th>Loanee Name</th>
				<th>Disburse Amount</th>
				<th>Amount Of FDR Deposite </th>
				<th>Loan Issue Bank </th>
				
				
				
				
				
				
				
										
			 </tr>';
			 
	$limit=10;		
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 
	 
	 
	 
	   $sqlh = "select sum(amount) as amountdis from tbl_loan_disbursement where loanee_id='".$rs->loanee_id."'";
	 $resh = db_query($sqlh);
	 while($rsh = db_fetch_object($resh)){
	  
	 
	  $amountdis=$rsh->amountdis;
	
	
	 }
	 //echo "select sum(cashipo+cashmo+cashcash) as sumamount, cashipo,cashmo,cashcash,ipono,currdatefield,currdatemo,currdatecash from tbl_rti_management GROUP BY nid";exit;
	
	/*if($mode_payment == '1')
	{
	$cond1 = 'cash_amount+cheque_amount';	
		
	}
	
	if($mode_payment == 'cash'){
		$cond1 = "cash_amount";
	}
	else if($mode_payment == 'cheque'){
		$cond1 = "cheque_amount";
	}*/
	
	
	
	
	 
	 //$sum = "select sum ";
	/*$conddate ="1=1";
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
	
	{
		
		$date1 ="";
	}*/
	
	/*if($rs->mode_payment == 'ipo')
	{
	
	$output .='<tr class="odd">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.ucwords($rs->application_type).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="right">'.$rs->mode_payment.'</td>
					  	 
					  <td align="right">'.$ipono.'</td>
					  <td align="right">'.$ipodate.'</td>
					  <td align="right">'.$cashipo.'</td>
					 				  
					  
	            </tr>';
				
				
				}
				
				
				else if($rs->mode_payment == 'mo')
				{
				$output .='<tr class="odd">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.ucwords($rs->application_type).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="right">'.$rs->mode_payment.'</td>
				  <td align="right">'.$currdatemo.'</td>
					  <td align="right">'.$cashmo.'</td>
					  
					    </tr>';
				}
				
				else if($rs->mode_payment == 'cash')
				{
				$output .='<tr class="odd">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.ucwords($rs->application_type).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="right">'.$rs->mode_payment.'</td>
				<td align="right">'.$currdatecash.'</td>
					  <td align="right">'.$cashcash.'</td>
					  
					    </tr>';
				
				
				}*/
				
				
	
	 
	 /*if($rs->status==0){
		       $st='Hearing';
		    }

             else if($rs->status==1){
			   $st ='Argument';
			}

            else if($rs->status==2){

               $st ='Pending For Decision';

               }

        else if($rs->status==3){

            $st ='Decision';

        }*/
		
	
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	  $output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->scheme_name).'</td>
					  <td align="right">'.$rs->bank_acc_no.'</td>
					  <td align="left">'.ucwords($rs->fname).' '.ucwords($rs->lname).'</td>
					  <td align="right">'.round(abs($rs->disbursed_amount)).'</td>
					  <td align="right">'.round(abs($rs->fdramount)).'</td>
					  <td align="left">'.ucwords(getBankName($rs->bank_name)).'</td>
					 
					  
					  
					  
					  
	            </tr>';
				
			
	
				
				
				}
				
				//$cc += $amount;
				
		
	
 
   
 
  
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

		

?>