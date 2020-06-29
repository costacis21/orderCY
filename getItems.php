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
$json_array = array();
//if(isset($_POST["venueID"])){
    $requestVenueID= $_GET["venueID"];

    // month value sent from the client with a POST request
    $sql = "SELECT * FROM Items WHERE VenueID = '{$requestVenueID}'";
    $result = $conn->query($sql) or die($conn->error);
    $Photos = array();
    $x = 0;
    $json_array = array();
    // Prepares all the results to be encoded in a JSON
    while ($row = $result->fetch_assoc()) {
        $item = array("Name" => $row['Name'],
            "VenueID" => $row['VenueID'],
            "ItemID" => $row['ItemID'],
            "Type" => $row['Type'],
            "Type2" => $row['Type2'],
            "Description" => $row['Description'],
            "Price" => $row['Price'],
            "Visible" => $row['Visible']
        );
        array_push($json_array, $item);
        $Photos[$x] = $row['Photo'];

        $x++;
//}
}


// encodes array with results from database
echo json_encode($json_array);
mysqli_close($conn);
?>