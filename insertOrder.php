<?php

// Create connection to database
//
//// Check connection
//if (mysqli_connect_errno()) {
//    echo "Failed to connect to MySQL: " . mysqli_connect_error();
//}
$servername="ordercy.a2hosted.com";
$username = "ordercya_root";
$password = "pu043=+JHQA!";
$dbname="ordercya_orderCy";


$conn = new mysqli($servername, $username, $password, $dbname);
$order= $_GET["order"];
$customer = substr($order, 0, strpos($order, '}')+1);
$itemOrders = substr($order, strpos($order, '}')+1, strlen($order));
$decodedOrder = json_decode($itemOrders,true);
$decodedCustomer = json_decode($customer,true);
//echo'<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
//echo'<h></h>';
$customersql="INSERT INTO CustomerOrders (TableNo,TelNo,VenueID) VALUES ({$decodedCustomer['tableNo']},{$decodedCustomer['telNo']},'{$decodedCustomer['venueID']}');";
$orderIDsql="SET @orderID = LAST_INSERT_ID();";
$ordersql = "";

if ($conn->query($customersql) == TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: ". $conn->error;
}
if ($conn->query($orderIDsql) == TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: ". $conn->error;
}

for($i=0;$i<count($decodedOrder);$i++) {
    if(isset($decodedOrder[$i]['itemID'])){
    $comment=$decodedOrder[$i]['comment'];
    $commentQty=$decodedOrder[$i]['commentQty'];
    $itemID=$decodedOrder[$i]['itemID'];
    $quantity=$decodedOrder[$i]['quantity'];
    $ordersql= "INSERT INTO Orders (Comments, CommentQty, ItemID, OrderID, Quantity) VALUES ('$comment', $commentQty, '$itemID', @orderID, $quantity);";
    if ($conn->query($ordersql) == TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: ". $conn->error;
    }
    }
    
}

//echo $customersql.$ordersql;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}




$conn->close();


// month value sent from the client with a POST request
//$sql = "SELECT * FROM Items WHERE VenueID = {$requestVenueID}";
//$result = mysqli_query($con, $sql);


// Prepares all the results to be encoded in a JSON



// encodes array with results from database
//mysqli_close($con);
?>