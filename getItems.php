<?php

// Create connection to database
$con = mysqli_connect("localhost", "root", "", "orderCy");

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
$json_array = array();
//if(isset($_POST["venueID"])){
    $requestVenueID= $_GET["venueID"];

    // month value sent from the client with a POST request
    $sql = "SELECT * FROM Items WHERE VenueID = {$requestVenueID}";
    $result = mysqli_query($con, $sql);

    $Photos = array();
    $x = 0;
    $json_array = array();
    // Prepares all the results to be encoded in a JSON
    while ($row = mysqli_fetch_assoc($result)) {
        $item = array("Name" => $row['Name'],
            "VenueID" => $row['VenueID'],
            "ItemID" => $row['ItemID'],
            "Type" => $row['Type'],
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
mysqli_close($con);
?>