<?php
// Create connection to database
$conn=new mysqli("localhost","root","","orderCy");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

// month value sent from the client with a POST request
$sql = "SELECT * FROM Restaurants";
$result = $conn->query($sql);
$json_array = array();
$Logos=array();
$x=0;
$json_array=array();
// Prepares all the results to be encoded in a JSON
while ($row = $result->fetch_assoc()) {
    $restaurant=array(  "Name"=>$row['Name'],
                        "VenueID"=>$row['VenueID'],
                        "City"=>$row['City'],
                        "Openhour"=>$row['Openhour'],
                        "Closehour"=> $row['Closehour']

    );
    array_push($json_array,$restaurant);
    $Logos[$x]        = $row['Logo'];

    $x++;
}


// encodes array with results from database
echo json_encode($json_array);
mysqli_close($conn);
?>