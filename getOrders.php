<?php

// Create connection to database
$servername="ordercy.a2hosted.com";
$username = "ordercya_root";
$password = "pu043=+JHQA!";
$dbname="ordercya_orderCy";


$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$venueID=$_GET['venueID'];
// month value sent from the client with a POST request
$sql = "SELECT * FROM CustomerOrders WHERE VenueID='{$venueID}'";
$sql = "SELECT
 CustomerOrders.TableNo, Orders.Comments, Orders.CommentQty, Orders.Quantity, Items.Name, CustomerOrders.OrderID, CustomerOrders.Status, CustomerOrders.Time, CustomerOrders.Bill
FROM
 CustomerOrders, Orders, Items
WHERE
 Items.VenueID = CustomerOrders.VenueID
AND
 CustomerOrders.OrderID = Orders.OrderID
 AND 
 Items.ItemID = Orders.ItemID
 AND
 CustomerOrders.VenueID = '{$venueID}'
 
 ORDER BY CustomerOrders.OrderID

 ;";
$result = $conn->query($sql);
$json_array = array();
$x = 0;
$json_array = array();
// Prepares all the results to be encoded in a JSON
while ($row = $result->fetch_assoc()) {
    $order = array("TableNo" => $row['TableNo'],
        "Comments" => $row['Comments'],
        "CommentQty" => $row['CommentQty'],
        "Quantity" => $row['Quantity'],
        "Name" => $row['Name'],
        "OrderID" => $row['OrderID'],
        "Status" => $row['Status'],
        "Time" => $row['Time'],
        "Bill" => $row['Bill']

    );
    array_push($json_array, $order);
    $x++;
}


// encodes array with results from database
echo json_encode($json_array);
mysqli_close($conn);


?>