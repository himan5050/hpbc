<?php   
 /* CAT:Line chart */

 /* pChart library inclusions */
 include("../class/pData.class.php");
 include("../class/pDraw.class.php");
 include("../class/pImage.class.php");

 /* Create and populate the pData object */
 $MyData = new pData();  
 //$MyData->addPoints(array(-4,VOID,VOID,12,8,3),"Probe 1");
$MyData->loadPalette("../palettes/light.color",TRUE); 
 $MyData->addPoints(explode(',',$_GET['participantStr']),"No of Participant");
 //$MyData->addPoints(array(2,7,5,18,19,22),"Probe 3");
 $MyData->setSerieTicks("No of Participant",7);
 //$MyData->setSerieWeight("Probe 3",2);
 $MyData->setAxisName(0,"No of Participant");
 $MyData->addPoints(explode(',',$_GET['stateStr']),"Labels");
 $MyData->setSerieDescription("Labels","State");
 $MyData->setAbscissa("Labels");

 /* Create the pChart object */
 $myPicture = new pImage(700,230,$MyData);

 /* Draw the background */
 $Settings = array("R"=>170, "G"=>183, "B"=>87, "Dash"=>1, "DashR"=>190, "DashG"=>203, "DashB"=>107);
 $myPicture->drawFilledRectangle(0,0,700,230,$Settings);

 /* Overlay with a gradient */
 $Settings = array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100);
 $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);
 $myPicture->drawGradientArea(0,0,700,20,DIRECTION_VERTICAL,array("StartR"=>0,"StartG"=>0,"StartB"=>0,"EndR"=>50,"EndG"=>50,"EndB"=>50,"Alpha"=>80));

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the picture title */ 
 $myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>10));
 $myPicture->drawText(10,13,"",array("R"=>255,"G"=>255,"B"=>255));

 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>11));
 $myPicture->drawText(250,55,"",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Draw the scale and the 1st chart */
 $myPicture->setGraphArea(60,60,650,190);
 $myPicture->drawFilledRectangle(60,60,650,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
 $myPicture->drawScale(array("DrawSubTicks"=>TRUE));
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 $myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>10));
 $myPicture->drawLineChart(array("DisplayValues"=>TRUE,"DisplayColor"=>DISPLAY_AUTO));
 $myPicture->setShadow(FALSE);

 /* Draw the scale and the 2nd chart */
// $myPicture->setGraphArea(500,60,670,190);
 //$myPicture->drawFilledRectangle(500,60,670,190,array("R"=>255,"G"=>255,"B"=>255,"Surrounding"=>-200,"Alpha"=>10));
 //$myPicture->drawScale(array("Pos"=>SCALE_POS_TOPBOTTOM,"DrawSubTicks"=>TRUE));
 //$myPicture->setShadow(TRUE,array("X"=>-1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));
 //$myPicture->drawLineChart();
 //$myPicture->setShadow(FALSE);

 /* Write the chart legend */
 //$myPicture->drawLegend(510,205,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));
 
$filename = $_GET['filename'];
$myPicture->autoOutput("pictures/".$filename.".png");
$myPicture->Render($filename.'.png');
?>
