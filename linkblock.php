<?php

require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//echo $_SERVER['REQUEST_URI'];
$u=explode('/',$_SERVER['REQUEST_URI']);
//echo "/".$u[1]."/";
//mysql_connect("localhost","root","");
//mysql_select_db("dsje");
global $user;
$us= $user->uid;


$use="select rid from users_roles where uid=".$us."";
$useq=db_query($use);
$user=db_fetch_array($useq);

 $mp="SELECT *
FROM `menu_per_role`
WHERE `rids` LIKE '%".$user['rid']."%'";
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
$s="select link_title,mlid,plid from menu_links where menu_name='menu-app-menu' and plid=0 and mlid IN (".$mi.")";
$q=db_query($s);
while($r=db_fetch_array($q))
{ 
  if(($i%3)==1)
  {
  $bl1.="<div style='width:200px; background-color:#cccccc; height:100px; border-bottom-style:solid;'><b>".$r['link_title']."</b><br/>";
  
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl1.= "<a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></br>";
   }
   $bl1.="</div>";
   }
   else if(($i%3)==2)
   {
     $bl2.="<div style='width:200px; height:200px; border-color:#993333'><b>".$r['link_title']."</b><br/>";
  
    $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl2.= "<a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></br>";
   }
   $bl2.="</div>";
   }
   
   else if(($i%3)==0)
   {
     $bl3.="<div style='width:200px; background-color:#cccccc; height:200px; border-color:#993333'><b>".$r['link_title']."</b><br/>";
  
   $ss="select * from menu_links where plid='".$r['mlid']."' and mlid IN (".$mi.")";
   $ssq=db_query($ss);
   while($ssr=db_fetch_array($ssq))
   {
     $bl3.= "<a href='".$base_url."/".$ssr['link_path']."'>".$ssr['link_title']."</a></br>";
   }
   $bl3.="</div>";
   }
   $i++;
}
echo "<table><tr><td valign='top'>".$bl1."</td><td valign='top'>".$bl2."</td><td valign='top'>".$bl3."</td></tr></table>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
