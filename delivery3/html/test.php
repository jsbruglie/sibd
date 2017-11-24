<?php
	
	require("../includes/config.php");

	require("../templates/header.php");

	$test_query_1 = ["DROP TABLE test1"];
    $test_query_2 = ["INSERT INTO patient (number, name, birthday, address) VALUES
           (NULL, ?, ?, ?)", ["Chico", "2017-11-08", "a"]];
    $test = transact([$test_query_2, $test_query_1]);
    echo $test;

?>