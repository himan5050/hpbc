<?php	
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
           

$data = array(
   "username" => "hpgovt",	        // type your assigned username here(for example:"username" => "CDACMUMBAI")

   "password" => "hpdit@1234",	        //type your password

   "senderid" =>"hpgovt",	       //type your senderID

   "smsservicetype" =>"singlemsg",     //*Note*  for single sms enter  îsinglemsgî , for bulk enter ìbulkmsgî

   "mobileno" =>"7307064458",	       //enter the mobile number

   "bulkmobno" => "",	//enter mobile numbers separated by commas for bulk sms otherwise leave it blank

   "content"  => "Attention HBCFDC Kangra Loan A/c no. K-20320. Your EMI for the month of Jan-2017 is due from 1st of this month onwards.Your payable outstanding amount is 475332.Please ignore this message if EMI already paid.For help, contact on 01892-264334,262282" 	      //type the message.

 );

        post_to_url("http://msdgweb.mgov.gov.in/esms/sendsmsrequest", $data);

 ?>
			
