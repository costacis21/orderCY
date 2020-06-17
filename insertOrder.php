<?php

// Create connection to database
//$con = mysqli_connect("localhost", "root", "", "orderCy");
//
//// Check connection
//if (mysqli_connect_errno()) {
//    echo "Failed to connect to MySQL: " . mysqli_connect_error();
//}
$json_array = array();
//if(isset($_POST["venueID"])){
$order= $_GET["order"];
$order='{"telNo":"99938434","tableNo":"10","venueID":"1"}[{},{},{"comment":"bla","quantity":"bli","itemID":"1","commentQty":"blo"},{"comment":"bdsdfsd","quantity":"bsdfsdf","commentQty":"bsdfsdf","itemID":"2"}]';
$customer = substr($order, 0, strpos($order, '}')+1);
$itemOrders = substr($order, strpos($order, '}')+1, strlen($order));
$decodedOrder = json_decode($itemOrders,true);

echo $decodedOrder[0]["comment"];



// month value sent from the client with a POST request
//$sql = "SELECT * FROM Items WHERE VenueID = {$requestVenueID}";
//$result = mysqli_query($con, $sql);


// Prepares all the results to be encoded in a JSON



// encodes array with results from database
//mysqli_close($con);
?>