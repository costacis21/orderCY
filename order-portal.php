<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
<script>
function toggleCustomerInfo(div) {
    var x = document.getElementById(div);
    if (x.style.display === "none") {
        x.style.display = "block";
    } else {
        x.style.display = "none";
    }
}
</script>
    <div>
        <nav class="navbar navbar-light navbar-expand-md navigation-clean">
            <div class="container"><a class="navbar-brand" href="#">Portal</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse"
                    id="navcol-1">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item" role="presentation"><a class="nav-link active" href="#">Orders</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="products.php">Products</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<?php
include 'dbfunctions.php';
$servername = "localhost";
$username = "costacis";
$password = "mynameisjeff69";
$dbname="orderCy";


//creates connection with server
$conn = mysqli_connect($servername, $username, $password);

//creates db if doesn't exist
createDB($conn,$dbname);


//creates connection with db
$conn = mysqli_connect($servername, $username, $password, $dbname);
//checks if table exists and creates it if it doesn't


if($_SERVER['REQUEST_METHOD']=='POST'){  

//interprets action and id for which to perform the action
$action=$_POST["action"];
$customerID=$_POST["customerID"];


if($action == 'del'){
	
//deletes from tb
$sqlquerry="DELETE FROM CustomerOrders WHERE OrderID={OrderID}";
mysqli_query($conn,$sqlquerry);


//refreshes site
header("Location: " . $_SERVER['REQUEST_URI']);
exit();

}

if($action == 'compl'){
	
$changestatus=$_POST["stchange"];

//change status
$sqlquerry="UPDATE tborders SET status='$changestatus' WHERE customerID={$customerID} AND productID={$productID}";
mysqli_query($conn,$sqlquerry);

//refreshes site
header("Location: " . $_SERVER['REQUEST_URI']);
exit();
	}
	
	
	
	
	
	
}

$venueID=$_GET['venueID'];



$sql = "SELECT Items.Name, CustomerOrders.TableNo FROM Items, CustomerOrders INNER JOIN CustomerOrders ON CustomerOrders.OrderID=Orders.OrderID WHERE VenueID='{$venueID}';";
$result = mysqli_query($conn, $sql);
if (is_numeric(mysqli_num_rows($result))) {




		echo ' <div class="table-responsive"><table class="table"> <thead><tr><th>Table</th>  <th>Item</th>  <th>Customer Info</th> <th>Action</th> </tr> </thead> <tbody>';

    // output data and actions of each record
    while($row = mysqli_fetch_assoc($result)) {
		

	
		
        echo '<tr> 
		<td>'.$row["TableNo"].'</td> 
		<td>&euro;' . $row["Name"] . '</td> 
		
		<td> 
			<button onclick="toggleCustomerInfo('.$row["customerID"].')">Display customer info</button>
			<div style="Display:none" id="'.$row["customerID"].'">';
			$custresult = mysqli_query($conn, "SELECT * FROM CustomerOrders WHERE OrderID={$row["customerID"]}");
				while($custrow = mysqli_fetch_assoc($custresult)) {
						echo'   Name: '.$custrow["name"].'<br>
								Email: '.$custrow["email"].'<br>
								Shipping Address: '.$custrow["addr"].'<br>
								Phone number: '.$custrow["phoNO"].'<br>
								Description: '.$custrow["description"].'<br>
						';
				}
				
	echo'	</div> 
		</td>
		<td> 
		<div class="dropdown"><button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" type="button">Dropdown</button>
            <div class="dropdown-menu" role="menu">
				<div class="dropdown-item" role="presentation">
					<form method="post" action="'. $_SERVER["PHP_SELF"] .'">
					
						<input class="btn btn-primary" type="submit"  value="Mark as '.$changestatus.'" >
						<input type="hidden" name="action" value="compl">
						<input type="hidden" name="productID" value="'.$row["productID"].'">
						<input type="hidden" name="customerID" value="'.$row["customerID"].'">
						<input type="hidden" name="stchange" value="'.$changestatus.'" >

					</form>
				</div>
							
				<div class="dropdown-item" role="presentation">
					<form method="post" action="'. $_SERVER["PHP_SELF"] .'">
							
						<input class="btn btn-primary" type="submit" value="Delete" >
						<input type="hidden" name="action" value="del" >
						<input type="hidden" name="productID" value="'.$row["productID"].'">
						<input type="hidden" name="customerID" value="'.$row["customerID"].'">
					
					</form>
							
				</div>							
							
			</div>
        </div>                
                   </td></tr>';
		
		
	}
	
	echo "</tbody></table>";
}else{
	echo'No orders found';
}

//closes connection with the server
mysqli_close($conn);


?>
	
   
                        
                  
                
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
