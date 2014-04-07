<?php
/*
This file downloads and stores the daily climatological data into the database from the NOAA servers.
Primary Author: Sean Freeman
Module Name: Forecast Downloader
Date Created: Feb. 25, 2014
Last Edited: March 30, 2014


*/

include("./db_vars.php");


//Only downloads the most recent data, due to problems with the NWS server.
function download_validation($sid){
//Station ID of the airport. This uses IATA conventions, per NWS requirements.

//Downloads the actual data from the NWS servers for parsing.
$climodata=file_get_contents('http://www.nws.noaa.gov/climate/getclimate.php?wfo=iln&sid='.$sid.'&pil=CLI&recent=yes');

//Now, to remove the HTML and get just the climatological report:
$climoreport=explode("</pre>",explode("<pre>",$climodata)[1])[0];

//Now that we have just the report, we need to parse it. This is actually relatively difficult.
//First, to break it down by category, I'm going to use a series of explode commands.

//First, to remove the initial text, and get only the data.
$climoreport2=explode("TEMPERATURE",$climoreport,2)[1];

//Now, we want to separate the temperature section.
$climoreport3=explode("PRECIPITATION",$climoreport2,2);

//This is just the temperature report.
$tempreport=$climoreport3[0];

//Now, to get the precip report.
$climoreport4=explode("MONTH TO DATE",$climoreport3[1],2);
$precipreport=$climoreport4[0];

//Now, to deal with the temperature report. All we need is high/low temp.
$tempreportarray=preg_split('/\s+/', $tempreport);

//The high temperature is listed after the word maximum.
//So, we find the word maximum and add one to the array.
//The same logic is used for minimum.

$hightemparrno=array_search('MAXIMUM',$tempreportarray)+1;

$hightemp=$tempreportarray[$hightemparrno];

$lowtemparrno=array_search('MINIMUM',$tempreportarray)+1;

$lowtemp=$tempreportarray[$lowtemparrno];


$precipreportarray=preg_split('/\s+/', $precipreport);

$precipamountno=array_search('TODAY',$precipreportarray)+1;

$precipamount=$precipreportarray[$precipamountno];
//in db_vars.php- it adds the actual high temp and low temp for the day
//db_add_forecasts($hightemp,$lowtemp, date('dmY'));
return array($hightemp,$lowtemp,$precipamount);
}

download_validation('tlh');
?>