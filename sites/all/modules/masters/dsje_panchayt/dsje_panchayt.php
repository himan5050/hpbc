<?php


    $district_id = $_POST['district_id'];
	$query = "SELECT tehsil_id, tehsil_name FROM tbl_tehsil WHERE district_id = '$district_id' AND status='1' ORDER BY tehsil_name";
	$result = db_query($query);
	
	$statearray = array();
	$statearray[''] = "--Select--";
	$counter = 0;
	while($row=db_fetch_object($result)) {
	'<option value="$row->tehsil_id">.$row->tehsil_name.</option>';
		
		$counter++;
	}
	
	if($counter==0) {
		$statearray[] = "No state in this Zone";
	}
	
	$form_state = array('submitted' => FALSE);
	$form_build_id = $_POST['form_build_id'];
	$form = form_builder('dsje_tehsil_form', $form, $form_state);
	$form['tehsil_id'] = array (
							'#type' => 'select',
							'#title' => t('Tehsil'),
							'#required' => TRUE,
							'#default_value' => $node->tehsil_id,
							'#options' => $statearray,
							'#id' => 'edit-tehsil_id',
							'#name' => 'tehsil_id',
		                    
						 );
	$output = drupal_render($form['tehsil_id']);
	return drupal_json(array('status' => TRUE, 'data' => $output));


