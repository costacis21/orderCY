<?php
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "teststavros";
$conn = mysqli_connect($servername, $username, $password, $dbname);


for($x=0;$x<50000;$x++) {
    $newVenueID = random_str(255);
    $sql="INSERT INTO tbTest (aa,bb,cc,dd) VALUES('{$newVenueID}','{$newVenueID}','{$newVenueID}', '{$newVenueID}')";
    $result  = mysqli_query($conn, $sql);

}



?>