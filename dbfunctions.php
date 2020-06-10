<?php
function checkTables($conn, $dbname, $tbname) {
    //gets tables from db
    $tables = mysqli_query($conn, "SHOW TABLES FROM {$dbname}");
    //stores table names in row array
    while ($row = mysqli_fetch_row($tables)) {
        $arr[] = $row[0];
    }
    //checks if table exists
    if (in_array($tbname, $arr) == FALSE) {
        $output = false;
    } else {
        $output = true;
    }
    return $output;
}
function createDB($conn, $dbname) {
    //creates db if not exists
    $sqlDBcreate = "CREATE DATABASE IF NOT EXISTS '$dbname'";
    mysqli_query($conn, $sqlDBcreate);
}
function refresh()
{
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}

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
?>
