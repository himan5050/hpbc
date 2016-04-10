<?php
function addeditscheme($schemeid = ''){
	global $base_root,$base_url;
	global $base_path;
		$selected = '';
		$scheme = isset($_POST['scheme_name'])?$_POST['scheme_name']:'';
		$mainscheme = isset($_POST['main_scheme'])?$_POST['main_scheme']:'';
		$sector = isset($_POST['sector'])?$_POST['sector']:'';
		$ltype = isset($_POST['loan_type'])?$_POST['loan_type']:'';
		$tenure = isset($_POST['tenure'])?$_POST['tenure']:'';
		$sofund = isset($_POST['fund_source'])?$_POST['fund_source']:'';
		$prcost = isset($_POST['project_cost'])?$_POST['project_cost']:'';
		$apex = isset($_POST['apex_share'])?$_POST['apex_share']:'';
		$prom = isset($_POST['promoter_share'])?$_POST['promoter_share']:'';
		$corp = isset($_POST['corp_share'])?$_POST['corp_share']:'';
		$capital = isset($_POST['capital_subsidy'])?$_POST['capital_subsidy']:'';
		$MMD = isset($_POST['MMD_FDR'])?$_POST['MMD_FDR']:'';
		$intsubsidy = isset($_POST['interest_subsidy'])?$_POST['interest_subsidy']:'';
		$intcalculation = isset($_POST['interest_type'])?$_POST['interest_type']:'';
		$frequency = isset($_POST['frequency'])?$_POST['frequency']:'';
		$docs = isset($_POST['eligibility'])?$_POST['eligibility']:'';
		$status = isset($_POST['status'])?$_POST['status']:'';
		$LOI = isset($_POST['LOI'])?$_POST['LOI']:'';
		$scheme_code = isset($_POST['scheme_code'])?$_POST['scheme_code']:'';
		$LOI_doc = '';
		$processing_fee = isset($_POST['processing_fee'])?$_POST['processing_fee']:'';
	if($schemeid && !$_POST['scheme_name']) // edit form
	{
		$query = "SELECT * FROM tbl_scheme_master WHERE loan_scheme_id = $schemeid";
		$res = db_query($query);
		$s = db_fetch_object($res);
		if($s->status == 167 || !$s->active)
		{
			form_set_error('','Sorry! You cant not edit the scheme.Please contact to loan admin section.');
			drupal_goto('loan/listscheme');
		}
		$scheme = $s->scheme_name;
		$mainscheme = $s->main_scheme;
		$sector = $s->sector;
		$ltype = $s->loan_type;
		$tenure = $s->tenure;
		$sofund = $s->fund_source;
		$prcost = $s->project_cost;
		$apex = $s->apex_share;
		$prom = $s->promoter_share;
		$corp = $s->corp_share;
		$capital = $s->capital_subsidy;
		$MMD = $s->MMD_FDR;
		$intsubsidy = $s->interest_subsidy;
		$intcalculation = $s->interest_type;
		$frequency = $s->frequency;
		$status = $s->status;
		$LOI = $s->LOI;
		$LOI_doc = $s->LOI_doc;
		$scheme_code = $s->scheme_code;
		$processing_fee = $s->processing_fee;
	}
	if(isset($_POST['updatescheme']))
		$error = updateScheme();

	$array = explode('/',$_GET['q']);
	
	$addedit = ($schemeid)?'Edit':'Add';
		 
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('List of Loan Scheme(s)', 'loan/listscheme');
	if($array[1] == 'addscheme'){
		$breadcrumb[] = l("$addedit Loan Scheme ", 'loan/addscheme');
	  }
	 drupal_set_breadcrumb($breadcrumb);
	 
$output = <<<EOD
<script>
	$(function() {
		$( "#schemenameid" ).focus();
EOD;

if($error)
{
	$output .= $error;
}

$output .= <<<EOD
	});
</script>
<div id="errorid" class="messages error" style="display:none;"></div>

<div id="form-container">
<form action="" name="addschemeform" method="post" enctype="multipart/form-data" onsubmit="return setdocuments();">
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="wrapper2">
<tr class="oddrow">
	<td align="center" colspan=4><h2>Loan Scheme $addedit Form</h2></td> 
</tr>
<tr class="evenrow">
	<td><div class="loantext">Scheme Name: <span title="This field is required." class="form-required">*</span></div><div class='loanform'><input type="text" name="scheme_name" value="$scheme" id="schemenameid" onKeyPress="return alphabet(event);" maxlength="100" /></div></td>
	<td><div class="loantext">Main Scheme: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
EOD;
	
	$output .= '<select name="main_scheme" id="main_schemeid"><option value="">Select</option>';

    $query = "SELECT schemeName_id, schemeName_name FROM tbl_schemenames WHERE status = 1 ORDER BY schemeName_name";
	$res = db_query($query);
	while($row = db_fetch_object($res))
	{
		if($mainscheme == $row->schemeName_id)
			$selected = 'selected="selected"';
		$output .= '<option value="'.$row->schemeName_id.'" '.$selected.'>'.ucwords($row->schemeName_name).'</option>';
			$selected = '';
	}

			
$output .= <<<EOD
		</select>
	</div></td>
</tr>

<tr class="oddrow">
	<td><div class="loantext">Sector: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
		<select name="sector" id="sectorid">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT sector_id, sector_name FROM tbl_sectors WHERE status = 1 ORDER BY sector_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($sector == $row->sector_id)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->sector_id.'" '.$selected.'>'.ucwords($row->sector_name).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>
	</div></td>
	<td><div class="loantext">Loan Type: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
		<select name="loan_type" id="loan_typeid" onChange="check_through_bank(this.options[this.selectedIndex].text);">
		<option value="">Select</option>
EOD;
			
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'loan type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($ltype == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
			$selected = '';
		}
	
$output .= <<<EOD
		</select>
	</div></td>
</tr> 
<!--<tr class="evenrow">
	<td><div class="loantext">Loan class</td>
	<td align="left">
		<select name="main_scheme">
			<option value=""></option>
		</select>
	</td>
</tr> 
-->
	<tr class="evenrow">
	<td><div class="loantext">Tenure: <span title="This field is required." class="form-required">*</span></div>
	<div class='loanform'>
		<select name="tenure" id="tenureid">
		<option value="">Select</option>
EOD;
		for($i = 6;$i<=120;)
		{
			if($tenure == $i)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
			$selected = '';
            $i = $i+6;
		}
$output .= <<<EOD
		</select>&nbsp;<div class="description"> Months: </div>
	</div></td>
	<td><div class="loantext">Source of fund: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
		<select name="fund_source" id="fund_sourceid">
		<option value="">Select</option>
EOD;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Fund Source' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($sofund == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>
	</div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Project Cost: <span title="This field is required." class="form-required">*</span></div><div class='loanform'><input type="text" name="project_cost" value="$prcost" id="prjcostid" maxlength="11" onkeypress="return paypaymain_custom(event,'prjcostid',11);" />&nbsp;<div class="description"> ( in Rs )</div></div></td>
	<td><div class="loantext">Apex share: <span title="This field is required." class="form-required">*</span></div>
	<div class='loanform'><input type="text" name="apex_share" id="apexid" value="$apex" onkeypress="return paypaymain_custom(event,'apexid',6);" onBlur="return calculatePromotorshare();" maxlength="6" />&nbsp;<div class="description">(in %age )</div></div></td>
</tr>
<tr class="evenrow">
	<td><div class="loantext">Corporation Share: <span title="This field is required." class="form-required">*</span></div>
	<div class='loanform'><input type="text" name="corp_share" id="corpid" value="$corp" onkeypress="return paypaymain_custom(event,'corpid',6);" onBlur="calculatePromotorshare();" maxlength="6" />&nbsp;<div class="description">( in %age )</div></div></td>
	<td><div class="loantext">Promotor Share:</div>

	<div id="promdivid" class="loanform">$prom</div> <input type="hidden" name="promoter_share" id="promid" value="$prom" /></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Capital Subsidy:</div>
	<div class="loanform">
EOD;
		$yesselected = 'checked="checked"';
		if($capital == 1)
			$yesselected = 'checked="checked"';
		if($capital == 0)
			$noselected = 'checked="checked"';

		$output .= '<input type="radio" name="capital_subsidy" value="1" '.$yesselected.' />&nbsp;Yes';
		$output .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="capital_subsidy" value="0" '.$noselected.' />&nbsp;No';
$output .= <<<EOD
	</div></td>
	<td><div class="loantext">Scheme Code: <span title="This field is required." class="form-required">*</span></div><div class='loanform'> <input type="text" name="scheme_code" id="scheme_codeid" value="$scheme_code" onKeyPress="return fononlyn(event);" maxlength="3" />&nbsp;<div class="description">( Should be 3 digit )</div></div></td>
	
</tr>
<tr class="evenrow" id="through_bank">
	<td><div class="loantext">MMD FDR: <span title="This field is required.">*</span></div>
	<div class='loanform'><input type="text" name="MMD_FDR" id="mmdfdrid" value="$MMD" maxlength="6" onkeypress="return paypaymain_custom(event,'mmdfdrid',6);" />&nbsp;<div class="description">( in %age )</div></div></td>
	<td><div class="loantext">Interest Subsidy:</div>
	<div class="loanform">
EOD;
		$yesselected = 'checked="checked"';
		if($intsubsidy == 1)
			$yesselected = 'checked="checked"';
		if($intsubsidy == 0)
			$noselected = 'checked="checked"';
		$output .= '<input type="radio" name="interest_subsidy" value="1" '.$yesselected.' />&nbsp;Yes';
		$output .= '&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="interest_subsidy" value="0" '.$noselected.' />&nbsp;No';
$output .= <<<EOD
	</div></td>
</tr>
<tr class="oddrow">
	<td><div class="loantext">Interest Calculation: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
		<select name="interest_type" id="interest_typeid">
		<option value="">Select</option>
EOD;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Interest Type' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($intcalculation == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>
	</div></td>
	<td><div class="loantext">Frequency: <span title="This field is required." class="form-required">*</span></div><div class='loanform'>
		<select name="frequency" id="frequencyid">
		<option value="">Select</option>
EOD;
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'loan frequency' ORDER BY l.lookup_name";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
			if($frequency == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.$row->lname.'</option>';
			$selected = '';
		}
$output .= <<<EOD
		</select>
	</div></td>
</tr>

<tr class="evenrow">
	<td valign="top" colspan=4><div class="loantext1" style="width:125px; margin-top:30px;">Document Required: <span title="This field is required." class="form-required">*</span></div>
		<div class="loanform1">
		<select name="alldocument" id="alldocumentid" multiple="multiple" minheight="10" size="4">
EOD;
		$query = "SELECT loanDoc_id, loanDoc_name FROM tbl_loandocs WHERE status = 1 ORDER BY loanDoc_name";
		$res = db_query($query);
        if(trim($_POST['eligibilitydocs'],','))
        {
            $beligibility_criteria =trim($_POST['eligibilitydocs'],',');
        }elseif($schemeid)
        {
        	$beligibility_criteria = trim(unserialize($s->eligibility_criteria),',');
        }
        
		while($row = db_fetch_object($res))
		{
			if(isset($beligibility_criteria))
			{
				$doc = explode(',',$beligibility_criteria);
				if(!in_array($row->loanDoc_id,$doc))
					$output .= '<option value="'.$row->loanDoc_id.'">'.ucwords($row->loanDoc_name).'</option>';
			}else{
				$output .= '<option value="'.$row->loanDoc_id.'">'.ucwords($row->loanDoc_name).'</option>';
			}
		}
$output .= <<<EOD
		</select>
		<input type='button' value=">>" onClick="copytoList(document.forms['addschemeform'].elements['alldocument'],document.forms['addschemeform'].elements['eligibility']);">
		<input type='button' value="<<" onClick="copytoList(document.forms['addschemeform'].elements['eligibility'],document.forms['addschemeform'].elements['alldocument']);">
		<select name="eligibility" id="eligibilityid" multiple="multiple" minheight="10">
EOD;
		if($schemeid)
		{
			$query = "SELECT loanDoc_id as lid, loanDoc_name as lname FROM tbl_loandocs WHERE status = 1 ORDER BY lname";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				$docarr[$row->lid] = $row->lname;
			}
			$doc = explode(',',trim(unserialize($s->eligibility_criteria),','));
			foreach($doc as $val)
			{
				$output .= '<option value="'.$val.'">'.ucwords($docarr[$val]).'</option>';
			}
		}
		if(isset($_POST['eligibilitydocs']))
		{
			$eligibility_criteria = explode(',',(trim($_POST['eligibilitydocs'],',')));
			$query = "SELECT loanDoc_id, loanDoc_name FROM tbl_loandocs WHERE status = 1 ORDER BY loanDoc_name";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				$docs[$row->loanDoc_id] = $row->loanDoc_name;
			}
			foreach($eligibility_criteria as $v)
			{
				$output .= '<option value="'.$v.'">'.ucwords($docs[$v]).'</option>';
			}
		}

$output .= <<<EOD
		</select></div>
	</td>
</tr>
<tr class="oddrow" >
	<td width="450"><div class="loantext">Status:</div>
	<div class="loanform">
EOD;
		$enable = ($schemeid)?'':'disabled="disabled"';
		$output .= '<select id="statusid" name="status" '.$enable.'>';
		$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Loan Scheme Status' ORDER BY l.lookup_name";
		$res = db_query($query);
		if($schemeid)
			$status = $status;
		else
			$status = 166;
		while($row = db_fetch_object($res))
		{
			if($status == $row->lid)
				$selected = 'selected="selected"';
			$output .= '<option value="'.$row->lid.'" '.$selected.'>'.ucwords($row->lname).'</option>';
			$selected = '';
		}
$output .= '</select>';

if($schemeid)
{
	if($LOI == 1 && $LOI_doc)
		$selected = 'checked = "checked"';
$output .= <<<EOD
	<br><br>
	<input type="checkbox" name="LOI" $selected id="LOIid" /> LOI Received ( Mandatory if approved or rejected )<br>
	<input type="file" size="40" id="edit-upload" class="form-file" name="LOI_doc" id="LOI_docid"/><div class="description">(Optional)</div><br>
EOD;
	$output .= 'LOI : <a href="'.$base_path.'sites/default/files/loan/'.$LOI_doc.'">'.$LOI_doc.'</a>';
$output .= <<<EOD
	<input type="hidden" value="$s->LOI_doc" name="LOI_file" id="LOI_fileid">
	<div class="description" style="width:390px;">The maximum upload size is <em>1 MB</em>. Only files with the following extensions may be uploaded: <em>zip,rar,doc,txt,docx,xls,ppt,xlsx,pptx</em>. </div>

</div>
EOD;
}


$output .= <<<EOD
	</td>
	<td><div class="loantext">Processing Fee: <div class="description">( If applicable )</div></div><div class='loanform'><input type="text" name="processing_fee" id="processing_feeid" value="$processing_fee" maxlength="6" onkeypress="return paypaymain_custom(event,'processing_feeid',6);"  />
	  &nbsp;<div class="description">( in %age )</div></div></td>
</tr>
</table>
<br>
<table width="100%" cellpadding="2" cellspacing="1" border="0" id="loan_classid">	
	<tr class="oddrow">	
		<td align="center" colspan=4><h2>Loan Scheme Class Entry</h2></td>   	
	</tr>
EOD;
$imgpath = drupal_get_path('theme','scst');
	if($schemeid && !isset($_POST['class']))
	{
		$ccounter = 0;
		$query = "SELECT * FROM tbl_scheme_class WHERE scheme_id = $schemeid ";
		$res = db_query($query);
		while($row = db_fetch_object($res))
		{
        	if($ccounter % 2)
            	$cl = 'oddrow';
            else
            	$cl = 'evenrow';
$output .= <<<EOD
				<tr class="$cl" id="lc$row->sid">
					<td class="form-text1"><div class="loantext">Class Name: <span title="This field is required." class="form-required">*</span></div>
					<div class="loanform"><input type="text" name="class[]" value="$row->class" id="cid$ccounter" maxlength="45" onKeyPress="return alphabet(event);" /></div></td>
					<td class="form-text1"><div class="loantext">ROI: <span title="This field is required." class="form-required">*</span></div>
					<div class="loanform"><input type="text" name="ROI[]"  id="rid$ccounter" value="$row->ROI" maxlength="6" onkeypress="return paypaymain_custom(event,'rid$ccounter',6);" /></div><img src="$base_url/$imgpath/images/b_drop.png" onclick="return remove_loanclass('lc$row->sid');" style="cursor:pointer;" /></td>
				</tr>
EOD;
			$ccounter++;
		}
	}else{
		if($_POST['class'])
		{
        	$cnum = 0;
			foreach($_POST['class'] as $key => $val)
			{
                if($cnum % 2)
                    $cl = 'oddrow';
                else
                    $cl = 'evenrow';
				$ROI = $_POST['ROI'][$key];
				$output .= <<<EOD
					<tr class="$cl" id="lc$key">
						<td class="form-text1"><div class="loantext">Class Name: <span title="This field is required." class="form-required">*</span></div>
						<div class="loanform"><input type="text" name="class[]"  id="cid$key" value="$val" maxlength="45" onKeyPress="return alphabet(event);" /></div>
						</td>
						<td class="form-text1"><div class="loantext">ROI: <span title="This field is required." class="form-required">*</span></div>
							<div class="loanform"><input type="text" name="ROI[]" id="rid$key" value="$ROI" maxlength="6" onkeypress="return paypaymain_custom(event,'rid$key',6);" /></div> <img src="$base_url/$imgpath/images/b_drop.png" onclick="return remove_loanclass('lc$key');" style="cursor:pointer;" /></td>
					</tr>
EOD;
            	$cnum++;
			}
		}else{
$output .= <<<EOD
	<tr class="evenrow" id="lc0">
		<td class="form-text1"><div class="loantext">Class Name: <span title="This field is required." class="form-required">*</span></div>
		<div class="loanform"><input type="text" name="class[]"  id="cid0" value="" maxlength="45" onKeyPress="return alphabet(event);" /></div>
		</td>
		<td class="form-text1"><div class="loantext">ROI: <span title="This field is required." class="form-required">*</span></div>
			<div class="loanform"><input type="text" name="ROI[]" id="rid0" value="" maxlength="6" onkeypress="return paypaymain_custom(event,'rid0',6);" /></div><img src="$base_url/$imgpath/images/b_drop.png" onclick="return remove_loanclass('lc0');" style="cursor:pointer;" /></td>
	</tr>
EOD;
		}
}
$output .= <<<EOD
    </table><table cellpadding="0" cellspacing="0" border="0">
	<tr class="oddrow"><td colspan="2" align="center"><input type="button" class="form-submit" value="Add Loan Class" id="submit" name="als" onClick="addMoreField('$base_url','$imgpath');"/></td></tr>		</table>


EOD;
	if($schemeid)
	{
		$output .= '<input type="hidden" value="'.$schemeid.'" name="sid" id="sid">';
	}
$output .= <<<EOD
<table width="100%" cellpadding="2" cellspacing="1" border="0">	
<tr class="evenrow"><td colspan="4" align="center" class="back">
<input type="hidden" value="Bank" name="loan_typetext" id="loan_typetextid">
<input type="hidden" value="" name="eligibilitydocs" id="eligibilitydocsid">
<input type="hidden" value="1" name="updatescheme">
EOD;
$output .= l("Back",'loan/listscheme');
$output .= <<<EOD
<input type="submit" class="form-submit" value="Save" id="submit" name="ls"/>

</td>
</tr>
</table>
</form>
</div>
EOD;

return $output;
}

function updateScheme()
{
	global $user;
	global $base_path;
	$fields = '';
	$valuestr = '';
	
 


	$error = validateSchemeForm();
	if(!$error)
	{
		$eligibility_criteria = serialize(trim($_POST['eligibilitydocs'],','));
		
        $result = db_query("SHOW COLUMNS FROM tbl_scheme_master");
        if (!$result) {
            return 'Could not run query: ' . db_error();
            
        }		
        while ($row = db_fetch_object($result)) {
			if($row->Field)
			{
				foreach($_POST as $key=>$val)
				{
					if($key == $row->Field)
					{
						if(isset($_POST['sid']))
						{
							$updatestr .= $key." = '".db_escape_string($val)."',";
						}else{
							$fields .= $key.',';
							$valuestr .= "'".db_escape_string($val)."',";
						}
					}
				}
				//$fieldarr[] = $row->Field;
			}
		}
		if(isset($_POST['sid']))
		{
			$updatestr .= "eligibility_criteria = '".$eligibility_criteria."',updatedby = '".$user->uid."',updatedon = '".time()."'";
			if(!isset($_POST['LOI']))
				$updatestr .= ",LOI = 0";
			else
				$updatestr .= ",LOI = 1";
			$update_statement = "UPDATE tbl_scheme_master SET $updatestr WHERE loan_scheme_id = ".$_POST['sid'];
			db_query($update_statement) or die("There is some error updating data");
		}else{
	
			$query = "SELECT l.lookup_id as lid, l.lookup_name as lname FROM tbl_lookuptypes lt,tbl_lookups l WHERE lt.lookupType_id = l.lookupType_id AND l.status = 1 AND lt.lookupType_name = 'Loan Scheme Status'";
			$res = db_query($query);
			$status = 0;
			while($row = db_fetch_object($res))
			{
				if($row->lname == 'Pending for approval')
					$status = $row->lid;
			}
	
			$fields .= "eligibility_criteria,status,active,createdby,createdon";
			$valuestr .= "'".$eligibility_criteria."','".$status."','1','".$user->uid."','".time()."'";
			//$fields = trim($fields,',');
			//$valuestr = trim($valuestr,',');
			$insert_statement = "INSERT INTO tbl_scheme_master ($fields) VALUES ($valuestr)";
			db_query($insert_statement) or die("There is some error inserting data");
			
			$liid = db_last_insert_id('tbl_scheme_master','loan_scheme_id');
		}
		if(isset($_POST['sid']))
		{
			$liid = $_POST['sid'];
			$delstmt = "DELETE FROM tbl_scheme_class WHERE scheme_id = ".$_POST['sid'];
			db_query($delstmt);
		}		
		foreach($_POST['class'] as $key => $val)
		{
			$stmt = "SELECT count(*) FROM tbl_scheme_class WHERE scheme_id = '".$liid."' AND class = '".$val."'";
			$rec = db_result(db_query($stmt));
			if($rec > 0)
			{
				drupal_set_message("A loan scheme class( $val ) is already exist ! Please enter some other name.");
			}else{
				if($val && $_POST['ROI'][$key])
				{
					$insert_statement = "INSERT INTO tbl_scheme_class (scheme_id,class,ROI) VALUES ('".$liid."','".$val."','".$_POST['ROI'][$key]."')";
					db_query($insert_statement) or die("There is some error inserting data");
				}
			}
		}
		if(isset($_POST['sid']))
		{
			if(isset($_FILES['LOI_doc']) && $_FILES['LOI_doc']['name'])
			{
                $extension = fileexts($_FILES['LOI_doc']['name']);
                $path_info = pathinfo($_FILES['LOI_doc']['name']);
                $ext = $path_info['extension'];
                @chmod('sites/default/files/loan','0777');
                if(is_file('sites/default/files/loan/'.$_POST['LOI_file']))
                    @unlink('sites/default/files/loan/'.$_POST['LOI_file']);
                $filename = 'LOI'.$_POST['sid'].'_'.time().'.'.$ext;
                @move_uploaded_file($_FILES['LOI_doc']['tmp_name'],'sites/default/files/loan/'.$filename);
                //chmod('sites/default/files/loan/'.$filename,'0777');
                $sql = "UPDATE tbl_scheme_master SET LOI_doc = '".$filename."' WHERE loan_scheme_id = ".$_POST['sid'];
                db_query($sql);
                
            }
            $message = getMessage('loanaddscheme', 'code02',array("0"=>$_REQUEST['scheme_name']));
            drupal_set_message($message);
            drupal_goto('loan/listscheme');
		}else{
			$message = getMessage('loanaddscheme', 'code01',array("0"=>$_REQUEST['scheme_name']));
			drupal_set_message($message);
			drupal_goto('loan/listscheme');
		}
	}else{
		return $error;
		//echo '<script language="javascript">if (document.readyState === "complete") {'.$error.'}</script>';
	}
}
//Server validation
function validateSchemeForm()
{
	$errorstr = '';
	$scriptcss = '';
	if(!$_POST['scheme_code'])
	{
		$errorstr = 1;
		form_set_error('scheme_code','Scheme code field is required.');
		$scriptcss .= '$("input[name=scheme_code]").addClass("error");';
	}else{
    	if(strlen($_POST['scheme_code']) < 3 || strlen($_POST['scheme_code']) > 3)
        {
            $errorstr = 1;
            form_set_error('scheme_code','Scheme code field should be 3 digit.');
            $scriptcss .= '$("input[name=scheme_code]").addClass("error");';
        }
    }
    if($_POST['scheme_name'])
    {
        $stmt = "SELECT count(*) FROM tbl_scheme_master WHERE scheme_name = '".$_POST['scheme_name']."'";
        if(isset($_POST['sid']))
            $stmt .= " AND loan_scheme_id != '".$_POST['sid']."'";
        $rec = db_result(db_query($stmt));
        if($rec > 0)
        {
            form_set_error('scheme_name',"A loan scheme with same name exist ! Please enter some other name.");
            $scriptcss .= '$("#schemenameid").addClass("error");';
        }
    }
    if($_POST['scheme_code'])
    {
        $stmt = "SELECT count(*) FROM tbl_scheme_master WHERE scheme_code = '".$_POST['scheme_code']."'";
        if(isset($_POST['sid']))
            $stmt .= " AND loan_scheme_id != '".$_POST['sid']."'";
        $rec = db_result(db_query($stmt));
        if($rec > 0)
        {
            form_set_error('scheme_code',"A loan scheme with same scheme code exist ! Please enter some other scheme code.");
            $scriptcss .= '$("input[name=scheme_code]").addClass("error");';
        }
    }
    if($_POST['processing_fee'] != '')
    {
        if(paypay('processing_fee',$_POST['processing_fee'],'Processing Fee'))
        {
            $scriptcss .= '$("#processing_feeid").addClass("error");';
    
        }else if(floatval($_POST['processing_fee']) > 100)
        {
            form_set_error('processing_fee','Processing Fee can not exceed 100.');
            $scriptcss .= '$("#processing_feeid").addClass("error");';
        }
    }
	if(!$_POST['scheme_name'])
	{
		$errorstr = 1;
		form_set_error('scheme_name','Scheme name field is required.');
        $scriptcss .= '$("#schemenameid").addClass("error");';
	}else{
    	if(alphabet('scheme_name',$_POST['scheme_name'],'Scheme Name'))
        {
            $errorstr = 1;
            $scriptcss .= 'document.addschemeform.scheme_name.className="error";';
        }
    	if(strlen($_POST['scheme_name']) > 100)
        {
            $errorstr = 1;
            form_set_error('scheme_name','Scheme Name field is too long. Please make sure it should contain maximum of 100 letters.');
            $scriptcss .= '$("select[name=scheme_name]").addClass("error");';
        }
    }
	if(!$_POST['main_scheme'])
	{
		$errorstr = 1;
		form_set_error('main_scheme','Main scheme name field is required.');
		$scriptcss .= '$("select[name=main_scheme]").addClass("error");';
	}
	if(!$_POST['sector'])
	{
		$errorstr = 1;
		form_set_error('sector','Sector field is required.');
		$scriptcss .= '$("select[name=sector]").addClass("error");';
	}
	if(!$_POST['loan_type'])
	{
		$errorstr = 1;
		form_set_error('loan_type','Loan type field is required.');
		$scriptcss .= '$("select[name=loan_type]").addClass("error");';
	}
	if(!$_POST['tenure'])
	{
		$errorstr = 1;
		form_set_error('tenure','Tenure field is required.');
		$scriptcss .= '$("select[name=tenure]").addClass("error");';
	}
	if(!$_POST['fund_source'])
	{
		$errorstr = 1;
		form_set_error('fund_source','Fund source field is required.');
		$scriptcss .= '$("select[name=fund_source]").addClass("error");';
	}
	if(!trim($_POST['eligibilitydocs'],','))
	{
		$errorstr = 1;
		form_set_error('eligibility','Document field is required.');
		$scriptcss .= '$("select[name=eligibility]").addClass("error");';
	}
	if(trim($_POST['project_cost']) == '')
	{
		$errorstr = 1;
		form_set_error('project_cost','Project cost field is required.');
		$scriptcss .= '$("input[name=project_cost]").addClass("error");';
	}elseif(trim($_POST['project_cost']) != 0 && trim($_POST['project_cost']) != '0.00'){
    	if(paypay('project_cost',$_POST['project_cost'],'Project cost'))
        {
            $scriptcss .= '$("input[name=project_cost]").addClass("error");';
        }
    }
	if($_POST['apex_share'] == '')
	{
		$errorstr = 1;
		form_set_error('apex_share','Apex share field is required.');
		$scriptcss .= '$("input[name=apex_share]").addClass("error");';
	}elseif(($_POST['apex_share'] != '0' && $_POST['apex_share'] != '0.00')){
    	if(paypay('apex_share',$_POST['apex_share'],'Apex share'))
        {
            $scriptcss .= '$("input[name=apex_share]").addClass("error");';
        }
    }
	if($_POST['corp_share'] == '')
	{
		$errorstr = 1;
		form_set_error('corp_share','Corporation share field is required.');
		$scriptcss .= '$("input[name=corp_share]").addClass("error");';
	}
    if($_POST['corp_share']){
  
    	if(paypay('corp_share',$_POST['corp_share'],'Corporation share'))
        {
            
            $scriptcss .= '$("input[name=corp_share]").addClass("error");';
        }
    }
/*	if(!$_POST['corp_share'])
	{
		$errorstr = 1;
        if($_POST['corp_share'] == 0)
        	form_set_error('corp_share','Corporation share field can not be 0.');
        else
			form_set_error('corp_share','Corporation share field is required.');
		$scriptcss .= '$("input[name=corp_share]").addClass("error");';
	}else{
    	if(paypay('corp_share',$_POST['corp_share'],'Corporation share'))
        {
            $scriptcss .= '$("input[name=corp_share]").addClass("error");';
        }
    }
*/
    if(floatval($_POST['apex_share']) && floatval($_POST['corp_share']))
    {
    	$total = $_POST['apex_share'] + $_POST['corp_share'];
        if($total > 100)
        {
            form_set_error('apex_share','Sum of Apex share and Corporation share can not exceed 100%.');
            $scriptcss .= '$("input[name=apex_share]").addClass("error");';
            $scriptcss .= '$("input[name=corp_share]").addClass("error");';
        }
    }
	if($_POST['capital_subsidy'] == '')
	{
		$errorstr = 1;
		form_set_error('capital_subsidy','Capital subsidy field is required.');
		$scriptcss .= '$("input[name=capital_subsidy]").addClass("error");';
	}
	if($_POST['loan_type'] == 147 && !$_POST['MMD_FDR'])
	{
		$errorstr = 1;
		form_set_error('MMD_FDR','MMD FDR field is required.');
		$scriptcss .= '$("input[name=MMD_FDR]").addClass("error");';
	}else if($_POST['loan_type'] == 147 && $_POST['MMD_FDR']){
    	if(paypay('MMD_FDR',$_POST['MMD_FDR'],'MMD FDR'))
        {
            $scriptcss .= '$("input[name=MMD_FDR]").addClass("error");';
        }
    }
	if(!$_POST['interest_type'])
	{
		$errorstr = 1;
		form_set_error('interest_type','Interest calculation field is required.');
		$scriptcss .= '$("select[name=interest_type]").addClass("error");';
	}
	if(!$_POST['frequency'])
	{

		$errorstr = 1;
		form_set_error('frequency','Frequency field is required.');
		$scriptcss .= '$("select[name=frequency]").addClass("error");';
	}
	if(isset($_POST['status']))
	{
    	if(!isset($_POST['LOI_file']) || !$_POST['LOI_file'])
        {
            if($_POST['status'] == 167 && (!isset($_POST['LOI']) || !$_FILES['LOI_doc']['name']))
            {
                $errorstr = 1;
                form_set_error('status','LOI received field is mandatory if status is approved and please ensure that LOI check box is checked properly.');
                $scriptcss .= '$("input[name=LOI_doc]").addClass("error");';
                $scriptcss .= '$("input[name=LOI]").addClass("error");';
            }
        }else if(!isset($_POST['LOI']) && $_FILES['LOI_doc']['name']){
            $errorstr = 1;
            form_set_error('status','LOI received field is mandatory if status is approved and please ensure that LOI check box is checked properly.');
            $scriptcss .= '$("input[name=LOI_doc]").addClass("error");';
        }
		if((isset($_FILES['LOI_doc']) && $_FILES['LOI_doc']['name']) && $_POST['status'] == 166)
		{
            form_set_error('status','Status field should be Approved if LOI recieved field is filled.');
            $scriptcss .= '$("select[name=status]").addClass("error");';
		}
	}

//print_r($_FILES);exit;
    if(isset($_POST['sid']))
    {
        if(isset($_FILES['LOI_doc']) && $_FILES['LOI_doc']['name'])
        {
			//$path_info = pathinfo($_FILES['LOI_doc']['name']);
			//$extension = '.'.$path_info['extension'];
            $extension = fileexts($_FILES['LOI_doc']['name']);
            if($extension == '.pdf' || $extension == '.zip' || $extension == '.rar'|| $extension == '.doc'|| $extension == '.txt' || $extension == '.docx' || $extension == '.xls' || $extension == '.xlsx' || $extension == '.ppt' || $extension == '.pptx')
            {
				if($_FILES['LOI_doc']['size'] > 0)
				{
					$fsize = $_FILES['LOI_doc']['size']/1048576;
					if($fsize > 1)
					{
						form_set_error('LOI_doc','Please upload file with max size of 1 MB');	
						$scriptcss .= '$("input[name=LOI_doc]").addClass("error");';
					}
				}else{
					form_set_error('LOI_doc','Please upload file with max size of 1 MB');	
					$scriptcss .= '$("input[name=LOI_doc]").addClass("error");';
				}
            }else{
                form_set_error('LOI_doc','Please upload file with extension zip,rar,doc,txt,docx,xls,ppt,xlsx,pptx');	
                $scriptcss .= '$("input[name=LOI_doc]").addClass("error");';
            }
		}
	}               
    $classarr = array_count_values($_POST['class']);
	foreach($_POST['class'] as $key => $val)
	{
        if($val && $classarr[$val] > 1)
        {
            form_set_error('class[$key]','A loan scheme class( '.$val.' ) is already exist ! Please enter some other name.');
            $scriptcss .= '$("#cid'.$key.'").addClass("error");';
        }
    	if(trim($val) && alphabet('class',$_POST['class'][$key],'Class Name'))
        {
            $errorstr = 1;
            $scriptcss .= '$("#cid'.$key.'").addClass("error");';
        }
		if(trim($val) && trim($_POST['ROI'][$key]) != '')
		{
        	if(floatval($_POST['ROI'][$key]) == 0)
            {
            	form_set_error('ROI[$key]','ROI can not be 0.');
                $scriptcss .= '$("#rid'.$key.'").addClass("error");';
            }elseif(paypay('ROI[$key]',$_POST['ROI'][$key],'ROI'))
            {
                $scriptcss .= '$("#rid'.$key.'").addClass("error");';
            }
            if(floatval($_POST['ROI'][$key]) > 99.99)
            {
                form_set_error('ROI[$key]','ROI should not be 100 or more than 100.');
                $scriptcss .= '$("#rid'.$key.'").addClass("error");';
            }
			$blank = 1;
		}
	}
	if(isset($more))
	{
		$errorstr = 1;
		form_set_error('class','ROI should not be 100 or more than 100.');
        $scriptcss .= '$("#rid'.$key.'").addClass("error");';
	}
	if(!isset($blank))
	{
		$errorstr = 1;
		form_set_error('class[0]','Atleast one pair of class and ROI is required.');
		$scriptcss .= '$("#cid0").addClass("error");';
		$scriptcss .= '$("#rid0").addClass("error");';
	}
	return $scriptcss;
}

