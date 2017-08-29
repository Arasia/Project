<?php
require('node_db.php');

function calDistance($index1, $index2)
{
	global $node_info;
	$lat1 = $node_info[$index1-1]["latitude"];
	$lon1 = $node_info[$index1-1]["longtitude"];
	$lat2 = $node_info[$index2-1]["latitude"];
	$lon2 = $node_info[$index2-1]["longtitude"];
	$theta;
	$dist;
	$theta = $lon1 - $lon2;
	$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *  cos(deg2rad($theta));
	//echo "\$dist1 : $dist<br />";
	$dist = acos($dist);
	//echo "\$dist2 : $dist<br />";
	$dist = rad2deg($dist);
	//echo "\$dist3 : $dist<br />";

	$dist = $dist * 60 * 1.1515;
	//echo "\$dist4 : $dist<br />";
	$dist = $dist * 1.609344;    // 단위 mile 에서 km 변환.  
	//echo "\$dist5 : $dist<br />";
	$dist = $dist * 1000.0;      // 단위  km 에서 m 로 변환  

	echo "\$dist! : $dist<br />";
}

function calAngle($index1, $index2)
{
	global $node_info;
	$lat1 = $node_info[$index1-1]["latitude"];
	$lon1 = $node_info[$index1-1]["longtitude"];
	$lat2 = $node_info[$index2-1]["latitude"];
	$lon2 = $node_info[$index2-1]["longtitude"];

	$angle = rad2deg(atan2($lon2-$lon1, $lat2-$lat1));
	if($angle <0)
		$angle = $angle + 360;

	echo "\$angle : $angle <br />";
}

//echo calDistance(37, 38);
?>