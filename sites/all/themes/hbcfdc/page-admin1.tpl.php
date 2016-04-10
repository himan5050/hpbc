<?php
global $user, $base_url,$language;

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
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
                <p><a href="index4.html"><img src="<?php print $base_path . $directory; ?>/images/logo.png" alt="HBCFDC" width="364" height="38" style="margin-top:12px; margin-left:10px;"/></a></p>
              </div>
            </div>
            <div class="h-cont">
							<div id="func">
									<div id="theme">
                                    
                                    
				<span>
                
             <?php
			  global $user;

  $username = $user->name;
  
$logout = $base_url.'/logout';
			 
			 ?>   
                
                <?php
				if ($user->uid != 0) {
				
				 echo ''.'Hi&nbsp;'.$username.'!&nbsp; | &nbsp;'."<a href='$node211'>Dashboard</a>&nbsp; | &nbsp;";
  echo "<a href=' $logout'>Log out</a>&nbsp;";
  
				}?></span></div>
	
						</div>
					</div>
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
                <div id="right2">
                  
                  <div class="module">
                    <div class="first">
                      <div class="sec">
                        
                        <div class="box-indent">
                          <div class="width">
                            
                          <?php print $navigation; ?>
                           </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php print $breadcrumb; ?>
          				
          					
         					 
         					 <?php if ($show_messages && $messages): print $messages; endif; ?>
                <div id="container">
                  <div class="width">
                    <?php if ($tabs): print '<ul class="tabs primary">'. $tabs .'</ul>'; endif; ?>
                              <br /><br /><br />
          <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
                    <div class="moduletable">
                      <div id="TabbedPanel1" class="TabbedPanels">
                        <div class="tabborder">
                          <div class="wrapper">
                            <div class="TabModulesGroup">
                              <div class="TabContent">
                              
                            
          
                               <?php print $content; ?> 
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
                                    <div class="width"><br />
                                        <h3>Success Stories</h3>
                                      <p>&nbsp;</p>
                                      <div class="news-content">
                                          <?php print $successstory; ?>
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
      
    </div><div id="footer" class="png">
        <div class="footer-right png">
          <div class="footer-bg png">
            <div class="space">  <?php print $footermenu;?></div>
          </div>
        </div>
      </div><?php print $closure;?>
  </div>
</div>

</body>
</html>
