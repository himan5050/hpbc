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
.maincoldate{margin-top:28px;}
</style>

<div id="form-container">
  <table width="100%" style="border:none;" >
  <tr>	<td align="left" class="tdform-width"><fieldset><legend>RTI Fee Report</legend>
	
    <table align="left" class="frmtbl">
  <tr>
	  <td align="left"><strong>Mode of Payment</strong>:</td>
	  <td><div class=""><?php print drupal_render($form['mode_paymentt']); ?></div></td>
  	  <td><strong>From Date</strong>:</td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['from_date']); ?></div></td>
	  <td><strong>To Date</strong>:</td>
  	  <td><div class="maincoldate"><?php print drupal_render($form['to_date']); ?></div></td>	  
  </tr>

 <tr><td colspan="6" align="right"><div style="margin-right:81px;"><?php print drupal_render($form); ?></div></td></tr>
  </table>
</fieldset>  </td>
    </tr>
  </table>
</div>
<?php
global $base_url;
$op = $_REQUEST['op'];
if($op == 'Generate'){
if($_REQUEST['from_date']['date'] == '' && $_REQUEST['to_date']['date'] == '' && $_REQUEST['section'] == '' && $_REQUEST['mode_paymentt'] == ''){
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
	$fromtime =  date('Y-m-d',strtotime('0 day',strtotime($from)));
	$totime = date('Y-m-d',strtotime('+1 day',strtotime($to)));
	 $mode_payment = $_REQUEST['mode_paymentt'];
	
	
  
     $sql = "SELECT * FROM tbl_rti_management   

where 1=1";

//$sql = "select 


$cond = '';	
	
	if($mode_payment){
		$cond .= " AND tbl_rti_management.mode_payment='$mode_payment'";
	}
		
		
		
	
	if($from!='' && $to!=''){
		 $cond .= " AND (tbl_rti_management.datecurrent BETWEEN '$fromtime' AND '$totime') ";
	}else{
		if($from!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$from' ";
		}
		if($to!=''){
			$cond .= " AND tbl_rti_management.datecurrent='$to' ";
		}
	}
	
	
	
  
  $query = $sql . $cond;
  
  $count_query = "SELECT COUNT(*) FROM (" .$query. ") AS count_query";

  $res = pager_query($query, 10, 0, $count_query);

 
   $pdfurl = $base_url."/rtifeegeneratepdf.php?op=rtifee_report";
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
	

	$output = '<table class="listingpage_scrolltable">
	
	<tr class="oddrow"><td align="left" colspan="8"><h2 style="text-align:left">RTI Fee Report</h2></td></tr>
	<tr>
	<td align="right" colspan="8">
	<a target="_blank" href="'.$pdfurl1.'"><img style="float:right;" src="'.$pdfimage.'" alt="Export to PDF" title="Export to PDF" /></a></td>
	</tr>
	
	</tr>
	';
   
   //$output .='';
   
if($mode_payment == '')
{ 

   $output .='<tr>
                <th>S. No.</th>
                <th>Application No.</th>
   				<th>Application Type</th>
				<th>Applicant Name</th>
				<th>Mode of Fees</th>
				<th>Amount</th>
				<th>Date</th>
				<th>IPO No.</th>		
			 </tr>';
			 
			 }
			 
			else if($mode_payment == 'ipo')
{ 

   $output .='<tr>
                <th>S. No.</th>
                <th>Application No.</th>
   				<th>Application Type</th>
				<th>Applicant Name</th>
				<th>Mode of Fees</th>
				<th>Amount</th>
				<th>IPO Date</th>
				<th>IPO No.</th>		
			 </tr>';
			 
			 }
			 else{
			  $output .='<tr>
                <th>S. No.</th>
                <th>Application No.</th>
   				<th>Application Type</th>
				<th>Applicant Name</th>
				<th>Mode of Fees</th>
				<th>Amount</th>
				<th>Date</th>			
			 </tr>';
			 
			 }
	$limit=10;		 
   if($_REQUEST['page']){
	$counter = $_REQUEST['page']*$limit;
	}else{
	$counter = 0;
	}
   while($rs = db_fetch_object($res)){
     $counter++;
	 //echo "select sum(cashipo+cashmo+cashcash) as sumamount, cashipo,cashmo,cashcash,ipono,currdatefield,currdatemo,currdatecash from tbl_rti_management GROUP BY nid";exit;
	if($mode_payment == 'ipo'){
		$cond1 = "cashipo";
	}
	else if($mode_payment == 'mo'){
		$cond1 = "cashmo";
	}
	else if($mode_payment == 'cash'){
		$cond1 = "cashcash";
	}
	
	
	else if($mode_payment == ''){
	  $cond1 = 'cashipo+cashmo+cashcash';
	} 
	 
	 //$sum = "select sum ";
	$conddate ="1=1";
	if($from!='' && $to!=''){
		 $conddate .= " AND (tbl_rti_management.datecurrent BETWEEN '$fromtime' AND '$totime') ";
	}
	
	
	 //echo "select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate";exit;
	$sqlamount=db_query("select sum(".$cond1.") as sumamount from tbl_rti_management where $conddate");
	
$sqlqu=db_fetch_object($sqlamount);

//echo $lo1 =$sqlqu->ipo;exit;

$sumamount =$sqlqu->sumamount;
	
	$ipono =$rs->ipono;
	$ipodate =$rs->currdatefield;
	
	$currdatemo =$rs->currdatemo;
	
	$currdatecash=$rs->currdatecash;
	if($rs->cashipo){$amount =round($rs->cashipo);}
	else if($rs->cashmo){$amount =round($rs->cashmo);}
	else if($rs->cashcash){$amount=round($rs->cashcash);} 
	
	
	if($rs->currdatefield){$date =date("d-m-Y",strtotime($rs->currdatefield));}
	else if($rs->currdatemo){$date =date("d-m-Y",strtotime($rs->currdatemo));}
	else if($rs->currdatecash){$date=date("d-m-Y",strtotime($rs->currdatecash));} 
	
	if($rs->mode_payment == '' || $rs->mode_payment == 'Online payment gateway'){
	$amount = 'N/A'	;
	$date = 'N/A';
	}
	/*if($rs->mode_payment == 'ipo')
	{
	
	$output .='<tr class="odd">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
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
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
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
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
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
		
	if($mode_payment == '')
{ 	
		
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	
if($rs->mode_payment == 'ipo'){ $pmode = 'IPO';}
else{$pmode = $rs->mode_payment;}	

if($pmode == ''){
$pmode ='N/A';	
}

if($ipono==''){$ipono='N/A';}
	  $output .='<tr class="'.$cl.'">
					  <td class="center">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.ucwords($pmode).'</td>					   
					  <td align="right">'.$amount.'</td>
					  <td align="center">'.$date.'</td>
					   <td align="right">'.$ipono.'</td>
					  
					  
					  
					  
	            </tr>';
				
				}
				
				else if($mode_payment == 'ipo')
				{
				
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	if($rs->mode_payment == 'ipo'){$pmode = 'IPO';}
	else if($rs->mode_payment == 'mo'){$pmode = 'MO';}	
	else{$pmode = $rs->mode_payment;}
	if($ipono==''){$ipono='N/A';}
				$output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.ucwords($pmode).'</td>
					   
					  <td align="right">'.$amount.'</td>
					  <td align="center">'.$date.'</td>
					   <td align="right">'.$ipono.'</td>
					  
					  
					  
					  
	            </tr>';
				
				}
				else{
					
						
	if ($counter%2==0){$cl="even";}else{$cl="odd";}
	if($rs->mode_payment == 'ipo'){$pmode = 'IPO';}
	else if($rs->mode_payment == 'mo'){$pmode = 'MO';}
	else{$pmode = $rs->mode_payment;}

				$output .='<tr class="'.$cl.'">
					  <td class="center" width="5%">'.$counter.'</td>
					  <td align="left">'.ucwords($rs->appno).'</td>
					  <td align="left">'.getLookupName(ucwords($rs->application_type)).'</td>
					  <td>'.ucwords($rs->application_name).'</td>
					  <td align="left">'.ucwords($pmode).'</td>					   
					  <td align="right">'.$amount.'</td>
					  <td align="center">'.$date.'</td>
					  
					  
					  
					  
					  
	            </tr>';
				
				
				
				}
				
				$cc += $amount;
				
		
	
   }
   
 
  
  if($counter > 0){
  
  $output .='</table>';
   echo $output .= theme('pager', NULL, 10, 0);
     echo '<tr class="odd"><td><strong>Total Amount</strong></td><td>&nbsp;</td><td>'.round($sumamount).'</td></tr>';
  }
  else if($fromtime >= $totime){
	  
	form_set_error('form','To Date should be more than From Date.');  
	  
  }
  
  else{
    echo '<font color="red"><b>No Record found...</b></font>';
  }
 }
		
}
?>