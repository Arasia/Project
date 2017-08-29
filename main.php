<?php
require('near_node.php');
require('node_db.php');
require('calculator.php');
require('Astar.php');

if(isset($_POST['function']))
{
	$func = $_POST['function'];

	if($func == 'Astar')
	{
		if (isset($_POST['latitude']) && isset($_POST['longtitude']) && isset($_POST['goal']))
		{
			$latitude = $_POST['latitude'];
			$longtitude = $_POST['longtitude'];
			$goal = $_POST['goal'];

			$start = near_node($latitude, $longtitude);

			Astar($start, $goal);
		}
	}
}

?>