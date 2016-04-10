<?php
error_reporting(0);

global $user, $base_url,$language;


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" dir="ltr" >
  <style type="text/css">
<!--
.style1 {font-size: 1em}
.style2 {font-size: 1.2em}
.style3 {
	font-size: 1.5em;
	font-weight: bold;
}
.style4 {font-size: 1.5em}
.style5 {
	color: #0066FF;
	font-weight: bold;
	font-size: 1.4em;
}
.style6 {
	font-size: 15px;
	font-weight: bold;
}
.style7 {
	font-size: 10px
}
.style12 {font-size: 12}
.style8 {	color: #666666;
	font-weight: bold;
	font-size: 14px;
}
-->
</style>
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	

		<script type="text/javascript" src="<?php print $base_path . $directory; ?>scripts/mootools-1.2.1.js"></script>
	<script type="text/javascript" src="<?php print $base_path . $directory; ?>/scripts/interface.js"></script>
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/style.css" type="text/css"/>
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/template.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/constant.css" type="text/css" />
	<link rel="stylesheet" href="<?php print $base_path . $directory; ?>/css/tabs.css" type="text/css" />
	<script type="application/javascript">
var min=8;
var max=18;
function increaseFontSize() {
   var p = document.getElementsByTagName('p');
   for(i=0;i<p.length;i++) {
      if(p[i].style.fontSize) {
         var s = parseInt(p[i].style.fontSize.replace("px",""));
      } else {
         var s = 12;
      }
      if(s!=max) {
         s += 1;
      }
      p[i].style.fontSize = s+"px"
   }
}
function decreaseFontSize() {
   var p = document.getElementsByTagName('p');
   for(i=0;i<p.length;i++) {
      if(p[i].style.fontSize) {
         var s = parseInt(p[i].style.fontSize.replace("px",""));
      } else {
         var s = 12;
      }
      if(s!=min) {
         s -= 1;
      }
      p[i].style.fontSize = s+"px"
   }   
}
</script>
	<script type="text/javascript">
	new TmDropDown('#topmenu', {durationUp:380, durationDown:480, transitionDown:Fx.Transitions.Pow.easeOut});
</script>
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
                   <?php print $search_box; ?>
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
                <div id="container">
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
                    <?php if($login){ ?> 
                    
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
                      
                      <?php }?>
                      <div class="module">
                    <div class="first">
                      <div class="sec"> 
                       <h3>News & Events</h3>                     
                        <div class="box-indent">
                          <div class="width"> 
                                                   
                          <?php print $newsevent; ?>
                          </div>
                        </div>
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
                          </div>
                        </div>
                        <div class="tabs-bottom clr"><span><span>&nbsp;</span></span> </div>
                      </div>
                    </div>
                    <p></p>
                    <div id="customblock">
                      <div class="border-left">
                        <div class="border-right">
                          <div class="border-bottom">
                            <div class="customblock-tl">
                              <div class="customblock-tr">
                                <div class="customblock-bl">
                                  <div class="customblock-br">
                                    <div class="width">
                                    <br>
<h3>Success Stories</h3>

                                      <div class="news-content">
                                      									 
                                          <?php print $scheduled; ?>									  		
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="width ind">
                      <table class="blog" cellpadding="0" cellspacing="0">
                      </table>
                    </div>
                  </div>
                </div>
                <div class="clr"></div>
              </div>
            </div>
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
<script type="text/javascript">
if ($('TabbedPanel1') != null ) {
new PrepareTabs($('TabbedPanel1'), $$('#TabbedPanel1 .TabContent'), $$('#TabbedPanel1 .TabContent h3'), {
	 blockWidth: 589,																		  
	 onDone: function(){
		 new Tabs($$('#TabbedPanel1 .TabContent'), $$('.TabItemsGroup li'), {blockWidth: 591});
	 }
});
}
var scheme = Cookie.read('color_scheme');
setColor(scheme);

  </script>
<?php print $closure;?>
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