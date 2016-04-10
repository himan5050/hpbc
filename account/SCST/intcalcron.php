<?php
//cron for interest calculation
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$sqlg = "select intbatch_id,Branch,Scheme,Account_Number,up_to_date,uid from tbl_intbatch  where batch_status=0";
$resg = db_query($sqlg);
while($rsg = db_fetch_object($resg)){
  db_query('START TRANSACTION');
  db_query('BEGIN');
  $account_number = $rsg->Account_Number;
  $to_date = $rsg->up_to_date;
  $corporation = $rsg->Branch;
  $scheme = $rsg->Scheme;
  if($account_number){
     $cond =  "AND account_id = '".$account_number."' AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '".$to_date."' ";
  }
  else {
    $cond =  "AND account_id != '' AND UNIX_TIMESTAMP(tbl_loan_detail.last_interest_calculated) < '".$to_date."'";
  }

   $sql = "select tbl_loan_detail.ROI, tbl_loanee_detail.account_id,tbl_loanee_detail.loanee_id,tbl_loan_detail.o_principal,tbl_loan_detail.o_interest,tbl_loan_detail.o_LD,tbl_loan_detail.o_other_charges,tbl_loan_detail.last_interest_calculated,tbl_loanee_detail.reg_number from tbl_loan_detail INNER JOIN tbl_loanee_detail ON (tbl_loanee_detail.reg_number=tbl_loan_detail.reg_number) where tbl_loanee_detail.corp_branch='".$corporation."' AND tbl_loan_detail.scheme_name='".$scheme."' $cond";


   $res = db_query($sql);
//$interestrate = GetInterest($scheme,$rs->o_principal,$rs->o_interest,$rs->last_interest_calculated,date('Y-m-d',$to_date),$rs->ROI);
    $error=0;
	$counter =0;
    while($rs = db_fetch_object($res)){
         $counter++;
			 $counter++;
	 
		  $sqld = "select sum(amount) as dsum from tbl_loan_disbursement where loanee_id = '".$rs->loanee_id."'";
	 $resd = db_query($sqld);
     $rsd = db_fetch_object($resd);
	 
	 $interestrate = GetInterest($scheme,$rsd->dsum,$rs->o_interest,$rs->last_interest_calculated,date('Y-m-d',$to_date),$rs->ROI);
	 $valint = explode(" ",$interestrate);

    
	 
	 //getting all disbursed data behalf of selected accoun number
	 
	 
	
	 
	 
	 $sqlf = "select am_id,starting_balance,payment_date,ending_balance,principal_paid from tbl_loan_amortisaton where payment_date >='".$rs->last_interest_calculated."' and loanacc_id='".$rs->account_id."'";
     $resf = db_query($sqlf);
	 
	 if($rsf = db_fetch_object($resf)){
		 
	 $detailscal1 =   Details_Interest1($scheme,$rs->o_principal,$rs->o_interest,$rs->last_interest_calculated,date('Y-m-d',$to_date),$rs->ROI,$rs->account_id);
	 $details = '<table>
					 <tr><th>Starting Bal</th><th>Principal Paid</th><th>Ending Bal</th><th>Payment Date</th><th>Number of days</th><th>Interest Amount</th></tr>
					 '.$detailscal1.'
					 </table>';
	
	
	$detailscal =   Details_Interest($scheme,$rs->o_principal,$rs->o_interest,$rs->last_interest_calculated,date('Y-m-d',$to_date),$rs->ROI,$rs->account_id);
	 $valf = '<table>
					 <tr><th>Starting Bal</th><th>Principal Paid</th><th>Ending Bal</th><th>Payment Date</th><th>Number of days</th><th>Interest Amount</th></tr>
					 '.$detailscal.'
					 </table>';
					 $valf = $detailscal;
	 }else{
	     $valf =$valint[2];
	 }
		 $valf;
		$interest = $rs->o_interest;
		$finalint = $rs->o_interest+$valf;
		$last_interest_calculated = $rsg->up_to_date;
		$batch_id = $rsg->intbatch_id;
		//echo $reg_number = $rs->reg_number;

		$from_date = $rs->last_interest_calculated;
		$to_date  =  $rsg->up_to_date;
		$calculation_date = date("Y-m-d");
		$amount = $detailscal;
		$type = 'interest';
		$account_id = $rs->account_id;
		$reg_number = $rs->reg_number;
        $last_interest_calculated = date('Y-m-d',$rsg->up_to_date);
    
		if(!db_query("UPDATE {tbl_loan_detail} SET  o_interest='".$finalint."',last_interest_calculated='".$last_interest_calculated."'  WHERE reg_number='".$reg_number."'")){
			  $error=1;
        }
		if(!db_query("INSERT INTO {tbl_loan_interestld} (account_id,type,amount,from_date,to_date,calculation_date,intbatch_id) VALUES ('".$account_id."','".$type."','".$amount."','".$from_date."','".$to_date."','".$calculation_date."','".$batch_id."') ")){
			   $error=1;
		}






  }
  if($error == 1){
	db_query('ROLLBACK');
  
  }else{
     $cuser = user_load($rsg->uid);
	 db_query('COMMIT');
	 db_query("update tbl_intbatch set batch_status=1 where intbatch_id='".$rsg->intbatch_id."'");
	 voucherentry($rsg->intbatch_id,$accountid = '','interest',$finalint,$GLcode = '',$bank = '',1);
	 //sending mail here with batch id for this uid
	 $parameter = json_encode(array(0=>$cuser->name,1=>$rsg->intbatch_id)); 
     createMail('interestcalculation', $cuser->mail,'',$parameter,'');
  }
}




function GetInterest($scheme,$ammount,$int,$last_int_c_date,$update,$roi){
  //interest_type
  $sql = "select interest_type from tbl_scheme_master where loan_scheme_id='".$scheme."'";
  $res = db_query($sql);
  $rs = db_fetch_object($res);
  $days = dateDiffByDays($last_int_c_date, $update);
  $rt = $days." ";
  if($rs->interest_type == '152'){
    $rt .= "Compund". " ";
    //compund
	//$interest = 
	$p = $ammount+$int;
	
  }else{
    //simple
	$rt .= "Simple". " ";
	$p = $ammount;
  }
  $interest = round(($p*$days*$roi)/(100*36500),2);
 return $rt .= $interest. " ";
 
}



/*
 @disbursed section start here Details_disbursed used in future
*/

/*function Details_disbursed($scheme,$ammount,$int,$last_int_c_date,$update,$roi,$loanee_id,$account_id){
  $damount= "";
  $ddate ="";
  $did = "";
  $sql = "SELECT * FROM {tbl_loan_disbursement} where createdon >='".strtotime($last_int_c_date)."' and loanee_id='".$loanee_id."'";
  $res = db_query($sql);
 
  //$output ='<table><tr><th>Date</th><th>Amount</th></tr>';

  while($rs = db_fetch_object($res)){
   //	$output .='<tr><td>'.date('d-m-Y',$rs->createdon).'</td><td>'.$rs->amount.'</td></tr>';
    $damount .=$rs->amount." ";
	$did .= $rs->id." ";
	$ddate .= $rs->createdon." ";
  }
 // $output .='</table>';
  

  $output .='<table><tr><td colspan="3"> Disbursed with Pay details</td></tr>';
  $output .='<tr><th>Date</th><th>Amount</th><th>I amount </th><th>No days</th></tr>';
  $damount = explode(" ",trim($damount));
  $did = explode(" ",trim($did));
  $ddate = explode(" ",trim($ddate));
  //here we display all elements i.e disbursed and repayment day wise
  $lastid = sizeof($did)-1;
  if(sizeof($did) > 1){
	  $k=0;
    //for multiple cal 
    for($i=0;$i < sizeof($did); $i++){
         $k++;
          $j = $i+1;
		   $date1 = $ddate[$i];
		   $date2 = $ddate[$j];

		  if(!$date2){
		    $days = dateDiffByDays($lastdater,date('Y-m-d',$date1));
			$interest = $interest+$inter;
			$inter = getFinterest($scheme,$pamount,$interest,$days,$roi);
			$interest = $interest+$inter;
			$output .='<tr><td>D'.date('d m Y',$ddate[$i]).'</td><td>'.$damount[$i].'</td><td>'.$pamount.'</td><td>'.$days.' Interest '.$inter.'</td></tr>';

		  }else{
  		     $output .='<tr><td>D'.date('d m Y',$ddate[$i]).'</td><td>'.$damount[$i].'</td><td>&nbsp;</td></tr>';
		  }
         if($date2){
          $sqla = "select am_id,starting_balance,payment_date,ending_balance,principal_paid from tbl_loan_amortisaton where UNIX_TIMESTAMP(payment_date) >=$date1 AND UNIX_TIMESTAMP(payment_date) <=$date2 and loanacc_id='".$account_id."'";
          $resa = db_query($sqla);
		  while($rsa = db_fetch_object($resa)){
            $pamount = $damount[$i]-$rsa->principal_paid;
			$interest = $interest+$inter;
			$days = dateDiffByDays(date('Y-m-d',$date1),$rsa->payment_date);
			$inter = getFinterest($scheme,$damount[$i],$interest,$days,$roi);
			$interest =$interest+$inter;
		    $output .='<tr><td>R'.$rsa->payment_date.'</td><td>'.$rsa->principal_paid.'</td><td>'.$damount[$i].'</td><td>'.dateDiffByDays(date('Y-m-d',$date1),$rsa->payment_date). ' Interest ' .$inter .'</td></tr>';
			$lastdater = $rsa->payment_date;
		  
          }

          

		}
		

	}
	        $days = dateDiffByDays(date('Y-m-d',$ddate[$lastid]),$update);
			$interest = $interest+$inter;
			$inter = getFinterest($scheme,$ammount,$interest,$days,$roi);
			$interest = $interest+$inter;
	$output .='<tr><td>&nbsp;</td><td>&nbsp;</td><td>'.$ammount.'</td><td>'.dateDiffByDays(date('Y-m-d',$ddate[$lastid]),$update).' Interst '.$inter.'</td></tr>';
  }else{
    //single cal
 
  $output .='<tr><td>D'.date('d m Y',$ddate[0]).'</td><td>'.$damount[0].'</td><td>'.$pamount.'</td><td>'.$days.' Interest '.$inter.'</td></tr>';
  $sqla = "select am_id,starting_balance,payment_date,ending_balance,principal_paid from tbl_loan_amortisaton where UNIX_TIMESTAMP(payment_date) >= '".strtotime(date('Y-m-d',$ddate[0]))."' AND loanacc_id='".$account_id."'";
          $resa = db_query($sqla);
		  $k=0;
		  while($rsa = db_fetch_object($resa)){
            $pamount = $damount[$i]-$rsa->principal_paid;
			$interest = $interest+$inter;
			if($k == 0){
			  $days = dateDiffByDays(date('Y-m-d',$ddate[0]),$rsa->payment_date);
			   $output .='<tr><td>R'.$rsa->payment_date.'</td><td>'.$rsa->principal_paid.'</td><td>'.$damount[$i].'</td><td>'.$days. ' Interest ' .$inter .'</td></tr>';
            }else{
			  $days = dateDiffByDays(date('Y-m-d',$ddate[0]),$rsa->payment_date);
			}
			$inter = getFinterest($scheme,$damount[$i],$interest,$days,$roi);
			$interest =$interest+$inter;
		    $output .='<tr><td>R'.$rsa->payment_date.'</td><td>'.$rsa->principal_paid.'</td><td>'.$damount[$i].'</td><td>'.dateDiffByDays(date('Y-m-d',$date1),$rsa->payment_date). ' Interest ' .$inter .'</td></tr>';
			$lastdater = $rsa->payment_date;
		  
          }

  }
  $output .='</table>';
  return  $output;
}
*/
/*disbursed section end here*/

/**
 @* getting interest from here
 */
function Details_Interest1($scheme,$ammount,$int,$last_int_c_date,$update,$roi,$account_id){
 
  $sql = "select am_id,starting_balance,payment_date,ending_balance,principal_paid, principal_paid from tbl_loan_amortisaton where payment_date >='".$last_int_c_date."' and loanacc_id='".$account_id."' and payment_date <='".$update."' ";
  $res = db_query($sql);
  $idarray ="";

  while($rs = db_fetch_object($res)){
   	$idarray .=$rs->am_id." ";
	$ending_balance .=$rs->ending_balance." ";
	$principal_paid .= $rs->principal_paid. " ";
	$starting_balance .=$rs->starting_balance. " ";
	$payment_date .=$rs->payment_date. " ";
	
  }
  return  $val = getInt1($scheme,$idarray,$update,$ending_balance,$principal_paid,$starting_balance,$payment_date,$int,$roi,$last_int_c_date);
}


function Details_Interest($scheme,$ammount,$int,$last_int_c_date,$update,$roi,$account_id){
 
  $sql = "select am_id,starting_balance,payment_date,ending_balance,principal_paid, principal_paid from tbl_loan_amortisaton where payment_date >='".$last_int_c_date."' and loanacc_id='".$account_id."' and payment_date <='".$update."' ";
  $res = db_query($sql);
  $idarray ="";

  while($rs = db_fetch_object($res)){
   	$idarray .=$rs->am_id." ";
	$ending_balance .=$rs->ending_balance." ";
	$principal_paid .= $rs->principal_paid. " ";
	$starting_balance .=$rs->starting_balance. " ";
	$payment_date .=$rs->payment_date. " ";
	
  }
  return  $val = getInt($scheme,$idarray,$update,$ending_balance,$principal_paid,$starting_balance,$payment_date,$int,$roi,$last_int_c_date);
}


function getInt($scheme,$idarray,$update,$ending_balance,$principal_paid,$starting_balance,$payment_date,$int,$roi,$last_int_c_date){
    $valint = explode(" ",$idarray);
	$starting_balance = explode(" ",$starting_balance);
	$principal_paid = explode(" ",$principal_paid);
	$ending_balance = explode(" ",$ending_balance);
	$payment_date = explode(" ",$payment_date);
	$lastid = sizeof($valint)-2;
	$inter1 =0;
	$interest=$int;
    
	

	for($i=0;$i < sizeof($valint)-1; $i++){
	 
	 
	 if($i == 0 && (sizeof($valint)-1 == 1)){
         
		 
         $sql1s = "select payment_date from tbl_loan_amortisaton where am_id ='".$valint[$i]."'";
         $res1s = db_query($sql1s);
         $rs1s = db_fetch_object($res1s);

	     $days = dateDiffByDays($last_int_c_date, $update);
         $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
		 if($inter < 0){
		   $inter =0;
		 }
         $interest = $inter+$interest;
		// $inter1 = $inter1+$inter;
         $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
		 break;
	  }
	 $j = $i+1 ;
	 $statusn = $i;
	 if($payment_date[$i] == $payment_date[$j]){
	  $i = $j;
	 } 
	  if($i == 0){
         
         $sql1s = "select payment_date from tbl_loan_amortisaton where am_id ='".$valint[$i]."'";
         $res1s = db_query($sql1s);
         $rs1s = db_fetch_object($res1s);
	     $days = dateDiffByDays($last_int_c_date, $last_int_c_date);
         $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
		 if($inter < 0){
		   $inter =0;
		 }
         $interest = $inter+$interest;
		// $inter1 = $inter1+$inter;
         $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
	  }else{
		  if($i == $lastid){
			
			 $days = get_date1($valint[$i],$update);
			 $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
			 if($inter < 0){
		          $inter =0;
		     }
			 $interest = $inter+$interest;
			// $inter1 = $inter1+$inter;
			 $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 
		  }else{
			 $j = $i-1;
			 $days = get_date($valint[$j],$valint[$i]);
			 if($days > 0){
               $m = $i+1;
			   $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
			   if($inter < 0){
		          $inter =0;
		       }
			   $interest = $inter+$interest;
			  // $inter1 = $inter1+$inter;
			   $intamount = $ending_balance[$j]-$principal_paid[$i];
			  // echo $ending_balance[$i].'<br />';
			   $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 }else{
				
               if($statusn == 0){
			    //
                   $days = dateDiffByDays($last_int_c_date, $payment_date[$i]);
			   }else{
			       $days = $days;
               }
			   $inter = getFinterest($scheme,$ending_balance[$i],$inter1,$days,$roi);
			   if($inter < 0){
		         $inter =0;
		       }
			   $interest = $inter+$interest;
			   $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 }
		  }
	 }
	}
	
	return $interest;
	//return $output;
}


function getInt1($scheme,$idarray,$update,$ending_balance,$principal_paid,$starting_balance,$payment_date,$int,$roi,$last_int_c_date){
    $valint = explode(" ",$idarray);
	$starting_balance = explode(" ",$starting_balance);
	$principal_paid = explode(" ",$principal_paid);
	$ending_balance = explode(" ",$ending_balance);
	$payment_date = explode(" ",$payment_date);
	$lastid = sizeof($valint)-2;
	$inter1 =0;
	$interest=$int;
    
	

	for($i=0;$i < sizeof($valint)-1; $i++){
	 
	 
	 if($i == 0 && (sizeof($valint)-1 == 1)){
         
		 
         $sql1s = "select payment_date from tbl_loan_amortisaton where am_id ='".$valint[$i]."'";
         $res1s = db_query($sql1s);
         $rs1s = db_fetch_object($res1s);

	     $days = dateDiffByDays($last_int_c_date, $update);
         $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
		 if($inter < 0){
		   $inter =0;
		 }
         $interest = $inter+$interest;
		// $inter1 = $inter1+$inter;
         $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
		 break;
	  }
	 $j = $i+1 ;
	 $statusn = $i;
	 if($payment_date[$i] == $payment_date[$j]){
	  $i = $j;
	 } 
	  if($i == 0){
         
         $sql1s = "select payment_date from tbl_loan_amortisaton where am_id ='".$valint[$i]."'";
         $res1s = db_query($sql1s);
         $rs1s = db_fetch_object($res1s);
	     $days = dateDiffByDays($last_int_c_date, $last_int_c_date);
         $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
		 if($inter < 0){
		   $inter =0;
		 }
         $interest = $inter+$interest;
		// $inter1 = $inter1+$inter;
         $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
	  }else{
		  if($i == $lastid){
			
			 $days = get_date1($valint[$i],$update);
			 $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
			 if($inter < 0){
		          $inter =0;
		     }
			 $interest = $inter+$interest;
			// $inter1 = $inter1+$inter;
			 $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 
		  }else{
			 $j = $i-1;
			 $days = get_date($valint[$j],$valint[$i]);
			 if($days > 0){
               $m = $i+1;
			   $inter = getFinterest($scheme,$ending_balance[$i],$interest,$days,$roi);
			   if($inter < 0){
		          $inter =0;
		       }
			   $interest = $inter+$interest;
			  // $inter1 = $inter1+$inter;
			   $intamount = $ending_balance[$j]-$principal_paid[$i];
			  // echo $ending_balance[$i].'<br />';
			   $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 }else{
				
               if($statusn == 0){
			    //
                   $days = dateDiffByDays($last_int_c_date, $payment_date[$i]);
			   }else{
			       $days = $days;
               }
			   $inter = getFinterest($scheme,$ending_balance[$i],$inter1,$days,$roi);
			   if($inter < 0){
		         $inter =0;
		       }
			   $interest = $inter+$interest;
			   $output .= '<tr><td>'.$starting_balance[$i].'</td><td>'.$principal_paid[$i].'</td><td>'.$ending_balance[$i].'</td><td>'.$payment_date[$i].'</td><td>'.$days.'</td><td>'.$inter.'</td></tr>';  
			 }
		  }
	 }
	}
	
	//return $interest;
	return $output;
}

function getFinterest($scheme,$ending_balance,$interest1,$days,$roi){
 //echo '<br />'.$interest1.'<br />';
  /*interest calculation start here*/
     
		 $interest =0; 
         $sqli = "select interest_type from tbl_scheme_master where loan_scheme_id='".$scheme."'";
         $resi = db_query($sqli);
         $rsi = db_fetch_object($resi);
		
         if($rsi->interest_type == '152'){
    	     // $interest+$int;
			  $p = $ending_balance+$interest1;
	
         }else{
            	//$p = $ending_balance;
				 $p = $ending_balance;
         }
		//echo $p.' ';
        return $interest = round(($p*$days*$roi)/(36500),6);

		 /*end interest calculation*/

}



function get_date($id,$next_id){
   $sql1 = "select payment_date from tbl_loan_amortisaton where am_id ='".$id."'";
   $res1 = db_query($sql1);
   $rs1 = db_fetch_object($res1);

   $sql = "select payment_date from tbl_loan_amortisaton where am_id ='".$next_id."'";
   $res = db_query($sql);
   $rs = db_fetch_object($res);

  return $days = dateDiffByDays($rs1->payment_date, $rs->payment_date);
}


function get_date1($id,$update){
   $sql1 = "select payment_date from tbl_loan_amortisaton where am_id ='".$id."'";
   $res1 = db_query($sql1);
   $rs1 = db_fetch_object($res1);
//echo $update;
  return $days = dateDiffByDays($rs1->payment_date, $update);
}

?>
