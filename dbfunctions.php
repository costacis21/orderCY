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
?>
