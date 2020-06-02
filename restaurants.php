<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>order-portal</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
            <div class="container"><a class="navbar-brand" href="#">Order-portal</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
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
$username = "costacis";
$password = "mynameisjeff69";
$dbname="ordercy";
include('dbfunctions.php');
include('validation.php');

$conn = mysqli_connect($servername, $username, $password, $dbname);



$sql = "SELECT * FROM restaurants";
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
    list($Name, $nameErr) = nameValid($_POST["name"]);
    list($City, $cityErr) = nameValid($_POST["city"]);
    $Openhour=$_POST["openhour"];
    $Closehour=$_POST["closehour"];

    //                        $Name=$_POST["name"];
    //                        $Description=$_POST["description"];
    //                        $Price=$_POST["price"];

    //checks if errors were generated
    if ($nameErr . $cityErr == '') {

        if (!empty($_POST['userUploadFile'])) {
            $Logo = addslashes(file_get_contents($_FILES['userUploadFile']['tmp_name']));
            $LogoProperties = getimageSize($_FILES['userUploadFile']['tmp_name']);
            mysqli_query($conn, "INSERT INTO restaurants (Name, City, Openhour, Closehour, LogoProperties,Logo) VALUES('{$Name}', '{$City}', '{$Openhour}', '{$Closehour}', '{$LogoProperties}','{$Logo}') ");
        }else {


            mysqli_query($conn, "INSERT INTO restaurants (Name, City, Openhour, Closehour) VALUES('{$Name}', '{$City}', '{$Openhour}', '{$Closehour}') ");

        }
        refresh();
        }
    }


    for ($x = 0; $x < count($VenuesID); $x++) {
						//generates form for each record
						echo '
	<img style="display:inline-block;" width="30%" height="30%" src="data:image/jpeg;base64,'.base64_encode( $Logos[$x]).'" alt="image">
	<form style="display:inline-block;" enctype="multipart/form-data" method="get" action="products.php">	
    
	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
	Name: <input name="name" type="text" value="' . $Names[$x] . '">
	City: <input  name="city" type="text" value="' . $Cities[$x] . '">
	Openhours: <input  name="openhour" type="text" value="' . $Openhours[$x] . '">
    Closehours: <input name="closehour" type="text" value="'.$Closehours[$x].'">

	<input type="hidden" name="VenueID" value="' . $VenuesID[$x] . '">
	<input class="btn btn-primary" type="submit" value="View Products">

	
	</form>
';
    }

//generates form to add record
echo '
	<form style="display:inline-block; float:bottom;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
    Name: <input name="name" type="text" value="">
	City: <input  name="city" type="text" value="">
	Openhours: <input  name="openhour" type="text" value="">
    Closehours: <input name="closehour" type="text" value="">

	<input type="hidden" name="VenueID" value="">
	<input type="hidden" name="action" value="add">
	<input class="btn btn-primary" type="submit" value="Add">
	</form>

	';
    mysqli_close($conn);
    ?>
                
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>