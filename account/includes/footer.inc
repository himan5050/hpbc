<?php
      
	echo '
 </div>

</div>
<!-- footer start here-->
<div id="footer">
        <div class="footer-right">
          <div class="footer-bg">
			 <ul>';
 
				  $s="select * from menu_links where menu_name='menu-footermenu' and plid=0 and link_path!='node' and hidden = 0";
				  $q=DB_query($s,$db);
				  while($r=Db_fetch_array($q))
				  {
				   echo '<li class="leaf"><a href="/'.$u[1].'/'.$r['link_path'].'"><span>'.$r['link_title'].'</span></a></li>';
				  }
echo '</ul>
          </div>
        </div>
      </div>
<!-- footer end-->

</body>
</html>';
?>