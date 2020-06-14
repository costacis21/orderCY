vm<?php
//sanitizes input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    //returns array with sanitized input
}
//validates and generates errors
function nameValid($name) {
    $nameErr = '';
    if (empty($name)) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($name);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "For name only letters and white space allowed";
        }
    }
    //returns array with sanitized input and errors
    return array(
        $name,
        $nameErr
    );
}

function cityValid($city) {
    $nameErr = '';
    if (empty($City)) {
        $cityErr = "city is required";
    } else {
        $name = test_input($city);
        if (!preg_match("/^[a-zA-Z ]*$/", $city)) {
            $city = "For city only letters and white space allowed";
        }
    }
    //returns array with sanitized input and errors
    return array(
        $city,
        $cityErr
    );
}
//validates and generates errors
function descriptionValid($description) {
    $descriptionErr = '';
    if (empty($description)) {
        $descriptionErr = "Description is required";
    } else {
        $description = test_input($description);
        if (!preg_match("/^(\w*\s*)*$/", $description)) {
            $descriptionErr = "For description only letters, digits and white space allowed";
        }
    }
    //returns array with sanitized input and errors
    return array(
        $description,
        $descriptionErr
    );
}
//validates and generates errors
function priceValid($price) {
    $priceErr = '';
    if (empty($price)) {
        $priceErr = "Price is required";
    } else {
        $price = test_input($price);
        if (!preg_match("/^\d*(.{1}\d+)?$/", $price)) {
            $priceErr = "For price only digits and decimal place(.) allowed";
        }
    }
    //returns array with sanitized input and errors
    return array(
        $price,
        $priceErr
    );
}
//validates and generates errors
function imgValid($userfile_tmp_name, $userfile_size, $uploadfile) {
    $imgErr       = '';
    $uploadstatus = 1;
    $extension    = strtolower(pathinfo($uploadfile, PATHINFO_EXTENSION));
    if ($fileToUpload_size > 10000000) {
        $imgErr .= "Your file is too large. Must be <10MB. ";
        $uploadstatus = 0;
    }
    if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {
        $imgErr .= "Only JPG, JPEG, PNG & GIF files are allowed. ";
        $uploadstatus = 0;
    }
    //returns array with sanitized input and errors 
    return array(
        $imgErr,
        $uploadstatus
    );
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

?>
