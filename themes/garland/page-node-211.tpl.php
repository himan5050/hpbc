<?php
global $user, $base_url;
if($user->uid > 0){
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
	<script language="javascript">

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
  </head>
  <body class="innerpagebody">
<div class="main-container">
<?php print $header; ?>
<div id="header">
<div class="hindi"><?php print $lbox; ?> </div>
<img src="<?php print $base_path . $directory; ?>/images/hpscst.png" width="564" height="51" hspace="0" vspace="0" alt="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation" title="Himachal Pradesh Scheduled Castes and Scheduled Tribe Development Corporation"/>
<div class="menu-block">
<div class="top-search middle">
         <?php print $searchblock; ?>
</div>

<?php if (isset($primary_links)) : ?>
  <?php print theme('links', $primary_links, array('class' => 'topmenu')) ?>
<?php endif; ?>		
</div>
</div>

<!-- top header end here -->

   <div align="right" class="adminpanel">
<?php
  $username = $user->name;

$node211 = $base_url.'/node/211';

 $change_password = $base_url.'/scst/resetpassword';
if ($user->uid != 0) {
  // code for the logout button
  echo ''.'Hi&nbsp;'.$username.'!&nbsp; | &nbsp;'."<a href='$node211'>Dashboard</a>&nbsp; | &nbsp;";
  echo "<a href=' $logout'>Log out</a>&nbsp; | &nbsp;";
 echo "<a href=' $change_password'>Change Password</a>";

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
  $bl1.="<div class='dashboard'><div id='top'><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-right.gif'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-left.gif' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle'><b>".$r['link_title']."</b></div></div><div id='border'>";
  
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")   order by weight asc";
   $ssq=db_query($ss);
  
   while($ssr=db_fetch_array($ssq))
   {
   
     $bl1.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   
   $bl1.="</div></div>";
   }
   else if(($i%3)==2)
   {
     $bl2.="<div class='dashboard2'><div id='top'><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-right-yellow.gif'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-left-yellow.gif' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle'><b>".$r['link_title']."</b></div></div><div id='border'>";
 
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")   order by weight asc";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl2.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   $bl2.="</div></div>";
   }
   
   else if(($i%3)==0)
   {
     $bl3.="<div class='dashboard3'><div id='top'><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-right-orange.gif'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-left-orange.gif' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle'><b>".$r['link_title']."</b><br/></div></div><div id='border'>";
  
   $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")  order by weight asc";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl3.= "<ul><li><a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></li></ul>";
   }
   $bl3.="</div></div>";
   }
   $i++;
}
echo "<table>";
echo "<tr><td colspan=3><div class='dashboard2'><div id='top'><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-right-yellow.gif'  align='right' width='12' height='26' hspace='0' vspace='0' border='0' /><img src='/SC&ST/sites/all/themes/scst/images/tp-cor-left-yellow.gif' align='left' width='12' height='26' hspace='0' vspace='0' border='0' /><div class='dashboardtitle' onclick='opendashboard();'><b>DASHBOARD</b></div></div><div id='border'>";

//*************PENDING LOAN CASES *********************

$ploans = 0;
$cuser = user_load($user->uid);
$roles = implode(',',array_flip($cuser->roles));
$pendingloansql = "SELECT COUNT(*) FROM tbl_workflow_task wt, tbl_workflow_docket wd, tbl_workflow w, tbl_loan_detail ld, tbl_workflow_details wdetail WHERE wt.doc_id = wd.doc_id AND wd.workflow_id = w.workflow_id AND w.workflow_id = wdetail.workflow_id AND w.workflow_name = 'loan' AND FIND_IN_SET(wdetail.role,'".$roles."') AND wdetail.level = wt.level AND wt.status = 1"; 
$res = db_query($pendingloansql);
$ploans = db_result($res);
?>
<input type="hidden" name="actionid" id="actionid" value="up" />
<div id="notification_dashboard">
<?php
if($ploans)
	echo "<img src=''> <a href='".$base_path."loan/listloans/pendingtask'>".$ploans." Pending Loan Applications</a>";
$pendingcommentsql = "SELECT COUNT(*) FROM tbl_loan_comment WHERE commentedto = '".$user->uid."' AND status = 1";
$res = db_query($pendingcommentsql);
$pcomments = db_result($res);
if($pcomments)
	echo "<br><img src=''> <a href='".$base_path."loan/listcomments/pendingtask'>".$pcomments." Pending Comments on Loan Applications</a>";
//*************PENDING LOAN CASES END *********************
?>
</div>

<?php

echo "</div></div></td></tr>";

echo "<tr><td valign='top'>".$bl1."</td><td valign='top'>".$bl2."</td><td
valign='top'>".$bl3."</td></tr></table>";
?>
		  
<!-- footer start here-->
<div id="footer"></div>
<!-- footer end-->
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