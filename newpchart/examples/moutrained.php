<?php   
 /* CAT:Bar Chart */

 /* pChart library inclusions */
 include("../class/pData.class.php");
 include("../class/pDraw.class.php");
 include("../class/pImage.class.php");
 //Total_Potential_Franchisee="'.$totalParticipant."&Total_Trained_Potential_Franchisee=".$totaltrainedParticipant."&text=".$utility_name

 /* Create and populate the pData object */



 $MyData = new pData();  
 $MyData->loadPalette("../palettes/light.color",TRUE);
 $MyData->addPoints(array($_REQUEST['total'],$_REQUEST['trained']),'');
 $MyData->setAxisName(0,"No. of Trained Participants(C&D)");
 $MyData->addPoints(array("MoU Target","Actual Trained C&D"),"Months");
 $MyData->setSerieDescription("Months","Month");
 $MyData->setAbscissa("Months");

 /* Create the pChart object */
 $myPicture = new pImage(700,300,$MyData);
 $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,array("StartR"=>240,"StartG"=>240,"StartB"=>240,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
 $myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>10));

 /* Draw the scale  */
 $myPicture->setGraphArea(50,30,680,200);
 $myPicture->drawScale(array("CycleBackground"=>TRUE,"DrawSubTicks"=>TRUE,"GridR"=>0,"GridG"=>0,"GridB"=>0,"GridAlpha"=>10));

 /* Turn on shadow computing */ 
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw the chart */
 $settings = array("Gradient"=>TRUE,"GradientMode"=>GRADIENT_EFFECT_CAN,"DisplayPos"=>LABEL_POS_INSIDE,"DisplayValues"=>TRUE,"DisplayR"=>255,"DisplayG"=>500,"DisplayB"=>255,"DisplayShadow"=>TRUE,"Surrounding"=>10);
 $myPicture->drawBarChart($settings);

 /* Write the chart legend */
 //$myPicture->drawLegend(110,12,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $filename = $_GET['filename'];
 $myPicture->autoOutput("pictures/".$filename."png");
  $myPicture->Render($filename.'.png');
?>