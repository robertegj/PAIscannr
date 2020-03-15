<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);	
	if(!empty($_POST['url'])||!empty($_GET['url'])){
		if(!empty($_POST['url'])) {
			$url = $_POST['url'];
		}
		if(!empty($_GET['url'])) {
			$url = $_GET['url'];
		}
		if(empty($url)){
			echo "Server Response: No data. Dying. Use POST or GET value URL.";
			die();
		}
		$output = shell_exec("python3 reach.py $url");
		echo $output;
		if(empty($output)){
			echo "API Response Blank.";
		}
	}
?>