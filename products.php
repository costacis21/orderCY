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
                        <li class="nav-item" role="presentation"><a class="nav-link" href="order-portal.php">Orders</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Products</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
	
<?php
include('dbfunctions.php');
include('validation.php');
//function to refresh the site
function refresh()
{
			header("Location: " . $_SERVER['REQUEST_URI']);
			exit();
}
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
			$Names[$x]        = $row['Name'];
			$ItemsID[$x]   = $row['ItemID'];
			$Descriptions[$x] = $row['Description'];
			$Prices[$x]       = $row['Price'];
			$Photos[$x]         = $row['Photo'];
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
						$status = $_POST["status"];
						//checks if errors where generated
						if ($priceErr . $nameErr . $descriptionErr == '') {
									//checks if new image is to be uploaded
									if (!empty($_POST['userfile'])) {
												//gets upload directory
												$uploaddir  = 'assets/img/';
												$uploadfile = $uploaddir . $_POST['userfile'];
												//validates image to upload
												list($imgErr, $uploadstatus) = imgValid($_FILES['userfile']['tmp_name'], $_FILES['userfile']['size'], $uploadfile);
												//check if error for image was generated
												if ($imgErr == '' && $uploadstatus == 1) {
															//tries to upload image
															(move_uploaded_file("uploadfile.jpg", $uploadfile));


															//updates all fields and refreshes
															mysqli_query($conn, "UPDATE Items SET Name='$Name', Description='$Description', Price='$Price', Photo='$uploadfile' WHERE ItemID=$ItemID");
															refresh();
												} else {
															//displays errors and refreshes
															displayErr($imgErr);
												}
									} else {
												//updates everything except image directory and refreshes
												mysqli_query($conn, "UPDATE Items SET Name='$name', Description='$description', Price='$price' WHERE ItemID=$ItemID");
												refresh();
									}
						} else {
									//displays errors
									displayErr($priceErr, $nameErr, $descriptionErr, $imgErr);
						}
			}
			if ($action == "add") {
						//validates and gets errors from input
//						list($Description, $descriptionErr) = descriptionValid($_POST["description"]);
//						list($Name, $nameErr) = nameValid($_POST["name"]);
//						list($Price, $priceErr) = priceValid($_POST["price"]);
                        $Name=$_POST["name"];
                        $Description=$_POST["description"];
                        $Price=$_POST["price"];

                //checks if errors were generated
						if ($priceErr . $nameErr . $descriptionErr == '') {
                            //if (!empty($_POST['userfile'])) {
                            $uploaddir  = "/assets/img/";
                            $uploadfile = $uploaddir . basename($_FILES['userUploadFile']["name"]);
                            //validates and gets errors
                            //list($imgErr, $uploadstatus) = imgValid($_FILES['userfile']['tmp_name'], $_FILES['userfile']['size'], $uploadfile);
                            //checks if errors were generated and tries to upload
//												if ($imgErr == '' && $uploadstatus == 1) {
                            move_uploaded_file($_FILES['userUploadFile']['tmp_name'], uploadfile);
                           // copy(uploadfile,$uploadfile);
                            //updates all entries and refreshes
                            mysqli_query($conn, "INSERT INTO Items (Name, Description, Price, Photo) VALUES('$Name', '$Description', '$Price', '$uploadfile') ");
                            refresh();
                           // }
//                if (count($_FILES) > 0) {
//
//                    if (is_uploaded_file($_FILES['userfile']['tmp_name'])) {
//                                $imgData = addslashes(file_get_contents($_FILES['userfile']['tmp_name']));
//                                $imageProperties = getimageSize($_FILES['userfile']['tmp_name']);
//                                mysqli_query($conn, "INSERT INTO Items (Name, Description, Price, Photo) VALUES('$Name', '$Description', '$Price', '$imgData') ");
//                                $current_id = mysqli_query($conn, $sql) or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($conn));
//                                if (isset($current_id)) {
//                                    refresh();
//                                }
//                            }
						} else {
									//displays errors and refreshes
									displayErr($priceErr, $nameErr, $descriptionErr, $imgErr);
						}
			}
}
//gets everything from tb
$sql    = "SELECT * FROM Items";
$result = mysqli_query($conn, $sql);
//checks if tb is empty
if (mysqli_num_rows($result) > 0) {
			//generates default value for status
			for ($x = 0; $x < count($ItemsID); $x++) {
						//generates form for each record
						echo '
	<img style="display:inline-block;" width="100px" height="100px" src="' . $Photos[$x] . '" alt="image">
	<form style="display:inline-block;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
    
	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
	Name: <input name="name" type="text" value="' . $Names[$x] . '">
	Description: <input  name="description" type="text" value="' . $Descriptions[$x] . '">
	Price: <input  name="price" type="text" value="' . $Prices[$x] . '">

	<input type="hidden" name="ItemID" value="' . $ItemsID[$x] . '">
	
	<input class="btn btn-primary" type="submit" name="action" value="Change">

	<input class="btn btn-primary" type="submit" name="action" value="Delete">
	
	</form>
';
			}
}
//generates form to add record	
echo '
	<form style="display:inline-block; float:bottom;" enctype="multipart/form-data" method="post" action"' . $_SERVER["PHP_SELF"] . '">	
	<input type="file" name="userUploadFile" enctype="multipart/form-data" id="userUploadFile">
	Name: <input name="name" type="text" value="' . $nameErr . '">
	Description: <input  name="description" type="text" value="' . $descriptionErr . '">
	Price: <input  name="price" type="text" value="' . $priceErr . '">
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

