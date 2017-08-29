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
	$dist = $dist * 1.609344;    // ���� mile ���� km ��ȯ.  
	//echo "\$dist5 : $dist<br />";
	$dist = $dist * 1000.0;      // ����  km ���� m �� ��ȯ  

	//echo "\$dist! : $dist<br />";

	$db = mysqli_connect("localhost", "root", "autoset", "campus_guide") or die(mysql_error());
	mysqli_set_charset($db,"utf8");

	$result = mysqli_query($db, "INSERT INTO `distant_info`(`start_node`, `finish_node`, `distance`, `enter`) VALUES ('$index1', '$index2', '$dist', 'N')");

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

calDistance(21, 31);
calDistance(31, 21);
calDistance(24, 28);
calDistance(28, 24);
calDistance(5, 15);
calDistance(15, 5);
?>