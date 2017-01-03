<?php
 if ($_FILES['file']['tmp_name']){
   $dom = DOMDocument::load( $_FILES['file']['tmp_name'] );
   $rows = $dom->getElementsByTagName('Row');
   $first_row = true;
   foreach ($rows as $row ){  //$tenure,$amount,$interest,$subsidy = 0,$loan_id = ''
        if (!$first_row ){          
             $loanee_id ="";
             $index = 1;
  
             $cells = $row->getElementsByTagName( 'Cell' ); ;
             foreach( $cells as $cell ){ 
                $ind = $cell->getAttribute('Index');
                if ( $ind != null ) $index = $ind;
                if ( $index == 1 ) { $loanee_id  =  $cell->nodeValue   ;  }  
                $index += 1;
            }
  
            //echo "</br>".$loanee_id; exit;
            sendSMS($loanee_id);
        }
        $first_row = false;
        
  }
} 


/**
 *Send SMS Reminder to Loan account. 
 */
function sendSMS($loanee_id){
   $Link = mysql_connect('localhost','root','');
   if ($Link) {
    mysql_select_db('hpbc', $Link);
   }
   
   if($loanee_id){
       //$ldetail = "SELECT loan_disburse_date FROM `hpbc`.`tbl_loan_detail` WHERE loan_id = '".$loan_id."'";
       $ldetail = "SELECT *  FROM `tbl_loanee_detail` WHERE `account_id` LIKE '".$loanee_id."'";
       $lres = mysql_query($ldetail);
       $ld = mysql_fetch_object($lres);
       $mobile_no = isset($ld->mobile)?$ld->mobile:'';

       print_r($mobile_no);
       
       if(isset($mobile_no)){
       $data = array(
            "username" => "hpgovt",	        // type your assigned username here(for example:"username" => "CDACMUMBAI")
            "password" => "hpdit@1234",	        //type your password
            "senderid" =>"hpgovt",	       //type your senderID
            "smsservicetype" =>"singlemsg",     //*Note*  for single sms enter  îsinglemsgî , for bulk enter ìbulkmsgî
            "mobileno" =>$mobile_no,
            "bulkmobno" => "bulkmobno",	//enter mobile numbers separated by commas for bulk sms otherwise leave it blank                                        
            "content"  => "Attention HBCFDC Kangra Loan A/c No. $loanee_id . Your EMI for the month of JANUARY-2017 is due from 1st of this month onwards.  As interest is calculated on daily basis, pay early to avoid paying more interest. Please ignore this message if EMI already paid. For help, contact on 01892-264334, 262282." 	      //type the message.
        );
       
       post_to_url("http://msdgweb.mgov.gov.in/esms/sendsmsrequest", $data);

       }
       print_r('Function Called('.$loanee_id.'): '.$mobile_no.'<br>');
   }
   
}


/**
 *
 * @param type $url
 * @param type SMS gateway integration API. 
 */
function post_to_url($url, $data) {
    $fields = '';
    foreach($data as $key => $value) {
        $fields .= $key . '=' . $value . '&';
    }
        
    rtrim($fields, '&');
    $post = curl_init();
    
    curl_setopt($post, CURLOPT_URL, $url);
    curl_setopt($post, CURLOPT_POST, count($data));
    curl_setopt($post, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($post, CURLOPT_RETURNTRANSFER, 1);
    echo $result = curl_exec($post);
    curl_close($post);
}
?>