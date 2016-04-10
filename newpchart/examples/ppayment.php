<?php   
 /* CAT:Pie charts */

 /* pChart library inclusions */
 include("../class/pData.class.php");
 include("../class/pDraw.class.php");
 include("../class/pPie.class.php");
 include("../class/pImage.class.php");


 $MyData = new pData();   
 
 if($_GET['subsidy_amount'] > 0){

   $MyData->addPoints(array($_GET['loanamount'],$_GET['subsidy_amount'],$_GET['interest']),"ScoreA"); 
   $MyData->addPoints(array("Loan Amount","Subsidy","Interest"),"Labels");
 
 }else{
   $MyData->addPoints(array($_GET['loanamount'],$_GET['interest']),"ScoreA"); 
   $MyData->addPoints(array("Loan Amount","Interest"),"Labels");
 } 
 $MyData->setSerieDescription("ScoreA","Application A");

 /* Define the absissa serie */
 
 $MyData->setAbscissa("Labels");

  /* Create the pChart object */
 $myPicture = new pImage(250,150,$MyData,TRUE);



 /* Add a border to the picture */
// $myPicture->drawRectangle(0,0,300,150,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the picture title */ 
 $myPicture->setFontProperties(array("FontName"=>"../fonts/verdana.ttf","FontSize"=>9));




 /* Create the pPie object */ 
 $PieChart = new pPie($myPicture,$MyData);

 /* Define the slice color */
 $PieChart->setSliceColor(0,array("R"=>255,"G"=>106,"B"=>9));//brown
 $PieChart->setSliceColor(2,array("R"=>3,"G"=>25,"B"=>156));//blue
 $PieChart->setSliceColor(1,array("R"=>108,"G"=>13,"B"=>143));//purple
 


 $myPicture->setShadow(TRUE,array("X"=>3,"Y"=>3,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>12));

 /* Draw a splitted pie chart */ 
 $PieChart->draw3DPie(125,75,array("WriteValues"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));


 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>20));

 $PieChart->drawPieLegend(20,130,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 $myPicture->autoOutput("pictures/example.draw3DPie.png");
 $filename = $_GET['filename'];
 $myPicture->Render($filename.'.png');
?>
