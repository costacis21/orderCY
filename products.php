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
            <div class="container"><a class="navbar-brand" href="#">Portal</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
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

//prompt for errors and reload site
function displayErr($priceErr = '', $imgErr = '', $descriptionErr = '', $nameErr = '')
{
			echo '<script>
		if("' . $imgErr . $priceErr . $nameErr . $descriptionErr . '"!==""){
			pressed=confirm("' . $imgErr . $priceErr . $nameErr . $descriptionErr . '");
			if(pressed==true || pressed==false){
				window.location="' . $_SERVER['REQUEST_URI'] . '";
			}
		}else{
			window.location="' . $_SERVER['REQUEST_URI'] . '";

		}
			
			</script>';
}
//initializes variables and arrays
$venueID=$_GET["VenueID"];
$servername = "localhost";
$username   = "costacis";
$password   = "mynameisjeff69";
$dbname     = "orderCy";
$ItemsID = array(
			''
);
$Names      = array(
			''
);
$Prices     = array(
			''
);

$Photos       = array(
			''
);
$Visible = array('');
$Descriptions= array('');
$Types=array('');

$imgErr     = $priceErr = $nameErr = $descriptionErr = '';
//creates connection with server
//$conn       = mysqli_connect($servername, $username, $password);
//checks if db exists and if not creates it
//createDB($conn, $dbname);
//creates connection with db
$conn = mysqli_connect($servername, $username, $password, $dbname);
//checks if tb exists and if not creates it
//if (!checkTables($conn, $dbname, 'tbproducts')) {
//			$sqlTBcreate = 'CREATE TABLE tbproducts (productID INT(3) PRIMARY KEY NOT NULL AUTO_INCREMENT, img TEXT ,name TINYTEXT NOT NULL, description TEXT NOT NULL, price FLOAT NOT NULL, status TINYTEXT NOT NULL)';
//			mysqli_query($conn, $sqlTBcreate);
//}
//initialize variables
$nameErr = $descriptionErr = $priceErr = "";
$Name    = $Description = $Price = "";
//retrieve everything
$sql     = "SELECT * FROM Items";
$result  = mysqli_query($conn, $sql);
//store everything in array for each entity
$x       = 0;
while ($row = mysqli_fetch_assoc($result)) {
    if($venueID==$row['VenueID']){
			$Names[$x]        = $row['Name'];
			$ItemsID[$x]   = $row['ItemID'];
			$Descriptions[$x] = $row['Description'];
			$Prices[$x]       = $row['Price'];
			$Photos[$x]         = $row['Photo'];
            $Visible[$x]        = $row['Visible'];
            $Types[$x]          =$row['Type'];
    }
			++$x;
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
						$sqlquerry = "DELETE FROM Items WHERE ItemID=$ItemID";
						mysqli_query($conn, $sqlquerry);
						mysqli_query($conn, "ALTER TABLE Items DROP COLUMN ItemID");
						mysqli_query($conn, "ALTER TABLE Items ADD ItemID INT AUTO_INCREMENT PRIMARY KEY");
						header("Location: " . $_SERVER['REQUEST_URI']);
						exit();
			}
			if ($action == 'Change') {
						//validates and gets errors
						list($description, $descriptionErr) = descriptionValid($_POST["description"]);
						list($name, $nameErr) = nameValid($_POST["name"]);
						list($price, $priceErr) = priceValid($_POST["price"]);
						$visible=$_POST["visible"];
						$type = $_POST["type"];
						//checks if errors where generated
						if ($priceErr . $nameErr . $descriptionErr == '') {
									//checks if new image is to be uploaded
									if (!empty($_POST['userUploadFile'])) {

												//check if error for image was generated
												    $uploadfile = addslashes(file_get_contents($_FILES['userUploadFile']['tmp_name']));
                                                    $imageProperties = getimageSize($_FILES['userUploadFile']['tmp_name']);

															//updates all fields and refreshes
                                                    mysqli_query($conn, "UPDATE Items SET Name='$Name', Description='$Description', Price='$Price', Visible='$visible',Photo='$uploadfile',PhotoProperties='$imageProperties', Type='$type' WHERE ItemID=$ItemID");
                                                    refresh();

									} else {
												//updates everything except image directory and refreshes
												mysqli_query($conn, "UPDATE Items SET Name='$name', Description='$description', Visible='$visible', Price='$price',Type='$type' WHERE ItemID=$ItemID");
												refresh();
									}
						} else {
									//displays errors
									displayErr($priceErr, $nameErr, $descriptionErr);
						}
			}
			if ($action == "add") {
						//validates and gets errors from input
						list($Description, $descriptionErr) = descriptionValid($_POST["description"]);
						list($Name, $nameErr) = nameValid($_POST["name"]);
						list($Price, $priceErr) = priceValid($_POST["price"]);
                        $visible=$_POST["visible"];

//                        $Name=$_POST["name"];
//                        $Description=$_POST["description"];
//                        $Price=$_POST["price"];

                //checks if errors were generated
						if ($priceErr . $nameErr . $descriptionErr == '') {

                            $uploadfile = addslashes(file_get_contents($_FILES['userUploadFile']['tmp_name']));
                            $imageProperties = getimageSize($_FILES['userUploadFile']['tmp_name']);


                            mysqli_query($conn, "INSERT INTO Items (Name, Description, Price, Photo, PhotoProperties,Visible,VenueID, Type) VALUES('{$Name}', '{$Description}', '{$Price}', '{$uploadfile}', '{$imageProperties}','{$visible}', '{$venueID}','{$type}') ");
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
for ($x = 0; $x < count($ItemsID); $x++) {
						//generates form for each record
    echo '
	<form style="display:inline-block;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
    <img style="display:inline-block;" width="30%" height="30%" src="data:image/jpeg;base64,'.base64_encode( $Photos[$x]).'" alt="image">

	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
	Name: <input name="name" type="text" value="' . $Names[$x] . '">
	Description: <input  name="description" type="text" value="' . $Descriptions[$x] . '">
	Price: <input  name="price" type="text" value="' . $Prices[$x] . '">
	Type:  <input  name="type" type="text" value="' . $Types[$x] . '">
    Visible: <input name="visible" type="text" value="'.$Visible[$x].'">
	<input type="hidden" name="ItemID" value="' . $ItemsID[$x] . '">
	
	<input class="btn btn-primary" type="submit" name="action" value="Change">

	<input class="btn btn-primary" type="submit" name="action" value="Delete">
	
	</form>
';

}
//generates form to add record	
echo '
	<form style="display:inline-block; float:bottom;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
	Name: <input name="name" type="text" value="' . $nameErr . '">
	Description: <input  name="description" type="text" value="' . $descriptionErr . '">
	Price: <input  name="price" type="text" value="' . $priceErr . '">
	Visible: <input name="visible" type="text" value="">
	Type:  <input  name="type" type="text" value="">


	<input type="hidden" name="action" value="add">
	<input class="btn btn-primary" type="submit" value="Add">
	</form>

	';
//closes connection with server	
mysqli_close($conn);
?>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

