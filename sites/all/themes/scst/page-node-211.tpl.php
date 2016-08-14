<?php
global $user, $base_url,$language;
if($user->uid > 0){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title>Control Panel | DSJE SHIMLA</title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
    <script type="text/javascript" src="<?php print $base_path . $directory; ?>/ddaccordion.js"></script>
	<script type="text/javascript" language="javascript">
		function opendashboard()
		{
			action = $("#actionid").val();
			if(action == 'up')
			{
				$("#notification_dashboard").slideUp('slow');
				$("#actionid").val('down');
			}else{
				$("#notification_dashboard").slideDown('slow');
				$("#actionid").val('up');
			}
		}
	</script>
    
    <script type="text/javascript">

ddaccordion.init({
	headerclass: "submenuheader", //Shared CSS class name of headers group
	contentclass: "submenu", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc] [] denotes no content
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: true, //persist state of opened contents within browser session?
	toggleclass: ["", ""], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["suffix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(headers, expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})


</script>
    
    
  </head>
   <body class="body">
<div class="main-container">
<div id="top-gradient"></div>
<?php print $header; ?>
<div id="header">

<div id="top">
<div id="top-left">
<div id="top-right">
<div class="hindi"><?php print $lbox; ?> </div>
<img src="<?php print $base_path . $directory; ?>/images/logo.png" width="364" height="38" hspace="0" vspace="0" alt="Himachal Backward Classes Finance And Development Corporation" title="Himachal Backward Classes Finance And Development Corporation"/>
</div>
</div>
</div>
<div class="menu-block">
<div class="top-search middle">
         <?php print $searchblock; ?>
</div>

 <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links, array('class' => 'menu-nav')) ?>
        <?php endif; ?>

</div>
</div>
<!-- top header end here -->

<!-- top header -->


<!-- top header end here -->
<div id="main-contentdash">
   <div class="adminpanel">
<?php
global $user;

  $username = $user->name;
   
    $jonsql = "select employee_id from tbl_joinings where program_uid ='".$user->uid."'";
	$jonres= db_query($jonsql);
	$jonrs = db_fetch_object($jonres);
	$empid=$jonrs->employee_id;
	
 $guestrolesql="select * from tbl_guestuser where username='".$username."'";
	 $guestres=db_query($guestrolesql);
	 $guestrs=db_fetch_object($guestres);
	 $guestid=$guestrs->email;
	 
	 $sqlvendor = "select * from {tbl_vendor} where username = '".$username."'";

 $resvendor = db_query($sqlvendor);
 $rsvendor = db_fetch_object($resvendor);
 $vendorid= $rsvendor->vendor_id;
 $vendoremail= $rsvendor->email;
 
 $sqllokmitra = "select * from {tbl_lokmitra} where username = '".$username."'";

 $reslokmitra = db_query($sqllokmitra);
 $rslokmitra = db_fetch_object($reslokmitra);
 $lokmitraid= $rslokmitra->lokmitra_id;
 $lokmitraemail= $rslokmitra->email;

if($language->language == 'hi'){
$node211 = $base_url.'/hi/welcome-deshboard';
$logout = $base_url.'/hi/logout';
$profilelink = $base_url.'/hi/viewprofile/'.$empid;
$rrofilelink = $base_url.'/hi/viewguestuser/'.$guestid;
$vendorlink = $base_url.'/hi/viewvendor/'.$vendoremail;
$lokmitralink = $base_url.'/hi/viewlokmitra/'.$lokmitraemail;
 $change_password = $base_url.'/hi/scst/resetpassword';
}else{
$node211 = $base_url.'/welcome-deshboard';
$logout = $base_url.'/logout';
$profilelink = $base_url.'/viewprofile/'.$empid;
$rrofilelink = $base_url.'/viewguestuser/'.$guestid;
$vendorlink = $base_url.'/viewvendor/'.$vendoremail;
$lokmitralink = $base_url.'/viewlokmitra/'.$lokmitraemail;
 $change_password = $base_url.'/scst/resetpassword';
 $editprofile = $base_url.'/editprofile';
 $editvender = $base_url.'/edit_vendor/'.$vendorid;
 $editlokmitra = $base_url.'/dsje/list/edit/lokmitra/'.$lokmitraid;
}



if ($user->uid != 0) {
  // code for the logout button
  echo ''.'Hi&nbsp;'.$username.'!&nbsp; | &nbsp;'."<a href='$node211'>Dashboard</a>&nbsp; | &nbsp;";
  echo "<a href=' $logout'>Log out</a>&nbsp; | &nbsp;";

 echo "<a href=' $change_password'>Change Password</a>";
 if($empid){
 echo "&nbsp; | &nbsp;<a href=' $profilelink'>View Profile</a>&nbsp; |&nbsp;<a href=' $editprofile'>Edit Profile</a>";
 }
 else if($guestid) {
echo "&nbsp; | &nbsp;<a href=' $rrofilelink'>View Profile</a>&nbsp; |&nbsp;<a href=' $editprofile'>Edit Profile</a>";	 
 }
 else if($vendorid) {
echo "&nbsp; | &nbsp;<a href=' $vendorlink'>View Profile</a>&nbsp; |&nbsp;<a href=' $editvender'>Edit Profile</a>";	 
 }
 else if($lokmitraid) {
echo "&nbsp; | &nbsp;<a href=' $lokmitralink'>View Profile</a>&nbsp; |&nbsp;<a href=' $editlokmitra'>Edit Profile</a>";	 
 }
}
else {
 //cho "<a href=\"?q=user\">Login</a>";
}
?>
</div> 
          <?php
//mysql_connect("10.10.10.27","dsjescst","dsje@321");
//mysql_select_db("dsje");



//echo "/".$u[1]."/";
//mysql_connect("localhost","root","");
//mysql_select_db("dsje");

$us= $user->uid;


$use="select rid from users_roles where uid=".$us."";
$useq=db_query($use);
$userc=db_fetch_array($useq);

 $mp="SELECT *
FROM `menu_per_role`
WHERE `rids` LIKE '%".$userc['rid']."%'";
$m="";
$mpq=db_query($mp);
while($mpr=db_fetch_array($mpq))
{
  $me[]=$mpr['mlid'];
  $m.=$mpr['mlid'].",";
}
 $mi=substr($m,0,-1);

$bl1="";
$bl2="";
$bl3="";
$i=1;
$s="select link_title,mlid,plid from menu_links where menu_name='menu-app-menu' and
plid=0 and mlid IN (".$mi.")";
$q=db_query($s);
while($r=db_fetch_array($q))
{ 
  if(($i%3)==1)
  {
  $bl1.="<div class='dashboard'><a class='menuitem submenuheader' href='#' ><div class='top'><img src='$base_path$directory/images/tp-cor-right.gif'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' alt='Himachal Pradesh' /><img src='$base_path$directory/images/tp-cor-left.gif' align='left' width='12' height='26' hspace='0' vspace='0' border='0' alt='Himachal Pradesh'/><div class='dashboardtitle'><b>".$r['link_title']."</b></div></div></a>
  
  
<div class='border'><div class='submenu'>";
  
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")   order by weight asc";
   $ssq=db_query($ss);
  
   while($ssr=db_fetch_array($ssq))
   {
   
     $bl1.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   
   $bl1.="</div></div></div>";
   }
   else if(($i%3)==2)
   {
     $bl2.="<div class='dashboard2'><a class='menuitem submenuheader' href='#' ><div class='top'><img src='$base_path$directory/images/tp-cor-right-yellow.gif' alt='image'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='$base_path$directory/images/tp-cor-left-yellow.gif' alt='image' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle'><b>".$r['link_title']."</b></div></div></a>
  
  
<div class='border'><div class='submenu'>";
 
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")   order by weight asc";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl2.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   $bl2.="</div></div></div>";
   }
   
   else if(($i%3)==0)
   {
     $bl3.="<div class='dashboard3'><a class='menuitem submenuheader' href='#' ><div class='top'><img src='$base_path$directory/images/tp-cor-right-orange.gif' alt='image'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='$base_path$directory/images/tp-cor-left-orange.gif' alt='image' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle'><b>".$r['link_title']."</b></div></div></a>
<div class='border'><div class='submenu'>";
  
   $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")  order by weight asc";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl3.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   $bl3.="</div></div></div>";
   }
   $i++;
}
echo "<table style='border:none;' align='center'>";
echo "<tr><td colspan='3'><div class='dashboard2' style='width:100%'><div class='top'><img src='$base_path$directory/images/tp-cor-right-yellow.gif'  alt='image' align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='$base_path$directory/images/tp-cor-left-yellow.gif'  alt='image' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle' onclick='opendashboard();'><b>My Task</b></div></div><div class='border'>";

?>
<input type="hidden" name="actionid" id="actionid" value="up" />

 <?php if ($show_messages && $messages): print $messages; endif; ?>

<div id="notification_dashboard">

<?php
//*************PENDING TASKS *********************


global $user;
/*$uid=$user->uid;	
$sqlrole = "select * from users_roles where uid='".$uid."'";
$res = db_query($sqlrole);
$rs = db_fetch_object($res);
$as = $rs->rid;	
	
	$dq="select * from tbl_workflow_details where role='$as'";
	$sql=db_query($dq);
	$sx=db_fetch_object($sql);
	$sdf=$sx->role;
	
	if($sdf == 38)
	{
	
	$sqlcount=db_query("SELECT COUNT(*) as task,workflow_name,home_url FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_grievance ld, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND ld.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id AND w.workflow_id = wdetail.workflow_id AND w.workflow_id = '5' AND wdetail.level = wt.level AND wt.status = 0 GROUP BY w.workflow_name");
	
	$row1=db_fetch_object($sqlcount);
	//$count=$row1->task;
	
	$count= l($row1->task." pending task(s) in ".$row1->workflow_name." section",$row1->home_url)."<br>";
	
	$sqlcount1=db_query("SELECT COUNT(*) as task,workflow_name,home_url FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_rti_management ld, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND ld.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id AND w.workflow_id = wdetail.workflow_id AND w.workflow_id = '7' AND wdetail.level = wt.level AND wt.status = 0 GROUP BY w.workflow_name");
	
	$row11=db_fetch_object($sqlcount1);
	//$count=$row1->task;
	
	$count1= l($row11->task." pending task(s) in ".$row11->workflow_name." section",$row11->home_url)."<br>";
	
	
	
	
	}
*/

$pcounter = 0;
$conditionstr = '';
$cuser = user_load($user->uid);
$roles = implode(',',array_flip($cuser->roles));
//print_r($roles);
$role = getRole($user->uid);
$corp_branch = getCorporationBranch($user->uid);
$rolecondition =  " AND wdetail.role = $role ";

if($corp_branch && ($role != 5 && $role != 6 && $role != 18 && $role != 19 && $role != 21 && $role != 10 && $role != 11 && $role != 22 && $role !=37))
{
		$conditionstr = "AND wd.corp_branch = $corp_branch";
}
if($role == 38)
{
	$rolecondition = ' AND ( w.workflow_id = 5 OR w.workflow_id = 7) ';
	$conditionstr = '';
	
}
/*if($corp_branch && ($role != 5 && $role != 6 && $role != 18 && $role != 19))
{
	if($role == 21 && $corp_branch != 12)
	{
		$conditionstr = "AND wd.corp_branch = $corp_branch";
	}
}
if($role == 38)
{
	$rolecondition = ' AND ( w.workflow_id = 5 OR w.workflow_id = 7) ';
	$conditionstr = '';
}*/
//$pendingloansql = "SELECT COUNT(*) as task,workflow_name,home_url FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND wd.workflow_id = wdetail.workflow_id AND wdetail.workflow_id = w.workflow_id AND wdetail.role = $role AND wdetail.level = wt.level AND wt.status = 0 AND w.workflow_id!=5 AND w.workflow_id!=7 $conditionstr GROUP BY w.workflow_name"; 
$pendingloansql = "SELECT COUNT(*) as task,workflow_name,home_url,wdetail.workflow_id as wid FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND wd.workflow_id = wdetail.workflow_id AND wdetail.workflow_id = w.workflow_id $rolecondition AND wdetail.level = wt.level AND wt.status = 0  $conditionstr GROUP BY w.workflow_name"; 
$res = db_query($pendingloansql);
//echo $pendingloansql;

//UID BASED TASK
//$uidsql = "SELECT COUNT(*) as uidtask,wt.uid,wdetail.workflow_id as wid FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND wd.workflow_id = wdetail.workflow_id AND wdetail.workflow_id = w.workflow_id $rolecondition AND !wt.level AND wt.uid = ".$user->uid." AND wt.status = 0 GROUP BY w.workflow_name";
$uidsql = "SELECT COUNT(*) as uidtask,wt.uid,wd.workflow_id as wid FROM tbl_workflow_task wt, tbl_workflow_docket wd WHERE wt.doc_id = wd.doc_id AND !wt.level AND wt.uid = ".$user->uid." AND wt.status = 0 GROUP BY wd.workflow_id";
//echo $uidsql;exit;
$uidres = db_query($uidsql);
while($uidrow = db_fetch_object($uidres))
{
	$uidtaskarr[$uidrow->wid] = $uidrow->uidtask;
}
?>
<table id="pendingtaskid">
<tr>
<td>
<?php
while($row = db_fetch_object($res))
{
	$pcounter++;
	$tasks = $row->task;
	if(isset($uidtaskarr))
	{
		if($uidtaskarr[$row->wid])
		{
			$tasks = $row->task + $uidtaskarr[$row->wid];
			$fuidarr[$row->wid] = 't';
		}
		
	}
/*	if($row->wid == 1 && ($role == 13 || $role == 18))
	{
		$sq = "SELECT COUNT(ld.*) FROM tbl_loan_detail ld,tbl_scheme_master sm where ld.scheme_name = sm.loan_scheme_id AND !ld.bank_acc_no AND sm.loan_type = 147 AND ld.sanction_date != '0000-00-00'";
		$resq = db_query($sq);
		$lp = db_fetch_object($resq);
		$tasks = $tasks + $lp;
	}*/
	echo l($tasks." pending task(s) in ".$row->workflow_name." section",$row->home_url)."<br>";
	
}
	foreach($uidtaskarr as $k => $v)
	{
		if(!$fuidarr[$k])
		{
			$pcounter++;
			$sql = "SELECT * FROM tbl_workflow WHERE workflow_id = '".$k."'";
			$wres = db_query($sql);
			$wn = db_fetch_object($wres);
			echo l($v." pending task(s) in ".$wn->workflow_name." section",$wn->home_url)."<br>";
		}
	}
	
if(!$pcounter)
	echo "You have no pending task(s).";
	
	
?>
</td>
<td>
<?php
/*
$ploans = 0;
$cuser = user_load($user->uid);
$roles = implode(',',array_flip($cuser->roles));
$pendingloansql = "SELECT COUNT(*) FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_loan_detail ld, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND ld.loan_docket = wd.doc_id AND wd.workflow_id = w.workflow_id AND w.workflow_id = wdetail.workflow_id AND w.workflow_name = 'loan' AND FIND_IN_SET(wdetail.role,'".$roles."') AND wdetail.level = wt.level AND wt.status = 1"; 
$res = db_query($pendingloansql);
$ploans = db_result($res);
if($ploans)
	echo "<img src=''> <a href='".$base_path."loan/listloans/pendingtask'>".$ploans." Pending Loan Applications</a>";
*/
$pcounter=0;
global $user;
$uidcommentsql = "SELECT COUNT(*) as comment,workflow_name,comment_url FROM tbl_loan_comment lc, tbl_workflow_docket wd, tbl_workflow w WHERE lc.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id  AND lc.uid='".$user->uid."' AND lc.status = 0  GROUP BY w.workflow_name"; 
$uidcommentres = db_query($uidcommentsql);
$uidcommentrs = db_fetch_object($uidcommentres);

if($uidcommentrs->workflow_name == 'resignation'){

$pcounter=1;
	echo l($uidcommentrs->comment." pending comment(s) in ".$uidcommentrs->workflow_name." section",$uidcommentrs->comment_url)."<br>";


}

else{

$pendingcommentsql = "SELECT COUNT(*) as comment,workflow_name,comment_url FROM tbl_loan_comment lc, tbl_workflow_docket wd, tbl_workflow w WHERE lc.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id  AND (FIND_IN_SET(lc.commentedto,'".$roles."') OR lc.uid = '".$user->uid."') AND lc.status = 0 $conditionstr GROUP BY w.workflow_name"; 
//$pendingcommentsql = "SELECT COUNT(*) FROM tbl_loan_comment WHERE commentedto = '".$user->uid."' AND status = 1";
$res = db_query($pendingcommentsql);
//$pcomments = db_result($res);
while($row = db_fetch_object($res))
{


	$pcounter++;
	echo l($row->comment." pending comment(s) in ".$row->workflow_name." section",$row->comment_url)."<br>";
	
}

}
if(!$pcounter)
	echo "You have no pending comment(s).";


?>
<?php



?>
</td>
</tr>
</table>
<?php
//*************PENDING TASKS END *********************





/*$sql="SELECT COUNT(*) as task  FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_grievance ld, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND ld.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id AND w.workflow_id = wdetail.workflow_id AND w.workflow_name = 'grievance' AND FIND_IN_SET(wdetail.role,'".$roles."') and wdetail.level = wt.level AND wt.status = 0"; 
$task=db_query($sql);
$szx=db_fetch_object($task);

echo "<a href='".$base_path."dsje/listgrievance/view'>".$szx->task." Pending Task</a>";
*/
 //echo $szx->task.'&nbsp;'.'Task is Pending';

 
?>

</div>



<?php

echo "</div></div></td></tr>";

echo "<tr>
			<td valign='top' width='32%'>".$bl1."</td>
			<td valign='top' width='32%'>".$bl2."</td>
			<td valign='top' width='32%'>".$bl3."</td>
</tr>
</table>";
?>
		  
<!-- footer start here-->
<div class="clr"></div>
</div>
<!-- footer start here-->
 <div id="footer" class="png">
        <div class="footer-right png">
          <div class="footer-bg png">
            <div class="space"><?php print $left; ?></div>
          </div>
        </div>
      </div>
<!-- footer end-->
</div>
</div>
<!-- main content end here-->
<?php print $closure ?>
<!-- /layout -->

  
  </body>
</html>
<?php
/*session_start();

 $pgtime= $_REQUEST['times'];
 
 if(isset($pgtime))
 {
   $pgtime=$pgtime;
   $useri=0;
 }
 else
 {
   $pgtime=0;
   $useri=$user->uid;
 }
 $useri=$user->uid;
if(isset($_COOKIE['ersess']))
 {
    $cook= $_COOKIE['ersess'];
 }
 else
 {
   $cook=0;
 }

if( $_SESSION['usnam']!='' && $cook!=1)
   { 
    $_SESSION['cou']=0;
   combineuserlogin($_SESSION['usnam'],$_SESSION['uspass']);
  }*/
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
}else{
global $base_url;
  drupal_goto($base_url);
}



?>