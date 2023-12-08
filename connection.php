<?php
// $database="miniproject";
// $user="root";
// $pswd="";
// try{
// $conn=new PDO("mysql:host=localhost;dbname=$database",$user,$pswd);
// }catch(PDOexception $e){
//     echo "error occured".$e->getmessage();
// }
// $database = "miniproject";
// $user = "root";
// $pswd = "";
// try {
//     $conn = new PDO("mysql:host=localhost;dbname=$database", $user, $pswd);
// } catch (PDOException $e) {
//     echo "Error occurred: " . $e->getMessage();
// }
// $host = '10.5.0.4';
// $username = 'root';
// $password = 'mysql8';
// $database = 'miniproject';

// $conn = new mysqli($host, $username, $password, $database);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }


// ALTER USER 'root'@'localhost' IDENTIFIED WITH 'mysql_native_password' BY '0000';
// GRANT ALL PRIVILEGES ON miniproject TO 'root'@'localhost' IDENTIFIED BY '0000';
// FLUSH PRIVILEGES;





// CREATE DATABASE miniproject;
// CREATE USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '0000';
// GRANT ALL ON miniproject.* TO 'root'@'localhost';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);




$host = '10.5.0.4';
$username = 'root';
$password = 'mysql8';
$database = 'miniproject';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
}
catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
// Password to be hashed
// $rawPassword = '0000';

// // Hash the password
// $hashedPassword = password_hash($rawPassword, PASSWORD_DEFAULT);

// // Display the hashed password
// echo $hashedPassword;
?>

