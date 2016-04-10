<?php
//include('includes/SQL_CommonFunctions.inc');
	global $root_path, $xpathObj, $emailObj;
	
	$emailObj = simplexml_load_file("email_config.xml");


function createMail($module = '',$to = '',$cc = '',$parameter = '',$db)
{
	global $emailObj;
	$bcc = '';
	$from_name = '';
	$to_name = '';
	$from = '';
	$fieldstr = "subject, body, emailfrom, emailto, cc, bcc, attachment, from_name, to_name, priority";
	foreach($emailObj->children() as $node => $child)
	{
		
		if($child[0]['name'] == $module)
		{
			foreach($child->children() as $nchild)
			{
				if($nchild[0]['id'] == 'Body')
				{
					$body = $nchild;
				}
				if($nchild[0]['id'] == 'Subject')
				{
					$subject = $nchild;
				}
				if($nchild[0]['id'] == 'Prority')
				{
					$priority = $nchild;
				}
			}
			if($parameter)
			{
				$parameterarr = json_decode($parameter);
				for($i = 0; $i < count($parameterarr); $i++){
					$body = str_replace("{".$i."}", $parameterarr[$i], $body);
				}	
			}
			$valuestr = "'".$subject."','".$body."','".$from."','".$to."','".$cc."','".$bcc."','".$attachment."','".$from_name."','".$to_name."','".$priority."'";
			 $sqll = "INSERT INTO tbl_email ($fieldstr) VALUES ($valuestr)";
			$sqllq=DB_query($sqll,$db);
			
			if(!$sqllq)
				return 0;
			else
				return 1;
		}
	}
}
?>