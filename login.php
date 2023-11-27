<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$name = $_POST["uname"];
$password = $_POST["pass"];

if (!empty($name) && !empty($password)) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "registration";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if (mysqli_connect_errno()) {
        die("Connection error: " . mysqli_connect_error());
    } else {
        $sql = "SELECT * FROM user_credentials WHERE stname=? AND stpassword=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['stname'] = $row['stname'];
            
            header('Location: profile.php');
            exit();
        } else {
            
            header("Location: login.html?error=Invalid username or Password");
            exit();
        }
    }
} else {
   
    header("Location: login.html?error=Invalid username or Password");
    exit();
}
?>
