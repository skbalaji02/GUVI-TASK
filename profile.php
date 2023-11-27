<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['stname'])) {
    $stname = $_SESSION['stname'];

    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "registration";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if (mysqli_connect_errno()) {
        die("Connection error: " . mysqli_connect_error());
    } else {
        
        $sql = "SELECT dob, fullname, fav_colour, fav_subject FROM user_credentials WHERE stname=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $stname);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $dob = $row['dob'] ? $row['dob'] : "Not provided";
            $fullname = $row['fullname'] ? $row['fullname'] : "Not provided";
            $fav_colour = $row['fav_colour'] ? $row['fav_colour'] : "Not provided";
            $fav_subject = $row['fav_subject'] ? $row['fav_subject'] : "Not provided";
        } else {
            
            $dob = "Not provided";
            $fullname = "Not provided";
            $fav_colour = "Not provided";
            $fav_subject = "Not provided";
        }

        $stmt->close();
        $conn->close();
    }
} else {
    
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(114.3deg, rgb(19, 126, 57) 0.2%, rgb(8, 65, 91) 68.5%);
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        body h2 {
            margin: 20px 0;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        form label {
            display: block;
            text-align: left;
            margin: 10px 0;
            color: #000000;
            font-weight: bold;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            box-sizing: border-box;
            border: 1px solid #000000;
            border-radius: 5px;
            outline: none;
        }

        form input[type="submit"] {
            background: linear-gradient(114.3deg, rgb(19, 126, 57) 0.2%, rgb(8, 65, 91) 68.5%);
            color: #FFFFFF;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        form input[type="submit"]:hover {
            background: linear-gradient(114.3deg, rgb(8, 65, 91) 0.2%, rgb(19, 126, 57) 68.5%);
        }

        form input[type="submit"],
        form input[type="button"] {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h2>User Dashboard</h2>
    <form action="profile_process.php" method="POST">
        <label for="new_dob">Date of Birth:</label>
        <input type="text" id="new_dob" name="new_dob" value="<?php echo $dob; ?>"><br>

        <label for="new_fullname">Full Name:</label>
        <input type="text" id="new_fullname" name="new_fullname" value="<?php echo $fullname; ?>"><br>

        <label for="new_fav_colour">Favorite Colour:</label>
        <input type="text" id="new_fav_colour" name="new_fav_colour" value="<?php echo $fav_colour; ?>"><br>

        <label for="new_fav_subject">Favorite Subject:</label>
        <input type="text" id="new_fav_subject" name="new_fav_subject" value="<?php echo $fav_subject; ?>"><br>

        <input type="submit" value="Update">
    </form>

    <form action="logout.php" method="POST">
        <input type="submit" value="Logout">
    </form>
</body>

</html>
