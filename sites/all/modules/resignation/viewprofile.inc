<?php 

function profile_page($empid){

$array = explode(',',$_GET['q']);
	$breadcrumb = array();
	$breadcrumb[] = l('Home', '<front>');
	$breadcrumb[] = l('View Profile', 'viewprofile/'.$empid);
	drupal_set_breadcrumb($breadcrumb);

if(getRole($user->uid) == 13){$level = 1;}
		else if(getRole($user->uid) == 6){$level = 2;}
		else if(getRole($user->uid) == 19){$level = 3;}


$ssql ="select * from tbl_resignation where emp_id='".getempidtouid($empid)."'";
$sres = db_query($ssql);
$srs =db_fetch_object($sres);

$sepsql = "select * from tbl_separation where emp_id='".$empid."'";
$sepres = db_query($sepsql);
$seprs = db_fetch_object($sepres);
if($seprs){
	$lk = '';
}
else{
  if($srs->status != 15 && $srs->status != ''){
	$lk =l('Cancel Resign','cresignation/'.$empid);
	}
 if($srs->status == 10){$lk= l('Cancel Resign','cresignation/'.$empid);}
	
	else if($srs->status == 0){
		if(getRole($user->uid) != 6)
			$lk= l('Resign','resignation/'.$empid);
	}
		
}
global $user;

if(getRole($user->uid) == 13){
	$idtemp = 'program_uid';
}else{
	$idtemp = 'employee_id';	
} 


   $sql="select * from tbl_joinings where employee_id ='".$empid."'";

 $res = db_query($sql);
 $rs = db_fetch_object($res);
 //echo '<pre>';
  //print_r($rs);
 //echo '<pre>';exit;
 if($rs->gender =='f'){
  $gender ='Female';
}else{
  $gender ='Male';
}
 if($rs->statusnodal =='0'){
  $statusnodal ='Disabled';
}else{
  $statusnodal ='Enabled';
}


$nationality=getLookupName($rs->nationality);
$religion=getLookupName($rs->religion);

$cas=db_query("select cast_name from tbl_cast where cast_id='".$rs->caste."'");
$cass=db_fetch_object($cas);
$caste=$cass->cast_name;

$employee_type=getLookupName($rs->employee_type);
$designationid=getLookupName($rs->current_designationid);
$Departmentid=getLookupName($rs->current_Departmentid);
$gender=getLookupName($rs->gender);
$add_type=getLookupName($rs->add_type);
$status2=getLookupName($rs->status2);





$sqlo = "SELECT corporation_name  FROM tbl_corporations WHERE corporation_id='".$rs->current_officeid."'";
$reso = db_query($sqlo);
$rso = db_fetch_object($reso);

$basic_pay= $rs->basic_pay;
$grade_pay= $rs->grade_pay;
if($rs->mobile == ''){
  $mobile ='NA';
}else{
  $mobile = $rs->mobile;
}
if($rs->extn == ''){
  $exten = 'NA';
}else{
  $exten = $rs->extn;
}
if($rs->pincode ==''){
  $pincode = 'NA';
}else{
  $pincode = $rs->pincode;
}
if($rs->employee_id ==''){
  $unique = 'NA';
}else{
  $unique = $rs->employee_id;
}

if($rs->field_medical_examination==0){
	$field_medical_examination= 'Yes';
		$field_medical_upload= $rs->field_medical_upload;
	}else{
	$field_medical_examination= 'No';
		$field_medical_upload= 'N/A';
	}
	
	

if($rs->field_character_certificate==0){
	$field_character= 'Yes';
	    $field_character_upload= $rs->field_character_upload;
	}else{
	$field_character= 'No';
	    $field_character_upload= 'N/A';
	}
	
	if($rs->field_allegiance==0){
	$field_allegiance= 'Yes';
	$field_allegiance_upload= $rs->field_allegiance_upload;
	}else{
	$field_allegiance= 'No';
	$field_allegiance_upload= 'N/A';
	}
	
	if($rs->field_oath==0){
	$field_oath= 'Yes';
		$field_oath_upload= $rs->field_oath_upload;
	
	}else{
	$field_oath= 'No';
		$field_oath_upload= 'N/A';
	
	}
	

if($rs->field_verification==0){
	$field_verification= 'Yes';
	$field_verification_upload= $rs->field_allegiance_upload;
	}else{
	$field_verification= 'No';
	$field_verification_upload= 'N/A';
	}
	if($rs->field_marital==0){
	$field_marital= 'Yes';
	$field_marital_upload= $rs->field_allegiance_upload;
	}else{
	$field_marital= 'No';
	$field_marital_upload= 'N/A';
	}
	
	

	if($rs->field_declaration==0){
	$field_declaration= 'Yes';
	$field_declaration_upload= $rs->field_declaration_upload;
	}else{
	$field_declaration= 'No';
	$field_declaration_upload= 'N/A';
	}
	
	
	if($rs->field_gpf==0){
	$field_gpf= 'Yes';
	$field_gpf_upload= $rs->field_gpf_upload;
	}else{
	$field_gpf= 'No';
	$field_gpf_upload= 'N/A';
	}
	if($rs->field_family==0){
	$field_family= 'Yes';
	$field_family_upload= $rs->field_family_upload;
	}else{
	$field_family= 'No';
		$field_family_upload= 'N/A';
}
	
	
	if($rs->field_dcr==0){
	$field_dcr= 'Yes';
	$field_dcr_upload= $rs->field_dcr_upload;
	}else{
	$field_dcr= 'No';
	$field_dcr_upload= 'N/A';
	}
	if($rs->field_training==0){
	$field_training= 'Yes';
		$field_training_upload= $rs->field_training_upload;
	}else{
	$field_training= 'No';
	$field_training_upload='N/A';
	}
	
	


$output .='<table width="100%" cellpadding="2" cellspacing="1" border="0" id="form-container">';
$output .='<tr class="oddrow"><td colspan="2" align="center"><h2>Profile Details</h2></td></tr>';
//$output .='<tr class="evenrow"><td width="50%">UID:</td><td class="normal">'.$unique.'</td></tr>';
//$rid = getRole($rs->program_uid);
$output .='<tr class="evenrow"><td width="50%">Employee Id:</td><td class="normal">'.ucwords($rs->employee_id).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Employee Name:</td><td class="normal"> '.ucwords($rs->employee_name).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Father Name:</td><td class="normal"> '.ucwords($rs->father_name).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Nationality:</td><td class="normal"> '.ucwords($nationality).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Date of Birth:</td><td class="normal">'.date('d-m-Y',strtotime(substr($rs->dob,0,10))).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Date of Joining:</td><td class="normal">'.date('d-m-Y',strtotime($rs->doj)).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Basic Pay:</td><td class="normal">'.$basic_pay.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Grade Pay:</td><td class="normal">'.$grade_pay.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Employee Type:</td><td class="normal">'.ucwords($employee_type).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Religion:</td><td class="normal"> '.ucwords($religion).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Caste:</td><td class="normal"> '.ucwords($caste).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Corporation Office :</td><td class="normal">'.ucwords($rso->corporation_name).'</td></tr>';

$output .='<tr class="evenrow"><td width="50%">Education Qualification:</td><td class="normal"> '.ucwords($rs->edu_qual).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Professional Qualification:</td><td class="normal"> '.ucwords($rs->prof_qual).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Personal Mark Of Identity:</td><td class="normal"> '.ucwords($rs->mark).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Height:</td><td class="normal"> '.ucwords($rs->height).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.ucwords($designationid).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Department:</td><td class="normal">'.ucwords($Departmentid).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Employee Status:</td><td class="normal">'.ucwords($status2).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Gender:</td><td class="normal">'.ucwords($gender).'</td></tr>';
//$output .='<tr class="oddrow"><td width="50%">Address:</td><td>';
$output .='<tr class="evenrow"><td width="50%">Address Type:</td><td class="normal">'.ucwords($add_type).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Addresss Line 1:</td><td class="normal">'.ucwords($rs->add_line1).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Addresss Line 2:</td><td class="normal">'.ucwords($rs->add_line2).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">State:</td><td class="normal">'.ucwords(getState($rs->state_id)).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">District:</td><td class="normal">'.ucwords(getdistrict($rs->district_id)).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Tehsil:</td><td class="normal">'.ucwords(gettehsil($rs->tehsil_id)).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Block:</td><td class="normal">'.$rs->block.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Panchayat:</td><td class="normal">'.$rs->panchayat.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Pin code:</td><td class="normal">'.$pincode.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Phone:</td><td class="normal">'.$rs->phone.'</td></tr>';
//$output .='<tr class="evenrow"><td width="50%">Extn:</td><td class="normal">'.$exten.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Mobile:</td><td class="normal">'.$mobile.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Email Id:</td><td class="normal">'.$rs->email.'</td></tr>';
//$output .='<tr class="oddrow"><td width="50%">Fax:</td><td class="normal">'.$rs->fax.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Status:</td><td class="normal">'.$statusnodal.'</td></tr>';
// Documents

$output .='<tr class="evenrow"><td colspan="2">DOCUMENTS</td></tr>';
//$output .='<tr class="oddrow" ></tr>';

///medical
if($rs->field_medical_examination==0){

	if($rs->field_medical_upload !=''){
	$output .='<tr class="evenrow"><td width="50%">Medical Examination</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_medical_upload).'</td></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Medical Examination</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="evenrow"><td width="50%">Examined By:</td><td class="normal">'.$rs->medical_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Examined On:</td><td class="normal">'.date('d-m-Y',strtotime($rs->medical_on)).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->medical_sno.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->medical_certified_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->medical_designationid).'</td></tr>';
}
else{

$output .='<tr class="evenrow"><td width="50%">Medical Examination</td><td width="50%" class="normal">N/A</td></tr>';
}

///character

if($rs->field_character_certificate==0){

	if($rs->field_character_upload !=''){
		$output .='<tr class="oddrow"><td width="50%">Character and Antecedents</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_character_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">Character and Antecedents</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="oddrow"><td width="50%">S. No.:</td><td class="normal" class="normal">'.$rs->character_sno.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Certified By:</td><td class="normal" class="normal">'.$rs->character_certified_by.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Designation:</td><td class="normal" class="normal">'.getLookupName($rs->character_designationid).'</td></tr>';
}
else{

$output .='<tr class="oddrow"><td width="50%">Character and Antecedents</td><td width="50%" class="normal">N/A</td></tr>';
}



///allegiance

if($rs->field_allegiance==0){

	if($rs->field_allegiance_upload !=''){
		$output .='<tr class="evenrow"><td width="50%">Allegiance to the Constitution</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_allegiance_upload).'</td></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Allegiance to the Constitution</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="evenrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->allegiance_sno.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->allegiance_certified_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->allegiance_designationid).'</td></tr>';
}
else{
$output .='<tr class="evenrow"><td width="50%">Allegiance to the Constitution</td><td width="50%" class="normal">N/A</td></tr>';
}

///oath

if($rs->field_oath==0){

	if($rs->field_oath_upload !=''){
		$output .='<tr class="oddrow"><td width="50%">Oath of Secrecy</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_oath_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">Oath of Secrecy</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="oddrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->oath_sno.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->oath_certified_by.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->oath_designationid).'</td></tr>';
}
else{
$output .='<tr class="oddrow"><td width="50%">Oath of Secrecy</td><td width="50%" class="normal">N/A</td></tr>';
}




///marital 

if($rs->field_marital==0){

	if($rs->field_marital_upload !=''){
		$output .='<tr class="evenrow"><td width="50%">Marital Status</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_marital_upload).'</td></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Marital Status</td><td width="50%" class="normal">File has not been uploaded</div></td></tr>';
		}

$output .='<tr class="evenrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->marital_sno.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->marital_certified_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->marital_designationid).'</td></tr>';
}
else{
$output .='<tr class="evenrow"><td width="50%">Marital Status</td><td width="50%" class="normal">N/A</td></tr>';
}



/////declaration

if($rs->field_declaration==0){

	if($rs->field_declaration_upload !=''){
		$output .='<tr class="oddrow"><td width="50%">Declaration</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_declaration_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">Declaration</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="oddrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->declaration_sno.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->declaration_certified_by.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->declaration_designationid).'</td></tr>';
}
else{
$output .='<tr class="oddrow"><td width="50%">Declaration</td><td width="50%" class="normal">N/A</td></tr>';
}

///verification

if($rs->field_verification==0){

	if($rs->field_verification_upload !=''){
		$output .='<tr class="evenrow"><td width="50%">Verification entries in Part-I</td><td width="50% class="normal"">'.l('Download',$base_url.'/'.$field_verification_upload).'</td></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Verification entries in Part-I</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="evenrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->verification_sno.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->verification_certified_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->verification_designationid).'</td></tr>';
}
else{
$output .='<tr class="evenrow"><td width="50%">Verification entries in Part-I</td><td width="50%" class="normal">N/A</td></tr>';
}




///gpf

if($rs->field_gpf==0){

	if($rs->field_gpf_upload !=''){
		$output .='<tr class="oddrow"><td width="50%">Nomination for G.P.F</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_gpf_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">Nomination for G.P.F</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="oddrow"><td width="50%">G.P.F No.:</td><td class="normal">'.$rs->gpf_sno.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Filed Nominations:</td><td class="normal">'.$rs->gpf_nomination.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->gpf_certified_by.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->gpf_designationid).'</td></tr>';
}
else{
$output .='<tr class="oddrow"><td width="50%">Nomination for G.P.F</td><td width="50%" class="normal">N/A</td></tr>';
}




///family 

if($rs->field_family==0){

	if($rs->field_family_upload !=''){
		$output .='<tr class="evenrow"><td width="50%">Family Particulars<td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_family_upload).'</div></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Family Particulars</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="evenrow"><td width="50%">S. No.:</td><td class="normal">'.$rs->family_sno.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->family_certified_by.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->family_designationid).'</td></tr>';
}
else{
$output .='<tr class="evenrow"><td width="50%">Family Particulars</td><td width="50%" class="normal">N/A</td></tr>';
}




//dcr

if($rs->field_dcr==0){

	if($rs->field_dcr_upload !=''){
		$output .='<tr class="oddrow"><td width="50%">D.C.R gratuity and family pension</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_dcr_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">D.C.R gratuity and family pension</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}


$output .='<tr class="oddrow"><td width="50%">Filed Nominations:</td><td class="normal">'.$rs->dcr_nomination.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Certified By:</td><td class="normal">'.$rs->dcr_certified_by.'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Designation:</td><td class="normal">'.getLookupName($rs->dcr_designationid).'</td></tr>';
}
else{
$output .='<tr class="oddrow"><td width="50%">D.C.R gratuity and family pension</td><td width="50%" class="normal">N/A</td></tr>';
}




//training

if($rs->field_training==0){

	if($rs->field_training_upload !=''){
		$output .='<tr class="evenrow"><td width="50%">Training Details</td><td width="50%" class="normal">'.l('Download',$base_url.'/'.$field_training_upload).'</td></tr>';
		}else{
			$output .='<tr class="evenrow"><td width="50%">Training Details</td><td width="50%" class="normal">File has not been uploaded</td></tr>';
		}

}else{
$output .='<tr class="evenrow"><td width="50%">Training Details</td><td width="50%" class="normal">N/A</td></tr>';
}
$field_resume_upload=$rs->field_resume_upload;

	if($field_resume_upload==''){
$field_resume_upload='N/A';

	}
if($rs->field_resume_upload !=''){
		$output .='<tr class="evenrow"><td width="50%" >Resume</td><td width="50%" colspan="2" class="normal underline">'.l('Download',$base_url.'/'.$field_resume_upload).'</td></tr>';
		}else{
			$output .='<tr class="oddrow"><td width="50%">Resume</td><td width="50%" colspan="2" class="normal">File has not been uploaded</td></tr>';
		}

$output .='<tr class="evenrow"><td colspan=2>Add Nominee details</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Name</td><td width="50%" colspan="2" class="normal">'.ucwords($rs->NomineeName).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Address</td><td width="50%" colspan="2" class="normal">'.ucwords($rs->NomineeAddress).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Phone</td><td width="50%" colspan="2" class="normal">'.$rs->NomineePhone.'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Relation</td><td width="50%" colspan="2" class="normal">'.ucwords(getLookupName($rs->NomineeRelation)).'</td></tr>';
$output .='<tr class="oddrow"><td width="50%">Employee Grade</td><td width="50%" colspan="2" class="normal">'.getemployeegrade2($rs->employeegrade2).'</td></tr>';
$output .='<tr class="evenrow"><td width="50%">Biometric</td><td width="50%" colspan="2" class="normal">'.$rs->biometricfield.'</td></tr>';
$output .= '<tr class="oddrow"><td colspan="2" align="center" class="back" >'.l('Close','<front>').$lk.'</td></tr>';
$output .= '</table>';
return $output;

}

?>