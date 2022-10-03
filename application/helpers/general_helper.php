<?php 
	// dump and die array output
	function dd($array=array())
	{
		echo '<pre>';
		print_r($array);
		die();
	}

	/*
		Calculate Distance between two points
	*/
	function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
	    $theta = $longitude1 - $longitude2;
	    $miles = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
	    $miles = acos($miles);
	    $miles = rad2deg($miles);
	    $miles = $miles * 60 * 1.1515;
	    $feet = $miles * 5280;
	    $yards = $feet / 3;
	    $kilometers = $miles * 1.609344;
	    $meters = $kilometers * 1000;
	    return compact('miles','feet','yards','kilometers','meters'); 
	}

	function oz_array_filter($array=array(),$filter_column='',$filterBy1='')
	{
		$filtered_array = array_filter($array, function ($var) use ( $filter_column,$filterBy1) {
		 return $var[''.$filter_column.''] == $filterBy1;
		  });
		return $filtered_array;
	}

	function get_all_days($start_day='',$end_day='')
	{
		$days_string = '';

		if(!empty($start_day) && !empty($end_day))
		{
			$result_days = array('monday','tuesday', 'wednesday','thursday','friday','saturday','sunday'); 

		$total_count = count($result_days);
		$currentKey = array_search($start_day, $result_days);
		$endKey = array_search($end_day, $result_days);

		$before = (isset($result_days[$currentKey - 1])) ? $result_days[$currentKey - 1] : $result_days[count($result_days) - 1];
		$after = (isset($result_days[$currentKey + 1])) ? $result_days[$currentKey + 1] : $result_days[0];

		$beforeNames = array_slice($result_days, 0,$endKey+1,true);
		$afterNames = array_slice($result_days, $currentKey , 7);
			$total_days = array_merge($beforeNames,$afterNames);
			return $days_string = implode(",",$total_days);

		}
		else
		{
			return $days_string;
		}
		

	}

	function getNumber($n) 
	{ 
	    $characters = '0123456789'; 
	    $randomString = ''; 
	    for ($i = 0; $i < $n; $i++) { 
	        $index = rand(0, strlen($characters) - 1); 
	        $randomString .= $characters[$index]; 
	    } 
	    return $randomString; 
	}  

	function cmp_sort_multi($a, $b)
	{
	    if ($a['created_on'] == $b['created_on']) {
	        return 0;
	    }
	    return ($a['created_on'] < $b['created_on']) ? -1 : 1;
	}

	function pre($data){
		echo "<pre>";
		print_r($data);
		exit;
	}
	
