<?php
$un = $_POST['stname'];
$e = $_POST['stmail'];
$p = $_POST['stpassword'];
$phn = $_POST['stmobile'];

if (!empty($un) && !empty($e) && !empty($p) && !empty($phn)) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "registration";
    $conn = new mysqli($host, $user, $pass, $dbname);

    if (mysqli_connect_errno()) {
        die("Connection error (" . mysqli_connect_errno() . "): " . mysqli_connect_error());
    } else {
        $select = "SELECT stmail FROM user_credentials WHERE stmail=? LIMIT 1";
        $insert = "INSERT INTO user_credentials (stname, stmail, stpassword, stmobile) VALUES (?, ?, ?, ?)";
        
        $stmt_select = $conn->prepare($select);
        $stmt_select->bind_param("s", $e);
        $stmt_select->execute();
        $stmt_select->store_result();
        $rnum = $stmt_select->num_rows;
        $stmt_select->close();

        if ($rnum == 0) {
            $stmt_insert = $conn->prepare($insert);
            $stmt_insert->bind_param("sssi", $un, $e, $p, $phn);
            $stmt_insert->execute();
            $stmt_insert->close();
            
            echo "Registration successful! Redirecting to login page...";
            header("refresh:0.5;url=login.html"); // Redirect after 2 seconds
            exit();
        } else {
            echo '<script>alert("User already exists with the provided email address.");</script>';
            header('refresh:0.5;url=register.html');
        }

        $conn->close();
    }
} else {
    echo "All fields are required.";
    die();
}
?>
