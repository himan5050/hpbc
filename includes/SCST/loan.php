<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$action=$_GET['action'];
$q=$_GET['q'];
$loaneeid=isset($_GET['lid'])?$_GET['lid']:'';


switch($action)
{
	case 'tehsil':
			$output = '<option value="">Select</option>';
			$sql = "SELECT tehsil_id, tehsil_name FROM tbl_tehsil WHERE district_id = $q";
			$res = db_query($sql);
			while($row = db_fetch_object($res))
			{
				$output .= '<option value="'.$row->tehsil_id.'">'.$row->tehsil_name.'</option>';
			}
			echo $output;
		break;
	case 'caste':
			$output = '<option value="">Select</option>';
			$sql = "SELECT cast_id, cast_name FROM tbl_cast WHERE religion_id = $q ORDER BY cast_name";
			$res = db_query($sql);
			while($row = db_fetch_object($res))
			{
				$output .= '<option value="'.$row->cast_id.'">'.$row->cast_name.'</option>';
			}
			echo $output;
		break;
	case 'block':
			$output = '<option value="">Select</option>';
			$query = "SELECT block_id, block_name FROM tbl_block WHERE status = 1 AND tehsil_id = $q ORDER BY block_name";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				$output .= '<option value="'.$row->block_id.'">'.$row->block_name.'</option>';
			}
			echo $output;
		break;
	case 'panchayat':
			$output = '<option value="">Select</option>';
			$query = "SELECT panchayt_id, panchayt_name FROM tbl_panchayt WHERE status = 1 AND block_id = $q";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				$output .= '<option value="'.$row->panchayt_id.'">'.$row->panchayt_name.'</option>';
			}
			echo $output;
		break;
	case 'scheme':
			//$output = '<option value="">Select</option>';
			$query = "SELECT project_cost, promoter_share, apex_share, corp_share FROM tbl_scheme_master WHERE loan_scheme_id = '".$q."' AND status = 167";
			$res = db_query($query);
			$row = db_fetch_object($res);
			$loanamount = $row->project_cost -($row->project_cost * $row->promoter_share / 100);
			echo $loanamount.",".$row->promoter_share.",".$row->apex_share.",".$row->corp_share;
		break;
	case 'schemedocument':
			//$output = '<option value="">Select</option>';
			$output = '<table style="border:none;">';
			$stmt = "SELECT eligibility_criteria FROM tbl_scheme_master WHERE loan_scheme_id = '".$q."' AND status = 167 AND active = 1";
			$result = db_query($stmt);
			$row = db_fetch_object($result);
			$docs = unserialize($row->eligibility_criteria);
			$query = "SELECT loanDoc_id, loanDoc_name FROM tbl_loanDocs WHERE FIND_IN_SET(loanDoc_id,'".$docs."') AND status = 1";
			$res = db_query($query);
			while($row = db_fetch_object($res))
			{
				$selected = '';
				$append = '';
				if($d[$row->loanDoc_id])
				{
					$selected = 'checked = "checked"';
					$append = '<div id="df'.$row->loanDoc_id.'">'.$d[$row->loanDoc_id].'<span style="cursor:pointer;" onclick="return removeloanfile('.$row->loanDoc_id.','.$s->loanee_id.','.$row->loanDoc_id.');"> | Remove</span><input type="hidden" name="documentexist['.$row->loanDoc_id.']" value="'.$d[$row->loanDoc_id].'"></div>';
				}
				$output .= '<tr><td><input type="checkbox" value="'.$row->loanDoc_id.'" name="document[]" '.$selected.'>&nbsp;'.$row->loanDoc_name.'</td><td><input type="file" size="40" id="edit-upload" class="form-file" name="doc'.$row->loanDoc_id.'"/> ( Optional ) '.$append.' </td></tr>';
			}
			$output .= '</table>';
			echo $output;exit;
		break;
	case 'removefile':
			$query = "SELECT loan_file FROM tbl_loan_documents WHERE loanee_id = $loaneeid AND document_id = $q";
			$res = db_query($query);
			$lfile = db_fetch_object($res);
			$query = "DELETE FROM tbl_loan_documents WHERE loanee_id = $loaneeid AND document_id = $q";
			if(db_query($query))
			{
				if(is_file('sites/default/files/loan/'.$lfile->loan_file))
					@unlink('sites/default/files/loan/'.$lfile->loan_file);
			}
			echo '1';
		break;
	
}

?>