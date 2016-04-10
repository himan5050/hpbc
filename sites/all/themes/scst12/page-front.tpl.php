<?php

error_reporting(0);
global $user, $base_url,$language;


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
    <link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/template.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/constant.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/tabs.css" type="text/css" />
  </head>
  <body id="body" class="body">
<div id="color1">
  <div id="color2">
    <div id="top-gradient"></div>
    <div class="main">
      <div id="top">
        <div id="top-left">
          <div id="top-right">
            <div id="logo" title="HBCFDC">
              <div class="space">
                <?php
          // Prepare header
          $site_fields = array();
          if ($site_name) {
            $site_fields[] = check_plain($site_name);
          }
          if ($site_slogan) {
            $site_fields[] = check_plain($site_slogan);
          }
          $site_title = implode(' ', $site_fields);
          if ($site_fields) {
            $site_fields[0] = '<span>'. $site_fields[0] .'</span>';
          }
          $site_html = implode(' ', $site_fields);

          if ($logo || $site_title) {
            print '<h1><a href="'. check_url($front_page) .'" title="'. $site_title .'">';
            if ($logo) {
              print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" id="logo" />';
            }
            print $site_html .'</a></h1>';
          }
        ?>
             </div>
            </div>
          <div class="h-cont"></div>
          </div>
        </div>
      </div>
      
      <div id="shadow-left">
        <div id="shadow-right">
          <div class="width bg">
            <div id="mid">
              <div id="search">
                <div class="module-search">
                   <?php //print $search_box; ?>
                </div>
              </div>
<div id="topmenu"  style="overflow: visible;">
              <div class="module-topmenu">
              <?php if (isset($primary_links)) : ?>
          <?php print theme('links', $primary_links) ?>
        <?php endif; ?></div>
              </div>
              <div class="clr"></div>
            </div>
<div id="content">
              <div class="width">
<div class="width"> 
 <div id="header">
                        <div class="logo2"></div>
 </div>
 </div>
<!-- top header end here -->

<!-- top header -->



<!-- services block -->

<!-- top header end here-->

<!-- main content start here-->
<div id="main-content">
<div class="adminpanel">
<?php
  $username = $user->name;
if($language->language == 'hi'){
$node211 = $base_url.'/hi/welcome-deshboard';
$logout=$base_url.'/hi/logout';
$change_password = $base_url.'/hi/scst/resetpassword';
}else{
$node211 = $base_url.'/welcome-deshboard';
$logout=$base_url.'/logout';
$change_password = $base_url.'/scst/resetpassword';
}
 
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
 <div id="right">
                  <div class="module-login">
                    <div class="first">
                      <div class="sec">
                       
                        <div class="box-indent">
                          <div class="width">
   <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul></div>'; endif; ?>
   <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
  <?php if ($show_messages && $messages): print $messages; endif; ?>
<?php print $login; ?>
 </div>
                        </div>
                      </div>
                    </div>
                  </div>
<div class="module">
                    <div class="first">
                      <div class="sec"> 
                       <h3>Latest News</h3>                     
                        <div class="box-indent">
                          <div class="width"> <?php print $latestnews; ?>
 </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
<div class="moduletable">
                      <div id="TabbedPanel1" class="TabbedPanels">
                        <div class="tabborder">
                          <div class="wrapper">
                            <div class="TabModulesGroup">
                              <div class="TabContent" id="user2">
<?php print $welcome; ?>
   </div>
                         

<div class="block1">
 <?php print $scheduled; ?>
</div>
 </div>
                        </div>
                        <div class="tabs-bottom clr"><span><span>&nbsp;</span></span> </div>
                      </div>
                    </div>
                    <p></p>
 </div>
        </div>
      </div>
 <div id="footer" class="png">
        <div class="footer-right png">
          <div class="footer-bg png">
            <div class="space"><?php print $footermenu; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- main content end here-->
<?php print $closure ?>
</body>
</html>
<?php
//echo '<pre>';
session_start();
//echo $_SESSION['usnam'];
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
//$ct = time();
//if($user->uid > 0 && ($user->access == $ct)){
if( $_SESSION['usnam']!='' && $cook!=1)
   { 
    $_SESSION['cou']=0;
	setcookie($_COOKIE['ersess'],1);
   combineuserlogin($_SESSION['usnam'],$_SESSION['uspass']);
  //}
}
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?>