<?php
	$api_key_mapbox="pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw";

   $region_names = array("'Erongo'",
	                      "'Hardap'",
	                      "'Karas'",
	                      "'Kavango East'",
	                      "'Kavango West'",
	                      "'Khomas'",
	                      "'Kunene'",
	                      "'Ohangwena'",
	                      "'Omaheke'",
	                      "'Omusati'",
	                      "'Oshana'",
	                      "'Oshikoto'",
	                      "'Otjozondjupa'",
	                      "'Zambezi'");

  	 $regions = implode(',',$region_names);
  	 
  	 $regions_short = null;
  	
    foreach($region_names as $region)
  	         $regions_short[] = substr($region,0,6) . "'";

	 // turn it into a CSV list
  	 $regions_short = implode(',',$regions_short);                
?>