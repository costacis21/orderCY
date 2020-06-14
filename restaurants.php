<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
            <div class="container"><a class="navbar-brand" href="#">Restaurant Portal</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                    id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Restaurants</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="products.php">Products</a></li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
	<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname="orderCy";
include('dbfunctions.php');
include('validation.php');

$conn = mysqli_connect($servername, $username, $password, $dbname);
    ini_set('log_errors',1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$Names       = array('');
$VenuesID   = array();
$Cities = array('');
$nameErr = $cityErr = '';


$sql = "SELECT * FROM Restaurants";
$result = mysqli_query($conn, $sql);
$x=0;
while ($row = mysqli_fetch_assoc($result)) {
    $Names[$x]        = $row['Name'];
    $VenuesID[$x]   = $row['VenueID'];
    $Cities[$x] = $row['City'];
    $Openhours[$x]       = $row['Openhour'];
    $Closehours[$x]         = $row['Closehour'];
    $Logos[$x]        = $row['Logo'];
    $x++;
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if($_POST["action"]=="add"){
            //list($Name, $nameErr) = nameValid($_POST["name"]);
            //list($City, $cityErr) = cityValid($_POST["city"]);
            $Name =$_POST["name"];
            $City =$_POST["city"];
            if(isset($_POST["openhour"])){
                $Openhour=date("H:i",strtotime($_POST["openhour"]));
            }else{
                $Openhour=date("H:i",1200);
            }
            if(isset($_POST["closehour"])){
                $Closehour=date("H:i",strtotime($_POST["closehour"]));
            }else{
                $Closehour=date("H:i",0000);
            }

            //                        $Name=$_POST["name"];
            //                        $Description=$_POST["description"];
            //                        $Price=$_POST["price"];

            //checks if errors were generated
            if ($nameErr . $cityErr == '') {
                $newVenueID=random_str(255);
                if ($_POST['userUploadFile']==4) {
                    mysqli_query($conn, "INSERT INTO Restaurants (VenueID, Name, City, Openhour, Closehour) VALUES('{$newVenueID}','{$Name}', '{$City}', '{$Openhour}', '{$Closehour}') ");

                }else {
                    $Logo = addslashes(file_get_contents($_FILES["userUploadFile"]["tmp_name"]));
                    $LogoProperties = getimageSize($_FILES["userUploadFile"]["tmp_name"]);
                    mysqli_query($conn, "INSERT INTO Restaurants (VenueID,Name, City, Openhour, Closehour, LogoProperties,Logo) VALUES('{$newVenueID}','{$Name}', '{$City}', '{$Openhour}', '{$Closehour}', '{$LogoProperties}','{$Logo}') ");

                }
                }else{
                displayErr($nameErr,$cityErr);
            }
            refresh();

        }
         if($_POST["action"]=="delete"){
             $VenueID=$_POST["VenueID"];
             mysqli_query($conn, "DELETE FROM Restaurants WHERE VenueID='{$VenueID}' ");
             refresh();
         }
         if($_POST["action"]=="edit") {
             list($Name, $nameErr) = nameValid($_POST["name"]);
             list($City, $cityErr) = nameValid($_POST["city"]);
             if(isset($_POST["openhour"])){
                 $Openhour=date("H:i",strtotime($_POST["openhour"]));
             }else{
                 $Openhour=date("H:i",1200);
             }
             if(isset($_POST["closehour"])){
                 $Closehour=date("H:i",strtotime($_POST["closehour"]));
             }else{
                 $Closehour=date("H:i",0000);
             }

             //                        $Name=$_POST["name"];
             //                        $Description=$_POST["description"];
             //                        $Price=$_POST["price"];

             //checks if errors were generated
             if ($nameErr . $cityErr == '') {
                 $VenueID=$_POST["VenueID"];
                 if ($_FILES["userUploadFile"]["error"]==4) {
                     mysqli_query($conn, "UPDATE Restaurants SET Name='{$Name}', City='{$City}', Openhour='{$Openhour}', Closehour='{$Closehour}' WHERE VenueID='{$VenueID}' ");

                 }else {
                     $Logo = addslashes(file_get_contents($_FILES["userUploadFile"]["tmp_name"]));
                     $LogoProperties = getimageSize($_FILES["userUploadFile"]["tmp_name"]);
                     mysqli_query($conn, "UPDATE Restaurants SET Name='{$Name}', City='{$City}', Openhour='{$Openhour}', Closehour='{$Closehour}', LogoProperties='{$LogoProperties}', Logo='{$Logo}' WHERE VenueID='{$VenueID}' ");



                 }
             }else{
                 displayErr($nameErr,$cityErr);
             }
             refresh();

         }

    }


    for ($x = 0; $x < count($VenuesID); $x++) {
						//generates form for each record
						echo '<tr>
	<form style="display:inline-block;" enctype="multipart/form-data" method="get" action="products.php">	
	<table class="table">
    <td><img style="display:inline-block;" width="10%" height="10%" src="data:image/jpeg;base64,'.base64_encode( $Logos[$x]).'" alt="image"></td>	
    <input name="name" type="hidden" value="' . $Names[$x] . '"></td>
	<input  name="city" type="hidden" value="' . $Cities[$x] . '"></td>
	<input  name="openhour" type="hidden" value="' . $Openhours[$x] . '"></td>
    <input name="closehour" type="hidden" value="'.$Closehours[$x].'"></td>
	<input type="hidden" name="VenueID" value="' . $VenuesID[$x] . '">
	<td><input class="btn btn-primary" type="submit" value="View Products"></td>
	</form>
	
	<form method="post" enctype="multipart/form-data" action="""'.$_SERVER["PHP_SELF"].'">
	<td><input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile"></td>
	<input type="hidden" name="VenueID" value="' . $VenuesID[$x] . '">
	<td>Name: <input name="name" type="text" value="' . $Names[$x] . '"></td>
	<td>City: <input  name="city" type="text" value="' . $Cities[$x] . '"></td>
	<td>Openhours: <input  name="openhour" type="time" value="' . $Openhours[$x] . '"></td>
    <td>Closehours: <input name="closehour" type="time" value="'.$Closehours[$x].'"></td>
	<td><input class="btn btn-primary" type="submit" name="action" value="edit"></td>
	
	</form>
	
	<form method="post" action="'.$_SERVER["PHP_SELF"].'">
	<input type="hidden" name="VenueID" value="' . $VenuesID[$x] . '">
	<td><input class="btn btn-primary" type="submit" name="action" value="delete"></td>
	</form>
	</tr>
	
';
    }

//generates form to add record
echo '<tr>
	<form style="display:inline-block; float:bottom;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
	
	<td><input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile"></td>
    <td>Name: <input name="name" type="text" value=""></td>
	<td>City: <input  name="city" type="text" value=""></td>
	<td>Openhours: <input  name="openhour" type="time" value=""></td>
    <td>Closehours: <input name="closehour" type="time" value=""></td>
	<input type="hidden" name="action" value="add">
	<td><input class="btn btn-primary" type="submit" value="Add"></td>
	</table>
	</form>

	';
    mysqli_close($conn);
    ?>
                
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>