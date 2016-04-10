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
    .maincoldate{margin-top:25px;}
    label{float:left; margin-right:5px;margin-top: 5px;}
</style>

<div id="rec_participant">
    <table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper">
        <tr>
            <td align="left" class="tdform-width"><fieldset><legend>Defaulters List Beyond Loan Period</legend>
                    <table align="left" class="frmtbl" >
                        <tr><td></td>
                            <td><?php print drupal_render($form['tehsil_id']); ?></td><td><div class="maincol"><?php print drupal_render($form['type']); ?></div></td><td width="5%">&nbsp;</td></tr><tr><td colspan="7" align="right"><div  style="margin-right:70px;"><?php print drupal_render($form); ?></div></td></tr>    

                    </table>
                </fieldset></td></tr>
    </table>
</div>

<?php
global $base_url, $user;
$op = $_REQUEST['op'];

if ($op == 'Generate') {
    $type = $_REQUEST['type'];
	$tehsil = $_REQUEST['tehsil_id'];
	   
    if ($type != '') {
       // $stdate = strtotime($_REQUEST['startdate']['date']);
      //  $endate = strtotime($_REQUEST['enddate']['date']);
      //  $std = explode('-', $_REQUEST['startdate']['date']);
      //  $startdate = strtotime($std[2] . '-' . $std[1] . '-' . $std[0]);
        
      //  $entd = explode('-', $_REQUEST['enddate']['date']);
      //  $enddate = strtotime($entd[2] . '-' . $entd[1] . '-' . $entd[0]);
        $us = "select current_officeid from tbl_joinings where program_uid='" . $user->uid . "'";
        $usq = db_query($us);
        $usr = db_fetch_array($usq);
        $usid = $usr['current_officeid'];
       // echo 'Current User Official Id = '.$usid; exit;
       
	   /*
        if ($type == 'alr') {
            $sql = "select tbl_loanee_detail.alr_status, 
			              tbl_loan_detail.emi_amount,
			              tbl_loan_detail.ROI,
			              tbl_loanee_detail.loanee_id,
			              tbl_loanee_detail.corp_branch,
			              tbl_scheme_master.scheme_name,
			              tbl_loanee_detail.account_id,
			              tbl_loanee_detail.fname,
			              tbl_loanee_detail.address1,
			              tbl_loanee_detail.address2,
			              tbl_loanee_detail.district,
			              tbl_loanee_detail.tehsil,
			              tbl_loanee_detail.block,
			              tbl_loanee_detail.reg_number,
			              alr.case_no,
			              alr.date 
			      from tbl_loanee_detail
                  INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
                  INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
                  inner join alr on (alr.case_no=tbl_loanee_detail.account_id) 
                  where alr.date >= '" . $stdate . "' and alr.date <= '" . $endate . "' 
				  and tbl_loanee_detail.alr_status=2 and tbl_loanee_detail.corp_branch='" . $usid . "'";
// where alr_status=1";
            // where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."'
            $query = db_query($sql);
            $l = 1;

            $sqlcount = "select COUNT(*) AS count, 
			                    tbl_loanee_detail.alr_status, tbl_loan_detail.emi_amount,
						        tbl_loan_detail.ROI,
						        tbl_loanee_detail.loanee_id,
						        tbl_loanee_detail.corp_branch,
						        tbl_scheme_master.scheme_name,
						        tbl_loanee_detail.account_id,
						        tbl_loanee_detail.fname,
						        tbl_loanee_detail.address1,
						        tbl_loanee_detail.address2,
						        tbl_loanee_detail.district,
						        tbl_loanee_detail.tehsil,
						        tbl_loanee_detail.block,
						        tbl_loanee_detail.reg_number 
						 from   tbl_loanee_detail
                         INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
                         INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
                         inner join alr on (alr.case_no=tbl_loanee_detail.account_id) 
                         where alr.date >= '" . $stdate . "' and alr.date <= '" . $endate . "' 
						       and tbl_loanee_detail.alr_status=2 and 
							   tbl_loanee_detail.corp_branch='" . $usid . "' 
						 GROUP BY tbl_loanee_detail.corp_branch";
// where alr_status=1";

            $rscount = db_query($sqlcount);
            $rscounter = db_fetch_object($rscount);
            if ($rscounter->count == 0 || $rscounter->count == '') {
                echo '<font color="red"><b>No Record found...</b></font>';
            } else {
                $output .= '<table>
		                     <tr class="oddrow">
							 <td colspan="13" align="right">
							 <h2 style="text-align:left;">Defaulters List Beyond Loan Period</h2></td></tr>
		                     <tr><td colspan="13" align="right">
							 <a href="' . $base_url . '/generatedefaulter6pdf.php?op=defaulter&startdate=' . $startdate . '&enddate=' . $enddate . '&type=' . $type . '" target="_blank">
							 <img src="account/images/pdf_icon.gif" style="float:right;" alt="pdf"/></a></td></tr>
		                     <tr><td colspan="13" align="right"></td></tr>
                             <tr><th><b>Sr. No.</b></th>
							 <tr><th><b>Account No.</b></th>
                             <th><b>Scheme Name</b></th>
                             <th><b>Loanee Name</b></th>
                             <th><b>Address</b></th>
                             <th><b>Tehsil</b></th>
                             <th><b>Opening Balance</b></th>
                             <th><b>Interest</b></th>
                             <th><b>Recover amount</b></th>
                             <th><b>Expected Amount</b></th>
                             <th><b>Outstanding Balance</b></th></tr>';


                while ($res = db_fetch_array($query)) {
                    if ($l % 2 == 0) {
                        $cla = "even";
                    } else {
                        $cla = "odd";
                    }

                    $opb = "select sum(amount) as opbal 
			        from tbl_loan_disbursement 
					where loanee_id='" . $res['loanee_id'] . "' group by loanee_id";
                    $opbq = db_query($opb);
                    $opbr = db_fetch_array($opbq);
                    $opi = "select sum(interest_paid) as intpaid, 
			               sum(principal_paid) as recovery 
					from tbl_loan_amortisaton 
					where loanacc_id='" . $res['account_id'] . "' group by loanacc_id";
                    $opiq = db_query($opi);
                    $opir = db_fetch_array($opiq);

                    $teh = "select tehsil_name from tbl_tehsil where tehsil_id='" . $res['tehsil'] . "'";
                    $tehq = db_query($teh);
                    $tehr = db_fetch_array($tehq);

                    $blo = "select block_name from tbl_block where block_id='" . $res['block'] . "'";
                    $bloq = db_query($blo);
                    $blor = db_fetch_array($bloq);

                    $panc = "select panchayt_name from tbl_panchayt where panchayt_id='" . $res['panchayat'] . "'";
                    $pancq = db_query($panc);
                    $pancr = db_fetch_array($pancq);

                    $ss1 = "select min(createdon) as start_date 
			        from tbl_loan_disbursement where loanee_id='" . $res['loanee_id'] . "' group by loanee_id";
                    $q1 = db_query($ss1);
                    $r1 = db_fetch_array($q1);

                    $months = floor((($enddate - $r1['start_date']) % 31556926) / 2629743.83);
                    /* $ex="SELECT MONTHS_BETWEEN('".date('d-m-Y',$r1['start_date'])."','".date('d-m-Y')."') AS MONTHS_BETWEEN FROM dual";
                      $exq=db_query($ex);
                      $exr=db_fetch_array($exq);
                      echo $exr['MONTHS_BETWEEN']; */

                   /*
				    $expted = $months * ($res['emi_amount'] * (($res['ROI']) / 100));
                    $outstanding = $expted - $opir['recovery'];


                    if ($res['alr_status'] == 2) {
                        $output .='<tr class="' . $cla . '"><td>' . $res['account_id'] . '</td><td>' . ucwords($res['scheme_name']) . '</td><td>' . ucwords($res['fname']) . '</td><td>' . $res['address1'] . '<br>' . $res['address2'] . '</td><td>' . ucwords($blor['block_name']) . '</td><td>' . ucwords($tehr['tehsil_name']) . '</td><td>' . ucwords($pancr['panchayt_name']) . '</td><td align="right">' . round($opbr['opbal']) . '</td><td align="right">' . round($opir['intpaid']) . '</td><td align="right">' . round($opir['recovery']) . '</td><td align="right">' . round($expted) . '</td><td align="right">' . round($outstanding) . '</td></tr>';
                    }
                    $l++;
                }
            }
        }


*/

  if ($type == 'defaulter') {
			
      $sql = "select tbl_loan_detail.emi_amount, 
	                    tbl_loan_detail.ROI, 
						tbl_loan_detail.o_principal, 
						tbl_loanee_detail.loanee_id,
						tbl_loanee_detail.corp_branch,
						tbl_scheme_master.scheme_name, 
						tbl_panchayt.panchayt_name, 
						tbl_tehsil.tehsil_name, 
						tbl_block.block_name, 
						tbl_loanee_detail.account_id, 
						tbl_loanee_detail.fname, 
						tbl_loanee_detail.lname, 
						tbl_loanee_detail.fh_name, 
						tbl_loanee_detail.address1, 
						tbl_loanee_detail.address2, 
						tbl_loanee_detail.district, 
						tbl_loanee_detail.tehsil, 
						tbl_loanee_detail.block, 
						tbl_loanee_detail.reg_number 
			     from tbl_loanee_detail 
				 INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat) 
				 INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil) 
				 INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block) 
				 INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number) 
				 INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name) 
                 
				 WHERE tbl_loanee_detail.tehsil = '".$tehsil."' AND
				       DATE_ADD(tbl_loan_detail.sanction_date, INTERVAL 6 YEAR) < '".date('y-m-d')."'"; 
// where alr_status=1";
            // where UNIX_TIMESTAMP(tbl_loan_detail.sanction_date) >= '".$startdate."' and UNIX_TIMESTAMP(tbl_loan_detail.sanction_date)<= '".$enddate."'
            $query = db_query($sql);
            $l = 1;

             $sqlcount = "select COUNT(*) AS count ,
			                  tbl_loan_detail.emi_amount,
			                  tbl_loan_detail.ROI,
                              tbl_loan_detail.o_principal,
			                  tbl_loanee_detail.loanee_id,
			                  tbl_loanee_detail.corp_branch,
			                  tbl_scheme_master.scheme_name,
			                  tbl_panchayt.panchayt_name,
			                  tbl_tehsil.tehsil_name,
			                  tbl_block.block_name,
							  tbl_loanee_detail.lname, tbl_loanee_detail.fh_name, 
			                  tbl_loanee_detail.account_id,
			                  tbl_loanee_detail.fname,
			                  tbl_loanee_detail.address1,
			                  tbl_loanee_detail.address2,
			                  tbl_loanee_detail.district,
			                  tbl_loanee_detail.tehsil,
			                  tbl_loanee_detail.block,
			                  tbl_loanee_detail.reg_number 
			          from tbl_loanee_detail
                      INNER JOIN tbl_panchayt ON (tbl_panchayt.panchayt_id=tbl_loanee_detail.panchayat)
                      INNER JOIN tbl_tehsil ON (tbl_tehsil.tehsil_id=tbl_loanee_detail.tehsil)
                      INNER JOIN tbl_block ON (tbl_block.block_id=tbl_loanee_detail.block)
                      INNER JOIN tbl_loan_detail ON (tbl_loan_detail.reg_number=tbl_loanee_detail.reg_number)
                      INNER JOIN tbl_scheme_master ON (tbl_scheme_master.loan_scheme_id=tbl_loan_detail.scheme_name)
                      WHERE tbl_loanee_detail.tehsil = '".$tehsil."' 
					  AND DATE_ADD(tbl_loan_detail.sanction_date, INTERVAL 6 YEAR) < '".date('y-m-d')."'"; 
// where alr_status=1";
            $rscount = db_query($sqlcount);
            $rscounter = db_fetch_object($rscount);
            if ($rscounter->count == 0 || $rscounter->count == '') {
                //echo '<font color="red"><b>No Record found...</b></font>';
            } else {
			//echo 'Controll goes in right way..';
                $sk = 0;
				$output .= '<table>
		                                        <tr class="oddrow">
												<td colspan="13" align="right">
												<h2 style="text-align:left;">Defaulters List</h2></td></tr>
		                                        <tr><td colspan="13" align="right">
												<a href="' . $base_url . '/generatedefaulter6pdf.php?op=defaulter&tehsil='
												 . $tehsil. '&type=' . $type . '" target="_blank">
												<img src="account/images/pdf_icon.gif" style="float:right;" alt="pdf"/></a></td></tr>
		                                        <tr><td colspan="13" align="right"></td></tr>
                                                <tr>
												<th><b>Sr. No.</b></th>
												<th><b>Account No.</b></th>
                                                <th width="10%"><b>Loanee & Guardian Name</b></th>
												<th><b>Scheme Name</b></th>
                                                <th><b>Address</b></th>
                                                <th><b>Tehsil</b></th>
                                                <th><b>Opening Balance</b></th>
                                                <th><b>Interest</b></th>
                                                <th><b>Recover amount</b></th>
                                                <th><b>Expected Amount</b></th>
                                                <th><b>Outstanding Balance</b></th></tr>';
                $val = 1;
                
				while ($res = db_fetch_array($query)) {
                    if ($l % 2 == 0) {
                        $cla = "even";
                    } else {
                        $cla = "odd";
                    }
					//echo '<br> here we know query is working and Loanee Id fetched = '.$res['fname'];
                    
					$opb = "select sum(amount) as opbal 
						              from tbl_loan_disbursement 
									  where loanee_id='" . $res['loanee_id'] . "' group by loanee_id";
                    $opbq = db_query($opb);
                    $opbr = db_fetch_array($opbq);

                    /* $opi="select sum(interest_paid) as intpaid, 
                      sum(principal_paid) as recovery
                      from tbl_loan_amortisaton
                      where loanacc_id='".$res['account_id']."' group by loanacc_id";
                      $opiq=db_query($opi);
                      $opir=db_fetch_array($opiq); */


                    $opi = "select sum(amount) as intpaid 
							   from tbl_loan_interestld 
							   where account_id='" . $res['account_id'] . "'";
                    $opiq = db_query($opi);
                    $opir = db_fetch_array($opiq);

                    $opr = "select sum(amount) as recovery
							   from tbl_loan_repayment 
							   where loanee_id='" . $res['loanee_id'] . "'";
                    $oprq = db_query($opr);
                    $oprr = db_fetch_array($oprq);


                    $ss = "select max(payment_date) as last_date 
						      from tbl_loan_amortisaton 
							  where loanacc_id='" . $res['account_id'] . "' group by loanacc_id";
                    $q = db_query($ss);
                    $r = db_fetch_array($q);

                    //if ($r['last_date'] != '') {
                        $ld = explode('-', $r['last_date']);
                        $mkt = mktime(0, 0, 0, ($ld[1] + 3), ($ld[2]), ($ld[0]));
                        $newdate = date('Y-m-d', $mkt);
                        $checkdate = strtotime($newdate);
                        $currdate = $enddate;

                        $ss1 = "select min(createdon) as start_date 
								      from tbl_loan_disbursement 
									  where loanee_id='" . $res['loanee_id'] . "' group by loanee_id";
                        $q1 = db_query($ss1);
                        $r1 = db_fetch_array($q1);

                        $months = floor((($enddate - $r1['start_date']) % 31556926) / 2629743.83);
                        //$expted = $months * ($res['emi_amount'] * (($res['ROI']) / 100));
                        $expted = $months * ($res['emi_amount']);
                        $outstanding = $expted - $opir['recovery'];
						//echo 'Is this termination Point????';
						//echo "the current date is = ".$currdate;
                        //if ($currdate >= $checkdate) {
                            /*
							if ($sk == 0) {
                                
                            } */
                            $sk++;
							

                           $output .='<tr class="' . $cla . '">
									            <td>' . $sk . '</td>
											    <td>' . $res['account_id'] . '</td>
												<td width="10%">'.ucwords($res['fname']).' '.ucwords($res['lname']).'<br/><br/> '.ucwords($res['fh_name']).'</td>
												<td>' . $res['scheme_name'] . '</td>
												<td>' . $res['address1'] . '</td>
												<td>' . $res['tehsil_name'] . '</td>
												<td>' . round($opbr['opbal']) . '</td>
												<td>' . round($opir['intpaid']) . '</td>
												<td>' . round($oprr['recovery']) . '</td>
												<td>' . round($expted) . '</td>
												<td>' . $res['o_principal'] . '</td></tr>';
						//}
                        
                   // } // if condition ends...
                    $l++;
                } // while loop ends///
            }// else loop ends..
            if ($val == 1) {
                
            } else {
                echo '<font color="red"><b>No Record found...</b></font>';
            }
        }

       $output .='</table>';
        echo $output;
    }
	
	 
}


?>