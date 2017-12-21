<?php
require_once './includes/bootstrap.inc';
drupal_bootstrap ( DRUPAL_BOOTSTRAP_FULL );

if ($_FILES['file']['tmp_name']){
	$dom = DOMDocument::load( $_FILES['file']['tmp_name'] );
	$rows = $dom->getElementsByTagName('Row');
	$first_row = true;
	$counter = 1;
	foreach ($rows as $row ){  //$tenure,$amount,$interest,$subsidy = 0,$loan_id = ''
		if (!$first_row ){
			$loanee_id ="";
			$index = 1;
			
			$cells = $row->getElementsByTagName( 'Cell' ); ;
			foreach( $cells as $cell ){
				$ind = $cell->getAttribute('Index');
				if ( $ind != null ) $index = $ind;
				if ( $index == 1 ) { $loanee_id  =  $cell->nodeValue   ;  }
				if ( $index == 2 ) { $tehsil_name  =  $cell->nodeValue   ;  }
				$index += 1;
			}
			
			
			$sql = db_query("SELECT tehsil_id FROM `tbl_tehsil` WHERE tehsil_name = '".$tehsil_name."'");
			$s = db_fetch_object($sql);
			if($s->tehsil_id) {
				$sql1 = db_query("UPDATE `tbl_loanee_detail` SET `tehsil` = '".$s->tehsil_id."' WHERE account_id = '".$loanee_id."'");
				echo $loanee_id.'---->';
				echo $tehsil_name.'--->';
				print_r($s->tehsil_id); echo '<BR>';
			} else {
				echo $loanee_id.'----->';
				echo $tehsil_name.'--->';
				print_r('Not Cover'); echo '<BR>';
			}
		}
		$first_row = false;
	}
}


?>
