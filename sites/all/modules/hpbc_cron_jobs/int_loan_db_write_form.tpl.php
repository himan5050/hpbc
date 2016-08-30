<style>
    .container-inline-date .form-item, .container-inline-date .form-item input {
        width: 100px;
        display:inline;
    }

    select { width:120px; }

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

    .maincoldate{margin-top:12px;}
    #edit-date-to-datepicker-popup-0{width:auto;}
</style>

<div id="rec_participant">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" id="wrapper">
        <tr><td align="left" class="tdform-width"><fieldset><legend>Apply Quarter Interest</legend>
                    <table align="left" class="frmtbl">
                        <tr>
                         <td colspan="4" align="right"><div style="margin-right:70px;"><?php print drupal_render($form); ?></div></td>
                        </tr>    
                    </table>
                </fieldset></td>
        </tr>
    </table>
</div>


<?php
$op = $_REQUEST['op'];
if ($op == 'Apply Qaurter Interest on Live Loan Accounts') {
	$file = drupal_get_path('module','hpbc_cron_jobs').'/tmp/int-'.date('Y-m').'.txt';
	if(!file_exists($file)){
		echo '<font color="red"><b>System is in process to calculate interest of all Live Loan Accounts, Please apply interest after some time.</b></font>';
	}else{
		$handle = @fopen($file, "r"); //read line one by one
		$values='';
		$error = 0;
		
		while (!feof($handle)) {
		 	$buffer = fgets($handle, 4096); // Read a line.
		 	$values = explode("|",$buffer);//Separate string by the means of |
		 	
		 	if(isset($values[1])){
		 		$sqld = "UPDATE `tbl_loan_detail` SET  o_principal='" . $values[5] . "',last_interest_calculated='" . $values[4] . "'  WHERE loan_id='" . $values[0] . "'";
		 		$resd = db_query($sqld);
		 		$sqli = "INSERT INTO `tbl_loan_interestld` (`id`, `account_id`, `type`, `amount`, `from_date`, `to_date`, `calculation_date`, `o_principle`, `reason`, `intbatch_id`) VALUES (NULL, '".$values[1]."', 'interest', '".$values[2]."', '".$values[3]."', '".$values[4]."', '".$values[4]."', '".$values[5]."', '212', '0')";
		 		$resi = db_query($sqli);
		 		if(!($resi && $resd)){
		 			$error = 1;
		 		}
		 	}
		}
		if(!$error){
			echo '<font color="green"><b>Interest Applied Successfully.</b></font>';
		}
		
	}
}
?>