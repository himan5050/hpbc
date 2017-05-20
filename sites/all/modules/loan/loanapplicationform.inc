<?php

function apply_for_loan($loanaccountid = '',$mg = ''){
	global $base_root;
	global $base_path;
	global $base_url;
	
	global $user;
	$role = getRole($user->uid);
	$selected = '';
	$gnumval = 1;
	$imagepath = drupal_get_path('theme','scst')."/images/";
	//print_r($_POST);exit;
	if(!$mg)
		$mg = isset($_POST['mg'])?$_POST['mg']:$mg;


	$fh_name = isset($_POST['p']['fh_name'])?$_POST['p']['fh_name']:'';
	$fname = isset($_POST['p']['fname'])?$_POST['p']['fname']:'';
	$lname = isset($_POST['p']['lname'])?$_POST['p']['lname']:'';
	$dob = isset($_POST['p']['dob'])?$_POST['p']['dob']:'';
	$gender = isset($_POST['p']['gender'])?$_POST['p']['gender']:'';
	$religion = isset($_POST['p']['religion'])?$_POST['p']['religion']:'';
	$category = isset($_POST['p']['category'])?$_POST['p']['category']:'';
	$caste = isset($_POST['p']['caste'])?$_POST['p']['caste']:'';
	$familyincome = isset($_POST['p']['family_income'])?$_POST['p']['family_income']:'';
	$incomecat = isset($_POST['p']['income_cat'])?$_POST['p']['income_cat']:'';
	$UID = isset($_POST['p']['UID'])?$_POST['p']['UID']:'';
	$email = isset($_POST['p']['email'])?$_POST['p']['email']:'';
	$mobile = isset($_POST['p']['mobile'])?$_POST['p']['mobile']:'';
	$phone = isset($_POST['p']['phone'])?$_POST['p']['phone']:'';
	$disability = isset($_POST['p']['disability'])?$_POST['p']['disability']:'';
	$qualification = isset($_POST['p']['education'])?$_POST['p']['education']:'';
	
	$addrtype = isset($_POST['p']['address_type'])?$_POST['p']['address_type']:'';
	$village = isset($_POST['p']['village'])?$_POST['p']['village']:'';
	$address1 = isset($_POST['p']['address1'])?$_POST['p']['address1']:'';
	$address2 = isset($_POST['p']['address2'])?$_POST['p']['address2']:'';
	$postoffice = isset($_POST['p']['post_office'])?$_POST['p']['post_office']:'';
	$state = isset($_POST['p']['state'])?$_POST['p']['state']:'';
	$district = isset($_POST['p']['district'])?$_POST['p']['district']:'';
	$tehsil = isset($_POST['p']['tehsil'])?$_POST['p']['tehsil']:'';
	$block = isset($_POST['p']['block'])?$_POST['p']['block']:'';
	$panchayat = isset($_POST['p']['panchayat'])?$_POST['p']['panchayat']:'';
	$pin = isset($_POST['p']['pincode'])?$_POST['p']['pincode']:'';
	
	$scheme = isset($_POST['l']['scheme_name'])?$_POST['l']['scheme_name']:'';
	$projectcost = isset($_POST['l']['project_cost'])?$_POST['l']['project_cost']:'';
	$workplace = isset($_POST['l']['work_place'])?$_POST['l']['work_place']:'';
	$running_loan = isset($_POST['l']['running_loan'])?$_POST['l']['running_loan']:'';
	$loan_amount = isset($_POST['l']['loan_amount'])?$_POST['l']['loan_amount']:'';
	$max_loan = isset($_POST['max_loan'])?$_POST['max_loan']:'';
	$branch = isset($_POST['p']['corp_branch'])?$_POST['p']['corp_branch']:'';
	$capital_subsidy = isset($_POST['l']['capital_subsidy'])?$_POST['l']['capital_subsidy']:'';
	$bank = isset($_POST['l']['bank'])?$_POST['l']['bank']:'';
	$bank_branch = isset($_POST['l']['bank_branch'])?$_POST['l']['bank_branch']:'';
	$bank_acc_no = isset($_POST['l']['bank_acc_no'])?$_POST['l']['bank_acc_no']:'';
	$disbursed_amount = isset($_POST['l']['disbursed_amount'])?$_POST['l']['disbursed_amount']:'';
	$disbursed_date = isset($_POST['l']['disbursed_date'])?$_POST['l']['disbursed_date']:'';
	$b_ROI = isset($_POST['l']['b_ROI'])?$_POST['l']['b_ROI']:'';
	$refno = isset($_POST['refno'])?$_POST['refno']:'';
	
/*	$gtype = isset($_POST['g']['gtype'])?$_POST['g']['gtype']:'';
	$gname = isset($_POST['g']['gname'])?$_POST['g']['gname']:'';
	$gnature = isset($_POST['g']['gnature'])?$_POST['g']['gnature']:'';
	$gamount = isset($_POST['g']['gamount'])?$_POST['g']['gamount']:'';
	$unit_name = isset($_POST['g']['unit_name'])?$_POST['g']['unit_name']:'';
	$basic_pay = isset($_POST['g']['basic_pay'])?$_POST['g']['basic_pay']:'';
	$total_pay = isset($_POST['g']['total_pay'])?$_POST['g']['total_pay']:'';
*/
	$superannuation_date = isset($_POST['g']['superannuation_date'])?$_POST['g']['superannuation_date']:'';
	$address = isset($_POST['g']['address'])?$_POST['g']['address']:'';
	$designation = isset($_POST['g']['designation'])?$_POST['g']['designation']:'';
	$office_addr_certified = isset($_POST['g']['office_addr_certified'])?$_POST['g']['office_addr_certified']:'';
	
	$docs = '';
	$licenseno = isset($_POST['licenseno'])?$_POST['licenseno']:'';
	$licenseexpire = isset($_POST['licenseexpiry'])?$_POST['licenseexpiry']:'';

	if(isset($_POST['updateloan']))
	{
		if(!isset($_POST['lid']))
			$error = updateLoanAccount('');
		if(isset($_POST['lid']))
		{
			$query = "SELECT reg_number FROM tbl_loan_detail WHERE loan_id = ".$_POST['lid'];
			$res = db_query($query);
			$l = db_fetch_object($res);
			$error = updateLoanAccount($l->reg_number,$mg);
		}		
	}	 
	if($loanaccountid) // edit form
	{
        $role = getRole($user->uid);

		$query = "SELECT ld.*,ad.*,sm.loan_type,sm.capital_subsidy as cs FROM tbl_loan_detail ld, tbl_loanee_detail ad, tbl_scheme_master sm WHERE ld.reg_number = ad.reg_number AND ld.scheme_name = sm.loan_scheme_id AND loan_id = $loanaccountid";
		$res = db_query($query);
		$s = db_fetch_object($res);
		if(isLoanSanctioned($loanaccountid))
		{
			if($s->loan_type != 147 && !$mg)
			{
				form_set_error('','Sorry! You cant not edit the loan application.Please contact to loan admin section.');
				drupal_goto('loan/listloans');
			}
			if($role == 5 || $role == 13 || $role == 18)
			{
			}elseif(!$mg){
				form_set_error('','Sorry! You cant not edit the loan application.Please contact to loan admin section.');
				drupal_goto('loan/listloans');
			}
		}
		$lstatus = $s->loan_status;
		$query = "SELECT * FROM tbl_loan_documents WHERE loanee_id = $s->loanee_id";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$d[$row->document_id] = $row->loan_file;
		}
	if(($loanaccountid && !isset($_POST['lid'])) || (($role == 5 || $role == 13 || $role == 18) && $s->loan_type == 147 && $sanctioned_date != '0000-00-00') || $mg) // edit form
	{
		$fh_name = $s->fh_name;
		$fname = $s->fname;
		$lname = $s->lname;
		$dob = date('d-m-Y',strtotime($s->dob));
		$gender = $s->gender;
		$religion = $s->religion;
		$category = $s->category;
		$caste = $s->caste;
		$familyincome = $s->family_income;
		$incomecat = $s->income_cat;
		$UID = $s->UID;
		$email = $s->email;
		$mobile = $s->mobile;
		$phone = $s->phone;
		$disability = $s->disability;
		$qualification = $s->education;
		
		$addrtype = $s->address_type;
		$village = $s->village;
		$address1 = $s->address1;
		$address2 = $s->address2;
		$postoffice = $s->post_office;
		$state = $s->state;
		$district = $s->district;
		$tehsil = $s->tehsil;
		$block = $s->block;
		$panchayat = $s->panchayat;
		$pin = $s->pincode;
		
		$scheme = $s->scheme_name;
		$projectcost = $s->project_cost;
		$workplace = $s->work_place;
		$running_loan = $s->running_loan;
		//$loan_amount = $s->loan_amount;
		$loan_amount = $s->loan_requirement;
		$branch = $s->corp_branch;
		$capital_subsidy = $s->capital_subsidy;
		$bank = ($s->bank)?$s->bank:$bank;
		$bank_branch = ($s->bank_branch)?$s->bank_branch:$bank_branch;
		$bank_acc_no = ($s->bank_acc_no)?$s->bank_acc_no:$bank_acc_no;
		$disbursed_amount = ($s->disbursed_amount)?$s->disbursed_amount:$disbursed_amount;
		$disbursed_date = ($s->disbursed_date != '0000-00-00')?date("d-m-Y",strtotime($s->disbursed_date)):$disbursed_date;
		$b_ROI = ($s->b_ROI != '0.00')?$s->b_ROI:$b_ROI;
		$licenseno = $s->licenseno;
		$refno = $s->refno;

		$licenseexpire = ($s->licenseexpiry == '0000-00-00')?'':date("d-m-Y",strtotime($s->licenseexpiry));
		$sanctioned_date = ($s->sanction_date != '0000-00-00')?date("d-m-Y",strtotime($s->sanction_date)):'0000-00-00';
	}
		$query = "SELECT project_cost, promoter_share FROM tbl_scheme_master WHERE loan_scheme_id = '".$scheme."' AND status = 167 AND active = 1";
		$res = db_query($query);
		$row = db_fetch_object($res);
		//$max_loan = $row->project_cost -($row->project_cost * $row->promoter_share / 100);
		$max_loan = $s->loan_requirement;
	}





	$array = explode('/',$_GET['q']);
	$addedit = ($loanaccountid)?'Edit':''; 
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan(s)', 'loan/listloans');
	$breadcrumb[] = "$addedit Loan Application Form";
	drupal_set_breadcrumb($breadcrumb);
$output = <<<EOD
	<script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.core.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/libraries/jquery.ui/ui/minified/ui.datepicker.min.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/lib/jquery.timeentry.pack.js?K"></script>
    <script type="text/javascript" src="$base_url/sites/all/modules/date/date_popup/date_popup.js?K"></script>
<script>
	$(function() {
		//$( "#fname" ).focus();
		datepicklen = $('#gnumid').val();
		for(i = 0;i<=datepicklen;i++)
		{
			$( "#datepicker"+i ).datepicker();
			$( "#datepicker"+i ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		}
		$( "#disbursed_dateid" ).datepicker();
		$( "#disbursed_dateid" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		$( "#licenseexpiry" ).datepicker();
		$( "#licenseexpiry" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
	});
	
	$(function() {
		$( "#dob" ).datepicker();
		$( "#dob" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
		
		checkrunningloan();
			
		$('#gsubmit').click(function() {
			gnum = parseInt($('#gnumid').val()) - 1;
			if ( $.browser.msie )
			{
				gcontent = $('#blankg').html().replace('id=datepicker','id=datepicker'+gnum);
			}else{
				gcontent = $('#blankg').html().replace('id="datepicker"','id="datepicker'+gnum+'"');
			}
			//gcontent = gc.replace("datepicker","datepicker0");
			fcontent = gcontent.replace('class="hasDatepicker"','');
			ffcontent = fcontent.replace('oyes',gnum);
			fffcontent = ffcontent.replace('ono',gnum);
			$('#gdiv').append('<br><div id="gdetailid'+gnum+'"><h2><input type="checkbox" value="'+gnum+'" name="gdetail'+gnum+'" id="gid'+gnum+'" checked="checked" onClick="openGuarantor('+gnum+');">Guarantor Detail</h2><table width="100%" cellpadding="2" cellspacing="1" border="0">'+fffcontent+'</table></div>');
			//alert("hi");
			
			$("#datepicker"+gnum).datepicker();
			$("#datepicker"+gnum).datepicker( "option", "dateFormat", "dd-mm-yy" );
			
			$('#gnumid').val(parseInt($('#gnumid').val())+1);
		});
		
EOD;
if($error)
{
	$output .= $error;
}
if($mg)
{
	$output .= '$("table#wrapper21 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper21 select").attr("disabled","true");';
	$output .= '$("table#wrapper21 input[type=radio]").attr("disabled","true");';
	$output .= '$("table#wrapper22 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper22 select").attr("disabled","true");';
	$output .= '$("table#wrapper23 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper23 select").attr("disabled","true");';
	$output .= '$("table#wrapper24 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper24 select").attr("disabled","true");';
	$output .= '$("table#wrapper26 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper27 input[type=file]").attr("disabled","true");';
	$output .= '$("table#wrapper27 span").attr("style","display:none;");';
	$output .= '$("#csid").attr("enabled","true");';
}

if(($role == 5 || $role == 13 || $role == 18) && $s->loan_type == 147 && $sanctioned_date != '0000-00-00')
{
	$output .= '$("table#wrapper21 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper21 select").attr("disabled","true");';
	$output .= '$("table#wrapper21 input[type=radio]").attr("disabled","true");';
	$output .= '$("table#wrapper22 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper22 select").attr("disabled","true");';
	$output .= '$("table#wrapper23 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper23 select").attr("disabled","true");';
	$output .= '$("table#wrapper24 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper24 select").attr("disabled","true");';
	$output .= '$("table#wrapper26 input[type=text]").attr("disabled","true");';
	$output .= '$("table#wrapper27 input[type=file]").attr("disabled","true");';
	$output .= '$("table#wrapper27 span").attr("style","display:none;");';
	$output .= '$("#csid").attr("enabled","true");';
}
$output .= <<<EOD
		
	});
</script>

<script type="text/javascript" src="path-to-file/thickbox.js"></script>
<div id="errorid" class="messages error" style="display:none;"></div>

<div id="form-container">
<form action="" name="loanapplicationform" method="post" enctype="multipart/form-data">
<table width="100%" cellspacing="2" cellpadding="1" border="0">
<tr class="oddrow">

	<td align="center" colspan=4><h2> $addedit Loan Application Form </h2></td>   

</tr>
</table>
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper21" style="border-left:#CCC 1px solid; border-right:#CCC 1px solid; border-top:#CCC 1px solid;">
<tr class="oddrow">
	<td align="center" colspan="4"><h2>Personal Detail</h2></td>   
</tr>

<tr class="evenrow">
	<td colspan="3"><div class="loantext">Any Running Loan?:</div>
		<div class='loanform'>
EOD;
		$yesselected = 'checked="checked"';
		if($running_loan == 1)
			$yesselected = 'checked="checked"';
		if($running_loan == 0)
			$noselected = 'checked="checked"';

		$output .= '<input type="radio" name="p[running_loan]" id="loanyes" value="1" '.$yesselected.' onclick="checkrunningloan();" /> Yes ';
		$output .= '<input type="radio" name="p[running_loan]" id="loanno" value="0" '.$noselected.' onclick="checkrunningloan();" /> No';
$output .= <<<EOD
	</div><div id="loan_error"></div></td></tr>
                
                
<tr class="evenrow">
	<td colspan="3"><div class="loantext">Passport Size Image:</div>
		<div class='loanform'>
EOD;
		$yesselected = 'checked="checked"';
		if($running_loan == 1)
			$yesselected = 'checked="checked"';
		if($running_loan == 0)
			$noselected = 'checked="checked"';

		$output .= '<input type="file" name="p[fimage]" id="loanyes" value="1" '.$yesselected.' onclick="checkrunningloan();" />';
                $output .= <<<EOD
	</div><div id="loan_error"></div></td></tr>
                
                
                
                
 </table>
 <div id="applyloanid">
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper22" style="border-top:0px;">
<tr class="oddrow">
	<td><div class="loantext">First Name: <span title="This field is required." class="form-required">*</span></div><div class='loanform'><input type="text" name="p[fname]" id="fname" value="$fname" onkeypress="return alphabet(event);" maxlength="45" /></div></td>
	<td align="left"><div class="loantext">Last Name: <span title="This field is required." class="form-required">*</span></div>
	<div class='loanform'><input type="text" name="p[lname]" id="lname" value="$lname"  onkeypress="return alphabet(event);" maxlength="45"/></div></td>
</tr>

<tr class="evenrow">

	<td><div class="loantext">Father / Husband Name: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[fh_name]" id="fh_name" value="$fh_name" onkeypress="return alphabet(event);" maxlength="45" /></div>
	</td>
    <td><div class="loantext">Qualification: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[education]" id="qualificationid" value="$qualification" onKeyPress="return alphanumeric(event);" maxlength="45" /></div></td>

</tr>
<tr class="oddrow">
	<td><div class="loantext">DOB: <span title="This field is required." class="form-required">*</span></div>
		<div id="hearing_id-datepicker-popup-0-wrapper"  class="loanform">
			<input type="text" name="p[dob]" id="dob" value="$dob" readonly="readonly" />
			<div class="description"> Format: dd-mm-yyyy</div>
		</div>	</td>
	<td><div class="loantext">Gender: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
EOD;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Gender' ORDER BY l.lookup_name";
		$res = db_query($query);
		$count = 0;
		while($row = db_fetch_object($res))
		{
			$count++;
			if($gender == $row->lid)
			{
				$selected = 'checked="checked"';
			}else{
				if($count == 1)
					$selected = 'checked="checked"';
			}
			$output .= '<input type="radio" name="p[gender]" value="'.$row->lid.'" '.$selected.'>&nbsp;'.$row->lname.'  ';
			$selected = '';
		}

$output .= <<<EOD
	</div></td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">Religion: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
	<select name="p[religion]" id="religionid" onChange="getLowerLevelData(this.value,'$base_url','casteid','caste','religionp');">
	<option value="">Select</option>
EOD;
	
		$counter = 0;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Religion' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$counter++;
			if($counter == 1)
				$rid = $row->lid;
			if($religion == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
			$selected = '';
		}

			
$output .= <<<EOD
		</select></div>&nbsp;<span id="religionp"></span>	</td>
	<td><div class="loantext">Category:</div>
		<div class='loanform'>
	<select name="p[category]" id="categoryid">
	<option value="">Select</option>
EOD;
	
		$counter = 0;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Caste Category' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$counter++;
			if($counter == 1)
				$rid = $row->lid;
			if($category == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}

			
$output .= <<<EOD
		</select>
	
	</div></td>
</tr>

<tr class="oddrow">
	<td><div class="loantext">Caste: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[caste]" id="casteid">
		<option value="">Select</option>
EOD;
		if($religion)
		{	
			$query = "SELECT cast_id, cast_name FROM tbl_cast WHERE status = 1 AND religion_id = $religion";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				if($caste == $row->cast_id)
					$selected = 'selected="selected"';
				$output .= '<option value="'.$row->cast_id.'" '.$selected.'>'.$row->cast_name.'</option>';
				$selected = '';
			}
		}
$output .= <<<EOD
		</select>	</div></td>
	<td><div class="loantext">Annual Family Income: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[family_income]" id="fincomeid" value="$familyincome" maxlength="11" onkeypress="return paypaymain_custom(event,'fincomeid',11);" /><br /><div class="description"> ( in Rs )</div></div></td>
</tr>

<tr class="evenrow">
	<td><div class="loantext">Income Category: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[income_cat]" id="income_catid">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Income Category' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($incomecat == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>	</td>
	<td><div class="loantext">UID(Adhar Card No.):</div>
		<div class='loanform'><input type="text" name="p[UID]" id="UID" value="$UID" maxlength="16" onkeypress="return alphanumeric(event);" /></div></td>
</tr> 
<tr class="oddrow">
	<td><div class="loantext">Email: </div>
		<div class='loanform'><input type="text" name="p[email]" id="emailid" value="$email" maxlength="200" onkeypress="return emailvali(event);" /></div></td>
	<td><div class="loantext">Mobile No.: <span title="This field is required.">*</span></div>
		<div class='loanform'><input type="text" name="p[mobile]" id="mobileid" value="$mobile" maxlength="10" onkeypress="return fononlyn(event);" /></div></td>
</tr>

<tr class="evenrow">
	<td><div class="loantext">Disability:</div>
		<div class='loanform'>
        <!--<input type="text" name="p[disability]" id="disabilityid" value="$disability" onkeypress="return alphanumeric(event);" />-->
		<select name="p[disability]" id="disabilityid">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Disability' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($disability == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select>
        
        <div class="description">( Note : Disability should not be less than 40% )</div></div></td>
        <td><div class="loantext">Phone: <span title="This field is required.">*</span></div><div class='loanform'><input type="text" name="p[phone]" id="phoneid" value="$phone" onkeypress="return validPhone(event);" maxlength="15" /></div></td>
	<!--<td><div class="loantext">Qualification: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[education]" id="qualificationid" value="$qualification" onKeyPress="return alphanumeric(event);" maxlength="45" /></div></td>-->
</tr>
</table>
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper23">
<tr class="oddrow">
	<td align="center" colspan=4><h2>Address Detail</h2></td>   
</tr>
<tr class="evenrow">
	<td><div class="loantext">Address Type: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[address_type]" id="addresstypeid" onchange="setRuralUrban(this.value);">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Address Type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($addrtype == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>
	</td>
	<td><div class="loantext">Address Line1: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[address1]" id="address1id"  maxlength="100" value="$address1" onkeypress="return addressvalidation(event);"></div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Address Line2: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" value="$address2" name="p[address2]" id="address2id"  maxlength="100" onkeypress="return textonlywithdotnemax(event,'address2id','100');"></div></td>
	<td><div class="loantext">State: <span title="This field is required." class="form-required">*</span></div>
				<div class='loanform'>
		<select name="p[state]" id="stateid" onChange="getLowerLevelData(this.value,'$base_url','districtid','district','statep');">
		<option value="">Select</option>
EOD;
		$counter = 0;
		$query = "SELECT state_id, state_name FROM tbl_dsjestate WHERE status = 1 ORDER BY state_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$counter++;
			if($counter == 1)
				$did = $row->state_id;
			if($state == $row->state_id)

				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->state_id.'" '.$selected.'>'.ucwords($row->state_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select></div>&nbsp;<span id="statep"></span><br>
</td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">District: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[district]" id="districtid" onChange="getLowerLevelData(this.value,'$base_url','tehsilid','tehsil','districtp');">
		<option value="">Select</option>
EOD;
		$counter = 0;
		if($state)
		{
            $query = "SELECT district_id, district_name FROM tbl_district WHERE status = 1 AND state_id = $state ORDER BY district_name";
            $res = db_query($query);
            while($row = db_fetch_object($res))
            {
                $counter++;
                if($counter == 1)
                    $did = $row->district_id;
                if($district && $district == $row->district_id)
                    $selected = 'selected="selected"';
                $output .= '<option value="'.$row->district_id.'" '.$selected.'>'.$row->district_name.'</option>';
                $selected = '';
            }
        }
$output .= <<<EOD
		</select></div>&nbsp;<span id="districtp"></span><br>
	</td>
	<td><div class="loantext">Tehsil: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[tehsil]" id="tehsilid" onChange="getLowerLevelData(this.value,'$base_url','blockid','block','tehsilp');">
		<option value="">Select</option>
EOD;
		if($district)
		{
			$query = "SELECT tehsil_id, tehsil_name FROM tbl_tehsil WHERE status = 1 AND district_id = $district ORDER BY tehsil_name";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				if($tehsil && $tehsil == $row->tehsil_id)
					$selected = 'selected="selected"';
				$output .= '<option value="'.$row->tehsil_id.'" '.$selected.'>'.$row->tehsil_name.'</option>';
				$selected = '';
			}
		}
$output .= <<<EOD
		</select></div>&nbsp;<span id="tehsilp"></span><br>
	</td></tr><tr class="oddrow">
	<td><div class="loantext">Block: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[block]" id="blockid" onChange="getLowerLevelData(this.value,'$base_url','panchayatid','panchayat','blockp');">
		<option value="">Select</option>
EOD;
		if($tehsil)
		{
			$query = "SELECT block_id, block_name FROM tbl_block WHERE status = 1 AND tehsil_id = $tehsil ORDER BY block_name";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				if($block && $block == $row->block_id)
					$selected = 'selected="selected"';
				$output .= '<option value="'.$row->block_id.'" '.$selected.'>'.$row->block_name.'</option>';
				$selected = '';
			}
		}
$output .= <<<EOD
		</select></div>&nbsp;<span id="blockp"></span>
	</td>
	<td><div class="loantext">Panchayat: <span title="This field is required.">*</span></div>
		<div class='loanform'>
		<select name="p[panchayat]" id="panchayatid">
		<option value="">Select</option>
EOD;
		if($block)
		{
			$query = "SELECT panchayt_id,  panchayt_name FROM tbl_panchayt WHERE status = 1 AND block_id = $block ORDER BY panchayt_name";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				if($panchayat && $panchayat == $row->panchayt_id)
					$selected = 'selected="selected"';
				$output .= '<option value="'.$row->panchayt_id.'" '.$selected.'>'.$row->panchayt_name.'</option>';
				$selected = '';
			}
		}
$output .= <<<EOD
		</select></div>
	</td></tr><tr class="evenrow">
    <td><div class="loantext">Post Office:</div>
		<div class='loanform'><input type="text" name="p[post_office]" id="postofficeid" value="$postoffice" maxlength="45" onkeypress="return alphabet(event);" /></div></td>

<td><div class="loantext">Village:</div>
		<div class='loanform'><input type="text" name="p[village]" id="villageid" value="$village" onBlur="calculatePromotorshare();" maxlength="45" onkeypress="return alphabet(event);" /></div></td></tr><tr class="oddrow">
	<td colspan="2"><div class="loantext">Pin Code: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="p[pincode]" id="pinid" maxlength="6" value="$pin" onKeyPress="return fononlyn(event);" /></div></td>
</tr>
</table>
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper24">
<tr class="oddrow">

	<td align="center" colspan=4><h2>Project Detail</h2></td>   

</tr>
<tr class="evenrow">
	<td><div class="loantext">Scheme: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="l[scheme_name]" id="schemeid" onChange="getProjectCost(this.value,'$base_url','scheme');">
		<option value="">Select</option>
EOD;
		if($loanaccountid)
        	$cond = "";
        else
        	$cond = " AND active = 1 ";   	
		$query = "SELECT loan_scheme_id, scheme_name FROM tbl_scheme_master WHERE status = 167 $cond ORDER BY scheme_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($scheme == $row->loan_scheme_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->loan_scheme_id.'" '.$selected.'>'.ucwords($row->scheme_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select></div>&nbsp;<span id="schemep"></span>
		<br /><br /><div id="allshareid" class="description"></div>
	</td>
	<td><div class="loantext">Project Cost: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="l[project_cost]" id="prjcostid" maxlength="11" value="$projectcost" onkeypress="return paypaymain_custom(event,'prjcostid',11);" /></div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Branch Office: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
		<select name="p[corp_branch]" id="corp_branchid">
		<option value="">Select</option>
EOD;
		$query = "SELECT corporation_id, corporation_name FROM tbl_corporations WHERE status = 1 ORDER BY corporation_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($branch == $row->corporation_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->corporation_id.'" '.$selected.'>'.ucwords($row->corporation_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select></div>
	</td>
	<td><div class="loantext">Place of Work:</div>
		<div class='loanform'><input type="text" name="l[work_place]" value="$workplace" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
</tr>
<tr class="evenrow">

	<td><div class="loantext">Max Loan Amount: <span title="This field is required.">*</span></div>
		<div class='loanform'><input type="text" name="l[loan_amount]" id="loanrequirementid" readonly="readonly" value="$loan_amount" /><input type="hidden" name="max_loan" id="maxloanid" value="$max_loan" onkeypress="return paypaymain_custom(event,'loanrequirementid',11);"></div></td>
EOD;
        if($s->cs)
        {
        	if($role == 18 || $role == 13)
            {
                $output .= <<<EOD
                    <td id='csid'><div class="loantext">Capital Subsidy:</div><div class='loanform'><input type="text" name="l[capital_subsidy]" id="capitalid" value="$capital_subsidy" maxlength="11" onkeypress="return paypaymain_custom(event,'capitalid',11);" /></div></td>
EOD;
			}else{
            	if($s->capital_subsidy)
                {
                    $output .= <<<EOD
                        <td id='csid'><div class="loantext">Capital Subsidy:</div><div class='loanform'><input type="text" name="l[capital_subsidy]" id="capitalid" value="$capital_subsidy" readonly="readonly" /></div></td>
EOD;
                }
            }
        }
	
	$output .= <<<EOD
</tr>
</table>
EOD;

	if($loanaccountid)
    {
        $cl = 'oddrow';
        if(($role == 5 || $role == 13 || $role == 18) && $s->loan_type == 147 && $sanctioned_date != '0000-00-00')
        {
		$output .= <<<EOD
		<br>
		<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper28">
		<tr class="oddrow">
			<td align="center" colspan=4><h2>Bank Detail</h2></td>   
		</tr>
EOD;
        	$cl = 'evenrow';
        
                $output .= <<<EOD
<tr class="oddrow">
	<td><div class="loantext">Bank: <span title="This field is required.">*</span></div>
		<div class='loanform'>		
        <select name="l[bank]" id="bankid" onChange="getLowerLevelData(this.value,'$base_url','bank_branchid','bank_branch','banckp');">
		<option value="">Select</option>
EOD;
		$query = "SELECT bank_id, bank_name FROM tbl_bank  WHERE status = 1 ORDER BY bank_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($bank == $row->bank_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->bank_id.'" '.$selected.'>'.ucwords($row->bank_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select></div>&nbsp;<span id="banckp"></span>
</td>
	<td><div class="loantext">Bank Branch: <span title="This field is required.">*</span></div>
		<div class='loanform'>
		<select name="l[bank_branch]" id="bank_branchid">
		<option value="">Select</option>
EOD;
		if($bank)
        {
            $query = "SELECT bankbranch_id, bankbranch_name FROM tbl_bankbranch WHERE bank_id = $bank AND status = 1 ORDER BY bankbranch_name";
            $res = db_query($query);
            while($row = db_fetch_object($res))
            {
                if($bank_branch == $row->bankbranch_id)
                    $selected = 'selected="selected"';
                $output .= '<option value="'.$row->bankbranch_id.'" '.$selected.'>'.ucwords($row->bankbranch_name).'</option>';
                $selected = '';
            }
        }
$output .= <<<EOD
		</select></div>
	</td>
</tr>
                <tr class="evenrow">
                    <td><div class="loantext">A/C No.: <span title="This field is required.">*</span></div><div class='loanform'><input type="text" name="l[bank_acc_no]" id="bank_acc_noid" value="$bank_acc_no" maxlength="15" onkeypress="return fononlyn(event);" /></div></td>
                    <td><div class="loantext">Disbursed Amount: <span title="This field is required.">*</span></div><div class='loanform'><input type="text" name="l[disbursed_amount]" id="disbursed_amountid" value="$disbursed_amount" maxlength="11" onkeypress="return paypaymain_custom(event,'disbursed_amountid',11);" /></div></td>
                </tr>
                <tr class="oddrow">
                    <td><div class="loantext">Disbursed Date: <span title="This field is required.">*</span></div><div class='loanform'><input type="text" name="l[disbursed_date]" id="disbursed_dateid" value="$disbursed_date" /></div></td>
                    <td><div class="loantext">ROI: <span title="This field is required.">*</span></div><div class='loanform'><input type="text" name="l[b_ROI]" id="b_ROIid" value="$b_ROI" maxlength="6" onkeypress="return paypaymain_custom(event,'b_ROIid',6);" /></div></td>
                </tr>
EOD;
        }
$output .= <<<EOD
</table>
EOD;
    }

$output .= <<<EOD
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper25">
<tr class="oddrow">

	<td align="center" colspan=4><h2>&nbsp;Guarantor Detail</h2> ( Note: <i>Please make sure that the check box for all guarantor details are checked. Unckeck to remove Guarantor</i> )</td>   

</tr>
</table>
<br>
<div id='gdiv'>
EOD;

$counter = 0;
if($loanaccountid && !isset($_POST['lid']))
{
	$sql = "SELECT * FROM tbl_guarantor_detail WHERE loanee_id = $s->loanee_id";
		$res = db_query($sql);
		while($g = db_fetch_object($res))
		{
			$yesselected = '';
			$noselected = '';
            if($g->superannuation_date != '0000-00-00')
                $superannuation = date('d-m-Y',strtotime($g->superannuation_date));
            else
            	$superannuation = '';
			$output .= '<div id="gdetailid'.$counter.'">';
			$output .= <<<EOD
				<h2><input type="checkbox" value="1" name="gdetail$counter" id="gid$counter" checked="checked" onClick="openGuarantor($counter);"><input type="hidden" value="$g->gid" name="guid$counter" id="guidid$counter">Guarantor Detail </h2>
			<table width="100%" cellpadding="2" cellspacing="1" border="0" id="g1">
			<tr class="oddrow">
				<td><div class="loantext">Guarantor Type: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
					<select name="g[gtype][$counter]">
					<option value="">Select</option>
EOD;
						
					$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Guarantor Type' ORDER BY l.lookup_name";
					$resl = db_query($query);
					while($row = db_fetch_object($resl))
					{
						if($g->gtype == $row->lid)
							$selected = 'selected="selected"';
						$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
						$selected = '';
					}
				
			$output .= <<<EOD
					</select>
                    </div>
				</td>
				<td><div class="loantext">Nature of Guaranty: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'>
					<select name="g[gnature][$counter]">
					<option value="">Select</option>
EOD;
						
					$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Nature Of Guaranty' ORDER BY l.lookup_name";
					$resl = db_query($query);
					while($row = db_fetch_object($resl))
					{
						if($g->gnature == $row->lid)
							$selected = 'selected="selected"';
						$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
						$selected = '';
					}

				
			$output .= <<<EOD
					</select></div>
				</td>
			</tr>
			
			<tr class="evenrow">
				<td><div class="loantext">Guarantor Name: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[gname][$counter]" value="$g->gname" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
				<td><div class="loantext">Amount of Guaranty: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[gamount][$counter]" value="$g->gamount" maxlength="11" onkeypress="return paypay(event);" /></div></td>
			</tr>
			<tr class="oddrow">
				<td><div class="loantext">Name of Unit: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[unit_name][$counter]" value="$g->unit_name" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
				<td><div class="loantext">Basic Pay: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[basic_pay][$counter]" value="$g->basic_pay" maxlength="11" onkeypress="return paypay(event);" /></div></td>
			</tr>
			<tr class="evenrow">
				<td><div class="loantext">Total Pay: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[total_pay][$counter]" value="$g->total_pay" maxlength="11" onkeypress="return paypay(event);" /></div></td>
				<td><div class="loantext">Superannuation Date:</div>
		
				<div id="hearing_id-datepicker-popup-0-wrapper"  class="loanform">
				<input type="text" value="$superannuation" id="datepicker0" name="g[superannuation_date][$counter]" maxlength="10" readonly="readonly"/>

			<div class="description"> Format: dd-mm-yyyy</div>
			</div>
				</td>
			</tr>
			<tr class="oddrow">
				<td><div class="loantext">Address: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><textarea name="g[address][$counter]" maxlength="100"  onkeypress="return addressvalidation(event);">$g->address</textarea></div></td>
				<td><div class="loantext">Designation: <span title="This field is required." class="form-required">*</span></div>
		<div class='loanform'><input type="text" name="g[designation][$counter]" value="$g->designation" maxlength="45" onkeypress="return alphanumeric(event);" /></div></td>
			</tr>
			<tr class="evenrow">
				<td><div class="loantext">Office Address Certified?:</div></td>
				<td align="left">
                <div class="loanform">
EOD;
					if($g->office_addr_certified == 1)
						$yesselected = 'checked="checked"';
					if($g->office_addr_certified == 0)
						$noselected = 'checked="checked"';
			
					$output .= '<input type="radio" name="g[office_addr_certified]['.$counter.']" value="1" '.$yesselected.' /> Yes';
					$output .= '<input type="radio" name="g[office_addr_certified]['.$counter.']" value="0" '.$noselected.' /> No</div>';
$output .= <<<EOD
				
                
                
                </div></td>
			</tr>
			</table>
EOD;
			$output .= '</div>';
		
			$counter++;
		}
        $gnumval = $counter + 1;
}
if(!$loanaccountid || $counter == 0 || isset($_POST['lid'])){
if(isset($_POST['g']['gname']))
{
	$len = $_POST['gnumname'];
}else{
	$len = 1;
}
//print_r($_POST['g']['gtype']);exit;
for($i=0;$i<$len;$i++)
{
	if(isset($_POST['gdetail'.$i]))
    {
	$gchecked = '';
	$gtype = isset($_POST['g']['gtype'][$i])?$_POST['g']['gtype'][$i]:'';
	$gname = isset($_POST['g']['gname'][$i])?$_POST['g']['gname'][$i]:'';
	$gnature = isset($_POST['g']['gnature'][$i])?$_POST['g']['gnature'][$i]:'';
	$gamount = isset($_POST['g']['gamount'][$i])?$_POST['g']['gamount'][$i]:'';
	$unit_name = isset($_POST['g']['unit_name'][$i])?$_POST['g']['unit_name'][$i]:'';
	$basic_pay = isset($_POST['g']['basic_pay'][$i])?$_POST['g']['basic_pay'][$i]:'';
	$total_pay = isset($_POST['g']['total_pay'][$i])?$_POST['g']['total_pay'][$i]:'';
	$designation = isset($_POST['g']['designation'][$i])?$_POST['g']['designation'][$i]:'';
	$superannuation_date = isset($_POST['g']['superannuation_date'][$i])?$_POST['g']['superannuation_date'][$i]:'';
	$address = isset($_POST['g']['address'][$i])?$_POST['g']['address'][$i]:'';
    $guidval = isset($_POST['guid'.$i])?$_POST['guid'.$i]:'';
	$gnumber = $i+1;
    
    //echo $i."==".$gtype;exit;
    if(isset($_POST['gdetail'.$i]))
    	$gchecked = " checked = 'checked'";
$output .= <<<EOD
<div id="gdetailid$i">
	<h2><input type="checkbox" value="1" name="gdetail$i" id="gid$i" $gchecked onClick="openGuarantor($i);">
EOD;
    $output .= '<input type="hidden" value="'.$guidval.'" name="guid'.$i.'" id="guidid'.$i.'">';
$output .= <<<EOD
    Guarantor Detail</h2>   
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="g1">
<tr class="oddrow">
	<td><div class="loantext">Guarantor Type: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'>
		<select name="g[gtype][$i]">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Guarantor Type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($gtype == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>
	</td>
	<td><div class="loantext">Nature of Guaranty: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'>
		<select name="g[gnature][$i]">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Nature Of Guaranty' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($gnature == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>
	</td>
</tr>

<tr class="evenrow">
	<td><div class="loantext">Guarantor Name: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[gname][$i]" value="$gname" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
	<td><div class="loantext">Amount of Guaranty: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[gamount][$i]" onkeypress="return paypay(event);" maxlength="11" value="$gamount" /></div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Name of Unit: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[unit_name][$i]" maxlength="45" onkeypress="return alphabet(event);" value="$unit_name" /></div></td>
	<td><div class="loantext">Basic Pay: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[basic_pay][$i]" onkeypress="return paypay(event);" maxlength="11" value="$basic_pay" /></div></td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">Total Pay: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[total_pay][$i]" value="$total_pay" maxlength="11" onkeypress="return paypay(event);" /></div></td>
	<td><div class="loantext">Superannuation Date: <span title="This field is required." class="form-required">*</span></div>
	<div id="hearing_id-datepicker-popup-0-wrapper"  class="loanform">
	<input type="text" value="$superannuation_date" id="datepicker$i" name="g[superannuation_date][$i]" maxlength="10" readonly="readonly"/>
<div class="description"> Format: dd-mm-yyyy</div>
</div>
	</td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Address: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><textarea name="g[address][$i]" maxlength="100" onkeypress="return addressvalidation(event);">$address</textarea></div></td>
	<td><div class="loantext">Designation: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[designation][$i]" value="$designation" maxlength="45" onkeypress="return alphanumeric(event);" /></div></td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">Office Address Certified?:</div></td>
	<td><div class='loanform'>
EOD;
		$yesselected = 'checked="checked"';
		if($office_addr_certified == 1)
			$yesselected = 'checked="checked"';
		if($office_addr_certified == 0)
			$noselected = 'checked="checked"';

		$output .= '<input type="radio" name="g[office_addr_certified]['.$i.']" value="1" '.$yesselected.' /> Yes';
		$output .= '<input type="radio" name="g[office_addr_certified]['.$i.']" value="0" '.$noselected.' /> No';
$output .= <<<EOD
	</div></td>
</tr>
</table>
</div>
EOD;
    }
$gnumval = $i + 1;
}
}
$output .= <<<EOD
</div>
<table>
<tr class="oddrow">

	<td align="center" colspan=4>
		<input type="button" class="form-submit" value="Add Guarantor" id="gsubmit" name="als" />
	</td>   

</tr>
</table>
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper26">
<tr class="oddrow">

	<td align="center" colspan=4><h2>Other Details</h2></td>   

</tr>
<tr class="evenrow" id="licenseid">
	<td  colspan='2' width="500"><div class="loantext">License No.:</div>
	<div class='loanform'><input type="text" name="licenseno" value="$licenseno" maxlength="15" onkeypress="return alphanumeric(event)" /></div>
	</td><td colspan='2' ><div class="loantext">Expiry Date:</div>
	<div class='loanform'><input type="text" name="licenseexpiry" id="licenseexpiry" readonly="readonly" value="$licenseexpire" /></div>
	<div class="description" style="float:right;">( Optional for Vehicle Loan )</div>
	</td>
</tr>
<tr class="oddrow" id="licenseid">
		</td><td colspan='2' ><div class="loantext">Ref No.:</div>
	<div class='loanform'><input type="text" name="refno" id="refno" value="$refno" /></div>
	
	</td>
</tr>
    
</table>
<div id="documentrequiredid">
EOD;
	
	if((isset($_POST['l']['scheme_name']) && $_POST['l']['scheme_name']) || (!isset($_POST['l']['scheme_name']) && $loanaccountid))
    {
    	if((isset($_POST['l']['scheme_name']) && $_POST['l']['scheme_name']))
        	$scheme = $_POST['l']['scheme_name'];
        $output .= '
        <table style="border:none;" id = "wrapper27">
        <tr class="oddrow">
            <td align="left" colspan="4">
           <h2 style="text-align:left;">Document Required</h2> &nbsp; ( Allowed extensions : .pdf .doc .docx .txt .xls .xlsx .pptx .ppt )
        
        <table>';

		$stmt = "SELECT eligibility_criteria FROM tbl_scheme_master WHERE loan_scheme_id = '".$scheme."' AND status = 167 AND active = 1";
		$result = db_query($stmt);
		$row = db_fetch_object($result);
		$docs = unserialize($row->eligibility_criteria);
		$query = "SELECT loanDoc_id, loanDoc_name FROM tbl_loandocs WHERE FIND_IN_SET(loanDoc_id,'".$docs."') AND status = 1";
		$res = db_query($query);
		$inc = 0;
		while($row = db_fetch_object($res))
		{
			$selected = '';
			$append = '';
            $docfile = $d[$row->loanDoc_id];
			if($d[$row->loanDoc_id])
			{
				$selected = 'checked = "checked"';
				$append = '<div id="df'.$row->loanDoc_id.'">'.l($d[$row->loanDoc_id],"loan/downloadfile/".$d[$row->loanDoc_id]);
$append .= <<<EOD
                <span style="cursor:pointer;" onClick="return removeloanfile($row->loanDoc_id,$s->loanee_id,'removefile');"> | Remove</span>
                <input type="hidden" name="documentexist[$row->loanDoc_id]" value="$docfile" />
                </div>
EOD;
			}else if($_POST['document'][$inc] && ($_FILES['doc'.$row->loanDoc_id]['name'] || $_POST['documentexist'][$row->loanDoc_id]))
			{
				$docfile = ($_FILES['doc'.$row->loanDoc_id]['name'])?($inc+1).$_FILES['doc'.$row->loanDoc_id]['name']:$_POST['documentexist'][$row->loanDoc_id];
				$selected = 'checked = "checked"';
				$append = '<div id="df'.$row->loanDoc_id.'">'.l($docfile,"loan/downloadfile/".$docfile."/temp");
				$append .= <<<EOD
                <input type="hidden" name="documentexist[$row->loanDoc_id]" value="$docfile" />
                </div>
EOD;

			}
			$output .= '<tr><td><input type="checkbox" value="'.$row->loanDoc_id.'" name="document[]" '.$selected.' id="doc'.$row->loanDoc_id.'">&nbsp;'.$row->loanDoc_name.'</td><td><input type="file" size="40" id="edit-upload" class="form-file" name="doc'.$row->loanDoc_id.'"/> ( Optional ) '.$append.' <span id="doc'.$row->loanDoc_id.'p"></span></td></tr>';

			$inc++;
		}
$output .= '
	</table>	
';
    }
$output .= '	</td></tr></table></div>';

	if($loanaccountid)
	{
		$output .= '<input type="hidden" value="'.$loanaccountid.'" name="lid" id="lid">';
	}
$output .= <<<EOD

<table width="100%" cellpadding="2" cellspacing="1" border="0" id="blankg" style="display:none;">
<tr class="oddrow">
	<td><div class="loantext">Guarantor Type: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'>
		<select name="g[gtype][]">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Guarantor Type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$output .= '<option value="'.$row->lid.'" >'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>
	</td>
	<td><div class="loantext">Nature of Guaranty: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'>
		<select name="g[gnature][]">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Nature Of Guaranty' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			$output .= '<option value="'.$row->lid.'">'.$row->lname.'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select></div>
	</td>
</tr>

<tr class="evenrow">
	<td><div class="loantext">Guarantor Name: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[gname][]" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
	<td><div class="loantext">Amount of Guaranty: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[gamount][]" onkeypress="return paypay(event);" maxlength="11" /></div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Name of Unit: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[unit_name][]" maxlength="45" onkeypress="return alphabet(event);" /></div></td>
	<td><div class="loantext">Basic Pay: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[basic_pay][]" onkeypress="return paypay(event);" maxlength="11" /></div></td>
</tr>
<tr class="evenrow" id = "said">
	<td><div class="loantext">Total Pay: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[total_pay][]" onkeypress="return paypay(event);" maxlength="11" /></div></td>
	<td><div class="loantext">Superannuation Date: <span title="This field is required." class="form-required">*</span></div>
	<div id="hearing_id-datepicker-popup-0-wrapper"  class="loanform">
	<input type="text" id="datepicker" name="g[superannuation_date][]" maxlength="10" readonly="readonly"/>
<div class="description"> Format: dd-mm-yyyy</div>
</div>
	</td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Address: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><textarea name="g[address][]" maxlength="100" onkeypress="return addressvalidation(event);"></textarea></div></td>
	<td><div class="loantext">Designation: <span title="This field is required." class="form-required">*</span></div>
<div class='loanform'><input type="text" name="g[designation][]" maxlength="45" onkeypress="return alphanumeric(event);" /></div></td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">Office Address Certified?:</div></td>
	<td><div class='loanform'>
EOD;

		$output .= '<input type="radio" name="g[office_addr_certified][oyes]" value="1" checked="checked" /> Yes';
		$output .= '<input type="radio" name="g[office_addr_certified][ono]" value="0" /> No';
$output .= <<<EOD
	</div></td>
</tr>
</table>



<input type='hidden' value='$gnumval' name="gnumname" id='gnumid'>
<input type="hidden" value="Bank" name="loan_typetext" id="loan_typetextid">
<input type="hidden" value="" name="eligibilitydocs" id="eligibilitydocsid">
<input type="hidden" value="1" name="updateloan">
<input type="hidden" value="$base_url" name="baseurl" id="baseurlid">
<input type="hidden" value="$imagepath" name="imagepath" id="imagepathid">
<input type="hidden" value="$mg" name="mg" id="mgid">
<br>
<div class="back" align="center">
EOD;

$output .= l("Back",'loan/listloans');
$output .= <<<EOD
&nbsp;&nbsp;<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/></div>
</div>
</form>
<input type="hidden" id="baseurl" value="$base_url"></div>
EOD;


if(isset($scriptcss))
	$output .= '<script>'.$scriptcss.'</script>';
return $output;
}


function getUniqueNumber($y,$sc)
{
		$num_str = sprintf("%0d", mt_rand(1,999999));
		$reg_number = $y.$sc.$num_str;
		$query = "SELECT loan_id FROM tbl_loan_detail WHERE reg_number = '".$reg_number."'";
		$res = db_query($query);
		$row = db_result($res);
		if($row)
			getUniqueNumber($y,$sc);

		else
			return $reg_number;
}

function updateLoanAccount($rid = '',$mg = '')
{
	global $user;
	global $base_root;
	global $base_path;
	$fields = '';
	$valuestr = '';
	$updatestr = '';
	$loanupdatestr = '';
	//$len = sizeof($_POST['g']['gname']);
	$len = $_POST['gnumname'];
	$error = validateLoanForm($len,$mg);
   
	if(!$error)
	{
		//$eligibility_criteria = serialize(trim($_POST['eligibilitydocs'],','));
		
		foreach($_POST['p'] as $key => $val)
		{
			if($key == 'dob')
				$val = databaseDateFormat($val,'indian','-');
			if($rid)
			{
				$updatestr .= $key." = '".db_escape_string($val)."',";
			}else{
				$fields .= $key.',';
				$valuestr .= "'".db_escape_string($val)."',";
			}
		}
		foreach($_POST['l'] as $key => $val)
		{
        	if($key == 'disbursed_date')
            	$val = databaseDateFormat($val,'indian','-');
			if($rid)
			{
				$loanupdatestr .= $key." = '".db_escape_string($val)."',";
			}else{
				$loanfields .= $key.',';
				$loanvaluestr .= "'".db_escape_string($val)."',";
			}
		}
		if($_POST['licenseno'])
		{
			if($rid)
			{
				$loanupdatestr .= "licenseno = '".db_escape_string($_POST['licenseno'])."',";
			}else{
				$loanfields .= 'licenseno,';
				$loanvaluestr .= "'".db_escape_string($_POST['licenseno'])."',";
			}
		}
		if($_POST['licenseexpiry'])
		{
			if($rid)
			{
				$loanupdatestr .= "licenseexpiry = '".db_escape_string(databaseDateFormat($_POST['licenseexpiry'],'indian','-'))."',";
			}else{
				$loanfields .= 'licenseexpiry,';
				$loanvaluestr .= "'".db_escape_string(databaseDateFormat($_POST['licenseexpiry'],'indian','-'))."',";
			}
		}
        
        if($_POST['refno'])
		{
			if($rid)
			{
				$updatestr .= "refno = '".db_escape_string($_POST['refno'])."',";
			}else{
				$fields .= 'refno,';
				$valuestr .= "'".db_escape_string($_POST['refno'])."',";
			}
		}
	
		$inserterror = '';
		$fields = trim($fields,',');
		$valuestr = trim($valuestr,',');
		$updatestr = trim($updatestr,',');
		//$loanupdatestr = trim($loanupdatestr,',');
		if(isset($_POST['l']['loan_amount']) && isset($_POST['l']['capital_subsidy']))
		{
			$loan_requirement = floatval($_POST['l']['loan_amount']) - floatval($_POST['l']['capital_subsidy']);

			//$loanupdatestr .= "loan_requirement = '".$loan_requirement."',o_principal = '".$loan_requirement."'";
			$loanupdatestr .= "loan_requirement = '".$loan_requirement."',";
		}
		db_query('START TRANSACTION');
		db_query('BEGIN');
		$query = "SELECT loanee_id FROM tbl_loanee_detail WHERE reg_number = '".$rid."'";
		$res = db_query($query);
		$s = db_fetch_object($res);
		if(!$mg)
		{
			if($rid) // IF LOAN EDIT AND WE HAVE LOAN ID
			{
				$updatestmt = "UPDATE tbl_loanee_detail SET $updatestr WHERE reg_number = '".$rid."'";
				if(!db_query($updatestmt))
					$inserterror = 1;
				$loanupdatestr = trim($loanupdatestr,',');
				$updatestmt = "UPDATE tbl_loan_detail SET $loanupdatestr WHERE reg_number = '".$rid."'";
				if(!db_query($updatestmt))
					$inserterror = 1;
			}else{ // FIRST TIME APPLYING LOAN ( INSERT IN LOAN TABLE )
		
	// *********** INSERT IN LOANEE AND LOAN TABLE ***********************

				
				$insert_statement = "INSERT INTO tbl_loanee_detail ($fields) VALUES ($valuestr)";
				if(!db_query($insert_statement))
				{
					$inserterror = 1;
				}
				$loaneeid = db_last_insert_id('tbl_loanee_detail','loanee_id');
				
				$loanfields .= "application_date,createdby,createdon,loan_status,corp_branch,loan_requirement,o_disburse_amount";
				$loanvaluestr .= "'".date("Y-m-d")."','".$user->uid."','".time()."',1,'".$_POST['p']['corp_branch']."','".$_POST['l']['loan_amount']."','".$_POST['l']['project_cost']."'";
				$insert_statement = "INSERT INTO tbl_loan_detail ($loanfields) VALUES ($loanvaluestr)";
				if(!db_query($insert_statement))
					$inserterror = 1;
				$loanid = db_last_insert_id('tbl_loan_detail','loan_id');
				
	// *********** INSERT IN LOANEE AND LOAN TABLE END ***********************
				
	// ****** INSERT IN WORKFLOW_DOCKET AND TASK TABLE , UPDATE GENERATED DOCKET NO IN LOAN TABLE *****
				
				//$corp_branch = getCorporationBranch($user->uid);
				$corp_branch = $_POST['p']['corp_branch'];
				$insert_statement = "INSERT INTO tbl_workflow_docket (workflow_id, time, status,corp_branch) VALUES (1,UNIX_TIMESTAMP(),'pending',$corp_branch)";
				//return $insert_statement;
				if(!db_query($insert_statement))
					$inserterror = 1;
				$docketid = db_last_insert_id('tbl_workflow_docket','doc_id');
				$update_statement = "UPDATE tbl_loan_detail SET loan_docket = $docketid WHERE loan_id = $loanid";
				if(!db_query($update_statement))
					$inserterror = 1;
				
				$insert_statement = "INSERT INTO tbl_workflow_task (level, doc_id) VALUES (1, $docketid)";
				if(!db_query($insert_statement))
					$inserterror = 1;

	// *********** INSERT IN TASK TABLE END ***********************
				
				$query = "SELECT short_code FROM tbl_district WHERE district_id = ".$_POST['p']['district']." AND status = 1 ";
				$res = db_query($query);
				$row = db_fetch_object($res);
				$sql = "SELECT uniquenum FROM tbl_loanee_detail ORDER by uniquenum DESC LIMIT 1";
				$unumres = db_query($sql);
				$unum = db_fetch_object($unumres);
				if($unum->uniquenum)
					$uniqueno = $unum->uniquenum + 1;
				else
					$uniqueno = 100000;
				$regnumber = date("Y").$row->short_code.$uniqueno;
				//$regnumber = getUniqueNumber(date("Y"),$row->short_code);
				$sql = "UPDATE tbl_loan_detail SET reg_number = '".$regnumber."' WHERE loan_id = $loanid";
				if(!db_query($sql))
					$inserterror = 1;
				$sql = "UPDATE tbl_loanee_detail SET reg_number = '".$regnumber."', uniquenum = '".$uniqueno."' WHERE loanee_id = $loaneeid";
				if(!db_query($sql))
					$inserterror = 1;
				
			}
		}
		
		$gvaluestr = '';
		$gupdatestr = '';
		if($rid)
			$loaneeid = $s->loanee_id;
        //print_r($_POST['g']);exit;
        $count = 0;
		for($i=0;$i<$len;$i++)
		{
			if(isset($_POST['gdetail'.$i]))
			{
            	$count++;
            	if(!$_POST['guid'.$i])
                    $gvaluestr .= '('.$loaneeid.',';
				foreach($_POST['g'] as $key => $val)
				{
                    if($count == 1)
                        $gfields .= $key.',';
					if($key == 'superannuation_date')
					{
						$_POST['g'][$key][$i] = databaseDateFormat($_POST['g'][$key][$i],'indian','-');
					}
					if($_POST['guid'.$i])
					{
						$gupdatestr .= $key." = '".db_escape_string($_POST['g'][$key][$i])."',";
					}else{
						$gvaluestr .= "'".db_escape_string($_POST['g'][$key][$i])."',";
					}
				}
				if($_POST['guid'.$i])
				{
					$q = "UPDATE tbl_guarantor_detail SET ".trim($gupdatestr,',')." WHERE loanee_id = ".$s->loanee_id." AND gid = ".$_POST['guid'.$i];
					if(!db_query($q))
						$inserterror = 1;
				}else{
					$gvaluestr = trim($gvaluestr,',').'),';
				}
			}else{
				//form_set_error('g[gname]', t('Guarantor is mandatory.'));
			}
		}
		if($gvaluestr)
		{
			//$gfields .= "loanee_id";
			//$gvaluestr .= "'".$loaneeid."'";
			$gfields = 'loanee_id,'.trim($gfields,',');
			$gvaluestr = trim($gvaluestr,',');
			$insert_statement = "INSERT INTO tbl_guarantor_detail ($gfields) VALUES $gvaluestr";
			
			if(!db_query($insert_statement))
				$inserterror = 1;
		}
		if(!$mg)
		{
			if(isset($_POST['document']))
			{
				$filecount = 0;
				foreach($_POST['document'] as $key => $val)
				{
					$filecount++;
					if($_FILES['doc'.$val]['name'] || $_POST['documentexist'][$val])
					{
					
						$ofilename = ($_FILES['doc'.$val]['name'])?$filecount.$_FILES['doc'.$val]['name']:$_POST['documentexist'][$val];
						$path_info = pathinfo($ofilename);
						$ext = $path_info['extension'];
						@chmod('sites/default/files/loan','0777');

						//if(is_file($base_path.'sites/default/files/loan/'.$_POST['LOI_file']))
						//	@unlink($base_path.'sites/default/files/loan/'.$_POST['LOI_file']);
						if($rid)
						{
							$sql = "SELECT loan_file FROM tbl_loan_documents WHERE loanee_id = $s->loanee_id AND document_id = $val";
							$dres = db_query($sql);
							$dfr = db_fetch_object($dres);
							if($dfr->loan_file != $ofilename)
								$filename = 'LOAN'.$s->loanee_id.$val.'_'.time().'.'.$ext;
						}else{
							$filename = 'LOAN'.$loaneeid.$val.'_'.time().'.'.$ext;
						}
						$filetouploadarr[$ofilename] = $filename;
						//@move_uploaded_file($_FILES['doc'.$val]['tmp_name'],'sites/default/files/loan/'.$filename);
						if($rid)
						{
								if($dfr->loan_file && is_file('sites/default/files/loan/'.$dfr->loan_file))
								{
									if($dfr->loan_file != $ofilename)
									{
										$filetounlinkarr[] = $dfr->loan_file;
										$sql = "UPDATE tbl_loan_documents SET loan_file = '".$filename."' WHERE loanee_id = $s->loanee_id AND document_id = $val";
										if(!db_query($sql))
											$inserterror = 1;
									}
								}else{
									$sql = "INSERT INTO tbl_loan_documents (loanee_id, document_id, loan_file) VALUES ($s->loanee_id, $val, '".$filename."')";
									if(!db_query($sql))
										$inserterror = 1;
								}
							//if(is_file('sites/default/files/loan/'.$_POST['documentexist'][$val]))
							//	@unlink('sites/default/files/loan/'.$_POST['documentexist'][$val]);
							
						}else{
							$sql = "INSERT INTO tbl_loan_documents (loanee_id, document_id, loan_file) VALUES ($loaneeid, $val, '".$filename."')";
							if(!db_query($sql))
							{
								$inserterror = 1;
							}
						}
					}
				
				
			   
				}
			}
		}
		
		
		if($inserterror)
		{
			db_query('ROLLBACK');
			drupal_set_message("There is some error in process.Please try again.");
			drupal_goto('loan/listloans');
		}else{
			db_query('COMMIT');
			foreach($filetounlinkarr as $v)
			{
				@unlink('sites/default/files/loan/'.$v);
			}
			foreach($filetouploadarr as $k => $v)
			{
				@copy('sites/default/files/loan/temp/'.$k,'sites/default/files/loan/'.$v);
				@unlink('sites/default/files/loan/temp/'.$k);
			}
			if($rid)
			{
				$message = getMessage('loans', 'code02',array("0"=>$rid));
				drupal_set_message($message);
				drupal_goto('loan/listloans');
			}else{
				$message = getMessage('loans', 'code12', '');
				drupal_set_message($message);
                if($_POST['p']['email'])
                {
                    $parameter = '';
                    $to = $_POST['p']['email'];
                    $name = $_POST['p']['fname'].' '.$_POST['p']['lname'];
                    $address = $_POST['p']['address1'].','.$_POST['p']['address2'];
                    $loanamount = $_POST['l']['loan_amount'];
                    $scheme = getscheme1($_POST['l']['scheme_name']);
                    //$ROI = getROI($_POST['l']['scheme_name']);
                    $parameter = json_encode(array("$name","$name","$address","$loanamount","$scheme"));
                    if(!createMail('loan_apply',$to,'',$parameter))
                    {
                        $inserterror = 1;
                    }
                }
                $parameter = '';
                $nameemail = unserialize(getNameEmail($_POST['p']['corp_branch'],17));
                $to = $nameemail[0];
                $name = $nameemail[1];
                $l = unserialize(getLoanDetail($loanid,1));
                $address = $l[0];
                $loanamount = $l[2];
                $scheme = $l[1];
                $ROI = $l[3];
                $applicant = $l[4];
                $mobile = $l[5];
                $parameter = json_encode(array("$name","$applicant","$applicant","$mobile","$address","$loanamount","$scheme","$ROI"));
                createMail('loan_FO_verification',$to,'',$parameter);
				drupal_goto('loan/listloans');
			}
		}
	}else{
		return $error;
	}
	
	
}
//Server validation
function validateLoanForm($len = '',$mg='')
{
	$errorstr = '';
	$scriptcss = '';
	if(!$mg && !isset($_POST['l']['bank']))
	{
		if(alphabet('p[fname]',$_POST['p']['fname'],'First name'))
		{
			$scriptcss .= '$("#fname").addClass("error");';
		}
		if(isEmpty('p[fname]',$_POST['p']['fname'],'First name'))
			$scriptcss .= '$("#fname").addClass("error");';

		if(alphabet('p[lname]',$_POST['p']['lname'],'Last name'))
		{
			$scriptcss .= '$("#lname").addClass("error");';
		}
		if(isEmpty('p[lname]',$_POST['p']['lname'],'Last name'))
			$scriptcss .= '$("#lname").addClass("error");';
		if(alphabet('p[fh_name]',$_POST['p']['fh_name'],'Father/Husband name'))
		{
			$scriptcss .= '$("#fh_name").addClass("error");';
		}
		if(isEmpty('p[fh_name]',$_POST['p']['fh_name'],'Father/Husband name'))
			$scriptcss .= '$("#fh_name").addClass("error");';
		if(($_POST['p']['family_income'] != 0 || $_POST['p']['family_income'] != '0.00') && isEmpty('p[family_income]',$_POST['p']['family_income'],'Family Income'))
			$scriptcss .= '$("#fincomeid").addClass("error");';
		if(isEmpty('p[income_cat]',$_POST['p']['income_cat'],'Income category'))
			$scriptcss .= '$("#income_catid").addClass("error");';

		if(isEmpty('p[religion]',$_POST['p']['religion'],'Religion '))
			$scriptcss .= '$("#religionid").addClass("error");';
		if(isEmpty('p[caste]',$_POST['p']['caste'],'Caste'))
			$scriptcss .= '$("#casteid").addClass("error");';
			
		if(isEmpty('p[family_income]',$_POST['p']['family_income'],'Family Income'))
		{
			$scriptcss .= '$("#fincomeid").addClass("error");';

		}else if(paypay('p[family_income]',$_POST['p']['family_income'],'Family Income'))
		{
			$scriptcss .= '$("#fincomeid").addClass("error");';
		}
		if($_POST['p']['email'])
		{
			if(isValidEmail('p[email]',$_POST['p']['email'],'Email'))
			{
				$scriptcss .= '$("#emailid").addClass("error");';
		
			}
		}
		if($_POST['p']['running_loan'] == 1)
		{
			form_set_error('p[running_loan]','You have already a running loan so you can not apply for loan.');
			$scriptcss .= '$("#loanyes").addClass("error");';
		}
		if(isEmpty('p[dob]',$_POST['p']['dob'],'Date of Birth'))
			$scriptcss .= '$("#dob").addClass("error");';
		if($_POST['p']['dob'] && (dateDiffByDays($_POST['p']['dob'],date("Y-m-d"))/365) < 18)
		{
			form_set_error('p[dob]','Enter date of birth such that your age will be minimum of 18 years.');
			$scriptcss .= '$("#dob").addClass("error");';
		} 
		if(!$_POST['p']['mobile'] && !$_POST['p']['phone'])
		{
			form_set_error('p[mobile]','One of phone or mobile number is mandatory.');
			$scriptcss .= '$("#mobileid").addClass("error");';
			$scriptcss .= '$("#phoneid").addClass("error");';
		}else{
			if($_POST['p']['mobile'] && isValidMobile('p[mobile]',$_POST['p']['mobile'],'Mobile number'))
			{
				$scriptcss .= '$("#mobileid").addClass("error");';
			}
			if($_POST['p']['phone'] && isValidPhone('p[phone]',$_POST['p']['phone'],'Phone number'))
			{
				$scriptcss .= '$("#phoneid").addClass("error");';
			}
		}
		if(!$_POST['p']['education'])
		{
			form_set_error('p[education]','Qualification field is required.');
			$scriptcss .= '$("#qualificationid").addClass("error");';
		}
		if(isEmpty('p[address_type]',$_POST['p']['address_type'],'Address Type'))
			$scriptcss .= '$("#addresstypeid").addClass("error");';
		if(isEmpty('p[state]',$_POST['p']['state'],'State'))
			$scriptcss .= '$("#stateid").addClass("error");';
		if(isEmpty('p[district]',$_POST['p']['district'],'District'))
			$scriptcss .= '$("#districtid").addClass("error");';
		if(isEmpty('p[tehsil]',$_POST['p']['tehsil'],'Tehsil'))
			$scriptcss .= '$("#tehsilid").addClass("error");';
		if(isEmpty('p[block]',$_POST['p']['block'],'Block'))
			$scriptcss .= '$("#blockid").addClass("error");';
		if($_POST['p']['address_type'] == 175)
		{
			if(isEmpty('p[panchayat]',$_POST['p']['panchayat'],'Panchayat'))
				$scriptcss .= '$("#panchayatid").addClass("error");';
		}
		
		if(trim($_POST['p']['pincode']) == '')
		{
			form_set_error('p[pincode]', t('Pin code field is required.'));
			$scriptcss .= '$("#pinid").addClass("error");';

		}else if(isValidPin('p[pincode]',$_POST['p']['pincode'],'Pincode'))
		{
			$scriptcss .= '$("#pinid").addClass("error");';
		}else if(strlen($_POST['p']['pincode']) < 6)
		{
			form_set_error('p[pincode]', t('Please enter a valid Pin code.'));
			$scriptcss .= '$("#pinid").addClass("error");';
		}
		if(isEmpty('p[address1]',$_POST['p']['address1'],'Address Line1'))
			$scriptcss .= '$("#address1id").addClass("error");';
		if(isEmpty('p[address2]',$_POST['p']['address2'],'Address Line2'))
			$scriptcss .= '$("#address2id").addClass("error");';
		if(isEmpty('l[scheme_name]',$_POST['l']['scheme_name'],'Scheme Name'))
			$scriptcss .= '$("#schemeid").addClass("error");';
		if(isEmpty('l[project_cost]',$_POST['l']['project_cost'],'Project cost'))
			$scriptcss .= '$("#prjcostid").addClass("error");';
		if(paypay('l[project_cost]',$_POST['l']['project_cost'],'Project cost'))
		{
			$scriptcss .= '$("#prjcostid").addClass("error");';
		}
		if(isEmpty('l[loan_amount]',$_POST['l']['loan_amount'],'Loan amount'))
		{
			$scriptcss .= '$("#requirementid").addClass("error");';
		}else if(paypay('l[loan_amount]',$_POST['l']['loan_amount'],'Loan amount'))
		{
			$scriptcss .= '$("#loan_amount").addClass("error");';
		}else if($_POST['l']['loan_amount'] > $_POST['max_loan'])
		{
			form_set_error('l[loan_amount]', t('Maximum Loan amount can not be more than '.$_POST['max_loan']));
			$scriptcss .= '$("#requirementid").addClass("error");';
		}
		if(isEmpty('p[corp_branch]',$_POST['p']['corp_branch'],'Branch office'))
			$scriptcss .= '$("#corp_branchid").addClass("error");';
		
		if(isset($_POST['l']['capital_subsidy']))
		{
			if($_POST['l']['capital_subsidy'] && paypay('l[capital_subsidy]',$_POST['l']['capital_subsidy'],'Capital Subsidy'))
			{
				$scriptcss .= '$("#capitalid").addClass("error");';
			}
			if(floatval($_POST['l']['capital_subsidy']) > floatval($_POST['l']['loan_amount']))
			{
				form_set_error('l[capital_subsidy]', t('Capital subsidy should not exceed the loan amount '.floatval($_POST['l']['loan_amount'])));
				$scriptcss .= '$("#capitalid").addClass("error");';
			}
		}
    }
	if(isset($_POST['l']['bank']))
	{
		$isbset = 0;
		if($_POST['l']['bank'])
		{
			$isbset++;
		}   
		if($_POST['l']['bank_branch'])
		{
			$isbset++;
		}   
		if($_POST['l']['bank_acc_no'])
		{
			$isbset++;
		}   
		if($_POST['l']['b_ROI'])
		{
			$isbset++;
		}   
		if($_POST['l']['disbursed_amount'])
		{
			$isbset++;
			if($_POST['l']['disbursed_amount'] > getLoanSanctionedAmount($_POST['lid']))
			{
				form_set_error('l[disbursed_amount]', t('Disbursed amount should not exceed the max loan amount '.floatval($_POST['l']['loan_amount'])));
				$scriptcss .= '$("#disbursed_amountid").addClass("error");';
			}
		}
		if($_POST['l']['disbursed_date'])
		{
			$isbset++;
			//echo $_POST['lid'];exit;
			$sanctioned_date = getSanctionedDate($_POST['lid']);
			if(databaseDateFormat($_POST['l']['disbursed_date'],'indian','-') < $sanctioned_date)
			{
				form_set_error('l[disbursed_date]', t('Disbursed date should be greater than loan sanctioned date '.date("d-m-Y",strtotime($sanctioned_date))));
				$scriptcss .= '$("#disbursed_dateid").addClass("error");';
			}
		}
		if($isbset && $isbset < 6)
		{
			form_set_error('l[disbursed_date]', t('All fields of bank detail ( Bank name, Bank branch, Disbursed amount, Disbursed date, Bank A/C no., ROI ) are required.'));
			if(!$_POST['l']['bank'])
				$scriptcss .= '$("#bankid").addClass("error");';
			if(!$_POST['l']['bank_branch'])
				$scriptcss .= '$("#bank_branchid").addClass("error");';
			if(!$_POST['l']['bank_acc_no'])
				$scriptcss .= '$("#bank_acc_noid").addClass("error");';
			if(!$_POST['l']['b_ROI'])
				$scriptcss .= '$("#b_ROIid").addClass("error");';
			if(!$_POST['l']['disbursed_date'])
				$scriptcss .= '$("#disbursed_dateid").addClass("error");';
			if(!$_POST['l']['disbursed_amount'])
				$scriptcss .= '$("#disbursed_amountid").addClass("error");';
		}
		if(isset($_POST['l']['bank_acc_no']) && $_POST['l']['bank_acc_no'] && strlen($_POST['l']['bank_acc_no']) < 8)
		{
			form_set_error('l[bank_acc_no]', t('Bank acc. no should have minimum of 8 digit.'));
			 $scriptcss .= '$("#bank_acc_noid").addClass("error");';
		}
		if($_POST['l']['b_ROI'] && $_POST['l']['b_ROI'] > 100)
		{
			form_set_error('l[b_ROI]', t('ROI should not exceed 100.'));
			$scriptcss .= '$("#b_ROIid").addClass("error");';
		}
	}
    $guarantor = 0;
	$err = 0;
	for($i=0;$i<$_POST['gnumname'];$i++)
	{
		if(isset($_POST['gdetail'.$i]))
		{
			$guarantor = 1;
			foreach($_POST['g'] as $key => $val)
			{
                $field = $key;
            	if($key == 'gtype')
                	$field = 'Guarantor type';
            	if($key == 'gnature')
                	$field = 'Nature of Guaranty';
            	if($key == 'gname')
                	$field = 'Guarantor name';
            	if($key == 'gamount')
                	$field = 'Guarantor amount';
            	if(($key == 'basic_pay' || $key == 'gamount' || $key == 'total_pay') && ($_POST['g'][$key][$i] == '0.00' || $_POST['g'][$key][$i] == '0'))
                {
                    form_set_error("g[".$key."][".$i."]",ucwords(str_replace('_',' ',$key)).' field can not be 0.');
                     $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
                }elseif($key != 'office_addr_certified' && isEmpty("g[".$key."][".$i."]",$_POST['g'][$key][$i],ucwords(str_replace('_',' ',$field))))
				{
                	if($key == 'gnature' || $key == 'gtype')
                        $scriptcss .= '$("select[name=\'g['.$key.']['.$i.']\']").addClass("error");';
                   	elseif($key == 'address')
                    	$scriptcss .= '$("textarea[name=\'g['.$key.']['.$i.']\']").addClass("error");';
                    else
                    	$scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
				}
				if($key == 'gname' && $_POST['g'][$key][$i])
				{
					if(alphanumeric("g[".$key."][".$i."]",$_POST['g']['gname'][$i],'Guarantor name'))
					{
                        $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
					}
				}
				if($key == 'gamount' && $_POST['g'][$key][$i] && $_POST['g'][$key][$i])
				{
					if(paypay('g['.$key.']['.$i.']',$_POST['g']['gamount'][$i],'Guaranty amount'))
					{
                        $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
					}
				}
				if($key == 'basic_pay' && $_POST['g'][$key][$i] != '0.00' && $_POST['g'][$key][$i])
				{
					if(paypay('g['.$key.']['.$i.']',$_POST['g']['basic_pay'][$i],'Basic pay'))
					{
                        $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
					}
				}
				if($key == 'total_pay' && $_POST['g'][$key][$i] != '0.00' && $_POST['g'][$key][$i])
				{
					if(paypay('g['.$key.']['.$i.']',$_POST['g']['total_pay'][$i],'Total pay'))
					{
                        $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
					}
				}
				if($key == 'designation' && $_POST['g'][$key][$i])
                {
					if(alphanumeric('g['.$key.']['.$i.']',$_POST['g']['designation'][$i],'Guarantor designation'))
                        $scriptcss .= '$("input[name=\'g['.$key.']['.$i.']\']").addClass("error");';
                }
			}
		}
	}
   if(!$mg)
	{
		if(isset($_POST['document']))
		{
			$filecount = 0;
			//print_r($_POST['document']);exit;
			foreach($_POST['document'] as $key => $val)
			{
				$filecount++;
				if($val && ($_FILES['doc'.$val]['name'] || $_POST['documentexist'][$val]))
				{
                
					$filename = ($_FILES['doc'.$val]['name'])?$_FILES['doc'.$val]['name']:$_POST['documentexist'][$val];
                  $extension = fileexts($filename);
                  if($extension == '.pdf' || $extension == '.zip' || $extension == '.rar'|| $extension == '.doc'|| $extension == '.txt' || $extension == '.docx' || $extension == '.xls' || $extension == '.xlsx' || $extension == '.ppt' || $extension == '.pptx')
					{
					@chmod('sites/default/files/loan/temp','0777');

					//if(is_file($base_path.'sites/default/files/loan/'.$_POST['LOI_file']))
					//	@unlink($base_path.'sites/default/files/loan/'.$_POST['LOI_file']);
					if($_FILES['doc'.$val]['name'])
					{
						@move_uploaded_file($_FILES['doc'.$val]['tmp_name'],'sites/default/files/loan/temp/'.$filecount.$filename);
						@chmod('sites/default/files/loan/temp/'.$filecount.$filename,'0777');
					}
                  
                  }else{
            
                    form_set_error("doc'.$val",'Please upload file with extension .pdf,.zip.rar,doc,txt,docx,xls,ppt,xlsx,pptx');
                    
                    $scriptcss .= '$("input[name=doc'.$val.']").addClass("error");';
                    }

                 }
                }
               }
	}
	return $scriptcss;
    
}

function fileexts1($filename) { 
$filename = strtolower($filename) ; 
$exts = split("[/\\.]", $filename) ; 
$n = count($exts)-1; 
$exts = $exts[$n]; 
return '.'.strtolower($exts); 
}

