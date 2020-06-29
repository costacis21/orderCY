<!DOCUTYPE HTML>
<html>
<head>

  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
 <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    
<style>
img{
width:10%;
height:10%;	
}
</style>
</head>
<body>
 <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
            <div class="container"><a class="navbar-brand" href="#">Products Portal</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                    id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation"><a class="nav-link" href="#">Products</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="restaurants.php">Restaurants</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
	
<?php
include('dbfunctions.php');
include('validation.php');
//function to refresh the site


function IsChecked($chkname,$value)
{
    if(!empty($_POST[$chkname]))
    {
        foreach($_POST[$chkname] as $chkval)
        {
            if($chkval == $value)
            {
                return true;
            }
        }
    }
    return false;
}


//initializes variables and arrays
$venueID=$_GET["VenueID"];
$servername="ordercy.a2hosted.com";
$username = "ordercya_root";
$password = "pu043=+JHQA!";
$dbname="ordercya_orderCy";


$conn = new mysqli($servername, $username, $password, $dbname);
$ItemsID[0] = array(

);
$Names      = array(
			''
);
$Prices     = array(

);

$Photos       = array(

);
$Visible = array();
$Descriptions= array('');
$Types=array();
$Types2=array();
$VSelected=array();
$NVSelected=array();

$imgErr     = $priceErr = $nameErr = $descriptionErr = '';
//creates connection with server
//$conn       = mysqli_connect($servername, $username, $password);
//checks if db exists and if not creates it
//createDB($conn, $dbname);
//creates connection with db
ini_set('log_errors',1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//$conn = new mysqli("localhost", "ordercya_root", "pu043=+JHQA!", "ordercya_orderCy");
//initialize variables
$nameErr = $descriptionErr = $priceErr = "";
$Name    = $Description = $Price = "";
//retrieve everything
$sql     = "SELECT * FROM Items";
$result = $conn->query($sql);
//store everything in array for each entity
$x = 0;
while ($row = $result->fetch_assoc()) {
    if($venueID==$row['VenueID']){
			$Names[$x]        = $row['Name'];
			$ItemsID[$x]   = $row['ItemID'];
			$Descriptions[$x] = $row['Description'];
			$Prices[$x]       = $row['Price'];
			$Photos[$x]         = $row['Photo'];
			$PhotosProperties[$x]= $row['PhotoProperties'];
            if($row["Visible"]==True){
                $Visible[$x]        ="Visible";
                $VSelected[$x]="Selected";
                $NVSelected[$x]="";
            }else{
                $Visible[$x]        ="Not Visible";
                $NVSelected[$x]="Selected";
                $VSelected[$x]="";

            }


            $Types[$x]          =$row['Type'];
            $Types2[$x]          =$row['Type2'];

        $x++;
    }

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			//gets action to perform
			$action = $_POST["action"];
			//increments index
			if (isset($_POST["ItemID"])) {
						$ItemID = $_POST["ItemID"];
			}
			if ($action == 'Delete') {
						//deletes record and refreshes primarykey ID
						$sqlquerry = "DELETE FROM Items WHERE ItemID='{$ItemID}'";
						mysqli_query($conn, $sqlquerry);
						//mysqli_query($conn, "ALTER TABLE Items DROP COLUMN ItemID");
						//mysqli_query($conn, "ALTER TABLE Items ADD ItemID INT AUTO_INCREMENT PRIMARY KEY");
						refresh();
			}
			if ($action == 'Change') {
						//validates and gets errors
						list($description, $descriptionErr) = descriptionValid($_POST["description"]);
						list($name, $nameErr) = nameValid($_POST["name"]);
						list($price, $priceErr) = priceValid($_POST["price"]);
                        $visible= $_POST["visible"];



                        $type = $_POST["type"];
                        $type2 = $_POST["type2"];

                //checks if errors where generated
						if ($priceErr . $nameErr . $descriptionErr == '') {
									//checks if new image is to be uploaded


                                if ($_FILES["userUploadFile"]["error"]==4) {

												//check if error for image was generated
                                    mysqli_query($conn, "UPDATE Items SET Name='{$name}', Description='{$description}', Visible='{$visible}', Price='{$price}',Type='{$type}',Type2='{$type2}' WHERE ItemID='{$ItemID}'");

                                } else {
												//updates everything except image directory and refreshes
                                    $uploadfile = addslashes(file_get_contents($_FILES['userUploadFile']['tmp_name']));
                                    $imageProperties = getimageSize($_FILES['userUploadFile']['tmp_name']);

                                    //updates all fields and refreshes
                                    mysqli_query($conn, "UPDATE Items SET Name='{$name}', Description='{$description}', Price='{$price}', Visible='{$visible}',Photo='{$uploadfile}',PhotoProperties='{$imageProperties}', Type='{$type}', Type2='{$type2}' WHERE ItemID='{$ItemID}'");

									}
						} else {
									//displays errors
									displayErr($priceErr, $nameErr, $descriptionErr);
						}
                refresh();

            }
			if ($action == "add") {
						//validates and gets errors from input
						list($Description, $descriptionErr) = descriptionValid($_POST["description"]);
						list($Name, $nameErr) = nameValid($_POST["name"]);
						list($Price, $priceErr) = priceValid($_POST["price"]);
						$type=$_POST['type'];
						$type = $_POST["type2"];

                $visible=$_POST["visible"];

                //checks if errors were generated
						if ($priceErr . $nameErr . $descriptionErr == '') {

                            $uploadfile = addslashes(file_get_contents($_FILES['userUploadFile']['tmp_name']));
                            $imageProperties = getimageSize($_FILES['userUploadFile']['tmp_name']);


                            mysqli_query($conn, "INSERT INTO Items (Name, Description, Price, Photo, PhotoProperties,Visible,VenueID, Type, Type2) VALUES('{$Name}', '{$Description}', '{$Price}', '{$uploadfile}', '{$imageProperties}','{$visible}', '{$venueID}','{$type}', '{$type2}') ");
                            refresh();

						} else {
									//displays errors and refreshes
									displayErr($priceErr, $nameErr, $descriptionErr, $imgErr);
						}
			}
}
//gets everything from tb

//checks if tb is empty
			//generates default value for status
if($ItemsID[0]) {
    echo '<div class="table-responsive">
<table class="table w-auto" style="width:100%;  border-collapse: collapse;">';
    for ($x = 0; $x < count($ItemsID); $x++) {
        //generates form for each record
       echo '
<tr class="d-flex">
	<form style="display:inline-block;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
    <td><img class="img-thumbnail" src="data:image/jpeg;base64,' . base64_encode($Photos[$x]) . '" alt="image"></td>

	<td><input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile"></td>
	<td>Name: <input name="name" type="text" value="' . $Names[$x] . '"></td>
	<td>Description: <input  name="description" type="text" value="' . $Descriptions[$x] . '"></td>
	<td>Price: <input  name="price" type="text" value="' . $Prices[$x] . '"></td>
	<td>Type:  <input  name="type" type="text" value="' . $Types[$x] . '"></td>
	<td>Type2:  <input  name="type2" type="text" value="' . $Types2[$x] . '"></td>

    <td><select name="visible"><option value="0"' . $NVSelected[$x] . '>Not Visible</option> <option value="1"' . $VSelected[$x] . '>Visible</option></select></td>
	<input type="hidden" name="ItemID" value="' . $ItemsID[$x] . '">
	
	<td><input class="btn btn-primary" type="submit" name="action" value="Change"></td>

	<td><input class="btn btn-primary" type="submit" name="action" value="Delete"></td>
	</form>
	</tr>
	
';

    }
}
//generates form to add record	
echo '<tr class="d-flex"">
	<form style="display:inline-block; float:bottom;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
	<td><input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile"></td>
	<td>Name: <input name="name" type="text" value="' . $nameErr . '"></td>
	<td>Description: <input  name="description" type="text" value="' . $descriptionErr . '"></td>
	<td>Price: <input  name="price" type="text" value="' . $priceErr . '"></td>
    <td><select name="visible"><option value="0">Not Visible</option> <option value="1">Visible</option></select></td>
	<td>Type:  <input  name="type" type="text" value=""></td>
	<td>Type2:  <input  name="type2" type="text" value=""></td>



	<input type="hidden" name="action" value="add">
	<td><input class="btn btn-primary" type="submit" value="Add"></td>
	</form>
	</tr>
</table>
</div>
	';
//closes connection with server	
mysqli_close($conn);
?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

