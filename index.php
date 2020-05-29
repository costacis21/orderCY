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
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="order-portal.php">Orders</a></li>
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
$dbname="profusiondb";


$conn = mysqli_connect($servername, $username, $password, $dbname);












$sql = "SELECT * FROM tborders";
$result = mysqli_query($conn, $sql);
//if (mysqli_num_rows($result) > 0) {
		echo ' <div class="table-responsive"><table class="table"> <thead><tr><th>productID</th>  <th>orderID</th>  <th>price</th>  <th>name</th> <th>email</th> <th>address</th> <th>phone no</th><th>description</th> <th>Action</th></tr> </thead> <tbody>';

    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
		if($row["productID"]!=0){
        echo '<tr> <td>'.$row["productID"].'</td> <td>' . $row["orderID"] . '</td>  <td> &euro;'.$row["price"]. '</td> <td>' .$row["name"]. ' </td> <td> ' .$row["email"]. ' </td>  <td> ' .$row["addr"]. ' </td><td> ' .$row["phoNO"]. ' </td><td> ' .$row["description"]. ' </td><td> <div class="dropdown"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Dropdown </button>
                            <div class="dropdown-menu" role="menu">
							<div class="dropdown-item" role="presentation">
							<form method="post">
							<input class="btn btn-primary" type="submit" name="action" value="Mark as complete" /><br/>
							<input class="btn btn-primary" type="hidden" name="action" value="compl'.$row["orderID"].'" /><br/>

							</form>
							</div>
							
							<div class="dropdown-item" role="presentation">
							<form method="post">
							<input class="btn btn-primary" type="submit" name="action" value="Delete" /><br/>
							<input class="btn btn-primary" type="hidden" name="action" value="del-'.$row["orderID"].'" /><br/>

							</form>
							</div>							
							
							</div>
                        </div>
                   </td></tr>';}
		
		
		
	}
	echo "</tbody></table></div>";


	mysqli_close($conn);


?>
	
   
                        <div class="dropdown"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Dropdown </button>
                            <div class="dropdown-menu" role="menu"><a class="dropdown-item" role="presentation" href="#">First Item</a><a class="dropdown-item" role="presentation" href="#">Second Item</a><a class="dropdown-item" role="presentation" href="#">Third Item</a></div>
                        </div>
                  
                
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>