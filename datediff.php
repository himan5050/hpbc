<?php
include_once './includes/bootstrap.inc';

drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

   $from=$_REQUEST["m1"];
// echo date('Ymd',strtotime('+14 days',$from));


   $to=$_REQUEST["m2"];

 echo dateDiff2($from,$to);
function dateDiff2($d1, $d2) {
// Return the number of days between the two dates:
if(strtotime($d1) > strtotime($d2)){
$out = 0;
}
else if($d1=='' && $d2){
$out =0;
}
else if($d2=='' && $d1){
$out = 1/2;
}
else{

/*$wsql="select * from tbl_lookups where lookupType_id=95";
$res= db_query($wsql);
while($rs = db_fetch_object($res)){
	if($rs->lookup_name){
		$minus += 1;
	}
	
}
*/

 $out = (round(abs(strtotime($d1)-strtotime($d2))/86400))+1;
 $firstdate = date('Y-m-d',strtotime($d1));
 $seconddate = date('Y-m-d',strtotime($d2));
 $numdays = dateDiffByDays($firstdate,$seconddate);
 $dd=0;
 $hd =0;
 for($k=1;$k<=$numdays;$k++){
     
	 if($k == 1){
	    //$fdate = explode("-",$d1);
		$fdate = $firstdate;
	    $day = date("D", strtotime($d1));
	    $wsql="select * from tbl_lookups where lookupType_id=95 AND UPPER(lookup_name)='".strtoupper($day)."' AND status=1";
	    $res= db_query($wsql);
	    if($rs = db_fetch_object($res)){
			 $dd++;
	    }
		$dto = $firstdate;
		 $hsql="select * from tbl_holidays where start_date LIKE '".date("Y-m-d",strtotime($dto))."%' AND status=1";
		$hres= db_query($hsql);
		if($hrs = db_fetch_object($hres)){
		   $hd++;
		}
			
	   
     }else{
	   //$fdate = explode("-",$dnex);
	 //  echo "here";
	    $fdate = $dnex;
	 }
//echo $k;
      $day_neshat = 60*60*24;
      $the_time = strtotime($fdate)+($day_neshat*1);
      $dnex = date('Y-m-d',$the_time);

/*
	  $tomorrow = mktime(0,0,0,$fdate[1],$fdate[0]+1,$fdate[2]);
	echo $dto = date("Y-m-d", $tomorrow);exit;
	 $dnex = date("d-m-Y", $tomorrow);

*/

	 $day = date("D", strtotime($dnex));
	 $wsql="select * from tbl_lookups where lookupType_id=95 AND UPPER(lookup_name)='".strtoupper($day)."' AND status=1";
	 $res= db_query($wsql);
	if($rs = db_fetch_object($res)){
			 $dd++;
	}else{
	  $hsql="select * from tbl_holidays where start_date LIKE '".date("Y-m-d",strtotime($dnex))."%'  AND status=1";
	  $hres= db_query($hsql);
		if($hrs = db_fetch_object($hres)){
			$hd++;
	   }
       
	}
  } 
	$out = ($numdays+1)-($dd+$hd);
}
if($out < 0){
  $out =0;
}
return $out;
}  // end function dateDiff

?>