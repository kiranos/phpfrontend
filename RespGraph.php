<?php

 /* CAT:Line chart */

 /* pChart library inclusions */
// include("pChart/class/pData.class.php");
// include("pChart/class/pDraw.class.php");
// include("pChart/class/pImage.class.php");

 /* Create and populate the pData object */
 $MyData = new pData();
// $MyData->addPoints(array(3000,10000,13000,7000,9000),"X-axis");
$MyData->addPoints($ResponseTime,"Response Time");
// $MyData->addPoints(array(3,12,15,8,5,-5),"Probe 2");
// $MyData->addPoints(array(2,7,5,18,19,22),"Probe 3");
// $MyData->setSerieTicks("Probe 2",4);
// $MyData->setSerieWeight("Probe 3",2);
 $MyData->setAxisName(0,"Response Time");
 $MyData->addPoints($ConnUsers,"Users");
// $MyData->setSerieDescription("Users","Current Users");
 $MyData->setAbscissa("Users");
 $MyData->setAbscissaName("concurrent Users");


 /* Create the pChart object */
 $myPicture = new pImage(700,230,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));

 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/Forgotte.ttf","FontSize"=>11));
 $myPicture->drawText(150,35,"Response Time in seconds",array("FontSize"=>20,"Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"pChart/fonts/pf_arma_five.ttf","FontSize"=>6));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,650,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawLineChart();

 /* Write the chart legend */
 $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture (choose the best way) */
 // $myPicture->autoOutput("pictures/example.drawLineChart.simple.png");

 $myPicture->render("images/ResponseGraph.png");

// $myPicture->stroke();

?>
