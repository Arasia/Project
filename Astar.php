<?php
	require('distant_db.php');
	define("MAX", 0xffffffff);
	define("NONE", -1);
	define("CHECK", 1);

	define("NUM_NODE", 39);

	$cityDist;
	$cityHeuristic;
	$cityEvaluateDist;
	$bestPath;
	$parent;
	
	$openList;
	$closeList;
	
	$response;

	function printParent($goal)
	{
		global $parent, $response;
		$city = $goal;
		$cnt = 0;

		while($parent[$city] != -1)
		{
			//echo '<p>' . $cnt . " : " . $parent[$city] ."Node</p>\n";
			$response[$cnt] = $parent[$city];
			$cnt++;
			$city = $parent[$city];
		}
	}

	function printBestPath()
	{
		global $bestPath;
		for($i = 0 ; $i < NUM_NODE ; $i++)
		{
			if($bestPath[$i] > 0)
			{
				//echo '<p>' . $i . ". " . $bestPath[$i] . "Node</p>\n";
			}
		}
	}
	
	function isOpenListEmpty()
	{
		global $openList;
		for($i = 0 ; $i < NUM_NODE ; $i++)
		{
			if($openList[$i] != NONE)
			{
				return 0;
			}
		}
		return 1;
	}

	function init($start, $goal)
	{
		global $cityDist, $cityHeuristic, $cityEvaluateDist, $bestPath, $parent;
		global $openList, $closeList;
		global $distant_info;

		for($i = 0 ; $i < NUM_NODE ; $i++)
		{
			for($j = 0 ; $j < NUM_NODE ; $j++)
			{
				$cityDist[$i][$j] = MAX;
			}
		}

		for($i = 0 ; $i < NUM_NODE ; $i++)
			$cityHeuristic[$i] = 1;

		for($i = 0 ; $i < NUM_NODE ; $i++)
		{
			$openList[$i] = NONE;
			$closeList[$i] = NONE;
			$parent[$i] = NONE;
		}
		
		foreach($distant_info as $value)
		{
			if(($value['enter'] == 'N') || ($value['start_node'] == $start) || $value['finish_node'] == $goal)
			{
				$start_node = $value['start_node'];
				$finish_node = $value['finish_node'];
				$distant = $value['distance'];
				$cityDist[$start_node][$finish_node] = $distant;
			}
		}

		for($i=0; $i<NUM_NODE; $i++)
			$cityDist[$i][$i]=0;
	}

	function Astar($start, $goal)
	{
		global $cityDist, $cityHeuristic, $cityEvaluateDist, $bestPath, $parent;
		global $openList, $closeList;
		
		global $response;

//		$start = (int)$_POST['start'];
//		$goal = (int)$_POST['goal'];
		$curNode = 0;
		$visit;
		$min;
		$index;
		$seq = 0;
		$i = 0; 
		$level = 0;
		
		init($start, $goal);

		$cityEvaluateDist[$start] = $cityHeuristic[$start] + $cityDist[$start][$start];
		$curNode = $start;
		$openList[$start] = CHECK;

		while( !isOpenListEmpty())
		{
			$min = MAX;
			$index = -1;

			for($visit = 0; $visit<NUM_NODE ; $visit++)
			{
				if($openList[$visit] == CHECK)
				{
					if($visit == $goal)
					{
						$index = $goal;
						break;
					}
					if($min > $cityEvaluateDist[$visit])
					{
						$min = $cityEvaluateDist[$visit];
						$index = $visit;
					}
				}
			}
			$curNode = $index;
			$bestPath[$seq] = $cityEvaluateDist[$index];
			$seq++;

			if($curNode == $goal)
				break;
			$openList[$curNode] = NONE;
			$closeList[$curNode] = CHECK;

			for($visit = 0 ; $visit < NUM_NODE ; $visit++)
			{
				if($closeList[$visit] == CHECK) continue;
				if($openList[$visit] == CHECK)
				{
					for($i =0; $i<NUM_NODE ; $i++)
					{
						if($openList[$i] == CHECK && $cityDist[$start][$i] > $cityDist[$start][$visit] + $cityDist[$visit][$i])
						{
							$cityDist[$start][$i] = $cityDist[$start][$visit] + $cityDist[$visit][$i];
							$cityEvaluateDist[$i] = $cityHeuristic[$i] + $cityDist[$start][$i];
							$parent[$i] = $visit;   
						}
					}
				}
				else
				{
					if($cityDist[$curNode][$visit] == MAX)
						continue;   
					if($cityDist[$start][$visit] > $cityDist[$start][$curNode]+$cityDist[$curNode][$visit])
					{
						$cityDist[$start][$visit] =  $cityDist[$start][$curNode]+$cityDist[$curNode][$visit];
						$parent[$visit] = $curNode;
					}
					$cityEvaluateDist[$visit] = $cityDist[$start][$visit] + $cityHeuristic[$visit];   
					$openList[$visit] = CHECK;
				}
			}
			$level++;
		}
		
		printBestPath();

		//echo "<p>tree level : " . $level . "</p>\n";
		$response["level"] = $level;
		printParent($goal);
	}	
	 Astar(37, 8);

	header('Content-type: application/json');
	echo json_encode($response);
	
?>