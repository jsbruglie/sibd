<?php
	
	require("../includes/config.php");

	require("../templates/header.php");

	//$test_query_1 = ['INSERT INTO `patient` (`number`, `name`, `birthday`, `address`) VALUES ("1", "Chico", "2017-11-07", "a")'];
    $test_query_1 = ["DROP TABLE test"];
    $test_query_2 = ['INSERT INTO `patient` (`number`, `name`, `birthday`, `address`) VALUES ("37", "Chico", "2017-11-07", "a")'];


    $test = transact([$test_query_2, $test_query_1]);
    echo $test;

?>