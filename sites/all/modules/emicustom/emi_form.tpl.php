<?php
 $array = explode('/',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('Calculate EMI', 'emi_cal');
?>
<table width="100%" border="0" cellspacing="2" cellpadding="1" id="form-container">
  <tr class="evenrow">
    <td align="center"><h2>EMI Calculator</h2></td>
    </tr>
  <tr class="oddrow">
    <td ><?php print drupal_render($form['loan_amount']); ?><?php print drupal_render($form['interest']); ?></td>
  </tr>
  <tr class="evenrow">
    <td><?php print drupal_render($form['tenuare']); ?></td>
  </tr>
  <tr class="oddrow">
    <td><?php print drupal_render($form['subsidy']); ?></td>
  </tr>
  <tr class="evenrow">
    <td class="back" align="center"><?php print drupal_render($form['submit']); ?></td>
	 <td><?php print drupal_render($form); ?></td>
  </tr> 
</table>
<br />

<?php


$op = $_POST['op'];


global $base_url;
$k=0;
if($_POST['subsidy']){
  
  if(!ereg("^[0-9]{1,10}$", $_POST['subsidy'])) {
         echo '<font color="red"><b>Please Enter valid Subsidy..</b></font><br />';
		 $k=1;
    }
}  

if($_POST['tenuare_year'] == '' && $_POST['tenuare_month'] == ''){
  echo '<font color="red"><b>Please Select Tenuare..</b></font>';
}
else if($_POST['tenuare_year'] || $_POST['tenuare_month']){
   if($_POST['tenuare_month'] > 11){
     echo '<font color="red"><b>Month shoud be less tahn 11.</b></font><br />';
   }
   
   if($_POST['tenuare_year']){ 
	if(!ereg("^[0-9]{1,10}$", $_POST['tenuare_year'])) {
        echo '<font color="red"><b>Please Enter valid Tenuare..</b></font><br />';
    }
   }

   if($_POST['tenuare_month']){ 
	if(!ereg("^[0-9]{1,10}$", $_POST['tenuare_month'])) {
        echo '<font color="red"><b>Please Enter  valid Tenuare..</b></font><br />';
    }
   }
}
else if($_POST['loan_amount']){
  if(!ereg("^[0-9]{1,10}$", $_POST['loan_amount'])) {
        echo '<font color="red"><b>Please Enter valid Loan amount..</b></font><br />';
    }
}
else if($_POST['interest']){
   if(!ereg("^([0-9]+(\.[0-9]+)?$)", $_POST['interest'])) {
         echo '<font color="red"><b>Please Enter valid Interest..</b></font><br />';
    }
}

 if($_POST['op'] == 'GO' && $_POST['loan_amount'] && $_POST['interest'] && ($_POST['tenuare_month'] || $_POST['tenuare_year']) && $k == 0){
  global $base_url;
  $p = $_POST['loan_amount'];
  $p_orgi = $_POST['loan_amount'];
  //$sub = 10000;
  
  if($_POST['subsidy']){
    $sub =$_POST['subsidy'];
  }else{
    $sub =0;
  }
  
  
  if($_POST['tenuare_month']){
    $t1 = 0.083*$_POST['tenuare_month'];
  }
  
  $t = $_POST['tenuare_year']+$t1;

  $n = $t*12;
  $N = $n+1;
  $r = $_POST['interest'];
  $N1 = $n*2;
  $rt = $r*$t;
  $round = round($N/$N1,2);
 //$round = '.529';
  $rounda = round($rt/100,2);
  //$round = $N/$N1;
$rounda = $rt/100;
  $int = round(($p-$sub)*$round*$rounda,2);
  
  //$int = ($p-$sub)*$round*$rounda;
  //$year_int = ($p-$sub)+$int;
  $year_int = ($p-$sub)+$int;
  $year_int_val = $year_int/$n;
  
  $emi_cal = round($year_int_val,2);
   //$emi_cal = $year_int_val;
  

  	 $psub_val = ($p-$sub);
  
  for($k=1;$k<=$n;$k++){
     
   
	     $p_rate = round($psub_val*$r,2);
		// $p_rate = $psub_val*$r;
	    // $psub_div = 12*100;
		 $psub_div = 12*100;
	    $p_orval = round(($p_rate/$psub_div),2);
		// $p_orval = $p_rate/$psub_div;
	 
	
	  $bachat_khata = round($emi_cal-$p_orval,2);
	 // $bachat_khata = $emi_cal-$p_orval;
	  $p_new  = round($psub_val-$bachat_khata,2);
	  //$p_new  = $psub_val-$bachat_khata;
	  $p = $p_new;
	  $psub_val = round($p_new,2);
	 
    // $output  .='EMI  '.$emi_cal.'  Interest     '.$p_orval .'       Bachat khata     '.$bachat_khata .'             End bal     '.$psub_val.'<br />';

	 $valout .='<tr><td>'.$k.'</td><td>'.floatval($emi_cal).'</td><td>'.floatval($p_orval) .'</td><td>'.floatval($bachat_khata) .'</td><td>'.floatval($psub_val).'</td></tr>';
  }
  




  $output ='<table>';
  $output .='<tr><td><b>Authorization Table : Monthly View</b></td></tr>'; 
  $output .='<tr><td><table>';
  $output .='<tr><th>Month</th><th>EMI</th><th>Interest Paid</th><th>Principal Paid</th><th>Ending Balance</th></tr>';
  $output .= $valout;
  $output .='</table></td></tr>';

  $output .='<tr><td><center><img alt="" src="'.$base_url.'/newpchart/examples/ppayment.php?loanamount='.floatval($p_orgi).'&subsidy_amount='.floatval($sub).'&interest='.floatval($year_int_val).'"></center></td></tr>';

  $output .='<tr><td>Loan Amount : '.floatval($p_orgi).' <br />Subsidy : '.floatval($sub).' <br /> Interest :'.round($int,2).'</td></tr>';
  echo $output .='</table>'; 
 
} 


?>
