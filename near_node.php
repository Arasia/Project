<?php

function near_node($lat1, $lon1)
{
	$num = array();
	$count = 0;

	$min = 9999999;
	$index;
	
	$flag1 = 0;
	$flag2 = 0;
	$flag3 = 0;
	$flag4 = 0;
	
	$width = 5;
	$height = 4;

	$box = (($lat1*1000)%10 -5) * $width + ($lon1*1000)%10;
	//echo "$box <br />";
	$num[$count++] = $box;

	if(($box % $width) == 1) $flag1 = 1;

	if(($box % $width) == 0) $flag2 = 1;

	if(($box / $width) == 0) $flag3 = 1;

	if(($box / $width) == ($height-1)) $flag4 = 1;

	if($flag1 == 0)
		$num[$count++] = $box -1;

	if($flag2 == 0)
		$num[$count++] = $box +1;

	if($flag3 == 0)
		$num[$count++] = $box -$width;

	if($flag4 == 0)
		$num[$count++] = $box +$width;

	if(($flag1 == 0) && ($flag3 == 0))
		$num[$count++] = $box -1 -$width;

	if(($flag1 == 0) && ($flag4 == 0))
		$num[$count++] = $box -1 +$width;

	if(($flag2 == 0) && ($flag3 == 0))
		$num[$count++] = $box +1 -$width;

	if(($flag2 == 0) && ($flag4 == 0))
		$num[$count++] = $box +1 +$width;

//	for($i = 0 ; $i < $count ; $i++)
//		echo "$num[$i] <br />";

	$db = mysqli_connect("localhost", "root", "autoset", "campus_guide") or die("Could not conect : " . mysql_error());
	mysqli_set_charset($db,"utf8"); 

	$result = mysqli_query($db, "SELECT * FROM `node_info` WHERE `box` IN(".implode(',',$num).")");
	while($row = mysqli_fetch_array($result))
	{
		//printf("index : %d, lat : %f, long : %f, box : %d, info : %s<br />", $row[0], $row[1], $row[2], $row[3], $row[4]);
		
		$lat2 = $row[1];
		$lon2 = $row[2];

		$theta;
		$dist;
		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *  cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);

		$dist = $dist * 60 * 1.1515;
		$dist = $dist * 1.609344;    // 단위 mile 에서 km 변환.  
		$dist = $dist * 1000.0;      // 단위  km 에서 m 로 변환

		if($min > $dist)
		{
			$min = $dist;
			$index = $row[0];
		}
	}
	return $index;
	//echo "<br /> <br/> 가까운 노드값 : $index <br /> <br/>";
	
//	$db2 = mysqli_connect("localhost", "root", "autoset", "campus_guide") or die("Could not conect : " . mysql_error());
//	mysqli_set_charset($db2,"utf8");
//	$result2 = mysqli_query($db2, "SELECT * FROM `node_info` WHERE `index` = '$index'");
//	while($row = mysqli_fetch_array($result2))
//	{
//		printf("index : %d, lat : %f, long : %f, box : %d, info : %s<br />", $row[0], $row[1], $row[2], $row[3], $row[4]);
//	}
}

?>
